<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Company;
use App\Department;
use App\Branch;
use App\Job;
use App\User;
use App\Setting;
use Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class SystemSettingController extends Controller
{

	public function index($value='')
	{
		$has_sub=Setting::where('name','has_sub')->first();
		$use_parent_setting=Setting::where('name','use_parent_setting')->first();
		return view('settings.systemsettings.index',compact('has_sub','use_parent_setting'));
	}
	public function switchHasSubsidiary(Request $request)
	  {
	    $setting=Setting::where('name','has_sub')->first();
	    if ($setting->value==1) {
	     $setting->update(['value'=>0]);
	      return 2;
	    }elseif($setting->value==0){
	      $setting->update(['value'=>1]);
	       return 1;
	    }
	  }
	  public function switchUseParentSetting(Request $request)
	  {
	    $setting=Setting::where('name','use_parent_setting')->first();
	    if ($setting->value==1) {
	     $setting->update(['value'=>0]);
	      return 2;
	    }elseif($setting->value==0){
	      $setting->update(['value'=>1]);
	       return 1;
	    }
	  }
	

}