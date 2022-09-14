<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FacialVerifyRequest extends Model
{
    protected $fillable=['user_id','image_url','response','status'];

    protected function user(){
        return $this->belongsTo('App\User');
    }
}
