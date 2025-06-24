@extends('layouts.master')
@section('title', 'Candidate List')
@section('PageContent')
    <style>
        .ribbon-corner.ribbon-fold {
            --tor-ribbon-polygon: polygon(0% 100%, 50% calc(100% - (var(--tor-ribbon-size) + 1em)), 100% 100%, 100% calc(100% + 0.5em), 0% calc(100% + 0.5em));
            margin: -0.34em;
        }

        .ribbon-corner {
            --tor-ribbon-size: 3em;
            --tor-ribbon-rotate: 45deg;
            --tor-ribbon-translateX: 50%;
            --tor-ribbon-translateY: calc((var(--tor-ribbon-size) + 1em) - 100%);
            --tor-ribbon-origin: 100% 0%;
            --tor-ribbon-polygon: polygon(0% 100%, 50% calc(100% - (var(--tor-ribbon-size) + 1em)), 100% 100%);
        }

        .ribbon-corner {
            background-color: #1f44ff;
            color: #fff;
            position: absolute;
            top: 0;
            right: 0;
            -webkit-clip-path: var(--tor-ribbon-polygon);
            clip-path: var(--tor-ribbon-polygon);
            transform: rotate(var(--tor-ribbon-rotate)) translateY(var(--tor-ribbon-translateY)) translateX(var(--tor-ribbon-translateX)) !important;
            transform-origin: var(--tor-ribbon-origin) !important;
            width: calc((var(--tor-ribbon-size) + 1em) * 2);
            height: 2em;
        }

        .ribbon-corner,
        .ribbon-bookmark-v,
        .ribbon-bookmark-h {
            display: flex;
            text-align: center;
            align-items: center;
            justify-content: center;
        }

        .bg-maroon {
            background-color: hsla(331, 74%, calc(30% * var(--tor-bg-lightness, 1)), var(--tor-bg-opacity, 1)) !important;
        }

        .bg-maroon {
            --tor-bg-lightness: 1;
            --tor-bg-opacity: 1;
        }

        .ribbon-corner.ribbon-fold:before {
            --tor-ribbon-fold-polygon: polygon(0% -10px, 100% -10px, 100% 100%, calc(100% - 0.5em - 10px) -10px, calc(0.5em + 10px) -10px, 0% 100%);
            background-color: inherit;
            filter: brightness(50%);
            -webkit-clip-path: var(--tor-ribbon-fold-polygon);
            clip-path: var(--tor-ribbon-fold-polygon);
            content: "";
            position: absolute;
            bottom: calc(-0.5em + 1px);
            left: 0;
            width: 100%;
            height: 0.5em;
            z-index: -1;
        }

        .active {
            background: darkolivegreen;
            color: white;
        }

        .active h6 {
            color: white;
        }


        .ribbon-box1.ribbon-fill1 {
            overflow: hidden;
        }


        .shadow-none {
            -webkit-box-shadow: none !important;
            box-shadow: none !important;
        }

        .ribbon-box1.ribbon-fill1 .ribbon1 {
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

        .ribbon-box1 .ribbon-primary1 {
            background: #0e8452;
        }

        .ribbon-box1 .ribbon1 {
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


    </style>
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">MRF Pipeline - Candidates</div>
        </div>

        <div class="card border-top border-0 border-4 border-success">
            <div class="card-body" style="padding-top:5px;font-size: 14px;">
                <div class="col-12 d-flex justify-content-left">
                    <span class="d-inline"><b>MRF Code :</b>
                        <span>{{ $mrf->JobCode }}</span></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <span class="d-inline"><b>Post Title :</b>
                        <span>{{ $mrf->Title }}</span></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <span class="d-inline"><b>Department :</b> <span>{{ $mrf->department_name }}</span></span>

                </div>

            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="card radius-10 {{ request()->get('filter') === 'total' ? 'active' : '' }}">
                    <a href="/candidate_association?mrf={{ base64_encode($mrf->MRFId) }}&filter=total">
                        <div class="card-body">
                            <div class="text-center">
                                <div>
                                    <p class="mb-0">Applications</p>
                                    <h6>{{ $mrf->Total }}</h6>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col">
                <div class="card radius-10 {{ request()->get('filter') === 'hr_screening' ? 'active' : '' }}">
                    <a href="/candidate_association?mrf={{ base64_encode($mrf->MRFId) }}&filter=hr_screening">
                        <div class="card-body">
                            <div class="text-center">
                                <div>
                                    <p class="mb-0">HR Screening</p>
                                    <h6>{{ $mrf->HR_Screening }}</h6>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col">
                <div class="card radius-10 {{ request()->get('filter') === 'tech_screening' ? 'active' : '' }}">
                    <a href="/candidate_association?mrf={{ base64_encode($mrf->MRFId) }}&filter=tech_screening">
                        <div class="card-body">
                            <div class="text-center">
                                <div>
                                    <p class="mb-0">Tech. Screening</p>
                                    <h6>{{ $mrf->Technical_Screening }}</h6>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col">
                <div class="card radius-10 {{ request()->get('filter') === 'interviewed' ? 'active' : '' }}">
                    <a href="/candidate_association?mrf={{ base64_encode($mrf->MRFId) }}&filter=interviewed">
                        <div class="card-body">
                            <div class="text-center">
                                <div>
                                    <p class="mb-0">Interviewed</p>
                                    <h6>{{ $mrf->Interviewed }}</h6>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col">
                <div class="card radius-10 {{ request()->get('filter') === 'selected' ? 'active' : '' }}">
                    <a href="/candidate_association?mrf={{ base64_encode($mrf->MRFId) }}&filter=selected">
                        <div class="card-body">
                            <div class="text-center">
                                <div>
                                    <p class="mb-0">Selected</p>
                                    <h6>{{ $mrf->Selected }}</h6>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col">
                <div class="card radius-10 {{ request()->get('filter') === 'offered' ? 'active' : '' }}">
                    <a href="/candidate_association?mrf={{ base64_encode($mrf->MRFId) }}&filter=offered">
                        <div class="card-body">
                            <div class="text-center">
                                <p class="mb-0">Job Offered</p>
                                <h6>{{ $mrf->Offered }}</h6>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col">
                <div class="card radius-10 {{ request()->get('filter') === 'accepted' ? 'active' : '' }}">
                    <a href="/candidate_association?mrf={{ base64_encode($mrf->MRFId) }}&filter=accepted">
                        <div class="card-body">
                            <div class="text-center">
                                <div>
                                    <p class="mb-0">Offer Accepted</p>
                                    <h6>{{ $mrf->Accepted }}</h6>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col">
                <div class="card radius-10 {{ request()->get('filter') === 'joined' ? 'active' : '' }}">
                    <a href="/candidate_association?mrf={{ base64_encode($mrf->MRFId) }}&filter=joined">
                        <div class="card-body">
                            <div class="text-center">
                                <div>
                                    <p class="mb-0">Joined</p>
                                    <h6>{{ $mrf->Joined }}</h6>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>


        <div class="row">
            <div class="col-md-9">
                @isset($candidate_list)
                    @foreach ($candidate_list as $candidate)
                        <div class="card ribbon-box1 border ribbon-fill1 shadow-none mb-3" style="background-color:">
                            @if ($candidate->candidate_status === 'Rejected')
                                <div class="ribbon-corner ribbon-fold bg-danger">Rejected</div>
                            @endif
                            @if ($candidate->candidate_status === 'Selected')
                                <div class="ribbon-corner ribbon-fold bg-success">Selected</div>
                            @endif
                            <div class="card-body" style="padding: 0px;border: 1px solid #ddd;">
                                @if($candidate->ProfileViewed ==='Y')
                                    <div class="ribbon1 ribbon-primary1"><i class="lni lni-checkmark-circle"
                                                                            style="transform: rotate(39deg);"></i></div>
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
                                                    @if( $candidate->IntervStatus == null || $candidate->IntervStatus == '')
                                                    @if($candidate->SLDPT ==='N' )
                                                        <button type="button"
                                                                class="btn btn-sm btn-outline-primary px-3"
                                                                title="Shortlisted by Department"
                                                                onclick='SLDPT({{ $candidate->JAId }}, "{{ $candidate->FName }}")'>
                                                            <i class="bx bx-bookmark mr-1"></i>SL DPT
                                                        </button>
                                                    @else
                                                        <button disabled type="button"
                                                                class="btn btn-sm btn-primary px-3">Shortlisted By
                                                            Department
                                                        </button></button>
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
                                                     style="width: 100px; height: 100px;" class=img-fluid rounded"
                                                alt="..." />
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
            <div class="col-3 www">
                <div class="card border-top border-0 border-4 border-danger">
                    <div class="card-body">
                        <div class="col-12 mb-2 d-flex justify-content-between">
                            <span class="d-inline fw-bold">Filter</span>
                            <span class="text-danger fw-bold" style="font-size: 14px; cursor: pointer;" id="reset"><i
                                        class="bx bx-refresh"></i>Reset</span>
                        </div>
                        @if (isset($_REQUEST['filter']) && $_REQUEST['filter'] === 'total')
                            <div class="col-12 mb-3">
                                <label for="Hr_Screening_Perform" class="form-label">HR Screening Performed ?</label>
                                <select name="Hr_Screening_Perform" id="Hr_Screening_Perform"
                                        class="form-select form-select-sm">
                                    <option value="">Select</option>
                                    <option value="Yes">Yes</option>
                                    <option value="No">No</option>

                                </select>
                                @if (isset($_REQUEST['Hr_Screening_Perform']) && $_REQUEST['Hr_Screening_Perform'] !== '')
                                    <script>
                                        $('#Hr_Screening_Perform').val('<?= $_REQUEST['Hr_Screening_Perform'] ?>');
                                    </script>
                                @endif
                            </div>
                        @endif
                        @if (isset($_REQUEST['filter']) && $_REQUEST['filter'] === 'hr_screening')
                            <div class="col-12 mb-3">
                                <label for="HR_Scr_Status" class="form-label">HR Screening Status</label>
                                <select name="HR_Scr_Status" id="HR_Scr_Status" class="form-select form-select-sm">
                                    <option value="">Select Status</option>
                                    <option value="Selected">Selected</option>
                                    <option value="Rejected">Rejected</option>

                                </select>
                                @if (isset($_REQUEST['HR_Scr_Status']) && $_REQUEST['HR_Scr_Status'] !== '')
                                    <script>
                                        $('#HR_Scr_Status').val('<?= $_REQUEST['HR_Scr_Status'] ?>');
                                    </script>
                                @endif
                            </div>
                        @endif

                        @if (isset($_REQUEST['filter']) && $_REQUEST['filter'] === 'tech_screening')
                            <div class="col-12 mb-3">
                                <label for="Tech_Scr_Status" class="form-label">Technical Screening Status</label>
                                <select name="Tech_Scr_Status" id="Tech_Scr_Status" class="form-select form-select-sm">
                                    <option value="">Select Status</option>
                                    <option value="Selected">Selected</option>
                                    <option value="Rejected">Rejected</option>

                                </select>
                                @if (isset($_REQUEST['Tech_Scr_Status']) && $_REQUEST['Tech_Scr_Status'] !== '')
                                    <script>
                                        $('#Tech_Scr_Status').val('<?= $_REQUEST['Tech_Scr_Status'] ?>');
                                    </script>
                                @endif
                            </div>
                        @endif

                        @if (isset($_REQUEST['filter']) && $_REQUEST['filter'] === 'interviewed')
                            <div class="col-12 mb-3">
                                <label for="Interview_Status" class="form-label">Interview Status</label>
                                <select name="Interview_Status" id="Interview_Status" class="form-select form-select-sm">
                                    <option value="">Select Status</option>
                                    <option value="Selected">Selected</option>
                                    <option value="Rejected">Rejected</option>

                                </select>
                                @if (isset($_REQUEST['Interview_Status']) && $_REQUEST['Interview_Status'] !== '')
                                    <script>
                                        $('#Interview_Status').val('<?= $_REQUEST['Interview_Status'] ?>');
                                    </script>
                                @endif
                            </div>
                        @endif

                        <div class="col-12 mb-3">
                            <label for="Gender" class="form-label">Gender :</label>
                            <select name="Gender" id="Gender" class="form-select form-select-sm">
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
                            <label for="Type" class="form-label">Application Filled By :</label>
                            <select name="Type" id="Type" class="form-select form-select-sm">
                                <option value="">Select</option>
                                <option value="Manual Entry">HR</option>
                                <option value="Regular">Candidate</option>

                            </select>
                            @if (isset($_REQUEST['Type']) && $_REQUEST['Type'] !== '')
                                <script>
                                    $('#Type').val('<?= $_REQUEST['Type'] ?>');
                                </script>
                            @endif
                        </div>
                        <div class="col-12 mb-3">
                            <label for="Source" class="form-label">Application Source :</label>
                            <select name="Source" id="Source" class="form-select form-select-sm">
                                <option value="">Select</option>
                                @foreach($resume_list as $key=>$value)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach

                            </select>
                            @if (isset($_REQUEST['Source']) && $_REQUEST['Source'] !== '')
                                <script>
                                    $('#Source').val('<?= $_REQUEST['Source'] ?>');
                                </script>
                            @endif
                        </div>
                    </div>
                </div>

            </div>
        </div>


        @if (!empty($candidate_list))
            {{ $candidate_list->links('vendor.pagination.custom') }}
        @endif
    </div>
    <div id="resume_modal" class="modal custom-modal fade" role="dialog" data-bs-backdrop="static"
         data-bs-keyboard="false">
        <div class="modal-dialog  modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title">Resume</h6>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="resume_div">

                </div>
            </div>
        </div>
    </div>
@endsection
@section('script_section')
    <script>
        $(document).on('change', '#Source', function () {
            let url = window.location.href;
            let Source = $(this).val();
            // Check if the 'Source' parameter already exists in the URL
            if (url.includes('Source=')) {
                // Replace the existing 'Source' parameter with the new value
                url = url.replace(/Source=([^&]*)/, 'Source=' + Source);
            } else {
                // If 'Source' parameter doesn't exist, add it to the URL
                if (url.includes('?')) {
                    url += '&Source=' + Source;
                } else {
                    url += '?Source=' + Source;
                }
            }
            // Update the URL
            window.location.href = url;
        });

        $(document).on('click', '#reset', function () {
            window.location.href = url;
        });

        $(document).on('change', '#HR_Scr_Status', function () {
            let url = window.location.href;
            let HR_Scr_Status = $(this).val();

            // Check if the 'HR_Scr_Status' parameter already exists in the URL
            if (url.includes('HR_Scr_Status=')) {
                // Replace the existing 'HR_Scr_Status' parameter with the new value
                url = url.replace(/HR_Scr_Status=([^&]*)/, 'HR_Scr_Status=' + HR_Scr_Status);
            } else {
                // If 'HR_Scr_Status' parameter doesn't exist, add it to the URL
                if (url.includes('?')) {
                    url += '&HR_Scr_Status=' + HR_Scr_Status;
                } else {
                    url += '?HR_Scr_Status=' + HR_Scr_Status;
                }
            }

            // Update the URL
            window.location.href = url;
        });
        $(document).on('change', '#Tech_Scr_Status', function () {
            let url = window.location.href;
            let Tech_Scr_Status = $(this).val();

            // Check if the 'Tech_Scr_Status' parameter already exists in the URL
            if (url.includes('Tech_Scr_Status=')) {
                // Replace the existing 'Tech_Scr_Status' parameter with the new value
                url = url.replace(/Tech_Scr_Status=([^&]*)/, 'Tech_Scr_Status=' + Tech_Scr_Status);
            } else {
                // If 'Tech_Scr_Status' parameter doesn't exist, add it to the URL
                if (url.includes('?')) {
                    url += '&Tech_Scr_Status=' + Tech_Scr_Status;
                } else {
                    url += '?Tech_Scr_Status=' + Tech_Scr_Status;
                }
            }

            // Update the URL
            window.location.href = url;
        });
        $(document).on('change', '#Interview_Status', function () {
            let url = window.location.href;
            let Interview_Status = $(this).val();

            // Check if the 'Interview_Status' parameter already exists in the URL
            if (url.includes('Interview_Status=')) {
                // Replace the existing 'Interview_Status' parameter with the new value
                url = url.replace(/Interview_Status=([^&]*)/, 'Interview_Status=' + Interview_Status);
            } else {
                // If 'Interview_Status' parameter doesn't exist, add it to the URL
                if (url.includes('?')) {
                    url += '&Interview_Status=' + Interview_Status;
                } else {
                    url += '?Interview_Status=' + Interview_Status;
                }
            }

            // Update the URL
            window.location.href = url;
        });

        $(document).on('change', '#Hr_Screening_Perform', function () {
            let url = window.location.href;
            let Hr_Screening_Perform = $(this).val();
            // Check if the 'Hr_Screening_Perform' parameter already exists in the URL
            if (url.includes('Hr_Screening_Perform=')) {
                // Replace the existing 'Hr_Screening_Perform' parameter with the new value
                url = url.replace(/Hr_Screening_Perform=([^&]*)/, 'Hr_Screening_Perform=' + Hr_Screening_Perform);
            } else {
                // If 'Hr_Screening_Perform' parameter doesn't exist, add it to the URL
                if (url.includes('?')) {
                    url += '&Hr_Screening_Perform=' + Hr_Screening_Perform;
                } else {
                    url += '?Hr_Screening_Perform=' + Hr_Screening_Perform;
                }
            }

            // Update the URL
            window.location.href = url;
        });

        $(document).on('change', '#Gender', function () {
            let url = window.location.href;
            let Gender = $(this).val();

            // Check if the 'Gender' parameter already exists in the URL
            if (url.includes('Gender=')) {
                // Replace the existing 'Gender' parameter with the new value
                url = url.replace(/Gender=([^&]*)/, 'Gender=' + Gender);
            } else {
                // If 'Gender' parameter doesn't exist, add it to the URL
                if (url.includes('?')) {
                    url += '&Gender=' + Gender;
                } else {
                    url += '?Gender=' + Gender;
                }
            }

            // Update the URL
            window.location.href = url;
        });

        $(document).on('change', '#Type', function () {
            let url = window.location.href;
            let Type = $(this).val();

            // Check if the 'Type' parameter already exists in the URL
            if (url.includes('Type=')) {
                // Replace the existing 'Type' parameter with the new value
                url = url.replace(/Type=([^&]*)/, 'Type=' + Type);
            } else {
                // If 'Type' parameter doesn't exist, add it to the URL
                if (url.includes('?')) {
                    url += '&Type=' + Type;
                } else {
                    url += '?Type=' + Type;
                }
            }

            // Update the URL
            window.location.href = url;
        });

        $(document).on('change', '#Source', function () {
            let url = window.location.href;
            let Source = $(this).val();

            // Check if the 'Source' parameter already exists in the URL
            if (url.includes('Source=')) {
                // Replace the existing 'Source' parameter with the new value
                url = url.replace(/Source=([^&]*)/, 'Source=' + Source);
            } else {
                // If 'Source' parameter doesn't exist, add it to the URL
                if (url.includes('?')) {
                    url += '&Source=' + Source;
                } else {
                    url += '?Source=' + Source;
                }
            }

            // Update the URL
            window.location.href = url;
        });

        function show_resume(id) {
            $.ajax({
                url: "{{ route('show_resume') }}",
                type: "POST",
                data: {
                    "_token": "{{ csrf_token() }}",
                    "JCId": id
                },
                success: function (data) {
                    $('#resume_div').html(data);
                }
            });
        }

        function SLDPT(JAId, Name) {

            if (confirm("Are you sure you want to shortlist " + Name + " ?")) {
                $.ajax({
                    url: "{{ route('sldpt_process') }}",
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
