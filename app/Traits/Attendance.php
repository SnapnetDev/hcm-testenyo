<?php

namespace App\Traits;


use App\AttendanceReport;
use App\Exemption;
use App\Setting;
use App\User;
use App\Shift;
use App\UserDailyShift;
use App\WorkingPeriod;
use Carbon\Carbon;

/**
 *
 */
trait Attendance
{

    function seconds_to_time($seconds)
    {
        $t = round($seconds);
        return sprintf('%02d:%02d:%02d', ($t / 3600), ($t / 60 % 60), $t % 60);
    }

    function time_to_seconds($time)
    {
        $sec = 0;
        foreach (array_reverse(explode(':', $time)) as $k => $v) $sec += pow(60, $k) * $v;
        return $sec;
    }

    public function time_diff($time1, $time2)
    {
        $time1 = strtotime("1/1/2018 $time1");
        $time2 = strtotime("1/1/2018 $time2");
        return ($time2 - $time1) / 3600;
    }

    public function checkStatus($clockin, $shifttime)
    {
        if ($clockin <= $shifttime) {
            return 'early';
        } else {
            return 'late';
        }
    }

    public function checkStatus2($clockin, $shifttime)
    {
        $grace_period=Setting::where('name','grace_period')->first()->value;
        $shifttime=Carbon::createFromFormat('H:i:s',$shifttime)->addMinutes($grace_period)->format('H:i:s');
        if ($clockin <= $shifttime) {
            return 'early';
        } else {
            return 'late';
        }
    }
    private function checkVerification($verify_id)
    {
        switch ($verify_id) {
            case '0' :
                return 'Password';
                break;
            case '1' :
                return 'Fingerprint';
                break;
            case '2' :
                return 'Card';
                break;
            case '9' :
                return 'Others';
                break;
        }
    }

    public function getHoursBetween($shift_in,$shift_out){
        $clock_in = Carbon::createFromFormat('H:i:s', $shift_in);
        if ($shift_out){
            $clock_out = Carbon::createFromFormat('H:i:s', $shift_out);
        }else{
            //if there is no clock out, set the clock out to be clockin
            $clock_out = Carbon::createFromFormat('H:i:s', $shift_in);
        }
        if ($clock_in>$clock_out){//it means clockout is next day
            $clock_out = $clock_out->addDay();
        }

        return $clock_in->diffInHours($clock_out);
    }

    public function getDayHoursNew($attendance)
    {   //function to calculate hours worked, overtime and work hours worked
        $wp = WorkingPeriod::all()->first();
        $total_hours = 0;
        $work_hours_worked = 0;
        $excess = 0;
        $overtime = 0;
        $work_end_time = '';
        $work_start_timeA = '';
        $shift_name='';
        $status = '';
        $amount='';
        if ($attendance) {
            $user = User::where('emp_num',$attendance->emp_num)->first();
            $amount=$user->role->daily_pay;
            $details = $attendance->attendancedetails;
            if(isset($details->first()->clock_in)){
                $first_clock_in = $details->first()->clock_in;
            }
            else{
                dd($attendance->id);
            }

            $last_clock_out = $details->last()->clock_out;
            //hours worked and work_hours_worked
            foreach ($details as $detail) {
                $clock_in = Carbon::createFromFormat('H:i:s', $detail->clock_in);
                if ($detail->clock_out){
                    $clock_out = Carbon::createFromFormat('H:i:s', $detail->clock_out);
                }else{
                    //if there is no clock out, set the clock out to be clockin
                    $clock_out = Carbon::createFromFormat('H:i:s', $detail->clock_in);
                }
                if ($clock_in>$clock_out){//it means clockout is next day
                    $clock_out = $clock_out->addDay();
                }

                $total_hours += $clock_in->diffInHours($clock_out);
                //$hours += $this->get_time_difference($detail->clock_in, $detail->clock_out);
            }
            //end of hours calculation
            //early or late determination
            if ($attendance->user_daily_shift_id != 0) {        //there is shift
                $shift_id = $attendance->user_daily_shift->shift->id;
                $shift = Shift::where('id', $shift_id)->first();
                $work_end_time = $shift->end_time;
                $work_start_timeA = $shift->start_time;
                $work_start_time = $shift->start_time;
                $shift_name=$shift->type;
            } else {
                $work_end_time = $wp->cob;
                $work_start_timeA = $wp->sob;
                $work_start_time = $wp->sob;
                $shift_name='Default Working Hours';
            }
            $status = $this->checkStatus2($first_clock_in, $work_start_time);//no shift, use business hours
            if ($shift_name=='Off Day'){$status='off';}
            //overtime
            if ($last_clock_out > $work_end_time) {
                $work_end = Carbon::createFromFormat('H:i:s', $work_end_time);
                $last_clock = Carbon::createFromFormat('H:i:s', $last_clock_out);
                $overtime = $last_clock->diffInHours($work_end);
                // $overtime = $this->get_time_difference($last_clock_out,$work_end_time);
            }
            if ($first_clock_in < $work_start_time) {
                $first_clock_in = Carbon::createFromFormat('H:i:s', $first_clock_in);
                $work_start_time = Carbon::createFromFormat('H:i:s', $work_start_time);
                $excess = $first_clock_in->diffInHours($work_start_time);
                $before_shift_start = Setting::where('name', 'before_shift_time')->first();
                if ($before_shift_start->value == '1') {
                    $overtime = $overtime + $excess;
                }
                $first_clock_in=$first_clock_in->format('H:i:s');
            }
            //$total_hours = $total_hours - $excess;  //remove time before shift starts to from hours worked
            //$total_hours = $total_hours - $overtime;  //remove overtime from hours worked

            if ($work_end_time>$work_start_time){   //the shift has no spillover
                //$this->info($shift_name.' - day');
            }
            else{       //the shift has spillover
                // $this->info($shift_name.' - spillover');

            }

            //if hours worked is showing negative, it means you worked in a shift that isn't yours


            $expected_hours=$this->getHoursBetween($work_start_timeA,$work_end_time);

        }
        return ['hours' => $total_hours, 'status' => $status, 'overtime' => $overtime,
            'shift_start' => $work_start_timeA, 'shift_end' => $work_end_time,'shift_name'=>$shift_name,'expected_hours'=>$expected_hours,
            'first_clock_in'=>$first_clock_in, 'last_clock_out'=>$last_clock_out,'amount'=>$amount];
    }

    public function attendanceToDB($from, $to)
    {
        //$today=Carbon::today()->format('Y-m-d');
        //$dates=[$today];
        //$dates=['2019-12-23'];
        //$not_processed_yet = \App\Attendance::whereIn('date', $dates)->get();

        //return $not_processed_yet = \App\Attendance::whereDate('date','>=', $from)->whereDate('date','<=', $to)->count();



        \App\Attendance::whereDate('date','>=', $from)->whereDate('date','<=', $to)->chunk(200, function ($not_processed_yet) {
            foreach ($not_processed_yet as $not_processed) {
                $this->attendanceReportForAttendance($not_processed);
            }
        });
        /* $not_processed_yet = \App\Attendance::whereDate('date','>=', $from)->whereDate('date','<=', $to)->get();
         foreach ($not_processed_yet as $not_processed) {
             $this->attendanceReportForAttendance($not_processed);
         }*/
        //$this->getAbsentees($dates);
    }
    public function correctOffToAbsent($dates,$company=null){
        foreach ($dates as $date) {
            $abs = AttendanceReport::where('date', $date)->where('status','off')->pluck('user_id')->toArray();
            if(isset($company)){
                $offs = User::where('status','1')->where('company_id',$company)->whereIn('id', $abs)->get();
            }else{
                $offs = User::where('status','1')->whereIn('id', $abs)->get();
            }
            foreach ($offs as $absentee) {
                $off_user_daily_shift = UserDailyShift::where('user_id', $absentee->id)->where('sdate', $date)->where('shift_id','!=','7')->first();
                if ($off_user_daily_shift){
                    $status='absent';
                    $shift = Shift::where('id', $off_user_daily_shift->shift_id)->first();
                    $shift_name = $shift->type;
                    \App\AttendanceReport::where('user_id',$absentee->id)->where('date',$date)
                        ->update(['status' => $status,'shift_name' => $shift_name]);
                }
            }
        }
    }
    public function correctAbsentToOff($dates,$company=null){
        foreach ($dates as $date) {
            $abs = AttendanceReport::where('date', $date)->where('status','absent')->pluck('user_id')->toArray();
            if(isset($company)){
                $absentees = User::where('status','1')->where('company_id',$company)->whereIn('id', $abs)->get();
            }else{
                $absentees = User::where('status','1')->whereIn('id', $abs)->get();
            }

            foreach ($absentees as $absentee) {
                $off_user_daily_shift = UserDailyShift::where('user_id', $absentee->id)->where('sdate', $date)->where('shift_id','7')->first();
                if ($off_user_daily_shift){
                    $status='off';
                    $shift = Shift::where('id', $off_user_daily_shift->shift_id)->first();
                    $shift_name = $shift->type;
                    \App\AttendanceReport::where('user_id',$absentee->id)->where('date',$date)
                        ->update(['status' => $status,'shift_name' => $shift_name]);
                }
            }
        }
    }
    public function getAbsentees($dates){
        foreach ($dates as $date) {$present = AttendanceReport::where('date', $date)->pluck('user_id')->toArray();
            $absentees = User::where('status','1')->whereNotIn('id', $present)->get();//for each day, fetch users that don't have attendance
            foreach ($absentees as $absentee) {
                $user_daily_shift = UserDailyShift::where('user_id', $absentee->id)->where('sdate', $date)->first();
                $status="absent";
                if ($user_daily_shift) {
                    $shift_id = $user_daily_shift->shift_id;
                    $shift = Shift::where('id', $user_daily_shift->shift_id)->first();
                    $shift_start = $shift->start_time;
                    $shift_end = $shift->end_time;
                    $shift_name = $shift->type;
                    if ($shift_name=='Off Day'){$status='off';}
                } else {
                    $wp = WorkingPeriod::all()->first();
                    $shift_start = $wp->sob;
                    $shift_end = $wp->cob;
                    $shift_name = 'Default Working Hours';
                }
                $expected_hours=$this->getHoursBetween($shift_start,$shift_end);
                $new = \App\AttendanceReport::updateOrCreate(
                    ['user_id' => $absentee->id, 'attendance_id' => '', 'date' => $date],
                    ['first_clockin' => '', 'last_clockout' => '',
                        'status' => $status, 'hours_worked' => '', 'overtime' => '',
                        'shift_name' => $shift_name, 'shift_start' => $shift_start, 'shift_end' => $shift_end,'expected_hours' => $expected_hours,'amount'=>'0']
                );  //add their details with null
            }
        }
    }

    public function attendanceReportForAttendance($not_processed)
    {
        // $this->info($not_processed->id);

        //find and update user daily shift if there is any
        $user_shift = UserDailyShift::where('user_id', $not_processed->user->id)->where('sdate', $not_processed->date)->first();

        if ($user_shift) {
            \App\Attendance::where('id', $not_processed->id)->update(['user_daily_shift_id' => $user_shift->id]);
        }
        $not_processed = \App\Attendance::find($not_processed->id);

        $data = $this->getDayHoursNew($not_processed);

        $first_clockin=$data['first_clock_in'];
        $last_clockout=$data['last_clock_out'];
        $hours = $data['hours'];
        $status = $data['status'];
        $overtime = $data['overtime'];

        $shift_start =$data['shift_start'];
        $shift_end = $data['shift_end'];
        $shift_name = $data['shift_name'];
        $amount=$data['amount'];
        $expected_hours=$data['expected_hours'];

        $new = \App\AttendanceReport::updateOrCreate(
            ['user_id' => $not_processed->user->id, 'date' => $not_processed->date],
            ['attendance_id' => $not_processed->id, 'first_clockin' => $first_clockin, 'last_clockout' => $last_clockout,
                'status' => $status, 'hours_worked' => $hours,'expected_hours' => $expected_hours, 'overtime' => $overtime,
                'shift_name' => $shift_name, 'shift_start' => $shift_start, 'shift_end' => $shift_end,
                'amount'=>$amount]
        );
    }

    public function get_time_difference($time1, $time2)
    {
        $time1 = strtotime("1/1/2018 $time1 ");
        $time2 = strtotime("1/1/2018 $time2");

        if ($time2 < $time1) {
            $time2 = $time2 + 86400;
        }

        return ($time2 - $time1) / 3600;

    }

    public function calculateDaysToPay($present_days,$company_pay_full_days){
        return ($present_days/$company_pay_full_days)*25;
    }

}