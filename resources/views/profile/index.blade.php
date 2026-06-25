@extends('layouts.app')
@section('title','Profil Saya')
@section('content')

<div class="pg-header">
    <div class="container pg-header-inner">
        <div class="breadcrumb"><a href="/">Beranda</a><span>›</span><span>Profil</span></div>
        <h1>👤 Profil Saya</h1>
        <p>Dashboard kesehatan gigi dan informasi akun Anda</p>
    </div>
</div>

<div class="container" style="padding:2.5rem 1.5rem 4rem;">

    {{-- Profile card --}}
    <div class="card" style="margin-bottom:1.75rem;display:flex;align-items:center;gap:2rem;flex-wrap:wrap;">
        <div>
            @if($user->avatar)
                <img src="{{ Storage::url($user->avatar) }}" alt="Avatar"
                    style="width:96px;height:96px;border-radius:50%;object-fit:cover;border:3px solid var(--primary);">
            @else
                <div style="width:96px;height:96px;border-radius:50%;background:linear-gradient(135deg,var(--primary),var(--secondary));display:flex;align-items:center;justify-content:center;font-size:2.5rem;font-weight:800;color:#fff;">
                    {{ strtoupper(substr($user->name,0,1)) }}
                </div>
            @endif
        </div>
        <div style="flex:1;">
            <h2 style="font-size:1.5rem;font-weight:800;margin-bottom:.25rem;">{{ $user->name }}</h2>
            <p style="color:var(--text-2);margin-bottom:.625rem;">{{ $user->email }}</p>
            <div style="display:flex;gap:.5rem;flex-wrap:wrap;">
                @if($user->phone)<span class="badge" style="background:var(--primary-light);color:var(--primary-dark);">📞 {{ $user->phone }}</span>@endif
                @if($user->gender)<span class="badge" style="background:#ede9fe;color:#4c1d95;">{{ $user->gender_label }}</span>@endif
                @if($user->date_of_birth)<span class="badge" style="background:#f0fdf4;color:#166534;">🎂 {{ $user->date_of_birth->format('d M Y') }}</span>@endif
            </div>
        </div>
        <a href="/profile/edit" class="btn btn-primary">✏️ Edit Profil</a>
    </div>

    {{-- Stats --}}
    <div class="grid-3" style="margin-bottom:1.75rem;">
        <div class="stat-card">
            <div class="stat-icon">📅</div>
            <div class="stat-value">{{ $totalBookings }}</div>
            <div class="stat-label">Total Booking</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">🦷</div>
            <div class="stat-value" style="font-size:1.25rem;">{{ $lastVisit ? $lastVisit->treatment_date->format('d M Y') : '—' }}</div>
            <div class="stat-label">Kunjungan Terakhir</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">🗓️</div>
            <div class="stat-value" style="font-size:1.25rem;">{{ $nextBooking ? $nextBooking->booking_date->format('d M') : '—' }}</div>
            <div class="stat-label">Booking Berikutnya</div>
        </div>
    </div>

    {{-- Health Dashboard --}}
    <div class="card" style="margin-bottom:1.75rem;">
        <h3 style="font-size:1rem;font-weight:700;margin-bottom:1.25rem;display:flex;align-items:center;gap:.5rem;">🩺 Dashboard Kesehatan Gigi</h3>
        <div class="grid-4">
            @foreach([
                ['Kunjungan Tahun Ini', $thisYearVisits, var(--primary)],
                ['Rata-rata Interval (Hari)', $avgInterval ?? '—', var(--secondary)],
                ['Jenis Perawatan', $distinctTreatments, var(--accent)],
                ['Status Kesehatan', $healthStatus, var(--success)],
            ] as [$label, $val, $col])
            <div style="text-align:center;padding:1.125rem;background:var(--bg-muted);border-radius:var(--r);">
                <div style="font-size:1.625rem;font-weight:800;color:var(--primary-dark);margin-bottom:.25rem;">{{ $val }}</div>
                <div style="font-size:.78rem;color:var(--text-3);">{{ $label }}</div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="card">
        <h3 style="font-size:1rem;font-weight:700;margin-bottom:1rem;">⚡ Aksi Cepat</h3>
        <div style="display:flex;gap:.75rem;flex-wrap:wrap;">
            <a href="/booking" class="btn btn-primary">📅 Buat Booking</a>
            <a href="/booking/riwayat" class="btn btn-outline">📋 Riwayat Booking</a>
            <a href="/riwayat-perawatan" class="btn btn-outline">🦷 Riwayat Perawatan</a>
            <a href="/antrian" class="btn btn-outline">🔢 Status Antrian</a>
            <a href="/pengingat" class="btn btn-outline">🔔 Pengingat</a>
        </div>
    </div>

</div>
@endsection
