<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            KategoriSopSeeder::class,
            SampleSopSeeder::class,
            SurveySeeder::class, // Tambahkan ini
        ]);
    }
}