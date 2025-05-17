@extends('layouts.demo')

@section('content')
<div class="">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h2>Daftar Kamar</h2>
            <a href="{{ route('rooms.create') }}" class="btn btn-primary">Tambah Kamar</a>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="rooms-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tipe Kamar</th>
                            <th>Harga (Rp)</th>
                            <th>Jumlah Kamar</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($rooms as $room => $item)
                        <tr>
                            <td>{{ $room + 1 }}</td>
                            <td>{{ ucfirst($item->type) }}</td>
                            <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                            <td>{{ $item->stock }}</td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-link text-dark p-0" type="button" id="aksiDropdownRoom{{ $item->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="bx bx-dots-vertical-rounded" style="font-size: 1.5rem;"></i>
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="aksiDropdownRoom{{ $item->id }}">
                                        <li>
                                            <a class="dropdown-item" href="{{ route('rooms.edit', $item->id) }}">
                                                <i class="bx bx-edit me-2"></i> Edit
                                            </a>
                                        </li>
                                        <li>
                                            <form action="{{ route('rooms.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus?');">
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
    $('#rooms-table').DataTable({
        "paging": true,
        "searching": true,
        "info": true
    });
});
</script>
@endsection
