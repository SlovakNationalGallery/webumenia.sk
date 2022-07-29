<?php

namespace App\Jobs;

use App\Elasticsearch\Repositories\ItemRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Carbon;

class ReindexItemsViewedSince implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    private Carbon $since;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Carbon $since)
    {
        $this->since = $since;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        app(ItemRepository::class)->reindexAllViewedSince($this->since);
    }
}
