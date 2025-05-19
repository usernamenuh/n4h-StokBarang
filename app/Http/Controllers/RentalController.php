<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mobil;
use App\Models\Rental;

class RentalController extends Controller
{
    public function index()
    {
        $mobils = Mobil::all();
        $rentals = Rental::all();
        return view('rental.index', compact('rentals', 'mobils'));
    }

    public function create()
    {
        $mobils = Mobil::all();
        return view('rental.create', compact('mobils'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'mobil_id' => 'required',
            'tanggal_awal_sewa' => 'required|date',
            'tanggal_akhir_sewa' => 'required|date|after_or_equal:tanggal_awal_sewa',
        ], [
            'mobil_id.required' => 'Mobil harus diisi',
            'tanggal_awal_sewa.required' => 'Tanggal awal sewa harus diisi',
            'tanggal_awal_sewa.date' => 'Tanggal awal sewa tidak valid',
            'tanggal_akhir_sewa.required' => 'Tanggal akhir sewa harus diisi',
            'tanggal_akhir_sewa.date' => 'Tanggal akhir sewa tidak valid',
            'tanggal_akhir_sewa.after_or_equal' => 'Tanggal akhir sewa harus setelah atau sama dengan tanggal awal sewa',
        ]);

        if (!\App\Models\Rental::isMobilAvailable($request->mobil_id, $request->tanggal_awal_sewa, $request->tanggal_akhir_sewa)) {
            return back()
                ->withInput()
                ->with('danger', 'Mobil sedang dirental pada tanggal tersebut!');
        }

        $rental = Rental::create($request->all());
        $mobil = Mobil::find($request->mobil_id);
        if ($mobil) {
            $mobil->status = 'dirental';
            $mobil->save();
        }
        return redirect()->route('rental.index')->with('success', 'Rental berhasil ditambahkan');
    }

    public function show(Rental $rental)
    {
        $mobils = Mobil::all();
        return view('rental.show', compact('rental', 'mobils'));
    }

    public function edit(Rental $rental)
    {
        $mobils = Mobil::all();
        return view('rental.edit', compact('rental', 'mobils'));
    }

    public function update(Request $request, Rental $rental)
    {
        $request->validate([
            'mobil_id' => 'required',
            'tanggal_awal_sewa' => 'required|date',
            'tanggal_akhir_sewa' => 'required|date|after_or_equal:tanggal_awal_sewa',
        ], [
            'mobil_id.required' => 'Mobil harus diisi',
            'tanggal_awal_sewa.required' => 'Tanggal awal sewa harus diisi',
            'tanggal_awal_sewa.date' => 'Tanggal awal sewa tidak valid',
            'tanggal_akhir_sewa.required' => 'Tanggal akhir sewa harus diisi',
            'tanggal_akhir_sewa.date' => 'Tanggal akhir sewa tidak valid',
            'tanggal_akhir_sewa.after_or_equal' => 'Tanggal akhir sewa harus setelah atau sama dengan tanggal awal sewa',
        ]);

        if (!\App\Models\Rental::isMobilAvailable($request->mobil_id, $request->tanggal_awal_sewa, $request->tanggal_akhir_sewa)) {
            return back()
                ->withInput()
                ->with('danger', 'Mobil sedang dirental pada tanggal tersebut!');
        }

        // Update status mobil jika diganti
        if ($rental->mobil_id != $request->mobil_id) {
            $mobilLama = Mobil::find($rental->mobil_id);
            if ($mobilLama) {
                $mobilLama->status = 'tersedia';
                $mobilLama->save();
            }
            $mobilBaru = Mobil::find($request->mobil_id);
            if ($mobilBaru) {
                $mobilBaru->status = 'dirental';
                $mobilBaru->save();
            }
        }
        $rental->update($request->all());
        return redirect()->route('rental.index')->with('success', 'Rental berhasil diubah');
    }

    public function destroy(Rental $rental)
    {
        // Ubah status mobil jadi tersedia
        $mobil = Mobil::find($rental->mobil_id);
        if ($mobil) {
            $mobil->status = 'tersedia';
            $mobil->save();
        }
        $rental->delete();
        return redirect()->route('rental.index')->with('danger', 'Rental berhasil dihapus');
    }
}
