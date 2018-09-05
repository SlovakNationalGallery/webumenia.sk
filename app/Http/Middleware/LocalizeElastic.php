<?php

namespace App\Http\Middleware;

use Closure;

class LocalizeElastic
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $elastic_translatable = \App::make('ElasticTranslatableService');
        $elastic_translatable->setCurrentIndex(\App::getLocale());

        return $next($request);
    }
}
