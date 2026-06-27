<?php

namespace App\Contracts;

interface QueueServiceInterface
{
    // Menampilkan nomor antrian dan status antrian pengguna
    public function getQueueStatus(int $userId);
}
