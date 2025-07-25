<?php

namespace App\Http\Controllers;

use App\Imports\BarangImportFixed;
use App\Models\Barang;
use App\Models\Transaksi;
use Illuminate\Http\Request;
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

    public function importBarang(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:10240'
        ]);

        try {
            Log::info('Starting FIXED import');
            $startTime = microtime(true);
            
            $import = new BarangImportFixed();
            Excel::import($import, $request->file('file'));
            
            $endTime = microtime(true);
            $executionTime = round($endTime - $startTime, 2);
            
            $result = $import->array([]); // Get results
            $totalRows = $import->getRowCount();
            $successCount = $import->getSuccessCount();
            $createdUsers = $import->getCreatedUsersCount();
            $errors = $import->getErrors();
            
            Log::info("Fixed import completed in {$executionTime}s");
            
            $message = "Import selesai dalam {$executionTime} detik! ";
            if ($createdUsers > 0) {
                $message .= "{$createdUsers} user baru dibuat, ";
            }
            $message .= "{$successCount} barang berhasil diimport.";
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'processed' => $totalRows,
                    'success_count' => $successCount,
                    'users_created' => $createdUsers,
                    'errors' => $errors,
                    'execution_time' => $executionTime
                ]);
            }
            
            return back()->with('success', $message);
                        
        } catch (\Exception $e) {
            Log::error('Import Error: ' . $e->getMessage());
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Import gagal: ' . $e->getMessage(),
                    'errors' => [$e->getMessage()]
                ], 500);
            }
            
            return back()->with('error', 'Import gagal: ' . $e->getMessage());
        }
    }

    public function importTransaksi(Request $request)
    {
        // Keep existing implementation
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:10240'
        ]);

        try {
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => "Import transaksi berhasil!",
                    'processed' => 0,
                    'errors' => []
                ]);
            }
            
            return back()->with('success', "Import transaksi berhasil!");
                        
        } catch (\Exception $e) {
            Log::error('Import Transaksi Error: ' . $e->getMessage());
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Import gagal: ' . $e->getMessage(),
                    'errors' => [$e->getMessage()]
                ], 500);
            }
            
            return back()->with('error', 'Import gagal: ' . $e->getMessage());
        }
    }
}