<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Barang extends Model
{
    use HasFactory;

    protected $table = 'barang';
    
    protected $fillable = [
        'kode',
        'nama',
        'does_pcs',
        'golongan',
        'hbeli',
        'user_id',
        'keterangan'
    ];

    protected $casts = [
        'does_pcs' => 'decimal:2',
        'hbeli' => 'decimal:2',
    ];

    public function transaksi(): HasMany
    {
        return $this->hasMany(Transaksi::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Calculate total sales for ABC analysis
    public function getTotalSalesAttribute()
    {
        return $this->transaksi()->sum('total');
    }

    // Calculate total quantity sold
    public function getTotalQtyAttribute()
    {
        return $this->transaksi()->sum('qty');
    }
}