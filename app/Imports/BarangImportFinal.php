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

class BarangImportFinal implements ToArray, WithHeadingRow
{
    use Importable;

    private $rowCount = 0;
    private $successCount = 0;
    private $createdUsers = 0;
    private $errors = [];

    public function array(array $rows)
    {
        $this->rowCount = count($rows);
        
        Log::info("=== FINAL IMPORT START ===");
        Log::info("Processing {$this->rowCount} rows");
        
        try {
            // Step 1: Create users
            $this->createUsersWithUniqueEmails($rows);
            
            // Step 2: Import barang dengan parsing yang sudah diperbaiki
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
        $userIds = array_unique(array_filter(array_column($rows, 'user_id')));
        $existingUserNames = User::whereIn('name', $userIds)->pluck('name')->toArray();
        $newUsers = array_diff($userIds, $existingUserNames);
        
        foreach ($newUsers as $userId) {
            try {
                $baseEmail = strtolower($userId);
                $email = $baseEmail . '@example.com';
                $counter = 1;
                
                while (User::where('email', $email)->exists()) {
                    $email = $baseEmail . $counter . '@example.com';
                    $counter++;
                }
                
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
        $users = User::pluck('id', 'name')->toArray();
        $existingCodes = Barang::pluck('id', 'kode')->toArray();
        
        $insertData = [];
        
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
                
                // FIXED: Parse values dengan method yang tepat berdasarkan debug
                $rawDoesPcs = $row['does_pcs'] ?? 1;
                $rawHbeli = $row['hbeli'] ?? 0;
                
                // For does_pcs: direct cast works fine (1.00 -> 1.0)
                $doesPcs = (float) $rawDoesPcs;
                
                // For hbeli: use fixed currency parser
                $hbeli = $this->parseIndonesianCurrency($rawHbeli);
                
                Log::info("Row " . ($index + 2) . " - does_pcs: {$rawDoesPcs} -> {$doesPcs}, hbeli: {$rawHbeli} -> {$hbeli}");
                
                $data = [
                    'kode' => $kode,
                    'nama' => $nama,
                    'does_pcs' => $doesPcs,
                    'golongan' => trim($row['golongan'] ?? 'GENERAL'),
                    'hbeli' => $hbeli,
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

    /**
     * FIXED: Parse Indonesian currency format correctly
     * Handles: "60,500.00", "2,900,000.00", "139,500.00" etc.
     */
    private function parseIndonesianCurrency($value)
    {
        // If already numeric and no comma, return as is
        if (is_numeric($value) && !str_contains((string)$value, ',')) {
            return floatval($value);
        }
        
        // Convert to string for processing
        $value = (string) $value;
        $value = trim($value);
        
        // If empty, return 0
        if (empty($value)) {
            return 0;
        }
        
        // Remove any currency symbols
        $value = str_replace(['Rp', 'Rp.', '$', '€', '¥', ' '], '', $value);
        $value = trim($value);
        
        // Handle Indonesian format: "2,900,000.00" or "60,500.00"
        if (str_contains($value, ',')) {
            // Check if there's a decimal point
            if (str_contains($value, '.')) {
                // Format: "2,900,000.00" 
                // Split by decimal point
                $parts = explode('.', $value);
                $decimalPart = array_pop($parts); // Get last part as decimal
                $integerPart = implode('', $parts); // Join remaining parts
                
                // Remove all commas from integer part
                $integerPart = str_replace(',', '', $integerPart);
                
                // Reconstruct the number
                $value = $integerPart . '.' . $decimalPart;
            } else {
                // Format: "2,900,000" (no decimal)
                $value = str_replace(',', '', $value);
            }
        }
        
        // Final cleanup: remove any remaining non-numeric characters except decimal point and minus
        $value = preg_replace('/[^\d.-]/', '', $value);
        
        // Convert to float
        $result = is_numeric($value) ? floatval($value) : 0;
        
        return $result;
    }
}