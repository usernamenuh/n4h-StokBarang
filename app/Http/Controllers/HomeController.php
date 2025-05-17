<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\pelanggan;
use App\Models\rooms;
use App\Models\hotel;
use Illuminate\Support\Facades\DB;

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
        $pelanggans = \App\Models\pelanggan::all();
        $jumlahPelanggan = \App\Models\pelanggan::count();
        $jumlahKamar = \App\Models\rooms::count();
        $totalPendapatan = \App\Models\hotel::whereNotNull('check_out')->get()->sum(function($hotel) {
            if ($hotel->room && $hotel->check_in && $hotel->check_out) {
                $hari = \Carbon\Carbon::parse($hotel->check_in)->diffInDays(\Carbon\Carbon::parse($hotel->check_out));
                return $hari * $hotel->room->price;
            }
            return 0;
        });
        $jumlahKamarDisewa = \App\Models\hotel::whereNull('check_out')->count();
        return view('home', compact('pelanggans', 'jumlahPelanggan', 'jumlahKamar', 'totalPendapatan', 'jumlahKamarDisewa'));
    }
}
