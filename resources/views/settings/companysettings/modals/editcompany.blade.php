<div class="modal fade in modal-3d-flip-horizontal modal-info" id="editCompanyModal" aria-hidden="true"
     aria-labelledby="editCompanyModal" role="dialog" tabindex="-1">
    <div class="modal-dialog ">
        <form class="form-horizontal" id="editCompanyForm" method="POST">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title" id="training_title">Edit Station</h4>
                </div>
                <div class="modal-body">
                    <div class="row row-lg col-xs-12">
                        <div class="col-xs-12">
                            @csrf
                            <div class="form-group">
                                <h4 class="example-title">Name</h4>
                                <input type="text" id="editname" name="name" class="form-control">
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <h4 class="example-title">Branch</h4>
                                        <select class="form-control" name="branch_id" id="editbranch_id">
                                            @foreach($branches as $branch)
                                                <option value="{{$branch->id}}">{{$branch->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <h4 class="example-title">State</h4>
                                        <select class="form-control" name="state_id" id="editstate_id">
                                            @foreach($states as $state)
                                                <option value="{{$state->id}}">{{$state->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <h4 class="example-title">Email</h4>
                                        <input type="text" name="email" id="editemail" class="form-control">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <h4 class="example-title">Station Manager</h4>
                                        <select class="form-control" name="user_id" id="edituser_id">
                                            @foreach($users as $user)
                                                <option value="{{$user->id}}">{{$user->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <h4 class="example-title">Address</h4>
                                <textarea class="form-control" id="editaddress" name="address" rows="3"></textarea>
                            </div>

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <h4 class="example-title">Biometric Serial</h4>
                                        <input type="number" min="0" name="biometric" id="editbiometric" class="form-control">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <h4 class="example-title">Status</h4>
                                        <select class="form-control" name="status" id="editcstatus">
                                            <option value="inactive">Inactive</option>
                                            <option value="active">Active</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <h4 class="example-title">Pay Full if Staff Worked</h4>
                                        <input type="number" min="0" name="pay_full_days" id="editpay_full_days" class="form-control">
                                    </div>
                                </div>
                            </div>
                           



                            <input type="hidden" name="company_id" id="editid">
                        </div>
                        <div class="clearfix hidden-sm-down hidden-lg-up"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="col-xs-12">
                        <div class="form-group">

                            <button type="submit" class="btn btn-info pull-left">Save</button>
                            <button type="button" class="btn btn-cancel" data-dismiss="modal">Cancel</button>
                        </div>
                        <!-- End Example Textarea -->
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>