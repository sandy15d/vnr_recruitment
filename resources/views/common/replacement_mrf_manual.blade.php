@php

@endphp
@extends('layouts.master')
@section('title', 'New Manpower Requisition Form')
@section('PageContent')
    <style>
        .table>:not(caption)>*>* {
            padding: 2px 1px;
        }

    </style>
    <div class="page-content">

        <div class="col-xl-8 mx-auto">
            <div class="card border-top border-0 border-4 border-primary">
                <div class="card-body p-3">
                    <div class="card-title d-flex align-items-center">
                        <div><i class="bx bxs-user me-1 font-22 text-primary"></i>
                        </div>
                        <h5 class="mb-0 text-primary">New Manpower Requisition Form</h5>

                    </div>
                    <hr>
                    <form action="{{ route('add_replacement_mrf_manual') }}" method="POST" id="addNewMrfForm"
                        name="addNewMrfForm">
                        @csrf
                        <div class="modal-body">
                            <table class="table borderless">
                                <tbody>
                                    <tr>
                                        <th>On Behalf of HOD<font class="text-danger">*</font>
                                        </th>
                                        <td><select id="OnBehalf" name="OnBehalf"
                                                class="form-control form-select form-select-sm">
                                                <option value="" selected>Select HOD</option>
                                                @foreach ($userlist as $key => $value)
                                                    <option value="{{ $key }}">{{ $value }}</option>
                                                @endforeach
                                            </select>

                                            <span class="text-danger error-text OnBehalf_error"></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th style="width:250px;">Reason for Creating New Position<font
                                                class="text-danger">*
                                            </font>
                                        </th>
                                        <td>
                                            <textarea class="form-control" rows="1" name="Reason" tabindex="1"
                                                autofocus></textarea>
                                            <span class="text-danger error-text Reason_error"></span>

                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Company<font class="text-danger">*</font>
                                        </th>
                                        <td><select id="Company" name="Company"
                                                class="form-control form-select form-select-sm" disabled>
                                                <option value="" selected disabled>Select Company</option>
                                                @foreach ($company_list as $key => $value)
                                                    <option value="{{ $key }}" @if ($key == session('Set_Company'))
                                                        {{ 'selected' }}
                                                @endif>{{ $value }}</option>
                                                @endforeach
                                            </select>

                                            <span class="text-danger error-text Company_error"></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Department<font class="text-danger">*</font>
                                        </th>
                                        <td>
                                            <div class="spinner-border text-primary d-none" role="status" id="DeptLoader">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                            <select id="Department" name="Department"
                                                class="form-control form-select form-select-sm">
                                                <option value="" selected disabled>Select Department</option>
                                                @foreach ($department_list as $key => $value)
                                                    <option value="{{ $key }}">{{ $value }}</option>
                                                @endforeach
                                            </select>
                                            <span class="text-danger error-text Department_error"></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Replacement For<font class="text-danger">*</font>
                                        </th>
                                        <td>
                                            <div class="spinner-border text-primary d-none" role="status" id="EmpLoader">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                            <select id="ReplacementFor" name="ReplacementFor"
                                                class="form-control form-select form-select-sm">
                                                <option value="" selected disabled>Select Employee</option>

                                            </select>
                                            <span class="text-danger error-text ReplacementFor_error"></span>
                                        </td>
                                    </tr>

                                    <tr>
                                        <th>Designation:</th>
                                        <td>
                                            <input type="text" class="form-control form-control-sm" id="Designation"
                                                name="Designation" readonly>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Grade:</th>
                                        <td>
                                            <input type="text" class="form-control form-control-sm" id="Grade" name="Grade"
                                                readonly>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Existing Location:</th>
                                        <td>
                                            <input type="text" class="form-control form-control-sm" id="ExistingLocation"
                                                name="ExistingLocation" readonly>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Existing CTC:</th>
                                        <td>
                                            <input type="text" class="form-control form-control-sm" id="ExCTC" name="ExCTC"
                                                readonly>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Desired Location:</th>  
                                        <td>
                                            <div style="width: 50%;display: inline-block;float: left">
                                                <select id="State" name="State[]"
                                                    class="form-control form-select form-select-sm reqinp"
                                                    onchange="getLocation(this.value)">
                                                    <option disabled="" selected="">Select State</option>
                                                    @foreach ($state_list as $key => $value)
                                                        <option value="{{ $key }}">{{ $value }}</option>
                                                    @endforeach 
                                                </select>
                                            </div>
                                            <div class="spinner-border text-primary d-none" role="status" id="LocLoader">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                            <div style="width: 50%;display: inline-block;float: left"
                                                class="ml-3">
                                                <select id="City" name="City[]"  
                                                    class="form-control form-select form-select-sm">
                                                    <option disabled="" selected="">Select City</option>
                                                </select> 
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Desired CTC (in Rs.) <font class="text-danger">*</font>
                                        </th>
                                        <td>
                                            <table class="table borderless" style="margin-bottom: 0px;">
                                                <tr>
                                                    <td><input type="text" name="MinCTC" id="MinCTC"
                                                            class="form-control form-control-sm" placeholder="Min">
                                                    </td>
                                                    <td><input type="text" name="MaxCTC" id="MaxCTC"
                                                            class="form-control form-control-sm" placeholder="Max"> 
                                                    </td>
                                                </tr>
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
                                            <button id="addEducation" type="button"
                                                class="btn btn-sm btn-warning mb-2 mt-2"><i
                                                    class="bx bx-plus"></i>Education</button>
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
                                    <tr>
                                        <th>Work Experience <font class="text-danger">*</font>
                                        </th>
                                        <td>
                                            <input type="text" name="WorkExp" id="WorkExp"
                                                class="form-control form-control-sm">
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Job Description</th>
                                        <td>
                                            <textarea name="JobInfo" id="JobInfo" class="JobInfo"></textarea>
                                        </td>
                                    </tr>



                                    <tr>
                                        <th>Mandatory Requirements</th>
                                        <td>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" id="KpChk">
                                                <label class="form-check-label" for="KpChk">Yes</label>
                                            </div>
                                            <table class="table borderless d-none" style="margin-bottom: 0px;" id="tblkp">
                                                <tbody id="MulKP">
                                                </tbody>

                                            </table>
                                            <button type="button" name="add" id="addKP"
                                                class="btn btn-warning btn-sm d-none"><i
                                                    class="bx bx-plus"></i>Add</button>
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
                            <button type="button" class="btn btn-secondary" id="Cancle">Cancel</button>
                            <button type="submit" class="btn btn-primary" id="SaveNewMrf">Submit</button>
                        </div>
                    </form>
                </div>
            </div>


        </div>
    </div>


@endsection

@section('script_section')

    <script>
        $(document).on('click', '#Cancle', function() {
            location.reload();
        });
        $("#KpChk").change(function() {
            if (!this.checked) {
                $("#tblkp").addClass("d-none");
                $("#addKP").addClass("d-none");
            } else {
                $("#tblkp").removeClass("d-none");
                $("#addKP").removeClass("d-none");
            }
        });
        $(document).ready(function() {
            CKEDITOR.replace('JobInfo');
        });


        var EducationList = '';




        getEducation();

        function getEducation() {
            $.ajax({
                type: "GET",
                url: "{{ route('getEducation') }}",
                async: false,
                success: function(res) {

                    if (res) {
                        $.each(res, function(key, value) {
                            EducationList = EducationList + '<option value="' + value + '">' + key +
                                '</option>';
                        });

                    }
                }
            });
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
                '    <option value="" selected disabled>Select Specialization</option>' +
                '</select>' +
                '<span class="text-danger error-text Specialization' + num + '_error"></span>' +
                '</td>';


            if (num > 1) {
                x +=
                    '<td><button type="button" name="remove" id="" class="btn btn-danger btn-sm  removeEducation">Remove</td></tr>';
                $('#MulEducation').append(x);
            } else {
                x +=
                    '<td></td></tr>';
                $('#MulEducation').html(x);
            }
        }

        $(document).on('click', '#addEducation', function() {
            EduCount++;
            mulEducation(EduCount);
        });

        $(document).on('click', '.removeEducation', function() {
            EduCount--;
            $(this).closest("tr").remove();
        });
        //===========================End Multiple Location=====================================//
        //=====================Start KeyPosition Criteria========================//


        var KPCount = 1;


        mulKP();

        function mulKP(n) {
            x = '<tr>';
            x += '<td >' +
                '<input type="text" class="form-control form-control-sm" id="KeyPosition' + n + '" name="KeyPosition[]">' +
                '</td>';

            if (n > 1) {
                x +=
                    '<td><button type="button" name="remove" id="" class="btn btn-danger btn-sm  removeKP">Remove</td></tr>';
                $('#MulKP').append(x);
            } else {
                x +=
                    '<td></td></tr>';
                $('#MulKP').html(x);
            }
        }
        $(document).on('click', '#addKP', function() {
            KPCount++;
            mulKP(KPCount);
        });

        $(document).on('click', '.removeKP', function() {
            KPCount--;
            $(this).closest("tr").remove();
        });


        function getSpecialization(EducationId, No) {
            var EducationId = EducationId;
            var No = No;
            $.ajax({
                type: "GET",
                url: "{{ route('getSpecialization') }}?EducationId=" + EducationId,
                async: false,
                beforeSend: function() {
                    $('#SpeLoader' + No).removeClass('d-none');
                    $('#Specialization' + No).addClass('d-none');
                },

                success: function(res) {

                    if (res) {
                        $('#SpeLoader' + No).addClass('d-none');
                        $('#Specialization' + No).removeClass('d-none');
                        $("#Specialization" + No).empty();
                        $("#Specialization" + No).append(
                            '<option value="" selected disabled >Select Specialization</option>');

                        $.each(res, function(key, value) {
                            $("#Specialization" + No).append('<option value="' + value + '">' + key +
                                '</option>');
                        });
                        $("#Specialization" + No).append('<option value="0">Other</option>');


                    } else {
                        $("#Specialization" + No).empty();
                    }
                }
            });
        }

        //===============================Get Employe by Department====================//
        $('#Department').change(function() {
            var DepartmentId = $(this).val();
            if (DepartmentId) {
                $.ajax({
                    type: "GET",
                    url: "{{ route('getResignedEmployee') }}?DepartmentId=" + DepartmentId,
                    beforeSend: function() {
                        $('#EmpLoader').removeClass('d-none');
                        $('#ReplacementFor').addClass('d-none');
                    },
                    success: function(res) {

                        if (res) {
                            $('#EmpLoader').addClass('d-none');
                            $('#ReplacementFor').removeClass('d-none');
                            $("#ReplacementFor").empty();
                            $("#ReplacementFor").append(
                                '<option value="" selected disabled >Select Employee</option>');
                            $.each(res, function(key, value) {
                                $("#ReplacementFor").append('<option value="' + value + '">' +
                                    key +
                                    '</option>');
                            });

                        } else {
                            $("#ReplacementFor").empty();
                        }
                    }
                });
            } else {
                $("#ReplacementFor").empty();

            }
        });

        $('#ReplacementFor').change(function() {
            var EmpId = $(this).val();

            $.ajax({
                type: "GET",
                url: "{{ route('getResignedEmpDetail') }}?EmpId=" + EmpId,
                beforeSend: function() {

                },
                success: function(data) {
                    if (data.empDetails != '') {
                        $('#Designation').val(data.empDetails[0].designation_name);
                        $('#Grade').val(data.empDetails[0].grade_name);
                        $('#ExistingLocation').val(data.empDetails[0].HqName);
                        $('#ExCTC').val(data.empDetails[0].CTC);
                    }
                }
            });

        });


        function getLocation(StateId) {
            var StateId = StateId;
            $.ajax({
                type: "GET",
                url: "{{ route('getDistrict') }}?StateId=" + StateId,
                async: false,
                beforeSend: function() {
                    $('#LocLoader').removeClass('d-none');
                    $('#City').addClass('d-none');
                },

                success: function(res) {

                    if (res) {
                        setTimeout(function() {
                                $('#LocLoader').addClass('d-none');
                                $('#City').removeClass('d-none');
                                $("#City").empty();
                                $("#City").append(
                                    '<option value="" selected disabled >Select City</option>');

                                $.each(res, function(key, value) {
                                    $("#City").append('<option value="' + value + '">' + key +
                                        '</option>');
                                });
                            },
                            500);


                    } else {
                        $("#City").empty();
                    }
                }
            });
        }
        //====================================== Add New MRF to the Database==========================//
        $('#addNewMrfForm').on('submit', function(e) {
            e.preventDefault();
            var form = this;
            for (instance in CKEDITOR.instances) {
                CKEDITOR.instances[instance].updateElement();
            }
            $('#Company').removeAttr('disabled');
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
                        $('#Company').attr('disabled', true);
                        $("#loader").modal('hide');
                        $.each(data.error, function(prefix, val) {
                            $(form).find('span.' + prefix + '_error').text(val[0]);
                        });
                    } else {
                        $(form)[0].reset();
                        $('#loader').modal('hide');
                        toastr.success(data.msg);
                        window.location.href = "{{ route('recruiter_mrf_entry') }}";
                       //window.location.reload();
                    }
                }
            });
        });
    </script>
@endsection