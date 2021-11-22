<?php

namespace App\Listeners;

use App\Models\Page;
use App\Redirect;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Spatie\MissingPageRedirector\Events\RouteWasHit;

class ResolveRouteWasHit
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Handle the event.
     *
     * @param  RouteWasHit  $event
     * @return void
     */
    public function handle(RouteWasHit $event)
    {
        $redirect = Redirect::where('source_url', $event->missingUrl)->increment('counter');
    }
}
