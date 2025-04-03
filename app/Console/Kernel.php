<?php

namespace App\Console;
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
        Commands\FetchQuestLabResults::class,
        Commands\SendReminderOneEmail::class,
        Commands\SendReminderTwoEmail::class,
        Commands\DoctorOnline::class,
        Commands\SendOrderEmail::class,
        Commands\SendCartReminder::class,
        Commands\CheckDoctorActivity::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('reminderone:emails')->everyMinute();
        $schedule->command('remindertwo:emails')->everyMinute();
        $schedule->command('check:doctor')->everyMinute();
        // $schedule->command('fetch:questLabResults')->everyFifteenMinutes();
        $schedule->command('sendorderemail:emails')->everyFiveMinutes();
        $schedule->command('cartreminder:emails')->weekly();
        $schedule->command('check:checkactivity')->everyMinute();
        // $schedule->command('sitemap:generate')->daily();
        // $schedule->command('report:payment')->daily();
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
