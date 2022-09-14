<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LeaveApproval extends Model
{
    protected $fillable=['leave_request_id','stage_id','approver_id','comments','status'];
    public function leave_request()
    {
    	return $this->belongsTo('App\LeaveRequest','leave_request_id');
    }

    public function approver()
    {
    	return $this->belongsTo('App\User','approver_id');
    }
     public function stage()
    {
    	return $this->belongsTo('App\Stage','stage_id');
    }

    
}
