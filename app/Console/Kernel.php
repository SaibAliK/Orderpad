<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Models\Rx;
use Carbon\Carbon;

class Kernel extends ConsoleKernel
{

    protected $commands = [
        //
    ];

    protected function schedule(Schedule $schedule)
    {

        $schedule->call(function () {
            Rx::where([
                'date_needed_by' => date('y-m-d'),
                'status' => 0
            ])->update([
                'status' => 1
            ]);

            $rxes = Rx::all();
            foreach($rxes as $rx)
            {
                $status_count = $rx->medications->where('status',1)->count();
                if($status_count == 0)
                {
                    $diffInDays = Carbon::parse($rx->updated_at)->diffInDays();
                    if($diffInDays >= 10)
                    {
                        $rx->delete();
                    }
                }
            }
            
            User::where('auth_code', '!=', null)->update(['auth_code' => null]);
        })->dailyAt('23:59');

        $schedule->call(function () {
            $rxes = Rx::where('order_today', '!=', null)->get();
            foreach($rxes as $rx)
            {
                $diffInHours = Carbon::parse($rx->order_today)->diffInHours();
                if($diffInHours >= 12)
                    $rx->delete();
            }
        })->hourly();

        $schedule->call(function(){

            $current_date = Carbon::now()->format('d-m-Y');
            $rxes = Rx::withTrashed()->where('date_needed_by',$current_date)->get();
            foreach($rxes as $rx)
            {
                $patient = $rx->patients->update([
                    'overdue' => "yes"
                ]);
            }
        })->dailyAt('23:59');

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
