@extends('layouts.demo')

@section('content')
<div class="">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h1>Mobil</h1>
            <a href="{{ route('mobil.create') }}" class="btn btn-primary">Tambah Mobil</a>
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
            <table class="table table-bordered" id="hotel-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nomor Polisi</th>
                        <th>Type Kendaraan</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($mobils as $mobil => $m)
                    <tr>
                        <td>{{ $mobil + 1 }}</td>
                        <td>{{ $m->nomor_polisi }}</td>
                        <td>{{ $m->type_kendaraan }}</td>
                        <td>
                            @if ($m->status == 'dirental')
                                <span class="badge bg-label-danger">Dirental</span>
                            @else
                                <span class="badge bg-label-success">Tersedia</span>
                            @endif
                        </td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-link text-dark p-0" type="button" id="aksiDropdown{{ $m->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bx bx-dots-vertical-rounded" style="font-size: 1.5rem;"></i>
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="aksiDropdown{{ $m->id }}">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('mobil.edit', $m->id) }}">
                                            <i class="bx bx-edit me-2"></i> Edit
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('mobil.show', $m->id) }}">
                                            <i class="bx bx-show me-2"></i> Show
                                        </a>
                                    </li>
                                    <li>
                                        <button type="button" class="dropdown-item text-danger" data-bs-toggle="modal" data-bs-target="#deleteModalMobil{{ $m->id }}">
                                            <i class="bx bx-trash me-2"></i> Delete
                                        </button>
                                    </li>
                                </ul>
                            </div>
                            <!-- Delete Modal -->
                            <div class="modal fade modal-confirm" id="deleteModalMobil{{ $m->id }}" tabindex="-1" aria-labelledby="deleteModalLabelMobil{{ $m->id }}" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content text-center p-4">
                                        <img src="https://img.icons8.com/color/96/000000/trash--v1.png" alt="Trash Icon" style="width:72px; margin: 0 auto 16px;"/>
                                        <div class="modal-body p-0">
                                            <h4 class="fw-bold mb-2" style="font-size:1.25rem;">Apakah Anda yakin ingin menghapus mobil ini?</h4>
                                            <div class="mb-4 text-muted" style="font-size:1rem;">"{{ $m->nomor_polisi }}"</div>
                                        </div>
                                        <div class="d-flex gap-2 justify-content-center mt-2">
                                            <button type="button" class="btn btn-outline-secondary btn-lg px-4" data-bs-dismiss="modal">Batal</button>
                                            <form action="{{ route('mobil.destroy', $m->id) }}" method="POST" class="d-inline">
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
