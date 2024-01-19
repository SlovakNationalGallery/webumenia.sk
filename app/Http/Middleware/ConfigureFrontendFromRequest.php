<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ConfigureFrontendFromRequest
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($frontend = $request->header('X-Frontend')) {
            config(['app.frontend' => $frontend]);
        }

        return $next($request);
    }
}
