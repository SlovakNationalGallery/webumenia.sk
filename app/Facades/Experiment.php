<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class Experiment extends Facade
{
    public static function is($name)
    {
        return static::$app['request']->session()->get('experiment') === $name;
    }

    public static function set($name)
    {
        return static::$app['request']->session()->put('experiment', $name);
    }
}
