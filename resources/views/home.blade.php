@extends('layouts.master')
@section('stylesheets')

    <link rel="stylesheet" href="{{ asset('global/vendor/datatables-bootstrap/dataTables.bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/examples/css/charts/chartjs.css')}}">
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
            <h1 class="page-title">Dashboard <span style="padding-left:120px"> Device Last Sync Date: {{ $last_sync->created_at }}</span></h1>

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

        
            <div class="row">
                <div class="col-xl-3 col-md-3">
                    <div class="card card-block p-15 bg-blue-500 ">
                        <div class="counter counter-md text-xs-left">
                            <div class="counter-label text-uppercase m-b-5 grey-50">Employees</div>
                            <div class="counter-number-group m-b-10">
                                <span class="counter-number grey-50">{{$count_users}}</span>
                            </div>
                            <div class="counter-label">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-3">
                    <div class="card card-block p-15 bg-light-green-700 ">
                        <div class="counter counter-md text-xs-left">
                            <div class="counter-label text-uppercase m-b-5 grey-50"> Employees Early Today
                            </div>
                            <div class="counter-number-group m-b-10">
                                <span class="counter-number grey-50">{{$earlys}}</span>
                            </div>
                            <div class="counter-label">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-3">
                    <div class="card card-block p-15 bg-red-300 ">
                        <div class="counter counter-md text-xs-left">
                            <div class="counter-label text-uppercase m-b-5 grey-50"> Employees Late Today
                            </div>
                            <div class="counter-number-group m-b-10">
                                <span class="counter-number grey-50">{{$lates}}</span>
                            </div>
                            <div class="counter-label">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-3">
                    <div class="card card-block p-15 bg-yellow-700 ">
                        <div class="counter counter-md text-xs-left">
                            <div class="counter-label text-uppercase m-b-5 grey-50"> Employees OFF Today
                            </div>
                            <div class="counter-number-group m-b-10">
                                <span class="counter-number grey-50">{{$offs}}</span>
                            </div>
                            <div class="counter-label">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-6 col-md-6">
                    <div class="panel">
                        <div class="panel-heading">
                            <h3 class="panel-title">Top 5 Early This Month</h3>
                        </div>
                        <div class="table-responsive h-200 scrollable is-enabled scrollable-vertical"
                             data-plugin="scrollable" style="position: relative;">
                            <div data-role="container" class="scrollable-container" style="height: 100px; ">
                                <div data-role="content" class="scrollable-content">
                                    <table class="table table-hover table-striped">
                                        <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Times Early</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($top_earlys as $top)
                                        <tr>
                                            <td>{{ $top->user->name }}</td>
                                            <td>{{ $top->count }}</td>
                                        </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="scrollable-bar scrollable-bar-vertical scrollable-bar-hide" draggable="false">
                                <div class="scrollable-bar-handle" style="height: 165.301px;"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6 col-md-6">
                    <div class="panel">
                        <div class="panel-heading">
                            <h3 class="panel-title">Top 5 Late This Month</h3>
                        </div>
                        <div class="table-responsive h-200 scrollable is-enabled scrollable-vertical"
                             data-plugin="scrollable" style="position: relative;">
                            <div data-role="container" class="scrollable-container" style="height: 100px; ">
                                <div data-role="content" class="scrollable-content">
                                    <table class="table table-hover table-striped">
                                        <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Times Late</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($top_lates as $top)
                                            <tr>
                                                <td>{{ $top->user->name }}</td>
                                                <td>{{ $top->count }}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="scrollable-bar scrollable-bar-vertical scrollable-bar-hide" draggable="false">
                                <div class="scrollable-bar-handle" style="height: 165.301px;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('global/vendor/datatables/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('global/vendor/datatables-fixedheader/dataTables.fixedHeader.js') }}"></script>
    <script src="{{ asset('global/vendor/datatables-bootstrap/dataTables.bootstrap.js') }}"></script>
    <script src="{{ asset('global/vendor/chart-js/Chart.js') }}"></script>
    <script src="{{ asset('global/vendor/moment/moment.min.js') }}"></script>
    <script src="{{ asset('global/vendor/moment/moment-duration-format.js') }}"></script>

@endsection