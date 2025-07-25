<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransaksiDetail extends Model
{
   protected $table = 'transaksi_details'; // per permintaanmu

    protected $fillable = [
        'transaksi_id',
        'barang_id',
        'kode_barang',
        'nama_barang',
        'qty',
        'harga_satuan',
        'subtotal',
    ];

    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class);
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }
}
