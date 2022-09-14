<?php
namespace App\Http\Controllers;

use App\AttendancePolicy;
use App\Setting;
use App\Workflow;
use Illuminate\Http\Request;
use App\WorkingPeriod;
use App\Company;
use App\Position;
class AttendanceSettingController extends Controller
{

	public function index($type='')
	{
		$companies=Company::all();
		foreach ($companies as $company) {
			if($company->workingperiod){

			}else{
				$workingperiod=new WorkingPeriod();
				$workingperiod->sob='08:00';
				$workingperiod->cob='17:00';
				$workingperiod->company_id=$company->id;
				$workingperiod->save();
			}
		}
		$workingperiods=WorkingPeriod::all();
        $workflows=Workflow::all();
        $ap=AttendancePolicy::first();
        $before_shift_time=Setting::where('name','before_shift_time')->first();
        $grace_period=Setting::where('name','grace_period')->first();
		return view('settings.attendancesettings.index',compact('companies','workingperiods','workflows','ap','before_shift_time','grace_period'));
	}

	public function saveAttendanceSettings(Request $request){
        $before_shift_time = ($request->before_shift_time==1) ? 1 : 0 ;
        Setting::where('name','before_shift_time')->update(['value'=>$before_shift_time]);
        Setting::where('name','grace_period')->update(['value'=>$request->grace_period]);
        return  response()->json('success',200);
    }
	public function listWorkingPeriod()
	{
		
	}
    public function saveExemptionWorkflow(Request $request)
    {
        $data=AttendancePolicy::find(1);
        $data->workflow_id=$request->workflow_id;
        $data->save();
        return  response()->json('success',200);
    }
	public function saveWorkingPeriod(Request $request)
	{
		WorkingPeriod::updateorCreate(['id'=>$request->working_period_id],['sob'=>$request->sob,'cob'=>$request->cob]);
		return  response()->json('success',200);
	}
	public function getWorkingPeriod($workingperiod_id)
	{
		$workingperiod=WorkingPeriod::find($workingperiod_id);
		return  response()->json($workingperiod,200);
	}
	public function deleteWorkingPeriod($workingperiod_id)
	{
		$workingperiod=WorkingPeriod::find($workingperiod_id);
		if ($workingperiod) {
			$workingperiod->delete();
		}else{
			return  response()->json(['failed'],200);
		}
		return  response()->json(['success'],200);
	}
	
	
	public function listProject()
	{
		$projects=Project::all();
		return view('settings.attendancesettings.position',compact('positions'));
	}

	public function saveProject(Request $request)
	{
		Project::updateOrCreate(['id'=>$request->project_id],['name'=>$request->name,'lga'=>$request->lga,'state'=>$request->state,'country'=>$request->country]);
		return  response()->json('success',200);
	}
	public function getProject($project_id)
	{
		$project=Project::find($project_id);
		return  response()->json($project,200);
	}
	public function deleteProject($project_id)
	{
		$project=Project::find($project_id);
		if ($project) {
			$project->delete();
		}else{
			return  response()->json(['failed'],200);
		}
		return  response()->json(['success'],200);
	}
	
	public function listEmployeeType()
	{
		$employeetypes=EmployeeType::all();
		return view('settings.attendancesettings.employeetype',compact('employeetypes'));
	}
	public function saveEmployeeType(Request $request)
	{
		EmployeeType::updateOrCreate(['id'=>$request->employeetype_id],['type'=>$request->type]);
		return  response()->json('success',200);
	}
	public function getEmployeeType($employeetype_id)
	{
		$employeetype=EmployeeType::find($employeetype_id);
		return  response()->json($employeetype,200);
	}
	public function deleteEmployeeType($employeetype_id)
	{
		$employeetype=EmployeeType::find($employeetype_id);
		if ($employeetype) {
			$employeetype->delete();
		}else{
			return  response()->json(['failed'],200);
		}
		return  response()->json(['success'],200);
	}
	public function listCostCenter()
	{
		$costcenters=CostCenter::all();
		return view('settings.attendancesettings.costcenter',compact('costcenters'));
	}
	public function saveCostCenter(Request $request)
	{
		CostCenter::updateOrCreate(['id'=>$request->costcenter_id],['code'=>$request->code]);
		return  response()->json('success',200);
	}
	public function getCostCenter($costcenter_id)
	{
		$costcenter=CostCenter::find($costcenter_id);
		return  response()->json($costcenter,200);
	}
	public function deleteCostCenter($costcenter_id)
	{
		$costcenter=CostCenter::find($costcenter_id);
		if ($costcenter) {
			$costcenter->delete();
		}else{
			return  response()->json(['failed'],200);
		}
		return  response()->json(['success'],200);
	}
	public function listAllowance()
	{
		$allowances=Allowance::all();
		return view('settings.attendancesettings.allowance',compact('allowances'));
	}
	public function saveAllowance(Request $request)
	{
		Allowance::updateOrCreate(['id'=>$request->allowance_id],['name'=>$request->name,'location_id'=>$request->location_id]);
		return  response()->json('success',200);
	}
	public function getAllowance($allowance_id)
	{
		$allowance=Allowance::find($allowance_id);
		return  response()->json($allowance,200);
	}
	public function deleteAllowance($allowance_id)
	{
		$allowance=Allowance::find($allowance_id);
		if ($allowance) {
			$allowance->delete();
		}else{
			return  response()->json(['failed'],200);
		}
		return  response()->json(['success'],200);
	}
	public function listshift()
	{
		$shifts=Shift::all();
		return view('settings.attendancesettings.shift',compact('shifts'));
	}
	public function saveShift(Request $request)
	{
		Shift::updateOrCreate(['id'=>$request->shift_id],['type'=>$request->type,'start_time'=>$request->start_time,'end_time'=>$request->end_time]);
		return  response()->json('success',200);
	}
	public function getShift($shift_id)
	{
		$shift=Shift::find($shift_id);
		return  response()->json($shift,200);
	}
	public function deleteShift($shift_id)
	{
		$shift=Shift::find($shift_id);
		if ($shift) {
			$shift->delete();
		}else{
			return  response()->json(['failed'],200);
		}
		return  response()->json(['success'],200);
	}
	
	
	

}