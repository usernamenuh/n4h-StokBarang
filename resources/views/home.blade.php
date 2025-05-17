@extends('layouts.demo')

@section('content')

<div class="">
    <!-- Card Ucapan Selamat Datang -->
    <div class="row mb-2">
      <div class="col-12">
        <div class="card shadow-sm rounded-4 p-4 d-flex flex-row align-items-center justify-content-between" style="min-height:170px;">
          <div class="d-flex flex-column justify-content-center" style="max-width:60%">
            <h5 class="card-title text-primary mb-2" style="font-size:1.3rem;">Selamat Datang di aplikasi manajemen hotel 🎉</h5>
            <p class="mb-3" style="color:#6c757d;">Aplikasi ini adalah sistem manajemen hotel yang membantu Anda mengelola operasional hotel dengan efektif dan efisien.</p>
            <a href="javascript:;" class="btn btn-outline-primary btn-sm" style="border-radius:8px; width:max-content;">View Badges</a>
          </div>
          <div class="d-none d-md-block" style="max-width:38%">
            <img src="{{ asset('template/assets/img/illustrations/man-with-laptop-light.png') }}" height="120" alt="View Badge User" style="object-fit:contain;"/>
          </div>
        </div>
      </div>
    </div>
    <!-- Card Statistik 2x2 -->
    <div class="row g-4 mb-4">
      <!-- Profit -->
      <div class="col-md-6 col-lg-3">
        <div class="card shadow-sm rounded-4 p-4 h-100 position-relative">
          <div class="dropdown position-absolute end-0 mt-2 me-2">
            <button class="btn p-0" type="button" data-bs-toggle="dropdown" aria-expanded="false">
              <i class="bx bx-dots-vertical-rounded fs-4"></i>
            </button>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="#">View More</a></li>
              <li><a class="dropdown-item" href="#">Delete</a></li>
            </ul>
          </div>
          <div class="d-flex align-items-center mb-3">
            <div class="d-flex align-items-center justify-content-center bg-success bg-opacity-10 rounded-3 me-3" style="width:48px; height:48px;">
              <i class="bx bx-pie-chart-alt" style="font-size:2rem; color:#fcfcfc;"></i>
            </div>
            <span class="fw-semibold text-muted">Profit</span>
          </div>
          <h3 class="mb-2" style="font-weight:600;">Rp {{ number_format($totalPendapatan,0,',','.') }}</h3>
          <div class="d-flex align-items-center">
            <span class="text-success fw-semibold"><i class="bx bx-up-arrow-alt"></i> +72.80%</span>
          </div>
        </div>
      </div>
      <!-- Sales -->
      <div class="col-md-6 col-lg-3">
        <div class="card shadow-sm rounded-4 p-4 h-100 position-relative">
          <div class="dropdown position-absolute end-0 mt-2 me-2">
            <button class="btn p-0" type="button" data-bs-toggle="dropdown" aria-expanded="false">
              <i class="bx bx-dots-vertical-rounded fs-4"></i>
            </button>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="#">View More</a></li>
              <li><a class="dropdown-item" href="#">Delete</a></li>
            </ul>
          </div>
          <div class="d-flex align-items-center mb-3">
            <div class="d-flex align-items-center justify-content-center bg-info bg-opacity-10 rounded-3 me-3" style="width:48px; height:48px;">
              <i class="bx bx-key" style="font-size:2rem; color:#ffffff;"></i>
            </div>
            <span class="fw-semibold text-muted">Kamar Disewa</span>
          </div>
          <h3 class="mb-2" style="font-weight:600;">{{ $jumlahKamarDisewa }}</h3>
          <div class="d-flex align-items-center">
            <span class="text-success fw-semibold"><i class="bx bx-up-arrow-alt"></i> +28.42%</span>
          </div>
        </div>
      </div>
      <!-- Payments -->
      <div class="col-md-6 col-lg-3">
        <div class="card shadow-sm rounded-4 p-4 h-100 position-relative">
          <div class="dropdown position-absolute end-0 mt-2 me-2">
            <button class="btn p-0" type="button" data-bs-toggle="dropdown" aria-expanded="false">
              <i class="bx bx-dots-vertical-rounded fs-4"></i>
            </button>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="#">View More</a></li>
              <li><a class="dropdown-item" href="#">Delete</a></li>
            </ul>
          </div>
          <div class="d-flex align-items-center mb-3">
            <div class="d-flex align-items-center justify-content-center bg-danger bg-opacity-10 rounded-3 me-3" style="width:48px; height:48px;">
              <i class="bx bx-user" style="font-size:2rem; color:#ffffff;"></i>
            </div>
            <span class="fw-semibold text-muted">Pelanggan</span>
          </div>
          <h3 class="mb-2" style="font-weight:600;">{{ $jumlahPelanggan }}</h3>
          <div class="d-flex align-items-center">
            <span class="text-danger fw-semibold"><i class="bx bx-down-arrow-alt"></i> -14.82%</span>
          </div>
        </div>
      </div>
      <!-- Transactions -->
      <div class="col-md-6 col-lg-3">
        <div class="card shadow-sm rounded-4 p-4 h-100 position-relative">
          <div class="dropdown position-absolute end-0 mt-2 me-2">
            <button class="btn p-0" type="button" data-bs-toggle="dropdown" aria-expanded="false">
              <i class="bx bx-dots-vertical-rounded fs-4"></i>
            </button>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="#">View More</a></li>
              <li><a class="dropdown-item" href="#">Delete</a></li>
            </ul>
          </div>
          <div class="d-flex align-items-center mb-3">
            <div class="d-flex align-items-center justify-content-center bg-primary bg-opacity-10 rounded-3 me-3" style="width:48px; height:48px;">
              <i class="bx bx-bed" style="font-size:2rem; color:#ffffff;"></i>
            </div>
            <span class="fw-semibold text-muted">Kamar</span>
          </div>
          <h3 class="mb-2" style="font-weight:600;">{{ $jumlahKamar }}</h3>
          <div class="d-flex align-items-center">
            <span class="text-success fw-semibold"><i class="bx bx-up-arrow-alt"></i> +28.14%</span>
          </div>
        </div>
      </div>
    </div>
    <!-- Tabel Pelanggan ...tetap seperti sebelumnya... -->
 {{--    @include('pelanggan.index') --}}
</div>

<!-- DataTables CSS & JS ...tetap... -->

@endsection
