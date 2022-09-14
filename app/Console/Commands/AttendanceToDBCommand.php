<?php

namespace App\Console\Commands;

use App\Traits\Attendance;
use Carbon\Carbon;
use Illuminate\Console\Command;

class AttendanceToDBCommand extends Command
{
    use attendance;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'to:db';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Push attendance to Attendance Report Database';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $from=Carbon::today()->subDays(7)->format('Y-m-d');
        $to=Carbon::today()->format('Y-m-d');
        $from='2022-02-01';
        $to='2022-02-16';
        $this->attendanceToDB($from,$to);
        
        //for people that set off day for 7 past day
        $dates=[];
        for($i=1; $i<8; $i++){
            $dates[]=Carbon::today()->subDays($i)->format('Y-m-d');
        }
        $this->correctAbsentToOff($dates);
    }
}
