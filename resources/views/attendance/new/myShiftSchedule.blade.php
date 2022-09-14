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
            <h1 class="page-title">{{__('My Shift Schedule')}}</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{url('/')}}">{{__('Home')}}</a></li>
                <li class="breadcrumb-item active">{{ $user->name }}{{__(' Shift Schedule')}}</li>
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
                    <form method="GET" action="{{route('my.shift.schedules')}}">
                        <div class="col-md-4" style="margin-left:-40px">
                            <div class="input-group">
                                <span class="input-group-addon">From <i class="icon fa fa-calendar" aria-hidden="true"></i></span>
                                <input type="text" class="form-control datepair-date datepair-start" id="startdate" data-plugin="datepicker" name="from" autocomplete="off" placeholder="{{$start}}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="input-group"><span class="input-group-addon">To <i class="icon fa fa-calendar" aria-hidden="true"></i></span>
                                <input type="text" class="form-control datepair-date datepair-start" id="enddate" data-plugin="datepicker" name="to"  autocomplete="off" placeholder="{{$end}}">
                            </div>
                        </div>
                        <div class="col-xl-3">
                            <button title="Search" class="btn btn-success btn-sm" type="submit">Search</button>
                        </div>
                    </form>
                </div>
            </div>

            <br>

            <div class="col-md-12 col-xs-12 col-md-12">
                <div class="panel panel-info panel-line">
                    <div class="panel-heading">
                        <h3 class="panel-title">My Shift Schedule from {{ $start }} to {{ $end }}</h3>
                        <div class="panel-actions">
                            <a href="{{ route('myShiftSwaps') }}">
                                <button class="btn btn-info">My Shift Swaps</button>
                            </a>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-hover dataTable table-striped w-full" data-plugin="dataTable">
                                <thead>
                                <tr>
                                    <th class="hidden-sm-down">{{__('Day')}}</th>
                                    <th class="hidden-sm-down">{{__('Shift')}}</th>
                                    <th class="hidden-sm-down">{{__('Start')}}</th>
                                    <th class="hidden-sm-down">{{__('End')}}</th>
                                    <th class="hidden-sm-down">{{__('ACTION')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php($today=\Carbon\Carbon::today())
                                @if(count($shifts)>0)
                                    @foreach($shifts as $shift)

                                        <tr>
                                            <td>{{$shift->sdate}}</td>
                                            <td>{{$shift->shift->type}}</td>
                                            <td>{{$shift->starts}}</td>
                                            <td>{{$shift->ends}}</td>
                                            <td>
                                                <div class="btn-group show">
                                                    <button type="button"
                                                            class="btn btn-info dropdown-toggle waves-effect waves-light waves-round"
                                                            id="exampleSizingDropdown2" data-toggle="dropdown"
                                                            aria-expanded="true">
                                                        Action
                                                    </button>
                                                    @if($shift->sdate>$today)
                                                    <div class="dropdown-menu show"
                                                         aria-labelledby="exampleSizingDropdown2" role="menu"
                                                         x-placement="bottom-start"
                                                         style="position: absolute; transform: translate3d(0px, 36px, 0px); top: 0px; left: 0px; will-change: transform;">
                                                        <a class="dropdown-item" href="javascript:void(0)"
                                                           onclick="apply({{$shift->id}},'{{$shift->sdate}}')" role="menuitem">Apply for
                                                            Shift Swap</a>
                                                    </div>
                                                    @endif
                                                </div>
                                            </td>

                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td>
                                            <b style="font-size:20px;"
                                               class="text-success"> {{__('No Shift Scheduled for this Period.')}}</b>
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

    <div class="modal fade in modal-3d-flip-horizontal modal-info" id="applySwapModal" aria-hidden="true" aria-labelledby="attendanceDetailsModal" role="dialog" tabindex="-1">
        <div class="modal-dialog " >
            <div class="modal-content">
                <div class="modal-header" >
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title" id="training_title">Apply for Shift Swap on <span id="dateval"></span></h4>
                </div>
                <div class="modal-body">
                    <div class="row row-lg col-xs-12">
                        <div class="col-xs-12">
                            <form method="post" id="applySwapForm">
                                {{csrf_field()}}
                                <input type="hidden" name="user_daily_shift_id" id="user_daily_shift_id">
                                <div class="col-xs-8">
                                    <div class="input-group">
                                        <h4 class="example-title">Swap with</h4>
                                        <select type="text" class="form-control datepair-date datepair-start" name="swapper_id">
                                            <option value="">Select Who to Swap With</option>
                                            @foreach($users as $user)
                                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xs-12">
                                    <div class="input-group">
                                        <h4 class="example-title">Reason</h4>
                                        <textarea  class="form-control datepair-date datepair-start" name="reason" rows="4"></textarea>
                                    </div>
                                </div>
                                <br>
                                <br>
                                <div class="col-xs-12">
                                    <button type="submit" class="btn btn-outline btn-primary">Apply</button>
                                </div>
                            </form>
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
        function apply(user_daily_shift_id,date)
        {
            $('#user_daily_shift_id').val(user_daily_shift_id);
            $('#dateval').html(date);
            $('#applySwapModal').modal();
        }

        $(document).on('submit','#applySwapForm',function(event){
            event.preventDefault();
            var form = $(this);
            var formdata = false;
            if (window.FormData){
                formdata = new FormData(form[0]);
            }
            $.ajax({
                url         : '{{route('swap_shift')}}',
                data        : formdata ? formdata : form.serialize(),
                cache       : false,
                contentType : false,
                processData : false,
                type        : 'POST',
                success     : function(data, textStatus, jqXHR){
                    console.log(data);
                    if(data=='exists')
                    {
                        toastr.info("Shift swap already applied for this date and is currently pending",'Info');
                    }if(data=='noshift')
                    {
                        toastr.info("The selected user doesn't have Shift for this day",'Info');
                    }
                    if(data=='success'){
                        toastr.success("Swift Swap Successfully applied for",'Success');

                        $('#applySwapModal').modal('toggle');
                        $( "#ldr" ).load('{{route('myShiftSwaps')}}');
                    }
                },
                error:function(data, textStatus, jqXHR){
                    jQuery.each( data['responseJSON'], function( i, val ) {
                        jQuery.each( val, function( i, valchild ) {
                            toastr.error(valchild[0]);
                        });
                    });
                }
            });

        });

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