<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\User;
use App\Models\Barang;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class TransaksiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $transaksis = Transaksi::with('user')->orderBy('tanggal', 'desc')->paginate(10);
        return view('transaksi.index', compact('transaksis'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::all();
        $barangs = Barang::all(); // For selecting items in transaction details
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
            'ongkos_kirim' => 'nullable|numeric|min:0',
            'keterangan' => 'nullable|string|max:1000',
            'jum_print' => 'nullable|integer|min:0',
            'details' => 'required|array|min:1',
            'details.*.barang_id' => 'required|exists:barangs,id',
            'details.*.quantity' => 'required|numeric|min:0.01',
            'details.*.price' => 'required|numeric|min:0',
            'details.*.discount' => 'nullable|numeric|min:0',
            'details.*.keterangan' => 'nullable|string|max:1000',
        ]);

        DB::beginTransaction();
        try {
            $transaksi = Transaksi::create([
                'tanggal' => $request->tanggal,
                'nomor' => $request->nomor,
                'customer' => $request->customer,
                'subtotal' => 0, // Will be calculated from details
                'disc' => 0,     // Will be calculated from details
                'ongkos_kirim' => $request->ongkos_kirim ?? 0,
                'total' => 0,    // Will be calculated from details
                'keterangan' => $request->keterangan,
                'tgl_input' => now(),
                'user_id' => Auth::check() ? Auth::user()->name : 'system',
                'user_id_fk' => Auth::id() ?? null,
                'jum_print' => $request->jum_print ?? 0,
            ]);

            $totalSubtotal = 0;
            $totalDiscount = 0;

            foreach ($request->details as $detailData) {
                $itemSubtotal = $detailData['quantity'] * $detailData['price'];
                $itemDiscount = $detailData['discount'] ?? 0;
                $itemTotalAfterDiscount = $itemSubtotal - $itemDiscount;

                TransactionDetail::create([
                    'transaksi_id' => $transaksi->id,
                    'barang_id' => $detailData['barang_id'],
                    'quantity' => $detailData['quantity'],
                    'price' => $detailData['price'],
                    'discount' => $itemDiscount,
                    'subtotal' => $itemTotalAfterDiscount,
                    'keterangan' => $detailData['keterangan'],
                ]);

                $totalSubtotal += $itemSubtotal;
                $totalDiscount += $itemDiscount;
            }

            $transaksi->subtotal = $totalSubtotal;
            $transaksi->disc = $totalDiscount;
            $transaksi->total = $totalSubtotal - $totalDiscount + ($request->ongkos_kirim ?? 0);
            $transaksi->save();

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
            'nomor' => [
                'required',
                'string',
                'max:50',
                Rule::unique('transaksis')->ignore($transaksi->id),
            ],
            'customer' => 'required|string|max:255',
            'ongkos_kirim' => 'nullable|numeric|min:0',
            'keterangan' => 'nullable|string|max:1000',
            'jum_print' => 'nullable|integer|min:0',
            'details' => 'required|array|min:1',
            'details.*.barang_id' => 'required|exists:barangs,id',
            'details.*.quantity' => 'required|numeric|min:0.01',
            'details.*.price' => 'required|numeric|min:0',
            'details.*.discount' => 'nullable|numeric|min:0',
            'details.*.keterangan' => 'nullable|string|max:1000',
        ]);

        DB::beginTransaction();
        try {
            // Delete existing details
            $transaksi->details()->delete();

            $totalSubtotal = 0;
            $totalDiscount = 0;

            foreach ($request->details as $detailData) {
                $itemSubtotal = $detailData['quantity'] * $detailData['price'];
                $itemDiscount = $detailData['discount'] ?? 0;
                $itemTotalAfterDiscount = $itemSubtotal - $itemDiscount;

                TransactionDetail::create([
                    'transaksi_id' => $transaksi->id,
                    'barang_id' => $detailData['barang_id'],
                    'quantity' => $detailData['quantity'],
                    'price' => $detailData['price'],
                    'discount' => $itemDiscount,
                    'subtotal' => $itemTotalAfterDiscount,
                    'keterangan' => $detailData['keterangan'],
                ]);

                $totalSubtotal += $itemSubtotal;
                $totalDiscount += $itemDiscount;
            }

            $transaksi->update([
                'tanggal' => $request->tanggal,
                'nomor' => $request->nomor,
                'customer' => $request->customer,
                'subtotal' => $totalSubtotal,
                'disc' => $totalDiscount,
                'ongkos_kirim' => $request->ongkos_kirim ?? 0,
                'total' => $totalSubtotal - $totalDiscount + ($request->ongkos_kirim ?? 0),
                'keterangan' => $request->keterangan,
                'tgl_input' => now(),
                'user_id' => Auth::check() ? Auth::user()->name : 'system',
                'user_id_fk' => Auth::id() ?? null,
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
            $transaksi->details()->delete(); // Delete associated details first
            $transaksi->delete();
            DB::commit();
            return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil dihapus!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menghapus transaksi: ' . $e->getMessage());
        }
    }
}
