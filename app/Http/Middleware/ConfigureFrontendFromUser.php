<?php

namespace App\Http\Middleware;

use App\Enums\FrontendEnum;
use App\Facades\Frontend;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ConfigureFrontendFromUser
{
    public function handle(Request $request, Closure $next): Response
    {
        $frontend = !$request->user()->can_administer
            ? FrontendEnum::from($request->user()->frontend)
            : null;

        Frontend::set($frontend);

        return $next($request);
    }
}
