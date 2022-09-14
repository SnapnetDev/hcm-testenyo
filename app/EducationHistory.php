<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EducationHistory extends Model
{
    //
    protected $table='emp_academics';
    protected $fillable=['title','qualification_id','year','institution','grade','course','emp_id'];

    public function user()
    {
        return $this->belongsTo('App\User','emp_id');
    }
    public function qualification()
    {
        return $this->belongsTo('App\Qualification','qualification_id');
    }
    
}
