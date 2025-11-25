# SI-SOP - Sistem Informasi Standard Operating Procedure
## Rumah Sakit Rubini Mempawah

### 📋 Deskripsi
Sistem Informasi untuk mempermudah Petugas Rumah Sakit dalam Pencarian Dokumen Standard Operating Procedure (SOP) di Rumah Sakit Rubini Mempawah. Sistem ini dilengkapi dengan fitur survei untuk evaluasi kepuasan layanan dan efektivitas SOP.

> 📘 Manual Pengguna: Lihat panduan lengkap penggunaan aplikasi di `MANUAL-BOOK.md`.

### 🚀 Fitur Utama

#### 1. **Sistem Login Multi-Role**
   - **Panel Bidang/Bagian**: Mengelola SOP (CRUD), membuat dan mengelola survei
   - **Panel Direktur**: Melihat analytics, history, dan analisis survei

#### 2. **Pencarian SOP (Tanpa Login)**
   - Pencarian berdasarkan kata kunci
   - Filter berdasarkan kategori dan bidang
   - Download file PDF SOP
   - Tampilan detail SOP dengan konten HTML
   - SOP terkait berdasarkan kategori

#### 3. **Sistem Notifikasi**
   - Notifikasi perubahan SOP
   - Notifikasi SOP baru
   - Notifikasi review SOP (otomatis untuk SOP > 1 tahun)
   - Sistem tandai baca/belum dibaca

#### 4. **Sistem Survei**
   - **Bidang**: Membuat dan mengelola survei
   - **Direktur**: Analisis hasil survei dan statistik
   - Berbagai tipe pertanyaan (rating, radio, checkbox, textarea, select, dll)
   - Survei anonim atau dengan identitas
   - Pengaturan periode survei
   - Target bidang spesifik

#### 5. **History & Audit Trail**
   - Tracking semua perubahan SOP
   - Log aktivitas user
   - Dashboard analytics untuk direktur
   - Statistik SOP per bidang dan status

### 🛠️ Teknologi
- **Backend**: Laravel 10.10
- **Admin Panel**: Filament 3.0
- **Database**: MySQL
- **Frontend**: Tailwind CSS, Alpine.js
- **Charts**: Chart.js 4.5.0
- **File Upload**: Support PDF untuk SOP

### 📦 Instalasi

1. **Clone Repository**
   ```bash
   git clone <repository-url>
   cd si-sop-rsrubini
   ```

2. **Install Dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Environment Setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Database Setup**
   ```bash
   # Buat database MySQL: si_sop_rs_rubini
   php artisan migrate
   php artisan db:seed
   ```

5. **Storage Link**
   ```bash
   php artisan storage:link
   ```

6. **Build Assets**
   ```bash
   npm run build
   ```

7. **Run Application**
   ```bash
   php artisan serve
   ```

### 👥 Default Users

| Role | Email | Password | Bidang/Bagian |
|------|-------|----------|---------------|
| Direktur | direktur@rsrubini.com | password123 | Direktur |
| Bidang Medis | medis@rsrubini.com | password123 | Bidang Medis |
| Bidang Keperawatan | keperawatan@rsrubini.com | password123 | Bidang Keperawatan |
| Bidang Penunjang | penunjang@rsrubini.com | password123 | Bidang Penunjang |

### 🌐 URL Akses

- **Pencarian Publik**: `http://localhost:8000/`
- **Survei Publik**: `http://localhost:8000/survey`
- **Panel Bidang**: `http://localhost:8000/bidang`
- **Panel Direktur**: `http://localhost:8000/direktur`

### 🧪 Testing & Commands

```bash
# Test aplikasi lengkap
php artisan test:si-sop

# Kirim notifikasi review SOP manual
php artisan sop:send-review-notifications

# Optimasi untuk production
composer run production-deploy
```

### ⚙️ Scheduled Tasks

Sistem menggunakan Laravel Scheduler untuk tugas otomatis:

```bash
# Tambahkan ke crontab untuk menjalankan scheduler
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

**Tasks yang dijadwalkan:**
- Notifikasi review SOP: Setiap bulan pada jam 09:00

### 📊 Fitur Dashboard

#### Panel Bidang
- Manajemen SOP (Create, Read, Update, Delete)
- Upload file PDF SOP
- Rich text editor untuk konten SOP
- Sistem notifikasi
- Manajemen survei lengkap
- Statistik SOP per bidang

#### Panel Direktur
- Analytics SOP keseluruhan
- Statistik survei dan respons
- Chart analisis per bidang
- History dan audit trail
- Analisis hasil survei

### 📁 Struktur Database

**Tabel Utama:**
- `users` - Data pengguna dengan role
- `kategori_sops` - Kategori SOP
- `sops` - Data SOP utama
- `sop_histories` - Riwayat perubahan SOP
- `notifikasis` - Sistem notifikasi
- `surveys` - Data survei
- `survey_questions` - Pertanyaan survei
- `survey_responses` - Respons survei
- `survey_answers` - Jawaban detail survei

### 🔧 Konfigurasi Production

1. **Environment Variables**
   ```bash
   APP_ENV=production
   APP_DEBUG=false
   APP_URL=https://your-domain.com
   ```

2. **Optimasi Cache**
   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   php artisan filament:optimize
   ```

3. **File Permissions**
   ```bash
   chmod -R 755 storage bootstrap/cache
   ```

### 📝 Catatan Pengembangan

- SOP mendukung konten HTML dengan rich text editor
- File SOP disimpan di `storage/app/public/sop-files/`
- Sistem notifikasi otomatis untuk SOP yang perlu review (> 1 tahun)
- Survei mendukung berbagai tipe pertanyaan dan analisis statistik
- Implementasi role-based access control untuk keamanan

### 🆘 Troubleshooting

**Masalah Umum:**
1. **File upload error**: Pastikan folder `storage/app/public/sop-files/` memiliki permission yang tepat
2. **Scheduler tidak jalan**: Pastikan crontab sudah dikonfigurasi dengan benar
3. **Filament error**: Jalankan `php artisan filament:upgrade` setelah update

### 📞 Support

Untuk bantuan teknis atau pertanyaan, silakan hubungi tim pengembang atau buat issue di repository ini.

### 📁 Struktur Project
```
si-sop-rs-rubini/
├── app/
├── database/
├── public/
├── resources/
├── routes/
├── storage/
├── tests/
├── .env
├── .env.testing
├── composer.json
├── package.json
├── phpunit.xml
├── README.md
```