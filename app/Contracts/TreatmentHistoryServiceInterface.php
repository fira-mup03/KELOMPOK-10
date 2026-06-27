<?php

namespace App\Contracts;

interface TreatmentHistoryServiceInterface
{
    // Menampilkan seluruh riwayat pemeriksaan dan perawatan pengguna
    public function getTreatmentHistory(int $userId);
}
