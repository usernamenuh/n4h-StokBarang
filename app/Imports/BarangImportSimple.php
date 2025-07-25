<?php

namespace App\Imports;

use App\Models\Barang;
use App\Models\User;
use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\Importable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;

class BarangImportSimple implements ToArray, WithHeadingRow
{
    use Importable;

    private $rowCount = 0;
    private $successCount = 0;
    private $createdUsers = 0;

    public function array(array $rows)
    {
        $this->rowCount = count($rows);
        
        Log::info("=== SIMPLE IMPORT START ===");
        Log::info("Processing {$this->rowCount} rows");
        
        // Step 1: Get unique users and create them
        $this->createUsers($rows);
        
        // Step 2: Bulk insert barang
        $this->insertBarang($rows);
        
        Log::info("=== IMPORT COMPLETED ===");
        Log::info("Users created: {$this->createdUsers}, Barang inserted: {$this->successCount}");
        
        return $this->successCount;
    }
    
    private function createUsers(array $rows)
    {
        // Get unique user_ids
        $userIds = array_unique(array_column($rows, 'user_id'));
        $userIds = array_filter($userIds); // Remove empty
        
        Log::info("Found " . count($userIds) . " unique users: " . implode(', ', $userIds));
        
        // Get existing users
        $existingUsers = User::whereIn('name', $userIds)->pluck('name')->toArray();
        
        // Create new users
        $newUsers = array_diff($userIds, $existingUsers);
        
        if (!empty($newUsers)) {
            $insertData = [];
            foreach ($newUsers as $userId) {
                $insertData[] = [
                    'name' => $userId,
                    'email' => strtolower($userId) . '@example.com',
                    'password' => Hash::make('password123'),
                    'email_verified_at' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            
            User::insert($insertData);
            $this->createdUsers = count($insertData);
            Log::info("Created {$this->createdUsers} new users");
        }
    }
    
    private function insertBarang(array $rows)
    {
        // Get user mapping
        $users = User::pluck('id', 'name')->toArray();
        
        // Get existing barang codes
        $existingCodes = Barang::pluck('id', 'kode')->toArray();
        
        $insertData = [];
        $updateData = [];
        
        foreach ($rows as $row) {
            $kode = trim($row['kode'] ?? '');
            $nama = trim($row['nama'] ?? '');
            $userIdFromExcel = trim($row['user_id'] ?? '');
            
            if (empty($kode) || empty($nama) || empty($userIdFromExcel)) {
                continue;
            }
            
            $userId = $users[$userIdFromExcel] ?? null;
            if (!$userId) {
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
            
            if (isset($existingCodes[$kode])) {
                $data['id'] = $existingCodes[$kode];
                $updateData[] = $data;
            } else {
                $data['created_at'] = now();
                $data['updated_at'] = now();
                $insertData[] = $data;
            }
        }
        
        // Bulk insert new records
        if (!empty($insertData)) {
            Barang::insert($insertData);
            $this->successCount = count($insertData);
            Log::info("Inserted {$this->successCount} barang");
        }
        
        // Update existing records
        if (!empty($updateData)) {
            foreach ($updateData as $data) {
                Barang::where('id', $data['id'])->update([
                    'nama' => $data['nama'],
                    'does_pcs' => $data['does_pcs'],
                    'golongan' => $data['golongan'],
                    'hbeli' => $data['hbeli'],
                    'user_id' => $data['user_id'],
                    'keterangan' => $data['keterangan'],
                    'updated_at' => now(),
                ]);
            }
            Log::info("Updated " . count($updateData) . " barang");
        }
    }
    
    public function getRowCount(): int { return $this->rowCount; }
    public function getSuccessCount(): int { return $this->successCount; }
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