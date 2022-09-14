<?php

namespace App\Http\Controllers;
use App\User;
use App\Company;
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
use Illuminate\Http\Request;
use App\Traits\UserProfile;

class UserProfileController extends Controller
{
	use UserProfile;
	public function index(Request $request)
    {
    	 // $user=User::find($user_id);
      //  $locations=Location::all();
      //  $staff_categories=StaffCategory::all();
      //  $positions=Position::all();
      //  return view('empmgt.partials.details',['user'=>$user,'locations'=>$locations,'staff_categories'=>$staff_categories,'positions'=>$positions]);
       $user=\Auth::user();
       $countries=\App\Country::all();
       $qualifications=Qualification::all();
       $competencies=Competency::all();
       $companies=Company::all();
       $banks=Bank::all();
       $grades=Grade::all();
        $company=Company::find(5);
        if(!$company){
          $company=Company::first();
        }
        $departments=$company->departments;
        $jobroles=$company->departments()->first()->jobs;
       // return $user->skills()->where('skills.id',1)->first()->pivot->competency;
       return view('empmgt.profile',['user'=>$user,'qualifications'=>$qualifications,'countries'=>$countries,'competencies'=>$competencies,'companies'=>$companies,'banks'=>$banks,'company'=>$company,'grades'=>$grades,'departments'=>$departments,'jobs'=>$jobroles]);
    }
   
   public function store(Request $request)
    {
        //
     
        return $this->processPost($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request)
    {
        //
        return $this->processGet($id,$request);
    }

}
