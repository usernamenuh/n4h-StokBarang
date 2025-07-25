<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaksi extends Model
{
    use HasFactory;

    protected $table = 'transaksi';
    
    protected $fillable = [
        'tanggal',
        'nomor',
        'customer',
        'barang_id',
        'qty',
        'subtotal',
        'disc',
        'ongkos',
        'total',
        'keterangan',
        'user_id'
    ];

    protected $casts = [
        'tanggal' => 'date',
        'subtotal' => 'decimal:2',
        'disc' => 'decimal:2',
        'ongkos' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    public function barang(): BelongsTo
    {
        return $this->belongsTo(Barang::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}