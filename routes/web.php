<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;


Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::resource('pelanggan', App\Http\Controllers\PelangganController::class);
Route::resource('hotel', App\Http\Controllers\HotelController::class);
Route::resource('rooms', App\Http\Controllers\RoomController::class);
Route::resource('barang', App\Http\Controllers\BarangController::class);
Route::resource('transaksi', App\Http\Controllers\TransaksiController::class);