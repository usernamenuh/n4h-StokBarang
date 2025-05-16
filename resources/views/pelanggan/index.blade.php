@extends('layouts.app')
@section('content')

<div class="container">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h2>Data Pelanggan</h2>
            <a href="{{ route('pelanggan.create') }}" class="btn btn-primary">Tambah Pelanggan</a>
        </div>
        <div class="card-body">
         <table class="table table-bordered">
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
                        <a href="{{ route('pelanggan.edit', $pelanggan->id) }}" class="btn btn-warning">Edit</a>
                        <form action="{{ route('pelanggan.destroy', $pelanggan->id) }}" method="POST" style="display:inline;">
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