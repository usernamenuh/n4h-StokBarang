<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\pelanggan;
use App\Models\rooms;
use App\Models\hotel;
use Illuminate\Support\Facades\DB;
use App\Models\Barang;
use App\Models\nilai;
use App\Models\dokter;
use App\Models\booking;
use App\Models\mobil;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $totalBarang = Barang::count();
        $totalStok = Barang::sum('stok');
        $barangStokTerbanyak = Barang::orderBy('stok', 'desc')->first();
        $barangHargaTertinggi = Barang::orderBy('harga', 'desc')->first();
        return view('home', compact('totalBarang', 'totalStok', 'barangStokTerbanyak', 'barangHargaTertinggi'));
    }
}
