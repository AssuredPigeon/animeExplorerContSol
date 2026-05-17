@extends('layouts.app')
@section('title', 'Mis Favoritos – Anime Explorer')
@section('content')

<div class="page-header">
    <h1><i class="bi bi-heart-fill me-2" style="color:var(--red)"></i>Mis Favoritos</h1>
    <p>Animes guardados en tu lista personal</p>
</div>

@if(session('success'))
    <div style="background:rgba(16,185,129,.12);border:1px solid var(--green);color:var(--green);
                padding:.75rem 1rem;border-radius:10px;margin-bottom:1.5rem;font-size:.88rem;">
        <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
    </div>
@endif

@if($favorites->isEmpty())
    <div class="empty-state">
        <i class="bi bi-heart" style="color:var(--red);opacity:.35"></i>
        <p>Aún no tienes favoritos guardados.</p>
        <a href="{{ route('anime.index') }}" class="btn-search"
           style="display:inline-flex;align-items:center;gap:.4rem;text-decoration:none;padding:.7rem 1.4rem;border-radius:10px;margin-top:1.25rem;">
            <i class="bi bi-trophy-fill"></i> Explorar Top Anime
        </a>
    </div>
@else
    <div class="row g-3 fade-in-up">
        @foreach($favorites as $fav)
        @php
            $words = explode(' ', strtoupper($fav->title));
            $abbr  = implode('', array_map(fn($w) => mb_substr($w,0,1), array_slice($words,0,3)));
        @endphp
        <div class="col-6 col-sm-4 col-md-3 col-lg-2">
            <div class="anime-card" style="position:relative;">

                {{-- Remove button --}}
                <form method="POST" action="{{ route('favorites.destroy', $fav->mal_id) }}"
                      style="position:absolute;top:.5rem;right:.5rem;z-index:10;"
                      onsubmit="return confirm('¿Quitar de favoritos?')">
                    @csrf @method('DELETE')
                    <button type="submit"
                            style="background:rgba(0,0,0,.65);backdrop-filter:blur(6px);
                                   border:none;color:var(--red);border-radius:6px;
                                   padding:.3rem .5rem;cursor:pointer;font-size:.8rem;
                                   transition:background .2s;"
                            title="Quitar de favoritos">
                        <i class="bi bi-heart-fill"></i>
                    </button>
                </form>

                <a href="{{ route('anime.show', $fav->mal_id) }}" style="text-decoration:none;color:inherit;">
                    <div class="card-poster">
                        @if($fav->image_url)
                            <img src="{{ $fav->image_url }}" alt="{{ $fav->title }}" loading="lazy">
                        @else
                            <div class="card-poster-placeholder">{{ $abbr }}</div>
                        @endif
                        @if($fav->score)
                            <span class="score-chip">
                                <i class="bi bi-star-fill" style="font-size:.65rem"></i>
                                {{ number_format($fav->score, 1) }}
                            </span>
                        @endif
                    </div>
                    <div class="card-body-ae">
                        <div class="card-title-ae">{{ $fav->title }}</div>
                        <div class="card-meta">
                            @if($fav->type)<span class="meta-tag">{{ $fav->type }}</span>@endif
                            @if($fav->year)<span class="meta-tag">{{ $fav->year }}</span>@endif
                        </div>
                    </div>
                </a>
            </div>
        </div>
        @endforeach
    </div>

    <p style="color:var(--text-muted);font-size:.82rem;text-align:center;margin-top:2rem;">
        {{ $favorites->count() }} anime{{ $favorites->count() !== 1 ? 's' : '' }} en tu lista
    </p>
@endif

@endsection
