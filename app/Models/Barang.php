<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    protected $table = 'barangs';

    protected $primaryKey = 'kode';
    public $incrementing = false; // Karena kode_barang bukan integer auto increment

    protected $fillable = [
        'kode',
        'nama',
        'does_pcs',
        'golongan',
        'hbeli',
        'user_id',
        'keterangan',
    ];

    // Jika kamu ingin relasi ke transaksi detail
    public function transaksiDetails()
    {
        return $this->hasMany(TransaksiDetail::class, 'kode_barang', 'kode_barang');
    }
}
