<?php

namespace App\Http\Controllers;

use App\Attendance;
use App\AttendanceReport;
use App\User;
use App\WorkingPeriod;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Traits\Attendance as AttendanceTrait;
use Illuminate\Support\Facades\Auth;

class AttendanceReportController extends Controller
{
    use AttendanceTrait;


    public function __construct()
    {
        $this->middleware('auth')->except(['attendance_reports']);
    }



    public function lateStaff(Request $request)
    {
        if ($request->filled('from') && $request->filled('to')) {
            $start = Carbon::createFromFormat('m/d/Y', $request->from)->subDay();
            $end = Carbon::createFromFormat('m/d/Y', $request->to);
        } else {
            $end = Carbon::today()->subDay();
            $start = Carbon::today()->subDay(7);
        }
        $days = $start->diffInDays($end);
        for ($i = 0; $i <= $days; $i++) {
            $start->addDay();
            $dates[] = $start->format('Y-m-d');
        }
        $start = $dates[0];
        $end = $dates[$days];
        $wp = WorkingPeriod::first();
        $datas = [];
        foreach ($dates as $date) {
            $users = AttendanceReport::where('date', $date)->where('status', 'early')->whereHas('user')->with('attendance.attendancedetails')->with('user')->get();
            $users2 = AttendanceReport::where('date', $date)->where('status', 'late')->whereHas('user')->with('attendance.attendancedetails')->with('user')->get();
            $datas[] = $users;
            $datas2[] = $users2;
        }
        return view('attendance.new.lateStaffReport', compact('datas', 'datas2', 'dates', 'start', 'end'));
    }

    public function allStaff()
    {
        $auth = Auth::user();
        $company_id = companyId();
        $users = User::where('company_id', '=', $company_id)->get();
        return view('attendance.new.allStaff', compact('users'));
    }

    public function staffAttendance($staff, Request $request)
    {
        //return $request->from;
        $user = User::findorfail($staff);
        if ($request->filled('from') && $request->filled('to')) {
            $start = Carbon::createFromFormat('m/d/Y', $request->from)->subDay();
            $end = Carbon::createFromFormat('m/d/Y', $request->to);
        } else {
            $end = Carbon::today()->subDay();
            $start = Carbon::today()->subDay(7);
        }
        //return $dates[2];
        $attendances = AttendanceReport::where('user_id', $staff)->whereBetween('date', [$start->format('Y-m-d'), $end->format('Y-m-d')])->get();
        $start = $start->format('m/d/Y');
        $end = $end->format('m/d/Y');
        $staff = $user;
        if ($request->filled('type')) {
            // return $attendances;
            if ($request->type == 'excel') {
                $name = $user->name.' Report';
                $view = 'attendance.new.excelSpecificStaffAttendance';
                $this->exportToExcel($attendances, [], $view, $name);
            }
        }

        return view('attendance.new.specificStaffAttendance', compact('attendances', 'user', 'staff', 'start', 'end'));
    }

    public function staffTimesheet(Request $request)
    {
        return view('attendance.new.staffTimesheet');
    }

    public function excelstaffTimesheet(Request $request)
    {
        if ($request->filled('from') && $request->filled('to')) {
            $start = Carbon::createFromFormat('m/d/Y', $request->from)->subDay();
            $end = Carbon::createFromFormat('m/d/Y', $request->to);
        } else {
            $end = Carbon::today()->subDay();
            $start = Carbon::today()->subDay(7);
        }
        $days = $start->diffInDays($end);
        for ($i = 0; $i <= $days; $i++) {
            $start->addDay();
            $dates[] = $start->format('Y-m-d');
        }
        $start = Carbon::createFromFormat('Y-m-d', $dates[0])->format('d M,');
        $end = Carbon::createFromFormat('Y-m-d', $dates[$days])->format('d M, Y');

        $company_id = companyId();
        $users = User::where('company_id', '=', $company_id)->get();
        foreach ($users as $user) {
            $ddd = [];
            foreach ($dates as $date) {
                $att = AttendanceReport::where('user_id', $user->id)->where('date', $date)->first();
                if ($att) {
                    $ddd[]=$att->hours_worked;
                    if ($att->hours_woked=='0'){
                        $ddd[] = 'absent';
                    }
                } else {
                    $ddd[] = 'absent';
                }
            }
            $user['dates'] = $ddd;
        }
        //return $users;

        $name = 'Sheet '.$start.' - '.$end;
        $view = 'attendance.new.excelTimesheetReport';
        $this->exportToExcel($users, $dates, $view, $name);
    }

    public function getDayHours(Attendance $attendance)
    {
        $wp = WorkingPeriod::all()->first();
        $hours = 0;
        if ($attendance) {
            $details = $attendance->attendancedetails;
            $time = $details->first()->clock_in;
            foreach ($details as $detail) {
                $hours += $this->get_time_difference($detail->clock_in, $detail->clock_out);
            }
            $diff = $this->time_diff($time, $wp->sob);
            if ($diff > 0) {
                return $hours - $diff;
            }
        }
        return $hours;
    }

    public function get_time_difference($time1, $time2)
    {
        $time1 = strtotime("1/1/2018 $time1 ");
        $time2 = strtotime("1/1/2018 $time2");

        if ($time2 < $time1) {
            $time2 = $time2 + 86400;
        }

        return ($time2 - $time1) / 3600;

    }

    public function exportShiftSchedules(Request $request)
    {
        if ($request->filled('from') && $request->filled('to')) {
            $start = Carbon::createFromFormat('m/d/Y', $request->from)->subDay();
            $end = Carbon::createFromFormat('m/d/Y', $request->to);
        } else {
            $end = Carbon::today()->subDay();
            $start = Carbon::today()->subDay(7);
        }
        $days = $start->diffInDays($end);
        for ($i = 0; $i <= $days; $i++) {
            $start->addDay();
            $dates[] = $start->format('Y-m-d');
        }

        $company_id = companyId();
        $users = User::where('status','1')->where('company_id', '=', $company_id)->get();
        foreach ($users as $user) {
            $ddd = [];
            foreach ($dates as $date) {
                $user_shift = \App\UserDailyShift::where('user_id', $user->id)->whereDate('sdate', $date)->with('shift')->first();
                if ($user_shift) {
                    $ddd[] = $user_shift->shift->type;
                } else {
                    $ddd[] = 'Default Working Period';
                }
            }

            $user['dates'] = $ddd;
        }
        //return $users;

        $name = 'Staff Shift Schedule Report';
        $view = 'attendance.new.excelShiftScheduleReport';
        $this->exportToExcel($users, $dates, $view, $name);
    }


    public function timesheetExcel($timesheet_id)
    {
        $timesheet = Timesheet::find($timesheet_id);
        $holidays = Holiday::whereMonth('date', $timesheet->month)->whereYear('date', $timesheet->year)->get();
        $name = $timesheet->month . '-' . $timesheet->year . ' Timesheet';


    }

    private function exportToExcel($datas, $dates, $view, $name)
    {

        return \Excel::create("$name", function ($excel) use ($datas, $view, $dates, $name) {

            $excel->sheet("$name", function ($sheet) use ($datas, $view, $dates) {
                $sheet->loadView("$view", compact("datas", "dates"))
                    ->setOrientation('landscape');
            });

        })->export('xlsx');
    }

    public function getDetails($attendance_id)
    {
        $attendancedetails = Attendance::find($attendance_id)->attendancedetails;
        return view('attendance.partials.AttendanceDetails', compact('attendancedetails'));
    }
    
    
    function powerBiReport(){
        $list = (new \App\AttendanceReport)->newQuery()->get();
        return [
            'list'=>$list
        ];
    }
    
    



    // API
    function attendance_reports()
    {
        // return 'Okay Attendance';
        $reports = \App\AttendanceReport::orderBy('id', 'desc')->limit(4000)->get();
        $data = collect(['attendances' => $reports]);   return $data; 
    }


}
