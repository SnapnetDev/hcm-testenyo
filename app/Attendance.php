<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $fillable = ['emp_num','user_daily_shift_id','date','shift_id'];

    public function user()
    {
    	return $this->belongsTo('App\User','emp_num','emp_num');
    }
    public function attendancedetails()
    {
    	return $this->hasMany('App\AttendanceDetail','attendance_id');
    }
    public function user_daily_shift(){
        return $this->belongsTo('App\UserDailyShift');
    }
    
}
