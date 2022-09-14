<?php
namespace App\Traits;
use App\Payroll;
use App\Bank;
use App\Company;
use App\CompanyAccountDetail;
use App\PayslipDetail;
use App\PayrollDetail;
use App\PayrollPolicy;
use App\SalaryComponent;
use App\SpecificSalaryComponent;
use App\Workflow;
use App\LatenessPolicy;
use App\Setting;
use App\Loan;
use App\User;
use App\Holiday;
use App\loanRequest;
use App\LoanApproval;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Auth;
use Excel;

trait LoanTrait{

	public function processGetLoan($route,Request $request)
	{
        // return $request->all();
		switch ($route) {
			case 'loan_requests':
           
				return $this->getLoanList($request);
				break;
			case 'my_loan_requests':
           
				return $this->myLoanList($request);
				break;
			case 'approve_loan_request':
           
				return $this->approveLoan($request);
				break;
			case 'loan_excel':
           
				return $this->loanExcel($request);
				break;
            
			default:
				# code...
				break;
		}
	}
	public function processPostLoan(Request $request)
	{
		switch ($request->type) {
			case 'loan_request':
				return $this->saveLoanRequest($request);
				break;
			
			default:
				# code...
				break;
		}
	}

	public function myLoanList(Request $request)
	{
		$loan_requests=Auth::user()->loan_requests()->paginate(5);

		return view('loan.mylist',compact('loan_requests'));
	}

	public function getLoanList(Request $request)
	{
		$loan_requests=LoanRequest::paginate(5);

		return view('loan.list',compact('loan_requests'));
	}

	public function saveLoanRequest(Request $request)
	{
		$netpay=$request->netpay;
		$maximum_allowed=$netpay*(intval(Setting::where('name','loan_allowed')->first()->value)/100);
		$annual_interest=intval(Setting::where('name','loan_interest')->first()->value);
		$monthly_rate=$annual_interest/12/100;
		$start=1;
		$monthly_payment;
		$length=1+$monthly_rate;
		for ($i=0; $i <$request->period ; $i++) { 
			$start=$start*$length;
		}
		$repayment_starts=date('Y-m-d',strtotime($request->starts.'-01'));
		$payment=$monthly_payment=$request->amount*$monthly_rate/(1-(1/$start));
		$total_amount_repayable=$payment*$i;
		$total_interest=$total_amount_repayable-$request->amount;
			$loan_request=LoanRequest::create(['user_id'=>Auth::user()->id,'netpay'=>$netpay,'amount'=>$request->amount, 'monthly_deduction'=>$payment,'period'=>$request->period,'current_rate'=>$annual_interest,'repayment_starts'=>$repayment_starts,'status'=>0,'approved_by'=>0,'completed'=>0]);
			$stage=Workflow::find($loan_request->workflow_id)->stages->first();
        $loan_approval=new LoanApproval::create(['stage_id'=>$stage->id,'status'=>0,'loan_request_id'=>$loan_request->id]);
       
			if($loan_request){
				return 'success';
			}else{
				return 'failed';
			}
			

	}
	public function approveLoan(Request $request)
	{
		$loan_request=loanRequest::find($request->loan_request_id);
	   if ($loan_request) {
	     $loan_request->update(['status'=>1,'approved_by'=>Auth::user()->id]);
	   }
	   return 'success';
	}

	public function loanExcel(Request $request)
	{
		$loan_request_id=$request->loan_request_id;
		$loan_request=LoanRequest::find($loan_request_id);
		$netpay=333245;
		$view='loan.loan_schedule';
		$this->exportToExcel($loan_request,$netpay,$view);

	}
	private function exportToExcel($datas,$netpay,$view){

        return     \Excel::create("$view", function($excel) use ($datas,$view,$netpay) {

            $excel->sheet("$view", function($sheet) use ($datas,$view,$netpay) {
                $sheet->loadView("$view",compact("datas","netpay"))
                ->setOrientation('landscape');
            });

        })->export('xlsx');
    }
}

