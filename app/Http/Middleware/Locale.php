<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class Locale
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $language = Session::get('website_language', config('app.locale'));
        // Get config from session, get default config if session is empty

        config(['app.locale' => $language]);
        // Change app language

        return $next($request);
    }
}
