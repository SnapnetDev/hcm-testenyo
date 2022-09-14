<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
	protected $fillable=['name','email','address','manager_id','company_id','region_id'];
   public function users()
    {
        return $this->hasMany('App\User');
    }
    public function manager()
    {
        return $this->belongsTo('App\User','manager_id')->withDefault();
    }
    public function stations()
    {
        return $this->hasMany('App\Company');
    }

    public function region()
    {
        return $this->belongsTo('App\Region');
    }


}
