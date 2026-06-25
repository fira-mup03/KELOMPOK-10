<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\TreatmentController;
use App\Http\Controllers\QueueController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\ReminderController;
use App\Http\Controllers\DoctorController;
use Illuminate\Support\Facades\Route;

// Halaman Utama
Route::get('/', function () {
    return view('welcome');
});

// Auth Routes (Guest only)
Route::middleware('guest')->group(function () {
    Route::get('/register',  [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/login',     [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login',    [AuthController::class, 'login']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Route yang membutuhkan login
Route::middleware('auth')->group(function () {

    // Profil
    Route::get('/profile',      [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile',      [ProfileController::class, 'update'])->name('profile.update');

    // Booking
    Route::get('/booking',             [BookingController::class, 'create'])->name('booking.create');
    Route::post('/booking',            [BookingController::class, 'store'])->name('booking.store');
    Route::get('/booking/riwayat',     [BookingController::class, 'index'])->name('booking.index');
    Route::get('/booking/{id}',        [BookingController::class, 'show'])->name('booking.show');
    Route::delete('/booking/{id}',     [BookingController::class, 'destroy'])->name('booking.destroy');

    // Riwayat Perawatan
    Route::get('/riwayat-perawatan',       [TreatmentController::class, 'index'])->name('treatment.index');
    Route::get('/riwayat-perawatan/{id}',  [TreatmentController::class, 'show'])->name('treatment.show');

    // Antrian
    Route::get('/antrian', [QueueController::class, 'index'])->name('queue.index');

    // Pengingat
    Route::get('/pengingat', [ReminderController::class, 'index'])->name('reminder.index');
});

// Route Publik
Route::get('/antrian/status',  [QueueController::class, 'status'])->name('queue.status');
Route::get('/edukasi',         [ArticleController::class, 'index'])->name('education.index');
Route::get('/edukasi/{slug}',  [ArticleController::class, 'show'])->name('education.show');

// API untuk form booking (AJAX)
Route::get('/api/doctors/{id}/schedules', [DoctorController::class, 'schedules'])->name('doctor.schedules');