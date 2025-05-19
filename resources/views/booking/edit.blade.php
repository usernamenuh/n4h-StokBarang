@extends('layouts.demo')
@section('content')
<div class="">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h1>Edit Booking</h1>
            <a href="{{ route('booking.index') }}" class="btn btn-secondary">Kembali</a>
        </div>
        <div class="card-body">
            <form action="{{ route('booking.update', $booking->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="nama_pasien" class="form-label">Nama Pasien</label>
                    <input type="text" name="nama_pasien" id="nama_pasien" class="form-control @error('nama_pasien') is-invalid @enderror" value="{{ old('nama_pasien', $booking->nama_pasien) }}">
                    @error('nama_pasien')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="dokter_id" class="form-label">Dokter</label>
                    <select name="dokter_id" id="dokter_id" class="form-control @error('dokter_id') is-invalid @enderror">
                        <option value="">Pilih Dokter</option>
                        @foreach ($dokters as $dokter)
                            <option value="{{ $dokter->id }}" {{ old('dokter_id', $booking->dokter_id) == $dokter->id ? 'selected' : '' }}>
                                {{ $dokter->nama_dokter }} -- {{ $dokter->spesialis }} ({{ $dokter->status }})
                            </option>
                        @endforeach
                    </select>
                    @error('dokter_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="hari" class="form-label">Hari</label>
                    <select name="hari" id="hari" class="form-control @error('hari') is-invalid @enderror" >
                        <option value="">Pilih Hari</option>
                        <option value="Senin" {{ old('hari', $booking->hari) == 'Senin' ? 'selected' : '' }}>Senin</option>
                        <option value="Selasa" {{ old('hari', $booking->hari) == 'Selasa' ? 'selected' : '' }}>Selasa</option>
                        <option value="Rabu" {{ old('hari', $booking->hari) == 'Rabu' ? 'selected' : '' }}>Rabu</option>
                        <option value="Kamis" {{ old('hari', $booking->hari) == 'Kamis' ? 'selected' : '' }}>Kamis</option>
                        <option value="Jumat" {{ old('hari', $booking->hari) == 'Jumat' ? 'selected' : '' }}>Jumat</option>
                        <option value="Sabtu" {{ old('hari', $booking->hari) == 'Sabtu' ? 'selected' : '' }}>Sabtu</option>
                        <option value="Minggu" {{ old('hari', $booking->hari) == 'Minggu' ? 'selected' : '' }}>Minggu</option>
                    </select>
                    @error('hari')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="jam_awal_praktik" class="form-label">Jam Awal Praktik</label>
                    <input type="date" name="jam_awal_praktik" id="jam_awal_praktik" class="form-control @error('jam_awal_praktik') is-invalid @enderror" value="{{ old('jam_awal_praktik', $booking->jam_awal_praktik) }}">
                    @error('jam_awal_praktik')
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