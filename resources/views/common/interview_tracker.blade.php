@extends('layouts.master')
@section('title', 'Interview Tracker')
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

        td.details-control {
            background: url("{{ asset('assets/images/details_open.png') }}") no-repeat center center;
            cursor: pointer;
        }

        tr.shown td.details-control {
            background: url("{{ asset('assets/images/details_close.png') }}") no-repeat center center;
        }

        .details-control {
            width: 40px;
        }

    </style>
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb  align-items-center mb-3">
            <div class="row mb-1">
                <div class="col-3 breadcrumb-title ">
                    Interview Tracker
                </div>
                <div class="col-2">
                    <select name="Fill_Company" id="Fill_Company" class="form-select form-select-sm"
                            onchange="GetCandidates(); GetDepartment();">
                        <option value="">Select Company</option>
                        @foreach ($company_list as $key => $value)
                            <option value="{{ $key }}">{{ $value }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-2">

                    <select name="Fill_Department" id="Fill_Department" class="form-select form-select-sm"
                            onchange="GetCandidates(); GetMRF();">
                        <option value="">Select Department</option>

                    </select>
                </div>
                <div class="col-2">

                    <select name="Fill_JobCode" id="Fill_JobCode" class="form-select form-select-sm"
                            onchange="GetCandidates();">
                        <option value="">Select MRF</option>

                    </select>
                </div>
                <div class="col-2">
                    <input type="text" name="Fill_Name" id="Fill_Name" class="form-control form-control-sm"
                           placeholder="Search by Name,Phone" onkeyup="GetCandidates();">
                </div>
                <div class="col-1">
                    <button type="reset" class="btn btn-danger btn-sm" id="reset"><i class="bx bx-refresh"></i></button>
                </div>
            </div>

        </div>
        <!--end breadcrumb-->

        <div class="card border-top border-0 border-4 border-success">
            <div class="card-body table-responsive">
                <table class="table table-condensed" id="candidate_table" style="width: 100%; margin-right:20px; ">
                    <thead class="text-center bg-success bg-gradient text-light">
                    <tr class="text-center">
                        <th style="width:30px;" rowspan="2"></th>
                        {{-- <td rowspan="2">#</td> --}}
                        <td rowspan="2">S.no</td>
                        {{--  <td rowspan="2">Ref.No</td>--}}
                        <td rowspan="2">Candidate</td>
                        {{-- <td rowspan="2">Phone</td>
                         <td rowspan="2">Email</td>--}}
                        <td rowspan="2">Department</td>
                        <td rowspan="2">SubDepartment</td>
                        {{--  <td rowspan="2">MRF</td>--}}
                        <th rowspan="2">Firo B <br>Fill</th>
                        <th rowspan="2">Interview Form <br>Fill</th>
                        <td rowspan="2">Test Score</td>
                        <td colspan="2" class="text-center" style="padding-right: 0px">Interview</td>
                        <td colspan="2" class="text-center" style="padding-right: 0px">2nd Round<br> Interview</td>
                        <td colspan="3" style="text-align: center;padding-right: 0px">Selected for</td>
                    </tr>
                    <tr class="text-center">

                        <td style="text-align: center">Interview <br>Status</td>
                        <td>Edit</td>
                        <td>Interview<br> Status</td>
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
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-info bg-gradient">
                    <h6 class="modal-title text-white" id="candidatename"></h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('first_round_interview') }}" method="POST" id="first_interview_form">
                    @csrf
                    <div class="modal-body">
                        <table style="width: 100%;">
                            <tr>
                                <th style="width: 30%">Interview Status</th>
                                <td style="width: 70%">
                                    <input type="hidden" name="ScId" id="ScId">
                                    <input type="hidden" name="JAId" id="JAId">
                                    <select name="IntervStatus" id="IntervStatus" class="form-select form-select-sm">
                                        <option value=""></option>
                                        <option value="Selected">Selected</option>
                                        <option value="Rejected">Rejected</option>
                                        <option value="On Hold">On Hold</option>
                                        <option value="Did not Attend">Did not Attend</option>
                                        <option value="2nd Round Interview">2nd Round Interview</option>
                                    </select>
                                </td>
                            </tr>
                            <tr id="rejct_tr" class="d-none">
                                <td></td>
                                <td>
                                    <input type="checkbox" name="RegretMail" id="RegretMail" value="Yes"> Send Regret
                                    mail to Candidate
                                </td>
                            </tr>
                            <tr id="intervsch_tr" class="d-none">
                                <th>Mode of Interview</th>
                                <td>
                                    <div class="row">
                                        <div class="col">
                                            <input class="form-check-input" type="radio" name="InterviewSchedule"
                                                   id="online" value="online">
                                            <label class="form-check-label" for="online">Online</label>
                                        </div>
                                        <div class="col">
                                            <input class="form-check-input" type="radio" name="InterviewSchedule"
                                                   id="offline" checked value="offline">
                                            <label class="form-check-label" for="offline" checked>Offline</label>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr id="intervlink_tr" class="d-none">
                                <th>Interview Link</th>
                                <td>
                                    <input type="text" name="InterviewLink" id="InterviewLink"
                                           class="form-control form-control-sm">
                                </td>
                            </tr>
                            <tr id="intervdt_tr" class="d-none">
                                <th>Date of Interview</th>
                                <td>
                                    <input type="date" name="InterviewDate" id="InterviewDate"
                                           class="form-control form-control-sm">
                                </td>
                            </tr>
                            <tr id="intervtime_tr" class="d-none">
                                <th>Interview Time</th>
                                <td>
                                    <input type="time" name="InterviewTime" id="InterviewTime"
                                           class="form-control form-control-sm">
                                </td>
                            </tr>
                            <tr id="intervloc_tr" class="d-none">
                                <th>Venue</th>
                                <td>
                                        <textarea name="InterviewLocation" id="InterviewLocation"
                                                  class="form-control form-control-sm">
                                        </textarea>
                                </td>
                            </tr>
                            <tr id="intervpannel_tr" class="d-none">
                                <th>Interview Pannel Members</th>
                                <td>
                                    <select name="InterviewPannel[]" id="InterviewPannel"
                                            class="form-select form-select-sm select2" multiple>
                                        @foreach($emplist as $list)
                                            <option value="{{$list->EmployeeID}}">{{$list->EmpCode}}
                                                - {{$list->Fname}} {{$list->Lname}}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                            <tr id="intervelg_tr" class="d-none">
                                <th>Travel Eligibility</th>
                                <td>
                                    <select name="TravelElg" id="TravelElg"
                                            class="form-select form-select-sm">
                                        <option value=""></option>
                                        <option value="Sleeper">Sleeper</option>
                                        <option value="3 AC">3 AC</option>
                                        <option value="2 AC">2 AC</option>
                                        <option value="Flight Economy">Flight Economy</option>
                                    </select>
                                </td>
                            </tr>
                            <tr id="intervmail_tr" class="d-none">

                                <td colspan="2">
                                    <table style="width: 100%">
                                        <tr>
                                            <th style="width: 30%">Interview Mail to Candidate</th>
                                            <td style="width: 25%">
                                                <select name="InterviewMail" id="InterviewMail"
                                                        class="form-select form-select-sm">
                                                    <option value="No">No</option>
                                                    <option value="Yes">Yes</option>
                                                </select>
                                            </td>
                                            <th>Mail to Panel Member</th>
                                            <td style="width: 25%">
                                                <select name="InterviewMailToPanel" id="InterviewMailToPanel"
                                                        class="form-select form-select-sm">
                                                    <option value="No">No</option>
                                                    <option value="Yes">Yes</option>
                                                </select>
                                            </td>
                                        </tr>
                                    </table>
                                </td>


                            </tr>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">Save changes</button>
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

                <form action="{{ route('second_round_interview') }}" method="POST" id="second_interview_form">
                    @csrf
                    <div class="modal-body">
                        <table class="table table-borderless">

                            <tr>
                                <td>Interview Status</td>
                                <td>
                                    <input type="hidden" name="ScId_2nd" id="ScId_2nd">
                                    <select name="IntervStatus2" id="IntervStatus2" class="form-select form-select-sm">
                                        <option value=""></option>
                                        <option value="Selected">Selected</option>
                                        <option value="Rejected">Rejected</option>
                                        <option value="On Hold">On Hold</option>
                                        <option value="Did not Attend">Did not Attend</option>
                                    </select>
                                </td>
                            </tr>
                            <tr id="rejct_tr2" class="d-none">
                                <td></td>
                                <td>
                                    <input type="checkbox" name="RegretMail2" id="RegretMail2" value="Yes"> Send Regret
                                    mail to Candidate
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">Save changes</button>
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

                <form action="{{ route('select_cmp_dpt_for_candidate') }}" method="POST" id="cmp_dpt_form">
                    @csrf
                    <div class="modal-body">
                        <table class="table table-borderless">
                            <tr>
                                <td>Company</td>
                                <td>
                                    <input type="hidden" name="ScId_cmp" id="ScId_cmp">
                                    <select name="SelectedForC" id="SelectedForC" class="form-select form-select-sm"
                                            onchange="GetDepartment1();">
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
                        <button type="submit" class="btn btn-success">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="intervcostmodal" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-info bg-gradient">
                    <h6 class="modal-title text-white">Interview Cost</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form action="{{ route('update_interview_cost') }}" method="POST" id="interview_cost_form">
                    @csrf
                    <div class="modal-body">
                        <table class="table table-borderless">
                            <tr>
                                <td>Travel:</td>
                                <td>
                                    <input type="hidden" name="IntervCost_JAId" id="IntervCost_JAId">
                                    <input type="text" name="Travel" id="Travel" class="form-control form-control-sm">
                                </td>
                            </tr>
                            <tr>
                                <td>Lodging</td>
                                <td>
                                    <input type="text" name="Lodging" id="Lodging" class="form-control form-control-sm">
                                </td>
                            </tr>
                            <tr>
                                <td>Relocation</td>
                                <td>
                                    <input type="text" name="Relocation" id="Relocation"
                                           class="form-control form-control-sm">
                                </td>
                            </tr>
                            <tr>
                                <td>Other</td>
                                <td>
                                    <input type="text" name="Other" id="Other" class="form-control form-control-sm">
                                </td>
                            </tr>

                        </table>


                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('script_section')
    <script>
        $("#InterviewPannel").select2();

        function GetDepartment() {
            var CompanyId = $('#Fill_Company').val();
            $.ajax({
                type: "GET",
                url: "{{ route('getDepartment') }}?CompanyId=" + CompanyId,
                success: function (res) {
                    if (res) {
                        $("#Fill_Department").empty();
                        $("#Fill_Department").append(
                            '<option value="" selected>Select Department</option>');
                        $.each(res, function (key, value) {
                            $("#Fill_Department").append('<option value="' + value + '">' +
                                key +
                                '</option>');
                        });
                    } else {
                        $("#Fill_Department").empty();
                    }
                }
            });
        }

        function GetMRF() {
            var DepartmentId = $('#Fill_Department').val();
            $.ajax({
                type: "GET",
                url: "{{ route('getMRFByDepartment') }}?DepartmentId=" + DepartmentId,
                beforeSend: function () {

                },
                success: function (res) {

                    if (res) {
                        $("#Fill_JobCode").empty();
                        $("#Fill_JobCode").append(
                            '<option value="" selected>Select MRF</option>');
                        $.each(res, function (key, value) {
                            $("#Fill_JobCode").append('<option value="' + value + '">' +
                                key +
                                '</option>');
                        });
                    } else {
                        $("#Fill_JobCode").empty();
                    }
                }
            });
        }

        function GetCandidates() {
            $("#candidate_table").DataTable().draw(true);
        }

        function GetDepartment1() { // for Interview Department Selection
            var CompanyId = $('#SelectedForC').val();
            $.ajax({
                type: "GET",
                url: "{{ route('getDepartment') }}?CompanyId=" + CompanyId,
                beforeSend: function () {

                },
                success: function (res) {

                    if (res) {
                        $("#SelectedForD").empty();
                        $("#SelectedForD").append(
                            '<option value="" selected disabled >Select Department</option>');
                        $.each(res, function (key, value) {
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
            $('#candidate_table').DataTable().draw(true);

        }

        $(document).on('click', '#reset', function () {
            location.reload();
        });

        $(document).ready(function () {


            function format(d) {
                x = '';
                x = x +
                    '<table class="table" style="background-color:">' +
                    '<tr>' +
                    '<td style="text-align:left;width:25%">Candidate Phone</td>' +
                    '<td style="text-align:left;width:25%">' + d.Phone + '</td>' +
                    '<td style="text-align:left;width:25%">Candidate Email</td>' +
                    '<td style="text-align:left;width:25%">' + d.Email + '</td>' +
                    '</tr>' +
                    '<tr><td colspan="8" class="fw-bold">1st Interview</td></tr>' +
                    '<tr>' +
                    '<td style="text-align:left;">Interview Date:</td>' ;
                if (d.IntervDt != null) {
                    x = x + '<td style="text-align:left;width:25%">' + d.IntervDt.split("-").reverse().join("-") +
                        '</td>';
                } else {
                    x = x + '<td style="text-align:left;width:25%">null</td>';
                }
                x = x + '<td style="text-align:left;">Location:</td>' +
                    '<td style="text-align:left;">' + d.IntervLoc + '</td>' +
                    '<td style="text-align:left;">Panel Member</td>' +
                    '<td style="text-align:left;" colspan="3">' + d.IntervPanel + '</td>' +
                    '</tr>';

                if (d.IntervStatus == '2nd Round Interview') {
                    x = x + '<tr><td colspan="8" class="fw-bold">2nd Round Interview</td></tr>' +
                        '<tr>' +
                        '<td style="text-align:left;">Interview Date:</td>' +
                        '<td style="text-align:left;">' + d.IntervDt2.split("-").reverse().join("-") +
                        '</td>' +
                        '<td style="text-align:left;">Location:</td>' +
                        '<td style="text-align:left;">' + d.IntervLoc2 + '</td>' +
                        '<td style="text-align:left;">Panel Member</td>' +
                        '<td style="text-align:left;" colspan="3">' + d.IntervPanel2 + '</td>' +
                        '</tr>';
                }

                x = x +
                    '<tr><td colspan="8" class="fw-bold">Interview Cost <i class="fa fa-pencil text-success" onclick="editIntervCost(' +
                    d.JAId + ')" style="cursor:pointer"></i></td></tr>' +
                    '<tr>' +
                    '<td style="text-align:left;">Travel:</td>' +
                    '<td style="text-align:left;">' + d.Travel +
                    '</td>' +
                    '<td style="text-align:left;">Lodging:</td>' +
                    '<td style="text-align:left;">' + d.Lodging + '</td>' +
                    '<td style="text-align:left;">Relocation</td>' +
                    '<td style="text-align:left;">' + d.Relocation + '</td>' +
                    '<td style="text-align:left;">Other</td>' +
                    '<td style="text-align:left;">' + d.Other + '</td>' +
                    '</tr>';
                x = x + '</table>';
                return x;
            }


            $('#candidate_table tbody').on('click', 'td.details-control', function () {
                var tr = $(this).closest('tr');
                var row = table.row(tr);

                if (row.child.isShown()) {
                    row.child.hide();
                    tr.removeClass('shown');
                } else {
                    // Open this row
                    row.child(format(row.data())).show();
                    tr.addClass('shown');
                }
            });


            table = $('#candidate_table').DataTable({
                processing: true,
                serverSide: true,
                ordering: false,
                searching: false,
                lengthChange: true,
                info: true,
                lengthMenu: [
                    [10, 25, 50, -1],
                    [10, 25, 50, "All"]
                ],
                destroy: true,
                // dom: 'Blfrtip',
                buttons: [{
                    extend: 'excelHtml5',
                    text: '<i class="fa fa-file-excel-o"></i>',
                    titleAttr: 'Excel',
                    title: $('.download_label').html(),
                    exportOptions: {
                        columns: ':visible'

                    }
                },


                    {
                        extend: 'pdfHtml5',
                        text: '<i class="fa fa-file-pdf-o"></i>',
                        titleAttr: 'PDF',
                        title: $('.download_label').html(),
                        exportOptions: {
                            columns: ':visible'

                        }
                    },

                    {
                        extend: 'print',
                        text: '<i class="fa fa-print"></i>',
                        titleAttr: 'Print',
                        title: $('.download_label').html(),
                        customize: function (win) {
                            $(win.document.body)
                                .css('font-size', '10pt');

                            $(win.document.body).find('table')
                                .addClass('compact')
                                .css('font-size', 'inherit');
                        },
                        exportOptions: {
                            columns: ':visible'
                        }
                    },


                ],
                ajax: {
                    url: "{{ route('getInterviewTrackerCandidate') }}",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: function (d) {
                        d.Company = $('#Fill_Company').val();
                        d.Department = $('#Fill_Department').val();
                        d.JPId = $('#Fill_JobCode').val();
                        d.Name = $('#Fill_Name').val();
                    },
                    type: 'POST',
                    dataType: "JSON",
                },
                columns: [{
                    "className": 'details-control',
                    "orderable": false,
                    "data": null,
                    "defaultContent": ''
                },
                    /*  {
                         data: 'chk',
                         name: 'chk'
                     }, */
                    {
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    /*{
                        data: 'ReferenceNo',
                        name: 'ReferenceNo'
                    },*/
                    {
                        data: 'Name',
                        name: 'Name'
                    },
                    /*{
                        data:'Phone',
                        name:'Phone'
                    },
                    {
                        data:'Email',
                        name:'Email'
                    },*/
                    {
                        data: 'Department',
                        name: 'Department'
                    },
                    {
                        data: 'sub_department',
                        name: 'sub_department'
                    },
                    /*  {
                          data: 'JobCode',
                          name: 'JobCode'
                      },*/
                    {
                        data: 'FIROB_Test',
                        name: 'FIROB_Test',
                        className: 'text-center'
                    }, {
                        data: 'InterviewSubmit',
                        name: 'InterviewSubmit',
                        className: 'text-center'
                    },
                    {
                        data: 'TestScore',
                        name: 'TestScore',
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
                    if (data['IntervStatus'] == 'Selected' || data['IntervStatus'] == null) {

                        $(cells[8]).css('background-color', 'rgb(218 209 237)')
                        $(cells[9]).css('background-color', 'rgb(218 209 237)')
                    }
                }

            });

            $(document).on('change', '#IntervStatus', function () {
                var IntervStatus = $(this).val();
                if (IntervStatus == 'Rejected') {
                    $("#rejct_tr").removeClass('d-none');
                } else if (IntervStatus == '2nd Round Interview') {
                    $("#intervsch_tr").removeClass('d-none');
                    $("#intervdt_tr").removeClass('d-none');
                    $("#intervtime_tr").removeClass('d-none');
                    $("#intervloc_tr").removeClass('d-none');
                    $("#intervpannel_tr").removeClass('d-none');
                    $("#intervelg_tr").removeClass('d-none');
                    $("#intervmail_tr").removeClass('d-none');
                    $("#RejectRemark").removeClass('reqinp');
                    $("#InterviewDate").addClass('reqinp');
                    $("#InterviewTime").addClass('reqinp');
                    $("#InterviewLocation").addClass('reqinp');
                    $("#InterviewPannel").addClass('reqinp');

                    $("#InterviewPannel").addClass('reqinp');
                    $("#rejct_tr").addClass("d-none");
                    $("#blaclist_tr").addClass("d-none");
                }
            });
            $(document).on('change', '#IntervStatus2', function () {
                var IntervStatus2 = $(this).val();
                if (IntervStatus2 == 'Rejected') {
                    $("#rejct_tr2").removeClass('d-none');
                } else {
                    $("#rejct_tr2").addClass('d-none');
                }
            });
        });

        $(document).on('click', '.select_all', function () {
            if ($(this).prop("checked") == true) {
                $(this).closest("tr").addClass("bg-secondary bg-gradient text-light");
            } else {
                $(this).closest("tr").removeClass("bg-secondary bg-gradient text-light");
            }
        });

        function editInt(JAId, ScId) {
            getCandidateName(JAId);
            getInterviewDetail(JAId);
            $('#ScId').val(ScId);
            $('#myModal').modal('show');
        }

        function getInterviewDetail(JAId) {
            $.ajax({
                type: "POST",
                url: "{{ route('getInterviewDetail') }}?JAId=" + JAId,
                success: function (res) {
                    if (res) {

                        $('#JAId').val(res.CandidateDetail.JAId);
                        $('#TechScreenStatus').val(res.CandidateDetail.ScreenStatus);

                        if (res.CandidateDetail.IntervStatus == '2nd Round Interview') {

                            $("#intervsch_tr").removeClass('d-none');
                            $("#intervdt_tr").removeClass('d-none');
                            $("#intervtime_tr").removeClass('d-none');
                            $("#intervloc_tr").removeClass('d-none');
                            $("#intervpannel_tr").removeClass('d-none');
                            $("#intervelg_tr").removeClass('d-none');
                            $("#intervmail_tr").removeClass('d-none');
                            $("#RejectRemark").removeClass('reqinp');
                            $("#InterviewDate").addClass('reqinp');
                            $("#InterviewTime").addClass('reqinp');
                            $("#InterviewLocation").addClass('reqinp');
                            $("#InterviewPannel").addClass('reqinp');

                            $("#InterviewPannel").addClass('reqinp');
                            $("#rejct_tr").addClass("d-none");
                            $("#blaclist_tr").addClass("d-none");
                        }
                        $("input[name=InterviewSchedule][value=" + res.CandidateDetail.InterviewMode2 + "]")
                            .attr('checked',
                                'checked');
                        if (res.CandidateDetail.InterviewMode2 == 'online') {
                            $("#intervlink_tr").removeClass('d-none');
                            $("#intervloc_tr").addClass('d-none');
                        } else if (res.CandidateDetail.InterviewMode2 == 'offline') {
                            $("#intervlink_tr").addClass('d-none');
                            $("#intervloc_tr").removeClass('d-none');
                        }

                        $("#IntervStatus").val(res.CandidateDetail.IntervStatus);
                        $("#InterviewDate").val(res.CandidateDetail.IntervDt2);
                        $("#InterviewLink").val(res.CandidateDetail.IntervLink2);


                        $("#InterviewTime").val(res.CandidateDetail.IntervTime2);
                        if (res.CandidateDetail.IntervLoc2 == null) {
                            $("#InterviewLocation").val("VNR Seeds Pvt Ltd. Canal Road Crossing, Ring Road No. 1 Raipur, Chhattisgarh, IN");
                        } else {
                            $("#InterviewLocation").val(res.CandidateDetail.IntervLoc2);
                        }
                        var panel = res.CandidateDetail.IntervPanel2;

                        $("#InterviewPannel").val(panel).trigger('change');


                        $("#TravelElg").val(res.CandidateDetail.travelEligibility2);

                        $("#InterviewMail").val(res.CandidateDetail.SendInterMail2);
                        $("#InterviewMailToPanel").val(res.CandidateDetail.PanelMail2);
                    }
                }
            });
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
                success: function (res) {
                    if (res) {
                        $("#candidatename").html('1st Round Interview Edit (' + res + ' )');
                        $("#candidatename1").html('2nd Round Interview Edit (' + res + ' )');
                        $("#candidatename_company").html('Selection for (' + res + ' )');
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
            let Score = $('#TestScore' + JAId).val();
            $.ajax({
                url: "{{ route('SaveTestScore') }}",
                type: 'POST',
                data: {
                    JAId: JAId,
                    Score: Score
                },
                dataType: 'json',

                success: function (data) {
                    if (data.status === 200) {

                        $('#candidate_table').DataTable().ajax.reload(null, false);
                        toastr.success(data.msg);
                    } else {
                        toastr.error(data.msg);
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
                    $("#loader").modal('show');
                },
                success: function (data) {
                    $("#loader").modal('hide');
                    if (data.status == 400) {
                        $.each(data.error, function (prefix, val) {
                            $(form).find('span.' + prefix + '_error').text(val[0]);
                        });
                    } else {
                        $(form)[0].reset();
                        $('#myModal').modal('hide');
                        $('#candidate_table').DataTable().ajax.reload(null, false);
                        toastr.success(data.msg);
                    }
                }
            });
        });

        $('#second_interview_form').on('submit', function (e) {
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
                        $('#2ndInterviewModal').modal('hide');
                        $('#candidate_table').DataTable().ajax.reload(null, false);
                        toastr.success(data.msg);
                    }
                }
            });
        });

        $('#cmp_dpt_form').on('submit', function (e) {
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
                        $('#companyModal').modal('hide');
                        $('#candidate_table').DataTable().ajax.reload(null, false);
                        toastr.success(data.msg);
                    }
                }
            });
        });

        function editIntervCost(JAId) {
            var JAId = JAId;
            $("#IntervCost_JAId").val(JAId);
            $.ajax({
                type: "POST",
                url: "{{ route('get_interview_cost') }}?JAId=" + JAId,
                success: function (res) {

                    $("#Travel").val(res.data.Travel);
                    $("#Relocation").val(res.data.Relocation);
                    $("#Lodging").val(res.data.Lodging);
                    $("#Other").val(res.data.Other);

                }
            });
            $('#intervcostmodal').modal('show');

        }

        $('#interview_cost_form').on('submit', function (e) {
            e.preventDefault();
            var form = this;
            $.ajax({
                url: $(form).attr('action'),
                method: $(form).attr('method'),
                data: new FormData(form),
                processData: false,
                dataType: 'json',
                contentType: false,

                success: function (data) {
                    if (data.status == 400) {
                        toastr.error(data.msg);
                    } else {
                        $(form)[0].reset();
                        $('#intervcostmodal').modal('hide');
                        $('#candidate_table').DataTable().ajax.reload(null, false);
                        toastr.success(data.msg);
                    }
                }
            });
        });

        $('#intervcostmodal').on('hidden.bs.modal', function () {
            $('#intervcostmodal form')[0].reset();
        });
        $('#myModal').on('hidden.bs.modal', function () {
            window.location.reload();
        });
        $('input[name="InterviewSchedule"]').change(function () {

            var selectedValue = $('input[name="InterviewSchedule"]:checked').val();

            if (selectedValue === "online") {
                $("#intervlink_tr").removeClass('d-none');
                $("#intervloc_tr").addClass('d-none');
            } else {
                $("#intervlink_tr").addClass('d-none');
                $("#intervloc_tr").removeClass('d-none');
            }
        });
    </script>
@endsection
