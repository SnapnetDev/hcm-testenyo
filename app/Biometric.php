<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Biometric extends Model
{
    // protected $connection = 'mysql3';
    protected $fillable=['data','url','headers','biometric_serial', 'created_at'];

    protected $casts=[
        'data'=>'array',
        'headers'=>'array',
        ];
}
