@extends('layouts.demo')
@section('content')
<div class="">
    <!-- Header Card -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm rounded-4 p-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="card-title text-primary mb-2" style="font-size:1.3rem;">
                            <i class="bx bx-bar-chart-alt-2 me-2"></i>Analisis Pareto ABC
                        </h5>
                        <p class="mb-0" style="color:#6c757d;">
                            Analisis ABC untuk mengkategorikan Customer dan Barang berdasarkan nilai kontribusi
                        </p>
                    </div>
                    <div class="d-none d-md-block">
                        <i class="bx bx-trending-up" style="font-size: 3rem; color: #696cff; opacity: 0.3;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if (session('success'))
        <div id="alert-success" class="custom-alert-success">
            <span class="custom-alert-icon">
                <svg width="20" height="20" fill="none" viewBox="0 0 20 20">
                    <circle cx="10" cy="10" r="10" fill="#22c55e" fill-opacity="0.15"/>
                    <path d="M6 10.5l3 3 5-5" stroke="#22c55e" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </span>
            <span class="custom-alert-text">{{ session('success') }}</span>
            <span class="custom-alert-close" onclick="$('#alert-success').fadeOut(300);">&times;</span>
        </div>
    @endif
    @if (session('error'))
        <div id="alert-danger" class="custom-alert-danger">
            <span class="custom-alert-icon">
                <svg width="20" height="20" fill="none" viewBox="0 0 20 20">
                    <circle cx="10" cy="10" r="10" fill="#f87171" fill-opacity="0.15"/>
                    <path d="M7 7l6 6M13 7l-6 6" stroke="#ef4444" stroke-width="2" stroke-linecap="round"/>
                </svg>
            </span>
            <span class="custom-alert-text">{{ session('error') }}</span>
            <span class="custom-alert-close" onclick="$('#alert-danger').fadeOut(300);">&times;</span>
        </div>
    @endif

    <!-- Analysis Controls -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm rounded-4 p-4">
                <form action="{{ route('pareto.analyze') }}" method="POST" class="row g-3">
                    @csrf
                    <div class="col-md-4">
                        <label for="type" class="form-label">Tipe Analisis</label>
                        <select class="form-select" id="type" name="type" required>
                            <option value="customer" {{ ($type ?? 'customer') == 'customer' ? 'selected' : '' }}>Customer</option>
                            <option value="barang" {{ ($type ?? '') == 'barang' ? 'selected' : '' }}>Barang</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="period" class="form-label">Periode</label>
                        <input type="month" class="form-control" id="period" name="period" 
                               value="{{ $period ?? date('Y-m') }}" required>
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="bx bx-play-circle me-1"></i>Jalankan Analisis
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ABC Summary Cards -->
    @if(isset($analysisResults) && $analysisResults->count() > 0)
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card shadow-sm rounded-4 p-4 h-100 border-start border-danger border-4">
                <div class="d-flex align-items-center mb-2">
                    <div class="d-flex align-items-center justify-content-center bg-danger bg-opacity-10 rounded-3 me-3" style="width:44px; height:44px;">
                        <span class="fw-bold text-danger" style="font-size:1.2rem;">A</span>
                    </div>
                    <span class="fw-semibold text-muted">Kategori A (80%)</span>
                </div>
                <h3 class="mb-2 text-danger" style="font-weight:600;">
                    {{ $analysisResults->where('abc_category', 'A')->count() }}
                </h3>
                <div class="small text-muted">High Value Items</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm rounded-4 p-4 h-100 border-start border-warning border-4">
                <div class="d-flex align-items-center mb-2">
                    <div class="d-flex align-items-center justify-content-center bg-warning bg-opacity-10 rounded-3 me-3" style="width:44px; height:44px;">
                        <span class="fw-bold text-warning" style="font-size:1.2rem;">B</span>
                    </div>
                    <span class="fw-semibold text-muted">Kategori B (15%)</span>
                </div>
                <h3 class="mb-2 text-warning" style="font-weight:600;">
                    {{ $analysisResults->where('abc_category', 'B')->count() }}
                </h3>
                <div class="small text-muted">Medium Value Items</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm rounded-4 p-4 h-100 border-start border-success border-4">
                <div class="d-flex align-items-center mb-2">
                    <div class="d-flex align-items-center justify-content-center bg-success bg-opacity-10 rounded-3 me-3" style="width:44px; height:44px;">
                        <span class="fw-bold text-success" style="font-size:1.2rem;">C</span>
                    </div>
                    <span class="fw-semibold text-muted">Kategori C (5%)</span>
                </div>
                <h3 class="mb-2 text-success" style="font-weight:600;">
                    {{ $analysisResults->where('abc_category', 'C')->count() }}
                </h3>
                <div class="small text-muted">Low Value Items</div>
            </div>
        </div>
    </div>
    @endif

    <!-- Analysis Results Table -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm rounded-4">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h6 class="mb-0 fw-semibold">
                        <i class="bx bx-list-ul me-2"></i>Hasil Analisis Pareto ABC
                    </h6>
                    <div class="d-flex gap-2">
                        <span class="badge bg-label-primary">{{ isset($analysisResults) ? $analysisResults->count() : 0 }} Items</span>
                        <span class="badge bg-label-info">{{ ucfirst($type ?? 'customer') }}</span>
                        <span class="badge bg-label-secondary">{{ isset($period) ? \Carbon\Carbon::createFromFormat('Y-m', $period)->format('F Y') : date('F Y') }}</span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover" id="pareto-table">
                            <thead>
                                <tr>
                                    <th>Rank</th>
                                    <th>{{ ($type ?? 'customer') == 'customer' ? 'Customer Name' : 'Barang Name' }}</th>
                                    <th>Total Value</th>
                                    <th>Percentage</th>
                                    <th>Cumulative %</th>
                                    <th>ABC Category</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($analysisResults ?? [] as $result)
                                <tr>
                                    <td>
                                        <span class="badge bg-label-secondary">{{ $result->rank_position }}</span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="bx {{ ($type ?? 'customer') == 'customer' ? 'bx-user' : 'bx-box' }} me-2 text-muted"></i>
                                            {{ $result->item_name }}
                                        </div>
                                    </td>
                                    <td>
                                        <span class="fw-semibold">Rp {{ number_format($result->total_value, 0, ',', '.') }}</span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="progress me-2" style="width: 60px; height: 6px;">
                                                <div class="progress-bar" role="progressbar" 
                                                     style="width: {{ $result->percentage }}%"></div>
                                            </div>
                                            <span class="small">{{ $result->percentage }}%</span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="small">{{ $result->cumulative_percentage }}%</span>
                                    </td>
                                    <td>
                                        @php
                                            $categoryColors = [
                                                'A' => 'danger',
                                                'B' => 'warning', 
                                                'C' => 'success'
                                            ];
                                        @endphp
                                        <span class="badge bg-{{ $categoryColors[$result->abc_category] }}">
                                            {{ $result->abc_category }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <i class="bx bx-bar-chart" style="font-size: 2rem; color: #ccc;"></i>
                                        <p class="text-muted mt-2">Belum ada data analisis untuk periode ini</p>
                                        <p class="small text-muted">Jalankan analisis terlebih dahulu</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .custom-alert-success {
        display: flex;
        align-items: center;
        background: #f0fdf4;
        color: #166534;
        border-radius: 10px;
        padding: 16px 24px;
        min-width: 500px;
        max-width: 600px;
        position: fixed;
        top: 30px;
        left: 50%;
        transform: translateX(-50%);
        z-index: 9999;
        box-shadow: 0 2px 8px rgba(34,197,94,0.08);
        font-size: 1rem;
        font-weight: 500;
        animation: fadeInDown 0.5s;
    }
    .custom-alert-icon {
        margin-right: 12px;
        display: flex;
        align-items: center;
    }
    .custom-alert-text {
        flex: 1;
    }
    .custom-alert-close {
        margin-left: 16px;
        cursor: pointer;
        font-size: 1.3rem;
        color: #22c55e;
        transition: color 0.2s;
    }
    .custom-alert-close:hover {
        color: #166534;
    }
    @keyframes fadeInDown {
        from { opacity: 0; transform: translateY(-20px) translateX(-50%);}
        to { opacity: 1; transform: translateY(0) translateX(-50%);}
    }
    @media (max-width: 600px) {
        .custom-alert-success {
            min-width: 90vw;
            max-width: 98vw;
            padding: 12px 8px;
            font-size: 0.95rem;
        }
    }
    .custom-alert-danger {
        display: flex;
        align-items: center;
        background: #fef2f2;
        color: #991b1b;
        border-radius: 10px;
        padding: 16px 24px;
        min-width: 500px;
        max-width: 600px;
        position: fixed;
        top: 30px;
        left: 50%;
        transform: translateX(-50%);
        z-index: 9999;
        box-shadow: 0 2px 8px rgba(239,68,68,0.08);
        font-size: 1rem;
        font-weight: 500;
        animation: fadeInDown 0.5s;
    }
    .custom-alert-danger .custom-alert-icon svg {
        margin-right: 12px;
        display: flex;
        align-items: center;
    }
    .custom-alert-danger .custom-alert-text {
        flex: 1;
    }
    .custom-alert-danger .custom-alert-close {
        margin-left: 16px;
        cursor: pointer;
        font-size: 1.3rem;
        color: #ef4444;
        transition: color 0.2s;
    }
    .custom-alert-danger .custom-alert-close:hover {
        color: #991b1b;
    }
    @media (max-width: 600px) {
        .custom-alert-danger {
            min-width: 90vw;
            max-width: 98vw;
            padding: 12px 8px;
            font-size: 0.95rem;
        }
    }
</style>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
$(document).ready(function() {
    $('#pareto-table').DataTable({
        "paging": true,
        "searching": true,
        "info": true,
        "order": [[0, "asc"]] // Sort by rank ascending
    });

    setTimeout(function(){
        $("#alert-success").fadeOut(400);
        $("#alert-danger").fadeOut(400);
    }, 3000);
});
</script>

@endsection
