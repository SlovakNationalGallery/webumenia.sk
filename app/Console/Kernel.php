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
        'App\Console\Commands\OaiPmhHarvest',
        'App\Console\Commands\ReindexElasticsearch',
        'App\Console\Commands\MakeSitemap',
        'App\Console\Commands\TestMemory',
        'App\Console\Commands\MakeSketchbook',
        'App\Console\Commands\ImportTags',
        'App\Console\Commands\Inspire',
        'App\Console\Commands\OaiPmhDownloadImages',
        'App\Console\Commands\MigrateTags',
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
