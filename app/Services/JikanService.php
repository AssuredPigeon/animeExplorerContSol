<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class JikanService
{
    protected Client $client;
    protected string $baseUrl = 'https://api.jikan.moe/v4';

    public function __construct()
    {
        // NOTE: no base_uri here – we build full URLs to avoid Guzzle path-resolution issues
        $this->client = new Client([
            'timeout' => 10.0,
        ]);
    }

    /**
     * Get the top anime list.
     */
    public function getTopAnime(int $page = 1, int $limit = 24): array
    {
        $cacheKey = "top_anime_page_{$page}_limit_{$limit}";

        return Cache::remember($cacheKey, now()->addMinutes(30), function () use ($page, $limit) {
            try {
                $response = $this->client->get($this->baseUrl . '/top/anime', [
                    'query' => [
                        'page'  => $page,
                        'limit' => $limit,
                    ],
                ]);

                return json_decode($response->getBody()->getContents(), true);
            } catch (RequestException $e) {
                Log::error('Jikan API error (getTopAnime): ' . $e->getMessage());
                return ['data' => [], 'pagination' => []];
            }
        });
    }

    /**
     * Search anime by query string.
     */
    public function searchAnime(string $query, int $page = 1, int $limit = 20): array
    {
        $cacheKey = "search_anime_" . md5($query) . "_page_{$page}";

        return Cache::remember($cacheKey, now()->addMinutes(15), function () use ($query, $page, $limit) {
            try {
                $response = $this->client->get($this->baseUrl . '/anime', [
                    'query' => [
                        'q'     => $query,
                        'page'  => $page,
                        'limit' => $limit,
                        'sfw'   => true,
                    ],
                ]);

                return json_decode($response->getBody()->getContents(), true);
            } catch (RequestException $e) {
                Log::error('Jikan API error (searchAnime): ' . $e->getMessage());
                return ['data' => [], 'pagination' => []];
            }
        });
    }

    /**
     * Get anime details by MAL ID.
     */
    public function getAnimeById(int $id): array
    {
        $cacheKey = "anime_detail_{$id}";

        return Cache::remember($cacheKey, now()->addHours(6), function () use ($id) {
            try {
                $response = $this->client->get($this->baseUrl . "/anime/{$id}");
                return json_decode($response->getBody()->getContents(), true);
            } catch (RequestException $e) {
                Log::error("Jikan API error (getAnimeById {$id}): " . $e->getMessage());
                return ['data' => null];
            }
        });
    }
}
