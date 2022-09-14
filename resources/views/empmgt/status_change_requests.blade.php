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
            <h1 class="page-title">Staff Status Change Request</h1>
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
                    <h3 class="panel-title">Staff Status Change Request</h3>
                    <div class="panel-actions">


                    </div>
                    <div class="panel-body">

                        <table class="table table-hover dataTable table-striped w-full" data-plugin="dataTable">
                            <thead>
                            <tr>
                                <th>Date</th>
                                <th>Staff</th>
                                <th>Status</th>
                                <th>Reason</th>
                                <th>Details</th>
                                <th>Created By</th>
                                <th>Station</th>
                                <th>Approve By</th>
                                <th>Approve</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($requests as $request)
                                <tr>
                                    <td>{{ $request->start_date }}</td>
                                    <td>{{ $request->user->name }}</td>
                                    <td>{{$request->status=='1'? 'Active' :('2' ? 'Suspended' : ('3'? 'Resigned' :('4'? 'Disengaged' :'Unknown'))) }}</td>
                                    <td>{{ $request->reason }}</td>
                                    <td>{{ $request->details }}</td>
                                    <td>{{ $request->suspender->name }}</td>
                                    <td>{{ $request->company->name }}</td>
                                    <td>{{ $request->approver->name }}</td>
                                    <td>{{ $request->approved }}</td>
                                    <td>
                                        @if(Auth::user()->id==$request->approved_by)
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-primary dropdown-toggle"
                                                        id="exampleIconDropdown1"
                                                        data-toggle="dropdown" aria-expanded="false">
                                                    Action
                                                </button>
                                               @if($request->approved=='pending')
                                                <div class="dropdown-menu" aria-labelledby="exampleIconDropdown1"
                                                     role="menu">
                                                    <a style="cursor:pointer;" class="dropdown-item text-success" id="{{$request->id}}"
                                                       href="{{ route('approve.request',['status_request'=>$request->id,'answer'=>'yes']) }}"><i class="fa fa-check" aria-hidden="true"></i>&nbsp;Approve</a>
                                                    <a style="cursor:pointer;" class="dropdown-item text-danger" id="{{$request->id}}"
                                                       href="{{ route('approve.request',['status_request'=>$request->id,'answer'=>'no']) }}"><i class="fa fa-close" aria-hidden="true"></i>&nbsp;Reject</a>
                                                </div>
                                                @endif
                                            </div>
                                        @endif
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
@endsection
<!-- Site Action -->
{{-- Add Location Modal --}}

<!-- End Add User Form -->

@section('scripts')


    <script src="{{ asset('global/vendor/fullcal/lib/moment.min.js') }}"></script>
    <script src="{{ asset('global/vendor/fullcal/fullcalendar.min.js') }}"></script>
    {{-- {!! $calendar->script() !!} --}}
@endsection