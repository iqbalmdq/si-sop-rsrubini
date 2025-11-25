# Manual Book Penggunaan Aplikasi SI‑SOP RS Rubini

Dokumen ini menjelaskan cara menggunakan aplikasi SI‑SOP (Sistem Informasi Standard Operating Procedure) untuk seluruh peran pengguna: Publik, Bidang/Bagian, dan Direktur. Fokus manual adalah operasional harian, mulai dari pencarian SOP, pengelolaan SOP, notifikasi, hingga pembuatan dan analisis survei.

## 1. Ringkasan Fitur
- Pencarian dan lihat SOP tanpa login (`/sop`, `/sop/{nomor_sop}`), preview PDF, dan unduh PDF.
- Portal Bidang untuk kelola kategori, SOP, notifikasi, dan survei (login Filament).
- Portal Direktur untuk lihat history SOP dan analisis hasil survei (Chart.js) (login Filament).
- Survei publik dengan berbagai tipe pertanyaan dan dukungan anonim.

## 2. Peran & Akses
- Publik:
  - Akses halaman pencarian SOP dan survei publik.
  - Tidak perlu login untuk melihat dan mengunduh SOP aktif.
- Bidang/Bagian:
  - Login via menu “Login Bidang” di header.
  - Kelola kategori SOP, data SOP, notifikasi, dan survei.
- Direktur:
  - Login via menu “Login Direktur” di header.
  - Melihat riwayat SOP dan melakukan analisis survei.

Catatan: Kredensial login disediakan oleh Admin. Lihat daftar akun default pada README bagian “Default Users”.

## 3. Halaman Publik (Tanpa Login)

### 3.1 Pencarian SOP
- Buka `http://localhost:8000/` atau `http://localhost:8000/sop`.
- Gunakan kotak “Cari SOP” untuk mengetik kata kunci (nomor, judul, deskripsi, atau isi).
- Gunakan filter “Kategori” dan “Bidang/Bagian” untuk mempersempit hasil.
- Hasil pencarian dapat dipaginasi; gunakan tombol navigasi halaman di bagian bawah.
- Setiap kartu hasil menampilkan:
  - Nomor SOP, kategori, judul, deskripsi singkat.
  - Bidang/Bagian, tanggal berlaku, dan versi.
- Aksi tersedia:
  - `Lihat` untuk membuka detail SOP: `/sop/{nomor_sop}`.
  - `Preview` (jika file tersedia): `/sop/{nomor_sop}/preview`.
  - `Download` (jika file tersedia): `/sop/{nomor_sop}/download`.


### 3.2 Detail SOP
- Halaman detail menampilkan informasi lengkap:
  - Nomor SOP dan Kategori.
  - Judul, deskripsi, konten, bidang/bagian, versi.
  - SOP terkait dalam kategori yang sama.
- Gunakan tombol `Preview` bila file PDF tersedia.
- Gunakan tombol `Download` bila file PDF tersedia.

### 3.3 Link Langsung SOP (Format Nomor)
- Format URL berdasarkan bagian nomor: `/sop/{type}/{category}/{number}/{year}`
- Download dari format bagian nomor: `/sop/{type}/{category}/{number}/{year}/download`
- Contoh: `/sop/SOP/ADM/001/2024` dan `/sop/SOP/ADM/001/2024/download`

### 3.4 Survei Publik
- Buka `http://localhost:8000/survey`.
- Pilih survei aktif, klik `Mulai Survei`.
- Ikuti langkah pertanyaan (progress bar terlihat di atas form).
- Tipe pertanyaan yang didukung:
  - Teks, Textarea, Radio, Checkbox, Select, Rating (1–5), Tanggal, Angka.
- Pertanyaan bertanda `*` wajib diisi.
- Klik `Kirim Jawaban` pada langkah terakhir.
- Setelah submit, Anda akan diarahkan ke halaman `Terima Kasih`.

## 4. Portal Bidang (Login)

### 4.1 Akses & Login
- Klik `Login Bidang` pada header publik atau akses panel sesuai konfigurasi Filament.
- Masukkan email dan password sesuai kredensial yang diberikan.

### 4.2 Navigasi Umum
- Sidebar menampilkan menu:
  - `Kategori SOP`, `SOP`, `Notifikasi`, `Survei`.
- Gunakan pencarian dan filter di setiap tabel untuk mempermudah pengelolaan.

### 4.3 Manajemen Kategori SOP
- Menu: `Kategori SOP`.
- Tambah kategori berisi `nama_kategori`, `kode_kategori`, `deskripsi`, dan `is_active`.
- Kolom `Jumlah SOP` menampilkan total SOP per kategori.
- Status aktif/nonaktif ditandai ikon hijau/merah.

### 4.4 Manajemen SOP
- Menu: `SOP`.
- Field penting:
  - `nomor_sop`, `judul_sop`, `deskripsi`, `isi_sop`, `kategori_id`, `bidang_bagian`, `file_path`, `status`, `tanggal_berlaku`, `tanggal_review`, `versi`.
- Status SOP:
  - `draft` (abu‑abu), `aktif` (hijau), `revisi` (kuning), `nonaktif` (merah).
- Aksi umum:
  - `Create`: tambahkan SOP baru, unggah PDF ke `file_path` (tersimpan di `storage/app/public/sop-files/`) bila ada.
  - `Edit`: memperbarui konten/metadata. Sistem mencatat perubahan ke `SopHistory`.
  - `Delete`: menghapus SOP bila diperlukan.
- Tabel mendukung pencarian judul/nomor, filter kategori dan bidang, serta sort data.

### 4.5 Notifikasi
- Menu: `Notifikasi`.
- Buat notifikasi untuk perubahan SOP, SOP baru, atau pengingat review.
- Field: `judul`, `pesan`, `tipe`, opsional `sop_id`, `target_bidang`, `is_read`.
- Target dapat `semua bidang` (kosong) atau spesifik bidang.

### 4.6 Manajemen Survei
- Menu: `Survei`.
- Buat survei dengan pengaturan:
  - Metadata: `judul`, `deskripsi`, `target_bidang` (opsional), pembuat.
  - Pengaturan: `anonim`, `izin_respon_ganda`, `tanggal_mulai`, `tanggal_berakhir`.
  - Status: `konsep` (draft), `aktif`, `ditutup`.
- Pertanyaan survei:
  - Tipe `teks`, `textarea`, `radio`, `checkbox`, `select`, `rating`, `tanggal`, `angka`.
  - Atur `wajib_diisi` dan `urutan` tampil.
- Publikasi:
  - Ubah status ke `aktif` agar muncul di halaman publik.
- Monitoring:
  - Lihat jumlah responden, waktu mulai/berakhir, serta statistik di panel.

## 5. Portal Direktur (Login)

### 5.1 Akses & Login
- Klik `Login Direktur` pada header publik atau akses panel sesuai konfigurasi Filament.

### 5.2 Analisis Survei
- Menu: `Analisis Survei`.
- Lihat daftar survei dengan kolom `Judul`, `Pembuat`, `Bidang`, `Status`, dan `Total Respons`.
- Buka detail analisis untuk melihat grafik (Chart.js) per pertanyaan:
  - Pie/Bar/Line (sesuai konfigurasi), persentase, dan jumlah.
  - Khusus pertanyaan teks/angka ditampilkan ringkasan dan statistik tanpa grafik.

### 5.3 Riwayat SOP
- Menu: `History SOP`.
- Melihat detail `SopHistory` (read‑only) untuk audit trail perubahan.

## 6. Praktik Terbaik
- Nomor SOP konsisten: hindari spasi/karakter khusus (gunakan slash `/` sesuai konvensi internal).
- Isi `tanggal_review` untuk mengelola pengingat review berkala.
- Gunakan `status` secara disiplin agar publik hanya melihat SOP `aktif`.
- Untuk survei non‑anonim, pertimbangkan `izin_respon_ganda` sesuai kebutuhan.

## 7. Troubleshooting & Error Umum
- “File SOP tidak ditemukan” saat unduh:
  - Pastikan `file_path` terisi dan file ada di `storage/app/public/{file_path}`.
  - Jalankan `php artisan storage:link` bila belum ada symbolic link.
- “Survei tidak tersedia/ditutup/belum dimulai”:
  - Periksa `status` survei (`aktif`) dan periode `tanggal_mulai`/`tanggal_berakhir`.
- “Pertanyaan wajib diisi”:
  - Isi semua pertanyaan bertanda `*`. Validasi dilakukan per pertanyaan.
 - “Preview tidak tampil”:
   - Pastikan file PDF tersimpan dan dapat diakses publik (`storage/app/public/sop-files/`).
   - Gunakan URL `Preview` di detail SOP: `/sop/{nomor_sop}/preview`.

## 8. FAQ
- Apakah publik bisa mengunduh semua SOP?
  - Hanya SOP dengan `status=aktif` dan memiliki file.
- Apakah survei selalu anonim?
  - Tergantung pengaturan `anonim`. Jika non‑anonim, respons dikaitkan ke user login.
- Bisakah seseorang mengisi survei lebih dari sekali?
  - Ya, jika `izin_respon_ganda=true`. Jika tidak, sistem mencegah duplikasi (untuk user login).

## 9. Referensi URL Penting
- Beranda/Publik SOP: `http://localhost:8000/` atau `http://localhost:8000/sop`
- Detail SOP: `/sop/{nomor_sop}`
- Download SOP: `/sop/{nomor_sop}/download`
- Preview SOP: `/sop/{nomor_sop}/preview`
- Daftar Survei: `http://localhost:8000/survey`
- Halaman Terima Kasih: `/survey/thankyou`
- Login Direktur (menu header): `route('filament.direktur.auth.login')`
- Login Bidang (menu header): `route('filament.bidang.auth.login')`
 - Pencarian SOP (JSON): `/sop/search`
 - Daftar Bidang (JSON): `/sop/bidang`

## 10. Kontak & Support
- Pertanyaan operasional: hubungi Admin RS Rubini.
- Masalah teknis: lihat README bagian “Support” atau hubungi tim pengembang.
