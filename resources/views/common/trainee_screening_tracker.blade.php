@extends('layouts.master')
@section('title', 'Interview Tracker - SIP/Trainee')
@section('PageContent')
    <style>
        .table > :not(caption) > * > * {
            padding: 2px 1px;
        }

        .frminp {
            padding: 4px !important;
            height: 25px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 550;
        }

        .frmbtn {
            padding: 2px 4px !important;
            font-size: 11px;
            cursor: pointer;
        }

        table,
        th,
        td {
            border: 0.25px solid white;
            vertical-align: middle;
        }

    </style>
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb  align-items-center mb-3">
            <div class="row mb-1">
                <div class="col-3 breadcrumb-title ">
                    Interview Tracker - SIP/Trainee
                </div>

            </div>
            <div class="row mb-2">
                <div class="col-2">
                    <select name="Fill_Company" id="Fill_Company" class="form-select form-select-sm"
                            onchange="GetCampusRecords(); GetDepartment();">
                        <option value="">Select Company</option>
                        @foreach ($company_list as $key => $value)
                            <option value="{{ $key }}">{{ $value }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-2">

                    <select name="Fill_Department" id="Fill_Department" class="form-select form-select-sm"
                            onchange="GetCampusRecords();">
                        <option value="">Select Department</option>

                    </select>
                </div>
                <div class="col-1">
                    <select name="Year" id="Year" class="form-select form-select-sm" onchange="GetCampusRecords();">
                        <option value="">Select Year</option>
                        @for ($i = 2021; $i <= date('Y'); $i++)
                            <option value="{{ $i }}">{{ $i }}</option>
                        @endfor
                    </select>
                </div>
                <div class="col-2">
                    <select name="Month" id="Month" class="form-select form-select-sm" onchange="GetCampusRecords();">
                        <option value="">Select Month</option>
                        @foreach ($months as $key => $value)
                            <option value="{{ $key }}">{{ $value }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-2">
                    <select name="Fill_Screening" id="Fill_Screening" class="form-select form-select-sm"
                            onchange="GetCampusRecords();">
                        <option value="">Select Screening Status</option>
                        <option value="Shortlist">Shortlist</option>
                        <option value="Reject">Reject</option>
                        <option value="Pending">Pending</option>
                    </select>
                </div>
                <div class="col-2">
                    <input type="text" name="Fill_Name" id="Fill_Name" class="form-control form-control-sm"
                           placeholder="Search by Name,Phone" onkeyup="GetCampusRecords();">
                </div>
                <div class="col-1">
                    <button type="reset" class="btn btn-danger btn-sm" id="reset"><i class="bx bx-refresh"></i></button>
                </div>
            </div>
        </div>
        <!--end breadcrumb-->

        <div class="card border-top border-0 border-4 border-success">
            <div class="card-body table-responsive">
                <div class=" bg-white  shadow-sm rounded stickThis mb-3" style="font-size: 14px;">
                    &nbsp;<span style="font-weight: bold;">↱</span>&nbsp;
                    <label class="text-success"><input id="checkall" type="checkbox" name="">&nbsp;Check all</label>
                    <i class="text-muted" style="font-size: 13px;">With selected:</i>
                    <span class="d-inline">
                        <label class="text-success" style="font-size: 13px; cursor: pointer;" data-bs-toggle="modal"
                               data-bs-target="#TechScreeningModal"><i class="fas fa-long-arrow-alt-right"></i> Set Screening Details</label>
                    </span>
                </div>
                <table class="table table-condensed" id="CampusApplication"
                       style="width: 100%; margin-right:20px;">
                    <thead class="text-center bg-success bg-gradient text-light">
                    <tr>
                        <td rowspan="2">#</td>
                        <td rowspan="2">S.no</td>
                        <td rowspan="2">Trainee ID</td>
                        <td rowspan="2">Reference No</td>
                        <td rowspan="2">JobCode</td>
                        <td rowspan="2">Department</td>
                        <td rowspan="2"> Name</td>
                        <td rowspan="2">Screen By</td>
                        <td rowspan="2">Technical Screening <br>Status</td>
                        <td rowspan="2">Send FIRO B</td>
                        <td colspan="5" class="text-center">Interview</td>
                    </tr>
                    <tr>

                        <td>Date of Interview</td>
                        <td>Interview Location</td>
                        <td>Interview Panel Members</td>
                        <td>Interview Status</td>
                        <td>Edit</td>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
    <div class="modal fade" id="myModal" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-info bg-gradient">
                    <h6 class="modal-title text-white" id="candidatename"></h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form action="{{ route('SaveTraineeInterview') }}" method="POST" id="first_interview_form">
                    @csrf
                    <div class="modal-body">
                        <table class="table table-borderless">

                            <tr>
                                <td>Date of Interview</td>
                                <td>
                                    <input type="hidden" name="TId" id="TId">
                                    <input type="date" name="IntervDt" id="IntervDt"
                                           class="form-control form-control-sm frminp">
                                </td>
                            </tr>
                            <tr>
                                <td>Interview Location</td>
                                <td>
                                    <input type="text" name="IntervLoc" id="IntervLoc"
                                           class="form-control form-control-sm frminp">
                                </td>
                            </tr>
                            <tr>
                                <td>Interview Panel Members</td>
                                <td>
                                    <textarea name="IntervPanel" id="IntervPanel" cols="10" rows="3"
                                              class="form-control form-control-sm"></textarea>
                                </td>
                            </tr>
                            <tr>
                                <td>Interview Status</td>
                                <td>
                                    <select name="IntervStatus" id="IntervStatus" class="form-select form-select-sm">
                                        <option value=""></option>
                                        <option value="Selected">Selected</option>
                                        <option value="Rejected">Rejected</option>
                                        <option value="Absent">Absent</option>
                                        <option value="OnHold">On-Hold</option>

                                    </select>
                                </td>
                            </tr>
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

    <div class="modal fade" id="TechScreeningModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static"
         data-bs-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-info bg-gradient">
                    <h6 class="modal-title text-white">Set Screening Details</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-borderless">
                        <tr>
                            <td>
                                Technical Screening Status
                            </td>
                            <td>
                                <select name="Bulk_Status" id="Bulk_Status" class="form-select form-select-sm">
                                    <option value="">Select</option>
                                    <option value="Shortlist">Shortlist</option>
                                    <option value="Reject">Reject</option>
                                </select>
                            </td>
                        </tr>

                        <tr id="bulk_int_div" class="d-none">
                            <td>Date of Interview</td>
                            <td>

                                <input type="date" name="Bulk_IntervDt" id="Bulk_IntervDt"
                                       class="form-control form-control-sm frminp">
                            </td>
                        </tr>
                        <tr id="bulk_loc_div" class="d-none">
                            <td>Interview Location</td>
                            <td>
                                <input type="text" name="Bulk_IntervLoc" id="Bulk_IntervLoc"
                                       class="form-control form-control-sm frminp">
                            </td>
                        </tr>
                        <tr id="bulk_mem_div" class="d-none">
                            <td>Interview Panel Members</td>
                            <td>
                                    <textarea name="Bulk_IntervPanel" id="Bulk_IntervPanel" cols="10" rows="3"
                                              class="form-control form-control-sm"></textarea>
                            </td>
                        </tr>

                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal">Close</button>
                    <button type="button" id="SetInterviewDetail" class="btn btn-primary btn-sm">Save
                        changes</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script_section')
    <script>
        $('#checkall').click(function() {
            if ($(this).prop("checked") == true) {
                $('.select_all').prop("checked", true);
            } else if ($(this).prop("checked") == false) {
                $('.select_all').prop("checked", false);
            }
        });

        $(document).on('click', '#SetInterviewDetail', function() {
            var Tid = [];
            var Bulk_Status = $('#Bulk_Status').val();
            var Bulk_IntervDt = $('#Bulk_IntervDt').val();
            var Bulk_IntervLoc = $('#Bulk_IntervLoc').val();
            var Bulk_IntervPanel = $('#Bulk_IntervPanel').val();
            $("input[name='selectCand']").each(function() {
                if ($(this).prop("checked") == true) {
                    var value = $(this).val();
                    Tid.push(value);
                }
            });

            if (Tid.length > 0) {
                if (confirm('Are you sure to Set Interview Details for Selected Candidates?')) {
                    $.ajax({
                        url: '{{ url('SetAllTraineeInterviewDetails') }}',
                        method: 'POST',
                        data: {
                            Tid: Tid,
                            Status: Bulk_Status,
                            Interview_Date: Bulk_IntervDt,
                            Interview_Location: Bulk_IntervLoc,
                            Interview_Panel: Bulk_IntervPanel
                        },
                        success: function(data) {
                            if (data.status == 400) {
                                alert('Something went wrong..!!');
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
        function GetDepartment() {
            var CompanyId = $('#Fill_Company').val();
            $.ajax({
                type: "GET",
                url: "{{ route('getDepartment') }}?CompanyId=" + CompanyId,
                beforeSend: function () {

                },
                success: function (res) {

                    if (res) {
                        $("#Fill_Department").empty();
                        $("#Fill_Department").append(
                            '<option value="" selected disabled >Select Department</option>');
                        $.each(res, function (key, value) {
                            $("#Fill_Department").append('<option value="' + value + '">' + key +
                                '</option>');
                        });
                    } else {
                        $("#Fill_Department").empty();
                    }
                }
            });
        }

        function GetCampusRecords() {
            $('#CampusApplication').DataTable().draw(true);

        }

        $(document).on('click', '#reset', function () {
            location.reload();
        });

        $(document).ready(function () {
            $('#CampusApplication').DataTable({
                processing: true,
                serverSide: true,
                ordering: false,
                searching: false,
                lengthChange: true,
                info: true,
                ajax: {
                    url: "{{ route('getTraineeScreeningCandidates') }}",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: function (d) {
                        d.Company = $('#Fill_Company').val();
                        d.Department = $('#Fill_Department').val();
                        d.Year = $('#Year').val();
                        d.Month = $('#Month').val();
                        d.Status = $('#Fill_Screening').val();
                        d.Name = $('#Fill_Name').val();
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
                        data: 'TId',
                        name: 'TId',
                        className: 'text-center'
                    },
                    {
                        data: 'ReferenceNo',
                        name: 'ReferenceNo'
                    },
                    {
                        data: 'JobCode',
                        name: 'JobCode'
                    },
                    {
                        data: 'Department',
                        name: 'Department'
                    },

                    {
                        data: 'CandidateName',
                        name: 'CandidateName'
                    },
                    {
                        data: 'ScreenBy',
                        name: 'ScreenBy'
                    },
                    {
                        data: 'ScreenStatus',
                        name: 'ScreenStatus',

                    },
                    {
                        data: 'SendFIROB',
                        name: 'SendFIROB'
                    },
                    {
                        data: 'IntervDt',
                        name: 'IntervDt'
                    },
                    {
                        data: 'IntervLoc',
                        name: 'IntervLoc'
                    },
                    {
                        data: 'IntervPanel',
                        name: 'IntervPanel'
                    },
                    {
                        data: 'IntervStatus',
                        name: 'IntervStatus'
                    },
                    {
                        data: 'IntervEdit',
                        name: 'IntervEdit'
                    }
                ],

            });
        });


        $(document).on('click', '.select_all', function () {
            if ($(this).prop("checked") == true) {
                $(this).closest("tr").addClass("bg-secondary bg-gradient text-light");
            } else {
                $(this).closest("tr").removeClass("bg-secondary bg-gradient text-light");
            }
        });


        function editScreenStatus(id) {
            $('#ScreenStatus' + id).prop("disabled", false);
        }

        function ChngScreenStatus(TId, va) {

            $.ajax({
                url: "{{ route('ChngTraineeScreenStatus') }}",
                type: 'POST',
                data: {
                    TId: TId,
                    va: va
                },
                dataType: 'json',
                beforeSend: function () {
                    $("#loader").modal('show');
                },
                success: function (data) {
                    if (data.status == 200) {
                        $("#loader").modal('hide');
                        $('#CampusApplication').DataTable().ajax.reload(null, false);
                        toastr.success(data.msg);
                    } else {
                        $("#loader").modal('hide');
                        toastr.error(data.msg);
                    }
                }
            });
        }

        function editSendFIROB(id) {
            $('#SendFIROB' + id).prop("disabled", false);
        }

        function ChngSendFIROB(TId, va) {

            $.ajax({
                url: "{{ route('SendFirobToTrainee') }}",
                type: 'POST',
                data: {
                    TId: TId,
                    va: va
                },
                dataType: 'json',
                beforeSend: function () {
                    $("#loader").modal('show');
                },
                success: function (data) {
                    if (data.status == 200) {
                        $("#loader").modal('hide');
                        $('#CampusApplication').DataTable().ajax.reload(null, false);
                        toastr.success(data.msg);
                    } else {
                        $("#loader").modal('hide');
                        toastr.error(data.msg);
                    }
                }
            });
        }

        function editInt(TId) {
            getTraineeName(TId);
            getInterviewDetails(TId);
            $("#TId").val(TId);
            $('#myModal').modal('show');
        }


        function getTraineeName(TId) {
            $.ajax({
                type: "POST",
                url: "{{ route('getTraineeName') }}?TId=" + TId,
                success: function (res) {
                    if (res) {
                        $("#candidatename").html('Interview(' + res + ' )');

                    }
                }
            });
        }
        function getInterviewDetails(TId) {
            $.ajax({
                type: "GET",
                url: "{{ route('getInterviewDetailsTrainee') }}?TId=" + TId,
                success: function (res) {
                    if (res) {
                        $("#IntervDt").val(res.data.IntervDt);
                        $("#IntervLoc").val(res.data.IntervLoc);
                        $("#IntervPanel").val(res.data.IntervPanel);
                        $("#IntervStatus").val(res.data.IntervStatus);
                    }
                }
            });
        }
        $('#first_interview_form').on('submit', function (e) {
            e.preventDefault();
            var form = this;
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
                        $('#myModal').modal('hide');
                        $('#CampusApplication').DataTable().ajax.reload(null, false);
                        toastr.success(data.msg);
                    }
                }
            });
        });

        $("#Bulk_Status").on("change",function (){
            var status = $(this).val();
            if(status=='Shortlist'){
                $("#bulk_int_div").removeClass('d-none');
                $("#bulk_loc_div").removeClass('d-none');
                $("#bulk_mem_div").removeClass('d-none');

            }else{
                $("#bulk_int_div").addClass('d-none');
                $("#bulk_loc_div").addClass('d-none');
                $("#bulk_mem_div").addClass('d-none');
            }
        });

    </script>
@endsection
