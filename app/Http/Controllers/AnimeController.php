<?php

namespace App\Http\Controllers;

use App\Services\JikanService;
use Illuminate\Http\Request;

class AnimeController extends Controller
{
    public function __construct(protected JikanService $jikan) {}

    /**
     * Show the top anime list.
     */
    public function index(Request $request)
    {
        $page = (int) $request->query('page', 1);
        $result = $this->jikan->getTopAnime($page, 24);

        $animes = $result['data'] ?? [];
        $pagination = $result['pagination'] ?? [];

        return view('anime.index', compact('animes', 'pagination', 'page'));
    }

    /**
     * Show the search form and results.
     */
    public function search(Request $request)
    {
        $query = $request->input('q', '');
        $page = (int) $request->query('page', 1);
        $animes = [];
        $pagination = [];

        if (! empty(trim($query))) {
            $result = $this->jikan->searchAnime($query, $page, 20);
            $animes = $result['data'] ?? [];
            $pagination = $result['pagination'] ?? [];
        }

        return view('anime.search', compact('animes', 'pagination', 'query', 'page'));
    }

    /**
     * Show anime detail page.
     */
    public function show(int $id)
    {
        $result = $this->jikan->getAnimeById($id);
        $anime = $result['data'] ?? null;

        if (! $anime) {
            abort(404, 'Anime not found.');
        }

        return view('anime.show', compact('anime'));
    }
}
