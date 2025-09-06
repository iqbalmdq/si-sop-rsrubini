<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sops', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_sop')->unique();
            $table->string('judul_sop');
            $table->text('deskripsi');
            $table->longText('isi_sop');
            $table->foreignId('kategori_id')->constrained('kategori_sops');
            $table->string('bidang_bagian');
            $table->string('file_path')->nullable();
            $table->enum('status', ['draft', 'aktif', 'revisi', 'nonaktif'])->default('draft');
            $table->date('tanggal_berlaku');
            $table->date('tanggal_review')->nullable();
            $table->integer('versi')->default(1);
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
            $table->timestamps();
            
            $table->index(['nomor_sop', 'status']);
            $table->index(['kategori_id', 'bidang_bagian']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sops');
    }
};