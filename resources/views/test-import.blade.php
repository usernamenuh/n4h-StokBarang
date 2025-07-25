<!DOCTYPE html>
<html>
<head>
    <title>Test Import Debug</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Test Import Debug</h2>
    
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Test Transaksi Import</div>
                <div class="card-body">
                    <form action="{{ route('imports.transaksi') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label>Upload Excel File</label>
                            <input type="file" name="file" class="form-control" accept=".xlsx,.xls,.csv" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Import Transaksi</button>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Test Barang Import</div>
                <div class="card-body">
                    <form action="{{ route('imports.barang') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label>Upload Excel File</label>
                            <input type="file" name="file" class="form-control" accept=".xlsx,.xls,.csv" required>
                        </div>
                        <button type="submit" class="btn btn-success">Import Barang</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    @if(session('success'))
        <div class="alert alert-success mt-3">{{ session('success') }}</div>
    @endif
    
    @if(session('error'))
        <div class="alert alert-danger mt-3">{{ session('error') }}</div>
    @endif
    
    <div class="mt-4">
        <h4>Debug Info</h4>
        <p><strong>Laravel Version:</strong> {{ app()->version() }}</p>
        <p><strong>PHP Version:</strong> {{ phpversion() }}</p>
        <p><strong>Storage Path:</strong> {{ storage_path('app/imports') }}</p>
        <p><strong>Log Path:</strong> {{ storage_path('logs/laravel.log') }}</p>
    </div>
</div>
</body>
</html>
