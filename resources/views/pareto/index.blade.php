@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Analisis Pareto ABC</h1>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Barang</th>
                <th>Total Qty</th>
                <th>Total Nilai (Rp)</th>
                <th>Persentase (%)</th>
                <th>Kategori</th>
                <th>Stok Saat Ini</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($hasil as $item)
                <tr>
                    <td>{{ $item['no'] }}</td>
                    <td>{{ $item['nama_barang'] }}</td>
                    <td>{{ $item['total_qty'] }}</td>
                    <td>Rp {{ number_format($item['total_nilai'], 0, ',', '.') }}</td>
                    <td>{{ $item['persentase'] }}%</td>
                    <td>{{ $item['kategori'] }}</td>
                    <td>{{ $item['stok_saat_ini'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
