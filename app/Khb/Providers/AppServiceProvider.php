<?php

namespace App\Khb\Providers;

use App\Harvest\Harvesters\ItemHarvester;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register() {
        $this->app->bind(ItemHarvester::class, \App\Khb\Harvest\Harvesters\ItemHarvester::class);
    }
}