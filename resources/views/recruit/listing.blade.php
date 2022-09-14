@extends('layouts.master')
@section('stylesheets')
 <link rel="stylesheet" href="{{ asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.min.css')}}"> 
 <link rel="stylesheet" href="{{ asset('global/vendor/datatables-bootstrap/dataTables.bootstrap.css') }}">
 <link rel="stylesheet" href="{{ asset('global/vendor/morris/morris.css')}}">
 <link rel="stylesheet" href="{{ asset('assets/examples/css/apps/work.css')}}">
@endsection
@section('content')
<!-- Page -->
  <div class="page ">
  	<div class="page-header">
  		<h1 class="page-title">{{__('Recruit')}}</h1>
		  <ol class="breadcrumb">
		    <li class="breadcrumb-item"><a href="{{url('/')}}">{{__('Home')}}</a></li>
		    <li class="breadcrumb-item active">{{__('Recruit')}}</li>
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
    
		<div class="page-content">

      	<div class="panel panel-bordered">
            <div class="panel-heading">
              <h3 class="panel-title">Job Title</h3>
              {{-- <div class="panel-actions">
              	<a href="#" class=" panel-action btn btn-info"><i class="icon md-edit"></i>Edit</a>
              </div> --}}
            </div>
            <div class="panel-body">
            	<div class="ribbon ribbon-clip ribbon-reverse ribbon-primary">
                        <span class="ribbon-inner"><a href="#">Ribbon</a></span>
                      </div>
                      <div class="ribbon ribbon-clip">
                        <span class="ribbon-inner"><a href="#">Ribbon</a></span>
                      </div>
              <h4><i class="icon md-graduation-cap"></i>Minimum Educational Qualification</h4>
              <p>Easily add a heading container to your panel with <code>.panel-heading</code>.
                You may also include any <code>&lt;h1&gt;</code>-<code>&lt;h6&gt;</code>                with a <code>.panel-title</code> class to add a pre-styled heading.</p>
              <p>For proper link coloring, be sure to place links in headings within
                <code>.panel-title</code>.</p>
            </div>
          </div>

          <div class="site-action" data-plugin="actionBtn">
    <button type="button" class=" btn-raised btn btn-success btn-floating" data-toggle="modal" data-target="#addJoblistingModal">
      <i class="icon md-plus animation-scale-up" aria-hidden="true"></i>
      
    </button>
    </div>
		</div> 

      	</div>
</div>
  <!-- End Page -->\
  @include('recruit.modals.addJoblisting')
  
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
    });
  </script>
@endsection