<?php

namespace App\Http\Controllers;

use App\Branch;
use App\Company;
use App\Mail\SendMail;
use App\ManualAttendance;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class ManualAttendanceController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function manualAttendance(Request $request){
        $date=Carbon::today();
        if ($request->filled('date')){
            $date=Carbon::parse($request->date);
        }
        $users=User::where('company_id',companyId())->whereIn('status',[0,1])->get();
        $manuals=ManualAttendance::where('date',$date->format('Y-m-d'))->where('company_id',companyId())->get();
        return view('attendance.manual.manual_daily_attendance',compact('date','manuals','users'));
    }

    public function storeManualAttendance(Request $request){
        //return $request->all();
        $sm=User::find(Auth::id());
        $user_id=$request->user_id;
        $time_in=$request->time_in;
        $time_out=$request->time_out;
        $company_id=companyId();
        $company=Company::find($company_id);
        $branch=Branch::find($company->branch_id);
        $ssm=$branch->manager_id;
        $date=$request->date;
        $reason=$request->reason;

        ManualAttendance::updateorcreate(['user_id'=>$user_id,'date'=>$date,'status'=>'pending'],
            ['company_id'=>$company->id,'manager_id'=>$sm->id,'ssm_id'=>$ssm,'time_in'=>$date.' '.$time_in,'time_out'=>$date.' '.$time_out,'reason'=>$reason]);

        $from='info@snapnet.com.ng';
        $view_template='emails.emaildailyshift';
        $subject=$company->name.' Manual Attendance Created';
        $message='Kindly login and approve manual attedance for '.$company->name;
        $data=['mail'=>$message];
        Mail::to($branch->email)->cc('timothy@snapnet.com.ng')->send(new SendMail($from,$subject,$data,$view_template));
        
        return 'done';
    }
    public function manualAttendanceExcelTemplate(Request $request){
        $first_row = [
            ['EmpNum'=>'','date'=>$request->date, 'time_in'=>'','time_out'=>'','reason'=>'']
        ];
        $users = User::where('company_id',companyId())->where('status','1')->select('name','emp_num')->get();
        return $this->exportToExcelDropDown('Manual Attendance Template',
            ['Attendance' => [$first_row, ''],'users' => [$users, 'A', 'users']]
        );
    }

    public function manualAttendanceExcel(Request $request){
       // return $request->all();
        $sm=User::find(Auth::id());
        $company_id=companyId();
        $company=Company::find($company_id);
        $branch=Branch::find($company->branch_id);
        $ssm=$branch->manager_id;
        if ($request->hasFile('template')) {
            try {
                $rows = \Excel::load($request->template)->get();
                if ($rows) {
                    $rows=$rows[0];
                    //return $rows;
                    foreach ($rows as $key => $row) {
                        $user = User::where('emp_num', $row['empnum'])->where('company_id',$company_id)->first();
                        if ($user) {
                            if (isset($row['time_in'])&&isset($row['time_out'])&&isset($row['reason'])&&isset($row['date'])){
                                $date=Carbon::parse($row['date'])->format('Y-m-d');
                                $time_in=$date.' '.Carbon::parse($row['time_in'])->format('H:i:s');
                                $time_out=$date.' '.Carbon::parse($row['time_out'])->format('H:i:s');
                                ManualAttendance::updateorcreate(['user_id'=>$user->id,'date'=>$date,'status'=>'pending'],
                                    ['company_id'=>$company->id,'manager_id'=>$sm->id,'ssm_id'=>$ssm,'time_in'=>$time_in,'time_out'=>$time_out,'reason'=>$row['reason']]);

                            }
                        }
                    }
                    return ['status'=> 'success','details'=>'Successfully uploaded details'];
                }
            } catch (\Exception $ex) {
                return ['status'=> 'error','details'=>$ex->getMessage()];
            }
        }
    }


    private function exportToExcelDropDown($worksheetname, $data)
    {


        return \Excel::create($worksheetname, function ($excel) use ($data) {
            foreach ($data as $sheetname => $realdata) {
                $excel->sheet($sheetname, function ($sheet) use ($realdata, $sheetname, $data) {
                    $last = collect($data)->last();
                    $sheet->fromArray($realdata[0]);


                    if ($sheetname == $last[2]) {


                        $i = 1;
                        foreach ($data as $key => $data) {

                            $Cell = $data[1];
                            if ($data[1] != '') {


                                $sheet->_parent->addNamedRange(
                                    new \PHPExcel_NamedRange(
                                        "sd{$data[1]}", $sheet->_parent->getSheet($i), "B2:B" . $sheet->_parent->getSheet($i)->getHighestRow()
                                    )
                                );
                                $i++;
                                for ($j = 2; $j <= 500; $j++) {


                                    $objValidation = $sheet->_parent->getSheet(0)->getCell("{$data[1]}$j")->getDataValidation();
                                    $objValidation->setType(\PHPExcel_Cell_DataValidation::TYPE_LIST);
                                    $objValidation->setErrorStyle(\PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
                                    $objValidation->setAllowBlank(false);
                                    $objValidation->setShowInputMessage(true);
                                    $objValidation->setShowErrorMessage(true);
                                    $objValidation->setShowDropDown(true);
                                    $objValidation->setErrorTitle('Input error');
                                    $objValidation->setError('Value is not in list.');
                                    $objValidation->setPromptTitle('Pick from list');
                                    // $objValidation->setPrompt('Please pick a value from the drop-down list.');
                                    $objValidation->setFormula1("sd{$data[1]}");


                                }
                            }
                        }
                    }


                });
            }
        })->download('xlsx');
    }

}
