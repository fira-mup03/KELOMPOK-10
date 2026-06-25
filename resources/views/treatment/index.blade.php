@extends('layouts.app')
@section('title', 'Riwayat Perawatan')
@section('content')

<div class="pg-header">
    <div class="container pg-header-inner">
        <div class="breadcrumb"><a href="/">Beranda</a><span>›</span><span>Riwayat Perawatan</span></div>
        <h1>🦷 Riwayat Perawatan</h1>
        <p>Rekam medis dan riwayat perawatan gigi Anda</p>
    </div>
</div>

<div class="container" style="padding:2.5rem 1.5rem 4rem;">
    {{-- Filter --}}
    <form method="GET" action="/riwayat-perawatan" class="card" style="margin-bottom:2rem;padding:1.25rem;">
        <div style="display:flex;gap:1rem;flex-wrap:wrap;align-items:flex-end;">
            <div style="flex:1;min-width:180px;">
                <label class="form-label">Filter Tahun</label>
                <select name="year" class="form-control">
                    <option value="">Semua Tahun</option>
                    @foreach($years as $year)
                        <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>{{ $year }}</option>
                    @endforeach
                </select>
            </div>
            <div style="flex:1;min-width:200px;">
                <label class="form-label">Filter Dokter</label>
                <select name="doctor_id" class="form-control">
                    <option value="">Semua Dokter</option>
                    @foreach($doctors as $doctor)
                        <option value="{{ $doctor->id }}" {{ request('doctor_id') == $doctor->id ? 'selected' : '' }}>{{ $doctor->name }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary">🔍 Filter</button>
            @if(request('year') || request('doctor_id'))
                <a href="/riwayat-perawatan" class="btn btn-secondary">✕ Reset</a>
            @endif
        </div>
    </form>

    @forelse($histories as $history)
        <div class="card card-link" style="margin-bottom:1rem;border-left:4px solid var(--primary);display:block;text-decoration:none;" onclick="window.location.href='/riwayat-perawatan/{{ $history->id }}'">
            <div style="display:flex;justify-content:space-between;align-items:start;flex-wrap:wrap;gap:1rem;">
                <div style="flex:1;">
                    <div style="display:flex;gap:.75rem;align-items:center;flex-wrap:wrap;margin-bottom:.625rem;">
                        <span style="font-size:.875rem;font-weight:700;color:var(--primary-dark);">{{ $history->treatment_date->format('d M Y') }}</span>
                        <span style="color:var(--text-3);">·</span>
                        <span style="font-size:.875rem;font-weight:500;color:var(--text-2);">{{ $history->doctor->name }}</span>
                    </div>
                    <h3 style="font-size:1.1rem;font-weight:700;margin-bottom:.5rem;color:var(--text-1);">{{ $history->diagnosis_short }}</h3>
                    <div style="display:flex;gap:.5rem;flex-wrap:wrap;">
                        <span class="badge badge-perawatan">{{ $history->treatment }}</span>
                        @if($history->next_visit)
                            <span class="badge" style="background:#f0fdf4;color:#166534;border:1px solid #bbf7d0;">
                                🗓 Kunjungan Berikutnya: {{ $history->next_visit->format('d M Y') }}
                            </span>
                        @endif
                    </div>
                </div>
                <div style="padding-top:.5rem;">
                    <span style="color:var(--primary);font-weight:600;font-size:.875rem;">Detail →</span>
                </div>
            </div>
        </div>
    @empty
        <div class="empty-state">
            <div class="empty-icon">🦷</div>
            <h3>Belum Ada Riwayat Perawatan</h3>
            <p>Riwayat medis akan muncul setelah Anda menyelesaikan pemeriksaan dengan dokter kami.</p>
            <a href="/booking" class="btn btn-primary">Buat Booking Pemeriksaan</a>
        </div>
    @endforelse

    <div style="margin-top:2rem;">
        {{ $histories->links() }}
    </div>
</div>
@endsection
