<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sops', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);
            $table->unsignedBigInteger('created_by')->nullable()->change();
            $table->unsignedBigInteger('updated_by')->nullable()->change();
            $table->foreign('created_by')->references('id')->on('users')->nullOnDelete();
            $table->foreign('updated_by')->references('id')->on('users')->nullOnDelete();
        });

        Schema::table('surveys', function (Blueprint $table) {
            $table->dropForeign(['dibuat_oleh']);
            $table->unsignedBigInteger('dibuat_oleh')->nullable()->change();
            $table->foreign('dibuat_oleh')->references('id')->on('users')->nullOnDelete();
        });

        Schema::table('notifikasis', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->unsignedBigInteger('created_by')->nullable()->change();
            $table->foreign('created_by')->references('id')->on('users')->nullOnDelete();
        });

        Schema::table('sop_histories', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->unsignedBigInteger('user_id')->nullable()->change();
            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('sops', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);
            $table->unsignedBigInteger('created_by')->nullable(false)->change();
            $table->unsignedBigInteger('updated_by')->nullable()->change();
            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('updated_by')->references('id')->on('users');
        });

        Schema::table('surveys', function (Blueprint $table) {
            $table->dropForeign(['dibuat_oleh']);
            $table->unsignedBigInteger('dibuat_oleh')->nullable(false)->change();
            $table->foreign('dibuat_oleh')->references('id')->on('users');
        });

        Schema::table('notifikasis', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->unsignedBigInteger('created_by')->nullable()->change();
            $table->foreign('created_by')->references('id')->on('users');
        });

        Schema::table('sop_histories', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->unsignedBigInteger('user_id')->nullable()->change();
            $table->foreign('user_id')->references('id')->on('users');
        });
    }
};
