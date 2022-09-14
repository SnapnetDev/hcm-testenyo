<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BscMetric extends Model
{
	protected $table="bsc_metrics";
   protected $fillable=['name','description'];

   public function submetrics()
    {
        return $this->hasMany('App\BscSubMetric', 'metric_id');
    }
}
