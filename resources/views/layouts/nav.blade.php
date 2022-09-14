<div class="site-menubar bg-white blue-grey-800" style="background-color: #fef228">
    <div class="site-menubar-body">
        <div>
            <div>
                <ul class="site-menu" data-plugin="menu">
                    <li class="site-menu-item ">
                        <a href="{{ route('home') }}" dropdown-tag="false">
                            <i class="site-menu-icon md-home" aria-hidden="true"></i>
                            <span class="site-menu-title">Home</span>
                        </a>
                    </li>


                    <li class="dropdown site-menu-item has-sub">
                        <a data-toggle="dropdown" href="javascript:void(0)" data-dropdown-toggle="false">
                            <i class="site-menu-icon fa fa-sitemap" aria-hidden="true"></i>
                            <span class="site-menu-title">Self Service</span>
                            <span class="site-menu-arrow"></span>
                        </a>
                        <div class="dropdown-menu">
                            <div class="site-menu-scroll-wrap is-list">
                                <div>
                                    <div>
                                        <ul class="site-menu-sub site-menu-normal-list">

                                            <li class="site-menu-item ">
                                                <a class="animsition-link" href="{{ route('attendance.user') }}">
                                                    <span class="site-menu-title">New My Attendance Calendar</span>
                                                </a>
                                            </li>
                                            <li class="site-menu-item ">
                                                <a class="animsition-link" href="{{ route('attendance.userr') }}">
                                                    <span class="site-menu-title">My attendance</span>
                                                </a>
                                            </li>
                                            <li class="site-menu-item ">
                                                <a class="animsition-link" href="{{ url('leave/myrequests') }}">
                                                    <span class="site-menu-title">Leave Requests</span>
                                                </a>
                                            </li>
                                            <li class="site-menu-item ">
                                                <a class="animsition-link" href="{{ route('my.shift.schedules') }}">
                                                    <span class="site-menu-title">My Shift Schedules Table</span>
                                                </a>
                                            </li>
                                            <li class="site-menu-item ">
                                                <a class="animsition-link"
                                                   href="{{ route('shift_schedule.user',Auth::user()->id) }}">
                                                    <span class="site-menu-title">My Shift Schedules Calendar</span>
                                                </a>
                                            </li>
                                            <li class="site-menu-item ">
                                                <a class="animsition-link" href="{{ route('my.exemptions') }}">
                                                    <span class="site-menu-title">My Exemptions</span>
                                                </a>
                                            </li>

                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>

                    @if(Auth::user()->role->permissions->contains('constant', 'manage_user')||Auth::user()->role->permissions->contains('constant', 'view_timesheet')||Auth::user()->role->permissions->contains('constant', 'export_timesheet')||Auth::user()->role->permissions->contains('constant', 'view_attendance')||Auth::user()->role->permissions->contains('constant', 'view_shift_schedule')||Auth::user()->role->permissions->contains('constant', 'approve_shift_swap')||Auth::user()->role->permissions->contains('constant', 'succession_planning'))
                        <li class="dropdown site-menu-item has-sub">
                            <a data-toggle="dropdown" href="javascript:void(0)" data-dropdown-toggle="false">
                                <i class="site-menu-icon fa fa-address-book" aria-hidden="true"></i>
                                <span class="site-menu-title">Core Administrative HR</span>
                                <span class="site-menu-arrow"></span>
                            </a>
                            <div class="dropdown-menu">
                                <div class="site-menu-scroll-wrap is-list">
                                    <div>
                                        <div>
                                            <ul class="site-menu-sub site-menu-normal-list">
                                                @if(Auth::user()->role->permissions->contains('constant', 'manage_user'))
                                                    <li class="site-menu-item ">
                                                        <a class="animsition-link" href="{{url('users')}}">
                                                            <span class="site-menu-title">Manage Employee</span>
                                                        </a>
                                                    </li>
                                                @endif
                                                @if(Auth::user()->role->permissions->contains('constant', 'manage_user'))
                                                    <li class="site-menu-item ">
                                                        <a class="animsition-link" href="{{route('status.requests')}}">
                                                            <span class="site-menu-title">Staff Status Change Request</span>
                                                        </a>
                                                    </li>
                                                @endif
                                                @if(Auth::user()->role->permissions->contains('constant', 'manage_user'))
                                                    <li class="site-menu-item ">
                                                        <a class="animsition-link" href="{{url('groups')}}">
                                                            <span class="site-menu-title">Manage User Groups</span>
                                                        </a>
                                                    </li>
                                                @endif

                                                 @if(Auth::user()->role->permissions->contains('constant', 'manage_user') && Auth::user()->role->permissions->contains('constant', 'verify_user'))
                                                    <li class="site-menu-item ">
                                                        <a class="animsition-link" href="{{route('verify.staff')}}">
                                                            <span class="site-menu-title">Verify Staff</span>
                                                        </a>
                                                    </li>
                                                @endif

                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                    @endif

                    @if(Auth::user()->role->permissions->contains('constant', 'view_shift_schedule')||Auth::user()->role->permissions->contains('constant', 'approve_shift_swap'))
                        <li class="dropdown site-menu-item has-sub">
                            <a data-toggle="dropdown" href="javascript:void(0)" data-dropdown-toggle="false">
                                <i class="site-menu-icon fa fa-address-book" aria-hidden="true"></i>
                                <span class="site-menu-title">Shifts Management</span>
                                <span class="site-menu-arrow"></span>
                            </a>
                            <div class="dropdown-menu">
                                <div class="site-menu-scroll-wrap is-list">
                                    <div>
                                        <div>
                                            <ul class="site-menu-sub site-menu-normal-list">
                                                @if(Auth::user()->role->permissions->contains('constant', 'view_shift_schedule'))
                                                    <li class="site-menu-item ">
                                                        <a class="animsition-link" href="{{url('employee_shift_schedules')}}">
                                                            <span class="site-menu-title">Shift Schedule</span>
                                                        </a>
                                                    </li>
                                                @endif
                                                @if(Auth::user()->role->permissions->contains('constant', 'approve_shift_swap'))
                                                    <li class="site-menu-item ">
                                                        <a class="animsition-link" href="{{url('users')}}">
                                                            <span class="site-menu-title">Shift Swaps</span>
                                                        </a>
                                                    </li>
                                                @endif

                                                @if(Auth::user()->role->permissions->contains('constant', 'view_shift_schedule'))
                                                    <li class="site-menu-item ">
                                                        <a class="animsition-link"
                                                           href="{{ route('exemption.approvals') }}">
                                                            <span class="site-menu-title">Exemption Approvals</span>
                                                        </a>
                                                    </li>
                                                @endif


                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                    @endif


                    @if(Auth::user()->role->permissions->contains('constant', 'manage_user')||Auth::user()->role->permissions->contains('constant', 'view_timesheet')||Auth::user()->role->permissions->contains('constant', 'export_timesheet')||Auth::user()->role->permissions->contains('constant', 'view_attendance')||Auth::user()->role->permissions->contains('constant', 'view_shift_schedule')||Auth::user()->role->permissions->contains('constant', 'approve_shift_swap')||Auth::user()->role->permissions->contains('constant', 'succession_planning'))
                        <li class="dropdown site-menu-item has-sub">
                            <a data-toggle="dropdown" href="javascript:void(0)" data-dropdown-toggle="false">
                                <i class="site-menu-icon fa fa-address-book" aria-hidden="true"></i>
                                <span class="site-menu-title">Attendance Reports</span>
                                <span class="site-menu-arrow"></span>
                            </a>
                            <div class="dropdown-menu">
                                <div class="site-menu-scroll-wrap is-list">
                                    <div>
                                        <div>
                                            <ul class="site-menu-sub site-menu-normal-list">
                                                @if(Auth::user()->role->permissions->contains('constant', 'view_attendance'))
                                                    <li class="site-menu-item ">
                                                        <a class="animsition-link" href="{{url('attendance/reports')}}">
                                                            <span class="site-menu-title">Daily Attendance Report</span>
                                                        </a>
                                                    </li>
                                                    <li class="site-menu-item ">
                                                        <a class="animsition-link" href="{{url('monthly/attendance/reports')}}">
                                                            <span class="site-menu-title">Monthly Attendance Report</span>
                                                        </a>
                                                    </li>
                                                @endif
                                                @if(Auth::user()->role->permissions->contains('constant', 'view_timesheet'))
                                                    <li class="site-menu-item ">
                                                        <a class="animsition-link"
                                                           href="{{route('attendance.all.staff')}}">
                                                            <span class="site-menu-title">Staff Attendance Report</span>
                                                        </a>
                                                    </li>
                                                @endif
                                                @if(Auth::user()->role->permissions->contains('constant', 'view_timesheet'))
                                                    <li class="site-menu-item ">
                                                        <a class="animsition-link" href="{{route('late.staff')}}">
                                                            <span class="site-menu-title">Staff Lateness Report</span>
                                                        </a>
                                                    </li>
                                                @endif
                                                @if(Auth::user()->role->permissions->contains('constant', 'view_timesheet'))
                                                   <!-- <li class="site-menu-item ">
                                                        <a class="animsition-link" href="{{route('staff.timesheet')}}">
                                                            <span class="site-menu-title">Staff Timesheet</span>
                                                        </a>
                                                    </li>-->
                                                @endif

                                                @if(Auth::user()->role->permissions->contains('constant', 'view_timesheet'))
                                                    <li class="site-menu-item ">
                                                        <a class="animsition-link" href="{{route('exemptions.index')}}">
                                                            <span class="site-menu-title">View Exemptions</span>
                                                        </a>
                                                    </li>
                                                @endif
                                                @if(Auth::user()->role->permissions->contains('constant', 'view_timesheet'))
                                                        <li class="site-menu-item ">
                                                            <a class="animsition-link"
                                                               href="{{ route('executive.view_attendance') }}">
                                                                <span class="site-menu-title">Executive Report</span>
                                                            </a>
                                                        </li>
                                                @endif


                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                    @endif


                    @if(Auth::user()->role->permissions->contains('constant', 'financial_report'))
                        <li class="dropdown site-menu-item has-sub">
                            <a data-toggle="dropdown" href="javascript:void(0)" data-dropdown-toggle="false">
                                <i class="site-menu-icon fa fa-address-book" aria-hidden="true"></i>
                                <span class="site-menu-title">Financial Reports</span>
                                <span class="site-menu-arrow"></span>
                            </a>
                            <div class="dropdown-menu">
                                <div class="site-menu-scroll-wrap is-list">
                                    <div>
                                        <div>
                                            <ul class="site-menu-sub site-menu-normal-list">
                                                @if(Auth::user()->role->permissions->contains('constant', 'view_attendance'))
                                                    <li class="site-menu-item ">
                                                        <a class="animsition-link" href="{{route('monthly.financial')}}">
                                                            <span class="site-menu-title">Monthly Financial Report</span>
                                                        </a>
                                                    </li>
                                                    <li class="site-menu-item ">
                                                        <a class="animsition-link" href="{{route('station.financial')}}">
                                                            <span class="site-menu-title">Station Financial Report</span>
                                                        </a>
                                                    </li>
                                                @endif
                                                @if(Auth::user()->role->permissions->contains('constant', 'view_timesheet'))
                                                    <li class="site-menu-item ">
                                                        <a class="animsition-link"
                                                           href="{{route('attendance.all.staff')}}">
                                                            <span class="site-menu-title">Role Financial Report</span>
                                                        </a>
                                                    </li>
                                                @endif
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                    @endif



                </ul>
            </div>
        </div>
    </div>
</div>