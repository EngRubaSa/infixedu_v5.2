<?php

namespace App\Console;
 
use App\Console\Commands\DemoCron;
use App\Console\Commands\QueueWork;
use App\Http\Middleware\CustomerMiddleware;
use Illuminate\Console\Scheduling\Schedule; 
use App\Http\Middleware\PurchaseVerification;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
   
    protected $commands = [
        Commands\DemoCron::class,
    ];


    protected $PurchaseVerificationMiddleware =[
        'purchaseVerification' => \App\Http\Middleware\PurchaseVerification::class,
    ];

    //    protected $middleware = [
    //     \App\http\Middleware\CustomerMiddleware::class,
    // ];
    
    /**
     * Define the application's command schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('demo:cron')->everyMinute();

        //$schedule->command('queue:work')->everyMinute()->withoutOverlapping();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');
        require base_path('routes/console.php');
    }
}
