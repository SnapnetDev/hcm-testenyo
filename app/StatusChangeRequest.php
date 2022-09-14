<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StatusChangeRequest extends Model
{
    protected $fillable=['user_id','status','reason','details','created_by','company_id','start_date','approved','approved_by'];

    public function user(){
        return $this->belongsTo('App\User');
    }

    public function suspender()
    {
        return $this->belongsTo('App\User', 'created_by');
    }
    public function approver()
    {
        return $this->belongsTo('App\User', 'approved_by');
    }
    public function company()
    {
        return $this->belongsTo('App\Company');
    }

}
