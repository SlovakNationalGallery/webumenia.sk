<?php

namespace App\Http\Middleware;

use App\Enums\FrontendEnum;
use App\Facades\Frontend;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class ConfigureFrontendFromUser
{
    public function handle(Request $request, Closure $next): Response
    {
        $frontend = Gate::denies('administer')
            ? FrontendEnum::from($request->user()->frontend)
            : null;

        Frontend::set($frontend);

        return $next($request);
    }
}
