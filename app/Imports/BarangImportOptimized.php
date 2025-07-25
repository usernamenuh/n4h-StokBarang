<?php

namespace App\Imports;

use App\Models\Barang;
use App\Models\User;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\Importable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;

class BarangImportOptimized implements ToCollection, WithHeadingRow
{
    use Importable;

    private $rowCount = 0;
    private $successCount = 0;
    private $updateCount = 0;
    private $skipCount = 0;
    private $userCache = [];
    private $createdUsers = 0;

    public function collection(Collection $rows)
    {
        $this->rowCount = $rows->count();
        
        Log::info("=== OPTIMIZED IMPORT START ===");
        Log::info("Total rows: {$this->rowCount}");
        
        // Step 1: Bulk create users (FAST)
        $this->bulkCreateUsers($rows);
        
        // Step 2: Cache users
        $this->cacheUsers();
        
        // Step 3: Bulk import barang (FAST)
        $this->bulkImportBarang($rows);
        
        Log::info("=== IMPORT COMPLETED ===");
        Log::info("Users created: {$this->createdUsers}, Barang created: {$this->successCount}, Updated: {$this->updateCount}");
    }
    
    private function bulkCreateUsers(Collection $rows)
    {
        Log::info("STEP 1: Bulk creating users...");
        
        // Get unique user_ids
        $uniqueUserIds = $rows->pluck('user_id')
            ->map(fn($id) => trim($id ?? ''))
            ->filter(fn($id) => !empty($id))
            ->unique()
            ->values();
            
        Log::info("Found {$uniqueUserIds->count()} unique users");
        
        // Get existing users
        $existingUsers = User::whereIn('name', $uniqueUserIds)->pluck('name')->toArray();
        
        // Prepare new users for bulk insert
        $newUsers = $uniqueUserIds->diff($existingUsers)->map(function($userId) {
            return [
                'name' => $userId,
                'email' => strtolower($userId) . '@example.com',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        })->toArray();
        
        // Bulk insert new users
        if (!empty($newUsers)) {
            User::insert($newUsers);
            $this->createdUsers = count($newUsers);
            Log::info("✓ Bulk created {$this->createdUsers} users");
        }
        
        Log::info("STEP 1 COMPLETED");
    }
    
    private function cacheUsers()
    {
        Log::info("STEP 2: Caching users...");
        
        $users = User::all(['id', 'name']);
        foreach ($users as $user) {
            $this->userCache[$user->name] = $user->id;
            $this->userCache[strtolower($user->name)] = $user->id;
            $this->userCache[strtoupper($user->name)] = $user->id;
        }
        
        Log::info("STEP 2 COMPLETED: Cached {$users->count()} users");
    }
    
    private function bulkImportBarang(Collection $rows)
    {
        Log::info("STEP 3: Bulk importing barang...");
        
        // Get existing barang codes
        $existingCodes = Barang::pluck('kode')->toArray();
        
        $insertData = [];
        $updateData = [];
        
        foreach ($rows as $index => $row) {
            try {
                $kode = trim($row['kode'] ?? '');
                $nama = trim($row['nama'] ?? '');
                $userIdFromExcel = trim($row['user_id'] ?? '');
                
                // Skip invalid rows
                if (empty($kode) || empty($nama)) {
                    $this->skipCount++;
                    continue;
                }
                
                // Get user ID
                $userId = $this->getUserId($userIdFromExcel);
                if (!$userId) {
                    $this->skipCount++;
                    continue;
                }
                
                $data = [
                    'kode' => $kode,
                    'nama' => $nama,
                    'does_pcs' => $this->parseNumeric($row['does_pcs'] ?? 1),
                    'golongan' => trim($row['golongan'] ?? 'GENERAL'),
                    'hbeli' => $this->parseNumeric($row['hbeli'] ?? 0),
                    'user_id' => $userId,
                    'keterangan' => trim($row['keterangan'] ?? ''),
                ];
                
                if (in_array($kode, $existingCodes)) {
                    // For update
                    $updateData[] = $data;
                } else {
                    // For insert
                    $data['created_at'] = now();
                    $data['updated_at'] = now();
                    $insertData[] = $data;
                }
                
            } catch (\Exception $e) {
                Log::error("Error processing row " . ($index + 2) . ": " . $e->getMessage());
                $this->skipCount++;
            }
        }
        
        // Bulk insert new records
        if (!empty($insertData)) {
            // Insert in chunks of 100
            $chunks = array_chunk($insertData, 100);
            foreach ($chunks as $chunk) {
                Barang::insert($chunk);
            }
            $this->successCount = count($insertData);
            Log::info("✓ Bulk inserted {$this->successCount} barang");
        }
        
        // Bulk update existing records
        if (!empty($updateData)) {
            foreach ($updateData as $data) {
                Barang::where('kode', $data['kode'])->update([
                    'nama' => $data['nama'],
                    'does_pcs' => $data['does_pcs'],
                    'golongan' => $data['golongan'],
                    'hbeli' => $data['hbeli'],
                    'user_id' => $data['user_id'],
                    'keterangan' => $data['keterangan'],
                    'updated_at' => now(),
                ]);
            }
            $this->updateCount = count($updateData);
            Log::info("✓ Updated {$this->updateCount} barang");
        }
        
        Log::info("STEP 3 COMPLETED");
    }
    
    private function getUserId($username)
    {
        if (empty($username)) {
            return auth()->id();
        }
        
        return $this->userCache[$username] 
            ?? $this->userCache[strtolower($username)] 
            ?? $this->userCache[strtoupper($username)] 
            ?? null;
    }
    
    // Getters
    public function getRowCount(): int { return $this->rowCount; }
    public function getSuccessCount(): int { return $this->successCount + $this->updateCount; }
    public function getCreateCount(): int { return $this->successCount; }
    public function getUpdateCount(): int { return $this->updateCount; }
    public function getSkipCount(): int { return $this->skipCount; }
    public function getCreatedUsersCount(): int { return $this->createdUsers; }

    private function parseNumeric($value)
    {
        if (is_numeric($value)) {
            return floatval($value);
        }
        
        $cleaned = str_replace(',', '', $value);
        $cleaned = preg_replace('/[^\d.-]/', '', $cleaned);
        
        return is_numeric($cleaned) ? floatval($cleaned) : 0;
    }
}