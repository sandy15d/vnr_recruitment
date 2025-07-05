@extends('layouts.master')
@section('title', 'Old Trainee')
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
                    Old Trainee
                </div>

            </div>
            <div class="row mb-3">
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
                <span style="font-weight: bold;">↱</span>
                <label class="text-primary"><input id="checkall" type="checkbox" name="">&nbsp;Check
                    all</label>
                <i class="text-muted" style="font-size: 13px;">With selected:</i> 
                <label class="text-primary " style=" cursor: pointer;" data-bs-toggle="modal"
                       data-bs-target="#AddJobPostModal"><i class="fas fa-share text-primary"></i> Map Candidate to Job
                </label>
                </span>
                <table class="table table-condensed" id="CampusApplication"
                       style="width: 100%; margin-right:20px;">
                    <thead class="text-center bg-success bg-gradient text-light">
                    <tr>
                        <td>#</td>
                        <td>S.No.</td>
                        <td>Trainee ID</td>
                        <td>Reference No.</td>
                        <td>Name</td>
                        <td>Department</td>
                        <th>State</th>
                        <th>City</th>
                        <th>Reporting Manager</th>
                        <th> Joined</th>
                        <td>DOJ</td>
                        <td>DOC</td>
                        <td>Stipend</td>
                        <td>Other Benifit</td>
                        <td style="width: 15%">Action</td>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>




    <div class="modal fade" id="expense_list_modal" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog  modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-info bg-gradient">
                    <h6 class="modal-title text-white" id="candidatename1"></h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <table class="table" id="expensetable" style="width: 100%">
                    <thead class="text-center bg-primary bg-gradient text-light">
                    <th>S.No.</th>
                    <th>Year</th>
                    <th>Month</th>
                    <th>Stipend</th>
                    <th>Expense</th>
                    <th>Total</th>
                    </thead>
                    <tbody id="expense_list" class="text-center">

                    </tbody>
                    <tfoot>
                    <tr>
                        <td colspan="5" class="fw-bold" style="text-align: right">Grand Total</td>
                        <td id="total" style="text-align: center" class="fw-bold"></td>
                    </tr>
                    </tfoot>
                </table>

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
                        <button type="submit" class="btn btn-primary btn-sm" id="addtojobpost">Save changes</button>
                    </div>
                </div>
            </form>
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
                lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
                info: true,
                dom: 'Blfrtip',
                destroy: true,
                buttons: [{
                    extend: 'excelHtml5',
                    text: '<i class="fa fa-file-excel-o"></i>',
                    titleAttr: 'Excel',
                    title: $('.download_label').html(),
                    exportOptions: {
                        modifier: {
                            page: 'all'
                        }

                    }
                },


                    {
                        extend: 'pdfHtml5',
                        text: '<i class="fa fa-file-pdf-o"></i>',
                        titleAttr: 'PDF',
                        title: $('.download_label').html(),
                        exportOptions: {
                            modifier: {
                                page: 'all'
                            }

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
                            modifier: {
                                page: 'all'
                            }
                        }
                    },


                ],
                ajax: {
                    url: "{{ route('get_old_trainee') }}",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: function (d) {
                        d.Company = $('#Fill_Company').val();
                        d.Department = $('#Fill_Department').val();
                        d.Year = $('#Year').val();
                        d.Month = $('#Month').val();
                        d.Name = $('#Fill_Name').val();
                    },
                    type: 'POST',
                    dataType: "JSON",
                },
                columns: [

                    {
                        data: 'chk',
                        name: 'chk',
                        'className': 'text-center'
                    },
                    {
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        'className': 'text-center'
                    },
                    {
                        data: 'TId',
                        name: 'TId',
                        'className': 'text-center'
                    },
                    {
                        data: 'ReferenceNo',
                        name: 'ReferenceNo'
                    },
                    {
                        data: 'CandidateName',
                        name: 'CandidateName'
                    },

                    {
                        data: 'Department',
                        name: 'Department'
                    },
                    {
                        data: 'State',
                        name: 'State'
                    },
                    {
                        data: 'HQ_City',
                        name: 'HQ_City'
                    },
                    {
                        data: 'Reporting',
                        name: 'Reporting'
                    },
                    {
                        data: 'Candidate_Joined',
                        name: 'Candidate_Joined'
                    },

                    {
                        data: 'Doj',
                        name: 'Doj'
                    },
                    {
                        data: 'Doc',
                        name: 'Doc'
                    },
                    {
                        data: 'Stipend',
                        name: 'Stipend'
                    },
                    {
                        data: 'OtherBenefit',
                        name: 'OtherBenefit'
                    },
                    {
                        data: 'Action',
                        name: 'Action'
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

        function getTraineeName(TId) {
            $.ajax({
                type: "POST",
                url: "{{ route('getTraineeName') }}?TId=" + TId,
                success: function (res) {
                    if (res) {
                        $("#candidatename").html('Edit Detail - (' + res + ' )');
                        $("#candidatename1").html('View Stipend / Expense - (' + res + ' )');
                        $("#candidatename2").html('Add Stipend / Expense - (' + res + ' )');
                    }
                }
            });
        }

        function view_expense(TId) {
            var TId = TId;
            getTraineeName(TId);
            $('#expensetable').DataTable({
                processing: true,
                serverSide: true,
                ordering: false,
                searching: false,
                lengthChange: true,
                lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
                info: true,
                dom: 'Blfrtip',
                buttons: [{
                    extend: 'excelHtml5',
                    text: '<i class="fa fa-file-excel-o"></i>',
                    titleAttr: 'Excel',
                    title: $('.download_label').html(),
                    exportOptions: {
                        modifier: {
                            page: 'all'
                        }

                    }
                },


                    {
                        extend: 'pdfHtml5',
                        text: '<i class="fa fa-file-pdf-o"></i>',
                        titleAttr: 'PDF',
                        title: $('.download_label').html(),
                        exportOptions: {
                            modifier: {
                                page: 'all'
                            }

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
                            modifier: {
                                page: 'all'
                            }
                        }
                    },


                ],
                destroy: true,
                ajax: {
                    url: "{{ route('get_expense_list') }}",
                    type: "POST",
                    data: {
                        TId: TId
                    },
                    dataType: "JSON",
                },
                columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex'
                },
                    {
                        data: 'Year',
                        name: 'Year'
                    },
                    {
                        data: 'Month',
                        name: 'Month'
                    },
                    {
                        data: 'Stipend',
                        name: 'Stipend'
                    },
                    {
                        data: 'Expense',
                        name: 'Expense'
                    },
                    {
                        data: 'Total',
                        name: 'Total'
                    }
                ],

                "footerCallback": function (row, data, start, end, display) {
                    var api = this.api(),
                        data;

                    // Remove the formatting to get integer data for summation
                    var intVal = function (i) {
                        return typeof i === 'string' ?
                            i.replace(/[\$,]/g, '') * 1 :
                            typeof i === 'number' ?
                                i : 0;
                    };

                    // Total over all pages
                    total = api
                        .column(5)
                        .data()
                        .reduce(function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);

                    // Total over this page
                    pageTotal = api
                        .column(5, {
                            page: 'current'
                        })
                        .data()
                        .reduce(function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);

                    // Update footer
                    $(api.column(5).footer()).html(
                        pageTotal
                    );
                }

            });
            $('#expense_list_modal').modal('show');
        }

        $('#checkall').click(function () {
            if ($(this).prop("checked") == true) {
                $('.japchks').prop("checked", true);
            } else if ($(this).prop("checked") == false) {
                $('.japchks').prop("checked", false);
            }
        });

        function checkAllorNot() {
            var allchk = 1;
            $('.japchks').each(function () {
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

        $(document).on('click', '#addtojobpost', function (e) {
            e.preventDefault();
            var TId = [];
            var JPId = $("#JPId").val();
            $("input[name='selectCand']").each(function () {
                if ($(this).prop("checked") == true) {
                    var value = $(this).val();
                    TId.push(value);
                }
            });

            if (TId.length > 0) {
                if (confirm('Are you sure to Map Selected Candidates to JobPost?')) {
                    $.ajax({
                        url: '{{ url('map_trainee_to_job') }}',
                        method: 'POST',
                        data: {
                            TId: TId,
                            JPId: JPId
                        },
                        success: function (data) {
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
    </script>
@endsection
