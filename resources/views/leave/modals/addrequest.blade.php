<div class="modal fade in modal-3d-flip-horizontal modal-info" id="addLeaveRequestModal" aria-hidden="true" aria-labelledby="addLeaveRequestModal" role="dialog" >
      <div class="modal-dialog ">
        <form class="form-horizontal" id="addLeaveRequestForm" enctype="multipart/form-data">
          <div class="modal-content">        
          <div class="modal-header" >
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title" id="training_title">Create New Leave Request</h4>
          </div>
            <div class="modal-body">         
                
                    
            
            @csrf
            <div class="form-group">
                  <label for="">Period</label>
                  <div class="input-daterange input-group" id="datepicker">
                    <input type="text" class="input-sm form-control" name="start_date" placeholder="From date" id="fromdate" value="" required="" />
                    <span class="input-group-addon">to</span>
                    <input type="text" class="input-sm form-control" name="end_date" placeholder="To date" id="todate" value="" required="" />
                </div>
                </div>
       
          {{-- <div class="form-group">
            <div class="radio-custom radio-default radio-inline">
              <input type="radio" id="withpay" name="paystatus" checked value="1">
              <label for="withpay">With Pay</label>
            </div>
            <div class="radio-custom radio-default radio-inline">
              <input type="radio" id="withoutpay" name="paystatus" value="0">
              <label for="withoutpay">Without Pay</label>
            </div>
          </div> --}}
          {{-- <div class="form-group">
            <label for="">Priority</label>
            <select class="form-control" id="priority" name="priority" data-plugin="select2" data-placeholder="Select Priority" data-allow-clear="true" style="width:100%;">
              <option value="">-Select Priority-</option>
              <option value="0">Normal</option>
              <option value="1">Medium</option>
              <option value="2">High</option>
            </select>
          </div> --}}
          <div class="form-group">
            <label for="">Number of Leave Days Requested</label>
            <input type="text" class="form-control" name="leave_days_requested" id="leave_days_requested" readonly>
            
          </div>
          <div class="form-group">
            <label for="">Leave Type</label>
            <select class="form-control" id="abtype" name="leave_id" data-plugin="select2" data-placeholder="Select Absence Type" data-allow-clear="true" style="width:100%;">
              <option value="">-Select Absence Type-</option>
              <option value="0">Annual Leave</option>
              @if(count($leaves) > 0)
              @foreach($leaves as $leave)
              <option value="{{$leave->id}}">{{$leave->name}}</option>
              @endforeach
              @endif
            </select>
          </div>
          <div class="form-group">
            <label for="">Days Entitled To</label>
            <input type="text" class="form-control" name="leavelength" id="leavelength" readonly>
            
          </div>
          <div class="form-group">
            <label for="">Days Remaining</label>
            <input type="text" class="form-control" name="leaveremaining" id="leaveremaining" readonly>
            
          </div>
          <input type="hidden" name="paystatus" id="paystatus">
          <div class="form-group">
                       <label for="">Replacement</label>
                      <select name="replacement" id="emps" style="width:100%;" class="form-control" required>
                        <option></option>
                      </select>
                    </div>
          <div class="form-group">
            <label for="">Reason</label>
            <textarea class="form-control" id="reason" name="reason" style="height: 100px;resize: none;" placeholder="Briefly State Reason" required="required"></textarea>
          </div>
          <div class="form-group">
            <label for="">Supporting Document (Not Compulsory)</label>
            <input type="file" name="absence_doc" class="form-control" required="required" />
          </div>
          <input type="hidden" name="type" value="save_request">
          
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