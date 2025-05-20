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
        $pelanggans = pelanggan::all();
        $jumlahPelanggan = pelanggan::count();
        $jumlahKamar = rooms::count();
        $totalPendapatan = hotel::whereNotNull('check_out')->get()->sum(function($hotel) {
            if ($hotel->room && $hotel->check_in && $hotel->check_out) {
                $hari = \Carbon\Carbon::parse($hotel->check_in)->diffInDays(\Carbon\Carbon::parse($hotel->check_out));
                return $hari * $hotel->room->price;
            }
            return 0;
        });
        $jumlahKamarDisewa = hotel::whereNull('check_out')->count();
        $totalBarang = Barang::count();
        $totalStok = Barang::sum('stok');
        $barangStokTerbanyak = Barang::orderBy('stok', 'desc')->first();
        $barangHargaTertinggi = Barang::orderBy('harga', 'desc')->first();
        $jumlahSiswa = nilai::count();
        $nilaiRataRata = nilai::avg('nilai_rata_rata');
        $nilaiTertinggi = nilai::max('nilai_rata_rata');
        $nilaiTerendah = nilai::min('nilai_rata_rata');
        $totalMobil = mobil::count();
        $mobilTersedia = mobil::where('status', 'tersedia')->count();
        $mobilDisewa = mobil::where('status', 'dirental')->count();
        $servis = 0;
        $totalDokter = dokter::count();
        $hariIni = \Carbon\Carbon::now()->locale('id')->isoFormat('dddd');
        $jadwalHariIni = dokter::where('hari', $hariIni)->count();
        $bookingAktif = booking::count();
        $dokterOffline = dokter::where('status', 'offline')->count();
        return view('home', compact('pelanggans', 'jumlahPelanggan', 'jumlahKamar', 'totalPendapatan', 'jumlahKamarDisewa', 'totalBarang', 'totalStok', 'barangStokTerbanyak', 'barangHargaTertinggi', 'jumlahSiswa', 'nilaiRataRata', 'nilaiTertinggi', 'nilaiTerendah', 'totalMobil', 'mobilTersedia', 'mobilDisewa', 'servis', 'totalDokter', 'jadwalHariIni', 'bookingAktif', 'dokterOffline'));
    }
}
