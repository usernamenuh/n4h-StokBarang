<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\TransaksiDetail;
use Illuminate\Support\Facades\DB;

class ParetoAnalisisController extends Controller
{
    public function index()
    {
        // Ambil data total qty & total nilai per barang dari transaksi
        $data = TransaksiDetail::select(
                'barang_id',
                DB::raw('SUM(qty) as total_qty'),
                DB::raw('SUM(subtotal) as total_nilai')
            )
            ->whereNotNull('barang_id') // hanya yang punya barang_id
            ->groupBy('barang_id')
            ->with('barang') // relasi ke Barang
            ->orderByDesc(DB::raw('SUM(subtotal)'))
            ->get();

        // Hitung total nilai untuk persentase kumulatif
        $totalSemuaNilai = $data->sum('total_nilai');

        // Tambahkan kolom tambahan untuk analisis Pareto
        $hasil = [];
        $akumulasiPersen = 0;

        foreach ($data as $index => $row) {
            $persentase = ($row->total_nilai / $totalSemuaNilai) * 100;
            $akumulasiPersen += $persentase;

            // Tentukan kategori ABC
            if ($akumulasiPersen <= 80) {
                $kategori = 'A';
            } elseif ($akumulasiPersen <= 95) {
                $kategori = 'B';
            } else {
                $kategori = 'C';
            }

            // Ambil stok terbaru dari tabel barang
            $stokSaatIni = $row->barang ? $row->barang->does_pcs : 0;

            $hasil[] = [
                'no' => $index + 1,
                'nama_barang' => $row->barang ? $row->barang->nama : 'Tidak Ditemukan',
                'total_qty' => $row->total_qty,
                'total_nilai' => $row->total_nilai,
                'persentase' => number_format($persentase, 2),
                'kategori' => $kategori,
                'stok_saat_ini' => $stokSaatIni,
            ];
        }

        return view('pareto.index', compact('hasil'));
    }
}
