<?php

namespace App\Imports;

use App\Models\Transaksi;
use App\Models\Barang;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Carbon\Carbon;

class TransaksiImport implements 
    ToModel, 
    WithHeadingRow, 
    SkipsOnError, 
    SkipsOnFailure,
    WithBatchInserts, 
    WithChunkReading
{
    use Importable, SkipsErrors, SkipsFailures;

    private $rowCount = 0;
    private $successCount = 0;

    public function model(array $row)
    {
        $this->rowCount++;
        
        // Map different possible column names
        $mappedRow = $this->mapColumns($row);
        
        // Find barang by kode or nama
        $barang = $this->findBarang($mappedRow);
        if (!$barang) {
            return null; // Skip if barang not found
        }

        // Parse date
        $tanggal = $this->parseDate($mappedRow['tanggal'] ?? null);
        if (!$tanggal) {
            return null; // Skip if date is invalid
        }

        $this->successCount++;
        return new Transaksi([
            'tanggal' => $tanggal,
            'nomor' => trim($mappedRow['nomor'] ?? ''),
            'customer' => trim($mappedRow['customer'] ?? ''),
            'barang_id' => $barang->id,
            'qty' => $this->parseInteger($mappedRow['qty'] ?? 1),
            'subtotal' => $this->parseNumeric($mappedRow['subtotal'] ?? 0),
            'disc' => $this->parseNumeric($mappedRow['disc'] ?? 0),
            'ongkos' => $this->parseNumeric($mappedRow['ongkos'] ?? 0),
            'total' => $this->parseNumeric($mappedRow['total'] ?? 0),
            'keterangan' => trim($mappedRow['keterangan'] ?? ''),
            'user_id' => auth()->id(),
        ]);
    }

    private function mapColumns(array $row)
    {
        $mapped = [];
        
        // Map various column names to expected format
        $columnMappings = [
            'tanggal' => ['tanggal', 'date', 'transaction_date', 'tgl'],
            'nomor' => ['nomor', 'number', 'transaction_number', 'no', 'invoice'],
            'customer' => ['customer', 'client', 'buyer', 'pelanggan'],
            'kode_barang' => ['kode_barang', 'item_code', 'product_code', 'kode'],
            'nama_barang' => ['nama_barang', 'item_name', 'product_name', 'nama'],
            'qty' => ['qty', 'quantity', 'jumlah', 'amount'],
            'subtotal' => ['subtotal', 'sub_total', 'amount'],
            'disc' => ['disc', 'discount', 'diskon', 'potongan'],
            'ongkos' => ['ongkos', 'shipping', 'delivery', 'ongkir'],
            'total' => ['total', 'grand_total', 'amount_total'],
            'keterangan' => ['keterangan', 'notes', 'remarks', 'description']
        ];
        
        foreach ($columnMappings as $targetKey => $possibleKeys) {
            foreach ($possibleKeys as $key) {
                if (isset($row[$key]) && !empty($row[$key])) {
                    $mapped[$targetKey] = $row[$key];
                    break;
                }
            }
        }
        
        return $mapped;
    }

    private function findBarang($mappedRow)
    {
        // Try to find by kode first
        if (!empty($mappedRow['kode_barang'])) {
            $barang = Barang::where('kode', trim($mappedRow['kode_barang']))->first();
            if ($barang) return $barang;
        }
        
        // Try to find by nama
        if (!empty($mappedRow['nama_barang'])) {
            $barang = Barang::where('nama', 'LIKE', '%' . trim($mappedRow['nama_barang']) . '%')->first();
            if ($barang) return $barang;
        }
        
        return null;
    }

    public function batchSize(): int
    {
        return 100;
    }

    public function chunkSize(): int
    {
        return 100;
    }
    
    public function getRowCount(): int
    {
        return $this->rowCount;
    }
    
    public function getSuccessCount(): int
    {
        return $this->successCount;
    }

    private function parseNumeric($value)
    {
        if (is_numeric($value)) {
            return floatval($value);
        }
        
        // Remove currency symbols and spaces
        $cleaned = preg_replace('/[^\d,.-]/', '', $value);
        
        // Handle different decimal separators
        if (strpos($cleaned, ',') !== false && strpos($cleaned, '.') !== false) {
            $cleaned = str_replace(',', '', $cleaned);
        } elseif (strpos($cleaned, ',') !== false) {
            $cleaned = str_replace(',', '.', $cleaned);
        }
        
        if (is_numeric($cleaned)) {
            return floatval($cleaned);
        }
        
        return 0;
    }

    private function parseInteger($value)
    {
        if (is_numeric($value)) {
            return intval($value);
        }
        
        return 1;
    }

    private function parseDate($value)
    {
        if (empty($value)) {
            return null;
        }

        try {
            // Handle Excel date serial numbers
            if (is_numeric($value)) {
                return Carbon::createFromFormat('Y-m-d', '1900-01-01')->addDays($value - 2);
            }
            
            // Try different date formats
            $formats = [
                'Y-m-d', 'd/m/Y', 'd-m-Y', 'm/d/Y', 'Y-m-d H:i:s',
                'd/m/Y H:i:s', 'd-m-Y H:i:s', 'Y/m/d', 'Y/m/d H:i:s'
            ];
            
            foreach ($formats as $format) {
                $date = \DateTime::createFromFormat($format, $value);
                if ($date !== false) {
                    return Carbon::instance($date);
                }
            }
            
            // Try Carbon's flexible parsing
            return Carbon::parse($value);
        } catch (\Exception $e) {
            return Carbon::now(); // Use current date as fallback
        }
    }
}