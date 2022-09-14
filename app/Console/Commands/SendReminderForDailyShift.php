<?php

namespace App\Console\Commands;

use App\Company;
use App\Mail\SendMail;
use App\User;
use App\UserDailyShift;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendReminderForDailyShift extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dailyshift:reminder';

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
        $view_template='emails.emaildailyshift';
        $ids=['5','64','65'];
        $companies= Company::whereNotIn('id',$ids)->get();
        foreach ($companies as $company){
            $sm=User::where('role_id','2')->where('status','1')->where('company_id',$company->id)->first();
            $subject='Shift Schedule Reminder for '.$company->name;
            $tmw=Carbon::tomorrow();
            $end=Carbon::tomorrow()->addDay(13);
            $message='Dear Station Manager,
                <br><br>Please be reminded to set your shifts for the working week starting from '.$tmw->format('D, d M Y').' to '.$end->format('D, d M Y').'
                <br><br>Ensure that you log on to TAMS and complete your shift setting no later than 8am on '.$tmw->format('D, d M Y').'.
                <br><br>Thank you.';

            $data=['mail'=>$message];
            if(isset($sm->email) && isset($company->branch->email)){
                //Mail::to('timothy@snapnet.com.ng')->send(new SendMail($from,$subject,$data,$view_template));
                Mail::to($sm->email)/*->cc($company->branch->email)*/->send(new SendMail($from,$subject,$data,$view_template));
            }

        }

        $emails=['funmilola.k@enyoretail.com','enyoit@enyoretail.com'];
        foreach($emails as $email){
            Mail::to($email)->send(new SendMail($from,$subject,$data,$view_template));
        }
        $subject='User Daily Shift Reminder Sent';
        $message='All User Daily Shift Reminder Sent';
        $data=['mail'=>$message];
        Mail::to('timothy@snapnet.com.ng')->cc('olaoluwa@snapnet.com.ng')->send(new SendMail($from,$subject,$data,$view_template));
    }
}
