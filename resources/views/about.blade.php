@extends('layouts.app')

@section('title', 'Acerca de – Anime Explorer')

@section('head')
<style>
.about-hero {
    position: relative;
    text-align: center;
    padding: 5rem 1rem 3rem;
    overflow: hidden;
}
.about-hero::before {
    content: '';
    position: absolute; inset: 0;
    background: radial-gradient(ellipse 70% 80% at 50% 0%, rgba(124,58,237,.35) 0%, transparent 70%);
    pointer-events: none;
}
.about-hero-badge {
    display: inline-flex; align-items: center; gap: .5rem;
    background: rgba(124,58,237,.15); border: 1px solid rgba(124,58,237,.35);
    color: var(--accent-light); padding: .32rem .9rem; border-radius: 20px;
    font-size: .78rem; font-weight: 600; letter-spacing: .05em; margin-bottom: 1.5rem;
}
.about-title {
    font-size: clamp(2rem, 5vw, 3.4rem);
    font-weight: 800; letter-spacing: -.03em; line-height: 1.1; margin-bottom: 1rem;
    background: linear-gradient(135deg, #fff 20%, var(--accent-light) 70%);
    -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
}
.about-sub {
    color: var(--text-muted); font-size: 1.05rem; line-height: 1.7;
    max-width: 620px; margin: 0 auto 2.5rem;
}

/* tech cards */
.tech-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1rem; margin-bottom: 3rem; }
.tech-card {
    background: var(--bg-card); border: 1px solid var(--border); border-radius: 14px;
    padding: 1.5rem 1.25rem; transition: transform .25s, border-color .25s, box-shadow .25s;
    display: flex; flex-direction: column; gap: .6rem;
}
.tech-card:hover {
    transform: translateY(-5px); border-color: var(--accent);
    box-shadow: 0 12px 40px rgba(0,0,0,.4), 0 0 0 1px var(--accent);
}
.tech-icon {
    width: 44px; height: 44px; border-radius: 10px;
    background: linear-gradient(135deg, var(--accent), var(--accent-light));
    display: flex; align-items: center; justify-content: center;
    font-size: 1.2rem; color: #fff;
}
.tech-name { font-size: 1rem; font-weight: 700; }
.tech-desc { font-size: .82rem; color: var(--text-muted); line-height: 1.55; }

/* features */
.feature-list { display: flex; flex-direction: column; gap: .75rem; margin-bottom: 3rem; }
.feature-item {
    background: var(--bg-card); border: 1px solid var(--border); border-radius: 12px;
    padding: 1rem 1.25rem; display: flex; align-items: center; gap: 1rem;
    transition: border-color .22s;
}
.feature-item:hover { border-color: var(--accent); }
.feature-ico {
    width: 38px; height: 38px; border-radius: 9px; flex-shrink: 0;
    background: var(--accent-glow); border: 1px solid var(--accent);
    display: flex; align-items: center; justify-content: center;
    color: var(--accent-light); font-size: 1rem;
}
.feature-text strong { font-size: .92rem; font-weight: 600; display: block; margin-bottom: .15rem; }
.feature-text span { font-size: .8rem; color: var(--text-muted); }

/* api credit */
.api-credit {
    background: linear-gradient(135deg, rgba(124,58,237,.12), rgba(168,85,247,.06));
    border: 1px solid var(--border); border-radius: 18px; padding: 2rem;
    display: flex; align-items: center; gap: 1.5rem; flex-wrap: wrap;
}
.api-credit-icon { font-size: 2.8rem; flex-shrink: 0; }
.api-credit h4 { font-size: 1.1rem; font-weight: 700; margin-bottom: .3rem; }
.api-credit p { font-size: .85rem; color: var(--text-muted); margin: 0; line-height: 1.6; }
.api-credit a { color: var(--accent-light); text-decoration: none; font-weight: 500; }
.api-credit a:hover { text-decoration: underline; }

/* section divider */
.about-section-title {
    font-size: 1.3rem; font-weight: 700; margin-bottom: 1.25rem;
    display: flex; align-items: center; gap: .6rem;
}
.asl { width: 3px; height: 1.2em; background: linear-gradient(to bottom, var(--accent), var(--accent-light)); border-radius: 3px; }
</style>
@endsection

@section('content')

{{-- Hero --}}
<div class="about-hero">
    <div class="about-hero-badge">
        <i class="bi bi-stars"></i>
        Proyecto académico · ContSol
    </div>
    <h1 class="about-title">Acerca de Anime Explorer</h1>
    <p class="about-sub">
        Una plataforma web para descubrir, explorar y conocer el universo del anime —
        construida con Laravel y alimentada por datos reales de MyAnimeList en tiempo real.
    </p>
    <a href="{{ route('home') }}" class="btn-search"
       style="display:inline-flex;align-items:center;gap:.45rem;text-decoration:none;padding:.75rem 1.6rem;">
        <i class="bi bi-house-fill"></i> Ir al inicio
    </a>
</div>

<div class="row g-5 mt-2">

    {{-- Left column --}}
    <div class="col-lg-7">

        {{-- Tech Stack --}}
        <div class="about-section-title"><div class="asl"></div><i class="bi bi-cpu-fill" style="color:var(--accent-light)"></i> Stack Tecnológico</div>
        <div class="tech-grid">
            <div class="tech-card">
                <div class="tech-icon"><i class="bi bi-box-seam"></i></div>
                <div class="tech-name">Laravel 12</div>
                <div class="tech-desc">Framework PHP para el backend, enrutamiento, vistas Blade y servicios.</div>
            </div>
            <div class="tech-card">
                <div class="tech-icon" style="background:linear-gradient(135deg,#f59e0b,#d97706)"><i class="bi bi-lightning"></i></div>
                <div class="tech-name">Jikan API v4</div>
                <div class="tech-desc">API REST gratuita que expone los datos públicos de MyAnimeList.</div>
            </div>
            <div class="tech-card">
                <div class="tech-icon" style="background:linear-gradient(135deg,#6366f1,#818cf8)"><i class="bi bi-bootstrap-fill"></i></div>
                <div class="tech-name">Bootstrap 5</div>
                <div class="tech-desc">Grid responsive y utilidades CSS para la maquetación de componentes.</div>
            </div>
            <div class="tech-card">
                <div class="tech-icon" style="background:linear-gradient(135deg,#10b981,#059669)"><i class="bi bi-arrow-left-right"></i></div>
                <div class="tech-name">Guzzle HTTP</div>
                <div class="tech-desc">Cliente HTTP para consumir la API de Jikan desde el servidor PHP.</div>
            </div>
            <div class="tech-card">
                <div class="tech-icon" style="background:linear-gradient(135deg,#ef4444,#dc2626)"><i class="bi bi-database"></i></div>
                <div class="tech-name">Laravel Cache</div>
                <div class="tech-desc">Caché SQLite para reducir llamadas a la API y mejorar tiempos de carga.</div>
            </div>
            <div class="tech-card">
                <div class="tech-icon" style="background:linear-gradient(135deg,#ec4899,#db2777)"><i class="bi bi-translate"></i></div>
                <div class="tech-name">MyMemory API</div>
                <div class="tech-desc">Traducción automática de sinopsis de inglés a español.</div>
            </div>
        </div>

        {{-- Features --}}
        <div class="about-section-title mt-2"><div class="asl"></div><i class="bi bi-check2-all" style="color:var(--green)"></i> Funcionalidades</div>
        <div class="feature-list">
            <div class="feature-item">
                <div class="feature-ico"><i class="bi bi-trophy-fill"></i></div>
                <div class="feature-text">
                    <strong>Top Anime</strong>
                    <span>Listado paginado con los mejores animes según el ranking de MAL.</span>
                </div>
            </div>
            <div class="feature-item">
                <div class="feature-ico"><i class="bi bi-search"></i></div>
                <div class="feature-text">
                    <strong>Búsqueda en tiempo real</strong>
                    <span>Busca cualquier título entre más de 24,000 animes disponibles.</span>
                </div>
            </div>
            <div class="feature-item">
                <div class="feature-ico"><i class="bi bi-card-text"></i></div>
                <div class="feature-text">
                    <strong>Página de detalles</strong>
                    <span>Sinopsis traducida, tráiler, estadísticas, géneros, estudio y más.</span>
                </div>
            </div>
            <div class="feature-item">
                <div class="feature-ico"><i class="bi bi-lightning-fill"></i></div>
                <div class="feature-text">
                    <strong>Sistema de caché inteligente</strong>
                    <span>Respuestas guardadas hasta 6 horas para una experiencia fluida.</span>
                </div>
            </div>
            <div class="feature-item">
                <div class="feature-ico"><i class="bi bi-moon-stars-fill"></i></div>
                <div class="feature-text">
                    <strong>Diseño Dark Mode</strong>
                    <span>Interfaz oscura con acentos morados, tipografía Outfit y microanimaciones.</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Right column --}}
    <div class="col-lg-5">

        {{-- About the project --}}
        <div class="about-section-title"><div class="asl"></div><i class="bi bi-file-code-fill" style="color:var(--gold)"></i> El Proyecto</div>
        <div class="synopsis-box mb-4">
            <p>
                <strong style="color:var(--text-primary)">Anime Explorer</strong> es una aplicación web desarrollada
                con <strong style="color:var(--accent-light)">Laravel 12</strong> que permite a los usuarios
                explorar el catálogo de MyAnimeList sin necesidad de registrarse.
            </p>
            <p class="mt-3">
                El sistema consulta la <strong style="color:var(--accent-light)">Jikan API v4</strong> — un wrapper
                REST no oficial de MAL — y almacena las respuestas en caché local para reducir latencias
                y respetar los límites de la API.
            </p>
            <p class="mt-3">
                Las sinopsis se traducen automáticamente al español mediante la
                <strong style="color:var(--accent-light)">MyMemory API</strong>, con chunks de 490 caracteres
                para respetar el límite gratuito.
            </p>
        </div>

        {{-- API Credit --}}
        <div class="api-credit mb-4">
            <div class="api-credit-icon">🗃️</div>
            <div>
                <h4>Powered by Jikan API</h4>
                <p>
                    Datos provistos por <a href="https://jikan.moe" target="_blank">jikan.moe</a>,
                    un wrapper no oficial y gratuito de MyAnimeList.
                    Actualizado cada 24 horas desde MAL.
                </p>
            </div>
        </div>

        {{-- Quick nav --}}
        <div class="about-section-title mt-2"><div class="asl"></div><i class="bi bi-map-fill" style="color:var(--accent-light)"></i> Navegar</div>
        <div class="d-flex flex-column gap-2">
            <a href="{{ route('home') }}"
               style="display:flex;align-items:center;gap:.8rem;padding:.9rem 1.1rem;background:var(--bg-card);border:1px solid var(--border);border-radius:12px;text-decoration:none;color:inherit;transition:.22s;"
               onmouseover="this.style.borderColor='var(--accent)'" onmouseout="this.style.borderColor='var(--border)'">
                <i class="bi bi-house-fill" style="color:var(--accent-light)"></i>
                <span style="font-weight:500">Inicio</span>
                <i class="bi bi-chevron-right ms-auto" style="color:var(--text-muted)"></i>
            </a>
            <a href="{{ route('anime.index') }}"
               style="display:flex;align-items:center;gap:.8rem;padding:.9rem 1.1rem;background:var(--bg-card);border:1px solid var(--border);border-radius:12px;text-decoration:none;color:inherit;transition:.22s;"
               onmouseover="this.style.borderColor='var(--accent)'" onmouseout="this.style.borderColor='var(--border)'">
                <i class="bi bi-trophy-fill" style="color:var(--gold)"></i>
                <span style="font-weight:500">Top Anime</span>
                <i class="bi bi-chevron-right ms-auto" style="color:var(--text-muted)"></i>
            </a>
            <a href="{{ route('anime.search') }}"
               style="display:flex;align-items:center;gap:.8rem;padding:.9rem 1.1rem;background:var(--bg-card);border:1px solid var(--border);border-radius:12px;text-decoration:none;color:inherit;transition:.22s;"
               onmouseover="this.style.borderColor='var(--accent)'" onmouseout="this.style.borderColor='var(--border)'">
                <i class="bi bi-search" style="color:var(--green)"></i>
                <span style="font-weight:500">Búsqueda</span>
                <i class="bi bi-chevron-right ms-auto" style="color:var(--text-muted)"></i>
            </a>
        </div>
    </div>
</div>

@endsection
