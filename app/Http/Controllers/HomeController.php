<?php

namespace App\Http\Controllers;

use App\AttendanceReport;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Company;
use App\User;
use App\WorkingPeriod;
use App\Attendance;
use App\Leave;
use App\LeaveRequest;
use App\Job;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use phpDocumentor\Reflection\DocBlock\Description;

// use Fzaninotto\Faker\Generator as Faker;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        /*$company_id = companyId();
        if ($company_id > 0) {
            $company = Company::find($company_id);
            $jobs = $company->jobs()->get();
        } else {
            $jobs = Job::all();
        }

        $pending_leave_requests = LeaveRequest::where('status', 0)->whereYear('start_date', date('Y'))->count();
        $date = date('Y-m-d');
        $yesterday = date('Y-m-d', strtotime('yesterday'));
        $lastmonth = date('m', strtotime('first day of previous month'));
        $absentees = User::whereDoesntHave('attendances', function ($query) use ($date) {
            $query->whereDate('date', $date);

        })->count();
        // $lt=User::whereHas('attendancedetails', function ($query) use ($date) {
        //     $query->whereDate('date', $date);

        // })->count();
        $usersPresent = User::whereHas('attendances', function ($query) use ($date) {
            $query->whereDate('date', $date);
        })->count();
        $yesterday_absentees = User::whereDoesntHave('attendances', function ($query) use ($yesterday) {
            $query->whereDate('date', $yesterday);
        })->count();
        $yesterday_usersPresent = User::whereHas('attendances', function ($query) use ($yesterday) {
            $query->whereDate('date', $yesterday);
        })->count();
        $lates = 0;
        $yesterday_lates = 0;
        $earlys = 0;
        $yesterday_earlys = 0;
        $first_clock_in = '';
        $diff = 0;
        $wp = WorkingPeriod::all()->first();

        $users = User::whereHas('attendances', function ($query) use ($date) {
            $query->whereDate('date', $date);
        })->get();
        /*foreach ($users as $user) {

            $first_clock_in = Attendance::where(['emp_num' => $user->emp_num, 'date' => $date])->first()->attendancedetails()->orderBy('clock_in', 'asc')->first()->clock_in;
            $first_clock_in_yesterday = Attendance::where(['emp_num' => $user->emp_num, 'date' => $yesterday])->first()->attendancedetails()->orderBy('clock_in', 'asc')->first()->clock_in;


            $diff = $this->time_diff($first_clock_in, $wp->sob);
            $yesterday_diff = $this->time_diff($first_clock_in_yesterday, $wp->sob);
            if ($diff > 0) {
                $earlys++;
            } else {
                $lates++;
            }
            if ($yesterday_diff > 0) {
                $yesterday_earlys++;
            } else {
                $yesterday_lates++;
            }

        }//
        $last_month_early_users = \App\TimesheetDetail::orderBy('average_first_clock_in', 'asc')->take(5)->get();
        $last_month_late_users = \App\TimesheetDetail::orderBy('average_first_clock_in', 'desc')->take(5)->get();
        $companies = Company::all();
        return view('demo_home', compact('companies', 'absentees', 'usersPresent', 'yesterday_absentees', 'yesterday_usersPresent', 'earlys', 'lates', 'yesterday_earlys', 'yesterday_lates', 'last_month_early_users', 'last_month_late_users', 'pending_leave_requests', 'jobs'));
        */

        $user=Auth::user();
        if ($user->role_id=='1'||$user->role_id=='6'||$user->role_id=='7') {
            return view('executiveview.attendance');

        } else {
            
            $date = Carbon::today();
            $company_id = companyId();
            $users = User::where('company_id', $company_id)->get();
            $count_users = $users->count();
            $user_ids = $users->pluck('id')->toArray();

            $attendances = AttendanceReport::whereIn('user_id', $users->pluck('id')->toArray())->whereDate('date', $date->format('Y-m-d'))->with('user')->get();
            $earlys = $attendances->where('status', 'early')->count();
            $lates = $attendances->where('status', 'late')->count();
            $absentees = $attendances->where('status', 'absent')->count();
            $presents = $attendances->whereIn('status', ['early', 'late'])->count();
            $offs = $attendances->where('shift_name', 'Off Day')->count();

            $top_earlys = AttendanceReport::select('user_id', DB::raw('count(status) as count'))->whereMonth('date', $date->format('m'))->whereYear('date', $date->format('Y'))->whereIn('user_id', $user_ids)->where('status', 'early')->with('user')->groupBy('user_id')->orderBy('count', 'DESC')->take(5)->get();
            $top_lates = AttendanceReport::select('user_id', DB::raw('count(status) as count'))->whereMonth('date', $date->format('m'))->whereYear('date', $date->format('Y'))->whereIn('user_id', $user_ids)->where('status', 'late')->with('user')->groupBy('user_id')->orderBy('count', 'DESC')->take(5)->get();

            $company=Company::find($company_id);
            $last_sync=$company->last_seen;
            return view('home', compact('earlys', 'lates', 'absentees', 'presents', 'count_users', 'offs', 'top_earlys', 'top_lates','last_sync'));

        }


    }

    public function executiveView()
    {

        return view('executiveview.index');
    }

    public function executiveViewLeave()
    {

        return view('executiveview.leave');
    }

    public function executiveViewAttendance()
    {

        return view('executiveview.attendance');
    }

    public function time_diff($time1, $time2)
    {
        $time1 = strtotime("1/1/2018 $time1");
        $time2 = strtotime("1/1/2018 $time2");

        // if ($time2 < $time1)
        // {
        //  $time2 = $time2 + 86400;
        // }

        return ($time2 - $time1) / 3600;
    }

    public function setfy($year)
    {
        session(['FY' => $year]);
        return response()->json('ok', 200);

    }

    public function setcpny($company_id)
    {
        session(['company_id' => $company_id]);
        return response()->json('ok', 200);


    }

    public function countries(Request $request)
    {
        if ($request->q == "") {
            return "";
        } else {
            $name = \App\Country::where('name', 'LIKE', '%' . $request->q . '%')
                ->select('id as id', 'name as text')
                ->get();
        }


        return $name;

    }

    public function states(Request $request, $country_id)
    {

        if ($request->q == "") {
            return "";
        } else {
            $country = \App\Country::find($country_id);
            $name = $country->states()->where('name', 'LIKE', '%' . $request->q . '%')
                ->select('id as id', 'name as text')
                ->get();
        }


        return $name;
    }

    public function lgas(Request $request, $state_id)
    {

        if ($request->q == "") {
            return "";
        } else {
            $state = \App\State::find($state_id);
            $name = $state->lgas()->where('name', 'LIKE', '%' . $request->q . '%')
                ->select('id as id', 'name as text')
                ->get();
        }


        return $name;
    }
}
