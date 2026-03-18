<?php

namespace App\Http\Middleware;

use App\Models\Visit;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RecordVisit
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        return $next($request);
    }

    /**
     * Record the visit after the response is sent to avoid slowing the request.
     */
    public function terminate(Request $request, Response $response): void
    {
        if ($this->shouldSkip($request)) {
            return;
        }

        $ip = $request->ip();
        $url = $request->fullUrl();

        if (! $ip || ! $url) {
            return;
        }

        $exists = Visit::where('ip_address', $ip)
            ->whereDate('visited_at', today())
            ->exists();

        if (! $exists) {
            Visit::create([
                'ip_address' => $ip,
                'url' => $url,
                'visited_at' => today(),
            ]);
        }
    }

    /**
     * Determine if the request should be skipped.
     */
    protected function shouldSkip(Request $request): bool
    {
        return $request->is('admin*')
            || $request->is('api*')
            || $request->is('webhook');
    }
}
