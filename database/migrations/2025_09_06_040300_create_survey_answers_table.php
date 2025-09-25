<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('survey_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('response_id')->constrained('survey_responses')->onDelete('cascade');
            $table->foreignId('question_id')->constrained('survey_questions')->onDelete('cascade');
            $table->text('teks_jawaban')->nullable();
            $table->json('data_jawaban')->nullable(); // untuk pilihan ganda, rating, dll
            $table->timestamps();
            
            $table->index(['response_id', 'question_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('survey_answers');
    }
};