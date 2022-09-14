<?php

namespace App\Jobs;

use App\AttendanceReport;
use App\FinancialReport;
use App\FinancialReportDetail;
use App\Setting;
use App\Traits\Attendance;
use App\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class CalculatePaymentJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels,attendance;
    public $year;
    public $month;
    public $start;
    public $end;
    public $user;
    public $days;

    /**
     * CalculatePaymentJob constructor.
     * @param $year
     * @param $month
     */
    public function __construct($year,$month,$start,$end,$user,$days)
    {
        $this->year=$year;
        $this->month=$month;
        $this->start=$start;
        $this->end=$end;
        $this->user=$user;
        $this->days=$days;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $year=$this->year;
        $month=$this->month;
        $start=$this->start;
        $end=$this->end;
        $days=$this->days;
        //$end=Carbon::parse($this->end)->addDays(6)->format('Y-m-d');

        /*$this_month = Carbon::create($year,$month);
        $last_month_month =$this_month->subMonth()->format('m');
        $last_month_year =$this_month->subMonth()->format('Y');

        $end = Carbon::create($year,$month,24)->format('Y-m-d');
        $start=Carbon::create($last_month_year,$last_month_month,27)->format('Y-m-d');*/

        $date = Carbon::create($year,$month);
        $attendance_report=AttendanceReport::whereMonth('date', $date->format('m'))->whereYear('date', $date->format('Y'))
            ->orderBy('id','desc')->first();
        if ($attendance_report){
            $last=$attendance_report;
            $last_date=$last->date;
            $last_date=Carbon::parse($last_date);
            $last_day=$last_date->format('d');
            $f_report=FinancialReport::updateOrCreate(['month' => $date->format('m'), 'year' => $date->format('Y')],
                ['start'=>$start,'end'=>$end, 'day' => $last_day,'created_by'=>$this->user,'attendance_report_id'=>$last->id,'days'=>$days]
            );
            FinancialReportDetail::where('finance_report_id',$f_report->id)->delete();
            User::with('role')->with('company')->chunk(10, function ($users) use($date,$f_report,$start,$end) {
                foreach ($users as $user) {
                    /*$start='2020-02-01';
                    $end='2020-02-26';*/
                    $user_att=AttendanceReport::whereBetween('date',[$start,$end])
                        //$user_att=AttendanceReport::whereMonth('date', $date->format('m'))->whereYear('date', $date->format('Y'))
                        ->where('user_id', $user->id)->get();
                    $present = $user_att->whereIn('status', ['late', 'early'])->count();
                    if ($present>0) {
                        $present=$present+$f_report->days;
                        $to_receive_days=$this->calculateDaysToPay($present,$user->company->pay_full_days);
                        $to_receive = $to_receive_days * $user->role->daily_pay;
                        if ($present >= $user->company->pay_full_days) {
                            $to_receive = $user->role->amount;
                        }
                        $absent=$user_att->where('status', 'absent')->count();
                        $late= $user_att->where('status', 'late')->count();
                        $off= $user_att->where('status', 'off')->count();
                        FinancialReportDetail::updateOrCreate(['user_id' => $user->id, 'finance_report_id' => $f_report->id],
                            ['role_id' => $user->role->name, 'days_worked' => $present, 'present' => $present,
                                'company_id' => $user->company_id, 'absent' => $absent, 'late' =>$late,'off'=>$off,
                                'amount_expected' => $user->role->amount, 'amount_received' => $to_receive]
                        );
                    }
                }
            });
            Setting::where('name','payroll_running')->update(['value'=>'no']);
            $message='Successfully ran payroll';
        }
        else{
            $message='Error running Payroll';
        }




    }
}
