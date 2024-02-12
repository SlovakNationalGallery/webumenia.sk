<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class Frontend extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \App\Services\Frontend::class;
    }
}