<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Sop;
use App\Models\KategoriSop;
use App\Models\User;

class SampleSopSeeder extends Seeder
{
    public function run(): void
    {
        $kategoris = KategoriSop::all();
        $users = User::where('role', 'bidang')->get();
        
        $sampleSops = [
            [
                'nomor_sop' => 'SOP/PM/001/2024',
                'judul_sop' => 'Prosedur Penerimaan Pasien Rawat Inap',
                'deskripsi' => 'SOP untuk mengatur prosedur penerimaan pasien rawat inap di RS Rubini Mempawah',
                'isi_sop' => '<h3>Tujuan</h3><p>Memberikan panduan dalam melakukan penerimaan pasien rawat inap.</p><h3>Ruang Lingkup</h3><p>Berlaku untuk seluruh petugas di bagian admisi dan perawat ruangan.</p><h3>Prosedur</h3><ol><li>Petugas menerima surat rujukan atau permintaan rawat inap</li><li>Melakukan verifikasi identitas pasien</li><li>Mengecek ketersediaan kamar</li><li>Melakukan registrasi pasien</li><li>Mengantar pasien ke ruangan</li></ol>',
                'kategori_id' => $kategoris->where('kode_kategori', 'PM')->first()->id,
                'bidang_bagian' => 'Bidang Medis',
                'status' => 'aktif',
                'tanggal_berlaku' => now()->subMonths(6),
                'versi' => 1,
                'created_by' => $users->where('bidang_bagian', 'Bidang Medis')->first()->id,
            ],
            [
                'nomor_sop' => 'SOP/KP/001/2024',
                'judul_sop' => 'Prosedur Pemberian Obat Oral',
                'deskripsi' => 'SOP untuk mengatur prosedur pemberian obat oral kepada pasien',
                'isi_sop' => '<h3>Tujuan</h3><p>Memberikan panduan dalam pemberian obat oral yang aman dan tepat.</p><h3>Ruang Lingkup</h3><p>Berlaku untuk seluruh perawat di RS Rubini Mempawah.</p><h3>Prosedur</h3><ol><li>Cuci tangan sebelum menyiapkan obat</li><li>Periksa identitas pasien (5 benar)</li><li>Siapkan obat sesuai dosis</li><li>Berikan obat kepada pasien</li><li>Dokumentasikan pemberian obat</li></ol>',
                'kategori_id' => $kategoris->where('kode_kategori', 'KP')->first()->id,
                'bidang_bagian' => 'Bidang Keperawatan',
                'status' => 'aktif',
                'tanggal_berlaku' => now()->subMonths(3),
                'versi' => 2,
                'created_by' => $users->where('bidang_bagian', 'Bidang Keperawatan')->first()->id,
            ],
            [
                'nomor_sop' => 'SOP/PN/001/2024',
                'judul_sop' => 'Prosedur Pengambilan Sampel Darah',
                'deskripsi' => 'SOP untuk mengatur prosedur pengambilan sampel darah untuk pemeriksaan laboratorium',
                'isi_sop' => '<h3>Tujuan</h3><p>Memberikan panduan dalam pengambilan sampel darah yang aman dan steril.</p><h3>Ruang Lingkup</h3><p>Berlaku untuk petugas laboratorium dan perawat.</p><h3>Prosedur</h3><ol><li>Siapkan alat dan bahan</li><li>Identifikasi pasien</li><li>Pilih lokasi pengambilan</li><li>Lakukan desinfeksi</li><li>Ambil sampel darah</li><li>Labeling dan kirim ke lab</li></ol>',
                'kategori_id' => $kategoris->where('kode_kategori', 'PN')->first()->id,
                'bidang_bagian' => 'Bidang Penunjang',
                'status' => 'aktif',
                'tanggal_berlaku' => now()->subMonths(4),
                'versi' => 1,
                'created_by' => $users->where('bidang_bagian', 'Bidang Penunjang')->first()->id,
            ],
            [
                'nomor_sop' => 'SOP/KPS/001/2024',
                'judul_sop' => 'Prosedur Identifikasi Pasien',
                'deskripsi' => 'SOP untuk mengatur prosedur identifikasi pasien guna mencegah kesalahan identitas',
                'isi_sop' => '<h3>Tujuan</h3><p>Memastikan identifikasi pasien yang benar untuk mencegah kesalahan medis.</p><h3>Ruang Lingkup</h3><p>Berlaku untuk seluruh petugas RS Rubini Mempawah.</p><h3>Prosedur</h3><ol><li>Gunakan minimal 2 identitas pasien</li><li>Tanyakan nama lengkap dan tanggal lahir</li><li>Cocokkan dengan gelang identitas</li><li>Verifikasi dengan rekam medis</li><li>Jika ragu, konfirmasi ulang</li></ol>',
                'kategori_id' => $kategoris->where('kode_kategori', 'KPS')->first()->id,
                'bidang_bagian' => 'Bidang Medis',
                'status' => 'aktif',
                'tanggal_berlaku' => now()->subMonths(2),
                'versi' => 1,
                'created_by' => $users->where('bidang_bagian', 'Bidang Medis')->first()->id,
            ],
            [
                'nomor_sop' => 'SOP/ADM/001/2024',
                'judul_sop' => 'Prosedur Pengelolaan Berkas Rekam Medis',
                'deskripsi' => 'SOP untuk mengatur pengelolaan berkas rekam medis pasien',
                'isi_sop' => '<h3>Tujuan</h3><p>Memberikan panduan dalam pengelolaan berkas rekam medis yang tertib dan aman.</p><h3>Ruang Lingkup</h3><p>Berlaku untuk petugas rekam medis dan administrasi.</p><h3>Prosedur</h3><ol><li>Terima berkas dari ruangan</li><li>Periksa kelengkapan berkas</li><li>Input data ke sistem</li><li>Simpan berkas sesuai nomor</li><li>Jaga kerahasiaan data</li></ol>',
                'kategori_id' => $kategoris->where('kode_kategori', 'ADM')->first()->id,
                'bidang_bagian' => 'Bidang Medis',
                'status' => 'draft',
                'tanggal_berlaku' => now()->addDays(30),
                'versi' => 1,
                'created_by' => $users->where('bidang_bagian', 'Bidang Medis')->first()->id,
            ],
        ];
        
        foreach ($sampleSops as $sopData) {
            Sop::create($sopData);
        }
    }
}