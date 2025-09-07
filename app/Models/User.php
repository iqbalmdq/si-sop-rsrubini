<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;

class User extends Authenticatable implements FilamentUser
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

    /**
     * Determine if the user can access the given Filament panel.
     */
    public function canAccessPanel(Panel $panel): bool
    {
        // Pastikan user aktif
        if (!$this->is_active) {
            return false;
        }

        // Cek akses berdasarkan panel ID dan role user
        return match ($panel->getId()) {
            'bidang' => $this->role === 'bidang',
            'direktur' => $this->role === 'direktur',
            default => false,
        };
    }
}