<?php

namespace App\Traits;

use App\Notifications\ApproveLeaveRequest;
use App\Notifications\LeaveRequestApproved;
use App\Notifications\LeaveRequestPassedStage;
use App\Notifications\LeaveRequestRejected;
use App\Leave;
use App\LeaveRequest;
use App\LeaveApproval;
use App\LeavePolicy;
use App\Holiday;
use App\Setting;
use App\Workflow;
use App\Stage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\User;

use Auth;

/**
 *
 */
trait LeaveTrait
{

    public $allowed = ['JPG', 'PNG', 'jpeg', 'png', 'gif', 'jpg', 'pdf'];

    public function processGet($route, Request $request)
    {
        switch ($route) {
            case 'myrequests':
                return $this->myRequests($request);
                break;
            case 'get_request':
                return $this->getRequest($request);
                break;
            case 'view_requests':
                return $this->viewRequests($request);
                break;
            case 'delete_request':
                return $this->deleteRequest($request);
                break;
            case 'show_approval':
                return $this->showApproval($request);
                break;
            case 'getdetails':
                return $this->getDetails($request);
                break;
            case 'approvals':
                return $this->approvals($request);
                break;
            case 'department_approvals':
                return $this->departmentApprovals($request);
                break;
            case 'get_leave_length':
                return $this->leaveLength($request);
                break;
            case 'get_leave_requested_days':
                return $this->leaveDaysRequested($request);
                break;

            default:
                # code...
                break;
        }
    }

    public function processPost(Request $request)
    {
        switch ($request->type) {
            case 'save_request':
                return $this->saveRequest($request);
                break;
            case 'save_approval':
                return $this->saveApproval($request);
                break;

            default:
                # code...
                break;
        }
    }

    public function leaveDaysRequested(Request $request)
    {

        return $this->differenceBetweenDays($request->fromdate, $request->todate);
    }

    public function leaveLength(Request $request)
    {
        if ($request->leave_id == 0) {
            $company_id = companyId();
            $lp = LeavePolicy::where('company_id', $company_id)->first();
            if (Auth::user()->grade) {
                if (Auth::user()->grade->leave_length > 0) {
                    $leavebank = Auth::user()->grade->leave_length;
                } else {
                    $leavebank = $lp->default_length;
                }

            } else {
                $leavebank = $lp->default_length;
            }
            $leave_left = $leavebank;
            // $leavebank=Auth::user()->promotionHistories()->latest()->first()->grade->leave_length;
            $leave_includes_weekend = $lp->includes_weekend;
            $leave_includes_holiday = $lp->includes_holiday;
            $holidays = Holiday::whereYear('date', date('Y-m-d'))->get();
            $pending_leave_requests = Auth::user()->leave_requests()->where('status', 0)->whereYear('start_date', date('Y'))->get();
            $leave_requests = Auth::user()->leave_requests()->whereYear('start_date', date('Y'))->get();

            $leaves = Leave::all();


            $used_leaves = Auth::user()->leave_requests()->where(['status' => 1, 'leave_id' => 0])->whereYear('start_date', date('Y'))->get();
            if ($used_leaves) {
                $used_days = 0;
                foreach ($used_leaves as $used_leave) {
                    $startdate = \Carbon\Carbon::parse($used_leave->start_date);

                    $used_days += $startdate->diffInDays($used_leave->end_date);
                    if ($leave_includes_weekend == 0) {

                        $weekends = 0;
                        $fromDate = $used_leave->start_date;
                        $toDate = $used_leave->end_date;
                        while (date("Y-m-d", $fromDate) != date("Y-m-d", $toDate)) {
                            $day = date("w", $fromDate);
                            if ($day == 0 || $day == 6) {
                                $weekends++;
                            }
                            $fromDate = strtotime(date("Y-m-d", $fromDate) . "+1 day");
                        }
                        $used_days = $used_days - $weekends;
                    } elseif ($leave_includes_holiday == 0) {

                        $fromDate = $used_leave->start_date;
                        $toDate = $used_leave->end_date;
                        $hols = Holiday::whereBetween('date', [$fromDate, $toDate])->count();
                        $used_days = $used_days - $hols;

                    }
                }
                $leaveleft = $leavebank - $used_days;
            }
            return ['balance' => $leaveleft, 'paystatus' => 1];
        } else {
            $leave = Leave::find($request->leave_id);
            return ['balance' => $leave->length, 'paystatus' => $leave->with_pay];
        }
    }

    public function myRequests(Request $request)
    {

        $company_id = companyId();
        $lp = LeavePolicy::where('company_id', $company_id)->first();
        if (Auth::user()->grade) {
            if (Auth::user()->grade->leave_length > 0) {
                $leavebank = Auth::user()->grade->leave_length;
                $oldleavebank = Auth::user()->grade->leave_length;
            } else {
                $leavebank = $lp->default_length;
                $oldleavebank = $lp->default_length;
            }

        } else {
            $leavebank = $lp->default_length;
            $oldleavebank = $lp->default_length;
        }
        $leave_left = $leavebank;
        $oldleaveleft = 0;
        if ($lp->uses_spillover == 1 && date('Y', strtotime(Auth::user()->hiredate)) < date('Y') && date('Y-m-d') <= date('Y-m-d', strtotime(date('Y') . '-' . $lp->spillover_month . "-" . $lp->spillover_day))) {

            $user_leave_spill = Auth::user()->leave_spills()->where(['from_year' => date('Y', strtotime("-1 year")), 'to_year' => date('Y')])->first();
            if ($user_leave_spill) {
                $oldleaveleft = $user_leave_spill->days - $user_leave_spill->used;
            }


        }

        // $leavebank=Auth::user()->promotionHistories()->latest()->first()->grade->leave_length;
        $leave_includes_weekend = $lp->includes_weekend;
        $leave_includes_holiday = $lp->includes_holiday;
        $holidays = Holiday::whereYear('date', date('Y-m-d'))->get();
        $pending_leave_requests = Auth::user()->leave_requests()->where('status', 0)->whereYear('start_date', date('Y'))->get();
        $leave_requests = Auth::user()->leave_requests()->whereYear('start_date', date('Y'))->get();

        $leaves = Leave::all();


        $used_leaves = Auth::user()->leave_requests()->where(['status' => 1, 'leave_id' => 0])->whereYear('start_date', date('Y'))->get();
        $used_days = Auth::user()->leave_requests()->whereYear('start_date', date('Y'))->where(['status' => 1, 'leave_id' => 0])->sum('length');
        $used_days_last_year = 0;

        if (date('Y', strtotime(Auth::user()->hiredate)) == date('Y')) {
            //porate for staff employed this year
            $leavebank = $leavebank / 12 * (12 - intval(date('m', strtotime(Auth::user()->hiredate))) + 1);
            $oldleavebank = 0;
        }
        // else{
        // 	$used_days_last_year=Auth::user()->leave_requests()->whereYear('start_date', date('Y',strtotime('-1 year')))->where('status',1)->sum('length');
        // 	if (date('Y',strtotime(Auth::user()->hiredate))== date('Y',strtotime('-1 year'))) {
        // 		$oldleavebank=$oldleavebank/12*(12-intval(date('m',strtotime(Auth::user()->hiredate)))+1);
        // 	}
        // 	$oldleaveleft=$leavebank-$used_days_last_year;
        // 	if ($oldleaveleft>=5) {
        // 		$oldleaveleft=5;
        // 	}
        // }
        $leaveleft = $leavebank - $used_days;
        // if ($used_leaves) {
        // 	$used_days=0;
        // 	foreach ($used_leaves as $used_leave) {
        // 		$startdate = \Carbon\Carbon::parse( $used_leave->start_date);

        // 		$used_days+= $startdate->diffInDays($used_leave->end_date);
        // 		if ($leave_includes_weekend==0) {

        // 			 $weekends = 0;
        // 			    $fromDate = $used_leave->start_date;
        // 			    $toDate = $used_leave->end_date;
        // 			    while (date("Y-m-d", $fromDate) != date("Y-m-d", $toDate)) {
        // 			        $day = date("w", $fromDate);
        // 			        if ($day == 0 || $day == 6) {
        // 			            $weekends ++;
        // 			        }
        // 			        $fromDate = strtotime(date("Y-m-d", $fromDate) . "+1 day");
        // 			    }
        // 				    $used_days=$used_days -  $weekends;
        // 			} elseif ($leave_includes_holiday==0) {

        // 				$fromDate = $used_leave->start_date;
        // 				    $toDate = $used_leave->end_date;
        // 			$hols=Holiday::whereBetween('date', [$fromDate, $toDate])->count();
        // 			$used_days=$used_days - $hols;

        // 		}
        // 	}
        // 	$leaveleft=$leavebank-$used_days;
        // }

        // return ['leavebank'=>$leavebank,'holidays'=>$hols];
        return view('leave.myrequests', compact('leavebank', 'holidays', 'leave_requests', 'pending_leave_requests', 'leaves', 'used_days', 'leaveleft', 'oldleaveleft'));
    }

    public function saveRequest(Request $request)
    {
        // $leave_workflow_id=Setting::where('name','leave_workflow')->first()->value;
        $mime = $request->file('absence_doc')->getClientOriginalextension();
        if (!(in_array($mime, $this->allowed))): throw new \Exception("Invalid File Type"); endif;

        $company_id = companyId();
        $leave_workflow_id = LeavePolicy::where('company_id', $company_id)->first()->workflow_id;

        $leave_request = LeaveRequest::create(['leave_id' => $request->leave_id, 'user_id' => Auth::user()->id, 'start_date' => date('Y-m-d', strtotime($request->start_date)), 'end_date' => date('Y-m-d', strtotime($request->end_date)), 'reason' => $request->reason, 'workflow_id' => $leave_workflow_id, 'paystatus' => $request->paystatus, 'status' => 0, 'length' => $request->leavelength, 'company_id' => $company_id, 'replacement_id' => $request->replacement, 'balance' => $request->leavelength]);
        $stage = Workflow::find($leave_request->workflow_id)->stages->first();
        if ($stage->type == 1) {
            $leave_request->leave_approvals()->create([
                'leave_request_id' => $request->id, 'stage_id' => $stage->id, 'comments' => '', 'status' => 0, 'approver_id' => $stage->user_id
            ]);
            if ($stage->user) {
                $stage->user->notify(new ApproveLeaveRequest($leave_request));
            }

        } elseif ($stage->type == 2) {
            $leave_request->leave_approvals()->create([
                'leave_request_id' => $request->id, 'stage_id' => $stage->id, 'comments' => '', 'status' => 0, 'approver_id' => 0
            ]);
            if ($stage->role->manages == 'dr') {
                if ($leave_request->user->managers) {
                    foreach ($leave_request->user->managers as $manager) {
                        $manager->notify(new ApproveLeaveRequest($leave_request));
                    }
                }
            } elseif ($stage->role->manage == 'all') {
                foreach ($stage->role->users as $user) {
                    $user->notify(new ApproveLeaveRequest($leave_request));
                }
            } elseif ($stage->role->manage == 'none') {
                foreach ($stage->role->users as $user) {
                    $user->notify(new ApproveLeaveRequest($leave_request));
                }
            }
        } elseif ($stage->type == 3) {
            if ($stage->group) {
                foreach ($stage->group->users as $user) {
                    $user->notify(new ApproveLeaveRequest($leave_request));
                }
            }

        }

        if ($request->file('absence_doc')) {
            $path = $request->file('absence_doc')->store('leave');
            if (Str::contains($path, 'leave')) {
                $filepath = Str::replaceFirst('leave', '', $path);
            } else {
                $filepath = $path;
            }
            $leave_request->absence_doc = $filepath;
            $leave_request->save();
        }


        return 'success';
    }

    public function updateRequest(Request $request)
    {
        $leave_workflow_id = Setting::where('name', 'leave_workflow')->first()->value;
        $approved_leave_request_exist = LeaveRequest::find($request->leave_request_id)->approvals()->where(function ($query) {
            $query->where('status', 1);

        })
            ->get();
        if (!$approved_leave_request_exist) {
            $request = LeaveRequest::find($request->leave_request_id)->update(['leave_id' => $request->leave_id, 'user_id' => $request->user_id, 'start_date' => date('Y-m-d', strtotime($request->start_date)), 'end_date' => date('Y-m-d', strtotime($request->end_date)), 'reason' => $request->reason, 'workflow_id' => $leave_work, 'status' => $request->status, 'replacement_id' => $request->replacement]);
        }


        return 'success';
    }

    public function deleteRequest(Request $request)
    {
        $lr = LeaveRequest::find($request->leave_request_id);
        if ($lr) {

            $lr->delete();
            return 'success';
        }
    }

    public function showApproval(Request $request)
    {
        $leave_request = LeaveRequest::find($request->leave_request_id);

        return view('leave.approval', ['leave_request' => $leave_request]);
    }

    public function approvals(Request $request)
    {
        $user = Auth::user();

        $user_approvals = $this->userApprovals($user);
        $dr_approvals = $this->getDRLeaveApprovals($user);
        $role_approvals = $this->roleApprovals($user);
        $group_approvals = $this->groupApprovals($user);

        return view('leave.approvals', compact('user_approvals', 'role_approvals', 'group_approvals', 'dr_approvals'));
    }

    public function departmentApprovals(Request $request)
    {
        $user = Auth::user();
        $dapprovals = LeaveApproval::whereHas('leave_request.user.job.department', function ($query) use ($user) {
            $query->where('leave_requests.user_id', '!=', $user->id)
                ->where('departments.manager_id', $user->id);

        })
            ->where('status', 0)->orderBy('id', 'desc')->get();
        return view('leave.department_approvals', compact('dapprovals'));
    }

    public function userApprovals(User $user)
    {
        return $las = LeaveApproval::whereHas('stage.user', function ($query) use ($user) {
            $query->where('users.id', $user->id);
        })->where('status', 0)->orderBy('id', 'desc')->get();

    }

    public function getDRLeaveApprovals(User $user)
    {
        return Auth::user()->getDRLeaveApprovals();
        // 	return $las = LeaveApproval::whereHas('stage.role.users',function($query) use($user){
        // 	$query->where('users.id',$user->id);
        // })

        //  ->where('status',0)->orderBy('id','desc')->get();

    }

    public function roleApprovals(User $user)
    {
        return $las = LeaveApproval::whereHas('stage.role', function ($query) use ($user) {
            $query->where('manages', '!=', 'dr')
                ->where('roles.id', $user->role_id);
        })->where('status', 0)->orderBy('id', 'desc')->get();
    }

    public function groupApprovals(User $user)
    {
        return $las = LeaveApproval::whereHas('stage.group.users', function ($query) use ($user) {
            $query->where('users.id', $user->id);
        })
            ->where('status', 0)->orderBy('id', 'desc')->get();

    }

    public function saveApproval(Request $request)
    {
        $leave_approval = LeaveApproval::find($request->leave_approval_id);
        $leave_approval->comments = $request->comment;
        if ($request->approval == 1) {
            $leave_approval->status = 1;
            $leave_approval->approver_id = Auth::user()->id;
            $leave_approval->save();
            // $logmsg=$leave_approval->document->filename.' was approved in the '.$leave_approval->stage->name.' in the '.$leave_approval->stage->workflow->name;
            // $this->saveLog('info','App\Review',$leave_approval->id,'leave_approvals',$logmsg,Auth::user()->id);
            $newposition = $leave_approval->stage->position + 1;
            $nextstage = Stage::where(['workflow_id' => $leave_approval->stage->workflow->id, 'position' => $newposition])->first();
            // return $review->stage->position+1;
            // return $nextstage;

            if ($nextstage) {

                $newleave_approval = new LeaveApproval();
                $newleave_approval->stage_id = $nextstage->id;
                $newleave_approval->leave_request_id = $leave_approval->leave_request->id;
                $newleave_approval->status = 0;
                $newleave_approval->save();
                // $logmsg='New review process started for '.$newleave_approval->document->filename.' in the '.$newleave_approval->stage->workflow->name;
                // $this->saveLog('info','App\Review',$leave_approval->id,'reviews',$logmsg,Auth::user()->id);
                if ($nextstage->type == 1) {

                    $nextstage->user->notify(new ApproveLeaveRequest($leave_approval->leave_request));
                } elseif ($nextstage->type == 2) {
                    if ($nextstage->role->manages == 'dr') {
                        foreach ($leave_approval->leave_request->user->managers as $manager) {
                            $manager->notify(new ApproveLeaveRequest($leave_approval->leave_request));
                        }
                    } elseif ($nextstage->role->manage == 'all') {
                        foreach ($nextstage->role->users as $user) {
                            $user->notify(new ApproveLeaveRequest($leave_approval->leave_request));
                        }
                    } elseif ($nextstage->role->manage == 'none') {
                        foreach ($nextstage->role->users as $user) {
                            $user->notify(new ApproveLeaveRequest($leave_approval->leave_request));
                        }
                    }
                } elseif ($nextstage->type == 3) {
                    foreach ($nextstage->group->users as $user) {
                        $user->notify(new ApproveLeaveRequest($leave_approval->leave_request));
                    }
                }

                $leave_approval->leave_request->user->notify(new LeaveRequestPassedStage($leave_approval, $leave_approval->stage, $newleave_approval->stage));
            } else {
                //update old leave balance
                $lp = LeavePolicy::where('company_id', $company_id)->first();
                $oldleaveleft = 0;
                if ($lp->uses_spillover == 1 && date('Y', strtotime(Auth::user()->hiredate)) < date('Y') && date('Y-m-d') <= date('Y-m-d', strtotime(date('Y') . '-' . $lp->spillover_month . "-" . $lp->spillover_day))) {

                    $user_leave_spill = Auth::user()->leave_spills()->where(['from_year' => date('Y', strtotime("-1 year")), 'to_year' => date('Y')])->first();
                    if ($user_leave_spill) {
                        $oldleaveleft = $user_leave_spill->days - $user_leave_spill->used;
                        if ($oldleaveleft <= $leave_approval->leave_request->length) {
                            $user_leave_spill->update(['days' => $oldleaveleft]);
                        } else {
                            $user_leave_spill->update(['days' => $leave_approval->leave_request->length]);
                        }
                    }


                }
                $leave_approval->leave_request->status = 1;
                $leave_approval->leave_request->save();


                $leave_approval->stage->user->notify(new LeaveRequestApproved($leave_approval->stage, $leave_approval));
            }


        } elseif ($request->approval == 2) {
            $leave_approval->status = 2;
            $leave_approval->comments = $request->comment;
            $leave_approval->approver_id = Auth::user()->id;
            $leave_approval->save();
            // $logmsg=$leave_approval->document->filename.' was rejected in the '.$leave_approval->stage->name.' in the '.$leave_approval->stage->workflow->name;
            // $this->saveLog('info','App\Review',$leave_approval->id,'leave_approvals',$logmsg,Auth::user()->id);
            $leave_approval->leave_request->user->notify(new LeaveRequestRejected($leave_approval->stage, $leave_approval));
            // return redirect()->route('documents.mypendingleave_approvals')->with(['success'=>'Document Reviewed Successfully']);
        }

        return 'success';


        // return redirect()->route('documents.mypendingreviews')->with(['success'=>'Leave Request Approved Successfully']);
    }

    public function getDetails(Request $request)
    {
        $leave_request = LeaveRequest::where('id', $request->leave_request_id)->get()->first();
        return view('leave.partials.leaveDetails', compact('leave_request'));
    }

    public function differenceBetweenDays($start_date, $end_date)
    {
        $company_id = companyId();
        $lp = LeavePolicy::where('company_id', $company_id)->first();
        $start = new \DateTime($start_date);
        $end = new \DateTime($end_date);
        // otherwise the  end date is excluded (bug?)
        $end->modify('+1 day');

        $interval = $end->diff($start);

        // total days
        $days = $interval->days;

        // create an iterateable period of date (P1D equates to 1 day)
        $period = new \DatePeriod($start, new \DateInterval('P1D'), $end);

        $holidays = \App\Holiday::where(['status' => 1, 'company_id' => $company_id])->get();//array('2012-09-07');

        foreach ($period as $dt) {
            $curr = $dt->format('D');

            // substract if Saturday or Sunday
            if (($curr == 'Sat' || $curr == 'Sun') && $lp->includes_weekend == 0) {
                $days--;
            }

            // (optional) for the updated question
            if ($holidays->count() > 0 && $lp->includes_holiday == 0) {

                if ($holidays->contains('date', $dt->format('Y-m-d'))) {

                    // if (in_array($dt->format('Y-m-d'), $holidays->toArray())) {
                    $days--;
                }

            }
        }


        return $days;
    }

}