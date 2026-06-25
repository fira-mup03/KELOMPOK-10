<?php

use App\Http\Controllers\DentalCareController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/profile', function () {
    return view('profile');
});

Route::get('/konsultasi', [DentalCareController::class, 'consultation']);

Route::get('/booking', [DentalCareController::class, 'bookingSchedule']);

Route::get('/riwayat-perawatan', [DentalCareController::class, 'treatmentHistory']);

Route::get('/antrian', [DentalCareController::class, 'queue']);

Route::get('/edukasi', [DentalCareController::class, 'education']);