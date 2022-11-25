<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Authority;

class NewCatalogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $authors = Authority::latest()->take(10)->get();
        return view('frontend.catalog-new.index-new',['authors' => $authors]);
    }
}
