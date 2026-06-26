@extends('layouts.app')
@section('title',$article->title)
@section('description',$article->excerpt)
@section('content')

<div class="pg-header">
    <div class="container pg-header-inner">
        <div class="breadcrumb"><a href="/">Beranda</a><span>›</span><a href="/edukasi">Edukasi</a><span>›</span><span>{{ Str::limit($article->title,35) }}</span></div>
        <div style="display:flex;gap:.75rem;align-items:center;margin-bottom:.75rem;flex-wrap:wrap;">
            <span class="badge" style="background:rgba(255,255,255,.2);color:#fff;font-size:.82rem;">{{ $article->category_label }}</span>
            <span style="opacity:.8;font-size:.875rem;">⏱ {{ $article->read_time }} menit baca</span>
        </div>
        <h1 style="font-size:1.875rem;max-width:760px;line-height:1.3;">{{ $article->title }}</h1>
    </div>
</div>

<div class="container" style="padding:2.5rem 1.5rem 4rem;">
    <div style="display:grid;grid-template-columns:1fr 300px;gap:2.5rem;align-items:start;">

        {{-- Content --}}
        <div>
            <div class="card" style="padding:2.25rem;">
                <div class="article-body">
                    {!! $article->content !!}
                </div>
            </div>
            <div style="display:flex;gap:.75rem;margin-top:1.5rem;flex-wrap:wrap;">
                <a href="/edukasi" class="btn btn-secondary">← Semua Artikel</a>
                <a href="/edukasi?category={{ $article->category }}" class="btn btn-outline">Artikel {{ $article->category_label }}</a>
                @auth <a href="/booking" class="btn btn-primary">📅 Booking Pemeriksaan</a> @endauth
            </div>
        </div>

        {{-- Sidebar --}}
        <div>
            @if($related->isNotEmpty())
            <div class="card" style="padding:1.25rem;margin-bottom:1.25rem;">
                <h3 style="font-size:.95rem;font-weight:700;margin-bottom:1rem;">📖 Artikel Terkait</h3>
                <div style="display:flex;flex-direction:column;gap:.625rem;">
                    @foreach($related as $r)
                    @php $icon2=['perawatan'=>'⚕️','penyakit'=>'🦠','tips'=>'💡','nutrisi'=>'🥗'][$r->category]??'📄'; @endphp
                    <a href="/edukasi/{{ $r->slug }}" style="display:flex;gap:.75rem;padding:.75rem;border-radius:8px;background:var(--bg-muted);transition:.2s;text-decoration:none;color:inherit;" onmouseover="this.style.background='var(--border)'" onmouseout="this.style.background='var(--bg-muted)'">
                        <div style="width:40px;height:40px;flex-shrink:0;border-radius:8px;background:linear-gradient(135deg,var(--primary),var(--secondary));display:flex;align-items:center;justify-content:center;font-size:1.2rem;">{{ $icon2 }}</div>
                        <div style="flex:1;min-width:0;">
                            <div style="font-size:.85rem;font-weight:600;color:var(--text-1);line-height:1.35;margin-bottom:.2rem;">{{ Str::limit($r->title,55) }}</div>
                            <div style="font-size:.75rem;color:var(--text-3);">⏱ {{ $r->read_time }} menit</div>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
            @endif

            @auth
            <div class="card" style="padding:1.5rem;text-align:center;background:linear-gradient(135deg,var(--primary-light),#ede9fe);">
                <div style="font-size:2rem;margin-bottom:.5rem;">🦷</div>
                <h4 style="font-weight:700;margin-bottom:.4rem;">Butuh Pemeriksaan?</h4>
                <p style="font-size:.85rem;color:var(--text-2);margin-bottom:1rem;line-height:1.6;">Booking jadwal dengan dokter gigi sekarang</p>
                <a href="/booking" class="btn btn-primary btn-full">Booking Sekarang</a>
            </div>
            @endauth
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.article-body{font-size:.975rem;line-height:1.9;color:var(--text-1)}
.article-body h2{font-size:1.3rem;font-weight:700;margin:1.75rem 0 .75rem;color:var(--text-1)}
.article-body h3{font-size:1.1rem;font-weight:700;margin:1.25rem 0 .5rem}
.article-body p{margin-bottom:1rem}
.article-body ul,.article-body ol{padding-left:1.5rem;margin-bottom:1rem}
.article-body li{margin-bottom:.5rem;line-height:1.75}
.article-body strong{font-weight:700;color:var(--text-1)}
@media(max-width:900px){.container>div[style*="grid-template-columns:1fr 300px"]{grid-template-columns:1fr!important}}
</style>
@endpush
