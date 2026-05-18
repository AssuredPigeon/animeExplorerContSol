<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AnimeRouteTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Cache::flush();
    }

    private function fakeTopAnime(): void
    {
        Http::fake([
            'api.jikan.moe/v4/top/anime*' => Http::response([
                'data' => [
                    [
                        'mal_id'   => 5114,
                        'title'    => 'Fullmetal Alchemist: Brotherhood',
                        'score'    => 9.11,
                        'rank'     => 1,
                        'type'     => 'TV',
                        'year'     => 2009,
                        'episodes' => 64,
                        'images'   => ['jpg' => ['image_url' => 'https://example.com/fma.jpg']],
                        'genres'   => [['name' => 'Action']],
                    ],
                    [
                        'mal_id'   => 9253,
                        'title'    => 'Steins;Gate',
                        'score'    => 9.07,
                        'rank'     => 2,
                        'type'     => 'TV',
                        'year'     => 2011,
                        'episodes' => 24,
                        'images'   => ['jpg' => ['image_url' => 'https://example.com/sg.jpg']],
                        'genres'   => [['name' => 'Sci-Fi']],
                    ],
                ],
                'pagination' => ['has_next_page' => true, 'last_visible_page' => 5],
            ], 200),
        ]);
    }

    /** #8 — La ruta "/" responde con HTTP 200. */
    #[Test]
    public function home_route_returns_200(): void
    {
        $this->fakeTopAnime();
        $this->get('/')->assertStatus(200);
    }

    /** #9 — La ruta "/top" responde con HTTP 200. */
    #[Test]
    public function top_anime_route_returns_200(): void
    {
        $this->fakeTopAnime();
        $this->get('/top')->assertStatus(200);
    }

    /** #10 — La vista de /top contiene el título recibido de la API (Http::fake). */
    #[Test]
    public function top_anime_route_contains_anime_title(): void
    {
        $this->fakeTopAnime();
        $this->get('/top')
            ->assertStatus(200)
            ->assertSee('Fullmetal Alchemist: Brotherhood');
    }

    /** #11 — La ruta "/search" sin query devuelve HTTP 200. */
    #[Test]
    public function search_route_returns_200_without_query(): void
    {
        $this->get('/search')->assertStatus(200);
    }

    /** #12 — "/search?q=Naruto" muestra resultados con Http::fake. */
    #[Test]
    public function search_route_shows_results_with_valid_query(): void
    {
        Http::fake([
            'api.jikan.moe/v4/anime*' => Http::response([
                'data' => [[
                    'mal_id'  => 20,
                    'title'   => 'Naruto',
                    'score'   => 8.0,
                    'type'    => 'TV',
                    'year'    => 2002,
                    'status'  => 'Finished Airing',
                    'images'  => ['jpg' => ['image_url' => 'https://example.com/naruto.jpg']],
                    'genres'  => [['name' => 'Action']],
                ]],
                'pagination' => ['has_next_page' => false, 'last_visible_page' => 1],
            ], 200),
        ]);

        $this->get('/search?q=Naruto')
            ->assertStatus(200)
            ->assertSee('Naruto');
    }

    /** #13 — "/anime/{id}" retorna HTTP 200 cuando el anime existe (Http::fake). */
    #[Test]
    public function anime_detail_route_returns_200_for_valid_id(): void
    {
        Http::fake([
            'api.jikan.moe/v4/anime/5114*' => Http::response([
                'data' => [
                    'mal_id'   => 5114,
                    'title'    => 'Fullmetal Alchemist: Brotherhood',
                    'synopsis' => 'Two brothers.',
                    'score'    => 9.11,
                    'type'     => 'TV',
                    'episodes' => 64,
                    'status'   => 'Finished Airing',
                    'images'   => ['jpg' => ['image_url' => 'https://example.com/fma.jpg']],
                    'genres'   => [['name' => 'Action']],
                ],
            ], 200),
            'api.mymemory.translated.net/*' => Http::response([
                'responseStatus' => 200,
                'responseData'   => ['translatedText' => 'Dos hermanos.'],
            ], 200),
        ]);

        $this->get('/anime/5114')
            ->assertStatus(200)
            ->assertSee('Fullmetal Alchemist: Brotherhood');
    }

    /** #14 — "/anime/{id}" retorna HTTP 404 cuando la API no encuentra el anime. */
    #[Test]
    public function anime_detail_route_returns_404_when_not_found(): void
    {
        Http::fake([
            'api.jikan.moe/v4/anime/999999*' => Http::response(['error' => 'Not Found'], 404),
        ]);

        $this->get('/anime/999999')->assertStatus(404);
    }
}
