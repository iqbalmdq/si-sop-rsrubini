<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Survey extends Model
{
    use HasFactory;

    protected $fillable = [
        'judul',
        'deskripsi',
        'status',
        'anonim',
        'izin_respon_ganda',
        'tanggal_mulai',
        'tanggal_berakhir',
        'target_bidang',
        'dibuat_oleh',
    ];

    protected $casts = [
        'tanggal_mulai' => 'datetime',
        'tanggal_berakhir' => 'datetime',
        'anonim' => 'boolean',
        'izin_respon_ganda' => 'boolean',
    ];

    public function questions(): HasMany
    {
        return $this->hasMany(SurveyQuestion::class)->orderBy('urutan');
    }

    public function responses(): HasMany
    {
        return $this->hasMany(SurveyResponse::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dibuat_oleh');
    }

    public function isActive(): bool
    {
        return $this->status === 'aktif' &&
               ($this->tanggal_mulai === null || $this->tanggal_mulai <= now()) &&
               ($this->tanggal_berakhir === null || $this->tanggal_berakhir >= now());
    }

    public function getResponsesCountAttribute(): int
    {
        return $this->responses()->count();
    }
}