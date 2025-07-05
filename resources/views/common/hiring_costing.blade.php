@extends('layouts.master')
@section('title', 'Campus Hiring Costing')
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
            padding: 2 px 4 px !important;
            font-size: 11px;
            cursor: pointer;
        }

    </style>
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb  align-items-center mb-3">
            <div class="row mb-1">
                <div class="col-5 breadcrumb-title ">
                    Campus Recruitment Costing
                </div>
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

                <div class="col-1">
                    <button type="reset" class="btn btn-danger btn-sm" id="reset"><i class="bx bx-refresh"></i></button>
                </div>
            </div>

        </div>
        <!--end breadcrumb-->

        <div class="card border-top border-0 border-4 border-success">
            <div class="card-body">
                <table class="table table-condensed align-middle table-bordered"
                    id="CampusApplication" style="width: 100%">
                    <thead class="text-center bg-success text-light">
                        <tr class="text-center">

                            <td class="th-sm">S.No.</td>
                            <td>College</td>
                            <td>JobCode</td>
                            <td>Department</td>
                            <td>Designation</td>
                            <td>Total Costing (in Rs)</td>
                            <td>Action</td>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <div class="modal fade" id="expense_modal" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog  modal-md">
            <div class="modal-content">
                <div class="modal-header bg-info bg-gradient">
                    <h6 class="modal-title text-white">Campus Recruitment Costing</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('updateCosting') }}" method="POST" id="campus_costing_form">
                    @csrf
                    <input type="hidden" name="JPId" id="JPId">
                    <div class="modal-body">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th>From<font class="text-danger">*</font>
                                    </th>
                                    <td> <input type="date" name="FromDate" id="FromDate"
                                            class="form-control form-control-sm">
                                    </td>
                                </tr>
                                <tr>
                                    <th>To<font class="text-danger">*</font>
                                    </th>
                                    <td> <input type="date" name="ToDate" id="ToDate" class="form-control form-control-sm">
                                    </td>
                                </tr>
                                <tr>
                                    <th>Total Appeared<font class="text-danger">*</font>
                                    </th>
                                    <td> <input type="text" name="Appeared" id="Appeared"
                                            class="form-control form-control-sm">
                                    </td>
                                </tr>
                                <tr>
                                    <th>Total Hires<font class="text-danger">*</font>
                                    </th>
                                    <td> <input type="text" name="Hired" id="Hired" class="form-control form-control-sm">
                                    </td>
                                </tr>

                                <tr>
                                    <td colspan="2">
                                        <table class="table table-bordered text-center">
                                            <tr class="bg-dark text-light">
                                                <td>S.No</td>
                                                <td>Particulars</td>
                                                <td>Amount (in Rs.)</td>
                                            </tr>
                                            <tr>
                                                <td>1</td>
                                                <td>RT Travel Cost</td>
                                                <td><input type="number" name="RT1" id="RT1"
                                                        class="form-control form-control-sm" onkeyup="calrt();avg_cost();"
                                                        min="0">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>2</td>
                                                <td>RT Accommodation Cost</td>
                                                <td><input type="number" name="RT2" id="RT2"
                                                        class="form-control form-control-sm" onkeyup="calrt();avg_cost();"
                                                        min="0">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>3</td>
                                                <td>RT Per day productivity Cost</td>
                                                <td><input type="number" name="RT3" id="RT3"
                                                        class="form-control form-control-sm" onkeyup="calrt();avg_cost();"
                                                        min="0">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>4</td>
                                                <td>RT Miscellaneous Expenses</td>
                                                <td><input type="number" name="RT4" id="RT4"
                                                        class="form-control form-control-sm" onkeyup="calrt();avg_cost();"
                                                        min="0">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td></td>
                                                <td>Total</td>
                                                <td><input type="text" name="Total" id="Total"
                                                        class="form-control form-control-sm" readonly>
                                            </tr>
                                            <tr>
                                                <td></td>
                                                <td>Avg Cost per Hire</td>
                                                <td><input type="text" name="AvgCost" id="AvgCost"
                                                        class="form-control form-control-sm" readonly>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>

                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="SaveChanges">Save changes</button>
                    </div>
                </form>
            </div>
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

        function GetCampusRecords() {
            $('#CampusApplication').DataTable().draw(true);

        }

        $(document).on('click', '#reset', function() {
            location.reload();
        });

        $(document).ready(function() {
            $('#CampusApplication').DataTable({
                processing: true,
                serverSide: true,
                ordering: false,
                searching: false,
                lengthChange: false,
                info: true,
                ajax: {
                    url: "{{ route('getCampusCosting') }}",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: function(d) {
                        d.Company = $('#Fill_Company').val();
                        d.Department = $('#Fill_Department').val();

                    },
                    type: 'POST',
                    dataType: "JSON",
                },
                columns: [


                    {
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        'className' : 'text-center'
                    },
                    {
                        data: 'College',
                        name: 'College'
                    },
                    {
                        data: 'JobCode',
                        name: 'JobCode'
                    },
                    {
                        data: 'Department',
                        name: 'Department',
                        'className' : 'text-center'
                    },
                    {
                        data: 'Designation',
                        name: 'Designation'
                    },
                    {
                        data: 'total',
                        name: 'total',
                        'className' : 'text-center'
                    },
                    {
                        data: 'Action',
                        name: 'Action',
                        'className' : 'text-center'
                    },



                ],
            });
        });

        function calrt() {
            let rt1 = parseInt($('#RT1').val() || 0);
            let rt2 = parseInt($('#RT2').val() || 0);
            let rt3 = parseInt($('#RT3').val() || 0);
            let rt4 = parseInt($('#RT4').val() || 0);
            $('#Total').val(rt1 + rt2 + rt3 + rt4);

        }

        function avg_cost() {
            var total_cost = $('#Total').val();
            var hired = $('#Hired').val();
            $('#AvgCost').val(Math.round(total_cost / hired));
        }


        function getCosting(JPId) {
            var JPId = JPId;
            $('#JPId').val(JPId);

            $.ajax({
                type: "POST",
                url: "{{ route('getCostingDetail') }}?JPId=" + JPId,
                success: function(res) {
                    if (res.status == 200) {
                      $("#FromDate").val(res.data.FromDate);
                      $("#ToDate").val(res.data.ToDate);
                      $("#Appeared").val(res.data.Appeared);
                      $("#Hired").val(res.data.Hired);
                      $("#RT1").val(res.data.RT1);
                      $("#RT2").val(res.data.RT2);
                      $("#RT3").val(res.data.RT3);
                      $("#RT4").val(res.data.RT4);
                      $("#AvgCost").val(res.data.AvgCost);
                      $("#Total").val(res.data.Total);
                    }
                }
            });
            $("#expense_modal").modal('show');
        }
        $('#campus_costing_form').on('submit', function(e) {
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
                    $("#loader").modal('show');
                },

                success: function(data) {
                    if (data.status == 400) {
                        $("#loader").modal('hide');
                        $.each(data.error, function(prefix, val) {
                            $(form).find('span.' + prefix + '_error').text(val[0]);
                        });
                    } else {
                        $(form)[0].reset();
                        $('#loader').modal('hide');
                        toastr.success(data.msg);
                        window.location.href = "{{ route('campus_hiring_costing') }}";
                    }
                }
            });
        });
    </script>

@endsection
