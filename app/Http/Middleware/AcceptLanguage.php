<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AcceptLanguage
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $locale = $request->header('Accept-Language');
        if (in_array($locale, config('translatable.locales'), true)) {
            app()->setLocale($locale);
        }
        return $next($request);
    }
}