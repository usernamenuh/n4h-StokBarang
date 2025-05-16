<?php

namespace App\Http\Controllers;

use App\Models\pelanggan;
use Illuminate\Http\Request;

class PelangganController extends Controller
{
    public function index()
    {
        $pelanggans = pelanggan::all();
        return view('pelanggan.index', compact('pelanggans'));
    }

    public function create()
    {
        return view('pelanggan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_pelanggan' => 'required|unique:pelanggans',
            'nama' => 'required',
            'alamat' => 'required',
            'telepon' => 'required',
        ], [
            'id_pelanggan.required' => 'ID Pelanggan tidak boleh kosong',
            'id_pelanggan.unique' => 'ID Pelanggan sudah ada',
            'nama.required' => 'Nama tidak boleh kosong',
            'alamat.required' => 'Alamat tidak boleh kosong',
            'telepon.required' => 'Telepon tidak boleh kosong',
        ]);

        pelanggan::create($request->all());
        return redirect()->route('pelanggan.index')->with('success', 'Pelanggan berhasil ditambahkan');
    }

    public function edit($id)
    {
        $pelanggan = pelanggan::findOrFail($id);
        return view('pelanggan.edit', compact('pelanggan'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'id_pelanggan' => 'required|unique:pelanggans,id_pelanggan,' . $id,
            'nama' => 'required',
            'alamat' => 'required',
            'telepon' => 'required',
        ], [
            'id_pelanggan.required' => 'ID Pelanggan tidak boleh kosong',
            'id_pelanggan.unique' => 'ID Pelanggan sudah ada',
            'nama.required' => 'Nama tidak boleh kosong',
            'alamat.required' => 'Alamat tidak boleh kosong',
            'telepon.required' => 'Telepon tidak boleh kosong',
        ]);

        $pelanggan = pelanggan::findOrFail($id);
        $pelanggan->update($request->all());
        return redirect()->route('pelanggan.index')->with('success', 'Pelanggan berhasil diupdate');
    }

    public function destroy($id)
    {
        $pelanggan = pelanggan::findOrFail($id);
        $pelanggan->delete();
        return redirect()->route('pelanggan.index')->with('success', 'Pelanggan berhasil dihapus');
    }
}
