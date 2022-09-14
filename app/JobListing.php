<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JobListing extends Model
{
    protected $table="joblistings";
    protected $fillable=['job_id','salary_from','salary_to','expires','status','level','employee_class_id','experience_from','experience_to','requirements'];

    public function job()
    {
    	$this->belongsTo('App\Job');
    }
    public function jobapplications($value='')
    {
    	$this->hasMany('App\JobApplication');
    }
}
