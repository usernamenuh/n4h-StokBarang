<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Auth::routes();

Route::get('/', function () {
    return Auth::check() ? redirect()->route('dashboard') : redirect()->route('login');
});

Route::middleware('auth')->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::controller(BarangController::class)->group(function () {

        Route::middleware('role:admin_gudang')->group(function () {

            Route::get('/barang/import', 'showImportForm')->name('barang.import.form');
            Route::post('/barang/import', 'import')->name('barang.import');
            Route::get('/barang/template', 'downloadTemplate')->name('barang.template');
        });
    });

    Route::controller(TransaksiController::class)->group(function () {
        Route::middleware('role:admin_gudang')->group(function () {
            Route::get('/transaksi/import', 'showImportForm')->name('transaksi.import.form');
        });

        Route::post('/transaksi/import', 'import')->name('transaksi.import');
        Route::get('/transaksi/import/template', 'downloadTemplate')->name('transaksi.import.template');
        Route::get('/transaksi/clear', 'clearData')->name('transaksi.clear');
        Route::get('/transaksi/test', 'testData')->name('transaksi.test');
    });

    Route::resource('transaksi', TransaksiController::class);
 Route::resource('barang', BarangController::class);
    Route::prefix('laporan')->name('laporan.')->group(function () {
        Route::get('/pareto', [LaporanController::class, 'analisisPareto'])->name('pareto');
        Route::get('/pareto/export', [LaporanController::class, 'exportPareto'])->name('pareto.export');
    });
});
