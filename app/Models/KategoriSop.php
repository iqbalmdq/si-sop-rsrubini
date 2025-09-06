<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KategoriSop extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_kategori',
        'kode_kategori',
        'deskripsi',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function sops(): HasMany
    {
        return $this->hasMany(Sop::class, 'kategori_id');
    }
}