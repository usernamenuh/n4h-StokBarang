@extends('layouts.demo')
@section('content')
<div class="">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
                <h1>Tambah Rental</h1>
            <a href="{{ route('rental.index') }}" class="btn btn-primary">Kembali</a>
        </div>
        @if (session('success'))
        <div id="alert-success" class="custom-alert-success">
            <span class="custom-alert-icon">
                <!-- SVG centang -->
                <svg width="20" height="20" fill="none" viewBox="0 0 20 20">
                    <circle cx="10" cy="10" r="10" fill="#22c55e" fill-opacity="0.15"/>
                    <path d="M6 10.5l3 3 5-5" stroke="#22c55e" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </span>
            <span class="custom-alert-text">{{ session('success') }}</span>
            <span class="custom-alert-close" onclick="$('#alert-success').fadeOut(300);">&times;</span>
        </div>
    @endif
    @if (session('danger') || session('error'))
        <div id="alert-danger" class="custom-alert-danger">
            <span class="custom-alert-icon">
                <!-- SVG silang -->
                <svg width="20" height="20" fill="none" viewBox="0 0 20 20">
                    <circle cx="10" cy="10" r="10" fill="#f87171" fill-opacity="0.15"/>
                    <path d="M7 7l6 6M13 7l-6 6" stroke="#ef4444" stroke-width="2" stroke-linecap="round"/>
                </svg>
            </span>
            <span class="custom-alert-text">{{ session('danger') ?? session('error') }}</span>
            <span class="custom-alert-close" onclick="$('#alert-danger').fadeOut(300);">&times;</span>
        </div>
    @endif
        <div class="card-body">
            <form action="{{ route('rental.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="mobil_id" class="form-label">Mobil</label>
                    <select name="mobil_id" class="form-control @error('mobil_id') is-invalid @enderror">
                        <option value="">Pilih Mobil</option>
                        @foreach($mobils as $mobil)
                            <option value="{{ $mobil->id }}" {{ old('mobil_id') == $mobil->id ? 'selected' : '' }}>
                                {{ $mobil->nomor_polisi }} - {{ $mobil->type_kendaraan }}
                            </option>
                        @endforeach
                    </select>
                    @error('mobil_id')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="tanggal_awal_sewa" class="form-label">Tanggal Awal Sewa</label>
                    <input type="date" class="form-control @error('tanggal_awal_sewa') is-invalid @enderror" id="tanggal_awal_sewa" name="tanggal_awal_sewa" value="{{ old('tanggal_awal_sewa') }}">
                    @error('tanggal_awal_sewa')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="tanggal_akhir_sewa" class="form-label">Tanggal Akhir Sewa</label>
                    <input type="date" class="form-control @error('tanggal_akhir_sewa') is-invalid @enderror" id="tanggal_akhir_sewa" name="tanggal_akhir_sewa" value="{{ old('tanggal_akhir_sewa') }}">
                    @error('tanggal_akhir_sewa')
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
<style>
    .custom-alert-success {
        display: flex;
        align-items: center;
        background: #f0fdf4;
        color: #166534;
        border-radius: 10px;
        padding: 16px 24px;
        min-width: 500px;
        max-width: 600px;
        position: fixed;
        top: 30px;
        left: 50%;
        transform: translateX(-50%);
        z-index: 9999;
        box-shadow: 0 2px 8px rgba(34,197,94,0.08);
        font-size: 1rem;
        font-weight: 500;
        animation: fadeInDown 0.5s;
    }
    .custom-alert-icon {
        margin-right: 12px;
        display: flex;
        align-items: center;
    }
    .custom-alert-text {
        flex: 1;
    }
    .custom-alert-close {
        margin-left: 16px;
        cursor: pointer;
        font-size: 1.3rem;
        color: #22c55e;
        transition: color 0.2s;
    }
    .custom-alert-close:hover {
        color: #166534;
    }
    @keyframes fadeInDown {
        from { opacity: 0; transform: translateY(-20px) translateX(-50%);}
        to { opacity: 1; transform: translateY(0) translateX(-50%);}
    }
    @media (max-width: 600px) {
        .custom-alert-success {
            min-width: 90vw;
            max-width: 98vw;
            padding: 12px 8px;
            font-size: 0.95rem;
        }
    }
    .custom-alert-danger {
        display: flex;
        align-items: center;
        background: #fef2f2;
        color: #991b1b;
        border-radius: 10px;
        padding: 16px 24px;
        min-width: 500px;
        max-width: 600px;
        position: fixed;
        top: 30px;
        left: 50%;
        transform: translateX(-50%);
        z-index: 9999;
        box-shadow: 0 2px 8px rgba(239,68,68,0.08);
        font-size: 1rem;
        font-weight: 500;
        animation: fadeInDown 0.5s;
    }
    .custom-alert-danger .custom-alert-icon svg {
        margin-right: 12px;
        display: flex;
        align-items: center;
    }
    .custom-alert-danger .custom-alert-text {
        flex: 1;
    }
    .custom-alert-danger .custom-alert-close {
        margin-left: 16px;
        cursor: pointer;
        font-size: 1.3rem;
        color: #ef4444;
        transition: color 0.2s;
    }
    .custom-alert-danger .custom-alert-close:hover {
        color: #991b1b;
    }
    @media (max-width: 600px) {
        .custom-alert-danger {
            min-width: 90vw;
            max-width: 98vw;
            padding: 12px 8px;
            font-size: 0.95rem;
        }
    }
    </style>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
    $(document).ready(function(){
        setTimeout(function(){
            $("#alert-success").fadeOut(400);
            $("#alert-danger").fadeOut(400);
        }, 3000); // 3 detik
    });
    </script>
@endsection
