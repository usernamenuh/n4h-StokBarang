<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\transaksi;

class Barang extends Model
{
    protected $table = 'barangs';
    protected $fillable = ['nama_barang', 'kode_barang', 'stok', 'harga', 'timestamp'];

    public function transaksis()
    {
        return $this->hasMany(transaksi::class, 'id_barang'); 
    }
}


