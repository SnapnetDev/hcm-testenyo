<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\CompanyScope;

class LatenessPolicy extends Model
{
   protected $table="lateness_policies";
   protected $fillable=['late_minute','deduction_type','deduction','status','policy_name','company_id'];

  public function grades()
  {
  	return $this->hasMany('App\Grade','grade_id');
  }
   protected static function boot()
    {
        parent::boot();
      
        static::addGlobalScope(new CompanyScope);
    }
}
