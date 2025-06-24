@php

    $country_list = DB::table('core_country')->pluck('country_name', 'id');
@endphp
@extends('layouts.master')
@section('title', 'Job Applications')
@section('PageContent')
    <style>
        .table> :not(caption)>*>* {
            padding: 2px 1px;
        }

        .frminp {
            padding: 4px !important;
            height: 25px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 550;
        }

        #applications {
            height: 1000px;
            overflow-y: scroll;
        }

        #applications::-webkit-scrollbar {
            display: none;
        }

        /* Hide scrollbar for IE, Edge and Firefox */
        #applications {
            -ms-overflow-style: none;
            /* IE and Edge */
            scrollbar-width: none;
            /* Firefox */
        }

        .ribbon-box.ribbon-fill {
            overflow: hidden;
        }

        .ribbon-box {
            position: relative;
        }

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
            background: #405189;
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
            background: #299cdb;
        }


        .ribbon-box.right .ribbon {
            position: absolute;
            left: auto;
            right: 0;
        }
    </style>

    <div class="page-content">
        <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4">
            <div class="col">
                <div class="card radius-10 border-start border-0 border-3 border-success mb-3">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <p class="mb-0 text-secondary">Total Applications:</p>
                            </div>
                            <div class="ms-auto font-20 my-1 text-success">{{ $total_candidate }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="row">
            <div class="col-9">
                <div class="card border-top border-0 border-4 border-success mb-1">
                    <div class="card-body d-flex justify-content-between" style="padding: 5px;">
                        <span class="d-inline">
                            <span style="font-weight: bold;">↱</span>
                            <label class="text-success"><input id="checkall" type="checkbox" name="">&nbsp;Check
                                all</label>
                            <i class="text-muted" style="font-size: 13px;">With selected:</i>
                            <label class="text-success " style=" cursor: pointer;" data-bs-toggle="modal"
                                data-bs-target="#TechScreeningModal"><i class="fas fa-share text-success"></i> Fwd. for
                                Technical
                                Screening
                            </label>
                        </span>
                        <span style="float: right"><button type="button" class="btn btn-primary btn-sm"
                                data-bs-toggle="modal" data-bs-target="#application_form_modal"><i
                                    class="bx bx-user mr-1"></i>New Application</button></span>
                        <span style="float: right"><button type="button" class="btn btn-primary btn-sm"
                                data-bs-toggle="modal" data-bs-target="#import_modal"><i
                                    class="bx bx-file"></i>Import</button></span>
                        <span style="float: right"><button type="button" class="btn btn-primary btn-sm"
                                id="bulk_download_cv"><i class="bx bx-file"></i>Bulk CV Download</button></span>
                    </div>
                </div>
                <div id="applications">
                    @foreach ($candidate_list as $row)
                        @php
                            $bg_color = '';
                            if ($row->Status == 'Rejected' || $row->BlackList == 1) {
                                $bg_color = '#fe36501f';
                            } else {
                                if ($row->FwdTechScr == 'Yes') {
                                    $bg_color = '#dbffdacc';
                                }
                            }
                        @endphp
                        <div class="card mb-3 ribbon-box border ribbon-fill shadow-none right"
                            style="background-color:<?= $bg_color ?>">
                            <div class="card-body" style="padding: 0px;border: 1px solid #ddd;">
                                @if ($row->ProfileViewed === 'Y')
                                    <div class="ribbon ribbon-primary"><i class="lni lni-checkmark"
                                            style="transform: rotate(308deg) !important;"></i>
                                    </div>
                                @endif
                                <div class="row  p-2 py-2">
                                    <div style="width: 80%;float: left;">
                                        <table class="jatbl table borderless appli-list"
                                            style="margin-bottom: 0px !important;">
                                            <tbody>
                                                <tr>
                                                    <td class="fw-bold-500"
                                                        style="text-align: left;background-color: #e7e7e7;">Applied For
                                                    </td>
                                                    <td colspan="3" style="text-align: left;background-color: #e7e7e7;">
                                                        :
                                                        <b><?= $row->DesigId != null ? getDesignation($row->DesigId) : "<i class='fa fa-pencil-square-o text-primary' aria-hidden='true' style='cursor: pointer;' id='AddToJobPost' data-id='$row->JAId'></i>" ?></b>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td colspan="4" style="padding-top:8px;">
                                                        <label>
                                                            @if ($row->Status == 'Selected' && $row->FwdTechScr == 'No' && $row->BlackList == 0)
                                                                <input type="checkbox" name="selectCand" class="japchks"
                                                                    onclick="checkAllorNot()" value="{{ $row->JAId }}">
                                                            @endif
                                                            <span
                                                                style="color: #275A72;font-weight: bold;padding-bottom: 10px;">
                                                                {{ $row->FName }} {{ $row->MName }} {{ $row->LName }}
                                                                (Ref.No. {{ $row->ReferenceNo }})
                                                            </span> <span>

                                                            </span>
                                                        </label>
                                                    </td>
                                                </tr>
                                                <tr class="">
                                                    <td class="fw-bold-500">Experience
                                                    </td>
                                                    <td style="text-align: left">:
                                                        @php
                                                            if ($row->Professional == 'F') {
                                                                echo 'Fresher';
                                                            } else {
                                                                if (
                                                                    $row->TotalYear != null ||
                                                                    $row->TotalMonth != null
                                                                ) {
                                                                    echo $row->TotalYear .
                                                                        ' Years ' .
                                                                        $row->TotalMonth .
                                                                        ' Months';
                                                                } elseif ($row->JobStartDate != null) {
                                                                    $fdate = $row->JobStartDate;
                                                                    if ($row->JobEndDate == null) {
                                                                        $tdate = Carbon\Carbon::now();
                                                                    } else {
                                                                        $tdate = $row->JobEndDate;
                                                                    }

                                                                    echo Carbon\Carbon::createFromDate($fdate)
                                                                        ->diff($tdate)
                                                                        ->format('%y Years %m Months');
                                                                } else {
                                                                    echo 'Experienced';
                                                                }
                                                            }
                                                        @endphp
                                                    </td>
                                                    <td class="fw-bold-500" style="text-align: left;">Contact No.</td>
                                                    <td style="text-align:left">
                                                        : {{ $row->Phone }}@if ($row->Verified == 'Y')
                                                            <i class="fadeIn animated bx bx-badge-check text-success"></i>
                                                        @endif
                                                    </td>
                                                </tr>
                                                <tr class="">
                                                    <td class="fw-bold-500">Cur. Company</td>
                                                    <td style="text-align: left">
                                                        : <?= $row->PresentCompany == null ? '' : $row->PresentCompany ?>
                                                    </td>
                                                    <td class="fw-bold-500" style="text-align: left">Email ID
                                                    </td>
                                                    <td style="text-align: left">
                                                        : {{ $row->Email }} @if ($row->Verified == 'Y')
                                                            <i class="fadeIn animated bx bx-badge-check text-success"></i>
                                                        @endif
                                                    </td>
                                                </tr>
                                                <tr class="">
                                                    <td class="fw-bold-500">Cur. Designation</td>
                                                    <td style="text-align: left">
                                                        : <?= $row->Designation == null ? '' : $row->Designation ?></td>
                                                    <td class="fw-bold-500" style="text-align: left">Education
                                                    </td>
                                                    <td style="text-align: left">
                                                        :
                                                        <?= $row->Education == null ? '' : getEducationCodeById($row->Education) ?>
                                                        <?= $row->Specialization == null ? '' : '-' . getSpecializationbyId($row->Specialization) ?>
                                                    </td>
                                                </tr>
                                                <tr class="">
                                                    <td class="fw-bold-500">Cur. Location</td>
                                                    <td style="text-align: left">: {{ $row->City }}</td>
                                                    <td class="fw-bold-500">Apply Date</td>
                                                    <td style="text-align: left">
                                                        : {{ date('d-m-Y', strtotime($row->ApplyDate)) }}</td>
                                                </tr>
                                                <tr>


                                                </tr>
                                                <tr>
                                                    <td class="fw-bold-500"> Source</td>
                                                    <td style="text-align: left">
                                                        : {{ getResumeSourceById($row->ResumeSource) }}
                                                    </td>


                                                </tr>
                                                @if ($row->BlackListRemark != null)
                                                    <tr>
                                                        <td colspan="4" class="text-danger fw-bold">
                                                            {{ $row->BlackListRemark }}
                                                        </td>
                                                    </tr>
                                                @endif
                                                @if ($row->UnBlockRemark != null)
                                                    <tr>
                                                        <td colspan="4" class="text-success fw-bold">
                                                            {{ $row->UnBlockRemark }}
                                                        </td>
                                                    </tr>
                                                @endif
                                                @if ($row->hr_screening_status !== null)
                                                    <tr>
                                                        <td class="fw-bold-500">HR Screening Status</td>
                                                        <td style="text-align: left"> :
                                                            &nbsp;{{ $row->hr_screening_status }}</td>
                                                        <td class="fw-bold-500">HR Screening By</td>
                                                        <td style="text-align: left">:
                                                            &nbsp;{{ getFullName($row->hr_screening_by) }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fw-bold-500">HR Screening Remark</td>
                                                        <td style="text-align: left;" colspan="3"> :
                                                            &nbsp;{{ $row->hr_screening_remark }}</td>
                                                    </tr>
                                                @endif

                                                {{-- @if ($row->Status == 'Rejected')
                                                 <tr>
                                                     <td class="fw-bold-500">Rejected Reason:</td>
                                                     <td class="fw-bold">{{ $row->RejectRemark }}</td>
                                                 </tr>
                                             @endif --}}
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="" style=" width: 20%;float: left;">

                                        <center>
                                            <small>
                                                <span class="text-primary m-1 " style="cursor: pointer; font-size:14px;">
                                                    @php
                                                        $sendingId = base64_encode($row->JAId);
                                                    @endphp
                                                    <a href="{{ route('candidate_detail') }}?jaid={{ $sendingId }}"
                                                        target="_blank">View Details</a>
                                                </span>
                                            </small>
                                        </center>


                                    </div>

                                </div>
                            </div>
                            @if ($row->manual_entry_by != null)
                                <div class="card-footer border-0 p-1 bg-light-warning text-dark">
                                    <div class="row " style="float: right;margin-right: 20px;">
                                        Manual Entry By: {{ $row->manual_entry_by_name }}
                                    </div>
                                </div>
                            @endif

                        </div>
                    @endforeach

                </div>

                {{ $candidate_list->appends([])->links('vendor.pagination.custom') }}
            </div>

            <div class="col-3 www">
                <div class="card border-top border-0 border-4 border-danger">
                    <div class="card-body">
                        <div class="col-12 mb-2 d-flex justify-content-between">
                            <span class="d-inline fw-bold">Filter</span>
                            <span class="text-danger fw-bold" style="font-size: 14px; cursor: pointer;" id="reset"><i
                                    class="bx bx-refresh"></i>Reset</span>
                        </div>
                        <div class="col-12 mb-3">
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
                        <div class="col-12 mb-3">

                            <select name="Fill_Department" id="Fill_Department" class="form-select form-select-sm"
                                onchange="GetApplications();">
                                <option value="">Select Department</option>
                            </select>
                            @if (isset($_REQUEST['Department']) && $_REQUEST['Department'] != '')
                                <script>
                                    $('#Fill_Department').val('<?= $_REQUEST['Department'] ?>');
                                </script>
                            @endif
                        </div>
                        <div class="col-12 mb-3">
                            <select name="Fill_JobCode" id="Fill_JobCode" class="form-select form-select-sm select2"
                                onchange="GetApplications();">
                                <option value="">Select JobCode</option>
                                @foreach ($jobpost_list as $item)
                                    <option value="{{ $item->JPId }}">{{ $item->JobCode }}</option>
                                @endforeach
                            </select>
                            @if (isset($_REQUEST['JobCode']) && $_REQUEST['JobCode'] != '')
                                <script>
                                    $('#Fill_JobCode').val('<?= $_REQUEST['JobCode'] ?>');
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
                            <select name="Source" id="Source" class="form-select form-select-sm"
                                onchange="GetApplications();">
                                <option value="">Select Source</option>
                                @foreach ($source_list as $key => $value)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                            @if (isset($_REQUEST['Source']) && $_REQUEST['Source'] != '')
                                <script>
                                    $('#Source').val('<?= $_REQUEST['Source'] ?>');
                                </script>
                            @endif
                        </div>
                        <div class="col-12 mb-3">
                            <select name="Fill_Gender" id="Fill_Gender" class="form-select form-select-sm"
                                onchange="GetApplications();">
                                <option value="">Select Gender</option>
                                <option value="M">Male</option>
                                <option value="F">Female</option>
                                <option value="O">Others</option>
                            </select>
                            @if (isset($_REQUEST['Gender']) && $_REQUEST['Gender'] != '')
                                <script>
                                    $('#Fill_Gender').val('<?= $_REQUEST['Gender'] ?>');
                                </script>
                            @endif
                        </div>
                        <div class="col-12 mb-3">
                            <select name="Education" id="Education" class="form-select form-select-sm"
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
                            <select name="State" id="State" class="form-select form-select-sm"
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
                            <select name="ManualEntry" id="ManualEntry" class="form-select form-select-sm"
                                onchange="GetApplications();">
                                <option value="">Select Manual Entry</option>
                                <option value="1">Yes</option>
                                <option value="0">No</option>
                            </select>
                            @if (isset($_REQUEST['ManualEntry']) && $_REQUEST['ManualEntry'] != '')
                                <script>
                                    $('#ManualEntry').val('<?= $_REQUEST['ManualEntry'] ?>');
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
                            <input type="email" name="Email1" id="Email1" class="form-control form-control-sm"
                                placeholder="Search by Email" onkeyup="GetApplications();">
                        </div>
                        @if (isset($_REQUEST['Email1']) && $_REQUEST['Email1'] != '')
                            <script>
                                $('#Email').val('<?= $_REQUEST['Email'] ?>');
                            </script>
                        @endif
                        <div class="col-12 mb-3">
                            <input type="text" name="Phone1" id="Phone1" class="form-control form-control-sm"
                                placeholder="Search by Phone" onkeyup="GetApplications();">
                        </div>
                        @if (isset($_REQUEST['Phone1']) && $_REQUEST['Phone1'] != '')
                            <script>
                                $('#Phone').val('<?= $_REQUEST['Phone1'] ?>');
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
    </div>


    <div class="modal fade" id="AddJobPostModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static"
        data-bs-keyboard="false">
        <div class="modal-dialog">
            <form action="{{ route('MapCandidateToJob') }}" method="POST" id="MapCandidateForm">
                <div class="modal-content">
                    <div class="modal-body">
                        <input type="hidden" name="AddJobPost_JAId" id="AddJobPost_JAId">
                        <label for="Status">Map Candidate to Job</label>
                        <select name="JPId" id="JPId" class="form-select form-select-sm">
                            <option value="">Select</option>
                            @foreach ($jobpost_list as $item)
                                <option value="{{ $item->JPId }}">{{ $item->JobCode }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary btn-sm">Save changes</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="TechScreeningModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static"
        data-bs-keyboard="false">
        <div class="modal-dialog modal-lg">

            <div class="modal-content">
                <div class="modal-body">
                    <table class="table table-bordered">
                        <tr>
                            <td style="vertical-align: middle;width: 40%;">Resume Sent For Technical Screen</td>
                            <td><input type="date" id="ResumeSent" class="form-control" value="<?php echo date('Y-m-d'); ?>">
                            </td>
                        </tr>
                        <tr>
                            <td style="vertical-align: middle;">Technical Screening By</td>
                            <td>
                                <select id="TechScrCompany" class="form-select form-select-sm mb-1">
                                    <option value="">Select Company</option>
                                    @foreach ($company_list as $key => $value)
                                        <option value="{{ $key }}">{{ $value }}</option>
                                    @endforeach
                                </select>

                                <select id="TechScrDepartment" class="form-select form-select-sm mb-1">
                                    <option value="">Select Department</option>
                                </select>
                                <select id="ScreeningBy" name="ScreeningBy[]" class="form-select form-select-sm"
                                    multiple>
                                    <option value="">Select Employee</option>
                                </select>
                            </td>
                        </tr>
                    </table>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal">Close</button>
                        <button type="button" id="SendForTechSceenBtn" class="btn btn-primary btn-sm">Save
                            changes
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="application_form_modal" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog  modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-info bg-gradient">
                    <h5 class="modal-title text-white">Job Application Form (Manual Entry)</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('job_application_manual') }}" method="POST" id="jobapplicationform">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-9 col-sm-12 table-responsive">
                                <table class=" table borderless d-inline-block">
                                    <tr>
                                        <td valign="middle">
                                            <b>Application Date</b>
                                            <font color="#FF0000">*</font>
                                        </td>
                                        <td>
                                            <input type="datetime-local" name="ApplyDate" id="ApplyDate"
                                                class="form-control form-control-sm reqinp">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td valign="middle"><b>Source of Resume</b>
                                            <font color="#FF0000">*
                                            </font>
                                        </td>
                                        <td>
                                            <select name="ResumeSource" id="ResumeSource"
                                                class="form-select form-select-sm reqinp"
                                                onchange="checkResumeSource(this.value);">
                                                <option value="">Select</option>
                                                @foreach ($resume_list as $key => $value)
                                                    <option value="{{ $key }}">{{ $value }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                    </tr>
                                    <tr id="othersource_tr" class="d-none">
                                        <td></td>
                                        <td>
                                            <textarea name="OtherResumeSource" id="OtherResumeSource" cols="30" rows="3"
                                                class="form-control form-control-sm"
                                                placeholder="Please provide Name & Contact nos. of Person, if came through any referral or Consultancy"></textarea>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td valign="middle"><b>Experience Level</b>
                                            <font color="#FF0000">*
                                            </font>
                                        </td>
                                        <td>
                                            <select name="Professional" id="Professional"
                                                class="form-select form-select-sm">
                                                <option value="F">Fresher</option>
                                                <option value="P">Experienced</option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td valign="middle" style="width: 150px !important"><b>Title</b>
                                            <font color="#FF0000">*
                                            </font>
                                        </td>
                                        <td style="width:800px !important">
                                            <label><input type="radio" name="Title" value="Mr." class="reqinp"
                                                    checked>
                                                Mr.</label>&emsp;
                                            <label><input type="radio" name="Title" value="Ms.">
                                                Ms.</label>&emsp;
                                            <label><input type="radio" name="Title" value="Mrs.">
                                                Mrs.</label>&emsp;
                                            <label><input type="radio" name="Title" value="Dr.">
                                                Dr.</label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td valign="middle" style="width: 300px;"><b>First Name</b>
                                            <font color="#FF0000">*</font>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control form-control-sm reqinp"
                                                name="FName" id="FName" onblur="return convertCase(this)"
                                                onkeypress="return isLetterKey(event)">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td valign="middle"><b>Middle Name</b></td>
                                        <td>
                                            <input type="text" class="form-control form-control-sm" name="MName"
                                                onblur="return convertCase(this)" onkeypress="return isLetterKey(event)">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td valign="middle"><b>Last Name</b>

                                        </td>
                                        <td>
                                            <input type="text" class="form-control form-control-sm" name="LName"
                                                onblur="return convertCase(this)" onkeypress="return isLetterKey(event)">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td valign="middle"><b>Gender</b>
                                            <font color="#FF0000">*
                                            </font>
                                        </td>
                                        <td>
                                            <select name="Gender" id="Gender"
                                                class="form-select form-select-sm reqinp">
                                                <option value="">Select</option>
                                                <option value="M">Male</option>
                                                <option value="F">Female</option>
                                                <option value="O">Other</option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td valign="middle"><b>Father's Name</b>
                                            <font color="#FF0000">*
                                            </font>
                                        </td>
                                        <td>
                                            <table style="width: 100%">
                                                <tr>
                                                    <td>
                                                        <select name="FatherTitle" id="FatherTitle"
                                                            class="form-select form-select-sm d-inline"
                                                            style="width: 80%;">
                                                            <option value="Mr.">Mr.</option>
                                                            <option value="Late">Late</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control form-control-sm reqinp"
                                                            name="FatherName" id="FatherName"
                                                            onblur="return convertCase(this)"
                                                            onkeypress="return isLetterKey(event)">
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td valign="middle"><b>Email ID</b>
                                            <font color="#FF0000">*
                                            </font>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control form-control-sm reqinp"
                                                name="Email" id="Email">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td valign="middle"><b>Phone No.</b>
                                            <font color="#FF0000">*
                                            </font>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control form-control-sm reqinp"
                                                name="Phone" id="Phone" onkeypress="return isNumberKey(event)"
                                                maxlength="10">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td valign="middle"><b>Nationality</b>
                                            <font color="#FF0000">*</font>
                                        </td>
                                        <td>
                                            <select name="Nationality" id="Nationality"
                                                class="form-select form-select-sm">
                                                <option value="">Select</option>
                                                @foreach ($country_list as $key => $value)
                                                    <option value="{{ $key }}"
                                                        {{ session('Set_Country') == $key ? 'selected' : '' }}>
                                                        {{ $value }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td valign="middle"><b>Aadhaar No. / NID No</b></td>
                                        <td>
                                            <input type="text" name="Aadhaar" id="Aadhaar" maxlength="12"
                                                onkeypress="return isNumberKey(event)"
                                                class="form-control form-control-sm">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><b>Upload Resume</b></td>
                                        <td><input type="file" name="Resume" id="Resume"
                                                class="form-control form-control-sm reqinp" accept=".pdf,.docx">
                                            <p class="text-primary">Plese upload PDF/Word Document
                                                Only.</p>
                                        </td>
                                    </tr>
                                </table>

                            </div>
                            <div class="col-lg-3 col-sm-12">
                                <div style="border: 1px solid #195999;vertical-align:top" class=" mt-3 d-inline-block"
                                    style="width: 150; height: 150;">
                                    <span id="preview">
                                        <center>
                                            <img src="{{ URL::to('/') }}/assets/images/user.png"
                                                style="width: 150px; height: 150px;" id="img1" />
                                        </center>
                                    </span>
                                    <center>

                                        <label>
                                            <input type="file" name="CandidateImage" id="CandidateImage"
                                                class="btn btn-sm mb-1 " style="width: 100px;display: none;"
                                                accept="image/png, image/gif, image/jpeg"><span
                                                class="btn btn-sm btn-light shadow-sm text-primary">Upload
                                                photo</span>
                                        </label>

                                    </center>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="SaveApplication">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="imagemodal" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true"
        data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <img src="" class="imagepreview" style="width: 100%;">
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="import_modal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static"
        data-bs-keyboard="false">
        <div class="modal-dialog modal-xl">
            <form action="{{ route('import.cv') }}" method="POST" enctype="multipart/form-data" id="importForm"
                style="width: 61%;margin: 0 auto;">
                @csrf
                <div class="modal-content">
                    <div class="modal-header bg-info bg-gradient">
                        <h5 class="modal-title text-white">Import CV</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <a href="{{ URL('/') }}/assets/cv_import.xlsx" download
                            class="float-end btn btn-info btn-sm" style="font-size: 15px;">Excel Import
                            Format</a><br><br>
                        <div id="error_msg" class="text-danger d-none"></div>

                        <p class="fw-bold">
                            Note: For Experience Level , P - Excerienced , F - Fresher
                        </p>

                        <p class="fw-bold">
                            Note: For Resume Source ,
                        <ol>
                            <li>1 - Company Careers Site</li>
                            <li>2 - Naukri.Com</li>
                            <li>3 - LinkedIn</li>
                            <li>4 - Walk-in</li>
                            <li>5 - Reference from VNR Employee</li>
                            <li>6 - Placement Agencies</li>
                            <li>7 - Campus</li>
                            <li>8 - Others</li>
                        </ol>
                        </p>

                        <input type="file" name="import_file" class="form-control form-control-sm"
                            accept=".xls,.xlsx">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary btn-sm">Import</button>
                    </div>

                </div>
            </form>
        </div>
    </div>
@endsection

@section('script_section')
    <script>
        $(document).ready(function() {
            $("#ScreeningBy").select2({
                placeholder: "Select Screening By",
                allowClear: true,
            });

            $('.pop').on('click', function() {
                $('.imagepreview').attr('src', $(this).find('img').attr('src'));
                $('#imagemodal').modal('show');
            });
            GetDepartment();


            function GetDepartment() {
                var CompanyId = $('#Fill_Company').val();
                $.ajax({
                    type: "GET",
                    url: "{{ route('getDepartment') }}?CompanyId=" + CompanyId,
                    success: function(res) {
                        if (res) {
                            $("#Fill_Department").empty();
                            $("#Fill_Department").append(
                                '<option value="">Select Department</option>');
                            $.each(res, function(key, value) {
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
                var Source = $('#Source').val() || '';
                var Gender = $('#Fill_Gender').val() || '';
                var Education = $('#Education').val() || '';
                var Name = $('#Name').val() || '';
                var Email = $('#Email1').val() || '';
                var Phone = $('#Phone1').val() || '';
                var ManualEntry = $('#ManualEntry').val() || '';
                var State = $('#State').val() || '';
                var City = $('#City').val() || '';
                var JobCode = $('#Fill_JobCode').val() || '';
                window.location.href = "{{ route('job_applications_not_viewed') }}?Company=" + Company +
                    "&Department=" +
                    Department + "&Year=" + Year + "&Month=" + Month + "&Source=" + Source + "&Gender=" + Gender +
                    "&Education=" + Education + "&Name=" + Name + "&Email=" + Email + "&Phone=" + Phone +
                    "&ManualEntry=" + ManualEntry + "&State=" + State + "&City=" + City + "&JobCode=" + JobCode;
            }


            $(document).on('change', '#TechScrCompany', function() {
                var CompanyId = $(this).val();
                $.ajax({
                    type: "GET",
                    url: "{{ route('getDepartment') }}?CompanyId=" + CompanyId,
                    success: function(res) {
                        if (res) {
                            $("#TechScrDepartment").empty();
                            $("#TechScrDepartment").append(
                                '<option value="">Select Department</option>');
                            $.each(res, function(key, value) {
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

            $(document).on('change', '#TechScrDepartment', function() {
                var DepartmentId = $(this).val();
                $.ajax({
                    type: "GET",
                    url: "{{ route('getReportingManager') }}?DepartmentId=" + DepartmentId,
                    success: function(res) {
                        if (res) {
                            $("#ScreeningBy").empty();
                            $("#ScreeningBy").append(
                                '<option value="">Select Department</option>');
                            $.each(res, function(key, value) {
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

            $(document).on('change', '#Fill_Company', function() {
                GetApplications();
            });
            $(document).on('change', '#Fill_Department', function() {
                GetApplications();

            });
            $(document).on('change', '#Fill_JobCode', function() {
                GetApplications();
            });

            $(document).on('change', '#Year', function() {
                GetApplications();
            });
            $(document).on('change', '#Month', function() {
                GetApplications();
            });
            $(document).on('change', '#Source', function() {
                GetApplications();
            });
            $(document).on('change', '#Fill_Gender', function() {
                GetApplications();
            });
            $(document).on('change', '#Education', function() {
                GetApplications();
            });
            $(document).on('blur', '#Name', function() {
                GetApplications();
            });

            $(document).on('blur', '#Email1', function() {
                GetApplications();
            });

            $(document).on('blur', '#Phone1', function() {
                GetApplications();
            });

            $(document).on('blur', '#City', function() {
                GetApplications();
            });

            $(document).on('change', '#ManualEntry', function() {
                GetApplications();
            });
            $(document).on('change', '#State', function() {
                GetApplications();
            });

            $(document).on('click', '#reset', function() {
                window.location.href = "{{ route('job_applications_not_viewed') }}";
            });


            $(document).on('click', '#AddToJobPost', function() {
                var JAId = $(this).data('id');
                $('#AddJobPost_JAId').val(JAId);
                $('#AddJobPostModal').modal('show');
            });


            $('#MapCandidateForm').on('submit', function(e) {
                e.preventDefault();
                var form = this;
                $.ajax({
                    url: $(form).attr('action'),
                    method: $(form).attr('method'),
                    data: new FormData(form),
                    processData: false,
                    dataType: 'json',
                    contentType: false,
                    success: function(data) {
                        if (data.status == 400) {
                            toastr.error(data.msg);
                        } else {
                            toastr.success(data.msg);
                            window.location.reload();
                        }
                    }
                });
            });


            /*  $(document).on('change', '#Status', function() {
                 var Status = $(this).val();
                 if (Status == 'Rejected') {
                     $('#RejectRemark').removeClass('d-none');
                     $("#RejectRemark").prop('required', true);
                 } else {
                     $('#RejectRemark').addClass('d-none');
                     $("#RejectRemark").prop('required', false);
                 }
             }); */


            $(document).on('change', '#CandidateImage', function(e) {
                const [file] = e.target.files;
                if (file) {
                    img1.src = URL.createObjectURL(file);
                }
            });

            $('#Phone').focusout(function() {
                var count = $(this).val().length;
                if (count != 10) {
                    alert('Phone number should be of 10 digits');
                    $(this).addClass('errorfield');
                } else {
                    $(this).removeClass('errorfield');
                }
            });

            $('#Aadhaar').focusout(function() {
                var count = $(this).val().length;
                if (count != 12) {
                    alert('Aadhaar Number should be of 12 digits');
                    $(this).addClass('errorfield');
                } else {
                    $(this).removeClass('errorfield');
                }
            });
        });


        $(document).on('click', '#SendForTechSceenBtn', function() {
            var JAId = [];
            var ScreeningBy = [];
            //var ScreeningBy = $('#ScreeningBy').val();
            var ResumeSent = $('#ResumeSent').val();
            $("input[name='selectCand']").each(function() {
                if ($(this).prop("checked") == true) {
                    var value = $(this).val();
                    JAId.push(value);
                }
            });

            // Loop through the selected "ScreeningBy" options and collect their values
            $("#ScreeningBy option:selected").each(function() {
                var value = $(this).val();
                ScreeningBy.push(value);
            });
            if (JAId.length > 0) {
                if (confirm('Are you sure to Send Selected Candidates to Screening Stage?')) {
                    $.ajax({
                        url: '{{ url('SendForTechScreening') }}',
                        method: 'POST',
                        data: {
                            JAId: JAId,
                            ScreeningBy: ScreeningBy,
                            ResumeSent: ResumeSent
                        },
                        success: function(data) {
                            if (data.status == 400) {
                                toastr.error('Something went wrong..!!');
                            } else {
                                toastr.success(data.msg);
                                window.location.reload();
                            }
                        }
                    });
                }

            } else {
                alert('No Candidate Selected!\nPlease select atleast one candidate to proceed.');
            }

        });

        $('#checkall').click(function() {
            if ($(this).prop("checked") == true) {
                $('.japchks').prop("checked", true);
            } else if ($(this).prop("checked") == false) {
                $('.japchks').prop("checked", false);
            }
        });

        function checkAllorNot() {
            var allchk = 1;
            $('.japchks').each(function() {
                if ($(this).prop("checked") == false) {
                    allchk = 0;
                }
            });
            if (allchk == 0) {
                $('#checkall').prop("checked", false);
            } else if (allchk == 1) {
                $('#checkall').prop("checked", true);
            }
        }

        //======================================================//
        function isNumberKey(evt) {
            var charCode = (evt.which) ? evt.which : event.keyCode
            if (charCode > 31 && (charCode < 48 || charCode > 57))
                return false;

            return true;
        }

        function convertCase(evt) {
            var text = $(evt).val();
            $(evt).val(camelCase(text));
        }

        function camelCase(str) {
            return str.replace(/(?:^|\s)\w/g, function(match) {

                return match.toUpperCase();
            });
        }

        function isLetterKey(evt) {
            var charCode = (evt.which) ? evt.which : event.keyCode;
            if ((charCode < 65 || charCode > 90) && (charCode < 97 || charCode > 122) && charCode !== 32) {
                return false;
            }
            return true;
        }

        function checkRequired() {
            var res = 0;
            $('.reqinp').each(function() {
                if ($(this).val() == '' || $(this).val() == null) {
                    $(this).addClass('errorfield');
                    res = 1;
                } else {
                    $(this).removeClass('errorfield');
                }
            });
            return res;
        }


        function checkResumeSource(id) {

            if (id == 5 || id == 6 || id == 8) {
                $('#othersource_tr').removeClass('d-none');
            } else {
                $('#othersource_tr').addClass('d-none');
            }

        }

        $('#jobapplicationform').on('submit', function(e) {
            e.preventDefault();
            var form = this;
            var reqcond = checkRequired();
            if (reqcond == 1) {
                alert('Please fill required field...!');
            } else {
                $.ajax({
                    url: $(form).attr('action'),
                    method: $(form).attr('method'),
                    data: new FormData(form),
                    processData: false,
                    dataType: 'json',
                    contentType: false,
                    beforeSend: function() {
                        $(form).find('span.error-text').text('');
                        $("#loader").modal('show');
                    },
                    success: function(data) {
                        if (data.status == 400) {
                            $("#loader").modal('hide');
                            toastr.error(data.msg);
                            $.each(data.error, function(prefix, val) {
                                $(form).find('span.' + prefix + '_error').text(val[0]);
                            });
                        } else {
                            $(form)[0].reset();
                            $('#loader').modal('hide');
                            toastr.success(data.msg);
                            window.location.reload();
                        }
                    }
                });
            }

        });


        $('#Phone').focusout(function() {
            var count = $(this).val().length;
            if (count != 10) {
                alert('Phone number should be of 10 digits');
                $(this).addClass('errorfield');
            } else {
                $(this).removeClass('errorfield');
            }
        });

        $('#Aadhaar').focusout(function() {
            var count = $(this).val().length;
            if (count != 12) {
                alert('Aadhaar Number should be of 12 digits');
                $(this).addClass('errorfield');
            } else {
                $(this).removeClass('errorfield');
            }
        });
        $('#importForm').submit(function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            $.ajax({
                url: $(this).attr('action'),
                type: $(this).attr('method'),
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: function() {
                    $("#loader").css('display', 'block');
                },
                success: function(response) {
                    if (response.status == 200) {
                        $("#loader").css('display', 'none');
                        toastr.success(response.message);
                        window.location.reload();
                    } else {
                        $("#loader").css('display', 'none');

                        var errorMessages = JSON.parse(response.error);
                        var errorHTML = "";
                        for (var rowNumber in errorMessages) {
                            if (errorMessages.hasOwnProperty(rowNumber)) {
                                var rowErrors = errorMessages[rowNumber];
                                var rowErrorMessage = "<b>Row " + rowNumber + " errors:</b><br>";
                                rowErrors.forEach(function(errorMessage) {
                                    rowErrorMessage += errorMessage + "<br>";
                                });
                                errorHTML += rowErrorMessage;
                            }
                        }
                        $("#error_msg").removeClass("d-none").html(errorHTML);
                    }
                },
                error: function(xhr, textStatus, errorThrown) {
                    console.log(xhr.responseText);
                }
            });
        });


        $("#bulk_download_cv").click(function() {
            var params = {
                Company: $('#Fill_Company').val() || '',
                Department: $('#Fill_Department').val() || '',
                Year: $('#Year').val() || '',
                Month: $('#Month').val() || '',
                Source: $('#Source').val() || '',
                Gender: $('#Fill_Gender').val() || '',
                Education: $('#Education').val() || '',
                Name: $('#Name').val() || '',
                Email: $('#Email1').val() || '',
                Phone: $('#Phone1').val() || '',
                ManualEntry: $('#ManualEntry').val() || '',
                State: $('#State').val() || '',
                City: $('#City').val() || '',
                JobCode: $('#Fill_JobCode').val() || ''
            };

            var queryString = $.param(params);
            window.location.href = "{{ route('download_bulk_cv') }}?" + queryString;
        });
    </script>
@endsection
