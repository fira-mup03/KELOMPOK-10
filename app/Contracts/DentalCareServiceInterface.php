<?php

namespace App\Contracts;

interface DentalCareServiceInterface
{
    /*
    |--------------------------------------------------------------------------
    | Fitur 1: Booking Jadwal Pemeriksaan
    |--------------------------------------------------------------------------
    */

    // Membuat booking jadwal pemeriksaan baru
    public function createBooking(int $userId, array $data);

    // Menampilkan daftar jadwal booking milik pengguna
    public function getBookingSchedule(int $userId);


    /*
    |--------------------------------------------------------------------------
    | Fitur 2: Melihat Antrian Pemeriksaan
    |--------------------------------------------------------------------------
    */

    // Menampilkan nomor antrian dan status antrian pengguna
    public function getQueueStatus(int $userId);


    /*
    |--------------------------------------------------------------------------
    | Fitur 3: Informasi dan Edukasi Kesehatan Gigi
    |--------------------------------------------------------------------------
    */

    // Menampilkan seluruh artikel edukasi kesehatan gigi
    public function getEducationArticles();

    // Menampilkan detail artikel edukasi berdasarkan ID
    public function getEducationArticleById(int $id);


    /*
    |--------------------------------------------------------------------------
    | Fitur 4: Riwayat Pemeriksaan dan Perawatan Gigi
    |--------------------------------------------------------------------------
    */

    // Menampilkan seluruh riwayat pemeriksaan dan perawatan pengguna
    public function getTreatmentHistory(int $userId);


    /*
    |--------------------------------------------------------------------------
    | Fitur 5: Pengingat Jadwal Pemeriksaan
    |--------------------------------------------------------------------------
    */

    // Menampilkan pengingat dari jadwal pemeriksaan pengguna
    public function getScheduleReminders(int $userId);
}