<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('survey_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('survey_id')->constrained('surveys')->onDelete('cascade');
            $table->string('teks_pertanyaan');
            $table->enum('tipe_pertanyaan', [
                'teks', 'textarea', 'radio', 'checkbox', 
                'select', 'rating', 'tanggal', 'angka'
            ]);
            $table->json('pilihan')->nullable(); // untuk radio, checkbox, select
            $table->boolean('wajib_diisi')->default(false);
            $table->integer('urutan')->default(0);
            $table->timestamps();
            
            $table->index(['survey_id', 'urutan']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('survey_questions');
    }
};