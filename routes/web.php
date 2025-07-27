<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\ParetoController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\TransaksiImportController;
use App\Http\Controllers\BarangImportController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Laravel default auth routes
Auth::routes();

// Root redirect - jika user sudah login ke home, jika belum ke login
Route::get('/', function () {
    return Auth::check() ? redirect()->route('home') : redirect()->route('login');
});

// Protected routes - hanya untuk user yang sudah login
Route::middleware('auth')->group(function () {
    
    // Home route (halaman transisi)
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    
    // Dashboard baru (sistem StockMaster)
    Route::prefix('dashboard')->name('dashboard.')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('index');
        Route::get('/barang', [DashboardController::class, 'barang'])->name('barang');
        Route::get('/transaksi', [DashboardController::class, 'transaksi'])->name('transaksi');
        Route::get('/analisis', [DashboardController::class, 'analisis'])->name('analisis');
    });
    
    // Alias untuk dashboard utama (backward compatibility)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Barang Management Routes
    Route::controller(BarangController::class)->group(function () {
        // Import & template routes (harus di atas agar tidak tertimpa route parameter)
        Route::get('/barang/import', 'showImportForm')->name('barang.import.form');
        Route::post('/barang/import', 'import')->name('barang.import');
        Route::get('/barang/template', 'downloadTemplate')->name('barang.template');
        
        // CRUD routes
        Route::get('/barang', 'index')->name('barang.index');
        Route::get('/barang/create', 'create')->name('barang.create');
        Route::post('/barang', 'store')->name('barang.store');
        Route::get('/barang/{barang}/edit', 'edit')->name('barang.edit');
        Route::put('/barang/{barang}', 'update')->name('barang.update');
        Route::delete('/barang/{barang}', 'destroy')->name('barang.destroy');
        Route::get('/barang/{barang}', 'show')->name('barang.show');
    });
    
    // Transaksi Management Routes
    Route::controller(TransaksiController::class)->group(function () {
        // Import routes dengan middleware
        Route::get('/transaksi/import', 'showImportForm')->name('transaksi.import.form')->middleware('RoleCheck');
        Route::post('/transaksi/import', 'import')->name('transaksi.import');
        Route::get('/transaksi/import/template', 'downloadTemplate')->name('transaksi.import.template');
        
        // Utility routes
        Route::get('/transaksi/clear', 'clearData')->name('transaksi.clear');
        Route::get('/transaksi/test', 'testData')->name('transaksi.test');
    });
    
    // Transaksi resource routes
    Route::resource('transaksi', TransaksiController::class);
    
    // Laporan & Analisis Routes
    Route::prefix('laporan')->name('laporan.')->group(function () {
        Route::get('/pareto', [LaporanController::class, 'analisisPareto'])->name('pareto');
        Route::get('/pareto/export', [LaporanController::class, 'exportPareto'])->name('pareto.export');
    });
    
    // Alias routes untuk backward compatibility
    Route::get('/analisis', function() {
        return redirect()->route('laporan.pareto');
    })->name('analisis.index');
    
});

// API Routes (jika diperlukan untuk AJAX calls)
Route::middleware(['auth', 'api'])->prefix('api')->group(function () {
    Route::get('/stats', [HomeController::class, 'getStats'])->name('api.stats');
    Route::get('/dashboard/stats', [DashboardController::class, 'getStats'])->name('api.dashboard.stats');
});
