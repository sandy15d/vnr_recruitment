@extends('layouts.master')
@section('title', 'Candidate List')
@section('PageContent')
    <style>

        .shadow-none {
            -webkit-box-shadow: none !important;
            box-shadow: none !important;
        }

        .ribbon-box.ribbon-fill .ribbon {
            -webkit-transform: rotate(-45deg);
            transform: rotate(-45deg);
            width: 79px;
            height: 42px;
            left: -36px;
            top: -16px;
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-pack: center;
            -ms-flex-pack: center;
            justify-content: center;
            -webkit-box-align: end;
            -ms-flex-align: end;
            align-items: flex-end;
        }

        .ribbon-box .ribbon-primary {
            background: #2a992e;
        }

        .ribbon-box .ribbon {
            padding: 5px 12px;
            -webkit-box-shadow: 2px 5px 10px rgba(33, 37, 41, .15);
            box-shadow: 2px 5px 10px rgba(33, 37, 41, .15);
            color: #fff;
            font-size: .8125rem;
            font-weight: 600;
            position: absolute;
            left: -1px;
            top: 5px;
        }


        .ribbon-box.right.ribbon-fill .ribbon {
            -webkit-transform: rotate(45deg);
            transform: rotate(45deg);
            right: -38px;
            left: auto;
        }

        .ribbon-box.right .ribbon-info {
            background: #2a992e;
        }


        .ribbon-box.right .ribbon {
            position: absolute;
            left: auto;
            right: 0;
        }
        .ribbon-box.ribbon-fill {
            overflow: hidden;
        }

        .ribbon-box {
            position: relative;
        }
    </style>
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Candidate Applications</div>
        </div>

        <div class="row">
            <div class="col-md-9">
                @isset($candidate_list)
                    @foreach ($candidate_list as $candidate)
                        <div class="card ribbon-box border ribbon-fill shadow-none mb-3 right" style="background-color:">

                            <div class="card-body" style="padding: 0px;border: 1px solid #ddd;">
                                @if($candidate->ProfileViewed ==='Y')
                                    <div class="ribbon ribbon-primary"><i class="lni lni-checkmark"
                                                                          style="transform: rotate(308deg) !important;"></i>
                                    </div>
                                @endif
                                <div class="row p-3 py-3">

                                    <div style="width: 80%;float: left;">
                                        <table class="jatbl table borderless appli-list"
                                               style="margin-bottom: 0px !important;">
                                            <tbody>
                                            <tr>
                                                <td colspan="4" style="padding-top:8px;">
                                                    <label>
                                                        <span style="color: #275A72;font-weight: bold;padding-bottom: 10px;">
                                                              {{ ucfirst(strtolower($candidate->FName)) }}
                                                            {{ ucfirst(strtolower($candidate->MName)) }}
                                                            {{ ucfirst(strtolower($candidate->LName)) }}
                                                     </span>
                                                    </label>
                                                </td>

                                            </tr>
                                            <tr class="">
                                                <td class="fw-bold-500">Experience
                                                </td>
                                                <td style="text-align: left">:
                                                    @php
                                                        if ($candidate->Professional === 'P') {
                                                            if ($candidate->TotalYear !== null || $candidate->TotalMonth !== null) {
                                                                $result = $candidate->TotalYear . ' Year and ' . $candidate->TotalMonth . ' Month';
                                                            } else {
                                                                $result = 'Experienced'; // or any other message you prefer
                                                            }
                                                        } else {
                                                            $result = 'Fresher';
                                                        }
                                                        echo $result;
                                                    @endphp
                                                </td>
                                                <td class="fw-bold-500" style="text-align: left;">Contact No.</td>
                                                <td style="text-align:left">
                                                    : {{ $candidate->Phone }}
                                                </td>
                                            </tr>
                                            <tr class="">
                                                <td class="fw-bold-500">Cur. Company</td>
                                                <td style="text-align: left">
                                                    : {{ $candidate->PresentCompany ?? '' }}
                                                </td>
                                                <td class="fw-bold-500" style="text-align: left">Email ID
                                                </td>
                                                <td style="text-align: left">
                                                    : {{ $candidate->Email }}
                                                </td>
                                            </tr>
                                            <tr class="">
                                                <td class="fw-bold-500">Cur. Designation</td>
                                                <td style="text-align: left">
                                                    : {{ $candidate->Designation }}
                                                </td>
                                                <td class="fw-bold-500" style="text-align: left">Education
                                                </td>
                                                <td style="text-align: left">
                                                    :
                                                    {{ getEducationCodeById($candidate->Education) }}
                                                    {{ $candidate->Specialization !== null ? '(' . getSpecializationbyId($candidate->Specialization) . ')' : '' }}
                                                </td>
                                            </tr>
                                            <tr class="">
                                                <td class="fw-bold-500">Cur. Location</td>
                                                <td style="text-align: left">: {{ $candidate->City }}</td>
                                                <td class="fw-bold-500">Apply Date</td>
                                                <td style="text-align: left">
                                                    : {{date('d-m-Y',strtotime($candidate->ApplyDate))}}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="fw-bold-500"> Source</td>
                                                <td style="text-align: left">
                                                    : {{ getResumeSourceById($candidate->ResumeSource) }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            @if($candidate->hr_screening_status !== null)
                                                <tr>
                                                    <td class="fw-bold-500">HR Screening Status</td>
                                                    <td style="text-align: left"> :
                                                        &nbsp;{{$candidate->hr_screening_status}}</td>
                                                    <td class="fw-bold-500">HR Screening By</td>
                                                    <td style="text-align: left">:
                                                        &nbsp;{{getFullName($candidate->hr_screening_by)}}</td>
                                                </tr>
                                                <tr>
                                                    <td class="fw-bold-500">HR Screening Remark</td>
                                                    <td style="text-align: left;" colspan="3"> :
                                                        &nbsp;{{$candidate->hr_screening_remark}}</td>
                                                </tr>
                                                <tr>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                                <tr>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                            @endif


                                            @if($candidate->FwdTechScr === 'Yes')
                                                <tr>
                                                    <td class="fw-bold-500">Tech. Screening Status</td>
                                                    <td style="text-align: left"> :
                                                        &nbsp;{{$candidate->tech_screening_status}}</td>
                                                    <td class="fw-bold-500">Tech. Screening By</td>
                                                    <td style="text-align: left">:
                                                        &nbsp;{{getFullName($candidate->tech_screening_by)}}</td>
                                                </tr>

                                            @endif

                                            <tr>

                                                <td class="fw-bold-500">Application Form Filled By:</td>
                                                <td> :
                                                    {{$candidate->Type === 'Manual Entry' ? 'HR' : 'Candidate'}}
                                                </td>
                                                <td></td>
                                                <td class="float-end">

                                                        @if($candidate->SLDPT ==='Y' )
                                                            <button disabled type="button"
                                                                    class="btn btn-sm btn-primary px-3">Shortlisted By
                                                                Department
                                                            </button>
                                                        @else
                                                        @if( $candidate->IntervStatus == null || $candidate->IntervStatus == '')
                                                            <button type="button"
                                                                    class="btn btn-sm btn-outline-primary px-3"
                                                                    title="Shortlisted by Department"
                                                                    onclick='SLDPT({{ $candidate->JAId }}, "{{ $candidate->FName }}")'>
                                                                <i class="bx bx-bookmark mr-1"></i>SL DPT
                                                            </button>

                                                        @endif
                                                    @endif
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="" style=" width: 20%;float: left;">

                                        <center>
                                            @if ($candidate->CandidateImage === null)
                                                <img src="{{ URL::to('/') }}/assets/images/user1.png"
                                                     style="width: 100px; height: 100px;" class=img-fluid rounded" alt="..." />
                                            @else
                                                <img src="{{ URL::to('/') }}/uploads/Picture/{{ $candidate->CandidateImage }}"
                                                     class="img-fluid rounded"
                                                     style="text-align: center;width: 100px;height: 100px;margin-top: 20px;margin-left: 20px;margin-bottom: 12px;"
                                                     alt="..."/>
                                            @endif
                                        </center>
                                        <center>
                                            @php
                                                $sendingId = base64_encode($candidate->JAId);
                                            @endphp
                                            <small>
                                                <span class="text-primary m-1 "
                                                      style="cursor: pointer; font-size:14px;">
                                                     <a href="{{ route('candidate_detail') }}?jaid={{ $sendingId }}"
                                                        target="_blank" class="text-primary mt-2 mb-2">View Details</a>
                                                </span>
                                            </small>
                                        </center>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endisset
            </div>
            <div class="col-3">
                <div class="card border-top border-0 border-4 border-danger">
                    <div class="card-body">
                        <div class="col-12 mb-2 d-flex justify-content-between">
                            <span class="d-inline fw-bold">Filter</span>
                            <span class="text-danger fw-bold" style="font-size: 14px; cursor: pointer;" id="reset"><i
                                    class="bx bx-refresh"></i>Reset</span>
                        </div>
                        <div class="col-12 mb-3">
                            <label for="Department" class="form-label">Department :</label>
                            <select name="Department" id="Department" class="form-select form-select-sm select2" onchange="GetApplications();">
                                <option value="">Select Department</option>
                                @foreach ($department_list as $key => $value)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                            @if (isset($_REQUEST['Department']) && $_REQUEST['Department'] !== '')
                                <script>
                                    $('#Department').val('<?= $_REQUEST['Department'] ?>');
                                </script>
                            @endif
                        </div>
                        <div class="col-12 mb-3">
                            <select name="Year" id="Year" class="form-select form-select-sm"
                                    onchange="GetApplications();">
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
                        <div class="col-12 mb-3">
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
                        <div class="col-12 mb-3">
                            <label for="Education" class="form-label">Education :</label>
                            <select name="Education" id="Education" class="form-select form-select-sm select2"
                                    onchange="GetApplications();">
                                <option value="">Select Education</option>
                                @foreach ($education_list as $key => $value)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                            @if (isset($_REQUEST['Education']) && $_REQUEST['Education'] != '')
                                <script>
                                    $('#Education').val('<?= $_REQUEST['Education'] ?>');
                                </script>
                            @endif
                        </div>
                        <div class="col-12 mb-3">
                            <label for="Education" class="form-label">Source :</label>
                            <select name="ResumeSource" id="ResumeSource" class="form-select form-select-sm select2"
                                    onchange="GetApplications();">
                                <option value="">Select Source</option>
                                @foreach ($source_list as $key => $value)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                            @if (isset($_REQUEST['ResumeSource']) && $_REQUEST['ResumeSource'] != '')
                                <script>
                                    $('#ResumeSource').val('<?= $_REQUEST['ResumeSource'] ?>');
                                </script>
                            @endif
                        </div>
                        <div class="col-12 mb-3">
                            <label for="State" class="form-label">State :</label>
                            <select name="State" id="State" class="form-select form-select-sm select2"
                                    onchange="GetApplications();">
                                <option value="">Select State</option>
                                @foreach ($state_list as $key => $value)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                            @if (isset($_REQUEST['State']) && $_REQUEST['State'] != '')
                                <script>
                                    $('#State').val('<?= $_REQUEST['State'] ?>');
                                </script>
                            @endif
                        </div>
                        <div class="col-12 mb-3">
                            <label for="Gender" class="form-label">Gender :</label>
                            <select name="Gender" id="Gender" class="form-select form-select-sm select2" onchange="GetApplications();">
                                <option value="">Select Gender</option>
                                <option value="M">Male</option>
                                <option value="F">Female</option>
                                <option value="O">Other</option>
                            </select>
                            @if (isset($_REQUEST['Gender']) && $_REQUEST['Gender'] !== '')
                                <script>
                                    $('#Gender').val('<?= $_REQUEST['Gender'] ?>');
                                </script>
                            @endif
                        </div>
                        <div class="col-12 mb-3">
                            <input type="text" name="Name" id="Name" class="form-control form-control-sm"
                                   placeholder="Search by Name" onkeyup="GetApplications();">
                        </div>
                        @if (isset($_REQUEST['Name']) && $_REQUEST['Name'] != '')
                            <script>
                                $('#Name').val('<?= $_REQUEST['Name'] ?>');
                            </script>
                        @endif
                        <div class="col-12 mb-3">
                            <input type="email" name="Email" id="Email" class="form-control form-control-sm"
                                   placeholder="Search by Email" onkeyup="GetApplications();">
                        </div>
                        @if (isset($_REQUEST['Email']) && $_REQUEST['Email'] != '')
                            <script>
                                $('#Email').val('<?= $_REQUEST['Email'] ?>');
                            </script>
                        @endif
                        <div class="col-12 mb-3">
                            <input type="text" name="Phone" id="Phone" class="form-control form-control-sm"
                                   placeholder="Search by Phone" onkeyup="GetApplications();">
                        </div>
                        @if (isset($_REQUEST['Phone']) && $_REQUEST['Phone'] != '')
                            <script>
                                $('#Phone').val('<?= $_REQUEST['Phone'] ?>');
                            </script>
                        @endif
                        <div class="col-12 mb-3">
                            <input type="text" name="City" id="City" class="form-control form-control-sm"
                                   placeholder="Search by City" onkeyup="GetApplications();">
                        </div>
                        @if (isset($_REQUEST['City']) && $_REQUEST['City'] != '')
                            <script>
                                $('#City').val('<?= $_REQUEST['City'] ?>');
                            </script>
                        @endif

                    </div>
                </div>

            </div>
        </div>


        @if (!empty($candidate_list))
            {{ $candidate_list->links('vendor.pagination.custom') }}
        @endif
    </div>

@endsection
@section('script_section')
    <script>
        $(".select2").select2();
        $(document).on('click', '#reset', function () {
            window.location.href = "{{route('resume_databank')}}";
        });

        function GetApplications() {

            var Department = $('#Department').val() || '';
            var Year = $('#Year').val() || '';
            var Month = $('#Month').val() || '';
            var ResumeSource = $('#ResumeSource').val() || '';
            var Gender = $('#Gender').val() || '';
            var Education = $('#Education').val() || '';
            var Name = $('#Name').val() || '';
            var Email = $('#Email').val() || '';
            var Phone = $('#Phone').val() || '';

            var State = $('#State').val() || '';
            var City = $('#City').val() || '';

            window.location.href = "{{ route('resume_databank') }}?Department=" +
                Department + "&Year=" + Year + "&Month=" + Month + "&ResumeSource=" + ResumeSource + "&Gender=" + Gender +
                "&Education=" + Education + "&Name=" + Name + "&Email=" + Email + "&Phone=" + Phone + "&State=" + State + "&City=" + City ;
        }

        function SLDPT(JAId, Name) {

            if (confirm("Are you sure you want to shortlist " + Name + " ?")) {
                $.ajax({
                    url: "{{ route('sldpt_process_from_databank') }}",
                    type: "POST",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "JAId": JAId
                    },
                    beforeSend: function () {
                        $('#loader').show();
                    },
                    success: function (data) {
                        $('#loader').hide();
                        if (data.status == 200) {
                            toastr.success(data.msg);
                        } else {
                            toastr.error(data.msg);
                        }
                        setTimeout(function () {
                            location.reload();
                        }, 2000);
                    }
                });
            }
        }
    </script>
@endsection
