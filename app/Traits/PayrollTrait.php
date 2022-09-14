<?php
namespace App\Traits;
use App\Payroll;
use App\Bank;
use App\Company;
use App\Department;
use App\CompanyAccountDetail;
use App\PayslipDetail;
use App\PayrollDetail;
use App\PayrollPolicy;
use App\SalaryComponent;
use App\SpecificSalaryComponent;
use App\Workflow;
use App\LatenessPolicy;
use App\Setting;
use App\User;
use App\Holiday;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Auth;
use Excel;
use PDF;
use App\Notifications\ApprovePayroll;

trait PayrollTrait{

	public function processGet($route,Request $request)
	{
        // return $request->all();
		switch ($route) {
			case 'runpayroll':
            //   return $date=date('Y-m-d',strtotime('01-'.$request->month));
            // return $request->all();
				return $this->runPayroll($request);
				break;
            case 'payroll_list':
            //   return $date=date('Y-m-d',strtotime('01-'.$request->month));
            // return $request->all();
                return $this->payrollList($request);
                break;
            case'user_payroll_detail':

                return $this->userPayrollDetail($request);
			 break;
             case'user_payroll_list':

                return $this->userPayrollList($request);
             break;
             case'download_payslip':

                return $this->downloadPayslip($request);
             break;
             case'issuepayslip':

                return $this->issuePayslip($request);
             break;
              case'rollback':

                return $this->rollbackPayroll($request);
             break;
              case'exportford365':

                return $this->exportForD365($request);
             break;
             case'exportforexcel':

                return $this->exportForExcel($request);
             break;
			default:
				# code...
				break;
		}
	}
	public function processPost(Request $request)
	{
		switch ($request->type) {
			case 'value':
				# code...
				break;
			
			default:
				# code...
				break;
		}
	}

    public function userPayrollDetail(Request $request)
    {
        $detail=PayrollDetail::find($request->payroll_detail_id);
        return view('compensation.partials.userPayrollDetails',compact('detail'));
    }
    public function userPayrollList(Request $request)
    {
       return view('compensation.userPayrollList');
    }

    public function exportForD365(Request $request)
    {
       
            $company_id=companyId();
            if ($company_id==0) {
               $departments=Department::all();
            } else {
                $departments=Department::where('company_id',$company_id)->get();
            }
            
           
           
       $payroll=Payroll::find($request->payroll_id);
               if ($payroll) {
                 $days=cal_days_in_month(CAL_GREGORIAN,$payroll->month,$payroll->year);
                     $date=date('Y-m-d',strtotime($payroll->year.'-'.$payroll->month.'-'.$days));
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
               // return $payroll->payroll_details->count();
               $view='compensation.partials.d365payroll2';
                // return view('compensation.d365payroll',compact('payroll','allowances','deductions','income_tax','salary','date','has_been_run'));
                 return     \Excel::create("export", function($excel) use ($allowances,$view,$deductions,$income_tax,$salary,$payroll,$date,$departments) {

            $excel->sheet("export", function($sheet) use ($allowances,$view,$deductions,$income_tax,$salary,$payroll,$date,$departments) {
                $sheet->loadView("$view",compact('payroll','allowances','deductions','income_tax','salary','date','departments'))
                ->setOrientation('landscape');
            });

        })->export('xlsx');
           }
    }

    public function exportForExcel(Request $request)
    {
       
            // $company_id=companyId();
            // if ($company_id==0) {
            //    $departments=Department::all();
            // } else {
            //     $departments=Department::where('company_id',$company_id)->get();
            // }
            
           
           
       $payroll=Payroll::find($request->payroll_id);
              
               // return $payroll->payroll_details->count();
               $view='compensation.partials.payrollexcel';
                // return view('compensation.d365payroll',compact('payroll','allowances','deductions','income_tax','salary','date','has_been_run'));
                 return     \Excel::create("export", function($excel) use ($view,$payroll) {

            $excel->sheet("export", function($sheet) use ($view,$payroll) {
                $sheet->loadView("$view",compact('payroll'))
                ->setOrientation('landscape');
            });

        })->export('xlsx');
     }
    
    public function payrollList(Request $request)
    {
        if ($request->filled('month')) {
     $date=date('Y-m-d',strtotime('01-'.$request->month));
            }else{
                $date=date('Y-m-d');
            }
             $company_id=companyId();
            // $company=\Auth::user()->company;
        $pmonth=date('m',strtotime($date));
        $pyear=date('Y',strtotime($date));
     
       $payroll=Payroll::where(['month'=>$pmonth,'year'=>$pyear,'company_id'=>$company_id])->first();

        
       if ($payroll) {
            $date=date('Y-m-d',strtotime($payroll->for));
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
        Auth::user()->notify(new ApprovePayroll($payroll));
       return view('compensation.payroll',compact('payroll','allowances','deductions','income_tax','salary','date','has_been_run'));
       } else {
          $has_been_run=0;
           // $employees=\Auth::user()->company->users()->has('promotionHistories.grade')->get();
          $employees=Company::where('id',$company_id)->first()->users()->has('promotionHistories.grade')->get();
    

    return view('compensation.payroll',compact('date','employees','has_been_run'));
          
       }
      
    }
    public function getUserNetPay($user_id)
    {
       $user=User::has('promotionHistories.grade')->where(['id'=>$user_id])->first(); 
        
                $payroll=[];
                $payroll['user_id']=$user->id;
                $payroll['date']=date('Y-m-d');
                $payroll['month']=$pmonth= date('m');
                $payroll['year']=$pyear=date('Y');
                $company_id=companyId();
            
                $payroll['company_id']=$company_id;
                    $payroll['start_day']=1;
                

                if(date('m',strtotime($user->hiredate))==$pmonth &&date('Y',strtotime($user->hiredate))!=$pyear){
                    $payroll['is_anniversary']=1;
                }else{
                    $payroll['is_anniversary']=0;
                }
                $payroll['working_days']=$this->getExpectedDays($pmonth,$pyear);
                $payroll['days_worked']=$this->getEmployeeDays($pmonth,$pyear,$payroll['start_day']);
                $this->calculatePAYE($payroll);
                if( $payroll['has_grade']==1){
                $payroll['serialize']['allowances'] = $payroll['allowances'];
                $payroll['serialize']['deductions'] = $payroll['deductions'];
                $payroll['serialize']['component_names'] = $payroll['component_names'];
                $payroll['serialize'] = serialize($payroll['serialize']);
                return $netpay=($payroll['basic_pay']+$payroll['total_allowances']-$payroll['total_deductions']-$payroll['paye'])*12;

                     }
    }
	public function runPayroll(Request $request)
	{
        
	    
        $date=date('Y-m-d',strtotime('01-'.$request->month));
        $pmonth=date('m',strtotime($date));
        $pyear=date('Y',strtotime($date));
        // $company=Company::find($request->company_id);
         $company_id=companyId();
         $basic_pay_percentage=intval(PayrollPolicy::where(['company_id'=>$company_id])->first()->basic_pay_percentage)/100;
         $users=User::has('promotionHistories.grade')->where('company_id',$company_id)->get();

        $payroll=Payroll::where(['month'=>$pmonth,'year'=>$pyear,'company_id'=>$company_id])->first();
        $pp=PayrollPolicy::where('company_id',$company_id)->first();

        if ($payroll) {
           return redirect()->back();
        } else {
             $PR=Payroll::create(['month'=>$pmonth,'year'=>$pyear,'company_id'=>$company_id,'workflow_id'=>$pp->workflow->id,'for'=>$date,'user_id'=>Auth::user()->id]);
             $components=SalaryComponent::where(['status'=>1,'company_id'=>$PR->company_id])->get()->pluck('id');
            
            $PR->salary_components()->attach($components);
                $allp=[];

            foreach ($users as $user) {
                 if($user->hiredate<=$date){
                  
                $payroll=[];
                $payroll['payroll']=$PR;
                $payroll['user_id']=$user->id;
                $payroll['date']=$date;
                $payroll['month']= $pmonth;
                $payroll['month']=$pyear;
                $payroll['company_id']=$company_id;
                
            
                if(date('m',strtotime($user->hiredate))==$pmonth &&date('Y',strtotime($user->hiredate))==$pyear){
                    $payroll['start_day']=date('d',strtotime($user->hiredate));
                }else{
                    $payroll['start_day']=1;
                }

                if(date('m',strtotime($user->hiredate))==$pmonth &&date('Y',strtotime($user->hiredate))!=$pyear){
                    $payroll['is_anniversary']=1;
                }else{
                    $payroll['is_anniversary']=0;
                }
                $payroll['working_days']=$this->getExpectedDays($pmonth,$pyear);
                $payroll['days_worked']=$this->getEmployeeDays($pmonth,$pyear,$payroll['start_day']);
                $this->calculatePAYE($payroll);
                $this->calculate_loan($payroll);
                if( $payroll['has_grade']==1){
                $payroll['serialize']['allowances'] = $payroll['allowances'];
                $payroll['serialize']['deductions'] = $payroll['deductions'];
                $payroll['serialize']['component_names'] = $payroll['component_names'];
                $payroll['serialize'] = serialize($payroll['serialize']);

                $payroll['sc_serialize']['sc_allowances'] = $payroll['sc_allowances'];
                $payroll['sc_serialize']['sc_deductions'] = $payroll['sc_deductions'];
                $payroll['sc_serialize']['sc_component_names'] = $payroll['sc_component_names'];
                $payroll['sc_serialize']['sc_project_code'] = $payroll['sc_project_code'];
                $payroll['sc_serialize']['sc_gl_code'] = $payroll['sc_gl_code'];
                $payroll['sc_serialize'] = serialize($payroll['sc_serialize']);

                $payroll['ssc_serialize']['ssc_allowances'] = $payroll['ssc_allowances'];
                $payroll['ssc_serialize']['ssc_deductions'] = $payroll['ssc_deductions'];
                $payroll['ssc_serialize']['ssc_component_names'] = $payroll['ssc_component_names'];
                $payroll['ssc_serialize']['ssc_project_code'] = $payroll['ssc_project_code'];
                $payroll['ssc_serialize']['ssc_gl_code'] = $payroll['ssc_gl_code'];
                $payroll['ssc_serialize'] = serialize($payroll['ssc_serialize']);
                     }
                 $payroll_details=PayrollDetail::create(['payroll_id'=>$PR->id,'user_id'=>$payroll['user_id'],'annual_gross_pay'=>$payroll['gross_pay'],'gross_pay'=>$payroll['gross_pay']/12,'basic_pay'=>$payroll['basic_pay'],'deductions'=>$payroll['total_deductions'],'allowances'=>$payroll['total_allowances'],'sc_allowances'=>$payroll['sc_total_allowances'],'sc_deductions'=>$payroll['sc_total_deductions'],'ssc_allowances'=>$payroll['ssc_total_allowances'],'ssc_deductions'=>$payroll['ssc_total_deductions'],'working_days'=>$payroll['working_days'],'worked_days'=>$payroll['days_worked'],'details'=>$payroll['serialize'],'sc_details'=>$payroll['sc_serialize'],'ssc_details'=>$payroll['ssc_serialize'],'is_anniversary'=>$payroll['is_anniversary'],'taxable_income'=>$payroll['taxable_income'],'annual_paye'=>$payroll['annual_paye'],'paye'=>$payroll['paye'],'consolidated_allowance'=>$payroll['consolidated_allowance']]);   
                $allp[]=$payroll;

              }  
            }
           
            return redirect(url('compensation/payroll_list?month='.date('m-Y',strtotime($date))));
        }
        

		
    
	}

	public function calculatePAYE(&$payroll)
	{
        $user=User::find($payroll['user_id']);
        if($user->promotionHistories){
          
            $payroll['gross_pay']=$user->promotionHistories()->latest()->first()->grade->basic_pay;
            $payroll['has_grade']=1;
            $payroll['gross_pay']= ($payroll['gross_pay']/$payroll['working_days'])*$payroll['days_worked'];

		
		$payroll['basic_pay_percentage']=floatval(PayrollPolicy::where(['company_id'=>$payroll['company_id']])->first()->basic_pay_percentage)/100;
		$payroll['basic_pay']=$payroll['gross_pay']*$payroll['basic_pay_percentage'];
		$this->allowancesanddeductions($payroll);
		$this->calculate_specific_salary_components($payroll);
        
		$payroll['taxable_income']= $payroll['gross_pay'] - $payroll['consolidated_allowance']-($payroll['not_taxable']*12) + ($payroll['ssc_total_allowances']*12);
		$this->calculate_tax($payroll);
		}else{

         $payroll['has_grade']=0;
        }


	}
	public function allowancesanddeductions(&$payroll)
	{
		$user_id=$payroll['user_id'];
		$components=SalaryComponent::where(['status'=>1,'company_id'=>$payroll['company_id']])->whereDoesntHave('exemptions', function ($query) use ($user_id){
			$query->where('users.id',$user_id);
			})->get();
		
		
		// foreach ($deductions as $deduction) {
		// 	$calc=$deduction->formula;
		// 	$calc=str_replace($basic_pay, '$'.$basic_pay, $calc);
		// 	$calc=str_replace($gross_pay, '$'.$gross_pay, $calc);
		// 	foreach ($components as $component) {
		// 		$calc=str_replace($component->constant, '$'.$component->constant, $calc);
		// 	}
		// 	$payroll[$deduction->constant]['name']=>$deduction_name;
		// 	$payroll[$deduction->constant]['value']=>eval("\$calc = \"$calc\";");
			
		// }

            // calculate allowances and deductions

            $net = ['basic_pay' => $payroll['basic_pay'],'basic_salary'=>$payroll['basic_pay'] ,'gross_salary' => $payroll['gross_pay'],'gross_pay'=>$payroll['gross_pay']];

            $payroll['allowances'] = $payroll['deductions'] =$payroll['component_names']=$payroll['sc_component_names']=  [];
             $payroll['sc_allowances'] = $payroll['sc_deductions'] =$payroll['sc_project_code'] = $payroll['sc_gl_code']= [];
            $payroll['total_allowances']=$payroll['total_deductions']= $payroll['not_taxable']=0;
             $payroll['sc_total_allowances']=$payroll['sc_total_deductions']=0;

            foreach ($components as $component) {

                if ($component->status == 1) {
                
                    $payroll['component_names'][$component->constant] = $component->name;
                    $payroll['sc_component_names'][$component->constant] = $component->name;
                    $payroll['sc_project_code'][$component->constant] = $component->project_code;
                    $payroll['sc_gl_code'][$component->constant] = $component->gl_code;
    
                    $net[$component->constant] = $value = $this->calculate_salary_component($component->constant, $component->formula, $net);
                    if ($component->type==1) {
                        $payroll['allowances'][$component->constant] = number_format($value, 2, '.', '');
                         $payroll['sc_allowances'][$component->constant] = number_format($value, 2, '.', '');

                    } else {
                       $payroll['deductions'][$component->constant] = number_format($value, 2, '.', '');
                        $payroll['sc_deductions'][$component->constant] = number_format($value, 2, '.', '');
                    }
                    
    
                    // $payroll[$component->type == 1 ? 'allowances' : 'deductions'][$component->constant] = 
                        // number_format($value, 2, '.', '');
                        if ($component->type == 1) {
                           $payroll['total_allowances'] += $value;
                           $payroll['sc_total_allowances'] += $value;
                        } else {
                           $payroll['total_deductions'] += $value;
                           $payroll['sc_total_deductions'] += $value;
                        }
                        if ($component->taxable==0) {
                          $payroll['not_taxable']=number_format($value, 2, '.', '');
                        }
                
                    
                }
            }
            $payroll['gross_pay']=$payroll['gross_pay']*12;
            // $payroll['gross_tax']=($payroll['gross_pay']-$payroll['deductions']['pension'])*12;
             $payroll['consolidated_allowance'] = $this->consolidated_allowance($payroll['gross_pay']);

	}

	private function consolidated_allowance($gross_salary) {

            $annual_gross = $gross_salary;


           return  $consolidated = ($annual_gross * (1 / 100)) > 200000 ?
                (($annual_gross * (1 / 100)) + ($annual_gross * (20 / 100))):
                (200000 + ($annual_gross * (20 / 100)));

            // return abs(number_format($consolidated, 2, '.', '') / 12);

        }


	private function calculate_salary_component($constant, $formula, &$net) {

            foreach ($net as $key => $value) {
                if (substr_count($formula, $key)) {
                    $formula = str_ireplace($key, $net[$key], $formula);
                }
            }

            eval('$result = (' . $formula .');');

            return $result;

        }

        private function calculate_specific_salary_components(&$payroll) {
        	$user=User::find($payroll['user_id']);
            $components = $user->specificSalaryComponents()->whereDate('starts', '<=', $payroll['date'])
            // ->whereDate('ends', '>=',  $payroll['date'])
            ->get();
            $payroll['ssc_allowances'] = $payroll['ssc_deductions'] =$payroll['ssc_project_code'] = $payroll['ssc_gl_code'] = [];
            $payroll['specifics']['allowances'] = $payroll['specifics']['deductions'] = 0;
            $payroll['ssc_total_allowances']=$payroll['ssc_total_deductions']=0;
            $payroll['ssc_component_names']=[];
            $user=User::find($payroll['user_id']);
            if ($components) {
                
                foreach ($components as $key => $component) {
                    if($component->status==1 and $component->completed!=1){

                     $payroll['component_names'][$key] = $component->name.'-'.$user->name;
                     $payroll['ssc_component_names'][$key] = $component->name.'-'.$user->name;
                    $payroll['specifics'][$component->type == 1 ? 'allowances' : 'deductions'] += $component->amount;
                     $payroll[$component->type == 1 ? 'ssc_allowances' : 'ssc_deductions'][$key] = number_format($component->amount, 2, '.', '');
                     $payroll['ssc_project_code'][$key] = $component->project_code;
                      $payroll['ssc_gl_code'][$key] = $component->gl_code;
                    $payroll[$component->type == 1 ? 'allowances' : 'deductions'][$key] = number_format($component->amount, 2, '.', '');

                    if (($component->grants+1)==$component->duration) {
                        $component->update(['grants'=>intval($component->grants)+1,'completed'=>1]);

                    }else{
                        $component->update(['grants'=>intval($component->grants)+1]);
                    }
                    $payroll['payroll']->specific_salary_components()->attach($component->id);
                    if ($component->type == 1) {
                          
                           $payroll['ssc_total_allowances'] += $component->amount;
                        } else {
                          
                           $payroll['ssc_total_deductions'] += $component->amount;
                        }
                }
                   
                }
            }
            
        }
        public function calculate_loan(&$payroll)
        {
            $user=User::find($payroll['user_id']);
            $loans=$user->loan_requests()->where(['status'=>1,'completed'=>0])->whereDate('repayment_starts','<=',$payroll['date'])->get();
            $payroll['loans']['deductions']=0;
            if ($loans) {
                
                foreach ($loans as $key => $component) {
                    if($component->status==1 and $component->completed!=1){

                     $payroll['component_names'][] = $user->name.'- Loan';
                     $payroll['ssc_component_names'][] = $user->name.'- Loan';

                    $payroll['loans']['deductions'] += $component->monthly_deduction;
                    $payroll['deductions'][] = number_format($component->monthly_deduction, 2, '.', '');
                     $payroll['ssc_deductions'][] = number_format($component->monthly_deduction, 2, '.', '');
                    $payroll['total_deductions']+=$component->monthly_deduction;
                    $payroll['ssc_total_deductions'] += $component->monthly_deduction;
                    if (($component->months_deducted+1)==$component->period) {
                        $component->update(['months_deducted'=>intval($component->months_deducted)+1,'completed'=>1]);

                    }else{
                        $component->update(['months_deducted'=>intval($component->months_deducted)+1]);
                    }
                     $payroll['payroll']->loan_requests()->attach($component->id);
                    
                    
                }
                   
                }
            }
        }
        public function calculate_tax(&$payroll)
        {
        	$ti=$payroll['taxable_income'];
        	$lv1=0;
        	$lv2=0;
        	$lv3=0;
        	$lv4=0;
        	$lv5=0;
        	$lv6=0;
        	//first level

        	if ($ti<300000) {
        		$lv1=$payroll['gross_pay']*0.01;
        		$ti=0;
        	} elseif ($ti==300000) {
        		$lv1=$ti*0.07;
        		$ti=$ti-$ti;
        	}elseif ($ti>300000) {
        		$ti=$ti-300000;
        		$lv1=300000*0.07;
        	}
        	// second level
        	if ($ti<300000) {
        		$lv2=$ti*0.11;
        		$ti=$ti-$ti;
        	} elseif ($ti==300000) {
        		$lv2=$ti*0.11;
        		$ti=$ti-$ti;
        	}elseif ($ti>300000) {
        		$ti=$ti-300000;
        		$lv2=300000*0.11;
        	}
        	//third level
        	if ($ti<500000) {
        		$lv3=$ti*0.15;
        		$ti=$ti-$ti;
        	} elseif ($ti==500000) {
        		$lv3=$ti*0.15;
        		$ti=$ti-$ti;
        	}elseif ($ti>500000) {
        		$ti=$ti-500000;
        		$lv3=500000*0.15;
        	}
        	//fourth level
        	if ($ti<500000) {
        		$lv4=$ti*0.19;
        		$ti=$ti-$ti;
        	} elseif ($ti==500000) {
        		$lv4=$ti*0.19;
        		$ti=$ti-$ti;
        	}elseif ($ti>500000) {
        		$ti=$ti-500000;
        		$lv4=500000*0.19;
        	}
        	//fifth level
        	if ($ti<1600000) {
        		$lv5=$ti*0.21;
        		$ti=$ti-$ti;
        	} elseif ($ti==1600000) {
        		$lv5=$ti*0.21;
        		$ti=$ti-$ti;
        	}elseif ($ti>1600000) {
        		$ti=$ti-1600000;
        		$lv5=1600000*0.21;
        	}
        	//sixth level
        	if ($ti<3200000) {
        		$lv6=$ti*0.24;
        		$ti=$ti-$ti;
        	} elseif ($ti==3200000) {
        		$lv6=$ti*0.24;
        		$ti=$ti-$ti;
        	}elseif ($ti>3200000) {	
        		$lv6=$ti*0.24;
        	}


        	$payroll['annual_paye']=$lv1+$lv2+$lv3+$lv4+$lv5+$lv6;
        	$payroll['paye']=$payroll['annual_paye']/12;
        	
        }

    public function getExpectedDays($month,$year)
    {
        $total_days=0;
        $days=cal_days_in_month(CAL_GREGORIAN,$month,$year);
         for ($i=1; $i <=$days ; $i++) { 
            if (date('N',strtotime("$year-$month-$i"))<6 && $this->checkHoliday("$year-$month-$i")==false) {
                    $total_days++;
            }     
            }
            return$total_days;
    }
    public function getEmployeeDays($month,$year,$start=1)
    {
        $total_days=0;
        $days=cal_days_in_month(CAL_GREGORIAN,$month,$year);
        if ($start==1) {
            for ($i=1; $i <=$days ; $i++) { 
            if (date('N',strtotime("$year-$month-$i"))<6 && $this->checkHoliday("$year-$month-$i")==false) {
                    $total_days++;
            }     
            }
             return $total_days;
        } elseif($start>1) {
            for ($i=$start; $i <=$days ; $i++) { 
            if (date('N',strtotime("$year-$month-$i"))<6 && $this->checkHoliday("$year-$month-$i")==false) {
                    $total_days++;
            } 
        }
        
       
            return $total_days;
    }
    }
    public function checkHoliday($date)
    {
        $has_holiday=Holiday::whereDate('date', $date)->first();
        $retVal = ($has_holiday) ? true : false ;
        return $retVal;
    }
    public function issuePayslip(Request $request)
    {

        $payroll=Payroll::where('id',$request->payroll_id);
       if ($payroll) {
         $payroll=$payroll->update(['payslip_issued'=>1]);
          return 'success';
       }
      
    }
    public function downloadPayslip(Request $request)
    {

        $detail=PayrollDetail::find($request->id);
        $logo=PayslipDetail::first()->logo;
        // return view('compensation.partials.payslip', compact('detail','logo'));
        $pdf = PDF::loadView('compensation.partials.payslip', compact('detail','logo'));
         // $pdf->setWatermarkImage(public_path('storage'.$logo));
        // $pdf->setWatermarkText('example', '150px');
        return $pdf->stream(Auth::user()->name.'.pdf');
    }
    public function rollbackPayroll(Request $request)
    {
     $payroll=Payroll::find($request->payroll_id);
     $sscs=$payroll->specific_salary_components;
     $lrs=$payroll->loan_requests;
     $pds=$payroll->payroll_details;
     if ($pds) {
      foreach ($pds as $pd) {
       $pd->delete();
      }
     }
     
     if ($sscs) {
       foreach ($sscs as $ssc) {
         $ssc->update(['grants'=>intval($ssc->grants)-1,'completed'=>0]);
         $payroll->specific_salary_components()->detach($ssc->id);
       }
     }
     if ($lrs) {
       foreach ($lrs as $lr) {
         $lr->update(['months_deducted'=>intval($lr->months_deducted)-1,'completed'=>0]);
         $payroll->loan_requests()->detach($lr->id);
       }
     }

     $payroll->delete();
     return 'success';
    }


}