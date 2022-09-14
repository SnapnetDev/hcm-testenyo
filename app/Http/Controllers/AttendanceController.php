<?php

namespace App\Http\Controllers;

use App\AttendanceDetail;
use App\AttendanceReport;
use App\FinancialReport;
use App\ShiftSwapRequest;
use App\UserDailyShift;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Traits\Attendance as AttendanceTrait;
use App\User;
use App\DailyAttendance;
use App\Attendance;
use App\Holiday;
use App\Setting;
use App\WorkingPeriod;
use App\Timesheet;
use App\TimesheetDetail;
use App\Shift;
use App\ShiftSchedule;
use App\UserShiftSchedule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use MaddHatter\LaravelFullcalendar\Facades\Calendar;
use App\ShiftSwap;

class AttendanceController extends Controller
{
    use AttendanceTrait;

    public function absenceManagement(Request $request)
    {
        if ($request->has('date')) {
            $date = Carbon::createFromFormat('m/d/Y', $request->date);
        } else {
            $date = Carbon::today();
        }
        $company_id = companyId();
        $users = User::where('company_id', $company_id)->get();

        $attendances = AttendanceReport::whereIn('user_id', $users->pluck('id')->toArray())->whereDate('date', $date->format('Y-m-d'))->with('user')->get();
        $earlys = $attendances->where('status', 'early')->count();
        $lates = $attendances->where('status', 'late')->count();
        $absentees = $attendances->where('status', 'absent')->count();
        $presents = $attendances->whereIn('status', ['early', 'late'])->count();
        $offs=UserDailyShift::whereIn('user_id', $users->pluck('id')->toArray())->whereDate('sdate',$date->format('Y-m-d'))->where('shift_id','7')->count();
        if ($request->type=='excel'){
            $view = 'attendance.new.excelattendanceReport';
            $name=$date->format('d M, Y').' report';
            return \Excel::create($name, function ($excel) use ($view, $attendances, $users, $lates, $earlys, $date, $absentees,$name) {

                $excel->sheet($name, function ($sheet) use ($view, $attendances, $users, $lates, $earlys, $date, $absentees) {
                    $sheet->loadView("$view", compact('attendances', 'lates', 'earlys', 'date', 'absentees'))
                        ->setOrientation('landscape');
                });

            })->export('xlsx');
        }
        return view('attendance.new.attendanceReport', compact('attendances', 'users', 'lates', 'earlys', 'absentees', 'date', 'presents','offs'));
    }
    /*
    public function monthlyAttendance(Request $request)
    {
        if ($request->has('date')) {
            $date = Carbon::createFromFormat('m-Y', $request->date);
        } else {
            $date = Carbon::today();
        }
        $company_id = companyId();
        $users = User::where('company_id', $company_id)->get();
        $finance=FinancialReport::where('month',$date->format('m'))->where('year',$date->format('Y'))->first();
        if ($finance){$exists='yes';}
        else{ $exists='no';}
        $allusers=[];
        foreach ($users as $user) {
            $user_attendances = AttendanceReport::where('user_id', $user->id)->whereYear('date', $date->format('Y'))->whereMonth('date', $date->format('m'))->get();
            if (count($user_attendances)>0){
                $user['total_hours'] = $user_attendances->whereIn('status', ['late', 'early'])->sum('hours_worked');
                $user['overtime_worked'] = $user_attendances->whereIn('status', ['late', 'early'])->sum('overtime');
                $user['earlys'] = $user_attendances->where('status', 'early')->count();
                $user['lates'] = $user_attendances->where('status', 'late')->count();
                $user['offs'] = $user_attendances->where('status', 'off')->count();
                $user['absents'] = $user_attendances->where('status', 'absent')->count();

                $present=$user['earlys']+ $user['lates'];
                $user['present'] = $present;
                $amount= $present*$user->role->daily_pay;
                $user['amount'] =  $amount;
                $allusers[]=$user;
            }
        }
        $users=$allusers;
        if ($request->type=='excel'){
            $view = 'attendance.new.excelmonthlyAttendanceReport';
            $name=$date->format('M, Y').' report';
            return \Excel::create($name, function ($excel) use ($view, $users, $date,$name) {

                $excel->sheet($name, function ($sheet) use ($view, $users, $date) {
                    $sheet->loadView("$view", compact('users', 'date'))
                        ->setOrientation('landscape');
                });

            })->export('xlsx');
        }
        //return $users;
        Setting::where('name','payroll_running')->update(['value'=>'no']);
        return view('attendance.new.monthlyAttendanceReport', compact('users', 'date','exists'));
    }
    */

    
    public function monthlyAttendance(Request $request)
    {
        if ($request->has('date')) {
            $date = Carbon::createFromFormat('m-Y', $request->date);
        } else {
            $date = Carbon::today();
        }
        $company_id = companyId();
        $users = User::where('company_id', $company_id)->get();
        $finance=FinancialReport::where('month',$date->format('m'))->where('year',$date->format('Y'))->first();
        if ($finance){$exists='yes';}
        else{ $exists='no';}
        $allusers=[];
        foreach ($users as $user) {
            $user_attendances = AttendanceReport::where('user_id', $user->id)->whereYear('date', $date->format('Y'))->whereMonth('date', $date->format('m'))->get();
            // if (count($user_attendances)>0){
                $user['total_hours'] = $user_attendances->whereIn('status', ['late', 'early'])->sum('hours_worked');
                $user['overtime_worked'] = $user_attendances->whereIn('status', ['late', 'early'])->sum('overtime');
                $user['earlys'] = $user_attendances->where('status', 'early')->count();
                $user['lates'] = $user_attendances->where('status', 'late')->count();
                $user['offs'] = $user_attendances->where('status', 'off')->count();
                $user['absents'] = $user_attendances->where('status', 'absent')->count();

                $present=$user['earlys']+ $user['lates'];
                $user['present'] = $present;
                $amount= $present*$user->role->daily_pay;
                $user['amount'] =  $amount;
                $allusers[]=$user;
                // array_push($allusers, $user);
                
            // }
        }
        $users=$allusers;
        if ($request->type=='excel'){
            $view = 'attendance.new.excelmonthlyAttendanceReport';
            $name=$date->format('M, Y').' report';
            return \Excel::create($name, function ($excel) use ($view, $users, $date,$name) {

                $excel->sheet($name, function ($sheet) use ($view, $users, $date) {
                    $sheet->loadView("$view", compact('users', 'date'))
                        ->setOrientation('landscape');
                });

            })->export('xlsx');
        }
        //return $users;
        Setting::where('name','payroll_running')->update(['value'=>'no']);
        return view('attendance.new.monthlyAttendanceReport', compact('users', 'date','exists'));
    }
    

    public function UserMonthlyAttendance($user, $date)
    {
        $date = Carbon::createFromFormat('Y-m-d', $date);
        $details = AttendanceReport::where('user_id', $user)->whereYear('date', $date->format('Y'))->whereMonth('date', $date->format('m'))->get();
        return view('attendance.new.partials.monthlyDetails', compact('details'));
    }

    public function employeesSchedule(Request $request)
    {
        $company_id = companyId();
        $users=User::where('company_id',$company_id)->pluck('id')->toArray();
        $date = date('Y-m-d', strtotime($request->date));
        $user_daily_shifts = \App\UserDailyShift::whereIn('user_id',$users)->where('sdate', $date)->get();
        return view('attendance.partials.dayScheduleDetails', compact('user_daily_shifts', 'date'));
    }

    public function exportShiftSchedule(Request $request)
    {

        $from = date('Y-m-d', strtotime($request->from));
        $to = date('Y-m-d', strtotime($request->to));
        $date = date('Y-m-d', strtotime($request->date));
        $user_daily_shifts = \App\UserDailyShift::whereBetween('sdate', [$from, $to])->get();
        return view('attendance.partials.dayScheduleDetails', compact('user_daily_shifts', 'date'));
    }


    public function userShiftSchedule($user_id)
    {
        $user=User::find($user_id);
        //$user = Auth::user();
        return view('attendance.userShiftSchedule', compact('user'));
    }

    public function myuserShiftSchedule()
    {
        $user = Auth::user();
        return view('attendance.userShiftSchedule', compact('user'));
    }

    public function myAttendanceCal()
    {
        $user_id = Auth::user()->id;
        $user = User::find($user_id);
        return view('attendance.userAttendance', compact('user'));
    }

    public function myShiftSchedule(Request $request)
    {
        $company_id = companyId();
        $user_id = Auth::user()->id;
        $user = User::find($user_id);
        //return $request->from;
        $user = Auth::user();
        if ($request->filled('from') && $request->filled('to')) {
            $start = Carbon::createFromFormat('m/d/Y', $request->from)->subDay();
            $end = Carbon::createFromFormat('m/d/Y', $request->to);
        } else {
            $start = Carbon::today()->subDay();
            $end = Carbon::today()->addDays(7);
        }
        $days = $end->diffInDays($start);
        for ($i = 0; $i <= $days; $i++) {
            $end->addDay();
            $dates[] = $end->format('Y-m-d');
        }
        $shifts = UserDailyShift::where('user_id', $user_id)->whereBetween('sdate', [$start, $end])->with('shift')->get();
        $start = Carbon::createFromFormat('Y-m-d', $dates[0])->format('m/d/Y');
        $end = Carbon::createFromFormat('Y-m-d', $dates[$days])->format('m/d/Y');
        $users = User::where('id', '!=', $user_id)->where('company_id', '=', $company_id)->get();
        return view('attendance.new.myShiftSchedule', compact('user', 'shifts', 'start', 'end', 'users'));
    }

    public function myAttendance(Request $request)
    {
        $user = Auth::user();
        if ($request->filled('from') && $request->filled('to')) {
            $start = Carbon::createFromFormat('m/d/Y', $request->from)->subDay();
            $end = Carbon::createFromFormat('m/d/Y', $request->to);
        } else {
            $end = Carbon::today()->subDay();
            $start = Carbon::today()->subDay(7);
        }
        //return $dates[2];
        $attendances = AttendanceReport::where('user_id', $user->id)->whereBetween('date', [$start->format('Y-m-d'), $end->format('Y-m-d')])->get();
        $start = $start->format('m/d/Y');
        $end = $end->format('m/d/Y');
        $staff = $user;
        if ($request->filled('type')) {
            if ($request->type == 'excel') {
                $name = 'Staff Report';
                $view = 'attendance.new.excelSpecificStaffAttendance';
                $this->exportToExcel($attendances, [], $view, $name);
            }
        }

        return view('attendance.new.userAttendance', compact('user', 'attendances', 'start', 'end'));
    }

    public function myAttendanceCalendar(Request $request, $user_id)
    {
        $user = User::find($user_id);
        $dispemp = [];

        $startdate = $request->start;
        $enddate = $request->end;
        $day_num = 0;

        $begin = new \DateTime($startdate);
        $end = new \DateTime($enddate . '+1 days');

        $interval = \DateInterval::createFromDateString('1 day');
        $period = new \DatePeriod($begin, $interval, $end);

        foreach ($period as $dt) {
            $attendance = Attendance::where(['emp_num' => $user->emp_num, 'date' => $dt->format(" Y-m-d")])->first();
            $isweekend = 0;
            $isholiday = 0;
            if ($attendance) {
                //return $attendance;
                //return AttendanceDetail::whereAttendance_id(7115)->get();
                $clock_in = $attendance->attendancedetails()->orderBy('clock_in', 'asc')->first()->clock_in;
                $clock_out = $attendance->attendancedetails()->orderBy('clock_out', 'desc')->first()->clock_out;
                $day_num = intval(date('N', strtotime($attendance->date)));
                if ($day_num >= 6) {
                    $isweekend = 1;
                }

                $isholiday = $this->checkHoliday($attendance->date);
                if ($isweekend == 1) {

                    $dispemp[] = [

                        'title' => 'Weekend',
                        'start' => ($clock_in) ? $attendance->date . 'T' . $clock_in : $attendance->date,
                        'end' => ($clock_out) ? $attendance->date . 'T' . $clock_out : $attendance->date,
                        'color' => '#67a8e4',
                        'id' => ($clock_in) ? $attendance->id : ''];
                } elseif ($isholiday == 1) {
                    $dispemp[] = [

                        'title' => 'Public Holiday',
                        'start' => ($clock_in) ? $attendance->date . 'T' . $clock_in : $attendance->date,
                        'end' => ($clock_out) ? $attendance->date . 'T' . $clock_out : $attendance->date,
                        'color' => '#67a8e4',
                        'id' => ($clock_in) ? $attendance->id : ''];
                } elseif ($clock_in) {
                    $dispemp[] = [

                        'title' => 'Present',
                        'start' => $attendance->date . 'T' . $clock_in,
                        'end' => $attendance->date . 'T' . $clock_out,
                        'color' => '#3aac76',
                        'id' => ($clock_in) ? $attendance->id : ''];
                } else {
                    $dispemp[] = [

                        'title' => 'Absent',
                        'start' => $attendance->date,
                        'end' => $attendance->date,
                        'color' => '#be3030',
                        'id' => ''];
                }

            } else {
                $dispemp[] = [

                    'title' => 'Absent',
                    'start' => $dt->format(" Y-m-d"),
                    'end' => $dt->format(" Y-m-d"),
                    'color' => '#be3030',
                    'id' => ''];

            }


        }

        if (isset($dispemp)):
            return response()->json($dispemp);
        else:
            $dispemp = ['title' => 'Nil', 'start' => '2018-09-09'];
            return response()->json($dispemp);
        endif;
    }
    public function shiftUploadedCalendar(Request $request)
    {
        $company_id=companyId();
        $shifts=Shift::whereIn('company_id',[$company_id])->pluck('id')->toArray();
        $dispemp = [];
        $startdate = $request->start;
        $enddate = $request->end;
        $begin = new \DateTime($startdate);
        $end = new \DateTime($enddate . '+1 days');
        $interval = \DateInterval::createFromDateString('1 day');
        $period = new \DatePeriod($begin, $interval, $end);
        foreach ($period as $dt) {
            $date=$dt->format("Y-m-d");
            $exists=UserDailyShift::where('sdate',$dt->format(" Y-m-d"))->whereIn('shift_id',$shifts)->first();
            $color = '#3aac76';//green
            $message = 'Shift Uploaded. Click to view';
            if (!$exists) {
                $color='#be3030';//red
                $message = 'No shift Uploaded yet';
            }
            $dispemp[] = [
                'title' => $message,
                'start' => $date,
                'end' => $date,
                'color' =>$color,
                'id' => ''
            ];
        }
        return response()->json($dispemp);
    }

    public function userShiftScheduleCalendar(Request $request, $user_id)
    {
        $user = User::find($user_id);
        $dispemp = [];
        $startdate = $request->start;
        $enddate = $request->end;
        $from = date('Y-m-d', strtotime($request->from));
        $to = date('Y-m-d', strtotime($request->to));

        $shift_schedules = UserDailyShift::where('user_id', $user->id)->whereBetween('sdate', [$startdate, $enddate])->get();
        $colours = ['#67a8e4', '#f32f53', '#77c949', '#FFC1CC', '#ffbb44', '#f32f53', '#67a8e4', '#67a8e4'];
        $i = 0;
        foreach ($shift_schedules as $shift_schedule) {
            $begin = new \DateTime($shift_schedule->starts);
            $end = new \DateTime($shift_schedule->starts . '+1 days');
            $col = $colours[0];
            $interval = \DateInterval::createFromDateString('1 day');
            $period = new \DatePeriod($begin, $interval, $end);

            foreach ($period as $dt) {

                $dispemp[] = [
                    'title' => $shift_schedule->shift->type,
                    'start' => $dt->format(" Y-m-d") . 'T' . $shift_schedule->shift->start_time,
                    'end' => $dt->format(" Y-m-d") . 'T' . $shift_schedule->shift->end_time,
                    'color' => $col,
                    'id' => $shift_schedule->id];
            }

            $i++;
        }

        if (isset($dispemp)):
            return response()->json($dispemp);
        else:
            $dispemp = ['title' => 'Nil', 'start' => '2016-09-09'];
            return response()->json($dispemp);
        endif;


    }

    public function userShiftScheduleDetails(Request $request, $id)
    {
        $user_shift_schedule = UserDailyShift::find($id);
        $company_id = companyId();
        $users = User::where('id', '!=', Auth::user()->id)->where('company_id', '=', $company_id)->get();
        return response()->json(['users' => $users,]);
    }

    public function swapShift(Request $request)
    {
        $user_daily_shift = UserDailyShift::find($request->user_daily_shift_id);
        $shiftSwap = ShiftSwapRequest::where(['owner_id' => Auth::user()->id, 'user_daily_shift_id' => $request->user_daily_shift_id, 'status' => 0])->first();
        if ($shiftSwap) {
            return response()->json('exists', 200);
        } else {
            $swapper_daily_shift = UserDailyShift::where('sdate', $user_daily_shift->sdate)->where('user_id', $request->swapper_id)->first();
            if ($swapper_daily_shift) {
                ShiftSwapRequest::create(['owner_id' => Auth::user()->id, 'swapper_id' => $request->swapper_id, 'approved_by' => 0,
                    'user_daily_shift_id' => $request->user_daily_shift_id,
                    'status' => 0, 'reason' => $request->reason, 'new_shift_id' => $swapper_daily_shift->shift_id, 'date' => $user_daily_shift->sdate]);
                return response()->json('success', 200);
            } else {
                return response()->json('noshift', 200);
            }
        }


    }

    public function myShiftSwaps()
    {
        $initiatedShiftSwaps = ShiftSwapRequest::where(['owner_id' => Auth::user()->id])->get();
        $suggestedShiftSwaps = ShiftSwapRequest::where(['swapper_id' => Auth::user()->id])->get();
        return view('attendance.myShiftSwaps', compact('initiatedShiftSwaps', 'suggestedShiftSwaps'));
    }

    public function shiftSwaps()
    {
        $shiftswaps = ShiftSwapRequest::all();
        return view('attendance.shiftSwaps', compact('shiftswaps'));
    }

    public function cancelShiftSwap($shiftSwap_id)
    {
        $shiftSwap = ShiftSwapRequest::where(['owner_id' => Auth::user()->id, 'id' => $shiftSwap_id, 'status' => 0]);
        if ($shiftSwap) {
            $shiftSwap->delete();
            return response()->json('success', 200);
        } else {
            return response()->json('denied', 200);
        }
    }

    public function rejectShiftSwaps($shiftSwap_id)
    {
        $shiftSwap = ShiftSwapRequest::where(['id' => $shiftSwap_id, 'status' => 0]);
        if ($shiftSwap) {
            $shiftSwap->update(['status' => 2, 'approved_by' => Auth::user()->id]);
            return response()->json('success', 200);
        } else {
            return response()->json('denied', 200);
        }
    }

    public function approveShiftSwaps($shiftSwap_id)
    {
        $shiftSwap = ShiftSwapRequest::where(['id' => $shiftSwap_id, 'status' => 0])->first();
        if ($shiftSwap) {
            ShiftSwapRequest::where('id', $shiftSwap->id)->update(['status' => 1, 'approved_by' => Auth::user()->id]);

            $user_shift = UserDailyShift::where('id', $shiftSwap->user_daily_shift_id)->first();
            $swapper_shift = UserDailyShift::where('user_id', $shiftSwap->swapper_id)->where('sdate', $user_shift->sdate)->first();

            $user_shift_id = $user_shift->shift_id;
            $swapper_shift_id = $swapper_shift->shift_id;
            //change applier shift
            $shift = \App\Shift::where('id', $swapper_shift_id)->first();
            if ($shift) {
                $sd = date('Y-m-d', strtotime($user_shift->sdate));
                $sdt = date('Y-m-d H:i:s', strtotime($sd . $shift->start_time));
                if ($shift->start_time > $shift->end_time) {
                    $edt = date('Y-m-d H:i:s', strtotime($sd . $shift->end_time . '+1 day'));
                } else {
                    $edt = date('Y-m-d H:i:s', strtotime($sd . $shift->end_time));
                }
                UserDailyShift::where('id', $user_shift->id)->update(['shift_id' => $shift->id, 'starts' => $sdt, 'ends' => $edt, 'sdate' => $sd]);
            }

            //change swapper shift
            $shift = \App\Shift::where('id', $user_shift_id)->first();
            if ($shift) {
                $sd = date('Y-m-d', strtotime($swapper_shift->sdate));
                $sdt = date('Y-m-d H:i:s', strtotime($sd . $shift->start_time));
                if ($shift->start_time > $shift->end_time) {
                    $edt = date('Y-m-d H:i:s', strtotime($sd . $shift->end_time . '+1 day'));
                } else {
                    $edt = date('Y-m-d H:i:s', strtotime($sd . $shift->end_time));
                }
                UserDailyShift::where('id', $swapper_shift->id)->update(['shift_id' => $shift->id, 'starts' => $sdt, 'ends' => $edt, 'sdate' => $sd]);
            }


            $new_swapper_shift_id = $user_shift->shift_id;
            return response()->json('success', 200);
        } else {
            return response()->json('denied', 200);
        }
    }

    public function shift_schedules()
    {
        $shift_schedules = ShiftSchedule::all();
        return view('attendance.shiftSchedules', compact('shift_schedules'));
    }

    public function shift_schedule_details($shift_schedule_id)
    {
        $shift_schedule = ShiftSchedule::find($shift_schedule_id);

        return view('attendance.viewShiftSchedule', compact('shift_schedule'));
    }

    public function schedule_shift(Request $request)
    {
        $startdate = $request->startdate;
        $enddate = $request->enddate;
        $company_id = companyId();
        $users = User::where('company_id', '=', $company_id)->get();
        $shifts = Shift::all()->toArray();
        $shifts_count = Shift::all()->count();
        $schedule_exists = ShiftSchedule::whereBetween('start_date', [date('Y-m-d', strtotime($startdate)), date('Y-m-d', strtotime($enddate))])->count();
        if ($schedule_exists > 0) {
            return 'exists';
        }
        $shiftadd = 0;
        $last_shift_schedule = ShiftSchedule::latest()->first();
        $shift_schedule = new ShiftSchedule();
        $shift_schedule->start_date = date('Y-m-d', strtotime($startdate));
        $shift_schedule->end_date = date('Y-m-d', strtotime($enddate));
        $shift_schedule->save();
        if ($last_shift_schedule) {
            foreach ($users as $user) {
                $last_user_shift_schedule = $user->usershiftschedules()->latest()->first();
                $shift_index = array_search($last_user_shift_schedule->shift_id, $shifts);
                if ($last_user_shift_schedule->shift_id == $shifts[$shiftadd]['id'] && $shift_index == $shifts_count - 1) {
                    $user->shifts()->attach($shifts[0]['id'], ['shift_schedule_id' => $shift_schedule->id, 'created_at' => date('Y-m-d h:i:s'), 'updated_at' => date('Y-m-d h:i:s')]);
                } elseif ($last_user_shift_schedule->shift_id == $shifts[$shiftadd]['id'] && $shift_index < $shifts_count - 1) {
                    $user->shifts()->attach($shifts[$shift_index + 1]['id'], ['shift_schedule_id' => $shift_schedule->id, 'created_at' => date('Y-m-d h:i:s'), 'updated_at' => date('Y-m-d h:i:s')]);
                } else {
                    $user->shifts()->attach($shifts[$shift_index + 1]['id'], ['shift_schedule_id' => $shift_schedule->id, 'created_at' => date('Y-m-d h:i:s'), 'updated_at' => date('Y-m-d h:i:s')]);
                }

                // $shiftadd++;
                if ($shiftadd == $shifts_count - 1) {
                    $shiftadd = 0;
                } else {
                    $shiftadd++;
                }

            }
        } else {
            foreach ($users as $user) {

                $user->shifts()->attach($shifts[$shiftadd]['id'], ['shift_schedule_id' => $shift_schedule->id]);
                if ($shiftadd == $shifts_count - 1) {
                    $shiftadd = 0;
                } else {
                    $shiftadd++;
                }

            }

        }


        return 'success';
    }

    public function queueTimesheet($month = 0, $year = 0)
    {
        // \Artisan::queue('timesheet:month',['month' => $month,'year'=>$year, '--queue' => 'default']);
        \Artisan::call('queue:work');
        return redirect('timesheets');
    }

    public function timesheetExcel($timesheet_id)
    {
        $timesheet = Timesheet::find($timesheet_id);
        $holidays = Holiday::whereMonth('date', $timesheet->month)->whereYear('date', $timesheet->year)->get();
        $view = 'attendance.exceltimesheet';
        $this->exportToExcel($timesheet, $holidays, $view);

    }

    private function exportToExcel($datas, $holidays, $view)
    {
        return \Excel::create("$view", function ($excel) use ($datas, $view, $holidays) {

            $excel->sheet("$view", function ($sheet) use ($datas, $view, $holidays) {
                $sheet->loadView("$view", compact("datas", "holidays"))
                    ->setOrientation('landscape');
            });

        })->export('xlsx');
    }
    private function exportToExcelNew($datas, $view, $name)
    {
        return \Excel::create("$name", function ($excel) use ($datas, $view, $name) {

            $excel->sheet("$name", function ($sheet) use ($datas, $view) {
                $sheet->loadView("$view", compact("datas"))
                    ->setOrientation('landscape');
            });

        })->export('xlsx');
    }


    public function timesheets()
    {
        $timesheets = Timesheet::all();
        return view('attendance.timesheet', compact('timesheets'));
    }

    public function timesheetDetail($timesheet_id)
    {
        $timesheet = Timesheet::find($timesheet_id);
        return view('attendance.timesheetdetails', compact('timesheet'));
    }

    public function userTimesheetDetail($user_id)
    {
        // $user=User::find($user_id);
        $detail = TimesheetDetail::where('user_id', $user_id)->get()->first();
        return view('attendance.partials.userTimesheetDetails', compact('detail'));
    }

    public function getDetails($attendance_id)
    {
        $attendancedetails = Attendance::find($attendance_id)->attendancedetails;
        return view('attendance.partials.attendanceDetails', compact('attendancedetails'));
    }

    public function getWorkingDays(Request $request)
    {
        $timesheet = [];
        $tdays = [];
        $company_id = companyId();
        $users = User::where('company_id', '=', $company_id)->get();

        $count = $users->count();
        $days = cal_days_in_month(CAL_GREGORIAN, $request->month, $request->year);
        $total_hours = 0;
        foreach ($users as $user) {

            $monthHours = $this->getMonthHours($user->emp_num, $request->month, $request->year);
            $weekdayHours = $this->getWeekdayHours($user->emp_num, $request->month, $request->year);
            $basicWorkHours = $this->getBasicWorkHours($user->emp_num, $request->month, $request->year);
            $overtimeWeekdayHours = $this->getOvertimeWeekdayHours($user->emp_num, $request->month, $request->year);
            $overtimeSaturdaysHours = $this->getOvertimeSaturdaysHours($user->emp_num, $request->month, $request->year);
            $overtimeSundaysHours = $this->getOvertimeSundaysHours($user->emp_num, $request->month, $request->year);
            $overtimeHolidaysHours = $this->getOvertimeHolidayHours($user->emp_num, $request->month, $request->year);
            $expectedworkhours = $this->getExpectedHours($user->emp_num, $request->month, $request->year);
            $expectedworkdays = $this->getExpectedDays($user->emp_num, $request->month, $request->year);
            $timesheet[$user->id]['sn'] = $count;
            $timesheet[$user->id]['badge_no'] = $user->emp_num;
            $timesheet[$user->id]['name'] = $user->name;
            $timesheet[$user->id]['position'] = $user->position->name;
            $timesheet[$user->id]['staff_location'] = $user->position->name;
            $timesheet[$user->id]['category'] = $user->position->name;
            $timesheet[$user->id]['employee_type'] = $user->position->name;
            // $timesheet[$user->id]['cost_center']=$user->cost_center->code;
            for ($i = 1; $i <= $days; $i++) {

                $timesheet[$user->id][$i] = $this->getDayHours($user->emp_num, $request->year . '-' . $request->month . '-' . $i);
                $tdays[$user->id][$i] = $timesheet[$user->id][$i];
                $total_hours += $timesheet[$user->id][$i];
            }
            $timesheet[$user->id]['total_hours'] = $total_hours;
            $timesheet[$user->id]['weekdayHours'] = $weekdayHours;
            $timesheet[$user->id]['basicWorkHours'] = $basicWorkHours;
            $timesheet[$user->id]['overtimeWeekdayHours'] = $overtimeWeekdayHours;
            $timesheet[$user->id]['overtimeSaturdaysHours'] = $overtimeSaturdaysHours;
            $timesheet[$user->id]['overtimeHolidaysHours'] = $overtimeHolidaysHours;
            $timesheet[$user->id]['overtimeSundaysHours'] = $overtimeSundaysHours;
            $timesheet[$user->id]['monthHours'] = $monthHours;
            $timesheet[$user->id]['expectedworkhours'] = $expectedworkhours;
            $timesheet[$user->id]['expectedworkdays'] = $expectedworkdays;
            $timesheet[$user->id]['test'] = $this->testTime("$request->year-$request->month-25");


        }
        return $timesheet;
        // $attendances = Attendance::where('emp_num'=>$request->emp_num)
        // 			->whereMonth('created_at', '7')
        // 			 ->whereYear('created_at', '2018')
        // 			 ->get();
        //  foreach ($attendances as $attendance) {
        //  	$attendance->attendancedetails;
        //  }
    }

    public function getDayHours($emp_num, $date)
    {
        $wp = WorkingPeriod::all()->first();
        $hours = 0;
        $diff = 0;
        $time = '';
        // dd($date);
        $attendance = Attendance::has('attendancedetails')->whereDate('date', $date)->where('emp_num', $emp_num)->first();

        if ($attendance) {
            $details = \App\AttendanceDetail::whereHas('attendance', function ($query) use ($emp_num, $date) {
                $query->whereDate('date', $date)->where('emp_num', $emp_num);
            })->orderBy('id', 'asc')->get();
            // $details=$attendance->attendancedetails->orderBy('id','desc');
            // $acount=$attendance->attendancedetails->count();
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



    public function getMonthHours($emp_num, $month, $year)
    {
        $total_hours = 0;
        $days = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        for ($i = 1; $i <= $days; $i++) {
            $total_hours += $this->getDayHours($emp_num, $year . '-' . $month . '-' . $i);

        }
        return $total_hours;
    }

    public function getCustMonthHours($user_id, $month, $year)
    {
        $total_hours = 0;
        $days = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        for ($i = 1; $i <= $days; $i++) {
            $total_hours += $this->getCustDayHours($user_id, $year . '-' . $month . '-' . $i);

        }
        return $total_hours;
    }

    public function getWeekdayHours($emp_num, $month, $year)
    {
        $total_hours = 0;
        $days = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        for ($i = 1; $i <= $days; $i++) {
            if (date('N', strtotime("$year-$month-$i")) < 6 && $this->checkHoliday("$year-$month-$i") == false) {
                $total_hours += $this->getDayHours($emp_num, $year . '-' . $month . '-' . $i);
            }


        }
        return $total_hours;

    }

    public function getCustWeekdayHours($user_id, $month, $year)
    {
        $total_hours = 0;
        $days = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        for ($i = 1; $i <= $days; $i++) {
            if (date('N', strtotime("$year-$month-$i")) < 6 && $this->checkHoliday("$year-$month-$i") == false) {
                $total_hours += $this->getCustDayHours($user_id, $year . '-' . $month . '-' . $i);
            }


        }
        return $total_hours;

    }

    public function getBasicWorkHours($emp_num, $month, $year)
    {
        $wp = WorkingPeriod::all()->first();
        $expectedworkhours = $this->get_time_difference($wp->sob, $wp->cob) - 1;
        $total_hours = 0;
        $days = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        // return $expectedworkhours;
        for ($i = 1; $i <= $days; $i++) {
            if (date('N', strtotime("$year-$month-$i")) < 6 && $this->checkHoliday("$year-$month-$i") == false) {
                if ($this->getDayHours($emp_num, "$year-$month-$i") >= $expectedworkhours) {
                    $total_hours += $expectedworkhours;
                } else {
                    $total_hours += $this->getDayHours($emp_num, $year . '-' . $month . '-' . $i);
                }
            }


        }
        return $total_hours;

    }

    public function getOvertimeWeekdayHours($emp_num, $month, $year)
    {
        $wp = WorkingPeriod::all()->first();
        $expectedworkhours = $this->get_time_difference($wp->sob, $wp->cob) - 1;
        $total_hours = 0;
        $days = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        for ($i = 1; $i <= $days; $i++) {
            if (date('N', strtotime("$year-$month-$i")) < 6 && $this->checkHoliday("$year-$month-$i") == false) {
                if ($this->getDayHours($emp_num, "$year-$month-$i") > $expectedworkhours) {
                    $total_hours += $this->getDayHours($emp_num, "$year-$month-$i") - $expectedworkhours;
                }
            }


        }
        return $total_hours;
    }

    public function getOvertimeSaturdaysHours($emp_num, $month, $year)
    {
        $total_hours = 0;
        $days = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        for ($i = 1; $i <= $days; $i++) {
            if (date('N', strtotime("$year-$month-$i")) == 6 && $this->checkHoliday("$year-$month-$i") == false) {
                $total_hours += $this->getDayHours($emp_num, $year . '-' . $month . '-' . $i);
            }


        }
        return $total_hours;
    }

    public function getOvertimeSundaysHours($emp_num, $month, $year)
    {
        $total_hours = 0;
        $days = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        for ($i = 1; $i <= $days; $i++) {
            if (date('N', strtotime("$year-$month-$i")) == 7 && $this->checkHoliday("$year-$month-$i") == false) {
                $total_hours += $this->getDayHours($emp_num, $year . '-' . $month . '-' . $i);
            }


        }
        return $total_hours;
    }

    public function getOvertimeHolidayHours($emp_num, $month, $year)
    {

        $total_hours = 0;
        $days = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        for ($i = 1; $i <= $days; $i++) {
            if ($this->checkHoliday($year . '-' . $month . '-' . $i) == true) {
                $total_hours += $this->getDayHours($emp_num, $year . '-' . $month . '-' . $i);
            }


        }
        return $total_hours;
    }

    public function getExpectedHours($emp_num, $month, $year)
    {
        $wp = WorkingPeriod::all()->first();
        $expectedworkhours = $this->get_time_difference($wp->sob, $wp->cob) - 1;
        $total_hours = 0;
        $days = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        // return $expectedworkhours;
        for ($i = 1; $i <= $days; $i++) {
            if (date('N', strtotime("$year-$month-$i")) < 6 && $this->checkHoliday("$year-$month-$i") == false) {

                $total_hours += $expectedworkhours;

            }


        }
        return $total_hours;
    }

    public function getExpectedDays($emp_num, $month, $year)
    {
        $wp = WorkingPeriod::all()->first();
        // $expectedworkhours=$this->get_time_difference($wp->sob, $wp->cob)-1;
        $total_days = 0;
        $days = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        // return $expectedworkhours;
        for ($i = 1; $i <= $days; $i++) {
            if (date('N', strtotime("$year-$month-$i")) < 6 && $this->checkHoliday("$year-$month-$i") == false) {

                $total_days++;

            }


        }
        return $total_days;
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

    public function time_diff($time1, $time2)
    {
        $time1 = strtotime("1/1/2018 $time1");
        $time2 = strtotime("1/1/2018 $time2");

        // if ($time2 < $time1)
        // {
        // 	$time2 = $time2 + 86400;
        // }
        // $to = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', '2015-05-06 $time1');
        // $from = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', '2015-05-06 $time2');


        // return $diff_in_minutes = $to->diffInMinutes($from);

        return ($time2 - $time1) / 3600;
    }

    public function checkHoliday($date)
    {
        $has_holiday = Holiday::whereDate('date', $date)->first();
        $retVal = ($has_holiday) ? true : false;
        return $retVal;
    }

    public function testTime($date)
    {
        $wp = WorkingPeriod::all()->first();
        $hours = 0;
        $diff = 0;
        $time = '';
        // dd($date);
        $attendance = Attendance::whereDate('date', $date)->first();
        if ($attendance) {
            $details = \App\AttendanceDetail::whereHas('attendance', function ($query) use ($date) {
                $query->whereDate('date', $date);
            })->orderBy('id', 'desc')->get();
            // $details=$attendance->attendancedetails->orderBy('id','desc');
            // $acount=$attendance->attendancedetails->count();
            $time = $details->first()->clock_in;
            foreach ($details as $detail) {
                $hours += $this->get_time_difference($detail->clock_in, $detail->clock_out);
            }
            $diff = $this->get_time_difference($time, $wp->sob);


        }

        return $diff;
    }

    public function viewAttendanceCalendar($value = '')
    {
        return view('xtrafeature.attendance360');
    }

    public function displayCalendar(Request $request)
    {
        try {
            $attendances = DailyAttendance::whereBetween('date', [$request->start, $request->end])->get();


            $emps = \DB::table('users')
                ->join('daily_attendance', 'users.emp_num', '=', 'daily_attendance.emp_num')
                ->select('users.name', 'daily_attendance.clock_in as startdate')
                ->whereBetween('daily_attendance.date', [$request->start, $request->end])
                ->get();

            foreach ($emps as $empres):

                $dispemp[] = ['title' => $empres->name, 'start' => $empres->startdate];

            endforeach;
            if (isset($dispemp)):
                return response()->json($dispemp);
            else:
                $dispemp = ['title' => 'Nil', 'start' => '2016-09-09'];
                return response()->json($dispemp);
            endif;

        } catch (\Exception $ex) {

            return response()->json("Error:$ex");
        }
    }

    public function saveAttendanceOld(Request $request)
    {
        $user = User::where(['emp_num' => $request->empnum])->first();
        if ($user) {
            $attendance = \App\CustAttendance::firstOrCreate(['user_id' => $user->id, 'clocktime' => date('H:i:s', strtotime($request->time)),
                'datetime' => date('Y-m-d H:i:s', strtotime($request->time))]);
        }
        return "success";
    }

    public function saveAttendance(Request $request)
    {
        $user = User::where(['emp_num' => $request->empnum])->first();
        $user_shift = UserDailyShift::where('user_id', $user->id)->where('sdate', date('Y-m-d', strtotime($request->time)))->first();
        if ($user_shift) {
            $shift = $user_shift->id;
        } else {
            $shift = 0;
        }
        $attendance = Attendance::firstOrCreate(['date' => date('Y-m-d', strtotime($request->time)), 'user_daily_shift_id' => $shift,
            'emp_num' => $request->empnum]);
        if ($request->type == 1) {
            AttendanceDetail::create(['clock_in' => date('H:i:s', strtotime($request->time))]);
        } elseif ($request->type == 0) {
            $ad = $attendance->attendancedetails()->latest()->first();
            if ($ad) {
                $ad->update(['clock_out' => date('H:i:s', strtotime($request->time))]);
            } else {   //no clockin but there is clock out, clockin and clock out is thesame time
                AttendanceDetail::create(['clock_in' => date('H:i:s', strtotime($request->time)),
                    'clock_out' => date('H:i:s', strtotime($request->time))]);
            }
        }
        return "success";
    }

    public function employeeShiftSchedules(Request $request)
    {
        $today=Carbon::today();
        return view('attendance.empShiftSchedules',compact('today'));
    }

    public function downloadShiftUploadTemplate(Request $request)
    {
        $heading = ['Employee Name', 'Staff ID'];
        if ($request->filled('from')) {
            $date = Carbon::createFromFormat('m/d/Y', $request->from);
        } else {
            $date = Carbon::today();
        }
        for ($i = 1; $i <= 14; $i++) {
            $form = $date->format('m/d/Y');
            $date = $date->addDay();
            $heading[] = $form;
        }
        //return $heading;
        $company_id = companyId();
        $users = \App\User::where('status','1')->where('company_id',$company_id)->select('emp_num as Staff Id', 'name as Employee Name')->get()->toArray();
        $shifts = \App\Shift::where('company_id',$company_id)->orwhere('company_id','1')->select('type as Shift Name', 'start_time as Starts', 'end_time as Ends', 'id as Shift ID')->get()->toArray();

        /*$sendshift = \App\Shift::where('company_id',$company_id)->orwhere('company_id','1')->with('shift_type')->get();
         $shifts=[];
         foreach ($sendshift as $shift){
             $sh['Shift Name']=$shift->shift_type->name;
             //$sh['Shift Name']=$shift->type;
             $sh['Starts']=$shift->start_time;
             $sh['Ends']=$shift->end_time;
             $sh['Shift ID']=$shift->id;

             $shifts[]=$sh;
         }*/
        //return $shifts;


        return $this->exportshiftexcel('template', [/*'template' => $template, */
            'users' => $users, 'shifts' => $shifts], $heading);
    }

    private function exportshiftexcel($worksheetname, $data, $heading)
    {
        return \Excel::create($worksheetname, function ($excel) use ($data, $heading) {
            foreach ($data as $sheetname => $realdata) {
                $excel->sheet($sheetname, function ($sheet) use ($realdata, $sheetname, $heading) {

                    if ($sheetname == 'users') {
                        $sheet->row(1, $heading);
                    }

                    $sheet->fromArray($realdata);

                    if ($sheetname == 'shifts') {
                        $sheet->_parent->addNamedRange(
                            new \PHPExcel_NamedRange(
                                'sdf', $sheet->_parent->getSheet(1), "A2:A" . $sheet->_parent->getSheet(1)->getHighestRow()
                            )
                        );

                        for ($j = 2; $j <= 200; $j++) {

                            foreach (range('C','P') as $letter){
                                $objValidation = $sheet->_parent->getSheet(0)->getCell("$letter$j")->getDataValidation();
                                $objValidation->setType(\PHPExcel_Cell_DataValidation::TYPE_LIST);
                                $objValidation->setErrorStyle(\PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
                                $objValidation->setAllowBlank(false);
                                $objValidation->setShowInputMessage(true);
                                $objValidation->setShowErrorMessage(true);
                                $objValidation->setShowDropDown(true);
                                $objValidation->setErrorTitle('Input error');
                                $objValidation->setError('Value is not in list.');
                                $objValidation->setPromptTitle('Pick Shift ID from list');
                                $objValidation->setPrompt('Please pick a value from the drop-down list.');
                                $objValidation->setFormula1('sdf');
                            }
                        }
                    }

                });
            }
        })->download('xlsx');
    }


    public function importUserShifts(Request $request)
    {
        $company_id = companyId();
        if ($request->hasFile('template')) 
        {
            $datas = \Excel::load($request->file('template')->getRealPath(), function ($reader) 
            {
                $reader->noHeading();
                // $reader->formatDates(true, 'Y-m-d');
            })->get();    //return $datas;
            $dates = [];
            $user = '';
            foreach ($datas[0] as $dkey => $data) 
            {
                if ($dkey == 0) 
                {
                    foreach ($data as $key => $value) 
                    {
                        if ($key != 0) 
                        {
                            $dates[$key] = $value;
                        }
                    }
                } 
                else
                {
                    foreach ($data as $key => $value) 
                    {
                        if ($key == 0) 
                        {
                            $user = \App\User::where('emp_num', $value)->first();
                        } 
                        else 
                        {
                            $value = trim($value, " ");
                            return $shift = \App\Shift::where('type', $value)->whereIn('company_id', $company_id)->first();

                            $shift = \App\Shift::where('type', $value)->whereIn('company_id',[$company_id, 1])->first();
                            if ($shift) 
                            {
                                $sd = date('Y-m-d', strtotime($dates[$key]));
                                $sdt = date('Y-m-d H:i:s', strtotime($sd . $shift->start_time));
                                if ($shift->start_time > $shift->end_time) 
                                {
                                    $edt = date('Y-m-d H:i:s', strtotime($sd . $shift->end_time . '+1 day'));
                                } 
                                else 
                                {
                                    $edt = date('Y-m-d H:i:s', strtotime($sd . $shift->end_time));
                                }
                                // if ($user) 
                                // {
                                //     $user_shift = \App\UserDailyShift::updateOrCreate(['user_id' => $user->id, 'sdate' => $sd], ['user_id' => $user->id, 'shift_id' => $shift->id, 'starts' => $sdt, 'ends' => $edt, 'sdate' => $sd]);
                                // }


                                if ($user) 
                                {
                                    $user_shift = \App\UserDailyShift::updateOrCreate(['user_id' => $user->id, 'sdate' => $sd], ['user_id' => $user->id, 'shift_id' => $shift->id, 'starts' => $sdt, 'ends' => $edt, 'sdate' => $sd]);
                                    $attendance=\App\Attendance::where(['date'=>$dates[$key],'emp_num'=>$user->id])->first();
                                    if($attendance)
                                    {
                                        ProcessSingleAttendanceJob::dispatch($attendance->id);
                                    }
                                }

                            }

                        }
                    }
                }

            }
        }
        return 'success';
    }


    public function appScheduleShift(Request $request){
        $company_id=companyId();
        $date=Carbon::today();
        $daterange=Carbon::today();
        $days=7;
        if (Auth::user()->role->manages == 'dr'){
            $users = User::where('company_id', $company_id)->whereIn('status',[0,1])
                ->whereHas('user.managers',function($query){
                    $query->where('manager_id',Auth::id());});
        }
        elseif(Auth::user()->role->manages == 'all'){
            $users = User::where('company_id', $company_id)->whereIn('status',[0,1]);
        }
        else{
            $users = User::where('company_id', $company_id)->whereIn('status',[0,1]);
        }
        if ($request->filled('date')){
            $date=Carbon::parse($request->date);
            $daterange=Carbon::parse($request->date);
        }
        if ($request->filled('days')){
            $days=$request->days;
        }
        for ($i = 1; $i <=$days ; $i++) {
            $form = $daterange->format('Y-m-d');
            $daterange = $daterange->addDay();
            $dates[] = $form;
        }
        $users=$users->get();
        $users_shifts=UserDailyShift::whereIn('user_id',$users->pluck('id')->toArray())->whereIn('sdate',$dates)->with('shift')->get();
        $shifts=Shift::whereIn('company_id',[$company_id,1])->get();
        if ($request->filled('type')){
            $view = 'attendance.new.excelShiftScheduleReport';
            $special='yes';
            $name='Shift Schedule report';
            return \Excel::create($name, function ($excel) use ($view, $users, $users_shifts,$special, $dates,$name) {

                $excel->sheet($name, function ($sheet) use ($view, $users, $users_shifts,$special, $dates) {
                    $sheet->loadView("$view", compact('users', 'users_shifts', 'special', 'dates'))
                        ->setOrientation('landscape');
                });
            })->export('xlsx');
        }
        return view('attendance.new.app_schedule_shift',compact('date','users','dates','users_shifts','shifts'));
    }

    public function appScheduleShiftSubmit(Request $request){
        //return $request->all();
        $company_id=companyId();
        foreach ($request->shift as $key => $users_shifts) {
            $user_id = $key;
            $user = \App\User::where('id', $user_id)->first();
            if ($user) {
                foreach ($users_shifts as $key2 => $shift_id) {
                    if (isset($shift_id)) {
                        $date = $key2;
                        $shift = \App\Shift::where('id', $shift_id)->whereIn('company_id', [$company_id, 1])->first();
                        if ($shift) {
                            if ($shift->start_time > $shift->end_time) {
                                $ends = date('Y-m-d H:i:s', strtotime($date . $shift->end_time . '+1 day'));
                            } else {
                                $ends = date('Y-m-d H:i:s', strtotime($date . $shift->end_time));
                            }
                            $starts = date('Y-m-d H:i:s', strtotime($date . $shift->start_time));
                            \App\UserDailyShift::updateOrCreate(['user_id' => $user->id, 'sdate' => $date],
                                ['user_id' => $user->id, 'shift_id' => $shift->id, 'starts' => $starts, 'ends' => $ends, 'sdate' => $date]);
                        }
                    }
                }
            }
        }

        return 'success';
    }
}
