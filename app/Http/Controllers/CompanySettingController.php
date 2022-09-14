<?php
namespace App\Http\Controllers;

use App\Region;
use App\State;
use Illuminate\Http\Request;
use App\Company;
use App\Department;
use App\Branch;
use App\Job;
use App\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class CompanySettingController extends Controller
{

    //company functions
    public function companies()
    {
        $companies = Company::get();
        // return $branches = Branch::withCount('stations')->get();
        $branches = Branch::withCount('stations')->get();
        $states = State::withCount('stations')->where('country_id','160')->get();
        $regions = Region::withCount('branches')->get();
        $users = User::all();
        return view('settings.companysettings.company', compact('companies','users','branches','states','regions'));
    }

    /*
    public function apicompanies()
    {
        $ids = ['5','64','65'];
        // return Company::where('id', $ids)->get();
        // return Company::whereNotIn('id',$ids)->where('biometric_serial','!=', null)->get();
        
        $companies = Company::whereNotIn('id',$ids)->where(function($query)
        {
            $query->where('biometric_serial','>', 0)->orWhere('biometric_serial','!=', "");
        } )->get();

        //get all list of company shifts
        $all_companies = [];
        foreach ($companies as $key => $company) 
        {
            $shift = \App\Shift::where('company_id', $company->id)->orderBy('created_at', 'desc')->first();
            $comp = $company->toArray();
            if($shift != '')
            {
                $shift = $shift->toArray();
            }
            else{ $shift = array(['type' => null, 'start_time' => null, 'end_time' => null, 'company_id' => $company->id, 'shift_type_id' => null]); }

            $biometric = \App\Biometric::where('biometric_serial', $company->biometric_serial)->orderBy('created_at', 'desc')->first();
            if($biometric != '')
            {
                $biometric = $biometric->toArray();
            }
            else{ $biometric = array(['id' => null, 'url' => null, 'headers' => null, 'data' => null, 'biometric_serial' => $company->biometric_serial, 'created_at' => null, 'updated_at' => null]); }
            
            $all_companies[$key] = array_merge($comp, $shift, $biometric);            
        }

        $data = collect(['stations' => $all_companies]);   return $data; 
    }
    */

    public function apicompanies(){
        $ids=['5','64','65'];
        return Company::whereNotIn('id',$ids)->where('biometric_serial','!=',null)->get();
    }

    public function saveCompany(Request $request)
    {
        /* Validator::make($request->all(), [
             'biometric' => 'required|unique:companies,biometric_serial',
         ])->validate();*/
        $company = Company::updateOrCreate(['id' => $request->company_id],
            ['name' => $request->name, 'email' => $request->email, 'address' => $request->address,
                'user_id' => $request->user_id, 'branch_id' => $request->branch_id, 'state_id' => $request->state_id,
                'biometric_serial'=>$request->biometric,'status'=>$request->status,'pay_full_days'=>$request->pay_full_days ? $request->pay_full_days : 25]);
        return response()->json(['success'], 200);
    }

    public function changeParentCompany($company_id = '')
    {
        $companies = Company::all();
        foreach ($companies as $company) {
            if ($company->id == $company_id) {
                $company->is_parent = 1;
                $company->save();
            } else {
                $company->is_parent = 0;
                $company->save();
            }

        }
        return 'success';

    }

    public function getCompany($company_id)
    {
        $company = Company::find($company_id);
        return response()->json($company, 200);

    }
    //end company functions
    //department functions
    public function departments($company_id)
    {

        $company = Company::find($company_id);
        $departments = $company->departments;
        $users = $company->users;
        return view('settings.companysettings.department', compact('departments', 'company', 'users'));
    }

    public function saveDepartment(Request $request)
    {
        Department::updateOrCreate(['id' => $request->department_id], ['name' => $request->name, 'manager_id' => $request->manager_id, 'company_id' => $request->company_id]);

        return response()->json(['success'], 200);
    }

    public function getDepartment($department_id)
    {
        $department = Department::find($department_id);
        return response()->json($department, 200);
    }

    public function deleteDepartment($department_id)
    {
        $department = Department::find($department_id);
        // return $department->users;
        if ($department->jobs->count() > 0) {
            return 'Department has users and cannot be deleted';
        }
        $department->delete();
        return response()->json(['success'], 200);
    }
    //end department functions
    //branch functions
    public function branches($company_id)
    {
        $branches = Branch::all();
        $company = Company::find($company_id);
        $users = User::all();
        return view('settings.companysettings.branch', compact('branches', 'company', 'users'));
    }

    public function saveBranch(Request $request)
    {
        Branch::updateOrCreate(['id' => $request->branch_id], ['name' => $request->name, 'email' => $request->email, 'address' => $request->address, 'company_id' => $request->company_id,
            'manager_id' => $request->manager_id,'region_id' => $request->region_id]);
        return response()->json(['success'], 200);
    }

    public function saveRegion(Request $request)
    {
        Region::updateOrCreate(['id' => $request->region_id], ['name' => $request->name,'area_manager_id' => $request->area_manager_id,'regional_lead_id' => $request->regional_lead_id,]);
        return response()->json(['success'], 200);
    }
    public function getRegion($region_id)
    {
        $branch = Region::find($region_id);
        return response()->json($branch, 200);
    }

    public function saveState(Request $request)
    {
        State::updateOrCreate(['id' => $request->state_id], ['name' => $request->name,'rep_id' => $request->rep_id]);
        return response()->json(['success'], 200);
    }
    public function getState($state_id)
    {
        $state = State::find($state_id);
        return response()->json($state, 200);
    }

    public function getBranch($branch_id)
    {
        $branch = Branch::find($branch_id);
        return response()->json($branch, 200);
    }

    public function deleteBranch($branch_id)
    {
        $branch = Branch::find($branch_id);
        if ($branch->has('users')) {
            return 'Branch has users and cannot be deleted';
        }
        $branch->delete();
        return response()->json(['success'], 200);
    }
    //end branch functions
    //job functions
    public function jobs($department_id)
    {
        $jobs = Job::all();
        return view('settings.companysettings.job', ['jobs' => $jobs]);
    }

    public function saveJob(Request $request)
    {
        Job::updateOrCreate(['id' => $request->job_id], [$request->all()]);
        return response()->json(['success'], 200);
    }

    public function getJob($job_id)
    {
        $job = Job::find($job_id);
        return response()->json([$job], 200);
    }
    //end job functions
    
    



    // API
    // function enyo_companies()
    // {
    //     $companies = \App\Company::orderBy('id', 'desc')->limit('50')->get();
    //     $data = collect(['companies' => $companies]);   return $data; 
    // }

    function enyo_companies()
    {
        $companies = \App\Company::orderBy('id', 'desc')->limit('50')->get()->makeHidden(['last_shift','last_seen' ]);
        $data = collect(['companies' => $companies]);   
        return $data; 
    }



    function enyo_branches()
    {
        $branches = \App\Branch::orderBy('name', 'asc')->get();
        $data = collect(['branches' => $branches]);   return $data; 
    }


}
