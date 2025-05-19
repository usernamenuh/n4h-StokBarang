@extends('layouts.demo')

@section('content')
<div class="">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h1>Mobil</h1>
            <a href="{{ route('mobil.index') }}" class="btn btn-primary">Kembali</a>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <tr>
                    <th colspan="2" class="text-center" style="background-color: #02366e; color: white;">Mobil</th>
                </tr>
                <tr>
                    <th>Nomor Polisi</th>
                    <td>{{ $mobil->nomor_polisi }}</td>
                </tr>
                <tr>
                    <th>Type Kendaraan</th>
                    <td>{{ $mobil->type_kendaraan }}</td>
                </tr>
                <tr>
                    <th>Status</th>
                    <td>
                        @if ($mobil->status == 'dirental')
                            <span class="badge bg-danger">Dirental</span>
                        @else
                            <span class="badge bg-success">Tersedia</span>
                        @endif
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>
@endsection