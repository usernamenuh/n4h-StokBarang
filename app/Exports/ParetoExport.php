<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ParetoExport implements FromView, WithStyles, WithColumnWidths
{
    protected $analisis;

    public function __construct($analisis)
    {
        $this->analisis = $analisis;
    }

    public function view(): View
    {
        return view('laporan.pareto_export', [
            'analisis' => $this->analisis
        ]);
    }

    public function styles(Worksheet $sheet)
    {
        // Judul (baris 1) dan header (baris 3) bold
        $styles = [
            1 => ['font' => ['bold' => true, 'size' => 16]],
            2 => ['font' => ['bold' => true, 'size' => 12], 'alignment' => ['horizontal' => 'center']],
            'A' => ['alignment' => ['horizontal' => 'center']],
        ];

        // Tambahkan jarak antar baris data (mulai baris 4)
        $rowCount = count($this->analisis) + 3; // 3 baris header
        for ($i = 3; $i <= $rowCount; $i++) {
            $sheet->getRowDimension($i)->setRowHeight(25); // Atur tinggi baris data
            // Pastikan font normal, bukan bold
            $sheet->getStyle($i)->getFont()->setBold(false);
            // Set vertical alignment center
            $sheet->getStyle($i)->getAlignment()->setVertical('center');
        }

        return $styles;
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,   // No
            'B' => 50,  // Nama Barang
            'C' => 15,  // Total Qty
            'D' => 20,  // Total Nilai
            'E' => 15,  // Persentase
            'F' => 18,  // Persentase Kumulatif (diperlebar)
            'G' => 10,  // Kategori
            'H' => 15,  // Stok
        ];
    }
}
