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
            <h1 class="page-title">{{__('Montly Time and Attendance Report for ')}}  {{ $date->format('M Y') }}</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{url('/')}}">{{__('Home')}}</a></li>
                <li class="breadcrumb-item active">{{__('Monthly Time and Attendance Report')}}</li>
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
                    <form method="GET" action="{{url("/monthly/attendance/reports")}}">
                        <div class="col-md-7" style="margin-left:-40px">
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="icon fa fa-calendar" aria-hidden="true"></i>
                                </span>
                                <input type="text" class="form-control datepair-date datepair-start" id="date2"
                                       data-plugin="datepicker" name="date" autocomplete="off"
                                       placeholder="{{ $date->format('m-Y') }}">
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

            <div class="col-md-12 col-xs-12 col-md-12">
                <div class="panel panel-info panel-line">
                    <div class="panel-heading">
                        <h3 class="panel-title">Attendance Report for {{ $date->format('M Y') }}</h3>
                        <div class="panel-actions">

                            <button class="btn btn-info">
                                <a style="text-decoration: none; color: white"  href='{{ route('monthly.attendance.report',['date'=>$date->format('m-Y'),'type'=>'excel']) }}'> Download
                                    Report</a>
                            </button>


                            @if(Auth::user()->role->permissions->contains('constant', 'run_payroll'))
                                @csrf
                                <input type="hidden" name="month" value="{{$date->format('m')}}">
                                <input type="hidden" name="year" value="{{$date->format('Y')}}">

                                <button onclick="runPayroll()" class="btn btn-info">@if($exists=='yes')Re @endif Run Payroll</button>
                                @if($exists=='yes')
                                    <br> <span>Payroll has already been ran for {{ $date->format('M Y') }}, do you want to recalculate?</span>
                                @endif
                            @endif

                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-hover dataTable table-striped w-full" data-plugin="dataTable">
                                <thead>
                                <tr>
                                    <th>EMPID</th>
                                    <th>{{__('NAME')}}</th>
                                    <th>{{__('ROLE')}}</th>
                                    <th class="hidden-sm-down">{{__('HOURS WORKED')}}</th>
                                    <th class="hidden-sm-down">{{__('OVERTIME WORKED')}}</th>
                                    <th class="hidden-sm-down">{{__('EARLY')}}</th>
                                    <th class="hidden-sm-down">{{__('LATE')}}</th>
                                    <th class="hidden-sm-down">{{__('OFF')}}</th>
                                    <th class="hidden-sm-down">{{__('ABSENT')}}</th>
                                    <th class="hidden-sm-down">{{__('PRESENT')}}</th>
                                    <th class="hidden-sm-down">{{__('ESTIMATED AMOUNT')}}</th>
                                    <th class="hidden-sm-down">{{__('ACTION')}}</th>
                                </tr>
                                </thead>
                                <tbody>

                                @foreach($users as $user)
                                    <tr>
                                        <td><a style="text-decoration: none;"  href="{{ route('attendance.staff',$user['id']) }}">{{$user->emp_num}}</a></td>
                                        <td><a style="text-decoration: none;"  href="{{ route('attendance.staff',$user['id']) }}">{{$user->name}}</a></td>
                                        <td>{{$user->role->name}}</td>
                                        <td>{{$user->total_hours}}</td>
                                        <td>{{$user->overtime_worked}}</td>
                                        <td> <span class="text text-success">{{$user->earlys}}</span></td>
                                        <td> <span class="text text-danger">{{$user->lates}}</span></td>
                                        <td> <span class="text text-primary">{{$user->offs}}</span></td>
                                        <td> <span class="text text-danger">{{$user->absents}}</span></td>
                                        <td> <span class="text text-success">{{$user->present}}</span></td>
                                        <td> <span class="text text-success">&#8358; {{number_format($user->amount,2)}}</span></td>
                                        <td>
                                            <button class="btn btn-info">
                                                <a style="cursor:pointer;" id="{{ $user->id }}"
                                                   onclick="viewMore(this.id)"><i class="fa fa-eye"
                                                                                  aria-hidden="true"></i>
                                                    &nbsp;View More</a>
                                            </button>
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
    <!-- End Page -->
    <div class="modal fade in modal-3d-flip-horizontal modal-info" id="attendanceDetailsModal" aria-hidden="true"
         aria-labelledby="attendanceDetailsModal" role="dialog" tabindex="-1">
        <div class="modal-dialog ">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title" id="training_title">Attendance Details</h4>
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


    <div class="modal fade in modal-3d-flip-horizontal modal-info" id="runPayrollModal" aria-hidden="true" role="dialog" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" >
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title" id="training_title"> @if($exists=='yes')Re @endif Run Payroll for {{ $date->format('M Y') }}</h4>
                </div>
                <div class="modal-body">
                    <form action="{{ route('run.financial.report') }}" method="Post" style=" display: inline;">
                        @csrf
                        <input type="hidden" name="month" value="{{$date->format('m')}}">
                        <input type="hidden" name="year" value="{{$date->format('Y')}}">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="icon fa fa-calendar" aria-hidden="true"></i>
                                </span>
                                    <input type="text" class="form-control datepair-date datepair-start" id="startdate"
                                           data-plugin="datepicker" name="start" autocomplete="off"
                                           placeholder="{{ $date->format('d M, Y') }}" value="{{ $date->format('m/d/Y') }}" required>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="icon fa fa-calendar" aria-hidden="true"></i>
                                </span>
                                    <input type="text" class="form-control datepair-date datepair-start" id="startdate"
                                           data-plugin="datepicker" name="end" autocomplete="off"
                                           placeholder="{{ $date->format('d M, Y') }}" value="{{ $date->format('m/d/Y') }}" required>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <h4>Add Days</h4>
                                    <input type="number" class="form-control" name="days" value="0">
                                </div>
                            </div>

                        </div>

                        <br>
                       
                        @if(\App\Setting::where('name','payroll_running')->first() && \App\Setting::where('name','payroll_running')->first()->value=='no')
                            <button class="btn btn-info">Run Payroll</button>
                        @else
                            <p>Payroll is Currently Running, Wait untill it is done</p> 
                        @endif
                        
                        <!--@if(\App\Setting::where('name','payroll_running')->first() && \App\Setting::where('name','payroll_running')->first()->value=='no')-->
                        <!--    <button class="btn btn-info">Run Payroll</button>-->
                        <!--@else-->
                        <!--    <p>Payroll is Currently Running, Wait untill it is done</p> -->
                        <!--@endif-->
                    </form>
                </div>
                <div class="modal-footer">
                    <div class="col-xs-12">
                        <div class="form-group">
                            <button type="button" class="btn btn-cancel" data-dismiss="modal">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <input type="hidden" value="{{ $date->format('Y-m-d') }}" id="getdate">
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

        function viewMore(user) {
            var date = $('#getdate').val();
            // $.get('{{ url('/attendance/getdetails') }}/'+attendance_id,function(data){
            //$("#detailLoader").load('{{ url('/attendance/getdetails') }}/' + user);
            $("#detailLoader").load('{{ url('/user/monthly/attendance') }}/' + user +'/'+date);
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
    <script>
        $("#date2").datepicker({
            format: "mm-yyyy",
            viewMode: "months",
            minViewMode: "months",
            orientation: "bottom"
        });
    </script>
    <script type="text/javascript">
        function runPayroll() {
            $('#runPayrollModal').modal();
        }

    </script>
@endsection
