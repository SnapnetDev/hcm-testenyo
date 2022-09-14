<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\PayrollTrait;
use App\User;
use App\Payroll;
use App\Company;

class CompensationController extends Controller
{
	use PayrollTrait;
   public function index()
   {
     $company_id=companyId();
     if ($company_id==0) {
        $payroll=Payroll::where(['month'=>date('m'),'year'=>date('Y'),'company_id'=>$company_id])->first();
       if ($payroll) {
            $date=date('Y-m-d');
       $allowances=0;
       $deductions=0;
       $income_tax=0;
       $salary=0;
       $has_been_run=1;
       foreach ($payroll->payroll_details as $detail) {
          $salary+=$detail->basic_pay;
          $allowances+=$detail->allowances;
          $deductions+=$detail->deductions;
          $income_tax+=$detail->paye;

       }

       return view('compensation.payroll',compact('payroll','allowances','deductions','income_tax','salary','date','has_been_run'));
       } else {
          $has_been_run=0;
        $employees=User::whereHas('promotionHistories.grade')->get();
    $date=date('Y-m-d');

    return view('compensation.payroll',compact('date','employees','has_been_run'));
          
       }

     } 
     
   	 $payroll=Payroll::where(['month'=>date('m'),'year'=>date('Y'),'company_id'=>$company_id])->first();
       if ($payroll) {
           	$date=date('Y-m-d');
       $allowances=0;
       $deductions=0;
       $income_tax=0;
       $salary=0;
       $has_been_run=1;
       foreach ($payroll->payroll_details as $detail) {
          $salary+=$detail->basic_pay;
          $allowances+=$detail->allowances;
          $deductions+=$detail->deductions;
          $income_tax+=$detail->paye;

       }

       return view('compensation.payroll',compact('payroll','allowances','deductions','income_tax','salary','date','has_been_run'));
       } else {
          $has_been_run=0;
        $employees=Company::where('id',$company_id)->first()->users()->has('promotionHistories.grade')->get();
   	$date=date('Y-m-d');

   	return view('compensation.payroll',compact('date','employees','has_been_run'));
          
       }

   	
   }
   public function store(Request $request)
    {
        //
        return $this->processPost($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request)
    {
  		
        return $this->processGet($id,$request);
    }
}
