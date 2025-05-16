@extends('layouts.app')
@section('content')

<div class="container">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h2>Edit Hotel</h2>
            <a href="{{ route('hotel.index') }}" class="btn btn-secondary">Kembali</a>
        </div>
        <div class="card-body">
            <form action="{{ route('hotel.update', $hotel->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="pelanggan_id" class="form-label">Pelanggan</label>
                    <select name="pelanggan_id" id="pelanggan_id" class="form-control @error('pelanggan_id') is-invalid @enderror" required>
                        <option value="">-- Pilih Pelanggan --</option>
                        @foreach($pelanggans as $pelanggan)
                            <option value="{{ $pelanggan->id }}" {{ old('pelanggan_id', $hotel->pelanggan_id) == $pelanggan->id ? 'selected' : '' }}>{{ $pelanggan->nama }}</option>
                        @endforeach
                    </select>
                    @error('pelanggan_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="kamar_tersedia" class="form-label">Kamar Tersedia</label>
                    <select name="kamar_tersedia" id="kamar_tersedia" class="form-control @error('kamar_tersedia') is-invalid @enderror" required>
                        <option value="">-- Pilih Kamar --</option>
                        @foreach($kamar_tersedia as $kamar)
                            <option value="{{ $kamar }}" {{ old('kamar_tersedia', $hotel->kamar_tersedia) == $kamar ? 'selected' : '' }}>{{ ucfirst($kamar) }}</option>
                        @endforeach
                    </select>
                    @error('kamar_tersedia')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="check_in" class="form-label">Check In</label>
                    <input type="datetime-local" class="form-control @error('check_in') is-invalid @enderror" id="check_in" name="check_in" value="{{ old('check_in', \Carbon\Carbon::parse($hotel->check_in)->format('Y-m-d\TH:i')) }}" required>
                    @error('check_in')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="check_out" class="form-label">Check Out</label>
                    <input type="datetime-local" class="form-control @error('check_out') is-invalid @enderror" id="check_out" name="check_out" value="{{ old('check_out', \Carbon\Carbon::parse($hotel->check_out)->format('Y-m-d\TH:i')) }}" required>
                    @error('check_out')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary">Update</button>
            </form>
        </div>
    </div>
</div>
@endsection
