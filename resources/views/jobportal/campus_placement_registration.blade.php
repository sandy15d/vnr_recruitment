<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--favicon-->
    <link rel="icon" href="{{ URL::to('/') }}/assets/images/favicon-32x32.png" type="image/png" />
    <!--plugins-->
    <link href="{{ URL::to('/') }}/assets/plugins/simplebar/css/simplebar.css" rel="stylesheet" />
    <link href="{{ URL::to('/') }}/assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css" rel="stylesheet" />
    <link href="{{ URL::to('/') }}/assets/plugins/metismenu/css/metisMenu.min.css" rel="stylesheet" />
    <!-- loader-->
    <link href="{{ URL::to('/') }}/assets/css/pace.min.css" rel="stylesheet" />
    <script src="{{ URL::to('/') }}/assets/js/pace.min.js"></script>
    <!-- Bootstrap CSS -->
    <link href="{{ URL::to('/') }}/assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&amp;display=swap" rel="stylesheet">
    <link href="{{ URL::to('/') }}/assets/css/app.css" rel="stylesheet">
    <link href="{{ URL::to('/') }}/assets/css/icons.css" rel="stylesheet">
    <title>Campus Placement Registration</title>
</head>
@php

$jpid = $_REQUEST['job'];
$jpid = base64_decode($jpid);
$query = DB::table('jobpost')
        ->leftJoin('core_department', 'core_department.id', '=', 'jobpost.DepartmentId')
        ->Where('JPId', $jpid)
        ->select('jobpost.*','core_department.department_name')
        ->first();
$checkExpiry = CheckJobPostExpiry($jpid);

@endphp

<body class="bg-login">
    <!--wrapper-->
    <div class="wrapper">
        <div>
            <div class="container-fluid">
                <div class="row row-cols-1 row-cols-lg-6 row-cols-xl-2">
                    <div class="col mx-auto">
                        <div class="card">
                            <div class="card-body">
                                @if ($checkExpiry == 'notexpired')
                                    <div class="border p-4 rounded">
                                        <div class="text-center">
                                            <div class="mb-4 text-center">
                                                <img src="{{ URL::to('/') }}/assets/images/vnrlogo.png" height="80"
                                                    alt="" />
                                            </div>
                                            <h5 class="">Registration for Campus Placement:</h5>
                                            <h6>{{ $query->department_name }}</h6>
                                        </div>
                                        <div class="form-body">
                                            <table class="table table-bordered">
                                                <tr>
                                                    <th>Department</th>
                                                    <td>{{ getDepartment($query->DepartmentId) }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Designation</th>
                                                    <td>{{ $query->Title }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Required Qualification</th>
                                                    <td>
                                                        @php
                                                            $data = unserialize($query->ReqQualification);
                                                        @endphp
                                                        <ul style="margin-bottom: 0px;">
                                                            @foreach ($data as $item1)
                                                                <li>{{ getEducationCodeById($item1['e']) }}
                                                                    @if ($item1['s'] != 0)
                                                                        {{ ' - ' . getSpecializationbyId($item1['s']) }}
                                                                    @endif
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th colspan="2">Job Details</th>
                                                </tr>
                                                <tr>
                                                    <td colspan="2">
                                                        @php
                                                            echo $query->Description;
                                                        @endphp
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>Pay Package</th>
                                                    <td>{{ $query->PayPackage }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Location Work</th>
                                                    <td>
                                                        @php
                                                        $loc = DB::table('mrf_location_position')->where('MRFId',$query->MRFId)->get();
                                                    @endphp
                                                   
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
                                                    
                                                </tr>
                                                <tr>
                                                    <th>Last Date for Online Registration</th>
                                                    <td>{{ \Carbon\Carbon::createFromFormat('Y-m-d', $query->LastDate)->format('d-m-Y') }}
                                                    </td>
                                                </tr>
                                            </table>



                                            <div class="col-12">
                                                <div class="d-grid">
                                                    <button type="button" class="btn btn-primary"
                                                        onclick="jobapply({{ $query->JPId }})"><i
                                                            class="bx bxs-user"></i>Register
                                                        Now</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                <div class="border p-4 rounded">
                                    <div class="text-center">
                                        <div class="mb-4 text-center">
                                            <img src="{{ URL::to('/') }}/assets/images/vnrlogo.png" height="80"
                                                alt="" />
                                        </div>
                                        <h5 class="text-danger">“The last date of registration has been expired. Contact your Placement Cell for more details.”</h5>

                                    </div>
                                </div>
                                @endif

                            </div>
                        </div>
                    </div>
                </div>
                <!--end row-->
            </div>
        </div>
    </div>
    <script>
        function jobapply(JPId) {
            console.log('clicked' + JPId);
            var JPId = btoa(JPId);
            window.open("{{ route('campus_apply_form') }}?jpid=" + JPId, '_blank')
        }
    </script>
    <script src="{{ URL::to('/') }}/assets/js/bootstrap.bundle.min.js"></script>
    <!--plugins-->
    <script src="{{ URL::to('/') }}/assets/js/jquery.min.js"></script>
    <script src="{{ URL::to('/') }}/assets/plugins/simplebar/js/simplebar.min.js"></script>
    <script src="{{ URL::to('/') }}/assets/plugins/metismenu/js/metisMenu.min.js"></script>
    <script src="{{ URL::to('/') }}/assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js"></script>
    <!--Password show & hide js -->

    <!--app JS-->
    <script src="{{ URL::to('/') }}/assets/js/app.js"></script>
</body>

</html>
