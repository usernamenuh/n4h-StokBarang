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

class BarangImportFixed implements ToArray, WithHeadingRow
{
    use Importable;

    private $rowCount = 0;
    private $successCount = 0;
    private $createdUsers = 0;
    private $errors = [];

    public function array(array $rows)
    {
        $this->rowCount = count($rows);
        
        Log::info("=== FIXED IMPORT START ===");
        Log::info("Processing {$this->rowCount} rows");
        
        try {
            // Step 1: Create users dengan email unique handling
            $this->createUsersWithUniqueEmails($rows);
            
            // Step 2: Import barang
            $this->importBarang($rows);
            
        } catch (\Exception $e) {
            Log::error("Import error: " . $e->getMessage());
            $this->errors[] = "Critical error: " . $e->getMessage();
        }
        
        Log::info("=== IMPORT COMPLETED ===");
        Log::info("Users created: {$this->createdUsers}, Barang imported: {$this->successCount}, Errors: " . count($this->errors));
        
        return [
            'processed' => $this->rowCount,
            'success_count' => $this->successCount,
            'users_created' => $this->createdUsers,
            'errors' => $this->errors
        ];
    }
    
    private function createUsersWithUniqueEmails(array $rows)
    {
        // Get unique user_ids from Excel
        $userIds = array_unique(array_filter(array_column($rows, 'user_id')));
        
        Log::info("Found unique user_ids: " . implode(', ', $userIds));
        
        // Get existing users (by name and email)
        $existingUserNames = User::whereIn('name', $userIds)->pluck('name')->toArray();
        
        // Create new users
        $newUsers = array_diff($userIds, $existingUserNames);
        
        foreach ($newUsers as $userId) {
            try {
                // Generate unique email
                $baseEmail = strtolower($userId);
                $email = $baseEmail . '@example.com';
                $counter = 1;
                
                // Check if email exists, add counter if needed
                while (User::where('email', $email)->exists()) {
                    $email = $baseEmail . $counter . '@example.com';
                    $counter++;
                }
                
                // Create user
                User::create([
                    'name' => $userId,
                    'email' => $email,
                    'password' => Hash::make('password123'),
                    'email_verified_at' => now(),
                ]);
                
                $this->createdUsers++;
                Log::info("Created user: {$userId} with email: {$email}");
                
            } catch (\Exception $e) {
                Log::error("Failed to create user {$userId}: " . $e->getMessage());
                $this->errors[] = "Failed to create user: {$userId}";
            }
        }
    }
    
    private function importBarang(array $rows)
    {
        // Get user mapping
        $users = User::pluck('id', 'name')->toArray();
        
        // Get existing barang codes
        $existingCodes = Barang::pluck('id', 'kode')->toArray();
        
        $insertData = [];
        $updateData = [];
        
        foreach ($rows as $index => $row) {
            try {
                $kode = trim($row['kode'] ?? '');
                $nama = trim($row['nama'] ?? '');
                $userIdFromExcel = trim($row['user_id'] ?? '');
                
                // Validate required fields
                if (empty($kode)) {
                    $this->errors[] = "Row " . ($index + 2) . ": Missing kode";
                    continue;
                }
                
                if (empty($nama)) {
                    $this->errors[] = "Row " . ($index + 2) . ": Missing nama";
                    continue;
                }
                
                if (empty($userIdFromExcel)) {
                    $this->errors[] = "Row " . ($index + 2) . ": Missing user_id";
                    continue;
                }
                
                // Get user ID
                $userId = $users[$userIdFromExcel] ?? null;
                if (!$userId) {
                    $this->errors[] = "Row " . ($index + 2) . ": User '{$userIdFromExcel}' not found";
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
                    // Update existing
                    Barang::where('kode', $kode)->update([
                        'nama' => $data['nama'],
                        'does_pcs' => $data['does_pcs'],
                        'golongan' => $data['golongan'],
                        'hbeli' => $data['hbeli'],
                        'user_id' => $data['user_id'],
                        'keterangan' => $data['keterangan'],
                        'updated_at' => now(),
                    ]);
                } else {
                    // Insert new
                    $data['created_at'] = now();
                    $data['updated_at'] = now();
                    $insertData[] = $data;
                }
                
            } catch (\Exception $e) {
                Log::error("Error processing row " . ($index + 2) . ": " . $e->getMessage());
                $this->errors[] = "Row " . ($index + 2) . ": " . $e->getMessage();
            }
        }
        
        // Bulk insert new records
        if (!empty($insertData)) {
            try {
                Barang::insert($insertData);
                $this->successCount = count($insertData);
                Log::info("Inserted {$this->successCount} barang records");
            } catch (\Exception $e) {
                Log::error("Bulk insert failed: " . $e->getMessage());
                $this->errors[] = "Bulk insert failed: " . $e->getMessage();
            }
        }
    }
    
    public function getRowCount(): int { return $this->rowCount; }
    public function getSuccessCount(): int { return $this->successCount; }
    public function getCreatedUsersCount(): int { return $this->createdUsers; }
    public function getErrors(): array { return $this->errors; }

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