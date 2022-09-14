<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $fillable = ['name', 'email','address','user_id','logo','branch_id','state_id','biometric_serial','idms_id','owner','status','pay_full_days'];
    protected $appends=['last_seen','last_shift'];
    // protected $appends=['last_seen'];

    public function users()
    {
        return $this->hasMany('App\User','company_id');
    }
    public function departments()
    {
        return $this->hasMany('App\Department');
    }
    public function branches()
    {
        return $this->hasMany('App\Branch');
    }
    public function weekend()
    {
        return $this->hasOne('App\Weekend');
    }
    public function workingperiod()
    {
        return $this->hasOne('App\WorkingPeriod');
    }
    public function jobs()
    {
        return $this->hasManyThrough('App\Job','App\Department');
    }
    public function branch(){
        return $this->belongsTo('App\Branch');
    }
    public function state(){
        return $this->belongsTo('App\State');
    }
    public function command_log(){
        return $this->hasMany('App\CompanyLog');
    }
    public function getlastSeenAttribute(){
        return Biometric::select('id', 'created_at')->where('biometric_serial', $this->biometric_serial)->orderBy('id','desc')->limit(1)->first();
    }
    /*public function last_seen(){
        return $this->hasOne('App\Biometric','biometric_serial','biometric_serial')->orderBy('id','desc');
    }*/
    /*public function last_shift(){
        return $this->hasManyThrough('App\UserDailyShift', 'App\User');
    }*/
    public function last_shift(){
        return $this->hasManyThrough('App\UserDailyShift', 'App\User')->get();
    }
    public function getlastShiftAttribute(){
        $users=User::select('id')->where('status','1')->where('company_id',$this->id)->pluck('id')->toArray();
        return UserDailyShift::whereIn('user_id',$users)->orderBy('sdate','desc')->limit(1)->first();
    }
}