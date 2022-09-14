<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Workflow extends Model
{
  protected $fillable=['name'];
  public function stages()
  {
    return $this->hasMany('App\Stage','workflow_id')
    ->orderBy('position', 'asc');
  }
  public function payrolls()
  {
    return $this->hasMany('App\Payroll');
  }
  public function payroll_policies()
  {
    return $this->hasMany('App\PayrollPolicy');
  }
  public function leave_policies()
  {
    return $this->hasMany('App\LoanPolicy');
  }
  public function loan_policies()
  {
    return $this->hasMany('App\LeavePolicy');
  }
  // public function audit_logs()
  // {
  //     return $this->morphMany('App\AuditLog', 'auditable');
  // }

}
