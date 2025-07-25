<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParetoAnalysis extends Model
{
    use HasFactory;

    protected $table = 'pareto_analysis';

    protected $fillable = [
        'analysis_type',
        'period',
        'item_id',
        'item_name',
        'total_value',
        'total_qty',
        'percentage',
        'cumulative_percentage',
        'abc_category',
        'rank'
    ];

    protected $casts = [
        'total_value' => 'decimal:2',
        'percentage' => 'decimal:2',
        'cumulative_percentage' => 'decimal:2',
    ];
}