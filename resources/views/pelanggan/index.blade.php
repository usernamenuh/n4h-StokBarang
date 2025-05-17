@extends('layouts.demo')
@section('content')

<div class="">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h2>Data Pelanggan</h2>
            <a href="{{ route('pelanggan.create') }}" class="btn btn-primary">Tambah Pelanggan</a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="pelanggan-table" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>ID Pelanggan</th>
                            <th>Nama</th>
                            <th>Telepon</th>
                            <th>Alamat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pelanggans as $p => $pelanggan)
                        <tr>
                            <td>{{ $p + 1 }}</td>
                            <td>{{ $pelanggan->id_pelanggan }}</td>
                            <td>{{ $pelanggan->nama }}</td>
                            <td>{{ $pelanggan->telepon }}</td>
                            <td>{{ $pelanggan->alamat }}</td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-link text-dark p-0" type="button" id="aksiDropdown{{ $pelanggan->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="bx bx-dots-vertical-rounded" style="font-size: 1.5rem;"></i>
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="aksiDropdown{{ $pelanggan->id }}">
                                        <li>
                                            <a class="dropdown-item" href="{{ route('pelanggan.edit', $pelanggan->id) }}">
                                                <i class="bx bx-edit me-2"></i> Edit
                                            </a>
                                        </li>
                                        <li>
                                            <form action="{{ route('pelanggan.destroy', $pelanggan->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus?');">
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
    $('#pelanggan-table').DataTable({
        "paging": true,
        "searching": true,
        "info": true
    });
});
</script>
@endsection
