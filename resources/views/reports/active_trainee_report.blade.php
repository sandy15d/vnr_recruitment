@extends('layouts.master')
@section('title', 'Trainee Report -  Selected')
@section('PageContent')
    <div class="page-content">
        @include('reports._reports_nav')
        <div class="card">
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-3">
                        <h6 class="text-success">Trainee Report - Selected</h6>
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
                    <div class="col-md-2">
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
                    <table class="table table-bordered  table-striped" id="CandidateTable">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Reference No</th>
                            <th>DOJ</th>
                            <th>Candidate</th>
                            <th>Gender</th>
                            <th>Education</th>
                            <th>College</th>
                            <th>Jan St.</th>
                            <th>Jan Exp.</th>
                            <th>Feb St.</th>
                            <th>Feb Exp.</th>
                            <th>Mar St.</th>
                            <th>Mar Exp.</th>
                            <th>Apr St.</th>
                            <th>Apr Exp.</th>
                            <th>May St.</th>
                            <th>May Exp.</th>
                            <th>Jun St.</th>
                            <th>Jun Exp.</th>
                            <th>Jul St.</th>
                            <th>Jul Exp.</th>
                            <th>Aug St.</th>
                            <th>Aug Exp.</th>
                            <th>Sep St.</th>
                            <th>Sep Exp.</th>
                            <th>Oct St.</th>
                            <th>Oct Exp.</th>
                            <th>Nov St.</th>
                            <th>Nov Exp.</th>
                            <th>Dec St.</th>
                            <th>Dec Exp.</th>
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
                    url: "{{ route('get_active_trainee_data') }}",
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
                        data: 'ReferenceNo',
                        name: 'ReferenceNo'
                    },
                    {
                        data:'Doj',
                        name:'Doj'
                    },
                    {
                        data: 'Name',
                        name: 'Name'
                    },
                    {
                        data: 'Gender',
                        name: 'Gender'
                    },
                    {
                        data: 'Education',
                        name: 'Education'
                    },
                    {
                        data: 'College',
                        name: 'College'
                    },
                    {
                        data: 'January_Stipend',
                        name: 'January_Stipend'
                    },
                    {
                        data: 'January_Expense',
                        name: 'January_Expense'
                    },
                    {
                        data: 'February_Stipend',
                        name: 'February_Stipend'
                    },
                    {
                        data: 'February_Expense',
                        name: 'February_Expense'
                    },
                    {
                        data: 'March_Stipend',
                        name: 'March_Stipend'
                    },
                    {
                        data: 'March_Expense',
                        name: 'March_Expense'
                    },
                    {
                        data: 'April_Stipend',
                        name: 'April_Stipend'
                    },
                    {
                        data: 'April_Expense',
                        name: 'April_Expense'
                    },
                    {
                        data: 'May_Stipend',
                        name: 'May_Stipend'
                    },
                    {
                        data: 'May_Expense',
                        name: 'May_Expense'
                    },
                    {
                        data: 'June_Stipend',
                        name: 'June_Stipend'
                    },
                    {
                        data: 'June_Expense',
                        name: 'June_Expense'
                    },
                    {
                        data: 'July_Stipend',
                        name: 'July_Stipend'
                    },
                    {
                        data: 'July_Expense',
                        name: 'July_Expense'
                    },
                    {
                        data: 'August_Stipend',
                        name: 'August_Stipend'
                    },
                    {
                        data: 'August_Expense',
                        name: 'August_Expense'
                    },
                    {
                        data: 'September_Stipend',
                        name: 'September_Stipend'
                    },
                    {
                        data: 'September_Expense',
                        name: 'September_Expense'
                    },
                    {
                        data: 'October_Stipend',
                        name: 'October_Stipend'
                    },
                    {
                        data: 'October_Expense',
                        name: 'October_Expense'
                    },
                    {
                        data: 'November_Stipend',
                        name: 'November_Stipend'
                    },
                    {
                        data: 'November_Expense',
                        name: 'November_Expense'
                    },
                    {
                        data: 'December_Stipend',
                        name: 'December_Stipend'
                    },
                    {
                        data: 'December_Expense',
                        name: 'December_Expense'
                    },


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
