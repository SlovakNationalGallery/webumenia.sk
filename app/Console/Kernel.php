<?php namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{

    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        \App\Console\Commands\OaiPmhHarvest::class,
        \App\Console\Commands\ReindexElasticsearch::class,
        \App\Console\Commands\SetupElasticsearch::class,
        \App\Console\Commands\MakeSitemap::class,
        \App\Console\Commands\TestMemory::class,
        \App\Console\Commands\MakeSketchbook::class,
        \App\Console\Commands\ImportTags::class,
        \App\Console\Commands\Inspire::class,
        \App\Console\Commands\OaiPmhDownloadImages::class,
        \App\Console\Commands\MigrateTags::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('inspire')
                 ->hourly();
    }
}
