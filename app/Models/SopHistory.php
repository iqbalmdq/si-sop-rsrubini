<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SopHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'sop_id',
        'aksi',
        'data_lama',
        'data_baru',
        'keterangan',
        'user_id',
        'tanggal_aksi',
    ];

    protected $casts = [
        'data_lama' => 'array',
        'data_baru' => 'array',
        'tanggal_aksi' => 'datetime',
    ];

    public function sop(): BelongsTo
    {
        return $this->belongsTo(Sop::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}