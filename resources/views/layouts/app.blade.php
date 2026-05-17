<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description"
        content="Anime Explorer – Descubre los mejores animes, busca tus favoritos y explora el universo del anime.">
    <title>@yield('title', 'Anime Explorer')</title>

    {{-- Bootstrap 5 --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    {{-- Bootstrap Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <style>
        /* ============================================================
           DESIGN SYSTEM – Anime Explorer Dark Theme
        ============================================================ */
        :root {
            --bg-base: #0d0d1a;
            --bg-card: #14142a;
            --bg-card-hover: #1c1c38;
            --bg-navbar: #0a0a16ee;
            --accent: #7c3aed;
            --accent-light: #a855f7;
            --accent-glow: rgba(124, 58, 237, .35);
            --gold: #f59e0b;
            --green: #10b981;
            --red: #ef4444;
            --text-primary: #f1f1f8;
            --text-muted: #8888aa;
            --border: rgba(124, 58, 237, .25);
            --radius-card: 14px;
            --transition: .25s cubic-bezier(.4, 0, .2, 1);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Outfit', sans-serif;
            background-color: var(--bg-base);
            color: var(--text-primary);
            min-height: 100vh;
        }

        /* ---- Scrollbar ---- */
        ::-webkit-scrollbar {
            width: 6px;
        }

        ::-webkit-scrollbar-track {
            background: var(--bg-base);
        }

        ::-webkit-scrollbar-thumb {
            background: var(--accent);
            border-radius: 3px;
        }

        /* ============================================================
           NAVBAR
        ============================================================ */
        .navbar-ae {
            position: sticky;
            top: 0;
            z-index: 1000;
            background: var(--bg-navbar);
            backdrop-filter: blur(18px);
            border-bottom: 1px solid var(--border);
            padding: .85rem 0;
        }

        .navbar-brand-ae {
            display: flex;
            align-items: center;
            gap: .55rem;
            text-decoration: none;
        }

        .brand-badge {
            background: linear-gradient(135deg, var(--accent), var(--accent-light));
            color: #fff;
            font-weight: 800;
            font-size: .7rem;
            letter-spacing: .08em;
            padding: .18rem .5rem;
            border-radius: 5px;
        }

        .brand-title {
            font-weight: 700;
            font-size: 1.15rem;
            color: var(--text-primary);
            letter-spacing: -.01em;
        }

        .brand-sub {
            font-size: .65rem;
            color: var(--text-muted);
            font-weight: 400;
            letter-spacing: .05em;
        }

        /* ContSol badge */
        .contsol-badge {
            font-size: .7rem;
            font-weight: 600;
            color: var(--text-muted);
            background: rgba(255, 255, 255, .04);
            border: 1px solid rgba(255, 255, 255, .12);
            padding: .22rem .6rem;
            border-radius: 6px;
            letter-spacing: .04em;
            margin-left: .5rem;
            transition: color var(--transition), border-color var(--transition);
        }

        .contsol-badge:hover {
            color: var(--text-primary);
            border-color: rgba(255, 255, 255, .25);
        }

        .nav-link-ae {
            color: var(--text-muted) !important;
            font-weight: 500;
            font-size: .92rem;
            padding: .4rem .85rem !important;
            border-radius: 8px;
            transition: color var(--transition), background var(--transition);
            text-decoration: none;
        }

        .nav-link-ae:hover,
        .nav-link-ae.active {
            color: var(--text-primary) !important;
            background: rgba(124, 58, 237, .18);
        }

        .nav-link-ae.active {
            color: var(--accent-light) !important;
        }

        /* ============================================================
           PAGE HEADER
        ============================================================ */
        .page-header {
            padding: 2.5rem 0 1.5rem;
            text-align: center;
        }

        .page-header h1 {
            font-size: 2rem;
            font-weight: 800;
            letter-spacing: -.02em;
            background: linear-gradient(135deg, #fff 30%, var(--accent-light));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .page-header p {
            color: var(--text-muted);
            font-size: .95rem;
            margin-top: .4rem;
        }

        /* ============================================================
           ANIME CARDS
        ============================================================ */
        .anime-card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--radius-card);
            overflow: hidden;
            transition: transform var(--transition), box-shadow var(--transition), border-color var(--transition);
            text-decoration: none;
            color: inherit;
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .anime-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 16px 48px rgba(0, 0, 0, .5), 0 0 0 1px var(--accent);
            border-color: var(--accent);
            color: inherit;
        }

        /* Card poster area */
        .card-poster {
            position: relative;
            aspect-ratio: 3/4;
            overflow: hidden;
            background: linear-gradient(135deg, var(--accent) 0%, #1e0a3c 100%);
        }

        .card-poster img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform .4s ease;
        }

        .anime-card:hover .card-poster img {
            transform: scale(1.06);
        }

        /* Rank badge */
        .rank-badge {
            position: absolute;
            top: .6rem;
            left: .6rem;
            background: var(--accent);
            color: #fff;
            font-size: .65rem;
            font-weight: 700;
            padding: .2rem .45rem;
            border-radius: 5px;
            letter-spacing: .04em;
        }

        /* Score chip */
        .score-chip {
            position: absolute;
            top: .6rem;
            right: .6rem;
            background: rgba(0, 0, 0, .7);
            backdrop-filter: blur(6px);
            color: var(--gold);
            font-size: .78rem;
            font-weight: 700;
            padding: .25rem .55rem;
            border-radius: 20px;
            display: flex;
            align-items: center;
            gap: .25rem;
        }

        /* Placeholder when no image */
        .card-poster-placeholder {
            position: absolute;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, var(--accent) 0%, #1e0a3c 100%);
            font-weight: 800;
            font-size: 2.2rem;
            color: rgba(255, 255, 255, .9);
            letter-spacing: .05em;
            text-align: center;
            padding: .5rem;
        }

        /* Card body */
        .card-body-ae {
            padding: .9rem 1rem;
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: .3rem;
        }

        .card-title-ae {
            font-size: .88rem;
            font-weight: 600;
            line-height: 1.3;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .card-meta {
            display: flex;
            flex-wrap: wrap;
            gap: .35rem;
            margin-top: auto;
        }

        .meta-tag {
            font-size: .68rem;
            color: var(--text-muted);
            background: rgba(255, 255, 255, .05);
            padding: .15rem .45rem;
            border-radius: 4px;
            white-space: nowrap;
        }

        /* ============================================================
           SEARCH BAR
        ============================================================ */
        .search-wrapper {
            max-width: 620px;
            margin: 0 auto 2rem;
        }

        .search-form {
            display: flex;
            gap: .5rem;
        }

        .search-input {
            flex: 1;
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: .8rem 1.2rem;
            color: var(--text-primary);
            font-family: 'Outfit', sans-serif;
            font-size: .95rem;
            transition: border-color var(--transition), box-shadow var(--transition);
            outline: none;
        }

        .search-input::placeholder {
            color: var(--text-muted);
        }

        .search-input:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px var(--accent-glow);
        }

        .btn-search {
            background: linear-gradient(135deg, var(--accent), var(--accent-light));
            color: #fff;
            border: none;
            border-radius: 10px;
            padding: .8rem 1.4rem;
            font-family: 'Outfit', sans-serif;
            font-weight: 600;
            font-size: .92rem;
            cursor: pointer;
            transition: opacity var(--transition), box-shadow var(--transition);
            white-space: nowrap;
        }

        .btn-search:hover {
            opacity: .9;
            box-shadow: 0 6px 24px var(--accent-glow);
        }

        /* ============================================================
           ANIME DETAIL PAGE
        ============================================================ */
        .detail-hero {
            display: grid;
            grid-template-columns: 260px 1fr;
            gap: 2rem;
            align-items: start;
        }

        @media (max-width: 768px) {
            .detail-hero {
                grid-template-columns: 1fr;
            }
        }

        .detail-poster {
            border-radius: var(--radius-card);
            overflow: hidden;
            box-shadow: 0 24px 64px rgba(0, 0, 0, .5);
        }

        .detail-poster img {
            width: 100%;
            display: block;
        }

        .detail-info {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .detail-title {
            font-size: 1.8rem;
            font-weight: 800;
            line-height: 1.2;
        }

        .detail-title-jp {
            color: var(--text-muted);
            font-size: .9rem;
            font-weight: 400;
        }

        .detail-badges {
            display: flex;
            flex-wrap: wrap;
            gap: .5rem;
        }

        .badge-ae {
            font-size: .75rem;
            font-weight: 600;
            padding: .3rem .75rem;
            border-radius: 6px;
            border: 1px solid;
        }

        .badge-score {
            color: var(--gold);
            border-color: var(--gold);
            background: rgba(245, 158, 11, .1);
        }

        .badge-status {
            color: var(--green);
            border-color: var(--green);
            background: rgba(16, 185, 129, .1);
        }

        .badge-type {
            color: var(--accent-light);
            border-color: var(--accent);
            background: var(--accent-glow);
        }

        .detail-stats {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(110px, 1fr));
            gap: .75rem;
        }

        .stat-box {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: .75rem;
            text-align: center;
        }

        .stat-label {
            font-size: .65rem;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: .08em;
        }

        .stat-value {
            font-size: 1rem;
            font-weight: 700;
            margin-top: .2rem;
        }

        .synopsis-box {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--radius-card);
            padding: 1.25rem;
        }

        .synopsis-box h3 {
            font-size: .85rem;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: .1em;
            margin-bottom: .6rem;
        }

        .synopsis-box p {
            font-size: .9rem;
            line-height: 1.7;
            color: var(--text-primary);
        }

        .genre-pill {
            background: var(--accent-glow);
            border: 1px solid var(--accent);
            color: var(--accent-light);
            padding: .25rem .65rem;
            border-radius: 20px;
            font-size: .75rem;
            font-weight: 500;
        }

        .btn-back {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            color: var(--text-muted);
            text-decoration: none;
            font-size: .88rem;
            font-weight: 500;
            transition: color var(--transition);
            margin-bottom: 1.5rem;
        }

        .btn-back:hover {
            color: var(--accent-light);
        }

        /* ============================================================
           PAGINATION
        ============================================================ */
        .pagination-ae {
            display: flex;
            justify-content: center;
            gap: .4rem;
            margin-top: 2.5rem;
            flex-wrap: wrap;
        }

        .page-btn {
            background: var(--bg-card);
            border: 1px solid var(--border);
            color: var(--text-primary);
            padding: .45rem .9rem;
            border-radius: 8px;
            font-size: .85rem;
            font-weight: 500;
            cursor: pointer;
            text-decoration: none;
            transition: background var(--transition), border-color var(--transition);
        }

        .page-btn:hover {
            background: var(--bg-card-hover);
            border-color: var(--accent);
            color: var(--text-primary);
        }

        .page-btn.current {
            background: var(--accent);
            border-color: var(--accent);
            color: #fff;
        }

        /* ============================================================
           SEARCH RESULT ROW (list style)
        ============================================================ */
        .result-row {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 12px;
            display: flex;
            gap: 1rem;
            padding: .9rem;
            align-items: center;
            text-decoration: none;
            color: inherit;
            transition: border-color var(--transition), transform var(--transition);
        }

        .result-row:hover {
            border-color: var(--accent);
            transform: translateX(4px);
            color: inherit;
        }

        .result-thumb {
            width: 56px;
            height: 80px;
            object-fit: cover;
            border-radius: 8px;
            flex-shrink: 0;
            background: var(--accent-glow);
        }

        .result-thumb-placeholder {
            width: 56px;
            height: 80px;
            border-radius: 8px;
            flex-shrink: 0;
            background: linear-gradient(135deg, var(--accent), #1e0a3c);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: .75rem;
            color: #fff;
            text-align: center;
        }

        .result-info {
            flex: 1;
            min-width: 0;
        }

        .result-title {
            font-weight: 600;
            font-size: .9rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .result-sub {
            font-size: .78rem;
            color: var(--text-muted);
            margin-top: .2rem;
        }

        .result-score {
            color: var(--gold);
            font-weight: 700;
            font-size: .9rem;
            flex-shrink: 0;
        }

        .result-arrow {
            color: var(--text-muted);
            flex-shrink: 0;
        }

        /* ============================================================
           EMPTY STATE
        ============================================================ */
        .empty-state {
            text-align: center;
            padding: 4rem 1rem;
            color: var(--text-muted);
        }

        .empty-state i {
            font-size: 3rem;
            opacity: .4;
            margin-bottom: 1rem;
            display: block;
        }

        .empty-state p {
            font-size: .95rem;
        }



        /* ============================================================
           LOADING SPINNER
        ============================================================ */
        .spinner-ae {
            width: 40px;
            height: 40px;
            border: 3px solid var(--border);
            border-top-color: var(--accent);
            border-radius: 50%;
            animation: spin .7s linear infinite;
            margin: 3rem auto;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        /* Fade-in animation */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-in-up {
            animation: fadeInUp .45s ease forwards;
        }
    </style>

    @yield('head')
</head>

<body>

    {{-- ======================================================
    NAVBAR
    ====================================================== --}}
    <nav class="navbar-ae">
        <div class="container d-flex align-items-center justify-content-between">

            {{-- Brand --}}
            <a href="{{ route('home') }}" class="navbar-brand-ae">
                <div style="display:flex; align-items:baseline; gap:.5rem;">
                    <span class="brand-title">Anime Explorer</span>
                    <span class="contsol-badge" style="margin-left:0; position:relative; top:-1px;">ContSol</span>
                </div>
            </a>

            {{-- Nav links + ContSol badge --}}
            <div class="d-flex align-items-center gap-1">
                <a href="{{ route('home') }}"
                    class="nav-link-ae {{ request()->routeIs('home') ? 'active' : '' }}">
                    <i class="bi bi-house me-1"></i>Inicio
                </a>
                <a href="{{ route('anime.index') }}"
                    class="nav-link-ae {{ request()->routeIs('anime.index') ? 'active' : '' }}">
                    <i class="bi bi-trophy me-1"></i>Top Anime
                </a>
                <a href="{{ route('anime.search') }}"
                    class="nav-link-ae {{ request()->routeIs('anime.search') ? 'active' : '' }}">
                    <i class="bi bi-search me-1"></i>Búsqueda
                </a>
                <a href="{{ route('about') }}"
                    class="nav-link-ae {{ request()->routeIs('about') ? 'active' : '' }}">
                    <i class="bi bi-info-circle me-1"></i>Acerca de
                </a>
            </div>

        </div>
    </nav>

    {{-- ======================================================
    MAIN CONTENT
    ====================================================== --}}
    <main class="container py-4">
        @yield('content')
    </main>


    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>

</html>