@extends('layouts.app')
@section('title', 'Detail Booking')
@section('content')

<div class="pg-header">
    <div class="container pg-header-inner">
        <div class="breadcrumb"><a href="/">Beranda</a><span>›</span><a href="/booking/riwayat">Booking</a><span>›</span><span>Detail</span></div>
        <h1>🗓️ Detail Booking</h1>
        <p>Informasi lengkap jadwal pemeriksaan Anda</p>
    </div>
</div>

<div style="max-width:760px;margin:2.5rem auto 4rem;padding:0 1.5rem;">
    <div class="card" style="padding:2rem;margin-bottom:1.5rem;">
        <div style="display:flex;justify-content:space-between;align-items:start;margin-bottom:1.5rem;flex-wrap:wrap;gap:1rem;border-bottom:1px solid var(--border);padding-bottom:1.5rem;">
            <div>
                <h2 style="font-size:1.625rem;font-weight:800;margin-bottom:.25rem;color:var(--text-1);">{{ $booking->doctor->name }}</h2>
                <p style="color:var(--text-2);font-weight:500;">{{ $booking->doctor->specialization }}</p>
            </div>
            <span class="badge badge-{{ $booking->status }}" style="font-size:.9rem;padding:.5rem 1.25rem;">
                {{ match($booking->status) {
                    'pending'   => '⏳ Menunggu Konfirmasi',
                    'confirmed' => '✓ Dikonfirmasi',
                    'cancelled' => '✕ Dibatalkan',
                    'done'      => '✓ Selesai',
                    default     => ucfirst($booking->status)
                } }}
            </span>
        </div>

        <div class="grid-2" style="margin-bottom:1.5rem;">
            <div style="padding:1.25rem;background:var(--bg-muted);border-radius:var(--r);">
                <div style="font-size:.8rem;font-weight:700;color:var(--text-3);text-transform:uppercase;letter-spacing:.05em;margin-bottom:.5rem;">📅 Tanggal</div>
                <div style="font-weight:700;color:var(--text-1);font-size:1.1rem;">{{ $booking->booking_date->format('l, d F Y') }}</div>
            </div>
            <div style="padding:1.25rem;background:var(--bg-muted);border-radius:var(--r);">
                <div style="font-size:.8rem;font-weight:700;color:var(--text-3);text-transform:uppercase;letter-spacing:.05em;margin-bottom:.5rem;">⏰ Jam</div>
                <div style="font-weight:700;color:var(--text-1);font-size:1.1rem;">{{ substr($booking->booking_time, 0, 5) }} WIB</div>
            </div>

            @if($booking->queue_number)
            <div style="padding:1.25rem;background:var(--primary-light);border-radius:var(--r);border:1px solid rgba(14,165,233,.2);">
                <div style="font-size:.8rem;font-weight:700;color:var(--primary-dark);text-transform:uppercase;letter-spacing:.05em;margin-bottom:.5rem;">🔢 Nomor Antrian</div>
                <div style="font-weight:900;font-size:1.5rem;color:var(--primary-dark);line-height:1;">A-{{ str_pad($booking->queue_number, 3, '0', STR_PAD_LEFT) }}</div>
            </div>
            @endif
            @if($booking->queue)
            <div style="padding:1.25rem;background:var(--bg-muted);border-radius:var(--r);">
                <div style="font-size:.8rem;font-weight:700;color:var(--text-3);text-transform:uppercase;letter-spacing:.05em;margin-bottom:.5rem;">📊 Status Antrian</div>
                <div style="font-weight:700;"><span class="badge badge-{{ $booking->queue->status }}">{{ $booking->queue->status_label }}</span></div>
            </div>
            @endif
        </div>

        @if($booking->complaint)
            <div style="padding:1.25rem;background:var(--bg-muted);border-radius:var(--r);margin-bottom:1.5rem;">
                <div style="font-size:.8rem;font-weight:700;color:var(--text-3);text-transform:uppercase;letter-spacing:.05em;margin-bottom:.5rem;">💬 Keluhan Anda</div>
                <p style="color:var(--text-1);line-height:1.7;">{{ $booking->complaint }}</p>
            </div>
        @endif

        @if($booking->notes)
            <div style="padding:1.25rem;background:#fffbeb;border:1px solid #fde68a;border-radius:var(--r);margin-bottom:1.5rem;">
                <div style="font-size:.8rem;font-weight:700;color:#92400e;text-transform:uppercase;letter-spacing:.05em;margin-bottom:.5rem;">📝 Catatan Dokter</div>
                <p style="color:#78350f;line-height:1.7;">{{ $booking->notes }}</p>
            </div>
        @endif

        <div style="display:flex;gap:1rem;flex-wrap:wrap;margin-top:2rem;">
            <a href="/booking/riwayat" class="btn btn-secondary">← Kembali</a>
            @if($booking->canBeCancelled())
                <form action="/booking/{{ $booking->id }}" method="POST"
                    onsubmit="return confirm('Yakin ingin membatalkan booking ini?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger">✕ Batalkan Booking</button>
                </form>
            @endif
            @if($booking->status !== 'cancelled')
                <a href="/antrian" class="btn btn-outline">🔢 Cek Status Antrian</a>
            @endif
        </div>
    </div>

    <div style="font-size:.85rem;color:var(--text-3);text-align:center;">
        ID Booking: #{{ str_pad($booking->id, 5, '0', STR_PAD_LEFT) }} &nbsp;·&nbsp; Dibuat pada: {{ $booking->created_at->format('d M Y H:i') }} WIB
    </div>
</div>
@endsection
