<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FinancialReportDetail extends Model
{
    protected $fillable=['user_id','role_id','company_id','finance_report_id','days_worked','present','absent','late','off','amount_expected','amount_received'];

    public function user(){
        return $this->belongsTo('App\User');
    }
    public function role(){
        return $this->belongsTo('App\Role');
    }
    public function finance_report(){
        return $this->belongsTo('App\FinanceReport');
    }
}
