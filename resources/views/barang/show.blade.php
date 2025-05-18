@extends('layouts.demo')
@section('content')
<div class="">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h1>Detail Barang</h1>
            <a href="{{ route('barang.index') }}" class="btn btn-secondary">Kembali</a>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h5>Nama Barang</h5>
                    <p>{{ $barang->nama_barang }}</p>
                </div>
                <div class="col-md-6">
                    <h5>Kode Barang</h5>
                    <p>{{ $barang->kode_barang }}</p>
                </div>
                <div class="col-md-6">
                    <h5>Stok</h5>
                    <p>{{ $barang->stok }}</p>
                </div>
                <div class="col-md-6">
                    <h5>Harga</h5>
                    <p>{{ $barang->harga }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection