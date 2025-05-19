@extends('layouts.demo')

@section('content')
<div class="">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h1>Nilai Mahasiswa</h1>
            <a href="{{ route('nilai.index') }}" class="btn btn-primary">Kembali</a>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <tr>
                    <th colspan="3" class="text-center" style="background-color: #02366e; color: white;">Nilai Mahasiswa</th>
                </tr>
                <tr>
                    <th>Nama Mahasiswa</th>
                    <td>{{ $nilai->nama_mahasiswa }}</td>
                </tr>
                <tr>
                    <th>NIM</th>
                    <td>{{ $nilai->nim }}</td>
                </tr>
                <tr>
                    <th>Nilai Rata-rata</th>
                    <td>{{ $nilai->nilai_rata_rata }}</td>
                </tr>
            </table>
        </div>
    </div>
</div>
@endsection