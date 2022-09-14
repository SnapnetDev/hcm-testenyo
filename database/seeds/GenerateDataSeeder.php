<?php

use App\UserDailyShift;
use Illuminate\Database\Seeder;

class GenerateDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users=\App\User::take(150)->get();
        $timein=0;
        $timeout=0;
        foreach ($users as $user) {
            for ($i=1; $i <= 31; $i++) {
                $date='2019-08-'.$i;
                $user_shift=UserDailyShift::where('user_id',$user->id)->where('sdate',$date)->first();
                if ($user_shift) {
                    $shift = $user_shift->id;
                } else {
                    $shift = 0;
                }
                $attendance=$user->attendances()->create(['date'=>$date,'shift_id'=>1,'user_daily_shift_id'=>$shift]);
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

    }
}
