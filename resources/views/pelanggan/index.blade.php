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
                                            <button type="button" class="dropdown-item text-danger" data-bs-toggle="modal" data-bs-target="#deleteModalPelanggan{{ $pelanggan->id }}">
                                                <i class="bx bx-trash me-2"></i> Delete
                                            </button>
                                        </li>
                                    </ul>
                                </div>
                                <!-- Delete Modal -->
                                <div class="modal fade modal-confirm" id="deleteModalPelanggan{{ $pelanggan->id }}" tabindex="-1" aria-labelledby="deleteModalLabelPelanggan{{ $pelanggan->id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content text-center p-4">
                                            <img src="https://img.icons8.com/color/96/000000/trash--v1.png" alt="Trash Icon" style="width:72px; margin: 0 auto 16px;"/>
                                            <div class="modal-body p-0">
                                                <h4 class="fw-bold mb-2" style="font-size:1.25rem;">Apakah Anda yakin ingin menghapus pelanggan ini?</h4>
                                                <div class="mb-4 text-muted" style="font-size:1rem;">"{{ $pelanggan->nama }}"</div>
                                            </div>
                                            <div class="d-flex gap-2 justify-content-center mt-2">
                                                <button type="button" class="btn btn-outline-secondary btn-lg px-4" data-bs-dismiss="modal">Batal</button>
                                                <form action="{{ route('pelanggan.destroy', $pelanggan->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-lg px-4">Hapus</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
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
<style>
.modal-confirm .modal-content {
    border-radius: 18px;
    border: none;
    box-shadow: 0 5px 24px rgba(0,0,0,0.13);
    padding: 0;
    max-width: 370px;
}
.modal-confirm .btn-lg {
    font-size: 1.1rem;
    border-radius: 8px;
    min-width: 120px;
    font-weight: 500;
    padding-top: 10px;
    padding-bottom: 10px;
}
.modal-confirm .btn-outline-secondary {
    background: #fff;
    border: 2px solid #e0e0e0;
    color: #333;
    transition: background 0.2s, color 0.2s;
}
.modal-confirm .btn-outline-secondary:hover {
    background: #f3f3f3;
    color: #111;
}
.modal-confirm .btn-danger {
    background: #e53935;
    border: none;
    transition: background 0.2s;
}
.modal-confirm .btn-danger:hover {
    background: #b71c1c;
}
.modal.show .modal-dialog {
    display: flex !important;
    align-items: center;
    justify-content: center;
    min-height: 100vh;
    margin: 0 auto;
}
.modal.fade .modal-dialog {
    transition: transform 0.3s ease-out;
    transform: scale(0.95);
}
.modal.show .modal-dialog {
    transform: scale(1);
}
</style>
@endsection
