@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4>Import Data Transaksi</h4>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {!! session('success') !!}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('transaksi.import') }}" method="POST" enctype="multipart/form-data" id="importForm">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="file" class="form-label">Pilih File Excel</label>
                            <input type="file" class="form-control" id="file" name="file" accept=".xlsx,.xls,.csv" required>
                            <div class="form-text">
                                Format yang didukung: .xlsx, .xls, .csv (Maksimal 10MB)
                            </div>
                        </div>

                        <div class="mb-3">
                            <button type="button" class="btn btn-info me-2" id="previewBtn">
                                <i class="fas fa-eye"></i> Preview Data
                            </button>
                            <button type="submit" class="btn btn-primary" id="importBtn">
                                <i class="fas fa-upload"></i> Import Data
                            </button>
                            <a href="{{ route('transaksi.import.template') }}" class="btn btn-secondary">
                                <i class="fas fa-download"></i> Download Template
                            </a>
                            <a href="{{ route('transaksi.clear') }}" class="btn btn-warning ms-2" 
                               onclick="return confirm('Yakin ingin hapus semua data?')">
                                🗑️ Hapus Semua Data
                            </a>
                        </div>
                    </form>

                    <!-- Preview Section -->
                    <div id="previewSection" style="display: none;">
                        <hr>
                        <h5>Preview Data</h5>
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered" id="previewTable">
                                <thead class="table-light">
                                    <tr>
                                        <th>Baris</th>
                                        <th>Tanggal</th>
                                        <th>Nomor</th>
                                        <th>Customer</th>
                                        <th>Subtotal</th>
                                        <th>Diskon</th>
                                        <th>Ongkir</th>
                                        <th>Total</th>
                                        <th>Keterangan</th>
                                        <th>User</th>
                                        <th>Tgl Input</th>
                                        <th>User ID</th>
                                    </tr>
                                </thead>
                                <tbody id="previewTableBody">
                                </tbody>
                            </table>
                        </div>
                        <div id="previewInfo" class="text-muted"></div>
                    </div>

                    <div class="mt-4">
                        <h6>Status Database:</h6>
                        <div class="alert alert-info">
                            <strong>Transaksi:</strong> {{ \App\Models\Transaksi::count() }} records<br>
                            <strong>Detail:</strong> {{ \App\Models\TransaksiDetail::count() }} records
                        </div>
                    </div>

                    <div class="mt-4">
                        <h6>Cara Kerja Import:</h6>
                        <div class="alert alert-secondary">
                            <ul class="mb-0">
                                <li>Baris 1-2: Diabaikan (header)</li>
                                <li>Baris dengan kolom B berisi tanda "-" dan kolom C tidak kosong = <strong>TRANSAKSI</strong></li>
                                <li>Baris lainnya yang tidak kosong = <strong>DETAIL BARANG</strong></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const previewBtn = document.getElementById('previewBtn');
    const fileInput = document.getElementById('file');
    const previewSection = document.getElementById('previewSection');
    const previewTableBody = document.getElementById('previewTableBody');
    const previewInfo = document.getElementById('previewInfo');

    previewBtn.addEventListener('click', function() {
        if (!fileInput.files[0]) {
            alert('Pilih file terlebih dahulu');
            return;
        }

        const formData = new FormData();
        formData.append('file', fileInput.files[0]);
        formData.append('_token', document.querySelector('input[name="_token"]').value);

        previewBtn.disabled = true;
        previewBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Loading...';

      
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                displayPreview(data.data, data.total_rows);
                previewSection.style.display = 'block';
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat preview');
        })
        .finally(() => {
            previewBtn.disabled = false;
            previewBtn.innerHTML = '<i class="fas fa-eye"></i> Preview Data';
        });
    });

    function displayPreview(data, totalRows) {
        previewTableBody.innerHTML = '';
        
        data.forEach((row, index) => {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>${index + 1}</td>
                <td>${row[0] || ''}</td>
                <td>${row[1] || ''}</td>
                <td>${row[2] || ''}</td>
                <td>${row[3] || ''}</td>
                <td>${row[4] || ''}</td>
                <td>${row[5] || ''}</td>
                <td>${row[6] || ''}</td>
                <td>${row[7] || ''}</td>
                <td>${row[8] || ''}</td>
                <td>${row[9] || ''}</td>
                <td>${row[10] || ''}</td>
            `;
            previewTableBody.appendChild(tr);
        });

        previewInfo.innerHTML = `Menampilkan ${data.length} dari ${totalRows} baris total`;
    }
});
</script>
@endsection
