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

class BarangImportDebug implements ToArray, WithHeadingRow
{
    use Importable;

    private $rowCount = 0;
    private $successCount = 0;
    private $createdUsers = 0;
    private $errors = [];

    public function array(array $rows)
    {
        $this->rowCount = count($rows);
        
        Log::info("=== DEBUG IMPORT START ===");
        Log::info("Processing {$this->rowCount} rows");
        
        // DEBUGGING: Log first few rows to see raw values
        foreach (array_slice($rows, 0, 3) as $index => $row) {
            Log::info("=== RAW ROW " . ($index + 1) . " ===");
            Log::info("Raw does_pcs: " . json_encode($row['does_pcs'] ?? 'null') . " (type: " . gettype($row['does_pcs'] ?? null) . ")");
            Log::info("Raw hbeli: " . json_encode($row['hbeli'] ?? 'null') . " (type: " . gettype($row['hbeli'] ?? null) . ")");
            Log::info("Full row: " . json_encode($row));
        }
        
        try {
            // Step 1: Create users
            $this->createUsersWithUniqueEmails($rows);
            
            // Step 2: Import barang dengan debugging
            $this->importBarangWithDebug($rows);
            
        } catch (\Exception $e) {
            Log::error("Import error: " . $e->getMessage());
            $this->errors[] = "Critical error: " . $e->getMessage();
        }
        
        Log::info("=== IMPORT COMPLETED ===");
        
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
                
            } catch (\Exception $e) {
                $this->errors[] = "Failed to create user: {$userId}";
            }
        }
    }
    
    private function importBarangWithDebug(array $rows)
    {
        $users = User::pluck('id', 'name')->toArray();
        $existingCodes = Barang::pluck('id', 'kode')->toArray();
        
        $insertData = [];
        
        foreach ($rows as $index => $row) {
            try {
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
                
                // DEBUGGING: Multiple parsing approaches
                $rawDoesPcs = $row['does_pcs'] ?? 1;
                $rawHbeli = $row['hbeli'] ?? 0;
                
                Log::info("=== ROW " . ($index + 2) . " PARSING ===");
                Log::info("Raw does_pcs: " . json_encode($rawDoesPcs) . " (type: " . gettype($rawDoesPcs) . ")");
                Log::info("Raw hbeli: " . json_encode($rawHbeli) . " (type: " . gettype($rawHbeli) . ")");
                
                // Method 1: Direct cast
                $doesPcs1 = (float) $rawDoesPcs;
                $hbeli1 = (float) $rawHbeli;
                
                // Method 2: String processing
                $doesPcs2 = $this->parseExcelNumeric($rawDoesPcs);
                $hbeli2 = $this->parseExcelNumeric($rawHbeli);
                
                // Method 3: Check if it's percentage (divide by 100 if > 10)
                $doesPcs3 = is_numeric($rawDoesPcs) && $rawDoesPcs > 10 ? $rawDoesPcs / 100 : (float) $rawDoesPcs;
                $hbeli3 = $rawHbeli;
                
                Log::info("Method 1 (direct cast) - does_pcs: {$doesPcs1}, hbeli: {$hbeli1}");
                Log::info("Method 2 (string parse) - does_pcs: {$doesPcs2}, hbeli: {$hbeli2}");
                Log::info("Method 3 (percentage fix) - does_pcs: {$doesPcs3}, hbeli: {$hbeli3}");
                
                // Use Method 3 for now (percentage fix)
                $finalDoesPcs = $doesPcs3;
                $finalHbeli = $this->parseExcelCurrency($rawHbeli);
                
                Log::info("FINAL VALUES - does_pcs: {$finalDoesPcs}, hbeli: {$finalHbeli}");
                
                $data = [
                    'kode' => $kode,
                    'nama' => $nama,
                    'does_pcs' => $finalDoesPcs,
                    'golongan' => trim($row['golongan'] ?? 'GENERAL'),
                    'hbeli' => $finalHbeli,
                    'user_id' => $userId,
                    'keterangan' => trim($row['keterangan'] ?? ''),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
                
                if (!isset($existingCodes[$kode])) {
                    $insertData[] = $data;
                }
                
                // Only process first 5 rows for debugging
                if ($index >= 4) break;
                
            } catch (\Exception $e) {
                Log::error("Error processing row " . ($index + 2) . ": " . $e->getMessage());
                $this->errors[] = "Row " . ($index + 2) . ": " . $e->getMessage();
            }
        }
        
        // Insert data
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

    private function parseExcelNumeric($value)
    {
        if (is_numeric($value) && !str_contains((string)$value, ',')) {
            return floatval($value);
        }
        
        $value = (string) $value;
        $value = trim($value);
        
        if (empty($value)) {
            return 0;
        }
        
        // Handle Indonesian format: 2,900,000.00
        if (str_contains($value, ',') && str_contains($value, '.')) {
            $parts = explode('.', $value);
            if (count($parts) == 2) {
                $decimal = array_pop($parts);
                $integer = str_replace(',', '', implode('', $parts));
                $value = $integer . '.' . $decimal;
            }
        } elseif (str_contains($value, ',') && !str_contains($value, '.')) {
            $value = str_replace(',', '', $value);
        }
        
        $value = preg_replace('/[^\d.-]/', '', $value);
        return is_numeric($value) ? floatval($value) : 0;
    }
    
    private function parseExcelCurrency($value)
    {
        // Special handling for currency values
        if (is_numeric($value)) {
            return floatval($value);
        }
        
        $value = (string) $value;
        
        // Remove currency symbols and spaces
        $value = str_replace(['Rp', 'Rp.', '$', '€', '¥', ' '], '', $value);
        
        // Handle format like "2,900,000.00"
        if (str_contains($value, ',') && str_contains($value, '.')) {
            // Find last dot (decimal separator)
            $lastDot = strrpos($value, '.');
            if ($lastDot !== false) {
                $decimal = substr($value, $lastDot + 1);
                $integer = substr($value, 0, $lastDot);
                $integer = str_replace(',', '', $integer);
                $value = $integer . '.' . $decimal;
            }
        } elseif (str_contains($value, ',')) {
            $value = str_replace(',', '', $value);
        }
        
        return is_numeric($value) ? floatval($value) : 0;
    }
}