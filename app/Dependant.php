<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Dependant extends Model
{
    protected $table='emp_dependants';
    protected $fillable=['name','dob','email','phone','relationship','user_id'];

    public function user()
    {
        return $this->belongsTo('App\User','user_id');
    }
    public function getDobAttribute($value)
    {
        return  date('m/d/Y',strtotime($value));
    }
}
