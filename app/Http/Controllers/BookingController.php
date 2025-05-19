<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Dokter;

class BookingController extends Controller
{
    public function index()
    {
        $dokters = Dokter::all();
        $bookings = Booking::all();
        return view('booking.index', compact('bookings', 'dokters'));
    }
    public function create()
    {
        $dokters = Dokter::all();
        return view('booking.create', compact('dokters'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'nama_pasien' => 'required',
            'dokter_id' => 'required',
            'hari' => 'required',
            'jam_awal_praktik' => 'required|date',
        ], [
            'nama_pasien.required' => 'Nama Pasien harus diisi',
            'dokter_id.required' => 'Dokter harus diisi',
            'hari.required' => 'Hari harus diisi',
            'jam_awal_praktik.required' => 'Jam Awal Praktik harus diisi',
            'jam_awal_praktik.date' => 'Format jam harus YYYY-MM-DD',
        ]);

        // Ambil data dokter
        $dokter = Dokter::find($request->dokter_id);

        // Validasi hari dan tanggal booking harus sesuai jadwal dokter
        if (
            !$dokter ||
            $request->hari !== $dokter->hari ||
            $request->jam_awal_praktik < $dokter->jam_awal_praktik ||
            $request->jam_awal_praktik > $dokter->jam_akhir_praktik
        ) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['jam_awal_praktik' => 'Booking hanya bisa dilakukan pada hari dan tanggal sesuai jadwal dokter!']);
        }

        // Cek apakah sudah ada booking dengan dokter, hari, dan tanggal yang sama
        $exists = Booking::where('dokter_id', $request->dokter_id)
            ->where('hari', $request->hari)
            ->where('jam_awal_praktik', $request->jam_awal_praktik)
            ->exists();

        if ($exists) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['jam_awal_praktik' => 'Jadwal dokter pada hari dan tanggal ini sudah di-booking!']);
        }

        // Jika belum ada, baru simpan booking
        Booking::create($request->all());

        // Update status dokter menjadi 'booking'
        $dokter->status = 'booking';
        $dokter->save();

        return redirect()->route('booking.index')->with('success', 'Booking berhasil ditambahkan');
    }
    public function edit(Booking $booking)
    {
        $dokters = Dokter::all();
        return view('booking.edit', compact('booking', 'dokters'));
    }
    public function update(Request $request, Booking $booking)
    {
        $request->validate([
            'nama_pasien' => 'required',
            'dokter_id' => 'required',
            'hari' => 'required',
            'jam_awal_praktik' => 'required|date',
        ], [
            'nama_pasien.required' => 'Nama Pasien harus diisi',
            'dokter_id.required' => 'Dokter harus diisi',
            'hari.required' => 'Hari harus diisi',
            'jam_awal_praktik.required' => 'Jam Awal Praktik harus diisi',
            'jam_awal_praktik.date' => 'Format jam harus YYYY-MM-DD',
        ]);

        // Ambil data dokter
        $dokter = Dokter::find($request->dokter_id);

        // Validasi hari dan tanggal booking harus sesuai jadwal dokter
        if (
            !$dokter ||
            $request->hari !== $dokter->hari ||
            $request->jam_awal_praktik < $dokter->jam_awal_praktik ||
            $request->jam_awal_praktik > $dokter->jam_akhir_praktik
        ) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['jam_awal_praktik' => 'Booking hanya bisa dilakukan pada hari dan tanggal sesuai jadwal dokter!']);
        }

        // Validasi booking bentrok, abaikan booking yang sedang diedit
        $exists = Booking::where('dokter_id', $request->dokter_id)
            ->where('hari', $request->hari)
            ->where('jam_awal_praktik', $request->jam_awal_praktik)
            ->where('id', '!=', $booking->id)
            ->exists();

        if ($exists) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['jam_awal_praktik' => 'Jadwal dokter pada hari dan tanggal ini sudah di-booking!']);
        }

        $dokterLamaId = $booking->dokter_id;
        $booking->update($request->all());

        // Update status dokter baru
        $adaBooking = Booking::where('dokter_id', $request->dokter_id)
            ->where('hari', $request->hari)
            ->where('jam_awal_praktik', $request->jam_awal_praktik)
            ->exists();

        if ($dokter) {
            $dokter->status = $adaBooking ? 'booking' : 'offline';
            $dokter->save();
        }

        // Update status dokter lama jika dokter diganti
        if ($dokterLamaId != $request->dokter_id) {
            $adaBookingLama = Booking::where('dokter_id', $dokterLamaId)->exists();
            $dokterLama = Dokter::find($dokterLamaId);
            if ($dokterLama && !$adaBookingLama) {
                $dokterLama->status = 'offline';
                $dokterLama->save();
            }
        }

        return redirect()->route('booking.index')->with('success', 'Booking berhasil diubah');
    }
    public function destroy(Booking $booking)
    {
        // Ambil dokter terkait
        $dokter = Dokter::find($booking->dokter_id);

        // Hapus booking
        $booking->delete();

        // Kembalikan status dokter ke 'offline'
        if ($dokter) {
            $dokter->status = 'offline';
            $dokter->save();
        }

        return redirect()->route('booking.index')->with('danger', 'Booking berhasil dihapus');
    }
    public function show(Booking $booking)
    {
        $dokters = Dokter::all();
        return view('booking.show', compact('booking', 'dokters'));
    }
}
