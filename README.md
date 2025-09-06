# SI-SOP - Sistem Informasi Standard Operating Procedure
## Rumah Sakit Rubini Mempawah

### 📋 Deskripsi
Sistem Informasi untuk mempermudah Petugas Rumah Sakit dalam Pencarian Dokumen Standard Operating Procedure (SOP) di Rumah Sakit Rubini Mempawah.

### 🚀 Fitur Utama
1. **Sistem Login Multi-Role**
   - Panel Bidang/Bagian: Mengelola SOP (CRUD)
   - Panel Direktur: Melihat analytics dan history

2. **Pencarian SOP (Tanpa Login)**
   - Pencarian berdasarkan kata kunci
   - Filter berdasarkan kategori dan bidang
   - Download file PDF SOP

3. **Sistem Notifikasi**
   - Notifikasi perubahan SOP
   - Notifikasi SOP baru
   - Notifikasi review SOP

4. **History & Audit Trail**
   - Tracking semua perubahan SOP
   - Log aktivitas user
   - Dashboard analytics untuk direktur

### 🛠️ Teknologi
- **Backend**: Laravel 10.0
- **Admin Panel**: Filament 3.0
- **Database**: MySQL
- **Frontend**: Tailwind CSS, Alpine.js

### 📦 Instalasi

1. **Clone Repository**
   ```bash
   git clone <repository-url>
   cd si-sop-rs-rubini
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
- **Panel Bidang**: `http://localhost:8000/bidang`
- **Panel Direktur**: `http://localhost:8000/direktur`

### 🧪 Testing

```bash
# Test aplikasi
php artisan test:si-sop

# Test notifikasi review
php artisan sop:send-review-notifications
```

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