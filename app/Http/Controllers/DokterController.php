<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dokter;
use Carbon\Carbon;

class DokterController extends Controller
{
    public function index()
    {
        $dokters = Dokter::all();
        return view('dokter.index', compact('dokters'));
    }
    public function create()
    {
        return view('dokter.create');
    }
    public function store(Request $request)
    {
        $request->validate([
            'nama_dokter' => 'required',
            'spesialis' => 'required',
            'hari' => 'required',
            'jam_awal_praktik' => 'required|date',
            'jam_akhir_praktik' => 'required|date|after:jam_awal_praktik',
        ], [
            'nama_dokter.required' => 'Nama Dokter harus diisi',
            'spesialis.required' => 'Spesialis harus diisi',
            'hari.required' => 'Hari harus diisi',
            'jam_awal_praktik.required' => 'Jam Awal Praktik harus diisi',
            'jam_awal_praktik.date' => 'Format jam harus YYYY-MM-DD',
            'jam_akhir_praktik.required' => 'Jam Akhir Praktik harus diisi',
            'jam_akhir_praktik.date' => 'Format jam harus YYYY-MM-DD',
            'jam_akhir_praktik.after' => 'Jam Akhir Praktik harus lebih besar dari Jam Awal Praktik',
        ]);
        Dokter::create($request->all());
        return redirect()->route('dokter.index')->with('success', 'Dokter berhasil ditambahkan');
    }
    public function edit(Dokter $dokter)
    {
        return view('dokter.edit', compact('dokter'));
    }
    public function update(Request $request, Dokter $dokter)
    {
        $request->validate([
            'nama_dokter' => 'required',
            'spesialis' => 'required',
            'hari' => 'required',
            'jam_awal_praktik' => 'required|date',
            'jam_akhir_praktik' => 'required|date',
        ], [
            'nama_dokter.required' => 'Nama Dokter harus diisi',
            'spesialis.required' => 'Spesialis harus diisi',
            'hari.required' => 'Hari harus diisi',
            'jam_awal_praktik.required' => 'Jam Awal Praktik harus diisi',
            'jam_awal_praktik.date' => 'Format jam harus YYYY-MM-DD',
            'jam_akhir_praktik.required' => 'Jam Akhir Praktik harus diisi',
            'jam_akhir_praktik.date' => 'Format jam harus YYYY-MM-DD',
        ]);
        $dokter->update($request->all());
        return redirect()->route('dokter.index')->with('success', 'Dokter berhasil diubah');
    }
    public function destroy(Dokter $dokter)
    {
        $dokter->delete();
        return redirect()->route('dokter.index')->with('danger', 'Dokter berhasil dihapus');
    }
    public function show(Dokter $dokter)
    {
        return view('dokter.show', compact('dokter'));
    }
}
