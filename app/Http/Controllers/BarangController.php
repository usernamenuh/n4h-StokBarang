<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;

class BarangController extends Controller
{
    public function index()
    {
         $barangTerlaris = Barang::withSum('transaksis as total_terjual', 'quantity')
        ->orderByDesc('total_terjual')
        ->take(10)
        ->get();
        $barangs = Barang::orderBy('nama_barang', 'asc')->get();
       
    return view('barang.index', compact('barangs', 'barangTerlaris'));

    }
    public function create()
    {
        $barangs = Barang::all();
        return view('barang.create', compact('barangs'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:255|unique:barangs,nama_barang,' . $request->id,
            'kode_barang' => 'required|unique:barangs,kode_barang',
            'stok' => 'required|integer',
            'harga' => 'required|integer',
        ], [
            'nama_barang.unique' => 'Nama barang sudah terdaftar',
            'nama_barang.max' => 'Nama barang maksimal 255 karakter',
            'nama_barang.required' => 'Nama barang tidak boleh kosong',
            'kode_barang.required' => 'Kode barang tidak boleh kosong',
            'kode_barang.unique' => 'Kode barang sudah terdaftar',
            'stok.required' => 'Stok tidak boleh kosong',
            'stok.integer' => 'Stok harus berupa angka',
            'harga.required' => 'Harga tidak boleh kosong',
            'harga.integer' => 'Harga harus berupa angka',
        ]);
        Barang::create($request->all());
        return redirect()->route('barang.index')->with('success', 'Barang berhasil ditambahkan');
    }
    public function edit($id)
    {
        $barang = Barang::findOrFail($id);
        return view('barang.edit', compact('barang'));
    }
    public function update(Request $request, Barang $barang)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'kode_barang' => 'required|unique:barangs,kode_barang,' . $barang->id,
            'stok' => 'required|integer',
            'harga' => 'required|integer',
        ], [
            'nama_barang.unique' => 'Nama barang sudah terdaftar',
            'nama_barang.max' => 'Nama barang maksimal 255 karakter',
            'nama_barang.required' => 'Nama barang tidak boleh kosong',
            'kode_barang.required' => 'Kode barang tidak boleh kosong',
            'kode_barang.unique' => 'Kode barang sudah terdaftar',
            'stok.required' => 'Stok tidak boleh kosong',
            'stok.integer' => 'Stok harus berupa angka',
            'harga.required' => 'Harga tidak boleh kosong',
            'harga.integer' => 'Harga harus berupa angka',
        ]);
        $barang->update($request->all());
        return redirect()->route('barang.index')->with('success', 'Barang berhasil diubah');
    }
    public function destroy(Barang $barang)
    {
        if ($barang->delete()) {
            return redirect()->route('barang.index')->with('danger', 'Barang berhasil dihapus');
        } else {
            return redirect()->route('barang.index')->with('error', 'Barang gagal dihapus');
        }
    }
    public function show(Barang $barang)
    {
        $barang = Barang::findOrFail($barang->id);
        return view('barang.show', compact('barang'));
    }
}
