<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'bidang_bagian',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
    ];

    public function sopsCreated(): HasMany
    {
        return $this->hasMany(Sop::class, 'created_by');
    }

    public function sopsUpdated(): HasMany
    {
        return $this->hasMany(Sop::class, 'updated_by');
    }

    public function sopHistories(): HasMany
    {
        return $this->hasMany(SopHistory::class);
    }

    public function notifikasis(): HasMany
    {
        return $this->hasMany(Notifikasi::class, 'created_by');
    }

    public function isBidang(): bool
    {
        return $this->role === 'bidang';
    }

    public function isDirektur(): bool
    {
        return $this->role === 'direktur';
    }
}