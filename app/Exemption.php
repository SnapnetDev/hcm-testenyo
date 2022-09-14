<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Exemption extends Model
{
    protected $fillable = ['type','user_id','attendance_id','reason'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }
    public function attendance()
    {
        return $this->belongsTo('App\Attendance');
    }

    public function exemption_approvals(){
        return $this->hasMany('App\ExemptionApproval');
    }
}
