<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BscDet extends Model
{
   
   protected $table="bsc_dets";
   protected $fillable=['department_id','measurement_period_id'];

   public function measurement_period()
    {
        return $this->belongsTo('App\BscMeasurementPeriod', 'measurement_period_id');
    }
public function department()
    {
        return $this->belongsTo('App\Department', 'department_id');
    }
public function details()
    {
        return $this->hasMany('App\BscDetDetail', 'bsc_det_id');
    }
}
