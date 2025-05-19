<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class dokter extends Model
{
    protected $table = 'dokters';
    protected $fillable = ['nama_dokter', 'spesialis', 'hari', 'jam_awal_praktik', 'jam_akhir_praktik', 'status'];

}

