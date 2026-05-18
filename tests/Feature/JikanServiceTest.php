<?php

namespace Tests\Feature;

use App\Services\JikanService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Pruebas de integración para JikanService usando Http::fake().
 *
 * Http::fake() intercepta todas las llamadas al facade Http de Laravel
 * y devuelve respuestas simuladas, sin realizar peticiones reales a la API.
 */
class JikanServiceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Cache::flush();
    }

    /** #1 — getTopAnime retorna datos correctos cuando la API responde (HTTP 200). */
    #[Test]
    public function get_top_anime_returns_data_on_success(): void
    {
        Http::fake([
            'api.jikan.moe/v4/top/anime*' => Http::response([
                'data' => [[
                    'mal_id' => 5114,
                    'title'  => 'Fullmetal Alchemist: Brotherhood',
                    'score'  => 9.11,
                    'rank'   => 1,
                    'type'   => 'TV',
                    'images' => ['jpg' => ['image_url' => 'https://example.com/fma.jpg']],
                ]],
                'pagination' => ['has_next_page' => true, 'last_visible_page' => 5],
            ], 200),
        ]);

        $result = (new JikanService())->getTopAnime(1, 24);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('data', $result);
        $this->assertArrayHasKey('pagination', $result);
        $this->assertCount(1, $result['data']);
        $this->assertEquals('Fullmetal Alchemist: Brotherhood', $result['data'][0]['title']);
        $this->assertEquals(9.11, $result['data'][0]['score']);
        $this->assertEquals(1, $result['data'][0]['rank']);
    }

    /** #2 — getTopAnime retorna arrays vacíos cuando la API falla (HTTP 500). */
    #[Test]
    public function get_top_anime_returns_empty_on_api_failure(): void
    {
        Http::fake(['api.jikan.moe/v4/top/anime*' => Http::response([], 500)]);

        $result = (new JikanService())->getTopAnime(1, 24);

        $this->assertIsArray($result);
        $this->assertEmpty($result['data']);
        $this->assertEmpty($result['pagination']);
    }

    /** #3 — searchAnime retorna resultados correctos con Http::fake(). */
    #[Test]
    public function search_anime_returns_results_on_success(): void
    {
        Http::fake([
            'api.jikan.moe/v4/anime*' => Http::response([
                'data' => [
                    ['mal_id' => 20,   'title' => 'Naruto',            'score' => 8.0,  'type' => 'TV', 'images' => ['jpg' => ['image_url' => 'https://example.com/n.jpg']]],
                    ['mal_id' => 1735, 'title' => 'Naruto: Shippuuden', 'score' => 8.26, 'type' => 'TV', 'images' => ['jpg' => ['image_url' => 'https://example.com/n2.jpg']]],
                ],
                'pagination' => ['has_next_page' => false, 'last_visible_page' => 1],
            ], 200),
        ]);

        $result = (new JikanService())->searchAnime('naruto', 1, 20);

        $this->assertIsArray($result);
        $this->assertCount(2, $result['data']);
        $this->assertEquals('Naruto', $result['data'][0]['title']);
        $this->assertEquals(20, $result['data'][0]['mal_id']);
    }

    /** #4 — searchAnime retorna vacío si la API devuelve 429 (rate limit). */
    #[Test]
    public function search_anime_returns_empty_on_rate_limit(): void
    {
        Http::fake(['api.jikan.moe/v4/anime*' => Http::response(['message' => 'Too Many Requests'], 429)]);

        $result = (new JikanService())->searchAnime('naruto', 1, 20);

        $this->assertIsArray($result);
        $this->assertEmpty($result['data']);
    }

    /** #5 — getAnimeById retorna detalles correctos cuando la API responde (HTTP 200). */
    #[Test]
    public function get_anime_by_id_returns_detail_on_success(): void
    {
        Http::fake([
            'api.jikan.moe/v4/anime/5114*' => Http::response([
                'data' => [
                    'mal_id'   => 5114,
                    'title'    => 'Fullmetal Alchemist: Brotherhood',
                    'synopsis' => 'Two brothers search for the philosopher stone.',
                    'score'    => 9.11,
                    'type'     => 'TV',
                    'episodes' => 64,
                    'status'   => 'Finished Airing',
                    'images'   => ['jpg' => ['image_url' => 'https://example.com/fma.jpg']],
                    'genres'   => [['name' => 'Action'], ['name' => 'Adventure']],
                ],
            ], 200),
            'api.mymemory.translated.net/*' => Http::response([
                'responseStatus' => 200,
                'responseData'   => ['translatedText' => 'Dos hermanos buscan la piedra filosofal.'],
            ], 200),
        ]);

        $result = (new JikanService())->getAnimeById(5114);

        $this->assertIsArray($result);
        $this->assertNotNull($result['data']);
        $this->assertEquals(5114, $result['data']['mal_id']);
        $this->assertEquals('Fullmetal Alchemist: Brotherhood', $result['data']['title']);
        $this->assertEquals(64, $result['data']['episodes']);
        $this->assertContains('Action', array_column($result['data']['genres'], 'name'));
    }

    /** #6 — getAnimeById retorna data null cuando el anime no existe (HTTP 404). */
    #[Test]
    public function get_anime_by_id_returns_null_on_not_found(): void
    {
        Http::fake(['api.jikan.moe/v4/anime/999999*' => Http::response(['error' => 'Not Found'], 404)]);

        $result = (new JikanService())->getAnimeById(999999);

        $this->assertIsArray($result);
        $this->assertNull($result['data']);
    }

    /** #7 — El caché evita llamadas repetidas; la API se llama solo una vez. */
    #[Test]
    public function get_top_anime_is_cached_and_api_called_only_once(): void
    {
        Http::fake([
            'api.jikan.moe/v4/top/anime*' => Http::response([
                'data'       => [['mal_id' => 1, 'title' => 'Cowboy Bebop']],
                'pagination' => [],
            ], 200),
        ]);

        $service = new JikanService();
        $first   = $service->getTopAnime(1, 24);
        $second  = $service->getTopAnime(1, 24);

        $this->assertEquals($first, $second);
        Http::assertSentCount(1);
    }
}
