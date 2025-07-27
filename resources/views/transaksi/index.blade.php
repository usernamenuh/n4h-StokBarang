@extends('layouts.dashboard')

@section('title', 'Data Transaksi')

@section('content')
    <div class="min-h-screen bg-gray-50">
        <!-- Reusable Header Component -->
        <x-dashboard-header title="Data Transaksi" subtitle="Kelola data transaksi dan riwayat penjualan" :showTabs="true"
            activeTab="transaksi" :showBanner="true" />

        <!-- Main Content -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-8">
            <!-- Success Alert -->
            @if (session('success'))
                <div id="alert-success" class="mb-6 flex items-center p-4 bg-green-50 border border-green-200 rounded-lg">
                    <div class="flex-shrink-0">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-3 flex-1">
                        <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                    </div>
                    <button onclick="document.getElementById('alert-success').remove()"
                        class="ml-3 text-green-600 hover:text-green-800">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            @endif

            <!-- Error Alert -->
            @if (session('danger') || session('error'))
                <div id="alert-danger" class="mb-6 flex items-center p-4 bg-red-50 border border-red-200 rounded-lg">
                    <div class="flex-shrink-0">
                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-3 flex-1">
                        <p class="text-sm font-medium text-red-800">{{ session('danger') ?? session('error') }}</p>
                    </div>
                    <button onclick="document.getElementById('alert-danger').remove()"
                        class="ml-3 text-red-600 hover:text-red-800">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            @endif

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-lg border border-gray-200 p-6 shadow-sm">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="text-sm font-medium text-gray-600">Total Transaksi</h3>
                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-file-alt text-blue-600 text-sm"></i>
                        </div>
                    </div>
                    <div class="text-2xl font-bold text-gray-900">{{ number_format($transaksis->count(), 0, ',', '.') }}
                    </div>
                    <p class="text-xs text-gray-500">Transaksi tercatat</p>
                </div>

                <div class="bg-white rounded-lg border border-gray-200 p-6 shadow-sm">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="text-sm font-medium text-gray-600">Total Penjualan</h3>
                        <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-chart-line text-green-600 text-sm"></i>
                        </div>
                    </div>
                    <div class="text-2xl font-bold text-gray-900">Rp
                        {{ number_format($transaksis->sum('total'), 0, ',', '.') }}</div>
                    <p class="text-xs text-gray-500">Nilai penjualan</p>
                </div>

                <div class="bg-white rounded-lg border border-gray-200 p-6 shadow-sm">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="text-sm font-medium text-gray-600">Rata-rata Transaksi</h3>
                        <div class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-calculator text-orange-600 text-sm"></i>
                        </div>
                    </div>
                    <div class="text-2xl font-bold text-gray-900">Rp
                        {{ $transaksis->count() > 0 ? number_format($transaksis->avg('total'), 0, ',', '.') : '0' }}</div>
                    <p class="text-xs text-gray-500">Per transaksi</p>
                </div>

                <div class="bg-white rounded-lg border border-gray-200 p-6 shadow-sm">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="text-sm font-medium text-gray-600">Customer Unik</h3>
                        <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-users text-purple-600 text-sm"></i>
                        </div>
                    </div>
                    <div class="text-2xl font-bold text-gray-900">{{ $transaksis->pluck('customer')->unique()->count() }}
                    </div>
                    <p class="text-xs text-gray-500">Customer aktif</p>
                </div>
            </div>

            <!-- Action Buttons and Search -->
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-6">
                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3">
                    @if (!isset($role) || $role != 'owner')
                        <a href="{{ route('transaksi.create') }}"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200 shadow-sm text-sm">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Tambah Transaksi
                        </a>
                        @php
                            $user = auth()->user();
                        @endphp

                        @if ($user->role !== 'owner')
                            <button onclick="openImportModal()"
                                class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors duration-200 shadow-sm text-sm">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                </svg>
                                Import Excel
                            </button>
                        @endif

                    @endif
                </div>

                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                    <!-- Search -->
                    <div class="relative min-w-0 flex-1 sm:w-64">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input type="text" id="searchInput" placeholder="Cari transaksi..."
                            class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 text-sm">
                    </div>

                    <!-- Filter by Customer -->
                    <select id="customerFilter"
                        class="block w-full sm:w-48 px-3 py-2 border border-gray-300 rounded-lg bg-white text-sm focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Semua Customer</option>
                        @foreach ($transaksis->pluck('customer')->unique()->filter()->sort() as $customer)
                            <option value="{{ $customer }}">{{ $customer }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Main Table Card -->
            <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900">
                            <i class="fas fa-receipt mr-2 text-blue-500"></i>
                            Daftar Transaksi
                        </h3>
                        <div class="text-sm text-gray-500">
                            Total: <span class="font-medium text-gray-900">{{ $transaksis->count() }}</span> transaksi
                        </div>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full table-auto" id="transaksi-table">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    No</th>
                                <th
                                    class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Tanggal & Nomor</th>
                                <th
                                    class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Customer</th>
                                <th
                                    class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Subtotal</th>
                                <th
                                    class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Diskon</th>
                                <th
                                    class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Ongkir</th>
                                <th
                                    class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Total</th>
                                <th
                                    class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    User</th>
                                <th
                                    class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider w-20">
                                    Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($transaksis as $i => $transaksi)
                                <tr class="hover:bg-gray-50 transition-colors duration-150">
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900 font-medium">
                                        {{ $i + 1 }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{ $transaksi->nomor }}</div>
                                            <div class="text-xs text-gray-500">
                                                {{ \Carbon\Carbon::parse($transaksi->tanggal)->format('d M Y') }}</div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="text-sm font-medium text-gray-900 max-w-32 truncate">
                                            {{ $transaksi->customer }}</div>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <div class="text-sm font-semibold text-blue-600">
                                            <i class="fas fa-rupiah-sign mr-1"></i>
                                            Rp {{ number_format($transaksi->subtotal, 0, '.', '.') }}
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <div class="text-sm font-semibold text-orange-600">
                                            <i class="fas fa-percentage mr-1"></i>
                                            Rp {{ number_format($transaksi->diskon, 0, '.', '.') }}
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <div class="text-sm font-semibold text-purple-600">
                                            <i class="fas fa-truck mr-1"></i>
                                            Rp {{ number_format($transaksi->ongkir, 0, '.', '.') }}
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <div class="text-sm font-bold text-green-600">
                                            <i class="fas fa-rupiah-sign mr-1"></i>
                                            Rp {{ number_format($transaksi->total, 0, '.', '.') }}
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-6 w-6">
                                                <div
                                                    class="h-6 w-6 rounded-full bg-gray-200 flex items-center justify-center">
                                                    <span class="text-xs font-medium text-gray-600">
                                                        {{ substr($transaksi->user->name ?? 'U', 0, 1) }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="ml-2">
                                                <div class="text-xs font-medium text-gray-900 max-w-20 truncate">
                                                    {{ $transaksi->user->name ?? 'Unknown' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-center">
                                        <div class="relative inline-block text-left">
                                            <button type="button"
                                                class="inline-flex items-center justify-center w-8 h-8 text-gray-400 hover:text-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 rounded-full hover:bg-gray-100 transition-colors"
                                                onclick="toggleDropdown('dropdown-{{ $transaksi->id }}')">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path
                                                        d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                                                </svg>
                                            </button>
                                            <div id="dropdown-{{ $transaksi->id }}"
                                                class="hidden absolute right-0 z-10 mt-2 w-44 bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5 border border-gray-200">
                                                <div class="py-1">
                                                    <a href="{{ route('transaksi.show', $transaksi->id) }}"
                                                        class="flex items-center px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                                                        <svg class="w-4 h-4 mr-2 text-blue-500" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                        </svg>
                                                        Lihat Detail
                                                    </a>
                                                    <a href="{{ route('transaksi.edit', $transaksi->id) }}"
                                                        class="flex items-center px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                                                        <svg class="w-4 h-4 mr-2 text-yellow-500" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                        </svg>
                                                        Edit
                                                    </a>
                                                    <button
                                                        onclick="openDeleteModal('{{ $transaksi->id }}', '{{ $transaksi->nomor }}')"
                                                        class="flex items-center w-full px-3 py-2 text-sm text-red-700 hover:bg-red-50 transition-colors">
                                                        <svg class="w-4 h-4 mr-2 text-red-500" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                        Hapus
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center">
                                            <svg class="w-12 h-12 text-gray-300 mb-4" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                            <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada data transaksi
                                            </h3>
                                            <p class="text-gray-500 mb-4">Mulai dengan menambahkan transaksi pertama Anda
                                            </p>
                                            @if (!isset($role) || $role != 'owner')
                                                <a href="{{ route('transaksi.create') }}"
                                                    class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                                    </svg>
                                                    Tambah Transaksi Pertama
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Import Modal for Transaksi -->
    <div id="importModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20">
            <div class="fixed inset-0 transition-opacity bg-black bg-opacity-60 backdrop-blur-sm"
                onclick="closeImportModal()"></div>

            <!-- Modal Container -->
            <div class="relative w-full max-w-lg mx-auto">
                <!-- Modal Header -->
                <div class="bg-gray-900 rounded-t-2xl px-6 py-4 text-white">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-purple-500 rounded-lg flex items-center justify-center">
                                <i class="fas fa-exchange-alt text-white text-sm"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold">Import Data Transaksi</h3>
                                <p class="text-sm text-gray-300">Upload file Excel untuk import data transaksi</p>
                            </div>
                        </div>
                        <button onclick="closeImportModal()" class="text-gray-400 hover:text-white transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Modal Content -->
                <div class="bg-white rounded-b-2xl p-8">
                    <form id="importForm" action="{{ route('transaksi.import') }}" method="POST"
                        enctype="multipart/form-data" class="space-y-6">
                        @csrf

                        <!-- File Upload Area -->
                        <div
                            class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-purple-400 transition-colors">
                            <input type="file" id="fileInput" name="file" accept=".xlsx,.xls,.csv" class="hidden"
                                required>
                            <div id="uploadArea" class="cursor-pointer"
                                onclick="document.getElementById('fileInput').click()">
                                <div
                                    class="w-16 h-16 mx-auto mb-4 bg-purple-100 rounded-full flex items-center justify-center">
                                    <svg class="w-8 h-8 text-purple-500" stroke="currentColor" fill="none"
                                        viewBox="0 0 48 48">
                                        <path
                                            d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </div>
                                <h4 class="text-lg font-semibold text-gray-900 mb-2">Upload File Excel</h4>
                                <p class="text-sm text-gray-600 mb-2">
                                    <span class="font-medium text-purple-600">Klik untuk upload</span> atau drag and drop
                                </p>
                                <p class="text-xs text-gray-500">Excel (.xlsx, .xls) atau CSV hingga 10MB</p>
                            </div>

                            <!-- File Info -->
                            <div id="fileInfo" class="hidden mt-6 p-4 bg-purple-50 rounded-lg">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <svg class="w-6 h-6 text-purple-600 mr-3" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        <span id="fileName" class="text-sm font-medium text-purple-900"></span>
                                    </div>
                                    <button type="button" onclick="clearFile()"
                                        class="text-purple-600 hover:text-purple-800">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Progress Bar -->
                        <div id="progressContainer" class="hidden">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm font-medium text-gray-700">Sedang mengimpor data transaksi...</span>
                                <span id="progressText" class="text-sm text-gray-500">0%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-3">
                                <div id="progressBar"
                                    class="bg-gradient-to-r from-purple-500 to-purple-600 h-3 rounded-full transition-all duration-300"
                                    style="width: 0%"></div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                            <button type="button" onclick="closeImportModal()"
                                class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition-colors">
                                Batal
                            </button>
                            <button type="submit" id="importButton"
                                class="px-8 py-3 bg-gradient-to-r from-purple-600 to-purple-700 text-white rounded-lg hover:from-purple-700 hover:to-purple-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed">
                                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                </svg>
                                Import Data Transaksi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div id="deleteModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" onclick="closeDeleteModal()"></div>
            <div
                class="inline-block w-full max-w-md p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-2xl">
                <div class="flex items-center justify-center w-12 h-12 mx-auto bg-red-100 rounded-full">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </div>
                <div class="mt-3 text-center">
                    <h3 class="text-lg font-medium leading-6 text-gray-900">Hapus Transaksi</h3>
                    <div class="mt-2">
                        <p class="text-sm text-gray-500">
                            Apakah Anda yakin ingin menghapus transaksi "<span id="deleteItemName"
                                class="font-semibold"></span>"?
                            Tindakan ini tidak dapat dibatalkan.
                        </p>
                    </div>
                </div>
                <div class="mt-6 grid grid-cols-2 gap-3">
                    <form id="deleteForm" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="w-full inline-flex justify-center px-4 py-2 text-sm font-medium text-white bg-red-600 border border-transparent rounded-md shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">
                            Hapus
                        </button>
                    </form>
                    <button type="button" onclick="closeDeleteModal()"
                        class="w-full inline-flex justify-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Auto hide alerts
                setTimeout(function() {
                    const alerts = document.querySelectorAll('#alert-success, #alert-danger');
                    alerts.forEach(alert => {
                        if (alert) {
                            alert.style.opacity = '0';
                            alert.style.transform = 'translateY(-10px)';
                            setTimeout(() => alert.remove(), 300);
                        }
                    });
                }, 5000);

                // Search functionality
                const searchInput = document.getElementById('searchInput');
                const customerFilter = document.getElementById('customerFilter');
                const tableRows = document.querySelectorAll('#transaksi-table tbody tr');

                function filterTable() {
                    const searchTerm = searchInput.value.toLowerCase();
                    const selectedCustomer = customerFilter.value.toLowerCase();

                    tableRows.forEach(row => {
                        if (row.cells.length === 1) return; // Skip empty state row

                        const nomor = row.cells[1].textContent.toLowerCase();
                        const customer = row.cells[2].textContent.toLowerCase();

                        const matchesSearch = nomor.includes(searchTerm) || customer.includes(searchTerm);
                        const matchesCustomer = selectedCustomer === '' || customer.includes(selectedCustomer);

                        if (matchesSearch && matchesCustomer) {
                            row.style.display = '';
                        } else {
                            row.style.display = 'none';
                        }
                    });
                }

                searchInput.addEventListener('input', filterTable);
                customerFilter.addEventListener('change', filterTable);

                // File input handling
                const fileInput = document.getElementById('fileInput');
                const fileInfo = document.getElementById('fileInfo');
                const fileName = document.getElementById('fileName');
                const uploadArea = document.getElementById('uploadArea');

                fileInput.addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    if (file) {
                        fileName.textContent = file.name;
                        fileInfo.classList.remove('hidden');
                        uploadArea.classList.add('hidden');
                    }
                });

                // Import form handling
                const importForm = document.getElementById('importForm');
                const progressContainer = document.getElementById('progressContainer');
                const progressBar = document.getElementById('progressBar');
                const progressText = document.getElementById('progressText');
                const importButton = document.getElementById('importButton');

                importForm.addEventListener('submit', function(e) {
                    e.preventDefault();

                    const formData = new FormData(this);

                    // Show progress
                    progressContainer.classList.remove('hidden');
                    importButton.disabled = true;
                    importButton.innerHTML =
                        '<svg class="w-4 h-4 inline mr-2 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>Mengimpor...';

                    // Simulate progress
                    let progress = 0;
                    const progressInterval = setInterval(() => {
                        progress += Math.random() * 30;
                        if (progress > 90) progress = 90;
                        progressBar.style.width = progress + '%';
                        progressText.textContent = Math.round(progress) + '%';
                    }, 200);

                    // Submit form
                    fetch(this.action, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    .getAttribute('content'),
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error(`HTTP error! status: ${response.status}`);
                            }
                            return response.json();
                        })
                        .then(data => {
                            clearInterval(progressInterval);
                            progressBar.style.width = '100%';
                            progressText.textContent = '100%';

                            setTimeout(() => {
                                if (data.success) {
                                    showNotification('success', data.message || 'Import berhasil!');
                                    closeImportModal();
                                    // Reload page to show new data
                                    setTimeout(() => location.reload(), 1000);
                                } else {
                                    showNotification('error', data.message || 'Import gagal!');
                                    resetImportForm();
                                }
                            }, 500);
                        })
                        .catch(error => {
                            clearInterval(progressInterval);
                            console.error('Import error:', error);
                            showNotification('error', 'Terjadi kesalahan saat import!');
                            resetImportForm();
                        });
                });
            });

            // Modal functions
            function openImportModal() {
                document.getElementById('importModal').classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }

            function closeImportModal() {
                document.getElementById('importModal').classList.add('hidden');
                document.body.style.overflow = 'auto';
                resetImportForm();
            }

            function resetImportForm() {
                const importButton = document.getElementById('importButton');
                const progressContainer = document.getElementById('progressContainer');
                const fileInfo = document.getElementById('fileInfo');
                const uploadArea = document.getElementById('uploadArea');
                const fileInput = document.getElementById('fileInput');

                importButton.disabled = false;
                importButton.innerHTML =
                    '<svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>Import Data Transaksi';
                progressContainer.classList.add('hidden');
                fileInfo.classList.add('hidden');
                uploadArea.classList.remove('hidden');
                fileInput.value = '';
            }

            function clearFile() {
                const fileInfo = document.getElementById('fileInfo');
                const uploadArea = document.getElementById('uploadArea');
                const fileInput = document.getElementById('fileInput');

                fileInfo.classList.add('hidden');
                uploadArea.classList.remove('hidden');
                fileInput.value = '';
            }

            // Dropdown functions
            function toggleDropdown(dropdownId) {
                const dropdown = document.getElementById(dropdownId);
                const allDropdowns = document.querySelectorAll('[id^="dropdown-"]');

                // Close all other dropdowns
                allDropdowns.forEach(d => {
                    if (d.id !== dropdownId) {
                        d.classList.add('hidden');
                    }
                });

                // Toggle current dropdown
                dropdown.classList.toggle('hidden');
            }

            // Close dropdowns when clicking outside
            document.addEventListener('click', function(event) {
                if (!event.target.closest('[onclick*="toggleDropdown"]')) {
                    document.querySelectorAll('[id^="dropdown-"]').forEach(d => {
                        d.classList.add('hidden');
                    });
                }
            });

            // Delete modal functions
            function openDeleteModal(id, name) {
                document.getElementById('deleteItemName').textContent = name;
                document.getElementById('deleteForm').action = `/transaksi/${id}`;
                document.getElementById('deleteModal').classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }

            function closeDeleteModal() {
                document.getElementById('deleteModal').classList.add('hidden');
                document.body.style.overflow = 'auto';
            }

            // Notification function
            function showNotification(type, message) {
                const notification = document.createElement('div');
                notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg transform transition-all duration-300 ${
        type === 'success' ? 'bg-green-50 border border-green-200 text-green-800' : 'bg-red-50 border border-red-200 text-red-800'
    }`;

                notification.innerHTML = `
        <div class="flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                ${type === 'success' 
                    ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>'
                    : '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>'
                }
            </svg>
            <span class="font-medium">${message}</span>
        </div>
    `;

                document.body.appendChild(notification);

                // Auto remove after 5 seconds
                setTimeout(() => {
                    notification.style.opacity = '0';
                    notification.style.transform = 'translateX(100%)';
                    setTimeout(() => notification.remove(), 300);
                }, 5000);
            }

            // Close modal with Escape key
            document.addEventListener('keydown', function(event) {
                if (event.key === 'Escape') {
                    closeDeleteModal();
                    closeImportModal();
                }
            });
        </script>
    @endpush
@endsection
