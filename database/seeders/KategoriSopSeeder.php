<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\KategoriSop;

class KategoriSopSeeder extends Seeder
{
    public function run(): void
    {
        $kategoris = [
            [
                'nama_kategori' => 'Pelayanan Medis',
                'kode_kategori' => 'PM',
                'deskripsi' => 'SOP terkait pelayanan medis dan tindakan medis',
            ],
            [
                'nama_kategori' => 'Keperawatan',
                'kode_kategori' => 'KP',
                'deskripsi' => 'SOP terkait pelayanan keperawatan',
            ],
            [
                'nama_kategori' => 'Penunjang Medis',
                'kode_kategori' => 'PN',
                'deskripsi' => 'SOP terkait pelayanan penunjang medis',
            ],
            [
                'nama_kategori' => 'Administrasi',
                'kode_kategori' => 'ADM',
                'deskripsi' => 'SOP terkait administrasi rumah sakit',
            ],
            [
                'nama_kategori' => 'Keselamatan Pasien',
                'kode_kategori' => 'KPS',
                'deskripsi' => 'SOP terkait keselamatan pasien',
            ],
            [
                'nama_kategori' => 'Manajemen Risiko',
                'kode_kategori' => 'MR',
                'deskripsi' => 'SOP terkait manajemen risiko',
            ],
        ];

        foreach ($kategoris as $kategori) {
            KategoriSop::create($kategori);
        }
    }
}