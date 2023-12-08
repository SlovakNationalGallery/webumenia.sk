<?php

namespace App\Http\Middleware;

use App\Facades\Experiment;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class SetExperiments
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $request->whenFilled('experiment', function ($experiment) {
            if ($experiment === 'new-catalog' || $experiment === 'new-katalog') {
                return Experiment::set('new-catalog');
            }
        });

        return $next($request);
    }
}
