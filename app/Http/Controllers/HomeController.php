<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Barang;
use App\Models\Transaksi;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     */
    public function index()
    {
        try {
            // Get statistics data
            $stats = $this->getStatsData();
            
            return view('home', $stats);
        } catch (\Exception $e) {
            // Fallback data jika ada error
            $fallbackStats = [
                'totalBarang' => 0,
                'totalTransaksi' => 0,
                'totalRevenue' => 0,
                'totalCustomers' => 0,
                'error' => 'Tidak dapat memuat data statistik'
            ];
            
            return view('home', $fallbackStats);
        }
    }

    /**
     * Get statistics data
     */
    private function getStatsData()
    {
        // Total Barang
        $totalBarang = $this->safeCount('barangs') ?? 0;
        
        // Total Transaksi
        $totalTransaksi = $this->safeCount('transaksis') ?? 0;
        
        // Total Revenue (asumsi ada kolom total_harga di transaksi)
        $totalRevenue = $this->safeSumColumn('transaksis', 'total_harga') ?? 0;
        
        // Stok Menipis (barang dengan stok < 10, sesuaikan dengan kebutuhan)
        $stokMenipis = $this->safeCountWhere('barangs', 'stok', '<', 10) ?? 0;
        
        return [
            'totalBarang' => $totalBarang,
            'totalTransaksi' => $totalTransaksi,
            'totalRevenue' => $totalRevenue,
            'totalCustomers' => $stokMenipis, // Menggunakan untuk stok menipis
            'user' => Auth::user()
        ];
    }

    /**
     * Safe count method with error handling
     */
    private function safeCount($table)
    {
        try {
            return DB::table($table)->count();
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * Safe sum method with error handling
     */
    private function safeSumColumn($table, $column)
    {
        try {
            return DB::table($table)->sum($column) ?? 0;
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * Safe count with where condition
     */
    private function safeCountWhere($table, $column, $operator, $value)
    {
        try {
            return DB::table($table)->where($column, $operator, $value)->count();
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * API endpoint untuk mendapatkan stats (untuk AJAX)
     */
    public function getStats()
    {
        $stats = $this->getStatsData();
        return response()->json($stats);
    }
}
