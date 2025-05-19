<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mobil;
class MobilController extends Controller
{
    public function index()
    {
        $mobils = Mobil::all();
        return view('mobil.index', compact('mobils'));
    }

    public function create()
    {
        return view('mobil.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nomor_polisi' => 'required|unique:mobils',
            'type_kendaraan' => 'required',
        ], [
            'nomor_polisi.required' => 'Nomor polisi harus diisi',
            'nomor_polisi.unique' => 'Nomor polisi sudah ada',
            'type_kendaraan.required' => 'Type kendaraan harus diisi',
        ]);
        Mobil::create($request->all());
        return redirect()->route('mobil.index')->with('success', 'Mobil berhasil ditambahkan');
    }

    public function show(Mobil $mobil)
    {
        return view('mobil.show', compact('mobil'));
    }

    public function edit(Mobil $mobil)
    {
        return view('mobil.edit', compact('mobil'));
    }

    public function update(Request $request, Mobil $mobil)
    {
        $request->validate([
            'nomor_polisi' => 'required|unique:mobils,nomor_polisi,' . $mobil->id,
            'type_kendaraan' => 'required',
        ], [
            'nomor_polisi.required' => 'Nomor polisi harus diisi',
            'nomor_polisi.unique' => 'Nomor polisi sudah ada',
            'type_kendaraan.required' => 'Type kendaraan harus diisi',
        ]);
        $mobil->update($request->all());
        return redirect()->route('mobil.index')->with('success', 'Mobil berhasil diubah');
    }

    public function destroy(Mobil $mobil)
    {
        $mobil->delete();
        return redirect()->route('mobil.index')->with('success', 'Mobil berhasil dihapus');
    }
}
