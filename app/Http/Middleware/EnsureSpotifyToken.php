<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSpotifyToken
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!session('spotify_user_token')) {
            return redirect()->route('home')->withErrors([
                'auth' => 'Silakan login dengan Spotify terlebih dahulu.'
            ]);
        }

        return $next($request);
    }
}
