@extends('layouts.master')
@section('stylesheets')
    <link rel="stylesheet" href="{{asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.css')}}">
    <link rel="stylesheet" href="{{asset('global/vendor/bootstrap-maxlength/bootstrap-maxlength.css')}}">
    <link rel="stylesheet" href="{{ asset('global/vendor/jt-timepicker/jquery-timepicker.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('global/vendor/datatables/datatables.min.css')}}">
@endsection
@section('content')
    <!-- Page -->
    <div class="page ">
        <div class="page-header">
            <h1 class="page-title">{{__('Early Staff Report')}}</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{url('/')}}">{{__('Home')}}</a></li>
                <li class="breadcrumb-item active">{{__('Early Staff Report')}}</li>
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
                <div class="col-lg-6"></div>
                <div class="col-lg-6">
                    <form method="GET" action="{{url("/cust_rts")}}">
                        <div class="col-md-4" style="margin-left:-40px">
                            <div class="input-group">
                                <span class="input-group-addon">From <i class="icon fa fa-calendar" aria-hidden="true"></i></span>
                                <input type="text" class="form-control datepair-date datepair-start" id="startdate" data-plugin="datepicker" name="from" autocomplete="off">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="input-group"><span class="input-group-addon">To <i class="icon fa fa-calendar" aria-hidden="true"></i></span>
                                <input type="text" class="form-control datepair-date datepair-start" id="enddate" data-plugin="datepicker" name="to"  autocomplete="off">
                            </div>
                        </div>
                        <div class="col-xl-3">
                            <button title="Export to Excel" class="btn btn-success btn-sm" type="submit">Export Report</button>
                        </div>
                    </form>
                </div>
            </div>

            <br>

            <div class="col-md-12 col-xs-12 col-md-12">
                <div class="panel panel-info panel-line">
                    <div class="panel-heading">
                        <h3 class="panel-title">Early Staff Report Today</h3>
                        <div class="panel-actions">
                            <button class="btn btn-info" data-toggle="modal" data-target="#addLeaveRequestModal">Download Today Report</button>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-hover dataTable table-striped w-full" data-plugin="dataTable">
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
                                   {{-- @foreach($leave_requests as $leave_request)
                                        <td>{{$leave_request->leave->name}}</td>
                                        <td>{{date("F j, Y", strtotime($leave_request->start_date))}}</td>
                                        <td>{{date("F j, Y", strtotime($leave_request->end_date))}}</td>
                                        <td>
                                            <span class=" tag tag-outline  {{$leave_request->priority==0?'tag-success':($leave_request->priority==1?'tag-warning':'tag-danger')}}">{{$leave_request->priority==0?'normal':($leave_request->priority==1?'medium':'high')}}</span>
                                        </td>
                                        <td>{{$leave_request->reason}}</td>
                                        <td>
                                            <span class=" tag   {{$leave_request->status==0?'tag-warning':($leave_request->status==1?'tag-success':'tag-danger')}}">{{$leave_request->status==0?'pending':($leave_request->status==1?'approved':'rejected')}}</span>
                                        </td>
                                        <td>{{$leave_request->paystatus==0?'without pay':'with pay'}}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-primary dropdown-toggle"
                                                        id="exampleIconDropdown1"
                                                        data-toggle="dropdown" aria-expanded="false">
                                                    Action
                                                </button>
                                                <div class="dropdown-menu" aria-labelledby="exampleIconDropdown1"
                                                     role="menu">
                                                    <a style="cursor:pointer;" class="dropdown-item"
                                                       id="{{$leave_request->id}}"
                                                       onclick="viewRequestApproval(this.id)"><i class="fa fa-eye"
                                                                                                 aria-hidden="true"></i>&nbsp;View
                                                        Approval</a>

                                                </div>
                                            </div>
                                        </td>
                                </tr>
                                @endforeach--}}

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
        @section('scripts')
            <script type="text/javascript">
                function datesearch(type = 0) {

                    console.log("Hello");
                    startdate = $('#startdate').val();
                    starttime = $('#starttime').val();
                    enddate = $('#enddate').val();
                    endtime = $('#endtime').val();
                    empname = $('#q').val();

                    if (empname != "") {
                        addionalsearch = "&q=" + empname;
                    } else {
                        addionalsearch = "";
                    }
                    if (startdate == "" || starttime == "" || enddate == "" || endtime == "") {
                        toastr.error("Please fill In all fields");

                        return;
                    }

                    if (type == 1) {

                        window.location = '{{url('attendance/timesearch')}}?startdate=' + startdate + '&enddate=' + enddate + '&starttime=' + starttime + '&enddtime=' + endtime + '&type=1' + addtionalsearch;

                        return;
                    }
                    window.location = '{{url('attendance/timesearch')}}?startdate=' + startdate + '&enddate=' + enddate + '&starttime=' + starttime + '&enddtime=' + endtime + '&type=0' + addtionalsearch;
                }

                function viewMore(attendance_id) {
                    // $.get('{{ url('/attendance/getdetails') }}/'+attendance_id,function(data){
                    $("#detailLoader").load('{{ url('/attendance/getdetails') }}/' + attendance_id);
                    $('#attendanceDetailsModal').modal();
                    // });
                }

            </script>
            <script src="{{asset('global/vendor/bootstrap-table/bootstrap-table.min.js')}}"></script>
            <script src="{{asset('global/vendor/bootstrap-table/extensions/mobile/bootstrap-table-mobile.js')}}"></script>
            <script src="{{asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.js')}}"></script>
            <script src="{{asset('global/vendor/jt-timepicker/jquery.timepicker.min.js')}}"></script>
            <script src="{{asset('global/vendor/datepair/datepair.min.js')}}"></script>
            <script src="{{asset('global/vendor/datepair/jquery.datepair.min.js')}}"></script>
            <script type="text/javascript"
                    src="{{ asset('global/vendor/datatables/jquery.dataTables.min.js')}}"></script>
            <script type="text/javascript">
                $('#atttable').DataTable();
            </script>
@endsection