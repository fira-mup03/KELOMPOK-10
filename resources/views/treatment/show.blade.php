@extends('layouts.app')
@section('title', 'Detail Riwayat Perawatan')
@section('content')

<div class="pg-header">
    <div class="container pg-header-inner">
        <div class="breadcrumb"><a href="/">Beranda</a><span>›</span><a href="/riwayat-perawatan">Riwayat</a><span>›</span><span>Detail</span></div>
        <h1>🦷 Detail Perawatan</h1>
        <p>{{ $history->treatment_date->format('d F Y') }}</p>
    </div>
</div>

<div class="container" style="padding:2.5rem 1.5rem 4rem;max-width:860px;">
    <div class="card" style="padding:2rem;margin-bottom:2rem;">
        <div style="display:flex;justify-content:space-between;align-items:start;margin-bottom:2rem;flex-wrap:wrap;gap:1.5rem;border-bottom:1px solid var(--border);padding-bottom:1.5rem;">
            <div>
                <h2 style="font-size:1.625rem;font-weight:800;margin-bottom:.25rem;color:var(--text-1);">{{ $history->doctor->name }}</h2>
                <p style="color:var(--text-2);font-weight:500;">{{ $history->doctor->specialization }}</p>
            </div>
            <div style="text-align:right;">
                <div style="font-size:1.5rem;font-weight:800;color:var(--primary-dark);">{{ $history->treatment_date->format('d M Y') }}</div>
            </div>
        </div>

        <div style="display:grid;gap:1.5rem;">
            <div style="padding:1.5rem;background:var(--bg-muted);border-radius:var(--r);">
                <div style="font-size:.85rem;font-weight:700;text-transform:uppercase;color:var(--text-3);letter-spacing:.06em;margin-bottom:.75rem;display:flex;align-items:center;gap:.5rem;">🔍 Diagnosa</div>
                <p style="color:var(--text-1);line-height:1.75;font-size:1.05rem;">{{ $history->diagnosis }}</p>
            </div>
            <div style="padding:1.5rem;background:var(--bg-muted);border-radius:var(--r);">
                <div style="font-size:.85rem;font-weight:700;text-transform:uppercase;color:var(--text-3);letter-spacing:.06em;margin-bottom:.75rem;display:flex;align-items:center;gap:.5rem;">⚕️ Tindakan Perawatan</div>
                <p style="color:var(--text-1);line-height:1.75;font-size:1.05rem;">{{ $history->treatment }}</p>
            </div>

            @if($history->prescription)
                <div style="padding:1.5rem;background:#fffbeb;border:1px solid #fde68a;border-radius:var(--r);">
                    <div style="font-size:.85rem;font-weight:700;text-transform:uppercase;color:#92400e;letter-spacing:.06em;margin-bottom:.75rem;display:flex;align-items:center;gap:.5rem;">💊 Resep Obat</div>
                    <p style="color:#78350f;line-height:1.75;font-size:1.05rem;white-space:pre-line;">{{ $history->prescription }}</p>
                </div>
            @endif

            @if($history->next_visit)
                <div style="padding:1.5rem;background:#f0fdf4;border:1px solid #bbf7d0;border-radius:var(--r);">
                    <div style="font-size:.85rem;font-weight:700;text-transform:uppercase;color:#166534;letter-spacing:.06em;margin-bottom:.5rem;display:flex;align-items:center;gap:.5rem;">🗓️ Saran Kunjungan Berikutnya</div>
                    <p style="color:#14532d;font-weight:800;font-size:1.25rem;">{{ $history->next_visit->format('l, d F Y') }}</p>
                    <p style="font-size:.875rem;color:#22c55e;margin-top:.25rem;font-weight:600;">
                        ({{ $history->next_visit->diffInDays(today()) > 0 ? $history->next_visit->diffInDays(today()) . ' hari lagi' : 'Sudah lewat' }})
                    </p>
                </div>
            @endif
        </div>

        <div style="display:flex;gap:1rem;margin-top:2.5rem;flex-wrap:wrap;border-top:1px solid var(--border);padding-top:1.5rem;">
            <a href="/riwayat-perawatan" class="btn btn-secondary">← Kembali</a>
            <a href="/booking?doctor_id={{ $history->doctor_id }}" class="btn btn-primary">
                📅 Booking Lanjutan dengan Dr. {{ explode(' ', $history->doctor->name)[1] ?? $history->doctor->name }}
            </a>
        </div>
    </div>

    @if($related->isNotEmpty())
        <h3 style="font-size:1.2rem;font-weight:800;margin-bottom:1.25rem;color:var(--text-1);">Riwayat Lainnya dengan Dokter Ini</h3>
        <div style="display:grid;gap:1rem;">
        @foreach($related as $r)
            <a href="/riwayat-perawatan/{{ $r->id }}" class="card card-link" style="display:flex;justify-content:space-between;align-items:center;padding:1.25rem 1.5rem;text-decoration:none;">
                <div>
                    <div style="font-weight:700;color:var(--text-1);font-size:1.05rem;margin-bottom:.25rem;">{{ $r->treatment_date->format('d M Y') }}</div>
                    <div style="font-size:.875rem;color:var(--text-2);">{{ $r->diagnosis_short }}</div>
                </div>
                <span style="color:var(--primary);font-weight:600;font-size:.875rem;">Detail →</span>
            </a>
        @endforeach
        </div>
    @endif
</div>
@endsection
