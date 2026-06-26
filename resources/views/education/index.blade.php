@extends('layouts.app')
@section('title','Edukasi Kesehatan Gigi')
@section('description','Baca artikel dan tips kesehatan gigi dari dokter-dokter terpercaya DentalCare.')
@section('content')

<div class="pg-header">
    <div class="container pg-header-inner">
        <div class="breadcrumb"><a href="/">Beranda</a><span>›</span><span>Edukasi</span></div>
        <h1>📚 Edukasi Kesehatan Gigi</h1>
        <p>Artikel dan tips terpercaya untuk menjaga senyum sehat Anda</p>
    </div>
</div>

<div class="container" style="padding:2.5rem 1.5rem 4rem;">

    {{-- Search --}}
    <form method="GET" action="/edukasi" style="margin-bottom:1.75rem;">
        <div style="display:flex;gap:1rem;flex-wrap:wrap;align-items:flex-end;">
            <div style="flex:1;min-width:220px;">
                <label class="form-label">Cari Artikel</label>
                <input type="text" name="q" class="form-control" value="{{ $search }}" placeholder="Contoh: gigi berlubang...">
            </div>
            <input type="hidden" name="category" value="{{ $category }}">
            <button type="submit" class="btn btn-primary">🔍 Cari</button>
            @if($search)<a href="/edukasi?category={{ $category }}" class="btn btn-secondary">✕ Reset</a>@endif
        </div>
    </form>

    {{-- Category tabs --}}
    <div style="display:flex;gap:.5rem;flex-wrap:wrap;margin-bottom:2rem;">
        @php $catIcons=['semua'=>'🌐','perawatan'=>'⚕️','penyakit'=>'🦠','tips'=>'💡','nutrisi'=>'🥗']; @endphp
        @foreach($categories as $cat)
        <a href="/edukasi?category={{ $cat }}{{ $search ? '&q='.$search : '' }}"
           style="padding:.45rem 1rem;border-radius:999px;font-size:.875rem;font-weight:600;transition:.2s;display:inline-flex;align-items:center;gap:.35rem;
                  background:{{ $category===$cat ? 'var(--primary)' : 'var(--bg-card)' }};
                  color:{{ $category===$cat ? '#fff' : 'var(--text-2)' }};
                  border:1.5px solid {{ $category===$cat ? 'var(--primary)' : 'var(--border)' }};">
            {{ $catIcons[$cat] ?? '' }} {{ ucfirst($cat) }}
        </a>
        @endforeach
    </div>

    {{-- Grid --}}
    @if($articles->isEmpty())
        <div class="empty-state">
            <div class="empty-icon">📚</div>
            <h3>Tidak Ada Artikel</h3>
            <p>Coba kata kunci atau kategori yang berbeda.</p>
            <a href="/edukasi" class="btn btn-primary">Lihat Semua Artikel</a>
        </div>
    @else
        <div class="grid-3" style="margin-bottom:2rem;">
            @foreach($articles as $article)
            @php
            $catColors=['perawatan'=>'135deg,#10b981,#06b6d4','penyakit'=>'135deg,#ef4444,#f59e0b',
                        'tips'=>'135deg,#3b82f6,#6366f1','nutrisi'=>'135deg,#f59e0b,#10b981'];
            $bg=$catColors[$article->category] ?? '135deg,var(--primary),var(--secondary)';
            $icon=['perawatan'=>'⚕️','penyakit'=>'🦠','tips'=>'💡','nutrisi'=>'🥗'][$article->category] ?? '📄';
            @endphp
            <a href="/edukasi/{{ $article->slug }}" class="card card-link" style="padding:0;overflow:hidden;">
                <div style="height:160px;background:linear-gradient({{ $bg }});display:flex;align-items:center;justify-content:center;font-size:3.5rem;">
                    {{ $icon }}
                </div>
                <div style="padding:1.25rem;">
                    <div style="display:flex;align-items:center;gap:.5rem;margin-bottom:.75rem;">
                        <span class="badge badge-{{ $article->category }}">{{ $article->category_label }}</span>
                        <span style="font-size:.78rem;color:var(--text-3);">⏱ {{ $article->read_time }} mnt</span>
                    </div>
                    <h3 style="font-size:.975rem;font-weight:700;line-height:1.4;margin-bottom:.5rem;color:var(--text-1);">{{ $article->title }}</h3>
                    <p style="font-size:.85rem;color:var(--text-2);line-height:1.65;">{{ $article->excerpt }}</p>
                    <div style="margin-top:1rem;font-size:.85rem;font-weight:600;color:var(--primary);">Baca Selengkapnya →</div>
                </div>
            </a>
            @endforeach
        </div>

        {{-- Pagination --}}
        @if($articles->hasPages())
        <div class="pagination">
            @if($articles->onFirstPage())
                <span class="page-link" style="opacity:.4;cursor:default;">← Prev</span>
            @else
                <a href="{{ $articles->previousPageUrl() }}" class="page-link">← Prev</a>
            @endif

            @foreach($articles->getUrlRange(1,$articles->lastPage()) as $page => $url)
                <a href="{{ $url }}" class="page-link {{ $articles->currentPage()===$page ? 'active' : '' }}">{{ $page }}</a>
            @endforeach

            @if($articles->hasMorePages())
                <a href="{{ $articles->nextPageUrl() }}" class="page-link">Next →</a>
            @else
                <span class="page-link" style="opacity:.4;cursor:default;">Next →</span>
            @endif
        </div>
        @endif
    @endif
</div>
@endsection
