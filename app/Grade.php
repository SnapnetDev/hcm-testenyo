<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
     protected $fillable = ['level','basic_pay','leave_length','lateness_policy_id','company_id','grade_category_id'];
    public function leaveperiod()
    {
        return $this->hasOne('App\LeavePeriod','grade_id');
    }
    public function lateness_policy()
    {
    	return $this->belongsTo('App\LatenessPolicy','lateness_policy_id');
    }
    public function grade_category()
    {
    	return $this->belongsTo('App\GradeCategory','grade_category_id');
    }
    public function users($value='')
    {
    	return $this->hasMany('App\User','grade_id');
    }


}
