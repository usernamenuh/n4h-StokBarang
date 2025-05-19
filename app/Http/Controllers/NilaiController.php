<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\nilai;
class NilaiController extends Controller
{
    public function index()
    {
        $nilais = nilai::all();
        return view('nilai.index', compact('nilais'));
    }

    public function create()
    {
        return view('nilai.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_mahasiswa' => 'required|string|unique:nilais,nama_mahasiswa',
            'nim' => 'required|unique:nilais,nim',
            'tugas' => 'required|numeric',
            'kehadiran' => 'required|numeric',
            'quiz' => 'required|numeric',
            'uts' => 'required|numeric',
            'uas' => 'required|numeric',
        ],[
            'nama_mahasiswa.required' => 'Nama Mahasiswa harus diisi',
            'nama_mahasiswa.string' => 'Nama Mahasiswa harus berupa string',
            'nama_mahasiswa.unique' => 'Nama Mahasiswa sudah ada',
            'nim.required' => 'NIM harus diisi',
            'nim.unique' => 'NIM sudah ada',
            'tugas.required' => 'Tugas harus diisi',
            'tugas.numeric' => 'Tugas harus berupa angka',
            'kehadiran.required' => 'Kehadiran harus diisi',
            'kehadiran.numeric' => 'Kehadiran harus berupa angka',
            'quiz.required' => 'Quiz harus diisi',
            'quiz.numeric' => 'Quiz harus berupa angka',
            'uts.required' => 'UTS harus diisi',
            'uts.numeric' => 'UTS harus berupa angka',
            'uas.required' => 'UAS harus diisi',
            'uas.numeric' => 'UAS harus berupa angka',
        ]);

        // Hitung rata-rata
        $rata_rata = (
            $request->tugas +
            $request->kehadiran +
            $request->quiz +
            $request->uts +
            $request->uas
        ) / 5;

        $nilai = new nilai();
        $nilai->nama_mahasiswa = $request->nama_mahasiswa;
        $nilai->nim = $request->nim;
        $nilai->nilai_rata_rata = $rata_rata;
        $nilai->save();

        return redirect()->route('nilai.index')->with('success', 'Nilai berhasil ditambahkan');
    }

    public function show($id)
    {
        $nilai = nilai::find($id);
        return view('nilai.show', compact('nilai'));
    }

    public function edit($id)
    {
        $nilai = nilai::find($id);
        return view('nilai.edit', compact('nilai'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_mahasiswa' => 'required|string|unique:nilais,nama_mahasiswa,' . $id,
            'nim' => 'required|unique:nilais,nim,' . $id,
            'nilai_rata_rata' => 'required|numeric',
        ], [
            'nama_mahasiswa.required' => 'Nama Mahasiswa harus diisi', 
            'nama_mahasiswa.string' => 'Nama Mahasiswa harus berupa string',
            'nama_mahasiswa.unique' => 'Nama Mahasiswa sudah ada',
            'nim.required' => 'NIM harus diisi',
            'nim.unique' => 'NIM sudah ada',
            'nilai_rata_rata.required' => 'Nilai Rata-rata harus diisi',
            'nilai_rata_rata.numeric' => 'Nilai Rata-rata harus berupa angka',
        ]);

        $nilai = nilai::find($id);
        $nilai->nama_mahasiswa = $request->nama_mahasiswa;
        $nilai->nim = $request->nim;
        $nilai->nilai_rata_rata = $request->nilai_rata_rata;
        $nilai->save();
        return redirect()->route('nilai.index')->with('success', 'Nilai berhasil diubah');
    }

    public function destroy($id)
    {
        $nilai = nilai::find($id);
        $nilai->delete();
        return redirect()->route('nilai.index')->with('success', 'Nilai berhasil dihapus');
    }
}