<?php

namespace App\Http\Controllers;

use App\Models\hotel;
use App\Models\pelanggan;
use App\Models\rooms;
use Illuminate\Http\Request;

class HotelController extends Controller
{
    public function index()
    {
        $hotels = hotel::with('pelanggan')->get();
        return view('hotel.index', compact('hotels'));
    }

    public function create()
    {
        $rooms = rooms::all();
        $pelanggans = pelanggan::all();
        return view('hotel.create', compact('rooms', 'pelanggans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pelanggan_id' => 'required|exists:pelanggans,id',
            'room_id' => 'required|exists:rooms,id',
            'check_in' => 'required|date',
            'check_out' => 'required|date|after:check_in',
        ], [
            'pelanggan_id.required' => 'ID Pelanggan tidak boleh kosong',
            'pelanggan_id.exists' => 'ID Pelanggan tidak ditemukan',
            'room_id.required' => 'Kamar tersedia harus dipilih',
            'room_id.exists' => 'Kamar tidak ditemukan',
            'check_in.required' => 'Tanggal Check In tidak boleh kosong',
            'check_out.required' => 'Tanggal Check Out tidak boleh kosong',
            'check_out.after' => 'Tanggal Check Out harus setelah Tanggal Check In',
        ]);

        // Ambil data kamar
        $room = rooms::findOrFail($request->room_id);
        if ($room->stock <= 0) {
            return back()->withErrors(['room_id' => 'Kamar tidak tersedia!'])->withInput();
        }

        // Kurangi stock kamar
        $room->decrement('stock');

        hotel::create($request->all());
        return redirect()->route('hotel.index')->with('success', 'Data hotel berhasil ditambahkan');
    }

    public function edit($id)
    {
        $hotel = hotel::findOrFail($id);
        $pelanggans = pelanggan::all();
        $room_id = ['single', 'double', 'suite'];
        return view('hotel.edit', compact('hotel', 'pelanggans', 'room_id'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'pelanggan_id' => 'required|exists:pelanggans,id',
            'room_id' => 'required|exists:rooms,id',
            'check_in' => 'required|date',
            'check_out' => 'required|date|after:check_in',
        ], [
            'pelanggan_id.required' => 'ID Pelanggan tidak boleh kosong',
            'pelanggan_id.exists' => 'ID Pelanggan tidak ditemukan',
            'room_id.required' => 'Kamar tersedia harus dipilih',
            'room_id.exists' => 'Kamar tidak ditemukan',
            'check_in.required' => 'Tanggal Check In tidak boleh kosong',
            'check_out.required' => 'Tanggal Check Out tidak boleh kosong',
            'check_out.after' => 'Tanggal Check Out harus setelah Tanggal Check In',
        ]);

        $hotel = hotel::findOrFail($id);
        $hotel->update($request->all());
        return redirect()->route('hotel.index')->with('success', 'Data hotel berhasil diupdate');
    }

    public function destroy($id)
    {
        $hotel = hotel::findOrFail($id);

        // Tambahkan kembali stock kamar
        $room = $hotel->room;
        if ($room) {
            $room->increment('stock');
        }

        $hotel->delete();
        return redirect()->route('hotel.index')->with('success', 'Data hotel berhasil dihapus');
    }

    public function checkout($hotelId)
    {
        $hotel = hotel::findOrFail($hotelId);
        $room = $hotel->room;
        if ($room->stock > 0) {
            $room->decrement('stock');
        }
        // lanjutkan proses checkout...
    }
}
