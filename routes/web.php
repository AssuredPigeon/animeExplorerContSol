<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AnimeController;

// Redirect root to top anime
Route::get('/', fn() => redirect()->route('anime.index'));

// Top anime list
Route::get('/top', [AnimeController::class, 'index'])->name('anime.index');

// Search
Route::get('/search', [AnimeController::class, 'search'])->name('anime.search');

// Anime detail
Route::get('/anime/{id}', [AnimeController::class, 'show'])->name('anime.show')->where('id', '[0-9]+');
