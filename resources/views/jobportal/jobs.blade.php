@php

    //$queries = DB::enableQueryLog();
    $regular_job = DB::table('jobpost')
        ->Join('manpowerrequisition', 'manpowerrequisition.MRFId', '=', 'jobpost.MRFId')
       // ->where('manpowerrequisition.CompanyId', 1)
        ->Where('jobpost.Status', 'Open')
        ->Where('jobpost.PostingView', 'Show')
        ->Where('jobpost.JobPostType', 'Regular')
        ->orderBy('JPId', 'desc')
        ->get();

    $sql = DB::table('jobpost')
        ->Join('manpowerrequisition', 'manpowerrequisition.MRFId', '=', 'jobpost.MRFId')
        ->where('manpowerrequisition.CompanyId', 1)
        ->Where('jobpost.Status', 'Open')
        ->Where('jobpost.PostingView', 'Show')
        ->Where('jobpost.JobPostType', 'SIP')
        ->orderBy('JPId', 'desc')
        ->get();

@endphp
    <!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS -->
    <link href="{{ URL::to('/') }}/assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link href="{{ URL::to('/') }}/assets/css/app.css" rel="stylesheet">
    <title>Jobs at VNR</title>
</head>

<body>
<!--wrapper-->
<div class="wrapper">
    <header class="login-header shadow">
        <nav class="navbar navbar-expand-lg rounded fixed-top rounded-0 shadow-sm"
             style=" background-color: #f09a3e;">
            <div class="container-fluid">
                <a class="navbar-brand" href="javascript:void(0);" style="height: 30px;">
                </a>
            </div>
        </nav>
    </header>
    <div class="d-flex align-items-center justify-content-center my-5">
        <div class="container">
            <div class="row ">
                <div class="col mx-auto">
                    <div class="card mt-3">
                        <div class="card-body">
                            <div class="border p-4 rounded">
                                <div class="text-center">
                                    <h3 class=""><img
                                            src="https://www.vnrseeds.com/wp-content/uploads/2018/12/vnr-logo-69x90.png">
                                    </h3>
                                    <h4 class="font-weight-bold" style="color: #f09a3e">Engage, Train & Retain</h4>
                                </div>
                                <div class="login-separater text-center mb-4"> <span
                                        style="font-size: 14px; color: #00823b;">VNR at our most valuable assets are its people</span>
                                    <hr style="margin-top: -10px"/>
                                </div>
                                <div class="row mb-2 d-none">
                                    <div class="col-sm-12 text-center">
                                        <button id="bntSip" class="btn btn-warning btn-lg center-block"
                                                style="margin-right: 30px;"
                                                OnClick="btnSip_Click()">SIP/Internship
                                        </button>
                                        <button id="btnJob" class="btn btn-success btn-lg center-block"
                                                OnClick="btnJob_Click()">Job Opportunities
                                        </button>
                                    </div>
                                </div>


                                <div class="form-body" id="regular_job">
                                    <h5 style="font-size: 24px; color: #008000; font-weight: 700;letter-spacing: 0px;"
                                        class="font-weight-bold">Current Openings</h5>
                                    <div class="table-responsive text-wrap">
                                        <table class="table table-bordered" style="width: 100%" id="myTable">
                                            <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Job Code</th>
                                                <th>Job Title</th>
                                                <th>Department</th>
                                                <th>Location</th>
                                                <th>Apply</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @for ($i = 0; $i < count($regular_job); $i++)
                                                <tr data-bs-toggle="collapse"
                                                    data-bs-target="#detail{{ $regular_job[$i]->JPId }}"
                                                    data-parent="#myTable" class="accordion-toggle"
                                                    style="cursor: pointer">
                                                    <td>{{ $i + 1 }}</td>
                                                    <td>{{ $regular_job[$i]->JobCode }}</td>
                                                    <td>{{ $regular_job[$i]->Title }}</td>
                                                    <td>{{ getDepartment($regular_job[$i]->DepartmentId) }}
                                                    </td>

                                                    @php
                                                        $locations = DB::table('mrf_location_position')
                                                                       ->where('MRFId', $regular_job[$i]->MRFId)
                                                                       ->get()
                                                                       ->filter(function ($item) {
                                                                            return !empty($item->City) && $item->City != 0 && !is_null($item->City);
                                                                       });

                                                        $locationStrings = $locations->map(function ($item) {
                                                            $city = getDistrictName($item->City);
                                                            $state = getStateName($item->State);
                                                            return "{$city} ({$state})";
                                                        })->implode(', ');
                                                    @endphp
                                                    <td>
                                                        {{ $locationStrings }}
                                                    </td>

                                                    <td><a href="javascript:void(0);"
                                                           style="color: #0008ff">View Details</a></td>
                                                </tr>
                                                <tr id="detail{{ $regular_job[$i]->JPId }}"
                                                    class="collapse accordion-collapse">
                                                    <td colspan="7" class="hiddenRow">
                                                        <div>
                                                            <table
                                                                class="table table-bordered table-striped table-sm">
                                                                @php
                                                                    $res = DB::table('jobpost')
                                                                        ->Join('manpowerrequisition', 'manpowerrequisition.MRFId', '=', 'jobpost.MRFId')
                                                                        ->where('jobpost.Status', 'Open')
                                                                        ->where('jobpost.JPId', $regular_job[$i]->JPId)
                                                                        ->orderBy('JPId', 'desc')
                                                                        ->get();
                                                                @endphp
                                                                @foreach ($res as $item)
                                                                    <tr>
                                                                        <td colspan="2">{{ $item->Title }}
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td style="width:200px;">Job Code</td>
                                                                        <td>
                                                                            {{ $item->JobCode }}
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Job Category</td>
                                                                        <td> {{ getDepartment($item->DepartmentId) }}
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Job Description</td>
                                                                        <td style="max-width: 200px !important;"><?= $item->Description ?></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Desired Candidate Profile</td>
                                                                        <td>
                                                                            @php
                                                                                $data = unserialize($item->KeyPositionCriteria);
                                                                            @endphp
                                                                            <ul>
                                                                                @foreach ($data as $item1)
                                                                                    <li>
                                                                                        {{ $item1 }}
                                                                                    </li>
                                                                                @endforeach
                                                                            </ul>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Education Qualification</td>
                                                                        <td>
                                                                            @php
                                                                                $data = unserialize($item->EducationId);
                                                                            @endphp
                                                                            <ul>
                                                                                @foreach ($data as $item1)
                                                                                    <li>{{ getEducationById($item1['e']) }}
                                                                                        @if ($item1['s'] != 0)
                                                                                            {{ ' - ' . getSpecializationbyId($item1['s']) }}
                                                                                        @endif
                                                                                    </li>
                                                                                @endforeach
                                                                            </ul>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Work Experience</td>
                                                                        <td><?= $item->WorkExp ?></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Job Location</td>
                                                                        @php
                                                                            $loc = DB::table('mrf_location_position')->where('MRFId',$item->MRFId)->get();
                                                                        @endphp
                                                                        <td>
                                                                            @foreach ($loc as $item1)
                                                                                @if ($item1->City != '' || $item1->City != 0)
                                                                                    {{ getDistrictName($item1->City) . ' (' }}
                                                                                @endif
                                                                                @if ($item1->State != '')
                                                                                    {{ getStateName($item1->State) }}
                                                                                @endif
                                                                                @if ($item1->City != '' || $item1->City != 0)
                                                                                    {{ ')' }}
                                                                                @endif
                                                                            @endforeach
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Salary Package</td>
                                                                        <td>Best as per industry standards</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td colspan="2" class="text-center">
                                                                            <button type="button"
                                                                                    class="btn btn-sm btn-primary"
                                                                                    onclick="jobapply({{ $item->JPId }})">
                                                                                Apply
                                                                                Now
                                                                            </button>
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            </table>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endfor
                                            </tbody>
                                        </table>
                                    </div>

                                    <p style="font-size: 15px;">Thanks for checking out our job openings. if you
                                        don't see any opportunities, please submit your resume & we'll get back to
                                        you if there are any suitable openings that match your profile. <a
                                            href="{{ route('apply_form') }}" class="btn btn-link btn-sm"><b>Submit
                                                your
                                                resume</b></a></p>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--end row-->
        </div>
    </div>
    <footer class="bg-white shadow-sm border-top p-2 text-center fixed-bottom">
        <p class="mb-0">Copyright © VNR Seeds 2021. All right reserved.</p>
    </footer>
</div>
<!--end wrapper-->
<!-- Bootstrap JS -->
<script src="{{ URL::to('/') }}/assets/js/bootstrap.bundle.min.js"></script>
<!--plugins-->
<script src="{{ URL::to('/') }}/assets/js/jquery.min.js"></script>

</body>

</html>
<script>
    function jobapply(JPId) {
        var JPId = btoa(JPId);
        window.open("{{ route('job_apply_form') }}?jpid=" + JPId, '_blank');
    }

    function traineeapply(JPId) {
        var JPId = btoa(JPId);
        window.open("{{ route('trainee_apply_form') }}?jpid=" + JPId, '_blank');
    }

    function btnSip_Click() {
        $("#sip_internship").removeClass('d-none');
        $("#regular_job").addClass('d-none');
    }

    function btnJob_Click() {
        $("#sip_internship").addClass('d-none');
        $("#regular_job").removeClass('d-none');
    }
</script>
