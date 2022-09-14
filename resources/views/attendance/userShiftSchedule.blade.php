@extends('layouts.master')
@section('stylesheets')


    <link rel="stylesheet" href="{{ asset('global/vendor/fullcal/fullcalendar.min.css') }}"/>
    <style type="text/css">
        a.list-group-item:hover {
            text-decoration: none;
            background-color: #3f51b5;
        }
    </style>
@endsection
@section('content')
    <div class="page ">
        <div class="page-header">
            <h1 class="page-title">Shift Schedule for {{$user->name}}</h1>
            <div class="page-header-actions">
                <div class="row no-space w-250 hidden-sm-down">

                    <div class="col-sm-6 col-xs-12">
                        <div class="counter">
                            <span class="counter-number font-weight-medium">{{date("M j, Y")}}</span>

                        </div>
                    </div>
                    <div class="col-sm-6 col-xs-12">
                        <div class="counter">
                            <span class="counter-number font-weight-medium" id="time">{{date('h:i s a')}}</span>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="page-content container-fluid">
            <div class="panel panel-info panel-line">
                <div class="panel-heading">
                    <h3 class="panel-title">User Schedules</h3>
                    <div class="panel-actions">

                        <a class="btn btn-info" href="{{ route('myShiftSwaps') }}">My Shift Swaps</a>

                    </div>
                </div>
                <div class="panel-body">

                    <div id="calendar"></div>
                </div>
            </div>
        </div>
    </div>
    <!-- Site Action -->
    {{-- Add Location Modal --}}
    @include('attendance.modals.shiftSwapModal')
    <!-- End Add User Form -->
@endsection
@section('scripts')


    <script src="{{ asset('global/vendor/fullcal/lib/moment.min.js') }}"></script>
    <script src="{{ asset('global/vendor/fullcal/fullcalendar.min.js') }}"></script>
    {{-- {!! $calendar->script() !!} --}}
    <script type="text/javascript">
        $(function () {
            $('#calendar').fullCalendar({
                noEventsMessage: '{{__('No Shift For today')}}',
                allDayText: '{{__('Shift for Today')}}',
                eventLimit: true,
                defaultView: 'month',
                header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'month,agendaWeek,agendaDay,listWeek'
                },
                events: {
                    url: '{{url('user_shift_schedule_calendar/'.$user->id)}}',
                    error: function () {
                        $('#script-warning').show();
                    },
                    color: '#263238',     // an option!
                    textColor: '#ffffff' // an option!

                },
                eventClick: function (eventObj) {
                    @if (Auth::user()->id==$user->id)
                    $.get('{{ url('/user_shift_schedule_details') }}/' + eventObj.id, function (data) {

                        // console.log(data);
                        $('#user_daily_shift_id').val(eventObj.id);
                        $('#swap_title_date').text(eventObj.start.format('ddd, MMMM DD, YYYY'));
                        $('#date').val(eventObj.start.format('YYYY-MM-DD'));

                        jQuery.each(data.users, function (i, val) {
                            $('#swap_users').append($('<option>', {value: val.id, text: val.name}));

                        });

                        var today = new Date();

                        if(formatDate(today)<eventObj.start.format('YYYY-MM-DD')){
                            $('#swapShiftModal').modal();
                        }

                    });


                    // console.log(eventObj.id);
                    @endif


                }

            });

            $(document).on('submit', '#swapShiftForm', function (event) {

                event.preventDefault();
                var form = $(this);
                var formdata = false;
                if (window.FormData) {
                    formdata = new FormData(form[0]);
                }
                $.ajax({
                    url: '{{route('swap_shift')}}',
                    data: formdata ? formdata : form.serialize(),
                    cache: false,
                    contentType: false,
                    processData: false,
                    type: 'POST',
                    success: function (data, textStatus, jqXHR) {

                        if(data=='exists')
                        {
                            toastr.info("Shift swap already applied for this date and is currently pending",'Info');
                        }if(data=='noshift')
                        {
                            toastr.info("The selected user doesn't have Shift for this day",'Info');
                        }
                        if(data=='success'){
                            toastr.success("Swift Swap Successfully applied for",'Success');

                            $('#swapShiftModal').modal('toggle');
                            $( "#ldr" ).load('{{route('myShiftSwaps')}}');
                        }

                    },
                    error: function (data, textStatus, jqXHR) {

                        toastr.error("A Shift swap has previously been applied for this day");

                    }
                });

            });

        });
        function formatDate(date) {
            var d = new Date(date),
                month = '' + (d.getMonth() + 1),
                day = '' + d.getDate(),
                year = d.getFullYear();

            if (month.length < 2) month = '0' + month;
            if (day.length < 2) day = '0' + day;

            return [year, month, day].join('-');
        }
    </script>
@endsection