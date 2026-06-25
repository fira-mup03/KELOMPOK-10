@extends('layouts.app')
@section('title', 'Status Antrian')
@section('content')

<div class="pg-header">
    <div class="container pg-header-inner">
        <div class="breadcrumb"><a href="/">Beranda</a><span>›</span><span>Antrian</span></div>
        <h1>🔢 Status Antrian Pemeriksaan</h1>
        <p>Pantau nomor antrian Anda secara real-time</p>
    </div>
</div>

<div class="container" style="padding:2.5rem 1.5rem 4rem;max-width:760px;">

    {{-- Antrian Saya --}}
    @if($myQueue)
        <div class="card" style="padding:3rem 2rem;text-align:center;margin-bottom:2.5rem;background:linear-gradient(135deg,var(--primary-light),#ede9fe);border:none;box-shadow:var(--sh-md);">
            <div style="font-size:.85rem;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:var(--primary-dark);margin-bottom:.75rem;">Nomor Antrian Anda</div>
            <div style="font-size:5.5rem;font-weight:900;color:var(--primary-dark);line-height:1;margin-bottom:.75rem;letter-spacing:-.02em;" id="my-number">
                {{ $myQueue->formatted_number }}
            </div>
            <div style="margin-bottom:2rem;">
                <span class="badge badge-{{ $myQueue->status }}" style="font-size:1.05rem;padding:.5rem 1.5rem;font-weight:700;">
                    {{ $myQueue->status_label }}
                </span>
            </div>

            @if($myQueue->booking)
                <div style="padding:1.25rem;background:rgba(255,255,255,.7);border-radius:var(--r);margin-bottom:1.25rem;text-align:left;display:flex;justify-content:space-between;align-items:center;">
                    <div>
                        <div style="font-size:.85rem;color:var(--text-2);font-weight:600;margin-bottom:.25rem;">Dokter Pemeriksa</div>
                        <div style="font-weight:800;color:var(--text-1);font-size:1.1rem;">{{ $myQueue->booking->doctor->name }}</div>
                    </div>
                    <div style="text-align:right;">
                        <div style="font-size:.85rem;color:var(--text-2);font-weight:600;margin-bottom:.25rem;">Waktu Booking</div>
                        <div style="font-weight:800;color:var(--primary-dark);font-size:1.1rem;">
                            {{ $myQueue->queue_date->isToday() ? 'Hari ini' : $myQueue->queue_date->format('d M') }}, {{ substr($myQueue->booking->booking_time, 0, 5) }} WIB
                        </div>
                    </div>
                </div>

                @if(!$myQueue->queue_date->isToday())
                <div class="alert alert-warning" style="text-align:left;font-size:.85rem;margin-bottom:1.25rem;">
                    ⚠️ Jadwal pemeriksaan Anda adalah untuk tanggal <strong>{{ $myQueue->queue_date->format('d F Y') }}</strong>. Estimasi waktu tunggu dan status panggilan baru akan aktif pada hari H.
                </div>
                @endif
            @endif

            @if($estimasi !== null && $myQueue->status === 'waiting' && $myQueue->queue_date->isToday())
                <div style="padding:1.25rem;background:rgba(255,255,255,.9);border-radius:var(--r);box-shadow:var(--sh-sm);">
                    <div style="font-size:.85rem;color:var(--text-2);font-weight:600;margin-bottom:.25rem;">⏱ Estimasi Waktu Tunggu</div>
                    <div style="font-size:2rem;font-weight:900;color:var(--warning);" id="estimasi-text">
                        ~{{ $estimasi }} menit
                    </div>
                </div>
            @endif
        </div>
    @else
        <div class="empty-state card" style="margin-bottom:2.5rem;padding:4rem 2rem;">
            <div class="empty-icon">🔢</div>
            <h3 style="font-size:1.25rem;margin-bottom:.75rem;">Tidak Ada Antrian Hari Ini</h3>
            <p style="margin-bottom:2rem;">Anda tidak memiliki jadwal pemeriksaan untuk hari ini.</p>
            <a href="/booking" class="btn btn-primary btn-lg">Buat Booking Baru</a>
        </div>
    @endif

    {{-- Antrian Sekarang (Realtime) --}}
    <div class="card" style="margin-bottom:2rem;">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem;border-bottom:1px solid var(--border);padding-bottom:1rem;">
            <h3 style="font-size:1.2rem;font-weight:800;color:var(--text-1);">📊 Status Antrian Global</h3>
            <span style="font-size:.8rem;color:var(--text-3);font-weight:500;" id="last-updated">Memuat...</span>
        </div>

        <div class="grid-2">
            <div style="text-align:center;padding:2rem;background:var(--bg-muted);border-radius:var(--r);">
                <div style="font-size:.85rem;font-weight:700;color:var(--text-2);text-transform:uppercase;letter-spacing:.05em;margin-bottom:.5rem;">Sedang Dipanggil</div>
                <div style="font-size:3.5rem;font-weight:900;color:var(--info);line-height:1;" id="current-number">—</div>
            </div>
            <div style="text-align:center;padding:2rem;background:var(--bg-muted);border-radius:var(--r);">
                <div style="font-size:.85rem;font-weight:700;color:var(--text-2);text-transform:uppercase;letter-spacing:.05em;margin-bottom:.5rem;">Antrian Menunggu</div>
                <div style="font-size:3.5rem;font-weight:900;color:var(--warning);line-height:1;" id="waiting-count">—</div>
            </div>
        </div>
    </div>

    <div style="text-align:center;font-size:.85rem;color:var(--text-3);font-weight:500;">
        🔄 Data diperbarui otomatis setiap 10 detik
    </div>
</div>
@endsection

@push('scripts')
<script>
function fetchQueueStatus(){
    fetch('/antrian/status')
        .then(r=>r.json())
        .then(d=>{
            document.getElementById('current-number').textContent=d.current_number||'—';
            document.getElementById('waiting-count').textContent=d.waiting_count;
            document.getElementById('last-updated').textContent='Diperbarui: '+d.last_updated;
        }).catch(()=>{});
}
fetchQueueStatus();setInterval(fetchQueueStatus,10000);
</script>
@endpush
