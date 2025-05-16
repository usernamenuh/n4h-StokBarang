@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h2>Tambah Kamar</h2>
            <a href="{{ route('rooms.index') }}" class="btn btn-secondary">Kembali</a>
        </div>
        <div class="card-body">
    <form action="{{ route('rooms.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="type" class="form-label">Tipe Kamar</label>
            <select name="type" id="type" class="form-control" required>
                <option value="">-- Pilih Tipe --</option>
                <option value="single" {{ old('type') == 'single' ? 'selected' : '' }}>Single</option>
                <option value="double" {{ old('type') == 'double' ? 'selected' : '' }}>Double</option>
                <option value="suite" {{ old('type') == 'suite' ? 'selected' : '' }}>Suite</option>
            </select>
            @error('type')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="stock" class="form-label">Jumlah Kamar</label>
            <input type="number" name="stock" id="stock" class="form-control" value="{{ old('stock') }}" min="0" required>
            @error('stock')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="price" class="form-label">Harga (Rp)</label>
            <input type="number" name="price" id="price" class="form-control" value="{{ old('price') }}" min="0" required>
            @error('price')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <button class="btn btn-primary" type="submit">Simpan</button>
        <a href="{{ route('rooms.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
        </div>
    </div>
</div>

<script>
document.getElementById('type').addEventListener('change', function() {
    let harga = 0;
    if(this.value === 'single') harga = 300000;
    if(this.value === 'double') harga = 600000;
    if(this.value === 'suite') harga = 900000;
    document.getElementById('price').value = harga;
});
</script>

@endsection
