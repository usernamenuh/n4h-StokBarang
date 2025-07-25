<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\ParetoController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\TransaksiImportController;
use App\Http\Controllers\BarangImportController;

Auth::routes();

Route::middleware(['auth'])->group(function () {
    // Home/Dashboard
    Route::get('/', [HomeController::class, 'index'])->name('home');
    
    // Management Barang

    
Route::get('/barang/import', [BarangImportController::class, 'showImportForm'])->name('barang.import.form');
Route::post('/barang/import', [BarangImportController::class, 'import'])->name('barang.import');
Route::get('/barang/template', [BarangImportController::class, 'downloadTemplate'])->name('barang.template');

    // Pareto Analysis
    Route::prefix('pareto')->name('pareto.')->group(function () {
        Route::get('/', [ParetoController::class, 'index'])->name('index');
        Route::post('/analyze', [ParetoController::class, 'analyze'])->name('analyze');
        Route::get('/results/{type}', [ParetoController::class, 'results'])->name('results');
    });
});

// Routes untuk import transaksi
Route::get('/transaksi/import', [TransaksiImportController::class, 'showImportForm'])->name('transaksi.import.form');
Route::post('/transaksi/import', [TransaksiImportController::class, 'import'])->name('transaksi.import');
Route::get('/transaksi/clear', [TransaksiImportController::class, 'clearData'])->name('transaksi.clear');
Route::get('/transaksi/test', [TransaksiImportController::class, 'testData'])->name('transaksi.test');
Route::get('/transaksi/import/template', [TransaksiImportController::class, 'downloadTemplate'])->name('transaksi.import.template');
Route::get('/transaksi/test', [TransaksiImportController::class, 'testData'])->name('transaksi.test');
