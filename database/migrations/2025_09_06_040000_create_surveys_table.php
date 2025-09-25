<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('surveys', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->text('deskripsi')->nullable();
            $table->enum('status', ['draft', 'aktif', 'tutup'])->default('draft');
            $table->boolean('anonim')->default(false);
            $table->boolean('izin_respon_ganda')->default(false);
            $table->datetime('tanggal_mulai')->nullable();
            $table->datetime('tanggal_berakhir')->nullable();
            $table->string('target_bidang')->nullable(); // null = semua bidang
            $table->foreignId('dibuat_oleh')->constrained('users');
            $table->timestamps();
            
            $table->index(['status', 'target_bidang']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('surveys');
    }
};