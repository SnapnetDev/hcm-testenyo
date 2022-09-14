<div class="modal fade in modal-3d-flip-horizontal modal-info" id="editSpecificSalaryComponentModal" aria-hidden="true" aria-labelledby="editSpecificSalaryComponentModal" role="dialog" tabindex="-1">
      <div class="modal-dialog ">
        <form class="form-horizontal" id="editSpecificSalaryComponentForm"  method="POST">
          <div class="modal-content">        
          <div class="modal-header" >
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title" id="training_title">Edit Salary Component</h4>
          </div>
            <div class="modal-body">         
                <div class="row row-lg col-xs-12">            
                  <div class="col-xs-12"> 
                    @csrf
                    <div class="form-group" >
                      <h4>Type</h4>
                      <input type="radio" id="sscallowance" name="sctype" value="1"> Allowance
                      <input type="radio" id="sscdeduction" name="sctype" value="0"> Deduction
                    </div>
                    <div class="form-group">
                      <h4 class="example-title">Name</h4>
                      <input type="text" name="name"  class="form-control">
                    </div>
                    <div class="form-group">
                      <h4 class="example-title">Employee</h4>
                      <select name="user_id" class="form-control select2" >
                        
                      </select>
                    </div>
                    <div class="form-group">
                      <h4 class="example-title">Comment (A note about this component)</h4>
                      <textarea name="comment"  class="form-control"></textarea>
                    </div>
                    <div class="form-group">
                      <h4 class="example-title">Amount</h4>
                      <input type="text" name="amount"  class="form-control">
                    </div>
                    <div class="form-group">
                      <h4 class="example-title">Duration</h4>
                      <input type="number" name="duration"  class="form-control">
                    </div>
                    <input type="hidden" name="type" value="specific_salary_component">
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