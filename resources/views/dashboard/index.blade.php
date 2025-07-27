@extends('layouts.dashboard')

@section('title', 'Dashboard - StockMaster')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Dashboard Header -->
    <x-dashboard-header 
        title="Manajemen Stok Barang" 
        subtitle="Dashboard overview dan analisis inventory"
        :showTabs="true"
        activeTab="overview"
        :showBanner="true"
    />

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-8">
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg border border-gray-200 p-6 shadow-sm">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-sm font-medium text-gray-600">Total Revenue</h3>
                    <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-rupiah-sign text-green-600 text-sm"></i>
                    </div>
                </div>
                <div class="text-2xl font-bold text-gray-900">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
                <p class="text-xs text-gray-500">Total nilai stok</p>
            </div>
            
            <div class="bg-white rounded-lg border border-gray-200 p-6 shadow-sm">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-sm font-medium text-gray-600">Total Barang</h3>
                    <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-boxes text-blue-600 text-sm"></i>
                    </div>
                </div>
                <div class="text-2xl font-bold text-gray-900">{{ number_format($totalBarang, 0, ',', '.') }}</div>
                <p class="text-xs text-gray-500">Item terdaftar</p>
            </div>
            
            <div class="bg-white rounded-lg border border-gray-200 p-6 shadow-sm">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-sm font-medium text-gray-600">Transaksi</h3>
                    <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-exchange-alt text-purple-600 text-sm"></i>
                    </div>
                </div>
                <div class="text-2xl font-bold text-gray-900">{{ number_format($totalTransaksi, 0, ',', '.') }}</div>
                <p class="text-xs text-gray-500">Transaksi tercatat</p>
            </div>
            
            <div class="bg-white rounded-lg border border-gray-200 p-6 shadow-sm">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-sm font-medium text-gray-600">Stok Menipis</h3>
                    <div class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-exclamation-triangle text-orange-600 text-sm"></i>
                    </div>
                </div>
                <div class="text-2xl font-bold text-gray-900">{{ number_format($stokMenipis, 0, ',', '.') }}</div>
                <p class="text-xs text-gray-500">Perlu restock</p>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Sales Chart -->
            <div class="bg-white rounded-lg border border-gray-200 p-6 shadow-sm">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-900">Ringkasan Penjualan</h3>
                    <div class="flex space-x-2">
                        <button class="px-3 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-full">12 Bulan</button>
                        <button class="px-3 py-1 text-xs font-medium text-gray-500 hover:bg-gray-100 rounded-full">6 Bulan</button>
                    </div>
                </div>
                <div class="h-64 relative">
                    <!-- Chart Canvas -->
                    <canvas id="salesChart" class="w-full h-full"></canvas>
                </div>
                <!-- Month Labels - Fixed positioning -->
                <div class="grid grid-cols-12 gap-1 text-xs text-gray-500 mt-4 px-4">
                    <span class="text-center">Jan</span>
                    <span class="text-center">Feb</span>
                    <span class="text-center">Mar</span>
                    <span class="text-center">Apr</span>
                    <span class="text-center">Mei</span>
                    <span class="text-center">Jun</span>
                    <span class="text-center">Jul</span>
                    <span class="text-center">Agu</span>
                    <span class="text-center">Sep</span>
                    <span class="text-center">Okt</span>
                    <span class="text-center">Nov</span>
                    <span class="text-center">Des</span>
                </div>
            </div>

            <!-- Recent Sales -->
            <div class="bg-white rounded-lg border border-gray-200 p-6 shadow-sm">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-900">Penjualan Terbaru</h3>
                    <a href="{{ route('transaksi.index') }}" class="text-sm text-blue-600 hover:text-blue-800">Lihat Semua</a>
                </div>
                <div class="space-y-4">
                    <p class="text-sm text-gray-600 mb-4">Anda telah melakukan {{ $recentSalesCount }} penjualan bulan ini.</p>
                    
                    @forelse($recentSales as $sale)
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center">
                                <span class="text-sm font-medium text-gray-600">{{ strtoupper(substr($sale['name'], 0, 1)) }}</span>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $sale['name'] }}</p>
                                <p class="text-xs text-gray-500">{{ $sale['email'] }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-medium text-gray-900">+Rp {{ number_format($sale['amount'], 0, ',', '.') }}</p>
                            <p class="text-xs text-gray-500">{{ $sale['date'] ?? '' }}</p>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8">
                        <svg class="w-12 h-12 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        <p class="text-gray-500">Belum ada penjualan terbaru</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Additional Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Stock Distribution Chart -->
            <div class="bg-white rounded-lg border border-gray-200 p-6 shadow-sm">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-900">Distribusi Stok</h3>
                    <div class="flex space-x-2">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            Stok Aman
                        </span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                            Stok Menipis
                        </span>
                    </div>
                </div>
                <div class="h-64 relative">
                    <canvas id="stockChart" class="w-full h-full"></canvas>
                </div>
                <div class="mt-4 grid grid-cols-2 gap-4 text-center">
                    <div>
                        <div class="text-2xl font-bold text-green-600">{{ $totalBarang - $stokMenipis }}</div>
                        <div class="text-sm text-gray-500">Stok Aman</div>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-red-600">{{ $stokMenipis }}</div>
                        <div class="text-sm text-gray-500">Stok Menipis</div>
                    </div>
                </div>
            </div>

            <!-- Top Categories -->
            <div class="bg-white rounded-lg border border-gray-200 p-6 shadow-sm">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-900">Kategori Teratas</h3>
                </div>
                <div class="space-y-4">
                    @foreach($categoryData as $category)
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-3 h-3 {{ $category['color'] }} rounded-full"></div>
                            <span class="text-sm font-medium text-gray-900">{{ $category['name'] }}</span>
                        </div>
                        <div class="flex items-center space-x-3">
                            <span class="text-sm text-gray-500">{{ $category['count'] }} item</span>
                            <div class="w-16 bg-gray-200 rounded-full h-2">
                                <div class="{{ $category['color'] }} h-2 rounded-full" style="width: {{ $category['percentage'] }}%"></div>
                            </div>
                            <span class="text-xs text-gray-400 w-8">{{ $category['percentage'] }}%</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">
                        <i class="fas fa-clock mr-2 text-blue-500"></i>
                        Aktivitas Terbaru
                    </h3>
                    <a href="{{ route('barang.index') }}" class="text-sm text-blue-600 hover:text-blue-800">
                        Lihat Semua
                    </a>
                </div>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @forelse($barangs->take(5) as $barang)
                    <div class="flex items-center space-x-4">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-plus text-green-600 text-xs"></i>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900">{{ $barang->nama }}</p>
                            <p class="text-sm text-gray-500">Ditambahkan ke kategori {{ $barang->golongan ?: 'Tidak Berkategori' }}</p>
                        </div>
                        <div class="flex-shrink-0 text-sm text-gray-500">
                            {{ $barang->created_at->diffForHumans() }}
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8">
                        <svg class="w-12 h-12 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        <p class="text-gray-500">Belum ada aktivitas terbaru</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js Script -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Real sales data from PHP
    const salesData = @json($salesData);
    
    // Sales Chart
    const salesCtx = document.getElementById('salesChart').getContext('2d');
    new Chart(salesCtx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
            datasets: [{
                label: 'Penjualan',
                data: salesData,
                borderColor: 'rgb(59, 130, 246)',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            if (value >= 1000000) {
                                return 'Rp ' + (value / 1000000) + 'M';
                            } else if (value >= 1000) {
                                return 'Rp ' + (value / 1000) + 'K';
                            }
                            return 'Rp ' + value;
                        }
                    }
                },
                x: {
                    display: false
                }
            }
        }
    });

    // Stock Distribution Chart (Doughnut) - Real data
    const stockCtx = document.getElementById('stockChart').getContext('2d');
    const stokAman = {{ $totalBarang - $stokMenipis }};
    const stokMenipis = {{ $stokMenipis }};
    
    new Chart(stockCtx, {
        type: 'doughnut',
        data: {
            labels: ['Stok Aman', 'Stok Menipis'],
            datasets: [{
                data: [stokAman, stokMenipis],
                backgroundColor: [
                    'rgb(34, 197, 94)',
                    'rgb(239, 68, 68)'
                ],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            cutout: '70%'
        }
    });
});
</script>
@endsection
