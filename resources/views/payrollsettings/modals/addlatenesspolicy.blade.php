<div class="modal fade in modal-3d-flip-horizontal modal-info" id="addLatenessPolicyModal" aria-hidden="true" aria-labelledby="addLatenessPolicyModal" role="dialog" tabindex="-1">
	    <div class="modal-dialog ">
	      
	        <div class="modal-content">        
	        <div class="modal-header" >
	          <button type="button" class="close" data-dismiss="modal">&times;</button>
	          <h4 class="modal-title" id="training_title">Add Lateness Policy</h4>
	        </div>
          <form class="form-horizontal" id="addLatenessPolicyForm"  method="POST">
            <div class="modal-body">         
                
                  	@csrf
                   <div class="form-group" >
                      <h4 class="example-title">Deduction Type</h4>
                      <input type="radio" id="percentage" name="deduction_type" value="1"> Percentage
                      <input type="radio" id="amount" name="deduction_type" value="0"> Amount
                    </div>
                  	<div class="form-group">
                  		<h4 class="example-title">Policy Name</h4>
                  		<input type="text" name="policy_name" class="form-control">
                  	</div>
                    <div class="form-group">
                      <h4 class="example-title">Grace Period</h4>
                      <input type="text" name="late_minute" class="form-control">
                    </div>
                    <div class="form-group">
                      <h4 class="example-title">Deduction</h4>
                      <input type="text" name="deduction" class="form-control">
                    </div>
                    <input type="hidden" name="type" value="lateness_policy">
                          
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
             </form>
	       </div>
	      
	    </div>
	  </div>