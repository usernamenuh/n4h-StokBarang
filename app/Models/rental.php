<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class rental extends Model
{
    protected $table = 'rentals';
    protected $fillable = ['mobil_id', 'tanggal_awal_sewa', 'tanggal_akhir_sewa'];
    protected $dates = ['tanggal_awal_sewa', 'tanggal_akhir_sewa'];
    protected $casts = [
        'tanggal_awal_sewa' => 'date',
        'tanggal_akhir_sewa' => 'date',
    ];

    public function mobil()
    {
        return $this->belongsTo(Mobil::class);
    }

    public static function isMobilAvailable($mobil_id, $tanggal_awal, $tanggal_akhir)
    {
        return !self::where('mobil_id', $mobil_id)
            ->where(function($query) use ($tanggal_awal, $tanggal_akhir) {
                $query->whereBetween('tanggal_awal_sewa', [$tanggal_awal, $tanggal_akhir])
                      ->orWhereBetween('tanggal_akhir_sewa', [$tanggal_awal, $tanggal_akhir])
                      ->orWhere(function($q) use ($tanggal_awal, $tanggal_akhir) {
                          $q->where('tanggal_awal_sewa', '<=', $tanggal_awal)
                            ->where('tanggal_akhir_sewa', '>=', $tanggal_akhir);
                      });
            })
            ->exists();
    }
}
