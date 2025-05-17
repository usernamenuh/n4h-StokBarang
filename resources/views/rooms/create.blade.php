@extends('layouts.demo')

@section('content')
<div class="">
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
                    <div class="input-group">
                        <span class="input-group-text"><i class="bx bx-bed"></i></span>
                        <select name="type" id="type" class="form-control @error('type') is-invalid @enderror" required>
                            <option value="">-- Pilih Tipe --</option>
                            <option value="single" {{ old('type') == 'single' ? 'selected' : '' }}>Single</option>
                            <option value="double" {{ old('type') == 'double' ? 'selected' : '' }}>Double</option>
                            <option value="suite" {{ old('type') == 'suite' ? 'selected' : '' }}>Suite</option>
                        </select>
                    </div>
                    @error('type')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="stock" class="form-label">Jumlah Kamar</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bx bx-hotel"></i></span>
                        <input type="number" name="stock" id="stock" class="form-control @error('stock') is-invalid @enderror" value="{{ old('stock') }}" min="0" required>
                    </div>
                    @error('stock')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="price" class="form-label">Harga (Rp)</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bx bx-money"></i></span>
                        <input type="text" id="price_display" class="form-control" value="{{ old('price', $room->price ?? '') }}" required>
                        <input type="hidden" name="price" id="price" value="{{ old('price', $room->price ?? '') }}">
                    </div>
                    @error('price')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <button class="btn btn-primary" type="submit">Simpan</button>
            </form>
        </div>
    </div>
</div>

<script>
function formatRupiah(angka) {
    return 'Rp ' + angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

document.getElementById('type').addEventListener('change', function() {
    let harga = 0;
    if(this.value === 'single') harga = 300000;
    if(this.value === 'double') harga = 600000;
    if(this.value === 'suite') harga = 900000;

    document.getElementById('price_display').value = formatRupiah(harga);
    document.getElementById('price').value = harga;
});

// Saat user mengedit manual, pastikan hidden input tetap angka
document.getElementById('price_display').addEventListener('input', function() {
    let angka = this.value.replace(/[^0-9]/g, '');
    document.getElementById('price').value = angka;
});

// On load, format tampilan
window.onload = function() {
    const priceInput = document.getElementById('price');
    const priceDisplay = document.getElementById('price_display');
    if(priceInput.value) {
        priceDisplay.value = formatRupiah(priceInput.value);
    }
};
</script>
@endsection
