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
            <a href="{{ route('imports.index') }}" class="btn btn-outline-primary btn-sm" style="border-radius:8px; width:max-content;">Import Excel</a>
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

    <!-- Analisis Pareto ABC -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm rounded-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-2 fw-semibold">
                        <i class="bx bx-bar-chart-alt-2 me-2 text-primary"></i>Analisis Pareto ABC
                    </h5>
                    <div class="d-flex gap-2">
                        <form action="{{ route('pareto.analyze') }}" method="POST" class="d-inline">
                            @csrf
                            <input type="hidden" name="type" value="customer">
                            <input type="hidden" name="period" value="{{ date('Y-m') }}">
                            <button type="submit" class="btn btn-sm btn-primary">
                                <i class="bx bx-play-circle me-1"></i>Analisis Customer
                            </button>
                        </form>
                        <form action="{{ route('pareto.analyze') }}" method="POST" class="d-inline">
                            @csrf
                            <input type="hidden" name="type" value="barang">
                            <input type="hidden" name="period" value="{{ date('Y-m') }}">
                            <button type="submit" class="btn btn-sm btn-success">
                                <i class="bx bx-play-circle me-1"></i>Analisis Barang
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <!-- Customer ABC Analysis -->
        <div class="col-md-6">
            <div class="card shadow-sm rounded-4">
                <div class="card-body">
                    <h6 class="fw-semibold mb-3">
                        <i class="bx bx-user me-2"></i>Top Customers (ABC)
                    </h6>
                    @if(isset($topCustomers) && $topCustomers->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover table-sm">
                                <thead>
                                    <tr>
                                        <th>Customer</th>
                                        <th>Total</th>
                                        <th>ABC</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($topCustomers as $customer)
                                    <tr>
                                        <td>{{ Str::limit($customer->item_name, 25) }}</td>
                                        <td>Rp {{ number_format($customer->total_value, 0, ',', '.') }}</td>
                                        <td>
                                            @php
                                                $colors = ['A' => 'danger', 'B' => 'warning', 'C' => 'success'];
                                            @endphp
                                            <span class="badge bg-{{ $colors[$customer->abc_category] ?? 'secondary' }}">
                                                {{ $customer->abc_category }}
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-3">
                            <i class="bx bx-user" style="font-size: 2rem; color: #ccc;"></i>
                            <p class="text-muted mt-2">Belum ada analisis customer</p>
                            <small class="text-muted">Jalankan analisis terlebih dahulu</small>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Barang ABC Analysis -->
        <div class="col-md-6">
            <div class="card shadow-sm rounded-4">
                <div class="card-body">
                    <h6 class="fw-semibold mb-3">
                        <i class="bx bx-box me-2"></i>Top Barang (ABC)
                    </h6>
                    @if(isset($topBarang) && $topBarang->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover table-sm">
                                <thead>
                                    <tr>
                                        <th>Barang</th>
                                        <th>Value</th>
                                        <th>ABC</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($topBarang as $barang)
                                    <tr>
                                        <td>{{ Str::limit($barang->item_name, 25) }}</td>
                                        <td>Rp {{ number_format($barang->total_value, 0, ',', '.') }}</td>
                                        <td>
                                            @php
                                                $colors = ['A' => 'danger', 'B' => 'warning', 'C' => 'success'];
                                            @endphp
                                            <span class="badge bg-{{ $colors[$barang->abc_category] ?? 'secondary' }}">
                                                {{ $barang->abc_category }}
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-3">
                            <i class="bx bx-box" style="font-size: 2rem; color: #ccc;"></i>
                            <p class="text-muted mt-2">Belum ada analisis barang</p>
                            <small class="text-muted">Jalankan analisis terlebih dahulu</small>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- ABC Category Summary -->
    @if((isset($topCustomers) && $topCustomers->count() > 0) || (isset($topBarang) && $topBarang->count() > 0))
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card shadow-sm rounded-4 p-3 h-100 border-start border-danger border-4">
                <div class="d-flex align-items-center mb-2">
                    <div class="d-flex align-items-center justify-content-center bg-danger bg-opacity-10 rounded-3 me-3" style="width:40px; height:40px;">
                        <span class="fw-bold text-danger" style="font-size:1.1rem;">A</span>
                    </div>
                    <span class="fw-semibold text-muted">Kategori A (High Value)</span>
                </div>
                <h3 class="mb-2 text-danger" style="font-weight:bold;">
                    {{ (isset($topCustomers) ? $topCustomers->where('abc_category', 'A')->count() : 0) + (isset($topBarang) ? $topBarang->where('abc_category', 'A')->count() : 0) }}
                </h3>
                <div class="text-muted small">Items berkontribusi 80% value</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm rounded-4 p-3 h-100 border-start border-warning border-4">
                <div class="d-flex align-items-center mb-2">
                    <div class="d-flex align-items-center justify-content-center bg-warning bg-opacity-10 rounded-3 me-3" style="width:40px; height:40px;">
                        <span class="fw-bold text-warning" style="font-size:1.1rem;">B</span>
                    </div>
                    <span class="fw-semibold text-muted">Kategori B (Medium Value)</span>
                </div>
                <h3 class="mb-2 text-warning" style="font-weight:bold;">
                    {{ (isset($topCustomers) ? $topCustomers->where('abc_category', 'B')->count() : 0) + (isset($topBarang) ? $topBarang->where('abc_category', 'B')->count() : 0) }}
                </h3>
                <div class="text-muted small">Items berkontribusi 15% value</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm rounded-4 p-3 h-100 border-start border-success border-4">
                <div class="d-flex align-items-center mb-2">
                    <div class="d-flex align-items-center justify-content-center bg-success bg-opacity-10 rounded-3 me-3" style="width:40px; height:40px;">
                        <span class="fw-bold text-success" style="font-size:1.1rem;">C</span>
                    </div>
                    <span class="fw-semibold text-muted">Kategori C (Low Value)</span>
                </div>
                <h3 class="mb-2 text-success" style="font-weight:bold;">
                    {{ (isset($topCustomers) ? $topCustomers->where('abc_category', 'C')->count() : 0) + (isset($topBarang) ? $topBarang->where('abc_category', 'C')->count() : 0) }}
                </h3>
                <div class="text-muted small">Items berkontribusi 5% value</div>
            </div>
        </div>
    </div>
    @endif
</div>

@endsection
