<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CommandLog extends Model
{
    protected $connection = 'mysql3';
    protected $fillable=['command','status','company_id','biometric_serial'];

    public function company(){
        return $this->belongsTo('App\Company');
    }
}