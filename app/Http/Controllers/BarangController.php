<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\User; // Pastikan model User diimport
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Untuk mendapatkan user yang sedang login

class BarangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $barangs = Barang::with('user')->orderBy('nama')->paginate(10);
        return view('barang.index', compact('barangs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Anda bisa mengirimkan daftar user jika ingin memilih user_id_fk dari dropdown
        // $users = User::all();
        return view('barang.create'); // , compact('users')
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'kode' => 'required|string|max:50|unique:barangs,kode',
            'nama' => 'required|string|max:255',
            'does_pcs' => 'required|numeric|min:0.01',
            'golongan' => 'nullable|string|max:255',
            'hbeli' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string|max:1000',
        ]);

        Barang::create([
            'kode' => $request->kode,
            'nama' => $request->nama,
            'does_pcs' => $request->does_pcs,
            'golongan' => $request->golongan,
            'hbeli' => $request->hbeli,
            'user_id' => Auth::check() ? Auth::user()->name : 'system', // Mengambil nama user
            'user_id_fk' => Auth::id() ?? null, // Mengambil ID user yang login
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->route('barang.index')->with('success', 'Barang berhasil ditambahkan!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Barang $barang)
    {
        // $users = User::all();
        return view('barang.edit', compact('barang')); // , compact('barang', 'users')
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Barang $barang)
    {
        $request->validate([
            'kode' => 'required|string|max:50|unique:barangs,kode,' . $barang->id,
            'nama' => 'required|string|max:255',
            'does_pcs' => 'required|numeric|min:0.01',
            'golongan' => 'nullable|string|max:255',
            'hbeli' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string|max:1000',
        ]);

        $barang->update([
            'kode' => $request->kode,
            'nama' => $request->nama,
            'does_pcs' => $request->does_pcs,
            'golongan' => $request->golongan,
            'hbeli' => $request->hbeli,
            'user_id' => Auth::check() ? Auth::user()->name : 'system', // Mengambil nama user
            'user_id_fk' => Auth::id() ?? null, // Mengambil ID user yang login
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->route('barang.index')->with('success', 'Barang berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Barang $barang)
    {
        try {
            $barang->delete();
            return redirect()->route('barang.index')->with('success', 'Barang berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->route('barang.index')->with('error', 'Gagal menghapus barang: ' . $e->getMessage());
        }
    }
}
