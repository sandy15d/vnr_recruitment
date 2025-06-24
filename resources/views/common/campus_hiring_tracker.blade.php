@extends('layouts.master')
@section('title', 'Campus Hiring Tracker')
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
                    Campus Hiring Tracker
                </div>
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
                <div class="col-2">
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
                <div class="col-1">
                    <button type="reset" class="btn btn-danger btn-sm" id="reset"><i class="bx bx-refresh"></i></button>
                </div>
            </div>

        </div>
        <!--end breadcrumb-->

        <div class="card border-top border-0 border-4 border-success">
            <div class="card-body table-responsive">
                <table class="table  table-condensed" id="CampusApplication"
                    style="width: 100%; margin-right:20px; ">
                    <thead class="text-center bg-success bg-gradient text-light">
                        <tr>
                            <td rowspan="2">#</td>
                            <td rowspan="2">S.No.</td>
                            <td rowspan="2">Reference No.</td>
                            <td rowspan="2">Department</td>
                            <td rowspan="2">Designation</td>
                            <td rowspan="2">University</td>
                            <td rowspan="2">Student Name</td>
                            <td rowspan="2">GD Result</td>
                            <td rowspan="2">Test Score</td>
                            <td rowspan="2">FIRO B</td>
                            <td colspan="5" class="text-center">Interview</td>
                            <td colspan="5" class="text-center">2nd Round Interview</td>
                            <td colspan="3" style="text-align: center;">Selected for</td>
                        </tr>
                        <tr>
                            <td>Date of Interview</td>
                            <td>Interview Location</td>
                            <td>Interview Panel Members</td>
                            <td>Interview Status</td>
                            <td>Edit</td>

                            <td>Date of Interview</td>
                            <td>Interview Location</td>
                            <td>Interview Panel Members</td>
                            <td>Interview Status</td>
                            <td>Edit</td>

                            <td>Company</td>
                            <td>Department</td>
                            <td>Edit</td>
                        </tr>

                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- The Modal -->
    <div class="modal fade" id="myModal" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-info bg-gradient">
                    <h6 class="modal-title text-white" id="candidatename"></h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form action="{{ route('SaveFirstInterview_Campus') }}" method="POST" id="first_interview_form">
                    @csrf
                    <div class="modal-body">
                        <table class="table table-borderless">
                            <tr>
                                <td>Date of Interview</td>
                                <td>
                                    <input type="hidden" name="ScId" id="ScId">
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
                                        <option value="On Hold">On Hold</option>
                                        <option value="2nd Round Interview">2nd Round Interview</option>
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
    <!-- The 2nd Interview Modal -->
    <div class="modal fade" id="2ndInterviewModal" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-info bg-gradient">
                    <h6 class="modal-title text-white" id="candidatename1"></h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form action="{{ route('SaveSecondInterview_Campus') }}" method="POST" id="second_interview_form">
                    @csrf
                    <div class="modal-body">
                        <table class="table table-borderless">
                            <tr>
                                <td>Date of Interview</td>
                                <td>
                                    <input type="hidden" name="ScId_2nd" id="ScId_2nd">
                                    <input type="date" name="IntervDt2" id="IntervDt2"
                                        class="form-control form-control-sm frminp">
                                </td>
                            </tr>
                            <tr>
                                <td>Interview Location</td>
                                <td>
                                    <input type="text" name="IntervLoc2" id="IntervLoc2"
                                        class="form-control form-control-sm frminp">
                                </td>
                            </tr>
                            <tr>
                                <td>Interview Panel Members</td>
                                <td>
                                    <textarea name="IntervPanel2" id="IntervPanel2" cols="10" rows="3"
                                        class="form-control form-control-sm"></textarea>
                                </td>
                            </tr>
                            <tr>
                                <td>Interview Status</td>
                                <td>
                                    <select name="IntervStatus2" id="IntervStatus2" class="form-select form-select-sm">
                                        <option value=""></option>
                                        <option value="Selected">Selected</option>
                                        <option value="Rejected">Rejected</option>
                                        <option value="On Hold">On Hold</option>
                                    </select>
                                </td>
                            </tr>
                        </table>


                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="companyModal" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-info bg-gradient">
                    <h6 class="modal-title text-white" id="candidatename_company"></h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form action="{{ route('Save_Cmp_Dpt_Campus') }}" method="POST" id="cmp_dpt_form">
                    @csrf
                    <div class="modal-body">
                        <table class="table table-borderless">
                            <tr>
                                <td>Company</td>
                                <td>
                                    <input type="hidden" name="ScId_cmp" id="ScId_cmp">
                                    <select name="SelectedForC" id="SelectedForC" class="form-select form-select-sm" onchange="GetDepartment1();">
                                        <option value="">Select Company</option>
                                        @foreach ($company_list as $key => $value)
                                            <option value="{{ $key }}">{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>Department</td>
                                <td>
                                    <select name="SelectedForD" id="SelectedForD"
                                        class="form-select form-select-sm"></select>
                                </td>
                            </tr>

                        </table>


                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('script_section')
    <script>
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
        function GetDepartment1() {     // for Interview Department Selection
            var CompanyId = $('#SelectedForC').val();
            $.ajax({
                type: "GET",
                url: "{{ route('getDepartment') }}?CompanyId=" + CompanyId,
                beforeSend: function() {

                },
                success: function(res) {

                    if (res) {
                        $("#SelectedForD").empty();
                        $("#SelectedForD").append(
                            '<option value="" selected disabled >Select Department</option>');
                        $.each(res, function(key, value) {
                            $("#SelectedForD").append('<option value="' + value + '">' + key +
                                '</option>');
                        });
                    } else {
                        $("#SelectedForD").empty();
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
                    url: "{{ route('getCampusHiringCandidates') }}",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: function(d) {
                        d.Company = $('#Fill_Company').val();
                        d.Department = $('#Fill_Department').val();
                        d.Year = $('#Year').val();
                        d.Month = $('#Month').val();
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
                        name: 'University'
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
                        name: 'TestScore',
                        'className' : 'text-center'
                    },
                    {
                        data: 'FIROB',
                        name: 'FIROB',
                        'className' : 'text-center'
                    },
                    {
                        data: 'IntervDt',
                        name: 'IntervDt',
                        'className' : 'text-center'
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
                    },
                    {
                        data: 'IntervDt2',
                        name: 'IntervDt2'
                    },
                    {
                        data: 'IntervLoc2',
                        name: 'IntervLoc2'
                    },
                    {
                        data: 'IntervPanel2',
                        name: 'IntervPanel2'
                    },
                    {
                        data: 'IntervStatus2',
                        name: 'IntervStatus2'
                    },
                    {
                        data: 'IntervEdit2',
                        name: 'IntervEdit2'
                    },

                    {
                        data: 'SelectedForC',
                        name: 'SelectedForC'
                    },
                    {
                        data: 'SelectedForD',
                        name: 'SelectedForD'
                    },
                    {
                        data: 'CompanyEdit',
                        name: 'CompanyEdit'
                    },




                ],
                createdRow: (row, data, dataIndex, cells) => {
                    if (data['IntervStatus'] != '2nd Round Interview') {
                        $(cells[15]).css('background-color', 'rgb(218 209 237)')
                        $(cells[16]).css('background-color', 'rgb(218 209 237)')
                        $(cells[17]).css('background-color', 'rgb(218 209 237)')
                        $(cells[18]).css('background-color', 'rgb(218 209 237)')
                        $(cells[19]).css('background-color', 'rgb(218 209 237)')
                    }
                }
            });
        });

        $(document).on('click', '.select_all', function() {
            if ($(this).prop("checked") == true) {
                $(this).closest("tr").addClass("bg-secondary bg-gradient text-light");
            } else {
                $(this).closest("tr").removeClass("bg-secondary bg-gradient text-light");
            }
        });

        function editInt(JAId, ScId) {
            getCandidateName(JAId);
            $('#ScId').val(ScId);
            $('#myModal').modal('show');
        }

        function editInt_2nd(JAId, ScId) {
            getCandidateName(JAId);
            $('#ScId_2nd').val(ScId);
            $('#2ndInterviewModal').modal('show');
        }

        function editCompany(JAId, ScId) {
            getCandidateName(JAId);
            $('#ScId_cmp').val(ScId);
            $('#companyModal').modal('show');
        }

        function getCandidateName(JAId) {
            $.ajax({
                type: "POST",
                url: "{{ route('getCandidateName') }}?JAId=" + JAId,
                success: function(res) {
                    if (res) {
                        $("#candidatename").html('1st Round Interview Edit (' + res + ' )');
                        $("#candidatename1").html('2nd Round Interview Edit (' + res + ' )');
                        $("#candidatename_company").html('Selection for (' + res + ' )');
                    }
                }
            });
        }

        $('#first_interview_form').on('submit', function(e) {
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

                },
                success: function(data) {
                    if (data.status == 400) {
                        $.each(data.error, function(prefix, val) {
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

        $('#second_interview_form').on('submit', function(e) {
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

                },
                success: function(data) {
                    if (data.status == 400) {
                        $.each(data.error, function(prefix, val) {
                            $(form).find('span.' + prefix + '_error').text(val[0]);
                        });
                    } else {
                        $(form)[0].reset();
                        $('#2ndInterviewModal').modal('hide');
                        $('#CampusApplication').DataTable().ajax.reload(null, false);
                        toastr.success(data.msg);
                    }
                }
            });
        });

        $('#cmp_dpt_form').on('submit', function(e) {
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

                },
                success: function(data) {
                    if (data.status == 400) {
                        $.each(data.error, function(prefix, val) {
                            $(form).find('span.' + prefix + '_error').text(val[0]);
                        });
                    } else {
                        $(form)[0].reset();
                        $('#companyModal').modal('hide');
                        $('#CampusApplication').DataTable().ajax.reload(null, false);
                        toastr.success(data.msg);
                    }
                }
            });
        });
    </script>
@endsection
