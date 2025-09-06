<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class Notifikasi extends Model
{
    use HasFactory;

    protected $fillable = [
        'judul',
        'pesan',
        'tipe',
        'sop_id',
        'target_bidang',
        'is_read',
        'created_by',
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];

    public function sop(): BelongsTo
    {
        return $this->belongsTo(Sop::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopeUnread(Builder $query): Builder
    {
        return $query->where('is_read', false);
    }

    public function scopeForBidang(Builder $query, string $bidang): Builder
    {
        return $query->where(function ($q) use ($bidang) {
            $q->whereNull('target_bidang')
              ->orWhere('target_bidang', $bidang);
        });
    }
}