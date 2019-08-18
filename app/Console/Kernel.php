<?php namespace App\Console;

use App\Jobs\HarvestJob;
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
        \App\Console\Commands\DescribeItemColors::class,
        \App\Console\Commands\ImportCsv::class,
        \App\Console\Commands\DescribeItemColors::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('sitemap:make')->daily();
        $schedule->call(function() {
            foreach (\App\SpiceHarvesterHarvest::where('cron_status', '=', 'daily')->get() as $harvest) {
                dispatch(new HarvestJob($harvest));
            }
        })->daily(); /* daily at midnight */
        $schedule->call(function() {
            foreach (\App\SpiceHarvesterHarvest::where('cron_status', '=', 'weekly')->get() as $harvest) {
                dispatch(new HarvestJob($harvest));
            }
        })->weeklyOn(6, '23:00'); /* sundays at 11pm */
    }
}
