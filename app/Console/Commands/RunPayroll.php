<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Payroll;
use App\Bank;
use App\CompanyAccountDetail;
use App\PayslipDetail;
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
use App\Company;
use Auth;
use Excel;
use App\Traits\PayrollTrait;

class RunPayroll extends Command
{
    use PayrollTrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    public $month=0;
    public $year=0;
    public $company_id=0;
    protected $signature = 'payroll:run ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Payroll run';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $this->month = $this->ask('What is the payroll month?');
        $this->year = $this->ask('What is the payroll year?');
        $companies=Company::all();
        $plucked = $companies->pluck('name');
        $companies=$plucked->all();
        $company = $this->choice('What the name of your company?', $companies);
        $company_id=Company::where('name',$company)->first()->id;
        $payroll=$this->runPayroll();
        $this->info(json_encode($payroll));


    }

    public function runPayroll()
    {
        $users=User::has('promotionHistories.grade')->get();
        $allp=[];
        foreach ($users as $user) {
            $payroll=[];
            $payroll['user_id']=$user->id;
            $payroll['month']=$this->month;
            $payroll['year']=$this->year;
        
            if(date('m',strtotime($user->hiredate))==$this->month &&date('Y',strtotime($user->hiredate))==$this->year){
                $payroll['start_day']=date('d',strtotime($user->hiredate));
            }else{
                $payroll['start_day']=1;
            }

            if(date('m',strtotime($user->hiredate))==$this->month &&date('Y',strtotime($user->hiredate))!=$this->year){
                $payroll['is_anniversary']=1;
            }else{
                $payroll['is_anniversary']=0;
            }
            $payroll['working_days']=$this->getExpectedDays($this->month,$this->year);
            $payroll['days_worked']=$this->getEmployeeDays($this->month,$this->year);
            $this->calculatePAYE($payroll);
            if( $payroll['has_grade']==1){
            $payroll['serialize']['allowances'] = $payroll['allowances'];
            $payroll['serialize']['deductions'] = $payroll['deductions'];
            $payroll['serialize']['component_names'] = $payroll['component_names'];
            $payroll['serialize'] = serialize($payroll['serialize']);
                 }
            $allp[]=$payroll;

            
        }
        return $allp;
    }
}
