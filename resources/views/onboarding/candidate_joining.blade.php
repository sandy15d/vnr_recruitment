@php

    $Year = date('Y');
    // Define common join structure
    $commonJoins = function () {
        return DB::table('offerletterbasic')
            ->join('jobapply', 'jobapply.JAId', '=', 'offerletterbasic.JAId')
            ->join('jobcandidates', 'jobcandidates.JCId', '=', 'jobapply.JCId')
            ->leftjoin('jobpost', 'jobpost.JPId', '=', 'jobapply.JPId')
            ->leftjoin('manpowerrequisition', 'manpowerrequisition.MRFId', '=', 'jobpost.MRFId');
    };

    // Define common query conditions
    $commonConditions = function ($query) use ($Year) {
        $query->where('jobcandidates.Nationality', session('Set_Country'))
            ->where('offerletterbasic.Answer', 'Accepted')
            ->where('Year', $Year)
            ->when(Auth::user()->role == 'R', function ($query) {
                $query->where(function ($innerQuery) {
                    $innerQuery->where('jobpost.CreatedBy', Auth::user()->id)
                        ->orWhere('manpowerrequisition.Allocated', Auth::user()->id);
                });
            });
    };

    $JoiningFormNotFilled = $commonJoins()->where($commonConditions)->where('FinalSubmit',0)->count();
    $PendingVerification = $commonJoins()->where($commonConditions)->leftjoin('candjoining','candjoining.JAId','jobapply.JAId')->where('Verification','Not Verified')->count();
    $DataProcessed = $commonJoins()->where($commonConditions)->leftjoin('candjoining','candjoining.JAId','jobapply.JAId')->where('ForwardToESS','Yes')->count();
    $NotJoin = $commonJoins()->where($commonConditions)->leftjoin('candjoining','candjoining.JAId','jobapply.JAId')->where('Joined','No')->count();
@endphp
@extends('layouts.master')
@section('title', 'Candidates for Joining')
@section('PageContent')
    <div class="page-content">
        <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4">
            <div class="col">
                <div class="card radius-10 mb-3 border-start border-0 border-3 border-success">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <p class="mb-0">Candidate not filled <br>Joining Form</p>
                            </div>
                            <div class="ms-auto font-20 my-1 text-success">
                                {{$JoiningFormNotFilled}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card radius-10 mb-3 border-start border-0 border-3 border-success">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <p class="mb-0">Document Verification<br>Pending</p>
                            </div>
                            <div class="ms-auto font-20 my-1 text-success">
                                {{$PendingVerification}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card radius-10 mb-3 border-start border-0 border-3 border-success">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <hp class="mb-0">Data Processed to <br>ESS</hp>
                            </div>
                            <div class="ms-auto font-20 my-1 text-success">
                                {{$DataProcessed}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card radius-10 mb-3 border-start border-0 border-3 border-success">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <hp class="mb-0 ">Candidate Not <br>Joined</hp>
                            </div>
                            <div class="ms-auto font-20 my-1 text-success">
                                {{$NotJoin}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-top border-0 border-4 border-danger mb-3 ">
            <div class="card-body" style="padding-top:5px;">
                <div class="col-12 d-flex justify-content-between" style="padding:5px;">
                    <span class="d-inline fw-bold">Filter</span>
                    <span class="text-danger fw-bold" style="font-size: 14px; cursor: pointer;" id="reset"><i
                                class="bx bx-refresh"></i>Reset</span>
                </div>
                <div class="row">
                    <div class="col-2">
                        <select name="Fill_Company" id="Fill_Company" class="form-select form-select-sm">
                            <option value="">Select Company</option>
                            @foreach ($company_list as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                        </select>
                        @if (isset($_REQUEST['Company']) && $_REQUEST['Company'] != '')
                            <script>
                                $('#Fill_Company').val('<?= $_REQUEST['Company'] ?>');
                            </script>
                        @endif
                    </div>
                    <div class="col-2">

                        <select name="Fill_Department" id="Fill_Department" class="form-select form-select-sm"
                                onchange="GetApplications();">
                            <option value="">Select Department</option>
                        </select>

                    </div>
                    <div class="col-2">
                        <select name="Year" id="Year" class="form-select form-select-sm" onchange="GetApplications();">
                            <option value="">Select Year</option>
                            @for ($i = 2021; $i <= date('Y'); $i++)
                                <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
                        </select>
                        @if (isset($_REQUEST['Year']) && $_REQUEST['Year'] != '')
                            <script>
                                $('#Year').val('<?= $_REQUEST['Year'] ?>');
                            </script>
                        @endif
                    </div>
                    <div class="col-2">
                        <select name="Month" id="Month" class="form-select form-select-sm"
                                onchange="GetApplications();">
                            <option value="">Select Month</option>
                            @foreach ($months as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                        </select>
                        @if (isset($_REQUEST['Month']) && $_REQUEST['Month'] != '')
                            <script>
                                $('#Month').val('<?= $_REQUEST['Month'] ?>');
                            </script>
                        @endif
                    </div>
                    <div class="col-2">
                        <select name="Status" id="Status" class="form-select form-select-sm"
                                onchange="GetApplications();">
                            <option value="">Select Status</option>
                            <option value="Yes">Joined</option>
                            <option value="No">Not Joined</option>

                        </select>
                        @if (isset($_REQUEST['Status']) && $_REQUEST['Status'] != '')
                            <script>
                                $('#Status').val('<?= $_REQUEST['Status'] ?>');
                            </script>
                        @endif
                    </div>
                    <div class="col-2">
                        <input type="text" name="Name" id="Name" class="form-control form-control-sm"
                               placeholder="Search by Name" onkeyup="GetApplications();">
                    </div>
                    @if (isset($_REQUEST['Name']) && $_REQUEST['Name'] != '')
                        <script>
                            $('#Name').val('<?= $_REQUEST['Name'] ?>');
                        </script>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card border-top border-0 border-4 border-success mb-1">
                <div class="card-body">
                    <table class="table table-bordered text-center">
                        <thead class="text-center bg-success bg-gradient text-light">
                        <tr>
                            <th>S.No.</th>
                            {{--   <th scope="col">Reference No.</th> --}}
                            <th scope="col">Candidate</th>
                            <th scope="col">Selected For</th>
                            <th scope="col">Joining DT</th>
                            <th scope="col">Joining Form Filled</th>
                            <th scope="col">Document Verified</th>
                            <th scope="col">Candidate Joined?</th>
                            <th scope="col">Data Forwarded to Ess</th>
                        </tr>
                        </thead>
                        <tbody>
                        @php
                            $i = 1;
                        @endphp
                        @foreach ($candidate_list as $row)
                            @php
                                $sendingId = base64_encode($row->JAId);

                            @endphp
                            <tr>
                                <td>
                                    @php
                                        if (Request::get('page') != null) {
                                            $y = (Request::get('page') - 1) * 20 + $i;
                                        } else {
                                            $y = $i;
                                        }
                                        echo $y;

                                    @endphp
                                </td>
                                {{--    <td>{{ $row->ReferenceNo }}</td> --}}
                                <td style="text-align:left;"><a class="text-success"
                                                                href="{{ route('candidate_detail') }}?jaid={{ $sendingId }}"
                                                                target="_blank">{{ $row->FName }} {{ $row->MName }}
                                        {{ $row->LName }}</a></td>
                                <td>{{ getDepartmentCode($row->SelectedForD) }}
                                    ({{ getcompany_code($row->SelectedForC) }})
                                </td>
                                <td>
                                    {{ date('d-m-Y',strtotime($row->JoinOnDt)) }}
                                </td>
                                <td>
                                    {{ $row->FinalSubmit == 1 ? 'Yes' : 'No' }}
                                </td>
                                <td>
                                    @if($row->FinalSubmit ==1)
                                        {{ $row->Verification }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    {{ $row->Joined }}
                                </td>
                                <td>
                                    {{ $row->ForwardToESS }}
                                </td>

                            </tr>
                            @php
                                $i++;
                            @endphp
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            {{ $candidate_list->links('vendor.pagination.custom') }}
        </div>
    </div>
@endsection
@section('script_section')
    <script>
        $(document).ready(function () {
            GetDepartment();

            function GetDepartment() {
                var CompanyId = $('#Fill_Company').val();
                $.ajax({
                    type: "GET",
                    url: "{{ route('getDepartment') }}?CompanyId=" + CompanyId,
                    success: function (res) {
                        if (res) {
                            $("#Fill_Department").empty();
                            $("#Fill_Department").append(
                                '<option value="">Select Department</option>');
                            $.each(res, function (key, value) {
                                $("#Fill_Department").append('<option value="' + value + '">' +
                                    key +
                                    '</option>');
                            });
                            $('#Fill_Department').val('<?= $_REQUEST['Department'] ?? '' ?>');
                        } else {
                            $("#Fill_Department").empty();
                        }
                    }
                });
            }

            function GetApplications() {
                var Company = $('#Fill_Company').val() || '';
                var Department = $('#Fill_Department').val() || '';
                var Year = $('#Year').val() || '';
                var Month = $('#Month').val() || '';
                var Gender = $('#Gender').val() || '';
                var Status = $('#Status').val() || '';
                var Name = $('#Name').val() || '';
                window.location.href = "{{ route('candidate_joining') }}?Company=" + Company + "&Department=" +
                    Department + "&Year=" + Year + "&Month=" + Month + "&Gender=" + Gender + "&Name=" + Name +
                    "&Status=" + Status;
            }

            $(document).on('change', '#TechScrCompany', function () {
                var CompanyId = $(this).val();
                $.ajax({
                    type: "GET",
                    url: "{{ route('getDepartment') }}?CompanyId=" + CompanyId,
                    success: function (res) {
                        if (res) {
                            $("#TechScrDepartment").empty();
                            $("#TechScrDepartment").append(
                                '<option value="">Select Department</option>');
                            $.each(res, function (key, value) {
                                $("#TechScrDepartment").append('<option value="' +
                                    value +
                                    '">' +
                                    key +
                                    '</option>');
                            });
                            $('#TechScrDepartment').val('<?= $_REQUEST['Department'] ?? '' ?>');
                        } else {
                            $("#TechScrDepartment").empty();
                        }
                    }
                });
            });

            $(document).on('change', '#TechScrDepartment', function () {
                var DepartmentId = $(this).val();
                $.ajax({
                    type: "GET",
                    url: "{{ route('getReportingManager') }}?DepartmentId=" + DepartmentId,
                    success: function (res) {
                        if (res) {
                            $("#ScreeningBy").empty();
                            $("#ScreeningBy").append(
                                '<option value="">Select Department</option>');
                            $.each(res, function (key, value) {
                                $("#ScreeningBy").append('<option value="' + value +
                                    '">' +
                                    key +
                                    '</option>');
                            });
                        } else {
                            $("#ScreeningBy").empty();
                        }
                    }
                });
            });

            $(document).on('change', '#Fill_Company', function () {
                GetApplications();
            });
            $(document).on('change', '#Fill_Department', function () {
                GetApplications();
            });
            $(document).on('change', '#Year', function () {
                GetApplications();
            });
            $(document).on('change', '#Month', function () {
                GetApplications();
            });
            $(document).on('change', '#Status', function () {
                GetApplications();
            });

            $(document).on('blur', '#Name', function () {
                GetApplications();
            });

            $(document).on('click', '#reset', function () {
                window.location.href = "{{ route('candidate_joining') }}";
            });


        });
    </script>
@endsection
