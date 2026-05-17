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
        // verify:false fixes cURL error 60 (SSL cert) on Windows local dev environments
        $this->client = new Client([
            'timeout' => 10.0,
            'verify'  => false,
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
                $response = $this->client->get($this->baseUrl.'/top/anime', [
                    'query' => [
                        'page' => $page,
                        'limit' => $limit,
                    ],
                ]);

                return json_decode($response->getBody()->getContents(), true);
            } catch (RequestException $e) {
                Log::error('Jikan API error (getTopAnime): '.$e->getMessage());

                return ['data' => [], 'pagination' => []];
            }
        });
    }

    /**
     * Search anime by query string.
     */
    public function searchAnime(string $query, int $page = 1, int $limit = 20): array
    {
        $cacheKey = 'search_anime_'.md5($query)."_page_{$page}";

        return Cache::remember($cacheKey, now()->addMinutes(15), function () use ($query, $page, $limit) {
            try {
                $response = $this->client->get($this->baseUrl.'/anime', [
                    'query' => [
                        'q' => $query,
                        'page' => $page,
                        'limit' => $limit,
                        'sfw' => true,
                    ],
                ]);

                return json_decode($response->getBody()->getContents(), true);
            } catch (RequestException $e) {
                Log::error('Jikan API error (searchAnime): '.$e->getMessage());

                return ['data' => [], 'pagination' => []];
            }
        });
    }

    /**
     * Get anime details by MAL ID (synopsis auto-translated to Spanish).
     */
    public function getAnimeById(int $id): array
    {
        $cacheKey = "anime_detail_{$id}";

        $result = Cache::remember($cacheKey, now()->addHours(6), function () use ($id) {
            try {
                $response = $this->client->get($this->baseUrl."/anime/{$id}");

                return json_decode($response->getBody()->getContents(), true);
            } catch (RequestException $e) {
                Log::error("Jikan API error (getAnimeById {$id}): ".$e->getMessage());

                return ['data' => null];
            }
        });

        // Translate synopsis to Spanish (cached separately for 30 days)
        if (! empty($result['data']['synopsis'])) {
            $result['data']['synopsis'] = $this->translateToSpanish($result['data']['synopsis']);
        }

        return $result;
    }

    /**
     * Translate text to Spanish using MyMemory free API (no key required).
     * Splits long texts into chunks to respect the 500-char limit per request.
     */
    protected function translateToSpanish(string $text): string
    {
        if (empty(trim($text))) {
            return $text;
        }

        $cacheKey = 'synopsis_es_'.md5($text);

        return Cache::remember($cacheKey, now()->addDays(30), function () use ($text) {
            // Split into sentences, grouping them into chunks < 490 chars
            $sentences = preg_split('/(?<=[.!?])\s+/', $text);
            $chunks = [];
            $current = '';

            foreach ($sentences as $sentence) {
                if (strlen($current) + strlen($sentence) + 1 > 490) {
                    if ($current !== '') {
                        $chunks[] = trim($current);
                    }
                    $current = $sentence;
                } else {
                    $current .= ($current ? ' ' : '').$sentence;
                }
            }
            if ($current !== '') {
                $chunks[] = trim($current);
            }

            $translated = [];

            foreach ($chunks as $chunk) {
                try {
                    $response = $this->client->get('https://api.mymemory.translated.net/get', [
                        'query' => [
                            'q' => $chunk,
                            'langpair' => 'en|es',
                        ],
                    ]);

                    $data = json_decode($response->getBody()->getContents(), true);
                    $status = $data['responseStatus'] ?? 0;

                    if ($status === 200 && ! empty($data['responseData']['translatedText'])) {
                        $translated[] = $data['responseData']['translatedText'];
                    } else {
                        $translated[] = $chunk; // fallback al original
                    }

                    // Pequeña pausa para respetar rate limits de la API
                    usleep(300000); // 300ms entre peticiones

                } catch (\Exception $e) {
                    Log::warning('Translation error: '.$e->getMessage());
                    $translated[] = $chunk;
                }
            }

            return implode(' ', $translated);
        });
    }
}
