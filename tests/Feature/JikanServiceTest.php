<?php

namespace Tests\Feature;

use App\Services\JikanService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
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
        // Limpiar caché antes de cada prueba para evitar resultados cacheados
        Cache::flush();
    }

    // =========================================================
    // getTopAnime
    // =========================================================

    /**
     * @test
     * Verifica que getTopAnime retorna los datos correctos
     * cuando la API responde exitosamente (HTTP 200).
     */
    public function test_get_top_anime_returns_data_on_success(): void
    {
        // ARRANGE: Simular respuesta exitosa de la API Jikan
        Http::fake([
            'api.jikan.moe/v4/top/anime*' => Http::response([
                'data' => [
                    [
                        'mal_id' => 5114,
                        'title'  => 'Fullmetal Alchemist: Brotherhood',
                        'score'  => 9.11,
                        'rank'   => 1,
                        'type'   => 'TV',
                        'images' => ['jpg' => ['image_url' => 'https://example.com/fma.jpg']],
                    ],
                ],
                'pagination' => [
                    'has_next_page'      => true,
                    'last_visible_page'  => 5,
                ],
            ], 200),
        ]);

        // ACT: Llamar al servicio
        $service = new JikanService();
        $result  = $service->getTopAnime(1, 24);

        // ASSERT: Verificar la estructura y contenido de la respuesta
        $this->assertIsArray($result);
        $this->assertArrayHasKey('data', $result);
        $this->assertArrayHasKey('pagination', $result);
        $this->assertCount(1, $result['data']);
        $this->assertEquals('Fullmetal Alchemist: Brotherhood', $result['data'][0]['title']);
        $this->assertEquals(9.11, $result['data'][0]['score']);
        $this->assertEquals(1, $result['data'][0]['rank']);
    }

    /**
     * @test
     * Verifica que getTopAnime retorna arrays vacíos cuando la API falla (HTTP 500).
     */
    public function test_get_top_anime_returns_empty_on_api_failure(): void
    {
        // ARRANGE: Simular error del servidor
        Http::fake([
            'api.jikan.moe/v4/top/anime*' => Http::response([], 500),
        ]);

        // ACT
        $service = new JikanService();
        $result  = $service->getTopAnime(1, 24);

        // ASSERT: El servicio maneja el error y devuelve estructura vacía
        $this->assertIsArray($result);
        $this->assertArrayHasKey('data', $result);
        $this->assertArrayHasKey('pagination', $result);
        $this->assertEmpty($result['data']);
        $this->assertEmpty($result['pagination']);
    }

    // =========================================================
    // searchAnime
    // =========================================================

    /**
     * @test
     * Verifica que searchAnime retorna resultados correctos con Http::fake().
     */
    public function test_search_anime_returns_results_on_success(): void
    {
        // ARRANGE
        Http::fake([
            'api.jikan.moe/v4/anime*' => Http::response([
                'data' => [
                    [
                        'mal_id' => 20,
                        'title'  => 'Naruto',
                        'score'  => 8.0,
                        'type'   => 'TV',
                        'images' => ['jpg' => ['image_url' => 'https://example.com/naruto.jpg']],
                    ],
                    [
                        'mal_id' => 1735,
                        'title'  => 'Naruto: Shippuuden',
                        'score'  => 8.26,
                        'type'   => 'TV',
                        'images' => ['jpg' => ['image_url' => 'https://example.com/naruto2.jpg']],
                    ],
                ],
                'pagination' => ['has_next_page' => false, 'last_visible_page' => 1],
            ], 200),
        ]);

        // ACT
        $service = new JikanService();
        $result  = $service->searchAnime('naruto', 1, 20);

        // ASSERT
        $this->assertIsArray($result);
        $this->assertArrayHasKey('data', $result);
        $this->assertCount(2, $result['data']);
        $this->assertEquals('Naruto', $result['data'][0]['title']);
        $this->assertEquals(20, $result['data'][0]['mal_id']);
    }

    /**
     * @test
     * Verifica que searchAnime retorna vacío si la API devuelve error 429 (rate limit).
     */
    public function test_search_anime_returns_empty_on_rate_limit(): void
    {
        // ARRANGE: Simular Too Many Requests
        Http::fake([
            'api.jikan.moe/v4/anime*' => Http::response(['message' => 'Too Many Requests'], 429),
        ]);

        // ACT
        $service = new JikanService();
        $result  = $service->searchAnime('naruto', 1, 20);

        // ASSERT
        $this->assertIsArray($result);
        $this->assertEmpty($result['data']);
    }

    // =========================================================
    // getAnimeById
    // =========================================================

    /**
     * @test
     * Verifica que getAnimeById retorna los detalles correctos del anime
     * cuando la API responde con HTTP 200.
     */
    public function test_get_anime_by_id_returns_detail_on_success(): void
    {
        // ARRANGE: Simular detalle de un anime específico
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
            // Bloquear la llamada de traducción para no depender de MyMemory en tests
            'api.mymemory.translated.net/*' => Http::response([
                'responseStatus' => 200,
                'responseData'   => ['translatedText' => 'Dos hermanos buscan la piedra filosofal.'],
            ], 200),
        ]);

        // ACT
        $service = new JikanService();
        $result  = $service->getAnimeById(5114);

        // ASSERT
        $this->assertIsArray($result);
        $this->assertArrayHasKey('data', $result);
        $this->assertNotNull($result['data']);
        $this->assertEquals(5114, $result['data']['mal_id']);
        $this->assertEquals('Fullmetal Alchemist: Brotherhood', $result['data']['title']);
        $this->assertEquals(64, $result['data']['episodes']);
        $this->assertContains('Action', array_column($result['data']['genres'], 'name'));
    }

    /**
     * @test
     * Verifica que getAnimeById retorna data null cuando el anime no existe (HTTP 404).
     */
    public function test_get_anime_by_id_returns_null_on_not_found(): void
    {
        // ARRANGE
        Http::fake([
            'api.jikan.moe/v4/anime/999999*' => Http::response(['error' => 'Not Found'], 404),
        ]);

        // ACT
        $service = new JikanService();
        $result  = $service->getAnimeById(999999);

        // ASSERT
        $this->assertIsArray($result);
        $this->assertArrayHasKey('data', $result);
        $this->assertNull($result['data']);
    }

    // =========================================================
    // Caché
    // =========================================================

    /**
     * @test
     * Verifica que el caché evita llamadas repetidas a la API Jikan.
     * La segunda llamada con los mismos parámetros NO debe generar una nueva petición HTTP.
     */
    public function test_get_top_anime_is_cached_and_api_called_only_once(): void
    {
        // ARRANGE
        Http::fake([
            'api.jikan.moe/v4/top/anime*' => Http::response([
                'data'       => [['mal_id' => 1, 'title' => 'Cowboy Bebop']],
                'pagination' => [],
            ], 200),
        ]);

        $service = new JikanService();

        // ACT: Llamar dos veces con los mismos parámetros
        $first  = $service->getTopAnime(1, 24);
        $second = $service->getTopAnime(1, 24);

        // ASSERT: Ambas respuestas son iguales
        $this->assertEquals($first, $second);

        // La API solo debió ser llamada UNA vez (el resto vino de caché)
        Http::assertSentCount(1);
    }
}
