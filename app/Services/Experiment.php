<?php

namespace App\Services;

use Illuminate\Support\Facades\Session;

/*
 * A wrapper around session that helps with determining whether
 * to serve an 'experimental' feature.
 */
class Experiment
{
    public static function is($name)
    {
        return Session::get('experiment') === $name;
    }

    public static function set($name)
    {
        return Session::put('experiment', $name);
    }
}
