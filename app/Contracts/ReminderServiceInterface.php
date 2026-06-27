<?php

namespace App\Contracts;

interface ReminderServiceInterface
{
    // Menampilkan pengingat dari jadwal pemeriksaan pengguna
    public function getScheduleReminders(int $userId);
}
