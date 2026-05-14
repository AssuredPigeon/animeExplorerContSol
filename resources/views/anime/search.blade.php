@extends('layouts.app')

@section('title', $query ? "Resultados: $query – Anime Explorer" : 'Búsqueda – Anime Explorer')

@section('content')

    {{-- Page Header --}}
    <div class="page-header">
        <h1><i class="bi bi-search me-2" style="color:var(--accent-light)"></i>Busca tu próximo anime</h1>
        <p>Explora miles de títulos del catálogo de MyAnimeList</p>
    </div>

    {{-- Search Form --}}
    <div class="search-wrapper">
        <form action="{{ route('anime.search') }}" method="GET" class="search-form" id="search-form">
            <input
                type="text"
                name="q"
                id="search-input"
                class="search-input"
                placeholder="Busca por nombre... (ej. Naruto, Demon Slayer)"
                value="{{ old('q', $query) }}"
                autocomplete="off"
                autofocus
            >
            <button type="submit" class="btn-search" id="btn-search">
                <i class="bi bi-search me-1"></i> Buscar
            </button>
        </form>
    </div>

    {{-- Results --}}
    @if($query !== '')
        <div class="mb-3" style="color:var(--text-muted); font-size:.88rem;">
            @if(count($animes) > 0)
                Mostrando resultados para <strong style="color:var(--text-primary)">{{ $query }}</strong>
            @else
                Sin resultados para <strong style="color:var(--text-primary)">{{ $query }}</strong>
            @endif
        </div>
    @endif

    @if($query !== '' && count($animes) === 0)
        <div class="empty-state">
            <i class="bi bi-emoji-frown"></i>
            <p>No encontramos nada para "<strong>{{ $query }}</strong>".<br>Intenta con otro nombre.</p>
        </div>

    @elseif(count($animes) > 0)

        <div class="d-flex flex-column gap-3 fade-in-up" id="results-list">
            @foreach($animes as $anime)
            @php
                $title  = $anime['title'] ?? 'Sin título';
                $imgUrl = $anime['images']['jpg']['image_url'] ?? null;
                $score  = $anime['score'] ?? null;
                $type   = $anime['type'] ?? '';
                $year   = $anime['year'] ?? ($anime['aired']['prop']['from']['year'] ?? '');
                $status = $anime['status'] ?? '';
                $malId  = $anime['mal_id'];
                $genres = collect($anime['genres'] ?? [])->pluck('name')->take(2)->implode(', ');

                $words  = explode(' ', strtoupper($title));
                $abbr   = implode('', array_map(fn($w) => mb_substr($w, 0, 1), array_slice($words, 0, 3)));
            @endphp

            <a href="{{ route('anime.show', $malId) }}" class="result-row">

                {{-- Thumbnail --}}
                @if($imgUrl)
                    <img src="{{ $imgUrl }}" alt="{{ $title }}" class="result-thumb" loading="lazy">
                @else
                    <div class="result-thumb-placeholder">{{ $abbr }}</div>
                @endif

                {{-- Info --}}
                <div class="result-info">
                    <div class="result-title">{{ $title }}</div>
                    <div class="result-sub">
                        {{ implode(' · ', array_filter([$type, $year ? (string)$year : null, $genres])) }}
                    </div>
                    @if($status)
                        <div class="result-sub mt-1">{{ $status }}</div>
                    @endif
                </div>

                {{-- Score --}}
                @if($score)
                    <div class="result-score">
                        <i class="bi bi-star-fill" style="font-size:.75rem; margin-right:.2rem"></i>
                        {{ number_format($score, 1) }}
                    </div>
                @endif

                <i class="bi bi-chevron-right result-arrow"></i>

            </a>
            @endforeach
        </div>

        {{-- Pagination --}}
        @php
            $hasNext  = $pagination['has_next_page'] ?? false;
            $lastPage = $pagination['last_visible_page'] ?? 1;
        @endphp

        @if($lastPage > 1)
        <div class="pagination-ae">
            @if($page > 1)
                <a href="{{ route('anime.search', ['q' => $query, 'page' => $page - 1]) }}" class="page-btn">
                    <i class="bi bi-chevron-left"></i> Anterior
                </a>
            @endif

            @php $start = max(1, $page - 2); $end = min($lastPage, $page + 2); @endphp
            @for($p = $start; $p <= $end; $p++)
                <a href="{{ route('anime.search', ['q' => $query, 'page' => $p]) }}"
                   class="page-btn {{ $p === $page ? 'current' : '' }}">{{ $p }}</a>
            @endfor

            @if($hasNext)
                <a href="{{ route('anime.search', ['q' => $query, 'page' => $page + 1]) }}" class="page-btn">
                    Siguiente <i class="bi bi-chevron-right"></i>
                </a>
            @endif
        </div>
        @endif

    @elseif($query === '')
        {{-- Initial state (no query yet) --}}
        <div class="empty-state">
            <i class="bi bi-stars"></i>
            <p>Escribe el nombre de un anime para comenzar a explorar.</p>
        </div>
    @endif

@endsection

@section('scripts')
<script>
    // Auto-submit on clear
    document.getElementById('search-input').addEventListener('input', function() {
        if (this.value.trim() === '' && '{{ $query }}' !== '') {
            window.location.href = '{{ route("anime.search") }}';
        }
    });
</script>
@endsection
