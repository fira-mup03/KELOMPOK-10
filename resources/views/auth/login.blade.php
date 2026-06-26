@extends('layouts.app')
@section('title','Masuk')
@section('content')
<div style="min-height:calc(100vh - var(--nav-h));display:flex;align-items:center;justify-content:center;background:linear-gradient(135deg,#f0f9ff,#e0f2fe,#ede9fe);padding:2rem;">
    <div style="width:100%;max-width:440px;">
        <div style="text-align:center;margin-bottom:2rem;">
            <div style="font-size:3rem;margin-bottom:.625rem;">🦷</div>
            <h1 style="font-size:1.75rem;font-weight:800;letter-spacing:-.03em;color:var(--text-1);margin-bottom:.375rem;">Selamat Datang Kembali</h1>
            <p style="color:var(--text-2);">Masuk ke akun DentalCare Anda</p>
        </div>

        <div class="card" style="padding:2rem;box-shadow:var(--sh-xl);">
            @if($errors->any())
                <div class="alert alert-danger">
                    @foreach($errors->all() as $e)<p>{{ $e }}</p>@endforeach
                </div>
            @endif

            <form action="/login" method="POST" id="login-form">
                @csrf
                <div class="form-group">
                    <label class="form-label" for="email">Alamat Email</label>
                    <input id="email" name="email" type="email" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                        value="{{ old('email') }}" placeholder="nama@email.com" required autocomplete="email">
                    @error('email')<span class="invalid-feedback">{{ $message }}</span>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label" for="password">Kata Sandi</label>
                    <input id="password" name="password" type="password" class="form-control"
                        placeholder="Minimal 8 karakter" required autocomplete="current-password">
                </div>
                <div style="display:flex;align-items:center;gap:.5rem;margin-bottom:1.5rem;">
                    <input type="checkbox" name="remember" id="remember" style="width:16px;height:16px;cursor:pointer;accent-color:var(--primary);">
                    <label for="remember" style="font-size:.875rem;color:var(--text-2);cursor:pointer;">Ingat saya</label>
                </div>
                <button type="submit" id="login-btn" class="btn btn-primary btn-full btn-lg">
                    Masuk ke Akun
                </button>
            </form>

            <div style="text-align:center;margin-top:1.5rem;padding-top:1.5rem;border-top:1px solid var(--border);font-size:.875rem;color:var(--text-2);">
                Belum punya akun? <a href="/register" style="color:var(--primary);font-weight:600;">Daftar sekarang</a>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
document.getElementById('login-form').addEventListener('submit',function(){
    const btn=document.getElementById('login-btn');
    btn.innerHTML='<span class="spinner"></span> Memproses...';btn.disabled=true;
});
</script>
@endpush
