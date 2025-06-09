<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DebugRequestMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Log request details only for lock-images routes
        if (str_contains($request->url(), 'lock-images') || str_contains($request->url(), 'emergency-lock')) {
            Log::info('DEBUG REQUEST:', [
                'method' => $request->method(),
                'url' => $request->url(),
                'path' => $request->path(),
                'headers' => $request->headers->all(),
                'route_name' => $request->route() ? $request->route()->getName() : 'No Route',
                'route_methods' => $request->route() ? $request->route()->getMethods() : 'No Methods',
            ]);
        }

        return $next($request);
    }
} 