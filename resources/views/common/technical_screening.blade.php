@extends('layouts.master')
@section('title', 'Screening Tracker')
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
            <div class="row mb-2">
                <div class="col-2">
                    <h6> Screening Tracker</h6>
                </div>
            </div>
            <div class="row">
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
                    <select name="Fill_Screening" id="Fill_Screening" class="form-select form-select-sm"
                            onchange="GetCandidates();">
                        <option value="">Select Status</option>
                        <option value="All">All</option>
                        <option value="Shortlist">Shortlist</option>
                        <option value="Reject">Reject</option>
                        <option value="Pending">Pending</option>
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
                <table class="table  table-condensed" id="candidate_table"
                       style="width: 100%; margin-right:20px; ">
                    <thead class="text-center bg-success bg-gradient text-light">
                    <tr class="text-center">
                        <th style="width:30px;"></th>
                        <th>S.No.</th>
                        {{--<th>Ref. No.</th>--}}
                        <th>Candidate Name</th>
                        <th>Department</th>
                        <th>SubDepartment</th>
                        <th>MRF</th>
                        <th>Tech. Screening By</th>
                        <th>Screening Status</th>
                        <th>Interview Mail to Candidate</th>
                        <th>Interview Mail to Panel</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="TechnicalScreenModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static"
         data-bs-keyboard="false">
        <div class="modal-dialog modal-lg">
            <form action="{{ route('CandidateTechnicalScreening') }}" method="POST" id="TechScreenForm">
                <div class="modal-content">
                    <div class="modal-header bg-primary bg-gradient">
                        <h5 class="modal-title text-light" id="CandidateName"></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="">
                        <div class="modal-body">
                            <input type="hidden" name="JAId" id="JAId">
                            <table class="table">
                                <tr>
                                    <th style="width: 30%">Resume Sent For Tech.Screening</th>
                                    <td style="width: 70%"><input type="date" name="ReSentForScreen"
                                                                  id="ReSentForScreen"
                                                                  class="form-control form-control-sm" disabled></td>
                                </tr>
                                <tr>
                                    <th>Resume Screened By</th>
                                    <td><input type="text" name="ScreeningBy" id="ScreeningBy"
                                               class="form-control form-control-sm" disabled></td>
                                </tr>
                                <tr>
                                    <th>Date Resume Screened</th>

                                    <td><input type="date" name="TechScreeningDate" id="TechScreeningDate"
                                               class="form-control form-control-sm">
                                    </td>
                                </tr>
                                <tr>
                                    <th>Techical Screening Status</th>
                                    <td>
                                        <select name="TechScreenStatus" id="TechScreenStatus"
                                                class="form-select form-select-sm reqinp">
                                            <option value="">Select</option>
                                            <option value="Shortlist">Shortlist</option>
                                            <option value="Reject">Reject</option>

                                        </select>
                                    </td>
                                </tr>
                                <tr id="rejct_tr" class="d-none">
                                    <th>Reject Remark</th>
                                    <td>
                                        <textarea name="RejectRemark" id="RejectRemark" rows="2"
                                                  class="form-control form-control-sm"></textarea>
                                        <input type="checkbox" name="RegretMail" id="RegretMail" value="Yes"> Send
                                        Regret
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
                                        {{--  <textarea name="InterviewPannel" id="InterviewPannel" class="form-control form-control-sm"></textarea>--}}
                                        <select name="InterviewPannel[]" id="InterviewPannel"
                                                class="form-select form-select-sm select2" multiple>
                                            @foreach($emplist as $list)
                                                <option value="{{$list->EmployeeID}}">{{$list->EmpCode}}
                                                    - {{$list->Fname}} {{$list->Sname}} {{$list->Lname}}</option>
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
                                                <th style="width: 25%">Interview Mail to Candidate</th>
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

                                <tr id="blaclist_tr" class="d-none">
                                    <th></th>
                                    <td>
                                        <input type="checkbox" name="BlackList" id="BlackList" value="1"> BlackList
                                        Candidate
                                    </td>
                                </tr>
                                <tr id="blacklistremark_tr" class="d-none">
                                    <th>Remark for Blacklisting:</th>
                                    <td>
                                        <textarea name="BlackListRemark" id="BlackListRemark" rows="2"
                                                  class="form-control form-control-sm"></textarea>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary btn-sm">Save changes</button>
                        </div>
                    </form>
                </div>
            </form>
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
                beforeSend: function () {

                },
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
            $('#candidate_table').DataTable().draw(true);

        }

        $(document).on('click', '#reset', function () {
            location.reload();
        });

        function getCandidateName(JAId) {
            $.ajax({
                type: "POST",
                url: "{{ route('getCandidateName') }}?JAId=" + JAId,
                success: function (res) {
                    if (res) {
                        $("#CandidateName").html(res);
                    }
                }
            });
        }

        function getScreenDetail(JAId) {
            $.ajax({
                type: "POST",
                url: "{{ route('getScreenDetail') }}?JAId=" + JAId,
                success: function (res) {
                    if (res) {
                        $('#ReSentForScreen').val(res.CandidateDetail.ReSentForScreen);
                        $('#ScreeningBy').val(res.CandidateDetail.Fname + ' ' + res.CandidateDetail.Sname +
                            ' ' + res.CandidateDetail.Lname);
                        $('#JAId').val(res.CandidateDetail.JAId);
                        $('#TechScreenStatus').val(res.CandidateDetail.ScreenStatus);

                        if (res.CandidateDetail.ScreenStatus == 'Shortlist') {
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
                        } else if (res.CandidateDetail.ScreenStatus == 'Reject') {
                            $("#rejct_tr").removeClass('d-none');
                            $("#blaclist_tr").removeClass('d-none');
                            $("#intervsch_tr").addClass('d-none');
                            $("#intervdt_tr").addClass('d-none');
                            $("#intervtime_tr").addClass('d-none');
                            $("#intervloc_tr").addClass('d-none');
                            $("#intervpannel_tr").addClass('d-none');
                            $("#intervelg_tr").addClass('d-none');
                            $("#intervmail_tr").addClass('d-none');
                            $("#RejectRemark").addClass('reqinp');
                            $("#InterviewDate").removeClass('reqinp');
                            $("#InterviewTime").removeClass('reqinp');
                            $("#InterviewLocation").removeClass('reqinp');
                            $("#InterviewPannel").removeClass('reqinp');

                            $("#InterviewPannel").removeClass('reqinp');
                        }
                        $("input[name=InterviewSchedule][value=" + res.CandidateDetail.InterviewMode + "]")
                            .attr('checked',
                                'checked');
                        if (res.CandidateDetail.InterviewMode == 'online') {
                            $("#intervlink_tr").removeClass('d-none');
                            $("#intervloc_tr").addClass('d-none');
                        } else {
                            $("#intervlink_tr").addClass('d-none');
                            $("#intervloc_tr").removeClass('d-none');
                        }
                        $("#InterviewDate").val(res.CandidateDetail.IntervDt);
                        $("#InterviewLink").val(res.CandidateDetail.IntervLink);
                        if (res.CandidateDetail.ResScreened != null) {
                            $("#TechScreeningDate").val(res.CandidateDetail.ResScreened);
                        }

                        $("#InterviewTime").val(res.CandidateDetail.IntervTime);
                        if (res.CandidateDetail.IntervLoc == null) {
                            $("#InterviewLocation").val("VNR Seeds Pvt Ltd. Canal Road Crossing, Ring Road No. 1 Raipur, Chhattisgarh, IN");
                        } else {
                            $("#InterviewLocation").val(res.CandidateDetail.IntervLoc);
                        }
                        var panel = res.CandidateDetail.IntervPanel;

                        $("#InterviewPannel").val(panel).trigger('change');


                        $("#TravelElg").val(res.CandidateDetail.travelEligibility);

                        $("#InterviewMail").val(res.CandidateDetail.SendInterMail);
                        $("#InterviewMailToPanel").val(res.CandidateDetail.PanelMail);
                    }
                }
            });
        }

        function EditTracker(JAId) {
            getCandidateName(JAId);
            getScreenDetail(JAId);
            $('#TechnicalScreenModal').modal('show');
        }

        function checkRequired() {
            var res = 0;
            $('.reqinp').each(function () {
                if ($(this).val() == '' || $(this).val() == null) {
                    $(this).addClass('errorfield');
                    res = 1;
                } else {
                    $(this).removeClass('errorfield');
                }
            });
            return res;
        }

        $(document).ready(function () {
            $('#TechScreenStatus').on('change', function () {
                var TechScreenStatus = $(this).val();
                if (TechScreenStatus == 'Reject') {
                    $("#rejct_tr").removeClass('d-none');
                    $("#blaclist_tr").removeClass('d-none');
                    $("#intervsch_tr").addClass('d-none');
                    $("#intervdt_tr").addClass('d-none');
                    $("#intervtime_tr").addClass('d-none');
                    $("#intervloc_tr").addClass('d-none');
                    $("#intervpannel_tr").addClass('d-none');
                    $("#intervelg_tr").addClass('d-none');
                    $("#intervmail_tr").addClass('d-none');

                    $("#RejectRemark").addClass('reqinp');

                    $("#InterviewDate").removeClass('reqinp');
                    $("#InterviewTime").removeClass('reqinp');
                    $("#InterviewLocation").removeClass('reqinp');
                    $("#InterviewPannel").removeClass('reqinp');
                    //    $("#TravelElg").removeClass('reqinp');
                    $("#InterviewPannel").removeClass('reqinp');


                } else {
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
                    //     $("#TravelElg").addClass('reqinp');
                    $("#InterviewPannel").addClass('reqinp');

                    $("#rejct_tr").addClass("d-none");
                    $("#blaclist_tr").addClass("d-none");
                }
            });


            function format(d) {
                x = '';
                x = x +
                    '<table class="table table-bordered bg-light text-dark">' +
                    '<tr>' +
                    '<td style="text-align:left;width:25%">Candidate Phone</td>' +
                    '<td style="text-align:left;width:25%">' + d.Phone + '</td>' +
                    '<td style="text-align:left;width:25%">Candidate Email</td>' +
                    '<td style="text-align:left;width:25%">' + d.Email + '</td>' +
                    '</tr>' +
                    '<tr>' +
                    '<td style="text-align:left;width:25%">Resume Sent for<br>Technical Screeing</td>' +
                    '<td style="text-align:left;width:25%">' + d.ReSentForScreen.split("-").reverse().join("-") +
                    '</td>' +
                    '<td style="text-align:left;width:25%">Date Resume Screened</td>';

                if (d.ResScreened != null) {
                    x = x + '<td style="text-align:left;width:25%">' + d.ResScreened.split("-").reverse().join(
                        "-") + '</td>';
                }
                x = x + '</td>' +
                    '</tr>' +

                    '<tr>' +
                    '<td style="text-align:left;width:25%">Technical Screen By</td>' +
                    '<td style="text-align:left;width:25%">' + d.ScreenedBy + '</td>' +
                    '<td style="text-align:left;width:25%">Screening Status</td>' +
                    '<td style="text-align:left;width:25%">' + d.ScreenStatus + '</td>' +
                    '</tr>';


                if (d.ScreenStatus == 'Shortlist') {

                    x = x + '<tr>' +
                        '<td>Technical Screening Remaks:</td>' +
                        '<td>' + d.screening_remark + '</td>' +
                        '<tr>';

                    x = x + '<tr>' +
                        '<td style="text-align:left;width:25%">Interview Date:</td>';
                    if (d.IntervDt != null) {
                        x = x + '<td style="text-align:left;width:25%">' + d.IntervDt.split("-").reverse().join("-") +
                            '</td>';
                    } else {
                        x = x + '<td style="text-align:left;width:25%">null</td>';
                    }

                    x = x + '<td style="text-align:left;width:25%">Interview Time</td>' +
                        '<td style="text-align:left;width:25%">' + d.interviewTime + '</td>' +
                        '</tr>' +

                        '<tr>' +
                        '<td style="text-align:left;width:25%">Venue:</td>' +
                        '<td style="text-align:left;width:25%">' + d.IntervLoc + '</td>' +
                        '<td style="text-align:left;width:25%">Mode of Interview:</td>' +
                        '<td style="text-align:left;width:25%">' + d.InterviewMode + '</td>' +
                        '</tr>' +

                        '<tr>' +
                        '<td style="text-align:left;width:25%">Panel</td>' +
                        '<td style="text-align:left;width:25%">' + d.IntervPanel + '</td>' +
                        '<td style="text-align:left;width:25%">Travel Eligibility</td>' +
                        '<td style="text-align:left;width:25%">' + d.travelElg + '</td>' +
                        '</tr>';
                } else {
                    x = x + '<tr>' +
                        '<td style="text-align:left;width:25%">Rejection Remark</td>' +
                        '<td  colspan="3" style="text-align:left;width:25%">' + d.RejectionRem + '</td>' +
                        '</tr>';
                }
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
        });

        table = $('#candidate_table').DataTable({
            processing: true,
            serverSide: true,
            info: true,
            searching: false,
            ordering: false,
            lengthChange: true,
            lengthMenu: [
                [10, 25, 50, -1],
                [10, 25, 50, "All"]
            ],
            destroy: true,
            dom: 'Blfrtip',
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
                url: "{{ route('getTechnicalSceeningCandidate') }}",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: function (d) {
                    d.Company = $('#Fill_Company').val();
                    d.Department = $('#Fill_Department').val();
                    d.JPId = $('#Fill_JobCode').val();
                    d.Name = $('#Fill_Name').val();
                    d.Status = $('#Fill_Screening').val();
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
                    name: 'DT_RowIndex',
                    'className': 'text-center'
                },

                /*{
                    data: 'ReferenceNo',
                    name: 'ReferenceNo'
                },*/
                {
                    data: 'Name',
                    name: 'Name',

                },
               /* {
                    data: 'Phone',
                    name: 'Phone'
                },
                {
                    data: 'Email',
                    name: 'Email'
                },*/
                {
                    data: 'Department',
                    name: 'Department'
                },
                {
                    data: 'sub_department',
                    name: 'sub_department'
                },
                {
                    data: 'JobCode',
                    name: 'JobCode'
                },

                {
                    data:'ScreenedBy',
                    name:'ScreenedBy',
                },
                {
                    data: 'ScreenStatus',
                    name: 'ScreenStatus',
                    'className': 'text-center'
                },

                {
                    data: 'SendInterMail',
                    name: 'SendInterMail',
                    'className': 'text-center'
                },
                {
                    data: 'PanelMail',
                    name: 'PanelMail',
                    'className': 'text-center'
                },
                {
                    data: 'Action',
                    name: 'Action',
                    'className': 'text-center'
                }

            ],
            "createdRow": function (row, data, name) {
                if (data['ScreenStatus'] == 'Reject') {
                    //  $(row).addClass('bg-danger text-light');

                } else if (data['ScreenStatus'] == 'Shortlist') {
                    // $(row).addClass('bg-success text-light');
                }
            }
        });

        $('#checkall').click(function () {
            if ($(this).prop("checked") == true) {
                $('.japchks').prop("checked", true);
            } else if ($(this).prop("checked") == false) {
                $('.japchks').prop("checked", false);
            }
        });

        $('#TechScreenForm').on('submit', function (e) {
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
                    beforeSend: function () {
                        $(form).find('span.error-text').text('');
                        $("#loader").modal('show');
                    },
                    success: function (data) {
                        if (data.status == 400) {
                            $("#loader").modal('hide');
                            $.each(data.error, function (prefix, val) {
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

        $("#BlackList").change(function () {
            if (!this.checked) {
                $("#blacklistremark_tr").addClass("d-none");
                $("#BlackListRemark").removeClass("reqinp");
            } else {
                $("#blacklistremark_tr").removeClass("d-none");
                $("#BlackListRemark").addClass("reqinp");
            }
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
