<?php namespace App\Providers;

use App\Authority;
use App\Item;
use App\Events\ItemPrimaryImageChanged;
use App\Listeners\ItemPrimaryImageChangedListener;
use App\Listeners\IncreaseRedirectCounter;
use App\Observers\AuthorityObserver;
use App\Observers\ItemObserver;
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
            IncreaseRedirectCounter::class,
        ],
    ];

    public function boot()
    {
        Authority::observe(AuthorityObserver::class);
        Item::observe(ItemObserver::class);

        // disable scout observers
        Item::disableSearchSyncing();
    }
}
