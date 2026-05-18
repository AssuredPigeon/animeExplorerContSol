<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

/**
 * PerformanceLogger Middleware
 *
 * Mide el tiempo total de cada request HTTP y lo registra en el log.
 * Equivalente a:
 *   start = time.perf_counter()
 *   response = handle_request()
 *   print(f"Tiempo: {time.perf_counter() - start:.3f}s")
 */
class PerformanceLogger
{
    public function handle(Request $request, Closure $next): Response
    {
        $start = microtime(true);

        /** @var Response $response */
        $response = $next($request);

        $elapsed = microtime(true) - $start;
        $ms      = round($elapsed * 1000, 2);

        Log::info(sprintf(
            '[PerformanceLogger] %s %s → %d | %s ms',
            $request->method(),
            $request->path(),
            $response->getStatusCode(),
            $ms
        ));

        // Agregar header de debug (visible en DevTools / Debugbar)
        $response->headers->set('X-Response-Time-Ms', $ms);

        return $response;
    }
}
