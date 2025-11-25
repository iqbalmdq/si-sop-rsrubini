<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Notifikasis: keep notification records, detach SOP reference on delete
        Schema::table('notifikasis', function (Blueprint $table) {
            $table->dropForeign(['sop_id']);
            $table->foreign('sop_id')
                ->references('id')
                ->on('sops')
                ->nullOnDelete();
        });

        // Sop histories: cascade delete histories when SOP is deleted
        Schema::table('sop_histories', function (Blueprint $table) {
            $table->dropForeign(['sop_id']);
            $table->foreign('sop_id')
                ->references('id')
                ->on('sops')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('notifikasis', function (Blueprint $table) {
            $table->dropForeign(['sop_id']);
            $table->foreign('sop_id')
                ->references('id')
                ->on('sops'); // default RESTRICT behavior
        });

        Schema::table('sop_histories', function (Blueprint $table) {
            $table->dropForeign(['sop_id']);
            $table->foreign('sop_id')
                ->references('id')
                ->on('sops'); // default RESTRICT behavior
        });
    }
};
