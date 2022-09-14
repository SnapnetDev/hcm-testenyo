<!DOCTYPE html>
<html class="no-js css-menubar" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'HCMatrix') }}</title>
    <link rel="apple-touch-icon" href="{{ asset('assets/images/apple-touch-icon.png') }}">
    <link rel="shortcut icon" href="{{ asset('assets/images/logo.png') }}">
    <!-- Stylesheets -->
    <link rel="stylesheet" href="{{ asset('global/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('global/css/bootstrap-extend.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/site.css') }}">
    <!-- Plugins -->
    <link rel="stylesheet" href="{{ asset('global/vendor/animsition/animsition.css') }}">
    <link rel="stylesheet" href="{{ asset('global/vendor/asscrollable/asScrollable.css') }}">
    <link rel="stylesheet" href="{{ asset('global/vendor/switchery/switchery.css') }}">
    <link rel="stylesheet" href="{{ asset('global/vendor/intro-js/introjs.css') }}">
    <link rel="stylesheet" href="{{ asset('global/vendor/slidepanel/slidePanel.css') }}">
    <link rel="stylesheet" href="{{ asset('global/vendor/flag-icon-css/flag-icon.css') }}">
    <link rel="stylesheet" href="{{ asset('global/vendor/waves/waves.css') }}">

    <link rel="stylesheet" href="{{ asset('global/vendor/toastr/toastr.css') }}">
    <link href="{{ asset('global/vendor/select2/select2.min.css') }}" rel="stylesheet" />
@yield('stylesheets')

<!-- Fonts -->
    <link rel="stylesheet" href="{{ asset('global/fonts/font-awesome/font-awesome.css') }}">
    <link rel="stylesheet" href="{{ asset('global/fonts/material-design/material-design.min.css') }}">
    <link rel="stylesheet" href="{{ asset('global/fonts/brand-icons/brand-icons.min.css') }}">

    <link rel='stylesheet' href='http://fonts.googleapis.com/css?family=Roboto:300,400,500,300italic'>
    <!--[if lt IE 9]>
    <script src="{{ asset('global/vendor/html5shiv/html5shiv.min.js') }}"></script>
    <![endif]-->
    <!--[if lt IE 10]>
    <script src="{{ asset('global/vendor/media-match/media.match.min.js') }}"></script>
    <script src="{{ asset('global/vendor/respond/respond.min.js') }}"></script>
    <![endif]-->
    <!-- Scripts -->
    <style type="text/css">
        .select2-container--open {
            z-index: 9999999;
        }
        #loader {
            position: absolute;
            left: 50%;
            top: 50%;
            z-index: 1;
            width: 150px;
            height: 150px;
            margin: -75px 0 0 -75px;
            border: 16px solid #f3f3f3;
            border-radius: 50%;
            border-top: 16px solid #3498db;
            width: 120px;
            height: 120px;
            -webkit-animation: spin 2s linear infinite;
            animation: spin 2s linear infinite;
        }

    </style>

    <link rel="stylesheet" href="{{ asset('global/vendor/datatables-bootstrap/dataTables.bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('global/vendor/bootstrap-datepicker/bootstrap-datepicker.min.css')}}">
    <script src="{{ asset('global/vendor/breakpoints/breakpoints.js') }}"></script>
    <script>
        Breakpoints();

        function setfy() {

            var year = document.getElementById('fiscalyear').value;
            $.get('{{url('setfy')}}/' + year, function (data, status, xhr) {

                if (xhr.status == 200) {


                    window.location.reload();

                }
            });


        }

        function setcpny() {

            var company_id = document.getElementById('cpny').value;
            $.get('{{url('setcpny')}}/' + company_id, function (data, status, xhr) {

                if (xhr.status == 200) {

                    console.log(data);
                    window.location = '{{url('home')}}';

                }
            });


        }


    </script>
</head>
<body class="animsition site-navbar-small app-{{isset($pageType) ? $pageType : 'contacts'}} page-aside-left">
<div id="loader"  style="display: none;z-index:9999999;"></div>
<!--[if lt IE 8]>
<p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade
    your browser</a> to improve your experience.</p>
<![endif]-->
<nav class="site-navbar navbar navbar-inverse bg-light-blue-2900 navbar-fixed-top navbar-mega" role="navigation" style="background-color: #294875">
    <div class="navbar-header">
        <button type="button" class="navbar-toggler hamburger hamburger-close navbar-toggler-left hided"
                data-toggle="menubar">
            <span class="sr-only">Toggle navigation</span>
            <span class="hamburger-bar"></span>
        </button>
        <button type="button" class="navbar-toggler collapsed" data-target="#site-navbar-collapse"
                data-toggle="collapse">
            <i class="icon md-more" aria-hidden="true"></i>
        </button>
        <div class="navbar-brand navbar-brand-center site-gridmenu-toggle" data-toggle="gridmenu">
            <a href="{{ route('home') }}" style="color: #fff;text-decoration: none">
                <img class="navbar-brand-logo" src="{{ asset('assets/images/logo.png') }}"
                     title="HCMatrix Time & Attendance">
                <span class="navbar-brand-text hidden-xs-down"> HCMatrix</span>
            </a>
        </div>
        <button type="button" class="navbar-toggler collapsed" data-target="#site-navbar-search"
                data-toggle="collapse">
            <span class="sr-only">Toggle Search</span>
            <i class="icon md-search" aria-hidden="true"></i>
        </button>
    </div>
    <div class="navbar-container container-fluid">
        <!-- Navbar Collapse -->
        <div class="collapse navbar-collapse navbar-collapse-toolbar" id="site-navbar-collapse">
            <!-- Navbar Toolbar -->
            <ul class="nav navbar-toolbar">
                <li class="nav-item hidden-float" id="toggleMenubar">
                    <a class="nav-link" data-toggle="menubar" href="#" role="button">
                        <i class="icon hamburger hamburger-arrow-left">
                            <span class="sr-only">Toggle menubar</span>
                            <span class="hamburger-bar"></span>
                        </i>
                    </a>
                </li>
                <li class="nav-item hidden-sm-down" id="toggleFullscreen">
                    <a class="nav-link icon icon-fullscreen" data-toggle="fullscreen" href="#" role="button">
                        <span class="sr-only">Toggle fullscreen</span>
                    </a>
                </li>
                <li class="nav-item hidden-float">
                    <a class="nav-link icon md-search" data-toggle="collapse" href="#" data-target="#site-navbar-search"
                       role="button">
                        <span class="sr-only">Toggle Search</span>
                    </a>
                </li>

            </ul>
            <!-- End Navbar Toolbar -->
            <!-- Navbar Toolbar Right -->
            <ul class="nav navbar-toolbar navbar-right navbar-toolbar-right">
                <li class="nav-item hidden-float" style="margin-top:15px;margin-right:10px;">
                  @if($_SERVER['HTTP_HOST'] == "demo-env.thehcmatrix.com")
                  @php
                  $title = "snapnet";
                  @endphp
                   <img src="{{ File::exists('storage/logo'.companyInfo()->logo)?asset('storage/logo/sn2.png'):''}}"
                    
                         style="height: 2.286rem; background:#fff" title="snapnet logo">
                  @else
                   @php
                  $title = userCompanyName();
                  @endphp
                  
                    <img src="{{ File::exists('storage/logo'.companyInfo()->logo)?asset('storage/logo/enyo-logo.png'):''}}"
                    
                         style="height: 2.286rem; background:#fff" title="{{$title}}">
                 @endif
                </li>
                @if (Auth::user()->role->permissions->contains('constant', 'group_access'))
                    <li class="nav-item hidden-float" style="margin-top:15px;">

                        <select class="form-control select2"  tabindex="-1" aria-hidden="true" id="cpny" onchange="setcpny()">
                            @php
                                $user=Auth::user();
                                  $companies=companies();

                                /*//check if the logged in staff is

                                 //a branch manager SSM/area manager
                                 $branch=\App\Branch::where('manager_id',$user->id)->pluck('id')->toArray();
                                 if (count($branch)>1){
                                    $companies=\App\Company::whereIn('branch_id',$branch)->get();
                                 }
                                //a regional manager (Regional Lead or HQ Area Manager)
                                $regional=\App\Region::where('regional_lead_id',$user->id)->orWhere('area_manager_id',$user->id)->first();
                                 if ($regional){
                                    $branches=$regional->branches->pluck('id')->toArray();
                                    $companies=\App\Company::whereIn('branch_id',$branches)->get();
                                 }

                                 //a state Representative
                                 $states=\App\State::where('rep_id',$user->id)->pluck('id')->toArray();
                                  if (count($states)>0){
                                        $companies=\App\Company::whereIn('state_id',$states)->get();
                                  }*/


                            $branches=$companies->groupBy('branch.id');
                            @endphp




                            @foreach($branches as $key => $branch)
                                <optgroup label="{{ $b_name=\App\Branch::find($key)->name }} Branch" data-select2-id="54">
                                    @foreach($branch as $company)
                                        <option value="{{$company->id}}"{{$company->id==session('company_id')?'selected':''}}>{{$company->name}} ({{ $company->users->where('status','1')->count() }})</option>
                                    @endforeach

                                </optgroup>
                            @endforeach

                        </select>

                    </li>
                @else
                    <li class="nav-item hidden-sm-down" id="toggleFullscreen">
                        <a class="nav-link " href="#" role="button" style="font-size: 16px;">
                            {{$title}}
                        </a>
                    </li>
                @endif

                {{--
                                <li class="nav-item hidden-float" style="margin-top:15px;">
                                    <select class="form-control " id="fiscalyear" onchange="setfy()">
                                        <option>- {{__('Fiscal Year')}} -</option>


                                        @for($i=2016; $i<=date('Y'); $i++ )

                                            <option value="{{$i}}">{{$i}}</option>
                                        @endfor
                                    </select>
                                </li>--}}
                {{--  <li class="nav-item dropdown">
                   <a class="nav-link" data-toggle="dropdown" href="javascript:void(0)" data-animation="scale-up"
                   aria-expanded="false" role="button">
                     <span class="flag-icon flag-icon-us"></span>
                   </a>
                   <div class="dropdown-menu" role="menu">
                     <a class="dropdown-item" href="javascript:void(0)" role="menuitem">
                       <span class="flag-icon flag-icon-gb"></span> English</a>
                     <a class="dropdown-item" href="javascript:void(0)" role="menuitem">
                       <span class="flag-icon flag-icon-fr"></span> French</a>
                     <a class="dropdown-item" href="javascript:void(0)" role="menuitem">
                       <span class="flag-icon flag-icon-cn"></span> Chinese</a>
                     <a class="dropdown-item" href="javascript:void(0)" role="menuitem">
                       <span class="flag-icon flag-icon-de"></span> German</a>
                     <a class="dropdown-item" href="javascript:void(0)" role="menuitem">
                       <span class="flag-icon flag-icon-nl"></span> Dutch</a>
                   </div>
                 </li> --}}
                <li class="nav-item dropdown">
                    <a class="nav-link navbar-avatar" data-toggle="dropdown" href="#" aria-expanded="false"
                       data-animation="scale-up" role="button">
              <span class="avatar avatar-online">
                <img src="{{ File::exists('storage/avatar'.Auth::user()->image)?asset('storage/avatar'.Auth::user()->image):(Auth::user()->sex=='M'?asset('global/portraits/male-user.png'):asset('global/portraits/female-user.png'))}}"
                     alt="...">
                <i></i>
              </span>
                    </a>
                    <div class="dropdown-menu" role="menu">
                        <a class="dropdown-item" href="{{ url('userprofile') }}" role="menuitem"><i
                                    class="icon md-account" aria-hidden="true"></i> Profile</a>
                        @if(Auth::user()->role->permissions->contains('constant', 'edit_settings'))
                            <a class="dropdown-item" href="{{ url('settings') }}" role="menuitem"><i
                                        class="icon md-settings" aria-hidden="true"></i> Settings</a>
                        @endif
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="{{ route('logout') }}" role="menuitem" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();"><i
                                    class="icon md-power" aria-hidden="true"></i> {{ __('Logout') }}</a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </div>
                </li>
                @if(Auth::user()->role->permissions->contains('constant', 'edit_settings'))
                    <li class="nav-item dropdown">
                        <a class="nav-link" data-toggle="dropdown" href="javascript:void(0)" title="Settings"
                           aria-expanded="false" data-animation="scale-up" role="button">
                            <i class="icon md-settings" aria-hidden="true" style="font-size: 24px;"></i>

                        </a>
                        <div class="dropdown-menu" role="menu">


                            <a class="dropdown-item" href="{{ url('settings') }}" role="menuitem">General Settings</a>
                        </div>
                    </li>
                @endif


            </ul>
            <!-- End Navbar Toolbar Right -->
        </div>
        <!-- End Navbar Collapse -->
        <!-- Site Navbar Seach -->
        <div class="collapse navbar-search-overlap" id="site-navbar-search">
            <form role="search">
                <div class="form-group">
                    <div class="input-search">
                        <i class="input-search-icon md-search" aria-hidden="true"></i>
                        <input type="text" class="form-control" name="site-search" placeholder="Search...">
                        <button type="button" class="input-search-close icon md-close" data-target="#site-navbar-search"
                                data-toggle="collapse" aria-label="Close"></button>
                    </div>
                </div>
            </form>
        </div>
        <!-- End Site Navbar Seach -->
    </div>
</nav>
@include('layouts.nav')
@yield('content')

<!-- Footer -->
<footer class="site-footer">
    <div class="site-footer-legal">© {{date('Y')}}<a href=""> Snapnet</a></div>

</footer>
<!-- Core  -->
<script src="{{ asset('global/vendor/babel-external-helpers/babel-external-helpers.js') }}"></script>
<script src="{{ asset('global/vendor/jquery/jquery.js') }}"></script>
<script src="{{ asset('global/vendor/tether/tether.js') }}"></script>
<script src="{{ asset('global/vendor/bootstrap/bootstrap.js') }}"></script>
<script src="{{ asset('global/vendor/animsition/animsition.js') }}"></script>
<script src="{{ asset('global/vendor/mousewheel/jquery.mousewheel.js') }}"></script>
<script src="{{ asset('global/vendor/asscrollbar/jquery-asScrollbar.js') }}"></script>
<script src="{{ asset('global/vendor/asscrollable/jquery-asScrollable.js') }}"></script>
<script src="{{ asset('global/vendor/waves/waves.js') }}"></script>
<!-- Plugins -->
<script src="{{ asset('global/vendor/switchery/switchery.min.js') }}"></script>
<script src="{{ asset('global/vendor/intro-js/intro.js') }}"></script>
<script src="{{ asset('global/vendor/screenfull/screenfull.js') }}"></script>
<script src="{{ asset('global/vendor/slidepanel/jquery-slidePanel.js') }}"></script>
<script src="{{ asset('global/vendor/tablesaw/tablesaw.jquery.js') }}"></script>
<script src="{{ asset('global/vendor/slidepanel/jquery-slidePanel.js') }}"></script>
<script src="{{ asset('global/vendor/aspaginator/jquery.asPaginator.min.js') }}"></script>
<script src="{{ asset('global/vendor/jquery-placeholder/jquery.placeholder.js') }}"></script>
<script src="{{ asset('global/vendor/bootbox/bootbox.js') }}"></script>
<!-- Scripts -->
<script src="{{ asset('global/js/State.js') }}"></script>
<script src="{{ asset('global/js/Component.js') }}"></script>
<script src="{{ asset('global/js/Plugin.js') }}"></script>
<script src="{{ asset('global/js/Base.js') }}"></script>
<script src="{{ asset('global/js/Config.js') }}"></script>
<script src="{{ asset('assets/js/Section/Menubar.js') }}"></script>
<script src="{{ asset('assets/js/Section/Sidebar.js') }}"></script>
<script src="{{ asset('assets/js/Section/PageAside.js') }}"></script>
<script src="{{ asset('assets/js/Plugin/menu.js') }}"></script>
<script src="{{ asset('global/js/config/colors.js') }}"></script>
<script src="{{ asset('assets/js/config/tour.js') }}"></script>
<script>
    Config.set('assets', '{{ asset('assets') }}');
</script>
<script src="{{ asset('assets/js/Site.js') }}"></script>
<script src="{{ asset('global/js/Plugin/asscrollable.js') }}"></script>
<script src="{{ asset('global/js/Plugin/slidepanel.js') }}"></script>
<script src="{{ asset('global/js/Plugin/switchery.js') }}"></script>
<script src="{{ asset('global/js/Plugin/tablesaw.js') }}"></script>
<script src="{{ asset('global/js/Plugin/sticky-header.js') }}"></script>
<script src="{{ asset('global/js/Plugin/action-btn.js') }}"></script>
<script src="{{ asset('global/js/Plugin/asselectable.js') }}"></script>
<script src="{{ asset('global/js/Plugin/editlist.js') }}"></script>
<script src="{{ asset('global/js/Plugin/aspaginator.js') }}"></script>
<script src="{{ asset('global/js/Plugin/animate-list.js') }}"></script>
<script src="{{ asset('global/js/Plugin/jquery-placeholder.js') }}"></script>
<script src="{{ asset('global/js/Plugin/material.js') }}"></script>
<script src="{{ asset('global/js/Plugin/selectable.js') }}"></script>
<script src="{{ asset('global/js/Plugin/bootbox.js') }}"></script>
<script src="{{ asset('assets/js/BaseApp.js') }}"></script>
<script src="{{ asset('assets/js/App/Contacts.js') }}"></script>
<script src="{{ asset('assets/examples/js/apps/contacts.js') }}"></script>

<script src="{{ asset('global/vendor/toastr/toastr.js') }}"></script>
<script src="{{ asset('global/js/Plugin/toastr.js') }}"></script>
<script src="{{ asset('global/vendor/datatables/jquery.dataTables.js') }}"></script>
<script src="{{ asset('global/vendor/datatables-fixedheader/dataTables.fixedHeader.js') }}"></script>
<script src="{{ asset('global/vendor/datatables-bootstrap/dataTables.bootstrap.js') }}"></script>

@yield('scripts')
<script type="text/javascript" src="{{ asset('assets/js/jquery.thooClock.js') }}"></script>
<script src="{{asset('global/vendor/select2/select2.min.js')}}"></script>

<script type="text/javascript">
    $(document).ready(function() {
        function ajaxStart() {
            toastr.remove();
            toastr.info('Processing ...');
            $('.btn').attr('disabled', true);

        }

        function ajaxStop() {
            $('.btn').attr('disabled', false);

            toastr.info('Done.');

        }

        function selectAjax(id, url) {
            $(`#${id}`).select2({
                ajax: {
                    delay: 250,
                    processResults: function (data) {

                        return {

                            results: data
                        };
                    },


                    url: function (params) {
                        return url;
                    }

                }
            });
        }

        // global Ajaxstart end
        $(document).ajaxStart(function () {
            ajaxStart();
            document.getElementById("loader").style.display = "block";
        }).ajaxStop(function () {
            ajaxStop();
            document.getElementById("loader").style.display = "none";
        });
        $('.select2').select2();
    });
    $(function () {

        $('#clockin').thooClock();

        setInterval(function () {

            $('#time').html(new Date(new Date().getTime()).toLocaleTimeString());


        }, 1000);
    });
</script>
<script type="text/javascript">
    $(function () {

        $('.site-menu-item a[href*="{{Request::url()}}"]').parent().addClass('active');
        $('.site-menu-item a[href*="{{Request::url()}}"]').parent().parent().parent().addClass('active').addClass('open');
    });
</script>


</body>
</html>