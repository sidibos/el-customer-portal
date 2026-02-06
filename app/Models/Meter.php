<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Meter extends Model
{
    use HasFactory;
    
    protected $fillable = ['site_id', 'meter_identifier', 'type', 'is_active'];

    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class);
    }

    public function readings(): HasMany
    {
        return $this->hasMany(MeterReading::class);
    }

    // convenient access to latest reading
    public function latestReading(): HasOne
    {
        return $this->hasOne(MeterReading::class)->latestOfMany('read_at');
    }

    public function consumptions(): HasMany
    {
        return $this->hasMany(Consumption::class);
    }
}
