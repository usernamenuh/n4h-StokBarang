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
use App\Http\Controllers\LaporanController;



Auth::routes();
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::controller(BarangController::class)->group(function () {
    // Import & template harus di atas agar tidak tertimpa route parameter
    Route::get('/barang/import', 'showImportForm')->name('barang.import.form');
    Route::post('/barang/import', 'import')->name('barang.import');
    Route::get('/barang/template', 'downloadTemplate')->name('barang.template');

    Route::get('/barang', 'index')->name('barang.index');
    Route::get('/barang/create', 'create')->name('barang.create');
    Route::post('/barang', 'store')->name('barang.store');
    Route::get('/barang/{barang}/edit', 'edit')->name('barang.edit');
    Route::put('/barang/{barang}', 'update')->name('barang.update');
    Route::delete('/barang/{barang}', 'destroy')->name('barang.destroy');
    Route::get('/barang/{barang}', 'show')->name('barang.show');
});


Route::controller(TransaksiController::class)->group(function () {
    Route::get('/transaksi/import', 'showImportForm')->name('transaksi.import.form')->middleware('RoleCheck');
    Route::post('/transaksi/import', 'import')->name('transaksi.import');
    Route::get('/transaksi/clear', 'clearData')->name('transaksi.clear');
    Route::get('/transaksi/test', 'testData')->name('transaksi.test');
    Route::get('/transaksi/import/template', 'downloadTemplate')->name('transaksi.import.template');
});
Route::resource('transaksi', TransaksiController::class);


Route::get('/laporan/pareto', [LaporanController::class, 'analisisPareto'])->name('laporan.pareto');
Route::get('/laporan/pareto/export', [App\Http\Controllers\LaporanController::class, 'exportPareto'])->name('laporan.pareto.export');