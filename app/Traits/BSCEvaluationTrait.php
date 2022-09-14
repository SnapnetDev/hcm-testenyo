<?php
namespace App\Traits;
use App\BscMetric;
use App\BscSubMetric;
use App\BscMeasurementPeriod;
use App\BscWeight;
use App\User;
use Auth;
use App\Department;
use App\Grade;
use App\GradeCategory;
use App\BscEvaluation;
use App\BscEvaluationDetail;
use App\BscDet;
use App\BscDetDetail;
use Illuminate\Http\Request;

trait BSCEvaluationTrait{
	public function processGet($route,Request $request){
		switch ($route) {
			case 'get_weight':
				# code...
				return $this->getWeight($request);
				break;
			case 'get_measurement_period':
				# code...
				return $this->getMeasurementPeriod($request);
				break;
			case 'get_evaluation_details':
				# code...
				return $this->getEvaluationDetails($request);
				break;
			case 'delete_evaluation_detail':
				# code...
				return $this->deleteEvaluationDetail($request);
				break;
			case 'get_evaluation_details_sum':
				# code...
				return $this->getEvaluationDetailsSum($request);
				break;
			case 'get_evaluation_wcp':
				# code...
				return $this->getEvaluationWcp($request);
				break;
			case 'get_evaluation':
				
				return $this->getEvaluation($request);
				break;
			case 'use_dept_template':
				
				return $this->useDeptTemplate($request);
				break;
			
			default:
				# code...
				break;
		}
		 
	}


	public function processPost(Request $request){
		// try{
		switch ($request->type) {
			case 'get_evaluation':
				
				return $this->getEvaluation($request);
				break;
			case 'save_evaluation_detail':
				# code...
				return $this->saveEvaluationDetail($request);
				break;
			case 'measurementperiod':
				# code...
				return $this->saveMeasurementPeriod($request);
				break;
			case 'save_evaluation_comment':
				# code...
				return $this->saveEvaluationComment($request);
				break;

			default:
				# code...
				break;
		}
		// }
		// catch(\Exception $ex){
		// 	return response()->json(['status'=>'error','message'=>$ex->getMessage()]);
		// }
	}

	public function getEvaluation(Request $request)
	{
		
		$user=User::find($request->employee);
		$mp=BscMeasurementPeriod::find($request->mp);
		$operation='evaluate';
		if ($user->grade) {
			if (!$user->department||!$user->grade->grade_category) {
				$request->session()->flash('error', 'User does not have a grade category or a department');
				return redirect()->back();
			}else{
						$evaluation=BscEvaluation::where(['user_id'=>$user->id,'bsc_measurement_period_id'=>$mp->id])->first();
					if($evaluation){
						$metrics=BscMetric::all();
						
						return view('bsc.evaluation',compact('user','operation','evaluation','metrics'));

					}else{
						
						
							$evaluation=BscEvaluation::create(['user_id'=>$user->id,'bsc_measurement_period_id'=>$mp->id,'department_id'=>$user->department_id,'grade_category_id'=>$user->grade->grade_category->id]);
							$metrics=BscMetric::all();
						
						return view('bsc.evaluation',compact('user','operation','evaluation','metrics'));

						

					}
			}


		}elseif(!$user->grade){
			$request->session()->flash('error', 'User does not have a grade ');
				return redirect()->back();
		}else{
			return 1;
			
		}
		
		
	}

	public function saveEvaluationDetail(Request $request)
	{
					
			$crra=$this->calc_result_achieved($request);
			 $evaluation_detail=BscEvaluationDetail::updateOrCreate(['id'=>$request->id],['bsc_evaluation_id'=>$request->bsc_evaluation_id,'metric_id'=>$request->metric_id,'business_goal'=>$request->business_goal,'measure'=>$request->measure,'source'=>$request->source,'lower'=>$request->lower,'mid'=>$request->mid,'upper'=>$request->upper,'actual'=>$request->actual,'weighting'=>$request->weighting,'comment'=>$request->comment,'crra'=>$crra,'wcp'=>($request->weighting/100)*$crra]);
			
			 $wcpSum=BscEvaluationDetail::where('bsc_evaluation_id',$evaluation_detail->bsc_evaluation_id)->sum('wcp');
			$evaluation=BscEvaluation::find($evaluation_detail->bsc_evaluation_id)->update(['score'=>$wcpSum]);
			return $evaluation_detail;
			
	
	}

	public function getEvaluationDetails(Request $request)
	{
		return $evaluation_details=BscEvaluationDetail::where(['bsc_evaluation_id'=>$request->bsc_evaluation_id,'metric_id'=>$request->metric_id])->get();
		
	}
	public function useDeptTemplate(Request $request)
	{
		$evaluation=BscEvaluation::find($request->bsc_evaluation_id);
		if ($evaluation) {
			$det=BscDet::where(['department_id'=>$evaluation->department->id,'measurement_period_id'=>$evaluation->measurement_period->id])->first();
		foreach ($det->details as $detail) {
			$evaluation->evaluation_details()->create(['metric_id'=>$detail->bsc_metric_id,'business_goal'=>$detail->business_goal,'measure'=>$detail->measure,'lower'=>$detail->lower,'mid'=>$detail->mid,'upper'=>$detail->upper,'weighting'=>$detail->weighting]);
		}
		return 'success';
		}
		
	}
	public function getEvaluationWcp(Request $request)
	{
		  $evaluation=BscEvaluation::find($request->bsc_evaluation_id);
		 return ['evaluation'=>$evaluation,'remark'=>$this->calc_Performance($evaluation->score)];

	}
	public function saveEvaluationComment(Request $request)
	{
		$evaluation=BscEvaluation::find($request->bsc_evaluation_id);
		$evaluation->update(['comment'=>$request->comment]);
		return 'success';
	}

	public function deleteEvaluationDetail(Request $request)
	{
		$evaluation_detail=BscEvaluationDetail::find($request->id);
		$evaluation_detail->delete();
		
	}
	public function getEvaluationDetailsSum(Request $request)
	{
		return $sum=BscEvaluationDetail::where(['bsc_evaluation_id'=>$request->bsc_evaluation_id,'metric_id'=>$request->metric_id])->sum('weighting');
	}

	 public function calc_Performance($summed_performance){
		if($summed_performance<=1.95){
			return "Poor Performance";
		}
		elseif($summed_performance<=2.45){
			return "Below Expectation";
		}
		elseif($summed_performance>=3.5){
			return "Exceeds Expectation";
		}
		elseif($summed_performance<=3.45){
			return "Meets Expectation";
		}
		else{
			return "";
		}
}

public function weighted_contribution(Request $request){
	$weighing=$request->weighing;
	$calc_result_achieved=$this->calc_result_achieved($request);
	return $weighing * $calc_result_achieved;
}

public function calc_result_achieved(Request $request)
{
   
	$lower= $request->lower;
	$mid_target= $request->mid;
	$upper_target= $request->upper;
	$act_result= $request->actual;

		
	if($lower<$mid_target){

		if($mid_target<$upper_target){

			if($act_result==""){
					return 1;
			}
		else{
			if($act_result>=$upper_target){
					return 4;
			}
		else{
			if($act_result<=$lower){
			return 1;
			}
		else{
			if($act_result==$mid_target){
			return 2.5;
		}
		else{
			if($act_result>$mid_target){
					return 4-($upper_target-$act_result)/($upper_target-$mid_target);
		}
		else{
			if($mid_target<$act_result ||$act_result>=$lower){
					return 2.5-($mid_target-$act_result)/($mid_target-$lower);


						       }
							}
						}
					}
				}
			}
		}
	else{
		if($act_result==""){
			return 1;
		}
		else{
			if($act_result>=$lower){
					return 1;
		}
		else{
			if($act_result<=$upper_target){
					return 4;
		}
		else{
			if($act_result==$mid_target){
					return 2.5;
		}
		else{
			if($act_result>=$mid_target){
					return 1+($lower-$act_result)/(($lower-$mid_target));
		}
		else{
			if($mid_target<$act_result || $act_result>=$upper_target){
					return 2.5+($mid_target-$act_result)/($mid_target-$upper_target);
		}
										}
									}
								}
							}
						}
					}

				}
			}

}