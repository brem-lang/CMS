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
                'device_type' => $this->detectDeviceType($request),
                'visited_at' => today(),
            ]);
        }
    }

    /**
     * Detect whether the request is from a mobile, tablet, or desktop device.
     */
    protected function detectDeviceType(Request $request): string
    {
        $userAgent = $request->userAgent() ?? '';

        // Expanded pattern to catch common mobile/tablet strings
        $mobilePatterns = '/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i';

        if (preg_match($mobilePatterns, $userAgent)) {
            return 'mobile';
        }

        // Explicit check for iPads/Tablets
        if (preg_match('/ipad|tablet|playbook|silk/i', $userAgent)) {
            return 'tablet';
        }

        return 'desktop';
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
