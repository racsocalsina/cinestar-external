<?php

namespace App\Console;

use App\Console\Commands\ResendEmailPurchase;
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
        ResendEmailPurchase::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('telescope:clear')->hourly()->withoutOverlapping();
        $schedule->command('purchase:release-ls')->everyFiveMinutes()->withoutOverlapping(5);
        $schedule->command('system:delete-junk-data')->dailyAt('03:00');
        $schedule->command('system:point-expiration-process')->dailyAt('00:00');
        $schedule->command('change:customer')->everyMinute()->withoutOverlapping();
        $schedule->command('passport:purge')->dailyAt('03:00');
        //$schedule->command('system:process-confirmed-purchases')->everyFiveMinutes()->withoutOverlapping();
        //$schedule->command('resend:voucher')->everyMinute();
        $schedule->command('retry:failed-purchases')->everyFiveMinutes()->withoutOverlapping();
        $schedule->command('horizon:snapshot')->everyFiveMinutes();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
