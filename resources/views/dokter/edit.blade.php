@extends('layouts.demo')
@section('content')
<div class="">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h1>Edit Dokter</h1>
            <a href="{{ route('dokter.index') }}" class="btn btn-secondary">Kembali</a>
        </div>
        <div class="card-body">
            <form action="{{ route('dokter.update', $dokter->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="nama_dokter" class="form-label">Nama Dokter</label>
                    <input type="text" name="nama_dokter" id="nama_dokter" class="form-control @error('nama_dokter') is-invalid @enderror" value="{{ old('nama_dokter', $dokter->nama_dokter) }}">
                    @error('nama_dokter')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="spesialis" class="form-label">Spesialis</label>
                    <input type="text" name="spesialis" id="spesialis" class="form-control @error('spesialis') is-invalid @enderror" value="{{ old('spesialis', $dokter->spesialis) }}">
                    @error('spesialis')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="hari" class="form-label">Hari</label>
                    <select name="hari" id="hari" class="form-control @error('hari') is-invalid @enderror">
                        <option value="">Pilih Hari</option>
                        <option value="Senin" {{ old('hari', $dokter->hari) == 'Senin' ? 'selected' : '' }}>Senin</option>
                        <option value="Selasa" {{ old('hari', $dokter->hari) == 'Selasa' ? 'selected' : '' }}>Selasa</option>
                        <option value="Rabu" {{ old('hari', $dokter->hari) == 'Rabu' ? 'selected' : '' }}>Rabu</option>
                        <option value="Kamis" {{ old('hari', $dokter->hari) == 'Kamis' ? 'selected' : '' }}>Kamis</option>
                        <option value="Jumat" {{ old('hari', $dokter->hari) == 'Jumat' ? 'selected' : '' }}>Jumat</option>
                        <option value="Sabtu" {{ old('hari', $dokter->hari) == 'Sabtu' ? 'selected' : '' }}>Sabtu</option>
                        <option value="Minggu" {{ old('hari', $dokter->hari) == 'Minggu' ? 'selected' : '' }}>Minggu</option>
                    </select>
                    @error('hari')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="jam_awal_praktik" class="form-label">Jam Awal Praktik</label>
                    <input type="date" name="jam_awal_praktik" id="jam_awal_praktik" class="form-control @error('jam_awal_praktik') is-invalid @enderror" value="{{ old('jam_awal_praktik', $dokter->jam_awal_praktik) }}">
                    @error('jam_awal_praktik')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="jam_akhir_praktik" class="form-label">Jam Akhir Praktik</label>
                    <input type="date" name="jam_akhir_praktik" id="jam_akhir_praktik" class="form-control @error('jam_akhir_praktik') is-invalid @enderror" value="{{ old('jam_akhir_praktik', $dokter->jam_akhir_praktik) }}">
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
