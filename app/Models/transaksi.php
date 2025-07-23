<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class transaksi extends Model
{
    protected $table = 'transaksis';
    protected $fillable = ['id_barang', 'quantity', 'total'];

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'id_barang');
    }
    
}
