@extends('layouts.demo')
@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <!-- Header -->
        <div class="flex justify-between items-center p-6 border-b border-gray-200">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Detail Barang</h1>
                <p class="text-sm text-gray-600 mt-1">Informasi lengkap data barang</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('barang.edit', $barang->id) }}" 
                   class="inline-flex items-center px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white font-medium rounded-lg transition-colors duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Edit
                </a>
                <a href="{{ route('barang.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition-colors duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Kembali
                </a>
            </div>
        </div>

        <!-- Content -->
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Left Column -->
                <div class="space-y-6">
                    <!-- Kode Barang -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wide mb-2">Kode Barang</h3>
                        <div class="flex items-center">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                {{ $barang->kode }}
                            </span>
                        </div>
                    </div>

                    <!-- Nama Barang -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wide mb-2">Nama Barang</h3>
                        <p class="text-lg font-semibold text-gray-900">{{ $barang->nama }}</p>
                    </div>

                    <!-- Does Pcs -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wide mb-2">Does Pcs</h3>
                        <div class="flex items-center">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-indigo-100 text-indigo-800">
                                {{ number_format($barang->does_pcs, 0) }}
                            </span>
                            <span class="ml-2 text-sm text-gray-600">unit konversi</span>
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="space-y-6">
                    <!-- Golongan -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wide mb-2">Golongan</h3>
                        <div class="flex items-center">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                {{ $barang->golongan }}
                            </span>
                        </div>
                    </div>

                    <!-- Harga Beli -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wide mb-2">Harga Beli</h3>
                        <div class="flex items-center">
                            <span class="text-2xl font-bold text-green-600">
                                Rp {{ number_format($barang->hbeli, 0, '.','.') }}
                            </span>
                        </div>
                    </div>

                    <!-- User Input -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wide mb-2">User Input</h3>
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center">
                                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900">{{ $barang->user->name ?? 'N/A' }}</p>
                                <p class="text-sm text-gray-500">{{ $barang->user->email ?? 'Email tidak tersedia' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Keterangan -->
            @if($barang->keterangan)
            <div class="mt-8 bg-gray-50 rounded-lg p-4">
                <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wide mb-2">Keterangan</h3>
                <p class="text-gray-900 leading-relaxed">{{ $barang->keterangan }}</p>
            </div>
            @endif

            <!-- Metadata -->
            <div class="mt-8 pt-6 border-t border-gray-200">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="flex items-center text-sm text-gray-500">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        <span>Dibuat: {{ $barang->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                    <div class="flex items-center text-sm text-gray-500">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        <span>Diupdate: {{ $barang->updated_at->format('d/m/Y H:i') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection