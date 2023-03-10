<?php

namespace App\Http\Controllers;

use App\Authority;
use App\Item;
use Illuminate\Support\Facades\Cache;

class NewCatalogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $authors = Authority::latest()
            ->take(10)
            ->get();

        $yearLimits = Cache::remember(
            'items.year-limits',
            now()->addHours(1),
            fn() => Item::getYearLimits()
        );

        return view('frontend.catalog-new.index-new', [
            'authors' => $authors,
            'yearLimits' => $yearLimits,
        ]);
    }
}
