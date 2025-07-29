<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TransaksiDetail;
use App\Models\Barang;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ParetoExport;
use Illuminate\Support\Facades\DB;
use App\Models\ParetoAnalisis;

class LaporanController extends Controller
{
    /**
     * Ambil data analisis Pareto (digunakan untuk view dan export)
     */
    private function getParetoData(Request $request)
    {
        $sortBy = $request->query('sort_by', 'value'); // Default to 'value'

        // Ambil data transaksi detail agregat
        $analisis = DB::table('transaksi_details')
            ->selectRaw('barang_id, nama_barang, SUM(qty) as total_qty, SUM(subtotal) as total_nilai')
            ->groupBy('barang_id', 'nama_barang')
            ->orderByDesc($sortBy === 'quantity' ? 'total_qty' : 'total_nilai')
            ->get();

        // Tentukan basis total untuk perhitungan persentase
        $totalSumOfBasis = $sortBy === 'quantity' ? $analisis->sum('total_qty') : $analisis->sum('total_nilai');
        $akumulasi = 0;

        foreach ($analisis as $item) {
            // Tentukan nilai basis untuk item saat ini
            $itemBasis = $sortBy === 'quantity' ? $item->total_qty : $item->total_nilai;

            // Hitung persentase
            $persentase = $totalSumOfBasis > 0 ? ($itemBasis / $totalSumOfBasis) * 100 : 0;
            $akumulasi += $persentase;

            // Tentukan kategori
            if ($akumulasi <= 80) {
                $kategori = 'A';
            } elseif ($akumulasi <= 95) {
                $kategori = 'B';
            } else {
                $kategori = 'C';
            }

            $item->persentase = round($persentase, 2);
            $item->kategori = $kategori;

            // Ambil stok saat ini dari tabel barangs
            $barang = Barang::where('id', $item->barang_id)->first();
            $item->stok_saat_ini = $barang ? $barang->does_pcs : 0;
        }

        return [$analisis, $totalSumOfBasis]; // Return totalSumOfBasis instead of totalNilaiSemua
    }

    /**
     * Tampilkan analisis Pareto di view
     */
    public function analisisPareto(Request $request)
    {
        [$analisis, $totalSumOfBasis] = $this->getParetoData($request);

        // Ambil periode sekarang (misal: tahun-bulan)
        $periode = date('Y-m');

        return view('laporan.pareto', compact('analisis', 'totalSumOfBasis'));
    }

    /**
     * Export analisis Pareto ke Excel
     */
    public function exportPareto(Request $request)
    {
        $sortBy = $request->query('sort_by', 'value'); // Default to 'value'
        $analisis = DB::table('transaksi_details')
            ->selectRaw('barang_id, nama_barang, SUM(qty) as total_qty, SUM(subtotal) as total_nilai')
            ->groupBy('barang_id', 'nama_barang')
            ->orderByDesc($sortBy === 'quantity' ? 'total_qty' : 'total_nilai')
            ->get();

        $totalSumOfBasis = $sortBy === 'quantity' ? $analisis->sum('total_qty') : $analisis->sum('total_nilai');
        $akumulasi = 0;

        foreach ($analisis as $item) {
            $itemBasis = $sortBy === 'quantity' ? $item->total_qty : $item->total_nilai;
            $persentase = $totalSumOfBasis > 0 ? ($itemBasis / $totalSumOfBasis) * 100 : 0;
            $akumulasi += $persentase;

            if ($akumulasi <= 80) {
                $kategori = 'A';
            } elseif ($akumulasi <= 95) {
                $kategori = 'B';
            } else {
                $kategori = 'C';
            }

            $item->persentase = round($persentase, 2);
            $item->kategori = $kategori;
            $item->stok_saat_ini = Barang::find($item->barang_id)?->does_pcs ?? 0;
        }

        return Excel::download(new ParetoExport($analisis), 'analisis_pareto.xlsx');
    }
}
