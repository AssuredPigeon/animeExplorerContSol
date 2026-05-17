@extends('layouts.app')
@section('title', 'Iniciar Sesión – Anime Explorer')
@section('head')
<style>
.auth-wrap {
    min-height: 80vh; display: flex; align-items: center; justify-content: center; padding: 2rem 1rem;
}
.auth-card {
    background: var(--bg-card); border: 1px solid var(--border);
    border-radius: 20px; padding: 2.5rem 2rem; width: 100%; max-width: 420px;
    box-shadow: 0 24px 64px rgba(0,0,0,.4);
    animation: fadeInUp .4s ease forwards;
}
.auth-logo { text-align: center; margin-bottom: 2rem; }
.auth-logo span { font-size: 1.3rem; font-weight: 800; }
.auth-title { font-size: 1.5rem; font-weight: 800; text-align: center; margin-bottom: .35rem; }
.auth-sub   { color: var(--text-muted); font-size: .88rem; text-align: center; margin-bottom: 2rem; }
.auth-field { margin-bottom: 1.1rem; }
.auth-label { font-size: .8rem; font-weight: 600; color: var(--text-muted); display: block; margin-bottom: .4rem; letter-spacing: .04em; text-transform: uppercase; }
.auth-input {
    width: 100%; background: rgba(255,255,255,.05); border: 1px solid var(--border);
    border-radius: 10px; padding: .75rem 1rem; color: var(--text-primary);
    font-family: 'Outfit', sans-serif; font-size: .95rem; outline: none;
    transition: border-color .22s, box-shadow .22s;
}
.auth-input::placeholder { color: var(--text-muted); }
.auth-input:focus { border-color: var(--accent); box-shadow: 0 0 0 3px var(--accent-glow); }
.auth-input.is-invalid { border-color: var(--red); }
.auth-error { color: var(--red); font-size: .78rem; margin-top: .3rem; }
.auth-btn {
    width: 100%; background: linear-gradient(135deg, var(--accent), var(--accent-light));
    color: #fff; border: none; border-radius: 10px; padding: .85rem;
    font-family: 'Outfit', sans-serif; font-weight: 700; font-size: .98rem;
    cursor: pointer; transition: opacity .2s; margin-top: .5rem;
}
.auth-btn:hover { opacity: .88; }
.auth-footer { text-align: center; margin-top: 1.5rem; font-size: .85rem; color: var(--text-muted); }
.auth-footer a { color: var(--accent-light); text-decoration: none; font-weight: 500; }
.auth-footer a:hover { text-decoration: underline; }
.auth-divider { display: flex; align-items: center; gap: .75rem; margin: 1.25rem 0; color: var(--text-muted); font-size: .78rem; }
.auth-divider::before, .auth-divider::after { content: ''; flex: 1; height: 1px; background: var(--border); }
</style>
@endsection
@section('content')
<div class="auth-wrap">
    <div class="auth-card">
        <div class="auth-logo">
            <span>Anime Explorer</span>
        </div>
        <h1 class="auth-title">Bienvenido de nuevo</h1>
        <p class="auth-sub">Inicia sesión para ver tus favoritos</p>

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="auth-field">
                <label class="auth-label" for="email">Correo electrónico</label>
                <input id="email" type="email" name="email" class="auth-input {{ $errors->has('email') ? 'is-invalid' : '' }}"
                       value="{{ old('email') }}" placeholder="tu@correo.com" autocomplete="email">
                @error('email')<div class="auth-error">{{ $message }}</div>@enderror
            </div>
            <div class="auth-field">
                <label class="auth-label" for="password">Contraseña</label>
                <input id="password" type="password" name="password" class="auth-input {{ $errors->has('password') ? 'is-invalid' : '' }}"
                       placeholder="••••••••" autocomplete="current-password">
                @error('password')<div class="auth-error">{{ $message }}</div>@enderror
            </div>
            <div style="display:flex; align-items:center; gap:.5rem; margin-bottom:.5rem;">
                <input type="checkbox" name="remember" id="remember" style="accent-color:var(--accent)">
                <label for="remember" style="font-size:.83rem; color:var(--text-muted); cursor:pointer;">Recordarme</label>
            </div>
            <button type="submit" class="auth-btn">Iniciar sesión</button>
        </form>

        <div class="auth-divider">o</div>

        <div class="auth-footer">
            ¿No tienes cuenta? <a href="{{ route('register') }}">Regístrate gratis</a>
        </div>
    </div>
</div>
@endsection
