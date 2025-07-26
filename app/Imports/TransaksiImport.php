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

        // Ambil semua barang di awal untuk cache
        $barangList = Barang::all();

        foreach ($rows as $index => $row) {
            $rowNumber = $index + 1;

            // Skip header baris 1 & 2
            if ($rowNumber <= 2) {
                continue;
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
            $colK = $row[10] ?? ''; // K = Tgl Input
            $colL = $row[11] ?? ''; // L = Jum Print

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
                    $tglInput = $this->parseDate($colK);
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

                    $transaksiCount++;
                    \Log::info("TRANSAKSI CREATED", ['id' => $this->currentTransaksi->id]);
                } catch (\Exception $e) {
                    \Log::error("Error creating transaksi: " . $e->getMessage());
                }
            } elseif (!$this->isDate($colB) && !empty($colC) && $this->currentTransaksi) {
                try {
                    $namaBarangExcel = trim($colC);
                    $qty = $this->parseQty($colE);
                    $hargaSatuan = $this->parseAmount($colH);
                    $subtotalDetail = $qty * $hargaSatuan;

                    // Cari barang
                    $barang = Barang::where('nama', $namaBarangExcel)
                        ->orWhere('nama', 'LIKE', '%' . $namaBarangExcel . '%')
                        ->first();

                    if (!$barang) {
                        // Skip jika barang tidak ada
                        \Log::warning("Barang tidak ditemukan, dilewati", [
                            'nama_excel' => $namaBarangExcel
                        ]);
                        continue;
                    }

                    // Jika ditemukan
                    $barangId = $barang->id;
                    $kodeBarang = $barang->kode;
                    $namaBarang = $barang->nama;

                    // Update stok
                    $stokLama = $barang->does_pcs;
                    $stokBaru = max($stokLama - $qty, 0);
                    Barang::where('id', $barangId)->update(['does_pcs' => $stokBaru]);

                    // Simpan detail transaksi
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
            if (preg_match('/^\d{2}\/\d{2}\/\d{4} \d{2}:\d{2}:\d{2}$/', $dateValue)) {
                // Format: 01/05/2025 08:30:00
                return Carbon::createFromFormat('d/m/Y H:i:s', $dateValue)->format('Y-m-d H:i:s');
            }
            if (preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $dateValue)) {
                // Format: 01/05/2025
                return Carbon::createFromFormat('d/m/Y', $dateValue)->format('Y-m-d');
            }
            return Carbon::parse($dateValue)->format('Y-m-d H:i:s');
        } catch (\Exception $e) {
            return Carbon::now()->format('Y-m-d H:i:s');
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
