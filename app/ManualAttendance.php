<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ManualAttendance extends Model
{
    protected $fillable=['user_id','company_id','manager_id','ssm_id','date','time_in','time_out','status','reason'];

    public function user(){
        return $this->belongsTo('App\User');
    }
}
