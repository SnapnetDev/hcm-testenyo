<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BscEvaluation extends Model
{
   protected $table="bsc_evaluations";
   protected $fillable=['user_id','bsc_measurement_period_id','department_id','grade_category_id','comment','score','manager_approved','evaluator_id','manager_approved','employee_approved','date_employee_approved','date_manager_approved'];

   public function measurement_period()
    {
        return $this->belongsTo('App\BscMeasurementPeriod', 'bsc_measurement_period_id');
    }
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }
    public function department()
    {
        return $this->belongsTo('App\Department', 'department_id');
    }
    public function grade_category()
    {
        return $this->belongsTo('App\BscSubMetric', 'grade_category_id');
    }
    public function evaluation_details()
    {
        return $this->hasMany('App\BscEvaluationDetail', 'bsc_evaluation_id');
    }
}
