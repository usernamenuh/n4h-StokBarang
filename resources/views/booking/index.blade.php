@extends('layouts.demo')

@section('content')
<div class="">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h1>Booking</h1>
            <a href="{{ route('booking.create') }}" class="btn btn-primary">Tambah Booking</a>
        </div>
        <div class="card-body">
            <table class="table table-bordered" id="hotel-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Pasien</th>
                        <th>Dokter</th>
                        <th>Hari</th>
                        <th>Jam Awal Praktik</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($bookings as $booking => $key)
                        <tr>
                            <td>{{ $booking + 1 }}</td>
                            <td>{{ $key->nama_pasien }}</td>
                            <td>{{ $key->dokter->nama_dokter }}</td>
                            <td>{{ $key->hari }}</td>
                            <td>{{ \Carbon\Carbon::parse($key->jam_awal_praktik)->translatedFormat('d F Y') }}</td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-link text-dark p-0" type="button" id="aksiDropdownDokter{{ $key->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="bx bx-dots-vertical-rounded" style="font-size: 1.5rem;"></i>
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="aksiDropdownDokter{{ $key->id }}">
                                        <li>
                                            <a class="dropdown-item" href="{{ route('booking.edit', $key->id) }}">
                                                <i class="bx bx-edit me-2"></i> Edit
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="{{ route('booking.show', $key->id) }}">
                                                <i class="bx bx-show me-2"></i> Show
                                            </a>
                                        </li>
                                        <li>
                                            <form action="{{ route('booking.destroy', $key->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus?');">
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
@endsection

