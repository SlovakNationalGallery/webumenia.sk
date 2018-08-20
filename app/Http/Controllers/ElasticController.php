<?php

namespace App\Http\Controllers;

class ElasticController extends Controller
{
    protected $elastic_translatable;

    function __construct()
    {
        $this->elastic_translatable = \App::make('ElasticTranslatableService');
    }
}
