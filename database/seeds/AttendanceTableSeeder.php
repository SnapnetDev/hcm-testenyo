<?php

use Illuminate\Database\Seeder;
use Fzaninotto\Faker\Generator as Faker;

class AttendanceTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    /*



    */
    public function run()
    {
    	$users=App\User::all();
    	$timein=0;
    	$timeout=0;
    	foreach ($users as $user) {
    		for ($i=1; $i <= 31; $i++) { 
    			$attendance=$user->attendances()->create(['date'=>'2018-08-'.$i,'shift_id'=>1]);
    			for ($j=0; $j <4; $j++) {
	    			if ($j==0) {
	    				$timein=mt_rand(6,9);
	    				$timeout=$timein+1;
	    			} elseif($j==1) {
	    				$timein=mt_rand(10,11);
	    				$timeout=$timein+2;
	    			}elseif($j==2) {
	    				$timein=mt_rand(14,15);
	    				$timeout=$timein+2;
	    			}elseif($j==3) {
	    				$timein=mt_rand(16,17);
	    				$timeout=$timein+mt_rand(1,4);
	    			}
    			
    				$attendance->attendancedetails()->create(['clock_in'=>$timein.':00:00','clock_out'=>$timeout.':00:00']);
    			}
    		}
    	}
    	

    	// Start date
		    // $date = '2018-07-01';
		    // // End date
		    // $end_date = '2018-07-31';
		  
		    // while (strtotime($date) <= strtotime($end_date)) {
		    //     echo "$date\n";
		    //     //use +1 month to loop through months between start and end dates
		    //     $date = date ("Y-m-d", strtotime("+1 day", strtotime($date)));
		    // }
        // for ($i=1; $i <31 ; $i++) {


        // 	// $faker=Faker\Factory::create();
        // 	// $attendance= new App\Attendance;
        // 	// $attendance->emp_num=$i;
        // 	// $attendance->date=date('Y-m-d');
        // 	// $attendance->shift_id=1;
        // 	// $attendance->save();

        // }
    }
}
