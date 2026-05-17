<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class JikanService
{
    protected string $baseUrl = 'https://api.jikan.moe/v4';

    // ── Helper: Debugbar measure (no-op when Debugbar is disabled/not present) ──

    private function dbStart(string $key, string $label): void
    {
        if (class_exists(\Debugbar::class) && app()->bound('debugbar')) {
            \Debugbar::startMeasure($key, $label);
        }
    }

    private function dbStop(string $key): void
    {
        if (class_exists(\Debugbar::class) && app()->bound('debugbar')) {
            try {
                \Debugbar::stopMeasure($key);
            } catch (\Exception) {
                // silently ignore if measure was never started
            }
        }
    }

    /**
     * Get the top anime list.
     */
    public function getTopAnime(int $page = 1, int $limit = 24): array
    {
        $cacheKey = "top_anime_page_{$page}_limit_{$limit}";

        // ── Measure total resolution time (cache hit OR miss) ──
        $this->dbStart('top_anime_total', "getTopAnime(page={$page}) — total");

        $result = Cache::remember($cacheKey, now()->addMinutes(30), function () use ($page, $limit) {
            // ── Only runs on cache MISS ──
            $this->dbStart('top_anime_http', 'Jikan HTTP — /top/anime (cache MISS)');

            try {
                $response = Http::withoutVerifying()
                    ->timeout(10)
                    ->get("{$this->baseUrl}/top/anime", [
                        'page'  => $page,
                        'limit' => $limit,
                    ]);

                $this->dbStop('top_anime_http');

                if ($response->successful()) {
                    return $response->json();
                }

                Log::error('Jikan API error (getTopAnime): HTTP ' . $response->status());

                return ['data' => [], 'pagination' => []];
            } catch (\Exception $e) {
                $this->dbStop('top_anime_http');
                Log::error('Jikan API error (getTopAnime): ' . $e->getMessage());

                return ['data' => [], 'pagination' => []];
            }
        });

        $this->dbStop('top_anime_total');

        return $result;
    }

    /**
     * Search anime by query string.
     */
    public function searchAnime(string $query, int $page = 1, int $limit = 20): array
    {
        $cacheKey = 'search_anime_' . md5($query) . "_page_{$page}";

        $this->dbStart('search_anime_total', "searchAnime('{$query}') — total");

        $result = Cache::remember($cacheKey, now()->addMinutes(15), function () use ($query, $page, $limit) {
            $this->dbStart('search_anime_http', "Jikan HTTP — /anime?q={$query} (cache MISS)");

            try {
                $response = Http::withoutVerifying()
                    ->timeout(10)
                    ->get("{$this->baseUrl}/anime", [
                        'q'     => $query,
                        'page'  => $page,
                        'limit' => $limit,
                        'sfw'   => true,
                    ]);

                $this->dbStop('search_anime_http');

                if ($response->successful()) {
                    return $response->json();
                }

                Log::error('Jikan API error (searchAnime): HTTP ' . $response->status());

                return ['data' => [], 'pagination' => []];
            } catch (\Exception $e) {
                $this->dbStop('search_anime_http');
                Log::error('Jikan API error (searchAnime): ' . $e->getMessage());

                return ['data' => [], 'pagination' => []];
            }
        });

        $this->dbStop('search_anime_total');

        return $result;
    }

    /**
     * Get anime details by MAL ID (synopsis auto-translated to Spanish).
     */
    public function getAnimeById(int $id): array
    {
        $cacheKey = "anime_detail_{$id}";

        $this->dbStart('anime_detail_total', "getAnimeById({$id}) — total");

        $result = Cache::remember($cacheKey, now()->addHours(6), function () use ($id) {
            $this->dbStart('anime_detail_http', "Jikan HTTP — /anime/{$id} (cache MISS)");

            try {
                $response = Http::withoutVerifying()
                    ->timeout(10)
                    ->get("{$this->baseUrl}/anime/{$id}");

                $this->dbStop('anime_detail_http');

                if ($response->successful()) {
                    return $response->json();
                }

                Log::error("Jikan API error (getAnimeById {$id}): HTTP " . $response->status());

                return ['data' => null];
            } catch (\Exception $e) {
                $this->dbStop('anime_detail_http');
                Log::error("Jikan API error (getAnimeById {$id}): " . $e->getMessage());

                return ['data' => null];
            }
        });

        $this->dbStop('anime_detail_total');

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

        $cacheKey = 'synopsis_es_' . md5($text);

        return Cache::remember($cacheKey, now()->addDays(30), function () use ($text) {
            $sentences = preg_split('/(?<=[.!?])\s+/', $text);
            $chunks    = [];
            $current   = '';

            foreach ($sentences as $sentence) {
                if (strlen($current) + strlen($sentence) + 1 > 490) {
                    if ($current !== '') {
                        $chunks[] = trim($current);
                    }
                    $current = $sentence;
                } else {
                    $current .= ($current ? ' ' : '') . $sentence;
                }
            }
            if ($current !== '') {
                $chunks[] = trim($current);
            }

            $translated = [];

            foreach ($chunks as $chunk) {
                try {
                    $response = Http::withoutVerifying()
                        ->timeout(8)
                        ->get('https://api.mymemory.translated.net/get', [
                            'q'        => $chunk,
                            'langpair' => 'en|es',
                        ]);

                    $data   = $response->json();
                    $status = $data['responseStatus'] ?? 0;

                    if ($status === 200 && ! empty($data['responseData']['translatedText'])) {
                        $translated[] = $data['responseData']['translatedText'];
                    } else {
                        $translated[] = $chunk;
                    }

                    usleep(300000);
                } catch (\Exception $e) {
                    Log::warning('Translation error: ' . $e->getMessage());
                    $translated[] = $chunk;
                }
            }

            return implode(' ', $translated);
        });
    }
}
