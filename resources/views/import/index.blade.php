@extends('layouts.demo')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm rounded-4 mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-semibold">
                        <i class="bx bx-upload me-2 text-primary"></i>Import Data Excel
                    </h5>
                    <div class="d-flex gap-2">
                        <span class="badge bg-success">{{ $barangCount ?? 0 }} Barang</span>
                        <span class="badge bg-primary">{{ $transaksiCount ?? 0 }} Transaksi</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Import Forms -->
    <div class="row g-4">
        <!-- Barang Import -->
        <div class="col-md-6">
            <div class="card shadow-sm rounded-4 h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="d-flex align-items-center justify-content-center bg-success bg-opacity-10 rounded-3 me-3" style="width:40px; height:40px;">
                            <i class="bx bx-box" style="font-size:1.5rem; color:#4CAF50;"></i>
                        </div>
                        <h5 class="mb-0 fw-semibold">Import Data Barang</h5>
                    </div>
                    
                    <form id="barangForm" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-4">
                            <label class="form-label fw-medium">Select Excel File</label>
                            <input type="file" name="file" id="barangFile" accept=".xlsx,.xls,.csv" 
                                   class="form-control" required>
                            <div class="form-text">Max file size: 10MB</div>
                        </div>
                        <button type="submit" class="btn btn-success w-100" id="barangSubmit">
                            <i class="bx bx-upload me-2"></i>Import Barang
                        </button>
                    </form>
                    
                    <div class="mt-4">
                        <h6 class="fw-semibold text-muted mb-2">Format Excel yang diperlukan:</h6>
                        <ul class="list-unstyled small text-muted">
                            <li><strong>Required:</strong> kode, nama, golongan, hbeli</li>
                            <li><strong>Optional:</strong> does_pcs, keterangan</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Transaksi Import -->
        <div class="col-md-6">
            <div class="card shadow-sm rounded-4 h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="d-flex align-items-center justify-content-center bg-primary bg-opacity-10 rounded-3 me-3" style="width:40px; height:40px;">
                            <i class="bx bx-receipt" style="font-size:1.5rem; color:#2196F3;"></i>
                        </div>
                        <h5 class="mb-0 fw-semibold">Import Data Transaksi</h5>
                    </div>
                    
                    <form id="transaksiForm" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-4">
                            <label class="form-label fw-medium">Select Excel File</label>
                            <input type="file" name="file" id="transaksiFile" accept=".xlsx,.xls,.csv" 
                                   class="form-control" required>
                            <div class="form-text">Max file size: 10MB</div>
                        </div>
                        <button type="submit" class="btn btn-primary w-100" id="transaksiSubmit">
                            <i class="bx bx-upload me-2"></i>Import Transaksi
                        </button>
                    </form>
                    
                    <div class="mt-4">
                        <h6 class="fw-semibold text-muted mb-2">Format Excel yang diperlukan:</h6>
                        <ul class="list-unstyled small text-muted">
                            <li><strong>Required:</strong> tanggal, nomor, customer, subtotal, total</li>
                            <li><strong>Optional:</strong> kode_barang, nama_barang, qty, disc, ongkos</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow-sm rounded-4">
                <div class="card-body">
                    <h6 class="fw-semibold mb-3">Quick Actions</h6>
                    <div class="d-flex flex-wrap gap-2">
                        <a href="{{ route('home') }}" class="btn btn-outline-primary btn-sm">
                            <i class="bx bx-home me-1"></i>Dashboard
                        </a>
                        <a href="{{ route('pareto.index') }}" class="btn btn-outline-success btn-sm">
                            <i class="bx bx-bar-chart-alt-2 me-1"></i>ABC Analysis
                        </a>
                        <a href="{{ route('barang.index') }}" class="btn btn-outline-info btn-sm">
                            <i class="bx bx-box me-1"></i>Data Barang
                        </a>
                        <a href="{{ route('transaksi.index') }}" class="btn btn-outline-warning btn-sm">
                            <i class="bx bx-receipt me-1"></i>Data Transaksi
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Import Progress Modal -->
<div class="modal fade" id="importModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title" id="importModalTitle">
                    <i class="bx bx-upload me-2"></i>Importing Data...
                </h5>
            </div>
            <div class="modal-body text-center py-4">
                <div class="mb-4">
                    <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
                
                <h6 id="importStatus" class="mb-3">Memproses file Excel...</h6>
                
                <div class="progress mb-3" style="height: 8px;">
                    <div class="progress-bar progress-bar-striped progress-bar-animated" 
                         id="importProgress" role="progressbar" style="width: 0%"></div>
                </div>
                
                <div id="importDetails" class="small text-muted">
                    <div>Processed: <span id="processedCount">0</span> rows</div>
                    <div>Errors: <span id="errorCount">0</span></div>
                    <div>Time elapsed: <span id="timeElapsed">0s</span></div>
                </div>
                
                <div id="importResult" class="mt-3" style="display: none;">
                    <div class="alert alert-success" id="successAlert" style="display: none;">
                        <i class="bx bx-check-circle me-2"></i>
                        <span id="successMessage"></span>
                    </div>
                    <div class="alert alert-danger" id="errorAlert" style="display: none;">
                        <i class="bx bx-error-circle me-2"></i>
                        <span id="errorMessage"></span>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0" id="modalFooter" style="display: none;">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="viewErrors" style="display: none;">
                    View Errors
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Error Details Modal -->
<div class="modal fade" id="errorModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bx bx-error-circle me-2 text-danger"></i>Import Errors
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Row</th>
                                <th>Field</th>
                                <th>Error</th>
                            </tr>
                        </thead>
                        <tbody id="errorTableBody">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let startTime;
    let timerInterval;
    
    // Handle Barang Import
    document.getElementById('barangForm').addEventListener('submit', function(e) {
        e.preventDefault();
        handleImport('barang', this);
    });
    
    // Handle Transaksi Import
    document.getElementById('transaksiForm').addEventListener('submit', function(e) {
        e.preventDefault();
        handleImport('transaksi', this);
    });
    
    function handleImport(type, form) {
        const fileInput = form.querySelector('input[type="file"]');
        const file = fileInput.files[0];
        
        if (!file) {
            toastr.error('Please select a file first!');
            return;
        }
        
        // Show modal
        const modal = new bootstrap.Modal(document.getElementById('importModal'));
        modal.show();
        
        // Reset modal content
        resetModal();
        document.getElementById('importModalTitle').innerHTML = 
            `<i class="bx bx-upload me-2"></i>Importing ${type.charAt(0).toUpperCase() + type.slice(1)}...`;
        
        // Start timer
        startTime = Date.now();
        timerInterval = setInterval(updateTimer, 1000);
        
        // Prepare form data
        const formData = new FormData();
        formData.append('file', file);
        formData.append('_token', form.querySelector('input[name="_token"]').value);
        
        // Send AJAX request
        fetch(`{{ url('/import') }}/${type}`, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            clearInterval(timerInterval);
            handleImportResponse(data);
        })
        .catch(error => {
            clearInterval(timerInterval);
            handleImportError(error);
        });
    }
    
    function resetModal() {
        document.getElementById('importStatus').textContent = 'Memproses file Excel...';
        document.getElementById('importProgress').style.width = '0%';
        document.getElementById('processedCount').textContent = '0';
        document.getElementById('errorCount').textContent = '0';
        document.getElementById('timeElapsed').textContent = '0s';
        document.getElementById('importResult').style.display = 'none';
        document.getElementById('modalFooter').style.display = 'none';
        document.getElementById('successAlert').style.display = 'none';
        document.getElementById('errorAlert').style.display = 'none';
    }
    
    function updateTimer() {
        const elapsed = Math.floor((Date.now() - startTime) / 1000);
        document.getElementById('timeElapsed').textContent = elapsed + 's';
        
        // Simulate progress (since we don't have real-time progress from server)
        const progress = Math.min(90, elapsed * 2);
        document.getElementById('importProgress').style.width = progress + '%';
    }
    
    function handleImportResponse(data) {
        document.getElementById('importProgress').style.width = '100%';
        document.getElementById('importStatus').textContent = 'Import completed!';
        document.getElementById('processedCount').textContent = data.processed || 0;
        document.getElementById('errorCount').textContent = data.errors ? data.errors.length : 0;
        
        document.getElementById('importResult').style.display = 'block';
        document.getElementById('modalFooter').style.display = 'flex';
        
        if (data.success) {
            document.getElementById('successAlert').style.display = 'block';
            document.getElementById('successMessage').textContent = data.message;
            
            if (data.errors && data.errors.length > 0) {
                document.getElementById('viewErrors').style.display = 'inline-block';
                window.importErrors = data.errors;
            }
            
            // Refresh page after 3 seconds
            setTimeout(() => {
                location.reload();
            }, 3000);
        } else {
            document.getElementById('errorAlert').style.display = 'block';
            document.getElementById('errorMessage').textContent = data.message;
        }
    }
    
    function handleImportError(error) {
        document.getElementById('importStatus').textContent = 'Import failed!';
        document.getElementById('importResult').style.display = 'block';
        document.getElementById('modalFooter').style.display = 'flex';
        document.getElementById('errorAlert').style.display = 'block';
        document.getElementById('errorMessage').textContent = 'An unexpected error occurred.';
    }
    
    // View Errors button
    document.getElementById('viewErrors').addEventListener('click', function() {
        if (window.importErrors) {
            const tbody = document.getElementById('errorTableBody');
            tbody.innerHTML = '';
            
            window.importErrors.forEach(error => {
                const row = tbody.insertRow();
                row.innerHTML = `
                    <td>${error.row || 'N/A'}</td>
                    <td>${error.field || 'N/A'}</td>
                    <td>${error.message || error}</td>
                `;
            });
            
            const errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
            errorModal.show();
        }
    });
});
</script>
@endsection