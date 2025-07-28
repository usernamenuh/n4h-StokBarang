<?php

namespace App\Imports;

use App\Models\Transaksi;
use App\Models\TransaksiDetail;
use App\Models\Barang;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class TransaksiImport implements ToCollection
{
    private $currentTransaksi = null;
    private $transaksiCount = 0;
    private $detailCount = 0;
    private $successCount = 0;
    private $errors = [];
    private $importFailedRows = [];
    private $importSuccessRows = [];

    public function collection(Collection $rows)
    {
        Log::info("=== IMPORT TRANSAKSI DIMULAI ===");
        Log::info("Memproses " . $rows->count() . " baris data");

        DB::beginTransaction();

        try {
            // Cache untuk performa
            $barangCache = Barang::all()->keyBy('nama');
            $userCache = User::all()->keyBy('name');

            foreach ($rows as $index => $row) {
                $rowNumber = $index + 1;

                // Skip header baris 1 & 2
                if ($rowNumber <= 2) {
                    continue;
                }

                try {
                    $this->processRow($row, $rowNumber, $barangCache, $userCache);
                } catch (\Exception $e) {
                    $this->handleRowError($rowNumber, $e->getMessage(), $row->toArray());
                }
            }

            DB::commit();
            Log::info("=== IMPORT TRANSAKSI BERHASIL ===");
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Import transaksi gagal: " . $e->getMessage());
            $this->errors[] = "Error kritis: " . $e->getMessage();
            Log::info("=== IMPORT TRANSAKSI GAGAL ===");
        }

        Log::info("Hasil - Transaksi: {$this->transaksiCount}, Detail: {$this->detailCount}, Berhasil: {$this->successCount}, Error: " . count($this->errors));
    }

    private function processRow($row, $rowNumber, $barangCache, $userCache)
    {
        // Konversi row ke array jika perlu
        if (!is_array($row)) {
            $row = $row->toArray();
        }

        // Ambil kolom sesuai posisi di Excel
        $colA = trim($row[0] ?? '');
        $colB = $row[1] ?? '';
        $colC = trim($row[2] ?? '');
        $colD = trim($row[3] ?? '');
        $colE = $row[4] ?? '';
        $colF = $row[5] ?? '';
        $colG = $row[6] ?? '';
        $colH = $row[7] ?? '';
        $colI = trim($row[8] ?? '');
        $colJ = trim($row[9] ?? '');
        $colK = $row[10] ?? '';
        $colL = $row[11] ?? '';

        Log::info("Processing Row {$rowNumber}", [
            'colB' => $colB,
            'colC' => $colC,
            'isDate' => $this->isDate($colB) ? 'YES' : 'NO'
        ]);

        if ($this->isDate($colB)) {
            // Ini baris TRANSAKSI
            $this->processTransaksiRow($rowNumber, $colB, $colC, $colD, $colE, $colF, $colG, $colH, $colI, $colJ, $colK, $colL, $userCache);
        } elseif (!$this->isDate($colB) && !empty($colC) && $this->currentTransaksi) {
            // Ini baris DETAIL
            $this->processDetailRow($rowNumber, $colC, $colE, $colH, $barangCache);
        }
    }

    private function processTransaksiRow($rowNumber, $colB, $colC, $colD, $colE, $colF, $colG, $colH, $colI, $colJ, $colK, $colL, $userCache)
    {
        // Validasi field wajib
        if (empty($colC)) {
            throw new \Exception("Nomor transaksi harus diisi");
        }

        if (empty($colD)) {
            throw new \Exception("Nama customer harus diisi");
        }

        // Cek duplikasi nomor transaksi
        if (Transaksi::where('nomor', $colC)->exists()) {
            throw new \Exception("Transaksi dengan nomor '{$colC}' sudah ada di database");
        }

        try {
            $tanggal = $this->parseDate($colB);
            $nomor = $colC;
            $customer = $colD;
            $subtotal = $this->parseRupiahAmount($colE);
            $diskon = $this->parseRupiahAmount($colF);
            $ongkir = $this->parseRupiahAmount($colG);
            $total = $this->parseRupiahAmount($colH);
            $userId = $this->findUserId($colJ, $userCache);
            $jumPrint = is_numeric($colL) ? (int)$colL : 0;

            $this->currentTransaksi = Transaksi::create([
                'tanggal' => $tanggal,
                'nomor' => $nomor,
                'customer' => $customer,
                'subtotal' => $subtotal,
                'diskon' => $diskon,
                'ongkir' => $ongkir,
                'total' => $total,
                'keterangan' => $colI,
                'user_id' => $userId,
                'jum_print' => $jumPrint,
                'tgl_input' => $this->parseDate($colK),
            ]);

            $this->transaksiCount++;
            $this->successCount++;
            $this->importSuccessRows[] = "Baris {$rowNumber}: Berhasil import transaksi {$nomor}";
            
            Log::info("TRANSAKSI CREATED", ['id' => $this->currentTransaksi->id, 'nomor' => $nomor]);
        } catch (\Exception $e) {
            if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
                throw new \Exception("Transaksi sudah ada atau sudah di import sebelumnya");
            }
            throw $e;
        }
    }

    private function processDetailRow($rowNumber, $colC, $colE, $colH, $barangCache)
    {
        $namaBarangExcel = trim($colC);
        
        if (empty($namaBarangExcel)) {
            throw new \Exception("Nama barang harus diisi");
        }

        $qty = $this->parseQty($colE);
        $hargaSatuan = $this->parseRupiahAmount($colH);

        if ($qty <= 0) {
            throw new \Exception("Quantity harus lebih dari 0");
        }

        if ($hargaSatuan < 0) {
            throw new \Exception("Harga satuan tidak boleh negatif");
        }

        // Cari barang
        $barang = $this->findBarang($namaBarangExcel, $barangCache);

        if (!$barang) {
            // Skip jika barang tidak ditemukan, jangan error
            Log::warning("Barang tidak ditemukan, dilewati", ['nama' => $namaBarangExcel]);
            return;
        }

        $subtotalDetail = $qty * $hargaSatuan;

        // Update stok barang
        $stokLama = $barang->does_pcs;
        $stokBaru = max($stokLama - $qty, 0);
        Barang::where('id', $barang->id)->update(['does_pcs' => $stokBaru]);

        // Simpan detail transaksi
        TransaksiDetail::create([
            'transaksi_id' => $this->currentTransaksi->id,
            'barang_id' => $barang->id,
            'kode_barang' => $barang->kode,
            'nama_barang' => $barang->nama,
            'qty' => $qty,
            'harga_satuan' => $hargaSatuan,
            'subtotal' => $subtotalDetail,
            'discount' => 0,
            'keterangan' => null,
        ]);

        $this->detailCount++;
        $this->importSuccessRows[] = "Baris {$rowNumber}: Berhasil import detail barang {$barang->nama}";
    }

    private function findBarang($namaBarang, $barangCache)
    {
        // Cari exact match dulu
        if ($barangCache->has($namaBarang)) {
            return $barangCache->get($namaBarang);
        }

        // Cari dengan LIKE
        foreach ($barangCache as $barang) {
            if (stripos($barang->nama, $namaBarang) !== false || 
                stripos($namaBarang, $barang->nama) !== false) {
                return $barang;
            }
        }

        return null;
    }

    private function handleRowError($rowNumber, $errorMessage, $rowData)
    {
        // Convert technical errors to user-friendly messages
        if (strpos($errorMessage, 'Duplicate entry') !== false) {
            $errorMessage = "Transaksi sudah ada atau sudah di import sebelumnya";
        } elseif (strpos($errorMessage, 'Integrity constraint violation') !== false) {
            $errorMessage = "Data transaksi sudah ada di database";
        } elseif (strpos($errorMessage, 'SQLSTATE') !== false) {
            $errorMessage = "Transaksi dengan data ini sudah ada di sistem";
        }

        $finalErrorMsg = "Baris {$rowNumber}: {$errorMessage}";
        Log::error($finalErrorMsg);
        $this->errors[] = $finalErrorMsg;
        $this->importFailedRows[] = [
            'baris' => $rowNumber,
            'error' => $errorMessage,
            'data' => $rowData
        ];
    }

    private function isDate($value)
    {
        if ($value instanceof \DateTime) return true;
        if (is_numeric($value) && $value > 40000) return true; // Excel serial date
        if (preg_match('/\d{2}\/\d{2}\/\d{4}/', $value)) return true;
        return false;
    }

    private function findUserId($userIdOrName, $userCache)
    {
        if (empty($userIdOrName)) return 1;
        
        // Cari exact match dulu
        if ($userCache->has($userIdOrName)) {
            return $userCache->get($userIdOrName)->id;
        }

        // Cari dengan LIKE
        foreach ($userCache as $user) {
            if (stripos($user->name, $userIdOrName) !== false) {
                return $user->id;
            }
        }

        return 1; // Default user
    }

    private function parseDate($dateValue)
    {
        try {
            if (empty($dateValue)) {
                return now()->format('Y-m-d H:i:s');
            }

            // Handle Excel serial date
            if (is_numeric($dateValue) && $dateValue > 40000) {
                return Carbon::createFromFormat('Y-m-d', '1900-01-01')->addDays($dateValue - 2)->format('Y-m-d H:i:s');
            }

            if (preg_match('/^\d{2}\/\d{2}\/\d{4} \d{2}:\d{2}:\d{2}$/', $dateValue)) {
                return Carbon::createFromFormat('d/m/Y H:i:s', $dateValue)->format('Y-m-d H:i:s');
            }
            
            if (preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $dateValue)) {
                return Carbon::createFromFormat('d/m/Y', $dateValue)->format('Y-m-d H:i:s');
            }
            
            return Carbon::parse($dateValue)->format('Y-m-d H:i:s');
        } catch (\Exception $e) {
            Log::warning("Failed to parse date: {$dateValue}, using current date");
            return now()->format('Y-m-d H:i:s');
        }
    }

    /**
     * Parse Indonesian Rupiah format to integer
     * Examples: "3.680.000,00" => 3680000
     *          "820.000" => 820000
     *          "1.280.000,00" => 1280000
     */
    private function parseRupiahAmount($value)
    {
        if (empty($value)) return 0;
        if (is_numeric($value)) return (float) $value;

        $value = trim((string) $value);
        
        // Remove currency symbols and spaces
        $value = preg_replace('/[Rp\s]/', '', $value);
        
        // Handle Indonesian format: 3.680.000,00
        if (preg_match('/^[\d.]+,\d{2}$/', $value)) {
            $parts = explode(',', $value);
            $integerPart = str_replace('.', '', $parts[0]);
            $decimalPart = $parts[1] ?? '00';
            return (float) ($integerPart . '.' . $decimalPart);
        }
        
        // Handle format without decimals: 3.680.000
        if (preg_match('/^[\d.]+$/', $value) && substr_count($value, '.') > 1) {
            return (float) str_replace('.', '', $value);
        }
        
        // Handle single decimal point: 1280.50
        if (preg_match('/^\d+\.\d{1,2}$/', $value)) {
            return (float) $value;
        }
        
        // Remove all non-numeric characters as fallback
        $cleaned = preg_replace('/[^\d]/', '', $value);
        return (float) $cleaned;
    }

    private function parseQty($value)
    {
        if (empty($value)) return 1;
        if (is_numeric($value)) return (float) $value;
        
        // Extract number from text like "3 Pcs"
        if (preg_match('/(\d+(?:[.,]\d+)?)/', $value, $matches)) {
            $number = str_replace(',', '.', $matches[1]);
            return (float) $number;
        }
        
        return 1;
    }

    // Getter methods for results
    public function getTransaksiCount(): int { return $this->transaksiCount; }
    public function getDetailCount(): int { return $this->detailCount; }
    public function getSuccessCount(): int { return $this->successCount; }
    public function getErrors(): array { return $this->errors; }
    public function getFailedRows(): array { return $this->importFailedRows; }
    public function getSuccessRows(): array { return $this->importSuccessRows; }
}
