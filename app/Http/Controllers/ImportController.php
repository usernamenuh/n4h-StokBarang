<?php

namespace App\Http\Controllers;

use App\Imports\BarangImportFinal;
use App\Imports\TransaksiExcelImport;
use App\Models\Barang;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;

class ImportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $barangCount = Barang::count();
        $transaksiCount = Transaksi::count();
        
        return view('import.index', compact('barangCount', 'transaksiCount'));
    }
}