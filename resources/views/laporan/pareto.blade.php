@extends('layouts.dashboard')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Reusable Header Component -->
    <x-dashboard-header 
        title="Analisis Pareto ABC" 
        subtitle="Klasifikasi barang berdasarkan nilai dan kontribusi penjualan"
        :showTabs="true"
        activeTab="analisis"
        :showBanner="true"
    />

    <!-- Pareto Analysis Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-8">
        <!-- Filter Section -->
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                <i class="fas fa-filter mr-2 text-blue-500"></i>Filter & Export
            </h3>
            
            <form method="GET" action="{{ route('laporan.pareto') }}" class="flex flex-wrap items-end gap-4">
                <div class="flex-1 min-w-48">
                    <label for="periode" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-calendar mr-2"></i>Periode
                    </label>
                    <input 
                        type="month" 
                        name="periode" 
                        id="periode"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors" 
                        value="{{ request('periode', date('Y-m')) }}"
                    >
                </div>
                
                <div class="flex-1 min-w-48">
                    <label for="sort_by" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-sort mr-2"></i>Urutkan Berdasarkan
                    </label>
                    <select 
                        name="sort_by" 
                        id="sort_by"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent focus:outline-none transition-colors" 
                    >
                        <option value="value" {{ request('sort_by', 'value') == 'value' ? 'selected' : '' }}>Total Nilai</option>
                        <option value="quantity" {{ request('sort_by') == 'quantity' ? 'selected' : '' }}>Total Kuantitas</option>
                    </select>
                </div>

                <div class="flex gap-3">
                    <button 
                        type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition-colors flex items-center"
                    >
                        <i class="fas fa-filter mr-2"></i>Filter Data
                    </button>
                    
                    <a 
                        href="{{ route('laporan.pareto.export', ['periode' => request('periode'), 'sort_by' => request('sort_by')]) }}" 
                        class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg font-medium transition-colors flex items-center text-decoration-none"
                    >
                        <i class="fas fa-file-excel mr-2"></i>Export Excel
                    </a>
                </div>
            </form>
        </div>

        <!-- ABC Categories Summary -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <!-- Category A -->
            <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center mr-3">
                            <span class="text-red-600 font-bold text-lg">A</span>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900">Kategori A</h3>
                            <p class="text-sm text-gray-500">High Value</p>
                        </div>
                    </div>
                </div>
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Jumlah Item:</span>
                        <span class="font-medium">{{ $analisis->where('kategori', 'A')->count() }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Kontribusi:</span>
                        <span class="font-medium text-red-600">
                            @php
                                $totalA = 0;
                                if (request('sort_by', 'value') == 'quantity') {
                                    $totalA = $analisis->where('kategori', 'A')->sum('total_qty');
                                } else {
                                    $totalA = $analisis->where('kategori', 'A')->sum('total_nilai');
                                }
                                echo $totalSumOfBasis > 0 ? '~' . round(($totalA / $totalSumOfBasis) * 100) . '%' : '0%';
                            @endphp
                        </span>
                    </div>
                </div>
            </div>

            <!-- Category B -->
            <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center mr-3">
                            <span class="text-yellow-600 font-bold text-lg">B</span>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900">Kategori B</h3>
                            <p class="text-sm text-gray-500">Medium Value</p>
                        </div>
                    </div>
                </div>
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Jumlah Item:</span>
                        <span class="font-medium">{{ $analisis->where('kategori', 'B')->count() }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Kontribusi:</span>
                        <span class="font-medium text-yellow-600">
                            @php
                                $totalB = 0;
                                if (request('sort_by', 'value') == 'quantity') {
                                    $totalB = $analisis->where('kategori', 'B')->sum('total_qty');
                                } else {
                                    $totalB = $analisis->where('kategori', 'B')->sum('total_nilai');
                                }
                                echo $totalSumOfBasis > 0 ? '~' . round(($totalB / $totalSumOfBasis) * 100) . '%' : '0%';
                            @endphp
                        </span>
                    </div>
                </div>
            </div>

            <!-- Category C -->
            <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                            <span class="text-green-600 font-bold text-lg">C</span>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900">Kategori C</h3>
                            <p class="text-sm text-gray-500">Low Value</p>
                        </div>
                    </div>
                </div>
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Jumlah Item:</span>
                        <span class="font-medium">{{ $analisis->where('kategori', 'C')->count() }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Kontribusi:</span>
                        <span class="font-medium text-green-600">
                            @php
                                $totalC = 0;
                                if (request('sort_by', 'value') == 'quantity') {
                                    $totalC = $analisis->where('kategori', 'C')->sum('total_qty');
                                } else {
                                    $totalC = $analisis->where('kategori', 'C')->sum('total_nilai');
                                }
                                echo $totalSumOfBasis > 0 ? '~' . round(($totalC / $totalSumOfBasis) * 100) . '%' : '0%';
                            @endphp
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Data Table -->
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">
                        <i class="fas fa-chart-bar mr-2 text-purple-500"></i>Detail Analisis Pareto
                    </h3>
                    <div class="flex items-center space-x-2 text-sm text-gray-500">
                        <i class="fas fa-info-circle"></i>
                        <span>{{ $analisis->count() }} item ditemukan</span>
                    </div>
                </div>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Barang</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Qty</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Nilai</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Persentase</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stok Saat Ini</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($analisis as $index => $item)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $index + 1 }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $item->nama_barang }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <div class="flex items-center">
                                        <i class="fas fa-boxes text-gray-400 mr-2"></i>
                                        {{ number_format($item->total_qty, 0, ',', '.') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <div class="flex items-center">
                                        <i class="fas fa-rupiah-sign text-gray-400 mr-2"></i>
                                       Rp {{ number_format($item->total_nilai , 0, ',', '.') }}

                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <div class="flex items-center">
                                        <div class="w-16 bg-gray-200 rounded-full h-2 mr-3">
                                            <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $item->persentase }}%"></div>
                                        </div>
                                        <span class="font-medium">{{ $item->persentase }}%</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($item->kategori == 'A')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            <i class="fas fa-star mr-1"></i>Kategori A
                                        </span>
                                    @elseif($item->kategori == 'B')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            <i class="fas fa-circle mr-1"></i>Kategori B
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-dot-circle mr-1"></i>Kategori C
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <div class="flex items-center">
                                        @if($item->stok_saat_ini < 10)
                                            <i class="fas fa-exclamation-triangle text-red-500 mr-2"></i>
                                            <span class="text-red-600 font-medium">{{ number_format($item->stok_saat_ini, 0, ',', '.') }}</span>
                                        @elseif($item->stok_saat_ini < 50)
                                            <i class="fas fa-exclamation-circle text-yellow-500 mr-2"></i>
                                            <span class="text-yellow-600 font-medium">{{ number_format($item->stok_saat_ini, 0, ',', '.') }}</span>
                                        @else
                                            <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                            <span class="text-green-600 font-medium">{{ number_format($item->stok_saat_ini, 0, ',', '.') }}</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-12">
                                    <div class="flex flex-col items-center">
                                        <i class="fas fa-chart-bar text-6xl text-gray-300 mb-4"></i>
                                        <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak ada data</h3>
                                        <p class="text-gray-500">Tidak ada data untuk periode yang dipilih</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pareto Principle Info -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mt-6">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <i class="fas fa-info-circle text-blue-500 text-xl"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-lg font-medium text-blue-900 mb-2">Tentang Analisis Pareto ABC</h3>
                    <div class="text-blue-800 space-y-2">
                        <p><strong>Kategori A (80% kontribusi):</strong> Item dengan kontribusi tinggi yang memerlukan kontrol ketat dan perhatian khusus dalam manajemen inventori.</p>
                        <p><strong>Kategori B (15% kontribusi):</strong> Item dengan kontribusi sedang yang memerlukan kontrol normal dengan review berkala.</p>
                        <p><strong>Kategori C (5% kontribusi):</strong> Item dengan kontribusi rendah yang dapat dikelola dengan kontrol sederhana.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Auto-set current month if no period selected
    document.addEventListener('DOMContentLoaded', function() {
        const periodeInput = document.getElementById('periode');
        if (!periodeInput.value) {
            const now = new Date();
            const currentMonth = now.getFullYear() + '-' + String(now.getMonth() + 1).padStart(2, '0');
            periodeInput.value = currentMonth;
        }
    });
</script>
@endsection
