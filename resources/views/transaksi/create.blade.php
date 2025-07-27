@extends('layouts.dashboard')

@section('title', 'Tambah Transaksi Baru')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <x-dashboard-header 
        title="Tambah Transaksi Baru" 
        subtitle="Tambahkan data transaksi baru dengan detail item"
        :showTabs="true"
        activeTab="transaksi"
        :showBanner="true"
    />

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-8">
        <!-- Breadcrumb -->
        <nav class="flex mb-0" aria-label="Breadcrumb">
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
                        <span class="ml-1 text-sm font-medium text-gray-500">Tambah Transaksi</span>
                    </div>
                </li>
            </ol>
        </nav>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        <form action="{{ route('transaksi.store') }}" method="POST" class="space-y-6">
            @csrf
            
            <!-- Main Form Card -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Left Column - Transaction Info -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Informasi Transaksi
                        </h3>
                    </div>
                    
                    <div class="p-6 space-y-4">
                        <!-- Tanggal -->
                        <div class="space-y-2">
                            <label for="tanggal" class="block text-sm font-semibold text-gray-700">
                                Tanggal <span class="text-red-500">*</span>
                            </label>
                            <input type="date" 
                                   name="tanggal" 
                                   id="tanggal" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none transition-all duration-200 @error('tanggal') border-red-500 ring-2 ring-red-200 @enderror" 
                                   value="{{ old('tanggal', date('Y-m-d')) }}" 
                                   required>
                            @error('tanggal')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Nomor Transaksi -->
                        <div class="space-y-2">
                            <label for="nomor" class="block text-sm font-semibold text-gray-700">
                                Nomor Transaksi <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   name="nomor" 
                                   id="nomor" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none transition-all duration-200 @error('nomor') border-red-500 ring-2 ring-red-200 @enderror" 
                                   value="{{ old('nomor') }}" 
                                   placeholder="Masukkan nomor transaksi"
                                   required>
                            @error('nomor')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Customer -->
                        <div class="space-y-2">
                            <label for="customer" class="block text-sm font-semibold text-gray-700">
                                Customer <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   name="customer" 
                                   id="customer" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none transition-all duration-200 @error('customer') border-red-500 ring-2 ring-red-200 @enderror" 
                                   value="{{ old('customer') }}" 
                                   placeholder="Nama customer"
                                   required>
                            @error('customer')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Ongkos Kirim -->
                        <div class="space-y-2">
                            <label for="ongkir" class="block text-sm font-semibold text-gray-700">
                                Ongkos Kirim
                            </label>
                            <div class="relative">
                                <span class="absolute left-3 top-3 text-gray-500">Rp</span>
                                @php
                                    $ongkirValue = old('ongkir', 0);
                                    $ongkirFormatted = $ongkirValue ? number_format($ongkirValue, 0, ',', '.') : '';
                                @endphp
                                <input type="text" 
                                       name="ongkir_display" 
                                       id="ongkir_display" 
                                       class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none transition-all duration-200 @error('ongkir') border-red-500 ring-2 ring-red-200 @enderror" 
                                       value="{{ $ongkirFormatted }}" 
                                       placeholder="0">
                                <input type="hidden" name="ongkir" id="ongkir" value="{{ $ongkirValue }}">
                            </div>
                            @error('ongkir')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Jumlah Print -->
                        <div class="space-y-2">
                            <label for="jum_print" class="block text-sm font-semibold text-gray-700">
                                Jumlah Print
                            </label>
                            <input type="number" 
                                   name="jum_print" 
                                   id="jum_print" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none transition-all duration-200 @error('jum_print') border-red-500 ring-2 ring-red-200 @enderror" 
                                   value="{{ old('jum_print', 0) }}" 
                                   min="0"
                                   placeholder="0">
                            @error('jum_print')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Keterangan -->
                        <div class="space-y-2">
                            <label for="keterangan" class="block text-sm font-semibold text-gray-700">
                                Keterangan
                            </label>
                            <textarea name="keterangan" 
                                      id="keterangan" 
                                      rows="3" 
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none transition-all duration-200 @error('keterangan') border-red-500 ring-2 ring-red-200 @enderror"
                                      placeholder="Keterangan tambahan...">{{ old('keterangan') }}</textarea>
                            @error('keterangan')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Right Column - Transaction Details -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-green-50 to-emerald-50">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                                Detail Transaksi
                            </h3>
                            <button type="button" 
                                    id="add-detail-row"
                                    class="inline-flex items-center px-3 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                </svg>
                                Tambah Item
                            </button>
                        </div>
                    </div>
                    
                    <div class="p-6">
                        <div id="transaction-details-container" class="space-y-4 max-h-96 overflow-y-auto">
                            @if (old('details'))
                                @foreach (old('details') as $index => $detail)
                                    <div class="detail-item border border-gray-200 rounded-lg p-4 bg-gray-50 relative">
                                        <div class="flex items-center justify-between mb-4">
                                            <h4 class="text-sm font-medium text-gray-700">Item #{{ $index + 1 }}</h4>
                                            <button type="button" class="text-red-500 hover:text-red-700 transition-colors remove-detail-row">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                </svg>
                                            </button>
                                        </div>
                                        
                                        <div class="grid grid-cols-1 gap-3">
                                            <!-- Barang -->
                                            <div class="space-y-1">
                                                <label class="block text-xs font-medium text-gray-700">Barang <span class="text-red-500">*</span></label>
                                                <select name="details[{{ $index }}][barang_id]" 
                                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none text-sm" 
                                                        required>
                                                    <option value="">Pilih Barang</option>
                                                    @foreach($barangs as $barang)
                                                        <option value="{{ $barang->id }}" {{ (old("details.$index.barang_id", $detail['barang_id'] ?? '') == $barang->id) ? 'selected' : '' }}>
                                                            {{ $barang->nama }} ({{ $barang->kode }})
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="grid grid-cols-2 gap-3">
                                                <!-- Kuantitas -->
                                                <div class="space-y-1">
                                                    <label class="block text-xs font-medium text-gray-700">Kuantitas <span class="text-red-500">*</span></label>
                                                    <input type="number" 
                                                           step="0.01"
                                                           name="details[{{ $index }}][qty]" 
                                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none text-sm" 
                                                           value="{{ old("details.$index.qty", $detail['qty'] ?? 1) }}" 
                                                           required 
                                                           min="0.01">
                                                </div>

                                                <!-- Harga Satuan -->
                                                <div class="space-y-1">
                                                    <label class="block text-xs font-medium text-gray-700">Harga Satuan <span class="text-red-500">*</span></label>
                                                    <div class="relative">
                                                        <span class="absolute left-2 top-2 text-gray-500 text-xs">Rp</span>
                                                        @php
                                                            $hargaSatuanValue = old("details.$index.harga_satuan", $detail['harga_satuan'] ?? 0);
                                                            $hargaSatuanFormatted = $hargaSatuanValue ? number_format($hargaSatuanValue, 0, ',', '.') : '';
                                                        @endphp
                                                        <input type="text" 
                                                               name="details[{{ $index }}][harga_satuan_display]" 
                                                               class="w-full pl-8 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none text-sm price-input" 
                                                               value="{{ $hargaSatuanFormatted }}" 
                                                               placeholder="0">
                                                        <input type="hidden" name="details[{{ $index }}][harga_satuan]" value="{{ $hargaSatuanValue }}">
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Diskon Item -->
                                            <div class="space-y-1">
                                                <label class="block text-xs font-medium text-gray-700">Diskon Item</label>
                                                <div class="relative">
                                                    <span class="absolute left-2 top-2 text-gray-500 text-xs">Rp</span>
                                                    @php
                                                        $diskonValue = old("details.$index.discount", $detail['discount'] ?? 0);
                                                        $diskonFormatted = $diskonValue ? number_format($diskonValue, 0, ',', '.') : '';
                                                    @endphp
                                                    <input type="text" 
                                                           name="details[{{ $index }}][discount_display]" 
                                                           class="w-full pl-8 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none text-sm price-input" 
                                                           value="{{ $diskonFormatted }}" 
                                                           placeholder="0">
                                                    <input type="hidden" name="details[{{ $index }}][discount]" value="{{ $diskonValue }}">
                                                </div>
                                            </div>

                                            <!-- Keterangan Item -->
                                            <div class="space-y-1">
                                                <label class="block text-xs font-medium text-gray-700">Keterangan Item</label>
                                                <textarea name="details[{{ $index }}][keterangan]" 
                                                          rows="2" 
                                                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none text-sm"
                                                          placeholder="Keterangan item...">{{ old("details.$index.keterangan", $detail['keterangan'] ?? '') }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="detail-item border border-gray-200 rounded-lg p-4 bg-gray-50 relative">
                                    <div class="flex items-center justify-between mb-4">
                                        <h4 class="text-sm font-medium text-gray-700">Item #1</h4>
                                        <button type="button" class="text-red-500 hover:text-red-700 transition-colors remove-detail-row">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                        </button>
                                    </div>
                                    
                                    <div class="grid grid-cols-1 gap-3">
                                        <!-- Barang -->
                                        <div class="space-y-1">
                                            <label class="block text-xs font-medium text-gray-700">Barang <span class="text-red-500">*</span></label>
                                            <select name="details[0][barang_id]" 
                                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none text-sm" 
                                                    required>
                                                <option value="">Pilih Barang</option>
                                                @foreach($barangs as $barang)
                                                    <option value="{{ $barang->id }}">{{ $barang->nama }} ({{ $barang->kode }})</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="grid grid-cols-2 gap-3">
                                            <!-- Kuantitas -->
                                            <div class="space-y-1">
                                                <label class="block text-xs font-medium text-gray-700">Kuantitas <span class="text-red-500">*</span></label>
                                                <input type="number" 
                                                       step="0.01"
                                                       name="details[0][qty]" 
                                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none text-sm" 
                                                       value="1" 
                                                       required 
                                                       min="0.01">
                                            </div>

                                            <!-- Harga Satuan -->
                                            <div class="space-y-1">
                                                <label class="block text-xs font-medium text-gray-700">Harga Satuan <span class="text-red-500">*</span></label>
                                                <div class="relative">
                                                    <span class="absolute left-2 top-2 text-gray-500 text-xs">Rp</span>
                                                    <input type="text" 
                                                           name="details[0][harga_satuan_display]" 
                                                           class="w-full pl-8 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none text-sm price-input" 
                                                           placeholder="0">
                                                    <input type="hidden" name="details[0][harga_satuan]" value="0">
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Diskon Item -->
                                        <div class="space-y-1">
                                            <label class="block text-xs font-medium text-gray-700">Diskon Item</label>
                                            <div class="relative">
                                                <span class="absolute left-2 top-2 text-gray-500 text-xs">Rp</span>
                                                <input type="text" 
                                                       name="details[0][discount_display]" 
                                                       class="w-full pl-8 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none text-sm price-input" 
                                                       placeholder="0">
                                                <input type="hidden" name="details[0][discount]" value="0">
                                            </div>
                                        </div>

                                        <!-- Keterangan Item -->
                                        <div class="space-y-1">
                                            <label class="block text-xs font-medium text-gray-700">Keterangan Item</label>
                                            <textarea name="details[0][keterangan]" 
                                                      rows="2" 
                                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none text-sm"
                                                      placeholder="Keterangan item..."></textarea>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end space-x-4 pt-6">
                <a href="{{ route('transaksi.index') }}" 
                   class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 font-medium">
                    Batal
                </a>
                <button type="submit" 
                        class="px-8 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 font-medium shadow-sm hover:shadow-md">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Simpan Transaksi
                </button>
            </div>
        </form>
    </div>
</div>

<script>
let detailIndex = {{ old('details') ? count(old('details')) : 1 }};

// Format number with dots as thousand separators
function formatNumber(num) {
    return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
}

// Remove dots and convert to number
function unformatNumber(str) {
    return str.replace(/\./g, '');
}

// Setup price input formatting
function setupPriceInput(displayInput, hiddenInput) {
    displayInput.addEventListener('input', function(e) {
        let value = e.target.value;
        
        // Remove all non-digit characters
        value = value.replace(/[^\d]/g, '');
        
        // Update hidden field with raw number
        hiddenInput.value = value;
        
        // Format display with dots
        if (value) {
            e.target.value = formatNumber(value);
        } else {
            e.target.value = '';
        }
    });

    // Handle keypress for price inputs (only allow numbers)
    displayInput.addEventListener('keypress', function(e) {
        // Allow only numbers
        if (!/[0-9]/.test(e.key) && !['Backspace', 'Delete', 'Tab', 'Enter', 'ArrowLeft', 'ArrowRight'].includes(e.key)) {
            e.preventDefault();
        }
    });
}

// Initialize existing price inputs
document.addEventListener('DOMContentLoaded', function() {
    // Setup ongkir formatting
    const ongkirDisplay = document.getElementById('ongkir_display');
    const ongkirHidden = document.getElementById('ongkir');
    if (ongkirDisplay && ongkirHidden) {
        setupPriceInput(ongkirDisplay, ongkirHidden);
    }

    // Setup existing detail price inputs
    document.querySelectorAll('.price-input').forEach(function(displayInput) {
        const hiddenInput = displayInput.nextElementSibling;
        if (hiddenInput && hiddenInput.type === 'hidden') {
            setupPriceInput(displayInput, hiddenInput);
        }
    });
});

document.getElementById('add-detail-row').addEventListener('click', function() {
    const container = document.getElementById('transaction-details-container');
    const newRow = document.createElement('div');
    newRow.className = 'detail-item border border-gray-200 rounded-lg p-4 bg-gray-50 relative';
    newRow.innerHTML = `
        <div class="flex items-center justify-between mb-4">
            <h4 class="text-sm font-medium text-gray-700">Item #${detailIndex + 1}</h4>
            <button type="button" class="text-red-500 hover:text-red-700 transition-colors remove-detail-row">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        
        <div class="grid grid-cols-1 gap-3">
            <!-- Barang -->
            <div class="space-y-1">
                <label class="block text-xs font-medium text-gray-700">Barang <span class="text-red-500">*</span></label>
                <select name="details[${detailIndex}][barang_id]" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none text-sm" 
                        required>
                    <option value="">Pilih Barang</option>
                    @foreach($barangs as $barang)
                        <option value="{{ $barang->id }}">{{ $barang->nama }} ({{ $barang->kode }})</option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <!-- Kuantitas -->
                <div class="space-y-1">
                    <label class="block text-xs font-medium text-gray-700">Kuantitas <span class="text-red-500">*</span></label>
                    <input type="number" 
                           step="0.01"
                           name="details[${detailIndex}][qty]" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none text-sm" 
                           value="1" 
                           required 
                           min="0.01">
                </div>

                <!-- Harga Satuan -->
                <div class="space-y-1">
                    <label class="block text-xs font-medium text-gray-700">Harga Satuan <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <span class="absolute left-2 top-2 text-gray-500 text-xs">Rp</span>
                        <input type="text" 
                               name="details[${detailIndex}][harga_satuan_display]" 
                               class="w-full pl-8 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none text-sm price-input" 
                               placeholder="0">
                        <input type="hidden" name="details[${detailIndex}][harga_satuan]" value="0">
                    </div>
                </div>
            </div>

            <!-- Diskon Item -->
            <div class="space-y-1">
                <label class="block text-xs font-medium text-gray-700">Diskon Item</label>
                <div class="relative">
                    <span class="absolute left-2 top-2 text-gray-500 text-xs">Rp</span>
                    <input type="text" 
                           name="details[${detailIndex}][discount_display]" 
                           class="w-full pl-8 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none text-sm price-input" 
                           placeholder="0">
                    <input type="hidden" name="details[${detailIndex}][discount]" value="0">
                </div>
            </div>

            <!-- Keterangan Item -->
            <div class="space-y-1">
                <label class="block text-xs font-medium text-gray-700">Keterangan Item</label>
                <textarea name="details[${detailIndex}][keterangan]" 
                          rows="2" 
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none text-sm"
                          placeholder="Keterangan item..."></textarea>
            </div>
        </div>
    `;
    
    container.appendChild(newRow);
    
    // Setup price formatting for new inputs
    const newPriceInputs = newRow.querySelectorAll('.price-input');
    newPriceInputs.forEach(function(displayInput) {
        const hiddenInput = displayInput.nextElementSibling;
        if (hiddenInput && hiddenInput.type === 'hidden') {
            setupPriceInput(displayInput, hiddenInput);
        }
    });
    
    detailIndex++;
    updateItemNumbers();
    attachRemoveListeners();
});

function updateItemNumbers() {
    const items = document.querySelectorAll('.detail-item');
    items.forEach((item, index) => {
        const header = item.querySelector('h4');
        header.textContent = `Item #${index + 1}`;
    });
}

function attachRemoveListeners() {
    document.querySelectorAll('.remove-detail-row').forEach(button => {
        button.onclick = function() {
            const container = document.getElementById('transaction-details-container');
            if (container.children.length > 1) {
                this.closest('.detail-item').remove();
                updateItemNumbers();
            }
        };
    });
}

// Initial setup
attachRemoveListeners();

// Add custom CSS to remove any unwanted outlines
const style = document.createElement('style');
style.textContent = `
    .price-input:focus,
    input[type="text"]:focus,
    input[type="number"]:focus,
    input[type="date"]:focus,
    select:focus,
    textarea:focus {
        outline: none !important;
        box-shadow: 0 0 0 2px rgb(59 130 246 / 0.5) !important;
    }
`;
document.head.appendChild(style);
</script>
@endsection