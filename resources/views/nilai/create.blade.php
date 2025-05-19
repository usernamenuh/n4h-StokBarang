@extends('layouts.demo')
@section('content')
<div class="">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h1>Tambah Nilai</h1>
            <a href="{{ route('nilai.index') }}" class="btn btn-primary">Kembali</a>
        </div>
        <div class="card-body">
            <form action="{{ route('nilai.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="nama_mahasiswa" class="form-label">Nama Mahasiswa</label>
                    <input type="text" class="form-control @error('nama_mahasiswa') is-invalid @enderror" id="nama_mahasiswa" name="nama_mahasiswa" value="{{ old('nama_mahasiswa') }}">
                    @error('nama_mahasiswa')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="nim" class="form-label">NIM</label>
                    <input type="text" class="form-control @error('nim') is-invalid @enderror" id="nim" name="nim" value="{{ old('nim') }}">
                    @error('nim')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="tugas" class="form-label">Nilai Tugas</label>
                    <input type="number" class="form-control @error('tugas') is-invalid @enderror" id="tugas" name="tugas" value="{{ old('tugas') }}">
                    @error('tugas')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="kehadiran" class="form-label">Nilai Kehadiran</label>
                    <input type="number" class="form-control @error('kehadiran') is-invalid @enderror" id="kehadiran" name="kehadiran" value="{{ old('kehadiran') }}">
                    @error('kehadiran')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="quiz" class="form-label">Nilai Quiz</label>
                    <input type="number" class="form-control @error('quiz') is-invalid @enderror" id="quiz" name="quiz" value="{{ old('quiz') }}">
                    @error('quiz')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="uts" class="form-label">Nilai UTS</label>
                    <input type="number" class="form-control @error('uts') is-invalid @enderror" id="uts" name="uts" value="{{ old('uts') }}">
                    @error('uts')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="uas" class="form-label">Nilai UAS</label>
                    <input type="number" class="form-control @error('uas') is-invalid @enderror" id="uas" name="uas" value="{{ old('uas') }}">
                    @error('uas')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
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
