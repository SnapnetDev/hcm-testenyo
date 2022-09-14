<?php

namespace App\Http\Controllers;

use App\Branch;
use App\FacialVerifyRequest;
use App\Jobs\VerifyFacialJob;
use App\Mail\SendMail;
use App\StatusChangeRequest;
use App\Traits\Biometric;
use App\Traits\FaceMatchTrait;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Filters\UserFilter;
use App\Company;
use App\Job;
use App\Department;
use App\Location;
use App\StaffCategory;
use App\Position;
use Validator;
use App\Role;
use App\Qualification;
use App\UserGroup;
use App\Competency;
use App\Bank;
use App\Grade;


class UserController extends Controller
{

    use biometric;
    public function index(Request $request)
    {
        $auth=Auth::user();
        $company_id=companyId();
        if (count($request->all())==0) {
            if ($company_id>0) {
                if (Auth::user()->role->manages=='all') {
                    $users=User::where('company_id','=',$company_id)->paginate(10);
                }elseif(Auth::User()->role->manages=="dr"){
                    $users=Auth::User()->employees()->paginate(10);
                }


            }else{
                $users=User::paginate(10);
            }
            $ncompany=Company::find($company_id);
            $staff_categories=StaffCategory::all();
            $grades=Grade::all();
           $companies=Company::all();
            $branches=$companies->first()->branches;
            $departments=Department::all();
            $qualifications=Qualification::all();
            if (Auth::user()->role->manages=='all') {
                $usersforcount=User::where('company_id','=',$company_id)->get();
            }elseif(Auth::User()->role->manages=="dr"){
                $usersforcount=Auth::User()->employees()->get();
            }
            $roles=Role::all();
            $competencies=Competency::all();
            $user_groups=UserGroup::all();
            $managers=User::whereHas('role',function ($query)  {
                $query->where('manages','dr');
                $query->orWhere('manages','all');
            })->get();
            return view('empmgt.list',['users'=>$users,'usersforcount'=>$usersforcount,'companies'=>$companies,'branches'=>$branches,'departments'=>$departments,'roles'=>$roles,'user_groups'=>$user_groups,'managers'=>$managers,'qualifications'=>$qualifications,'competencies'=>$competencies,'ncompany'=>$ncompany,'grades'=>$grades,'staff_categories'=>$staff_categories]);

        }else{
            $users=UserFilter::apply($request);
            $companies=Company::all();
            $ncompany=Company::find($company_id);
            $staff_categories=StaffCategory::all();
            $grades=Grade::all();
            $branches=$companies->first()->branches;
            $departments=Department::all();
            if (Auth::user()->role->manages=='all') {
                $usersforcount=User::where('company_id','=',$company_id)->get();
            }elseif(Auth::User()->role->manages=="dr"){
                $usersforcount=Auth::User()->employees()->get();
            }
            $roles=Role::all();
            $competencies=Competency::all();
            $user_groups=UserGroup::all();

            $managers=User::whereHas('role',function ($query)  {
                $query->where('manages','dr');
                $query->orWhere('manages','all');
            })->get();

            if ($request->excel==true) {
                $view='empmgt.list-excel';
                // return view('compensation.d365payroll',compact('payroll','allowances','deductions','income_tax','salary','date','has_been_run'));
                return     \Excel::create("export", function($excel) use ($users,$view) {

                    $excel->sheet("export", function($sheet) use ($users,$view) {
                        $sheet->loadView("$view",compact('users'))
                            ->setOrientation('landscape');
                    });

                })->export('xlsx');
                # code...
            }
            if ($request->excelall==true) {
                $view='empmgt.list-excel';
                $users=User::where('company_id','=',$company_id)->get();
                // return view('compensation.d365payroll',compact('payroll','allowances','deductions','income_tax','salary','date','has_been_run'));
                return     \Excel::create("export", function($excel) use ($users,$view) {

                    $excel->sheet("export", function($sheet) use ($users,$view) {
                        $sheet->loadView("$view",compact('users'))
                            ->setOrientation('landscape');
                    });

                })->export('xlsx');
                # code...
            }
            return view('empmgt.list',['users'=>$users,'usersforcount'=>$usersforcount,'companies'=>$companies,'branches'=>$branches,'departments'=>$departments,'roles'=>$roles,'user_groups'=>$user_groups,'managers'=>$managers,'competencies'=>$competencies,'ncompany'=>$ncompany,'grades'=>$grades,'staff_categories'=>$staff_categories]);

        }

    }
    public function getCompanyDepartmentsBranches($company_id){
        $company=Company::find($company_id);
        return ['departments'=>$company->departments,'branches'=>$company->branches];
    }
    public function getDepartmentJobroles($department_id){
        $department=Department::find($department_id);
        return ['jobroles'=>$department->jobs];
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }
    public function assignRole(Request $request)
    {
        // return $request->users;
        $users_count=count($request->users);
        $role_id=$request->role_id;

        for ($i=0; $i < $users_count; $i++) {
            $user=User::find($request->users[$i]);
            $user->role_id=$role_id;
            $user->save();
        }
        return 'success';
    }
    public function alterStatus(Request $request)
    {
        $request->users=explode(',',$request->users);;
        $users_count=count($request->users);
        $status=$request->role_id;

        for ($i=0; $i < $users_count; $i++) {
            $user=User::find($request->users[$i]);
            $user->status=$status;
            $user->save();

            $company_id=companyId();
            $com=Company::where('id',$company_id)->first();
            $branch=Branch::find($com->branch_id);
            $ssm=$branch->manager_id;

            /* $stat=new StatusChangeRequest();
             $stat->user_id=$request->users[$i];
             $stat->reason=$request->reason;
             $stat->details=$request->details;
             $stat->status=$status;
             $stat->start_date=Carbon::today();
             $stat->created_by=Auth::user()->id;
             $stat->company_id=$company_id;
             $stat->approved_by=$ssm;
             $stat->save();*/
            if ($status=='1'){
                //create user on biometric device
                // $this->createUser($user->id);
            }
            elseif ($status=='4'){
                $this->deleteUser($user->id);
            }
            else{
                //disabled//remove user
                //do not delete a user from biometric device, just change their status. Delete them from device only if They have been disengaged
                //$this->deleteUser($user->id);
            }
        }
        //send mail that the following users have been requested for a change
        /* $message='Kindly review and approve on TAMS Application';
         $data=[
             'users'=>$request->users,
             'mail'=>$message,
             'status'=>$status,
         ];
         $from='info@snapnet.com.ng';
         $subject='Status Change Request';
         $view_template='emails.statusrequest_mail';*/

        //Mail::to($user->email)->send(new SendMail($from,$subject,$data,$view_template));
        //Mail::to($branch->manager->email)->send(new SendMail($from,$subject,$data,$view_template));

        /*function statusIdToName($id){
            if ($id=='1'){return 'Active';}
            elseif ($id=='2'){return 'Suspended';}
            elseif ($id=='3'){return 'Resigned';}
            elseif ($id=='4'){return 'Disengaged';}
            return 'Suspended';
        }*/

        return 'success';
    }
    public function assignManager(Request $request)
    {
        // return $request->users;
        $users_count=count($request->users);
        $manager_id=$request->manager_id;
        $manager=User::find($manager_id);

        for ($i=0; $i < $users_count; $i++) {
            $user=User::find($request->users[$i]);
            $has_manager=$user->managers->contains('id',$manager_id);
            $user->line_manager_id=$manager_id;
            $user->save();
            // $has_manager=User::find($request->users[$i])->whereHas('managers',function ($query) use($manager_id)  {
            //      $query->where('users.id',$manager_id);
            //  })->get();
            if (!$has_manager && $manager_id!=$request->users[$i]) {
                $user->managers()->attach($manager_id);
                $user->line_manager_id=$manager_id;
                $user->save();
            }
        }
        return 'success';
    }
    public function assignGroup(Request $request)
    {
        // return $request->users;
        $users_count=count($request->users);
        $group_id=$request->group_id;
        $group=UserGroup::find($group_id);

        for ($i=0; $i < $users_count; $i++) {
            $user=User::find($request->users[$i]);
            $has_group=$user->user_groups->contains('id',$group_id);
            // $has_manager=User::find($request->users[$i])->whereHas('managers',function ($query) use($manager_id)  {
            //      $query->where('users.id',$manager_id);
            //  })->get();
            if (!$has_group) {
                $user->user_groups()->attach($group_id);
            }
        }
        return 'success';
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), ['name'=>'required|min:3','emp_num'=>['required',
            Rule::unique('users')->ignore($request->user_id)
        ]]);

        if ($validator->fails()) {
            return response()->json([
                $validator->errors()
            ],401);
        }
        //build LGA
        // $lga=\App\LocalGovernment::find($request->lga);
        // if (!$lga and $request->lga!='') {
        //     $lga=\App\LocalGovernment::create(['name'=>$request->lga,'state_id'=>$request->state]);
        // }
        //end build LGA
        $user=User::find($request->user_id);
        $user->update(['name'=>$request->name,'email'=>$request->email,'phone'=>$request->phone,'emp_num'=>$request->emp_num,'sex'=>$request->sex,'address'=>$request->address,'marital_status'=>$request->marital_status,'dob'=>date('Y-m-d',strtotime($request->dob)),'branch_id'=>$request->branch_id,'company_id'=>$request->company_id,'bank_id'=>$request->bank_id,'bank_account_no'=>$request->bank_account_no,'country_id'=>0,'state_id'=>0,'lga_id'=>0]);

        $nok=\App\Nok::updateOrCreate(['id'=>$request->nok_id],['name'=>$request->name,'phone'=>$request->nok_phone,'address'=>$request->nok_address,'relationship'=>$request->nok_relationship,'user_id'=>$request->user_id]);

        if ($request->file('avatar')) {
            $path = $request->file('avatar')->store('public/avatar');
            if (Str::contains($path, 'public/avatar')) {
                $filepath= Str::replaceFirst('public/avatar', '', $path);
            } else {
                $filepath= $path;
            }
            $user->image = $filepath;
            $user->save();


            $url= asset('uploads/public/avatar'.$filepath);
            $urls=[$url];//937e1891-8b33-4fe0-bced-e60dc9e42c5d
            $image_id= $this->addFacetoList($urls);
            $user->image_id = $image_id[$url];
            $user->save();
        }


        return 'success';
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //

    }
    public function search(Request $request)
    {


        if($request->q==""){
            return "";
        }
        else{
            $name=\App\User::where('name','LIKE','%'.$request->q.'%')
                ->select('id as id','name as text')
                ->get();
        }


        return $name;


    }
    public function modal($user_id)
    {
        $user=User::find($user_id);
        return view('empmgt.partials.info',['user'=>$user]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit($user_id)
    {
        $user=User::findorFail($user_id);
        $countries=\App\Country::all();
        $qualifications=Qualification::all();
        $competencies=Competency::all();
        $companies=Company::all();
        $banks=Bank::all();
        $company=Company::find('1');
        $grades=Grade::all();
        if(!$company){
            $company=Company::first();
        }
        $departments=$company->departments;
        $jobroles=$company->departments()->first()->jobs;
        $staff_categories=StaffCategory::all();
        // return $user->skills()->where('skills.id',1)->first()->pivot->competency;
        return view('empmgt.profile',['user'=>$user,'qualifications'=>$qualifications,'countries'=>$countries,'competencies'=>$competencies,'companies'=>$companies,'banks'=>$banks,'company'=>$company,'grades'=>$grades,'departments'=>$departments,'jobs'=>$jobroles,'staff_categories'=>$staff_categories]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
    }
    public function saveNew(Request $request)
    {

        $validator = Validator::make($request->all(), ['name'=>'required|min:3','emp_num'=>['required',
            Rule::unique('users')->ignore($request->user_id)
        ]]);

        if ($validator->fails()) {
            return response()->json([
                $validator->errors()
            ],401);
        }

        $user=User::create(['name'=>$request->name,'email'=>$request->email,'phone'=>$request->phone,
            'emp_num'=>$request->emp_num,'sex'=>$request->sex,
            'hiredate'=>date('Y-m-d',strtotime($request->started)),
            'dob'=>date('Y-m-d',strtotime($request->dob)),'branch_id'=>$request->branch_id,
            'company_id'=>$request->company_id,'job_id'=>$request->job_id,'department_id'=>$request->department_id,
            'role_id'=>$request->role_id,'status'=>1]);

        $this->createUser($user->id);

        $user->jobs()->attach($request->job_id, ['started' => date('Y-m-d',strtotime($request->started))]);

        $user->save();

        return 'success';
    }

    public function statusRequests(){
        $requests=StatusChangeRequest::all();
        return view('empmgt.status_change_requests',compact('requests'));
    }

    public function approveStatusRequest($status_change_id,$answer){
        $stat=StatusChangeRequest::find($status_change_id);
        if(Auth::user()->id==$stat->approved_by){
            $stat->approved=$answer;
            $stat->save();
            if ($answer=='yes'){
                $user=User::find($stat->user_id);
                $user->status=$stat->status;
                $user->save();
                if($stat->status=='4' || $stat->status=='3'){
                    //$this->deleteUser($user->id);
                }
            }
            //send email notification to admins that it has been approved or declined
        }
        return Redirect::back();
    }

    public function verifyuser(){
        $users=[];
        return view('empmgt.verify',compact('users'));
    }

    use facematchtrait;
    public function postVerifyUser(Request $request){
        if ($request->file('avatar')) {
            $path = $request->file('avatar')->store('verify');
            if (Str::contains($path, 'verify')) {
                $filepath= Str::replaceFirst('verify', '', $path);
            } else {
                $filepath= $path;
            }

            $url= asset('uploads/verify'.$filepath);
            $res = $this->faceDetectandMatch($url);
            if (isset($res->error->message)) {
                throw new \Exception($res->error->message);
            }
            $newRes = [];
            foreach ($res as $response) {
                $response = (array)$response;
                $newRes[] = array_merge($response, ['user' => User::where('image_id', $response['persistedFaceId'])->with('company')->first()]);
            }
            $users = $newRes;
            /*$new=new FacialVerifyRequest();
            $new->user_id=Auth::id();
            $new->image_url=$url;
            $new->save();
            VerifyFacialJob::dispatch($new->id);
            $users=[];
            $message='The process will be running in background, You will get a response when the matching is complete';*/

            return view('empmgt.verify',compact('users','url'));
        }
    }

    public function apiVerifyUser(Request $request){
        //return $request->all();
        if (!$request->filled('fileurl')) {
            return ['status'=>'fail','details'=>'request image not found'];
        }
        $url = $request->fileurl;
        //check if the url contains a valid picture
        $res = $this->faceDetectandMatch($url);
        if (isset($res->error->message)) {
            throw new \Exception($res->error->message);
        }
        $newRes = [];
        foreach ($res as $response) {
            $response = (array)$response;
            $newRes[] = array_merge($response, ['user' => User::where('image_id', $response['persistedFaceId'])->with('company')->first()]);
        }
        $users = $newRes;
        return ['status'=>'success','requested_photo' => $url, 'data' => $users,'details'=>'successfull request'];

    }
    
    



    // API
    function users()
    {
        $users = \App\User::orderBy('id', 'desc')->get();
        $data = collect(['users' => $users]);   return $data; 
    }


    function states()
    {
        $states = \App\State::orderBy('name', 'asc')->get();
        $data = collect(['states' => $states]);   return $data; 
    }


}
