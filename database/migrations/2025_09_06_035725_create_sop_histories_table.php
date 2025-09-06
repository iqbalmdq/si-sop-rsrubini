<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sop_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sop_id')->constrained('sops');
            $table->enum('aksi', ['created', 'updated', 'deleted', 'status_changed']);
            $table->json('data_lama')->nullable();
            $table->json('data_baru')->nullable();
            $table->text('keterangan')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users');
            $table->timestamp('tanggal_aksi');
            $table->timestamps();
            
            $table->index(['sop_id', 'tanggal_aksi']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sop_histories');
    }
};