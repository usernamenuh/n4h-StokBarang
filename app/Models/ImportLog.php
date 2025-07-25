<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImportLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'file_name',
        'file_path',
        'import_type',
        'total_rows',
        'successful_rows',
        'failed_rows',
        'status',
        'error_details',
        'imported_by',
        'imported_at'
    ];

    protected $casts = [
        'error_details' => 'array',
        'imported_at' => 'datetime',
        'total_rows' => 'integer',
        'successful_rows' => 'integer',
        'failed_rows' => 'integer'
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class, 'imported_by');
    }

    // Scopes
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }
}
