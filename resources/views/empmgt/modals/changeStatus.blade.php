<div class="modal fade in modal-3d-flip-horizontal modal-info" id="changeStatusModal" aria-hidden="true" aria-labelledby="changeStatusModal" role="dialog" tabindex="-1">
    <div class="modal-dialog ">
        <form class="form-horizontal" id="changeStatusForm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title" id="training_title">Change User Status</h4>
                </div>
                <div class="modal-body">
                    <div class="row row-lg col-xs-12">
                        <div class="col-xs-12">
                            @csrf
                            <div class="form-group">
                                <h4 class="example-title">Status</h4>
                                <select id="status_id" name="role_id" class="form-control">
                                    <option value="1">Active</option>
                                    <option value="2">Suspended</option>
                                    <option value="3">Resigned</option>
                                    <option value="4">Disengaged</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <h4 class="example-title">Reason</h4>
                                <select id="reason" name="reason" class="form-control" required>
                                    <option value="theft">Theft</option>
                                    <option value="shortage">Shortage</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <h4 class="example-title">Details</h4>
                                <textarea name="details" class="form-control" id="" cols="30" rows="5" required></textarea>
                            </div>
                        </div>
                        <div class="clearfix hidden-sm-down hidden-lg-up"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="col-xs-12">
                        <div class="form-group">
                            <button type="submit" id="changeStatus" class="btn btn-info pull-left">Save</button>
                            <button type="button" class="btn btn-cancel" data-dismiss="modal">Cancel</button>
                        </div>
                        <!-- End Example Textarea -->
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>