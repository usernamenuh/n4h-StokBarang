@extends('layouts.dashboard')

@section('title', 'Edit Barang')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <x-dashboard-header 
        title="Edit Barang" 
        subtitle="Perbarui informasi data barang"
        :showTabs="true"
        activeTab="barang"
        :showBanner="true"
    />

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-8">
        <!-- Breadcrumb -->
        <nav class="flex mb-8" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('barang.index') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                        Data Barang
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                        </svg>
                        <span class="ml-1 text-sm font-medium text-gray-500">{{ Str::limit($barang->nama, 40) }}</span>
                    </div>
                </li>
            </ol>
        </nav>

        <!-- Success/Error Messages -->
        @if (session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg" role="alert">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                </div>
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg" role="alert">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <!-- Form Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <!-- Card Header -->
            <div class="px-6 py-5 border-b border-gray-200 bg-gradient-to-r from-purple-50 to-indigo-50">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-purple-100 rounded-xl flex items-center justify-center mr-4">
                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900">Form Edit Barang</h3>
                            <p class="text-sm text-gray-600 mt-1">Perbarui informasi barang: {{ $barang->nama }}</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-3">
                        <a href="{{ route('barang.show', $barang->id) }}" class="inline-flex items-center px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            Lihat Detail
                        </a>
                        <a href="{{ route('barang.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Kembali
                        </a>
                    </div>
                </div>
            </div>

            <!-- Form Content -->
            <div class="p-6">
                <form action="{{ route('barang.update', $barang->id) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Kode Barang -->
                        <div>
                            <label for="kode" class="block text-sm font-medium text-gray-700 mb-2">
                                Kode Barang <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   name="kode" 
                                   id="kode" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent focus:outline-none transition-colors duration-200 @error('kode') border-red-500 @enderror" 
                                   value="{{ old('kode', $barang->kode) }}" 
                                   placeholder="Masukkan kode barang"
                                   required>
                            @error('kode')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Nama Barang -->
                        <div>
                            <label for="nama" class="block text-sm font-medium text-gray-700 mb-2">
                                Nama Barang <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   name="nama" 
                                   id="nama" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent focus:outline-none transition-colors duration-200 @error('nama') border-red-500 @enderror" 
                                   value="{{ old('nama', $barang->nama) }}" 
                                   placeholder="Masukkan nama barang"
                                   required>
                            @error('nama')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Does Pcs -->
                        <div>
                            <label for="does_pcs" class="block text-sm font-medium text-gray-700 mb-2">
                                Does Pcs (Konversi Unit) <span class="text-red-500">*</span>
                            </label>
                            @php
                                $doesPcsValue = old('does_pcs', $barang->does_pcs);
                                $doesPcsDisplay = ($doesPcsValue == 0) ? '' : $doesPcsValue;
                            @endphp
                            <input type="number" 
                                   name="does_pcs" 
                                   id="does_pcs" 
                                   step="0.01"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent focus:outline-none transition-colors duration-200 @error('does_pcs') border-red-500 @enderror" 
                                   value="{{ $doesPcsDisplay }}" 
                                   min="0"
                                   placeholder="Masukkan nilai konversi"
                                   required>
                            <p class="mt-1 text-xs text-gray-500">Nilai konversi unit (contoh: 1 dus = 12 pcs)</p>
                            @error('does_pcs')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Golongan -->
                        <div>
                            <label for="golongan" class="block text-sm font-medium text-gray-700 mb-2">
                                Golongan (Kategori) <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   name="golongan" 
                                   id="golongan" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent focus:outline-none transition-colors duration-200 @error('golongan') border-red-500 @enderror" 
                                   value="{{ old('golongan', $barang->golongan) }}" 
                                   placeholder="Masukkan golongan barang"
                                   required>
                            @error('golongan')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Harga Beli -->
                        <div>
                            <label for="hbeli" class="block text-sm font-medium text-gray-700 mb-2">
                                Harga Beli <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute left-3 top-3 text-gray-500">Rp</span>
                                @php
                                    $hbeliValue = old('hbeli', $barang->hbeli);
                                    $hbeliFormatted = $hbeliValue ? number_format($hbeliValue, 0, ',', '.') : '';
                                @endphp
                                <input type="text" 
                                       name="hbeli_display" 
                                       id="hbeli_display" 
                                       class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent focus:outline-none transition-colors duration-200 @error('hbeli') border-red-500 @enderror" 
                                       value="{{ $hbeliFormatted }}" 
                                       placeholder="0"
                                       required>
                                <input type="hidden" name="hbeli" id="hbeli" value="{{ $hbeliValue }}">
                            </div>
                            @error('hbeli')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- User Input -->
                        <div>
                            <label for="user_id" class="block text-sm font-medium text-gray-700 mb-2">
                                User Input
                            </label>
                            <select name="user_id" 
                                    id="user_id" 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent focus:outline-none transition-colors duration-200 @error('user_id') border-red-500 @enderror">
                                <option value="">Pilih User</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ old('user_id', $barang->user_id) == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Keterangan -->
                    <div>
                        <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-2">
                            Keterangan
                        </label>
                        <textarea name="keterangan" 
                                  id="keterangan" 
                                  rows="4" 
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent focus:outline-none transition-colors duration-200 @error('keterangan') border-red-500 @enderror" 
                                  placeholder="Masukkan keterangan tambahan (opsional)">{{ old('keterangan', $barang->keterangan) }}</textarea>
                        @error('keterangan')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Form Actions -->
                    <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                        <a href="{{ route('barang.show', $barang->id) }}" 
                           class="inline-flex items-center px-6 py-3 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition-colors duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            Batal
                        </a>
                        <button type="submit" 
                                class="inline-flex items-center px-6 py-3 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition-colors duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Update Barang
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
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

document.addEventListener('DOMContentLoaded', function() {
    const hbeliDisplay = document.getElementById('hbeli_display');
    const hbeliHidden = document.getElementById('hbeli');
    const doesPcs = document.getElementById('does_pcs');

    // Setup harga beli formatting
    if (hbeliDisplay && hbeliHidden) {
        setupPriceInput(hbeliDisplay, hbeliHidden);
    }

    // Handle does_pcs to not show zero
    doesPcs.addEventListener('focus', function(e) {
        if (e.target.value == '0' || e.target.value == '0.00') {
            e.target.value = '';
        }
    });

    doesPcs.addEventListener('blur', function(e) {
        if (e.target.value === '' || e.target.value === '0' || e.target.value === '0.00') {
            e.target.value = '';
        }
    });

    // Prevent form submission if does_pcs is empty
    document.querySelector('form').addEventListener('submit', function(e) {
        if (doesPcs.value === '' || doesPcs.value === '0') {
            e.preventDefault();
            alert('Does Pcs tidak boleh kosong atau nol');
            doesPcs.focus();
            return false;
        }
    });

    // Add custom CSS to remove any unwanted outlines
    const style = document.createElement('style');
    style.textContent = `
        input[type="text"]:focus,
        input[type="number"]:focus,
        select:focus,
        textarea:focus {
            outline: none !important;
            box-shadow: 0 0 0 2px rgb(147 51 234 / 0.5) !important;
        }
    `;
    document.head.appendChild(style);
});
</script>
@endsection
