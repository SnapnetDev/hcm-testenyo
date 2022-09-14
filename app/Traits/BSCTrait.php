<?php
namespace App\Traits;
use App\BscMetric;
use App\BscSubMetric;
use App\BscMeasurementPeriod;
use App\BscWeight;
use App\BscDet;
use App\BscDetDetail;
use App\BscEvaluation;
use App\BscEvaluationDetail;
use App\Department;
use Illuminate\Http\Request;
use Excel;

trait BscTrait{

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
			case 'download_template':
				# code...
				return $this->downloadTemplate($request);
				break;
			case 'get_det_details':
				# code...
				return $this->getDETDetails($request);
				break;
			case 'delete_det_detail':
				# code...
				return $this->deleteDETDetail($request);
				break;
			case 'get_det':
				# code...
				return $this->getDET($request);
				break;
			case 'template':
				# code...
				return $this->template($request);
				break;
			
			
			default:
				# code...
				break;
		}
		 
	}


	public function processPost(Request $request){
		// try{
		switch ($request->type) {
			case 'editweight':
				# code...
				return $this->saveWeight($request);
				break;
			case 'measurementperiod':
				# code...
				return $this->saveMeasurementPeriod($request);
				break;
			case 'save_det_detail':
				# code...
				return $this->saveDETDetail($request);
				break;
			case 'import_template':
				# code...
				return $this->importTemplate($request);
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

	public function bscsettings(Request $request)
	{
		$metrics=BscMetric::all();
		$measurementperiods=BscMeasurementPeriod::all();
		$perspectives=BscWeight::all();
		return view('settings.bscsettings.metric',compact('metrics','measurementperiods','perspectives'));
	}
	public function saveMetric(Request $request)
	{
		BscMetric::updateOrCreate(['id',$request->metric_id],['name'=>$request->name,'description'=>$request->description]);
		return 'success';
	}
	public function getMetric(Request $request)
	{
		return $metric= BscMetric::find($request->submetric_id);
	}
	
	public function getSubmetric(Request $request)
	{
		return $submetric= BscSubMetric::find($request->submetric_id);
	}
	
	public function getMeasurementPeriod(Request $request)
	{
		return $measurementperiod= BscMeasurementPeriod::find($request->mp_id);
	}
	public function saveMeasurementPeriod(Request $request)
	{
		$month=date('m',strtotime('01-'.$request->to));
		$year=date('Y',strtotime('01-'.$request->to));
		$days=cal_days_in_month(CAL_GREGORIAN,$month,$year);
		BscMeasurementPeriod::updateOrCreate(['id'=>$request->mp_id],['from'=>date('Y-m-d',strtotime('01-'.$request->from)),'to'=>date('Y-m-d',strtotime($days.'-'.$request->to))]);
		return 'success';
	}

	public function getWeight(Request $request)
	{
		return $weight= BscWeight::where('id',$request->weight_id)->with(['department.company', 'department','metric','grade_category'])->first();
	}

	public function saveWeight(Request $request)
	{
		BscWeight::where('id',$request->weight_id)->update(['percentage'=>$request->percentage]);
		return 'success';
	}

	
private function downloadTemplate(Request $request){

                           $template=['Perspective','Business Goal','Measure','Lower','Mid-Target','Upper-Target','Weighting'];
                           $perspective=\App\BscMetric::select('id as Internal ID','name')->get()->toArray();

                           return $this->exportexcel('template',['template'=>$template,'perspective'=>$perspective]);

              }

	private function exportexcel($worksheetname,$data)
	{
		return \Excel::create($worksheetname, function($excel) use ($data)
		{
			foreach($data as $sheetname=>$realdata)
			{
				$excel->sheet($sheetname, function($sheet) use ($realdata,$sheetname)
				{
					  
			            $sheet->fromArray($realdata);
			           $sheet->_parent->getSheet(0)->setColumnFormat(array(
							    'G' => '0%'
							));

			      if($sheetname=='perspective'){
			      
		            	$sheet->_parent->addNamedRange(
		            		new \PHPExcel_NamedRange(
		            			'sd', $sheet->_parent->getSheet( 1 ), "B2:B" . $sheet->_parent->getSheet( 1 )->getHighestRow()
		            		)
		            	);
		            
			     for($j=2; $j<=100; $j++){ 
			           $objValidation = $sheet->_parent->getSheet(0)->getCell("A$j")->getDataValidation();
			           $objValidation->setType(\PHPExcel_Cell_DataValidation::TYPE_LIST);
			           $objValidation->setErrorStyle(\PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
			           $objValidation->setAllowBlank(false);
			           $objValidation->setShowInputMessage(true);
			           $objValidation->setShowErrorMessage(true);
			           $objValidation->setShowDropDown(true);
			           $objValidation->setErrorTitle('Input error');
			           $objValidation->setError('Value is not in list.');
			           $objValidation->setPromptTitle('Pick from list');
			           $objValidation->setPrompt('Please pick a value from the drop-down list.');
      				   $objValidation->setFormula1('sd');



			            }
			            }
  		
				});
			}
		})->download('xlsx');
	}

		public function getDet(Request $request)
	{
		
		$department=Department::find($request->department);
		$mp=BscMeasurementPeriod::find($request->mp);
		$operation='evaluate';
		
						$det=BscDet::where(['department_id'=>$department->id,'measurement_period_id'=>$request->mp])->first();
					if($det){
						$metrics=BscMetric::all();
						
						return view('bsc.view_department_template',compact('det','metrics','department'));

					}else{
						
						
							$det=BscDet::create(['measurement_period_id'=>$request->mp,'department_id'=>$department->id]);
							$metrics=BscMetric::all();
						
						return view('bsc.view_department_template',compact('det','metrics','department'));

						

					}
			


		
		
	}

	public function saveDETDetail(Request $request)
	{
					
			
			 $det_detail=BscDetDetail::updateOrCreate(['id'=>$request->id],['bsc_det_id'=>$request->bsc_det_id,'bsc_metric_id'=>$request->bsc_metric_id,'business_goal'=>$request->business_goal,'measure'=>$request->measure,'lower'=>$request->lower,'mid'=>$request->mid,'upper'=>$request->upper,'weighting'=>$request->weighting]);
			
			 
			return $det_detail;
			
	
	}

	public function getDETDetails(Request $request)
	{
		return $det_details=BscDetDetail::where(['bsc_det_id'=>$request->bsc_det_id,'bsc_metric_id'=>$request->bsc_metric_id])->get();
		
	}

	public function deleteDETDetail(Request $request)
	{
		$det_detail=BscDetDetail::find($request->id);
		$det_detail->delete();
		
	}

	public function template(Request $request)
	{
		$company_id=companyId();
        $measurement_periods=BscMeasurementPeriod::all();
        
        $departments=Department::where('company_id',$company_id)->get();
     
       
        return view('bsc.template',compact('measurement_periods','departments'));
	}
	public function importTemplate(Request $request)
	{
		$document = $request->file('template');
		$det=BscDet::find($request->det_id);
		 //$document->getRealPath();
		// return $document->getClientOriginalName();
		// $document->getClientOriginalExtension();
		// $document->getSize();
		// $document->getMimeType();
		

		 if($request->hasFile('template')){

		 	$datas=\Excel::load($request->file('template')->getrealPath(), function($reader) { 
                                         $reader->noHeading()->skipRows(1);
                           })->get();
		 	
                           foreach ($datas[0] as $data) {
                           	// dd($data[0]);
                           	if ($data[0]) {
			            			$metric=BscMetric::where('name',$data[0])->first();
			            			 $det_detail=BscDetDetail::create(['bsc_det_id'=>$det->id,'bsc_metric_id'=>$metric->id,'business_goal'=>$data[1],'measure'=>$data[2],'lower'=>$data[3],'mid'=>$data[4],'upper'=>$data[5],'weighting'=>$data[6]*100]);
			            			
			            			
			            		}
			                                                       
                           }



       //      Excel::load($request->file('template')->getRealPath(), function ($reader) use($det) {
       //      	// dd($reader->toArray());
           	
       //      	foreach ($reader->toArray() as $key => $row) {
            		 	
       //      		if ($row[0][0]['perspective']) {
       //      			$metric=BscMetric::where('name',$row[0][0]['perspective'])->first();
       //      			 $det_detail=BscDetDetail::create(['bsc_det_id'=>$det->id,'bsc_metric_id'=>$metric->id,'business_goal'=>$row[0][0]['business_goal'],'measure'=>$row[0][0]['measure'],'lower'=>$row[0][0]['lower'],'mid'=>$row[0][0]['mid_target'],'upper'=>$row[0][0]['upper_target'],'weighting'=>$row[0][0]['weighting']*100]);
            			
            			
       //      		}
            		
            		
						 // }
       //      });
            
          // $request->session()->flash('success', 'Import was successful!');

        return 'success';
        }

	}


}