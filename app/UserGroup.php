<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserGroup extends Model
{
    protected $fillable=['name'];
    protected $table='user_groups';

    public function users()
    {
    	return $this->belongsToMany('App\User','user_group_user','user_group_id','user_id');
    }

     
}
