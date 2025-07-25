@extends('layouts.demo')

@section('title', 'Edit Transaksi')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Edit Transaksi: {{ $transaksi->nomor }}</h1>

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

    <div class="bg-white shadow-md rounded-lg p-6">
        <form action="{{ route('transaksi.update', $transaksi->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div>
                    <label for="tanggal" class="block text-gray-700 text-sm font-bold mb-2">Tanggal:</label>
                    <input type="date" name="tanggal" id="tanggal" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('tanggal') border-red-500 @enderror" value="{{ old('tanggal', $transaksi->tanggal) }}" required>
                    @error('tanggal')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="nomor" class="block text-gray-700 text-sm font-bold mb-2">Nomor Transaksi:</label>
                    <input type="text" name="nomor" id="nomor" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('nomor') border-red-500 @enderror" value="{{ old('nomor', $transaksi->nomor) }}" required>
                    @error('nomor')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="customer" class="block text-gray-700 text-sm font-bold mb-2">Customer:</label>
                    <input type="text" name="customer" id="customer" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('customer') border-red-500 @enderror" value="{{ old('customer', $transaksi->customer) }}" required>
                    @error('customer')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="ongkos_kirim" class="block text-gray-700 text-sm font-bold mb-2">Ongkos Kirim:</label>
                    <input type="number" step="0.01" name="ongkos_kirim" id="ongkos_kirim" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('ongkos_kirim') border-red-500 @enderror" value="{{ old('ongkos_kirim', $transaksi->ongkos_kirim) }}" min="0">
                    @error('ongkos_kirim')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="keterangan" class="block text-gray-700 text-sm font-bold mb-2">Keterangan:</label>
                    <textarea name="keterangan" id="keterangan" rows="3" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('keterangan') border-red-500 @enderror">{{ old('keterangan', $transaksi->keterangan) }}</textarea>
                    @error('keterangan')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="jum_print" class="block text-gray-700 text-sm font-bold mb-2">Jumlah Print:</label>
                    <input type="number" name="jum_print" id="jum_print" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('jum_print') border-red-500 @enderror" value="{{ old('jum_print', $transaksi->jum_print) }}" min="0">
                    @error('jum_print')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <h2 class="text-xl font-bold mb-4">Detail Transaksi</h2>
            <div id="transaction-details-container">
                @if (old('details'))
                    @foreach (old('details') as $index => $detail)
                        @include('transaksi.partials.detail_row', ['index' => $index, 'detail' => $detail, 'barangs' => $barangs])
                    @endforeach
                @else
                    @foreach ($transaksi->details as $index => $detail)
                        @include('transaksi.partials.detail_row', ['index' => $index, 'detail' => $detail, 'barangs' => $barangs])
                    @endforeach
                @endif
            </div>
            <button type="button" id="add-detail-row" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded mt-4">Tambah Item</button>

            <div class="flex items-center justify-between mt-6">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Perbarui Transaksi
                </button>
                <a href="{{ route('transaksi.index') }}" class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

<script>
    let detailIndex = {{ old('details') ? count(old('details')) : $transaksi->details->count() }};
    document.getElementById('add-detail-row').addEventListener('click', function() {
        const container = document.getElementById('transaction-details-container');
        const newRow = document.createElement('div');
        newRow.classList.add('grid', 'grid-cols-1', 'md:grid-cols-6', 'gap-4', 'mb-4', 'border', 'p-4', 'rounded-lg', 'relative');
        newRow.innerHTML = `
            <div>
                <label for="details_${detailIndex}_barang_id" class="block text-gray-700 text-sm font-bold mb-2">Barang:</label>
                <select name="details[${detailIndex}][barang_id]" id="details_${detailIndex}_barang_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    <option value="">Pilih Barang</option>
                    @foreach($barangs as $barang)
                        <option value="{{ $barang->id }}">{{ $barang->nama }} ({{ $barang->kode }})</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="details_${detailIndex}_quantity" class="block text-gray-700 text-sm font-bold mb-2">Kuantitas:</label>
                <input type="number" step="0.01" name="details[${detailIndex}][quantity]" id="details_${detailIndex}_quantity" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="1" required min="0.01">
            </div>
            <div>
                <label for="details_${detailIndex}_price" class="block text-gray-700 text-sm font-bold mb-2">Harga Satuan:</label>
                <input type="number" step="0.01" name="details[${detailIndex}][price]" id="details_${detailIndex}_price" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="0" required min="0">
            </div>
            <div>
                <label for="details_${detailIndex}_discount" class="block text-gray-700 text-sm font-bold mb-2">Diskon Item:</label>
                <input type="number" step="0.01" name="details[${detailIndex}][discount]" id="details_${detailIndex}_discount" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="0" min="0">
            </div>
            <div class="md:col-span-2">
                <label for="details_${detailIndex}_keterangan" class="block text-gray-700 text-sm font-bold mb-2">Keterangan Item:</label>
                <textarea name="details[${detailIndex}][keterangan]" id="details_${detailIndex}_keterangan" rows="1" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"></textarea>
            </div>
            <button type="button" class="absolute top-2 right-2 bg-red-500 hover:bg-red-700 text-white text-xs font-bold py-1 px-2 rounded remove-detail-row">X</button>
        `;
        container.appendChild(newRow);
        detailIndex++;
        attachRemoveListeners();
    });

    function attachRemoveListeners() {
        document.querySelectorAll('.remove-detail-row').forEach(button => {
            button.onclick = function() {
                this.closest('.grid').remove();
            };
        });
    }

    attachRemoveListeners(); // Attach listeners for initial rows
</script>
@endsection
