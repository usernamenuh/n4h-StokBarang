@extends('layouts.demo')
@section('content')
<div class="">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h1>Tambah Dokter</h1>
            <a href="{{ route('dokter.index') }}" class="btn btn-secondary">Kembali</a>
        </div>
        <div class="card-body">
            <form action="{{ route('dokter.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="nama_dokter" class="form-label">Nama Dokter</label>
                    <input type="text" name="nama_dokter" id="nama_dokter" class="form-control @error('nama_dokter') is-invalid @enderror" value="{{ old('nama_dokter') }}">
                    @error('nama_dokter')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="spesialis" class="form-label">Spesialis</label>
                    <input type="text" name="spesialis" id="spesialis" class="form-control @error('spesialis') is-invalid @enderror" value="{{ old('spesialis') }}">
                    @error('spesialis')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="hari" class="form-label">Hari</label>
                    <select name="hari" id="hari" class="form-control @error('hari') is-invalid @enderror">
                        <option value="">Pilih Hari</option>
                        <option value="Senin">Senin</option>
                        <option value="Selasa">Selasa</option>
                        <option value="Rabu">Rabu</option>
                        <option value="Kamis">Kamis</option>
                        <option value="Jumat">Jumat</option>
                        <option value="Sabtu">Sabtu</option>
                        <option value="Minggu">Minggu</option>
                    </select>
                    @error('hari')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="jam_awal_praktik" class="form-label">Jam Awal Praktik</label>
                    <input type="date" name="jam_awal_praktik" id="jam_awal_praktik" class="form-control @error('jam_awal_praktik') is-invalid @enderror" value="{{ old('jam_awal_praktik') }}">
                    @error('jam_awal_praktik')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="jam_akhir_praktik" class="form-label">Jam Akhir Praktik</label>
                    <input type="date" name="jam_akhir_praktik" id="jam_akhir_praktik" class="form-control @error('jam_akhir_praktik') is-invalid @enderror" value="{{ old('jam_akhir_praktik') }}">
                    @error('jam_akhir_praktik')
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
@endsection
