<?php

namespace Tests;

use Illuminate\Support\Facades\Event;

trait WithoutSearchIndexing
{
    /**
     * Fakes all events, effectively disabling ItemObserver and AuthorityObserver
     *
     * @return void
     */
    public function disableModelSearchIndexing()
    {
        Event::fake();
    }
}
