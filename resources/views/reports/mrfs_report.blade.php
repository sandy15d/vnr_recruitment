@extends('layouts.master')
@section('title', 'MRF Report')
@section('PageContent')
    <div class="page-content">
        @include('reports._reports_nav')
        <div class="card">
            <div class="card-body">
                <div class="row mb-1">
                    <div class="col-2">
                        <h6 class="text-success">MRF Details</h6>
                    </div>
                    <div class="col-2">
                        <select name="Fill_Company" id="Fill_Company" class="form-select form-select-sm"
                            onchange="GetActiveMRF(); GetDepartment();">
                            <option value="">Select Company</option>
                            @foreach ($company_list as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-2">

                        <select name="Fill_Department" id="Fill_Department" class="form-select form-select-sm"
                            onchange="GetActiveMRF();">
                            <option value="">Select Department</option>

                        </select>
                    </div>
                    <div class="col-2">
                        <select name="Year" id="Year" class="form-select form-select-sm" onchange="GetActiveMRF();">
                            <option value="">Select Year</option>
                            @for ($i = 2021; $i <= date('Y'); $i++)
                                <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-2">
                        <select name="Month" id="Month" class="form-select form-select-sm" onchange="GetActiveMRF();">
                            <option value="">Select Month</option>
                            @foreach ($months as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-1">
                        <button type="reset" class="btn btn-danger btn-sm" id="reset"><i
                                class="bx bx-refresh"></i></button>
                    </div>
                </div>
                <div class="table-responsive">

                    <table class="table table-bordered  table-hover" id="MRFTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>MRF Type</th>
                                <th>If Replacement,For</th>
                                <th>Job Code</th>
                                <th>Department</th>
                                <th>Designation</th>
                                <th>No. of Openings</th>
                                <th>Position Filled</th>
                                <th>Created By</th>
                                <th>On Behalf of</th>
                                <th>Created DT</th>
                                <th>Allocated To</th>
                                <th>Status</th>
                                <th>Closing DT</th>
                                <th>Close Reason</th>

                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endsection
    @section('script_section')
        <script>
            $(document).ready(function() {
                $('#MRFTable').DataTable({
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
                        url: "{{ route('getMrfReport') }}",
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
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex'
                        },
                        {
                            data: 'Type',
                            name: 'Type'
                        },
                        {
                            data: 'ReplacementFor',
                            name: 'ReplacementFor'
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
                            data: 'Designation',
                            name: 'Designation'
                        },
                        {
                            data: 'Positions',
                            name: 'Positions'
                        },
                        {
                            data: 'Position_Filled',
                            name: 'Position_Filled'
                        },

                        {
                            data: 'CreatedBy',
                            name: 'CreatedBy'
                        },
                        {
                            data: 'OnBehalf',
                            name: 'OnBehalf'
                        },
                        {
                            data: 'CreatedTime',
                            name: 'CreatedTime'
                        },
                        {
                            data: 'Allocated',
                            name: 'Allocated'
                        },

                        {
                            data: 'Status',
                            name: 'Status'
                        },
                        {
                            data: 'CloseDt',
                            name: 'CloseDt'
                        },
                        {
                            data: 'CloseReason',
                            name: 'CloseReason'
                        }


                    ],
                });
            });

            function GetActiveMRF() {
                $('#MRFTable').DataTable().draw(true);
            }
            $(document).on('click', '#reset', function() {
                location.reload();
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
        </script>
    @endsection
