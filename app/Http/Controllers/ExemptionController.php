<?php

namespace App\Http\Controllers;

use App\AttendancePolicy;
use App\Exemption;
use App\ExemptionApproval;
use App\Notifications\ApproveExemptionRequest;
use App\Notifications\ApproveLeaveRequest;
use App\Notifications\LeaveRequestApproved;
use App\Notifications\LeaveRequestPassedStage;
use App\Notifications\LeaveRequestRejected;
use App\Stage;
use App\User;
use App\Workflow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class ExemptionController extends Controller
{

    public function index(){
        $exemptions=Exemption::all();
        return view('attendance.new.exemptions',compact('exemptions'));
    }

    public function myexemptions(){
        $user_id=Auth::user()->id;
        $exemptions=Exemption::where('user_id',$user_id)->get();
        return view('attendance.new.myExemptions',compact('exemptions'));
    }

    public function userAttendanceExemptions($user,$attendance)
    {
        $exemptions=Exemption::where('user_id',$user)->where('attendance_id',$attendance)->get();
        return view('attendance.new.partials.exemptionDetails',compact('exemptions'));
    }
    public function storeExemptions(Request $request)
    {
        //return $request->all();
        Validator::make($request->all(), [
            'user_id' => 'required',
            'attendance_id' => 'required',
            'reason' => 'required',
            'type' => 'required',
        ])->validate();

        $new= new Exemption();
        $new->user_id=$request->user_id;
        $new->attendance_id=$request->attendance_id;
        $new->type=$request->type;
        $new->reason=$request->reason;
        $new->save();

        $this->saveRequest($new);

        return Redirect::back();
    }

    public function saveRequest(Exemption $exemption)
    {
        $company_id = companyId();
        $attendance_workflow_id = AttendancePolicy::/*where('company_id', $company_id)->*/first()->workflow_id;


        $stage = Workflow::find($attendance_workflow_id)->stages->first();
        if ($stage->type == 1) {
            $exemption->exemption_approvals()->create([
                'exemption_id' => $exemption->id, 'stage_id' => $stage->id, 'comments' => '', 'status' => 0, 'approver_id' => $stage->user_id
            ]);
            if ($stage->user) {
                $stage->user->notify(new ApproveExemptionRequest($exemption));
            }

        } elseif ($stage->type == 2) {
            $exemption->exemption_approvals()->create([
                'leave_request_id' => $exemption->id, 'stage_id' => $stage->id, 'comments' => '', 'status' => 0, 'approver_id' => 0
            ]);
            if ($stage->role->manages == 'dr') {
                if ($exemption->user->managers) {
                    foreach ($exemption->user->managers as $manager) {
                        $manager->notify(new ApproveExemptionRequest($exemption));
                    }
                }
            } elseif ($stage->role->manage == 'all') {
                foreach ($stage->role->users as $user) {
                    $user->notify(new ApproveExemptionRequest($exemption));
                }
            } elseif ($stage->role->manage == 'none') {
                foreach ($stage->role->users as $user) {
                    $user->notify(new ApproveExemptionRequest($exemption));
                }
            }
        } elseif ($stage->type == 3) {
            if ($stage->group) {
                foreach ($stage->group->users as $user) {
                    $user->notify(new ApproveExemptionRequest($exemption));
                }
            }

        }
        return 'success';
    }


    public function exemptionApprovals(){
        $user=Auth::user();
        $user_approvals=$this->userApprovals($user);
        $dr_approvals=$this->getDRLeaveApprovals($user);
        $role_approvals=$this->roleApprovals($user);
        $group_approvals=$this->groupApprovals($user);
        return view('attendance.new.approvals',compact('user_approvals','role_approvals','group_approvals','dr_approvals'));
    }

    public function saveApproval(Request $request)
    {
        //return $request->all();
        $exemption_approval = ExemptionApproval::find($request->exemption_approval_id);
        $exemption=Exemption::find($exemption_approval->exemption->id);
        $exemption_approval->comments = $request->comment;
        if ($request->approval == 1) {
            $exemption_approval->status = 1;
            $exemption_approval->approver_id = Auth::user()->id;
            $exemption_approval->save();
            $newposition = $exemption_approval->stage->position + 1;
            $nextstage = Stage::where(['workflow_id' => $exemption_approval->stage->workflow->id, 'position' => $newposition])->first();

            if ($nextstage) {
                $newleave_approval = new ExemptionApproval();
                $newleave_approval->stage_id = $nextstage->id;
                $newleave_approval->exemption_id = $exemption_approval->exemption->id;
                $newleave_approval->status = 0;
                $newleave_approval->save();
                if ($nextstage->type == 1) {

                    $nextstage->user->notify(new ApproveExemptionRequest($exemption_approval->exemption));
                } elseif ($nextstage->type == 2) {
                    if ($nextstage->role->manages == 'dr') {
                        foreach ($exemption_approval->exemption->user->managers as $manager) {
                            $manager->notify(new ApproveExemptionRequest($exemption_approval->exemption));
                        }
                    } elseif ($nextstage->role->manage == 'all') {
                        foreach ($nextstage->role->users as $user) {
                            $user->notify(new ApproveExemptionRequest($exemption_approval->exemption));
                        }
                    } elseif ($nextstage->role->manage == 'none') {
                        foreach ($nextstage->role->users as $user) {
                            $user->notify(new ApproveExemptionRequest($exemption_approval->exemption));
                        }
                    }
                } elseif ($nextstage->type == 3) {
                    foreach ($nextstage->group->users as $user) {
                        $user->notify(new ApproveExemptionRequest($exemption_approval->exemption));
                    }
                }
                //$exemption_approval->exemption->user->notify(new LeaveRequestPassedStage($exemption_approval, $exemption_approval->stage, $newleave_approval->stage));
            } else {
                $exemption_approval->status = 1;
                $exemption_approval->save();


                $exemption->approved='yes';
                $exemption->save();

               // $exemption_approval->stage->user->notify(new LeaveRequestApproved($exemption_approval->stage, $exemption_approval));
            }
        } elseif ($request->approval == 2) {
            $exemption_approval->status = 2;
            $exemption_approval->comments = $request->comment;
            $exemption_approval->approver_id = Auth::user()->id;
            $exemption_approval->save();

            $exemption->approved='rejected';
            $exemption->save();
            //$exemption_approval->leave_request->user->notify(new LeaveRequestRejected($exemption_approval->stage, $exemption_approval));
        }
        return 'success';


        // return redirect()->route('documents.mypendingreviews')->with(['success'=>'Leave Request Approved Successfully']);
    }





    public function userApprovals(User $user)
    {
        return $las = ExemptionApproval::whereHas('stage.user',function($query) use($user){
            $query->where('users.id',$user->id);
        })->where('status',0)->orderBy('id','desc')->get();
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
        return $las = ExemptionApproval::whereHas('stage.role',function($query) use($user){
            $query->where('manages','!=','dr')
                ->where('roles.id',$user->role_id);
        })->where('status',0)->orderBy('id','desc')->get();
    }
    public function groupApprovals(User $user)
    {
        return $las = ExemptionApproval::whereHas('stage.group.users',function($query) use($user){
            $query->where('users.id',$user->id);
        })

            ->where('status',0)->orderBy('id','desc')->get();

    }



}
