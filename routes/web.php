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
Route::resource('nilai', App\Http\Controllers\NilaiController::class);
Route::resource('mobil', App\Http\Controllers\MobilController::class);
Route::resource('rental', App\Http\Controllers\RentalController::class);
Route::resource('dokter', App\Http\Controllers\DokterController::class);
Route::resource('booking', App\Http\Controllers\BookingController::class);