<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LeaveSpill extends Model
{
    protected $fillable=['user_id','from_year','to_year','days','used','valid'];
    protected $table='leave_spills';

    public function user()
    {
    	return $this->belongsTo('App\User','user_id');
    }
    

     
}
