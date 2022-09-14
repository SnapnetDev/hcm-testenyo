<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FinancialReport extends Model
{
    protected $fillable=['day','month','year','start','end','created_by','attendance_report_id','days'];
}
