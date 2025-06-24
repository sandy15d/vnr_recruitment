@extends('layouts.master')
@section('title', 'Closed MRF Details')
@section('PageContent')
    <style>
        .table > :not(caption) > * > * {
            padding: 2px 1px;
        }

    </style>
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb  align-items-center mb-3">
            <h5 class="row mb-1">
                <h5 class="download_label text-muted">
                    Closed MRF Details
                </h5>
                <div class="row">
                    <div class="col-2">
                        <select name="Fill_Company" id="Fill_Company" class="form-select form-select-sm"
                                onchange="GetClosedMRF(); GetDepartment();">
                            <option value="">Select Company</option>
                            @foreach ($company_list as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-2">

                        <select name="Fill_Department" id="Fill_Department" class="form-select form-select-sm"
                                onchange="GetClosedMRF();">
                            <option value="">Select Department</option>

                        </select>
                    </div>
                    <div class="col-1">
                        <select name="Year" id="Year" class="form-select form-select-sm" onchange="GetClosedMRF();">
                            <option value="">Select Year</option>
                            @for ($i = 2021; $i <= date('Y'); $i++)
                                <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-2">
                        <select name="Month" id="Month" class="form-select form-select-sm" onchange="GetClosedMRF();">
                            <option value="">Select Month</option>
                            @foreach ($months as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-2">
                        <select name="MRFType" id="MRFType" class="form-select form-select-sm"
                                onchange="GetClosedMRF();">
                            <option value="">Select MRF Type</option>
                            <option value="New">New</option>
                            <option value="Replacement">Replacement</option>
                            <option value="Campus">Campus</option>
                            <option value="SIP">SIP/Internship</option>
                        </select>
                    </div>
                    <div class="col-2">
                        <select name="Recruiter" id="Recruiter" class="form-select form-select-sm"
                                onchange="GetClosedMRF();">
                            <option value="">Select Recruiter</option>
                            @foreach($recruiters as $recruiter)
                                <option value="{{ $recruiter->id }}">{{ $recruiter->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-1">
                        <button type="reset" class="btn btn-danger btn-sm" id="reset"><i class="bx bx-refresh"></i>
                        </button>
                    </div>
                </div>
        </div>


        <!--end breadcrumb-->

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-condensed table-bordered" id="MRFTable" style="width: 100%">
                                <thead class="bg-success text-light">

                                <tr>
                                    <th class="noExport"></th>
                                    <th class="th-sm">S.No.</th>
                                    <th>Type</th>
                                    <th>JobCode</th>
                                    <th>Department</th>
                                    <th>SubDepartment</th>
                                    <th>Designation</th>
                                    <th>Position</th>
                                    <th>Position Filled</th>
                                    {{-- <th>Location</th> --}}
                                    <th>MRF Date</th>
                                    <th>On Behalf</th>
                                    <th>Created By</th>
                                    <th>Allocated Date</th>
                                    <th>Allocated To</th>
                                    <th>Close Date</th>
                                    <th>Days to fill</th>
                                    <th>Reason</th>
                                    <th class="noExport">Details</th>
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
    <div class="modal fade" id="editMRFModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static"
         data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-info bg-gradient">
                    <h5 class="modal-title text-white">MRF Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('updateMRF') }}" method="POST" id="update_mrf_form">
                    @csrf
                    <div class="modal-body">
                        <table class="table borderless">
                            <tbody>
                            <tr id="onbehalf_tr" class="d-none">
                                <th>On Behalf of</th>
                                <td>
                                    <input type="text" id="on_behalf" name="on_behalf"
                                           class="form-control form-control-sm">
                                </td>
                            </tr>
                            <tr>
                                <input type="hidden" name="MRFId" id="MRFId">
                                <input type="hidden" name="MRF_Type" id="MRF_Type">
                                <th style="width:250px;">Reason for Creating New Position<font class="text-danger">*
                                    </font>
                                </th>
                                <td>
                                    <textarea class="form-control" rows="1" name="Reason" id="Reason" tabindex="1"
                                              autofocus></textarea>
                                    <span class="text-danger error-text Reason_error"></span>

                                </td>
                            </tr>
                            <tr>
                                <th>Company<font class="text-danger">*</font>
                                </th>
                                <td><select id="Company" name="Company" class="form-control form-select form-select-sm">
                                        <option value="" selected disabled>Select Company</option>
                                        @foreach ($company_list as $key => $value)
                                            <option value="{{ $key }}">{{ $value }}</option>
                                        @endforeach
                                    </select>
                                    <span class="text-danger error-text Company_error"></span>
                                </td>
                            </tr>
                            <tr>
                                <th>Department<font class="text-danger">*</font>
                                </th>
                                <td>
                                    <div class="spinner-border text-primary d-none" role="status" id="DeptLoader"> <span
                                            class="visually-hidden">Loading...</span>
                                    </div>
                                    <select id="Department" name="Department" id="Department"
                                            class="form-control form-select form-select-sm">
                                        <option value="" selected disabled>Select Department</option>
                                        @foreach ($department_list as $key => $value)
                                            <option value="{{ $key }}">{{ $value }}</option>
                                        @endforeach
                                    </select>
                                    <span class="text-danger error-text Department_error"></span>
                                </td>
                            </tr>
                            <tr id="deisgnation_tr" class="d-none">
                                <th>Designation<font class="text-danger">*</font>
                                </th>
                                <td>
                                    <div class="spinner-border text-primary d-none" role="status" id="DesigLoader">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                    <select id="Designation" name="Designation"
                                            class="form-control form-select form-select-sm">
                                        <option value="" selected disabled>Select Designation</option>
                                        @foreach ($designation_list as $key => $value)
                                            <option value="{{ $key }}">{{ $value }}</option>
                                        @endforeach
                                    </select>
                                    <span class="text-danger error-text Designation_error"></span>
                                </td>
                            </tr>

                            <tr>
                                <th>Location & Man Power <font class="text-danger">*</font>
                                </th>
                                <td>
                                    <table class="table borderless" style="margin-bottom: 0px;">
                                        <tbody id="MulLocation">
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <tr id="ctc_tr">
                                <th>Desired CTC (in Rs.) <font class="text-danger">*</font>
                                </th>
                                <td>
                                    <table class="table borderless" style="margin-bottom: 0px;">
                                        <tr>
                                            <td><input type="text" name="MinCTC" id="MinCTC"
                                                       class="form-control form-control-sm" placeholder="Min"></td>
                                            <td><input type="text" name="MaxCTC" id="MaxCTC"
                                                       class="form-control form-control-sm" placeholder="Max"></td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr id="stipend_tr">
                                <th>Desired Stipend (in Rs. Per Month) <font class="text-danger">*</font>
                                </th>
                                <td>
                                    <input type="text" name="Stipend" id="Stipend" class="form-control form-control-sm">
                                </td>
                            </tr>
                            <tr id="other_benifit_tr">
                                <th>Other Benefits</th>
                                <td>
                                    <table class="table borderless" style="margin-bottom: 0px;">
                                        <tbody>
                                        <tr>
                                            <td>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input " type="checkbox"
                                                           id="two_wheeler_check">
                                                    <label class="form-check-label" for="two_wheeler_check">2
                                                        Wheeler reimbursement Rs.
                                                    </label>
                                                </div>
                                                <div class="form-check form-check-inline d-none"
                                                     id="two_wheeler_div">
                                                    <input type="text" name="two_wheeler" id="two_wheeler"
                                                           style="border-radius: .2rem; border:1px solid #ced4da; padding:.25rem">
                                                    per
                                                    km
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="form-check form-check-inline" style="width: 200px;">
                                                    <input class="form-check-input " type="checkbox" id="da_check">
                                                    <label class="form-check-label" for="da_check">DA
                                                    </label>
                                                </div>
                                                <div class="form-check form-check-inline d-none" id="da_div">
                                                    <input type="text" name="da" id="da"
                                                           style="border-radius: .2rem; border:1px solid #ced4da; padding:.25rem">
                                                    Rs. per
                                                    Day
                                                </div>
                                            </td>
                                        </tr>

                                        </tbody>
                                    </table>

                                </td>
                            </tr>


                            <tr>
                                <th>Desired Eductaion
                                </th>
                                <td>
                                    <table class="table borderless" style="margin-bottom: 0px;">
                                        <tbody id="MulEducation">
                                        </tbody>
                                    </table>
                                </td>
                            </tr>

                            <tr>
                                <th>Desired University/College</th>
                                <td>
                                    <select name="University[]" id="University"
                                            class="form-control form-select form-select-sm multiple-select"
                                            multiple="multiple">

                                        @foreach ($institute_list as $key => $value)
                                            <option value="{{ $key }}">{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                            <tr id="work_exp_tr">
                                <th>Work Experience <font class="text-danger">*</font>
                                </th>
                                <td>
                                    <input type="text" name="WorkExp" id="WorkExp" class="form-control form-control-sm">
                                </td>
                            </tr>
                            <tr>
                                <th>Job Description</th>
                                <td>
                                    <textarea name="JobInfo" id="JobInfo" class="form-control"></textarea>
                                </td>
                            </tr>
                            <tr id="duration_tr">
                                <th>Training Duration</th>
                                <td>
                                    <table class="table borderless" style="margin-bottom: 0px;">
                                        <tbody>
                                        <tr>
                                            <td valign="middle">From</td>
                                            <td>
                                                <input type="date" name="Tr_Frm_Date" id="Tr_Frm_Date"
                                                       class="form-control form-control-sm">
                                                <span class="text-danger error-text Tr_Frm_Date_error"></span>
                                            </td>
                                            <td valign="middle">To</td>
                                            <td>
                                                <input type="date" name="Tr_To_Date" id="Tr_To_Date"
                                                       class="form-control form-control-sm">
                                                <span class="text-danger error-text Tr_To_Date_error"></span>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <th>Mandatory Requirement</th>
                                <td>

                                    <table class="table borderless" style="margin-bottom: 0px;">
                                        <tbody id="MulKP">
                                        </tbody>
                                    </table>
                                    <button type="button" name="add" id="addKP"
                                            class="btn btn-warning btn-sm mb-2 mt-2"><i class="bx bx-plus"></i></button>
                                </td>
                            </tr>
                            <tr>
                                <th>Any Other Remark</th>
                                <td>
                                    <textarea name="Remark" id="Remark" class="form-control"></textarea>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="UpdateMRF">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('script_section')
    <script>
        CKEDITOR.replace('JobInfo', {
            height: 100
        });
        var KPCount;
        $(document).ready(function () {
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
                    exportOptions: {
                        columns: "thead th:not(.noExport)",
                        format: {
                            body: function (inner, rowidx, colidx, node) {
                                if ($(node).children("select").length > 0) {
                                    // we are in a cell containing a "select" drop-down - so, get it:
                                    var selectNode = node.firstElementChild;
                                    var txt = selectNode.options[selectNode.selectedIndex]
                                        .text;
                                    //var txt = selectNode.options[selectNode.selectedIndex].text;
                                    return txt;
                                } else {
                                    return inner; // the standard cell contents
                                }
                            }
                        }
                    }
                },

                    {
                        extend: 'print',
                        text: '<i class="fa fa-print"></i> Print',
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
                            columns: "thead th:not(.noExport)",
                            format: {
                                body: function (inner, rowidx, colidx, node) {
                                    if ($(node).children("select").length > 0) {
                                        // we are in a cell containing a "select" drop-down - so, get it:
                                        var selectNode = node.firstElementChild;
                                        var txt = selectNode.options[selectNode.selectedIndex]
                                            .text;
                                        //var txt = selectNode.options[selectNode.selectedIndex].text;
                                        return txt;
                                    } else {
                                        return inner; // the standard cell contents
                                    }
                                }
                            }
                        }
                    },


                ],

                ajax: {
                    url: "{{ route('getCloseMrf') }}",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: function (d) {
                        d.Company = $('#Fill_Company').val();
                        d.Department = $('#Fill_Department').val();
                        d.Year = $('#Year').val();
                        d.Month = $('#Month').val();
                        d.Recruiter = $('#Recruiter').val();
                        d.MRFType = $('#MRFType').val();
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
                        data: 'Type',
                        name: 'Type'
                    },
                    {
                        data: 'JobCode',
                        name: 'JobCode'
                    },
                    {
                        data: 'DepartmentId',
                        name: 'DepartmentId'
                    },
                    {
                        data: 'sub_department',
                        name: 'sub_department'
                    },
                    {
                        data: 'DesigId',
                        name: 'DesigId'
                    },
                    {
                        data: 'Positions',
                        name: 'Positions',
                        'className': 'text-center'
                    },
                    {
                        data: 'Position_Filled',
                        name: 'Position_Filled',
                        'className': 'text-center'
                    },
                    /* {
                        data: 'LocationIds',
                        name: 'LocationIds'
                    }, */
                    {
                        data: 'MRFDate',
                        name: 'MRFDate'
                    },
                    {
                        data: 'OnBehalf',
                        name: 'OnBehalf'
                    },
                    {
                        data: 'CreatedBy',
                        name: 'CreatedBy'
                    },


                    {
                        data: 'AllocatedDt',
                        name: 'AllocatedDt'
                    },
                    {
                        data: 'Recruiter',
                        name: 'Recruiter'
                    },
                    {
                        data: 'CloseDt',
                        name: 'CloseDt'
                    },
                    {
                        data: 'daystofill',
                        name: 'daystofill'
                    },
                    {
                        data: 'CloseReason',
                        name: 'CloseReason'
                    },
                    {
                        data: 'Details',
                        name: 'Details'
                    }
                ],
            });
        });

        $(document).on('click', '#reset', function () {
            location.reload();
        });

        function GetClosedMRF() {
            $('#MRFTable').DataTable().draw(true);
        }

        $("#two_wheeler_check").change(function () {
            if (!this.checked) {
                $("#two_wheeler_div").addClass("d-none");
            } else {
                $("#two_wheeler_div").removeClass("d-none");
            }
        });
        $("#da_check").change(function () {
            if (!this.checked) {
                $("#da_div").addClass("d-none");
            } else {
                $("#da_div").removeClass("d-none");
            }
        });

        $(document).on('click', '#viewMRF', function () {
            var MRFId = $(this).data('id');
            $.post('<?= route('getMRFDetails') ?>', {
                MRFId: MRFId
            }, function (data) {

                $('#editMRFModal').find('input[name="MRFId"]').val(data.MRFDetails.MRFId);
                if (data.on_behalf != '') {
                    $("#onbehalf_tr").removeClass('d-none');
                } else {
                    $("#onbehalf_tr").addClass('d-none');
                }
                $("#on_behalf").val(data.on_behalf);
                $('#MRF_Type').val(data.MRFDetails.Type);
                $('#Reason').val(data.MRFDetails.Reason);
                $('#Company').val(data.MRFDetails.CompanyId);
                $('#Department').val(data.MRFDetails.DepartmentId);
                $('#Designation').val(data.MRFDetails.DesigId);
                $('#WorkExp').val(data.MRFDetails.WorkExp);
                $('#MinCTC').val(data.MRFDetails.MinCTC);
                $('#MaxCTC').val(data.MRFDetails.MaxCTC);
                $('#MaxCTC').val(data.MRFDetails.MaxCTC);
                $('#Stipend').val(data.MRFDetails.Stipend);
                CKEDITOR.instances['JobInfo'].setData(data.MRFDetails.Info);
                $('#Remark').val(data.MRFDetails.Remarks);
                var UniversityValue = data.UniversityDetails;
                var selectedOptions = UniversityValue.toString().split(",");

                $('#University').select2({
                    multiple: true,
                });
                $('#University').val(selectedOptions).trigger('change');

                KPCount = (data.KPDetails).length;
                var KPValue = data.KPDetails.toString().split(",");
                for (i = 1; i <= KPCount; i++) {
                    mulKP(i);
                    $('#KeyPosition' + i).val(KPValue[i - 1]);
                }


                LocCount = (data.LocationDetails).length;
                for (j = 1; j <= LocCount; j++) {
                    mulLocation(j);
                    $('#State' + j).val(data.LocationDetails[j - 1].State);
                    $('#City' + j).val(data.LocationDetails[j - 1].City);
                    $('#ManPower' + j).val(data.LocationDetails[j - 1].Nop);

                }

                EduCount = (data.EducationDetails).length;

                for (a = 1; a <= EduCount; a++) {
                    mulEducation(a);
                    $('#Education' + a).val(data.EducationDetails[a - 1].e);
                    $("#Specialization" + a).val(data.EducationDetails[a - 1].s);
                }

                var form = document.getElementById("update_mrf_form");
                var elements = form.elements;
                for (var i = 0, len = elements.length; i < len; ++i) {
                    elements[i].disabled = true;
                }
                CKEDITOR.instances['JobInfo'].setReadOnly(true);

                if (data.MRFDetails.Type == 'SIP' || data.MRFDetails.Type == 'SIP_HrManual') {
                    $('#deisgnation_tr').addClass('d-none');
                    $('#stipend_tr').removeClass('d-none');
                    $('#duration_tr').removeClass('d-none');
                    $('#ctc_tr').addClass('d-none');
                    $('#other_benifit_tr').removeClass('d-none');
                    if (data.MRFDetails.TwoWheeler != null) {
                        $('#two_wheeler_check').prop('checked', true);
                        $("#two_wheeler_div").removeClass("d-none");
                        $('#two_wheeler').val(data.MRFDetails.TwoWheeler);
                    }
                    if (data.MRFDetails.DA != null) {
                        $('#da_check').prop('checked', true);
                        $("#da_div").removeClass("d-none");
                        $('#da').val(data.MRFDetails.DA);
                    }
                    if (data.MRFDetails.Tr_Frm_Date != null) {
                        $('#Tr_Frm_Date').val(data.MRFDetails.Tr_Frm_Date);
                    }
                    if (data.MRFDetails.Tr_To_Date != null) {
                        $('#Tr_To_Date').val(data.MRFDetails.Tr_To_Date);
                    }
                } else {
                    $('#deisgnation_tr').removeClass('d-none');
                    $('#stipend_tr').addClass('d-none');
                    $('#duration_tr').addClass('d-none');
                    $('#ctc_tr').removeClass('d-none');
                    $('#other_benifit_tr').addClass('d-none');
                }


                $('.modal-footer').addClass('d-none');
                $('#editMRFModal').modal('show');
            }, 'json');
        });

        var StateList = '';
        var CityList = '';
        var EducationList = '';
        var SpecializationList = '';
        getState();

        function getState() {
            $.ajax({
                type: "GET",
                url: "{{ route('getState') }}",
                async: false,
                success: function (res) {
                    if (res) {
                        $.each(res, function (key, value) {
                            StateList = StateList + '<option value="' + value + '">' + key +
                                '</option>';
                        });
                    }
                }
            });
        }

        getCity();

        function getCity() {
            $.ajax({
                type: "GET",
                url: "{{ route('getAllDistrict') }}",
                async: false,
                success: function (res) {
                    if (res) {
                        $.each(res, function (key, value) {
                            CityList = CityList + '<option value="' + value + '">' + key +
                                '</option>';
                        });
                    }
                }
            });
        }

        getEducation();
        getAllSP();

        function getEducation() {
            $.ajax({
                type: "GET",
                url: "{{ route('getEducation') }}",
                async: false,
                success: function (res) {
                    if (res) {
                        $.each(res, function (key, value) {
                            EducationList = EducationList + '<option value="' + value + '">' + key +
                                '</option>';
                        });
                    }
                }
            });
        }

        function getAllSP() {
            $.ajax({
                type: "GET",
                url: "{{ route('getAllSP') }}",
                async: false,
                success: function (res) {
                    if (res) {
                        $.each(res, function (key, value) {
                            SpecializationList = SpecializationList + '<option value="' + key + '">' +
                                value +
                                '</option>';
                        });
                    }
                }
            });
        }

        var LocCount = 1;
        mulLocation(LocCount);

        function mulLocation(number) {
            x = '<tr>';
            x += '<td >' +
                ' <select  name="State[]" id="State' +
                number +
                '" class="form-control form-select form-select-sm" onchange="getLocation(this.value,' + number + ')">' +
                '  <option value="" selected disabled>Select State</option>' + StateList +
                '</select>' +
                ' <span class="text-danger error-text State' + number + '_error"></span>' +
                '</td>';
            x += '<td>' +
                '<div class="spinner-border text-primary d-none" role="status" id="LocLoader' + number +
                '"> <span class="visually-hidden">Loading...</span></div>' +
                '       <select  id="City' + number + '" name="City[]" class="form-control form-select form-select-sm">' +
                '    <option value="" selected disabled>Select City</option>' + CityList +
                '</select>' +
                '<span class="text-danger error-text City' + number + '_error"></span>' +
                '</td>';
            x += '<td>' +
                '  <input type="text" name="ManPower[]" id="ManPower' + number +
                '" class="form-control form-control-sm" style="width:130px" placeholder="No. of Manpower">' +
                '<span class="text-danger error-text ManPower' + number + '_error"></span>' +
                '</td>';
            $('#MulLocation').html(x);

        }

        mulKP();

        function mulKP(n) {
            x = '<tr>';
            x += '<td >' +
                '<input type="text" class="form-control form-control-sm" id="KeyPosition' + n + '" name="KeyPosition[]">' +
                '</td>';

            $('#MulKP').html(x);

        }

        //-------------------------------Start Multiple Education===========================//

        var EduCount = 1;
        mulEducation(EduCount);

        function mulEducation(num) {
            x = '<tr>';
            x += '<td >' +
                ' <select  name="Education[]" id="Education' +
                num +
                '" class="form-control form-select form-select-sm" onchange="getSpecialization(this.value,' + num + ')">' +
                '  <option value="" selected disabled>Select Education</option>' + EducationList +
                '</select>' +
                ' <span class="text-danger error-text Education' + num + '_error"></span>' +
                '</td>';
            x += '<td>' +
                '<div class="spinner-border text-primary d-none" role="status" id="SpeLoader' + num +
                '"> <span class="visually-hidden">Loading...</span></div>' +
                '       <select  id="Specialization' + num +
                '" name="Specialization[]" class="form-control form-select form-select-sm">' +

                '    <option value="0" >Other</option>' + SpecializationList +
                '</select>' +
                '<span class="text-danger error-text Specialization' + num + '_error"></span>' +
                '</td>';

            $('#MulEducation').html(x);

        }


        $(document).on('click', '.select_all', function () {
            if ($(this).prop("checked") == true) {
                $(this).closest("tr").addClass("bg-secondary bg-gradient text-light");
            } else {
                $(this).closest("tr").removeClass("bg-secondary bg-gradient text-light");
            }
        });

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
    </script>

@endsection
