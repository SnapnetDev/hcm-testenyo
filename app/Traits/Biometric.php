<?php

namespace App\Traits;

use App\AttendanceDetail;
use App\CommandLog;
use App\Company;
use App\Jobs\ProcessSingleAttendanceJob;
use App\User;
use App\UserDailyShift;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use GuzzleHttp\Client;
use App\Branch;


trait Biometric
{
    public function commandToSend($device_serial)
    {
        $logs = CommandLog::where('status', 'pending')->where('biometric_serial', $device_serial)->limit('3')->get();
        if (count($logs)>0) {
            $commands='';
            foreach ($logs as $log){
                $command = $log->command.'\n';
                $commands=$commands.$command;
            }
            return $this->commandresponse($commands);
        }
        else{
            return $this->returnOk();
        }
    }

    public function deleteUser($user_id)
    {
        $user = User::findorFail($user_id);
        $contents = "DATA DELETE USERINFO PIN=$user->emp_num\tName=$user->name\tPri=0";
        $this->logCommand($contents,$user->company_id,$user->company->biometric_serial);
    }

    public function createUser($user_id)
    {
        $user = User::findorFail($user_id);



        if($user->company_id!='19'){
            //put the above inside here


            if ($user->role_id=='2'){
                //station manager
                $contents = "DATA UPDATE USERINFO PIN=$user->emp_num\tName=$user->name\tPri=14\tPasswd=3696\t";
            }
            else{
                $contents = "DATA UPDATE USERINFO PIN=$user->emp_num\tName=$user->name\tPri=0\tPasswd=1117\t";
            }

        }
        else{
            $contents = "DATA UPDATE USERINFO PIN=$user->emp_num\tName=$user->name\tPri=0";
        }


        $this->logCommand($contents,$user->company_id,$user->company->biometric_serial);

    }

    public function updateLogOnReturn($array){
        foreach ($array as $item){
            CommandLog::where('id',$item['id'])->update(['status'=>$item['status']]);
        }
    }

    public function logCommand($command,$company_id,$biometric_serial){
        $log=new CommandLog();
        $log->command='XX';
        $log->status='queued';
        $log->company_id=$company_id;
        $log->biometric_serial=$biometric_serial;
        $log->save();
        $contents = "C:$log->id:".$command;
        CommandLog::where('id',$log->id)->update(['status'=>'pending','command'=>$contents]);
    }

    public function createMultipleUsers($users)
    {
        foreach ($users as $user) {
            $this->createUser($user->id);
        }
    }


    public function deleteMultipleUsers($users)
    {
        foreach ($users as $user) {
            $this->deleteUser($user->id);
        }
    }

    private function returnOk()
    {
        $contents = 'OK';
        return $this->commandresponse($contents);
    }
    public function commandresponse($command){
        /*   $now=Carbon::now();*/
        $statusCode = 200;
        $response = Response::make($command, $statusCode);
        $response->header('Content-Type', 'text/plain');
        /*$response->header('Date', 'text/plain')*/;

        //Date: Tue, 30 Jun 2015 01:24 26 GMT
        return $response;
    }

    public function fetchEnyoUsers(){

         $client = new Client();
        $response=$client->post(
            'https://api.officelime.com:9099/v1/token',
            array(
                'headers' => ['client_id' => 'F58B3E9F-ADA7-4DB0-BE35642622868F78'],
                'form_params' => [
                    'Email' => 'tobe@snapnet.com.ng',
                    'Key' => 'MAdv4k3AN2WgR5ajxVzguOyTh/SStIuA6euQE0sVexryG0l9r8wJa'
                ],'verify' =>false,
                
            )
        );


       $auth_response= json_decode($response->getBody());
       $token=$auth_response->token;
    //   \Log::info($token);


        $client = new Client(['base_uri' => 'https://api.officelime.com:9099/v1/','verify' =>false]);
        $headers = [
            'Authorization' => 'Bearer ' . $token,
            'Accept'        => 'application/json',
        ];
        for ($i=1;$i<=2;$i++){
        $location_res = $client->request('GET', 'locations?page='.$i.'&limit=100', [
            'headers' => $headers
        ]);


            // \Log::info('page '.$i);
            // \Log::info('');
        $locations=json_decode($location_res->getBody());
//        print_r($locations->data);
        foreach ($locations->data as $location){
            //   \Log::info($location->name);
            $branch=Branch::firstOrCreate(['name'=>$location->branch],
                [   'company_id'=>'5',
                    'email'=>$location->branch,
                    'address'=>$location->branch,
                    'manager_id'=>'2',
                    'region_id'=>'1',
                ]);
           $company= Company::updateOrCreate(['name'=>$location->name],
                ['email'=>$location->name,
                    'address'=>$location->address,
                    'name'=>$location->name,
                    'user_id'=>'1',
                    'branch_id'=>$branch->id,
                    'idms_id'=>$location->id
                    //'state_id'=>'2671',
                ]);


            $emp_res = $client->request('GET', 'employee/location/'.$location->id.'?limit=100', [
                'headers' => $headers
            ]);
            $users=json_decode($emp_res->getBody());
            foreach ($users->data as $user){
                //   \Log::info('id= '.$user->id);
                //   \Log::info('user_id= '.$user->user->id);
                //   \Log::info($user->user->surname.' '.$user->user->first_name);
                //   \Log::info('job title= '.$user->job_title->name);
                // \Log::info('ref id= '.$user->ref_id);
                //   \Log::info('');
                if($user->ref_id==''){
                    $ref=$user->id;
                }else{
                    $ref=$user->ref_id;
                }
                $use= User::updateOrCreate(['emp_num'=>$ref],
                    ['name'=>$user->user->surname.' '.$user->user->first_name,
                        'company_id'=>$company->id,
                        'email'=>$user->user->email,
                        'address'=>$user->address,
                        'role_id'=>$this->findrole($user->job_title->name),
                        'staff_category_id'=>'0',
                        'job_id'=>'1',
                        'status'=>'1',
                        'branch_id'=>'1',
                    ]);
//
                if(isset($location->biometric_serial)){
                    $this->createUser($use->id);
                }

            }
//            print_r($users->data);

        }
        }
    }

    private function findrole($role){
        if ($role=='Station Manager'){
            return '2';
        }
        elseif ($role=='Assessment Day - Station Manager'){
            return '2';
        }

        elseif ($role=='Manager'){
            return '2';
        }
        elseif ($role=='Customer Attendant'){
            return '5';
        }
        elseif(strpos($role, 'Customer Attendant') !== false){
            return '5';
        }
        elseif ($role=='Supervisor'){
            return '4';
        }
        elseif ($role=='Forecourt Supervisor - Lagos'){
            return '4';
        }
        elseif ($role=='Cashier'){
            return '3';
        }
        elseif ($role=='Assessment Day- Cashier'){
            return '3';
        }
        elseif ($role=='Territory Lead - West'){
            return '6';
        }
        elseif ($role=='Territory Lead - North'){
            return '6';
        }
        elseif ($role=='Senior Station Manager'){
            return '7';
        }
        elseif ($role=='HQ Area Manager - North'){
            return '7';
        }
        elseif ($role=='HQ Area Manager - West'){
            return '7';
        }
        elseif ($role=='Vehicon Admin'){
            return '8';
        }
        elseif ($role=='Service Advisors'){
            return '9';
        }
        elseif ($role=='Vehicon Apprentice'){
            return '10';
        }
        elseif ($role=='Vehicon Operator'){
            return '11';
        }
        elseif ($role=='Vehicon Technician'){
            return '12';
        }
        elseif ($role=='Vehicon Assistant'){
            return '16';
        }
        elseif ($role=='Vehicon Asst. Operator'){
            return '17';
        }
        elseif ($role=='Security Guard'){
            return '18';
        }
        else{
            return '1';
        }
    }

    public function processCleanAttendanceRecords($clean_data){
        foreach ($clean_data as $clean){
            $this->saveAttendance($clean);
        }
    }

    public function saveAttendance($data)
    {
        $overflow='0';
        //$station=Company::where('biometric_serial',$data['serial'])->first();
        $save_date=date('Y-m-d', strtotime($data['time']));
        $user = User::where(['emp_num' => $data['emp_num']])->first();
        //$user = User::where(['emp_num' => $data['emp_num']])->where('company_id',$station->id)->first();
        if ($user) {
            if ($data['status_id'] == 1) {//if clockout check the ends time of a shift
                $has_end=UserDailyShift::where('user_id', $user->id)->where('ends', date('Y-m-d', strtotime($data['time'])))->first();
                if ($has_end){  //if there is a shift that ends today,
                    $save_date= $has_end->sdate;
                    $shift = $has_end->id;
                }
                else{   //no shift ends today, check shift for today
                    $user_shift = UserDailyShift::where('user_id', $user->id)->where('sdate', date('Y-m-d', strtotime($data['time'])))->first();
                    if ($user_shift) {
                        $shift = $user_shift->id;
                    } else {
                        //there is no shift for today, but we need to push this clockout to a clockin
                        //check if there is a clockin in the previous date that doesnt have a clockout
                        //if the clockin in the previous date is less than 24 hours from the $data['time'], save_date=$previous_date
                        $shift = 0;
                        $yesterday=date('Y-m-d', strtotime($data['time']. '-1 day'));
                        $yesterdat_att=\App\Attendance::where('date',$yesterday)->where('emp_num',$data['emp_num'])->first();

                        if($yesterdat_att){
                            $det = $yesterdat_att->attendancedetails()->latest()->first();
                            if ($det) {  //a clockin exists
                                if ($det->clock_out == '') {//there is a null clock_out
                                    $out_time= date('H:i:s', strtotime($data['time']));
                                    if ($det->clock_in>$out_time){//check if the clockin yesterday is greater than the clock out of now
                                        //it means the clockout made today is actually for yesterday attendance
                                        $save_date=$yesterday;
                                    }
                                }
                                //nothing will happen, the previous date has clockout
                            }
                        }

                        else{
                            $user_shift = UserDailyShift::where('user_id', $user->id)->where('sdate', date('Y-m-d', strtotime($data['time'])))->first();
                            if ($user_shift) {
                                $shift = $user_shift->id;
                            } else {
                                $shift = 0;
                            }
                        }
                    }
                }
            }
            else{
                $user_shift = UserDailyShift::where('user_id', $user->id)->where('sdate', date('Y-m-d', strtotime($data['time'])))->first();
                if ($user_shift) {
                    $shift = $user_shift->id;
                } else {
                    $shift = 0;
                }

            }
            $attendance = \App\Attendance::updateOrCreate(['date' => $save_date, 'shift_id' => '1',//shift is not meant to be here
                'emp_num' => $data['emp_num']],
                ['user_daily_shift_id' => $shift]);




            if ($data['status_id'] == 0 || $data['status_id'] > 1) {//clock in
                //if (isset($attendance->attendancedetails()->latest()->first()->clock_out)){//if there is a previous clockout
                if (AttendanceDetail::where('attendance_id',$attendance->id)->count()>0){//if there is a previous clockout

                    if ($attendance->attendancedetails()->latest()->first()->clock_out == '') {//clockin twice, previous clockin will become previous clockout time
                        if (date('H:i:s', strtotime($data['time']))!=$attendance->attendancedetails()->latest()->first()->clock_in){
                            //update clockout as clockin if the previous clock in time is not the same as the clock in coming now
                            $attendance->attendancedetails()->latest()->first()
                                ->update(['clock_out' => $attendance->attendancedetails()->latest()->first()->clock_in]);
                        }
                    }

                }
                //fresh clockin
                AttendanceDetail::updateOrCreate(['attendance_id' => $attendance->id, 'clock_in' => date('H:i:s', strtotime($data['time']))]);
            } elseif ($data['status_id'] == 1) {//clockout
                $ad = $attendance->attendancedetails()->latest()->first();
                if ($ad) {  //a clockin exists
                    if ($ad->clock_out == '') {    //a clockin exists but no clockout, create a clockout
                        $ad->update(['clock_out' => date('H:i:s', strtotime($data['time']))]);
                    } else {  //a clockout is made without a clockin. create a clockout and clockin as clockout time
                        AttendanceDetail::updateOrCreate(['attendance_id' => $attendance->id, 'clock_in' => date('H:i:s', strtotime($data['time'])),
                            'clock_out' => date('H:i:s', strtotime($data['time']))]);
                    }
                } else {   //no clockin but there is clock out, clockin and clock out is thesame time
                    AttendanceDetail::create(['attendance_id' => $attendance->id, 'clock_in' => date('H:i:s', strtotime($data['time'])),
                        'clock_out' => date('H:i:s', strtotime($data['time']))]);
                }
            }
            //after adding, call a job to update attendanceReport

            ProcessSingleAttendanceJob::dispatch($attendance->id);
            //return "success";
        }
    }

}