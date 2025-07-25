@extends('layouts.demo')
@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <!-- Header -->
        <div class="flex justify-between items-center p-6 border-b border-gray-200">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Tambah Barang</h1>
                <p class="text-sm text-gray-600 mt-1">Tambahkan data barang baru ke sistem</p>
            </div>
            <a href="{{ route('barang.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition-colors duration-200">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali
            </a>
        </div>

        <!-- Form -->
        <div class="p-6">
            <form action="{{ route('barang.store') }}" method="POST" class="space-y-6">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Kode Barang -->
                    <div>
                        <label for="kode" class="block text-sm font-medium text-gray-700 mb-2">
                            Kode Barang <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="kode" 
                               id="kode" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 @error('kode') border-red-500 @enderror" 
                               value="{{ old('kode') }}" 
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
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 @error('nama') border-red-500 @enderror" 
                               value="{{ old('nama') }}" 
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
                        <input type="number" 
                               step="0.01" 
                               name="does_pcs" 
                               id="does_pcs" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 @error('does_pcs') border-red-500 @enderror" 
                               value="{{ old('does_pcs', 1) }}" 
                               min="0.01"
                               placeholder="1.00"
                               required>
                        @error('does_pcs')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">Nilai konversi unit (contoh: 1 dus = 12 pcs)</p>
                    </div>

                    <!-- Golongan -->
                    <div>
                        <label for="golongan" class="block text-sm font-medium text-gray-700 mb-2">
                            Golongan (Kategori) <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="golongan" 
                               id="golongan" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 @error('golongan') border-red-500 @enderror" 
                               value="{{ old('golongan') }}" 
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
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">Rp</span>
                            </div>
                            <input type="number" 
                                   step="0.01" 
                                   name="hbeli" 
                                   id="hbeli" 
                                   class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 @error('hbeli') border-red-500 @enderror" 
                                   value="{{ old('hbeli', 0) }}" 
                                   min="0"
                                   placeholder="0"
                                   required>
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
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 @error('user_id') border-red-500 @enderror">
                            <option value="">Pilih User</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
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
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 @error('keterangan') border-red-500 @enderror"
                              placeholder="Masukkan keterangan tambahan (opsional)">{{ old('keterangan') }}</textarea>
                    @error('keterangan')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                    <a href="{{ route('barang.index') }}" 
                       class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                        Batal
                    </a>
                    <button type="submit" 
                            class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Simpan Barang
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto format kode barang
    const kodeInput = document.getElementById('kode');
    kodeInput.addEventListener('input', function() {
        let value = this.value.toUpperCase();
        if (!value.startsWith('BRG-') && value.length > 0) {
            this.value = 'BRG-' + value.replace(/^BRG-/, '');
        }
    });
    
    // Format harga input
    const hargaInput = document.getElementById('hbeli');
    hargaInput.addEventListener('input', function() {
        // Remove non-numeric characters except decimal point
        let value = this.value.replace(/[^\d.]/g, '');
        this.value = value;
    });
});
</script>
@endsection