@extends('layouts.demo')

@section('content')
<div class="container mt-4">
    <h2>Import Data Barang</h2>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->has('file'))
        <div class="alert alert-danger">
            {{ $errors->first('file') }}
        </div>
    @endif

    @if(session('import_errors'))
        <div class="alert alert-warning">
            <strong>Beberapa data gagal diimpor:</strong>
            <ul>
                @foreach(session('import_errors') as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('barang.import') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="file" class="form-label">Pilih File Excel</label>
            <input type="file" name="file" id="file" class="form-control" required accept=".xlsx,.xls,.csv">
        </div>
        <button type="submit" class="btn btn-primary">Import Barang</button>
    </form>

    <hr>

    <a href="{{ route('barang.template') }}" class="btn btn-outline-secondary">Download Template Excel</a>
</div>
@endsection
