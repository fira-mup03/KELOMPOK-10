@extends('layouts.app')
@section('title', 'Pengingat Jadwal')
@section('content')

<div class="pg-header">
    <div class="container pg-header-inner">
        <div class="breadcrumb"><a href="/">Beranda</a><span>›</span><span>Pengingat</span></div>
        <h1>🔔 Pengingat Jadwal</h1>
        <p>Kelola jadwal dan pengingat pemeriksaan gigi Anda</p>
    </div>
</div>

<div class="container" style="padding:2.5rem 1.5rem 4rem;max-width:860px;">

    {{-- Upcoming --}}
    <div style="margin-bottom:4rem;">
        <h2 style="font-size:1.25rem;font-weight:800;margin-bottom:1.5rem;display:flex;align-items:center;gap:.75rem;color:var(--text-1);border-bottom:2px solid var(--border);padding-bottom:.75rem;">
            📅 Jadwal Akan Datang
            <span style="background:var(--primary-light);color:var(--primary-dark);border-radius:999px;padding:.2rem .75rem;font-size:.85rem;">{{ $upcoming->count() }}</span>
        </h2>

        @forelse($upcoming as $reminder)
            @php $b = $reminder->booking; @endphp
            <div class="card card-link" style="margin-bottom:1.25rem;border-left:4px solid var(--primary);display:block;text-decoration:none;" onclick="window.location.href='/booking/{{ $b->id }}'">
                <div style="display:flex;justify-content:space-between;align-items:start;flex-wrap:wrap;gap:1.5rem;">
                    <div style="flex:1;">
                        <div style="display:flex;gap:.75rem;align-items:center;flex-wrap:wrap;margin-bottom:.75rem;">
                            <span class="badge badge-{{ $b->status }}">
                                {{ match($b->status) { 'pending'=>'⏳ Menunggu','confirmed'=>'✓ Dikonfirmasi','cancelled'=>'✕ Dibatalkan','done'=>'✓ Selesai',default=>ucfirst($b->status) } }}
                            </span>
                            @if($b->queue_number)
                                <span style="font-size:.85rem;color:var(--primary-dark);font-weight:700;">Antrian A-{{ str_pad($b->queue_number, 3, '0', STR_PAD_LEFT) }}</span>
                            @endif
                        </div>
                        <h3 style="font-size:1.2rem;font-weight:800;margin-bottom:.25rem;color:var(--text-1);">{{ $b->doctor->name }}</h3>
                        <p style="color:var(--text-2);font-size:.9rem;font-weight:500;">{{ $b->doctor->specialization }}</p>
                        <div style="margin-top:.75rem;font-size:.9rem;color:var(--text-2);display:flex;gap:1.5rem;">
                            <span style="display:flex;align-items:center;gap:.35rem;"><span style="color:var(--text-3);">📅</span> {{ $b->booking_date->format('l, d F Y') }}</span>
                            <span style="display:flex;align-items:center;gap:.35rem;"><span style="color:var(--text-3);">⏰</span> {{ substr($b->booking_time, 0, 5) }} WIB</span>
                        </div>
                    </div>
                    <div style="display:flex;gap:.75rem;flex-wrap:wrap;">
                        @if($b->canBeCancelled())
                            <form action="/booking/{{ $b->id }}" method="POST" onsubmit="event.stopPropagation(); return confirm('Batalkan booking ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Batalkan</button>
                            </form>
                        @endif
                        <span class="btn btn-outline btn-sm">Detail</span>
                    </div>
                </div>
            </div>
        @empty
            <div class="empty-state card" style="padding:4rem 2rem;">
                <div style="font-size:3.5rem;margin-bottom:.75rem;opacity:.4;">📅</div>
                <h3 style="font-size:1.1rem;margin-bottom:.5rem;">Tidak Ada Jadwal Mendatang</h3>
                <p style="color:var(--text-3);margin-bottom:1.5rem;">Anda belum memiliki jadwal pemeriksaan yang akan datang.</p>
                <a href="/booking" class="btn btn-primary">Buat Booking Baru</a>
            </div>
        @endforelse
    </div>

    {{-- Past --}}
    <div>
        <h2 style="font-size:1.25rem;font-weight:800;margin-bottom:1.5rem;display:flex;align-items:center;gap:.75rem;color:var(--text-2);border-bottom:2px solid var(--border);padding-bottom:.75rem;">
            ✅ Selesai / Lewat
            <span style="background:var(--bg-muted);color:var(--text-3);border-radius:999px;padding:.2rem .75rem;font-size:.85rem;">{{ $past->count() }}</span>
        </h2>

        @forelse($past as $reminder)
            @php $b = $reminder->booking; @endphp
            <a href="/booking/{{ $b->id }}" class="card card-link" style="margin-bottom:1rem;border-left:4px solid var(--border-h);opacity:.85;display:block;text-decoration:none;">
                <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:1.5rem;">
                    <div>
                        <div style="display:flex;gap:.75rem;align-items:center;margin-bottom:.5rem;">
                            <span class="badge badge-{{ $b->status }}" style="font-size:.75rem;padding:.2rem .6rem;">
                                {{ match($b->status) { 'pending'=>'⏳ Menunggu','confirmed'=>'✓ Dikonfirmasi','cancelled'=>'✕ Dibatalkan','done'=>'✓ Selesai',default=>ucfirst($b->status) } }}
                            </span>
                        </div>
                        <div style="font-weight:700;color:var(--text-1);font-size:1.05rem;">{{ $b->doctor->name }}</div>
                        <div style="font-size:.85rem;color:var(--text-3);margin-top:.25rem;">
                            {{ $b->booking_date->format('d M Y') }} · {{ substr($b->booking_time, 0, 5) }}
                        </div>
                    </div>
                    <span style="color:var(--text-2);font-weight:600;font-size:.85rem;">Detail →</span>
                </div>
            </a>
        @empty
            <div class="empty-state card" style="padding:2rem;margin-bottom:1rem;opacity:.7;">
                <p style="color:var(--text-3);margin:0;font-size:.9rem;">Belum ada riwayat jadwal.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
