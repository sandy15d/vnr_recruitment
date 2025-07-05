
@extends('layouts.master')
@section('title', 'HR Screening Report')
@section('PageContent')
    <div class="page-content">
        @include('reports._reports_nav')
        <div class="card">
            <div class="card-body">

                <div class="row mb-1">
                    <div class="col-3">
                        <h6 class="text-success">HR Screening Details</h6>
                    </div>
                    <div class="col-2">
                        <select name="Fill_Company" id="Fill_Company" class="form-select form-select-sm"
                            onchange="get_detail(); GetDepartment();">
                            <option value="">Select Company</option>
                            @foreach ($company_list as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-2">

                        <select name="Fill_Department" id="Fill_Department" class="form-select form-select-sm"
                            onchange="get_detail();">
                            <option value="">Select Department</option>

                        </select>
                    </div>
                    <div class="col-2">
                        <select name="Year" id="Year" class="form-select form-select-sm" onchange="get_detail();">
                            <option value="">Select Year</option>
                            @for ($i = 2021; $i <= date('Y'); $i++)
                                <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-2">
                        <select name="Month" id="Month" class="form-select form-select-sm" onchange="get_detail();">
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

                    <table class="table table-bordered  table-hover" id="MRFTable" style="width: 100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Reference No</th>
                                <th>Candidate Name</th>
                                <th>Department</th>
                                <th>MRF</th>
                                <th>Screening Date</th>
                                <th>HR Screening Status</th>
                                <th>Remark</th>
                                <th>Source</th>
                                <th>Other Source</th>
                                <th>Recruiter</th>
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
                        url: "{{ route('get_hr_screening_report') }}",
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
                            data: 'ReferenceNo',
                            name: 'ReferenceNo'
                        },
                        {
                            data: 'Name',
                            name: 'Name'
                        },
                        {
                            data: 'Department',
                            name: 'Department'
                        },
                        {
                            data: 'JobCode',
                            name: 'JobCode'
                        },
                        {
                            data:'ScreeningDate',
                            name:'ScreeningDate'
                        },
                        {
                            data: 'Status',
                            name: 'Status'
                        },
                        {
                            data:'Remark',
                            name:'Remark'
                        },
                        {
                            data: 'ResumeSource',
                            name: 'ResumeSource'
                        },
                        {
                            data: 'OtherResumeSource',
                            name: 'OtherResumeSource',
                        },

                        {
                            data: 'name',
                            name: 'name'
                        },
                    ],
                });
            });

            function get_detail() {
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
