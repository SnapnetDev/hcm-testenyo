@extends('layouts.master')
@section('stylesheets')
 <link rel="stylesheet" href="{{ asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.min.css')}}"> 
 <link rel="stylesheet" href="{{ asset('global/vendor/datatables-bootstrap/dataTables.bootstrap.css') }}">
 <link rel="stylesheet" href="{{ asset('global/vendor/morris/morris.css')}}">
@endsection
@section('content')
<!-- Page -->
  <div class="page ">
  	<div class="page-header">
  		<h1 class="page-title">{{__('Employee Payroll')}}</h1>
		  <ol class="breadcrumb">
		    <li class="breadcrumb-item"><a href="{{url('/')}}">{{__('Home')}}</a></li>
		    <li class="breadcrumb-item active">{{__('Employee Payroll')}}</li>
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
    
		<div class="page-content container-fluid bg-white">
			@if($has_been_run==1)
	      	<div class="row " style="padding-top:20px; padding-bottom: 30px;">
	      		<div class="col-md-2">
	      			
	      		</div>
	      		<div class="col-md-3 ">
	      			
	      			<div id="exampleMorrisDonut" style="height: 250px;"></div>
	      			<form id="monthForm" method="GET" action="{{url('compensation/payroll_list')}}" >
	                 <div class="input-group">
	               
	                   
	                    <input type="text" id="" placeholder="mm-yyyy" name="month" class="form-control datepicker">
	                      
	                    <span class="input-group-btn">
	                      <button type="submit" class="btn btn-primary"><i class="icon fa fa-search" aria-hidden="true"></i></button>
	                    </span>
	                    
	                   
	                  </div>
	                  </form>
	      		</div>
	      		<div class="col-md-7">
	      			
	                <ul class="list-group list-group-dividered ">
	            	<li class="list-group-item"><strong>Payroll For:	{{date('M-Y',strtotime($date))}}</strong></li>
	                  <li class="list-group-item">Wallet Balance:</li>
	                  <li class="list-group-item">Salary:&#8358;{{number_format( $salary,2)}}</li>
	                 <li class="list-group-item">Allowances:&#8358;{{number_format( $allowances,2)}}</li>
	                  <li class="list-group-item">Deductions:&#8358;{{number_format( $deductions+$income_tax,2)}}</li>
	                  <li class="list-group-item">Total Net Pay :&#8358;{{number_format( ($salary+$allowances-( $deductions+$income_tax))),2)}}</li>
	                </ul>
	                <div class="btn-group btn-group-justified">
	                	@if($payroll->payslip_issued==0)
	                    <div class="btn-group" role="group">
	                    	
	                      <button type="button" id="payslipbtn" class="btn btn-primary btn-outline" onclick="issuePayslip({{$payroll->id}})">
	                        <i class="icon fa fa-list-alt" aria-hidden="true"></i>
	                        <br>
	                        <span class="text-uppercase hidden-sm-down">Issue Payslip</span>
	                      </button>
	                    </div>
	                    @endif
	                    <div class="btn-group" role="group">
	                      <button type="button" class="btn btn-info btn-outline">
	                        <i class="icon fa fa-money" aria-hidden="true"></i>
	                        <br>
	                        <span class="text-uppercase hidden-sm-down">Run Payment</span>
	                      </button>
	                    </div>
	                    <div class="btn-group" role="group">
	                      <button type="button" class="btn btn-danger btn-outline" onclick="rollbackPayroll({{$payroll->id}})">
	                        <i class="icon fa fa-refresh" aria-hidden="true"></i>
	                        <br>
	                        <span class="text-uppercase hidden-sm-down">Rollback Payroll</span>
	                      </button>
	                    </div>
	                    <div class="btn-group" role="group">
	                      <a type="button" class="btn btn-success btn-outline" href="{{ url('compensation/exportforexcel?payroll_id='.$payroll->id) }}">
	                        <i class="icon fa fa-file-excel-o" aria-hidden="true"></i>
	                        <br>
	                        <span class="text-uppercase hidden-sm-down">Export Payroll</span>
	                      </a>
	                    </div>
	                    <div class="btn-group" role="group">
	                      <a type="button" class="btn btn-success btn-outline" href="{{ url('compensation/exportford365?payroll_id='.$payroll->id) }}">
	                        <i class="icon fa fa-download" aria-hidden="true"></i>
	                        <br>
	                        <span class="text-uppercase hidden-sm-down">Export for NAV</span>
	                      </a>
	                    </div>
	              	</div>

	          	</div>
	  		</div>
	  		 <div class="panel panel-info panel-line">
                <div class="panel-heading">
                  <h3 class="panel-title">Employee List</h3>
                  <div class="panel-actions">
                    
                  
                </div>
            	</div>
                <div class="panel-body">
	  			<table class="table table-striped" id="payroll-table">
	  				<thead>
	  					<tr>
	  						<th></th>
	  						<th>Employee Number</th>
	  						<th>Employee Name</th>
	  						<th>Grade</th>
	  						<th>Gross pay</th>
	  						<th>Action</th>
	  					</tr>
	  				</thead>
	  				<tbody>
	  					@php
	  						$sn=1;
	  					@endphp
	  					@foreach ($payroll->payroll_details as $detail)
	  						<tr>
	  							<td>{{$sn}}</td>
	  						<td>{{$detail->user->emp_num}}</td>
	  						<td>{{$detail->user->name}}</td>
	  						<td>{{$detail->user->promotionHistories()->latest()->first()->grade->level}}</td>
	  						<td>&#8358;{{number_format( $detail->user->promotionHistories()->latest()->first()->grade->basic_pay,2)}}</td>
	  						<td> <a onclick="viewMore({{$detail->id}})" class="text-center"><i class="btn btn-sm btn-primary waves-effect icon fa fa-eye" aria-hidden="true" title="view"></i></a></td>
	  					</tr>
	  					@php
	  						$sn++;
	  					@endphp
	  					@endforeach
	  					
	  				</tbody>
	  			</table>
		  		</div>
		  	</div>

  	<div class="modal fade in modal-3d-flip-horizontal modal-info" id="userPayrollDetailsModal" aria-hidden="true" aria-labelledby="attendanceDetailsModal" role="dialog" tabindex="-1">
      <div class="modal-dialog modal-lg" >
        <div class="modal-content">        
          <div class="modal-header" >
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title" id="training_title">Employee Payroll Details </h4>
          </div>
            <div class="modal-body">         
                <div class="row row-lg col-xs-12">            
                  <div class="col-xs-12" id="detailLoader"> 
                    
                  </div>
                  <div class="clearfix hidden-sm-down hidden-lg-up"></div>            
                </div>        
            </div>
            <div class="modal-footer">
              <div class="col-xs-12">
                
                  
                  <!-- End Example Textarea -->
                </div>
             </div>
         </div>
      </div>
    </div>
		  	@elseif($has_been_run==0)
		  	<div class="row " style="padding-top:20px; padding-bottom: 30px;">
	      		<div class="col-md-4">
	      		</div>
	      		<div class="col-md-5">
	      			
	               
	               
	                    
	                      <a  class="btn btn-primary pull-right" href="{{url('compensation/runpayroll?month='.date('m-Y',strtotime($date)))}}">
	                        <i class="icon fa fa-list-alt" aria-hidden="true"></i>
	                        
	                        <span class="text-uppercase hidden-sm-down">Run Payroll</span>
	                      </a>
	                   
	              

	          	</div>
	      		<div class="col-md-3 ">
	      			<form id="monthForm" method="GET" action="{{url('compensation/runpayroll')}}" >
	                 <div class="input-group">
	               
	                   
	                    <input type="text" id="" placeholder="mm-yyyy" name="month" class="form-control datepicker">
	                      
	                    <span class="input-group-btn">
	                      <button title="search month" type="submit" class="btn btn-primary"><i class="icon fa fa-search" aria-hidden="true"></i></button>
	                    </span>
	                    
	                   
	                  </div>
	                  </form>
	      		</div>
	      		
	  		</div>
	  		 <div class="panel panel-info panel-line">
                <div class="panel-heading">
                  <h3 class="panel-title">Employee List</h3>
                  <div class="panel-actions">
                   
                  
                </div>
            	</div>
                <div class="panel-body">
	  			<table class="table table-striped" id="payroll-table">
	  				<thead>
	  					<tr>
	  						<th>Employee Number</th>
	  						<th>Employee Name</th>
	  						<th>Grade</th>
	  						<th>Gross pay</th>
	  						<th>Action</th>
	  					</tr>
	  				</thead>
	  				<tbody>
	  					@foreach ($employees as $employee)
	  						<tr>
	  						<td>{{$employee->emp_num}}</td>
	  						<td>{{$employee->name}}</td>
	  						<td>{{$employee->promotionHistories()->latest()->first()->grade->level}}</td>
	  						<td>{{$employee->promotionHistories()->latest()->first()->grade->basic_pay}}</td>
	  						<td></td>
	  					</tr>
	  					@endforeach
	  					
	  				</tbody>
	  			</table>
		  		</div>
		  	</div>
			@endif
	  		</div>
		</div> 

      	</div>
</div>
  <!-- End Page -->
  
@endsection
@section('scripts')
<script src="{{asset('global/vendor/bootstrap-table/bootstrap-table.min.js')}}"></script>
  <script src="{{asset('global/vendor/bootstrap-table/extensions/mobile/bootstrap-table-mobile.js')}}"></script>
  <script type="text/javascript" src="{{ asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>
  <script src="{{ asset('global/vendor/datatables/jquery.dataTables.js') }}"></script>
  <script src="{{ asset('global/vendor/datatables-fixedheader/dataTables.fixedHeader.js') }}"></script>
  <script src="{{ asset('global/vendor/datatables-bootstrap/dataTables.bootstrap.js') }}"></script>
  <script src="{{ asset('global/vendor/raphael/raphael-min.js')}}"></script>
  <script src="{{ asset('global/vendor/morris/morris.min.js')}}"></script>
  <script type="text/javascript">
  	  $(document).ready(function() {
    $('.datepicker').datepicker({
    autoclose: true,
    format:'mm-yyyy',
     viewMode: "months", 
    minViewMode: "months"
});
     $('#payroll-table').DataTable( );
    
    
    });
  	  @if($has_been_run==1)
  	 
  	   new Morris.Donut({
      element: 'exampleMorrisDonut',
      data: [{
        label: "Salary",
        value: {{$salary}}
      }, {
        label: "Allowances",
        value: {{$allowances}}
      }, {
        label: "Deductions",
        value: {{$deductions+$income_tax}}
      }, ],
      // barSizeRatio: 0.35,
      resize: true,
      colors: [Config.colors("red", 500), Config.colors("primary", 500), Config.colors("grey", 400)]
    });

  	   @endif
function viewMore(detail_id)
{
  
      $("#detailLoader").load('{{ url('/compensation/user_payroll_detail') }}/?payroll_detail_id='+detail_id);
    $('#userPayrollDetailsModal').modal();
  
}
@if($has_been_run==1)
function issuePayslip(payroll_id){
    $.get('{{ url('/compensation/issuepayslip') }}/',{ payroll_id: {{$payroll->id}}},function(data){
      if (data=='success') {
    toastr.success("Payslip Issued successfully",'Success');
    location.reload();
      }else{
        toastr.error("Error issuing payslip",'Error');
      }
     
    });
  }
  function rollbackPayroll(payroll_id){
    $.get('{{ url('/compensation/rollback') }}/',{ payroll_id: {{$payroll->id}}},function(data){
      if (data=='success') {
    toastr.success("Rollback Successful",'Success');
    location.reload();
      }else{
        toastr.error("Error Rolling Back",'Error');
      }
     
    });
  }
  @endif
  </script>
@endsection