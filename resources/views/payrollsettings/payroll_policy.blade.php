	<div class="page-header">
  		<h1 class="page-title">{{__('All Settings')}}</h1>
		  <ol class="breadcrumb">
		    <li class="breadcrumb-item"><a href="{{url('/')}}">{{__('Home')}}</a></li>
		    <li class="breadcrumb-item ">{{__('Payroll Settings')}}</li>
		    <li class="breadcrumb-item active">{{__('You are Here')}}</li>
		  </ol>
		  <div class="page-header-actions">
		    <div class="row no-space w-250 hidden-sm-down">

		      <div class="col-sm-6 col-xs-12">
		        <div class="counter">
		          <span class="counter-number font-weight-medium">{{date('Y-m-d')}}</span>

		        </div>
		      </div>
		      <div class="col-sm-6 col-xs-12">
		        <div class="counter">
		          <span class="counter-number font-weight-medium" id="time"></span>
		        </div>
		      </div>
		    </div>
		  </div>
	</div>
      <div class="page-content container-fluid">
      	<div class="row">
        	<div class="col-md-12 col-xs-12">
        		<div class="panel panel-info panel-line">
		            <div class="panel-heading">
		              <h3 class="panel-title">Payroll Policy Settings</h3>
		              <div class="panel-actions">
                			

              			</div>
		            	</div>
		            	<form id="editPayrollPolicyForm" enctype="multipart/form-data">
		            <div class="panel-body">
		            <div class="col-md-6">
		            	@csrf
		            		<div class="form-group" >
	          					<h4>When do you run your Payroll?</h4>
	          					<input type="radio" id="current_month" {{$pp->payroll_runs==1?'checked':''}} name="when" value="1"> End of current month
	          					<input type="radio" id="next_month" {{$pp->payroll_runs==0?'checked':''}} name="when" value="0"> Beginning of next month
	          				</div>
	          				<div class="form-group" >
	          					<h4>Basic Pay Percentage</h4>
	          					<input type="text" name="basic_pay_percentage" class="form-control" value="{{$pp->basic_pay_percentage}}">
	          				</div>
	          				<div class="form-group" >
	          					<h4>Approval Workflow</h4>
	          					<select class="form-control" name="workflow_id">
	          						@forelse($workflows as $workflow)
	          						<option value="{{$workflow->id}}" {{$pp->workflow_id==$workflow->id?'selected':''}}>{{$workflow->name}}</option>
	          						@empty
	          						<option value="0">Please Create a Workflow</option>
	          						@endforelse
	          						
	          					</select>
	          				</div>
	          				<input type="hidden" name=" type" value="payroll_policy">
	          					            	
		            </div>
	                 
	          		</div>
	          		<div class="panel-footer">
	          			<div class="form-group">
	          					<button class="btn btn-info" >Save Changes</button>
	          				</div>
	          		</div>
	          		</form>
		          </div>
		          <div class="panel panel-info panel-line">
		            <div class="panel-heading">
		              <h3 class="panel-title">Lateness Policy</h3>
		              <div class="panel-actions">
                			<input type="checkbox" class="active-toggle" id="lps"{{$pp->use_lateness==1?'checked':''}} >

              			</div>
		            	</div>
		            <div class="panel-body">
		            	<button class="btn btn-info" data-toggle="modal" data-target="#addLatenessPolicyModal">Add Policy</button>
		            	<div class="table-responsive">
		            <table class="table">
		            	<thead>
		            		<tr>
		            			<th>Policy Name</th>
		            			<th>Grace Period (Minutes)</th>
		            			<th>Deduction Type</th>
		            			<th>Deduction</th>
		            			<th>Status</th>
		            			<th>Action</th>
		            		</tr>
		            	</thead>
		            	<tbody>
		            		@forelse ($latenesspolicies as $latenesspolicy)
		            		<tr>
		            				<td>{{$latenesspolicy->policy_name}}</td>
		            			<td>{{$latenesspolicy->late_minute}}</td>
		            			<td>{{$latenesspolicy->deduction_type==1?'Percentage':'Amount'}}</td>
		            			<td>{{$latenesspolicy->deduction}}</td>
		            			<td>
		            				<input type="checkbox" class="active-toggle sc-status" id="{{$latenesspolicy->id}}" {{$latenesspolicy->status == 1?'checked':''}} >
		            				</td>
		                    		
		                    		<td>
		                    			<div class="btn-group" role="group">
					                    <button type="button" class="btn btn-primary dropdown-toggle" id="exampleIconDropdown1"
					                    data-toggle="dropdown" aria-expanded="false">
					                      Action
					                    </button>
				                    <div class="dropdown-menu" aria-labelledby="exampleIconDropdown1" role="menu">
				                      <a class="dropdown-item" id="{{$latenesspolicy->id}}" onclick="prepareEditData(this.id)"><i class="fa fa-pencil" aria-hidden="true"></i>&nbsp;Edit Lateness Policy</a>
				                       <a class="dropdown-item" id="{{$latenesspolicy->id}}" onclick="deleteLatenessPolicy(this.id)"><i class="fa fa-trash" aria-hidden="true"></i>&nbsp;Delete Lateness Policy</a>
				                      
				                    </div>
				                  </div></td>
		            		</tr>
		            		@empty
		            		<tr>
		            			<td colspan="6">No policy set yet</td>
		            		</tr>
		            		
		            		@endforelse
		            		
		            		
		            	</tbody>
		            </table>
	                  </div>
	          		</div>
	          		
		          </div>
	        	</div>
	    	</div>
	    	<div class="col-md-12 col-xs-12">
	    		
	    	</div>
		</div>
	  </div>
	   @include('payrollsettings.modals.addlatenesspolicy')
	  {{-- edit grade modal --}}
	   @include('payrollsettings.modals.editlatenesspolicy')
	  <!-- End Page -->
	    <script type="text/javascript">
  	$(function() {
  

    $('#lps').bootstrapToggle({
      on: 'Enabled',
      off: 'Disabled',
      onstyle:'info',
      offstyle:'default'
    });
     $('.sc-status').bootstrapToggle({
      on: 'Enabled',
      off: 'Disabled',
      onstyle:'info',
      offstyle:'default'
    });


  	});
  	
  	$(function() {
  

  	$(document).on('submit','#addLatenessPolicyForm',function(event){
		 event.preventDefault();
		 var form = $(this);
		    var formdata = false;
		    if (window.FormData){
		        formdata = new FormData(form[0]);
		    }
		    $.ajax({
		        url         : '{{route('payrollsettings.store')}}',
		        data        : formdata ? formdata : form.serialize(),
		        cache       : false,
		        contentType : false,
		        processData : false,
		        type        : 'POST',
		        success     : function(data, textStatus, jqXHR){

		            toastr.success("Changes saved successfully",'Success');
		           $('#addLatenessPolicyModal').modal('toggle');
					$( "#ldr" ).load('{{url('payrollsettings/payroll_policy')}}');
					// console.log(data);

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
  	$(function() {
  	$(document).on('submit','#editLatenessPolicyForm',function(event){
		 event.preventDefault();
		 var form = $(this);
		    var formdata = false;
		    if (window.FormData){
		        formdata = new FormData(form[0]);
		    }
		    $.ajax({
		        url         : '{{route('payrollsettings.store')}}',
		        data        : formdata ? formdata : form.serialize(),
		        cache       : false,
		        contentType : false,
		        processData : false,
		        type        : 'POST',
		        success     : function(data, textStatus, jqXHR){

		            toastr.success("Changes saved successfully",'Success');
		            $('#editLatenessPolicyModal').modal('toggle');
					$( "#ldr" ).load('{{url('payrollsettings/payroll_policy')}}');
		        },
		        error:function(data, textStatus, jqXHR){
		        	 jQuery.each( data['responseJSON'], function( i, val ) {
							  jQuery.each( val, function( i, valchild ) {
							  toastr["error"](valchild[0]);
							});  
							});
		        }
		    });
      
		});
  });
  	$(function() {
  	$(document).on('submit','#editPayrollPolicyForm',function(event){
		 event.preventDefault();
		 var form = $(this);
		    var formdata = false;
		    if (window.FormData){
		        formdata = new FormData(form[0]);
		    }
		    $.ajax({
		        url         : '{{route('payrollsettings.store')}}',
		        data        : formdata ? formdata : form.serialize(),
		        cache       : false,
		        contentType : false,
		        processData : false,
		        type        : 'POST',
		        success     : function(data, textStatus, jqXHR){

		            toastr.success("Changes saved successfully",'Success');
		           
					$( "#ldr" ).load('{{url('payrollsettings/payroll_policy')}}');
		        },
		        error:function(data, textStatus, jqXHR){
		        	 jQuery.each( data['responseJSON'], function( i, val ) {
							  jQuery.each( val, function( i, valchild ) {
							  toastr["error"](valchild[0]);
							});  
							});
		        }
		    });
      
		});
  });

  	$(function() {
  	 $('.sc-status').on('change', function() {
  		lateness_policy_id= $(this).attr('id');
  		
  		 $.get('{{ url('/payrollsettings/change_lateness_policy_status') }}/',{ lateness_policy_id: lateness_policy_id },function(data){
  		 	if (data==1) {
  		 		toastr.success("Lateness Policy Enabled",'Success');
  		 	}
  		 	if(data==2){
  		 		toastr.warning("Lateness Policy Disabled",'Success');
  		 	}
  		 	$( "#ldr" ).load('{{url('payrollsettings/payroll_policy')}}');
  		 });
  	});



  	   $('#lps').on('change', function() {

		 $.get('{{ url('/payrollsettings/switch_lateness_policy') }}/',function(data){
  		 	if (data==1) {
  		 		toastr.success("Lateness Policy Enabled",'Success');
  		 	}
  		 	if(data==2){
  		 		toastr.warning("Lateness Policy Disabled",'Success');
  		 	}
  		 	$( "#ldr" ).load('{{url('payrollsettings/payroll_policy')}}');
  		 });
		});
  });
  // 	$(function() {
  // 	$(document).on('click','.sc-status',function(event){
  // 		lateness_policy_id= $(this).attr('id');
  		
  // 		 $.get('{{ url('/payrollsettings/change_lateness_policy_status') }}/',{ lateness_policy_id: lateness_policy_id },function(data){
  // 		 	if (data==1) {
  // 		 		toastr.success("Lateness Policy Enabled",'Success');
  // 		 	}
  // 		 	if(data==2){
  // 		 		toastr.warning("Lateness Policy Disabled",'Success');
  // 		 	}
  // 		 	$( "#ldr" ).load('{{url('payrollsettings/payroll_policy')}}');
  // 		 });
  // 	});
  // });
  	
  	function prepareEditData(lateness_policy_id){
    $.get('{{ url('/payrollsettings/lateness_policy') }}/',{ lateness_policy_id: lateness_policy_id },function(data){
    	
     $('#edit_policy_name').val(data.policy_name);
     $('#edit_late_minute').val(data.late_minute);
     $('#edit_deduction').val(data.deduction);
     
    
    if (data.deduction_type==1) {
    	$("#edit_percentage").prop("checked", true);
    	$("#edit_amount").prop("checked", false);
    }else{
    	$("#edit_amount").prop("checked", true);
    	$("#edit_deduction").prop("checked", false);
    }
    
   	
     $('#editid').val(data.id);
    });
    $('#editLatenessPolicyModal').modal();
  }

  function deleteLatenessPolicy(lateness_policy_id){
    $.get('{{ url('/payrollsettings/delete_lateness_policy') }}/',{ lateness_policy_id: lateness_policy_id },function(data){
    	if (data=='success') {
 		toastr.success("Lateness Policy deleted successfully",'Success');
 		$( "#ldr" ).load('{{url('payrollsettings/payroll_policy')}}');
    	}else{
    		toastr.error("Error deleting Lateness Policy",'Success');
    	}
     
    });
  }
  </script>

