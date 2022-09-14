@extends('layouts.master')
@section('stylesheets')
    <link rel="stylesheet" href="{{ asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.css') }}">
    <link href="{{ asset('global/vendor/select2/select2.min.css') }}" rel="stylesheet"/>
    <link rel="stylesheet" href="{{ asset('global/vendor/bootstrap-table/bootstrap-table.css') }}">
    <style type="text/css">
        .btn-file {
            position: relative;
            overflow: hidden;
        }

        .btn-file input[type=file] {
            position: absolute;
            top: 0;
            right: 0;
            width: 50%;
            text-align: center;
            filter: alpha(opacity=0);
            opacity: 0;
            outline: none;
            background: white;
            cursor: inherit;
            display: block;
            background: #333;
        }


    </style>
@endsection
@section('content')
    <div class="page">
        <div class="page-header">
            <h1 class="page-title">User Profile</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="../index.html">Home</a></li>
                <li class="breadcrumb-item"><a href="javascript:void(0)">Employee Management</a></li>
                <li class="breadcrumb-item active">User Profile</li>

            </ol>
            <div class="page-header-actions">
                @if($user->id==Auth::user()->id)
                    <button type="button" data-target="#changePasswordModal" data-toggle="modal" id="changePassword" class="btn  btn-primary"  >
                        Change Password
                    </button>
                @endif

                <button type="button" id="datasave" class="btn  btn-primary">
                    Save
                </button>
                <button type="button" class="btn btn-sm btn-icon btn-primary btn-round" data-toggle="tooltip"
                        data-original-title="Refresh">
                    <i class="icon md-refresh-alt" aria-hidden="true"></i>
                </button>
                <button type="button" class="btn btn-sm btn-icon btn-primary btn-round" data-toggle="tooltip"
                        data-original-title="Setting">
                    <i class="icon md-settings" aria-hidden="true"></i>
                </button>
            </div>
        </div>
        <div class="page-content container-fluid">
            <div class="row">
                <div class="col-md-12 col-xs-12">
                    <!-- Panel -->
                    <div class="panel">
                        <div class="panel-body nav-tabs-animate nav-tabs-horizontal" data-plugin="tabs">
                            <ul class="nav nav-tabs nav-tabs-line" role="tablist">
                                <li class="nav-item" role="presentation"><a class="active nav-link" data-toggle="tab"
                                                                            href="#personal"
                                                                            aria-controls="activities" role="tab">Personal
                                        Information </a></li>
                                <li class="nav-item" role="presentation"><a class="nav-link" data-toggle="tab"
                                                                            href="#academics" aria-controls="profile"
                                                                            role="tab">Academic History</a></li>
                                <li class="nav-item" role="presentation"><a class="nav-link" data-toggle="tab"
                                                                            href="#dependants" aria-controls="messages"
                                                                            role="tab">Dependants</a></li>
                                <li class="nav-item" role="presentation"><a class="nav-link" data-toggle="tab"
                                                                            href="#skills" aria-controls="messages"
                                                                            role="tab">Skills</a></li>
                                <li class="nav-item" role="presentation"><a class="nav-link" data-toggle="tab"
                                                                            href="#experience" aria-controls="messages"
                                                                            role="tab">Work Experience</a></li>
                                <li class="nav-item" role="presentation"><a class="nav-link" data-toggle="tab"
                                                                            href="#history" aria-controls="messages"
                                                                            role="tab">Promotion History</a></li>
                                <li class="nav-item" role="presentation"><a class="nav-link" data-toggle="tab"
                                                                            href="#job_history" aria-controls="messages"
                                                                            role="tab">Job History</a></li>
                                <li class="nav-item" role="presentation"><a class="nav-link" data-toggle="tab"
                                                                            href="#managers" aria-controls="messages"
                                                                            role="tab">Managers</a></li>
                                <li class="nav-item" role="presentation"><a class="nav-link" data-toggle="tab"
                                                                            href="#direct_reports"
                                                                            aria-controls="messages"
                                                                            role="tab">Direct reports</a></li>
                                <li class="nav-item" role="presentation"><a class="nav-link" data-toggle="tab"
                                                                            href="#user_groups" aria-controls="messages"
                                                                            role="tab">User Groups</a></li>

                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active animation-slide-left" id="personal" role="tabpanel">
                                    <br>
                                    <form enctype="multipart/form-data" id="emp-data" method="POST" onsubmit="">
                                        @csrf
                                        <input type="hidden" name="user_id" value="{{$user->id}}">
                                        <div class="row">
                                            <div class="col-md-4 col-lg-4">
                                                <div class="form-group">
                                                    <label>Upload Image</label>
                                                    <img class="img-circle img-bordered img-bordered-blue text-center" width="150" height="150"
                                                    
                                                    src="{{ ($user->image!=null && File::exists('uploads/public/avatar'.$user->image))?asset('uploads/public/avatar'.$user->image):asset('global/portraits/male-user.png')}}"
                                                    alt="..." id='img-upload'>
                                                    <div class="input-group">
                                                      <span class="input-group-btn">
                                                          <span class="btn btn-default btn-file">
                                                              Browse… <input type="file" id="imgInp" name="avatar" accept="image/*">
                                                          </span>
                                                      </span>
                                                        <input type="text" class="form-control" readonly>
                                                    </div>
                                                </div>


                                            </div>

                                            <br>
                                            @if (Auth::user()->role->permissions->contains('constant', 'edit_user_advanced'))
                                                {{-- expr --}}
                                            @endif
                                            <div class="col-md-4">
                                                <div class="form-group form-material" data-plugin="formMaterial">
                                                    <label class="form-control-label" for="inputText">Name</label>
                                                    <input type="text" class="form-control" id="name"
                                                           value="{{$user->name}}" name="name" placeholder="Name"
                                                           required/>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group form-material" data-plugin="formMaterial">
                                                    <label class="form-control-label" for="inputText">Employee
                                                        Number </label>
                                                    <input type="text" class="form-control" id="emp_num"
                                                           value="{{$user->emp_num}}" name="emp_num"
                                                           placeholder="Employee Number"
                                                           required/>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group form-material" data-plugin="formMaterial">
                                                    <label class="form-control-label" for="inputText">Email</label>
                                                    <input type="email" class="form-control" id="email"
                                                           value="{{$user->email}}" name="email" placeholder="Email"
                                                    />
                                                </div>

                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group form-material" data-plugin="formMaterial">
                                                    <label class="form-control-label" for="inputText">Phone
                                                        Number</label>
                                                    <input type="text" class="form-control" id="phone"
                                                           value="{{$user->phone}}" name="phone"
                                                           placeholder="Phone Number"
                                                    />
                                                </div>

                                            </div>
                                        </div>

                                        <hr>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group form-material" data-plugin="formMaterial">
                                                    <label class="form-control-label" for="select">Sex</label>
                                                    <select class="form-control" id="sex" name="sex">
                                                        <option value="M" {{$user->sex=='M'?'selected':''}}>Male
                                                        </option>
                                                        <option value="F" {{$user->sex=='F'?'selected':''}}>Female
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group form-material" data-plugin="formMaterial">
                                                    <label class="form-control-label" for="select">Marital
                                                        Status</label>
                                                    <select class="form-control" id="marital_status"
                                                            name="marital_status">
                                                        <option>Single</option>
                                                        <option>Married</option>
                                                        <option>Divorced</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group form-material" data-plugin="formMaterial">
                                                    <label class="form-control-label" for="inputText">Date of
                                                        Birth</label>
                                                    <input type="text" class="form-control datepicker" id="dob"
                                                           name="dob" placeholder="Phone Number"
                                                           value="{{date("m/d/Y",strtotime($user->dob))}}"/>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-8">
                                                <div class="form-group form-material" data-plugin="formMaterial">
                                                    <label class="form-control-label" for="inputText">Address</label>
                                                    <textarea class="form-control" id="address" name="address"
                                                              rows="3">{{$user->address}}</textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group form-material" data-plugin="formMaterial">
                                                    <label class="form-control-label" for="select">Payroll Type</label>
                                                    <select class="form-control" id="sex" name="sex">
                                                        <option value="M" {{$user->payroll_type=='norm'?'selected':''}}>
                                                            Normal
                                                        </option>
                                                        <option value="F" {{$user->payroll_type=='tmsa'?'selected':''}}>
                                                            TMSA
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group form-material" data-plugin="formMaterial">
                                                    <label class="form-control-label" for="select">Country</label>
                                                    <select class="form-control " id="country" name="country" multiple>
                                                        @if($user->lga)
                                                            <option value="{{$user->lga->state->country->id}}">{{$user->lga->state->country->name}}</option>
                                                        @endif
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group form-material" data-plugin="formMaterial">
                                                    <label class="form-control-label" for="select">State/Region</label>
                                                    <select class="form-control " id="state" name="state">
                                                        @if($user->lga)
                                                            <option value="{{$user->lga->state->id}}">{{$user->lga->state->name}}</option>
                                                        @endif
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group form-material" data-plugin="formMaterial">
                                                    <label class="form-control-label" for="select">LGA/ District</label>
                                                    <select class="form-control " id="lga" name="lga">
                                                        @if($user->lga)
                                                            <option value="{{$user->lga->id}}">{{$user->lga->name}}</option>
                                                        @endif
                                                    </select>
                                                </div>
                                            </div>

                                        </div>

                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group form-material" data-plugin="formMaterial">
                                                    <label class="form-control-label" for="select">Company</label>
                                                    <select class="form-control" id="company_id" name="company_id"
                                                            onchange="companyChange(this.value)">
                                                        @foreach ($companies as $comp)
                                                            <option value="{{$comp->id}}" {{$comp->id==$user->company->id?'selected':''}}>{{$comp->name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group form-material" data-plugin="formMaterial">
                                                    <label class="form-control-label" for="select">Branch</label>
                                                    <select class="form-control" id="branch_id" name="branch_id">
                                                        @if ($user->company()->count()>0 && $user->company->branches()->count()>0)
                                                            @foreach($user->company->branches as $branch)
                                                                <option value="{{$branch->id}}" {{$branch->id==$user->branch_id?'selected':''}}>{{$branch->name}}</option>
                                                            @endforeach
                                                        @endif
                                                        @if($company->branches()->count()>0)
                                                            @foreach($company->branches as $branch)
                                                                <option value="{{$branch->id}}" {{$branch->id==$user->branch_id?'selected':''}}>{{$branch->name}}</option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group form-material" data-plugin="formMaterial">
                                                    <label class="form-control-label" for="select">Department</label>

                                                    <input type="text" class="form-control " disabled id="inputText"
                                                           name="inputText"
                                                           placeholder="{{$user->jobs()->count()>0?$user->jobs()->latest('started')->first()->department->name:''}}"
                                                    />

                                                </div>
                                            </div>


                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group form-material" data-plugin="formMaterial">
                                                    <label class="form-control-label" for="inputText">Hire Date</label>
                                                    <input type="text" class="form-control datepicker" id="hiredate"
                                                           name="hiredate" placeholder="Hire Date"
                                                           value="{{$user->hiredate?date('m/d/Y',strtotime($user->hiredate)):''}}"
                                                    />
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group form-material" data-plugin="formMaterial">
                                                    <label class="form-control-label" for="select">Current Job
                                                        Role</label>
                                                    <input type="text" class="form-control " disabled id="inputText"
                                                           name="inputText"
                                                           placeholder="{{$user->jobs()->count()>0?$user->jobs()->latest('started')->first()->title:''}}"
                                                    />
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group form-material" data-plugin="formMaterial">
                                                    <label class="form-control-label" for="select">Grade</label>
                                                    <input type="text" class="form-control " disabled id="inputText"
                                                           name="inputText"
                                                           placeholder="{{count($user->promotionHistories)>0?$user->promotionHistories()->latest()->first()->grade->level:''}}"
                                                    />
                                                </div>
                                            </div>


                                        </div>
                                        <div class="row">
                                            <h4 style="padding-left: 15px;">Account Details</h4>

                                            <div class="col-md-4">
                                                <div class="form-group form-material" data-plugin="formMaterial">
                                                    <label class="form-control-label" for="select">Bank</label>
                                                    <select class="form-control" id="bank_id" name="bank_id">
                                                        @foreach ($banks as $bank)
                                                            <option value="{{$bank->id}}">{{$bank->bank_name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group form-material" data-plugin="formMaterial">
                                                    <label class="form-control-label" for="inputText">Account
                                                        Number</label>
                                                    <input type="text" class="form-control "
                                                           value="{{$user->bank_account_no}}" id="account_no"
                                                           name="bank_account_no" placeholder="Account Number"
                                                    />
                                                </div>
                                            </div>


                                        </div>
                                        <div class="row">
                                            <h4 style="padding-left: 15px;">Next of Kin</h4>
                                            <div class="col-md-4">
                                                <input type="hidden" name="nok_id"
                                                       value="{{$user->nok()->count()>0?$user->nok->id:''}}">
                                                <div class="form-group form-material" data-plugin="formMaterial">
                                                    <label class="form-control-label" for="inputText">Name</label>
                                                    <input type="text" class="form-control " id="nok_name"
                                                           value="{{$user->nok()->count()>0?$user->nok->name:''}}"
                                                           name="nok_name" placeholder="Name"
                                                    />
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group form-material" data-plugin="formMaterial">
                                                    <label class="form-control-label" for="select">Relationship</label>
                                                    <select class="form-control" id="nok_relationship"
                                                            name="nok_relationship">
                                                        <option value="spouse">Spouse</option>
                                                        <option value="husband">Husband</option>
                                                        <option value="wife">Wife</option>
                                                        <option value="father">Father</option>
                                                        <option value="mother">Mother</option>
                                                        <option value="brother">Brother</option>
                                                        <option value="sister">Sister</option>
                                                        <option value="nephew">Nephew</option>
                                                        <option value="niece">Niece</option>
                                                        <option value="uncle">Uncle</option>
                                                        <option value="aunt">Aunt</option>
                                                        <option value="son">Son</option>
                                                        <option value="daughter">Daughter</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group form-material" data-plugin="formMaterial">
                                                    <label class="form-control-label" for="inputText">Phone
                                                        Number</label>
                                                    <input type="text" class="form-control " id="nok_phone"
                                                           name="nok_phone"
                                                           value="{{$user->nok()->count()>0?$user->nok->phone:''}}"
                                                           placeholder="Phone Number"
                                                    />
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group form-material" data-plugin="formMaterial">
                                                    <label class="form-control-label" for="inputText">Address of Next of
                                                        Kin</label>
                                                    <textarea class="form-control" id="nok_address" name="nok_address"
                                                              rows="3">{{$user->nok()->count()>0?$user->nok->address:''}}</textarea>
                                                </div>
                                            </div>


                                        </div>
                                        <br>
                                        <button type="submit" id="savebutton" class="btn btn-primary btn-lg">Save</button>
                                        
                                    <div id="loader" style="display: none;" class="loader vertical-align-middle loader-ellipsis"></div>
    
                                    </form>

                                </div>
                                <div class="tab-pane animation-slide-left" id="academics" role="tabpanel">
                                    <br>
                                    <button class="btn btn-primary " data-target="#addQualificationModal"
                                            data-toggle="modal">Add Qualification
                                    </button>
                                    <table id="exampleTablePagination" data-toggle="table"
                                           data-query-params="queryParams" data-mobile-responsive="true"
                                           data-height="400" data-pagination="true" data-search="true"
                                           class="table table-striped">
                                        <thead>
                                        <tr>
                                            <th>Title:</th>
                                            <th>Qualification:</th>
                                            <th>Year:</th>
                                            <th>Institution:</th>
                                            <th>CGPA/ Grad / Score:</th>
                                            <th>Discipline:</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @forelse($user->educationHistories as $history)
                                            <tr>
                                                <td>{{$history->title}}</td>
                                                @if($history->qualification_id>0)
                                                    <td>{{$history->qualification->name}}</td>
                                                @else
                                                    <td></td>
                                                @endif
                                                <td>{{$history->year}}</td>
                                                <td>{{$history->institution}}</td>
                                                <td>{{$history->grade}}</td>
                                                <td>{{$history->course}}</td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <button type="button" class="btn btn-primary dropdown-toggle"
                                                                id="exampleIconDropdown1"
                                                                data-toggle="dropdown" aria-expanded="false">
                                                            Action
                                                        </button>
                                                        <div class="dropdown-menu"
                                                             aria-labelledby="exampleIconDropdown1" role="menu">
                                                            <a class="dropdown-item" id="{{$history->id}}"
                                                               onclick="prepareEditAHData(this.id)"><i
                                                                        class="fa fa-pencil" aria-hidden="true"></i>&nbsp;Edit
                                                                Qualification</a>
                                                            <a class="dropdown-item" id="{{$history->id}}"
                                                               onclick="deleteAcademicHistory(this.id)"><i
                                                                        class="fa fa-trash" aria-hidden="true"></i>&nbsp;Delete
                                                                Qualification</a>

                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                        @endforelse

                                        </tbody>
                                    </table>
                                </div>
                                <div class="tab-pane animation-slide-left" id="dependants" role="tabpanel">
                                    <br>
                                    <button class="btn btn-primary " data-target="#addDependantModal"
                                            data-toggle="modal">Add Dependant
                                    </button>
                                    <table id="exampleTablePagination" data-toggle="table"
                                           data-query-params="queryParams" data-mobile-responsive="true"
                                           data-height="400" data-pagination="true" data-search="true"
                                           class="table table-striped">
                                        <thead>
                                        <tr>
                                            <th>Name:</th>
                                            <th>Date of Birth:</th>
                                            <th>Email:</th>
                                            <th>Phone Number:</th>
                                            <th>Relationship:</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @forelse($user->dependants as $dependant)
                                            <tr>
                                                <td>{{$dependant->name}}</td>
                                                <td>{{date("F j, Y", strtotime($dependant->dob))}}</td>
                                                <td>{{$dependant->email}}</td>
                                                <td>{{$dependant->phone}}</td>
                                                <td>{{ucfirst($dependant->relationship)}}</td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <button type="button" class="btn btn-primary dropdown-toggle"
                                                                id="exampleIconDropdown1"
                                                                data-toggle="dropdown" aria-expanded="false">
                                                            Action
                                                        </button>
                                                        <div class="dropdown-menu"
                                                             aria-labelledby="exampleIconDropdown1" role="menu">
                                                            <a class="dropdown-item" id="{{$dependant->id}}"
                                                               onclick="prepareEditDData(this.id)"><i
                                                                        class="fa fa-pencil" aria-hidden="true"></i>&nbsp;Edit
                                                                Dependant</a>
                                                            <a class="dropdown-item" id="{{$dependant->id}}"
                                                               onclick="deleteDependant(this.id)"><i class="fa fa-trash"
                                                                                                     aria-hidden="true"></i>&nbsp;Delete
                                                                Dependant</a>

                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                        @endforelse

                                        </tbody>
                                    </table>
                                </div>
                                <div class="tab-pane animation-slide-left" id="skills" role="tabpanel">
                                    <br>
                                    <button class="btn btn-primary " data-target="#addSkillModal" data-toggle="modal">
                                        Add Skill
                                    </button>
                                    <table id="exampleTablePagination" data-toggle="table"
                                           data-query-params="queryParams" data-mobile-responsive="true"
                                           data-height="400" data-pagination="true" data-search="true"
                                           class="table table-striped">
                                        <thead>
                                        <tr>
                                            <th>Skill:</th>
                                            <th>Competency:</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @forelse($user->skills as $skill)
                                            <tr>
                                                <td>{{$skill->name}}</td>
                                                <td>{{$skill->pivot->competency->proficiency}}</td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <button type="button"
                                                                class="btn btn-primary btn-sm dropdown-toggle"
                                                                id="exampleIconDropdown1"
                                                                data-toggle="dropdown" aria-expanded="false">
                                                            Action
                                                        </button>
                                                        <div class="dropdown-menu"
                                                             aria-labelledby="exampleIconDropdown1" role="menu">
                                                            <a class="dropdown-item" id="{{$skill->id}}"
                                                               onclick="prepareEditSData(this.id)"><i
                                                                        class="fa fa-pencil" aria-hidden="true"></i>&nbsp;Edit
                                                                Skill</a>
                                                            <a class="dropdown-item" id="{{$skill->id}}"
                                                               onclick="deleteSkill(this.id)"><i class="fa fa-trash"
                                                                                                 aria-hidden="true"></i>&nbsp;Delete
                                                                Skill</a>

                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                        @endforelse

                                        </tbody>
                                    </table>
                                </div>
                                <div class="tab-pane animation-slide-left" id="experience" role="tabpanel">
                                    <br>
                                    <button class="btn btn-primary " data-target="#addWorkExperienceModal"
                                            data-toggle="modal">Add Employment History
                                    </button>
                                    <table id="exampleTablePagination" data-toggle="table"
                                           data-query-params="queryParams" data-mobile-responsive="true"
                                           data-height="400" data-pagination="true" data-search="true"
                                           class="table table-striped">
                                        <thead>
                                        <tr>
                                            <th>Organization:</th>
                                            <th>Position:</th>
                                            <th>Start Date:</th>
                                            <th>End Date:</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @forelse($user->employmentHistories as $ehistory)
                                            <tr>
                                                <td>{{$ehistory->organization}}</td>
                                                <td>{{$ehistory->position}}</td>
                                                <td>{{date("F j, Y", strtotime($ehistory->start_date))}}</td>
                                                <td>{{date("F j, Y", strtotime($ehistory->end_date))}}</td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <button type="button" class="btn btn-primary dropdown-toggle"
                                                                id="exampleIconDropdown1"
                                                                data-toggle="dropdown" aria-expanded="false">
                                                            Action
                                                        </button>
                                                        <div class="dropdown-menu"
                                                             aria-labelledby="exampleIconDropdown1" role="menu">
                                                            <a class="dropdown-item" id="{{$ehistory->id}}"
                                                               onclick="prepareEditWEData(this.id)"><i
                                                                        class="fa fa-pencil" aria-hidden="true"></i>&nbsp;Edit
                                                                Employment History</a>
                                                            <a class="dropdown-item" id="{{$ehistory->id}}"
                                                               onclick="deleteWorkExperience(this.id)"><i
                                                                        class="fa fa-trash" aria-hidden="true"></i>&nbsp;Delete
                                                                Employment History</a>

                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                        @endforelse

                                        </tbody>
                                    </table>
                                </div>
                                <div class="tab-pane animation-slide-left" id="history" role="tabpanel">
                                    <br>
                                    <button class="btn btn-primary " data-target="#changeGradeModal"
                                            data-toggle="modal">Change Grade
                                    </button>
                                    <table id="exampleTablePagination" data-toggle="table"
                                           data-query-params="queryParams" data-mobile-responsive="true"
                                           data-height="400" data-pagination="true" data-search="true"
                                           class="table table-striped">
                                        <thead>
                                        <tr>
                                            <th>Old Grade:</th>
                                            <th>New Grade:</th>
                                            <th>Approved By</th>
                                            <th>Approved On</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @forelse($user->promotionHistories as $phistory)
                                            <tr>

                                                <td>{{$phistory->oldgrade?$phistory->oldgrade->level:''}}</td>
                                                <td>{{$phistory->grade?$phistory->grade->level:''}}</td>
                                                <td>{{$phistory->approver?$phistory->approver->name:''}}</td>
                                                <td>{{date("F j, Y", strtotime($phistory->approved_on))}}</td>
                                            </tr>
                                        @empty
                                        @endforelse

                                        </tbody>
                                    </table>
                                </div>
                                <div class="tab-pane animation-slide-left" id="job_history" role="tabpanel">
                                    <br>
                                    <button class="btn btn-primary " data-target="#assignJobRoleModal"
                                            data-toggle="modal">Assign New Job
                                    </button>
                                    <table id="exampleTablePagination" data-toggle="table"
                                           data-query-params="queryParams" data-mobile-responsive="true"
                                           data-height="400" data-pagination="true" data-search="true"
                                           class="table table-striped">
                                        <thead>
                                        <tr>
                                            <th>Job Role:</th>
                                            <th>Department:</th>
                                            <th>Started on</th>
                                            <th>Ended</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @forelse($user->jobs as $job)
                                            <tr>

                                                <td>{{$job->title}}</td>
                                                <td>{{$job->department->name}}</td>
                                                <td>{{$job->pivot->started?date("F j, Y", strtotime($job->pivot->started)):''}}</td>
                                                <td>{{$job->pivot->ended?date("F j, Y", strtotime($job->pivot->ended)):''}}</td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <button type="button"
                                                                class="btn btn-primary btn-sm dropdown-toggle"
                                                                id="exampleIconDropdown1"
                                                                data-toggle="dropdown" aria-expanded="false">
                                                            Action
                                                        </button>
                                                        <div class="dropdown-menu"
                                                             aria-labelledby="exampleIconDropdown1" role="menu">

                                                            <a class="dropdown-item" id="{{$job->id}}"
                                                               onclick="deleteJob(this.id)"><i class="fa fa-trash"
                                                                                               aria-hidden="true"></i>&nbsp;Remove
                                                                Job</a>

                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                        @endforelse

                                        </tbody>
                                    </table>
                                </div>
                                <div class="tab-pane animation-slide-left" id="managers" role="tabpanel">
                                    <table id="exampleTablePagination" data-toggle="table"
                                           data-query-params="queryParams" data-mobile-responsive="true"
                                           data-height="400" data-pagination="true" data-search="true"
                                           class="table table-striped">
                                        <thead>
                                        <tr>
                                            <th>Name:</th>
                                            <th>Email:</th>
                                            <th>Type:</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @forelse($user->managers as $manager)
                                            <tr>
                                                <td>{{$manager->name}}</td>
                                                <td>{{$manager->email}}</td>
                                                <td class="{{$manager->id==$user->line_manager_id?"text-primary":""}}">{{$manager->id==$user->line_manager_id?"Primary Manager":"Secondary Manager"}}</td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <button type="button"
                                                                class="btn btn-primary btn-sm dropdown-toggle"
                                                                id="exampleIconDropdown1"
                                                                data-toggle="dropdown" aria-expanded="false">
                                                            Action
                                                        </button>
                                                        <div class="dropdown-menu"
                                                             aria-labelledby="exampleIconDropdown1" role="menu">
                                                            @if($manager->id!=$user->line_manager_id)
                                                                <a class="dropdown-item" id="{{$manager->id}}"
                                                                   onclick="makePrimaryManager(this.id,{{$user->id}})"><i
                                                                            class="fa fa-pencil" aria-hidden="true"></i>&nbsp;Make
                                                                    Primary Line Manager</a>
                                                            @endif
                                                            <a class="dropdown-item" id="{{$manager->id}}"
                                                               onclick="removeManager(this.id,{{$user->id}})"><i
                                                                        class="fa fa-trash" aria-hidden="true"></i>&nbsp;Remove
                                                                Manager</a>

                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                        @endforelse

                                        </tbody>
                                    </table>
                                </div>
                                <div class="tab-pane animation-slide-left" id="direct_reports" role="tabpanel">
                                    <table id="exampleTablePagination" data-toggle="table"
                                           data-query-params="queryParams" data-mobile-responsive="true"
                                           data-height="400" data-pagination="true" data-search="true"
                                           class="table table-striped">
                                        <thead>
                                        <tr>
                                            <th>Name:</th>
                                            <th>Email:</th>
                                            <th>Type:</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @forelse($user->employees as $employee)
                                            <tr>
                                                <td>{{$employee->name}}</td>
                                                <td>{{$employee->email}}</td>
                                                <td class=" {{$employee->line_manager_id==$user->id?"text-primary":""}}">{{$employee->line_manager_id==$user->id?"Primary Manager":"Secondary Manager"}}</td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <button type="button"
                                                                class="btn btn-primary btn-sm dropdown-toggle"
                                                                id="exampleIconDropdown1"
                                                                data-toggle="dropdown" aria-expanded="false">
                                                            Action
                                                        </button>
                                                        <div class="dropdown-menu"
                                                             aria-labelledby="exampleIconDropdown1" role="menu">
                                                            @if($employee->line_manager_id!=$user->id)
                                                                <a class="dropdown-item" id="{{$employee->id}}"
                                                                   onclick="makePrimaryManager({{$user->id}},this.id)"><i
                                                                            class="fa fa-pencil" aria-hidden="true"></i>&nbsp;Make
                                                                    Primary Line Manager</a>
                                                            @endif
                                                            <a class="dropdown-item" id="{{$employee->id}}"
                                                               onclick="removeManager({{$user->id}},this.id)"><i
                                                                        class="fa fa-trash" aria-hidden="true"></i>&nbsp;Remove
                                                                Direct report</a>

                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                        @endforelse

                                        </tbody>
                                    </table>
                                </div>
                                <div class="tab-pane animation-slide-left" id="user_groups" role="tabpanel">
                                    <table id="exampleTablePagination" data-toggle="table"
                                           data-query-params="queryParams" data-mobile-responsive="true"
                                           data-height="400" data-pagination="true" data-search="true"
                                           class="table table-striped">
                                        <thead>
                                        <tr>
                                            <th>Name:</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @forelse($user->user_groups as $group)
                                            <tr>
                                                <td>{{$group->name}}</td>
                                                <td></td>
                                            </tr>
                                        @empty
                                        @endforelse

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Panel -->


                </div>

            </div>

        </div>
    </div>
    @include('empmgt.modals.adddependant')
    @include('empmgt.modals.addqualification')
    @include('empmgt.modals.addskill')
    @include('empmgt.modals.addworkexperience')
    @include('empmgt.modals.editdependant')
    @include('empmgt.modals.editqualification')
    @include('empmgt.modals.editskill')
    @include('empmgt.modals.editworkexperience')
    @include('empmgt.modals.changeGrade')
    @include('empmgt.modals.assignjobrole')
    @if($user->id==Auth::user()->id)
        @include('empmgt.modals.changepassword')
    @endif
@endsection
@section('scripts')
    <script src="{{asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.js')}}"></script>
    <script src="{{asset('global/js/Plugin/bootstrap-datepicker.js')}}"></script>
    <script src="{{asset('global/vendor/select2/select2.min.js')}}"></script>
    <script src="{{asset('global/vendor/bootstrap-table/bootstrap-table.min.js')}}"></script>
    <script src="{{asset('global/vendor/bootstrap-table/extensions/mobile/bootstrap-table-mobile.js')}}"></script>
    <script src="{{asset("js/countries.js")}}"></script>
    {{-- <script language="javascript">
             populateCountries("country", "state");
         </script> --}}
    <script type="text/javascript">


        $(document).ready(function () {
            //date picker initialization
            $('.datepicker').datepicker();
            //function for picture change
            $(document).on('change', '.btn-file :file', function () {
                var input = $(this),
                    label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
                input.trigger('fileselect', [label]);
            });

            $('.btn-file :file').on('fileselect', function (event, label) {

                var input = $(this).parents('.input-group').find(':text'),
                    log = label;

                if (input.length) {
                    input.val(log);
                } else {
                    if (log) alert(log);
                }

            });

            function readURL(input) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();

                    reader.onload = function (e) {
                        $('#img-upload').attr('src', e.target.result);
                    }

                    reader.readAsDataURL(input.files[0]);
                }
            }

            $("#imgInp").change(function () {
                readURL(this);
            });


            $(document).on('submit', '#emp-data', function (event) {
                event.preventDefault();
                var form = $(this);
                var formdata = false;
                if (window.FormData) {
                    formdata = new FormData(form[0]);
                }
                // console.log(formdata);
                // return;
                //var formAction = form.attr('action');
                $("#savebutton").hide();
                $("#loader").show();
                $.ajax({
                    url: '{{url('users')}}',
                    data: formdata ? formdata : form.serialize(),
                    cache: false,
                    contentType: false,
                    processData: false,
                    type: 'POST',
                    success: function (data, textStatus, jqXHR) {
                        toastr["success"]("Changes saved successfully", 'Success');
                         $("#savebutton").show();
                         $("#loader").hide();
                    },
                    error: function (data, textStatus, jqXHR) {
                             $("#savebutton").show();
                            $("#loader").hide();
                        jQuery.each(data['responseJSON'], function (i, val) {

                            jQuery.each(val, function (i, valchild) {

                                toastr["error"](valchild[0]);

                            });

                        });
                        console.log(textStatus);
                        console.log(jqXHR);
                    }
                });

            });
            //submit form on button click
            $(document).on('click', '#datasave', function (e) {

                // document.getElementById("emp-data");
                var form = document.getElementById("emp-data");
                var formdata = false;
                if (window.FormData) {
                    formdata = new FormData(form[0]);
                    console.log(formdata);
                }
                // console.log(formdata);
                // return
                //var formAction = form.attr('action');
                $.ajax({
                    url: '{{url('users')}}',
                    data: formdata ? formdata : form.serialize(),
                    cache: false,
                    contentType: false,
                    processData: false,
                    type: 'POST',
                    success: function (data, textStatus, jqXHR) {
                        toastr["success"]("Changes saved successfully", 'Success');
                    },
                    error: function (data, textStatus, jqXHR) {

                        jQuery.each(data['responseJSON'], function (i, val) {

                            jQuery.each(val, function (i, valchild) {

                                toastr["error"](valchild[0]);

                            });

                        });
                        console.log(data);
                        console.log(textStatus);
                        console.log(jqXHR);
                    }
                });

            });

        });

        $(function () {
            $(document).on('submit', '#addDependantForm', function (event) {
                event.preventDefault();
                var form = $(this);
                var formdata = false;
                if (window.FormData) {
                    formdata = new FormData(form[0]);
                }
                $.ajax({
                    url: '{{route('userprofile.store')}}',
                    data: formdata ? formdata : form.serialize(),
                    cache: false,
                    contentType: false,
                    processData: false,
                    type: 'POST',
                    success: function (data, textStatus, jqXHR) {
                        console.log(data);
                        // toastr.success("Changes saved successfully",'Success');
                        // $('#addDependantModal').modal('toggle');
                        // location.reload();

                    },
                    error: function (data, textStatus, jqXHR) {
                        jQuery.each(data['responseJSON'], function (i, val) {
                            jQuery.each(val, function (i, valchild) {
                                toastr.error(valchild[0]);
                            });
                        });
                    }
                });

            });
        });
        $(function () {
            $(document).on('submit', '#editDependantForm', function (event) {
                event.preventDefault();
                var form = $(this);
                var formdata = false;
                if (window.FormData) {
                    formdata = new FormData(form[0]);
                }
                $.ajax({
                    url: '{{route('userprofile.store')}}',
                    data: formdata ? formdata : form.serialize(),
                    cache: false,
                    contentType: false,
                    processData: false,
                    type: 'POST',
                    success: function (data, textStatus, jqXHR) {

                        toastr.success("Changes saved successfully", 'Success');
                        $('#editDependantModal').modal('toggle');
                        location.reload();
                    },
                    error: function (data, textStatus, jqXHR) {
                        jQuery.each(data['responseJSON'], function (i, val) {
                            jQuery.each(val, function (i, valchild) {
                                toastr["error"](valchild[0]);
                            });
                        });
                    }
                });

            });
        });


        function prepareEditDData(dependant_id) {
            $.get('{{ url('/userprofile/dependant') }}/', {dependant_id: dependant_id}, function (data) {

                $('#editdname').val(data.name);
                $('#editddob').val(data.dob);
                $('#editdemail').val(data.email);
                $('#editdphone').val(data.phone);
                $('#editdrelationship').val(data.relationship);
                $('#dependant_id').val(data.id);
                $('#editDependantModal').modal();
            });
        }

        function deleteDependant(dependant_id) {
            $.get('{{ url('/userprofile/delete_dependant') }}/', {dependant_id: dependant_id}, function (data) {
                if (data == 'success') {
                    toastr.success("Dependant deleted successfully", 'Success');
                    location.reload();
                } else {
                    toastr.error("Error deleting Dependant", 'Error');
                }

            });
        }

        $(function () {
            $(document).on('submit', '#addSkillForm', function (event) {
                event.preventDefault();
                var form = $(this);
                var formdata = false;
                if (window.FormData) {
                    formdata = new FormData(form[0]);
                }
                $.ajax({
                    url: '{{route('userprofile.store')}}',
                    data: formdata ? formdata : form.serialize(),
                    cache: false,
                    contentType: false,
                    processData: false,
                    type: 'POST',
                    success: function (data, textStatus, jqXHR) {


                        toastr.success("Changes saved successfully", 'Success');
                        $('#addSkillModal').modal('toggle');
                        location.reload();

                    },
                    error: function (data, textStatus, jqXHR) {
                        jQuery.each(data['responseJSON'], function (i, val) {
                            jQuery.each(val, function (i, valchild) {
                                toastr.error(valchild[0]);
                            });
                        });
                    }
                });

            });
        });
        $(function () {
            $(document).on('submit', '#editSkillForm', function (event) {
                event.preventDefault();
                var form = $(this);
                var formdata = false;
                if (window.FormData) {
                    formdata = new FormData(form[0]);
                }
                $.ajax({
                    url: '{{route('userprofile.store')}}',
                    data: formdata ? formdata : form.serialize(),
                    cache: false,
                    contentType: false,
                    processData: false,
                    type: 'POST',
                    success: function (data, textStatus, jqXHR) {

                        toastr.success("Changes saved successfully", 'Success');
                        $('#editSkillModal').modal('toggle');
                        location.reload();
                    },
                    error: function (data, textStatus, jqXHR) {
                        jQuery.each(data['responseJSON'], function (i, val) {
                            jQuery.each(val, function (i, valchild) {
                                toastr["error"](valchild[0]);
                            });
                        });
                    }
                });

            });

            $('.skills').select2({
                placeholder: "Skill",
                multiple: false,
                id: function (bond) {
                    return bond._id;
                },
                ajax: {
                    delay: 250,
                    processResults: function (data) {
                        return {
                            results: data
                        };
                    },
                    url: function (params) {
                        return '{{url('job_skill_search')}}';
                    }
                },
                tags: true

            });

        });


        function prepareEditSData(skill_id) {
            $.get('{{ url('/userprofile/skill') }}/', {skill_id: skill_id, user_id:{{$user->id}} }, function (data) {


                $('#editscompetency').val(data.pivot.competency_id);
                $('#skill_id').val(data.id);
                $("#editsskill").find('option')
                    .remove();
                $('#editSkillModal').modal();
                $("#editsskill").append($('<option>', {value: data.id, text: data.name, selected: 'selected'}));
            });
        }

        function deleteSkill(skill_id) {
            $.get('{{ url('/userprofile/delete_skill') }}/', {
                skill_id: skill_id,
                user_id:{{$user->id}} }, function (data) {
                if (data == 'success') {
                    toastr.success("Skill deleted successfully", 'Success');
                    location.reload();
                } else {
                    toastr.error("Error Deleting Skill", 'Error');
                }

            });
        }

        function makePrimaryManager(manager_id, user_id) {
            $.get('{{ url('/userprofile/primary_manager') }}/', {
                manager_id: manager_id,
                user_id: user_id
            }, function (data) {
                if (data == 'success') {
                    toastr.success("Success", 'Success');
                    location.reload();
                } else {
                    toastr.error("Error Encountered", 'Error');
                }

            });
        }

        function removeManager(manager_id, user_id) {
            $.get('{{ url('/userprofile/remove_manager') }}/', {
                manager_id: manager_id,
                user_id: user_id
            }, function (data) {
                if (data == 'success') {
                    toastr.success("Manager removed successfully", 'Success');
                    location.reload();
                } else {
                    toastr.error("Error Removing Manager", 'Error');
                }

            });
        }

        function deleteJob(job_id) {
            $.get('{{ url('/userprofile/delete_job_history') }}/', {
                job_id: job_id,
                user_id:{{$user->id}} }, function (data) {
                if (data == 'success') {
                    toastr.success("Job History deleted successfully", 'Success');
                    location.reload();
                } else {
                    toastr.error("Error Deleting Job History", 'Error');
                }

            });
        }

        $(function () {
            $(document).on('submit', '#addQualificationForm', function (event) {
                event.preventDefault();
                var form = $(this);
                var formdata = false;
                if (window.FormData) {
                    formdata = new FormData(form[0]);
                }
                $.ajax({
                    url: '{{route('userprofile.store')}}',
                    data: formdata ? formdata : form.serialize(),
                    cache: false,
                    contentType: false,
                    processData: false,
                    type: 'POST',
                    success: function (data, textStatus, jqXHR) {

                        toastr.success("Changes saved successfully", 'Success');
                        $('#addQualificationModal').modal('toggle');
                        location.reload();

                    },
                    error: function (data, textStatus, jqXHR) {
                        jQuery.each(data['responseJSON'], function (i, val) {
                            jQuery.each(val, function (i, valchild) {
                                toastr.error(valchild[0]);
                            });
                        });
                    }
                });

            });
        });
        $(function () {
            $(document).on('submit', '#editQualificationForm', function (event) {
                event.preventDefault();
                var form = $(this);
                var formdata = false;
                if (window.FormData) {
                    formdata = new FormData(form[0]);
                }
                $.ajax({
                    url: '{{route('userprofile.store')}}',
                    data: formdata ? formdata : form.serialize(),
                    cache: false,
                    contentType: false,
                    processData: false,
                    type: 'POST',
                    success: function (data, textStatus, jqXHR) {

                        toastr.success("Changes saved successfully", 'Success');
                        $('#editQualificationModal').modal('toggle');
                        location.reload();
                    },
                    error: function (data, textStatus, jqXHR) {
                        jQuery.each(data['responseJSON'], function (i, val) {
                            jQuery.each(val, function (i, valchild) {
                                toastr["error"](valchild[0]);
                            });
                        });
                    }
                });

            });
        });

        $(function () {
            $(document).on('submit', '#assignJobRoleForm', function (event) {
                event.preventDefault();
                var form = $(this);
                var formdata = false;
                if (window.FormData) {
                    formdata = new FormData(form[0]);
                }
                $.ajax({
                    url: '{{route('userprofile.store')}}',
                    data: formdata ? formdata : form.serialize(),
                    cache: false,
                    contentType: false,
                    processData: false,
                    type: 'POST',
                    success: function (data, textStatus, jqXHR) {

                        toastr.success("Changes saved successfully", 'Success');
                        $('#assignJobRoleModal').modal('toggle');
                        location.reload();
                    },
                    error: function (data, textStatus, jqXHR) {
                        jQuery.each(data['responseJSON'], function (i, val) {
                            jQuery.each(val, function (i, valchild) {
                                toastr["error"](valchild[0]);
                            });
                        });
                    }
                });

            });
        });


        function prepareEditAHData(emp_academic_id) {
            $.get('{{ url('/userprofile/academic_history') }}/', {academic_history_id: emp_academic_id}, function (data) {
                console.log(emp_academic_id);
                console.log(data);
                $('#editqqualification_id').val(data.qualification_id);
                $('#editqtitle').val(data.title);
                $('#editqinstitution').val(data.institution);
                $('#editqyear').val(data.year);
                $('#editqcourse').val(data.course);
                $('#editqgrade').val(data.grade);
                $('#academic_history_id').val(data.id);
                $('#editQualificationModal').modal();
            });
        }

        function deleteAcademicHistory(emp_academic_id) {
            $.get('{{ url('/userprofile/delete_academic_history') }}/', {academic_history_id: emp_academic_id}, function (data) {
                if (data == 'success') {
                    toastr.success("Academic History deleted successfully", 'Success');
                    location.reload();
                } else {
                    toastr.error("Error Deleting Academic History", 'Success');
                }

            });
        }

        $(function () {
            $(document).on('submit', '#addWorkExperienceForm', function (event) {
                event.preventDefault();
                var form = $(this);
                var formdata = false;
                if (window.FormData) {
                    formdata = new FormData(form[0]);
                }
                $.ajax({
                    url: '{{route('userprofile.store')}}',
                    data: formdata ? formdata : form.serialize(),
                    cache: false,
                    contentType: false,
                    processData: false,
                    type: 'POST',
                    success: function (data, textStatus, jqXHR) {

                        toastr.success("Changes saved successfully", 'Success');
                        $('#addWorkExperienceModal').modal('toggle');
                        location.reload();

                    },
                    error: function (data, textStatus, jqXHR) {
                        jQuery.each(data['responseJSON'], function (i, val) {
                            jQuery.each(val, function (i, valchild) {
                                toastr.error(valchild[0]);
                            });
                        });
                    }
                });

            });
        });
        $(function () {
            $(document).on('submit', '#editWorkExperienceForm', function (event) {
                event.preventDefault();
                var form = $(this);
                var formdata = false;
                if (window.FormData) {
                    formdata = new FormData(form[0]);
                }
                $.ajax({
                    url: '{{route('userprofile.store')}}',
                    data: formdata ? formdata : form.serialize(),
                    cache: false,
                    contentType: false,
                    processData: false,
                    type: 'POST',
                    success: function (data, textStatus, jqXHR) {

                        toastr.success("Changes saved successfully", 'Success');
                        $('#editWorkExperienceModal').modal('toggle');
                        location.reload();
                    },
                    error: function (data, textStatus, jqXHR) {
                        jQuery.each(data['responseJSON'], function (i, val) {
                            jQuery.each(val, function (i, valchild) {
                                toastr["error"](valchild[0]);
                            });
                        });
                    }
                });

            });

            $(document).on('click', '#changeGrade', function (event) {
                event.preventDefault();
                grade = $("#grade_id").val();
                $.get('{{ url('/userprofile/changegrade') }}/', {
                    grade_id: grade,
                    user_id:{{$user->id}} }, function (data) {
                    toastr.success("Grade Changed Successfully", 'Success');
                    $('#changeGradeModal').modal('toggle');
                    location.reload();
                });
            });
        });


        function prepareEditWEData(work_experience_id) {
            $.get('{{ url('/userprofile/work_experience') }}/', {work_experience_id: work_experience_id}, function (data) {

                $('#editworganization').val(data.organization);
                $('#editwposition').val(data.position);
                $('#editwstart_date').val(data.start_date);
                $('#editwend_date').val(data.end_date);
                $('#work_experience_id').val(data.id);
                $('#editWorkExperienceModal').modal();
            });
        }

        function deleteWorkExperience(work_experience_id) {
            $.get('{{ url('/userprofile/delete_work_experience') }}/', {work_experience_id: work_experience_id}, function (data) {
                if (data == 'success') {
                    toastr.success("Work Experience deleted successfully", 'Success');
                    location.reload();
                } else {
                    toastr.error("Error Deleting Work Experience", 'Success');
                }

            });
        }


        function departmentChange(department_id) {
            event.preventDefault();
            $.get('{{ url('/users/department/jobroles') }}/' + department_id, function (data) {


                if (data.jobs == '') {
                    $("#jobroles").empty();
                    $('#jobroles').append($('<option>', {value: 0, text: 'Please Create a Jobrole in Department'}));
                } else {
                    $("#jobroles").empty();
                    jQuery.each(data.jobroles, function (i, val) {
                        $('#jobroles').append($('<option>', {value: val.id, text: val.title}));
                    });
                }

            });
        }

        function companyChange(company_id) {
            event.preventDefault();
            $.get('{{ url('/users/company/departmentsandbranches') }}/' + company_id, function (data) {


                if (data.branches == '') {
                    $("#branch_id").empty();
                    $('#branch_id').append($('<option>', {value: 0, text: 'Please Create a Branch'}));
                } else {
                    $("#branch_id").empty();
                    jQuery.each(data.branches, function (i, val) {
                        $('#branch_id').append($('<option>', {value: val.id, text: val.name}));
                    });
                }

            });
        }

        //   function changeLgas(state_id){
        //     $.get('{{ url('/userprofile/lgas') }}/',{ state_id: state_id },function(data){
        //     $('#lga').html();
        //   jQuery.each( data, function( i, val ) {

        //      $("#lga").append($('<option>', {value:val.id, text:val.name}));
        //      // console.log(val.name);
        //             });
        // });
        //   }
        var country = $('#country').val();
        var state = $('#state').val();
        $('#country').select2({
            ajax: {
                delay: 250,
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
                url: function (params) {
                    return '{{url('location/country')}}';
                }
            }
        });
        $('#state').select2({
            ajax: {
                delay: 250,
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
                url: function (country) {
                    return '{{url('location/state')}}/' + $('#country').val();
                }
            }
        });
        $('#lga').select2({
            ajax: {
                delay: 250,
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
                url: function (state) {
                    return '{{url('location/lga')}}/' + $('#state').val();
                }
            },
            tags: true
        });


        @if($user->id==Auth::user()->id)
        $(function() {
            $(document).on('submit','#changePasswordForm',function(event){
                event.preventDefault();
                toastr.info('Processing ...','Info',{timeOut: 0,closeButton: true,extendedTimeOut:0 });
                var form = $(this);
                var formdata = false;
                if (window.FormData){
                    formdata = new FormData(form[0]);
                }
                $.ajax({
                    url         : '{{route('userprofile.store')}}',
                    data        : formdata ? formdata : form.serialize(),
                    cache       : false,
                    contentType : false,
                    processData : false,
                    type        : 'POST',
                    success     : function(data, textStatus, jqXHR){



                        if(data=='success'){
                            toastr.success("Changes saved successfully",'Success');
                            $('#changePasswordModal').modal('toggle');
                            location.reload();
                        }
                        if(data=='failed'){
                            toastr.error("You entered the wrong password",'Wrong Current Password');
                        }




                    },
                    error:function(data, textStatus, jqXHR){
                        jQuery.each( data['responseJSON'], function( i, val ) {
                            jQuery.each( val, function( i, valchild ) {
                                toastr.error(valchild[0]);
                            });
                        });
                    }
                });

            });

        });
        @endif
    </script>

@endsection