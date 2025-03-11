<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;
class MeasureResponseTime
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $startTime = microtime(true);

        $response = $next($request);

        $endTime = microtime(true);

        $executionTime = $endTime - $startTime;

        $response->headers->set('X-Execution-Time', $executionTime . ' seconds');

        Log::info('Tiempo de respuesta: ' . $executionTime . ' segundos', [
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'status' => $response->status(),
        ]);

        return $response;
    }
}
