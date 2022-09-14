<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    protected $fillable=['name','regional_lead_id','area_manager_id'];

    public function branches(){
        return $this->hasMany('App\Branch');
    }

    public function regional_lead(){
       return $this->belongsTo('App\User','regional_lead_id');
    }

    public function area_manager(){
        return $this->belongsTo('App\User','area_manager_id');
    }
}
