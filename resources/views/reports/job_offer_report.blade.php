
@extends('layouts.master')
@section('title', 'Job Offers Report')
@section('PageContent')
    <div class="page-content">
        @include('reports._reports_nav')
        <div class="card">
            <div class="card-body">
                <div class="row mb-1">
                    <div class="col-3">
                        <h6 class="text-success">Job Offers Details</h6>
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
                                <th>Company</th>
                                <th>Department</th>
                                <th>Source</th>
                                <th>Other Source</th>
                                <th>Recruiter</th>
                                <th>MRF</th>
                                <th>Position</th>
                                <th>Candidate Name</th>
                                <th>Vertical</th>
                                <th>Zone</th>
                                <th>Region</th>
                                <th>Off. Ltr. Sent for review DT</th>
                                <th>Off. Ltr. Reviewed By</th>
                                <th>Review Status</th>
                                <th>Off. Ltr. sent to candidate DT</th>
                                <th>Status</th>
                                <th>DOJ</th>
                                <th style="width: 20%">Rejection Remarks</th>

                            </tr>
                        </thead>
                        <tbody>
                           {{--  @foreach ($candidate_list as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->ReferenceNo }}</td>
                                    <td>{{ getcompany_code($item->Company) }}</td>
                                    <td>{{ getDepartmentCode($item->Department) }}</td>

                                    <td>{{ $item->JobCode }}</td>
                                    <td>{{ getDesignationCode($item->Designation) }}</td>
                                    <td>{{ $item->FName }} {{ $item->LName }}</td>
                                   <td>{{$item->review_date}}</td>
                                   <td>{{getFullName($item->EmpId)}}</td>
                                   <td>
                                       {{$item->review_status}}
                                   </td>
                                   <td>{{$item->of_sent_dt}}</td>
                                   <td>{{$item->Answer}}</td>
                                   <td style="width:50px;">{{$item->RejReason}}</td>
                                   <td>{{$item->JoinOnDt}}</td>


                                </tr>
                            @endforeach --}}
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
                        url: "{{ route('get_job_offer_report') }}",
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
                            data: 'Company',
                            name: 'Company'
                        },
                        {
                            data: 'Department',
                            name: 'Department'
                        },
                        {
                            data: 'ResumeSource',
                            name: 'ResumeSource'
                        },
                        {
                            data:'OtherResumeSource',
                            name:'OtherResumeSource'
                        },
                        {
                            data: 'name',
                            name: 'name'
                        },

                        {
                            data: 'JobCode',
                            name: 'JobCode'
                        },
                        {
                            data: 'Designation',
                            name: 'Designation'
                        },
                        {
                            data: 'Name',
                            name: 'Name'
                        },
                        {
                            data: 'VerticalName',
                            name: 'VerticalName'
                        },
                        {
                            data: 'RegionName',
                            name: 'RegionName'
                        },
                        {
                            data: 'ZoneName',
                            name: 'ZoneName'
                        },
                        {
                            data: 'review_date',
                            name: 'review_date'
                        },
                        {
                            data: 'review_by',
                            name: 'review_by'
                        },
                        {
                            data: 'review_status',
                            name: 'review_status'
                        },
                        {
                            data: 'of_sent_dt',
                            name: 'of_sent_dt'
                        },
                        {
                            data: 'Answer',
                            name: 'Answer'
                        },
                        {
                            data: 'JoinOnDt',
                            name: 'JoinOnDt'
                        },
                        {
                            data: 'RejReason',
                            name: 'RejReason'
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
