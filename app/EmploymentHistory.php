<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmploymentHistory extends Model
{
    //
    protected $table="emp_histories";
    protected $fillable=['user_id','organization','position','start_date','end_date'];
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
