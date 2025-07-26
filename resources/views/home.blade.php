@extends('layouts.demo')

@section('content')

<div class="">
    <!-- Card Ucapan Selamat Datang -->
    <div class="row mb-2">
      <div class="col-12">
        <div class="card shadow-sm rounded-4 p-4 d-flex flex-row align-items-start justify-content-between" style="min-height:120px;">
          <div class="d-flex flex-column justify-content-center" style="max-width:70%">
            <h5 class="card-title text-primary mb-2" style="font-size:1.3rem;">Selamat Datang di Sistem ABC Analysis 🎉</h5>
            <p class="mb-3" style="color:#6c757d;">Aplikasi ini adalah sistem manajemen barang dengan analisis Pareto ABC yang membantu Anda mengelola operasional dengan efektif dan efisien.</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Statistik Aplikasi Barang -->
    <h5 class="mb-3 mt-3">Statistik Aplikasi Barang</h5>
    <div class="row g-4 mb-4">
      <div class="col-md-6 col-lg-3">
        <div class="card shadow-sm rounded-4 p-3 h-100">
          <div class="d-flex align-items-center mb-2">
            <div class="d-flex align-items-center justify-content-center bg-success bg-opacity-10 rounded-3 me-3" style="width:40px; height:40px;">
              <i class="bx bx-box" style="font-size:1.5rem; color:#4CAF50;"></i>
            </div>
            <span class="fw-semibold text-muted">Total Barang</span>
          </div>
          <h3 class="mb-2" style="font-weight:bold;">{{ $totalBarang ?? 0 }}</h3>
        </div>
      </div>
      <div class="col-md-6 col-lg-3">
        <div class="card shadow-sm rounded-4 p-3 h-100">
          <div class="d-flex align-items-center mb-2">
            <div class="d-flex align-items-center justify-content-center bg-primary bg-opacity-10 rounded-3 me-3" style="width:40px; height:40px;">
              <i class="bx bx-receipt" style="font-size:1.5rem; color:#2196F3;"></i>
            </div>
            <span class="fw-semibold text-muted">Total Transaksi</span>
          </div>
          <h3 class="mb-2" style="font-weight:bold;">{{ $totalTransaksi ?? 0 }}</h3>
        </div>
      </div>
      <div class="col-md-6 col-lg-3">
        <div class="card shadow-sm rounded-4 p-3 h-100">
          <div class="d-flex align-items-center mb-2">
            <div class="d-flex align-items-center justify-content-center bg-warning bg-opacity-10 rounded-3 me-3" style="width:40px; height:40px;">
              <i class="bx bx-money" style="font-size:1.5rem; color:#FFC107;"></i>
            </div>
            <span class="fw-semibold text-muted">Total Revenue</span>
          </div>
          <h3 class="mb-2" style="font-weight:bold;">
            Rp {{ number_format($totalRevenue ?? 0, 0, ',', '.') }}
          </h3>
        </div>
      </div>
      <div class="col-md-6 col-lg-3">
        <div class="card shadow-sm rounded-4 p-3 h-100">
          <div class="d-flex align-items-center mb-2">
            <div class="d-flex align-items-center justify-content-center bg-info bg-opacity-10 rounded-3 me-3" style="width:40px; height:40px;">
              <i class="bx bx-user" style="font-size:1.5rem; color:#9C27B0;"></i>
            </div>
            <span class="fw-semibold text-muted">Total Customer</span>
          </div>
          <h3 class="mb-2" style="font-weight:bold;">{{ $totalCustomers ?? 0 }}</h3>
        </div>
      </div>
    </div>
</div>

<!-- Tombol Analisis Laporan -->
<div class="mb-4">
    <a href="{{ route('laporan.pareto') }}" class="btn btn-primary">
        <i class="bx bx-bar-chart"></i> Analisis Laporan Pareto
    </a>
</div>

@endsection
