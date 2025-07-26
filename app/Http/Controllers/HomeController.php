<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Transaksi;
use App\Models\ParetoAnalisis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{

    public function index()
    {
        // Basic statistics
        $totalBarang = Barang::count();
        $totalTransaksi = Transaksi::count();
        $totalRevenue = Transaksi::sum('total');
        $totalCustomers = Transaksi::distinct('customer')->count();

        // Get latest ABC analysis results
        $analisis = ParetoAnalisis::all(); // Ganti dengan query Pareto Analysis kamu, misal: ParetoAnalysis::all();

        // Hitung total nilai semua (misal total penjualan)
        $totalNilaiSemua = $analisis->sum('total_nilai');

        return view('home', compact(
            'totalBarang',
            'totalTransaksi',
            'totalRevenue',
            'totalCustomers',
            'analisis',
            'totalNilaiSemua' // <-- tambahkan ini
        ));
    }
}
