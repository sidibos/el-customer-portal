<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MeterReading extends Model
{
    use HasFactory;
    
    protected $fillable = ['meter_id', 'reading', 'read_at'];

    protected $casts = [
        'read_at' => 'datetime',
    ];

    public function meter(): BelongsTo
    {
        return $this->belongsTo(Meter::class);
    }
}
