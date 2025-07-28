<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\User;
use App\Models\Barang;
use App\Models\TransaksiDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use App\Imports\TransaksiImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;

class TransaksiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $transaksis = Transaksi::with('user')->orderBy('tanggal', 'desc')->get();
        return view('transaksi.index', compact('transaksis'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::all();
        $barangs = Barang::all();
        return view('transaksi.create', compact('users', 'barangs'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'nomor' => 'required|string|max:50|unique:transaksis,nomor',
            'customer' => 'required|string|max:255',
            'ongkir' => 'nullable|numeric|min:0',
            'keterangan' => 'nullable|string|max:1000',
            'jum_print' => 'nullable|integer|min:0',
            'details' => 'required|array|min:1',
            'details.*.barang_id' => 'required|exists:barangs,id',
            'details.*.qty' => 'required|numeric|min:0.01',
            'details.*.harga_satuan' => 'required|numeric|min:0',
        ], [
    'tanggal.required' => 'Tanggal transaksi wajib diisi.',
    'tanggal.date' => 'Format tanggal tidak valid.',
    'nomor.required' => 'Nomor transaksi wajib diisi.',
    'nomor.max' => 'Nomor transaksi maksimal 50 karakter.',
    'nomor.unique' => 'Nomor transaksi sudah digunakan.',
    'customer.required' => 'Nama customer wajib diisi.',
    'customer.max' => 'Nama customer maksimal 255 karakter.',
    'ongkir.numeric' => 'Ongkir harus berupa angka.',
    'ongkir.min' => 'Ongkir minimal 0.',
    'keterangan.max' => 'Keterangan maksimal 1000 karakter.',
    'jum_print.integer' => 'Jumlah cetak harus berupa angka bulat.',
    'jum_print.min' => 'Jumlah cetak minimal 0.',
    'details.required' => 'Detail transaksi wajib diisi.',
    'details.array' => 'Format detail transaksi tidak valid.',
    'details.min' => 'Minimal harus ada 1 barang dalam transaksi.',
    'details.*.barang_id.required' => 'Barang wajib dipilih.',
    'details.*.barang_id.exists' => 'Barang yang dipilih tidak ditemukan.',
    'details.*.qty.required' => 'Jumlah barang wajib diisi.',
    'details.*.qty.numeric' => 'Jumlah barang harus berupa angka.',
    'details.*.qty.min' => 'Jumlah barang minimal 0.01.',
    'details.*.harga_satuan.required' => 'Harga satuan wajib diisi.',
    'details.*.harga_satuan.numeric' => 'Harga satuan harus berupa angka.',
    'details.*.harga_satuan.min' => 'Harga satuan minimal 0.',
]);

        DB::beginTransaction();
        try {
            $transaksi = Transaksi::create([
                'tanggal' => $request->tanggal,
                'nomor' => $request->nomor,
                'customer' => $request->customer,
                'subtotal' => 0,
                'diskon' => 0,
                'ongkir' => $request->ongkir ?? 0,
                'total' => 0,
                'keterangan' => $request->keterangan,
                'user_id' => Auth::id(),
                'jum_print' => $request->jum_print ?? 0,
            ]);

            $totalSubtotal = 0;
            $totalDiskon = 0;

            foreach ($request->details as $detail) {
                $barang = Barang::find($detail['barang_id']);
                $itemSubtotal = $detail['qty'] * $detail['harga_satuan'];
                $itemDiscount = $detail['discount'] ?? 0;

                TransaksiDetail::create([
                    'transaksi_id' => $transaksi->id,
                    'barang_id' => $detail['barang_id'],
                    'kode_barang' => $barang->kode,
                    'nama_barang' => $barang->nama,
                    'qty' => $detail['qty'],
                    'harga_satuan' => $detail['harga_satuan'],
                    'discount' => $itemDiscount,
                    'subtotal' => $itemSubtotal - $itemDiscount,
                    'keterangan' => $detail['keterangan'] ?? null,
                ]);

                $totalSubtotal += $itemSubtotal;
                $totalDiskon += $itemDiscount;
            }

            $transaksi->update([
                'subtotal' => $totalSubtotal,
                'diskon' => $totalDiskon,
                'total' => $totalSubtotal - $totalDiskon + ($request->ongkir ?? 0),
            ]);

            DB::commit();
            return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil ditambahkan!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Gagal menambahkan transaksi: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Transaksi $transaksi)
    {
        $transaksi->load('details.barang');
        return view('transaksi.show', compact('transaksi'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transaksi $transaksi)
    {
        $transaksi->load('details.barang');
        $users = User::all();
        $barangs = Barang::all();
        return view('transaksi.edit', compact('transaksi', 'users', 'barangs'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Transaksi $transaksi)
    {
        $request->validate([
    'tanggal' => 'required|date',
    'nomor' => 'required|string|max:50|unique:transaksis,nomor,' . $transaksi->id,
    'customer' => 'required|string|max:255',
    'ongkir' => 'nullable|numeric|min:0',
    'keterangan' => 'nullable|string|max:1000',
    'jum_print' => 'nullable|integer|min:0',
    'details' => 'required|array|min:1',
    'details.*.barang_id' => 'required|exists:barangs,id',
    'details.*.qty' => 'required|numeric|min:0.01',
    'details.*.harga_satuan' => 'required|numeric|min:0',
], [
    'tanggal.required' => 'Tanggal wajib diisi.',
    'tanggal.date' => 'Format tanggal tidak valid.',
    'nomor.required' => 'Nomor transaksi wajib diisi.',
    'nomor.max' => 'Nomor transaksi maksimal 50 karakter.',
    'nomor.unique' => 'Nomor transaksi sudah digunakan.',
    'customer.required' => 'Nama customer wajib diisi.',
    'customer.max' => 'Nama customer maksimal 255 karakter.',
    'ongkir.numeric' => 'Ongkir harus berupa angka.',
    'ongkir.min' => 'Ongkir minimal 0.',
    'keterangan.max' => 'Keterangan maksimal 1000 karakter.',
    'jum_print.integer' => 'Jumlah print harus berupa bilangan bulat.',
    'jum_print.min' => 'Jumlah print minimal 0.',
    'details.required' => 'Detail transaksi wajib diisi.',
    'details.array' => 'Format detail transaksi tidak valid.',
    'details.min' => 'Minimal harus ada satu barang.',
    'details.*.barang_id.required' => 'Barang wajib dipilih.',
    'details.*.barang_id.exists' => 'Barang tidak ditemukan di database.',
    'details.*.qty.required' => 'Jumlah barang wajib diisi.',
    'details.*.qty.numeric' => 'Jumlah barang harus berupa angka.',
    'details.*.qty.min' => 'Jumlah barang minimal 0.01.',
    'details.*.harga_satuan.required' => 'Harga satuan wajib diisi.',
    'details.*.harga_satuan.numeric' => 'Harga satuan harus berupa angka.',
    'details.*.harga_satuan.min' => 'Harga satuan minimal 0.',
]);


        DB::beginTransaction();
        try {
            $transaksi->details()->delete();

            $totalSubtotal = 0;
            $totalDiskon = 0;

            foreach ($request->details as $detail) {
                $barang = Barang::find($detail['barang_id']);
                $itemSubtotal = $detail['qty'] * $detail['harga_satuan'];
                $itemDiscount = $detail['discount'] ?? 0;

                TransaksiDetail::create([
                    'transaksi_id' => $transaksi->id,
                    'barang_id' => $detail['barang_id'],
                    'kode_barang' => $barang->kode,
                    'nama_barang' => $barang->nama,
                    'qty' => $detail['qty'],
                    'harga_satuan' => $detail['harga_satuan'],
                    'discount' => $itemDiscount,
                    'subtotal' => $itemSubtotal - $itemDiscount,
                    'keterangan' => $detail['keterangan'] ?? null,
                ]);

                $totalSubtotal += $itemSubtotal;
                $totalDiskon += $itemDiscount;
            }

            $transaksi->update([
                'tanggal' => $request->tanggal,
                'nomor' => $request->nomor,
                'customer' => $request->customer,
                'subtotal' => $totalSubtotal,
                'diskon' => $totalDiskon,
                'ongkir' => $request->ongkir ?? 0,
                'total' => $totalSubtotal - $totalDiskon + ($request->ongkir ?? 0),
                'keterangan' => $request->keterangan,
                'user_id' => Auth::id(),
                'jum_print' => $request->jum_print ?? 0,
            ]);

            DB::commit();
            return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil diperbarui!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui transaksi: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaksi $transaksi)
    {
        DB::beginTransaction();
        try {
            $transaksi->details()->delete();
            $transaksi->delete();
            DB::commit();
            return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil dihapus!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menghapus transaksi: ' . $e->getMessage());
        }
    }

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
            Log::info('🚀 MULAI IMPORT', ['filename' => $file->getClientOriginalName()]);
            
            set_time_limit(300);
            ini_set('memory_limit', '512M');
            
            Excel::import(new TransaksiImport, $file);

            DB::commit();

            $transaksiCount = Transaksi::count();
            $detailCount = TransaksiDetail::count();

            Log::info('✅ IMPORT SELESAI', [
                'transaksi' => $transaksiCount,
                'detail' => $detailCount
            ]);

            // Check if request is AJAX
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => "🎉 IMPORT SELESAI! {$transaksiCount} transaksi dan {$detailCount} detail barang telah diimport.",
                    'data' => [
                        'transaksi_count' => $transaksiCount,
                        'detail_count' => $detailCount
                    ]
                ]);
            }

            return redirect()->back()->with(
                'success',
                "🎉 IMPORT SELESAI! {$transaksiCount} transaksi dan {$detailCount} detail barang telah diimport."
            );
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('❌ IMPORT GAGAL: ' . $e->getMessage(), [
                'file' => $file->getClientOriginalName() ?? 'unknown',
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            // Check if request is AJAX
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Import gagal: ' . $e->getMessage(),
                    'error' => $e->getMessage()
                ], 422);
            }

            return redirect()->back()
                ->withErrors(['file' => 'Error: ' . $e->getMessage()])
                ->withInput();
        }
    }

    public function clearData()
    {
        try {
            DB::beginTransaction();
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            TransaksiDetail::truncate();
            Transaksi::truncate();
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            DB::commit();
            return redirect()->back()->with('success', '🗑️ Semua data berhasil dihapus!');
        } catch (\Exception $e) {
            DB::rollBack();
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            return redirect()->back()->withErrors(['error' => 'Gagal hapus data: ' . $e->getMessage()]);
        }
    }
}
