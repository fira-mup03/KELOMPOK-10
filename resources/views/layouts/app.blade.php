<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'DentalCare') — DentalCare</title>
    <meta name="description" content="@yield('description', 'Aplikasi manajemen kesehatan gigi terpercaya.')">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
/* ===================== RESET ===================== */
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
html{scroll-behavior:smooth}
body{font-family:'Inter',system-ui,sans-serif;background:#f8fafc;color:#0f172a;line-height:1.6;-webkit-font-smoothing:antialiased}
a{text-decoration:none;color:inherit}
img{max-width:100%;display:block}
button{cursor:pointer;font-family:inherit}
ul,ol{list-style:none}

/* ===================== VARIABLES ===================== */
:root{
  --primary:#0ea5e9;--primary-dark:#0284c7;--primary-light:#e0f2fe;
  --secondary:#06b6d4;--accent:#6366f1;
  --success:#10b981;--warning:#f59e0b;--danger:#ef4444;--info:#3b82f6;
  --bg:#f8fafc;--bg-card:#fff;--bg-muted:#f1f5f9;
  --border:#e2e8f0;--border-h:#cbd5e1;
  --text-1:#0f172a;--text-2:#475569;--text-3:#94a3b8;
  --sh-sm:0 1px 3px rgba(0,0,0,.08);
  --sh-md:0 4px 16px rgba(0,0,0,.10);
  --sh-lg:0 10px 40px rgba(0,0,0,.12);
  --sh-xl:0 20px 60px rgba(14,165,233,.18);
  --r:.75rem;--r-lg:1.25rem;--r-xl:2rem;
  --nav-h:72px;--tr:.25s cubic-bezier(.4,0,.2,1);
}

/* ===================== NAVBAR ===================== */
.navbar{position:fixed;top:0;left:0;right:0;height:var(--nav-h);background:rgba(255,255,255,.96);backdrop-filter:blur(12px);border-bottom:1px solid var(--border);z-index:1000;transition:box-shadow var(--tr)}
.navbar.scrolled{box-shadow:var(--sh-md)}
.nav-inner{max-width:1280px;margin:0 auto;padding:0 1.5rem;height:100%;display:flex;align-items:center;gap:1.5rem}
.nav-logo{font-size:1.3rem;font-weight:800;color:var(--primary-dark);letter-spacing:-.02em;flex-shrink:0}
.nav-logo span{color:var(--secondary)}
.nav-links{display:flex;gap:.25rem;flex:1}
.nav-links a{padding:.45rem .875rem;border-radius:8px;font-size:.875rem;font-weight:500;color:var(--text-2);transition:var(--tr)}
.nav-links a:hover,.nav-links a.active{background:var(--primary-light);color:var(--primary-dark)}
.nav-actions{display:flex;align-items:center;gap:.625rem}
.btn-nav-outline{padding:.4rem .875rem;border:1.5px solid var(--border);border-radius:8px;font-size:.85rem;font-weight:600;color:var(--text-2);transition:var(--tr)}
.btn-nav-outline:hover{border-color:var(--primary);color:var(--primary)}
.btn-nav-solid{padding:.4rem 1rem;background:linear-gradient(135deg,var(--primary),var(--secondary));border-radius:8px;font-size:.85rem;font-weight:600;color:#fff;box-shadow:0 2px 8px rgba(14,165,233,.3);transition:var(--tr)}
.btn-nav-solid:hover{transform:translateY(-1px);box-shadow:0 4px 14px rgba(14,165,233,.4)}

/* User dropdown */
.nav-user{position:relative}
.nav-avatar-wrap{display:flex;align-items:center;cursor:pointer}
.nav-avatar-img{width:36px;height:36px;border-radius:50%;object-fit:cover;border:2px solid var(--primary)}
.nav-avatar-init{width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,var(--primary),var(--secondary));color:#fff;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:.9rem}

/* Invisible bridge to fix hover loss */
.nav-dropdown-wrapper{position:absolute;top:100%;right:0;padding-top:8px;display:none;z-index:200}
.nav-user:hover .nav-dropdown-wrapper{display:block}

.nav-dropdown{background:#fff;border:1px solid var(--border);border-radius:var(--r);box-shadow:var(--sh-lg);padding:.5rem;min-width:175px;display:flex;flex-direction:column;gap:.25rem}
.nav-dropdown a,.nav-dropdown button{display:flex;align-items:center;gap:.5rem;padding:.55rem .875rem;border-radius:6px;font-size:.875rem;font-weight:500;color:var(--text-2);background:none;border:none;text-align:left;width:100%;cursor:pointer;transition:var(--tr)}
.nav-dropdown a:hover,.nav-dropdown button:hover{background:var(--bg-muted);color:var(--text-1)}

/* Notification Badge */
.nav-notif{position:relative;display:flex;align-items:center;justify-content:center;width:36px;height:36px;border-radius:50%;background:var(--bg-muted);color:var(--text-2);transition:var(--tr)}
.nav-notif:hover{background:var(--border);color:var(--primary-dark)}
.nav-notif-badge{position:absolute;top:-2px;right:-2px;background:var(--danger);color:#fff;font-size:.65rem;font-weight:800;width:18px;height:18px;display:flex;align-items:center;justify-content:center;border-radius:50%;border:2px solid #fff}


/* ===================== FLASH ===================== */
.flash{position:fixed;top:calc(var(--nav-h) + 12px);right:1.5rem;padding:.875rem 1.25rem;border-radius:var(--r);display:flex;align-items:center;gap:1rem;font-size:.875rem;font-weight:500;z-index:999;box-shadow:var(--sh-lg);max-width:400px;transition:opacity .5s}
.flash-ok{background:#d1fae5;color:#065f46;border:1px solid #a7f3d0}
.flash-err{background:#fee2e2;color:#991b1b;border:1px solid #fca5a5}
.flash button{background:none;border:none;font-size:1rem;cursor:pointer;opacity:.6}
.flash button:hover{opacity:1}

/* ===================== LAYOUT ===================== */
.main-wrap{min-height:calc(100vh - var(--nav-h));padding-top:var(--nav-h)}
.container{max-width:1280px;margin:0 auto;padding:0 1.5rem}
.container-sm{max-width:760px;margin:0 auto;padding:0 1.5rem}

/* ===================== PAGE HEADER ===================== */
.pg-header{background:linear-gradient(135deg,#0ea5e9 0%,#06b6d4 55%,#6366f1 100%);padding:3.5rem 0 2.5rem;color:#fff;position:relative;overflow:hidden}
.pg-header::before{content:'';position:absolute;top:-50%;right:-8%;width:560px;height:560px;border-radius:50%;background:rgba(255,255,255,.06)}
.pg-header-inner{position:relative;z-index:1}
.breadcrumb{display:flex;gap:.5rem;align-items:center;font-size:.8rem;opacity:.78;margin-bottom:.875rem}
.breadcrumb a:hover{opacity:1;text-decoration:underline}
.breadcrumb span{opacity:.5}
.pg-header h1{font-size:2.1rem;font-weight:800;margin-bottom:.4rem}
.pg-header p{font-size:1rem;opacity:.85}

/* ===================== CARDS ===================== */
.card{background:var(--bg-card);border:1px solid var(--border);border-radius:var(--r-lg);padding:1.5rem;box-shadow:var(--sh-sm);transition:var(--tr)}
.card:hover{box-shadow:var(--sh-md);border-color:var(--border-h)}
.card-link{display:block;text-decoration:none;color:inherit}
.card-link:hover{transform:translateY(-3px);box-shadow:var(--sh-lg)!important;border-color:var(--primary)!important}

/* ===================== BADGES ===================== */
.badge{display:inline-flex;align-items:center;padding:.22rem .65rem;border-radius:999px;font-size:.75rem;font-weight:600;letter-spacing:.01em}
.badge-pending   {background:#fef3c7;color:#92400e}
.badge-confirmed {background:#d1fae5;color:#065f46}
.badge-cancelled {background:#fee2e2;color:#991b1b}
.badge-done      {background:#ede9fe;color:#4c1d95}
.badge-waiting   {background:#fef3c7;color:#92400e}
.badge-in_progress{background:#dbeafe;color:#1e40af}
.badge-skipped   {background:#f1f5f9;color:#64748b}
.badge-perawatan {background:#d1fae5;color:#065f46}
.badge-penyakit  {background:#fee2e2;color:#991b1b}
.badge-tips      {background:#dbeafe;color:#1e40af}
.badge-nutrisi   {background:#fef3c7;color:#92400e}

/* ===================== BUTTONS ===================== */
.btn{display:inline-flex;align-items:center;justify-content:center;gap:.45rem;padding:.6rem 1.25rem;border-radius:8px;font-size:.875rem;font-weight:600;border:none;cursor:pointer;transition:var(--tr);text-decoration:none;line-height:1;white-space:nowrap}
.btn-primary{background:linear-gradient(135deg,var(--primary),var(--secondary));color:#fff;box-shadow:0 2px 8px rgba(14,165,233,.3)}
.btn-primary:hover{transform:translateY(-1px);box-shadow:0 4px 14px rgba(14,165,233,.4)}
.btn-danger{background:var(--danger);color:#fff}
.btn-danger:hover{background:#dc2626;transform:translateY(-1px)}
.btn-outline{background:transparent;color:var(--primary);border:1.5px solid var(--primary)}
.btn-outline:hover{background:var(--primary-light)}
.btn-secondary{background:var(--bg-muted);color:var(--text-2)}
.btn-secondary:hover{background:var(--border)}
.btn-sm{padding:.35rem .8rem;font-size:.8rem;border-radius:6px}
.btn-lg{padding:.875rem 2rem;font-size:1rem}
.btn-full{width:100%;justify-content:center}

/* ===================== FORMS ===================== */
.form-group{margin-bottom:1.25rem}
.form-label{display:block;font-size:.875rem;font-weight:600;color:var(--text-2);margin-bottom:.375rem}
.form-control{width:100%;padding:.65rem 1rem;border:1.5px solid var(--border);border-radius:8px;font-size:.925rem;font-family:inherit;color:var(--text-1);background:#fff;transition:var(--tr);outline:none}
.form-control:focus{border-color:var(--primary);box-shadow:0 0 0 3px rgba(14,165,233,.12)}
.form-control.is-invalid{border-color:var(--danger)}
.invalid-feedback{display:block;color:var(--danger);font-size:.8rem;margin-top:.3rem}
textarea.form-control{resize:vertical;min-height:100px}
select.form-control{cursor:pointer}

/* ===================== GRID ===================== */
.grid-2{display:grid;grid-template-columns:repeat(2,1fr);gap:1.5rem}
.grid-3{display:grid;grid-template-columns:repeat(3,1fr);gap:1.5rem}
.grid-4{display:grid;grid-template-columns:repeat(4,1fr);gap:1.25rem}

/* ===================== ALERTS ===================== */
.alert{padding:1rem 1.25rem;border-radius:var(--r);margin-bottom:1.5rem;font-size:.875rem}
.alert-info   {background:#eff6ff;border:1px solid #bfdbfe;color:#1e40af}
.alert-success{background:#f0fdf4;border:1px solid #bbf7d0;color:#166534}
.alert-warning{background:#fffbeb;border:1px solid #fde68a;color:#92400e}
.alert-danger {background:#fff1f2;border:1px solid #fecdd3;color:#9f1239}

/* ===================== SPINNER ===================== */
.spinner{display:inline-block;width:18px;height:18px;border:2px solid rgba(255,255,255,.35);border-top-color:#fff;border-radius:50%;animation:spin .65s linear infinite}
@keyframes spin{to{transform:rotate(360deg)}}

/* ===================== STAT CARD ===================== */
.stat-card{background:#fff;border:1px solid var(--border);border-radius:var(--r-lg);padding:1.5rem;text-align:center;box-shadow:var(--sh-sm)}
.stat-icon{font-size:2rem;margin-bottom:.625rem}
.stat-value{font-size:1.875rem;font-weight:800;color:var(--primary-dark);line-height:1;margin-bottom:.25rem}
.stat-label{font-size:.8rem;color:var(--text-3);font-weight:500}

/* ===================== EMPTY STATE ===================== */
.empty-state{text-align:center;padding:4rem 2rem}
.empty-icon{font-size:3.5rem;margin-bottom:1rem;opacity:.4}
.empty-state h3{font-size:1.2rem;font-weight:700;margin-bottom:.5rem}
.empty-state p{color:var(--text-3);margin-bottom:1.5rem}

/* ===================== SECTION ===================== */
.section{padding:5rem 0}
.section-label{display:inline-block;font-size:.78rem;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:var(--primary);background:var(--primary-light);padding:.3rem .9rem;border-radius:999px;margin-bottom:1rem}
.section-title{font-size:2.1rem;font-weight:800;letter-spacing:-.03em;margin-bottom:.875rem}
.section-sub{color:var(--text-2);font-size:1rem;max-width:540px;margin:0 auto}

/* ===================== PAGINATION ===================== */
.pagination{display:flex;gap:.5rem;justify-content:center;margin-top:2rem;flex-wrap:wrap}
.page-link{padding:.45rem .875rem;border:1px solid var(--border);border-radius:8px;font-size:.875rem;color:var(--text-2);transition:var(--tr)}
.page-link:hover,.page-link.active{background:var(--primary);color:#fff;border-color:var(--primary)}

/* ===================== FOOTER ===================== */
.site-footer{background:#0f172a;color:#94a3b8;padding:4rem 0 0;margin-top:5rem}
.footer-grid{max-width:1280px;margin:0 auto;padding:0 1.5rem 3rem;display:grid;grid-template-columns:1.5fr 1fr;gap:4rem}
.footer-logo{font-size:1.3rem;font-weight:800;color:#fff;margin-bottom:1rem}
.footer-logo span{color:var(--secondary)}
.footer-desc{font-size:.875rem;line-height:1.75;color:#64748b;max-width:300px}
.footer-links-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:1.5rem}
.footer-links-grid h4{font-size:.78rem;font-weight:700;color:#e2e8f0;margin-bottom:.875rem;text-transform:uppercase;letter-spacing:.06em}
.footer-links-grid a{display:block;font-size:.875rem;color:#64748b;padding:.2rem 0;transition:var(--tr)}
.footer-links-grid a:hover{color:var(--primary)}
.footer-bottom{border-top:1px solid #1e293b;padding:1.25rem 1.5rem;text-align:center;font-size:.8rem;color:#475569}

/* ===================== UTILS ===================== */
.d-flex{display:flex}.d-grid{display:grid}
.align-center{align-items:center}.justify-between{justify-content:space-between}.justify-center{justify-content:center}
.gap-1{gap:.5rem}.gap-2{gap:1rem}.gap-3{gap:1.5rem}.gap-4{gap:2rem}
.flex-wrap{flex-wrap:wrap}.flex-1{flex:1}
.mt-1{margin-top:.5rem}.mt-2{margin-top:1rem}.mt-3{margin-top:1.5rem}.mt-4{margin-top:2rem}
.mb-1{margin-bottom:.5rem}.mb-2{margin-bottom:1rem}.mb-3{margin-bottom:1.5rem}.mb-4{margin-bottom:2rem}
.text-center{text-align:center}.text-right{text-align:right}
.text-muted{color:var(--text-3)}.text-sm{font-size:.875rem}.text-xs{font-size:.8rem}
.font-bold{font-weight:700}.font-semibold{font-weight:600}
.w-full{width:100%}
.rounded{border-radius:var(--r)}.rounded-lg{border-radius:var(--r-lg)}
.bg-muted{background:var(--bg-muted)}
.border-l-primary{border-left:4px solid var(--primary)}
.border-l-muted{border-left:4px solid var(--border)}
.p-1{padding:.5rem}.p-2{padding:1rem}.p-3{padding:1.5rem}.p-4{padding:2rem}
.px-1{padding-left:.5rem;padding-right:.5rem}.px-2{padding-left:1rem;padding-right:1rem}
.py-1{padding-top:.5rem;padding-bottom:.5rem}

/* ===================== RESPONSIVE ===================== */
@media(max-width:1024px){.grid-4{grid-template-columns:repeat(2,1fr)}.footer-grid{grid-template-columns:1fr;gap:2rem}.footer-links-grid{grid-template-columns:repeat(2,1fr)}}
@media(max-width:768px){.nav-links{display:none}.grid-2,.grid-3,.grid-4{grid-template-columns:1fr}.pg-header h1{font-size:1.625rem}.section-title{font-size:1.625rem}.hero-grid{grid-template-columns:1fr!important}}
@media(max-width:480px){.nav-actions .btn-nav-outline{display:none}.footer-links-grid{grid-template-columns:1fr}}
    </style>
    @stack('styles')
</head>
<body>
    {{-- ===== NAVBAR ===== --}}
    <nav class="navbar" id="mainNav">
        <div class="nav-inner">
            <a href="/" class="nav-logo">🦷 <span>Dental</span>Care</a>

            <div class="nav-links">
                <a href="/" class="{{ request()->is('/') ? 'active' : '' }}">Beranda</a>
                <a href="/edukasi" class="{{ request()->is('edukasi*') ? 'active' : '' }}">Edukasi</a>
                @auth
                <a href="/booking" class="{{ request()->is('booking*') ? 'active' : '' }}">Booking</a>
                <a href="/antrian" class="{{ request()->is('antrian*') ? 'active' : '' }}">Antrian</a>
                <a href="/riwayat-perawatan" class="{{ request()->is('riwayat*') ? 'active' : '' }}">Riwayat</a>
                @endauth
            </div>

            <div class="nav-actions">
                @auth
                    @php
                        // Cek apakah ada notifikasi H-1 atau hari ini yang berstatus pending/confirmed
                        $notifCount = \App\Models\Reminder::where('user_id', Auth::id())
                            ->whereHas('booking', function($q) {
                                $q->whereIn('status', ['pending', 'confirmed'])
                                  ->where('booking_date', '>=', today());
                            })->count();
                    @endphp
                    
                    <a href="/pengingat" class="nav-notif" title="Pengingat Jadwal">
                        🔔
                        @if($notifCount > 0)
                            <span class="nav-notif-badge">{{ $notifCount }}</span>
                        @endif
                    </a>

                    <div class="nav-user">
                        <div class="nav-avatar-wrap" title="{{ Auth::user()->name }}">
                            @if(Auth::user()->avatar)
                                <img src="{{ Storage::url(Auth::user()->avatar) }}" alt="Avatar" class="nav-avatar-img">
                            @else
                                <div class="nav-avatar-init">{{ strtoupper(substr(Auth::user()->name,0,1)) }}</div>
                            @endif
                        </div>
                        <div class="nav-dropdown-wrapper">
                            <div class="nav-dropdown">
                                <a href="/profile">👤 Profil Saya</a>
                                <a href="/booking/riwayat">📋 Riwayat Booking</a>
                                <a href="/pengingat">🔔 Pengingat</a>
                                <div style="height:1px;background:var(--border);margin:.25rem 0;"></div>
                                <form action="/logout" method="POST">
                                    @csrf
                                    <button type="submit">🚪 Keluar</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @else
                    <a href="/login" class="btn-nav-outline">Masuk</a>
                    <a href="/register" class="btn-nav-solid">Daftar</a>
                @endauth
            </div>
        </div>
    </nav>

    {{-- ===== FLASH MESSAGES ===== --}}
    @if(session('success'))
        <div class="flash flash-ok" id="flash-msg">
            <span>✓ {{ session('success') }}</span>
            <button onclick="this.parentElement.remove()">✕</button>
        </div>
    @endif
    @if(session('error'))
        <div class="flash flash-err" id="flash-msg">
            <span>✕ {{ session('error') }}</span>
            <button onclick="this.parentElement.remove()">✕</button>
        </div>
    @endif

    {{-- ===== CONTENT ===== --}}
    <main class="main-wrap">
        @yield('content')
    </main>

    {{-- ===== FOOTER ===== --}}
    <footer class="site-footer">
        <div class="footer-grid">
            <div>
                <div class="footer-logo">🦷 <span>Dental</span>Care</div>
                <p class="footer-desc">Rawat senyum Anda, tingkatkan kualitas hidup. Platform manajemen kesehatan gigi yang modern dan terpercaya.</p>
            </div>
            <div class="footer-links-grid">
                <div>
                    <h4>Layanan</h4>
                    <a href="/booking">Booking</a>
                    <a href="/antrian">Antrian</a>
                    <a href="/riwayat-perawatan">Riwayat</a>
                    <a href="/pengingat">Pengingat</a>
                </div>
                <div>
                    <h4>Edukasi</h4>
                    <a href="/edukasi?category=perawatan">Perawatan</a>
                    <a href="/edukasi?category=penyakit">Penyakit</a>
                    <a href="/edukasi?category=tips">Tips</a>
                    <a href="/edukasi?category=nutrisi">Nutrisi</a>
                </div>
                <div>
                    <h4>Akun</h4>
                    @auth
                        <a href="/profile">Profil</a>
                        <a href="/booking/riwayat">Riwayat Booking</a>
                    @else
                        <a href="/login">Masuk</a>
                        <a href="/register">Daftar</a>
                    @endauth
                </div>
            </div>
        </div>
        <div class="footer-bottom">© {{ date('Y') }} DentalCare — Hak Cipta Dilindungi</div>
    </footer>

    @stack('scripts')
    <script>
    // Auto-hide flash
    setTimeout(()=>{const f=document.getElementById('flash-msg');if(f){f.style.opacity='0';setTimeout(()=>f.remove(),600)}},4500);
    // Navbar scroll
    window.addEventListener('scroll',()=>{document.getElementById('mainNav').classList.toggle('scrolled',window.scrollY>50)});
    </script>
</body>
</html>
