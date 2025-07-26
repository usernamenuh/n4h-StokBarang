@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Analisis Pareto ABC</h2>

    <!-- Filter Periode -->
    <form method="GET" action="{{ route('laporan.pareto') }}" class="mb-4">
        <div class="row mb-2">
            <div class="col-md-3">
                <input type="month" name="periode" class="form-control" value="{{ request('periode') }}">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary">Filter</button>
            </div>
            <div class="col-md-3">
                <!-- Tombol Export -->
                <a href="{{ route('laporan.pareto.export', ['periode' => request('periode')]) }}" class="btn btn-success">
                    <i class="bi bi-file-earmark-excel"></i> Export Excel
                </a>
            </div>
        </div>
    </form>

    <table class="table table-bordered table-striped">
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
            @forelse($analisis as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->nama_barang }}</td>
                    <td>{{ number_format($item->total_qty, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($item->total_nilai, 0, ',', '.') }}</td>
                    <td>{{ $item->persentase }}%</td>
                    <td>
                        @if($item->kategori == 'A')
                            <span class="badge bg-danger">A</span>
                        @elseif($item->kategori == 'B')
                            <span class="badge bg-warning">B</span>
                        @else
                            <span class="badge bg-success">C</span>
                        @endif
                    </td>
                    <td>{{ number_format($item->stok_saat_ini, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">Tidak ada data untuk periode ini</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <p><strong>Total Penjualan: Rp {{ number_format($totalNilaiSemua, 0, ',', '.') }}</strong></p>
</div>
@endsection
