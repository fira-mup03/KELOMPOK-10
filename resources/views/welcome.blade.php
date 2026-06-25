@extends('layouts.app')
@section('title','Beranda')
@section('description','DentalCare — Platform manajemen kesehatan gigi. Booking pemeriksaan, pantau antrian, dan akses riwayat perawatan dengan mudah.')

@section('content')

{{-- ===== HERO ===== --}}
<section style="min-height:calc(100vh - var(--nav-h));display:flex;align-items:center;background:linear-gradient(135deg,#f0f9ff 0%,#e0f2fe 45%,#ede9fe 100%);padding:3.5rem 0;position:relative;overflow:hidden;">
    <div style="position:absolute;top:-100px;right:-80px;width:520px;height:520px;border-radius:50%;background:rgba(14,165,233,.07);"></div>
    <div style="position:absolute;bottom:-80px;left:-60px;width:380px;height:380px;border-radius:50%;background:rgba(99,102,241,.06);"></div>

    <div class="container" style="position:relative;z-index:1;">
        <div class="d-grid hero-grid" style="grid-template-columns:1fr 1fr;gap:4rem;align-items:center;">
            {{-- Left --}}
            <div>
                <div style="display:inline-flex;align-items:center;gap:.5rem;background:rgba(14,165,233,.1);border:1px solid rgba(14,165,233,.2);border-radius:999px;padding:.35rem 1rem;font-size:.8rem;font-weight:600;color:var(--primary-dark);margin-bottom:1.5rem;">
                    ✨ Platform Kesehatan Gigi Terpercaya
                </div>
                <h1 style="font-size:3.25rem;font-weight:900;letter-spacing:-.04em;line-height:1.08;margin-bottom:1.25rem;color:var(--text-1);">
                    Rawat Senyum<br>
                    Bersama <span style="background:linear-gradient(135deg,var(--primary),var(--secondary));-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;">DentalCare</span>
                </h1>
                <p style="font-size:1.1rem;color:var(--text-2);line-height:1.8;margin-bottom:2rem;max-width:480px;">
                    Booking pemeriksaan, pantau antrian real-time, dan kelola riwayat perawatan gigi Anda — semua dalam satu platform yang modern.
                </p>
                <div style="display:flex;gap:1rem;flex-wrap:wrap;margin-bottom:3rem;">
                    <a href="{{ auth()->check() ? '/booking' : '/register' }}" class="btn btn-primary btn-lg">
                        {{ auth()->check() ? '📅 Buat Booking' : '🚀 Mulai Sekarang' }}
                    </a>
                    <a href="/edukasi" class="btn btn-outline btn-lg">📚 Edukasi</a>
                </div>
                <div style="display:flex;gap:2.5rem;flex-wrap:wrap;">
                    @foreach([['1K+','Pengguna Aktif'],['4+','Dokter Spesialis'],['24/7','Info Online']] as $s)
                    <div>
                        <div style="font-size:1.875rem;font-weight:900;color:var(--primary-dark);">{{ $s[0] }}</div>
                        <div style="font-size:.8rem;color:var(--text-3);">{{ $s[1] }}</div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Right card --}}
            <div style="display:flex;justify-content:center;">
                <div style="background:#fff;border-radius:var(--r-xl);padding:2.25rem;box-shadow:var(--sh-xl);max-width:350px;width:100%;">
                    <div style="text-align:center;font-size:3.5rem;margin-bottom:1rem;">🦷</div>
                    <h3 style="text-align:center;font-size:1.1rem;font-weight:700;margin-bottom:1.5rem;color:var(--text-1);">Kesehatan Gigi Anda</h3>
                    <div style="display:flex;flex-direction:column;gap:.75rem;">
                        @foreach([['📅','Booking Mudah','Pilih dokter & jadwal'],['🔢','Antrian Real-time','Pantau nomor antrian'],['🩺','Rekam Medis Digital','Riwayat perawatan lengkap']] as $f)
                        <div style="padding:.875rem;background:var(--bg-muted);border-radius:10px;display:flex;align-items:center;gap:.875rem;">
                            <span style="font-size:1.4rem;flex-shrink:0;">{{ $f[0] }}</span>
                            <div>
                                <div style="font-size:.875rem;font-weight:600;color:var(--text-1);">{{ $f[1] }}</div>
                                <div style="font-size:.78rem;color:var(--text-3);">{{ $f[2] }}</div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ===== SERVICES ===== --}}
<section class="section" id="layanan">
    <div class="container">
        <div style="text-align:center;margin-bottom:3rem;">
            <div class="section-label">LAYANAN KAMI</div>
            <h2 class="section-title">Semua Kebutuhan Gigi Anda</h2>
            <p class="section-sub">Pilih layanan untuk menjaga kesehatan gigi dan senyum Anda.</p>
        </div>
        <div class="grid-3">
            @php
            $services = [
                ['📅','Booking Pemeriksaan','Pilih dokter dan jadwal pemeriksaan sesuai ketersediaan.',auth()->check()?'/booking':'/login','Booking Sekarang'],
                ['🦷','Riwayat Perawatan','Lihat rekam medis dan hasil pemeriksaan lengkap.',auth()->check()?'/riwayat-perawatan':'/login','Lihat Riwayat'],
                ['🔢','Antrian Pemeriksaan','Pantau nomor antrian secara real-time tanpa perlu menunggu di tempat.',auth()->check()?'/antrian':'/login','Cek Antrian'],
                ['📚','Edukasi Kesehatan Gigi','Baca artikel dan tips dari dokter gigi terpercaya.','/edukasi','Baca Artikel'],
                ['🔔','Pengingat Jadwal','Dapatkan pengingat otomatis sehari sebelum jadwal.',auth()->check()?'/pengingat':'/login','Lihat Pengingat'],
                ['👤','Profil & Dashboard','Kelola profil dan pantau dashboard kesehatan gigi.',auth()->check()?'/profile':'/login','Buka Profil'],
            ];
            @endphp
            @foreach($services as $s)
            <a href="{{ $s[3] }}" class="card card-link" style="text-align:center;padding:2rem;">
                <div style="font-size:2.5rem;margin-bottom:1rem;">{{ $s[0] }}</div>
                <h3 style="font-size:1rem;font-weight:700;margin-bottom:.5rem;color:var(--text-1);">{{ $s[1] }}</h3>
                <p style="color:var(--text-2);font-size:.875rem;line-height:1.65;margin-bottom:1rem;">{{ $s[2] }}</p>
                <span style="color:var(--primary);font-weight:600;font-size:.875rem;">{{ $s[4] }} →</span>
            </a>
            @endforeach
        </div>
    </div>
</section>

{{-- ===== ABOUT ===== --}}
<section class="section" id="tentang" style="background:var(--bg-muted);">
    <div class="container">
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:4rem;align-items:center;" class="hero-grid">
            <div>
                <div class="section-label" style="margin-bottom:1rem;">TENTANG DENTAL CARE</div>
                <h2 class="section-title">Perawatan Gigi Lebih Mudah & Modern</h2>
                <p style="color:var(--text-2);line-height:1.85;margin-bottom:1.5rem;">
                    DentalCare membantu pasien mendapatkan layanan kesehatan gigi berkualitas dengan teknologi digital. Dari booking hingga riwayat perawatan, semua tersedia di genggaman Anda.
                </p>
                <div style="display:flex;flex-direction:column;gap:.875rem;">
                    @foreach(['Booking online kapan saja & di mana saja','Pemantauan antrian secara real-time','Rekam medis digital yang aman','Pengingat jadwal otomatis H-1'] as $item)
                    <div style="display:flex;gap:.75rem;align-items:center;">
                        <div style="width:22px;height:22px;border-radius:50%;background:linear-gradient(135deg,var(--primary),var(--secondary));color:#fff;display:flex;align-items:center;justify-content:center;font-size:.7rem;font-weight:700;flex-shrink:0;">✓</div>
                        <span style="font-weight:500;color:var(--text-1);">{{ $item }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
            <div style="display:flex;justify-content:center;">
                <div style="font-size:8rem;animation:float 3s ease-in-out infinite;filter:drop-shadow(0 16px 32px rgba(14,165,233,.25));">🦷</div>
            </div>
        </div>
    </div>
</section>

@push('styles')
<style>
@keyframes float{0%,100%{transform:translateY(0)}50%{transform:translateY(-14px)}}
.card-link{transition:transform .25s,box-shadow .25s,border-color .25s}
.card-link:hover{transform:translateY(-4px)}
@media(max-width:768px){.hero-grid{grid-template-columns:1fr!important}h1{font-size:2.25rem!important}}
</style>
@endpush
@endsection