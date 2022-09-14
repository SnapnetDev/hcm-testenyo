	  <div class="modal fade in modal-3d-flip-horizontal modal-info" id="editRegionModal" aria-hidden="true" aria-labelledby="editRegionModal" role="dialog" tabindex="-1">
	    <div class="modal-dialog ">
	      <form class="form-horizontal" id="editRegionForm"  method="POST">
	        <div class="modal-content">        
	        <div class="modal-header" >
	          <button type="button" class="close" data-dismiss="modal">&times;</button>
	          <h4 class="modal-title" id="training_title">Edit Region</h4>
	        </div>
            <div class="modal-body">         
                <div class="row row-lg col-xs-12">            
                  <div class="col-xs-12"> 
                  	@csrf
                  	<div class="form-group">
                  		<h4 class="example-title">Name</h4>
                  		<input type="text" id="editrname" name="name" class="form-control">
                  	</div>

					  <div class="form-group">
						  <h4 class="example-title">Regional Lead</h4>
						  <select class="form-control" name="regional_lead_id" id="editruser">
							  @foreach($users as $user)
								  <option value="{{$user->id}}">{{$user->name}}</option>
							  @endforeach
						  </select>
					  </div>

					  <div class="form-group">
						  <h4 class="example-title">Area Manager</h4>
						  <select class="form-control" name="area_manager_id" id="editruser">
							  @foreach($users as $user)
								  <option value="{{$user->id}}">{{$user->name}}</option>
							  @endforeach
						  </select>
					  </div>

                    <input type="hidden" name="region_id" id="editregion_id">
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