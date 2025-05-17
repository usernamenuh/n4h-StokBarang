@extends('layouts.demo')
@section('content')

<div class="">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h2>Tambah Hotel</h2>
            <a href="{{ route('hotel.index') }}" class="btn btn-secondary">Kembali</a>
        </div>
        <div class="card-body">
            <form action="{{ route('hotel.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="pelanggan_id" class="form-label">Pelanggan</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bx bx-user"></i></span>
                        <select name="pelanggan_id" id="pelanggan_id" class="form-control @error('pelanggan_id') is-invalid @enderror" required>
                            <option value="">-- Pilih Pelanggan --</option>
                            @foreach($pelanggans as $pelanggan)
                                <option value="{{ $pelanggan->id }}" {{ old('pelanggan_id') == $pelanggan->id ? 'selected' : '' }}>{{ $pelanggan->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('pelanggan_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="room_id" class="form-label">Kamar</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bx bx-bed"></i></span>
                        <select name="room_id" id="room_id" class="form-control @error('room_id') is-invalid @enderror" required>
                            <option value="">-- Pilih Kamar --</option>
                            @foreach($rooms as $room)
                                <option value="{{ $room->id }}" data-harga="{{ $room->price }}" {{ old('room_id') == $room->id ? 'selected' : '' }}>
                                    {{ ucfirst($room->type) }} (Sisa: {{ $room->stock }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @error('room_id')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="harga_kamar" class="form-label">Harga Kamar (Rp)</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bx bx-money"></i></span>
                        <input type="text" id="harga_kamar" class="form-control" value="" readonly>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="check_in" class="form-label">Check In</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bx bx-calendar"></i></span>
                        <input type="datetime-local" class="form-control @error('check_in') is-invalid @enderror" id="check_in" name="check_in" value="{{ old('check_in') }}" required>
                    </div>
                    @error('check_in')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="check_out" class="form-label">Check Out</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bx bx-calendar"></i></span>
                        <input type="datetime-local" class="form-control @error('check_out') is-invalid @enderror" id="check_out" name="check_out" value="{{ old('check_out') }}" required>
                    </div>
                    @error('check_out')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="total_harga" class="form-label">Total Harga (Rp)</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bx bx-calculator"></i></span>
                        <input type="text" id="total_harga" class="form-control" value="" readonly>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </form>
        </div>
    </div>
</div>

<script>
    function formatRupiah(angka) {
        return 'Rp ' + angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    function getHarga() {
        var select = document.getElementById('room_id');
        return parseInt(select.options[select.selectedIndex]?.getAttribute('data-harga')) || 0;
    }

    function hitungHari() {
        var checkIn = document.getElementById('check_in').value;
        var checkOut = document.getElementById('check_out').value;
        if (checkIn && checkOut) {
            var tglIn = new Date(checkIn);
            var tglOut = new Date(checkOut);
            var diff = tglOut - tglIn;
            var hari = diff / (1000 * 60 * 60 * 24);
            return hari > 0 ? hari : 0;
        }
        return 0;
    }

    function updateHargaDanTotal() {
        var harga = getHarga();
        document.getElementById('harga_kamar').value = harga ? formatRupiah(harga) : '';
        var hari = hitungHari();
        var total = harga * hari;
        document.getElementById('total_harga').value = total ? formatRupiah(total) : '';
    }

    document.getElementById('room_id').addEventListener('change', updateHargaDanTotal);
    document.getElementById('check_in').addEventListener('change', updateHargaDanTotal);
    document.getElementById('check_out').addEventListener('change', updateHargaDanTotal);

    window.onload = updateHargaDanTotal;
</script>
@endsection
