<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class booking extends Model
{
    protected $table = 'bookings';
    protected $fillable = ['nama_pasien', 'dokter_id', 'hari', 'jam_awal_praktik'];

    public function dokter()
    {
        return $this->belongsTo(Dokter::class);
    }
}
