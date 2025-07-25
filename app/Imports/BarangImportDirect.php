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

class BarangImportDirect implements ToCollection, WithHeadingRow
{
    use Importable;

    private $rowCount = 0;
    private $successCount = 0;
    private $updateCount = 0;
    private $skipCount = 0;
    private $userCache = [];

    public function collection(Collection $rows)
    {
        $this->rowCount = $rows->count();
        
        // Cache all users to avoid repeated queries
        $this->cacheUsers();
        
        Log::info("Starting import of {$this->rowCount} rows");
        Log::info("Available users: " . implode(', ', array_keys($this->userCache)));
        
        foreach ($rows as $index => $row) {
            try {
                $kode = trim($row['kode'] ?? '');
                $nama = trim($row['nama'] ?? '');
                $doesPcs = $this->parseNumeric($row['does_pcs'] ?? 1);
                $golongan = trim($row['golongan'] ?? 'GENERAL');
                $hbeli = $this->parseNumeric($row['hbeli'] ?? 0);
                $keterangan = trim($row['keterangan'] ?? '');
                $userIdFromExcel = trim($row['user_id'] ?? '');
                
                Log::info("Processing row " . ($index + 2) . ": kode={$kode}, user_id={$userIdFromExcel}");
                
                if (empty($kode) || empty($nama)) {
                    Log::warning("Skipping row " . ($index + 2) . ": Missing required fields (kode or nama)");
                    $this->skipCount++;
                    continue;
                }
                
                // Get user ID from username
                $userId = $this->getUserId($userIdFromExcel);
                if (!$userId) {
                    Log::warning("Skipping row " . ($index + 2) . ": User '{$userIdFromExcel}' not found in database");
                    $this->skipCount++;
                    continue;
                }
                
                Log::info("Found user ID {$userId} for username '{$userIdFromExcel}'");
                
                // Use updateOrCreate to handle duplicates
                $barang = Barang::updateOrCreate(
                    ['kode' => $kode], // Search condition
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
                    Log::info("Created new barang: {$kode} for user: {$userIdFromExcel}");
                } else {
                    $this->updateCount++;
                    Log::info("Updated existing barang: {$kode} for user: {$userIdFromExcel}");
                }
                
            } catch (\Exception $e) {
                Log::error("Error processing row " . ($index + 2) . ": " . $e->getMessage());
                Log::error("Row data: " . json_encode($row->toArray()));
                $this->skipCount++;
            }
        }
        
        Log::info("Import completed: {$this->successCount} created, {$this->updateCount} updated, {$this->skipCount} skipped");
    }
    
    private function cacheUsers()
    {
        $users = User::all();
        Log::info("Found " . $users->count() . " users in database");
        
        foreach ($users as $user) {
            // Cache by exact name
            $this->userCache[$user->name] = $user->id;
            // Cache by lowercase name
            $this->userCache[strtolower($user->name)] = $user->id;
            // Cache by uppercase name
            $this->userCache[strtoupper($user->name)] = $user->id;
            // Also cache by email username part
            $emailUsername = explode('@', $user->email)[0];
            $this->userCache[strtolower($emailUsername)] = $user->id;
            $this->userCache[strtoupper($emailUsername)] = $user->id;
            
            Log::info("Cached user: {$user->name} (ID: {$user->id})");
        }
    }
    
    private function getUserId($username)
    {
        if (empty($username)) {
            Log::warning("Empty username, using authenticated user");
            return auth()->id(); // Fallback to current user
        }
        
        $originalUsername = $username;
        
        // Try exact match first
        if (isset($this->userCache[$username])) {
            Log::info("Found exact match for user: {$username}");
            return $this->userCache[$username];
        }
        
        // Try lowercase
        if (isset($this->userCache[strtolower($username)])) {
            Log::info("Found lowercase match for user: {$username}");
            return $this->userCache[strtolower($username)];
        }
        
        // Try uppercase
        if (isset($this->userCache[strtoupper($username)])) {
            Log::info("Found uppercase match for user: {$username}");
            return $this->userCache[strtoupper($username)];
        }
        
        Log::error("User not found: '{$originalUsername}'. Available users: " . implode(', ', array_keys($this->userCache)));
        return null; // User not found
    }
    
    public function getRowCount(): int
    {
        return $this->rowCount;
    }
    
    public function getSuccessCount(): int
    {
        return $this->successCount + $this->updateCount;
    }
    
    public function getCreateCount(): int
    {
        return $this->successCount;
    }
    
    public function getUpdateCount(): int
    {
        return $this->updateCount;
    }
    
    public function getSkipCount(): int
    {
        return $this->skipCount;
    }

    private function parseNumeric($value)
    {
        if (is_numeric($value)) {
            return floatval($value);
        }
        
        // Handle format like "2,900,000.00"
        $cleaned = str_replace(',', '', $value);
        $cleaned = preg_replace('/[^\d.-]/', '', $cleaned);
        
        if (is_numeric($cleaned)) {
            return floatval($cleaned);
        }
        
        return 0;
    }
}