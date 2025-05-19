<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class nilai extends Model
{
    protected $table = 'nilais';
    protected $fillable = ['nama_mahasiswa', 'nim', 'nilai_rata_rata'];
}
