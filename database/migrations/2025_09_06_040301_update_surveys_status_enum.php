<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Update existing 'draft' values to 'konsep'
        DB::table('surveys')->where('status', 'draft')->update(['status' => 'konsep']);
        
        // Modify the enum to use Indonesian values
        DB::statement("ALTER TABLE surveys MODIFY COLUMN status ENUM('konsep', 'aktif', 'tutup') DEFAULT 'konsep'");
    }

    public function down(): void
    {
        // Revert 'konsep' values back to 'draft'
        DB::table('surveys')->where('status', 'konsep')->update(['status' => 'draft']);
        
        // Revert the enum back to original values
        DB::statement("ALTER TABLE surveys MODIFY COLUMN status ENUM('draft', 'aktif', 'tutup') DEFAULT 'draft'");
    }
};