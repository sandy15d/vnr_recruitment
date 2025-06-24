@php

    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\DB;
    $Notification = DB::table('notification')
        ->where('userid', Auth::user()->id)
        ->where('status', 0)
        ->where('notification_read', 0)
        ->orderBy('id', 'DESC')
        ->get();
    $NotificationCount = $Notification->count();

    $CompanyQry = DB::table('core_company')
        ->where('id', session('Set_Company'))
        ->first();
    $CountryQry = DB::table('core_country')
        ->where('id', session('Set_Country'))
        ->first();
    $permission = DB::table('permission')
        ->leftJoin('user_permission', 'permission.PId', '=', 'user_permission.PId')
        ->where('user_permission.UserId', Auth::user()->id)
        ->select('permission.PageName')
        ->get();
    $resultArray = json_decode(json_encode($permission), true);



@endphp
    <!doctype html>
<html lang="en" class="{{ session('ThemeStyle') }} {{ session('SidebarColor') }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="{{ URL::to('/') }}/assets/js/jquery.min.js"></script>

    <link rel="stylesheet" href="{{ URL::to('/') }}/assets/css/mystyle.css"/>
    <link rel="icon" href="{{ URL::to('/') }}/assets/images/hr.png" type="image/png"/>
    <link href="{{ URL::to('/') }}/assets/plugins/simplebar/css/simplebar.css" rel="stylesheet"/>
    <link href="{{ URL::to('/') }}/assets/plugins/select2/css/select2.min.css" rel="stylesheet"/>
    <link href="{{ URL::to('/') }}/assets/plugins/select2/css/select2-bootstrap4.css" rel="stylesheet"/>
    <link href="{{ URL::to('/') }}/assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css" rel="stylesheet"/>
    <link href="{{ URL::to('/') }}/assets/plugins/metismenu/css/metisMenu.min.css" rel="stylesheet"/>
    <link href="{{ URL::to('/') }}/assets/css/pace.min.css" rel="stylesheet"/>
    <script src="{{ URL::to('/') }}/assets/js/pace.min.js"></script>
    <link href="{{ URL::to('/') }}/assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&amp;display=swap" rel="stylesheet">
    <link href="{{ URL::to('/') }}/assets/css/custom.css" rel="stylesheet">
    <link href="{{ URL::to('/') }}/assets/css/app.css" rel="stylesheet">

    <link href="{{ URL::to('/') }}/assets/css/icons.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ URL::to('/') }}/assets/css/dark-theme.css"/>
    <link rel="stylesheet" href="{{ URL::to('/') }}/assets/css/semi-dark.css"/>
    <link rel="stylesheet" href="{{ URL::to('/') }}/assets/css/header-colors.css"/>
    <link rel="stylesheet" href="{{ URL::to('/') }}/assets/css/sweetalert2.min.css"/>
    <link rel="stylesheet" href="{{ URL::to('/') }}/assets/css/toastr.min.css"/>
    <link rel="stylesheet" href="{{ URL::to('/') }}/assets/css/BsMultiSelect.css"/>
    <link href="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/css/bootstrap4-toggle.min.css"
          rel="stylesheet">
    <link href="{{ URL::to('/') }}/assets/plugins/datatable/css/dataTables.bootstrap5.min.css" rel="stylesheet"/>
    <link href="{{ URL::to('/') }}/assets/plugins/datatable/css/dataTablesButtons.css" rel="stylesheet"/>
    <script src="https://kit.fontawesome.com/ffb012e977.js" crossorigin="anonymous"></script>
    <script src="{{ URL::to('/') }}/assets/ckeditor/ckeditor.js"></script>

    <script src="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/js/bootstrap4-toggle.min.js"></script>
    <title>@yield('title')</title>

    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/index.global.min.js'></script>
    <script>
        $(document).ready(function () {
            $('a[data-bs-toggle="tab"]').on('show.bs.tab', function (e) {

                localStorage.setItem('activeTab', $(e.target).attr('href'));
            });
            var activeTab = localStorage.getItem('activeTab');
            if (activeTab) {
                $('#myTab a[href="' + activeTab + '"]').tab('show');
            }
        });
    </script>
    <style>
        .btn--red {
            color: #fff;
            background-color: #e944ff;
            background: linear-gradient(103deg, #ff0036 0%, #ff9116 0%, #fe19fe 100%);
        }

        .btn--new {
            color: #fff;
            background: #2193b0;

            background: -webkit-linear-gradient(to right, #6dd5ed, #2193b0);
            background: linear-gradient(to right, #6dd5ed, #2193b0);
        }

        .btn--edit {
            color: #fff;
            background: #8360c3;
            background: -webkit-linear-gradient(to right, #00416A, #E4E5E6);
            background: linear-gradient(to right, #00416A, #E4E5E6);

        }

        .btn--green {
            color: #fff;
            background: #d7e428;
            background: -webkit-linear-gradient(to right, #56ab2f, #a8e063);
            background: linear-gradient(to right, #56ab2f, #a8e063);

        }

        .btn-xs {
            padding: .35rem .4rem;
            font-size: .875rem;

            border-radius: .2rem;
        }

        .borderless td,
        .borderless th {
            border: none;
        }

        .errorfield {
            border: 2px solid #E8290B;
        }

        div.dt-buttons {
            float: right;
            height: 50px;
        }

        .btn-outline-secondary {
            position: relative;
            display: inline-block;
            /*   box-sizing: border-box; */
            margin-right: 0.333em;
            padding: 2px 6.2px;
            border-radius: 0px;
            cursor: pointer;
            font-size: 14px;
            white-space: nowrap;
            overflow: hidden;
            /*  background-color: #fff; */
            outline: none;
            border-top: 0;
            border-left: 0;
            border-right: 0;
            border-bottom: 0;
            /*  border-bottom: 1px solid #ddd; */
        }

        .overlay {
            display: none;
            position: fixed;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            z-index: 999;
            background: rgba(255, 255, 255, 0.8) url("loader.gif") center no-repeat;
        }


        body.loading {
            overflow: hidden;
        }

        body.loading .overlay {
            display: block;
        }

        .fc-myCustomButton-button {

            color: #fff !important;
            background-color: #0e8452 !important;
            border-color: #0e8452 !important;
        }

        /*.fc-event, .fc-event-title {
            padding: 0 1px;
            white-space: normal !important;
        }*/

    </style>

</head>

<body>
<div class="wrapper">
    <div class="sidebar-wrapper" data-simplebar="true">
        <div class="sidebar-header">
            <div>
                <img src="{{ URL::to('/') }}/assets/images/hr.png" class="logo-icon" alt="HR">
            </div>
            <div>
                <h4 class="logo-text" style="color:#0e8452;">HR Recruitment</h4>
            </div>
            <div class="toggle-icon ms-auto"><i class='bx bx-arrow-to-left' style="color:#0e8452 "></i>
            </div>
        </div>
        <!--navigation-->

        <ul class="metismenu" id="menu">

            @if (Auth::user()->role == 'A')
                <li class="{{ request()->is('admin/dashboard') ? 'mm-active' : '' }}">
                    <a title="Dashboard" href="/admin/dashboard">
                        <div class="parent-icon"><i class="fas fa-laptop-house text-success"></i>
                        </div>
                        <div class="menu-title">Dashboard</div>
                    </a>
                </li>
                <li>
                    <a title="Master" href="javascript:;" class="has-arrow">
                        <div class="parent-icon"><i class="lni lni-react  text-danger"></i>
                        </div>
                        <div class="menu-title">Master</div>
                    </a>
                    <ul>
                        <li><a title="Company" href="/admin/company"><i
                                    class="bx bx-right-arrow-alt"></i>Company</a></li>
                        <li><a title="Country" href="/admin/country"><i
                                    class="bx bx-right-arrow-alt"></i>Country</a></li>
                        <li><a title="State(HQ)" href="/admin/state"><i
                                    class="bx bx-right-arrow-alt"></i>State(HQ)</a></li>
                        <li><a title="Headquarter" href="/admin/headquarter"><i
                                    class="bx bx-right-arrow-alt"></i>Headquarter</a></li>
                        <li><a title="Department" href="/admin/department"><i
                                    class="bx bx-right-arrow-alt"></i>Department</a></li>
                        <li><a title="Designation" href="/admin/designation"><i
                                    class="bx bx-right-arrow-alt"></i>Designation</a></li>
                        <li><a title="Grade" href="/admin/grade"><i class="bx bx-right-arrow-alt"></i>Grade</a>
                        </li>

                        <li><a title="States(General Purpose)" href="/admin/gen_states"><i
                                    class="bx bx-right-arrow-alt"></i>States(General Purpose)</a></li>
                        <li><a title="District" href="/admin/district"><i
                                    class="bx bx-right-arrow-alt"></i>District</a></li>
                        <li><a title="Education" href="/admin/education"><i
                                    class="bx bx-right-arrow-alt"></i>Education</a></li>
                        <li><a title="Education Specialization" href="/admin/eduspecialization"><i
                                    class="bx bx-right-arrow-alt"></i>Education Specialization</a></li>
                        <li><a title="Education Institute" href="/admin/institute"><i
                                    class="bx bx-right-arrow-alt"></i>Education Institute</a></li>
                        <li><a title="Resume Source" href="/admin/resumesource"><i
                                    class="bx bx-right-arrow-alt"></i>Resume Source</a>
                        </li>
                        <li><a title="Employee" href="/admin/employee"><i
                                    class="bx bx-right-arrow-alt"></i>Employee</a></li>
                        <li><a title="Position Code" href="/position_code"><i
                                    class="bx bx-right-arrow-alt"></i>Position Code</a></li>
                        <li><a title="Department Vertical" href="/admin/department_vertical"><i
                                    class="bx bx-right-arrow-alt"></i>Department Vertical</a></li>
                        <li><a title="Department Vertical" href="/admin/minimum_wage"><i
                                    class="bx bx-right-arrow-alt"></i>Minimum Wage</a></li>
                        <li>
                            <a title="Eligibility" class="has-arrow" href="javascript:;">
                                <i class="bx bx-right-arrow-alt"></i>Eligibility </a>
                            <ul>
                                <li><a title="Lodging & Other" href="/admin/lodging"><i
                                            class="bx bx-right-arrow-alt"></i>Lodging & Other</a>
                                </li>
                                <li><a title="Travel" href="/admin/travel"><i
                                            class="bx bx-right-arrow-alt"></i>Travel</a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a title="Eligibility" class="has-arrow" href="javascript:;">
                                <i class="bx bx-right-arrow-alt"></i>Region & Zone </a>
                            <ul>
                                <li><a title="Lodging & Other" href="/admin/region"><i
                                            class="bx bx-right-arrow-alt"></i>Region & Zone Master</a>
                                </li>
                                <li><a title="Travel" href="/admin/hq_wise_region"><i
                                            class="bx bx-right-arrow-alt"></i> HQ and Vertical Wise</a>
                                </li>
                            </ul>
                        </li>
                        <li><a title="Communication Control" href="/admin/communication_control"><i
                                    class="bx bx-right-arrow-alt"></i>Communication Control</a></li>
                        <li><a title="Communication Control" href="{{route('core_api.index')}}"><i
                                    class="bx bx-right-arrow-alt"></i>Core API</a></li>
                        <li>
                            <a title="Eligibility" class="has-arrow" href="javascript:;">
                                <i class="bx bx-right-arrow-alt"></i>Core Mapping</a>
                            <ul>
                                <li><a title="Department" href="{{route('core_department_map')}}"><i
                                            class="bx bx-right-arrow-alt"></i>Department Mapping</a>
                                </li>
                                <li><a title="Designation" href="{{route('core_designation_map')}}"><i
                                            class="bx bx-right-arrow-alt"></i> Designation Mapping</a>
                                </li>
                                <li><a title="Designation" href="{{route('core_job_map')}}"><i
                                            class="bx bx-right-arrow-alt"></i> JobPost Mapping</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <li>
                    <a title="MRF" href="javascript:;" class="has-arrow">
                        <div class="parent-icon"><i class="bx bx-category" style="color: maroon"></i>
                        </div>
                        <div class="menu-title">MRF</div>
                    </a>
                    <ul>
                        <li><a title="New MRF" href="/admin/mrf"><i class="bx bx-right-arrow-alt"></i>New
                                MRF</a>
                        </li>
                        <li><a title="Active MRF" href="/admin/active_mrf"><i
                                    class="bx bx-right-arrow-alt"></i>Active MRF</a></li>
                        <li><a title="Closed MRF" href="/admin/closedmrf"><i
                                    class="bx bx-right-arrow-alt"></i>Closed MRF</a></li>
                        <li><a title="Manual Entry" href="/recruiter_mrf_entry"><i
                                    class="bx bx-right-arrow-alt"></i>Manual Entry</a>
                        </li>

                    </ul>
                </li>
            @endif


            @if (Auth::user()->role == 'H')
                <li class="{{ request()->is('hod/dashboard') ? 'mm-active' : '' }}">
                    <a title="Dashboard" href="/hod/dashboard">
                        <div class="parent-icon"><i class="fas fa-laptop-house text-primary"></i>
                        </div>
                        <div class="menu-title">Dashboard</div>
                    </a>
                </li>
                @if (has_permission($resultArray, 'My Team Details'))
                    <li>
                        <a title="My Team Details" href="/hod/myteam">
                            <div class="parent-icon"><i class="fas fa-users text-info"></i>
                            </div>
                            <div class="menu-title">My Team Details</div>
                        </a>
                    </li>
                @endif
                @if (has_permission($resultArray, 'New MRF Creation') || has_permission($resultArray, 'Campus MRF Creation') || has_permission($resultArray, 'SIP MRF Creation'))
                    <li>
                        <a title="MRF" href="/hod/manpowerrequisition">
                            <div class="parent-icon"><i class="fas fa-feather-alt text-success"></i>
                            </div>
                            <div class="menu-title">MRF</div>
                        </a>
                    </li>
                @endif
                @if (has_permission($resultArray, 'Interview Schedule'))
                    <li>
                        <a title="Interview Schedule" href="/hod/interviewschedule">
                            <div class="parent-icon"><i class="far fa-calendar-alt text-warning"></i>
                            </div>
                            <div class="menu-title">Interview Schedule</div>
                        </a>
                    </li>
                @endif
                @if (has_permission($resultArray, 'Pending Screening'))
                    <li>
                        <a title="Interview Schedule" href="/hod/pending_screening">
                            <div class="parent-icon"><i class="fa  fa-filter text-danger"></i>
                            </div>
                            <div class="menu-title">Pending Screening</div>
                        </a>
                    </li>
                @endif
                @if (has_permission($resultArray, 'MRF Approval'))
                    <li>
                        <a title="MRF Approval" href="mrf_approval_list">
                            <div class="parent-icon"><i class="fa  fa-list text-success"></i>
                            </div>
                            <div class="menu-title">Pending MRF Approval</div>
                        </a>
                    </li>
                @endif

                <li>
                    <a title="Resume Databank" href="resume_databank">
                        <div class="parent-icon"><i class="fa  fa-folder text-success"></i>
                        </div>
                        <div class="menu-title">Resume Databank</div>
                    </a>
                </li>
                {{--<li>
                    <a title="Resume Databank" href="shared_profile">
                        <div class="parent-icon"><i class="fa  fa-users"></i>
                        </div>
                        <div class="menu-title">Shared Profile</div>
                    </a>
                </li>--}}
            @endif



            @if (Auth::user()->role == 'R')
                <li class="{{ request()->is('recruiter/dashboard') ? 'mm-active' : '' }}">
                    <a title="Dashboard" href="/recruiter/dashboard">
                        <div class="parent-icon"><i class="fas fa-laptop-house text-primary"></i>
                        </div>
                        <div class="menu-title">Dashboard</div>
                    </a>
                </li>
                @if (has_permission($resultArray, 'MRF Allocated') || has_permission($resultArray, 'MRF Manual Entry'))
                    <li
                    <li>
                        <a title="MRF" href="javascript:;" class="has-arrow">
                            <div class="parent-icon"><i class="bx bx-category" style="color: #198754"></i>
                            </div>
                            <div class="menu-title">MRF</div>
                        </a>
                        <ul>
                            @if (has_permission($resultArray, 'MRF Allocated'))
                                <li><a title="MRF Allocated" href="/recruiter/mrf_allocated"><i
                                            class="bx bx-right-arrow-alt"></i>MRF
                                        Allocated</a></li>
                            @endif
                            @if (has_permission($resultArray, 'MRF Manual Entry'))
                                <li><a title="MRF Manual Entry" href="/recruiter_mrf_entry"><i
                                            class="bx bx-right-arrow-alt"></i>Manual
                                        Entry</a></li>
                            @endif
                        </ul>
                    </li>
                @endif
            @endif

            @if (Auth::user()->role == 'A' || Auth::user()->role == 'R')
                @if (has_permission($resultArray, 'Job & Response') ||
                        has_permission($resultArray, 'Job Applications' || Auth::user()->role == 'A'))
                    <li>
                        <a title="Job Application Management" href="javascript:;" class="has-arrow">
                            <div class="parent-icon"><i class="lni lni-write text-warning"></i>
                            </div>
                            <div class="menu-title">Job Application Management</div>
                        </a>
                        <ul>
                            @if (has_permission($resultArray, 'Job & Response' || Auth::user()->role == 'A'))
                                <li><a title="Job & Response" href="/job_response"><i
                                            class="bx bx-right-arrow-alt"></i>Job & Response</a>
                                </li>
                            @endif
                            @if (has_permission($resultArray, 'Job Applications' || Auth::user()->role == 'A'))
                                <li><a title="Job Applications" href="/job_applications"><i
                                            class="bx bx-right-arrow-alt"></i>Job
                                        Application
                                        (Resume Databank)</a>
                                </li>
                                <li><a title="Job Applications" href="/job_applications_not_viewed"><i
                                            class="bx bx-right-arrow-alt"></i>Candidates Not Viewed</a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif
                @if (has_permission($resultArray, 'Screening Tracker') ||
                        has_permission($resultArray, 'Interview Tracker' || Auth::user()->role == 'A'))
                    <li>
                        <a title="Recruitment Tracker" href="javascript:;" class="has-arrow">
                            <div class="parent-icon"><i class="lni lni-timer  text-info"></i>
                            </div>
                            <div class="menu-title">Recruitment Tracker</div>
                        </a>
                        <ul>
                            @if (has_permission($resultArray, 'Screening Tracker' || Auth::user()->role == 'A'))
                                <li><a title="Screening Tracker" href="/TechnicalScreening"><i
                                            class="bx bx-right-arrow-alt"></i>Screening
                                        Tracker</a></li>
                            @endif
                            @if (has_permission($resultArray, 'Interview Tracker' || Auth::user()->role == 'A'))
                                <li><a title="Interview Tracker" href="/interview_tracker"><i
                                            class="bx bx-right-arrow-alt"></i>Interview
                                        Tracker</a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif
                @if (has_permission($resultArray, 'Job Offers') ||
                        has_permission($resultArray, 'Candidates for Joining' || Auth::user()->role == 'A'))
                    <li>
                        <a title="Onboarding" href="javascript:;" class="has-arrow">
                            <div class="parent-icon"><i class="fadeIn animated bx bx-walk  text-success"></i>
                            </div>
                            <div class="menu-title">Onboarding</div>
                        </a>
                        <ul>
                            @if (has_permission($resultArray, 'Job Offers'))
                                <li><a title="Job Offers" href="/offer_letter"><i
                                            class="bx bx-right-arrow-alt"></i>Job Offers</a>
                                </li>
                            @endif
                            @if (has_permission($resultArray, 'Candidates for Joining'))
                                <li><a title="Candidates for Joining" href="/candidate_joining"><i
                                            class="bx bx-right-arrow-alt"></i>Candidates
                                        for Joining</a></li>
                            @endif
                        </ul>
                    </li>
                @endif
                @if (has_permission($resultArray, 'Campus MRF') ||
                        has_permission($resultArray, 'Campus Application') ||
                        has_permission($resultArray, 'Campus Screening Tracker') ||
                        has_permission($resultArray, 'Campus Hiring Tracker') ||
                        has_permission($resultArray, 'Campus Hiring Costing' || Auth::user()->role == 'A'))
                    <li>
                        <a title="Campus Hirings" href="javascript:;" class="has-arrow">
                            <div class="parent-icon"><i class="lni lni-ux  text-primary"></i>
                            </div>
                            <div class="menu-title">Campus Hirings</div>
                        </a>
                        <ul>
                            @if (has_permission($resultArray, 'Campus MRF' || Auth::user()->role == 'A'))
                                <li><a title="Campus MRF" href="/campus_mrf_allocated"><i
                                            class="bx bx-right-arrow-alt"></i>Campus
                                        MRF</a>
                                </li>
                            @endif
                            @if (has_permission($resultArray, 'Campus Application' || Auth::user()->role == 'A'))
                                <li><a title="Campus Application" href="/campus_applications"><i
                                            class="bx bx-right-arrow-alt"></i>Campus
                                        Application</a></li>
                            @endif
                            @if (has_permission($resultArray, 'Campus Screening Tracker' || Auth::user()->role == 'A'))
                                <li><a title="Campus Screening Tracker" href="/campus_screening_tracker"><i
                                            class="bx bx-right-arrow-alt"></i>Screening
                                        Tracker</a></li>
                            @endif
                            @if (has_permission($resultArray, 'Campus Hiring Tracker' || Auth::user()->role == 'A'))
                                <li><a title="Campus Hiring Tracker" href="/campus_hiring_tracker"><i
                                            class="bx bx-right-arrow-alt"></i>Hiring
                                        Tracker</a></li>
                            @endif
                            @if (has_permission($resultArray, 'Campus Hiring Costing' || Auth::user()->role == 'A'))
                                <li><a title="Campus Hiring Costing" href="/campus_hiring_costing"><i
                                            class="bx bx-right-arrow-alt"></i>Hiring
                                        Costing</a></li>
                            @endif
                        </ul>
                    </li>
                @endif
                @if (has_permission($resultArray, 'Trainee MRF') ||
                        has_permission($resultArray, 'Trainee Application') ||
                        has_permission($resultArray, 'Trainee Tracker') ||
                        has_permission($resultArray, 'Active Trainee') ||
                        has_permission($resultArray, 'Old Trainee' || Auth::user()->role == 'A'))
                    <li>
                        <a title="Trainee" href="javascript:;" class="has-arrow">
                            <div class="parent-icon"><i class="fadeIn animated bx bx-atom  text-danger"></i>
                            </div>
                            <div class="menu-title">Trainee</div>
                        </a>
                        <ul>
                            @if (has_permission($resultArray, 'Trainee MRF' || Auth::user()->role == 'A'))
                                <li><a title="Trainee MRF" href="/trainee_mrf_allocated"><i
                                            class="bx bx-right-arrow-alt"></i>Trainee
                                        MRF</a>
                                </li>
                            @endif
                            @if (has_permission($resultArray, 'Trainee Application' || Auth::user()->role == 'A'))
                                <li><a title="Trainee Application" href="/trainee_applications"><i
                                            class="bx bx-right-arrow-alt"></i>Trainee
                                        Application</a></li>
                            @endif
                            @if (has_permission($resultArray, 'Trainee Tracker' || Auth::user()->role == 'A'))
                                <li><a title="Trainee Tracker" href="/trainee_screening_tracker"><i
                                            class="bx bx-right-arrow-alt"></i>Interview Tracker - SIP/Trainee</a></li>
                            @endif
                            @if (has_permission($resultArray, 'Active Trainee' || Auth::user()->role == 'A'))
                                <li><a title="Active Trainee" href="/active_trainee"><i
                                            class="bx bx-right-arrow-alt"></i>Active
                                        Trainee</a>
                                </li>
                            @endif
                            @if (has_permission($resultArray, 'Old Trainee' || Auth::user()->role == 'A'))
                                <li><a title="Old Trainee" href="/old_trainee"><i
                                            class="bx bx-right-arrow-alt"></i>Old Trainee</a>
                                </li>
                            @endif
                        </ul>
                    </li>
                    <li>
                        <a title="Sent Mails" href="/admin/sentemails" target="_blank">
                            <div class="parent-icon"><i class='fadeIn animated bx bx-mail-send text-primary'></i>
                            </div>
                            <div class="menu-title">Sent Mails</div>
                        </a>
                    </li>
                @endif
                @if (has_permission($resultArray, 'Test Module') || Auth::user()->role == 'A')
                    <li>
                        <a href="javascript:;" class="has-arrow">
                            <div class="parent-icon"><i class="lni lni-target-customer"
                                                        style="color: #6610f2"></i>
                            </div>
                            <div class="menu-title">Online Test</div>
                        </a>
                        <ul>
                            <li><a title="Subject Master" href="{{ route('test_candidate.index') }}"><i
                                        class="bx bx-right-arrow-alt"></i>Candidates</a></li>
                            <li><a title="Subject Master" href="{{ route('exam_master.index') }}"><i
                                        class="bx bx-right-arrow-alt"></i>Exam Master</a></li>

                            <li><a title="Subject Master" href="{{ route('subject_master.index') }}"><i
                                        class="bx bx-right-arrow-alt"></i>Subject Master</a></li>
                            <li><a title="Question Bank" href="{{ route('question_bank.index') }}"><i
                                        class="bx bx-right-arrow-alt"></i>Question Bank</a></li>
                            <li><a title="Question Bank" href="{{ route('candidate_assessment_report') }}"><i
                                        class="bx bx-right-arrow-alt"></i>Assessment Report</a></li>
                        </ul>
                    </li>
                @endif
                <li>
                    <a title="Reports" href="javascript:;" class="has-arrow">
                        <div class="parent-icon"><i class='lni lni-slack' style="color: crimson"></i>
                        </div>
                        <div class="menu-title">Reports</div>
                    </a>
                    <ul>
                        <li><a title="FIRO B Test" href="/Firob_Reports"><i
                                    class="bx bx-right-arrow-alt"></i>FIRO
                                B Test</a></li>
                        <li><a href="/reports_download"><i class="bx bx-right-arrow-alt"></i>Report's in
                                Excel</a>
                        </li>
                        <li><a href="/mrf_report"><i class="bx bx-right-arrow-alt"></i>MRF Report</a>
                        </li>
                        {{-- <li><a href="/mrf_tat"><i class="bx bx-right-arrow-alt"></i>MRF TAT</a>
                         </li>--}}
                        @if(Auth::user()->role == 'A')
                            <li>
                                <a href="/recruiter_report"> <i class="bx bx-right-arrow-alt"></i>
                                    Recruiter Wise Report
                                </a>
                            </li>

                            <li>
                                <a href="/manual_entry_report"> <i class="bx bx-right-arrow-alt"></i>
                                    Mannual Entry Report
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>

            @endif

            @if (Auth::user()->role == 'A')
                <li><a title="Users" href="/admin/userlist">
                        <div class="parent-icon"><i class='bx bx-user text-info'></i>
                        </div>
                        <div class="menu-title">Users</div>
                    </a>
                </li>
                <li><a title="Users" href="{{ route('user_department_list') }}">
                        <div class="parent-icon"><i class='bx bx-link text-info'></i>
                        </div>
                        <div class="menu-title">Map User</div>
                    </a>
                </li>
                <li>
                    <a title="Logs" href="/admin/userlogs">
                        <div class="parent-icon"><i class='bx bx-news text-success'></i>
                        </div>
                        <div class="menu-title">Logs</div>
                    </a>
                </li>

            @endif
        </ul>

    </div>

    <header>
        <div class="topbar d-flex align-items-center">
            <nav class="navbar navbar-expand">
                <div class="mobile-toggle-menu"><i class='bx bx-menu'></i>

                </div>
                <div class="search-bar flex-grow-1">
                    <div class="position-relative search-bar-box">
                        <h4 style="color:#0e8452;" class="logo-text">{{ $CompanyQry->company_name }}
                            ({{ $CountryQry->country_name }})</h4>
                    </div>
                </div>
                <div class="top-menu ms-auto">
                    <ul class="navbar-nav align-items-center">

                        <li class="nav-item dropdown-large">
                            <a id="sidebarsetting" class="nav-link dropdown-toggle dropdown-toggle-nocaret"
                               href="#" role="button"> <i class='bx bx-shape-polygon'></i>
                            </a>
                        </li>
                        <li class="nav-item dropdown dropdown-large">
                            <a class="nav-link dropdown-toggle dropdown-toggle-nocaret position-relative"
                               href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                @if ($NotificationCount > 0)
                                    <span class="alert-count">
                            {{ $NotificationCount }}
                        </span>
                                @endif
                                <i class='bx bx-bell'></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a href="javascript:;">
                                    <div class="msg-header">
                                        <p class="msg-header-title">Notifications</p>
                                        <p class="msg-header-clear ms-auto" onclick="markAllRead()">Marks all as
                                            read</p>
                                    </div>
                                </a>
                                <div class="header-notifications-list">

                                    @foreach ($Notification as $item)
                                        <a class="dropdown-item" href="javascript:;"
                                           onclick="readNotification({{ $item->id }})">
                                            <div class="d-flex align-items-center">
                                                <div class="notify bg-light-primary">
                                                    @if ($item->title == 'MRF Allocated')
                                                        <i class="bx bx-file  text-primary"></i>
                                                    @elseif($item->title == 'Offer Letter')
                                                        <i class="bx bx-user text-danger"></i>
                                                    @elseif($item->title == 'Job Post Create')
                                                        <i class="bx bx-send text-success"></i>
                                                    @endif

                                                </div>
                                                <div class="flex-grow-1"
                                                     style="box-sizing: content-box; width: 100%; white-space: normal;">
                                                    <h6 class="msg-name">{{ $item->title }}
                                                        <span
                                                            class="msg-time float-end">{{ \Carbon\Carbon::parse($item->created_at)->diffForHumans() }}</span>
                                                    </h6>
                                                    <p class="msg-info">{{ $item->description }}
                                                        {{-- <span class="user-online float-end mt-3"></span> --}}
                                                    </p>
                                                </div>
                                            </div>
                                        </a>
                                    @endforeach
                                </div>

                            </div>
                        </li>
                        <li class="nav-item dropdown dropdown-large d-none">
                            <div class="dropdown-menu dropdown-menu-end">
                                <div class="header-message-list">
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="user-box dropdown">
                    <a class="d-flex align-items-center nav-link dropdown-toggle dropdown-toggle-nocaret"
                       href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="{{ URL::to('/') }}/assets/images/avatars/avatar-2.png" class="user-img"
                             alt="user avatar">
                        <div class="user-info ps-3">
                            <p class="user-name mb-0"> {{ Auth::user()->name }}</p>
                        </div>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="/change-password"><i
                                    class="bx bx-user"></i><span>Change Password</span></a></li>
                        <li>
                            <div class="dropdown-divider mb-0"></div>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('logout') }}"
                               onclick="event.preventDefault();
                              document.getElementById('logout-form').submit();">
                                {{ __('Logout') }}
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                  class="d-none">
                                @csrf
                            </form>

                        </li>
                    </ul>
                </div>
            </nav>
        </div>
    </header>

    <div class="page-wrapper">

        @yield('PageContent')

        <div class="modal" id="loader" data-bs-backdrop="static" data-bs-keyboard="false"
             style="background-color: rgba(0,0,0,.0001)">
            <div class="modal-dialog modal-dialog-centered">
                <div class="spinner-border text-danger" style="width: 5rem; height: 5rem;" role="status"> <span
                        class="visually-hidden">Loading...</span>
                </div>
            </div>
        </div>
    </div>


    <div class="overlay toggle-icon"></div>

    <a href="javaScript:;" class="back-to-top"><i class='bx bxs-up-arrow-alt'></i></a>

    <footer class="page-footer">
        <p class="mb-0">Developed and Managed By: IT Department, VNR Seeds Pvt. Ltd.</p>
    </footer>
</div>

<div class="switcher-wrapper">
    <div class="switcher-body">
        <div class="d-flex align-items-center">
            <h5 class="mb-0 text-uppercase">Theme Customizer</h5>
            <button type="button" class="btn-close ms-auto close-switcher" aria-label="Close"></button>
        </div>
        <hr/>
        <h6 class="mb-0">Theme Styles</h6>
        <hr/>
        <div class="d-flex align-items-center justify-content-between">
            <div class="form-check">
                <input class="form-check-input" type="radio" name="flexRadioDefault" id="lightmode"
                    {{ session('ThemeStyle') == 'light-theme' ? 'checked' : '' }}>
                <label class="form-check-label" for="lightmode">Light</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="flexRadioDefault" id="darkmode"
                    {{ session('ThemeStyle') == 'dark-theme' ? 'checked' : '' }}>
                <label class="form-check-label" for="darkmode">Dark</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="flexRadioDefault" id="semidark"
                    {{ session('ThemeStyle') == 'semi-dark' ? 'checked' : '' }}>
                <label class="form-check-label" for="semidark">Semi Dark</label>
            </div>
        </div>
        <hr/>
        <div class="form-check">
            <input class="form-check-input" type="radio" id="minimaltheme" name="flexRadioDefault"
                {{ session('ThemeStyle') == 'minimal-theme' ? 'checked' : '' }}>
            <label class="form-check-label" for="minimaltheme">Minimal Theme</label>
        </div>
        <hr/>
        <h6 class="mb-0">Sidebar Colors</h6>
        <hr/>
        <div class="header-colors-indigators">
            <div class="row row-cols-auto g-3">
                <div class="col">
                    <div class="indigator sidebarcolor1" id="sidebarcolor1"></div>
                </div>
                <div class="col">
                    <div class="indigator sidebarcolor2" id="sidebarcolor2"></div>
                </div>
                <div class="col">
                    <div class="indigator sidebarcolor3" id="sidebarcolor3"></div>
                </div>
                <div class="col">
                    <div class="indigator sidebarcolor4" id="sidebarcolor4"></div>
                </div>
                <div class="col">
                    <div class="indigator sidebarcolor5" id="sidebarcolor5"></div>
                </div>
                <div class="col">
                    <div class="indigator sidebarcolor6" id="sidebarcolor6"></div>
                </div>
                <div class="col">
                    <div class="indigator sidebarcolor7" id="sidebarcolor7"></div>
                </div>
                <div class="col">
                    <div class="indigator sidebarcolor8" id="sidebarcolor8"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal" id="eventModal" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius:10px;">
            <div class="modal-header">
                <h6 class="modal-title">Add New Event</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{route('save_event')}}" method="POST" id="eventForm">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="start_time">Start Date/Time :</label>
                            <input type="datetime-local" name="start_time" id="start_time" class="form-control"
                                   min="{{now()->format('Y-m-d\TH:i')}}">
                            <span class="text-danger error-text start_time_error"></span>
                        </div>
                        <div class="col-md-6">
                            <label for="end_time">End Date/Time :</label>
                            <input type="datetime-local" name="end_time" id="end_time" class="form-control"
                                   min="{{now()->format('Y-m-d\TH:i')}}">
                            <span class="text-danger error-text end_time_error"></span>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <label for="event_title">Title :</label>
                            <input type="text" name="event_title" id="event_title" class="form-control">
                            <span class="text-danger error-text event_title_error"></span>
                        </div>

                    </div>
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <label for="event_description">Description :</label>
                            <textarea name="event_description" id="event_description" class="form-control"></textarea>
                            <span class="text-danger error-text event_description_error"></span>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!--end switcher-->
<!-- Bootstrap JS -->
<script src="{{ URL::to('/') }}/assets/js/bootstrap.bundle.min.js"></script>
<!--plugins-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"
        integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous">
</script>
<script src="{{ URL::to('/') }}/assets/plugins/simplebar/js/simplebar.min.js"></script>
<script src="{{ URL::to('/') }}/assets/plugins/metismenu/js/metisMenu.min.js"></script>
<script src="{{ URL::to('/') }}/assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js"></script>
<script src="{{ URL::to('/') }}/assets/plugins/select2/js/select2.min.js"></script>
<script src="{{ URL::to('/') }}/assets/plugins/datatable/js/jquery.dataTables.min.js"></script>
<script src="{{ URL::to('/') }}/assets/plugins/datatable/js/dataTables.bootstrap5.min.js"></script>
<script src="{{ URL::to('/') }}/assets/js/sweetalert2.min.js"></script>
<script src="{{ URL::to('/') }}/assets/js/toastr.min.js"></script>
<script src="{{ URL::to('/') }}/assets/js/BsMultiSelect.min.js"></script>

<!--app JS-->
<script src="{{ URL::to('/') }}/assets/js/app.js"></script>
<script src="{{ URL::to('/') }}/assets/js/canvasjs.min.js"></script>

@yield('script_section')

<script>
    $(document).ready(function () {
        toastr.options.timeOut = 10000;
        @if (Session::has('error'))
        toastr.error('{{ Session::get('error') }}');
        @endif
        @if (Session::has('success'))
        toastr.success('{{ Session::get('success') }}');
        @endif
        $('.multiple-select').select2({
            theme: 'bootstrap4',
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' :
                'style',
            placeholder: $(this).data('placeholder'),

            allowClear: Boolean($(this).data('allow-clear')),
        });

        $('.single-select').select2({
            theme: 'bootstrap4',
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' :
                'style',
            placeholder: $(this).data('placeholder'),
            allowClear: Boolean($(this).data('allow-clear')),
        });

        $(document).on('click', '#sidebarsetting', function () {
            $(".switcher-wrapper").toggleClass("switcher-toggled");
// $(".wrapper").removeClass("toggled");
        });

        /* $( ".sidebar-wrapper" ).hover(function() {
        $(".wrapper").toggleClass("toggled");
        }); */
//=====================Set Light Theme=====================
        $(document).on('click', '#lightmode', function () {
            $.ajax({
                url: "{{ route('setTheme') }}",
                method: 'POST',
                data: {
                    ThemeStyle: 'lightmode'
                },
                dataType: 'json',
                success: function (data) {
                    if (data.status == 200) {

                        location.reload();
                    } else {
                        alert('failed');
                    }
                }
            });
        });
//======================Set Dark Theme  ========================

        $(document).on('click', '#darkmode', function () {
            $.ajax({
                url: "{{ route('setTheme') }}",
                method: 'POST',
                data: {
                    ThemeStyle: 'darkmode'
                },
                dataType: 'json',
                success: function (data) {
                    if (data.status == 200) {

                        location.reload();
                    } else {
                        alert('failed');
                    }
                }
            });
        });

//======================Set Semi Dark Theme  ========================

        $(document).on('click', '#semidark', function () {
            $.ajax({
                url: "{{ route('setTheme') }}",
                method: 'POST',
                data: {
                    ThemeStyle: 'semidark'
                },
                dataType: 'json',
                success: function (data) {
                    if (data.status == 200) {

                        location.reload();
                    } else {
                        alert('failed');
                    }
                }
            });
        });

// ======================Set minimaltheme Theme  ========================

        $(document).on('click', '#minimaltheme', function () {
            $.ajax({
                url: "{{ route('setTheme') }}",
                method: 'POST',
                data: {
                    ThemeStyle: 'minimaltheme'
                },
                dataType: 'json',
                success: function (data) {
                    if (data.status == 200) {

                        location.reload();
                    } else {
                        alert('failed');
                    }
                }
            });
        });
// ======================Set Sidebar1 Theme  ========================

        $(document).on('click', '#sidebarcolor1', function () {
            $.ajax({
                url: "{{ route('setTheme') }}",
                method: 'POST',
                data: {
                    ThemeStyle: 'sidebarcolor1'
                },
                dataType: 'json',
                success: function (data) {
                    if (data.status == 200) {

                        location.reload();
                    } else {
                        alert('failed');
                    }
                }
            });
        });

        $(document).on('click', '#sidebarcolor2', function () {
            $.ajax({
                url: "{{ route('setTheme') }}",
                method: 'POST',
                data: {
                    ThemeStyle: 'sidebarcolor2'
                },
                dataType: 'json',
                success: function (data) {
                    if (data.status == 200) {

                        location.reload();
                    } else {
                        alert('failed');
                    }
                }
            });
        });
        $(document).on('click', '#sidebarcolor3', function () {
            $.ajax({
                url: "{{ route('setTheme') }}",
                method: 'POST',
                data: {
                    ThemeStyle: 'sidebarcolor3'
                },
                dataType: 'json',
                success: function (data) {
                    if (data.status == 200) {

                        location.reload();
                    } else {
                        alert('failed');
                    }
                }
            });
        });
        $(document).on('click', '#sidebarcolor4', function () {
            $.ajax({
                url: "{{ route('setTheme') }}",
                method: 'POST',
                data: {
                    ThemeStyle: 'sidebarcolor4'
                },
                dataType: 'json',
                success: function (data) {
                    if (data.status == 200) {

                        location.reload();
                    } else {
                        alert('failed');
                    }
                }
            });
        });
        $(document).on('click', '#sidebarcolor5', function () {
            $.ajax({
                url: "{{ route('setTheme') }}",
                method: 'POST',
                data: {
                    ThemeStyle: 'sidebarcolor5'
                },
                dataType: 'json',
                success: function (data) {
                    if (data.status == 200) {

                        location.reload();
                    } else {
                        alert('failed');
                    }
                }
            });
        });
        $(document).on('click', '#sidebarcolor6', function () {
            $.ajax({
                url: "{{ route('setTheme') }}",
                method: 'POST',
                data: {
                    ThemeStyle: 'sidebarcolor6'
                },
                dataType: 'json',
                success: function (data) {
                    if (data.status == 200) {

                        location.reload();
                    } else {
                        alert('failed');
                    }
                }
            });
        });
        $(document).on('click', '#sidebarcolor7', function () {
            $.ajax({
                url: "{{ route('setTheme') }}",
                method: 'POST',
                data: {
                    ThemeStyle: 'sidebarcolor7'
                },
                dataType: 'json',
                success: function (data) {
                    if (data.status == 200) {

                        location.reload();
                    } else {
                        alert('failed');
                    }
                }
            });
        });
        $(document).on('click', '#sidebarcolor8', function () {
            $.ajax({
                url: "{{ route('setTheme') }}",
                method: 'POST',
                data: {
                    ThemeStyle: 'sidebarcolor8'
                },
                dataType: 'json',
                success: function (data) {
                    if (data.status == 200) {

                        location.reload();
                    } else {
                        alert('failed');
                    }
                }
            });
        });

        $('#eventForm').on('submit', function (e) {
            e.preventDefault();
            let form = this;
            $.ajax({
                url: $(form).attr('action'),
                method: $(form).attr('method'),
                data: new FormData(form),
                processData: false,
                dataType: 'json',
                contentType: false,
                beforeSend: function () {
                    $(form).find('span.error-text').text('');
                },
                success: function (data) {

                    if (data.status == 400) {
                        $.each(data.error, function (prefix, val) {
                            $(form).find('span.' + prefix + '_error').text(val[0]);
                        });
                    } else {
                        $(form)[0].reset();
                        $('#eventModal').modal('hide');
                        toastr.success(data.message);
                        setTimeout(function () {
                            location.reload();
                        }, 1000);
                    }
                }
            });
        });
    });
    toastr.options.preventDuplicates = true;
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    function readNotification(id) {
        var id = id;
        $.ajax({
            url: "{{ route('notificationMarkRead') }}?id=" + id,
            method: 'POST',

            dataType: 'json',
            success: function (data) {
                if (data.status == 200) {
                    location.reload();
                    // console.log($('.alert-count').text());
                } else {
                    alert('failed');
                }
            }
        });
    }

    function markAllRead() {
        $.ajax({
            url: "{{ route('markAllRead') }}",
            method: 'POST',
            dataType: 'json',
            success: function (data) {
                if (data.status == 200) {
                    location.reload();
                } else {
                    alert('failed');
                }
            }
        });
    }

</script>
@livewire('livewire-ui-spotlight')

</body>

</html>
