<?php

namespace App\Http\Controllers;

use App\Attendance;
use App\Jobs\ProcessDeviceData;
use App\ManualAttendance;
use App\AttendanceDetail;
use App\Branch;
use App\Biometric;
use App\CommandLog;
use App\Company;
use App\Jobs\ProcessSingleAttendanceJob;
use App\Jobs\FetchUsersJob;

use App\Mail\SendMail;
use App\User;
use App\FinancialReport;
use App\WorkingPeriod;
use App\UserDailyShift;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use App\Traits\Attendance as AttendanceTrait;
use App\Traits\Biometric as BiometricTrait;
use Artisan;
use App\Traits\FaceMatchTrait as FMT;


use App\AttendanceReport;
use DB;
use Seguce92\DomPDF\PDF;

class BiometricController extends Controller
{
    use AttendanceTrait,BiometricTrait,FMT;
    public function data(){

        ini_set('max_execution_time', '0');
         $this->fetchEnyoUsers();
        
       //  Artisan::call('queue:work');
         return 'done';

        //Artisan::call('fetch:locations');
        //Artisan::call('fetch:users');
        //return $data;

        $serial='111';
      
       //$data="1568\t2020-11-07 06:52:18\t0\t1\t\t0\t0\t\n1467\t2020-11-07 06:52:21\t0\t1\t\t0\t0\t\n2269\t2020-11-07 06:55:26\t0\t1\t\t0\t0\t\n2114\t2020-11-07 06:55:29\t0\t1\t\t0\t0\t\n6363\t2020-11-07 07:00:33\t0\t1\t\t0\t0\t\n1590\t2020-11-07 07:00:44\t0\t1\t\t0\t0\t\n9292\t2020-11-07 07:05:28\t0\t1\t\t0\t0\t\n2123\t2020-11-07 07:30:26\t0\t1\t\t0\t0\t\n1218\t2020-11-07 07:30:29\t0\t1\t\t0\t0\t\n1160\t2020-11-07 07:30:36\t0\t1\t\t0\t0\t\n2201\t2020-11-07 07:39:54\t0\t1\t\t0\t0\t\n3265\t2020-11-07 07:40:01\t0\t1\t\t0\t0\t\n3212\t2020-11-07 08:03:03\t0\t1\t\t0\t0\t\n1218\t2020-11-07 08:03:34\t0\t1\t\t0\t0\t\n1448\t2020-11-07 08:27:24\t0\t1\t\t0\t0\t\n3400\t2020-11-07 08:33:48\t0\t1\t\t0\t0\t\n993\t2020-11-07 10:18:07\t0\t1\t\t0\t0\t\n1558\t2020-11-07 15:39:05\t1\t1\t\t0\t0\t\n1558\t2020-11-07 16:55:45\t0\t1\t\t0\t0\t\n3212\t2020-11-07 17:32:51\t1\t1\t\t0\t0\t\n3265\t2020-11-07 18:17:15\t1\t1\t\t0\t0\t\n2201\t2020-11-07 18:20:16\t1\t1\t\t0\t0\t\n2122\t2020-11-07 21:50:16\t1\t1\t\t0\t0\t\n1462\t2020-11-07 21:50:26\t1\t1\t\t0\t0\t\n7574\t2020-11-07 21:50:41\t1\t1\t\t0\t0\t\n1598\t2020-11-07 22:49:16\t1\t1\t\t0\t0\t\n1462\t2020-11-07 22:49:23\t1\t1\t\t0\t0\t\n1724\t2020-11-07 22:51:19\t1\t1\t\t0\t0\t\n1664\t2020-11-07 22:51:29\t1\t1\t\t0\t0\t\n1326\t2020-11-07 23:33:14\t1\t1\t\t0\t0\t\n2440\t2020-11-07 23:33:21\t1\t1\t\t0\t0\t\n1108\t2020-11-07 23:34:31\t1\t1\t\t0\t0\t\n1218\t2020-11-07 23:34:39\t1\t1\t\t0\t0\t\n2123\t2020-11-07 23:40:33\t1\t1\t\t0\t0\t\n1218\t2020-11-07 23:40:42\t1\t1\t\t0\t0\t\n1160\t2020-11-07 23:40:44\t1\t1\t\t0\t0\t\n1558\t2020-11-08 00:27:06\t1\t1\t\t0\t0\t\n1590\t2020-11-08 00:27:24\t1\t1\t\t0\t0\t\n6363\t2020-11-08 00:27:35\t1\t1\t\t0\t0\t\n1462\t2020-11-08 00:47:17\t1\t1\t\t0\t0\t\n1598\t2020-11-08 00:47:24\t1\t1\t\t0\t0\t\n1091\t2020-11-08 00:47:35\t1\t1\t\t0\t0\t\n2122\t2020-11-08 00:47:46\t1\t1\t\t0\t0\t\n1150\t2020-11-08 00:47:55\t1\t1\t\t0\t0\t\n1558\t2020-11-08 00:48:41\t1\t1\t\t0\t0\t\n2269\t2020-11-08 01:15:23\t1\t1\t\t0\t0\t\n2114\t2020-11-08 01:15:28\t1\t1\t\t0\t0\t\n3266\t2020-11-08 01:32:28\t1\t1\t\t0\t0\t\n1091\t2020-11-08 01:32:35\t1\t1\t\t0\t0\t\n7575\t2020-11-08 02:53:27\t1\t1\t\t0\t0\t\n7576\t2020-11-08 02:53:49\t1\t1\t\t0\t0\t\n1724\t2020-11-08 03:09:06\t1\t1\t\t0\t0\t\n1664\t2020-11-08 03:09:13\t1\t1\t\t0\t0\t\n1218\t2020-11-08 05:18:02\t0\t1\t\t0\t0\t\n";
       $data="2117\t2020-11-06 23:23:52\t1\t1\t\t0\t0\t\n1462\t2020-11-06 23:24:25\t1\t1\t\t0\t0\t\n1598\t2020-11-06 23:24:31\t1\t1\t\t0\t0\t\n1091\t2020-11-06 23:24:38\t1\t1\t\t0\t0\t\n2122\t2020-11-06 23:24:44\t1\t1\t\t0\t0\t\n1558\t2020-11-06 23:25:04\t1\t1\t\t0\t0\t\n7574\t2020-11-06 23:50:06\t1\t1\t\t0\t0\t\n6363\t2020-11-07 00:13:25\t1\t1\t\t0\t0\t\n1590\t2020-11-07 00:13:31\t1\t1\t\t0\t0\t\n2269\t2020-11-07 00:20:14\t1\t1\t\t0\t0\t\n2114\t2020-11-07 00:20:18\t1\t1\t\t0\t0\t\n1218\t2020-11-07 00:20:26\t1\t1\t\t0\t0\t\n1160\t2020-11-07 00:20:57\t1\t1\t\t0\t0\t\n2123\t2020-11-07 00:21:02\t1\t1\t\t0\t0\t\n7574\t2020-11-07 01:17:41\t1\t1\t\t0\t0\t\n2269\t2020-11-07 01:18:30\t1\t1\t\t0\t0\t\n2114\t2020-11-07 01:18:39\t1\t1\t\t0\t0\t\n1571\t2020-11-07 01:18:44\t1\t1\t\t0\t0\t\n1160\t2020-11-07 01:57:59\t1\t1\t\t0\t0\t\n1218\t2020-11-07 01:58:11\t1\t1\t\t0\t0\t\n1558\t2020-11-07 01:58:16\t1\t1\t\t0\t0\t\n2123\t2020-11-07 01:58:21\t1\t1\t\t0\t0\t\n1500\t2020-11-07 01:58:27\t1\t1\t\t0\t0\t\n1108\t2020-11-07 02:09:32\t1\t1\t\t0\t0\t\n7576\t2020-11-07 02:20:25\t1\t1\t\t0\t0\t\n7575\t2020-11-07 02:20:30\t1\t1\t\t0\t0\t\n1598\t2020-11-07 05:00:39\t0\t1\t\t0\t0\t\n1462\t2020-11-07 05:00:46\t0\t1\t\t0\t0\t\n2122\t2020-11-07 05:15:08\t0\t1\t\t0\t0\t\n1462\t2020-11-07 05:15:12\t0\t1\t\t0\t0\t\n7574\t2020-11-07 05:15:21\t0\t1\t\t0\t0\t\n2269\t2020-11-07 05:41:06\t0\t1\t\t0\t0\t\n2114\t2020-11-07 05:41:13\t0\t1\t\t0\t0\t\n1571\t2020-11-07 05:41:20\t0\t1\t\t0\t0\t\n1108\t2020-11-07 05:42:30\t0\t1\t\t0\t0\t\n1462\t2020-11-07 05:50:31\t0\t1\t\t0\t0\t\n1598\t2020-11-07 05:50:38\t0\t1\t\t0\t0\t\n1091\t2020-11-07 05:50:55\t0\t1\t\t0\t0\t\n1108\t2020-11-07 05:51:02\t0\t1\t\t0\t0\t\n2122\t2020-11-07 05:51:09\t0\t1\t\t0\t0\t\n1150\t2020-11-07 05:51:15\t0\t1\t\t0\t0\t\n1326\t2020-11-07 06:02:27\t0\t1\t\t0\t0\t\n2440\t2020-11-07 06:02:35\t0\t1\t\t0\t0\t\n3266\t2020-11-07 06:38:25\t0\t1\t\t0\t0\t\n1091\t2020-11-07 06:38:32\t0\t1\t\t0\t0\t\n1558\t2020-11-07 06:38:40\t0\t1\t\t0\t0\t\n7576\t2020-11-07 06:39:07\t0\t1\t\t0\t0\t\n7575\t2020-11-07 06:40:53\t0\t1\t\t0\t0\t\n2441\t2020-11-07 06:44:41\t0\t1\t\t0\t0\t\n2117\t2020-11-07 06:44:49\t0\t1\t\t0\t0\t\n1724\t2020-11-07 06:45:26\t0\t1\t\t0\t0\t\n1664\t2020-11-07 06:45:33\t0\t1\t\t0\t0\t\n";

        $arr = preg_split('/\\r\\n|\\r|,|\\n/', $data);//user id       //time      //status    //VERIFY          //WORKCODE       //RESERVED1
        $clean_data=[];
        foreach ($arr as $a) {
            $totalwords = strlen($a);
            if ($totalwords > 10) {    //if all the required data is inside the received format
                $d = explode("\t", $a);
                $empnum = $d[0];
                $time = $d[1];
                $status_id = $d[2];
                $verify_id = $d[3];
                $clean_data[] = ['emp_num' => $empnum, 'time' => $time, 'status_id' => $status_id, 'verify_id' => $verify_id,'serial'=>$serial];
            }
        }
        ProcessDeviceData::dispatch($clean_data);

        //ProcessDeviceData::dispatch($data,$serial);
        return $this->returnOk();
        return 'success';



        return 'done';
    }
    public function approval($id,Request $request)
    {
        $manual = ManualAttendance::find($id);
        if (Auth::id() == $manual->ssm_id || Auth::id()=='1') {
            if ($request->status == 1) {
                //approve
                ManualAttendance::where('id',$id)->update(['status'=>'approved']);
                if ($manual->time_in){
                    //0 = clockin
                    $data = ['emp_num' => $manual->user->emp_num, 'time' => $manual->time_in, 'status_id' => 0, 'verify_id' => 1,'serial'=>00];
                    $this->saveAttendance($data);
                }
                if ($manual->time_out){
                    //1 = clockout
                    $data = ['emp_num' => $manual->user->emp_num, 'time' => $manual->time_out, 'status_id' => 1, 'verify_id' => 1,'serial'=>00];
                    $this->saveAttendance($data);
                }
                return 'approved';

            } else {
                ManualAttendance::where('id',$id)->update(['status'=>'declined']);
                return 'declined';
            }
        }
        return 'error';
    }

    public function fetchusers(){
        //fetch enyo users
        //Artisan::call('fetch:users');
        FetchUsersJob::dispatch();
        return Redirect::back();
    }

    public function enrollUsers(){
        $company_id = companyId();
        //$users=User::where('company_id',$company_id)->get();
        $users=User::where('company_id',$company_id)->where('status','1')->get();
        $this->createMultipleUsers($users);
        return Redirect::back();
    }
    public function removeUsers(){
        $company_id = companyId();
        $users=User::where('company_id',$company_id)->get();
        $this->deleteMultipleUsers($users);
        return Redirect::back();
    }

    public function checkDevice(Request $request)
    {
        $this->savetoTable($request);
        $last = Biometric::orderBy('id', 'ASC')->first();
        if ($last) {
            $time = $last->created_at->timestamp;
        } else {
            $time = now()->timestamp;
        }
        //$contents="C:18:DATA USER PIN=22\tName=Soladoye\tPasswd=1234\tCard=123456\tPri=0";
        $log= CommandLog::where('biometric_serial',$request->SN)->orderBy('id','desc')->first();
        if ($log){
            $command=$log->command;
        }
        $command = "GET OPTION FROM:%s{$request->SN}\nStamp=1565089939\nOpStamp=1565089939\nErrorDelay=30\nDelay=10\nTransTimes=00:00;14:05\nTransInterval=1\nTransFlag=1111000000\nTimeZone=1\nRealtime=1\nEncrypt=0\n";
        return $this->commandresponse($command);
    }

    public function getRequest(Request $request)
    {
        //this is the second thing that gets called on device start up. You send OK if it you dont have any command to send
        //$this->savetoTable($request);
        $serial_number=$request->SN;//5204191960072
        return $this->commandToSend($serial_number);
    }

    //once a successful command request is made, the device makes a call to deviceCMD
    public function deviceCMD(Request $request)
    {
        $data=$request->getContent();
        $arr = preg_split('/\\r\\n|\\r|,|\\n/', $data);
        $return_data=[];
        foreach ($arr as $a) {
            $totalwords = strlen($a);
            if ($totalwords > 10) {    //if all the required data is inside the received format
                $d = explode("&", $a);
                $id = $d[0];
                $status=$d[1];
                $id_length=strlen($id);
                $position=strpos($id,'ID=');
                $end=$id_length-$position;
                $real_id=substr($id, $position+3, $end);

                $status_length=strlen($status);
                $position2=strpos($status,'Return=');
                $end2=$status_length-$position2;
                $real_status=substr($status, $position2+7, $end2);
                $return_data[] = ['id' => $real_id, 'status' => $real_status];
            }
        }
        $this->updateLogOnReturn($return_data);
        $this->savetoTable($request);
        return $this->returnOk();
    }
    public function receiveRecords(Request $request)
    {
        ini_set('max_execution_time', '0');
        if (isset($request->table)) {
            $table = $request->table;
        } else {
            $this->doNothing();
        }
        switch ($table) {
            case 'ATTLOG':
                $this->savetoTable($request);
                $this->logAttendance($request);

                return $this->returnOk();
                break;
            case 'ATTPHOTO':
                //receiveOnSitePhoto($request);
                break;
            case 'OPERLOG':
                $this->savetoTable($request);
                //ureceiveUserInfo($request);
                break;
            default:
                $this->doNothing();
                break;
        }
        return $this->returnOk();
    }

    private function logAttendance(Request $request)
    {
        $serial=$request->SN;
        $data = $request->getContent();
        //$this->logAttendanceProcess($data,$serial);
        $arr = preg_split('/\\r\\n|\\r|,|\\n/', $data);//user id       //time      //status    //VERIFY          //WORKCODE       //RESERVED1
        $clean_data=[];
        foreach ($arr as $a) {
            $totalwords = strlen($a);
            if ($totalwords > 10) {    //if all the required data is inside the received format
                $d = explode("\t", $a);
                $empnum = $d[0];
                $time = $d[1];
                $status_id = $d[2];
                $verify_id = $d[3];
                if($verify_id!=0){
                    $clean_data[] = ['emp_num' => $empnum, 'time' => $time, 'status_id' => $status_id, 'verify_id' => $verify_id,'serial'=>$serial];    
                }
                
            }
        }
        ProcessDeviceData::dispatch($clean_data);
    }

    private function doNothing()
    {

    }


    private function savetoTable(Request $request)
    {
        $new = new Biometric();
        $new->headers = $request->header();
        $new->url = $request->getMethod() . '- ' . $request->fullUrl();
        $new->data = $request->getContent();
        $new->biometric_serial = $request->SN;
        $new->save();
    }



}
