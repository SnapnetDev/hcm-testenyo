<?php

namespace App\Console\Commands;

use App\Traits\Attendance;
use Illuminate\Console\Command;
use Carbon\Carbon;

class GetAbsenteesCommand extends Command
{
     use attendance;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get:absentees';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get absentees';

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
        
        
        /*$yest=Carbon::yesterday()->format('Y-m-d');
        $dates=[$yest];
        $dates=['2020-03-18','2020-03-19'];
        $this->getAbsentees($dates);*/
         
         $yest=Carbon::yesterday()->format('Y-m-d');
        $today=Carbon::today()->format('Y-m-d');
        $dates=[$yest,$today];
        // $dates=$this->displayDates('2021-12-20',$today,'Y-m-d');
        $this->getAbsentees($dates);
        
        
       /* $dates=['2020-03-01','2020-03-02','2020-03-03','2020-03-04','2020-03-05','2020-03-06','2020-03-07','2020-03-08','2020-03-09','2020-03-10','2020-03-11','2020-03-12','2020-03-14','2020-03-15'];
        $this->correctAbsentToOff($dates);*/
        
        
    }
    public function displayDates($date1, $date2, $format = 'd-m-Y' ) {
      $dates = array();
      $current = strtotime($date1);
      $date2 = strtotime($date2);
      $stepVal = '+1 day';
      while( $current <= $date2 ) {
         $dates[] = date($format, $current);
         $current = strtotime($stepVal, $current);
      }
      return $dates;
   }
}
