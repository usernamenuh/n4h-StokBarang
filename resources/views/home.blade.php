@extends('layouts.demo')

@section('content')

<div class="">
    <!-- Card Ucapan Selamat Datang -->
    <div class="row mb-2">
      <div class="col-12">
        <div class="card shadow-sm rounded-4 p-4 d-flex flex-row align-items-center justify-content-between" style="min-height:170px;">
          <div class="d-flex flex-column justify-content-center" style="max-width:60%">
            <h5 class="card-title text-primary mb-2" style="font-size:1.3rem;">Selamat Datang di Mana 🎉</h5>
            <p class="mb-3" style="color:#6c757d;">Aplikasi ini adalah sistem manajemen Mana mana yang membantu Anda mengelola operasional Mana mana dengan efektif dan efisien.</p>
            <a href="javascript:;" class="btn btn-outline-primary btn-sm" style="border-radius:8px; width:max-content;">View Badges</a>
          </div>
          <div class="d-none d-md-block" style="max-width:38%">
            <img src="{{ asset('template/assets/anime/anya.png') }}"
             alt="Anime Girl"
             style="position: absolute; right: -10px; bottom: -2px; width: 120px; height: auto; z-index: 3;">
          </div>
        </div>
      </div>
    </div>
    <!-- Card Statistik 2x2 -->
    <h5 class="mb-3 mt-4">Statistik Aplikasi Hotel</h5>
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
            <span class="text-dangerimage.png fw-semibold"><i class="bx bx-down-arrow-alt"></i> -28.42%</span>
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

<style>
    .app-cards-row {
        display: flex;
        gap: 32px;
        margin-bottom: 32px;
        flex-wrap: nowrap;
        justify-content: flex-start;
    }
    .app-card {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 2px 12px #0001;
        padding: 28px 24px 24px 24px;
        width: 240px;
        min-height: 170px;
        position: relative;
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        transition: box-shadow 0.2s;
    }
    .app-card:hover {
        box-shadow: 0 4px 24px #0002;
    }
    .app-card-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 18px;
        font-size: 2rem;
    }
    .app-card-title {
        font-size: 1.15rem;
        font-weight: 600;
        color: #444;
        margin-bottom: 10px;
    }
    .app-card-badge {
        font-size: 0.95rem;
        border-radius: 8px;
        padding: 4px 16px;
        font-weight: 500;
        margin-top: 8px;
        display: inline-block;
    }
    .app-card-badge.barang { background: #ede9fe; color: #7c3aed; }
    .app-card-badge.nilai { background: #dcfce7; color: #22c55e; }
    .app-card-badge.mobil { background: #fef9c3; color: #f59e42; }
    .app-card-badge.dokter { background: #e0f2fe; color: #0ea5e9; }
    @media (max-width: 1100px) {
        .app-cards-row { flex-wrap: wrap; gap: 18px; }
        .app-card { width: 100%; min-width: 180px; }
    }
</style>

<!-- Statistik Aplikasi Barang -->
<h5 class="mb-3 mt-4">Statistik Aplikasi Barang</h5>
<div class="row g-4 mb-4">
  <div class="col-md-6 col-lg-3">
    <div class="card shadow-sm rounded-4 p-4 h-100">
      <div class="d-flex align-items-center mb-2">
        <div class="d-flex align-items-center justify-content-center bg-primary bg-opacity-10 rounded-3 me-3" style="width:44px; height:44px;">
          <i class="bx bx-box" style="font-size:1.7rem; color:#fff;"></i>
        </div>
        <span class="fw-semibold text-muted">Total Barang</span>
      </div>
      <h3 class="mb-2" style="font-weight:600;">{{ $totalBarang }}</h3>
    </div>
  </div>
  <div class="col-md-6 col-lg-3">
    <div class="card shadow-sm rounded-4 p-4 h-100">
      <div class="d-flex align-items-center mb-2">
        <div class="d-flex align-items-center justify-content-center bg-success bg-opacity-10 rounded-3 me-3" style="width:44px; height:44px;">
          <i class="bx bx-layer" style="font-size:1.7rem; color:#fff;"></i>
        </div>
        <span class="fw-semibold text-muted">Total Stok</span>
      </div>
      <h3 class="mb-2" style="font-weight:600;">{{ $totalStok }}</h3>
    </div>
  </div>
  <div class="col-md-6 col-lg-3">
    <div class="card shadow-sm rounded-4 p-4 h-100">
      <div class="d-flex align-items-center mb-2">
        <div class="d-flex align-items-center justify-content-center bg-danger bg-opacity-10 rounded-3 me-3" style="width:44px; height:44px;">
          <i class="bx bx-up-arrow-circle" style="font-size:1.7rem; color:#fff;"></i>
        </div>
        <span class="fw-semibold text-muted">Stok Terbanyak</span>
      </div>
      <h3 class="mb-2" style="font-weight:600;">
        {{ $barangStokTerbanyak ? $barangStokTerbanyak->stok : 0 }}
      </h3>
      <div class="small text-muted">
        {{ $barangStokTerbanyak ? $barangStokTerbanyak->nama_barang : '-' }}
      </div>
    </div>
  </div>
  <div class="col-md-6 col-lg-3">
    <div class="card shadow-sm rounded-4 p-4 h-100">
      <div class="d-flex align-items-center mb-2">
        <div class="d-flex align-items-center justify-content-center bg-warning bg-opacity-10 rounded-3 me-3" style="width:44px; height:44px;">
          <i class="bx bx-money" style="font-size:1.7rem; color:#fff;"></i>
        </div>
        <span class="fw-semibold text-muted">Harga Tertinggi</span>
      </div>
      <h3 class="mb-2" style="font-weight:600;">
        {{ $barangHargaTertinggi ? 'Rp ' . number_format($barangHargaTertinggi->harga, 0, ',', '.') : 0 }}
      </h3>
      <div class="small text-muted">
        {{ $barangHargaTertinggi ? $barangHargaTertinggi->nama_barang : '-' }}
      </div>
    </div>
  </div>
</div>

<!-- Statistik Aplikasi Nilai -->
<h5 class="mb-3 mt-4">Statistik Aplikasi Nilai</h5>
<div class="row g-4 mb-4">
  <div class="col-md-6 col-lg-3">
    <div class="card shadow-sm rounded-4 p-4 h-100">
      <div class="d-flex align-items-center mb-2">
        <div class="d-flex align-items-center justify-content-center bg-primary bg-opacity-10 rounded-3 me-3" style="width:44px; height:44px;">
          <i class="bx bx-user" style="font-size:1.7rem; color:#fff;"></i>
        </div>
        <span class="fw-semibold text-muted">Siswa</span>
      </div>
      <h3 class="mb-2" style="font-weight:600;">{{ $jumlahSiswa }}</h3>
    </div>
  </div>
  <div class="col-md-6 col-lg-3">
    <div class="card shadow-sm rounded-4 p-4 h-100">
      <div class="d-flex align-items-center mb-2">
        <div class="d-flex align-items-center justify-content-center bg-success bg-opacity-10 rounded-3 me-3" style="width:44px; height:44px;">
          <i class="bx bx-bar-chart" style="font-size:1.7rem; color:#fff;"></i>
        </div>
        <span class="fw-semibold text-muted">Nilai Rata-rata</span>
      </div>
      <h3 class="mb-2" style="font-weight:600;">{{ number_format($nilaiRataRata, 0) }}</h3>
    </div>
  </div>
  <div class="col-md-6 col-lg-3">
    <div class="card shadow-sm rounded-4 p-4 h-100">
      <div class="d-flex align-items-center mb-2">
        <div class="d-flex align-items-center justify-content-center bg-info bg-opacity-10 rounded-3 me-3" style="width:44px; height:44px;">
          <i class="bx bx-up-arrow-alt" style="font-size:1.7rem; color:#fff;"></i>
        </div>
        <span class="fw-semibold text-muted">Nilai Tertinggi</span>
      </div>
      <h3 class="mb-2" style="font-weight:600;">{{ $nilaiTertinggi }}</h3>
    </div>
  </div>
  <div class="col-md-6 col-lg-3">
    <div class="card shadow-sm rounded-4 p-4 h-100">
      <div class="d-flex align-items-center mb-2">
        <div class="d-flex align-items-center justify-content-center bg-danger bg-opacity-10 rounded-3 me-3" style="width:44px; height:44px;">
          <i class="bx bx-down-arrow-alt" style="font-size:1.7rem; color:#fff;"></i>
        </div>
        <span class="fw-semibold text-muted">Nilai Terendah</span>
      </div>
      <h3 class="mb-2" style="font-weight:600;">{{ $nilaiTerendah }}</h3>
    </div>
  </div>
</div>

<!-- Statistik Aplikasi Mobil -->
<h5 class="mb-3 mt-4">Statistik Aplikasi Mobil</h5>
<div class="row g-4 mb-4">
  <div class="col-md-6 col-lg-3">
    <div class="card shadow-sm rounded-4 p-4 h-100">
      <div class="d-flex align-items-center mb-2">
        <div class="d-flex align-items-center justify-content-center bg-primary bg-opacity-10 rounded-3 me-3" style="width:44px; height:44px;">
          <i class="bx bx-car" style="font-size:1.7rem; color:#fff;"></i>
        </div>
        <span class="fw-semibold text-muted">Total Mobil</span>
      </div>
      <h3 class="mb-2" style="font-weight:600;">{{ $totalMobil }}</h3>
    </div>
  </div>
  <div class="col-md-6 col-lg-3">
    <div class="card shadow-sm rounded-4 p-4 h-100">
      <div class="d-flex align-items-center mb-2">
        <div class="d-flex align-items-center justify-content-center bg-success bg-opacity-10 rounded-3 me-3" style="width:44px; height:44px;">
          <i class="bx bx-check" style="font-size:1.7rem; color:#fff;"></i>
        </div>
        <span class="fw-semibold text-muted">Mobil Tersedia</span>
      </div>
      <h3 class="mb-2" style="font-weight:600;">{{ $mobilTersedia }}</h3>
    </div>
  </div>
  <div class="col-md-6 col-lg-3">
    <div class="card shadow-sm rounded-4 p-4 h-100">
      <div class="d-flex align-items-center mb-2">
        <div class="d-flex align-items-center justify-content-center bg-warning bg-opacity-10 rounded-3 me-3" style="width:44px; height:44px;">
          <i class="bx bx-log-in" style="font-size:1.7rem; color:#fff;"></i>
        </div>
        <span class="fw-semibold text-muted">Mobil Disewa</span>
      </div>
      <h3 class="mb-2" style="font-weight:600;">{{ $mobilDisewa }}</h3>
    </div>
  </div>
  <div class="col-md-6 col-lg-3">
    <div class="card shadow-sm rounded-4 p-4 h-100">
      <div class="d-flex align-items-center mb-2">
        <div class="d-flex align-items-center justify-content-center bg-danger bg-opacity-10 rounded-3 me-3" style="width:44px; height:44px;">
          <i class="bx bx-wrench" style="font-size:1.7rem; color:#fff;"></i>
        </div>
        <span class="fw-semibold text-muted">Servis</span>
      </div>
      <h3 class="mb-2" style="font-weight:600;">{{ $servis }}</h3>
    </div>
  </div>
</div>

<!-- Statistik Aplikasi Dokter -->
<h5 class="mb-3 mt-4">Statistik Aplikasi Dokter</h5>
<div class="row g-4 mb-4">
  <div class="col-md-6 col-lg-3">
    <div class="card shadow-sm rounded-4 p-4 h-100">
      <div class="d-flex align-items-center mb-2">
        <div class="d-flex align-items-center justify-content-center bg-primary bg-opacity-10 rounded-3 me-3" style="width:44px; height:44px;">
          <i class="bx bx-user" style="font-size:1.7rem; color:#fff;"></i>
        </div>
        <span class="fw-semibold text-muted">Total Dokter</span>
      </div>
      <h3 class="mb-2" style="font-weight:600;">{{ $totalDokter }}</h3>
    </div>
  </div>
  <div class="col-md-6 col-lg-3">
    <div class="card shadow-sm rounded-4 p-4 h-100">
      <div class="d-flex align-items-center mb-2">
        <div class="d-flex align-items-center justify-content-center bg-success bg-opacity-10 rounded-3 me-3" style="width:44px; height:44px;">
          <i class="bx bx-calendar" style="font-size:1.7rem; color:#fff;"></i>
        </div>
        <span class="fw-semibold text-muted">Jadwal Hari Ini</span>
      </div>
      <h3 class="mb-2" style="font-weight:600;">{{ $jadwalHariIni }}</h3>
    </div>
  </div>
  <div class="col-md-6 col-lg-3">
    <div class="card shadow-sm rounded-4 p-4 h-100">
      <div class="d-flex align-items-center mb-2">
        <div class="d-flex align-items-center justify-content-center bg-info bg-opacity-10 rounded-3 me-3" style="width:44px; height:44px;">
          <i class="bx bx-bookmark" style="font-size:1.7rem; color:#fff;"></i>
        </div>
        <span class="fw-semibold text-muted">Booking Aktif</span>
      </div>
      <h3 class="mb-2" style="font-weight:600;">{{ $bookingAktif }}</h3>
    </div>
  </div>
  <div class="col-md-6 col-lg-3">
    <div class="card shadow-sm rounded-4 p-4 h-100">
      <div class="d-flex align-items-center mb-2">
        <div class="d-flex align-items-center justify-content-center bg-danger bg-opacity-10 rounded-3 me-3" style="width:44px; height:44px;">
          <i class="bx bx-user-x" style="font-size:1.7rem; color:#fff;"></i>
        </div>
        <span class="fw-semibold text-muted">Dokter Offline</span>
      </div>
      <h3 class="mb-2" style="font-weight:600;">{{ $dokterOffline }}</h3>
    </div>
  </div>
</div>

@endsection
