<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;



class Barang extends Model
{
    protected $table = 'barangs';

    protected $primaryKey = 'id'; // ✅ perbaiki
    public $incrementing = true;  // ✅ karena pakai auto increment id

    protected $fillable = [
        'kode',
        'nama',
        'does_pcs',  // ✅ ini stok
        'golongan',
        'hbeli',
        'user_id',
        'keterangan',
    ];

    public function transaksiDetails()
    {
        return $this->hasMany(TransaksiDetail::class, 'barang_id', 'id');
    }
}


