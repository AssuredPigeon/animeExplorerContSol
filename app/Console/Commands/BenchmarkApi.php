<?php

namespace App\Console\Commands;

use App\Services\JikanService;
use App\Services\PerformanceService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

/**
 * php artisan anime:benchmark
 *
 * Compara el tiempo de respuesta SIN caché (llamada real a la API)
 * vs CON caché (segunda llamada, sirve desde memoria/archivo local).
 *
 * Esto demuestra la optimización de rendimiento requerida por el proyecto.
 */
class BenchmarkApi extends Command
{
    protected $signature = 'anime:benchmark';
    protected $description = 'Mide el rendimiento de la API Jikan con y sin caché';

    public function handle(JikanService $jikan, PerformanceService $perf): int
    {
        $this->info('');
        $this->info('╔══════════════════════════════════════════════════════╗');
        $this->info('║        Anime Explorer – Benchmark de Rendimiento     ║');
        $this->info('╚══════════════════════════════════════════════════════╝');
        $this->newLine();

        // ── 1. Sin caché (limpiar antes de medir) ───────────────────────
        $this->line('🔴  Midiendo SIN caché (llamada real a la API Jikan)...');
        Cache::flush();

        [$result1, $msNocache] = $perf->measure('sin_cache', fn() => $jikan->getTopAnime(1, 5));

        $countNocache = count($result1['data'] ?? []);
        $this->line("    → Animes recibidos : {$countNocache}");
        $this->line("    → Tiempo transcurrido : <fg=red>{$msNocache} ms</>");

        $this->newLine();

        // ── 2. Con caché (segunda llamada, debe venir del caché) ─────────
        $this->line('🟢  Midiendo CON caché (segunda llamada, sin llamada HTTP)...');

        [$result2, $msCache] = $perf->measure('con_cache', fn() => $jikan->getTopAnime(1, 5));

        $countCache = count($result2['data'] ?? []);
        $this->line("    → Animes recibidos : {$countCache}");
        $this->line("    → Tiempo transcurrido : <fg=green>{$msCache} ms</>");

        $this->newLine();

        // ── 3. Resumen ───────────────────────────────────────────────────
        $mejora = $msNocache > 0
            ? round((($msNocache - $msCache) / $msNocache) * 100, 1)
            : 0;

        $factor = $msCache > 0 ? round($msNocache / $msCache, 1) : '∞';

        $this->info('┌─────────────────────────────────────────────┐');
        $this->info('│             RESUMEN DE RENDIMIENTO           │');
        $this->info('├─────────────────────────────────────────────┤');
        $this->info("│  Sin caché  : {$msNocache} ms");
        $this->info("│  Con caché  : {$msCache} ms");
        $this->info("│  Mejora     : {$mejora}% más rápido");
        $this->info("│  Factor     : {$factor}x");
        $this->info('└─────────────────────────────────────────────┘');
        $this->newLine();

        // ── 4. Benchmark de Búsqueda ─────────────────────────────────────
        $this->line('Benchmark de Búsqueda — "Chainsaw Man"...');
        Cache::flush();

        [$sRes, $msSinCache] = $perf->measure('search_sin_cache', fn() => $jikan->searchAnime('Chainsaw Man', 1, 5));
        $this->line("    → Sin caché : <fg=red>{$msSinCache} ms</>");

        [$sRes2, $msConCache] = $perf->measure('search_con_cache', fn() => $jikan->searchAnime('Chainsaw Man', 1, 5));
        $this->line("    → Con caché : <fg=green>{$msConCache} ms</>");

        $this->newLine();
        $this->info('Benchmark completado. Resultados guardados en storage/logs/laravel.log');

        return Command::SUCCESS;
    }
}
