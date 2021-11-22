<?php namespace App\Providers;

use App\Events\ItemPrimaryImageChanged;
use App\Listeners\ItemPrimaryImageChangedListener;
use App\Listeners\ResolveRouteWasHit;
use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Spatie\MissingPageRedirector\Events\RouteWasHit;

class EventServiceProvider extends ServiceProvider
{

    /**
     * The event handler mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'event.name' => [
            'EventListener',
        ],
        ItemPrimaryImageChanged::class => [
            ItemPrimaryImageChangedListener::class,
        ],
        RouteWasHit::class => [
            ResolveRouteWasHit::class,
        ],
    ];
}
