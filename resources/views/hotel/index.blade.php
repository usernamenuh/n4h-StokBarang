@extends('layouts.app')
@section('content')
@php use Carbon\Carbon; @endphp

<div class="container">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h2>Data Hotel</h2>
            <a href="{{ route('hotel.create') }}" class="btn btn-primary">Tambah Hotel</a>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Pelanggan</th>
                        <th>Kamar</th>
                        <th>Harga / Hari (Rp)</th>
                        <th>Jumlah Hari</th>
                        <th>Total Harga (Rp)</th>
                        <th>Check In</th>
                        <th>Check Out</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($hotels as $i => $hotel)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $hotel->pelanggan->nama ?? '-' }}</td>
                        <td>{{ ucfirst($hotel->room->type ?? '-') }}</td>
                        <td>
                            @if($hotel->room)
                                Rp {{ number_format($hotel->room->price, 0, ',', '.') }}
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            @php
                                $hari = 0;
                                if ($hotel->check_in && $hotel->check_out) {
                                    $hari = \Carbon\Carbon::parse($hotel->check_in)->diffInDays(\Carbon\Carbon::parse($hotel->check_out));
                                }
                            @endphp
                            {{ $hari }}
                        </td>
                        <td>
                            @if($hotel->room)
                                @php
                                    $total = $hari * $hotel->room->price;
                                @endphp
                                Rp {{ number_format($total, 0, ',', '.') }}
                            @else
                                -
                            @endif
                        </td>
                        <td>{{ $hotel->check_in }}</td>
                        <td>{{ $hotel->check_out }}</td>
                        <td>
                            <a href="{{ route('hotel.edit', $hotel->id) }}" class="btn btn-warning">Edit</a>
                            <form action="{{ route('hotel.destroy', $hotel->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection