@extends('layouts.master')
@section('stylesheets')
    <link rel="stylesheet" href="{{asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.css')}}">
    <link rel="stylesheet" href="{{asset('global/vendor/bootstrap-maxlength/bootstrap-maxlength.css')}}">
    <link rel="stylesheet" href="{{ asset('global/vendor/jt-timepicker/jquery-timepicker.css') }}">
@endsection
@section('content')
    <!-- Page -->
    <div class="page ">
        <div class="page-header">
            <h1 class="page-title">{{__('Daily Time and Attendance Report for ')}}  {{ $date }}</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{url('/')}}">{{__('Home')}}</a></li>
                <li class="breadcrumb-item active">{{__('Daily Time and Attendance Report')}}</li>
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
                <div class="col-lg-7"></div>
                <div class="col-lg-5">
                    <form method="GET" action="{{url("/attendance/reports")}}">
                        <div class="col-md-7" style="margin-left:-40px">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="icon fa fa-calendar"
                                                                   aria-hidden="true"></i></span>
                                <input type="text" class="form-control datepair-date datepair-start" id="startdate"
                                       data-plugin="datepicker" name="date" autocomplete="off" placeholder="{{ $date }}">
                            </div>
                        </div>
                        <div class="col-md-5">
                            <button class="btn btn-primary" type="submit">Search</button>
                            {{--  <a href="{{url('cust_dts')}}" title="Export to Excel" class="btn btn-success btn-sm"><i class="fa fa-file-excel-o"></i>
                              </a>--}}
                        </div>
                    </form>
                </div>
            </div>

            <br>
            <div class="col-lg-3 col-xs-12">
                <!-- Card -->
                <div class="card card-block p-30" onclick="viewEarly()">
                    <div class="counter counter-md text-xs-left">
                        <div class="counter-label text-uppercase m-b-5"><b>{{__('Early Employee(s)')}}</b>
                        </div>
                        <div class="counter-number-group m-b-10">
                            <span class="counter-number">{{$earlys}}</span>
                        </div>
                        <div class="counter-label">
                            <div class="progress progress-xs m-b-10">
                                <div class="progress-bar progress-bar-danger bg-blue-600" aria-valuenow="70.3"
                                     aria-valuemin="0" aria-valuemax="100" style="width: 70.3%" role="progressbar">
                                    <span class="sr-only">1%</span>
                                </div>
                            </div>
                            <div class="counter counter-sm text-xs-left">
                                <div class="counter-number-group">
                                    <span class="counter-icon blue-600 m-r-5"><i class="wb-graph-up"></i></span>
                                    <span class="counter-number">{{-- {{round(($attstat['early']/$attstat['total'])*100,1)}} --}}%</span>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Card -->
            </div>
            <div class="col-lg-3 col-xs-12" onclick="viewLate()">
                <!-- Card -->
                <div class="card card-block p-30">
                    <div class="counter counter-md text-xs-left">
                        <div class="counter-label text-uppercase m-b-5"><b>{{__('Late Employee(s)')}}</b>
                        </div>
                        <div class="counter-number-group m-b-10">
                            <span class="counter-number">{{$lates}}</span>
                        </div>
                        <div class="counter-label">
                            <div class="progress progress-xs m-b-10">
                                <div class="progress-bar progress-bar-info bg-red-600" aria-valuenow="70.3"
                                     aria-valuemin="0" aria-valuemax="100" style="width: 70.3%" role="progressbar">
                                    <span class="sr-only">70.3%</span>
                                </div>
                            </div>
                            <div class="counter counter-sm text-xs-left">
                                <div class="counter-number-group">
                                    <span class="counter-icon blue-600 m-r-5"><i class="wb-graph-up"></i></span>
                                    <span class="counter-number">{{-- {{round(($attstat['late']/$attstat['total'])*100,1)}} --}}%</span>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Card -->
            </div>
            <div class="col-lg-3 col-xs-12">
                <!-- Card -->
                <div class="card card-block p-30">
                    <div class="counter counter-md text-xs-left">
                        <div class="counter-label text-uppercase m-b-5"><b>{{__('Present Employee(s)')}}</b></div>
                        <div class="counter-number-group m-b-10">
                            <span class="counter-number">{{count($attendances)}}</span>
                        </div>
                        <div class="counter-label">
                            <div class="progress progress-xs m-b-10">
                                <div class="progress-bar progress-bar-danger bg-blue-600" aria-valuenow="70.3"
                                     aria-valuemin="0" aria-valuemax="100" style="width: 70.3%" role="progressbar">
                                    <span class="sr-only">1%</span>
                                </div>
                            </div>
                            <div class="counter counter-sm text-xs-left">
                                <div class="counter-number-group">
                                    <span class="counter-icon blue-600 m-r-5"><i class="wb-graph-up"></i></span>
                                    <span class="counter-number">{{-- {{round(($attstat['early']/$attstat['total'])*100,1)}} --}}%</span>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Card -->
            </div>
            <div class="col-lg-3 col-xs-12">
                <!-- Card -->
                <div class="card card-block p-30">
                    <div class="counter counter-md text-xs-left">
                        <div class="counter-label text-uppercase m-b-5"><b>{{__('Absent Employee(s)')}}</b></div>
                        <div class="counter-number-group m-b-10">
                            <span class="counter-number">{{count($absentees)}}</span>
                        </div>
                        <div class="counter-label">
                            <div class="progress progress-xs m-b-10">
                                <div class="progress-bar progress-bar-danger bg-blue-600" aria-valuenow="70.3"
                                     aria-valuemin="0" aria-valuemax="100" style="width: 70.3%" role="progressbar">
                                    <span class="sr-only">1%</span>
                                </div>
                            </div>
                            <div class="counter counter-sm text-xs-left">
                                <div class="counter-number-group">
                                    <span class="counter-icon blue-600 m-r-5"><i class="wb-graph-up"></i></span>
                                    <span class="counter-number">{{-- {{round(($attstat['early']/$attstat['total'])*100,1)}} --}}%</span>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Card -->
            </div>


            <div class="col-md-12 col-xs-12 col-md-12">
                <div class="panel panel-info panel-line">
                    <div class="panel-heading">
                        <h3 class="panel-title">Attendance Report for {{ $date }}</h3>
                        <div class="panel-actions">
                            <button class="btn btn-info">
                                <a href='{{ route('attendance.absenceManagement.excel',['date'=>$date]) }}'> Download
                                    Report</a>
                            </button>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-hover dataTable table-striped w-full" data-plugin="dataTable">
                                <thead>
                                <tr>
                                    <th>EMPID</th>
                                    <th>{{__('NAME')}}</th>
                                    <th class="hidden-sm-down">{{__('CLOCK IN')}}</th>
                                    <th class="hidden-sm-down">{{__('SHIFT STARTS')}}</th>
                                    <th class="hidden-sm-down">{{__('SHIFT ENDS')}}</th>
                                    <th class="hidden-sm-down">{{__('CLOCK OUT')}}</th>
                                    <th class="hidden-sm-down">{{__('SHIFT')}}</th>
                                    <th class="hidden-sm-down">{{__('STATUS')}}</th>
                                    <th class="hidden-sm-down">{{__('ACTION')}}</th>
                                </tr>
                                </thead>
                                <tbody>

                                @if(count($attendances)>0)
                                    @foreach($attendances as $attendance)

                                        <tr>
                                            <td>{{$attendance['emp_num']}}</td>
                                            <td>{{$attendance['name']}}</td>
                                            <td class="hidden-sm-down">
                                            <span class="text text-success">
                                            <b>{{$attendance['first_clock_in']}}</b>
                                            </span>
                                            </td>
                                            <td>{{ $attendance['shift_starts'] }}</td>
                                            <td>{{ $attendance['shift_ends'] }}</td>
                                            <td class="hidden-sm-down">
                                              <span class="text text-danger">
                                                  <b>@if($attendance['first_clock_out']=="") {{__('Nill')}} @else {{$attendance['first_clock_out']}} @endif</b>
                                              </span>
                                            </td>
                                            <td>{{$attendance['shift']}}</td>
                                            <td>
                                                <span class="tag {{$attendance['diff']>=0?'tag-success':'tag-danger'}}">{{$attendance['diff']>=0?'Early':'Late'}}</span>
                                            </td>
                                            <td>
                                                <button class="btn btn-info">
                                                    <a style="cursor:pointer;" id="{{$attendance['attendance_id']}}"
                                                       onclick="viewMore(this.id)"><i class="fa fa-eye"
                                                                                      aria-hidden="true"></i>
                                                        &nbsp;View More</a>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td>
                                        </td>
                                        <td>
                                        </td>
                                        <td>
                                        </td>
                                        <td>
                                            <b style="font-size:20px;"
                                               class="text-success"> {{__('No Attendance Report For Today Yet')}}</b>
                                        </td>

                                    </tr>
                                @endif

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
    <!-- End Page -->
    <div class="modal fade in modal-3d-flip-horizontal modal-info" id="attendanceDetailsModal" aria-hidden="true"
         aria-labelledby="attendanceDetailsModal" role="dialog" tabindex="-1">
        <div class="modal-dialog ">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title" id="training_title">Clock In History</h4>
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


    <div class="modal fade in modal-3d-flip-horizontal modal-info" id="earlyDetailsModal" aria-hidden="true"
         aria-labelledby="attendanceDetailsModal" role="dialog" tabindex="-1">
        <div class="modal-dialog ">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title" id="training_title">Early Staff</h4>
                </div>
                <div class="modal-body">
                    <div class="row row-lg col-xs-12">
                        <div class="col-xs-12">
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th>EMPID</th>
                                    <th>{{__('NAME')}}</th>
                                    <th class="hidden-sm-down">{{__('CLOCK IN')}}</th>
                                    <th class="hidden-sm-down">{{__('SHIFT STARTS')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(count($attendances)>0)
                                    @foreach($attendances as $attendance)
                                        @if($attendance['diff']>=0)
                                            <tr>
                                                <td>{{$attendance['emp_num']}}</td>
                                                <td>{{$attendance['name']}}</td>
                                                <td class="hidden-sm-down">
                                                <span class="text text-success">
                                                <b>{{$attendance['first_clock_in']}}</b>
                                                </span>
                                                </td>
                                                <td>{{ $attendance['shift_starts'] }}</td>
                                            </tr>
                                        @endif
                                    @endforeach
                                @else
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td>
                                            <b style="font-size:20px;"
                                               class="text-success"> {{__('No Attendance Report For Today Yet')}}</b>
                                        </td>
                                    </tr>
                                @endif
                                </tbody>
                            </table>
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

    <div class="modal fade in modal-3d-flip-horizontal modal-info" id="lateDetailsModal" aria-hidden="true"
         aria-labelledby="attendanceDetailsModal" role="dialog" tabindex="-1">
        <div class="modal-dialog ">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title" id="training_title">Late Staff</h4>
                </div>
                <div class="modal-body">
                    <div class="row row-lg col-xs-12">
                        <div class="col-xs-12">
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th>EMPID</th>
                                    <th>{{__('NAME')}}</th>
                                    <th class="hidden-sm-down">{{__('CLOCK IN')}}</th>
                                    <th class="hidden-sm-down">{{__('SHIFT STARTS')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(count($attendances)>0)
                                    @foreach($attendances as $attendance)
                                        @if($attendance['diff']>=0)
                                        @else
                                            <tr>
                                                <td>{{$attendance['emp_num']}}</td>
                                                <td>{{$attendance['name']}}</td>
                                                <td class="hidden-sm-down">
                                                <span class="text text-success">
                                                <b>{{$attendance['first_clock_in']}}</b>
                                                </span>
                                                </td>
                                                <td>{{ $attendance['shift_starts'] }}</td>
                                            </tr>
                                        @endif
                                    @endforeach
                                @else
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td>
                                            <b style="font-size:20px;"
                                               class="text-success"> {{__('No Attendance Report For Today Yet')}}</b>
                                        </td>
                                    </tr>
                                @endif
                                </tbody>
                            </table>
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

        function viewEarly() {
            $('#earlyDetailsModal').modal();
            // });
        }

        function viewLate() {
            $('#lateDetailsModal').modal();
            // });
        }

    </script>
    <script src="{{asset('global/vendor/bootstrap-table/bootstrap-table.min.js')}}"></script>
    <script src="{{asset('global/vendor/bootstrap-table/extensions/mobile/bootstrap-table-mobile.js')}}"></script>
    <script src="{{asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.js')}}"></script>
    <script src="{{asset('global/vendor/jt-timepicker/jquery.timepicker.min.js')}}"></script>
    <script src="{{asset('global/vendor/datepair/datepair.min.js')}}"></script>
    <script src="{{asset('global/vendor/datepair/jquery.datepair.min.js')}}"></script>
@endsection