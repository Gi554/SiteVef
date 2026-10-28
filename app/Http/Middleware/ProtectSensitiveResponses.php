<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ProtectSensitiveResponses
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        if ($request->routeIs('admin.*', 'login', 'register', 'password.*', 'verification.*', 'donation.kkiapay*', 'kkiapay.webhook', 'profile.*', 'dashboard')) {
            $response->headers->set('Cache-Control', 'no-store, private');
            $response->headers->set('Referrer-Policy', 'no-referrer');
            $response->headers->set('X-Frame-Options', 'DENY');
            $response->headers->set('X-Robots-Tag', 'noindex, nofollow');
        }

        return $response;
    }
}
