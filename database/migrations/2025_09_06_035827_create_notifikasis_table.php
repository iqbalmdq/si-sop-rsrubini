<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifikasis', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->text('pesan');
            $table->enum('tipe', ['sop_baru', 'sop_update', 'sop_delete', 'sop_review']);
            $table->foreignId('sop_id')->nullable()->constrained('sops');
            $table->string('target_bidang')->nullable(); // null = semua bidang
            $table->boolean('is_read')->default(false);
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->timestamps();
            
            $table->index(['target_bidang', 'is_read']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifikasis');
    }
};