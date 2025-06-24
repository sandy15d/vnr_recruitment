@extends('layouts.master')
@section('title', 'MRF Allocated')
@section('PageContent')
    <style>
        .table> :not(caption)>*>* {
            padding: 2px 1px;
        }
    </style>
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb align-items-center mb-3">


            <div class="row">
                <div class="breadcrumb-title pe-3 download_label col-3">Allocated MRF Details</div>
                <div class="col-2">
                    <select name="Fill_Company" id="Fill_Company" class="form-select form-select-sm"
                        onchange="GetAllocatedMrf(); GetDepartment();">
                        <option value="">Select Company</option>
                        @foreach ($company_list as $key => $value)
                            <option value="{{ $key }}">{{ $value }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-2">

                    <select name="Fill_Department" id="Fill_Department" class="form-select form-select-sm"
                        onchange="GetAllocatedMrf();">
                        <option value="">Select Department</option>

                    </select>
                </div>
                <div class="col-2">
                    <select name="Year" id="Year" class="form-select form-select-sm" onchange="GetAllocatedMrf();">
                        <option value="">Select Year</option>
                        @for ($i = 2021; $i <= date('Y'); $i++)
                            <option value="{{ $i }}">{{ $i }}</option>
                        @endfor
                    </select>
                </div>
                <div class="col-2">
                    <select name="Month" id="Month" class="form-select form-select-sm" onchange="GetAllocatedMrf();">
                        <option value="">Select Month</option>
                        @foreach ($months as $key => $value)
                            <option value="{{ $key }}">{{ $value }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col">
                    <button type="reset" class="btn btn-danger btn-sm" id="reset"><i
                            class="bx bx-refresh"></i></button>
                </div>
            </div>

        </div>
        <!--end breadcrumb-->
        <hr />
        <div class="card">
            <div class="card-body">
                <div class="row mb-1">
                    <div class="col-6">
                        <button type="button" class="btn btn-primary btn-sm" id="openMrf" data-status='Open'>Open MRF
                            <span class="badge bg-warning text-dark" style="font-size: 10px;">{{ $OpenMRF }}</span>
                        </button>
                        <button class="btn btn-outline-primary btn-sm pull-right" data-status='Close' id="closedMrf">
                            Closed
                            MRF <span class="badge bg-warning text-dark"
                                style="font-size: 10px;">{{ $CloseMRF }}</span></button>
                    </div>


                </div>
                <hr />
                <div>
                    <table class="table  table-hover table-striped table-condensed align-middle text-center table-bordered"
                        id="MRFTable" style="width: 100%">
                        <thead class="text-center bg-primary text-light">
                            <tr class="text-center">
                                <td></td>
                                <td class="th-sm">S.No</td>
                                <td>MRF Date</td>
                                <td>Type</td>
                                <td>JobCode</td>
                                <th>Job Post Title</th>
                                <td>Department</td>
                                <td>Designation</td>
                                <td>Position</td>
                                <td>Location</td>
                                <td>Job Posting</td>
                                <td>View on Site</td>
                                <td>Position Filled</td>
                                <td>Close Date</td>
                                <td>Action</td>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- ======================UPdate Modal======================= -->
 {{--    <div class="modal fade" id="updatepostmodal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static"
        data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-info bg-gradient">
                    <h5 class="modal-title text-white">Create Job Post</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('updateJobPost') }}" method="POST" id="updateJobPostForm">
                    @csrf
                    <div class="modal-body">
                        <table class="table borderless">
                            <tbody>
                                <tr>
                                    <th>Job Post Title <span class="text-danger">*</span></th>
                                    <td>
                                        <input type="text" name="jobTitle" id="jobTitle"
                                            class="form-control form-control-sm" required>
                                    </td>
                                </tr>
                                <tr>
                                    <th style="width:250px;">Designation<span class="text-danger">*
                                        </span>
                                    </th>
                                    <td>
                                        <input type="hidden" name="MRFId" id="MRFId">
                                        <input type="text" name="Designation" id="Designation"
                                            class="form-control form-control-sm" readonly>
                                        <span class="text-danger error-text Designation_error"></span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Job Code <span class="text-danger">*</span>
                                    </th>
                                    <td>
                                        <input type="text" name="JobCode" id="JobCode"
                                            class="form-control form-control-sm" readonly>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Job Description</th>
                                    <td>
                                        <textarea name="JobInfo" id="JobInfo" class="JobInfo"></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Mandatory Requirements</th>
                                    <td>
                                        <table class="table borderless" style="margin-bottom: 0px;">
                                            <tbody id="MulKP">
                                            </tbody>
                                        </table>
                                        <button type="button" name="add" id="addKP"
                                            class="btn btn-warning btn-sm mb-2 mt-2"><i class="bx bx-plus"></i></button>
                                    </td>
                                </tr>

                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="updateJobPost">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div> --}}
    <!-- ============================================= -->


    <div class="modal fade" id="createpostmodal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static"
        data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-info bg-gradient">
                    <h5 class="modal-title text-white">Create Job Post</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('createJobPost') }}" method="POST" id="createJobPostForm">
                    @csrf
                    <div class="modal-body">
                        <table class="table borderless">
                            <tbody>
                                <tr>
                                    <th>Job Post Title <span class="text-danger">*</span></th>
                                    <td>
                                        <input type="text" name="jobTitle" id="jobTitle"
                                            class="form-control form-control-sm" required>
                                    </td>
                                </tr>
                                <tr>
                                    <th style="width:250px;">Designation<span class="text-danger">*
                                        </span>
                                    </th>
                                    <td>
                                        <input type="hidden" name="MRFId" id="MRFId">
                                        <input type="text" name="Designation" id="Designation"
                                            class="form-control form-control-sm" readonly>
                                        <span class="text-danger error-text Designation_error"></span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Job Code <span class="text-danger">*</span>
                                    </th>
                                    <td>
                                        <input type="text" name="JobCode" id="JobCode"
                                            class="form-control form-control-sm" readonly>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Job Description</th>
                                    <td>
                                        <textarea name="JobInfo" id="JobInfo" class="JobInfo"></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Mandatory Requirements</th>
                                    <td>
                                        <table class="table borderless" style="margin-bottom: 0px;">
                                            <tbody id="MulKP">
                                            </tbody>
                                        </table>
                                        <button type="button" name="add" id="addKP"
                                            class="btn btn-warning btn-sm mb-2 mt-2"><i class="bx bx-plus"></i></button>
                                    </td>
                                </tr>

                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="CreateJobPost">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editMRFModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static"
        data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-info bg-gradient">
                    <h5 class="modal-title text-white">MRF Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('updateMRF') }}" method="POST" id="update_mrf_form">
                    @csrf
                    <div class="modal-body">
                        <table class="table borderless">
                            <tbody>
                                <tr>
                                    <input type="hidden" name="MRFId" id="MRFId">
                                    <input type="hidden" name="MRF_Type" id="MRF_Type">
                                    <th style="width:250px;">Reason for Creating New Position<span class="text-danger">*
                                        </span>
                                    </th>
                                    <td>
                                        <textarea class="form-control" rows="1" name="Reason" id="Reason" tabindex="1" autofocus></textarea>
                                        <span class="text-danger error-text Reason_error"></span>

                                    </td>
                                </tr>
                                <tr>
                                    <th>Company<span class="text-danger">*</span>
                                    </th>
                                    <td><select id="Company" name="Company"
                                            class="form-control form-select form-select-sm">
                                            <option value="" selected disabled>Select Company</option>
                                            @foreach ($company_list as $key => $value)
                                                <option value="{{ $key }}">{{ $value }}</option>
                                            @endforeach
                                        </select>
                                        <span class="text-danger error-text Company_error"></span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Department<span class="text-danger">*</span>
                                    </th>
                                    <td>
                                        <div class="spinner-border text-primary d-none" role="status" id="DeptLoader">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                        <select id="Department" name="Department" id="Department"
                                            class="form-control form-select form-select-sm">
                                            <option value="" selected disabled>Select Department</option>
                                            @foreach ($department_list as $key => $value)
                                                <option value="{{ $key }}">{{ $value }}</option>
                                            @endforeach
                                        </select>
                                        <span class="text-danger error-text Department_error"></span>
                                    </td>
                                </tr>
                                <tr id="deisgnation_tr" class="d-none">
                                    <th>Designation<span class="text-danger">*</span>
                                    </th>
                                    <td>
                                        <div class="spinner-border text-primary d-none" role="status" id="DesigLoader">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                        <select id="editDesignation" name="editDesignation"
                                            class="form-control form-select form-select-sm">
                                            <option value="" selected disabled>Select Designation</option>
                                            @foreach ($designation_list as $key => $value)
                                                <option value="{{ $key }}">{{ $value }}</option>
                                            @endforeach
                                        </select>
                                        <span class="text-danger error-text Designation_error"></span>
                                    </td>
                                </tr>

                                <tr>
                                    <th>Location & Man Power <span class="text-danger">*</span>
                                    </th>
                                    <td>
                                        <table class="table borderless" style="margin-bottom: 0px;">
                                            <tbody id="MulLocation">
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                                <tr id="ctc_tr">
                                    <th>Desired CTC (in Rs.) <span class="text-danger">*</span>
                                    </th>
                                    <td>
                                        <table class="table borderless" style="margin-bottom: 0px;">
                                            <tr>
                                                <td><input type="text" name="MinCTC" id="MinCTC"
                                                        class="form-control form-control-sm" placeholder="Min"></td>
                                                <td><input type="text" name="MaxCTC" id="MaxCTC"
                                                        class="form-control form-control-sm" placeholder="Max"></td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr id="stipend_tr">
                                    <th>Desired Stipend (in Rs. Per Month) <span class="text-danger">*</span>
                                    </th>
                                    <td>
                                        <input type="text" name="Stipend" id="Stipend"
                                            class="form-control form-control-sm">
                                    </td>
                                </tr>
                                <tr id="other_benifit_tr">
                                    <th>Other Benefits</th>
                                    <td>
                                        <table class="table borderless" style="margin-bottom: 0px;">
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input " type="checkbox"
                                                                id="two_wheeler_check">
                                                            <label class="form-check-label" for="two_wheeler_check">2
                                                                Wheeler reimbursement Rs.
                                                            </label>
                                                        </div>
                                                        <div class="form-check form-check-inline d-none"
                                                            id="two_wheeler_div">
                                                            <input type="text" name="two_wheeler" id="two_wheeler"
                                                                style="border-radius: .2rem; border:1px solid #ced4da; padding:.25rem">
                                                            per
                                                            km
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div class="form-check form-check-inline" style="width: 200px;">
                                                            <input class="form-check-input " type="checkbox"
                                                                id="da_check">
                                                            <label class="form-check-label" for="da_check">DA
                                                            </label>
                                                        </div>
                                                        <div class="form-check form-check-inline d-none" id="da_div">
                                                            <input type="text" name="da" id="da"
                                                                style="border-radius: .2rem; border:1px solid #ced4da; padding:.25rem">
                                                            Rs. per
                                                            Day
                                                        </div>
                                                    </td>
                                                </tr>

                                            </tbody>
                                        </table>

                                    </td>
                                </tr>


                                <tr>
                                    <th>Desired Eductaion
                                    </th>
                                    <td>
                                        <table class="table borderless" style="margin-bottom: 0px;">
                                            <tbody id="MulEducation">
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>

                                <tr>
                                    <th>Desired University/College</th>
                                    <td>
                                        <select name="University[]" id="University"
                                            class="form-control form-select form-select-sm multiple-select"
                                            multiple="multiple">

                                            @foreach ($institute_list as $key => $value)
                                                <option value="{{ $key }}">{{ $value }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>
                                <tr id="work_exp_tr">
                                    <th>Work Experience <span class="text-danger">*</span>
                                    </th>
                                    <td>
                                        <input type="text" name="WorkExp" id="WorkExp"
                                            class="form-control form-control-sm">
                                    </td>
                                </tr>
                                <tr>
                                    <th>Job Description</th>
                                    <td>
                                        <textarea name="editJobInfo" id="editJobInfo" class="form-control"></textarea>
                                    </td>
                                </tr>
                                <tr id="duration_tr">
                                    <th>Training Duration</th>
                                    <td>
                                        <table class="table borderless" style="margin-bottom: 0px;">
                                            <tbody>
                                                <tr>
                                                    <td valign="middle">From</td>
                                                    <td>
                                                        <input type="date" name="Tr_Frm_Date" id="Tr_Frm_Date"
                                                            class="form-control form-control-sm">
                                                        <span class="text-danger error-text Tr_Frm_Date_error"></span>
                                                    </td>
                                                    <td valign="middle">To</td>
                                                    <td>
                                                        <input type="date" name="Tr_To_Date" id="Tr_To_Date"
                                                            class="form-control form-control-sm">
                                                        <span class="text-danger error-text Tr_To_Date_error"></span>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Mandatory Requirement</th>
                                    <td>

                                        <table class="table borderless" style="margin-bottom: 0px;">
                                            <tbody id="editMulKP">
                                            </tbody>
                                        </table>
                                        <button type="button" name="editadd" id="editaddKP"
                                            class="btn btn-warning btn-sm mb-2 mt-2"><i class="bx bx-plus"></i></button>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Any Other Remark</th>
                                    <td>
                                        <textarea name="Remark" id="Remark" class="form-control"></textarea>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="UpdateMRF">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="closemrfmodal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static"
        data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
                <div class="modal-header bg-info bg-gradient">
                    <h5 class="modal-title text-white">Close MRF</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('close_mrf') }}" method="POST" id="close_mrf_form">
                    @csrf
                    <div class="modal-body">
                        <table class="table borderless">
                            <tbody>
                                <tr>
                                    <input type="hidden" name="MrId" id="MrId">
                                    <td>No. of Candidate Hired</td>
                                    <td><input type="text" id="hired" name="hired"
                                            class="form-control form-control-sm">
                                        <span class="text-danger error-text hired_error"></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Reason to Close MRF</td>
                                    <td>
                                        <textarea name="reason" id="reason" class="form-control form-control-sm"></textarea>
                                        <span class="text-danger error-text reason_error"></span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="Close_MRF">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="mrf_position_modal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static"
        data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-info bg-gradient">
                    <h5 class="modal-title text-white">Position Filled Against MRF : <span id="mrf_detail"
                            style="color: black"></span>
                    </h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <table class="table table-bordered" style="width: 100%">
                        <thead class="bg-light">
                            <tr class="text-center">
                                <th>S.No</th>
                                <th>Date</th>
                                <th>Position Filled</th>
                                <th>Remark</th>
                            </tr>
                        </thead>
                        <tbody id="myTableBody"></tbody>
                    </table>
                    <div class="row">
                        <div class="col-md-3">
                            <input type="hidden" id="position_filled_mrf">
                            <input type="text" class="form-control form-control-sm" id="position_filled"
                                name="position_filled" placeholder="Position Filled">
                        </div>
                        <div class="col-md-6">
                            <input type="text" class="form-control form-control-sm" id="position_filled_remark"
                                name="position_filled_remark" placeholder="Remark">
                        </div>
                        <div class="col-md-3">
                            <button class="btn btn-sm btn-primary" id="save_position_filled">Save</button>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>

                </div>

            </div>
        </div>
    </div>
@endsection
@section('script_section')
    <script>
        CKEDITOR.replace('JobInfo');
        CKEDITOR.replace('editJobInfo');

        var MrfStatus = 'Open';

        $('#MRFTable').DataTable({
            processing: true,
            serverSide: true,
            info: true,
            searching: false,

            lengthChange: false,
            ordering: false,

            ajax: {
                url: "{{ route('getAllAllocatedMRF') }}",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: function(d) {
                    d.Company = $('#Fill_Company').val();
                    d.Department = $('#Fill_Department').val();
                    d.Year = $('#Year').val();
                    d.Month = $('#Month').val();
                    d.MrfStatus = MrfStatus;
                },
                type: 'POST',
                dataType: "JSON",
            },
            columns: [

                {
                    data: 'chk',
                    name: 'chk'
                },
                {
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex'
                },
                {
                    data: 'MRFDate',
                    name: 'MRFDate'
                },
                {
                    data: 'Type',
                    name: 'Type'
                },
                {
                    data: 'JobCode',
                    name: 'JobCode'
                },
                {
                    data: 'Title',
                    name: 'Title'
                },
                {
                    data: 'department_name',
                    name: 'department_name'
                },
                {
                    data: 'designation_name',
                    name: 'designation_name'
                },
                {
                    data: 'Positions',
                    name: 'Positions'
                },
                {
                    data: 'LocationIds',
                    name: 'LocationIds'
                },

                {
                    data: 'JobPost',
                    name: 'JobPost'
                },
                {
                    data: 'JobShow',
                    name: 'JobShow'
                },
                {
                    data: 'position_filled',
                    name: 'position_filled'
                },
                {
                    data: 'CloseDt',
                    name: 'CloseDt'
                },

                {
                    data: 'Action',
                    name: 'Action'
                }
            ],

        });

        function GetAllocatedMrf() {
            $('#MRFTable').DataTable().draw(true);

        }


        $(document).on('click', '#openMrf', function() {
            MrfStatus = 'Open';
            $('#openMrf').removeClass('btn-outline-primary');
            $('#closedMrf').removeClass('btn-primary');
            $('#closedMrf').addClass('btn-outline-primary');
            $('#openMrf').addClass('btn-primary');
            GetAllocatedMrf();
        });

        $(document).on('click', '#closedMrf', function() {

            MrfStatus = 'Close';
            $('#closedMrf').removeClass('btn-outline-primary');
            $('#openMrf').removeClass('btn-primary');
            $('#openMrf').addClass('btn-outline-primary');
            $('#closedMrf').addClass('btn-primary');
            GetAllocatedMrf();
        });

        function GetDepartment() {
            var CompanyId = $('#Fill_Company').val();
            $.ajax({
                type: "GET",
                url: "{{ route('getDepartment') }}?CompanyId=" + CompanyId,
                beforeSend: function() {

                },
                success: function(res) {

                    if (res) {
                        $("#Fill_Department").empty();
                        $("#Fill_Department").append(
                            '<option value="" selected disabled >Select Department</option>');
                        $.each(res, function(key, value) {
                            $("#Fill_Department").append('<option value="' + value + '">' + key +
                                '</option>');
                        });
                    } else {
                        $("#Fill_Department").empty();
                    }
                }
            });
        }


        $(document).on('click', '.select_all', function() {
            if ($(this).prop("checked") == true) {
                $(this).closest("tr").addClass("bg-secondary bg-gradient text-light");
            } else {
                $(this).closest("tr").removeClass("bg-secondary bg-gradient text-light");
            }
        });

        $(document).on('click', '#reset', function() {
            window.location.reload();
        });


        mulKP();

        function mulKP(n) {
            x = '<tr>';
            x += '<td >' +
                '<input type="text" class="form-control form-control-sm" id="KeyPosition' + n + '" name="KeyPosition[]">' +
                '</td>';

            if (n > 1) {
                x +=
                    '<td><button type="button" name="remove" id="" class="btn btn-danger btn-sm  removeKP"><i class="bx bx-x"></td></tr>';
                $('#MulKP').append(x);
            } else {
                x +=
                    '';
                $('#MulKP').html(x);
            }
        }

        $(document).on('click', '#addKP', function() {
            KPCount++;
            mulKP(KPCount);
        });

        $(document).on('click', '.removeKP', function() {
            KPCount--;
            $(this).closest("tr").remove();
        });

        function getDetailForJobPost(MRFId) {
            var MRFId = MRFId;
            $.post('<?= route('getDetailForJobPost') ?>', {
                MRFId: MRFId
            }, function(data) {
                $('#MRFId').val(data.MRFDetails.MRFId);
                $('#jobTitle').val(data.jobTitle);
                $('#Designation').val(data.Designation);
                $('#JobCode').val(data.MRFDetails.JobCode);

                CKEDITOR.instances['JobInfo'].setData(data.MRFDetails.Info);

                KPCount = (data.KPDetails).length;
                var KPValue = data.KPDetails.toString().split(",");
                for (i = 1; i <= KPCount; i++) {
                    mulKP(i);
                    $('#KeyPosition' + i).val(KPValue[i - 1]);
                }

            }, 'json');
        }
        //  =========================update modal ajex===============================

        $('#updateJobPostForm').on('submit', function(e) {
            e.preventDefault();
            var form = this;
            for (instance in CKEDITOR.instances) {
                CKEDITOR.instances[instance].updateElement();
            }
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
                        $.each(data.error, function(prefix, val) {
                            $(form).find('span.' + prefix + '_error').text(val[0]);
                        });
                    } else {
                        $(form)[0].reset();
                        $('#loader').modal('hide');
                        $('#updatepostmodal').modal('hide');
                        $('#MRFTable').DataTable().ajax.reload(null, false);
                        toastr.success(data.msg);
                    }
                }
            });
        });
        //  ========================================================
        $('#createJobPostForm').on('submit', function(e) {
            e.preventDefault();
            var form = this;
            for (instance in CKEDITOR.instances) {
                CKEDITOR.instances[instance].updateElement();
            }
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
                        $.each(data.error, function(prefix, val) {
                            $(form).find('span.' + prefix + '_error').text(val[0]);
                        });
                    } else {
                        $(form)[0].reset();
                        $('#loader').modal('hide');
                        $('#createpostmodal').modal('hide');
                        $('#MRFTable').DataTable().ajax.reload(null, false);
                        toastr.success(data.msg);
                    }
                }
            });
        });


        function editmrf(id) {
            $('#postStatus' + id).prop("disabled", false);
        }

        function ChngPostingView(JPId, va) {

            $.ajax({
                url: "{{ route('ChngPostingView') }}",
                type: 'POST',
                data: {
                    JPId: JPId,
                    va: va
                },
                dataType: 'json',
                beforeSend: function() {
                    $("#loader").modal('show');
                },
                success: function(data) {
                    if (data.status == 200) {
                        $("#loader").modal('hide');
                        $('#MRFTable').DataTable().ajax.reload(null, false);
                        toastr.success(data.msg);
                    } else {
                        toastr.error(data.msg);
                    }
                }
            });
        }


        $(document).on('click', '.select_all', function() {
            if ($(this).prop("checked") == true) {
                $(this).closest("tr").addClass("bg-secondary bg-gradient");
            } else {
                $(this).closest("tr").removeClass("bg-secondary bg-gradient");
            }
        });

        $("#two_wheeler_check").change(function() {
            if (!this.checked) {
                $("#two_wheeler_div").addClass("d-none");
            } else {
                $("#two_wheeler_div").removeClass("d-none");
            }
        });

        $("#da_check").change(function() {
            if (!this.checked) {
                $("#da_div").addClass("d-none");
            } else {
                $("#da_div").removeClass("d-none");
            }
        });

        $(document).on('click', '#viewMRF', function() {
            var MRFId = $(this).data('id');
            $.post('<?= route('getMRFDetails') ?>', {
                MRFId: MRFId
            }, function(data) {
                if (data.MRFDetails.status != 'New') {
                    $('#edit_mrf_btn').addClass('d-none');
                }
                $('#editMRFModal').find('input[name="MRFId"]').val(data.MRFDetails.MRFId);
                $('#MRF_Type').val(data.MRFDetails.Type);
                $('#Reason').val(data.MRFDetails.Reason);
                $('#Company').val(data.MRFDetails.CompanyId);
                $('#Department').val(data.MRFDetails.DepartmentId);
                $('#editDesignation').val(data.MRFDetails.DesigId);
                $('#MinCTC').val(data.MRFDetails.MinCTC);
                $('#MaxCTC').val(data.MRFDetails.MaxCTC);
                $('#MaxCTC').val(data.MRFDetails.MaxCTC);
                $('#Stipend').val(data.MRFDetails.Stipend);
                CKEDITOR.instances['editJobInfo'].setData(data.MRFDetails.Info);
                $('#Remark').val(data.MRFDetails.Remarks);
                $('#WorkExp').val(data.MRFDetails.WorkExp);
                var UniversityValue = data.UniversityDetails;
                var selectedOptions = UniversityValue.toString().split(",");

                $('#University').select2({
                    multiple: true,
                });
                $('#University').val(selectedOptions).trigger('change');

                editKPCount = (data.KPDetails).length;
                var editKPValue = data.KPDetails.toString().split(",");
                for (i = 1; i <= editKPCount; i++) {
                    editmulKP(i);
                    $('#editKeyPosition' + i).val(editKPValue[i - 1]);
                }


                LocCount = (data.LocationDetails).length;
                for (j = 1; j <= LocCount; j++) {
                    mulLocation(j);
                    $('#State' + j).val(data.LocationDetails[j - 1].state);
                    $('#City' + j).val(data.LocationDetails[j - 1].city);
                    $('#ManPower' + j).val(data.LocationDetails[j - 1].nop);

                }

                EduCount = (data.EducationDetails).length;

                for (a = 1; a <= EduCount; a++) {
                    mulEducation(a);
                    $('#Education' + a).val(data.EducationDetails[a - 1].e);
                    $("#Specialization" + a).val(data.EducationDetails[a - 1].s);
                }

                var form = document.getElementById("update_mrf_form");
                var elements = form.elements;
                for (var i = 0, len = elements.length; i < len; ++i) {
                    elements[i].disabled = true;
                }
                CKEDITOR.instances['editJobInfo'].setReadOnly(true);

                if (data.MRFDetails.Type == 'SIP' || data.MRFDetails.Type == 'SIP_HrManual') {
                    $('#deisgnation_tr').addClass('d-none');
                    $('#work_exp_tr').addClass('d-none');
                    $('#stipend_tr').removeClass('d-none');
                    $('#duration_tr').removeClass('d-none');
                    $('#ctc_tr').addClass('d-none');
                    $('#other_benifit_tr').removeClass('d-none');
                    if (data.MRFDetails.TwoWheeler != null) {
                        $('#two_wheeler_check').prop('checked', true);
                        $("#two_wheeler_div").removeClass("d-none");
                        $('#two_wheeler').val(data.MRFDetails.TwoWheeler);
                    }
                    if (data.MRFDetails.DA != null) {
                        $('#da_check').prop('checked', true);
                        $("#da_div").removeClass("d-none");
                        $('#da').val(data.MRFDetails.DA);
                    }
                    if (data.MRFDetails.Tr_Frm_Date != null) {
                        $('#Tr_Frm_Date').val(data.MRFDetails.Tr_Frm_Date);
                    }
                    if (data.MRFDetails.Tr_To_Date != null) {
                        $('#Tr_To_Date').val(data.MRFDetails.Tr_To_Date);
                    }
                } else {
                    $('#deisgnation_tr').removeClass('d-none');
                    $('#work_exp_tr').removeClass('d-none');
                    $('#stipend_tr').addClass('d-none');
                    $('#duration_tr').addClass('d-none');
                    $('#ctc_tr').removeClass('d-none');
                    $('#other_benifit_tr').addClass('d-none');
                }


                $('.modal-footer').addClass('d-none');
                $('#editMRFModal').modal('show');
            }, 'json');
        });


        $(document).on('click', '#closemrf', function() {
            var MRFId = $(this).data('id');
            $("#MrId").val(MRFId);
            $("#closemrfmodal").modal('show');
        });


        var KPCount = 1;
        var editKPCount = 1;
        var StateList = '';
        var CityList = '';
        var EducationList = '';
        var SpecializationList = '';
        getState();

        function getState() {
            $.ajax({
                type: "GET",
                url: "{{ route('getState') }}",
                async: false,
                success: function(res) {
                    if (res) {
                        $.each(res, function(key, value) {
                            StateList = StateList + '<option value="' + value + '">' + key +
                                '</option>';
                        });
                    }
                }
            });
        }

        getCity();

        function getCity() {
            $.ajax({
                type: "GET",
                url: "{{ route('getAllDistrict') }}",
                async: false,
                success: function(res) {
                    if (res) {
                        $.each(res, function(key, value) {
                            CityList = CityList + '<option value="' + value + '">' + key +
                                '</option>';
                        });
                    }
                }
            });
        }

        getEducation();
        getAllSP();

        function getEducation() {
            $.ajax({
                type: "GET",
                url: "{{ route('getEducation') }}",
                async: false,
                success: function(res) {
                    if (res) {
                        $.each(res, function(key, value) {
                            EducationList = EducationList + '<option value="' + value + '">' + key +
                                '</option>';
                        });
                    }
                }
            });
        }

        function getAllSP() {
            $.ajax({
                type: "GET",
                url: "{{ route('getAllSP') }}",
                async: false,
                success: function(res) {
                    if (res) {
                        $.each(res, function(key, value) {
                            SpecializationList = SpecializationList + '<option value="' + key + '">' +
                                value +
                                '</option>';
                        });
                    }
                }
            });
        }

        function getSpecialization(EducationId, No) {
            var EducationId = EducationId;
            var No = No;
            $.ajax({
                type: "GET",
                url: "{{ route('getSpecialization') }}?EducationId=" + EducationId,
                async: false,
                beforeSend: function() {
                    $('#SpeLoader' + No).removeClass('d-none');
                    $('#Specialization' + No).addClass('d-none');
                },

                success: function(res) {

                    if (res) {
                        $('#SpeLoader' + No).addClass('d-none');
                        $('#Specialization' + No).removeClass('d-none');
                        $("#Specialization" + No).empty();
                        $("#Specialization" + No).append(
                            '<option value="" selected disabled >Select Specialization</option>');

                        $.each(res, function(key, value) {
                            $("#Specialization" + No).append('<option value="' + value + '">' + key +
                                '</option>');
                        });
                        $("#Specialization" + No).append('<option value="0">Other</option>');


                    } else {
                        $("#Specialization" + No).empty();
                    }
                }
            });
        }

        var LocCount = 1;
        mulLocation(LocCount);

        function mulLocation(number) {
            x = '<tr>';
            x += '<td >' +
                ' <select  name="State[]" id="State' +
                number +
                '" class="form-control form-select form-select-sm" onchange="getLocation(this.value,' + number + ')">' +
                '  <option value="" selected disabled>Select State</option>' + StateList +
                '</select>' +
                ' <span class="text-danger error-text State' + number + '_error"></span>' +
                '</td>';
            x += '<td>' +
                '<div class="spinner-border text-primary d-none" role="status" id="LocLoader' + number +
                '"> <span class="visually-hidden">Loading...</span></div>' +
                '       <select  id="City' + number + '" name="City[]" class="form-control form-select form-select-sm">' +
                '    <option value="0" selected>Select City</option>' + CityList +
                '</select>' +
                '<span class="text-danger error-text City' + number + '_error"></span>' +
                '</td>';
            x += '<td>' +
                '  <input type="text" name="ManPower[]" id="ManPower' + number +
                '" class="form-control form-control-sm" style="width:130px" placeholder="No. of Manpower">' +
                '<span class="text-danger error-text ManPower' + number + '_error"></span>' +
                '</td>';
            if (number > 1) {
                x +=
                    '<td><button type="button" name="remove" id="" class="btn btn-danger btn-sm  removeLocation">Remove</td></tr>';
                $('#MulLocation').append(x);
            } else {
                x +=
                    '<td><button type="button" name="add" id="addLocation" class="btn btn-warning btn-sm ">Add</button></td></tr>';
                $('#MulLocation').html(x);
            }
        }

        $(document).on('click', '#addLocation', function() {
            LocCount++;
            mulLocation(LocCount);
        });
        $(document).on('click', '.removeLocation', function() {
            LocCount--;
            $(this).closest("tr").remove();
        });


        //-------------------------------Start Multiple Education===========================//

        var EduCount = 1;
        mulEducation(EduCount);

        function mulEducation(num) {
            x = '<tr>';
            x += '<td >' +
                ' <select  name="Education[]" id="Education' +
                num +
                '" class="form-control form-select form-select-sm" onchange="getSpecialization(this.value,' + num + ')">' +
                '  <option value="" selected disabled>Select Education</option>' + EducationList +
                '</select>' +
                ' <span class="text-danger error-text Education' + num + '_error"></span>' +
                '</td>';
            x += '<td>' +
                '<div class="spinner-border text-primary d-none" role="status" id="SpeLoader' + num +
                '"> <span class="visually-hidden">Loading...</span></div>' +
                '       <select  id="Specialization' + num +
                '" name="Specialization[]" class="form-control form-select form-select-sm">' +

                '    <option value="0" >Other</option>' + SpecializationList +
                '</select>' +
                '<span class="text-danger error-text Specialization' + num + '_error"></span>' +
                '</td>';


            if (num > 1) {
                x +=
                    '<td><button type="button" name="remove" id="" class="btn btn-danger btn-sm  removeEducation">Remove</td></tr>';
                $('#MulEducation').append(x);
            } else {
                x +=
                    '<td><button type="button" name="add" id="addEducation" class="btn btn-warning btn-sm ">Add</button></td></tr>';
                $('#MulEducation').html(x);
            }
        }

        $(document).on('click', '#addEducation', function() {
            EduCount++;
            mulEducation(EduCount);
        });

        $(document).on('click', '.removeEducation', function() {
            EduCount--;
            $(this).closest("tr").remove();
        });


        function getLocation(StateId, No) {
            var StateId = StateId;
            var No = No;
            $.ajax({
                type: "GET",
                url: "{{ route('getDistrict') }}?StateId=" + StateId,
                async: false,
                beforeSend: function() {
                    $('#LocLoader' + No).removeClass('d-none');
                    $('#City' + No).addClass('d-none');
                },

                success: function(res) {

                    if (res) {
                        $('#LocLoader' + No).addClass('d-none');
                        $('#City' + No).removeClass('d-none');
                        $("#City" + No).empty();
                        $("#City" + No).append(
                            '<option value="0" selected>Select City</option>');

                        $.each(res, function(key, value) {
                            $("#City" + No).append('<option value="' + value + '">' + key +
                                '</option>');
                        });

                    } else {
                        $("#City" + No).empty();
                    }
                }
            });
        }

        editmulKP();

        function editmulKP(n) {
            x = '<tr>';
            x += '<td >' +
                '<input type="text" class="form-control form-control-sm" id="editKeyPosition' + n +
                '" name="editKeyPosition[]">' +
                '</td>';
            if (n > 1) {
                x +=
                    '<td><button type="button" name="remove" id="" class="btn btn-danger btn-sm  editremoveKP"><i class="bx bx-x"></td></tr>';
                $('#editMulKP').append(x);
            } else {
                x +=
                    '';
                $('#editMulKP').html(x);
            }
        }

        $(document).on('click', '#editaddKP', function() {
            editKPCount++;
            editmulKP(editKPCount);
        });

        $(document).on('click', '.editremoveKP', function() {
            editKPCount--;
            $(this).closest("tr").remove();
        });

        $('#close_mrf_form').on('submit', function(e) {
            e.preventDefault();
            var form = this;

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
                        $.each(data.error, function(prefix, val) {
                            $(form).find('span.' + prefix + '_error').text(val[0]);
                        });
                    } else {
                        $(form)[0].reset();
                        $('#loader').modal('hide');
                        $('#closemrfmodal').modal('hide');
                        $('#MRFTable').DataTable().ajax.reload(null, false);
                        toastr.success(data.msg);
                    }
                }
            });
        });

        function getPositionFilled(MRFId, JobCode) {
            $("#mrf_detail").html(JobCode);
            $("#position_filled_mrf").val(MRFId);
            $.ajax({
                type: "GET",
                url: "{{ route('get_mrf_position_filled_detail') }}?MRFId=" + MRFId,
                async: false,
                beforeSend: function() {

                },
                success: function(res) {
                    $("#myTableBody").html(res.html);
                }
            });
        }

        $(document).on('click', "#save_position_filled", function() {
            const MRFId = $("#position_filled_mrf").val();
            const Filled = $("#position_filled").val();
            const Remark = $("#position_filled_remark").val();
            if (Filled === '' || Remark === '') {
                alert('Please fill the position and remark...');
            } else {
                $.ajax({
                    type: "POST",
                    url: "{{ route('save_mrf_position_filled') }}",
                    data: {
                        MRFId: MRFId,
                        Filled: Filled,
                        Remark: Remark
                    },
                    async: false,
                    beforeSend: function() {

                    },
                    success: function(res) {
                        if (res.status === 400) {
                            toastr.error(res.msg);
                        } else {
                            window.location.reload();
                        }

                    }
                });
            }
        });
    </script>
@endsection
