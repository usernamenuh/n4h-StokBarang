<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\ParetoController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Auth::routes();

Route::middleware(['auth'])->group(function () {
    // Home/Dashboard
    Route::get('/', [HomeController::class, 'index'])->name('home');
    
    // Management Barang
    Route::resource('barang', BarangController::class);
    Route::resource('transaksi', TransaksiController::class);
    
    // Import & Analysis - Fixed route names to match your template
    Route::prefix('import')->name('import.')->group(function () {
        Route::get('/', [ImportController::class, 'index'])->name('index');
        Route::post('/barang', [ImportController::class, 'importBarang'])->name('barang');
        Route::post('/transaksi', [ImportController::class, 'importTransaksi'])->name('transaksi');
    });
    
    // Alternative route group to match your home template
    Route::prefix('imports')->name('imports.')->group(function () {
        Route::get('/', [ImportController::class, 'index'])->name('index');
        Route::post('/barang', [ImportController::class, 'importBarang'])->name('barang');
        Route::post('/transaksi', [ImportController::class, 'importTransaksi'])->name('transaksi');
    });
    
    // Pareto Analysis
    Route::prefix('pareto')->name('pareto.')->group(function () {
        Route::get('/', [ParetoController::class, 'index'])->name('index');
        Route::post('/analyze', [ParetoController::class, 'analyze'])->name('analyze');
        Route::get('/results/{type}', [ParetoController::class, 'results'])->name('results');
    });
});

Route::get('/check-users', function() {
    $users = \App\Models\User::all(['id', 'name', 'email']);
    return response()->json($users);
})->middleware('auth');