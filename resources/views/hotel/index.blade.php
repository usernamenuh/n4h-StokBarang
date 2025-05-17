@extends('layouts.demo')
@section('content')
@php use Carbon\Carbon; @endphp

<div class="">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h2>Data Hotel</h2>
            <a href="{{ route('hotel.create') }}" class="btn btn-primary">Tambah Hotel</a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="hotel-table">
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
                                <div class="dropdown">
                                    <button class="btn btn-link text-dark p-0" type="button" id="aksiDropdownHotel{{ $hotel->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="bx bx-dots-vertical-rounded" style="font-size: 1.5rem;"></i>
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="aksiDropdownHotel{{ $hotel->id }}">
                                        <li>
                                            <a class="dropdown-item" href="{{ route('hotel.edit', $hotel->id) }}">
                                                <i class="bx bx-edit me-2"></i> Edit
                                            </a>
                                        </li>
                                        <li>
                                            <form action="{{ route('hotel.destroy', $hotel->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item text-danger">
                                                    <i class="bx bx-trash me-2"></i> Delete
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- DataTables CSS & JS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css"/>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script>
$(document).ready(function() {
    $('#hotel-table').DataTable({
        "paging": true,
        "searching": true,
        "info": true
    });
});
</script>
@endsection
