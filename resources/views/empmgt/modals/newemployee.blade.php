<!-- Modal -->
<div class="modal fade modal-success" id="addUserForm" aria-hidden="false" aria-labelledby="addUserForm"
     role="dialog">
    <div class="modal-dialog  modal-top modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title" id="exampleFillInModalTitle">New Employee</h4>
            </div>
            <form id="addNewUserForm" method="POST">
                <div class="modal-body">
                    @csrf
                    <div class="row">
                        <div class="col-xs-12 col-xl-6">
                            <div class="form-group" style="margin-bottom:0px">
                                <label class="form-control-label" for="name">Name</label>
                                <input type="text" class="form-control" id="name" name="name" placeholder="Name" required autocomplete="off">
                            </div>
                        </div>
                        <div class="col-xs-12 col-xl-6">
                            <div class="form-group" style="margin-bottom:0px">
                                <label class="form-control-label" for="emp_num">Staff Id</label>
                                <input type="text" class="form-control" id="emp_num" name="emp_num" placeholder="Employee Number" required/>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-xl-6">
                            <div class="form-group" style="margin-bottom:0px">
                                <label class="form-control-label" for="email">Email</label>
                                <input type="email" class="form-control" id="email" name="email" placeholder="Email"
                                />
                            </div>
                        </div>
                        <div class="col-xs-12 col-xl-6">
                            <div class="form-group" style="margin-bottom:0px">
                                <label class="form-control-label" for="phone">Phone Number</label>
                                <input type="text" class="form-control" id="phone" name="phone"
                                       placeholder="Phone Number"
                                />
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-xl-6">
                            <div class="form-group" style="margin-bottom:0px">
                                <label class="form-control-label" for="sex">Sex</label>
                                <select class="form-control" id="sex" name="sex" required>
                                    <option value='M'>Male</option>
                                    <option value='F'>Female</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-12 col-xl-6">
                            <div class="form-group">
                                <h4 class="example-title">Date of Birth</h4>
                                <input type="text" placeholder="Date of Birth" name="dob"
                                       class="form-control datepicker">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-xl-6 ">
                            <div class="form-group" style="margin-bottom:0px">
                                <label class="form-control-label" for="grade">Role</label>
                                <select id="grade_id" name="role_id" class="form-control select2" required style="width: 100%">
                                    @foreach($roles as $role)
                                        <option value="{{$role->id}}">{{$role->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-12 col-xl-6">
                            <div class="form-group" style="margin-bottom:0px">
                                <label class="form-control-label" for="department_id">Department</label>
                                <select class="form-control select2" name="department_id" onchange="departmentChange(this.value);" required style="width: 100%">
                                    @forelse($departments as $department)
                                        <option value="{{$department->id}}">{{$department->name}}</option>
                                    @empty
                                        <option value="0">Please Create a department</option>
                                    @endforelse
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">

                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-xl-6">
                            <div class="form-group">
                                <h4 class="example-title">Started</h4>
                                <input type="text" required placeholder="Started" name="started" class="form-control datepicker" autocomplete="off">
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <input type="hidden" name="company_id" value="{{$ncompany->id}}">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- End Modal -->