<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class pelanggan extends Model
{
    protected $table = 'pelanggans';

    protected $fillable = [
        'id_pelanggan',
        'nama',
        'alamat',
        'telepon'
    ];
}
