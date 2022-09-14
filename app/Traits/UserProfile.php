<?php

namespace App\Traits;

use App\User;
use App\Qualification;
use App\EducationHistory;
use App\EmploymentHistory;
use App\Skill;
use App\Dependant;
use App\Department;
use App\Company;
use App\Job;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Http\Request;


trait UserProfile
{
    public function processGet($route, Request $request)
    {
        switch ($route) {
            case 'profile':
                return $this->profile($request);
                break;
            case 'academic_history':
                return $this->academic_history($request);
                break;
            case 'delete_academic_history':
                return $this->delete_academic_history($request);
                break;
            case 'dependant':
                return $this->dependant($request);
                break;
            case 'delete_dependant':
                return $this->delete_dependant($request);
                break;
            case 'skill':
                return $this->skill($request);
                break;
            case 'delete_skill':
                return $this->delete_skill($request);
                break;
            case 'work_experience':
                return $this->work_experience($request);
                break;
            case 'delete_work_experience':
                return $this->delete_work_experience($request);
                break;
            case 'states':
                return $this->states($request);
                break;
            case 'lgas':
                return $this->lgas($request);
                break;
            case 'changegrade':
                return $this->changegrade($request);
                break;
            case 'primary_manager':
                return $this->makePrimaryManager($request);
                break;
            case 'remove_manager':
                return $this->removeManager($request);
                break;

            case 'delete_job_history':
                return $this->delete_job_history($request);
                break;
            case 'notifications':
                return $this->viewNotifications($request);
                break;
            case 'notification':
                return $this->viewNotificationInfo($request);
                break;


            default:
                # code...
                break;
        }
    }

    public function processPost(Request $request)
    {
        switch ($request->type) {
            case 'academic_history':
                return $this->save_academic_history($request);
                break;
            case 'dependant':
                return $this->save_dependant($request);
                break;
            case 'skill':
                return $this->save_skill($request);
                break;
            case 'work_experience':
                return $this->save_work_experience($request);
                break;
            case 'job_history':
                return $this->save_job_history($request);
                break;
            case 'change_password':
                return $this->changePassword($request);
                break;

            default:
                # code...
                break;
        }
    }

    public function profile(Request $request)
    {
        $user = User::find($request->user_id);
        // return view('empmgt.partials.details',['user'=>$user]);
        return $request->user_id;
    }

    public function academic_history(Request $request)
    {
        $ah = EducationHistory::find($request->academic_history_id);
        return $ah;
    }

    public function delete_academic_history(Request $request)
    {
        $ah = EducationHistory::find($request->academic_history_id);
        if ($ah) {
            $ah->delete();
            return 'success';
        }
        return 'failed';
    }

    public function save_academic_history(Request $request)
    {
        try {
            $ah = EducationHistory::updateOrCreate(['id' => $request->academic_history_id], ['title' => $request->title, 'qualification_id' => $request->qualification_id, 'institution' => $request->institution, 'year' => $request->year, 'course' => $request->course, 'grade' => $request->grade, 'emp_id' => $request->user_id]);
            return 'success';

        } catch (\Exception $ex) {
            return $ex->getMessage();
        }


    }

    public function dependant(Request $request)
    {
        $dependant = Dependant::find($request->dependant_id);
        return $dependant;
    }

    public function delete_dependant(Request $request)
    {
        $dependant = Dependant::find($request->dependant_id);
        if ($dependant) {
            $dependant->delete();
            return 'success';
        }
        return 'failed';
    }

    public function save_dependant(Request $request)
    {
        try {
            $dependant = Dependant::updateOrCreate(['id' => $request->dependant_id], ['name' => $request->name, 'dob' => date('Y-m-d', strtotime($request->dob)), 'email' => $request->email, 'phone' => $request->phone, 'relationship' => $request->relationship, 'user_id' => $request->user_id]);
            return 'success';

        } catch (\Exception $ex) {
            return $ex->getMessage();
        }
    }

    public function skill(Request $request)
    {
        $user = User::find($request->user_id);
        $skill = $user->skills()->where('skills.id', $request->skill_id)->first();
        return $skill;
    }

    public function delete_skill(Request $request)
    {
        $user = User::find($request->user_id);


        $user->skills()->detach($request->skill_id);
        return 'success';
    }

    public function save_skill(Request $request)
    {
        try {
            $skill = Skill::where('id', $request->skill)->orWhere('name', 'like', '%' . $request->skill . '%')->first();
            if (!$skill) {
                $skill = Skill::create(['name' => $request->skill]);
            }
            $user = User::find($request->user_id);
            $has_skill = User::whereHas('skills', function ($query) use ($skill) {
                $query->where('skills.id', $skill->id);
            })->get();
            if ($has_skill) {
                $user->skills()->detach($skill->id);
            }

            $user->skills()->attach($skill->id, ['competency_id' => $request->competency_id]);
            return 'success';

        } catch (\Exception $ex) {
            return $ex->getMessage();
        }
    }

    public function work_experience(Request $request)
    {
        $work_experience = EmploymentHistory::find($request->work_experience_id);
        return $work_experience;
    }

    public function delete_work_experience(Request $request)
    {
        $work_experience = EmploymentHistory::find($request->work_experience_id);
        if ($work_experience) {
            $work_experience->delete();
            return 'success';
        }
        return 'failed';
    }

    public function save_work_experience(Request $request)
    {
        try {
            $work_experience = EmploymentHistory::updateOrCreate(['id' => $request->work_experience_id], ['organization' => $request->organization, 'position' => $request->position, 'start_date' => date('Y-m-d', strtotime($request->start_date)), 'end_date' => date('Y-m-d', strtotime($request->end_date)), 'user_id' => $request->user_id]);
            return 'success';

        } catch (\Exception $ex) {
            return $ex->getMessage();
        }
    }

    public function save_job_history(Request $request)
    {
        try {
            $user = User::find($request->user_id);

            $user->jobs()->detach($request->job_id);

            $user->jobs()->attach($request->job_id, ['started' => date('Y-m-d', strtotime($request->started)), 'ended' => date('Y-m-d', strtotime($request->ended))]);
            $user->job_id = $request->job_id;
            $user->save();

            return 'success';

        } catch (\Exception $ex) {
            return $ex->getMessage();
        }
    }

    public function update_job_history(Request $request)
    {
        try {
            $user = User::find($request->user_id);

            $user->jobs()->detach($request->job_id);

            $user->jobs()->updateExistingPivot($request->job_id, ['started' => date('Y-m-d', strtotime($request->started)), 'ended' => date('Y-m-d', strtotime($request->ended))]);

            return 'success';

        } catch (\Exception $ex) {
            return $ex->getMessage();
        }
    }

    public function delete_job_history(Request $request)
    {
        try {
            $user = User::find($request->user_id);;
            $user->jobs()->detach($request->job_id);

            $job = $user->jobs()->latest()->first();
            // dd($manager);
            if ($job) {
                $user->job_id = $job->id;
                $user->department_id = $job->department->id;
            } else {
                $user->job_id = 0;
                $user->department_id = 0;
            }
            $user->save();

            return 'success';

        } catch (\Exception $ex) {
            return $ex->getMessage();
        }
    }

    public function states(Request $request)
    {
        $country = \App\Country::find($request->country_id);
        return $country->states;
    }

    public function lgas(Request $request)
    {

        $state = \App\State::find($request->state_id);
        return $state->lgas;
    }

    public function changegrade(Request $request)
    {
        $user = User::find($request->user_id);
        if ($user->promotionHistories) {
            $oldgrade = $user->promotionHistories()->latest()->first()->grade;
            if ($oldgrade->id != $request->grade_id) {
                $user->promotionHistories()->create([
                    'old_grade_id' => $oldgrade->id, 'grade_id' => $request->grade_id, 'approved_on' => date('Y-m-d'), 'approved_by' => Auth::user()->id
                ]);
                $user->grade_id = $request->grade_id;
                $user->save();
            }

        } else {
            $user->promotionHistories()->create([
                'old_grade_id' => $request->grade_id, 'grade_id' => $request->grade_id, 'approved_on' => date('Y-m-d'), 'approved_by' => Auth::user()->id
            ]);
            $user->grade_id = $request->grade_id;
            $user->save();
        }

        return 'success';
    }

    public function removeManager(Request $request)
    {
        $user = User::find($request->user_id);


        $user->managers()->detach($request->manager_id);
        $manager = $user->managers()->latest()->first();
        // dd($manager);
        if ($manager) {
            $user->line_manager_id = $manager->id;
            $user->save();
        } else {
            $user->line_manager_id = 0;
            $user->save();
        }

        return 'success';
    }

    public function makePrimaryManager(Request $request)
    {
        $user = User::find($request->user_id);

        $user->managers()->detach($request->manager_id);
        $user->managers()->attach($request->manager_id);
        $user->line_manager_id = $request->manager_id;
        $user->save();
        return 'success';
    }

    public function viewNotifications(Request $request)
    {
        $pageType = "mailbox";
        return view('notification.list', compact('pageType'));
    }

    public function viewNotificationInfo(Request $request)
    {
        $noti = Auth::user()->notifications()->where('id', $request->notification_id)->first();
        $noti->update(['read_at']);
        return view('notification.partials.info', compact('noti'));
    }

    public function changePassword(Request $request)
    {

        if (Hash::check($request->password,  Auth::user()->password)) {
            $validator = Validator::make($request->all(),[
                'new_password' => ['required',
                    'min:8',
                    'confirmed']
            ]);
            if ($validator->fails()) {
                return response()->json([
                    $validator->errors()
                ],401);
            }
            $request->user()->fill([
                'password' => Hash::make($request->new_password)
            ])->save();
            return 'success';
        }else{
            return 'failed';
        }
    }

}