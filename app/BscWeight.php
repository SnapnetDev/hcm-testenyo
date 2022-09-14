<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BscWeight extends Model
{
    protected $table="bsc_weights";
   protected $fillable=['department_id','grade_category_id','metric_id','percentage'];

   public function department()
    {
        return $this->belongsTo('App\Department', 'department_id');
    }
    public function metric()
    {
        return $this->belongsTo('App\BscMetric', 'metric_id');
    }
    public function grade_category()
    {
        return $this->belongsTo('App\GradeCategory', 'grade_category_id');
    }

}
