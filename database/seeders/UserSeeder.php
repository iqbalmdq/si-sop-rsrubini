<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
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

        // User Bagian Tata Usaha
        User::create([
            'name' => 'Bagian Tata Usaha',
            'email' => 'tatausaha@rsrubini.com',
            'password' => Hash::make('password123'),
            'role' => 'bidang',
            'bidang_bagian' => 'Bagian Tata Usaha',
            'is_active' => true,
        ]);

        // User Bidang Pelayanan
        User::create([
            'name' => 'Bidang Pelayanan',
            'email' => 'pelayanan@rsrubini.com',
            'password' => Hash::make('password123'),
            'role' => 'bidang',
            'bidang_bagian' => 'Bidang Pelayanan',
            'is_active' => true,
        ]);

        // User Bidang Pengendalian
        User::create([
            'name' => 'Bidang Pengendalian',
            'email' => 'pengendalian@rsrubini.com',
            'password' => Hash::make('password123'),
            'role' => 'bidang',
            'bidang_bagian' => 'Bidang Pengendalian',
            'is_active' => true,
        ]);

        // User Bidang Penunjang
        User::create([
            'name' => 'Bidang Penunjang',
            'email' => 'penunjang@rsrubini.com',
            'password' => Hash::make('password123'),
            'role' => 'bidang',
            'bidang_bagian' => 'Bidang Penunjang',
            'is_active' => true,
        ]);
    }
}
