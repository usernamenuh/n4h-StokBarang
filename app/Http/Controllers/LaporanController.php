<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TransaksiDetail;
use App\Models\Barang;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ParetoExport;
use Illuminate\Support\Facades\DB;
use App\Models\ParetoAnalisis;
use Carbon\Carbon;

class LaporanController extends Controller
{
    /**
     * Ambil data analisis Pareto (digunakan untuk view dan export)
     */
    private function getParetoData(Request $request)
    {
        $sortBy = $request->query('sort_by', 'value'); 
        $analisis = DB::table('transaksi_details')
            ->join('transaksis', 'transaksi_details.transaksi_id', '=', 'transaksis.id')
            ->selectRaw('transaksi_details.barang_id, transaksi_details.nama_barang, SUM(transaksi_details.qty) as total_qty, SUM(transaksi_details.subtotal) as total_nilai')
            ->groupBy('transaksi_details.barang_id', 'transaksi_details.nama_barang')
            ->orderByDesc($sortBy === 'quantity' ? 'total_qty' : 'total_nilai')
            ->get();

        $totalSumOfBasis = $sortBy === 'quantity' ? $analisis->sum('total_qty') : $analisis->sum('total_nilai');
        
        $akumulasiKumulatif = 0;

        foreach ($analisis as $item) {
            $itemBasis = $sortBy === 'quantity' ? $item->total_qty : $item->total_nilai;

            // Hitung persentase kontribusi individual
            $persentase = $totalSumOfBasis > 0 ? ($itemBasis / $totalSumOfBasis) * 100 : 0;
            
            // Hitung persentase kumulatif
            $akumulasiKumulatif += $persentase;

            // Klasifikasi ABC berdasarkan persentase kumulatif
            if ($akumulasiKumulatif <= 80) {
                $kategori = 'A';
            } elseif ($akumulasiKumulatif <= 95) {
                $kategori = 'B';
            } else {
                $kategori = 'C';
            }

            $item->persentase = round($persentase, 2);
            $item->persentase_kumulatif = round($akumulasiKumulatif, 2); // Tambah kolom kumulatif
            $item->kategori = $kategori;

            // Ambil data stok saat ini
            $barang = Barang::where('id', $item->barang_id)->first();
            $item->stok_saat_ini = $barang ? $barang->does_pcs : 0;
        }

        return [$analisis, $totalSumOfBasis];
    }

    /**
     * Tampilkan analisis Pareto di view
     */
    public function analisisPareto(Request $request)
    {
        [$analisis, $totalSumOfBasis] = $this->getParetoData($request);
        $periode = $request->query('periode', date('Y-m'));
        return view('laporan.pareto', compact('analisis', 'totalSumOfBasis'));
    }

    /**
     * Export analisis Pareto ke Excel
     */
    public function exportPareto(Request $request)
    {
        $sortBy = $request->query('sort_by', 'value'); 

        $analisis = DB::table('transaksi_details')
            ->join('transaksis', 'transaksi_details.transaksi_id', '=', 'transaksis.id')
            ->selectRaw('transaksi_details.barang_id, transaksi_details.nama_barang, SUM(transaksi_details.qty) as total_qty, SUM(transaksi_details.subtotal) as total_nilai')
            ->groupBy('transaksi_details.barang_id', 'transaksi_details.nama_barang')
            ->orderByDesc($sortBy === 'quantity' ? 'total_qty' : 'total_nilai')
            ->get();

        $totalSumOfBasis = $sortBy === 'quantity' ? $analisis->sum('total_qty') : $analisis->sum('total_nilai');
        
        $akumulasiKumulatif = 0;

        foreach ($analisis as $item) {
            $itemBasis = $sortBy === 'quantity' ? $item->total_qty : $item->total_nilai;
            $persentase = $totalSumOfBasis > 0 ? ($itemBasis / $totalSumOfBasis) * 100 : 0;
            $akumulasiKumulatif += $persentase;

            if ($akumulasiKumulatif <= 80) {
                $kategori = 'A';
            } elseif ($akumulasiKumulatif <= 95) {
                $kategori = 'B';
            } else {
                $kategori = 'C';
            }

            $item->persentase = round($persentase, 2);
            $item->persentase_kumulatif = round($akumulasiKumulatif, 2);
            $item->kategori = $kategori;
            $item->stok_saat_ini = Barang::find($item->barang_id)?->does_pcs ?? 0;
        }

        return Excel::download(new ParetoExport($analisis), 'analisis_pareto.xlsx');
    }
}
