<?php

namespace App\Http\Controllers;

use App\Imports\TransaksiImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TransaksiImportController extends Controller
{
    public function showImportForm()
    {
        return view('transaksi.import');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:10240',
        ]);

        try {
            DB::beginTransaction();

            $file = $request->file('file');
            
            Log::info('🚀 MULAI IMPORT', [
                'filename' => $file->getClientOriginalName()
            ]);

            // Set timeout lebih lama untuk file besar
            set_time_limit(300); // 5 menit
            ini_set('memory_limit', '512M');

            // Import
            Excel::import(new TransaksiImport, $file);

            DB::commit();

            // Hitung hasil
            $transaksiCount = \App\Models\Transaksi::count();
            $detailCount = \App\Models\TransaksiDetail::count();

            Log::info('✅ IMPORT SELESAI', [
                'transaksi' => $transaksiCount,
                'detail' => $detailCount
            ]);

            return redirect()->back()->with('success', 
                "🎉 IMPORT SELESAI! {$transaksiCount} transaksi dan {$detailCount} detail barang telah diimport."
            );

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('❌ IMPORT GAGAL: ' . $e->getMessage());

            return redirect()->back()
                ->withErrors(['file' => 'Error: ' . $e->getMessage()])
                ->withInput();
        }
    }

    public function clearData()
    {
        try {
            DB::beginTransaction();
            
            // Disable foreign key checks
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            
            // Truncate tables
            \App\Models\TransaksiDetail::truncate();
            \App\Models\Transaksi::truncate();
            
            // Enable foreign key checks
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            
            DB::commit();
            
            return redirect()->back()->with('success', '🗑️ Semua data berhasil dihapus!');
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            // Enable foreign key checks back
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            
            return redirect()->back()->withErrors(['error' => 'Gagal hapus data: ' . $e->getMessage()]);
        }
    }

    public function testData()
    {
        try {
            // Test buat transaksi manual
            $transaksi = \App\Models\Transaksi::create([
                'tanggal' => '2025-01-26',
                'nomor' => 'TEST-00001',
                'customer' => 'Test Customer',
                'subtotal' => 100000,
                'diskon' => 0,
                'ongkir' => 5000,
                'total' => 105000,
                'keterangan' => 'Test transaksi',
                'user_id' => 1,
            ]);

            // Test buat detail manual
            \App\Models\TransaksiDetail::create([
                'transaksi_id' => $transaksi->id,
                'kode_barang' => 'TEST001',
                'nama_barang' => 'Test Barang',
                'qty' => 1,
                'harga_satuan' => 100000,
                'subtotal' => 100000,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Test data berhasil dibuat',
                'transaksi_id' => $transaksi->id
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }
}
