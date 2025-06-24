@extends('layouts.master')
@section('title', 'Trainee Report - College Wise')
@section('PageContent')
    <div class="page-content">
        @include('reports._reports_nav')
        <div class="card">
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-3">
                        <h6 class="text-success">Trainee Report - College Wise</h6>
                    </div>

                </div>
                <div class="row mb-3">
                    <div class="col-md-1">
                        <select name="Fill_Year" id="Fill_Year" class="form-select form-select-sm"
                                onchange="GetTrainee();">
                            <option value=""> Year</option>
                            @for ($i = 2021; $i <= date('Y'); $i++)
                                <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-2">
                        <select name="Fill_Company" id="Fill_Company" class="form-select form-select-sm"
                                onchange="GetTrainee(); GetDepartment();">
                            <option value="">Select Company</option>
                            @foreach ($company_list as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-2">

                        <select name="Fill_Department" id="Fill_Department" class="form-select form-select-sm"
                                onchange="GetTrainee();">
                            <option value="">Select Department</option>

                        </select>
                    </div>
                    <div class="col-3">
                        <select name="Fill_College" id="Fill_College" class="form-select form-select-sm"
                                onchange="GetTrainee();">
                            <option value="">Select College</option>
                            @foreach ($college_list as $key => $value)
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
                    <table class="table table-bordered  table-hover" id="CandidateTable">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>College</th>
                            <th>No. of Applicants</th>
                            <th>Screening (Shortlisted)</th>
                            <th>Screening (Rejected)</th>
                            <th>Screening (Pending)</th>
                            <th>Interview (Selected)</th>
                            <th>Interview (Rejected)</th>
                            <th>Interview (Pending)</th>
                            <th>Joined</th>

                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script_section')
    <script>
        $(document).ready(function () {
            $("#Fill_College").select2();
            $('#CandidateTable').DataTable({
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
                },],

                ajax: {
                    url: "{{ route('get_college_wise_trainee_data') }}",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: function (d) {
                        d.College = $('#Fill_College').val();
                       d.Year = $('#Fill_Year').val();
                        d.Company = $('#Fill_Company').val();
                        d.Department = $('#Fill_Department').val();
                    },
                    type: 'POST',
                    dataType: "JSON",
                },
                columns: [
                    {
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },

                    {
                        data: 'College',
                        name: 'College'
                    },
                    {
                        data: 'total_applicants',
                        name: 'total_applicants'
                    },
                    {
                        data: 'screening_shortlist',
                        name: 'screening_shortlist'
                    },
                    {
                        data: 'screening_reject',
                        name: 'screening_reject'
                    },
                    {
                        data: 'screening_pending',
                        name: 'screening_pending'
                    },
                    {
                        data: 'interview_selected',
                        name: 'interview_selected'
                    },
                    {
                        data: 'interview_rejected',
                        name: 'interview_rejected'
                    },
                    {
                        data: 'interview_pending',
                        name: 'interview_pending'
                    },
                    {
                        data: 'total_joined_candidates',
                        name: 'total_joined_candidates'
                    }


                ],
            });
        });

        function GetTrainee() {
            $('#CandidateTable').DataTable().draw(true);
        }

        $(document).on('click', '#reset', function () {
            location.reload();
        });

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
    </script>
@endsection
