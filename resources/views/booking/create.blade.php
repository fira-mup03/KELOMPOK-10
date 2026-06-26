@extends('layouts.app')
@section('title','Booking Pemeriksaan')
@section('content')

<div class="pg-header">
    <div class="container pg-header-inner">
        <div class="breadcrumb"><a href="/">Beranda</a><span>›</span><span>Booking</span></div>
        <h1>📅 Booking Pemeriksaan</h1>
        <p>Buat jadwal pemeriksaan gigi dengan dokter pilihan Anda</p>
    </div>
</div>

<div style="max-width:800px;margin:2.5rem auto 4rem;padding:0 1.5rem;">
    <div class="card" style="padding:2rem;">
        @if($errors->any())
        <div class="alert alert-danger">
            @foreach($errors->all() as $e)<p>{{ $e }}</p>@endforeach
        </div>
        @endif

        <form action="/booking" method="POST" id="booking-form">
            @csrf

            @foreach([1=>['Pilih Dokter'],2=>['Pilih Tanggal'],3=>['Pilih Waktu'],4=>['Keluhan (Opsional)']] as $n=>$step)
            <div style="margin-bottom:2rem;">
                <div style="display:flex;align-items:center;gap:.75rem;margin-bottom:1rem;">
                    <div style="width:30px;height:30px;border-radius:50%;background:linear-gradient(135deg,var(--primary),var(--secondary));color:#fff;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:.85rem;flex-shrink:0;">{{ $n }}</div>
                    <h3 style="font-size:.975rem;font-weight:700;color:var(--text-1);">{{ $step[0] }}</h3>
                </div>
                @if($n===1)
                <div class="form-group">
                    <label class="form-label" for="doctor_id">Dokter Gigi</label>
                    <select id="doctor_id" name="doctor_id" class="form-control {{ $errors->has('doctor_id')?'is-invalid':'' }}" required>
                        <option value="">— Pilih dokter —</option>
                        @foreach($doctors as $doc)
                        <option value="{{ $doc->id }}" {{ old('doctor_id')==$doc->id?'selected':'' }}>{{ $doc->name }} — {{ $doc->specialization }}</option>
                        @endforeach
                    </select>
                    @error('doctor_id')<span class="invalid-feedback">{{ $message }}</span>@enderror
                </div>
                <div id="schedule-info" style="display:none;" class="alert alert-info" id="sch-info">
                    <span id="sch-text"></span>
                </div>
                @elseif($n===2)
                <div class="form-group">
                    <label class="form-label" for="booking_date">Tanggal Pemeriksaan</label>
                    <input id="booking_date" name="booking_date" type="date"
                        class="form-control {{ $errors->has('booking_date')?'is-invalid':'' }}"
                        value="{{ old('booking_date') }}" min="{{ date('Y-m-d') }}" required>
                    @error('booking_date')<span class="invalid-feedback">{{ $message }}</span>@enderror
                </div>
                @elseif($n===3)
                <div class="form-group">
                    <label class="form-label" for="booking_time">Jam Pemeriksaan</label>
                    <select id="booking_time" name="booking_time" class="form-control" required>
                        <option value="">— Pilih jam (pilih dokter & tanggal dulu) —</option>
                        @if(old('booking_time'))
                        <option value="{{ old('booking_time') }}" selected>{{ old('booking_time') }}</option>
                        @endif
                    </select>
                    @error('booking_time')<span class="invalid-feedback">{{ $message }}</span>@enderror
                </div>
                @else
                <div class="form-group">
                    <label class="form-label" for="complaint">Ceritakan Keluhan Anda</label>
                    <textarea id="complaint" name="complaint" class="form-control" rows="4"
                        placeholder="Contoh: Gigi geraham kiri bawah sakit saat makan makanan dingin...">{{ old('complaint') }}</textarea>
                </div>
                @endif
            </div>
            @endforeach

            <div style="display:flex;gap:1rem;justify-content:flex-end;border-top:1px solid var(--border);padding-top:1.5rem;">
                <a href="/booking/riwayat" class="btn btn-secondary">Lihat Riwayat</a>
                <button type="submit" id="submit-btn" class="btn btn-primary btn-lg">📅 Konfirmasi Booking</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
const dayNames=['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
let schedules=[];

document.getElementById('doctor_id').addEventListener('change',async function(){
    const id=this.value;
    const info=document.getElementById('schedule-info');
    if(!id){schedules=[];info.style.display='none';return;}
    const res=await fetch(`/api/doctors/${id}/schedules`);
    const data=await res.json();
    schedules=data.schedules;
    const days=schedules.map(s=>dayNames[s.day_of_week]).join(', ');
    document.getElementById('sch-text').textContent=`📅 Dokter berpraktik: ${days}`;
    info.style.display='block';
    document.getElementById('booking_date').value='';
    updateSlots();
});

document.getElementById('booking_date').addEventListener('change',updateSlots);

function updateSlots(){
    const date=document.getElementById('booking_date').value;
    const sel=document.getElementById('booking_time');
    sel.innerHTML='<option value="">— Pilih jam —</option>';
    if(!date)return;
    const dow=new Date(date+'T00:00:00').getDay();
    const sch=schedules.find(s=>s.day_of_week===dow);
    if(!sch){
        sel.innerHTML='<option value="" disabled>⚠ Dokter tidak berpraktik hari ini</option>';
        return;
    }
    sch.time_slots.forEach(slot=>{
        const o=document.createElement('option');o.value=slot;o.textContent=slot;sel.appendChild(o);
    });
}

document.getElementById('booking-form').addEventListener('submit',function(){
    const b=document.getElementById('submit-btn');
    b.innerHTML='<span class="spinner"></span> Memproses...';b.disabled=true;
});
</script>
@endpush
