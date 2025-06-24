@extends('layouts.master')
@section('title', 'Campus Screening Tracker')
@section('PageContent')
    <style>
        .table>:not(caption)>*>* {
            padding: 2px 1px;
        }

        .frminp {
            padding: 4 px !important;
            height: 25 px;
            border-radius: 4 px;
            font-size: 11px;
            font-weight: 550;
        }

        .frmbtn {
            padding: 2 px 4 px !important;
            font-size: 11px;
            cursor: pointer;
        }

    </style>
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb  align-items-center mb-3">
            <div class="row mb-1">
                <div class="col-3 breadcrumb-title ">
                    Campus Scr. Tracker
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
                        <option value="">Year</option>
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
                        <option value="">Select Status</option>
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
                <div class=" bg-white  shadow-sm rounded stickThis " style="font-size: 14px;">
                    &nbsp;<span style="font-weight: bold;">↱</span>&nbsp;
                    <label class="text-success"><input id="checkall" type="checkbox" name="">&nbsp;Check all</label>
                    <i class="text-muted" style="font-size: 13px;">With selected:</i>
                    <span class="d-inline">
                        <label class="text-success" style="font-size: 13px; cursor: pointer;" data-bs-toggle="modal"
                            data-bs-target="#TechScreeningModal"><i class="fas fa-long-arrow-alt-right"></i> Set Technical
                            Screening
                            Status</label> &nbsp;
                    </span>



                </div>
                <table class="table  table-condensed align-middle table-bordered "
                    id="CampusApplication" style="width: 100%; margin-right:20px;">
                    <thead class="text-center bg-success bg-gradient text-light">
                        <tr>
                            <td>#</td>
                            <td>S.No.</td>
                            <td>Reference No.</td>
                            <td>Department</td>
                            <td>Designation</td>
                            <td>University</td>
                            <td>Student Name</td>
                            <td>GD Result</td>
                            <td>Test Score</td>
                            <td>Technical Screening <br>Status</td>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
    <div class="modal fade" id="TechScreeningModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static"
        data-bs-keyboard="false">
        <div class="modal-dialog">

            <div class="modal-content">
                <div class="modal-body">


                    <div class="row">
                        <div class="col"><label for="techStatus">Technical Screening Status</label></div>
                        <div class="col">
                            <select name="techStatus" id="techStatus" class="form-select form-select-sm">
                                <option value="">Select</option>
                                <option value="Shortlist">Shortlist</option>
                                <option value="Reject">Reject</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal">Close</button>
                    <button type="button" id="SendForTechSceenBtn" class="btn btn-primary btn-sm">Save
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
                $('.japchks').prop("checked", true);
            } else if ($(this).prop("checked") == false) {
                $('.japchks').prop("checked", false);
            }
        });

        $(document).on('click', '#SendForTechSceenBtn', function() {
            var JAId = [];
            var techStatus = $('#techStatus').val();
            $("input[name='selectCand']").each(function() {
                if ($(this).prop("checked") == true) {
                    var value = $(this).val();
                    JAId.push(value);
                }
            });
            if (JAId.length > 0) {
                if (confirm('Are you sure to Set Tecnical Screening Status for Selected Candidates?')) {
                    $.ajax({
                        url: '{{ url('SetAllCampusTechScrStatus') }}',
                        method: 'POST',
                        data: {
                            JAId: JAId,
                            techStatus: techStatus
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

        function GetCampusRecords() {
            $('#CampusApplication').DataTable().draw(true);

        }

        $(document).on('click', '#reset', function() {
            location.reload();
        });

        $(document).ready(function() {


            $('#CampusApplication').DataTable({
                processing: true,
                serverSide: true,
                ordering: false,
                searching: false,
                lengthChange: false,
                info: true,
                ajax: {
                    url: "{{ route('getCampusScreeningCandidates') }}",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: function(d) {
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
                        name: 'chk',
                        'className' : 'text-center'
                    },
                    {
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        'className' : 'text-center'
                    },
                    {
                        data: 'ReferenceNo',
                        name: 'ReferenceNo'
                    },
                    {
                        data: 'Department',
                        name: 'Department',
                        'className' : 'text-center'
                    },
                    {
                        data: 'Designation',
                        name: 'Designation',
                        'className' : 'text-center'
                    },
                    {
                        data: 'University',
                        name: 'University',
                        'className' : 'text-center'
                    },
                    {
                        data: 'StudentName',
                        name: 'StudentName'
                    },
                    {
                        data: 'GDResult',
                        name: 'GDResult'
                    },
                    {
                        data: 'TestScore',
                        name: 'TestScore'
                    },
                    {
                        data: 'ScreenStatus',
                        name: 'ScreenStatus'
                    },




                ],
                /* createdRow: (row, data, dataIndex, cells) => {
                    if (data['IntervStatus'] != '2nd Round Interview') {
                        $(cells[3]).css('background-color', 'gray')
                    }
                } */
            });
        });



        $(document).on('click', '.select_all', function() {
            if ($(this).prop("checked") == true) {
                $(this).closest("tr").addClass("bg-secondary bg-gradient text-light");
            } else {
                $(this).closest("tr").removeClass("bg-secondary bg-gradient text-light");
            }
        });

        function editGDRes(id) {
            $('#GDResult' + id).prop("disabled", false);
        }

        function ChngGDResult(JAId, va) {

            $.ajax({
                url: "{{ route('ChngGDResult') }}",
                type: 'POST',
                data: {
                    JAId: JAId,
                    va: va
                },
                dataType: 'json',
                beforeSend: function() {
                    $("#loader").modal('show');
                },
                success: function(data) {
                    if (data.status == 200) {
                        $("#loader").modal('hide');
                        $('#CampusApplication').DataTable().ajax.reload(null, false);
                        toastr.success(data.msg);
                    } else {
                        toastr.error(data.msg);
                    }
                }
            });
        }

        function editTestScore(id) {
            $('#TestScore' + id).prop("disabled", false);
            $('#TestScoreEdit' + id).addClass('d-none');
            $('#SaveScore' + id).removeClass('d-none');
        }

        function SaveTestScore(JAId) {
            var Score = $('#TestScore' + JAId).val();
            $.ajax({
                url: "{{ route('SaveTestScore') }}",
                type: 'POST',
                data: {
                    JAId: JAId,
                    Score: Score
                },
                dataType: 'json',

                success: function(data) {
                    if (data.status == 200) {

                        $('#CampusApplication').DataTable().ajax.reload(null, false);
                        toastr.success(data.msg);
                    } else {
                        toastr.error(data.msg);
                    }
                }
            });
        }

        function editScreenStatus(id) {
            $('#ScreenStatus' + id).prop("disabled", false);
        }

        function ChngScreenStatus(JAId, va) {

            $.ajax({
                url: "{{ route('ChngScreenStatus') }}",
                type: 'POST',
                data: {
                    JAId: JAId,
                    va: va
                },
                dataType: 'json',
                beforeSend: function() {
                    $("#loader").modal('show');
                },
                success: function(data) {
                    if (data.status == 200) {
                        $("#loader").modal('hide');
                        $('#CampusApplication').DataTable().ajax.reload(null, false);
                        toastr.success(data.msg);
                    } else {
                        toastr.error(data.msg);
                    }
                }
            });
        }
    </script>
@endsection
