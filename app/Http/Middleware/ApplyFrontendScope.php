<?php

namespace App\Http\Middleware;

use App\Collection;
use App\Facades\Frontend;
use App\Item;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApplyFrontendScope
{
    public function handle(Request $request, \Closure $next): Response
    {
        Collection::addGlobalScope('frontend', fn ($query) => $query->whereJsonContains('collections.frontends', Frontend::get()));
        Item::addGlobalScope('frontend', fn ($query) => $query->whereJsonContains('items.frontends', Frontend::get()));

        return $next($request);
    }
}