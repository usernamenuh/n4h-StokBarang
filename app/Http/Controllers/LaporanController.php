<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TransaksiDetail;
use App\Models\Barang;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ParetoExport;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    /**
     * Ambil data analisis Pareto (digunakan untuk view dan export)
     */
    private function getParetoData()
    {
        // Ambil data transaksi detail agregat
        $analisis = DB::table('transaksi_details')
            ->selectRaw('barang_id, nama_barang, SUM(qty) as total_qty, SUM(subtotal) as total_nilai')
            ->groupBy('barang_id', 'nama_barang')
            ->orderByDesc('total_nilai')
            ->get();

        $totalNilaiSemua = $analisis->sum('total_nilai');
        $akumulasi = 0;

        foreach ($analisis as $item) {
            // Hitung persentase
            $persentase = $totalNilaiSemua > 0 ? ($item->total_nilai / $totalNilaiSemua) * 100 : 0;
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

        return [$analisis, $totalNilaiSemua];
    }

    /**
     * Tampilkan analisis Pareto di view
     */
    public function analisisPareto()
    {
        [$analisis, $totalNilaiSemua] = $this->getParetoData();
        return view('laporan.pareto', compact('analisis', 'totalNilaiSemua'));
    }

    /**
     * Export analisis Pareto ke Excel
     */
   public function exportPareto()
{
    $analisis = \DB::table('transaksi_details')
        ->selectRaw('barang_id, nama_barang, SUM(qty) as total_qty, SUM(subtotal) as total_nilai')
        ->groupBy('barang_id', 'nama_barang')
        ->orderByDesc('total_nilai')
        ->get();

    $totalNilaiSemua = $analisis->sum('total_nilai');
    $akumulasi = 0;

    foreach ($analisis as $item) {
        $persentase = $totalNilaiSemua > 0 ? ($item->total_nilai / $totalNilaiSemua) * 100 : 0;
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
        $item->stok_saat_ini = \App\Models\Barang::find($item->barang_id)?->does_pcs ?? 0;
    }

    return Excel::download(new ParetoExport($analisis), 'analisis_pareto.xlsx');
}

}
