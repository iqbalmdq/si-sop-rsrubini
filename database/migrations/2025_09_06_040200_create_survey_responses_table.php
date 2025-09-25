<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('survey_responses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('survey_id')->constrained('surveys')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('session_id')->nullable(); // untuk respon anonim
            $table->timestamp('waktu_submit');
            $table->timestamps();
            
            $table->index(['survey_id', 'user_id']);
            $table->index(['survey_id', 'session_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('survey_responses');
    }
};