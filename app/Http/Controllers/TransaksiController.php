<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\transaksi;
use App\Models\Barang;

class TransaksiController extends Controller
{
    public function index()
    {
        $transaksis = transaksi::orderBy('created_at', 'desc')->get();
        return view('transaksi.index', compact('transaksis'));
    }
    public function create()
    {
        $transaksi = transaksi::all();
        $barangs = Barang::all();
        return view('transaksi.create', compact('transaksi', 'barangs'));
    }
    public function store(Request $request)
    {
        $barang = Barang::findOrFail($request->id_barang);

        $request->validate([
            'id_barang' => 'required|exists:barangs,id',
            'quantity' => [
                'required',
                'integer',
                'min:1',
                function ($attribute, $value, $fail) use ($barang) {
                    if ($value > $barang->stok) {
                        $fail('Stok tidak cukup! Stok tersedia: ' . $barang->stok);
                    }
                }
            ],
            'total' => 'required',
        ], [
            'id_barang.required' => 'Barang tidak boleh kosong',
            'quantity.required' => 'Quantity tidak boleh kosong',
            'quantity.integer' => 'Quantity harus berupa angka',
            'quantity.min' => 'Quantity tidak boleh kurang dari 1',
            'total.required' => 'Total tidak boleh kosong',
            'total.string' => 'Total harus berupa string',
        ]);

        $barang->stok -= $request->quantity;
        $barang->save();
        $total = $barang->harga * $request->quantity;
        $request->merge(['total' => $total]);
        transaksi::create($request->all());
        return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil ditambahkan');
    }
    public function edit($id)
    {
        $transaksi = transaksi::find($id);
        $barangs = Barang::all();
        return view('transaksi.edit', compact('transaksi', 'barangs'));
    }
    public function update(Request $request, $id)
    {
        $transaksi = transaksi::find($id);
        $barangLama = Barang::find($transaksi->id_barang);

        // Kembalikan stok lama
        $barangLama->stok += $transaksi->quantity;
        $barangLama->save();

        $barangBaru = Barang::find($request->id_barang);

        $request->validate([
            'id_barang' => 'required|exists:barangs,id',
            'quantity' => [
                'required',
                'integer',
                'min:1',
                function ($attribute, $value, $fail) use ($barangBaru) {
                    if ($value > $barangBaru->stok) {
                        $fail('Stok tidak cukup! Stok tersedia: ' . $barangBaru->stok);
                    }
                }
            ],
            'total' => 'required|string',
        ], [
            'id_barang.required' => 'Barang tidak boleh kosong',
            'quantity.required' => 'Quantity tidak boleh kosong',
            'quantity.integer' => 'Quantity harus berupa angka',
            'quantity.min' => 'Quantity tidak boleh kurang dari 1',
            'total.required' => 'Total tidak boleh kosong',
            'total.string' => 'Total harus berupa string',
        ]);

        // Kurangi stok baru
        $barangBaru->stok -= $request->quantity;
        $barangBaru->save();

        $total = $barangBaru->harga * $request->quantity;
        $request->merge(['total' => $total]);
        $transaksi->update($request->all());

        return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil diubah');
    }
    public function destroy($id)
    {
        $transaksi = transaksi::find($id);
        $barang = Barang::find($transaksi->id_barang);
        $barang->stok += $transaksi->quantity;
        $barang->save();
        $transaksi->delete();
        return redirect()->route('transaksi.index')->with('danger', 'Transaksi berhasil dihapus');
    }
    public function show($id)
    {
        $transaksi = transaksi::find($id);
        return view('transaksi.show', compact('transaksi'));
    }
}
