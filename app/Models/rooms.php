<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class rooms extends Model
{
    protected $fillable = [
        'type',
        'stock',
        'price',
    ];
}
