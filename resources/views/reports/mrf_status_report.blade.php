@php

    $Year = date('Y');

@endphp
@extends('layouts.master')
@section('title', 'MRF Report')
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
            padding: 2px 4px !important;
            font-size: 11px;
            cursor: pointer;
        }

        .table thead th {
            font-weight: normal;
        }
    </style>
    <div class="page-content">


        <div class="card border-top border-0 border-4 border-danger mb-3 ">
            <div class="card-body" style="padding-top:5px;">
                <div class="col-12 d-flex justify-content-between" style="padding:5px;">
                    <span class="d-inline fw-bold">Filter</span>
                    <span class="text-danger fw-bold" style="font-size: 14px; cursor: pointer;" id="reset"><i
                            class="bx bx-refresh"></i>Reset</span>
                </div>
                <div class="row">
                    <div class="col-2">
                        <select name="Fill_Company" id="Fill_Company" class="form-select form-select-sm"
                            onchange="GetDepartment();">
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
                    <div class="col-2">

                        <select name="Fill_Department" id="Fill_Department" class="form-select form-select-sm"
                            onchange="GetApplications();">
                            <option value="">Select Department</option>
                        </select>

                    </div>
                    <div class="col-2">
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
                    <div class="col-2">
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
                    <div class="col-2">
                        <select name="Status" id="Status" class="form-select form-select-sm">
                            <option value="">Select MRF Status</option>
                            <option value="Approved">Active</option>
                            <option value="Close">Closed</option>
                            <option value="Rejected">Rejected</option>
                            <option value="New">Pending</option>

                        </select>
                        @if (isset($_REQUEST['Status']) && $_REQUEST['Status'] != '')
                            <script>
                                $('#Status').val('<?= $_REQUEST['Status'] ?>');
                            </script>
                        @endif
                    </div>

                    <div class="col-2">
                        <select name="Recruiter" id="Recruiter" class="form-select form-select-sm">
                            <option value="">Select Recruiter</option>
                            @foreach ($recruiters as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                        @if (isset($_REQUEST['Recruiter']) && $_REQUEST['Recruiter'] != '')
                            <script>
                                $('#Recruiter').val('<?= $_REQUEST['Recruiter'] ?>');
                            </script>
                        @endif
                    </div>

                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card border-top border-0 border-4 border-success mb-1">
                <div class="card-body">
                    <table class="table table-bordered text-center" id="myTable">
                        <thead class="text-center bg-success bg-gradient text-light">
                            <th scope="col">S.No.</th>
                            <th scope="col">Department</th>
                            <th>Type</th>
                            <th scope="col">MRF</th>
                            <th scope="col">No. of Openings</th>
                            <th scope="col">Total Application</th>
                            <th scope="col">HR Screening</th>
                            <th scope="col">HR Fwd to Tech Scr</th>
                            <th scope="col">Technical Screening</th>
                            <th scope="col">Interviewed</th>
                            <th scope="col">Selected</th>
                            <th scope="col">Job Offered</th>
                            <th scope="col">Offer Accepted</th>
                            <th scope="col">Offer Rejected</th>
                            <th scope="col">Joined</th>
                            <th scope="col">Yet to Join</th>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
@endsection
@section('script_section')
    <script>
        $(document).ready(function() {
            $('#myTable').DataTable({
                processing: true,
                serverSide: true,
                ordering: false,
                searching: false,
                lengthChange: true,
                lengthMenu: [
                    [10, 25, 50, -1],
                    [10, 25, 50, "All"]
                ],
                info: true,
                dom: 'Blfrtip',
                buttons: [{
                    extend: 'excelHtml5',
                    text: '<i class="fa fa-file-excel-o"></i> Export',
                    titleAttr: 'Excel',
                    title: $('.download_label').html(),
                }, ],
                ajax: {
                    url: "{{ route('get_mrf_report_data') }}",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: function(d) {
                        d.Company = $('#Fill_Company').val();
                        d.Department = $('#Fill_Department').val();
                        d.Year = $('#Year').val();
                        d.Month = $('#Month').val();
                        d.Status = $("#Status").val();
                        d.Recruiter = $("#Recruiter").val();
                    },
                    type: 'POST',
                    dataType: "JSON",
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'department_name',
                        name: 'department_name'
                    },
                    {
                        data:'Type',
                        name:'Type'
                    },
                    {
                        data: 'JobCode',
                        name: 'JobCode'
                    },
                    {
                        data: 'Positions',
                        name: 'Positions'
                    },
                    {
                        data: 'Total',
                        name: 'Total'
                    },
                    {
                        data: 'HR_Screening',
                        name: 'HR_Screening'
                    },
                    {
                        data: 'HR_FWD',
                        name: 'HR_FWD'
                    },
                    {
                        data: 'Technical_Screening',
                        name: 'Technical_Screening'
                    },
                    {
                        data: 'Interviewed',
                        name: 'Interviewed'
                    },
                    {
                        data: 'Selected',
                        name: 'Selected'
                    },
                    {
                        data: 'Offered',
                        name: 'Offered'
                    },
                    {
                        data: 'Accepted',
                        name: 'Accepted'
                    },
                    {
                        data: 'Rejected',
                        name: 'Rejected'
                    },
                    {
                        data: 'Joined',
                        name: 'Joined'
                    },
                    {
                        data: 'Yet_to_Joined',
                        name: 'Yet_to_Joined'
                    },
                ],
            });

            $(document).on('change', '#Fill_Company', function() {
                Filter_Data();
            });
            $(document).on('change', '#Fill_Department', function() {
                Filter_Data();
            });
            $(document).on('change', '#Year', function() {
                Filter_Data();
            });
            $(document).on('change', '#Month', function() {
                Filter_Data();
            });
            $(document).on('change', '#Status', function() {
                Filter_Data();
            });
            $(document).on('change', '#Recruiter', function() {
                Filter_Data();
            });
            $(document).on('click', '#reset', function() {
                window.location.reload();
            });
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

        function Filter_Data() {
            $("#myTable").DataTable().draw(true);
        }
    </script>
@endsection
