<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // User Direktur
        User::create([
            'name' => 'Direktur RS Rubini',
            'email' => 'direktur@rsrubini.com',
            'password' => Hash::make('password123'),
            'role' => 'direktur',
            'bidang_bagian' => 'Direktur',
            'is_active' => true,
        ]);

        // User Bidang Medis
        User::create([
            'name' => 'Admin Bidang Medis',
            'email' => 'medis@rsrubini.com',
            'password' => Hash::make('password123'),
            'role' => 'bidang',
            'bidang_bagian' => 'Bidang Medis',
            'is_active' => true,
        ]);

        // User Bidang Keperawatan
        User::create([
            'name' => 'Admin Bidang Keperawatan',
            'email' => 'keperawatan@rsrubini.com',
            'password' => Hash::make('password123'),
            'role' => 'bidang',
            'bidang_bagian' => 'Bidang Keperawatan',
            'is_active' => true,
        ]);

        // User Bidang Penunjang
        User::create([
            'name' => 'Admin Bidang Penunjang',
            'email' => 'penunjang@rsrubini.com',
            'password' => Hash::make('password123'),
            'role' => 'bidang',
            'bidang_bagian' => 'Bidang Penunjang',
            'is_active' => true,
        ]);
    }
}