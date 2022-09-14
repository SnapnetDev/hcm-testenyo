@extends('layouts.master')
@section('stylesheets')
  <link rel="stylesheet" href="{{ asset('global/vendor/bootstrap-table/bootstrap-table.css') }}">
  <link rel="stylesheet" href="{{ asset('global/vendor/alertify/alertify.min.css') }}">
  <link rel="stylesheet" href="{{ asset('global/vendor/bootstrap-toggle/css/bootstrap-toggle.min.css')}}">
  <link rel="stylesheet" href="{{ asset('global/fonts/7-stroke/7-stroke.css')}}">
  <link rel="stylesheet" href="{{ asset('global/fonts/octicons/octicons.css')}}">
   <link href="{{ asset('global/vendor/select2/select2.min.css') }}" rel="stylesheet" />
   <link rel="stylesheet" type="text/css" href="http://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">


  <style type="text/css">
  	 .btn[disabled]{
  	 	pointer-events: none;
  	 	cursor: not-allowed;
  	 }
  </style>
@endsection

@section('content')
<!-- Page -->
  <div class="page ">
  	<div class="page-aside">
      <!-- Contacts Sidebar -->
      <div class="page-aside-switch">
        <i class="icon md-chevron-left" aria-hidden="true"></i>
        <i class="icon md-chevron-right" aria-hidden="true"></i>
      </div>
      <div class="page-aside-inner page-aside-scroll">
        <div data-role="container">
          <div data-role="content">
            <div class="page-aside-section">
              <div class="list-group">
                 <a class="list-group-item setting-linker active"  href="{{ url('payrollsettings/account') }}">
                 <i class="icon fa fa-credit-card setting-linker" aria-hidden="true"></i>{{__('Payroll Account Settings')}}
                </a>
                <a class="list-group-item setting-linker"  href="{{ url('payrollsettings/payslip') }}">
                 <i class="icon fa fa-file-text-o setting-linker" aria-hidden="true"></i>{{__('Payslip Detail Settings')}}
                </a>
                <a class="list-group-item setting-linker" href="{{ url('payrollsettings/salary_components') }}">
                 <i class="icon fa fa-outdent" aria-hidden="true"></i>{{__('Salary Component Settings')}}
                </a>
                <a class="list-group-item setting-linker" href="{{ url('payrollsettings/specific_salary_components') }}">
                 <i class="icon fa fa-indent" aria-hidden="true"></i>{{__('Specific Salary Component Settings')}}
                </a>
                <a class="list-group-item setting-linker" href="{{ url('payrollsettings/payroll_policy') }}">
                 <i class="icon oi-law setting-linker" aria-hidden="true"></i>{{__('Payroll Policy Settings')}}
                </a>
                <a class="list-group-item setting-linker" href="{{ url('payrollsettings/tmsa_policy') }}">
                 <i class="icon oi-law setting-linker" aria-hidden="true"></i>{{__('TMSA Policy Settings')}}
                </a>
                {{-- <a class="list-group-item setting-linker" href="{{ url('payrollsettings/loan_policy') }}">
                 <i class="icon oi-law setting-linker" aria-hidden="true"></i>{{__('Loan Policy Settings')}}
                </a> --}}
                <a class="list-group-item" href="javascript:void(0)">
                 <i class="icon pe-wallet setting-linker" aria-hidden="true"></i>{{__('Payroll Wallet Settings')}}
                </a>
               
              </div>
            </div>
            
            
            
            
          </div>
        </div>
      </div>
    </div>
    <div class="page-main">
    <div id="ldr">
  	
	
		
	</div>
	</div>
    
    
  	
	</div>
  <!-- End Page -->

@endsection
@section('scripts')
<script src="{{asset('global/vendor/bootstrap-table/bootstrap-table.min.js')}}"></script>
  <script src="{{asset('global/vendor/bootstrap-table/extensions/mobile/bootstrap-table-mobile.js')}}"></script>
  <script type="text/javascript" src="{{ asset('global/vendor/alertify/alertify.js') }}"></script>
  <script type="text/javascript" src="{{ asset('global/vendor/bootstrap-toggle/js/bootstrap-toggle.min.js')}}"></script>
  <script src="{{asset('global/vendor/select2/select2.min.js')}}"></script>
    
   <script type="text/javascript" src="http://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script type="text/javascript">
 
	$(function(){

    url=sessionStorage.getItem('phref')!=null ? sessionStorage.getItem('phref') : "{{url('payrollsettings/account')}}";
    // console.log(url);
    $( ".setting-linker" ).each(function() {
        $( this ).attr( "href" )==sessionStorage.getItem('phref')?$(this).addClass( "active" ):$(this).removeClass( "active" );
      });
    
  href = $(this).attr('href');
		$( "#ldr" ).load(url);



	});
	$(document).on('click','.linker',function(event){
		event.preventDefault();
	href = $(this).attr('href');
  sessionStorage.setItem('phref',href);
	// console.log(href);
	$( "#ldr" ).load( href );
	});


	$(document).on('click','.setting-linker',function(event){
		event.preventDefault();
		$( ".setting-linker" ).each(function() {
			  $( this ).removeClass( "active" );
			});
		$(this).addClass( "active" );
	href = $(this).attr('href');
  sessionStorage.setItem('phref',href);
	$( "#ldr" ).load( href );
	});
</script>

  
@endsection
