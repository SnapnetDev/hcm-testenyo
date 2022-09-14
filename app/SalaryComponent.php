<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\CompanyScope;



class SalaryComponent extends Model
{
    protected $table='salary_components';
    protected $fillable=['name','gl_code','project_code','type','constant','formula','comment','status','company_id','taxable'];

    public function exemptions()
    {
        return $this->belongsToMany('App\User','salary_component_exemptions','salary_component_id','user_id');
    }
    public function payrolls()
    {
        return $this->belongsToMany('App\Payroll','payroll_salary_component','salary_component_id','payroll_id');
    }
    protected static function boot()
    {
        parent::boot();
      
        static::addGlobalScope(new CompanyScope);
    }
}
