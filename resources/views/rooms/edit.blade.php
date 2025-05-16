@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h2>Edit Kamar</h2>
            <a href="{{ route('rooms.index') }}" class="btn btn-secondary">Kembali</a>
        </div>
        <div class="card-body">
            <form action="{{ route('rooms.update', $room->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="type" class="form-label">Tipe Kamar</label>
                    <select name="type" id="type" class="form-control" required>
                        <option value="">-- Pilih Tipe --</option>
                        <option value="single" {{ $room->type == 'single' ? 'selected' : '' }}>Single</option>
                        <option value="double" {{ $room->type == 'double' ? 'selected' : '' }}>Double</option>
                        <option value="suite" {{ $room->type == 'suite' ? 'selected' : '' }}>Suite</option>
                    </select>
                    @error('type')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="stock" class="form-label">Jumlah Kamar</label>
                    <input type="number" name="stock" id="stock" class="form-control" value="{{ $room->stock }}" min="0" required>
                    @error('stock')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="price" class="form-label">Harga (Rp)</label>
                    <input type="number" name="price" id="price" class="form-control" value="{{ $room->price }}" min="0" required>
                    @error('price')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <button class="btn btn-primary" type="submit">Update</button>
            </form>
        </div>
    </div>
</div>
@endsection
