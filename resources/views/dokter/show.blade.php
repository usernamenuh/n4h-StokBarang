@extends('layouts.demo')

@section('content')
    <div class="">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h1>Dokter</h1>
                <a href="{{ route('dokter.index') }}" class="btn btn-primary">Kembali</a>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th colspan="2" class="text-center">Data Dokter</th>
                    </tr>
                    <tr>
                        <th>Nama Dokter</th>
                        <td>{{ $dokter->nama_dokter }}</td>
                    </tr>
                    <tr>
                        <th>Spesialis</th>
                        <td>{{ $dokter->spesialis }}</td>
                    </tr>
                    <tr>
                        <th>Hari</th>
                        <td>{{ $dokter->hari }}</td>
                    </tr>
                    <tr>
                        <th>Jam Awal Praktik</th>
                        <td>{{ $dokter->jam_awal_praktik }}</td>
                    </tr>
                    <tr>
                        <th>Jam Akhir Praktik</th>
                        <td>{{ $dokter->jam_akhir_praktik }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
@endsection