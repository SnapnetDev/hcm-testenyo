@extends('layouts.master')
@section('stylesheets')
 <link rel="stylesheet" href="{{ asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.min.css')}}"> 
 <link href="{{ asset('global/vendor/select2/select2.min.css') }}" rel="stylesheet" />
@endsection
@section('content')
<!-- Page -->
  <div class="page ">
    <div class="page-header">
      <h1 class="page-title">{{__('Leave Request')}}</h1>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{url('/')}}">{{__('Home')}}</a></li>
        <li class="breadcrumb-item active">{{__('Leave Request')}}</li>
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
        <div class="row" data-plugin="matchHeight" data-by-row="true">
  <!-- First Row -->
  <div class="col-xl-3 col-md-6 col-xs-12 info-panel">
    <div class="card card-shadow">
      <div class="card-block bg-white p-20">
        <button type="button" class="btn btn-floating btn-sm btn-warning" data-toggle="modal" data-target="#holidays">
          <i class="fa fa-lg fa-plane"></i>
        </button>
        <span class="m-l-15 font-weight-400">HOLIDAYS</span>
        <div class="content-text text-xs-center m-b-0">
          <i class="text-success icon wb-triangle-up font-size-20"></i>
          <span class="font-size-40 font-weight-100">{{count($holidays)}} </span>
          <p class="blue-grey-400 font-weight-100 m-0">Recognised Public Holidays</p>
        </div>
      </div>
    </div>
  </div>
  <div class="col-xl-3 col-md-6 col-xs-12 info-panel">
    <div class="card card-shadow">
      <div class="card-block bg-white p-20">
        <button type="button" class="btn btn-floating btn-sm btn-danger">
          <i class="fa fa-calendar-o"></i>
        </button>
        <span class="m-l-15 font-weight-400">LEAVE BANK</span>
        <div class="content-text text-xs-center m-b-0">
          <i class="text-success icon wb-triangle-down font-size-20">
          </i>
          <span class="font-size-40 font-weight-100">
            
            {{$leavebank>0?$leavebank:0}} Days
           
          </span>
          <p class="blue-grey-400 font-weight-100 m-0">Annual Leave Due</p>
        </div>
      </div>
    </div>
  </div>
   <div class="col-xl-3 col-md-6 col-xs-12 info-panel">
    <div class="card card-shadow">
      <div class="card-block bg-white p-20">
        <button type="button" class="btn btn-floating btn-sm btn-danger">
          <i class="fa fa-calendar-o"></i>
        </button>
        <span class="m-l-15 font-weight-400">LEAVE BANK</span>
        <div class="content-text text-xs-center m-b-0">
          <i class="text-success icon wb-triangle-down font-size-20">
          </i>
          <span class="font-size-40 font-weight-100">
            
            {{$oldleaveleft>0?$oldleaveleft:0}} Days
           
          </span>
          <p class="blue-grey-400 font-weight-100 m-0">Spill Over days</p>
        </div>
      </div>
    </div>
  </div>
  <div class="col-xl-3 col-md-6 col-xs-12 info-panel">
    <div class="card card-shadow">
      <div class="card-block bg-white p-20">
        <button type="button" class="btn btn-floating btn-sm btn-danger">
          <i class="fa fa-calendar-o"></i>
        </button>
        <span class="m-l-15 font-weight-400">LEAVE USED</span>
        <div class="content-text text-xs-center m-b-0">
          <i class="text-success icon wb-triangle-down font-size-20">
          </i>
          <span class="font-size-40 font-weight-100">
            
            {{$used_days>0?$used_days:0}} Days
           
          </span>
          <p class="blue-grey-400 font-weight-100 m-0">Annual Leave Due</p>
        </div>
      </div>
    </div>
  </div>
  <div class="col-xl-3 col-md-6 col-xs-12 info-panel">
    <div class="card card-shadow">
      <div class="card-block bg-white p-20">
        <button type="button" class="btn btn-floating btn-sm btn-danger">
          <i class="fa fa-calendar-o"></i>
        </button>
        <span class="m-l-15 font-weight-400">LEAVE REMAINING</span>
        <div class="content-text text-xs-center m-b-0">
          <i class="text-success icon wb-triangle-down font-size-20">
          </i>
          <span class="font-size-40 font-weight-100">
            
            {{$leaveleft>0?$leaveleft:0}} Days
           
          </span>
          <p class="blue-grey-400 font-weight-100 m-0">Annual Leave Due</p>
        </div>
      </div>
    </div>
  </div>
  <div class="col-xl-3 col-md-6 col-xs-12 info-panel">
    <div class="card card-shadow">
      <div class="card-block bg-white p-20">
        <button type="button" class="btn btn-floating btn-sm btn-success" data-toggle="modal" data-target="#requests">
          <i class="fa fa-question"></i>
        </button>
        <span class="m-l-15 font-weight-400">REQUESTS</span>
        <div class="content-text text-xs-center m-b-0">
          <i class="text-danger icon wb-triangle-up font-size-20">
          </i>
          <span class="font-size-40 font-weight-100">{{count($leave_requests)}}</span>
          <p class="blue-grey-400 font-weight-100 m-0">
            
            {{count($pending_leave_requests)}} Pending Approvals
          </p>
        </div>
      </div>
    </div>
  </div>
  <div class="col-xl-3 col-md-6 col-xs-12 info-panel">
    <div class="card card-shadow">
      <div class="card-block bg-white p-20">
        <button type="button" class="btn btn-floating btn-sm btn-primary" data-toggle="modal" data-target="#categories">
          <i class="fa fa-cubes"></i>
        </button>
        <span class="m-l-15 font-weight-400">Leave Type</span>
        <div class="content-text text-xs-center m-b-0">
          <i class="text-success icon wb-triangle-up font-size-20">
          </i>
          <span class="font-size-40 font-weight-100">{{count($leaves)}}</span>
          <p class="blue-grey-400 font-weight-100 m-0">Leave Type</p>
        </div>
      </div>
    </div>
  </div>
  <!-- End First Row -->
  {{-- second row --}}
  <div class="col-ms-12 col-xs-12 col-md-12">
    <div class="panel panel-info panel-line">
                <div class="panel-heading">
                  <h3 class="panel-title">New Leave Request</h3>
                  <div class="panel-actions">
                      <button class="btn btn-info" data-toggle="modal" data-target="#addLeaveRequestModal">New Leave Request</button>
                    
                    </div>
                  </div>
                <div class="panel-body">
                  <div class="table-responsive">
         <table class="table table striped">
          <thead>
            <tr>
            <th>Leave Type</th>
            <th>Starts</th>
            <th>Ends</th>
            <th>Priority</th>
            <th>Reason</th>
            <th>Approval Status</th>
            <th>With Pay</th>
            <th>Action</th>
          </tr>
          </thead>
          <tbody>
            <tr>
          @foreach($leave_requests as $leave_request)
          <td>{{$leave_request->leave_name}}</td>
            <td>{{date("F j, Y", strtotime($leave_request->start_date))}}</td>
            <td>{{date("F j, Y", strtotime($leave_request->end_date))}}</td>
            <td><span class=" tag tag-outline  {{$leave_request->priority==0?'tag-success':($leave_request->priority==1?'tag-warning':'tag-danger')}}">{{$leave_request->priority==0?'normal':($leave_request->priority==1?'medium':'high')}}</span></td>
            <td>{{$leave_request->reason}}</td>
            <td><span class=" tag   {{$leave_request->status==0?'tag-warning':($leave_request->status==1?'tag-success':'tag-danger')}}">{{$leave_request->status==0?'pending':($leave_request->status==1?'approved':'rejected')}}</span></td>
            <td>{{$leave_request->paystatus==0?'without pay':'with pay'}}</td>
            <td>
              <div class="btn-group" role="group">
                  <button type="button" class="btn btn-primary dropdown-toggle" id="exampleIconDropdown1"
                  data-toggle="dropdown" aria-expanded="false">
                    Action
                  </button>
                <div class="dropdown-menu" aria-labelledby="exampleIconDropdown1" role="menu">
                  <a style="cursor:pointer;"class="dropdown-item" id="{{$leave_request->id}}" onclick="viewRequestApproval(this.id)"><i class="fa fa-eye" aria-hidden="true"></i>&nbsp;View Approval</a>
                  
                </div>
              </div>
            </td>
        </tr>
          @endforeach
         
          </tbody>
          
         </table>
         </div> 
         
         
      </div>
  </div>

    </div>
    
  </div>
</div>
</div>
  <!-- End Page -->
   @include('leave.modals.addrequest')
   {{-- Leave Request Details Modal --}}
   <div class="modal fade in modal-3d-flip-horizontal modal-info" id="leaveDetailsModal" aria-hidden="true" aria-labelledby="leaveDetailsModal" role="dialog" tabindex="-1">
      <div class="modal-dialog modal-lg" >
        <div class="modal-content">        
          <div class="modal-header" >
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title" id="training_title">Leave Request Details</h4>
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
@endsection
@section('scripts')
<script src="{{asset('global/vendor/bootstrap-table/bootstrap-table.min.js')}}"></script>
  <script src="{{asset('global/vendor/bootstrap-table/extensions/mobile/bootstrap-table-mobile.js')}}"></script>
  <script type="text/javascript" src="{{ asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>
    <script src="{{asset('global/vendor/select2/select2.min.js')}}"></script>
  <script type="text/javascript">

let check = 0;
      let vl = 0;

      function doValidate(v){
        vl = v || vl;
        check = vl - $('#leave_days_requested').val();
        if (check < 0){
           alert('Your leave days cannot exceed your entitled days (' + $('#leave_days_requested').val() + ')');
         }
        return {
          check: check > 0,
          value: check
        };
      } 


      $(document).ready(function() {

    $('.input-daterange').datepicker({
    autoclose: true,
    format:'yyyy-mm-dd'
});
    $(document).on('submit','#addLeaveRequestForm',function(event){
      event.preventDefault();

      if (doValidate().check){

        var form = $(this);
        var formdata = false;
        if (window.FormData){
            formdata = new FormData(form[0]);
        }
        $.ajax({
            url         : '{{route('leave.store')}}',
            data        : formdata ? formdata : form.serialize(),
            cache       : false,
            contentType : false,
            processData : false,
            type        : 'POST',
            success     : function(data, textStatus, jqXHR){

                 toastr.success("Changes saved successfully",'Success');
                $('#addLeaveRequestModal').modal('toggle');
          
            },
            error:function(data, textStatus, jqXHR){
               jQuery.each( data['responseJSON'], function( i, val ) {
                jQuery.each( val, function( i, valchild ) {
                toastr.error(valchild[0]);
              });  
              });
            }
        });


      }
      
    });


    });
      function viewRequestApproval(leave_request_id)
{
   $(document).ready(function() {
      $("#detailLoader").load('{{ url('/leave/getdetails') }}?leave_request_id='+leave_request_id);
    $('#leaveDetailsModal').modal();
  });
  
}


    $(function() {
       




     $('#abtype').on('change', function() {
      leave_id= $(this).val();
      
        $.get('{{ url('/leave/get_leave_length') }}/',{ leave_id: leave_id },function(data){
        $('#leavelength').val(data.balance);
         $('#paystatus').val(data.paystatus);
         
         if (doValidate(data.balance).check){
           $('#leaveremaining').val(doValidate(data.balance).value);  
         }
        //  let check = data.balance - $('#leave_days_requested').val();
        //  if (check < 0){
        //    alert('Your leave days cannot exceed your entitled days (' + $('#leave_days_requested').val() + ')');
        //    check = 0;
        //  }
        //   $('#leaveremaining').val(check);
       });
    });
     //get leave days requested

     $('#fromdate').on('change', function() {
      fromdate= $('#fromdate').val();
       todate= $('#todate').val();
      
        $.get('{{ url('/leave/get_leave_requested_days') }}/',{ fromdate: fromdate,todate:todate },function(data){
        $('#leave_days_requested').val(data);
       });
    });
     $('#todate').on('change', function() {
      fromdate= $('#fromdate').val();
       todate= $('#todate').val();
      
        $.get('{{ url('/leave/get_leave_requested_days') }}/',{ fromdate: fromdate,todate:todate },function(data){
        $('#leave_days_requested').val(data);
       });
    });
      });

     $(function(){

  $('#emps').select2({
    placeholder: "Employee Name",
     multiple: false,
    id: function(bond){ return bond._id; },
     ajax: {
     delay: 250,
     processResults: function (data) {
          return {        
    results: data
      };
    },
    url: function (params) {
    return '{{url('users')}}/search';
    } 
    }
    
  });
  
  });

  </script>
@endsection