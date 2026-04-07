<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        /** @var \Symfony\Component\HttpFoundation\Response $response */
        $response = $next($request);

        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('Referrer-Policy', config('security.referrer_policy', 'strict-origin-when-cross-origin'));
        $response->headers->set('Permissions-Policy', config('security.permissions_policy'));
        $response->headers->set('Cross-Origin-Resource-Policy', config('security.cross_origin_resource_policy', 'same-site'));

        if ($policy = config('security.content_security_policy')) {
            $response->headers->set('Content-Security-Policy', $policy);
        }

        if ($request->isSecure() || app()->environment('production')) {
            $response->headers->set(
                'Strict-Transport-Security',
                'max-age='.config('security.hsts_max_age', 31536000).'; includeSubDomains; preload'
            );
        }

        return $response;
    }
}
