@extends('layouts.app')
@section('title','Edit Profil')
@section('content')

<div class="pg-header">
    <div class="container pg-header-inner">
        <div class="breadcrumb"><a href="/">Beranda</a><span>›</span><a href="/profile">Profil</a><span>›</span><span>Edit</span></div>
        <h1>✏️ Edit Profil</h1>
        <p>Perbarui informasi akun dan data diri Anda</p>
    </div>
</div>

<div style="max-width:760px;margin:2.5rem auto 4rem;padding:0 1.5rem;">
    <div class="card" style="padding:2rem;">
        <form action="/profile" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            @if($errors->any())
                <div class="alert alert-danger">
                    @foreach($errors->all() as $e)<p>{{ $e }}</p>@endforeach
                </div>
            @endif

            {{-- Avatar --}}
            <div style="display:flex;align-items:center;gap:1.5rem;margin-bottom:2rem;padding:1.5rem;background:var(--bg-muted);border-radius:var(--r);">
                @if($user->avatar)
                    <img id="avatar-preview" src="{{ Storage::url($user->avatar) }}" alt="Avatar"
                        style="width:80px;height:80px;border-radius:50%;object-fit:cover;border:2px solid var(--primary);">
                @else
                    <div id="avatar-preview" style="width:80px;height:80px;border-radius:50%;background:linear-gradient(135deg,var(--primary),var(--secondary));display:flex;align-items:center;justify-content:center;font-size:2rem;font-weight:800;color:#fff;">
                        {{ strtoupper(substr($user->name,0,1)) }}
                    </div>
                @endif
                <div>
                    <label class="btn btn-outline btn-sm" for="avatar" style="cursor:pointer;display:inline-flex;">📷 Ganti Foto</label>
                    <input type="file" id="avatar" name="avatar" accept="image/*" style="display:none;" onchange="previewImg(this)">
                    <p style="font-size:.8rem;color:var(--text-3);margin-top:.5rem;">JPG, PNG, WebP · Maks 2MB</p>
                </div>
            </div>

            <div class="grid-2">
                <div class="form-group">
                    <label class="form-label" for="name">Nama Lengkap *</label>
                    <input id="name" name="name" type="text" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
                        value="{{ old('name',$user->name) }}" required>
                    @error('name')<span class="invalid-feedback">{{ $message }}</span>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label" for="phone">No. Telepon</label>
                    <input id="phone" name="phone" type="tel" class="form-control" value="{{ old('phone',$user->phone) }}" placeholder="08xxxxxxxxxx">
                </div>
                <div class="form-group">
                    <label class="form-label" for="date_of_birth">Tanggal Lahir</label>
                    <input id="date_of_birth" name="date_of_birth" type="date" class="form-control"
                        value="{{ old('date_of_birth',$user->date_of_birth?->format('Y-m-d')) }}">
                </div>
                <div class="form-group">
                    <label class="form-label" for="gender">Jenis Kelamin</label>
                    <select id="gender" name="gender" class="form-control">
                        <option value="">— Pilih —</option>
                        <option value="L" {{ old('gender',$user->gender)==='L'?'selected':'' }}>Laki-laki</option>
                        <option value="P" {{ old('gender',$user->gender)==='P'?'selected':'' }}>Perempuan</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="address">Alamat</label>
                <textarea id="address" name="address" class="form-control" placeholder="Jl. Contoh No. 1, Kota">{{ old('address',$user->address) }}</textarea>
            </div>

            <div style="display:flex;gap:1rem;justify-content:flex-end;margin-top:.5rem;">
                <a href="/profile" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">💾 Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endsection
@push('scripts')
<script>
function previewImg(input){
    if(!input.files||!input.files[0])return;
    const reader=new FileReader();
    reader.onload=e=>{
        const p=document.getElementById('avatar-preview');
        if(p.tagName==='IMG'){p.src=e.target.result;}
        else{const img=document.createElement('img');img.src=e.target.result;img.id='avatar-preview';
        img.style.cssText='width:80px;height:80px;border-radius:50%;object-fit:cover;border:2px solid var(--primary);';
        p.replaceWith(img);}
    };
    reader.readAsDataURL(input.files[0]);
}
</script>
@endpush
