<?php

namespace Database\Seeders;

use App\Models\Survey;
use App\Models\SurveyQuestion;
use App\Models\User;
use Illuminate\Database\Seeder;

class SurveySeeder extends Seeder
{
    public function run(): void
    {
        $bidangUser = User::where('role', 'bidang')->first();

        // Survey 1: Kepuasan Layanan
        $survey1 = Survey::create([
            'judul' => 'Survei Kepuasan Layanan Rumah Sakit',
            'deskripsi' => 'Survei ini bertujuan untuk mengevaluasi tingkat kepuasan pasien dan keluarga terhadap kualitas layanan yang diberikan oleh Rumah Sakit Rubini. Feedback Anda sangat berharga untuk meningkatkan kualitas pelayanan kami.',
            'status' => 'aktif',
            'anonim' => true,
            'izin_respon_ganda' => false,
            'tanggal_mulai' => now(),
            'tanggal_berakhir' => now()->addDays(30),
            'target_bidang' => null,
            'dibuat_oleh' => $bidangUser->id,
        ]);

        SurveyQuestion::create([
            'survey_id' => $survey1->id,
            'teks_pertanyaan' => 'Bagaimana penilaian Anda terhadap kualitas pelayanan rumah sakit secara keseluruhan?',
            'tipe_pertanyaan' => 'rating',
            'wajib_diisi' => true,
            'urutan' => 1,
        ]);

        SurveyQuestion::create([
            'survey_id' => $survey1->id,
            'teks_pertanyaan' => 'Aspek layanan mana yang paling memuaskan menurut Anda?',
            'tipe_pertanyaan' => 'radio',
            'pilihan' => [
                ['value' => 'Pelayanan Dokter dan Tenaga Medis'],
                ['value' => 'Pelayanan Perawat'],
                ['value' => 'Pelayanan Administrasi dan Pendaftaran'],
                ['value' => 'Fasilitas dan Kebersihan Rumah Sakit'],
                ['value' => 'Kecepatan Pelayanan'],
            ],
            'wajib_diisi' => true,
            'urutan' => 2,
        ]);

        SurveyQuestion::create([
            'survey_id' => $survey1->id,
            'teks_pertanyaan' => 'Apakah ada aspek yang perlu diperbaiki? (Pilih semua yang sesuai)',
            'tipe_pertanyaan' => 'checkbox',
            'pilihan' => [
                ['value' => 'Waktu tunggu yang terlalu lama'],
                ['value' => 'Komunikasi dengan tenaga medis'],
                ['value' => 'Kebersihan fasilitas'],
                ['value' => 'Sistem antrian'],
                ['value' => 'Informasi biaya pengobatan'],
                ['value' => 'Tidak ada yang perlu diperbaiki'],
            ],
            'wajib_diisi' => false,
            'urutan' => 3,
        ]);

        SurveyQuestion::create([
            'survey_id' => $survey1->id,
            'teks_pertanyaan' => 'Berikan saran dan masukan Anda untuk meningkatkan kualitas layanan kami:',
            'tipe_pertanyaan' => 'textarea',
            'wajib_diisi' => false,
            'urutan' => 4,
        ]);

        // Survey 2: Evaluasi SOP
        $survey2 = Survey::create([
            'judul' => 'Evaluasi Efektivitas Standard Operating Procedure (SOP)',
            'deskripsi' => 'Survei ini ditujukan untuk mengevaluasi sejauh mana SOP yang berlaku dapat dipahami, diimplementasikan, dan memberikan manfaat dalam operasional sehari-hari di bidang pelayanan.',
            'status' => 'aktif',
            'anonim' => false,
            'izin_respon_ganda' => false,
            'tanggal_mulai' => now(),
            'tanggal_berakhir' => now()->addDays(14),
            'target_bidang' => 'Bidang Pelayanan',
            'dibuat_oleh' => $bidangUser->id,
        ]);

        SurveyQuestion::create([
            'survey_id' => $survey2->id,
            'teks_pertanyaan' => 'Seberapa mudah SOP yang ada dipahami dan diimplementasikan dalam pekerjaan sehari-hari?',
            'tipe_pertanyaan' => 'rating',
            'wajib_diisi' => true,
            'urutan' => 1,
        ]);

        SurveyQuestion::create([
            'survey_id' => $survey2->id,
            'teks_pertanyaan' => 'Menurut Anda, SOP mana yang paling memerlukan perbaikan atau pembaruan?',
            'tipe_pertanyaan' => 'checkbox',
            'pilihan' => [
                ['value' => 'SOP Penerimaan dan Registrasi Pasien'],
                ['value' => 'SOP Pemeriksaan Medis dan Diagnosis'],
                ['value' => 'SOP Perawatan dan Monitoring Pasien'],
                ['value' => 'SOP Discharge Planning dan Pemulangan'],
                ['value' => 'SOP Penanganan Keadaan Darurat'],
                ['value' => 'SOP Dokumentasi Medis'],
            ],
            'wajib_diisi' => false,
            'urutan' => 2,
        ]);

        SurveyQuestion::create([
            'survey_id' => $survey2->id,
            'teks_pertanyaan' => 'Apakah Anda merasa perlu pelatihan tambahan terkait implementasi SOP?',
            'tipe_pertanyaan' => 'radio',
            'pilihan' => [
                ['value' => 'Sangat perlu'],
                ['value' => 'Perlu'],
                ['value' => 'Cukup'],
                ['value' => 'Tidak perlu'],
            ],
            'wajib_diisi' => true,
            'urutan' => 3,
        ]);

        SurveyQuestion::create([
            'survey_id' => $survey2->id,
            'teks_pertanyaan' => 'Berikan masukan spesifik untuk perbaikan SOP atau proses kerja:',
            'tipe_pertanyaan' => 'textarea',
            'wajib_diisi' => false,
            'urutan' => 4,
        ]);

        // Survey 3: Evaluasi Fasilitas
        $survey3 = Survey::create([
            'judul' => 'Evaluasi Fasilitas dan Infrastruktur Rumah Sakit',
            'deskripsi' => 'Survei untuk mengevaluasi kondisi dan kecukupan fasilitas serta infrastruktur yang mendukung operasional rumah sakit.',
            'status' => 'konsep',
            'anonim' => false,
            'izin_respon_ganda' => true,
            'tanggal_mulai' => now()->addDays(7),
            'tanggal_berakhir' => now()->addDays(21),
            'target_bidang' => 'Bidang Penunjang',
            'dibuat_oleh' => $bidangUser->id,
        ]);

        SurveyQuestion::create([
            'survey_id' => $survey3->id,
            'teks_pertanyaan' => 'Bagaimana kondisi fasilitas di area kerja Anda saat ini?',
            'tipe_pertanyaan' => 'select',
            'pilihan' => [
                ['value' => 'Sangat Baik'],
                ['value' => 'Baik'],
                ['value' => 'Cukup'],
                ['value' => 'Kurang'],
                ['value' => 'Sangat Kurang'],
            ],
            'wajib_diisi' => true,
            'urutan' => 1,
        ]);

        SurveyQuestion::create([
            'survey_id' => $survey3->id,
            'teks_pertanyaan' => 'Fasilitas apa yang paling memerlukan perbaikan atau penambahan?',
            'tipe_pertanyaan' => 'textarea',
            'wajib_diisi' => true,
            'urutan' => 2,
        ]);
    }
}
