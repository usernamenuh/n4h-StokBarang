@extends('layouts.dashboard')

@section('title', 'Detail Transaksi')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <x-dashboard-header 
        title="Detail Transaksi" 
        subtitle="Informasi lengkap data transaksi dan detail item"
        :showTabs="true"
        activeTab="transaksi"
        :showBanner="true"
    />

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-8">
        <!-- Breadcrumb -->
        <nav class="flex mb-8" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('transaksi.index') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Data Transaksi
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                        </svg>
                        <span class="ml-1 text-sm font-medium text-gray-500">{{ $transaksi->nomor }}</span>
                    </div>
                </li>
            </ol>
        </nav>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Left Column - Transaction Info -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900">
                            <i class="fas fa-info-circle mr-2 text-blue-500"></i>
                            Detail Transaksi
                        </h3>
                        <div class="flex space-x-2">
                            <a href="{{ route('transaksi.edit', $transaksi->id) }}" 
                               class="inline-flex items-center px-3 py-2 bg-yellow-600 hover:bg-yellow-700 text-white text-sm font-medium rounded-lg transition-colors">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                Edit
                            </a>
                            <a href="{{ route('transaksi.index') }}" 
                               class="inline-flex items-center px-3 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg transition-colors">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                                </svg>
                                Kembali
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="p-6 space-y-6">
                    <!-- Nomor Transaksi -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h4 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-2">Nomor Transaksi</h4>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                            {{ $transaksi->nomor }}
                        </span>
                    </div>

                    <!-- Tanggal -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h4 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-2">Tanggal Transaksi</h4>
                        <p class="text-lg font-semibold text-gray-900">{{ \Carbon\Carbon::parse($transaksi->tanggal)->format('d F Y') }}</p>
                        <p class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($transaksi->tanggal)->format('l') }}</p>
                    </div>

                    <!-- Customer -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h4 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-2">Customer</h4>
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10">
                                <div class="h-10 w-10 rounded-full bg-purple-100 flex items-center justify-center">
                                    <span class="text-sm font-medium text-purple-600">
                                        {{ substr($transaksi->customer, 0, 1) }}
                                    </span>
                                </div>
                            </div>
                            <div class="ml-3">
                                <p class="text-lg font-semibold text-gray-900">{{ $transaksi->customer }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Ongkir & Print -->
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h4 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-2">Ongkos Kirim</h4>
                            <p class="text-lg font-semibold text-purple-600">
                                <i class="fas fa-truck mr-1"></i>
                                Rp {{ number_format($transaksi->ongkir ?? 0, 0, '.', '.') }}
                            </p>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h4 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-2">Jumlah Print</h4>
                            <p class="text-lg font-semibold text-orange-600">
                                <i class="fas fa-print mr-1"></i>
                                {{ $transaksi->jum_print ?? 0 }}
                            </p>
                        </div>
                    </div>

                    <!-- User Input -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h4 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-2">User Input</h4>
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-8 w-8">
                                <div class="h-8 w-8 rounded-full bg-gray-300 flex items-center justify-center">
                                    <span class="text-xs font-medium text-gray-600">
                                        {{ substr($transaksi->user->name ?? 'U', 0, 1) }}
                                    </span>
                                </div>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900">{{ $transaksi->user->name ?? 'N/A' }}</p>
                                <p class="text-sm text-gray-500">{{ $transaksi->user->email ?? 'Email tidak tersedia' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Keterangan -->
                    @if($transaksi->keterangan)
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h4 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-2">Keterangan</h4>
                        <p class="text-gray-900 leading-relaxed">{{ $transaksi->keterangan }}</p>
                    </div>
                    @endif

                    <!-- Metadata -->
                    <div class="pt-4 border-t border-gray-200">
                        <div class="grid grid-cols-1 gap-2">
                            <div class="flex items-center text-sm text-gray-500">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                </svg>
                                <span>Dibuat: {{ $transaksi->created_at->format('d/m/Y H:i') }}</span>
                            </div>
                            <div class="flex items-center text-sm text-gray-500">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                <span>Diupdate: {{ $transaksi->updated_at->format('d/m/Y H:i') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column - Transaction Details -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-green-50 to-emerald-50">
                    <h3 class="text-lg font-semibold text-gray-900">
                        <i class="fas fa-list mr-2 text-green-500"></i>
                        Detail Item Transaksi
                    </h3>
                </div>
                
                <div class="p-6">
                    @if($transaksi->details && $transaksi->details->count() > 0)
                        <div class="space-y-4">
                            @foreach($transaksi->details as $index => $detail)
                            <div class="border border-gray-200 rounded-lg p-4 bg-gray-50">
                                <div class="flex items-center justify-between mb-3">
                                    <h4 class="text-sm font-medium text-gray-700">Item #{{ $index + 1 }}</h4>
                                    <span class="text-sm font-semibold text-green-600">
                                        Rp {{ number_format($detail->total_item ?? 0, 0, '.', '.') }}
                                    </span>
                                </div>
                                
                                <div class="space-y-3">
                                    <!-- Barang -->
                                    <div>
                                        <span class="text-xs font-medium text-gray-500 uppercase tracking-wide">Barang</span>
                                        <p class="text-sm font-medium text-gray-900">{{ $detail->barang->nama ?? 'N/A' }}</p>
                                        <p class="text-xs text-gray-500">{{ $detail->barang->kode ?? 'N/A' }}</p>
                                    </div>

                                    <!-- Kuantitas & Harga -->
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <span class="text-xs font-medium text-gray-500 uppercase tracking-wide">Kuantitas</span>
                                            <p class="text-sm font-medium text-gray-900">{{ number_format($detail->qty ?? 0, 0, '.', '.') }}</p>
                                        </div>
                                        <div>
                                            <span class="text-xs font-medium text-gray-500 uppercase tracking-wide">Harga Satuan</span>
                                            <p class="text-sm font-medium text-gray-900">Rp {{ number_format($detail->harga_satuan ?? 0, 0, '.', '.') }}</p>
                                        </div>
                                    </div>

                                    <!-- Diskon -->
                                    @if($detail->diskon > 0)
                                    <div>
                                        <span class="text-xs font-medium text-gray-500 uppercase tracking-wide">Diskon Item</span>
                                        <p class="text-sm font-medium text-orange-600">-Rp {{ number_format($detail->diskon, 0, '.', '.') }}</p>
                                    </div>
                                    @endif

                                    <!-- Keterangan Item -->
                                    @if($detail->keterangan)
                                    <div>
                                        <span class="text-xs font-medium text-gray-500 uppercase tracking-wide">Keterangan</span>
                                        <p class="text-sm text-gray-700">{{ $detail->keterangan_item }}</p>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="w-12 h-12 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                            </svg>
                            <p class="text-gray-500">Tidak ada detail item</p>
                        </div>
                    @endif

                    <!-- Summary -->
                    <div class="mt-6 p-4 bg-blue-50 rounded-lg border border-blue-200">
                        <div class="space-y-2">
                            <div class="flex justify-between text-sm">
                                <span class="font-medium text-gray-700">Subtotal:</span>
                                <span class="font-semibold text-gray-900">Rp {{ number_format($transaksi->subtotal ?? 0, 0, '.', '.') }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="font-medium text-gray-700">Ongkir:</span>
                                <span class="font-semibold text-gray-900">Rp {{ number_format($transaksi->ongkir ?? 0, 0, '.', '.') }}</span>
                            </div>
                            <div class="flex justify-between text-lg border-t border-blue-300 pt-2">
                                <span class="font-bold text-gray-900">Total:</span>
                                <span class="font-bold text-blue-600">Rp {{ number_format(($transaksi->subtotal ?? 0) + ($transaksi->ongkir ?? 0), 0, '.', '.') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
