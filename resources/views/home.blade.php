@extends('layouts.app')

@section('title', 'Anime Explorer – Descubre el mejor anime')

@section('head')
<style>
/* ====== HERO ====== */
.hero {
    position: relative;
    min-height: 88vh;
    display: flex;
    align-items: center;
    overflow: hidden;
    padding: 5rem 0 4rem;
}
.hero-bg {
    position: absolute; inset: 0;
    background:
        radial-gradient(ellipse 80% 70% at 50% -10%, rgba(124,58,237,.4) 0%, transparent 65%),
        radial-gradient(ellipse 55% 55% at 90% 70%, rgba(168,85,247,.22) 0%, transparent 60%),
        radial-gradient(ellipse 45% 45% at 5%  80%, rgba(79,70,229,.18)  0%, transparent 55%);
}
.orb { position:absolute; border-radius:50%; filter:blur(70px); opacity:.28; animation: floatOrb 9s ease-in-out infinite; }
.orb-1 { width:420px;height:420px; background:var(--accent);       top:-120px;left:-80px;  animation-delay:0s;  }
.orb-2 { width:320px;height:320px; background:var(--accent-light); bottom:-60px;right:8%;  animation-delay:3.5s;}
.orb-3 { width:220px;height:220px; background:#4f46e5;             top:45%;right:28%;      animation-delay:7s;  }
@keyframes floatOrb {
    0%,100% { transform:translate(0,0); }
    33%      { transform:translate(18px,-28px); }
    66%      { transform:translate(-14px,22px); }
}
.hero-content { position:relative; z-index:2; }
.hero-eyebrow {
    display:inline-flex; align-items:center; gap:.5rem;
    background:rgba(124,58,237,.18); border:1px solid rgba(124,58,237,.4);
    color:var(--accent-light); padding:.32rem .9rem; border-radius:20px;
    font-size:.78rem; font-weight:600; letter-spacing:.05em; margin-bottom:1.5rem;
}
.hero-title {
    font-size: clamp(2.4rem, 5.5vw, 4.2rem);
    font-weight:800; line-height:1.1; letter-spacing:-.03em; margin-bottom:1.2rem;
}
.hero-title .gt {
    background:linear-gradient(135deg,#fff 0%,var(--accent-light) 55%,#c084fc 100%);
    -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text;
}
.hero-sub { color:var(--text-muted); font-size:1.05rem; line-height:1.65; max-width:480px; margin-bottom:2rem; }

/* hero search */
.hsearch { position:relative; max-width:540px; }
.hsearch-input {
    width:100%; background:rgba(255,255,255,.06); backdrop-filter:blur(12px);
    border:1px solid rgba(124,58,237,.3); border-radius:14px;
    padding:.95rem 1.2rem .95rem 3.2rem; color:var(--text-primary);
    font-family:'Outfit',sans-serif; font-size:.98rem; outline:none;
    transition:border-color .25s, box-shadow .25s;
}
.hsearch-input::placeholder { color:var(--text-muted); }
.hsearch-input:focus { border-color:var(--accent); box-shadow:0 0 0 4px var(--accent-glow); }
.hsearch-ico { position:absolute; left:1.1rem; top:50%; transform:translateY(-50%); color:var(--text-muted); pointer-events:none; }
.hsearch-btn {
    position:absolute; right:.5rem; top:50%; transform:translateY(-50%);
    background:linear-gradient(135deg,var(--accent),var(--accent-light));
    color:#fff; border:none; border-radius:10px; padding:.55rem 1.1rem;
    font-family:'Outfit',sans-serif; font-weight:600; font-size:.88rem; cursor:pointer; transition:opacity .2s;
}
.hsearch-btn:hover { opacity:.88; }

/* cta ghost btn */
.btn-ghost {
    display:inline-flex; align-items:center; gap:.4rem;
    padding:.7rem 1.2rem; border-radius:10px; text-decoration:none;
    color:var(--text-muted); border:1px solid var(--border);
    font-weight:500; font-size:.92rem; transition:.22s;
}
.btn-ghost:hover { border-color:var(--accent); color:var(--text-primary); }

/* floating posters */
.hero-posters { position:relative; height:430px; }
.pf {
    position:absolute; border-radius:16px; overflow:hidden;
    box-shadow:0 28px 70px rgba(0,0,0,.65); transition:transform .35s ease;
}
.pf img { width:100%; height:100%; object-fit:cover; display:block; }
.pf:hover { transform:scale(1.05) !important; }
.pf-main  { width:185px;height:265px; top:50%;left:50%; transform:translate(-50%,-50%); z-index:3; border:2px solid var(--accent); }
.pf-left  { width:155px;height:225px; top:50%;left:4%;  transform:translateY(-50%) rotate(-9deg);  z-index:2; opacity:.82; }
.pf-right { width:155px;height:225px; top:50%;right:4%; transform:translateY(-50%) rotate(9deg);   z-index:2; opacity:.82; }
.pf-main::before {
    content:''; position:absolute; inset:-25px;
    background:radial-gradient(circle, var(--accent-glow) 0%, transparent 70%);
    z-index:-1;
}

/* ====== GENRE CHIPS ====== */
.gscroll { display:flex; gap:.55rem; overflow-x:auto; padding-bottom:.4rem; scrollbar-width:none; margin:2rem 0 2.5rem; }
.gscroll::-webkit-scrollbar { display:none; }
.gchip {
    background:rgba(124,58,237,.1); border:1px solid rgba(124,58,237,.22);
    color:var(--accent-light); padding:.3rem .85rem; border-radius:20px;
    font-size:.78rem; font-weight:500; white-space:nowrap; text-decoration:none; transition:.2s;
}
.gchip:hover { background:rgba(124,58,237,.28); border-color:var(--accent); color:#fff; }

/* ====== STATS ====== */
.stats-band {
    background:linear-gradient(135deg,rgba(124,58,237,.1),rgba(168,85,247,.06));
    border:1px solid var(--border); border-radius:20px;
    display:grid; grid-template-columns:repeat(auto-fit,minmax(150px,1fr));
    gap:1.5rem; padding:2.2rem 2.5rem; margin-bottom:3.5rem; text-align:center;
}
.sbn { font-size:2rem; font-weight:800;
    background:linear-gradient(135deg,#fff,var(--accent-light));
    -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text; line-height:1;
}
.sbl { font-size:.78rem; color:var(--text-muted); margin-top:.4rem; font-weight:500; }

/* ====== SECTION HEADER ====== */
.sec-hdr { display:flex; align-items:center; justify-content:space-between; margin-bottom:1.4rem; }
.sec-title { font-size:1.35rem; font-weight:700; display:flex; align-items:center; gap:.55rem; }
.sec-line { width:3px; height:1.25em; background:linear-gradient(to bottom,var(--accent),var(--accent-light)); border-radius:3px; flex-shrink:0; }
.sec-link { color:var(--accent-light); font-size:.84rem; font-weight:500; text-decoration:none; display:flex; align-items:center; gap:.3rem; transition:gap .2s; }
.sec-link:hover { gap:.6rem; }

/* ====== CTA FOOTER ====== */
.cta-band {
    text-align:center; padding:5rem 1rem 2rem; position:relative;
}
.cta-band::before {
    content:''; position:absolute; inset:0;
    background:radial-gradient(ellipse 60% 80% at 50% 100%,rgba(124,58,237,.15) 0%,transparent 70%);
    pointer-events:none;
}
</style>
@endsection

@section('content')
@php
    $h1 = $featured[0] ?? null;
    $h2 = $featured[1] ?? null;
    $h3 = $featured[2] ?? null;
@endphp

{{-- ===== HERO ===== --}}
<section class="hero" style="margin:-1.5rem calc(-50vw + 50%) 0; padding-left:calc(50vw - 50%); padding-right:calc(50vw - 50%);">
    <div class="hero-bg"></div>
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>
    <div class="orb orb-3"></div>

    <div class="container">
        <div class="row align-items-center g-5">

            <div class="col-lg-6 hero-content">
                <div class="hero-eyebrow">
                    <i class="bi bi-lightning-charge-fill"></i>
                    Datos en tiempo real · MyAnimeList
                </div>
                <h1 class="hero-title">
                    Descubre tu próximo<br>
                    <span class="gt">anime favorito</span>
                </h1>
                <p class="hero-sub">
                    Explora miles de títulos, consulta rankings actualizados y encuentra
                    tu próxima obsesión animada — todo en un solo lugar.
                </p>

                <form action="{{ route('anime.search') }}" method="GET" class="hsearch">
                    <i class="bi bi-search hsearch-ico"></i>
                    <input type="text" name="q" id="hero-search-q"
                           class="hsearch-input"
                           placeholder="Ej. Attack on Titan, Naruto…"
                           autocomplete="off">
                    <button type="submit" class="hsearch-btn">Buscar</button>
                </form>

                <div class="d-flex gap-3 mt-4" style="flex-wrap:wrap;">
                    <a href="{{ route('anime.index') }}" class="btn-search"
                       style="display:inline-flex;align-items:center;gap:.4rem;text-decoration:none;padding:.7rem 1.4rem;border-radius:10px;">
                        <i class="bi bi-trophy-fill"></i> Top Anime
                    </a>
                    <a href="{{ route('about') }}" class="btn-ghost">
                        <i class="bi bi-info-circle"></i> Acerca de
                    </a>
                </div>
            </div>

            {{-- Floating posters --}}
            <div class="col-lg-6 d-none d-lg-flex justify-content-center">
                <div class="hero-posters">
                    @if($h2 && ($h2['images']['jpg']['image_url'] ?? null))
                    <div class="pf pf-left">
                        <img src="{{ $h2['images']['jpg']['image_url'] }}" alt="{{ $h2['title'] }}" width="155" height="225">
                    </div>
                    @endif

                    @if($h1 && ($h1['images']['jpg']['large_image_url'] ?? $h1['images']['jpg']['image_url'] ?? null))
                    <div class="pf pf-main">
                        <img src="{{ $h1['images']['jpg']['large_image_url'] ?? $h1['images']['jpg']['image_url'] }}" alt="{{ $h1['title'] }}" width="185" height="265">
                    </div>
                    @endif

                    @if($h3 && ($h3['images']['jpg']['image_url'] ?? null))
                    <div class="pf pf-right">
                        <img src="{{ $h3['images']['jpg']['image_url'] }}" alt="{{ $h3['title'] }}" width="155" height="225">
                    </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</section>

{{-- ===== GENRE CHIPS ===== --}}
<div class="gscroll">
    @foreach(['Acción','Aventura','Romance','Comedia','Drama','Fantasy','Sci-Fi','Horror','Misterio','Deportes','Slice of Life','Sobrenatural','Psicológico','Shounen'] as $g)
        <a href="{{ route('anime.search', ['q' => $g]) }}" class="gchip">{{ $g }}</a>
    @endforeach
</div>

{{-- ===== STATS ===== --}}
<div class="stats-band">
    <div><div class="sbn">+24K</div><div class="sbl">Títulos en la base de datos</div></div>
    <div><div class="sbn">Live</div><div class="sbl">Datos actualizados via Jikan API</div></div>
    <div><div class="sbn">Top 500</div><div class="sbl">Rankings de MyAnimeList</div></div>
    <div><div class="sbn">Gratis</div><div class="sbl">Sin registro, sin costo</div></div>
</div>

{{-- ===== FEATURED ===== --}}
<div class="sec-hdr">
    <div class="sec-title">
        <div class="sec-line"></div>
        <i class="bi bi-trophy-fill" style="color:var(--gold)"></i>
        Top Destacados
    </div>
    <a href="{{ route('anime.index') }}" class="sec-link">Ver todos <i class="bi bi-arrow-right"></i></a>
</div>

<div class="row g-3 fade-in-up">
    @foreach($featured as $anime)
    @php
        $title  = $anime['title'] ?? 'Sin título';
        $imgUrl = $anime['images']['jpg']['image_url'] ?? null;
        $score  = $anime['score'] ?? null;
        $rank   = $anime['rank'] ?? null;
        $type   = $anime['type'] ?? '';
        $year   = $anime['year'] ?? ($anime['aired']['prop']['from']['year'] ?? '');
        $malId  = $anime['mal_id'];
        $words  = explode(' ', strtoupper($title));
        $abbr   = implode('', array_map(fn($w) => mb_substr($w,0,1), array_slice($words,0,3)));
    @endphp
    <div class="col-6 col-sm-4 col-md-2">
        <a href="{{ route('anime.show', $malId) }}" class="anime-card">
            <div class="card-poster">
                @if($imgUrl)
                    <img src="{{ $imgUrl }}" alt="{{ $title }}" loading="lazy">
                @else
                    <div class="card-poster-placeholder">{{ $abbr }}</div>
                @endif
                @if($rank)<span class="rank-badge">#{{ $rank }}</span>@endif
                @if($score)
                    <span class="score-chip">
                        <i class="bi bi-star-fill" style="font-size:.65rem"></i>
                        {{ number_format($score,1) }}
                    </span>
                @endif
            </div>
            <div class="card-body-ae">
                <div class="card-title-ae">{{ $title }}</div>
                <div class="card-meta">
                    @if($type)<span class="meta-tag">{{ $type }}</span>@endif
                    @if($year)<span class="meta-tag">{{ $year }}</span>@endif
                </div>
            </div>
        </a>
    </div>
    @endforeach
</div>

{{-- ===== TRENDING ===== --}}
@if(count($trending) > 0)
<div class="sec-hdr mt-5">
    <div class="sec-title">
        <div class="sec-line"></div>
        <i class="bi bi-fire" style="color:#f97316"></i>
        Tendencias
    </div>
</div>
<div class="row g-3 fade-in-up">
    @foreach($trending as $anime)
    @php
        $title  = $anime['title'] ?? 'Sin título';
        $imgUrl = $anime['images']['jpg']['image_url'] ?? null;
        $score  = $anime['score'] ?? null;
        $rank   = $anime['rank'] ?? null;
        $type   = $anime['type'] ?? '';
        $year   = $anime['year'] ?? ($anime['aired']['prop']['from']['year'] ?? '');
        $malId  = $anime['mal_id'];
        $words  = explode(' ', strtoupper($title));
        $abbr   = implode('', array_map(fn($w) => mb_substr($w,0,1), array_slice($words,0,3)));
    @endphp
    <div class="col-6 col-sm-4 col-md-2">
        <a href="{{ route('anime.show', $malId) }}" class="anime-card">
            <div class="card-poster">
                @if($imgUrl)
                    <img src="{{ $imgUrl }}" alt="{{ $title }}" loading="lazy">
                @else
                    <div class="card-poster-placeholder">{{ $abbr }}</div>
                @endif
                @if($rank)<span class="rank-badge">#{{ $rank }}</span>@endif
                @if($score)
                    <span class="score-chip">
                        <i class="bi bi-star-fill" style="font-size:.65rem"></i>
                        {{ number_format($score,1) }}
                    </span>
                @endif
            </div>
            <div class="card-body-ae">
                <div class="card-title-ae">{{ $title }}</div>
                <div class="card-meta">
                    @if($type)<span class="meta-tag">{{ $type }}</span>@endif
                    @if($year)<span class="meta-tag">{{ $year }}</span>@endif
                </div>
            </div>
        </a>
    </div>
    @endforeach
</div>
@endif

{{-- ===== CTA ===== --}}
<div class="cta-band">
    <h2 style="font-size:2rem;font-weight:800;margin-bottom:.75rem;">¿Listo para explorar?</h2>
    <p style="color:var(--text-muted);margin-bottom:2rem;">Busca entre miles de títulos y descubre el anime perfecto para ti.</p>
    <a href="{{ route('anime.search') }}" class="btn-search"
       style="display:inline-flex;align-items:center;gap:.5rem;text-decoration:none;font-size:1rem;padding:.9rem 2rem;">
        <i class="bi bi-search"></i> Comenzar búsqueda
    </a>
</div>
@endsection
