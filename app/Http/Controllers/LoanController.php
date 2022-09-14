<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\LoanTrait;
use App\Traits\PayrollTrait;
use App\User;
use App\LoanRequest;
use App\Setting;
use Auth;

class LoanController extends Controller
{
	use LoanTrait,PayrollTrait;
   public function index()
   {

   	
   	if(Auth::user()->promotionHistories){
   	$netpay=$this->getUserNetPay(Auth::user()->id);
   	$maximum_allowed=$netpay*(intval(Setting::where('name','loan_allowed')->first()->value)/100);
   	$annual_interest=intval(Setting::where('name','loan_interest')->first()->value);

   	return view('loan.index',compact('netpay','maximum_allowed','annual_interest'));
	  }else{
	  	return 'Please set grade for user';
	  }  
       
   	
   }
   public function store(Request $request)
    {
        //
        return $this->processPostLoan($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request)
    {
  		
        return $this->processGetLoan($id,$request);
    }
}
