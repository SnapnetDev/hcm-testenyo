<div class="modal fade in modal-3d-flip-horizontal modal-info" id="addSpecificSalaryComponentModal" aria-hidden="true" aria-labelledby="addSpecificSalaryComponentModal" role="dialog" >
	    <div class="modal-dialog ">
	      
	        <div class="modal-content">        
	        <div class="modal-header" >
	          <button type="button" class="close" data-dismiss="modal">&times;</button>
	          <h4 class="modal-title" id="training_title">Add New Specific Salary Component</h4>
	        </div>
            <div class="modal-body"> 
           
              
            
            <form class="form-horizontal" id="uploadSpecificSalaryComponentForm"  method="POST">
             
                    @csrf
                    <div class="form-group">
                      <h4 class="example-title">Upload Excel Sheet</h4>
                      <a href="#">Want to add multiple at once? Download excel template here</a>
                      <input type="file" name="sscs" class="form-control">
                    </div>
                    <div class="form-group">
                    <button type="submit" class="btn btn-info pull-left ">Upload</button>
                    </div>
                  
                  
            </form>
            <br>
            <hr> 
            <form  class="form-horizontal" id="addSpecificSalaryComponentForm"  method="POST">
              
                    @csrf
                    <div class="form-group" >
                      <h4>Type</h4>
                      <input type="radio" id="sscallowance" name="ssctype" value="1"> Allowance
                      <input type="radio" id="sscdeduction" name="ssctype" value="0"> Deduction
                    </div>
                    <div class="form-group">
                      <h4 class="example-title">Name</h4>
                      <input type="text" name="name"  class="form-control">
                    </div>
                    <div class="form-group">
                      <h4 class="example-title">Employee</h4>
                      <select name="user_id" id="emps" style="width:100%;" class="form-control" >
                        <option></option>
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
                      <h4 class="example-title">General Ledger Code</h4>
                      <input type="text" name="gl_code"  class="form-control">
                    </div>
                    <div class="form-group">
                      <h4 class="example-title">Project Code</h4>
                      <input type="text" name="project_code"  class="form-control">
                    </div>
                    <div class="form-group">
                      <h4 class="example-title">Duration(Months)</h4>
                      <input type="number" name="duration"  class="form-control">
                    </div>
                    <input type="hidden" name="type" value="specific_salary_component">
                 
                <div class="form-group">
                    
                    <button type="submit" class="btn btn-info pull-left">Save</button>
                  </div>
                  <br>
            </form>     
                  
            </div>
            
	       </div>
	      
	    </div>
	  </div>