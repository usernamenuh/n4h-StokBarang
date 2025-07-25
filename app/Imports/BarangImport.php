<?php

namespace App\Imports;

use App\Models\Barang;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Illuminate\Support\Facades\Log;

class BarangImport implements 
    ToModel, 
    WithHeadingRow, 
    SkipsOnError,
    WithBatchInserts, 
    WithChunkReading
{
    use Importable, SkipsErrors;

    private $rowCount = 0;
    private $successCount = 0;

    public function model(array $row)
    {
        $this->rowCount++;
        
        // Log the row data for debugging
        Log::info('Processing row: ', $row);
        
        // Direct mapping based on your Excel structure
        $kode = trim($row['kode'] ?? '');
        $nama = trim($row['nama'] ?? '');
        $doesPcs = $this->parseNumeric($row['does_pcs'] ?? 1);
        $golongan = trim($row['golongan'] ?? '');
        $hbeli = $this->parseNumeric($row['hbeli'] ?? 0);
        $userId = trim($row['user_id'] ?? '');
        $keterangan = trim($row['keterangan'] ?? '');
        
        // Skip if essential fields are empty
        if (empty($kode) || empty($nama)) {
            Log::warning("Skipping row {$this->rowCount}: Missing kode or nama");
            return null;
        }

        try {
            // Check if already exists
            $existing = Barang::where('kode', $kode)->first();
            if ($existing) {
                Log::info("Updating existing barang: {$kode}");
                $existing->update([
                    'nama' => $nama,
                    'does_pcs' => $doesPcs,
                    'golongan' => $golongan ?: 'GENERAL',
                    'hbeli' => $hbeli,
                    'keterangan' => $keterangan,
                ]);
                $this->successCount++;
                return null;
            }

            $this->successCount++;
            $barang = new Barang([
                'kode' => $kode,
                'nama' => $nama,
                'does_pcs' => $doesPcs,
                'golongan' => $golongan ?: 'GENERAL',
                'hbeli' => $hbeli,
                'user_id' => auth()->id(),
                'keterangan' => $keterangan,
            ]);
            
            Log::info("Creating new barang: {$kode}");
            return $barang;
            
        } catch (\Exception $e) {
            Log::error("Error processing row {$this->rowCount}: " . $e->getMessage());
            return null;
        }
    }

    public function batchSize(): int
    {
        return 50; // Even smaller batches
    }

    public function chunkSize(): int
    {
        return 50;
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
        
        // Handle your specific format (e.g., "2,900,000.00")
        $cleaned = str_replace(',', '', $value);
        $cleaned = preg_replace('/[^\d.-]/', '', $cleaned);
        
        if (is_numeric($cleaned)) {
            return floatval($cleaned);
        }
        
        return 0;
    }
}