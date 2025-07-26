<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $table = 'transaksis';

    protected $fillable = [
        'tanggal',
        'nomor',
        'customer',
        'subtotal',
        'diskon',
        'ongkir',
        'total',
        'keterangan',
        'user_id',
        'tgl_input', 'jum_print',
    ];

    public function details()
    {
        return $this->hasMany(TransaksiDetail::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}