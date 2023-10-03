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
        return view('frontend.catalog-new.index-new');
    }
}
