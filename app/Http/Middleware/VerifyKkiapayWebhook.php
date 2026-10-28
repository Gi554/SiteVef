<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyKkiapayWebhook
{
    public function handle(Request $request, Closure $next): Response
    {
        $secret = config('kkiapay.webhook_secret');
        abort_unless(is_string($secret) && $secret !== '', 503);
        abort_unless(hash_equals($secret, $request->header('x-kkiapay-secret', '')), 401);

        return $next($request);
    }
}
