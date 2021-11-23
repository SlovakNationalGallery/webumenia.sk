<?php namespace App\Providers;

use App\Authority;
use App\Item;
use App\Redirect;
use App\Events\ItemPrimaryImageChanged;
use App\Listeners\ItemPrimaryImageChangedListener;
use App\Listeners\ResolveRouteWasHit;
use App\Observers\AuthorityObserver;
use App\Observers\ItemObserver;
use App\Observers\RedirectObserver;
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

    public function boot()
    {
        Authority::observe(AuthorityObserver::class);
        Item::observe(ItemObserver::class);
        Redirect::observe(RedirectObserver::class);
    }
}
