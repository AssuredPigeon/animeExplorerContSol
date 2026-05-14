@extends('layouts.app')

@section('title', 'Top Anime – Anime Explorer')

@section('content')

    {{-- Page Header --}}
    <div class="page-header">
        <h1><i class="bi bi-trophy-fill me-2" style="color:var(--gold)"></i>Top Anime</h1>
        <p>Los animes mejor calificados según MyAnimeList</p>
    </div>

    {{-- Error / empty state --}}
    @if(empty($animes))
        <div class="empty-state">
            <i class="bi bi-wifi-off"></i>
            <p>No se pudieron cargar los datos. Verifica tu conexión o intenta más tarde.</p>
        </div>
    @else

    {{-- Grid de cards --}}
    <div class="row g-3 fade-in-up" id="anime-grid">
        @foreach($animes as $anime)
        @php
            $title   = $anime['title'] ?? 'Sin título';
            $imgUrl  = $anime['images']['jpg']['image_url'] ?? null;
            $score   = $anime['score']    ?? null;
            $rank    = $anime['rank']     ?? null;
            $eps     = $anime['episodes'] ?? '?';
            $type    = $anime['type']     ?? '';
            $year    = $anime['year']     ?? ($anime['aired']['prop']['from']['year'] ?? '');
            $malId   = $anime['mal_id'];

            // Abbreviation for placeholder (first letters of each word, max 3)
            $words  = explode(' ', strtoupper($title));
            $abbr   = implode('', array_map(fn($w) => mb_substr($w, 0, 1), array_slice($words, 0, 3)));
        @endphp

        <div class="col-6 col-sm-4 col-md-3 col-lg-2">
            <a href="{{ route('anime.show', $malId) }}" class="anime-card">

                {{-- Poster --}}
                <div class="card-poster">
                    @if($imgUrl)
                        <img src="{{ $imgUrl }}" alt="{{ $title }}" loading="lazy">
                    @else
                        <div class="card-poster-placeholder">{{ $abbr }}</div>
                    @endif

                    {{-- Rank badge --}}
                    @if($rank)
                        <span class="rank-badge">#{{ $rank }}</span>
                    @endif

                    {{-- Score chip --}}
                    @if($score)
                        <span class="score-chip">
                            <i class="bi bi-star-fill" style="font-size:.65rem"></i>
                            {{ number_format($score, 1) }}
                        </span>
                    @endif
                </div>

                {{-- Body --}}
                <div class="card-body-ae">
                    <div class="card-title-ae">{{ $title }}</div>
                    <div class="card-meta">
                        @if($type)  <span class="meta-tag">{{ $type }}</span> @endif
                        @if($year)  <span class="meta-tag">{{ $year }}</span> @endif
                        @if($eps)   <span class="meta-tag">{{ $eps }} ep</span> @endif
                    </div>
                </div>

            </a>
        </div>
        @endforeach
    </div>

    {{-- Pagination --}}
    @php
        $hasNext = $pagination['has_next_page'] ?? false;
        $lastPage = $pagination['last_visible_page'] ?? 1;
    @endphp

    @if($lastPage > 1)
    <div class="pagination-ae">
        @if($page > 1)
            <a href="{{ route('anime.index', ['page' => $page - 1]) }}" class="page-btn">
                <i class="bi bi-chevron-left"></i> Anterior
            </a>
        @endif

        @php
            $start = max(1, $page - 2);
            $end   = min($lastPage, $page + 2);
        @endphp

        @for($p = $start; $p <= $end; $p++)
            <a href="{{ route('anime.index', ['page' => $p]) }}"
               class="page-btn {{ $p === $page ? 'current' : '' }}">{{ $p }}</a>
        @endfor

        @if($hasNext)
            <a href="{{ route('anime.index', ['page' => $page + 1]) }}" class="page-btn">
                Siguiente <i class="bi bi-chevron-right"></i>
            </a>
        @endif
    </div>
    @endif

    @endif {{-- end if animes --}}

@endsection
