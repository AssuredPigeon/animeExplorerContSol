@extends('layouts.app')

@section('title', ($anime['title'] ?? 'Detalle') . ' – Anime Explorer')

@section('content')
@php
    $title      = $anime['title']          ?? 'Sin título';
    $titleJp    = $anime['title_japanese'] ?? '';
    $imgUrl     = $anime['images']['jpg']['large_image_url'] ?? $anime['images']['jpg']['image_url'] ?? null;
    $score      = $anime['score']          ?? null;
    $rank       = $anime['rank']           ?? null;
    $popularity = $anime['popularity']     ?? null;
    $members    = $anime['members']        ?? null;
    $status     = $anime['status']         ?? '—';
    $type       = $anime['type']           ?? '—';
    $eps        = $anime['episodes']       ?? '?';
    $duration   = $anime['duration']       ?? '—';
    $year       = $anime['year']           ?? ($anime['aired']['prop']['from']['year'] ?? '—');
    $studios    = collect($anime['studios'] ?? [])->pluck('name')->implode(', ') ?: '—';
    $source     = $anime['source']         ?? '—';
    $synopsis   = $anime['synopsis']       ?? null;
    $genres     = $anime['genres']         ?? [];
    $themes     = $anime['themes']         ?? [];
    $allGenres  = array_merge($genres, $themes);
    $trailer    = $anime['trailer']['embed_url'] ?? null;
    $malUrl     = $anime['url']            ?? '#';

    $words      = explode(' ', strtoupper($title));
    $abbr       = implode('', array_map(fn($w) => mb_substr($w, 0, 1), array_slice($words, 0, 3)));
@endphp

    {{-- Back button --}}
    <a href="javascript:history.back()" class="btn-back">
        <i class="bi bi-arrow-left"></i> Volver
    </a>

    {{-- Hero section --}}
    <div class="detail-hero fade-in-up">

        {{-- Poster --}}
        <div class="detail-poster">
            @if($imgUrl)
                <img src="{{ $imgUrl }}" alt="{{ $title }}">
            @else
                <div style="aspect-ratio:3/4; background:linear-gradient(135deg,var(--accent),#1e0a3c);
                            display:flex; align-items:center; justify-content:center;
                            font-size:3rem; font-weight:800; color:rgba(255,255,255,.9)">
                    {{ $abbr }}
                </div>
            @endif
        </div>

        {{-- Info panel --}}
        <div class="detail-info">

            <div>
                <h1 class="detail-title">{{ $title }}</h1>
                @if($titleJp)
                    <p class="detail-title-jp">{{ $titleJp }}</p>
                @endif
            </div>

            {{-- Badges --}}
            <div class="detail-badges">
                @if($score)
                    <span class="badge-ae badge-score">
                        <i class="bi bi-star-fill me-1"></i>{{ number_format($score, 1) }} Score
                    </span>
                @endif
                @if($status)
                    <span class="badge-ae badge-status">{{ $status }}</span>
                @endif
                @if($type)
                    <span class="badge-ae badge-type">{{ $type }}</span>
                @endif
                @if($rank)
                    <span class="badge-ae" style="color:var(--text-muted); border-color:var(--border); background:transparent">
                        #{{ $rank }} Ranking
                    </span>
                @endif
            </div>

            {{-- Stats grid --}}
            <div class="detail-stats">
                <div class="stat-box">
                    <div class="stat-label">Episodios</div>
                    <div class="stat-value">{{ $eps }}</div>
                </div>
                <div class="stat-box">
                    <div class="stat-label">Duración</div>
                    <div class="stat-value" style="font-size:.8rem">{{ Str::before($duration, ' per') }}</div>
                </div>
                <div class="stat-box">
                    <div class="stat-label">Año</div>
                    <div class="stat-value">{{ $year }}</div>
                </div>
                @if($popularity)
                <div class="stat-box">
                    <div class="stat-label">Popularidad</div>
                    <div class="stat-value">#{{ number_format($popularity) }}</div>
                </div>
                @endif
                @if($members)
                <div class="stat-box">
                    <div class="stat-label">Miembros MAL</div>
                    <div class="stat-value" style="font-size:.85rem">{{ number_format($members) }}</div>
                </div>
                @endif
                <div class="stat-box">
                    <div class="stat-label">Fuente</div>
                    <div class="stat-value" style="font-size:.8rem">{{ $source }}</div>
                </div>
            </div>

            {{-- Studio --}}
            @if($studios !== '—')
            <div>
                <span style="font-size:.75rem; color:var(--text-muted); text-transform:uppercase; letter-spacing:.08em">Estudio</span>
                <div style="font-weight:600; margin-top:.2rem">{{ $studios }}</div>
            </div>
            @endif

            {{-- Genres --}}
            @if(count($allGenres) > 0)
            <div class="d-flex flex-wrap gap-2">
                @foreach($allGenres as $g)
                    <span class="genre-pill">{{ $g['name'] }}</span>
                @endforeach
            </div>
            @endif

            {{-- MAL link --}}
            <div>
                <a href="{{ $malUrl }}" target="_blank" rel="noopener"
                   style="display:inline-flex; align-items:center; gap:.4rem;
                          color:var(--accent-light); font-size:.88rem; text-decoration:none; font-weight:500">
                    <i class="bi bi-box-arrow-up-right"></i> Ver en MyAnimeList
                </a>
            </div>

        </div>
    </div>

    {{-- Synopsis --}}
    @if($synopsis)
    <div class="synopsis-box mt-4 fade-in-up">
        <h3>Sinopsis</h3>
        <p>{{ $synopsis }}</p>
    </div>
    @endif

    {{-- Trailer --}}
    @if($trailer)
    <div class="mt-4 fade-in-up">
        <div class="synopsis-box">
            <h3 class="mb-3">Tráiler</h3>
            <div style="position:relative; padding-bottom:56.25%; border-radius:10px; overflow:hidden;">
                <iframe src="{{ $trailer }}"
                        style="position:absolute; inset:0; width:100%; height:100%; border:none;"
                        allowfullscreen loading="lazy"></iframe>
            </div>
        </div>
    </div>
    @endif

@endsection
