<?php

namespace App\Providers;

use App\Authority;
use App\Item;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [SendEmailVerificationNotification::class],
        ItemPrimaryImageChanged::class => [ItemPrimaryImageChangedListener::class],
        RouteWasHit::class => [IncreaseRedirectCounter::class],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        Authority::observe(AuthorityObserver::class);
        Item::observe(ItemObserver::class);

        // disable scout observers
        Item::disableSearchSyncing();
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    public function shouldDiscoverEvents()
    {
        return false;
    }
}
