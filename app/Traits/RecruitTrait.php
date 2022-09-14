<?php
namespace App\Traits;
use Illuminate\Http\Request;
use App\JobList;
use App\Applicant;
use App\User;
use App\JobApplication;


trait RecruitTrait
{
	public function processGet($route,Request $request){
		switch ($route) {
			
				case 'joblist':
				# code...
				return $this->joblist($request);
				break;
				case 'jobapplicant':
				# code...
				return $this->jobapplicant($request);
				break;
			default:
				return $this->index($request);
				break;
		}
		 
	}


	public function processPost(Request $request){
		try{
		switch ($request->type) {
			case 'saveComment':
				# code...
			     return $this->saveComment($request);
				break;
			
			default:
				# code...
				break;
		}
	}
	catch(\Exception $ex){

		return response()->json(['status'=>'error','message'=>$ex->getMessage()]);
	}

	}

	public function joblist(Request $request)
	{
		
	}
}