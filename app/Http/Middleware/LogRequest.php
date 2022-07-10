<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LogRequest
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\JsonResponse
     */
    public function handle(Request $request, Closure $next): mixed {
        return $next($request);
    }

    public function terminate(Request $request, mixed $response): void {
        if (is_a($response, 'JsonResponse')) {
            Log::info('app.request', ['request' => $request->all(), 'response' => json_decode($response->getContent(), true)]);
        } else {
            Log::info('app.request', ['request' => $request->all(), 'response' => $response]);
        }
    }
}
