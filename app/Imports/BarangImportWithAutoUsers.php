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

class BarangImportWithAutoUsers implements ToCollection, WithHeadingRow
{
    use Importable;

    private $rowCount = 0;
    private $successCount = 0;
    private $updateCount = 0;
    private $skipCount = 0;
    private $userCache = [];
    private $createdUsers = 0;
    private $errors = [];

    public function collection(Collection $rows)
    {
        $this->rowCount = $rows->count();
        
        Log::info("=== STARTING IMPORT ===");
        Log::info("Total rows to process: {$this->rowCount}");
        
        try {
            // Step 1: Scan dan create users
            $this->scanAndCreateUsers($rows);
            
            // Step 2: Cache users
            $this->cacheUsers();
            
            // Step 3: Import barang
            $this->importBarang($rows);
            
        } catch (\Exception $e) {
            Log::error("Critical import error: " . $e->getMessage());
            Log::error("Stack trace: " . $e->getTraceAsString());
            throw $e;
        }
        
        Log::info("=== IMPORT COMPLETED ===");
        Log::info("Users created: {$this->createdUsers}");
        Log::info("Barang created: {$this->successCount}");
        Log::info("Barang updated: {$this->updateCount}");
        Log::info("Rows skipped: {$this->skipCount}");
        Log::info("Errors: " . count($this->errors));
    }
    
    private function scanAndCreateUsers(Collection $rows)
    {
        Log::info("STEP 1: Scanning unique user_id from Excel...");
        
        $uniqueUserIds = $rows->pluck('user_id')
            ->map(function($userId) {
                return trim($userId ?? '');
            })
            ->filter(function($userId) {
                return !empty($userId);
            })
            ->unique()
            ->values();
            
        Log::info("Found " . $uniqueUserIds->count() . " unique user_ids: " . $uniqueUserIds->implode(', '));
        
        foreach ($uniqueUserIds as $userId) {
            try {
                $user = User::updateOrCreate(
                    ['name' => $userId],
                    [
                        'email' => strtolower($userId) . '@example.com',
                        'password' => Hash::make('password123'),
                        'email_verified_at' => now(),
                    ]
                );
                
                if ($user->wasRecentlyCreated) {
                    $this->createdUsers++;
                    Log::info("✓ Created user: {$userId} (ID: {$user->id})");
                } else {
                    Log::info("✓ User exists: {$userId} (ID: {$user->id})");
                }
                
            } catch (\Exception $e) {
                Log::error("✗ Error creating user {$userId}: " . $e->getMessage());
                $this->errors[] = "Failed to create user: {$userId}";
            }
        }
        
        Log::info("STEP 1 COMPLETED: {$this->createdUsers} new users created");
    }
    
    private function cacheUsers()
    {
        Log::info("STEP 2: Caching users...");
        
        $users = User::all();
        Log::info("Found {$users->count()} total users in database");
        
        foreach ($users as $user) {
            $this->userCache[$user->name] = $user->id;
            $this->userCache[strtolower($user->name)] = $user->id;
            $this->userCache[strtoupper($user->name)] = $user->id;
            
            Log::debug("Cached user: {$user->name} => {$user->id}");
        }
        
        Log::info("STEP 2 COMPLETED: Cached " . count($users) . " users");
    }
    
    private function importBarang(Collection $rows)
    {
        Log::info("STEP 3: Importing barang...");
        
        foreach ($rows as $index => $row) {
            $rowNumber = $index + 2; // Excel row number (header = 1)
            
            try {
                // Validate required fields
                $kode = trim($row['kode'] ?? '');
                $nama = trim($row['nama'] ?? '');
                $userIdFromExcel = trim($row['user_id'] ?? '');
                
                Log::debug("Processing row {$rowNumber}: kode='{$kode}', nama='{$nama}', user_id='{$userIdFromExcel}'");
                
                if (empty($kode)) {
                    Log::warning("✗ Row {$rowNumber}: Missing kode");
                    $this->skipCount++;
                    $this->errors[] = "Row {$rowNumber}: Missing kode";
                    continue;
                }
                
                if (empty($nama)) {
                    Log::warning("✗ Row {$rowNumber}: Missing nama");
                    $this->skipCount++;
                    $this->errors[] = "Row {$rowNumber}: Missing nama";
                    continue;
                }
                
                // Parse other fields
                $doesPcs = $this->parseNumeric($row['does_pcs'] ?? 1);
                $golongan = trim($row['golongan'] ?? 'GENERAL');
                $hbeli = $this->parseNumeric($row['hbeli'] ?? 0);
                $keterangan = trim($row['keterangan'] ?? '');
                
                // Get user ID
                $userId = $this->getUserId($userIdFromExcel);
                if (!$userId) {
                    Log::warning("✗ Row {$rowNumber}: User '{$userIdFromExcel}' not found");
                    $this->skipCount++;
                    $this->errors[] = "Row {$rowNumber}: User '{$userIdFromExcel}' not found";
                    continue;
                }
                
                // Insert/Update barang
                $barang = Barang::updateOrCreate(
                    ['kode' => $kode],
                    [
                        'nama' => $nama,
                        'does_pcs' => $doesPcs,
                        'golongan' => $golongan,
                        'hbeli' => $hbeli,
                        'user_id' => $userId,
                        'keterangan' => $keterangan,
                    ]
                );
                
                if ($barang->wasRecentlyCreated) {
                    $this->successCount++;
                    Log::info("✓ Row {$rowNumber}: Created barang '{$kode}' for user '{$userIdFromExcel}'");
                } else {
                    $this->updateCount++;
                    Log::info("✓ Row {$rowNumber}: Updated barang '{$kode}' for user '{$userIdFromExcel}'");
                }
                
            } catch (\Exception $e) {
                Log::error("✗ Row {$rowNumber}: " . $e->getMessage());
                Log::error("Row data: " . json_encode($row->toArray()));
                $this->skipCount++;
                $this->errors[] = "Row {$rowNumber}: " . $e->getMessage();
            }
        }
        
        Log::info("STEP 3 COMPLETED: {$this->successCount} created, {$this->updateCount} updated, {$this->skipCount} skipped");
        
        if (!empty($this->errors)) {
            Log::warning("ERRORS ENCOUNTERED:");
            foreach ($this->errors as $error) {
                Log::warning("- " . $error);
            }
        }
    }
    
    private function getUserId($username)
    {
        if (empty($username)) {
            return auth()->id();
        }
        
        // Try exact match
        if (isset($this->userCache[$username])) {
            return $this->userCache[$username];
        }
        
        // Try case variations
        if (isset($this->userCache[strtolower($username)])) {
            return $this->userCache[strtolower($username)];
        }
        
        if (isset($this->userCache[strtoupper($username)])) {
            return $this->userCache[strtoupper($username)];
        }
        
        Log::error("User not found: '{$username}'. Available users: " . implode(', ', array_keys($this->userCache)));
        return null;
    }
    
    // Getters
    public function getRowCount(): int { return $this->rowCount; }
    public function getSuccessCount(): int { return $this->successCount + $this->updateCount; }
    public function getCreateCount(): int { return $this->successCount; }
    public function getUpdateCount(): int { return $this->updateCount; }
    public function getSkipCount(): int { return $this->skipCount; }
    public function getCreatedUsersCount(): int { return $this->createdUsers; }
    public function getErrors(): array { return $this->errors; }

    private function parseNumeric($value)
    {
        if (is_numeric($value)) {
            return floatval($value);
        }
        
        $cleaned = str_replace(',', '', $value);
        $cleaned = preg_replace('/[^\d.-]/', '', $cleaned);
        
        if (is_numeric($cleaned)) {
            return floatval($cleaned);
        }
        
        return 0;
    }
}