<?php

use App\Http\Controllers\AnimeController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

// ── Landing ──────────────────────────────────────────────
Route::get('/', [HomeController::class, 'index'])->name('home');

// ── Anime ─────────────────────────────────────────────────
Route::get('/top',          [AnimeController::class, 'index'])->name('anime.index');
Route::get('/search',       [AnimeController::class, 'search'])->name('anime.search');
Route::get('/anime/{id}',   [AnimeController::class, 'show'])->name('anime.show')->where('id', '[0-9]+');

// ── About ─────────────────────────────────────────────────
Route::get('/about', fn () => view('about'))->name('about');

// ── Auth (guests only) ────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login',    [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login',   [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register',[AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// ── Favorites (auth required) ─────────────────────────────
Route::middleware('auth')->group(function () {
    Route::get('/favorites',                    [FavoriteController::class, 'index'])->name('favorites.index');
    Route::post('/favorites/toggle',            [FavoriteController::class, 'toggle'])->name('favorites.toggle');
    Route::delete('/favorites/{malId}',         [FavoriteController::class, 'destroy'])->name('favorites.destroy');
});
