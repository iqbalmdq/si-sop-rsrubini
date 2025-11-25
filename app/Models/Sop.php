<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sop extends Model
{
    use HasFactory;

    protected $fillable = [
        'nomor_sop',
        'judul_sop',
        'deskripsi',
        'isi_sop',
        'kategori_id',
        'bidang_bagian',
        'file_path',
        'status',
        'tanggal_berlaku',
        'tanggal_review',
        'versi',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'tanggal_berlaku' => 'date',
        'tanggal_review' => 'date',
    ];

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(KategoriSop::class, 'kategori_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function histories(): HasMany
    {
        return $this->hasMany(SopHistory::class);
    }

    public function notifikasis(): HasMany
    {
        return $this->hasMany(Notifikasi::class);
    }

    public function scopeAktif(Builder $query): Builder
    {
        return $query->where('status', 'aktif');
    }

    public function scopeByBidang(Builder $query, string $bidang): Builder
    {
        return $query->where('bidang_bagian', $bidang);
    }

    public function scopeSearch(Builder $query, string $search): Builder
    {
        return $query->where(function ($q) use ($search) {
            $q->where('nomor_sop', 'like', "%{$search}%")
                ->orWhere('judul_sop', 'like', "%{$search}%")
                ->orWhere('deskripsi', 'like', "%{$search}%")
                ->orWhere('isi_sop', 'like', "%{$search}%");
        });
    }

    public function getRouteKeyName(): string
    {
        return 'id';
    }
}
