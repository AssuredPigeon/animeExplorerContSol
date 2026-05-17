<?php

namespace App\Http\Controllers;

use App\Services\JikanService;

class HomeController extends Controller
{
    public function __construct(protected JikanService $jikan) {}

    public function index()
    {
        $topResult = $this->jikan->getTopAnime(1, 18);
        $all       = $topResult['data'] ?? [];

        $featured = array_slice($all, 0, 6);
        $trending = array_slice($all, 6, 12);

        return view('home', compact('featured', 'trending'));
    }
}
