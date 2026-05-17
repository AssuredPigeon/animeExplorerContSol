<?php

use App\Http\Controllers\AnimeController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

// Landing page
Route::get('/', [HomeController::class, 'index'])->name('home');

// Top anime list
Route::get('/top', [AnimeController::class, 'index'])->name('anime.index');

// Search
Route::get('/search', [AnimeController::class, 'search'])->name('anime.search');

// Anime detail
Route::get('/anime/{id}', [AnimeController::class, 'show'])->name('anime.show')->where('id', '[0-9]+');

// About
Route::get('/about', fn () => view('about'))->name('about');
