<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\rooms;

class hotel extends Model
{
    protected $table = 'hotels';

    protected $fillable = [
        'pelanggan_id',
        'room_id',
        'check_in',
        'check_out'
    ];

    // Relasi ke pelanggan
    public function pelanggan()
    {
        return $this->belongsTo(\App\Models\pelanggan::class, 'pelanggan_id');
    }
    
    // Relasi ke kamar
    public function room()
    {
        return $this->belongsTo(rooms::class);
    }
}
