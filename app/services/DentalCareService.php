<?php

namespace App\Services;

use App\Contracts\DentalCareServiceInterface;

class DentalCareService implements DentalCareServiceInterface
{
    public function getBookingSchedule()
    {
        return [
            [
                'tanggal' => '30 Juni 2026',
                'jam' => '09.00 WIB',
                'dokter' => 'drg. Siti Rahma',
            ],
        ];
    }

    public function getQueue()
    {
        return [
            'nomor_antrian' => 'A-12',
            'status' => 'Menunggu',
            'perkiraan_waktu' => '10.30 WIB',
        ];
    }

    public function getEducation()
    {
        return [
            'Cara menyikat gigi yang benar',
            'Pentingnya kontrol gigi setiap 6 bulan',
            'Tips menjaga kesehatan gusi',
        ];
    }

    public function getTreatmentHistory()
    {
        return [
            [
                'tanggal' => '12 Juni 2026',
                'jenis_perawatan' => 'Pemeriksaan Gigi',
                'keterangan' => 'Kondisi gigi baik',
            ],
        ];
    }

    public function getConsultation()
    {
        return [
            'judul' => 'Konsultasi Gigi',
            'pesan' => 'Silakan tuliskan keluhan atau pertanyaan Anda.',
        ];
    }
}