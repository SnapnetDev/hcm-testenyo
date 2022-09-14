<?php

namespace App\Console\Commands;

use App\Branch;
use App\Company;
use App\Mail\SendAttachMail;
use App\Mail\SendMail;
use App\User;
use App\UserDailyShift;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendReportForDailyShift extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dailyshift:report';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $from='info@snapnet.com.ng';
        $ids=['5','64','65'];
        $companies= Company::where('biometric_serial','!=',null)->whereNotIn('id',$ids)->orderBy('name','asc')->get();
        foreach ($companies as $company){
            $company['ready']='NO';
            $tmw=Carbon::tomorrow();
            $users=User::where('status','1')->where('company_id',$company->id)->get();
            $user_ids= $users->pluck('id')->toArray();
            $last_uds=UserDailyShift::whereIn('user_id',$user_ids)->orderBy('sdate','desc')->first();
            $next_uds=UserDailyShift::whereIn('user_id',$user_ids)->where('sdate',$tmw->format('Y-m-d'))->first();
            if ($next_uds){
                $company['ready']='YES';
            }
            if($company->last_seen){
                $sync=$company->last_seen->created_at->format('d M Y H:i s');
            }
            else{
                $sync='Never';
            }
            if($last_uds){
                $date=$last_uds->sdate;
            }
            else{
                $date='Never';
            }
            $company['sync']=$sync;
            $company['date']=$date;
        }

        $view_t='emails.emaildailyshift';
        $view_template='emails.shiftreport';
        $branches=Branch::all();
        foreach ($branches as $branch){
            $stations=$companies->where('branch_id',$branch->id);
            $mail='Kindly find attached report for Shift Scheduled in '.$branch->name;
            $subject='Shift Schedule report for '.$branch->name.' Branch';
            $data=['stations'=>$stations,'mail'=>$mail,'subject'=>$subject];

            $pdf = \App::make('dompdf.wrapper');
            $pdf->loadView($view_template, compact('data'));

            if(isset($branch->email)){
                Mail::to($branch->email)->send(new SendAttachMail($from,$subject,$data,$view_t,$pdf));

            }
        }
        $emails=['timothy@snapnet.com.ng','funmilola.k@enyoretail.com','enyoit@enyoretail.com'];

        $subject='All Stations Shift Schedule Report';
        $mail='Please download the attached Shift Schedule Report for all Stations';
        $data=['stations'=>$companies,'mail'=>$mail,'subject'=>$subject];

        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadView($view_template, compact('data'));
        //Mail::to('timothy@snapnet.com.ng')->send(new SendAttachMail($from,$subject,$data,$view_t,$pdf));
        foreach($emails as $email){
            Mail::to($email)->send(new SendAttachMail($from,$subject,$data,$view_t,$pdf));
        }
    }
}
