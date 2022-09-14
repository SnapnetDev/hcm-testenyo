<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    //
    protected $fillable=['month','year','company_id','workflow_id','paysilp_issued','for','user_id','approved','disbursed'];
    protected $table="payroll";

    public function company()
    {
    	return $this->belongsTo('App\Company');
    }

    public function workflow()
    {
    	return $this->belongsTo('App\Workflow');
    }
    public function user()
    {
        return $this->belongsTo('App\User');
    }
    public function payroll_details()
    {
    	return $this->hasMany('App\PayrollDetail');
    }
    public function specific_salary_components()
    {
        return $this->belongsToMany('App\SpecificSalaryComponent','payroll_specific_salary_component','payroll_id','specific_salary_component_id');
    }
    public function loan_requests()
    {
        return $this->belongsToMany('App\LoanRequest','payroll_loan_request','payroll_id','loan_request_id');
    }
    public function salary_components()
    {
        return $this->belongsToMany('App\SalaryComponent','payroll_salary_component','payroll_id','salary_component_id');
    }
}
