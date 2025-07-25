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
                    <h5>Kode Barang</h5>
                    <p><span class="badge bg-primary">{{ $barang->kode }}</span></p>
                </div>
                <div class="col-md-6">
                    <h5>Nama Barang</h5>
                    <p>{{ $barang->nama }}</p>
                </div>
                <div class="col-md-6">
                    <h5>Does Pcs</h5>
                    <p>{{ number_format($barang->does_pcs, 2) }}</p>
                </div>
                <div class="col-md-6">
                    <h5>Golongan</h5>
                    <p><span class="badge bg-info">{{ $barang->golongan }}</span></p>
                </div>
                <div class="col-md-6">
                    <h5>Harga Beli</h5>
                    <p class="text-success fw-bold">Rp {{ number_format($barang->hbeli, 0, ',', '.') }}</p>
                </div>
                <div class="col-md-6">
                    <h5>User Input</h5>
                    <p>{{ $barang->user->name ?? $barang->user_id }}</p>
                </div>
                @if($barang->keterangan)
                <div class="col-12">
                    <h5>Keterangan</h5>
                    <p>{{ $barang->keterangan }}</p>
                </div>
                @endif
                <div class="col-12 mt-3">
                    <small class="text-muted">
                        Dibuat: {{ $barang->created_at->format('d/m/Y H:i') }} | 
                        Diupdate: {{ $barang->updated_at->format('d/m/Y H:i') }}
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection