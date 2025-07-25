<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Transaksi;
use App\Models\ParetoAnalysis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        // Basic statistics
        $totalBarang = Barang::count();
        $totalTransaksi = Transaksi::count();
        $totalRevenue = Transaksi::sum('total');
        $totalCustomers = Transaksi::distinct('customer')->count();

        // Get latest ABC analysis results
  

        return view('home', 

        );
    }
}