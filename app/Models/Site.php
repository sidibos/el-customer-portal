<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Site extends Model
{
    use HasFactory;
    
    protected $fillable = ['customer_id', 'name', 'address'];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function meters(): HasMany
    {
        return $this->hasMany(Meter::class);
    }
}