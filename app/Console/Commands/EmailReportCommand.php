<?php

namespace App\Console\Commands;

use App\AttendanceReport;
use App\Branch;
use App\Company;
use App\Mail\SendAttachMail;
use App\Mail\SendMail;
use App\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class EmailReportCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:report';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send daily attendance report to SSM via email';

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
        $view_t='emails.emaildailyshift';
        $view_template='emails.emailexedailyreport';
        $branches=Branch::all();
        $today=Carbon::yesterday();
        $from='info@snapnet.com.ng';
        $ids=['5','64','65'];
        $companies= Company::where('biometric_serial','!=',null)->whereNotIn('id',$ids)->orderBy('name','asc')->get();
        foreach ($companies as $station){
            $users = User::where('company_id', $station->id)->get();
            $attendances = AttendanceReport::whereIn('user_id', $users->pluck('id')->toArray())->whereDate('date', $today->format('Y-m-d'))->with('user')->get();
            $station['exp_hours']=0;
            $station['avg_hours']=0;
            $station['earlys'] = $attendances->where('status', 'early')->count();
            $station['lates'] = $attendances->where('status', 'late')->count();
            $station['absentees'] = $attendances->where('status', 'absent')->count();
            $station['offs'] = $attendances->where('status', 'off')->count();
            $station['presents'] = $attendances->whereIn('status', ['early', 'late'])->count();
            $station['amount'] = $attendances->sum('amount');

            foreach ($attendances as $attendance){

                $station['all_exp_hours']+=$this->getHoursBetween($attendance->shift_start,$attendance->shift_end);
            }

            $station['hours_worked'] =$attendances->where('hours_worked','!=',"")->sum('hours_worked');

            if( $attendances->count()>0){
                $station['exp_hours']=$station['all_exp_hours']/$attendances->count();
            }

            if ($station->lates+$station->earlys>0){
                $station['avg_hours'] = $station->hours_worked/($station->lates+$station->earlys);
            }
        }

        foreach ($branches as $branch){
            $subject=$branch->name.' FIELD STAFF ATTENDANCE REPORT FOR '.$today->format('D d M, Y');
            $stations=$companies->where('branch_id',$branch->id);
            $data=['stations'=>$stations,'branch_name'=>$branch->name,'date'=>$today->format('D d M, Y'),'subject'=>$subject];

            $pdf = \App::make('dompdf.wrapper');
            $pdf->loadView($view_template, compact('data'))->setPaper('a4', 'landscape');
            $data['mail']='Please download the attached file for '. $subject;

            //Mail::to($branch->email)->send(new SendAttachMail($from,$subject,$data,$view_t,$pdf));
            //Mail::to('timothy@snapnet.com.ng')->send(new SendAttachMail($from,$subject,$data,$view_t,$pdf));
        }

        //$emails=['olaoluwa@snapnet.com.ng','timothy@snapnet.com.ng','odoma.m@enyoretail.com','muhammad.k@enyoretail.com','godwin.o@enyoretail.com','toluwalashe.a@enyoretail.com','othman.a@enyoretail.com','habiba.a@enyoretail.com','nneka.n@enyoretail.com','rasheedat.s@enyoretail.com','oluwatosin.a@enyoretail.com','olabanjo.a@enyoretail.com','fernando.m@enyoretail.com','abayomi.a@enyoretail.com','odoma.m@enyoretail.com','adesanmi.a@enyoretail.com ','mohammed.g@enyoretail.com'];
        $emails=['timothy@snapnet.com.ng','odoma.m@enyoretail.com'];
        $view_template2='emails.emailexedailyreport';
        $subject='FIELD STAFF GENERAL ATTENDANCE REPORT FOR '.$today->format('D d M, Y');
        $data=['stations'=>$companies,'date'=>$today->format('D d M, Y'),'subject'=>$subject];

        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadView($view_template2, compact('data'))->setPaper('a4', 'landscape');
        $data['mail']='Please download the attached file for '. $subject;

         foreach($emails as $email){
             Mail::to($email)->send(new SendAttachMail($from,$subject,$data,$view_t,$pdf));
         }
        //Mail::to('timothy@snapnet.com.ng')->send(new SendAttachMail($from,$subject,$data,$view_t,$pdf));

    }

    public function getHoursBetween($shift_in,$shift_out){
        $clock_in = Carbon::createFromFormat('H:i:s', $shift_in);
        if ($shift_out){
            $clock_out = Carbon::createFromFormat('H:i:s', $shift_out);
        }else{
            //if there is no clock out, set the clock out to be clockin
            $clock_out = Carbon::createFromFormat('H:i:s', $shift_in);
        }
        if ($clock_in>$clock_out){//it means clockout is next day
            $clock_out = $clock_out->addDay();
        }

        return $clock_in->diffInHours($clock_out);
    }
}
