<div class="grid grid-cols-1 md:grid-cols-6 gap-4 mb-4 border p-4 rounded-lg relative">
    <div>
        <label for="details_{{ $index }}_barang_id" class="block text-gray-700 text-sm font-bold mb-2">Barang:</label>
        <select name="details[{{ $index }}][barang_id]" id="details_{{ $index }}_barang_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('details.' . $index . '.barang_id') border-red-500 @enderror" required>
            <option value="">Pilih Barang</option>
            @foreach($barangs as $barang)
                <option value="{{ $barang->id }}" {{ (old('details.' . $index . '.barang_id', $detail['barang_id'] ?? '') == $barang->id) ? 'selected' : '' }}>
                    {{ $barang->nama }} ({{ $barang->kode }})
                </option>
            @endforeach
        </select>
        @error('details.' . $index . '.barang_id')
            <p class="text-red-500 text-xs italic">{{ $message }}</p>
        @enderror
    </div>
    <div>
        <label for="details_{{ $index }}_quantity" class="block text-gray-700 text-sm font-bold mb-2">Kuantitas:</label>
        <input type="number" step="0.01" name="details[{{ $index }}][quantity]" id="details_{{ $index }}_quantity" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('details.' . $index . '.quantity') border-red-500 @enderror" value="{{ old('details.' . $index . '.quantity', $detail['quantity'] ?? 1) }}" required min="0.01">
        @error('details.' . $index . '.quantity')
            <p class="text-red-500 text-xs italic">{{ $message }}</p>
        @enderror
    </div>
    <div>
        <label for="details_{{ $index }}_price" class="block text-gray-700 text-sm font-bold mb-2">Harga Satuan:</label>
        <input type="number" step="0.01" name="details[{{ $index }}][price]" id="details_{{ $index }}_price" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('details.' . $index . '.price') border-red-500 @enderror" value="{{ old('details.' . $index . '.price', $detail['price'] ?? 0) }}" required min="0">
        @error('details.' . $index . '.price')
            <p class="text-red-500 text-xs italic">{{ $message }}</p>
        @enderror
    </div>
    <div>
        <label for="details_{{ $index }}_discount" class="block text-gray-700 text-sm font-bold mb-2">Diskon Item:</label>
        <input type="number" step="0.01" name="details[{{ $index }}][discount]" id="details_{{ $index }}_discount" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('details.' . $index . '.discount') border-red-500 @enderror" value="{{ old('details.' . $index . '.discount', $detail['discount'] ?? 0) }}" min="0">
        @error('details.' . $index . '.discount')
            <p class="text-red-500 text-xs italic">{{ $message }}</p>
        @enderror
    </div>
    <div class="md:col-span-2">
        <label for="details_{{ $index }}_keterangan" class="block text-gray-700 text-sm font-bold mb-2">Keterangan Item:</label>
        <textarea name="details[{{ $index }}][keterangan]" id="details_{{ $index }}_keterangan" rows="1" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('details.' . $index . '.keterangan') border-red-500 @enderror">{{ old('details.' . $index . '.keterangan', $detail['keterangan'] ?? '') }}</textarea>
        @error('details.' . $index . '.keterangan')
            <p class="text-red-500 text-xs italic">{{ $message }}</p>
        @enderror
    </div>
    <button type="button" class="absolute top-2 right-2 bg-red-500 hover:bg-red-700 text-white text-xs font-bold py-1 px-2 rounded remove-detail-row">X</button>
</div>
