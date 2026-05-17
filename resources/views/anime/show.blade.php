@extends('layouts.app')

@section('title', ($anime['title'] ?? 'Detalle') . ' – Anime Explorer')

@section('head')
<style>
/* ====== BACKDROP HERO ====== */
.show-backdrop {
    position: relative;
    margin: -1rem calc(-50vw + 50%) 0;
    padding: 0 calc(50vw - 50%);
    min-height: 340px;
    display: flex;
    align-items: flex-end;
    overflow: hidden;
}
.show-backdrop-img {
    position: absolute; inset: 0;
    background-size: cover;
    background-position: center top;
    filter: blur(22px) brightness(.35) saturate(1.4);
    transform: scale(1.08);
}
.show-backdrop-grad {
    position: absolute; inset: 0;
    background: linear-gradient(
        to bottom,
        rgba(13,13,26,.2) 0%,
        rgba(13,13,26,.55) 60%,
        var(--bg-base) 100%
    );
}
.show-backdrop-content {
    position: relative; z-index: 2;
    width: 100%; padding: 2rem calc(50vw - 600px + 12px);
    padding-bottom: 0;
}
@media (max-width: 1200px) {
    .show-backdrop-content { padding-left: 1rem; padding-right: 1rem; }
}

/* ====== HERO LAYOUT ====== */
.show-hero {
    display: grid;
    grid-template-columns: 220px 1fr;
    gap: 2rem;
    align-items: flex-end;
}
@media (max-width: 768px) {
    .show-hero { grid-template-columns: 1fr; align-items: center; }
    .show-poster-wrap { max-width: 180px; margin: 0 auto; }
}

/* ====== POSTER ====== */
.show-poster-wrap {
    position: relative;
}
.show-poster-wrap img,
.show-poster-placeholder {
    width: 100%; border-radius: 14px;
    display: block;
    box-shadow: 0 24px 64px rgba(0,0,0,.7);
    border: 2px solid rgba(124,58,237,.4);
}
.show-poster-placeholder {
    aspect-ratio: 3/4;
    background: linear-gradient(135deg, var(--accent), #1e0a3c);
    display: flex; align-items: center; justify-content: center;
    font-size: 3rem; font-weight: 800; color: rgba(255,255,255,.9);
}
.show-score-ring {
    position: absolute;
    bottom: -14px; right: -14px;
    width: 60px; height: 60px;
    background: linear-gradient(135deg, var(--gold), #f97316);
    border-radius: 50%;
    display: flex; flex-direction: column;
    align-items: center; justify-content: center;
    box-shadow: 0 8px 24px rgba(245,158,11,.45);
    border: 3px solid var(--bg-base);
}
.show-score-ring .snum { font-size: .95rem; font-weight: 800; color: #fff; line-height: 1; }
.show-score-ring .slbl { font-size: .5rem; color: rgba(255,255,255,.75); text-transform: uppercase; letter-spacing: .06em; }

/* ====== INFO PANEL ====== */
.show-info { padding-bottom: 1.5rem; }
.show-title {
    font-size: clamp(1.6rem, 4vw, 2.6rem);
    font-weight: 800; line-height: 1.15; letter-spacing: -.025em;
    margin-bottom: .4rem;
}
.show-title-jp { color: var(--text-muted); font-size: .9rem; margin-bottom: 1rem; }

.show-badges { display: flex; flex-wrap: wrap; gap: .5rem; margin-bottom: 1.1rem; }
.sbadge {
    font-size: .73rem; font-weight: 600; padding: .28rem .72rem;
    border-radius: 6px; border: 1px solid;
}
.sbadge-score  { color: var(--gold);         border-color: var(--gold);         background: rgba(245,158,11,.1);  }
.sbadge-status { color: var(--green);        border-color: var(--green);        background: rgba(16,185,129,.1);  }
.sbadge-type   { color: var(--accent-light); border-color: var(--accent);       background: var(--accent-glow);   }
.sbadge-rank   { color: var(--text-muted);   border-color: var(--border);       background: transparent;          }

/* ====== STATS ====== */
.show-stats {
    display: flex; flex-wrap: wrap; gap: .6rem; margin-bottom: 1.1rem;
}
.sstat {
    background: rgba(255,255,255,.05);
    border: 1px solid var(--border);
    border-radius: 10px; padding: .6rem .9rem;
    text-align: center; flex: 1 1 90px;
}
.sstat-label { font-size: .6rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: .08em; }
.sstat-val   { font-size: .95rem; font-weight: 700; margin-top: .15rem; }

/* ====== GENRES ====== */
.genre-pills { display: flex; flex-wrap: wrap; gap: .4rem; }
.gpill {
    background: var(--accent-glow); border: 1px solid var(--accent);
    color: var(--accent-light); padding: .22rem .6rem;
    border-radius: 20px; font-size: .73rem; font-weight: 500;
    text-decoration: none; transition: background .2s;
}
.gpill:hover { background: rgba(124,58,237,.35); color: #fff; }

/* ====== BODY SECTIONS ====== */
.show-body { display: grid; grid-template-columns: 1fr 320px; gap: 1.5rem; margin-top: 2rem; }
@media (max-width: 900px) { .show-body { grid-template-columns: 1fr; } }

.info-box {
    background: var(--bg-card); border: 1px solid var(--border);
    border-radius: 14px; padding: 1.3rem;
}
.info-box h3 {
    font-size: .75rem; color: var(--text-muted);
    text-transform: uppercase; letter-spacing: .1em; margin-bottom: .75rem;
    display: flex; align-items: center; gap: .4rem;
}
.synopsis-text { font-size: .9rem; line-height: 1.75; color: var(--text-primary); }

/* sidebar rows */
.detail-row {
    display: flex; justify-content: space-between; align-items: flex-start;
    padding: .55rem 0; border-bottom: 1px solid rgba(255,255,255,.05);
    gap: 1rem;
}
.detail-row:last-child { border-bottom: none; }
.dr-label { font-size: .78rem; color: var(--text-muted); flex-shrink: 0; }
.dr-val   { font-size: .82rem; font-weight: 500; text-align: right; }

/* trailer */
.trailer-wrap {
    position: relative; padding-bottom: 56.25%;
    border-radius: 10px; overflow: hidden;
}
.trailer-wrap iframe {
    position: absolute; inset: 0; width: 100%; height: 100%; border: none;
}

/* back btn */
.btn-back-show {
    display: inline-flex; align-items: center; gap: .4rem;
    color: var(--text-muted); text-decoration: none; font-size: .88rem;
    font-weight: 500; transition: color .2s; margin-bottom: 1.25rem;
}
.btn-back-show:hover { color: var(--accent-light); }

/* external link */
.btn-mal {
    display: inline-flex; align-items: center; gap: .4rem;
    color: var(--accent-light); font-size: .85rem; font-weight: 500;
    text-decoration: none; border: 1px solid rgba(124,58,237,.35);
    padding: .45rem .9rem; border-radius: 8px; transition: .2s; margin-top: .5rem;
}
.btn-mal:hover { border-color: var(--accent); background: var(--accent-glow); color: #fff; }
</style>
@endsection

@section('content')
@php
    $title      = $anime['title']          ?? 'Sin título';
    $titleJp    = $anime['title_japanese'] ?? '';
    $imgLg      = $anime['images']['jpg']['large_image_url'] ?? $anime['images']['jpg']['image_url'] ?? null;
    $imgSm      = $anime['images']['jpg']['image_url'] ?? null;
    $score      = $anime['score']          ?? null;
    $rank       = $anime['rank']           ?? null;
    $popularity = $anime['popularity']     ?? null;
    $members    = $anime['members']        ?? null;
    $favorites  = $anime['favorites']      ?? null;
    $status     = $anime['status']         ?? '—';
    $type       = $anime['type']           ?? '—';
    $eps        = $anime['episodes']       ?? '?';
    $duration   = $anime['duration']       ?? '—';
    $rating     = $anime['rating']         ?? '—';
    $year       = $anime['year']           ?? ($anime['aired']['prop']['from']['year'] ?? '—');
    $airedStr   = $anime['aired']['string'] ?? '—';
    $studios    = collect($anime['studios'] ?? [])->pluck('name')->implode(', ') ?: '—';
    $producers  = collect($anime['producers'] ?? [])->pluck('name')->take(3)->implode(', ') ?: '—';
    $source     = $anime['source']         ?? '—';
    $synopsis   = $anime['synopsis']       ?? null;
    $genres     = $anime['genres']         ?? [];
    $themes     = $anime['themes']         ?? [];
    $demographics = $anime['demographics'] ?? [];
    $allGenres  = array_merge($genres, $themes, $demographics);
    $trailer    = $anime['trailer']['embed_url'] ?? null;
    $malUrl     = $anime['url']            ?? '#';
    $words      = explode(' ', strtoupper($title));
    $abbr       = implode('', array_map(fn($w) => mb_substr($w, 0, 1), array_slice($words, 0, 3)));
@endphp

{{-- ===== BACKDROP HERO ===== --}}
<div class="show-backdrop">
    @if($imgLg ?? $imgSm)
    <div class="show-backdrop-img"
         style="background-image:url('{{ $imgLg ?? $imgSm }}')"></div>
    @endif
    <div class="show-backdrop-grad"></div>

    <div class="show-backdrop-content container">

        <a href="javascript:history.back()" class="btn-back-show">
            <i class="bi bi-arrow-left"></i> Volver
        </a>

        <div class="show-hero">

            {{-- Poster --}}
            <div class="show-poster-wrap fade-in-up">
                @if($imgLg)
                    <img src="{{ $imgLg }}" alt="{{ $title }}">
                @else
                    <div class="show-poster-placeholder">{{ $abbr }}</div>
                @endif
                @if($score)
                <div class="show-score-ring">
                    <span class="snum">{{ number_format($score, 1) }}</span>
                    <span class="slbl">Score</span>
                </div>
                @endif
            </div>

            {{-- Info --}}
            <div class="show-info fade-in-up">
                <h1 class="show-title">{{ $title }}</h1>
                @if($titleJp)
                    <p class="show-title-jp">{{ $titleJp }}</p>
                @endif

                <div class="show-badges">
                    @if($score)
                        <span class="sbadge sbadge-score"><i class="bi bi-star-fill me-1"></i>{{ number_format($score,1) }}</span>
                    @endif
                    @if($status && $status !== '—')
                        <span class="sbadge sbadge-status">{{ $status }}</span>
                    @endif
                    @if($type && $type !== '—')
                        <span class="sbadge sbadge-type">{{ $type }}</span>
                    @endif
                    @if($rank)
                        <span class="sbadge sbadge-rank">#{{ $rank }} Ranking</span>
                    @endif
                </div>

                <div class="show-stats">
                    <div class="sstat">
                        <div class="sstat-label">Episodios</div>
                        <div class="sstat-val">{{ $eps }}</div>
                    </div>
                    <div class="sstat">
                        <div class="sstat-label">Año</div>
                        <div class="sstat-val">{{ $year }}</div>
                    </div>
                    @if($popularity)
                    <div class="sstat">
                        <div class="sstat-label">Popularidad</div>
                        <div class="sstat-val">#{{ number_format($popularity) }}</div>
                    </div>
                    @endif
                    @if($members)
                    <div class="sstat">
                        <div class="sstat-label">Miembros</div>
                        <div class="sstat-val">{{ number_format($members / 1000, 0) }}K</div>
                    </div>
                    @endif
                    @if($favorites)
                    <div class="sstat">
                        <div class="sstat-label">Favoritos</div>
                        <div class="sstat-val">{{ number_format($favorites / 1000, 0) }}K</div>
                    </div>
                    @endif
                </div>

                @if(count($allGenres) > 0)
                <div class="genre-pills mb-3">
                    @foreach($allGenres as $g)
                        <a href="{{ route('anime.search', ['q' => $g['name']]) }}" class="gpill">{{ $g['name'] }}</a>
                    @endforeach
                </div>
                @endif

                <a href="{{ $malUrl }}" target="_blank" rel="noopener" class="btn-mal">
                    <i class="bi bi-box-arrow-up-right"></i> Ver en MyAnimeList
                </a>
            </div>
        </div>
    </div>
</div>

{{-- ===== BODY ===== --}}
<div class="container">
<div class="show-body">

    {{-- Main column --}}
    <div class="d-flex flex-column gap-3">

        {{-- Synopsis --}}
        @if($synopsis)
        <div class="info-box">
            <h3><i class="bi bi-text-paragraph"></i> Sinopsis</h3>
            <p class="synopsis-text">{{ $synopsis }}</p>
        </div>
        @endif

        {{-- Trailer --}}
        @if($trailer)
        <div class="info-box">
            <h3><i class="bi bi-play-circle-fill"></i> Tráiler</h3>
            <div class="trailer-wrap">
                <iframe src="{{ $trailer }}" allowfullscreen loading="lazy"></iframe>
            </div>
        </div>
        @endif

    </div>

    {{-- Sidebar --}}
    <div class="d-flex flex-column gap-3">

        <div class="info-box">
            <h3><i class="bi bi-info-circle-fill"></i> Información</h3>
            <div class="detail-row">
                <span class="dr-label">Tipo</span>
                <span class="dr-val">{{ $type }}</span>
            </div>
            <div class="detail-row">
                <span class="dr-label">Duración</span>
                <span class="dr-val">{{ Str::before($duration, ' per') }}</span>
            </div>
            <div class="detail-row">
                <span class="dr-label">Estado</span>
                <span class="dr-val">{{ $status }}</span>
            </div>
            <div class="detail-row">
                <span class="dr-label">Emisión</span>
                <span class="dr-val">{{ $airedStr }}</span>
            </div>
            <div class="detail-row">
                <span class="dr-label">Estudio</span>
                <span class="dr-val">{{ $studios }}</span>
            </div>
            <div class="detail-row">
                <span class="dr-label">Productores</span>
                <span class="dr-val">{{ $producers }}</span>
            </div>
            <div class="detail-row">
                <span class="dr-label">Fuente</span>
                <span class="dr-val">{{ $source }}</span>
            </div>
            <div class="detail-row">
                <span class="dr-label">Clasificación</span>
                <span class="dr-val" style="font-size:.75rem">{{ $rating }}</span>
            </div>
            @if($rank)
            <div class="detail-row">
                <span class="dr-label">Ranking MAL</span>
                <span class="dr-val" style="color:var(--gold)">#{{ number_format($rank) }}</span>
            </div>
            @endif
        </div>

        {{-- Genres sidebar --}}
        @if(count($allGenres) > 0)
        <div class="info-box">
            <h3><i class="bi bi-tags-fill"></i> Géneros</h3>
            <div class="genre-pills">
                @foreach($allGenres as $g)
                    <a href="{{ route('anime.search', ['q' => $g['name']]) }}" class="gpill">{{ $g['name'] }}</a>
                @endforeach
            </div>
        </div>
        @endif

    </div>
</div>
</div>

@endsection
