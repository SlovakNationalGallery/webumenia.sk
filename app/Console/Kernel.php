<?php namespace App\Console;

use App\Jobs\HarvestJob;
use App\Jobs\ReindexItemsViewedSince;
use App\SpiceHarvesterHarvest;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Carbon;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('sitemap:make')->daily();

        $schedule
            ->call(function () {
                foreach (
                    SpiceHarvesterHarvest::where('cron_status', '=', 'daily')->get()
                    as $harvest
                ) {
                    dispatch(new HarvestJob($harvest));
                }
            })
            ->daily(); /* daily at midnight */

        $schedule
            ->call(function () {
                foreach (
                    SpiceHarvesterHarvest::where('cron_status', '=', 'weekly')->get()
                    as $harvest
                ) {
                    dispatch(new HarvestJob($harvest));
                }
            })
            ->weeklyOn(6, '23:00'); /* sundays at 11pm */

        $schedule
            ->call(function () {
                $lastDayAndABit = Carbon::now()->subHours(25);
                dispatch(new ReindexItemsViewedSince($lastDayAndABit));
            })
            ->daily();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
