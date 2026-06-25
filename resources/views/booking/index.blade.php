@extends('layouts.app')
@section('title', 'Riwayat Booking')
@section('content')

<div class="pg-header">
    <div class="container pg-header-inner">
        <div class="breadcrumb"><a href="/">Beranda</a><span>›</span><span>Riwayat Booking</span></div>
        <h1>📋 Riwayat Booking Saya</h1>
        <p>Semua jadwal pemeriksaan yang pernah Anda buat</p>
    </div>
</div>

<div class="container" style="padding:2.5rem 1.5rem 4rem;">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:2rem;flex-wrap:wrap;gap:1rem;">
        <h2 style="font-size:1.25rem;font-weight:700;color:var(--text-1);">{{ $bookings->total() }} Booking Ditemukan</h2>
        <a href="/booking" class="btn btn-primary">+ Buat Booking Baru</a>
    </div>

    @forelse($bookings as $booking)
        <div class="card" style="margin-bottom:1rem;display:flex;align-items:center;gap:1.5rem;flex-wrap:wrap;">
            <div style="flex-shrink:0;text-align:center;min-width:70px;background:var(--bg-muted);padding:1rem .5rem;border-radius:var(--r);">
                <div style="font-size:1.75rem;font-weight:900;color:var(--primary-dark);line-height:1;">{{ $booking->booking_date->format('d') }}</div>
                <div style="font-size:.8rem;color:var(--text-3);text-transform:uppercase;margin-top:.25rem;font-weight:600;">{{ $booking->booking_date->format('M Y') }}</div>
            </div>
            <div style="flex:1;min-width:200px;">
                <div style="font-weight:700;font-size:1.1rem;color:var(--text-1);margin-bottom:.25rem;">{{ $booking->doctor->name }}</div>
                <div style="font-size:.875rem;color:var(--text-2);">{{ $booking->doctor->specialization }}</div>
                <div style="display:flex;gap:1rem;margin-top:.5rem;">
                    <span style="font-size:.85rem;color:var(--text-3);display:flex;align-items:center;gap:.35rem;">⏰ {{ substr($booking->booking_time, 0, 5) }} WIB</span>
                    @if($booking->queue_number)
                        <span style="font-size:.85rem;color:var(--text-3);display:flex;align-items:center;gap:.35rem;">🔢 Antrian A-{{ str_pad($booking->queue_number, 3, '0', STR_PAD_LEFT) }}</span>
                    @endif
                </div>
            </div>
            <div style="display:flex;align-items:center;gap:.75rem;flex-wrap:wrap;">
                <span class="badge badge-{{ $booking->status }}" style="padding:.35rem .85rem;font-size:.8rem;">
                    {{ match($booking->status) {
                        'pending'   => '⏳ Menunggu',
                        'confirmed' => '✓ Dikonfirmasi',
                        'cancelled' => '✕ Dibatalkan',
                        'done'      => '✓ Selesai',
                        default     => ucfirst($booking->status)
                    } }}
                </span>
                <a href="/booking/{{ $booking->id }}" class="btn btn-outline btn-sm">Detail</a>
                @if($booking->canBeCancelled())
                    <form action="/booking/{{ $booking->id }}" method="POST"
                        onsubmit="return confirm('Yakin ingin membatalkan booking ini?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Batalkan</button>
                    </form>
                @endif
            </div>
        </div>
    @empty
        <div class="empty-state">
            <div class="empty-icon">📅</div>
            <h3>Belum Ada Booking</h3>
            <p>Anda belum pernah membuat booking pemeriksaan.</p>
            <a href="/booking" class="btn btn-primary">Buat Booking Pertama</a>
        </div>
    @endforelse

    <div style="margin-top:2rem;">
        {{ $bookings->links() }}
    </div>
</div>
@endsection
