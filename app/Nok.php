<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Nok extends Model
{
    protected $table="noks";
    protected $fillable=['name','relationship','phone','address','user_id'];
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
