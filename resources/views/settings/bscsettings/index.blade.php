	<style type="text/css">
  .head>tr> th{
    color: #fff;
  }
  .my-btn.btn-sm {
    font-size: 0.7.5rem;
    width: 1.5rem;
    height: 1.5rem;
    padding: 0;
}
</style>
	<div class="page-header">
  		<h1 class="page-title">{{__('All Settings')}}</h1>
		  <ol class="breadcrumb">
		    <li class="breadcrumb-item"><a href="{{url('/')}}">{{__('Home')}}</a></li>
		    <li class="breadcrumb-item ">{{__('Balance Score Card')}}</li>
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
		              <h3 class="panel-title">Balance Score Card Department Weight Percentages</h3>
		              <div class="panel-actions">
                			

              			</div>
		            	</div>
		            <div class="panel-body">
		            
	                  <table id="exampleTablePagination" data-toggle="table" 
		                  data-query-params="queryParams" data-mobile-responsive="true"
		                  data-height="400" data-pagination="true" data-search="true" class="table table-striped datatable"   >
		                    <thead>
		                      <tr>
		                        <th >Department</th>
		                        <th>Company</th>
		                        <th >Grade Category</th>
		                        <th>Perspective</th>
		                        <th>Percentage</th>
		                        <th>Action</th>
		                        </tr> 
		                    </thead>
		                    <tbody>
		                    	@forelse($departments as $department)
		                    	
		                    	
				            	@forelse($grade_categories as $grade_category)
				            				
	            					@foreach($metrics as $metric)
	            					<tr>
				            		<td >{{$department->name}}</td>
				            		<td >{{$department->company->name}}</td>
				            		<td>{{$grade_category->name}}</td>
				            		<td>{{$metric->name}}</td>
	            					@php
	            						$weight=bscweight($department->id,$grade_category->id,$metric->id);
	            					@endphp
	                        	 	<td class="weight" id="td_{{$weight->id}}">
	                        	 		{{$weight->percentage}}
	                        	 	</td>
	                        	 	<td><button class="btn btn-primary weightbtn" id="{{$weight->id}}"><i class="fa fa-pencil"></i> </button> </td>
	                        	 	@endforeach
	                        	 	
				            				</tr>
				            		@empty
				            		@endforelse
		                    	
		                    	@empty
		                    	@endforelse
		                    	
		                    </tbody>
	                  </table>
	          		</div>
	          		
		          </div>
		          <div class="panel panel-info panel-line">
		            <div class="panel-heading">
		              <h3 class="panel-title">Measurement Period</h3>
		              <div class="panel-actions">
                			<button class="btn btn-info" data-toggle="modal" data-target="#addMeasurementPeriodModal">Add Measurement Period</button>

              			</div>
		            	</div>
		            <div class="panel-body">
		            
	                  <table id="exampleTablePagination" data-toggle="table" 
		                  data-query-params="queryParams" data-mobile-responsive="true"
		                  data-height="400" data-pagination="true" data-search="true" class="table table-striped datatable"   >
		                    <thead>
		                      <tr>
		                        <th >From</th>
		                        <th>To</th>
		                        <th>Created On</th>
		                        <th>Action</th>
		                        </tr> 
		                    </thead>
		                    <tbody>
		                    	@forelse($measurement_periods as $measurement_period)
		                    	
		                    	
				            	
	            					<tr>
				            		<td >{{date('F-Y',strtotime($measurement_period->from))}}</td>
				            		<td >{{date('F-Y',strtotime($measurement_period->to))}}</td>
				            		<td>{{date("F j, Y",strtotime($measurement_period->created_at))}}</td>
				            		<td>
		                    			<div class="btn-group" role="group">
					                    <button type="button" class="btn btn-primary dropdown-toggle" id="exampleIconDropdown1"
					                    data-toggle="dropdown" aria-expanded="false">
					                      Action
					                    </button>
				                    <div class="dropdown-menu" aria-labelledby="exampleIconDropdown1" role="menu">
				                      <a class="dropdown-item editmp" id="{{$measurement_period->id}}" ><i class="fa fa-pencil" aria-hidden="true"></i>&nbsp;Edit Measurement Period</a>
				                      
				                    </div>
				                  </div></td>
	            					</tr>
		                    	@empty
		                    	@endforelse
		                    	
		                    </tbody>
	                  </table>
	          		</div>
	          		
		          </div>
		          {{-- start balance scorecard perspective --}}

		           {{-- end balance scorecard perspective --}}
		            {{-- start balance scorecard measurement period --}}
		             {{-- end balance scorecard measurement period--}}
	        	</div>
	    	</div>
	    	<div class="col-md-12 col-xs-12">
	    		
	    	</div>
		</div>
	  </div>
	  {{-- Add Company Modal --}}
	   @include('settings.bscsettings.modals.editweight')
	   @include('settings.bscsettings.modals.addmeasurementperiod')
	   @include('settings.bscsettings.modals.editmeasurementperiod')
	 
	  <!-- End Page -->
	    <script type="text/javascript">
  	$(function() {

  		// var DateCreated = new Date(Date.parse('2019-01-11')).format("MM/dd/yyyy");
  		
   $('.datatable').DataTable();
   $('#weighttable').editableTableWidget();
    $('.datepicker').datepicker({
    autoclose: true,
    format:'mm-yyyy',
     viewMode: "months", 
    minViewMode: "months"
});


//    $('.weight').on('change', function(evt, newValue) {
// 	var total=0;
// 	$(this).siblings().each(function() {
//             total+=parseInt(this.value);
           	 
//         });
// 	if(total>100){
// 		toastr.error('Total Percentage cannot be more than 100');
// 		return;
// 	}
// 	id=$(this).attr('id');
// 	value=$(this).val();
// 	$.get(
//    "{{url('bscsettings/weight')}}", 
//    { id:id, value: value }, // put your parameters here
//    function(date){
//       toastr.success("Changes saved successfully",'Success');
//    },
//    'html'
// );

// });

$('.weightbtn').on('click', function(event) {
	id=$(this).attr('id');


	$.get('{{ url('bscsettings/get_weight') }}',{weight_id:id},function(data){
    	
     $('#editwpercentage').val(data.percentage);
     $('#editwid').val(data.id);
      $('#editwcompany').val(data.department.company.name);
       $('#editwdepartment').val(data.department.name);
        $('#editwperspective').val(data.metric.name);
         $('#editwgradecategory').val(data.grade_category.name);

    });
    $('#editWeightModal').modal();
});
$('.editmp').on('click', function(event) {
	id=$(this).attr('id');


	$.get('{{ url('bscsettings/get_measurement_period') }}',{mp_id:id},function(data){
    	

     $('#editmpfrom').val(formatMPDate(data.from));
     $('#editmpid').val(data.id);
      $('#editmpto').val(formatMPDate(data.to));
    });
    $('#editMeasurementPeriodModal').modal();
});

  	$(document).on('submit','#editWeightForm',function(event){
		 event.preventDefault();
		 var form = $(this);
		    var formdata = false;
		    if (window.FormData){
		        formdata = new FormData(form[0]);
		    }
		    $.ajax({
		        url         : '{{url('bscsettings')}}',
		        data        : formdata ? formdata : form.serialize(),
		        cache       : false,
		        contentType : false,
		        processData : false,
		        type        : 'POST',
		        success     : function(data, textStatus, jqXHR){
		        	
		        	var newpercentage = document.forms['editWeightForm'].elements['percentage'].value;
		        	var weightid=document.forms['editWeightForm'].elements['weight_id'].value;
		        	$('#td_'+weightid).html(newpercentage);
		            toastr.success("Changes saved successfully",'Success');
		           $('#editWeightModal').modal('toggle');
					// $( "#ldr" ).load('{{url('bscsettings')}}');

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
  	$(document).on('submit','#addMeasurementPeriodForm',function(event){
		 event.preventDefault();
		 var form = $(this);
		    var formdata = false;
		    if (window.FormData){
		        formdata = new FormData(form[0]);
		    }
		    $.ajax({
		        url         : '{{url('bscsettings')}}',
		        data        : formdata ? formdata : form.serialize(),
		        cache       : false,
		        contentType : false,
		        processData : false,
		        type        : 'POST',
		        success     : function(data, textStatus, jqXHR){

		            toastr["success"]("Changes saved successfully",'Success');
		            $('#addMeasurementPeriodModal').modal('toggle');
					$( "#ldr" ).load('{{url('bscsettings')}}');
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
  	$(document).on('submit','#editMeasurementPeriodForm',function(event){
		 event.preventDefault();
		 var form = $(this);
		    var formdata = false;
		    if (window.FormData){
		        formdata = new FormData(form[0]);
		    }
		    $.ajax({
		        url         : '{{url('bscsettings')}}',
		        data        : formdata ? formdata : form.serialize(),
		        cache       : false,
		        contentType : false,
		        processData : false,
		        type        : 'POST',
		        success     : function(data, textStatus, jqXHR){

		            toastr["success"]("Changes saved successfully",'Success');
		            $('#editMeasurementPeriodModal').modal('toggle');
					$( "#ldr" ).load('{{url('bscsettings')}}');
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
  	function prepareMPEditData(mp_id){
    $.get('{{ url('bscsettings/metric') }}/'+company_id,function(data){
    	console.log(data);
     $('#editname').val(data.name);
     $('#editid').val(data.id);
     $('#editemail').val(data.email);
     $('#editaddress').val(data.address);
     $('#edituser').val(data.user_id);
    });
    $('#editCompanyModal').modal();
  }
  function formatMPDate(date){
  	var d = new Date(date);
         month = '' + (d.getMonth() + 1);
         day = '' + d.getDate();
         year = d.getFullYear();

     if (month.length < 2) month = '0' + month;
     if (day.length < 2) day = '0' + day;

    return [month,year].join('-');
  		
  }
  
  
  </script>

