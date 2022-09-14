<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Scope;
// use Auth;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'email', 'password','emp_num','sex','dob','phone','marital_status','password',
        'company_id','branch_id','job_id','hiredate','role_id','image','remember_token','created_at','updated_at',
        'address','lga_id','employment_status','superadmin','bank_id','bank_account_no','state_id','country_id',
        'grade_id','line_manager_id','payroll_type','project_salary_category_id','last_login_at','last_login_ip','union_id','status'];
    protected $dates=['hiredate'];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

     /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();
        if(!\Auth::guest()){
        $auth = \Auth::user();
    //     if (session()->has('company_id')) {
    //     $comp_id=session('company_id');
    //     static::addGlobalScope('company_id', function (Builder $builder) use ($auth,$comp_id){
             
    //            if ($auth->role->permissions->contains('constant', 'group_access')) {
    //                     if ($comp_id==0) {
    //                          $builder->where('company_id', '>',  0);
    //                     } else {
    //                         $builder->where('company_id',  $comp_id);
    //                     }
                        
                       

    //             }
    //             else{
    //                 $builder->where('company_id', $auth->company_id);
    //             }
            
    //     });
    // }else{
    //     if (\Auth::user()->company and \Auth::user()->role->permissions->contains('constant', 'group_access')) {
        
    //     static::addGlobalScope('company_id', function (Builder $builder) use ($auth){
             
              
    //                     $builder->where('company_id', '>',  0);
            
    //     });
    //     session(['company_id'=>0]);
        
         
    // }elseif (\Auth::user()->company) {
    //     static::addGlobalScope('company_id', function (Builder $builder) use ($auth){
             
               
    //                 $builder->where('company_id', $auth->company_id);
                
            
    //     });
        
    //     $company=\App\Company::where('id',\Auth::user()->company_id)->get()->first();
    //     session(['company_id'=>$company->id]);
        
         
    // }else{
    //     $company=\App\Company::where('is_parent',1)->get()->first();
    //     static::addGlobalScope('company_id', function (Builder $builder) use ($company){
             
              
    //                 $builder->where('company_id', $company->id);
               
            
    //     });
        

    //     session(['company_id'=>$company->id]);
        
    // }
    // }


        static::addGlobalScope('company_id', function (Builder $builder) use ($auth){
             
               if ($auth->role->permissions->contains('constant', 'group_access')) {
                        $builder->where('company_id', '>',  0);

                }
                else{
                    $builder->where('company_id', $auth->company_id);
                }
            
        });
        }
    }

    public function role()
    {
        return $this->belongsTo('App\Role');
    }
    public function branch()
    {
        return $this->belongsTo('App\Branch');
    }
    public function department()
    {
        return $this->belongsTo('App\Department');
    }
    public function job()
    {
        return $this->belongsTo('App\Job');
    }
    public function company()
    {
        return $this->belongsTo('App\Company');
    }
    public function bank()
    {
        return $this->belongsTo('App\Bank');
    }
    // public function subsidiary()
    // {
    //     return $this->belongsTo('App\Subsidiary');
    // }
    //Nok is the next of kin
    public function nok()
    {
        return $this->hasOne('App\Nok');
    }
    public function jobs()
    {
        return $this->belongsToMany('App\Job','employee_job','user_id','job_id')->withPivot('started', 'ended')->withTimestamps();
    }
    public function dependants()
    {
        return $this->hasMany('App\Dependant','user_id');
    }
    public function exemptions()
    {
        return $this->hasMany('App\Exemption');
    }
    public function employmentHistories()
    {
        return $this->hasMany('App\EmploymentHistory');
    }
    public function promotionHistories()
    {
        return $this->hasMany('App\PromotionHistory');
    }
    public function educationHistories()
    {
        return $this->hasMany('App\EducationHistory','emp_id');
    }
    public function skills()
    {
        return $this->belongsToMany('App\Skill')->using('App\UserSkillCompetency')->withTimestamps()->withPivot('competency_id');
    }
   
    public function profHistories()
    {
        return $this->hasMany('App\ProfHistory');
    }
    public function socialMediaAccounts()
    {
        return $this->belongsToMany('App\SocialMediaAccount','user_social_media_account','user_id','social_media_account_id');
    }
    public function managers()
    {
        return $this->belongsToMany('App\User','employee_manager','employee_id','manager_id')->withTimestamps();
    }
    public function employees()
    {
        return $this->belongsToMany('App\User','employee_manager','manager_id','employee_id')->withTimestamps();
    }
    public function employmentStatus()
    {
        return $this->belongsTo('App\EmploymentStatus');
    }
    // public function grades()
    // {
    //     return $this->hasManyThrough('App\Grade','App\PromotionHistory');
    // }
    public function grade()
    {
        return $this->belongsTo('App\Grade');
    }
    public function performanceseason(){
         $checkseason= \App\PerformanceSeason::select('reviewStart')->value('reviewStart');
             return $checkseason;

    }
    public function quarterName($num){
        switch ($num) {
            case 1:
               return 'First';
                break;
                case 2:
               return 'Second';
                break;
                case 3:
               return 'Third';
                break;
                case 4:
               return 'Fourth';
                break;
            
            default:
                # code...
                break;
        }

        // $formatter = new \NumberFormatter('en_US', \NumberFormatter::SPELLOUT);
        // $formatter->setTextAttribute(\NumberFormatter::DEFAULT_RULESET,"%spellout-ordinal");
        // return ucfirst($formatter->format($num));
    }
  public function progressreport(){
        return $this->hasMany('App\ProgressReport','emp_id');
    }

public function getquarter(){
    
    //getquarter
    $review=\App\PerformanceSeason::value('reviewFreq');

    return 12/$review;
    
  }
    public function getEmploymentStatusAttribute(){

        return $this->employment_status_id==1 ? 'Locked' : 'Open';
    }

    public function goal(){
        return $this->hasMany('App\Goal','user_id')->withDefault();
    }

     public function getProbationStatusAttribute(){
          if(!is_null($this->hiredate) && $this->hiredate->diffInDays()<=180){
            return '<span class="tag tag-warning">On-Probation</span>';
          }
          elseif(!is_null($this->hiredate) && $this->hiredate->diffInDays()>180 && $this->confirmed==1){
            return '<span class="tag tag-success">Confirmed</span>';
          }
          else{
            return 'N/A';
          }
        }
    public function getEmployeeeJobAttribute(){
        $getLattest=\App\EmployeeJob::where('user_id',$this->id)->orderBy('started','desc')->value('job_id');
        return $getLattest;
    }
    public function getDepartmentAttribute(){
        $getdept=\App\Job::where('id',$this->getEmployeeeJobAttribute())->value('department_id');
        return $getdept;
    }

    public function position()
    {
        return $this->belongsTo('App\Position','position_id');
    }
    public function location()
    {
        return $this->belongsTo('App\Location','location_id');
    }
    public function category()
    {
        return $this->belongsTo('App\StaffCategory','staff_category_id');
    }
    public function employee_type()
    {
        return $this->belongsTo('App\EmployeeType','employee_type_id');
    }
    public function costcenter()
    {
        return $this->belongsTo('App\CostCenter','costcenter_id');
    }
    public function attendances()
    {
         return $this->hasMany('App\Attendance','emp_num','emp_num');
    }
    public function attendancedetails()
    {
         return $this->hasManyThrough('App\AttendanceDetail','App\Attendance','emp_num','emp_num');
    }
    public function timesheets()
    {
        return $this->hasManyThrough('App\Timesheet','App\TimesheetDetail','user_id');
    }
    public function timesheetdetails()
    {
        return $this->hasMany('App\TimesheetDetail','user_id');
    }
    public function shifts()
    {
         return $this->belongsToMany('App\Shift','user_shift_schedule','user_id','shift_id');
    }
    public function shift_schedules()
    {
         return $this->belongsToMany('App\ShiftSchedule','user_shift_schedule','user_id','shift_schedule_id');
    }
    public function usershiftschedules()
    {
        return $this->hasMany('App\UserShiftSchedule');
    }
    public function initiatedShiftSwaps()
    {
        return $this->hasMany('App\UserShiftSchedule','owner_id');
    }
    public function suggestedShiftSwaps()
    {
        return $this->hasMany('App\UserShiftSchedule','swapper_id');
    }
    public function SalaryComponents()
    {
        return $this->belongsToMany('App\SalaryComponent','salary_component_exemptions','user_id','salary_component_id');
    }
    public function specificSalaryComponents(){
         return $this->hasMany('App\SpecificSalaryComponent','emp_id');
    }
    public function leave_requests()
    {
        return $this->hasMany('App\LeaveRequest','user_id');
    }
    public function user_groups()
    {
        return $this->belongsToMany('App\UserGroup','user_group_user','user_id','user_group_id');
    }
    public function loan_requests()
    {
        return $this->hasMany('App\LoanRequest','user_id');
    }
    public function payroll_details()
    {
         return $this->hasMany('App\PayrollDetail','user_id');
    }

    public function stages()
    {
        return $this->morphMany('App\Stage', 'stageable');
    }
    public function lga()
    {
       return $this->belongsTo('App\LocalGovernment');
    }
     public function state()
    {
       return $this->belongsTo('App\State');
    }
     public function country()
    {
       return $this->belongsTo('App\Country');
    }
    public function getDRLeaveApprovals()
    {
        $lm_id=$this->id;

        return \App\LeaveApproval::whereHas('leave_request',function($query) use ($lm_id){
                     $query->whereHas('user',function($query) use ($lm_id){
                                $query->whereHas('managers',function($query) use ($lm_id){
                                        $query->where('manager_id',$lm_id);
                                });
                        });
                })
                ->whereHas('stage.role',function($query) use($lm_id){
                $query->where('manages','dr');
                
                 })
                ->get();
        # code...
    }
    public function user_daily_shifts()
    {
         return $this->hasMany('App\UserDailyShift','user_id');
    }
    public function user_cust_attendances()
    {
         return $this->hasMany('App\CustAttendance','user_id');
    }

    public function my_departments()
    {
         return $this->hasMany('App\Department','manager_id');
    }

    //RELATIONSHIP FOR TRAINING MODULE STARTS
    public function TrainingRecommended()
    {
        return $this->hasMany('App\TrainingRecommended', 'trainee_id', 'suggester_id', 'approver_id');
    }

    public function TrainingBudget()
    {
        return $this->hasMany('App\TrainingBudget', 'status_id');
    }

    public function trainings()
    {
        return $this->belongsToMany('training_user', 'App\User', 'user_id', 'training_id');
    }

    // public function trainings()
    // {
    //     return $this->belongsToMany('training_user', 'App\User', 'user_id', 'training_id');
    // }




    public function TrainingUser()
    {
        return $this->hasMany('App\TrainingUser', 'user_id');
    }
     public function rec_tranings()
    {
        return $this->belongsToMany('App\TrainingRecommended','rec_training_trainee' ,'trainee_id','rec_training_id');
    }

    public function separations()
    {
        return $this->hasMany('App\Separation');
    }
    public function applications()
    {
        return $this->morphMany('App\JobApplication', 'applicable');
    }
    public function favorites()
    {
        return $this->morphMany('App\JobFavorite', 'favorable');
    }

    public function plmanager()
    {
        return $this->belongsTo('App\User','line_manager_id');
    }
    public function pdreports()
    {
        return $this->hasMany('App\User','line_manager_id');
    }

    public function getOnlygradeAttribute()
    {
         $gc = explode("-", $this->grade->level);
         return $gc[0];
    }
    public function getOnlylevelAttribute()
    {
        if (strpos( $this->grade->level,'-') !== false) {
                 $gc = explode("-", $this->grade->level);
         return $gc[1];
            }else{
                return '';
            }
        
    }
    public function project_salary_category()
    {
        return $this->belongsTo('App\PaceSalaryCategory','project_salary_category_id');
    }

    public function project_salary_timesheets()
    {
        return $this->hasMany('App\ProjectSalaryTimesheet','project_salary_category_id');
    }
    public function suspensions()
    {
        return $this->hasMany('App\Suspension');
    }
    public function suspension_deductions()
    {
        return $this->hasManyThrough('App\SuspensionDeduction', 'App\Suspension');
    }
    public function union()
    {
        return $this->belongsTo('App\UserUnion','union_id');
    }
    public function leave_spills()
    {
       return $this->hasMany('App\LeaveSpill','user_id');
    }

    public function attendancereport(){
        return $this->hasMany('App\AttendanceReport');
    }
    public function ScopeMonthlyReport($query,$month,$year){
        return $query->whereHas('attendancereport',function($query) use($month,$year) {
        });
    }
    public function ScopeMonthlyReport2($query,$month,$year){
        return $query->whereHas('attendancereport',function($query) use($month,$year) {
            $query->whereYear('date',$year)->whereMonth('date',$month);
        });
    }


}
