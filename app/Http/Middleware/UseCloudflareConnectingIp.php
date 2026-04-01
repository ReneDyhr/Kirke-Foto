<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class UseCloudflareConnectingIp
{
    /**
     * Ensure Laravel sees the real client IP when traffic comes through Cloudflare.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $cfConnectingIp = $request->headers->get('CF-Connecting-IP');

        if (\is_string($cfConnectingIp) && \filter_var($cfConnectingIp, \FILTER_VALIDATE_IP) !== false) {
            $request->server->set('HTTP_X_FORWARDED_FOR', $cfConnectingIp);
            $request->server->set('REMOTE_ADDR', $cfConnectingIp);
        }

        return $next($request);
    }
}
