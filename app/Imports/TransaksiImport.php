<?php

namespace App\Imports;

use App\Models\Transaksi;
use App\Models\TransaksiDetail;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class TransaksiImport implements ToCollection
{
    private $currentTransaksi = null;

    public function collection(Collection $rows)
    {
        $transaksiCount = 0;
        $detailCount = 0;
        
        foreach ($rows as $index => $row) {
            $rowNumber = $index + 1;
            
            // Skip 2 baris pertama
            if ($rowNumber <= 2) {
                continue;
            }
            
            // Ambil SEMUA kolom yang ada
            $cols = [];
            for ($i = 0; $i < count($row); $i++) {
                $rawValue = isset($row[$i]) ? $row[$i] : '';
                $cols[$i] = trim(preg_replace('/\s+/', ' ', $rawValue));
            }
            
            // DEBUGGING: Tampilkan semua kolom untuk 5 baris pertama
            if ($rowNumber <= 7) {
                \Log::info("=== ROW {$rowNumber} ALL COLUMNS ===", $cols);
            }
            
            // DETEKSI TRANSAKSI: Cari di SEMUA kolom yang mengandung format XXXX-XXXXX
            $isTransaksi = false;
            $transaksiCol = -1;
            
            foreach ($cols as $colIndex => $colValue) {
                if (preg_match('/^\d{4}-\d{5}$/', $colValue)) {
                    $isTransaksi = true;
                    $transaksiCol = $colIndex;
                    \Log::info("✅ TRANSAKSI FOUND in column {$colIndex}: {$colValue}");
                    break;
                }
            }
            
            if ($isTransaksi) {
                // BUAT TRANSAKSI - Gunakan kolom yang ditemukan
                try {
                    // Cari kolom tanggal (biasanya kolom pertama atau sebelum nomor transaksi)
                    $tanggalCol = $transaksiCol > 0 ? $transaksiCol - 1 : 0;
                    
                    $this->currentTransaksi = Transaksi::create([
                        'tanggal' => $this->parseDate($cols[$tanggalCol] ?? ''),
                        'nomor' => $cols[$transaksiCol],
                        'customer' => $cols[$transaksiCol + 1] ?? 'Unknown Customer',
                        'subtotal' => $this->parseAmount($cols[$transaksiCol + 2] ?? '0'),
                        'diskon' => $this->parseAmount($cols[$transaksiCol + 3] ?? '0'),
                        'ongkir' => $this->parseAmount($cols[$transaksiCol + 4] ?? '0'),
                        'total' => $this->parseAmount($cols[$transaksiCol + 5] ?? '0'),
                        'keterangan' => $cols[$transaksiCol + 6] ?? '',
                        'user_id' => 1,
                    ]);
                    
                    $transaksiCount++;
                    \Log::info("🎉 TRANSAKSI CREATED: ID={$this->currentTransaksi->id}, Nomor={$cols[$transaksiCol]}");
                    
                } catch (\Exception $e) {
                    \Log::error("❌ Error creating transaksi: " . $e->getMessage(), [
                        'row' => $rowNumber,
                        'transaksi_col' => $transaksiCol,
                        'data' => array_slice($cols, 0, 10)
                    ]);
                }
                
            } else {
                // DETEKSI DETAIL - Jika ada current transaksi dan baris tidak kosong
                if ($this->currentTransaksi && !empty(array_filter($cols))) {
                    
                    // Cari kolom yang berisi kode barang atau nama barang
                    $kodeBarang = '';
                    $namaBarang = '';
                    $qty = 1;
                    $hargaSatuan = 0;
                    $subtotal = 0;
                    
                    // Strategi: Ambil kolom pertama yang tidak kosong sebagai kode barang
                    foreach ($cols as $colIndex => $colValue) {
                        if (!empty($colValue) && $colValue != 'DEKSON') {
                            if (empty($kodeBarang)) {
                                $kodeBarang = $colValue;
                            } else if (empty($namaBarang) && strlen($colValue) > 3) {
                                $namaBarang = $colValue;
                                break;
                            }
                        }
                    }
                    
                    // Cari angka untuk qty, harga, subtotal
                    foreach ($cols as $colValue) {
                        if (is_numeric($colValue) || preg_match('/[\d,.]/', $colValue)) {
                            $amount = $this->parseAmount($colValue);
                            if ($amount > 0) {
                                if ($qty == 1 && $amount < 100) {
                                    $qty = $amount;
                                } else if ($hargaSatuan == 0) {
                                    $hargaSatuan = $amount;
                                } else if ($subtotal == 0) {
                                    $subtotal = $amount;
                                    break;
                                }
                            }
                        }
                    }
                    
                    if (!empty($kodeBarang) || !empty($namaBarang)) {
                        try {
                            TransaksiDetail::create([
                                'transaksi_id' => $this->currentTransaksi->id,
                                'kode_barang' => $kodeBarang ?: 'AUTO-' . time(),
                                'nama_barang' => $namaBarang ?: 'Unknown Item',
                                'qty' => $qty,
                                'harga_satuan' => $hargaSatuan,
                                'subtotal' => $subtotal ?: ($qty * $hargaSatuan),
                            ]);
                            
                            $detailCount++;
                            \Log::info("🎉 DETAIL CREATED: {$kodeBarang} - {$namaBarang}");
                            
                        } catch (\Exception $e) {
                            \Log::error("❌ Error creating detail: " . $e->getMessage(), [
                                'row' => $rowNumber,
                                'transaksi_id' => $this->currentTransaksi->id,
                                'kode_barang' => $kodeBarang,
                                'nama_barang' => $namaBarang
                            ]);
                        }
                    }
                }
            }
        }
        
        \Log::info("🏁 FINAL RESULT", [
            'transaksi' => $transaksiCount,
            'detail' => $detailCount,
            'current_transaksi_id' => $this->currentTransaksi ? $this->currentTransaksi->id : null
        ]);
    }

    private function parseDate($dateValue)
    {
        if (empty($dateValue)) {
            return Carbon::now()->format('Y-m-d');
        }

        try {
            // Jika format d/m/Y
            if (preg_match('/^\d{1,2}\/\d{1,2}\/\d{4}$/', $dateValue)) {
                return Carbon::createFromFormat('d/m/Y', $dateValue)->format('Y-m-d');
            }
            
            // Jika Excel serial number
            if (is_numeric($dateValue) && $dateValue > 40000) {
                $unixTimestamp = ($dateValue - 25569) * 86400;
                return date('Y-m-d', $unixTimestamp);
            }
            
            // Fallback
            return Carbon::parse($dateValue)->format('Y-m-d');
            
        } catch (\Exception $e) {
            \Log::warning("Date parse failed: {$dateValue}");
            return Carbon::now()->format('Y-m-d');
        }
    }

    private function parseAmount($value)
    {
        if (empty($value)) return 0;
        
        // Convert to string first
        $value = (string) $value;
        
        // Hapus semua kecuali angka, koma, titik
        $cleaned = preg_replace('/[^\d,.]/', '', $value);
        if (empty($cleaned)) return 0;
        
        // Jika ada titik dan koma (format Indonesia)
        if (strpos($cleaned, '.') !== false && strpos($cleaned, ',') !== false) {
            $cleaned = str_replace('.', '', $cleaned); // Hapus titik
            $cleaned = str_replace(',', '.', $cleaned); // Koma jadi titik
        }
        // Jika hanya ada koma
        else if (strpos($cleaned, ',') !== false) {
            $cleaned = str_replace(',', '.', $cleaned);
        }
        
        return (float) $cleaned;
    }

    private function parseQty($value)
    {
        if (empty($value)) return 1;
        
        // Convert to string first
        $value = (string) $value;
        
        // Extract angka saja
        $cleaned = preg_replace('/[^\d,.]/', '', $value);
        if (empty($cleaned)) return 1;
        
        $cleaned = str_replace(',', '.', $cleaned);
        $result = (float) $cleaned;
        
        return $result > 0 ? $result : 1;
    }
}
