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
            <h1 class="page-title">{{__('Monthly Summary Report for ')}}  {{ $date->format('M, Y') }}</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{url('/')}}">{{__('Home')}}</a></li>
                <li class="breadcrumb-item active">{{__('Monthly Financial Summary')}}</li>
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
                    <form method="GET" action="{{route("station.financial")}}">
                        <div class="col-md-7" style="margin-left:-40px">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="icon fa fa-calendar"
                                                                   aria-hidden="true"></i></span>
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
                        <h3 class="panel-title">Financial Summary for {{ $date->format('M Y') }}</h3>
                        <div class="panel-actions">
                            <button class="btn btn-info">
                                <a  style="text-decoration: none; color: white" onclick="viewMore()">Download Report</a>
                            </button>
                        </div>
                    </div>
                    <div class="panel-body">
                        <table class="table table-hover dataTable table-striped w-full" data-plugin="dataTable">
                            <thead>
                            <tr>
                                <th>STATION ID</th>
                                <th>{{__('STATION NAME')}}</th>
                                <th>{{__('STATION MANAGER')}}</th>
                                <th class="hidden-sm-down">{{__('No of Staff')}}</th>
                                <th class="hidden-sm-down">{{__('Max Expected')}}</th>
                                <th class="hidden-sm-down">{{__('Amount Paid')}}</th>
                                {{-- <th class="hidden-sm-down">{{__('ACTION')}}</th>--}}
                            </tr>
                            </thead>
                            <tbody>

                            @foreach($companies as $station)
                                <tr>
                                    <td><a style="text-decoration: none;"  href="{{ route('attendance.staff',$station->id) }}">{{$station->id}}</a></td>
                                    <td><a style="text-decoration: none;"  href="{{ route('attendance.staff',$station->id) }}">{{$station->name}}</a></td>
                                    <td>{{\App\User::where('company_id',$station->id)->where('role_id','2')->where('status','1')->first() ? \App\User::where('company_id',$station->id)->where('role_id','2')->where('status','1')->first()->name : 'None' }}</td>
                                    <td>{{$station->no_of_staff}}</td>
                                    <td>{{number_format($station->amount_expected,2)}}</td>
                                    <td>{{number_format($station->amount_received,2)}}</td>
                                    {{-- <td>
                                         <button class="btn btn-info">
                                             <a style="cursor:pointer;" id="{{ $repo->id }}"
                                                onclick="viewMore(this.id)"><i class="fa fa-eye"
                                                                               aria-hidden="true"></i>
                                                 &nbsp;View More</a>
                                         </button>
                                     </td>--}}
                                </tr>
                            @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>

    </div>
    <input type="hidden" value="{{ $date->format('Y-m-d') }}" id="getdate">


    <div class="modal fade in modal-3d-flip-horizontal modal-info" id="downloadreportModal" aria-hidden="true" role="dialog" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" >
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title" id="training_title">Download Report per Role</h4>
                </div>
                <div class="modal-body">
                    <form class="form-inline" action="{{ route('station.financial')}}" method="GET">
                        <input type="hidden" name="date" value="{{$date->format('m-Y')}}">
                        <input type="hidden" name="type" value="excel">
                        <div class="row">

                        </div>
                        @foreach(\App\Role::all() as $role)
                            <div class="col-md-4">
                                <input type="checkbox" id="role{{$role->id}}" name="roles[]" value="{{ $role->id }}">
                                <label for="role{{$role->id}}">{{ $role->name }}</label>
                            </div>
                        @endforeach
                        <br>
                        <button class="btn btn-info">Download Report</button>
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

@endsection
@section('scripts')
    <script type="text/javascript">
        function viewMore() {
            $('#downloadreportModal').modal();
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
@endsection