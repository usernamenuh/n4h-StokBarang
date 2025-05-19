@extends('layouts.demo')

@section('content')
<div class="">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h1>Rental</h1>
            <a href="{{ route('rental.index') }}" class="btn btn-primary">Kembali</a>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <tr>
                    <th colspan="3" class="text-center" style="background-color: #02366e; color: white;">Rental</th>
                </tr>
                <tr>
                    <th>Mobil</th>
                    <td>{{ $rental->mobil->nama_mobil }}</td>
                </tr>
                <tr>
                    <th>Tanggal Awal Sewa</th>
                    <td>{{ $rental->tanggal_awal_sewa }}</td>
                </tr>
                <tr>
                    <th>Tanggal Akhir Sewa</th>
                    <td>{{ $rental->tanggal_akhir_sewa }}</td>
                </tr>
            </table>
        </div>
    </div>
</div>
@endsection