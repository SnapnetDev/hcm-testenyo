<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BscMeasurementPeriod extends Model
{
    protected $table="bsc_measurement_periods";
   protected $fillable=['from','to'];

   public function submetrics()
    {
        return $this->hasMany('App\BscSubMetric', 'measurement_period_id');
    }
}
