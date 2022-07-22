<?php

namespace App\Facades;

use App\Services\Experiment as ExperimentService;
use Illuminate\Support\Facades\Facade;

/*
 * A wrapper around session that helps with determining whether
 * to serve an 'experimental' feature.
 */
class Experiment extends Facade
{
    protected static function getFacadeAccessor()
    {
        return ExperimentService::class;
    }
}
