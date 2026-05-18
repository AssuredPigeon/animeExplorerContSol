<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

/**
 * PerformanceService
 *
 * Medición de tiempos con microtime() similar al patrón:
 *   start = time.perf_counter()
 *   ...
 *   elapsed = time.perf_counter() - start
 *
 * Se usa para demostrar la mejora de rendimiento gracias al caché.
 */
class PerformanceService
{
    /** @var array<string, float> Tiempos de inicio indexados por etiqueta */
    private array $starts = [];

    /** @var array<string, float> Resultados en segundos */
    private array $results = [];

    /**
     * Inicia un temporizador con la etiqueta dada.
     */
    public function start(string $label): void
    {
        $this->starts[$label] = microtime(true);
    }

    /**
     * Detiene el temporizador y guarda el resultado.
     *
     * @return float Tiempo transcurrido en segundos
     */
    public function stop(string $label): float
    {
        if (!isset($this->starts[$label])) {
            return 0.0;
        }

        $elapsed = microtime(true) - $this->starts[$label];
        $this->results[$label] = $elapsed;
        unset($this->starts[$label]);

        return $elapsed;
    }

    /**
     * Detiene y registra el tiempo en el log de Laravel.
     */
    public function stopAndLog(string $label, string $context = ''): float
    {
        $elapsed = $this->stop($label);
        $ms      = round($elapsed * 1000, 2);
        $ctx     = $context ? " [{$context}]" : '';

        Log::info("[PerformanceService]{$ctx} {$label}: {$ms} ms");

        return $elapsed;
    }

    /**
     * Devuelve todos los resultados registrados en milisegundos.
     *
     * @return array<string, float>
     */
    public function getResultsMs(): array
    {
        $ms = [];
        foreach ($this->results as $label => $secs) {
            $ms[$label] = round($secs * 1000, 2);
        }
        return $ms;
    }

    /**
     * Devuelve un resultado específico en milisegundos (0 si no existe).
     */
    public function getMs(string $label): float
    {
        return isset($this->results[$label])
            ? round($this->results[$label] * 1000, 2)
            : 0.0;
    }

    /**
     * Mide el tiempo de ejecución de un Closure.
     *
     * Uso:
     *   [$result, $ms] = $perf->measure('mi_label', fn() => doSomething());
     *
     * @return array{mixed, float}  [$returnValue, $elapsedMs]
     */
    public function measure(string $label, callable $callback): array
    {
        $this->start($label);
        $result  = $callback();
        $elapsed = $this->stopAndLog($label);

        return [$result, round($elapsed * 1000, 2)];
    }
}
