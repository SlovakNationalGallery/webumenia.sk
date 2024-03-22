<?php

namespace App\Http\Middleware;

use App\Enums\FrontendEnum;
use App\Facades\Frontend;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ConfigureFrontendFromRequest
{
    public function handle(Request $request, Closure $next): Response
    {
        $frontend = FrontendEnum::tryFrom($request->header('X-Frontend'));
        if ($frontend) {
            Frontend::set($frontend);
        }

        return $next($request);
    }
}
