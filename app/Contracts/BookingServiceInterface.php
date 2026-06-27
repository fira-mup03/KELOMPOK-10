<?php

namespace App\Contracts;

interface BookingServiceInterface
{
    // Membuat booking jadwal pemeriksaan baru
    public function createBooking(int $userId, array $data);

    // Menampilkan daftar jadwal booking milik pengguna
    public function getBookingSchedule(int $userId);
}
