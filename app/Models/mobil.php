<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class mobil extends Model
{
    protected $table = 'mobils';
    protected $fillable = ['nomor_polisi', 'type_kendaraan'];
}
