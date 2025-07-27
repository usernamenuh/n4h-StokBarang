@extends('layouts.demo')

@section('content')
<div class="container-fluid">
    <!-- Loading Overlay -->
    <div id="loadingOverlay" class="position-fixed top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center" style="background: rgba(0,0,0,0.8); z-index: 9999; backdrop-filter: blur(5px);">
        <div class="text-center text-white">
            <div class="spinner-border text-light mb-3" role="status" style="width: 3rem; height: 3rem;">
                <span class="visually-hidden">Loading...</span>
            </div>
            <h5>Memuat Dashboard...</h5>
            <p class="text-light opacity-75">Mengalihkan ke sistem baru</p>
        </div>
    </div>

    <!-- Transition Welcome Card -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-lg rounded-4 p-4 border-0" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height:150px;">
                <div class="d-flex flex-column justify-content-center text-white">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-white bg-opacity-20 rounded-3 p-2 me-3">
                            <i class="bx bx-rocket" style="font-size:2rem;"></i>
                        </div>
                        <div>
                            <h4 class="mb-1 fw-bold">Selamat Datang, {{ Auth::user()->name }}! 🎉</h4>
                            <p class="mb-0 opacity-90">Sistem StockMaster telah diperbarui dengan tampilan dan fitur baru</p>
                        </div>
                    </div>
                    <div class="d-flex gap-3">
                        <button onclick="goToDashboard()" class="btn btn-light btn-sm px-4">
                            <i class="bx bx-tachometer-alt me-2"></i>Dashboard Baru
                        </button>
                        <button onclick="stayHere()" class="btn btn-outline-light btn-sm px-4">
                            <i class="bx bx-time me-2"></i>Tetap di Sini
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats Preview -->
    <div class="row g-4 mb-4" id="statsSection">
        <div class="col-md-6 col-lg-3">
            <div class="card shadow-sm rounded-4 p-3 h-100 border-0 hover-card">
                <div class="d-flex align-items-center mb-2">
                    <div class="d-flex align-items-center justify-content-center bg-success bg-opacity-10 rounded-3 me-3" style="width:40px; height:40px;">
                        <i class="bx bx-box" style="font-size:1.5rem; color:#4CAF50;"></i>
                    </div>
                    <span class="fw-semibold text-muted">Total Barang</span>
                </div>
                <h3 class="mb-2 fw-bold text-success">{{ $totalBarang ?? 1234 }}</h3>
                <small class="text-success">
                    <i class="bx bx-trending-up me-1"></i>+12% dari bulan lalu
                </small>
            </div>
        </div>
        
        <div class="col-md-6 col-lg-3">
            <div class="card shadow-sm rounded-4 p-3 h-100 border-0 hover-card">
                <div class="d-flex align-items-center mb-2">
                    <div class="d-flex align-items-center justify-content-center bg-primary bg-opacity-10 rounded-3 me-3" style="width:40px; height:40px;">
                        <i class="bx bx-receipt" style="font-size:1.5rem; color:#2196F3;"></i>
                    </div>
                    <span class="fw-semibold text-muted">Total Transaksi</span>
                </div>
                <h3 class="mb-2 fw-bold text-primary">{{ $totalTransaksi ?? 89 }}</h3>
                <small class="text-success">
                    <i class="bx bx-trending-up me-1"></i>+8% hari ini
                </small>
            </div>
        </div>
        
        <div class="col-md-6 col-lg-3">
            <div class="card shadow-sm rounded-4 p-3 h-100 border-0 hover-card">
                <div class="d-flex align-items-center mb-2">
                    <div class="d-flex align-items-center justify-content-center bg-warning bg-opacity-10 rounded-3 me-3" style="width:40px; height:40px;">
                        <i class="bx bx-money" style="font-size:1.5rem; color:#FFC107;"></i>
                    </div>
                    <span class="fw-semibold text-muted">Total Revenue</span>
                </div>
                <h3 class="mb-2 fw-bold text-warning">
                    Rp {{ number_format($totalRevenue ?? 45200000, 0, ',', '.') }}
                </h3>
                <small class="text-success">
                    <i class="bx bx-trending-up me-1"></i>+15% dari target
                </small>
            </div>
        </div>
        
        <div class="col-md-6 col-lg-3">
            <div class="card shadow-sm rounded-4 p-3 h-100 border-0 hover-card">
                <div class="d-flex align-items-center mb-2">
                    <div class="d-flex align-items-center justify-content-center bg-info bg-opacity-10 rounded-3 me-3" style="width:40px; height:40px;">
                        <i class="bx bx-user" style="font-size:1.5rem; color:#9C27B0;"></i>
                    </div>
                    <span class="fw-semibold text-muted">Stok Menipis</span>
                </div>
                <h3 class="mb-2 fw-bold text-danger">{{ $totalCustomers ?? 23 }}</h3>
                <small class="text-danger">
                    <i class="bx bx-trending-down me-1"></i>Perlu perhatian
                </small>
            </div>
        </div>
    </div>

    <!-- Action Cards -->
    <div class="row g-4 mb-4" id="actionSection">
        <div class="col-md-6">
            <div class="card shadow-sm rounded-4 border-0 h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-primary bg-opacity-10 rounded-3 p-2 me-3">
                            <i class="bx bx-tachometer-alt" style="font-size:1.5rem; color:#2196F3;"></i>
                        </div>
                        <h5 class="mb-0 fw-bold">Dashboard Baru</h5>
                    </div>
                    <p class="text-muted mb-3">Nikmati pengalaman baru dengan interface modern, fitur lengkap, dan performa yang lebih baik.</p>
                    <div class="d-flex gap-2">
                        <button onclick="goToDashboard()" class="btn btn-primary btn-sm">
                            <i class="bx bx-rocket me-1"></i>Coba Sekarang
                        </button>
                        <a href="{{ route('dashboard') }}" class="btn btn-outline-primary btn-sm">
                            <i class="bx bx-link-external me-1"></i>Buka di Tab Baru
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card shadow-sm rounded-4 border-0 h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-success bg-opacity-10 rounded-3 p-2 me-3">
                            <i class="bx bx-bar-chart" style="font-size:1.5rem; color:#4CAF50;"></i>
                        </div>
                        <h5 class="mb-0 fw-bold">Analisis Laporan</h5>
                    </div>
                    <p class="text-muted mb-3">Akses laporan Pareto dan analisis ABC untuk optimasi manajemen inventory Anda.</p>
                    <div class="d-flex gap-2">
                        <a href="{{ route('laporan.pareto') }}" class="btn btn-success btn-sm">
                            <i class="bx bx-bar-chart me-1"></i>Lihat Laporan
                        </a>
                        <button class="btn btn-outline-success btn-sm">
                            <i class="bx bx-download me-1"></i>Export Data
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Migration Notice -->
    <div class="alert alert-info rounded-4 border-0" role="alert">
        <div class="d-flex align-items-center">
            <i class="bx bx-info-circle me-3" style="font-size:1.5rem;"></i>
            <div>
                <h6 class="alert-heading mb-1">Sistem Sedang Dalam Transisi</h6>
                <p class="mb-0">Kami sedang memigrasikan ke dashboard baru yang lebih modern. Anda dapat menggunakan kedua versi selama masa transisi ini.</p>
            </div>
        </div>
    </div>
</div>

<style>
.hover-card {
    transition: all 0.3s ease;
    cursor: pointer;
}

.hover-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.1) !important;
}

#loadingOverlay {
    opacity: 0;
    pointer-events: none;
    transition: opacity 0.5s ease;
}

#loadingOverlay.show {
    opacity: 1;
    pointer-events: all;
}

.fade-out {
    opacity: 0;
    transform: translateY(20px);
    transition: all 0.5s ease;
}
</style>

<script>
function goToDashboard() {
    // Show loading overlay
    document.getElementById('loadingOverlay').classList.add('show');
    
    // Add fade out effect to content
    document.getElementById('statsSection').classList.add('fade-out');
    document.getElementById('actionSection').classList.add('fade-out');
    
    // Redirect after animation
    setTimeout(() => {
        window.location.href = "{{ route('dashboard') }}";
    }, 1500);
}

function stayHere() {
    // Hide loading overlay if shown
    document.getElementById('loadingOverlay').classList.remove('show');
    
    // Show a toast or alert
    if (typeof bootstrap !== 'undefined' && bootstrap.Toast) {
        // If Bootstrap 5 is available
        const toastHtml = `
            <div class="toast align-items-center text-white bg-success border-0" role="alert">
                <div class="d-flex">
                    <div class="toast-body">
                        <i class="bx bx-check me-2"></i>Anda tetap di halaman ini. Dashboard baru tersedia kapan saja!
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            </div>
        `;
        
        // Add toast to page and show it
        const toastContainer = document.createElement('div');
        toastContainer.className = 'toast-container position-fixed top-0 end-0 p-3';
        toastContainer.innerHTML = toastHtml;
        document.body.appendChild(toastContainer);
        
        const toast = new bootstrap.Toast(toastContainer.querySelector('.toast'));
        toast.show();
        
        // Remove toast container after it's hidden
        setTimeout(() => {
            document.body.removeChild(toastContainer);
        }, 5000);
    } else {
        // Fallback alert
        alert('Anda tetap di halaman ini. Dashboard baru tersedia kapan saja!');
    }
}

// Auto redirect after 10 seconds (optional)
let autoRedirectTimer;
function startAutoRedirect() {
    autoRedirectTimer = setTimeout(() => {
        if (confirm('Ingin mencoba dashboard baru sekarang?')) {
            goToDashboard();
        }
    }, 10000); // 10 seconds
}

// Uncomment the line below if you want auto redirect
// startAutoRedirect();

// Add hover effects to stats cards
document.addEventListener('DOMContentLoaded', function() {
    const cards = document.querySelectorAll('.hover-card');
    cards.forEach(card => {
        card.addEventListener('click', function() {
            // Add click animation
            this.style.transform = 'scale(0.95)';
            setTimeout(() => {
                this.style.transform = '';
            }, 150);
        });
    });
});
</script>
@endsection
