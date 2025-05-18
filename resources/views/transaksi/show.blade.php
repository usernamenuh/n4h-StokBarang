@extends('layouts.demo')
@section('content')
<div class="">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h1>Detail Transaksi</h1>
            <a href="{{ route('transaksi.index') }}" class="btn btn-secondary">Kembali</a>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped" id="hotel-table">
                <tr>
                    <th>Nama Barang</th>
                    <th>Quantity</th>
                    <th>Total</th>
                </tr>
                <tr>
                    <td>{{ $transaksi->barang->nama_barang }}</td>
                    <td>{{ $transaksi->quantity }}</td>
                    <td>Rp. {{ number_format($transaksi->total, 0, ',', '.') }}</td>
                </tr>
            </table>
        </div>
    </div>
</div>
@endsection