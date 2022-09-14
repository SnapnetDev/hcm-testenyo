<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AttendanceDetail extends Model
{
    protected $fillable = ['attendance_id','date','clock_in','clock_out'];

    public function attendance()
    {
    	return $this->belongsTo('App\Attendance','attendance_id');
    }
}
