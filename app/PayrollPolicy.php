<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\CompanyScope;

class PayrollPolicy extends Model
{
    protected $table='payroll_policies';
    protected $fillable=['payroll_runs','basic_pay_percentage','user_id','workflow_id','company_id','use_lateness'];

    public function editor()
    {
    	return $this->belongsTo('App\User','user_id');
    }
    public function workflow()
    {
    	return $this->belongsTo('App\Workflow','workflow_id');
    }
     protected static function boot()
    {
        parent::boot();
      
        static::addGlobalScope(new CompanyScope);
    }
}
