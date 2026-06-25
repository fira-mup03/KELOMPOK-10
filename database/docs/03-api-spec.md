# API Specification

> Dokumentasikan endpoint API yang digunakan pada aplikasi Dental Care.

---

## Booking Jadwal Pemeriksaan

**Method:** `POST`

**URL:** `/api/v1/appointments`

**Deskripsi:**
`Membuat jadwal pemeriksaan gigi baru sesuai tanggal dan waktu yang dipilih pengguna.`

**Autentikasi Diperlukan:** `Ya`

**Sumber:** `Internal System`

**Request Headers:**

```text
Authorization: Bearer <token>
Content-Type: application/json
```

**Request Body:**

```json
{
    "date": "2026-06-30",
    "time": "10:00",
    "complaint": "Gigi terasa nyeri saat mengunyah"
}
```

**Response Sukses (`201 Created`):**

```json
{
    "status": "success",
    "message": "Jadwal pemeriksaan berhasil dibuat",
    "data": {
        "appointment_id": 1,
        "date": "2026-06-30",
        "time": "10:00",
        "status": "Menunggu Konfirmasi"
    }
}
```

**Response Gagal:**

```json
{
    "status": "error",
    "message": "Jadwal yang dipilih tidak tersedia"
}
```

---

## Melihat Antrian Pemeriksaan

**Method:** `GET`

**URL:** `/api/v1/queue`

**Deskripsi:**
`Menampilkan nomor antrian pemeriksaan dan status antrian pengguna.`

**Autentikasi Diperlukan:** `Ya`

**Sumber:** `Internal System`

**Request Headers:**

```text
Authorization: Bearer <token>
Content-Type: application/json
```

**Response Sukses (`200 OK`):**

```json
{
    "status": "success",
    "data": {
        "queue_number": 12,
        "queue_status": "Menunggu",
        "estimated_time": "11:30",
        "appointment_date": "2026-06-30"
    }
}
```

**Response Gagal:**

```json
{
    "status": "error",
    "message": "Data antrian belum tersedia"
}
```

---

## Informasi dan Edukasi Kesehatan Gigi

**Method:** `GET`

**URL:** `/api/v1/education`

**Deskripsi:**
`Menampilkan daftar artikel, tips, dan informasi edukasi mengenai kesehatan gigi dan mulut.`

**Autentikasi Diperlukan:** `Tidak`

**Sumber:** `Internal System`

**Request Headers:**

```text
Content-Type: application/json
```

**Response Sukses (`200 OK`):**

```json
{
    "status": "success",
    "data": [
        {
            "id": 1,
            "title": "Cara Menyikat Gigi yang Benar",
            "category": "Tips Kesehatan Gigi",
            "description": "Panduan menjaga kebersihan gigi dan mulut setiap hari."
        },
        {
            "id": 2,
            "title": "Penyebab Gigi Berlubang",
            "category": "Penyakit Gigi",
            "description": "Informasi mengenai penyebab dan cara mencegah gigi berlubang."
        }
    ]
}
```

**Response Gagal:**

```json
{
    "status": "error",
    "message": "Data edukasi tidak tersedia"
}
```

---

## Riwayat Pemeriksaan dan Perawatan Gigi

**Method:** `GET`

**URL:** `/api/v1/treatment-history`

**Deskripsi:**
`Menampilkan riwayat pemeriksaan dan perawatan gigi yang pernah dilakukan oleh pengguna.`

**Autentikasi Diperlukan:** `Ya`

**Sumber:** `Internal System`

**Request Headers:**

```text
Authorization: Bearer <token>
Content-Type: application/json
```

**Response Sukses (`200 OK`):**

```json
{
    "status": "success",
    "data": [
        {
            "id": 1,
            "date": "2026-05-10",
            "treatment": "Scaling Gigi",
            "doctor": "drg. Andi",
            "notes": "Disarankan kontrol kembali dalam 6 bulan."
        },
        {
            "id": 2,
            "date": "2026-04-15",
            "treatment": "Tambal Gigi",
            "doctor": "drg. Siti",
            "notes": "Tambalan dalam kondisi baik."
        }
    ]
}
```

**Response Gagal:**

```json
{
    "status": "error",
    "message": "Riwayat perawatan belum tersedia"
}
```

---

## Konsultasi Kesehatan Gigi

**Method:** `POST`

**URL:** `/api/v1/consultations`

**Deskripsi:**
`Mengirim pertanyaan atau keluhan pengguna terkait kesehatan gigi dan mulut.`

**Autentikasi Diperlukan:** `Ya`

**Sumber:** `Internal System`

**Request Headers:**

```text
Authorization: Bearer <token>
Content-Type: application/json
```

**Request Body:**

```json
{
    "subject": "Nyeri Gigi",
    "message": "Gigi bagian belakang terasa sakit saat mengunyah makanan."
}
```

**Response Sukses (`201 Created`):**

```json
{
    "status": "success",
    "message": "Konsultasi berhasil dikirim",
    "data": {
        "consultation_id": 1,
        "status": "Menunggu Respons"
    }
}
```

**Response Gagal:**

```json
{
    "status": "error",
    "message": "Konsultasi gagal dikirim"
}
```

---

## Melihat Riwayat Konsultasi

**Method:** `GET`

**URL:** `/api/v1/consultations`

**Deskripsi:**
`Menampilkan daftar konsultasi kesehatan gigi yang pernah dikirim oleh pengguna.`

**Autentikasi Diperlukan:** `Ya`

**Sumber:** `Internal System`

**Request Headers:**

```text
Authorization: Bearer <token>
Content-Type: application/json
```

**Response Sukses (`200 OK`):**

```json
{
    "status": "success",
    "data": [
        {
            "id": 1,
            "subject": "Nyeri Gigi",
            "message": "Gigi terasa sakit saat mengunyah.",
            "status": "Sudah Dijawab",
            "response": "Disarankan melakukan pemeriksaan langsung ke dokter gigi."
        }
    ]
}
```

**Response Gagal:**

```json
{
    "status": "error",
    "message": "Riwayat konsultasi tidak tersedia"
}
```
