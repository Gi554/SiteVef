<?php

namespace App\Http\Middleware;

use App\Models\Visite;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrackVisite
{
    public function handle(Request $request, Closure $next): Response
    {
        // Ne tracker que les pages publiques GET (pas admin, pas API, pas assets)
        $response = $next($request);
        if ($request->isMethod('GET') && $response->isSuccessful()
            && ! $request->routeIs('admin.*', 'dashboard', 'login', 'register', 'profile.*', 'password.*', 'verification.*', 'donation.kkiapay*', 'donation.success')) {
            $today = now()->toDateString();
            $ip = $request->ip();

            // Une seule entrée par IP par jour (visiteur unique journalier)
            Visite::firstOrCreate(
                ['date' => $today, 'ip_address' => $ip],
                ['page' => $request->path()]
            );
        }

        return $response;
    }
}
