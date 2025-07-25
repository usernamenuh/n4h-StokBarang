<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TransaksiTemplateEnhanced implements FromArray, WithHeadings, WithStyles
{
    public function array(): array
    {
        return [
            [
                '01/08/2025',
                'TXN-20250801-001',
                'RICHARD MODERN M | INTERIOR SAMWANG',
                '3680000',
                '0',
                '20000',
                '3700000',
                'INTERIOR DE MANGGUR',
                '01/08/2025',
                'SITI',
                '1'
            ],
            [
                '02/08/2025',
                'TXN-20250802-002',
                'JURALIK DEKSON LDH HALIMINA',
                '820000',
                '0',
                '0',
                '820000',
                'Pembelian barang',
                '02/08/2025',
                'DINI',
                '1'
            ],
            [
                '03/08/2025',
                'TXN-20250803-003',
                'PT MAJU BERSAMA',
                '1500000',
                '50000',
                '25000',
                '1475000',
                'Order bulanan',
                '03/08/2025',
                'ADMIN',
                '2'
            ]
        ];
    }

    public function headings(): array
    {
        return [
            'tanggal',
            'nomor',
            'customer',
            'subtotal',
            'disc',
            'ongkos_kirim',
            'total',
            'keterangan',
            'tgl_input',
            'user_id',
            'jum_print'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Set column widths
        $sheet->getColumnDimension('A')->setWidth(12);
        $sheet->getColumnDimension('B')->setWidth(20);
        $sheet->getColumnDimension('C')->setWidth(35);
        $sheet->getColumnDimension('D')->setWidth(15);
        $sheet->getColumnDimension('E')->setWidth(12);
        $sheet->getColumnDimension('F')->setWidth(15);
        $sheet->getColumnDimension('G')->setWidth(15);
        $sheet->getColumnDimension('H')->setWidth(25);
        $sheet->getColumnDimension('I')->setWidth(12);
        $sheet->getColumnDimension('J')->setWidth(12);
        $sheet->getColumnDimension('K')->setWidth(10);

        return [
            1 => [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'E3F2FD']
                ]
            ],
        ];
    }
}
