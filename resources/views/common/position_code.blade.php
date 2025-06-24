@extends('layouts.master')
@section('title', 'Candidate Detail')
@section('PageContent')
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb  align-items-center">
            <div class="row mb-3">
                <div class="col-2 breadcrumb-title ">
                    Position Code
                </div>
                <div class="col-10">
                    <button class="btn btn-primary px-1 btn-sm ml-2" style="float:right;margin-left:10px;" data-bs-toggle="modal"
                        data-bs-target="#addPositionModal"><i class="fadeIn animated bx bx-plus"></i>Add New Positin Code</button>
                    <button style="float:right;" class="btn btn-sm btn--red" id="SyncPositionCode" ><i class="fadeIn animated bx bx-sync"></i>Sync</button>
                </div>
            </div>
            <div class="row mb-1">
                <div class="col-2">
                    <select name="Fill_Company" id="Fill_Company" class="form-select form-select-sm"
                        onchange="get_position_code(); get_department();">
                        <option value="">Select Company</option>
                        @foreach ($company_list as $key => $value)
                            <option value="{{ $key }}">{{ $value }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-2">
                    <div class="spinner-border text-primary d-none" role="status" id="DeptLoader">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <select name="Fill_Department" id="Fill_Department" class="form-select form-select-sm"
                        onchange="get_position_code(); get_designation();">
                        <option value="">Select Department</option>
                    </select>
                </div>

                <div class="col-2">
                    <div class="spinner-border text-primary d-none" role="status" id="DesigLoader">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <select name="Fill_Designation" id="Fill_Designation" class="form-select form-select-sm"
                        onchange="get_position_code();">
                        <option value="">Select Department</option>
                    </select>
                </div>
                <div class="col-2">
                    <select name="Status" id="Status" class="form-select form-select-sm" onchange="get_position_code();">
                        <option value="">Status</option>
                        <option value="A">Active</option>
                        <option value="D">Deactive</option>
                    </select>
                </div>
                <div class="col-2">
                    <button type="reset" class="btn btn-danger btn-sm" id="reset"><i class="bx bx-refresh"></i></button>
                </div>
            </div>
        </div>
        <!--end breadcrumb-->
        <hr />
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <ul class="nav nav-pills mb-3" role="tablist" style="border-bottom: 1px solid #ddd;margin-bottom:0px !important;">
                            <li class="nav-item" role="presentation">
                                <a class="nav-link active" data-bs-toggle="pill" href="#unused_position_code" role="tab"
                                    aria-selected="false">
                                    <div class="d-flex align-items-center">
                                        <div class="tab-icon"><i class="bx bx-hdd font-18 me-1"></i>
                                        </div>
                                        <div class="tab-title">Unused Position Code</div>
                                    </div>
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" data-bs-toggle="pill" href="#used_position_code" role="tab"
                                    aria-selected="true">
                                    <div class="d-flex align-items-center">
                                        <div class="tab-icon"><i class="bx bx-server font-18 me-1"></i>
                                        </div>
                                        <div class="tab-title">Used Position Code</div>
                                    </div>
                                </a>
                            </li>


                        </ul>
                    </div>

                </div>
                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade active show" id="unused_position_code" role="tabpanel">

                        <div class="table-responsive">
                            <table class="table table-bordered  table-condensed text-center"
                                id="myTable" style="width: 100%">
                                <thead class="bg-success text-light">
                                    <th class="td-sm">S.No.</th>
                                    <th>Company</th>
                                    <th>Department</th>
                                    <th>Designation</th>
                                    <th>Grade</th>
                                    <th>Vertical</th>
                                    <th>Position Code</th>
                                </thead>
                            </table>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="used_position_code" role="tabpanel">
                        <div class="table-responsive">
                            <table class="table  table-condensed"
                                id="positioncodetable" style="width: 100%">
                                <thead class="bg-success text-light text-center">
                                    <tr>
                                        <td class="td-sm">S.No.</td>
                                        <td>EmpCode</td>
                                        <td>Employee</td>
                                        <td>Company</td>
                                        <td>Department</td>
                                        <td>Designation</td>
                                        <td>Grade</td>
                                        <td>Vertical</td>
                                        <td>Sequence</td>
                                        <td>PositionCode</td>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>

    <div class="modal fade" id="addPositionModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static"
        data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Position Code</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('add_position_code') }}" method="POST" id="addPositionCodeForm">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-6 form-group">
                                <label for="Company">Company Name</label>
                                <select name="Company" id="Company" class="form-select form-select-sm"
                                    onchange="get_department1(); get_grade();">
                                    <option value="">Select Company</option>
                                    @foreach ($company_list as $key => $value)
                                        <option value="{{ $key }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                                <span class="text-danger error-text Company_error"></span>
                            </div>
                            <div class="col-6 form-group">
                                <label for="Department">Department</label>
                                <select name="Department" id="Department" class="form-select form-select-sm"
                                    onchange="get_designation1();">
                                    <option value="">Select Department</option>
                                </select>
                                <span class="text-danger error-text Department_error"></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-4 form-group">
                                <label for="Designation">Designation</label>
                                <select name="Designation" id="Designation" class="form-select form-select-sm"
                                    onchange="get_grade();">
                                    <option value="">Select Designation</option>
                                </select>
                                <span class="text-danger error-text Designation_error"></span>
                            </div>
                            <div class="col-4 form-group">
                                <label for="Grade">Grade</label>
                                <select name="Grade" id="Grade" class="form-select form-select-sm">
                                    <option value="">Select Grade</option>
                                </select>
                                <span class="text-danger error-text Grade_error"></span>
                            </div>
                            <div class="col-4 form-group">
                                <label for="Vertical">Vertical</label>
                                <select name="Vertical" id="Vertical" class="form-select form-select-sm">
                                    <option value="">Select Vertical</option>
                                    <option value="CB">CB</option>
                                    <option value="FC">FC</option>
                                    <option value="VC">VC</option>
                                    <option value="BFC">BFC</option>
                                    <option value="BVC">BVC</option>
                                    <option value="BSFC">BSFC</option>
                                    <option value="BSVC">BSVC</option>
                                    <option value="RS">RS</option>   <!-- Root Stock-->
                                </select>
                                <span class="text-danger error-text Vertical_error"></span>
                            </div>
                        </div>

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
        $(document).ready(function() {
            $('#positioncodetable').DataTable({
                processing: true,
                serverSide: true,
                info: true,
                searching: false,
                ordering: false,
                lengthChange: false,
                ajax: {
                    url: "{{ route('show_all_position_code') }}",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: function(d) {
                        d.Company = $('#Fill_Company').val();
                        d.Department = $('#Fill_Department').val();
                        d.Designation = $('#Fill_Designation').val();
                        d.Status = $('#Status').val();
                    },
                    type: 'POST',
                    dataType: "JSON",
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        'className' : 'text-center'
                    },
                    {
                        data: 'emp_code',
                        name: 'emp_code',
                        'className' : 'text-center'
                    },
                    {
                        data: 'fullname',
                        name: 'fullname'
                    },

                    {
                        data: 'Company',
                        name: 'Company',
                        'className' : 'text-center'
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
                        data: 'Grade',
                        name: 'Grade',
                        'className' : 'text-center'
                    },
                    {
                        data: 'vertical',
                        name: 'vertical',
                        'className' : 'text-center'
                    },
                    {
                        data: 'sequence',
                        name: 'sequence',
                        'className' : 'text-center'
                    },
                    {
                        data: 'position_code',
                        name: 'position_code'
                    }

                ],
                "createdRow": function(row, data, name) {
                    if (data['EmpStatus'] == 'D') {
                        $(row).addClass('bg-danger text-light');

                    }
                }
            });

            $('#myTable').DataTable({
                processing: true,
                serverSide: true,
                info: true,
                searching: false,
                ordering: false,
                lengthChange: false,
                ajax: {
                    url: "{{ route('unused_position_code') }}",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: function(d) {
                        d.Company = $('#Fill_Company').val();
                        d.Department = $('#Fill_Department').val();
                        d.Designation = $('#Fill_Designation').val();
                        d.Status = $('#Status').val();
                    },
                    type: 'POST',
                    dataType: "JSON",
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },

                    {
                        data: 'Company',
                        name: 'Company'
                    },
                    {
                        data: 'Department',
                        name: 'Department',

                    },
                    {
                        data: 'Designation',
                        name: 'Designation'
                    },
                    {
                        data: 'Grade',
                        name: 'Grade'
                    },
                    {
                        data: 'vertical',
                        name: 'vertical'
                    },
                    {
                        data: 'position_code',
                        name: 'position_code'
                    }

                ],

            });

        });
        $('#addPositionCodeForm').on('submit', function(e) {
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
                    // $('#loader').modal('show');
                },
                success: function(data) {
                    if (data.status == 400) {
                        $.each(data.error, function(prefix, val) {
                            $(form).find('span.' + prefix + '_error').text(val[0]);
                        });
                        toastr.error(data.msg);
                    } else {
                        $(form)[0].reset();
                        // $('#loader').modal('hide');
                        $('#addPositionModal').modal('hide');
                        $('#myTable').DataTable().ajax.reload(null, false);
                        toastr.success(data.msg);
                    }
                }
            });


        });

        $(document).on('click', '#SyncPositionCode', function() {
            var url = '<?= route('SyncPositionCode') ?>';
            swal.fire({
                title: 'Are you sure?',
                html: 'Synchronize Position Code Data from ESS',
                showCancelButton: true,
                showCloseButton: true,
                cancelButtonText: 'Cancel',
                confirmButtonText: 'Yes, Synchronize',
                cancelButtonColor: '#d33',
                confirmButtonColor: '#556ee6',
                width: 400,
                allowOutsideClick: false,


            }).then(function(result) {
                if (result.value) {
                    $.post(url, function(data) {
                        if (data.status == 200) {
                            $('#loader').modal('hide');
                            $('#positioncodetable').DataTable().ajax.reload(null, false);
                            toastr.success(data.msg);

                        } else {
                            toastr.error(data.msg);
                        }
                    }, 'json');
                }
            });
        });

        function get_position_code() {
            $('#positioncodetable').DataTable().draw(true);
        }

        function get_department() {
            var CompanyId = $('#Fill_Company').val();
            $.ajax({
                type: "GET",
                url: "{{ route('getDepartment') }}?CompanyId=" + CompanyId,
                beforeSend: function() {
                    $('#DeptLoader').removeClass('d-none');
                    $('#Fill_Department').addClass('d-none');
                },
                success: function(res) {
                    if (res) {
                        $('#DeptLoader').addClass('d-none');
                        $('#Fill_Department').removeClass('d-none');
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

        function get_department1() {
            var CompanyId = $('#Company').val();
            $.ajax({
                type: "GET",
                url: "{{ route('getDepartment') }}?CompanyId=" + CompanyId,

                success: function(res) {
                    if (res) {

                        $('#Department').removeClass('d-none');
                        $("#Department").empty();
                        $("#Department").append(
                            '<option value="" selected disabled >Select Department</option>');
                        $.each(res, function(key, value) {
                            $("#Department").append('<option value="' + value + '">' + key +
                                '</option>');
                        });
                    } else {
                        $("#Department").empty();
                    }
                }
            });
        }

        function get_designation() {
            var DepartmentId = $('#Fill_Department').val();
            $.ajax({
                type: "GET",
                url: "{{ route('getDesignation') }}?DepartmentId=" + DepartmentId,
                beforeSend: function() {
                    $('#DesigLoader').removeClass('d-none');
                    $('#Fill_Designation').addClass('d-none');
                },
                success: function(res) {
                    if (res) {
                        $('#DesigLoader').addClass('d-none');
                        $('#Fill_Designation').removeClass('d-none');
                        $("#Fill_Designation").empty();
                        $("#Fill_Designation").append(
                            '<option value="" selected disabled >Select Designation</option>');
                        $.each(res, function(key, value) {
                            $("#Fill_Designation").append('<option value="' + value + '">' + key +
                                '</option>');
                        });
                    } else {
                        $("#Fill_Designation").empty();
                    }
                }
            });
        }

        function get_designation1() {
            var DepartmentId = $('#Department').val();
            $.ajax({
                type: "GET",
                url: "{{ route('getDesignation') }}?DepartmentId=" + DepartmentId,

                success: function(res) {
                    if (res) {

                        $('#Designation').removeClass('d-none');
                        $("#Designation").empty();
                        $("#Designation").append(
                            '<option value="" selected disabled >Select Designation</option>');
                        $.each(res, function(key, value) {
                            $("#Designation").append('<option value="' + value + '">' + key +
                                '</option>');
                        });
                    } else {
                        $("#Designation").empty();
                    }
                }
            });
        }

        function get_grade() {
            var CompanyId = $('#Company').val();
            $.ajax({
                type: "GET",
                url: "{{ route('getGrade') }}?CompanyId=" + CompanyId,

                success: function(res) {
                    if (res) {

                        $('#Grade').removeClass('d-none');
                        $("#Grade").empty();
                        $("#Grade").append(
                            '<option value="" selected disabled >Select Grade</option>');
                        $.each(res, function(key, value) {
                            $("#Grade").append('<option value="' + value + '">' + key +
                                '</option>');
                        });
                    } else {
                        $("#Grade").empty();
                    }
                }
            });
        }

        $(document).on('click', '#reset', function() {
            location.reload();
        });
    </script>
@endsection
