# Desain Fitur — DentalCare Application
**Laravel 13 · PHP 8.3 · MySQL**
> Dokumen ini mendefinisikan semua fitur yang akan diimplementasikan berdasarkan 6 layanan yang tampil di halaman utama website DentalCare.

---

## Daftar Isi
1. [Skema Database Global](#1-skema-database-global)
2. [Autentikasi & Profil Pengguna](#2-autentikasi--profil-pengguna)
3. [Booking Pemeriksaan](#3-booking-pemeriksaan)
4. [Riwayat Perawatan](#4-riwayat-perawatan)
5. [Antrian Pemeriksaan](#5-antrian-pemeriksaan)
6. [Edukasi Kesehatan Gigi](#6-edukasi-kesehatan-gigi)
7. [Pengingat Jadwal Pemeriksaan](#7-pengingat-jadwal-pemeriksaan)
8. [Ringkasan Route](#8-ringkasan-route)

---

## 1. Skema Database Global

### Tabel Baru yang Perlu Dibuat

```sql
-- Tabel dokter gigi
CREATE TABLE doctors (
    id              BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    name            VARCHAR(100) NOT NULL,
    specialization  VARCHAR(100) DEFAULT 'Dokter Gigi Umum',
    photo           VARCHAR(255) NULLABLE,
    is_active       BOOLEAN DEFAULT TRUE,
    created_at      TIMESTAMP,
    updated_at      TIMESTAMP
);

-- Tabel jadwal praktik dokter
CREATE TABLE doctor_schedules (
    id          BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    doctor_id   BIGINT UNSIGNED NOT NULL,
    day_of_week TINYINT NOT NULL COMMENT '0=Minggu, 1=Senin, ..., 6=Sabtu',
    start_time  TIME NOT NULL,
    end_time    TIME NOT NULL,
    max_quota   INT DEFAULT 20,
    FOREIGN KEY (doctor_id) REFERENCES doctors(id) ON DELETE CASCADE
);

-- Tabel booking pemeriksaan
CREATE TABLE bookings (
    id              BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    user_id         BIGINT UNSIGNED NOT NULL,
    doctor_id       BIGINT UNSIGNED NOT NULL,
    booking_date    DATE NOT NULL,
    booking_time    TIME NOT NULL,
    complaint       TEXT NULLABLE COMMENT 'Keluhan yang disampaikan pasien',
    status          ENUM('pending','confirmed','cancelled','done') DEFAULT 'pending',
    queue_number    INT NULLABLE,
    notes           TEXT NULLABLE COMMENT 'Catatan dari admin/dokter',
    created_at      TIMESTAMP,
    updated_at      TIMESTAMP,
    FOREIGN KEY (user_id)   REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (doctor_id) REFERENCES doctors(id) ON DELETE CASCADE
);

-- Tabel riwayat perawatan
CREATE TABLE treatment_histories (
    id              BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    user_id         BIGINT UNSIGNED NOT NULL,
    booking_id      BIGINT UNSIGNED NULLABLE,
    doctor_id       BIGINT UNSIGNED NOT NULL,
    treatment_date  DATE NOT NULL,
    diagnosis       TEXT NOT NULL COMMENT 'Diagnosa dokter',
    treatment       TEXT NOT NULL COMMENT 'Tindakan perawatan yang dilakukan',
    prescription    TEXT NULLABLE COMMENT 'Resep obat (opsional)',
    next_visit      DATE NULLABLE COMMENT 'Saran kunjungan berikutnya',
    created_at      TIMESTAMP,
    updated_at      TIMESTAMP,
    FOREIGN KEY (user_id)    REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (booking_id) REFERENCES bookings(id) ON DELETE SET NULL,
    FOREIGN KEY (doctor_id)  REFERENCES doctors(id) ON DELETE CASCADE
);

-- Tabel antrian (snapshot harian)
CREATE TABLE queues (
    id              BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    booking_id      BIGINT UNSIGNED NOT NULL,
    queue_number    INT NOT NULL,
    queue_date      DATE NOT NULL,
    status          ENUM('waiting','in_progress','done','skipped') DEFAULT 'waiting',
    called_at       TIMESTAMP NULLABLE,
    done_at         TIMESTAMP NULLABLE,
    created_at      TIMESTAMP,
    updated_at      TIMESTAMP,
    FOREIGN KEY (booking_id) REFERENCES bookings(id) ON DELETE CASCADE
);

-- Tabel artikel edukasi
CREATE TABLE articles (
    id           BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    title        VARCHAR(255) NOT NULL,
    slug         VARCHAR(255) NOT NULL UNIQUE,
    category     ENUM('perawatan','penyakit','tips','nutrisi') NOT NULL,
    thumbnail    VARCHAR(255) NULLABLE,
    content      LONGTEXT NOT NULL,
    read_time    INT DEFAULT 3 COMMENT 'Estimasi waktu baca dalam menit',
    is_published BOOLEAN DEFAULT TRUE,
    created_at   TIMESTAMP,
    updated_at   TIMESTAMP
);

-- Tabel pengingat
CREATE TABLE reminders (
    id          BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    user_id     BIGINT UNSIGNED NOT NULL,
    booking_id  BIGINT UNSIGNED NOT NULL,
    remind_at   DATETIME NOT NULL COMMENT 'Waktu pengingat dikirim',
    channel     ENUM('in_app','email') DEFAULT 'in_app',
    is_sent     BOOLEAN DEFAULT FALSE,
    created_at  TIMESTAMP,
    updated_at  TIMESTAMP,
    FOREIGN KEY (user_id)    REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (booking_id) REFERENCES bookings(id) ON DELETE CASCADE
);

-- Kolom tambahan di tabel users
ALTER TABLE users ADD COLUMN phone         VARCHAR(20)  NULLABLE AFTER email;
ALTER TABLE users ADD COLUMN date_of_birth DATE         NULLABLE AFTER phone;
ALTER TABLE users ADD COLUMN gender        ENUM('L','P') NULLABLE AFTER date_of_birth;
ALTER TABLE users ADD COLUMN address       TEXT         NULLABLE AFTER gender;
ALTER TABLE users ADD COLUMN avatar        VARCHAR(255) NULLABLE AFTER address;
```

> **Catatan:** Semua skema di atas akan dibuat sebagai file Migration Laravel (`php artisan make:migration`), bukan dieksekusi langsung ke SQL.

---

## 2. Autentikasi & Profil Pengguna

### 2.1 Halaman yang Dibutuhkan
| Halaman | Route | View |
|---|---|---|
| Register | `GET/POST /register` | `auth/register.blade.php` |
| Login | `GET/POST /login` | `auth/login.blade.php` |
| Profil (lihat) | `GET /profile` | `profile/index.blade.php` |
| Edit Profil | `GET /profile/edit` | `profile/edit.blade.php` |
| Simpan Edit | `PUT /profile` | — (redirect) |

### 2.2 Logika Bisnis — Profil
1. **Halaman Profil** menampilkan dashboard ringkasan:
   - **Kartu total booking** yang pernah dibuat pengguna (`bookings.count`)
   - **Kartu kunjungan terakhir** (tanggal dari `treatment_histories` terbaru)
   - **Kartu kunjungan berikutnya** (booking aktif paling dekat tanggalnya)
   - **Health Dashboard**: Tampilan visual 4 metrik dari riwayat perawatan:
     - Jumlah kunjungan tahun ini
     - Rata-rata interval kunjungan (hari)
     - Jumlah perawatan berbeda yang diterima
     - Status kesehatan gigi (dihitung dari diagnosa terbaru)
2. **Edit Profil** mengizinkan pengguna mengubah: nama, telepon, tanggal lahir, jenis kelamin, alamat, dan foto avatar.
3. Avatar disimpan di `storage/app/public/avatars/` dan diakses via `Storage::url()`.
4. Middleware `auth` diterapkan ke semua route profil.

---

## 3. Booking Pemeriksaan

### 3.1 Halaman yang Dibutuhkan
| Halaman | Route | View |
|---|---|---|
| Form Booking | `GET /booking` | `booking/create.blade.php` |
| Simpan Booking | `POST /booking` | — (redirect) |
| Daftar Booking Saya | `GET /booking/riwayat` | `booking/index.blade.php` |
| Detail Booking | `GET /booking/{id}` | `booking/show.blade.php` |
| Batalkan Booking | `DELETE /booking/{id}` | — (redirect) |

### 3.2 Logika Bisnis — Booking
1. **Form Booking** menampilkan:
   - Dropdown **pilih dokter** (diambil dari tabel `doctors`)
   - Setelah dokter dipilih, muncul kalender interaktif yang hanya mengaktifkan **hari-hari jadwal dokter** dari `doctor_schedules`
   - Setelah tanggal dipilih, muncul **pilihan jam** berdasarkan `start_time` dan `end_time` dokter (slot per 30 menit)
   - Input **keluhan** (teks bebas)
2. **Validasi saat menyimpan:**
   - Tanggal tidak boleh di masa lalu
   - Pasien tidak boleh memiliki 2 booking aktif (`pending`/`confirmed`) di tanggal yang sama
   - Kuota dokter di hari itu tidak boleh melebihi `doctor_schedules.max_quota` (hitung dari `bookings` yang `status != 'cancelled'` di tanggal itu)
3. **Setelah booking tersimpan:**
   - Status default: `pending`
   - Otomatis buat entri di tabel `queues` dengan `queue_number` = total booking di hari itu + 1
   - Otomatis buat entri `reminders` dengan `remind_at` = `booking_date - 1 hari jam 08:00`
   - Kirim notifikasi in-app bahwa booking berhasil dibuat
4. **Pembatalan:**
   - Hanya bisa dibatalkan jika `booking_date > CURDATE()` (belum melewati hari H)
   - Update `status` menjadi `cancelled`
   - Update `queues.status` menjadi `skipped`

### 3.3 Alur Booking (Flow Diagram)
```
Pengguna pilih dokter
        ↓
AJAX GET /api/doctors/{id}/schedules
        ↓
Tampilkan hari aktif di kalender
        ↓
Pengguna pilih tanggal → tampilkan slot jam tersedia
        ↓
Pengguna isi keluhan → klik Booking
        ↓
POST /booking → validasi server
        ↓
[Kuota penuh?] → Tampilkan error
        ↓
[Berhasil] → Simpan booking + buat antrian + buat reminder
        ↓
Redirect ke /booking/{id} dengan pesan sukses
```

---

## 4. Riwayat Perawatan

### 4.1 Halaman yang Dibutuhkan
| Halaman | Route | View |
|---|---|---|
| Daftar Riwayat | `GET /riwayat-perawatan` | `treatment/index.blade.php` |
| Detail Riwayat | `GET /riwayat-perawatan/{id}` | `treatment/show.blade.php` |

### 4.2 Logika Bisnis — Riwayat Perawatan
1. Halaman daftar menampilkan **semua riwayat perawatan** milik pengguna yang login, diurutkan dari terbaru.
2. Setiap kartu riwayat menampilkan:
   - Tanggal perawatan
   - Nama dokter
   - Diagnosa singkat (50 karakter pertama + `...`)
   - Badge tindakan (contoh: *Tambal Gigi*, *Cabut Gigi*, *Scaling*)
3. **Filter tersedia:**
   - Filter tahun (dropdown)
   - Filter nama dokter
4. Halaman detail menampilkan informasi lengkap:
   - Diagnosa lengkap
   - Tindakan perawatan
   - Resep/obat yang diberikan
   - Saran kunjungan berikutnya
   - Tombol **Booking Lanjutan** yang otomatis mengisi dokter sama di form booking
5. Data riwayat **hanya bisa dibuat oleh admin/dokter**, tidak bisa diisi sendiri oleh pasien (proteksi middleware role).

---

## 5. Antrian Pemeriksaan

### 5.1 Halaman yang Dibutuhkan
| Halaman | Route | View |
|---|---|---|
| Status Antrian Saya | `GET /antrian` | `queue/index.blade.php` |
| Status Antrian Realtime (publik) | `GET /antrian/status` | JSON response |

### 5.2 Logika Bisnis — Antrian
1. Halaman **Status Antrian Saya** menampilkan:
   - Nomor antrian pengguna untuk hari ini
   - Status antrian: **Menunggu / Dipanggil / Selesai / Dilewati**
   - Estimasi waktu tunggu:
     ```
     estimasi = (nomor_antrian_saya - nomor_sedang_dipanggil) × 20 menit
     ```
2. **Realtime update** menggunakan polling AJAX setiap 10 detik:
   ```js
   setInterval(() => {
       fetch('/antrian/status')
           .then(res => res.json())
           .then(data => updateUI(data));
   }, 10000);
   ```
3. **Logika nomor antrian:**
   - Digenerate saat booking dikonfirmasi (`status = confirmed`)
   - Format: `A-001`, `A-002`, ... (reset setiap hari berdasarkan `queue_date`)
4. **Update status antrian** (dilakukan oleh admin/dokter):
   - *Panggil Berikutnya* → `status = in_progress`, simpan `called_at = NOW()`
   - *Selesai* → `status = done`, simpan `done_at = NOW()`
   - *Lewati* → `status = skipped`

---

## 6. Edukasi Kesehatan Gigi

### 6.1 Halaman yang Dibutuhkan
| Halaman | Route | View |
|---|---|---|
| Daftar Artikel | `GET /edukasi` | `education/index.blade.php` |
| Detail Artikel | `GET /edukasi/{slug}` | `education/show.blade.php` |

### 6.2 Logika Bisnis — Edukasi
1. Halaman daftar menampilkan artikel `is_published = true` dalam format **grid kartu**.
2. Setiap kartu menampilkan: thumbnail, badge kategori, judul, estimasi waktu baca, dan cuplikan isi.
3. **Filter berdasarkan kategori** (query parameter `?category=...`):
   - `semua` | `perawatan` | `penyakit` | `tips` | `nutrisi`
4. **Pencarian judul** (query parameter `?q=...`) menggunakan `LIKE '%keyword%'`.
5. Halaman detail: artikel lengkap + **3 artikel terkait** (kategori sama, ID berbeda).
6. CRUD artikel dikelola oleh admin (di luar scope halaman pengguna).

---

## 7. Pengingat Jadwal Pemeriksaan

### 7.1 Halaman yang Dibutuhkan
| Halaman | Route | View |
|---|---|---|
| Daftar Pengingat | `GET /pengingat` | `reminder/index.blade.php` |

### 7.2 Logika Bisnis — Pengingat
1. Halaman mengelompokkan pengingat menjadi dua bagian:
   - **Akan Datang** — booking yang `booking_date >= TODAY`
   - **Selesai / Lewat** — booking `status = done` atau tanggal sudah lewat
2. Setiap kartu menampilkan:
   - Tanggal & jam pemeriksaan
   - Nama dokter
   - Nomor antrian (jika sudah ada)
   - Badge status booking (warna berbeda per status)
   - Tombol **Batalkan** (jika `booking_date > TODAY` dan status bukan `cancelled`)
   - Tombol **Lihat Detail** → `/booking/{id}`
3. **Pengiriman Pengingat Otomatis (Laravel Scheduler):**
   ```php
   // routes/console.php
   Schedule::command('reminder:send')->hourly();
   ```
   Artisan command `reminder:send`:
   ```
   1. Query: reminders WHERE is_sent = false AND remind_at <= NOW()
   2. Untuk setiap reminder:
      a. Buat notifikasi in-app (Laravel Notification)
      b. Jika channel = 'email': kirim AppointmentReminderMail
      c. Update is_sent = true
   ```

---

## 8. Ringkasan Route

```php
// Auth
Route::get('/register',  [AuthController::class, 'showRegister']);
Route::post('/register', [AuthController::class, 'register']);
Route::get('/login',     [AuthController::class, 'showLogin']);
Route::post('/login',    [AuthController::class, 'login']);
Route::post('/logout',   [AuthController::class, 'logout']);

// Route yang membutuhkan login
Route::middleware('auth')->group(function () {

    // Profil
    Route::get('/profile',      [ProfileController::class, 'show']);
    Route::get('/profile/edit', [ProfileController::class, 'edit']);
    Route::put('/profile',      [ProfileController::class, 'update']);

    // Booking
    Route::get('/booking',            [BookingController::class, 'create']);
    Route::post('/booking',           [BookingController::class, 'store']);
    Route::get('/booking/riwayat',    [BookingController::class, 'index']);
    Route::get('/booking/{id}',       [BookingController::class, 'show']);
    Route::delete('/booking/{id}',    [BookingController::class, 'destroy']);

    // Riwayat Perawatan
    Route::get('/riwayat-perawatan',      [TreatmentController::class, 'index']);
    Route::get('/riwayat-perawatan/{id}', [TreatmentController::class, 'show']);

    // Antrian
    Route::get('/antrian', [QueueController::class, 'index']);

    // Pengingat
    Route::get('/pengingat', [ReminderController::class, 'index']);
});

// Route Publik
Route::get('/antrian/status', [QueueController::class, 'status']);  // JSON polling
Route::get('/edukasi',        [ArticleController::class, 'index']);
Route::get('/edukasi/{slug}', [ArticleController::class, 'show']);

// API untuk form booking (AJAX)
Route::get('/api/doctors/{id}/schedules', [DoctorController::class, 'schedules']);
```

---

## Urutan Implementasi yang Disarankan

| Prioritas | Modul | Alasan |
|---|---|---|
| 1 | Migrations & Models | Fondasi semua fitur lain |
| 2 | Autentikasi (Login/Register) | Diperlukan sebelum fitur lain |
| 3 | Profil Pengguna | Digunakan di navbar & banyak relasi |
| 4 | Booking Pemeriksaan | Fitur inti utama |
| 5 | Antrian Pemeriksaan | Dependen pada data booking |
| 6 | Riwayat Perawatan | Dependen pada data booking |
| 7 | Pengingat Jadwal | Dependen pada data booking |
| 8 | Edukasi Kesehatan Gigi | Independen, bisa dikerjakan paralel |

> **Pola per modul:** Migration → Model → Controller → View → Route
