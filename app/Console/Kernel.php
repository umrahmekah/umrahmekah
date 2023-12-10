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
        Commands\Inspire::class,
        Commands\CronIncompletepayment::class,
        Commands\MigrateImportSqlCommand::class,
        Commands\ReloadAllCommand::class,
        Commands\ReloadCacheCommand::class,
        Commands\ReloadDbCommand::class,
        Commands\SeedDevelopmentDataCommand::class,
        Commands\BirthdayEmail::class,
        Commands\RamadhanBlast::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('inspire')
                 ->hourly();

        $schedule->command('mail:incomplete')
                 ->daily();

        $schedule->command('mail:birthday')
                 ->daily();
    }
}
