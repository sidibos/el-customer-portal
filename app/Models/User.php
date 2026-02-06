<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name', 
        'email', 
        'password', 
        'customer_id', 
        'type', 
        'phone',
    ];

    protected $hidden = ['password', 'remember_token'];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
