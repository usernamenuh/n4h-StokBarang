<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
     * For backward compatibility, redirect to dashboard
     */
    public function index()
    {
        // Redirect ke dashboard baru
        return redirect()->route('dashboard');
    }

    /**
     * Get statistics data for API calls
     */
    public function getStats()
    {
        try {
            // Get basic stats
            $stats = [
                'totalBarang' => $this->safeCount('barangs'),
                'totalTransaksi' => $this->safeCount('transaksis'),
                'totalRevenue' => $this->safeSumColumn('transaksis', 'total_harga'),
                'stokMenipis' => $this->safeCountWhere('barangs', 'stok', '<', 10),
                'user' => Auth::user()
            ];
            
            return response()->json($stats);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Unable to fetch stats',
                'totalBarang' => 0,
                'totalTransaksi' => 0,
                'totalRevenue' => 0,
                'stokMenipis' => 0,
            ]);
        }
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
}
