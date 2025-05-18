@extends('layouts.demo')
@section('content')
<div class="">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h1>Edit Transaksi</h1>
            <a href="{{ route('transaksi.index') }}" class="btn btn-secondary">Kembali</a>
        </div>
        <div class="card-body">
            <form action="{{ route('transaksi.update', $transaksi->id) }}" method="POST">
                @method('PUT')
                @csrf
                <div class="mb-3">
                    <label for="id_barang">Nama Barang</label>
                    <select name="id_barang" id="id_barang" class="form-control @error('id_barang') is-invalid @enderror">
                        <option>-----Pilih Barang-----</option>
                        @foreach ($barangs as $barang)
                            <option value="{{ $barang->id }}" data-harga="{{ $barang->harga }}" data-stok="{{ $barang->stok }}" {{ $transaksi->id_barang == $barang->id ? 'selected' : '' }}>
                                {{ $barang->nama_barang }} - {{ $barang->stok }} - Rp. {{ number_format($barang->harga, 0, ',', '.') }}
                            </option>
                        @endforeach
                    </select>
                    @error('id_barang')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="quantity">Quantity</label>
                    <input type="number" name="quantity" id="quantity" class="form-control @error('quantity') is-invalid @enderror" value="{{ $transaksi->quantity }}">
                    <div id="qty-error" class="text-danger" style="display:none; font-size:0.95em;"></div>
                    @error('quantity')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="total">Total</label>
                    <input type="text" name="total" id="total" class="form-control @error('total') is-invalid @enderror" value="Rp. {{ number_format($transaksi->total, 0, ',', '.') }}" readonly>
                    @error('total')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectBarang = document.getElementById('id_barang');
    const qtyInput = document.getElementById('quantity');
    const totalInput = document.getElementById('total');
    const qtyError = document.getElementById('qty-error');

    function updateTotal() {
        const selectedOption = selectBarang.options[selectBarang.selectedIndex];
        const hargaSatuan = parseInt(selectedOption.getAttribute('data-harga')) || 0;
        const stok = parseInt(selectedOption.getAttribute('data-stok')) || 0;
        const quantity = parseInt(qtyInput.value) || 0;
        const total = hargaSatuan * quantity;

        // Validasi stok
        if (quantity > stok && stok > 0) {
            qtyError.style.display = 'block';
            qtyError.textContent = 'Stok tidak cukup! Stok tersedia: ' + stok;
        } else {
            qtyError.style.display = 'none';
            qtyError.textContent = '';
        }

        totalInput.value = total > 0 ? 'Rp. ' + total.toLocaleString('id-ID') : '';
    }

    selectBarang.addEventListener('change', updateTotal);
    qtyInput.addEventListener('input', updateTotal);
});
</script>
@endsection