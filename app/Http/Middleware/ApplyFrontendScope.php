<?php

namespace App\Http\Middleware;

use App\Facades\Frontend;
use App\Item;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApplyFrontendScope
{
    public function handle(Request $request, \Closure $next): Response
    {
        Item::addGlobalScope('frontend', fn ($query) => $query->whereJsonContains('frontends', Frontend::get()));

        return $next($request);
    }
}