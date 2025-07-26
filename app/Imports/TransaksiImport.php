<?php

namespace App\Imports;

use App\Models\Transaksi;
use App\Models\TransaksiDetail;
use App\Models\Barang;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class TransaksiImport implements ToCollection
{
    private $currentTransaksi = null;
    private $transaksiCount = 0;
    private $detailCount = 0;

    public function collection(Collection $rows)
{
    $transaksiCount = 0;
    $detailCount = 0;

    foreach ($rows as $index => $row) {
        $rowNumber = $index + 1;

        // Skip header baris 1 & 2
        if ($rowNumber <= 2) {
            continue;
        }

        // Ambil kolom sesuai posisi di Excel
        $colA = trim($row[0] ?? ''); // Kode
        $colB = $row[1] ?? '';       // Tanggal
        $colC = trim($row[2] ?? ''); // Nomor
        $colD = trim($row[3] ?? ''); // Customer
        $colE = $row[4] ?? '';       // Qty/Subtotal
        $colF = $row[5] ?? '';       // Diskon
        $colG = $row[6] ?? '';       // Ongkos
        $colH = $row[7] ?? '';       // Total
        $colI = trim($row[8] ?? ''); // Keterangan
        $colJ = trim($row[9] ?? ''); // User

        // Debug info
        \Log::info("ROW {$rowNumber}", [
            'colA' => $colA,
            'colB' => $colB,
            'colC' => $colC,
            'isDate' => $this->isDate($colB) ? 'YES' : 'NO'
        ]);

        if ($this->isDate($colB)) {
            // Ini baris TRANSAKSI
            try {
                $tanggal = $this->parseDate($colB);
                $nomor = $colC;
                $customer = $colD;
                $subtotal = $this->parseAmount($colE);
                $diskon = $this->parseAmount($colF);
                $ongkir = $this->parseAmount($colG);
                $total = $this->parseAmount($colH);
                $userId = $this->findUserId($colJ);

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
                ]);

                $transaksiCount++;
                \Log::info("TRANSAKSI CREATED", ['id' => $this->currentTransaksi->id]);

            } catch (\Exception $e) {
                \Log::error("Error creating transaksi: " . $e->getMessage());
            }

        } elseif (!$this->isDate($colB) && !empty($colC) && $this->currentTransaksi) {
    // Ini baris DETAIL

            // Ini baris DETAIL
            try {
                $namaBarang = $colC;
                $qty = $this->parseQty($colE);
                $hargaSatuan = $this->parseAmount($colH);
                $subtotalDetail = $qty * $hargaSatuan;

                $barangId = $this->findBarangId($namaBarang);
                $kodeBarang = $this->generateKodeBarang($namaBarang);

                TransaksiDetail::create([
                    'transaksi_id' => $this->currentTransaksi->id,
                    'barang_id' => $barangId,
                    'kode_barang' => $kodeBarang,
                    'nama_barang' => $namaBarang,
                    'qty' => $qty,
                    'harga_satuan' => $hargaSatuan,
                    'subtotal' => $subtotalDetail,
                ]);

                $detailCount++;

            } catch (\Exception $e) {
                \Log::error("Error creating detail: " . $e->getMessage());
            }
        }
    }

    \Log::info("FINAL RESULT", [
        'transaksi' => $transaksiCount,
        'detail' => $detailCount
    ]);
}

private function isDate($value)
{
    if ($value instanceof \DateTime) return true;
    if (is_numeric($value) && $value > 40000) return true; // Excel serial date
    if (preg_match('/\d{2}\/\d{2}\/\d{4}/', $value)) return true;
    return false;
}

    

    public function getTransaksiCount()
    {
        return $this->transaksiCount;
    }

    public function getDetailCount()
    {
        return $this->detailCount;
    }

    private function findUserId($userIdOrName)
    {
        if (empty($userIdOrName)) return 1;
        $user = User::where('name', 'LIKE', '%' . $userIdOrName . '%')->first();
        return $user ? $user->id : 1;
    }

    private function findBarangId($namaBarang)
    {
        if (empty($namaBarang)) return null;
        $barang = Barang::where('nama', 'LIKE', '%' . $namaBarang . '%')->first();
        return $barang ? $barang->id : null;
    }

    private function generateKodeBarang($namaBarang)
    {
        $words = explode(' ', $namaBarang);
        $kode = '';
        foreach ($words as $word) {
            if (strlen($word) > 2) $kode .= strtoupper(substr($word, 0, 3));
        }
        return substr($kode, 0, 20);
    }

    private function parseDate($dateValue)
    {
        try {
            if (preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $dateValue)) {
                return Carbon::createFromFormat('d/m/Y', $dateValue)->format('Y-m-d');
            }
            return Carbon::parse($dateValue)->format('Y-m-d');
        } catch (\Exception $e) {
            return Carbon::now()->format('Y-m-d');
        }
    }

    private function parseAmount($value)
    {
        if (empty($value)) return 0;
        $cleaned = preg_replace('/[^\d]/', '', $value);
        return (float) $cleaned;
    }

    private function parseQty($value)
    {
        if (empty($value)) return 1;
        if (preg_match('/(\d+)/', $value, $m)) return (int) $m[1];
        return 1;
    }
}
