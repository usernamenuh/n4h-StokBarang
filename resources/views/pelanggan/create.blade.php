@extends('layouts.demo')
@section('content')

<div class="">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h2>Tambah Pelanggan</h2>
            <a href="{{ route('pelanggan.index') }}" class="btn btn-secondary">Kembali</a>
        </div>
        <div class="card-body">
            <form action="{{ route('pelanggan.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="id_pelanggan" class="form-label">ID Pelanggan</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bx bx-id-card"></i></span>
                        <input type="text" class="form-control @error('id_pelanggan') is-invalid @enderror" id="id_pelanggan" name="id_pelanggan" value="{{ old('id_pelanggan') }}" required>
                    </div>
                    @error('id_pelanggan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="nama" class="form-label">Nama</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bx bx-user"></i></span>
                        <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama" value="{{ old('nama') }}" required>
                    </div>
                    @error('nama')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="telepon" class="form-label">Telepon</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bx bx-phone"></i></span>
                        <input type="text" class="form-control @error('telepon') is-invalid @enderror" id="telepon" name="telepon" value="{{ old('telepon') }}" required>
                    </div>
                    @error('telepon')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="alamat" class="form-label">Alamat</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bx bx-map"></i></span>
                        <textarea class="form-control @error('alamat') is-invalid @enderror" id="alamat" name="alamat" rows="3" required>{{ old('alamat') }}</textarea>
                    </div>
                    @error('alamat')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const idInput = document.getElementById('id_pelanggan');
    idInput.addEventListener('input', function() {
        if (!idInput.value.startsWith('PLG-')) {
            idInput.value = 'PLG-' + idInput.value.replace(/^PLG-/, '');
        }
    });
});
</script>
@endsection
