@extends('layouts.demo')

@section('content')
    <div class="">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h1>Booking</h1>
                <a href="{{ route('booking.index') }}" class="btn btn-primary">Kembali</a>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th colspan="2" class="text-center">Data Booking</th>
                    </tr>
                    <tr>
                        <th>Nama Pasien</th>
                        <td>{{ $booking->nama_pasien }}</td>
                    </tr>
                    <tr>
                        <th>Dokter</th>
                        <td>{{ $booking->dokter->nama_dokter }}</td>
                    </tr>
                    <tr>
                        <th>Hari</th>
                        <td>{{ $booking->hari }}</td>
                    </tr>
                    <tr>
                        <th>Jam Awal Praktik</th>
                        <td>{{ \Carbon\Carbon::parse($booking->jam_awal_praktik)->translatedFormat('d F Y') }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
@endsection