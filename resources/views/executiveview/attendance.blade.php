@extends('layouts.master')
@section('stylesheets')
  
@endsection
@section('content')
<!-- Page -->
  <div class="page ">
  	<div class="page-header">
  		<h1 class="page-title">{{__('Executive Attendance Report')}}</h1>
		  <ol class="breadcrumb">
		    <li class="breadcrumb-item"><a href="{{url('/')}}">{{__('Home')}}</a></li>
		    <li class="breadcrumb-item active">{{__('Executive Attendance Report')}}</li>
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
      	{{--<iframe width="100%" height="800" src="https://app.powerbi.com/view?r=eyJrIjoiMjRhNTZjODMtZTFmOC00YWE0LTg3ZDQtZjQ0ODg0NTVkNWIwIiwidCI6ImJhMTMwZWNhLTMwMzAtNDhlMS05MDg5LWM5NzkyOTNhZWI3MCIsImMiOjh9" frameborder="0" allowFullScreen="true"></iframe>--}}
		  {{--<iframe width="100%" height="800" src="https://app.powerbi.com/reportEmbed?reportId=88e05e06-67fa-4a09-ada9-2ca44551e948&autoAuth=true&ctid=ba130eca-3030-48e1-9089-c979293aeb70&config=eyJjbHVzdGVyVXJsIjoiaHR0cHM6Ly93YWJpLW5vcnRoLWV1cm9wZS1yZWRpcmVjdC5hbmFseXNpcy53aW5kb3dzLm5ldCJ9" frameborder="0" allowFullScreen="true"></iframe>--}}
		  {{-- <iframe width="100%" height="750" src="//report.pali365.com/external-report/3" frameborder="0" allowFullScreen="true"></iframe> --}}

		  <iframe title="Time Attendance Report" width="100%" height="600" src="https://app.powerbi.com/view?r=eyJrIjoiYTYxMGJmMTEtYjBlMC00MGQyLTgyMjgtYjVhOWUxNzc3ZTA3IiwidCI6ImJhMTMwZWNhLTMwMzAtNDhlMS05MDg5LWM5NzkyOTNhZWI3MCIsImMiOjh9" frameborder="0" allowFullScreen="true"></iframe>
    </div>
  	
	</div>
  <!-- End Page -->
@endsection
@section('scripts')
<script src="{{asset('global/vendor/bootstrap-table/bootstrap-table.min.js')}}"></script>
  <script src="{{asset('global/vendor/bootstrap-table/extensions/mobile/bootstrap-table-mobile.js')}}"></script>
@endsection