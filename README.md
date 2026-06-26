# 🦷 DentalCare - Platform Manajemen Kesehatan Gigi Modern

DentalCare adalah sistem informasi dan platform web manajemen klinik kesehatan gigi yang dibangun menggunakan framework **Laravel 13**. Aplikasi ini memudahkan pasien untuk melakukan reservasi (booking) dokter gigi, memantau nomor antrian secara *real-time*, melihat rekam medis (riwayat perawatan), serta membaca artikel edukasi kesehatan gigi.

---

## 🌟 Fitur Utama

- **Autentikasi & Profil**: Registrasi pasien, login, manajemen profil, dan unggah avatar foto.
- **Booking Jadwal**: Sistem reservasi cerdas yang mendeteksi hari praktik dokter dan membatasi kuota harian.
- **Antrian Real-time**: Pasien dapat melihat nomor antrian yang sedang dipanggil dan estimasi waktu tunggu secara langsung di layar mereka.
- **Riwayat Perawatan (Rekam Medis)**: Rekaman diagnosis, tindakan, dan resep dokter untuk setiap kunjungan, dilengkapi fitur *filter* (tahun/dokter).
- **Pengingat (Reminders)**: Indikator visual pintar yang mengingatkan jadwal pemeriksaan yang akan datang.
- **Pusat Edukasi**: Kumpulan artikel kesehatan gigi dengan sistem pencarian dan kategori (Perawatan, Penyakit, Tips, Nutrisi).
- **Desain Modern**: Antarmuka *responsive* yang cantik menggunakan kustom CSS tanpa perlu *compile* pihak ketiga saat dijalankan.

---

## 🛠️ Teknologi yang Digunakan

- **Backend**: Laravel v13.x (PHP 8.2+)
- **Database**: MySQL / MariaDB
- **Frontend**: Blade Templating Engine, Vanilla CSS, Vanilla JavaScript
- **Arsitektur**: Model-View-Controller (MVC)

---

## ⚙️ Persyaratan Sistem (Prerequisites)

Sebelum menjalankan aplikasi, pastikan komputer Anda telah terinstal:
- **PHP** versi 8.2 atau lebih baru.
- **Composer** (untuk manajemen package PHP).
- **MySQL / MariaDB** (bisa menggunakan XAMPP, Laragon, dsb).
- **Git** (opsional, untuk *cloning* repositori).

---

## 🚀 Cara Menjalankan Aplikasi di Komputer Lokal

Ikuti langkah-langkah di bawah ini secara berurutan untuk menjalankan aplikasi dari nol:

### 1. Kloning Repositori & Masuk ke Folder Proyek
```bash
git clone https://github.com/username-anda/nama-repo-anda.git
cd KELOMPOK-10
```
*(Catatan: Sesuaikan link di atas dengan link GitHub repositori Anda yang sebenarnya).*

### 2. Install Dependensi PHP (Composer)
```bash
composer install
```

### 3. Konfigurasi Environment (Database)
Gandakan file pengaturan environment bawaan dan ganti namanya:
```bash
cp .env.example .env
```
*(Untuk Windows CMD gunakan `copy .env.example .env`)*

Buka file `.env` di *code editor* Anda, lalu cari bagian pengaturan *database* dan sesuaikan seperti ini:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=db_dentalcare
DB_USERNAME=root
DB_PASSWORD=
```
👉 **Penting**: Buat database kosong terlebih dahulu di phpMyAdmin (atau *database client* lainnya) dengan nama `db_dentalcare` sebelum lanjut ke langkah berikutnya.

### 4. Buat Application Key
```bash
php artisan key:generate
```

### 5. Buat Tautan Penyimpanan (Storage Link)
Penting agar foto profil (avatar) pasien dapat ditampilkan:
```bash
php artisan storage:link
```

### 6. Migrasi Database dan Seed Data Dummy
Langkah ini akan membuat semua struktur tabel otomatis dan mengisinya dengan data dokter, artikel, dan akun percobaan:
```bash
php artisan migrate --seed
```

### 7. Jalankan Server Lokal
```bash
php artisan serve
```

Aplikasi sekarang dapat diakses melalui browser Anda di URL:
**[http://127.0.0.1:8000](http://127.0.0.1:8000)**

---

## 🔑 Akun Demo (Untuk Testing)

Saat Anda menjalankan perintah `--seed` pada langkah 6, sistem otomatis membuat satu akun pasien yang bisa langsung Anda gunakan untuk pengujian:

- **Email**: `pasien@dentalcare.com`
- **Password**: `password`

---

## 📝 Catatan Khusus Lingkungan PHP

Aplikasi ini menggunakan **Laravel 13** yang *secara ideal* memerlukan PHP 8.3+. Namun proyek ini telah dikonfigurasi agar bisa berjalan di **PHP 8.2**. Jika Anda menemui *error constraint* Composer di kemudian hari, pastikan versi PHP komputer Anda sesuai.

---
*Dibuat untuk memenuhi tugas akhir/proyek pengembangan Web (Kelompok 10).*
