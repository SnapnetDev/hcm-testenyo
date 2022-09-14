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
            <h1 class="page-title">{{__('Specific Staff Attendance Report')}}</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{url('/')}}">{{__('Home')}}</a></li>
                <li class="breadcrumb-item active">{{__('Specific Staff Attendance Report')}}</li>
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
            <div class="col-md-12 col-xs-12 col-md-12">
                <div class="panel panel-info panel-line">
                    <div class="panel-heading">
                        <h3 class="panel-title">Staff List</h3>
                        <div class="panel-actions">

                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-hover dataTable table-striped w-full" data-plugin="dataTable">
                                <thead>
                                <tr>
                                    <th>Staff ID</th>
                                    <th>Staff Name</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    @foreach($users as $user)
                                        <td>{{$user->emp_num}}</td>
                                        <td>{{$user->name}}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-primary dropdown-toggle"
                                                        id="exampleIconDropdown1"
                                                        data-toggle="dropdown" aria-expanded="false">
                                                    Action
                                                </button>
                                                <div class="dropdown-menu" aria-labelledby="exampleIconDropdown1"
                                                     role="menu">
                                                    <a style="cursor:pointer;" class="dropdown-item" href="{{ route('attendance.staff',$user->id) }}"><i class="fa fa-eye" aria-hidden="true"></i>&nbsp;View Attendance Report</a>

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