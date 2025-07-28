<?php

namespace App\Imports;

use App\Models\Barang;
use App\Models\User;
use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\Importable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class BarangImportFinal implements ToArray, WithHeadingRow
{
    use Importable;

    private $rowCount = 0;
    private $successCount = 0;
    private $createdUsers = 0;
    private $errors = [];
    private $importFailedRows = [];
    private $importSuccessRows = [];

    public function array(array $rows)
    {
        $this->rowCount = count($rows);

        Log::info("=== IMPORT BARANG DIMULAI ===");
        Log::info("Memproses {$this->rowCount} baris data");

        DB::beginTransaction();

        try {
            // Langkah 1: Buat user jika belum ada
            $this->buatUserDenganEmailUnik($rows);

            // Langkah 2: Import data barang
            $this->importBarang($rows);

            DB::commit();
            Log::info("=== IMPORT BERHASIL ===");
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Import gagal: " . $e->getMessage());
            $this->errors[] = "Error kritis: " . $e->getMessage();
            Log::info("=== IMPORT GAGAL ===");
        }

        Log::info("Hasil - User dibuat: {$this->createdUsers}, Barang diimpor: {$this->successCount}, Error: " . count($this->errors));

        return [
            'total_data' => $this->rowCount,
            'berhasil' => $this->successCount,
            'user_dibuat' => $this->createdUsers,
            'errors' => $this->errors,
            'baris_gagal' => $this->importFailedRows,
            'baris_berhasil' => $this->importSuccessRows
        ];
    }

    private function buatUserDenganEmailUnik(array $rows)
    {
        $userIds = array_unique(array_filter(array_column($rows, 'user_id')));
        
        if (empty($userIds)) {
            throw new \Exception("Tidak ditemukan nilai user_id yang valid dalam file import");
        }

        $existingUsers = User::whereIn('name', $userIds)->pluck('name')->toArray();
        $newUsers = array_diff($userIds, $existingUsers);

        foreach ($newUsers as $userId) {
            try {
                if (empty(trim($userId))) {
                    throw new \Exception("Nilai user_id kosong ditemukan");
                }

                $baseEmail = strtolower(preg_replace('/[^a-z0-9]/i', '', $userId));
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
                Log::info("Berhasil membuat user: {$userId} dengan email: {$email}");
            } catch (\Exception $e) {
                Log::error("Gagal membuat user {$userId}: " . $e->getMessage());
                $this->errors[] = "Gagal membuat user: {$userId} - " . $e->getMessage();
            }
        }
    }

    private function importBarang(array $rows)
    {
        $users = User::pluck('id', 'name')->toArray();
        $existingCodes = Barang::pluck('id', 'kode')->toArray();

        $insertData = [];
        $updateCount = 0;

        foreach ($rows as $index => $row) {
            $rowNumber = $index + 2; // +2 karena baris Excel dimulai dari 1 dan header adalah baris 1
            $errorPrefix = "Baris {$rowNumber}:";

            try {
                // Validasi dan bersihkan input
                $kode = trim($row['kode'] ?? '');
                $nama = trim($row['nama'] ?? '');
                $userIdFromExcel = trim($row['user_id'] ?? '');

                // Validasi field wajib
                if (empty($kode)) {
                    throw new \Exception("Kolom kode harus diisi");
                }

                if (empty($nama)) {
                    throw new \Exception("Kolom nama harus diisi");
                }

                if (empty($userIdFromExcel)) {
                    throw new \Exception("Kolom user_id harus diisi");
                }

                // Validasi user ada
                if (!isset($users[$userIdFromExcel])) {
                    throw new \Exception("User '{$userIdFromExcel}' tidak ditemukan di database");
                }

                $userId = $users[$userIdFromExcel];

                // Parse nilai numerik
                $doesPcs = $this->parseNumericValue($row['does_pcs'] ?? 1, 'does_pcs');
                $hbeli = $this->parseRupiahToInteger($row['hbeli'] ?? 0);

                if ($hbeli < 0) {
                    throw new \Exception("Nilai hbeli tidak boleh negatif");
                }

                $barangData = [
                    'kode' => $kode,
                    'nama' => $nama,
                    'does_pcs' => $doesPcs,
                    'golongan' => trim($row['golongan'] ?? 'GENERAL'),
                    'hbeli' => $hbeli, // Disimpan sebagai integer (contoh: 50000 untuk Rp50.000)
                    'user_id' => $userId,
                    'keterangan' => trim($row['keterangan'] ?? ''),
                    'updated_at' => now(),
                ];

                if (isset($existingCodes[$kode])) {
                    // Update data yang sudah ada
                    Barang::where('kode', $kode)->update($barangData);
                    $updateCount++;
                    $this->importSuccessRows[] = "Baris {$rowNumber}: Berhasil update barang dengan kode {$kode}";
                } else {
                    // Siapkan data baru
                    $barangData['created_at'] = now();
                    $insertData[] = $barangData;
                    $this->importSuccessRows[] = "Baris {$rowNumber}: Berhasil siapkan barang baru dengan kode {$kode}";
                }

            } catch (\Exception $e) {
                $errorMsg = "{$errorPrefix} " . $e->getMessage();
                Log::error($errorMsg);
                $this->errors[] = $errorMsg;
                $this->importFailedRows[] = [
                    'baris' => $rowNumber,
                    'error' => $e->getMessage(),
                    'data' => $row
                ];
            }
        }

        // Insert data baru sekaligus
        if (!empty($insertData)) {
            try {
                $chunks = array_chunk($insertData, 100); // Proses per 100 data untuk import besar
                foreach ($chunks as $chunk) {
                    Barang::insert($chunk);
                    $this->successCount += count($chunk);
                }
                Log::info("Berhasil menyimpan {$this->successCount} data barang baru");
            } catch (\Exception $e) {
                throw new \Exception("Gagal insert data: " . $e->getMessage());
            }
        }

        Log::info("Berhasil update {$updateCount} data barang yang sudah ada");
        $this->successCount += $updateCount;
    }

    /**
     * Konversi nilai Rupiah dari Excel ke integer
     * Contoh: "Rp50.000,00" => 50000
     *          "75.000" => 75000
     *          "100,500.25" => 100500
     */
    private function parseRupiahToInteger($value): int
    {
        // Jika sudah integer, langsung return
        if (is_int($value)) {
            return $value;
        }

        // Jika sudah float, konversi ke integer
        if (is_float($value)) {
            return (int) round($value);
        }

        $value = trim((string) $value);

        // Handle nilai kosong
        if ($value === '') {
            return 0;
        }

        // Hapus semua karakter non-digit kecuali koma dan titik
        $value = preg_replace('/[^0-9,.]/', '', $value);

        // Handle format Indonesia (1.500,00) dan internasional (1,500.00)
        if (strpos($value, ',') !== false && strpos($value, '.') !== false) {
            // Jika koma ada sebelum titik, artinya titik sebagai ribuan (1.234,56)
            if (strpos($value, ',') > strpos($value, '.')) {
                $value = str_replace('.', '', $value);
                $value = str_replace(',', '.', $value);
            } else {
                // Jika koma ada setelah titik, artinya koma sebagai ribuan (1,234.56)
                $value = str_replace(',', '', $value);
            }
        } else {
            // Ganti koma dengan titik untuk desimal
            $value = str_replace(',', '.', $value);
        }

        // Konversi ke float lalu ke integer (bulatkan)
        $floatValue = (float) $value;
        return (int) round($floatValue);
    }

    private function parseNumericValue($value, $fieldName)
    {
        if (is_numeric($value)) {
            return floatval($value);
        }

        $value = trim((string) $value);

        if ($value === '') {
            return $fieldName === 'hbeli' ? 0 : 1; // Nilai default
        }

        // Hanya ambil bagian numerik
        $value = preg_replace('/[^0-9,.]/', '', $value);
        $value = str_replace(',', '.', $value);

        if (!is_numeric($value)) {
            throw new \Exception("Nilai numerik tidak valid untuk {$fieldName}: '{$value}'");
        }

        return floatval($value);
    }

    // Method untuk mendapatkan hasil import
    public function getTotalData(): int { return $this->rowCount; }
    public function getJumlahBerhasil(): int { return $this->successCount; }
    public function getJumlahUserDibuat(): int { return $this->createdUsers; }
    public function getDaftarError(): array { return $this->errors; }
    public function getBarisGagal(): array { return $this->importFailedRows; }
    public function getBarisBerhasil(): array { return $this->importSuccessRows; }
}