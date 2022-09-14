<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BscDetDetail extends Model
{
   protected $table="bsc_det_details";
   protected $fillable=['bsc_det_id','bsc_metric_id','business_goal','measure','lower','mid','upper','actual','weighting'];
   public function metric()
    {
        return $this->belongsTo('App\BscMetric', 'metric_id');
    }
    public function det()
    {
        return $this->belongsTo('App\BscDet', 'bsc_det_id');
    }
}
