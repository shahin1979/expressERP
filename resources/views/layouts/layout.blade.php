<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>HR Management System</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="robots" content="all,follow">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Bootstrap CSS-->
    <link href="{!! asset('assets/bootstrap-4.3.1/css/bootstrap.min.css') !!}" rel="stylesheet" type="text/css" />
    <!-- Font Awesome CSS-->
    <link href="{!! asset('assets/font-awesome-4.7.0/css/font-awesome.min.css') !!}" rel="stylesheet" type="text/css" />
    <!-- theme stylesheet-->
    <link href="{!! asset('assets/css/style.default.css') !!}" rel="stylesheet" type="text/css" />
    {{--<link rel="shortcut icon" href="https://d19m59y37dris4.cloudfront.net/admin-premium/1-4-4/img/favicon.ico">--}}


    <link href="{!! asset('assets/DataTables-1.10.18/css/jquery.dataTables.min.css') !!}" rel="stylesheet" type="text/css" />
    <link href="{!! asset('assets/jquery-ui-1.12.1/jquery-ui.css') !!}" rel="stylesheet" type="text/css" />

    <link href="{!! asset('assets/css/mdb.css') !!}" rel="stylesheet" type="text/css" />

    <link href="{!! asset('assets/tabs/css/style.css') !!}" rel="stylesheet" type="text/css" />

    <link href="{!! asset('assets/css/jquery.datetimepicker.min.css') !!}" rel="stylesheet" type="text/css" />




    {{--    <script type="text/javascript" src="{!! asset('assets/jquery-ui-1.12.1/jquery-ui.js') !!}"></script>--}}


</head>
<body>

<div class="page">
    <!-- Main Navbar-->
    <header class="header">
        <nav class="navbar">

            <div class="container-fluid">
                <div class="navbar-holder d-flex align-items-center justify-content-between">
                    <!-- Navbar Header-->
                    <div class="navbar-header">
                        <!-- Navbar Brand -->
                        <a href="#" class="navbar-brand d-none d-sm-inline-block">
                            <div class="col-md-4 col-sm-2 col-xs-3">
                                <img src="{!! asset('assets/images/HRMLogo-01.png') !!}" style="height: 25%" class="img-responsive">
                            </div>
                        </a>

                        <!-- Toggle Button--><a id="toggle-btn" href="#" class="menu-btn active"><span></span><span></span><span></span></a>
                    </div>
                    <!-- Navbar Menu -->
                    <ul class="nav-menu list-unstyled d-flex flex-md-row align-items-md-center">
                        <!-- Logout    -->
                        <li class="nav-item">

                            <a class="nav-link logout" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();"> <span class="d-none d-sm-inline">Logout</span><i class="fa fa-sign-out"></i></a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <div class="page-content d-flex align-items-stretch">
        <!-- Side Navbar -->
        <nav class="side-navbar">
            <!-- Sidebar Header-->
            <div class="sidebar-header d-flex align-items-center"><a href="#">
                    <div class="avatar"><img src="{!! asset('assets/images/male.jpeg') !!}" alt="..." class="img-fluid rounded-circle"></div></a>
                <div class="title">
                    <h1 class="h4">{!! \Illuminate\Support\Facades\Auth::user()->name !!}</h1>
                    <p style="font-weight: bold;">{!! \Illuminate\Support\Facades\Session::get('session_user_dept_name') !!}</p>
                    {{--<p>--}}
                    {{--@if($user->isOnline())--}}

                    {{--{!! \Illuminate\Support\Facades\Cache::get('user-is-online-'.\Illuminate\Support\Facades\Auth::id())!!}--}}

                    {{--Cache::get('key');--}}
                    {{--@endif--}}
                    {{--</p>--}}
                </div>
            </div>
            <!-- Sidebar Navidation Menus-->

            <ul class="list-unstyled">
                <li class="active"><a href="{!! route('home') !!}"> <i class="icon-home"></i>Home </a></li>
            </ul>



            <ul class="list-unstyled">

                @include('partials.notice-menu')

                <span class="heading" style="font-weight: bold; color: #980000">HRM</span>

                <li><a class="font-weight-bold" href="#authDropdown" aria-expanded="false" data-toggle="collapse"> <i class="icon-grid"></i>AUTH</a>
                    <ul id="authDropdown" class="collapse list-unstyled ">
                        <li><a href="{{ route('register') }}">Add User</a></li>
                        <li><a href="{!! route('privillege/index') !!}">User Privillege</a></li>
                        <li><a href="{!! route('password/reset') !!}">Change Password</a></li>
                        <li><a href="{!! url('password/check') !!}">Reset Password</a></li>
                        <li><a href="#">Report</a></li>

                    </ul>
                </li>

                {{--@if(get_user_role_id(\Illuminate\Support\Facades\Auth::id())!=3)--}}

                <li><a class="font-weight-bold" href="#companyDropdown" aria-expanded="false" data-toggle="collapse"> <i class="icon-grid"></i>COMPANY</a>
                    <ul id="companyDropdown" class="collapse list-unstyled ">
                        <li><a href="{!! route('company/index') !!}">Company Info</a></li>
                        <li><a href="#">Report</a></li>
                        {{--<li><a href="{!! route('doctor/index') !!}">Doctors</a></li>--}}
                        {{--<li><a href="#">Add Assistant</a></li>--}}

                    </ul>
                </li>

                <li><a class="font-weight-bold" href="#adminDropdown" aria-expanded="false" data-toggle="collapse"> <i class="icon-grid"></i>ADMIN</a>
                    <ul id="adminDropdown" class="collapse list-unstyled ">
                        <li><a href="{!! route('admin/divisionIndex') !!}">Divisions</a></li>
                        <li><a href="{!! route('admin/departmentIndex') !!}">Departments</a></li>
                        <li><a href="{!! route('admin/sectionIndex') !!}">Sections</a></li>
                        <li><a href="#">Report</a></li>
                        {{--<li><a href="{!! route('doctor/index') !!}">Doctors</a></li>--}}
                        {{--<li><a href="#">Add Assistant</a></li>--}}

                    </ul>
                </li>
                {{--@endif--}}


                <li><a class="font-weight-bold" href="#empDropdown" aria-expanded="false" data-toggle="collapse"> <i class="icon-grid"></i>EMPLOYEE</a>
                    <ul id="empDropdown" class="collapse list-unstyled ">
                        <li><a href="{!! route('employee/designationIndex') !!}">Designations</a></li>
                        <li><a href="{!! route('employee/titleIndex') !!}">Titles</a></li>
                        <li><a href="{!! route('employee/employeeIndex') !!}">Employee Regular</a></li>

                        {{--<li><a href="{!! route('employee/employeeIndex') !!}">Employee Regular</a></li>--}}

                        <li><a href="#empReportDropdown" aria-expanded="false" data-toggle="collapse">Report</a></li>
                        <ul id="empReportDropdown" class="collapse list-unstyled " style="padding-left: 20px">
                            {{--<li><a href="{!! route('employee/empProfileIndex') !!}">Employee Details</a></li>--}}
                            <li><a href="{!! route('employee/report/empListIndex') !!}">Employee List</a></li>
                            {{--                                <li><a href="{!! route('employee/report/inactiveEmpListIndex') !!}">Inactive Employee List</a></li>--}}
                        </ul>

                        {{--                        <li><a href="{!! route('employee/sectionIndex') !!}">Units</a></li>--}}
                        {{--<li><a href="{!! route('doctor/index') !!}">Doctors</a></li>--}}
                        {{--<li><a href="#">Add Assistant</a></li>--}}

                    </ul>
                </li>

                <li><a class="font-weight-bold" href="#rosterDropdown" aria-expanded="false" data-toggle="collapse"> <i class="icon-grid"></i>ROSTER</a>
                    <ul id="rosterDropdown" class="collapse list-unstyled ">
                        <li><a href="{!! route('roster/locationIndex') !!}">Duty Locations</a></li>
                        <li><a href="{!! route('roster/shiftIndex') !!}">Roster Settings</a></li>
                        <li><a href="{!! route('roster/employeeRosterIndex') !!}">Roster Entry</a></li>
                        <li><a href="{!! route('roster/updateRosterIndex') !!}">Roster Update</a></li>
                        <li><a href="{!! route('roster/approveRosterIndex') !!}">Roster Approve</a></li>
                        <li><a href="{!! route('roster/printRosterIndex') !!}">Roster Print</a></li>
                        <li><a href="{!! route('roster/printRosterWiseEmployeeIndex') !!}">Roster Wise Employee List</a></li>
                        <li><a href="#">Report</a></li>
                        {{--                        <li><a href="{!! route('employee/sectionIndex') !!}">Units</a></li>--}}
                        {{--<li><a href="{!! route('doctor/index') !!}">Doctors</a></li>--}}
                        {{--<li><a href="#">Add Assistant</a></li>--}}

                    </ul>
                </li>


                <li><a class="font-weight-bold" href="#leaveDropdown" aria-expanded="false" data-toggle="collapse"> <i class="icon-grid"></i>LEAVE</a>
                    <ul id="leaveDropdown" class="collapse list-unstyled ">
                        <li><a href="{!! route('leave/masterIndex') !!}">Leave Master</a></li>
                        <li><a href="{!! route('leave/applyIndex') !!}">Apply For Leave</a></li>
                        <li><a href="{!! route('leave/acknowledgeIndex') !!}">Acknowledge Leave</a></li>

                        <li><a href="{!! route('leave/recommendIndex') !!}">Recommend Leave</a></li>
                        <li><a href="{!! route('leave/approveIndex') !!}">Approve Leave</a></li>
                        <li><a href="{!! route('leave/updateIndex') !!}">Update Leave</a></li>
                        {{--<li><a href="#">Report</a></li>--}}
                        <li><a href="{!! route('leave/pendingLeaveIndex') !!}">Pending Leave</a></li>
                        {{--<li><a href="{!! route('doctor/index') !!}">Doctors</a></li>--}}
                        {{--<li><a href="#">Add Assistant</a></li>--}}

                    </ul>
                </li>


                {{--<li><a class="font-weight-bold" href="#saleryDropdown" aria-expanded="false" data-toggle="collapse"> <i class="icon-grid"></i>PAYROLL</a>--}}
                {{--<ul id="saleryDropdown" class="collapse list-unstyled ">--}}
                {{--<li><a href="{!! route('payroll/salarySetupIndex') !!}">Salery Setup</a></li>--}}
                {{--<li><a href="{!! route('payroll/arearSetupIndex') !!}">Arear Setup</a></li>--}}
                {{--<li><a href="{!! route('payroll/advanceSetupIndex') !!}">Advance Setup</a></li>--}}
                {{--<li><a href="{!! route('payroll/food&otherSetupIndex') !!}">Food & Other Deduction</a></li>--}}
                {{--<li><a href="#saleryReportDropdown" aria-expanded="false" data-toggle="collapse">Report</a></li>--}}
                {{--<ul id="saleryReportDropdown" class="collapse list-unstyled " style="padding-left: 20px">--}}
                {{--<li><a href="{!! route('attendance/dateReportIndex') !!}">Department wise salery Report </a></li>--}}
                {{--</ul>--}}

                {{--</ul>--}}
                {{--</li>--}}



                <li><a class="font-weight-bold" href="#attendanceDropdown" aria-expanded="false" data-toggle="collapse"> <i class="icon-grid"></i>ATTENDANCE</a>
                    <ul id="attendanceDropdown" class="collapse list-unstyled ">
                        <li><a href="{!! route('attendance/processIndex') !!}">Attendance Process</a></li>
                        <li><a href="{!! route('attendance/holidayIndex') !!}">Holiday Setup</a></li>
                        <li><a href="{!! route('attendance/manualIndex') !!}">Manual Attendance</a></li>
                        <li><a href="{!! route('attendance/updateIndex') !!}">Modify Attendance</a></li>
                        <li><a href="{!! route('attendance/onDutyIndex') !!}">Employee On Duty</a></li>

                        <li><a href="#attReportDropdown" aria-expanded="false" data-toggle="collapse">Report</a></li>
                        <ul id="attReportDropdown" class="collapse list-unstyled " style="padding-left: 20px">
                            <li><a href="{!! route('attendance/dateReportIndex') !!}">Date Wise Attendance</a></li>
                            <li><a href="{!! route('attendance/dateRangeReportIndex') !!}">Date Range Attendance</a></li>
                            <li><a href="{!! route('attendance/dailyAttendanceStatusIndex') !!}">Date Attendance Summery</a></li>
                            <li><a href="#">Employee Punch Info</a></li>
                        </ul>
                        {{--                        <li><a href="{!! route('employee/sectionIndex') !!}">Units</a></li>--}}
                        {{--<li><a href="{!! route('doctor/index') !!}">Doctors</a></li>--}}
                        {{--<li><a href="#">Add Assistant</a></li>--}}

                    </ul>
                </li>



                <li><a class="font-weight-bold" href="#overtimeDropdown" aria-expanded="false" data-toggle="collapse"> <i class="icon-grid"></i>OVERTIME</a>
                    <ul id="overtimeDropdown" class="collapse list-unstyled ">
                        <li><a href="{!! route('overtime/setupIndex') !!}">Overtime Setup</a></li>
                        {{--                        <li><a href="{!! route('overtime/updateIndex') !!}">Approve Overtime</a></li>--}}
                        <li><a href="{!! route('overtime/approveIndex') !!}">Approve Overtime</a></li>
                        <li><a href="{!! route('overtime/calculationIndex') !!}">Monthly Overtime Finalize</a></li>
                        {{--<li><a href="{!! route('attendance/updateIndex') !!}">Update Attendance</a></li>--}}
                        {{--<li><a href="{!! route('attendance/processIndex') !!}">Attendance Process</a></li>--}}
                        <li><a href="#overtimeReportDropdown" aria-expanded="false" data-toggle="collapse">Report</a></li>
                        <ul id="overtimeReportDropdown" class="collapse list-unstyled " style="padding-left: 20px">
                            <li><a href="{!! route('overtime/dateRangeReportIndex') !!}">Date Range Overtime Report</a></li>
                            <li><a href="{!! route('overtime/employeeOvertimeIndex') !!}">Employee Overtime Summary</a></li>
                            {{--<li><a href="#">Employee Punch Info</a></li>--}}
                        </ul>
                        {{--                        <li><a href="{!! route('employee/sectionIndex') !!}">Units</a></li>--}}
                        {{--<li><a href="{!! route('doctor/index') !!}">Doctors</a></li>--}}
                        {{--<li><a href="#">Add Assistant</a></li>--}}

                    </ul>
                </li>



                <li><a class="font-weight-bold" href="#trainingDropdown" aria-expanded="false" data-toggle="collapse"> <i class="icon-grid"></i>TRAINING</a>
                    <ul id="trainingDropdown" class="collapse list-unstyled ">
                        <li><a href="{!! route('training/newTrainingIndex') !!}">New Training</a></li>
                        <li><a href="{!! route('training/scheduleTrainingIndex') !!}">Schedule A Training</a></li>
                        {{--<li><a href="#">Select Trainee</a></li>--}}
                        {{--<li><a href="#">Complete Training</a></li>--}}
                        <li><a href="#">Report</a></li>
                        {{--                        <li><a href="{!! route('employee/sectionIndex') !!}">Units</a></li>--}}
                        {{--<li><a href="{!! route('doctor/index') !!}">Doctors</a></li>--}}
                        {{--<li><a href="#">Add Assistant</a></li>--}}

                    </ul>
                </li>


                @include('partials.payroll')


                @include('partials.external')

                @include('partials.report-menu')

                @include('partials.food-beverages')
            </ul>

        </nav>
        <div class="content-inner">
            <!-- Page Header-->
            <header class="page-header">
                <div class="container-fluid">
                    @yield('pagetitle')

                </div>
            </header>


            <main class="py-4">
                @include('partials.flash-message')

                @yield('content')
            </main>




            <!-- Page Footer-->
            <footer class="main-footer">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-6">
                            <p>BRB Hospitals Ltd &copy; 2014-2019</p>
                        </div>
                        <div class="col-sm-6 text-right">
                            <p>Version 1.0.0</p>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>
</div>

<!-- JavaScript files-->


{{--<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>--}}

{{--<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>--}}
<script type="text/javascript" src="{!! asset('assets/bootstrap-4.3.1/js/bootstrap.min.js') !!}"></script>
{{--<!-- Main File-->--}}
<script type="text/javascript" src="{!! asset('assets/js/front.js') !!}"></script>

<script type="text/javascript" src="{!! asset('assets/DataTables-1.10.18/js/jquery.dataTables.min.js') !!}"></script>
<script type="text/javascript" src="{!! asset('assets/DataTables-1.10.18/js/dataTables.jqueryui.min.js') !!}"></script>

<script type="text/javascript" src="{!! asset('assets/js/jquery.datetimepicker.js') !!}"></script>
<script type="text/javascript" src="{!! asset('assets/dist/bs-custom-file-input.min.js') !!}"></script>

{{--<script type="text/javascript" src="{!! asset('assets/js/jquery-3.3.1.min.js') !!}"></script>--}}
{{--<script type="text/javascript" src="{!! asset('assets/jquery-ui-1.12.1/jquery-ui.js') !!}"></script>--}}

<script type="text/javascript">
    function idleTimer() {
        var t;
        //window.onload = resetTimer;
        window.onmousemove = resetTimer; // catches mouse movements
        window.onmousedown = resetTimer; // catches mouse movements
        window.onclick = resetTimer;     // catches mouse clicks
        window.onscroll = resetTimer;    // catches scrolling
        window.onkeypress = resetTimer;  //catches keyboard actions

        function logout() {
            window.location.href = '/logout';  //Adapt to actual logout script
        }

        function reload() {
            window.location = self.location.href;  //Reloads the current page
        }

        function resetTimer() {
            clearTimeout(t);
            // t = setTimeout(logout, 300000);  // time is in milliseconds (1000 is 1 second)
            t= setTimeout(reload, 400000);  // time is in milliseconds (1000 is 1 second)
        }
    }
</script>


@stack('scripts')

</body>
</html>
