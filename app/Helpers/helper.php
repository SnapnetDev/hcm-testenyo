<?php

function userCompanyName(){
	if (session()->has('company_id')) {
		$company=\App\Company::where('id',session('company_id'))->get()->first();
		return $company->name;
	}else{
		if(\Auth::user()->company){
		$company=\App\Company::where('id',\Auth::user()->company_id)->get()->first();
		session(['company_id'=>$company->id]);
		return $company->name;
		 

	}else{
		
		$company=\App\Company::where('is_parent',1)->get()->first();

		session(['company_id'=>$company->id]);
		return $company->name;
	}
	}
}

	function companyInfo(){
	if (session()->has('company_id')) {
		$company=\App\Company::where('id',session('company_id'))->get()->first();
		return $company;
	}else{
		if(\Auth::user()->company){
		$company=\App\Company::where('id',\Auth::user()->company_id)->get()->first();
		session(['company_id'=>$company->id]);
		return $company;
		 

	}else{
		
		$company=\App\Company::where('is_parent',1)->get()->first();

		session(['company_id'=>$company->id]);
		return $company;
	}
	}

	
	
}
function companyId(){
	if (session()->has('company_id')) {
		
		return session('company_id');
	}else{
		if (\Auth::user()->company) {
		
		$company=\App\Company::where('id',\Auth::user()->company_id)->get()->first();
		session(['company_id'=>$company->id]);
		return $company->id;
		 
	}else{
		
		$company=\App\Company::where('is_parent',1)->get()->first();

		session(['company_id'=>$company->id]);
		return $company->id;
	}
	}
}
function companies(){
	return \App\Company::all();
}

function punctualityStatus($time){
	$wp=\App\WorkingPeriod::all()->first();
	$time1 = strtotime("1/1/2018 $time1");
		$time2 = strtotime("1/1/2018 $time2");

	return ($time2 - $time1) / 3600;
}
function employeeFirstClockin($emp_num,$date){
	return $ci=\App\Attendance::where(['emp_num'=>$emp_num,'date'=>$date])->first()->attendancedetails()->orderBy('clock_in', 'asc')->first()->clock_in;
}
function employeeLastClockout($emp_num,$date){
	return $co=\App\Attendance::where(['emp_num'=>$emp_num,'date'=>$date])->first()->attendancedetails()->orderBy('clock_out', 'desc')->first()->clock_out;
}
function userAttendanceId($emp_num,$date){
	return $id=\App\Attendance::where(['emp_num'=>$emp_num,'date'=>$date])->first()->id;
}
function time_to_seconds($time) {
    $sec = 0;
    foreach (array_reverse(explode(':', $time)) as $k => $v) $sec += pow(60, $k) * $v;
    return $sec;
}

function bscweight($department_id,$grade_category_id,$metric_id){
	$weight=\App\BscWeight::where(['department_id'=>$department_id,'grade_category_id'=>$grade_category_id,'metric_id'=>$metric_id])->first();
	if ($weight) {
		return $weight;
	}elseif(!$weight){
		return $weight=\App\BscWeight::create(['department_id'=>$department_id,'grade_category_id'=>$grade_category_id,'metric_id'=>$metric_id,'percentage'=>25]);
	}
}