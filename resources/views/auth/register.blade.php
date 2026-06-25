@extends('layouts.app')
@section('title','Daftar Akun')
@section('content')
<div style="min-height:calc(100vh - var(--nav-h));display:flex;align-items:center;justify-content:center;background:linear-gradient(135deg,#f0f9ff,#e0f2fe,#ede9fe);padding:2rem;">
    <div style="width:100%;max-width:480px;">
        <div style="text-align:center;margin-bottom:2rem;">
            <div style="font-size:3rem;margin-bottom:.625rem;">🦷</div>
            <h1 style="font-size:1.75rem;font-weight:800;letter-spacing:-.03em;color:var(--text-1);margin-bottom:.375rem;">Buat Akun Baru</h1>
            <p style="color:var(--text-2);">Bergabung dengan DentalCare hari ini</p>
        </div>

        <div class="card" style="padding:2rem;box-shadow:var(--sh-xl);">
            @if($errors->any())
                <div class="alert alert-danger">
                    @foreach($errors->all() as $e)<p>{{ $e }}</p>@endforeach
                </div>
            @endif

            <form action="/register" method="POST" id="reg-form">
                @csrf
                <div class="form-group">
                    <label class="form-label" for="name">Nama Lengkap</label>
                    <input id="name" name="name" type="text" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
                        value="{{ old('name') }}" placeholder="Budi Santoso" required autocomplete="name">
                    @error('name')<span class="invalid-feedback">{{ $message }}</span>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label" for="email">Alamat Email</label>
                    <input id="email" name="email" type="email" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                        value="{{ old('email') }}" placeholder="nama@email.com" required autocomplete="email">
                    @error('email')<span class="invalid-feedback">{{ $message }}</span>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label" for="password">Kata Sandi</label>
                    <input id="password" name="password" type="password" class="form-control"
                        placeholder="Minimal 8 karakter" required autocomplete="new-password">
                    @error('password')<span class="invalid-feedback">{{ $message }}</span>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label" for="password_confirmation">Konfirmasi Kata Sandi</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" class="form-control"
                        placeholder="Ulangi kata sandi" required autocomplete="new-password">
                </div>
                <div style="font-size:.82rem;color:var(--text-3);margin-bottom:1.25rem;padding:.875rem;background:var(--bg-muted);border-radius:8px;line-height:1.6;">
                    🔒 Dengan mendaftar, Anda menyetujui kebijakan privasi dan syarat penggunaan DentalCare.
                </div>
                <button type="submit" id="reg-btn" class="btn btn-primary btn-full btn-lg">
                    Buat Akun Sekarang
                </button>
            </form>

            <div style="text-align:center;margin-top:1.5rem;padding-top:1.5rem;border-top:1px solid var(--border);font-size:.875rem;color:var(--text-2);">
                Sudah punya akun? <a href="/login" style="color:var(--primary);font-weight:600;">Masuk di sini</a>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
document.getElementById('reg-form').addEventListener('submit',function(){
    const btn=document.getElementById('reg-btn');
    btn.innerHTML='<span class="spinner"></span> Membuat Akun...';btn.disabled=true;
});
</script>
@endpush
