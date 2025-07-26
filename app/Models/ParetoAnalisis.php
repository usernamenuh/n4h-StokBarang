<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class ParetoAnalisis extends Model
{
    protected $table = 'pareto_analises';

    protected $fillable = [
        'barang_id',
        'nama_barang',
        'total_qty',
        'total_nilai',
        'persentase',
        'kategori',
        'stok_saat_ini',
        'periode',
    ];

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    }

}
