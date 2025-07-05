@php


$RepEmpId = request()->query('ei');
$res = DB::table('master_employee')
    ->where('EmployeeID', $RepEmpId)
    ->get();

$state_list = DB::table('states')
    ->orderBy('StateName', 'asc')
    ->pluck('StateName', 'StateId');

$education_list = DB::table('master_education')
    ->orderBy('EducationName', 'asc')
    ->pluck('EducationId', 'EducationCode');

$institute_list = DB::table('master_institute')
    ->orderBy('InstituteName', 'asc')
    ->pluck('InstituteName', 'InstituteId');
@endphp
@extends('layouts.master')
@section('title', 'Replacement Manpower Requisition Form')
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
                        <h5 class="mb-0 text-primary">Replacement Manpower Requisition Form</h5>

                    </div>
                    <hr>
                    <form action="{{ route('addRepMrf') }}" method="POST" id="addRepMrfForm">

                        <table class="table">
                            <tr>
                                <th style="width: 25%;">Replacement For:</th>
                                <td>
                                    <input type="text" class="form-control form-control-sm" id="Replacement"
                                        name="Replacement" value="{{ $res[0]->Fname . ' ' . $res[0]->Lname }}" readonly>
                                    <input type="hidden" name="ReplacementFor" id="ReplacementFor"
                                        value="{{ request()->query('ei') }}">
                                </td>
                            </tr>
                            <tr>
                                <th>Designation:</th>
                                <td>
                                    <input type="text" class="form-control form-control-sm" id="Designation"
                                        name="Designation" value="{{ getDesignationCode($res[0]->DesigId) }}" readonly>
                                </td>
                            </tr>
                            <tr>
                                <th>Grade:</th>
                                <td>
                                    <input type="text" class="form-control form-control-sm" id="Grade"
                                        value="{{ getGradeValue($res[0]->GradeId) }}" name="Grade" readonly>
                                </td>
                            </tr>
                            <tr>
                                <th>Existing Location:</th>
                                <td>
                                    <input type="text" class="form-control form-control-sm" id="ExistingLocation"
                                        name="ExistingLocation" value="{{ getHQ($res[0]->Location) }}" readonly>
                                </td>
                            </tr>
                            <tr>
                                <th>Existing CTC:</th>
                                <td>
                                    <input type="text" class="form-control form-control-sm" id="ExCTC" name="ExCTC"
                                        value="{{ $res[0]->CTC }}" readonly>
                                </td>
                            </tr>
                            <tr>
                                <th>Desired Location:</th>
                                <td>
                                    <div style="width: 50%;display: inline-block;float: left">
                                        <select id="State" name="State"
                                            class="form-control form-select form-select-sm reqinp"
                                            onchange="getLocation(this.value)">
                                            <option disabled="" selected="">Select State</option>
                                            @foreach ($state_list as $key => $value)
                                                <option value="{{ $key }}">{{ $value }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="spinner-border text-primary d-none" role="status" id="LocLoader"><span
                                            class="visually-hidden">Loading...</span></div>
                                    <div style="width: 50%;display: inline-block;float: left" class="ml-3">
                                        <select id="City" name="City" class="form-control form-select form-select-sm">
                                            <option disabled="" selected="">Select City</option>
                                        </select>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th>Desired CTC (in Rs.):</th>
                                <td>
                                    <div style="width: 50%;display: inline-block;float: left">
                                        <input type="text" name="MinCTC" id="MinCTC" class="form-control form-control-sm"
                                            placeholder="Min">
                                    </div>
                                    <div style="width: 50%;display: inline-block;float: left" class="ml-3">
                                        <input type="text" name="MaxCTC" id="MaxCTC" class="form-control form-control-sm"
                                            placeholder="Max">
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th>Desired Education:</th>

                                <td>

                                    <table class="table borderless" style="margin-bottom: 0px;">
                                        <tbody id="MulEducation">
                                        </tbody>
                                    </table>
                                    <button id="addEducation" type="button" class="btn btn-sm btn-warning mb-2 mt-2"><i
                                            class="bx bx-plus"></i>Education</button>

                                </td>
                            </tr>
                            <tr>
                                <th>Desired University/College</th>
                                <td>
                                    <select name="University[]" id="University"
                                        class="form-control form-select form-select-sm multiple-select" multiple="multiple">
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
                                        class="form-control form-control-sm reqinp">
                                </td>
                            </tr>
                            <tr>
                                <th>Mandatory Requirements</th>
                                <td>
                                    <table class="table borderless" style="margin-bottom: 0px;">
                                        <tbody id="MulKP">
                                        </tbody>
                                    </table>
                                    <button type="button" name="add" id="addKP" class="btn btn-warning btn-sm mb-2 mt-2"><i
                                            class="bx bx-plus"></i></button>
                                </td>
                            </tr>
                            <tr>
                                <th>Job Description</th>
                                <td>
                                    <textarea name="JobInfo" id="JobInfo" class="JobInfo"></textarea>
                                </td>
                            </tr>
                            <tr>
                                <th>Any Other Remark</th>
                                <td>
                                    <textarea name="Remark" id="Remark" class="form-control" rows="2"></textarea>
                                </td>
                            </tr>
                            <tr>
                                <th>Action:</th>
                                <td>
                                    <button type="submit" class="btn btn-primary btn-sm">Save</button>
                                    <button type="reset" class="btn btn-danger btn-sm">Cancle</button>
                                </td>
                            </tr>
                        </table>
                    </form>
                </div>
            </div>


        </div>
    </div>


@endsection

@section('script_section')
    <script>
        CKEDITOR.replace('JobInfo');
        var EducationList;
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
                '" class="form-control form-select form-select-sm reqinp" onchange="getSpecialization(this.value,' + num +
                ')">' +
                '  <option value="" selected disabled>Select Education</option>' + EducationList +
                '</select>' +
                ' <span class="text-danger error-text Education' + num + '_error"></span>' +
                '</td>';
            x += '<td>' +
                '<div class="spinner-border text-primary d-none" role="status" id="SpeLoader' + num +
                '"> <span class="visually-hidden">Loading...</span></div>' +
                '       <select  id="Specialization' + num +
                '" name="Specialization[]" class="form-control form-select form-select-sm reqinp">' +
                '    <option value="" selected disabled>Select Specialization</option>' +
                '</select>' +
                '<span class="text-danger error-text Specialization' + num + '_error"></span>' +
                '</td>';


            if (num > 1) {
                x +=
                    '<td><button type="button" name="remove" id="" class="btn btn-danger btn-sm  removeEducation"><i class="bx bx-x"></i></td></tr>';
                $('#MulEducation').append(x);
            } else {
                x +=
                    '';
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
                    '<td><button type="button" name="remove" id="" class="btn btn-danger btn-xs  removeKP"><i class="bx bx-x"></td></tr>';
                $('#MulKP').append(x);
            } else {
                x +=
                    '';
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
        //=====================End KP========================
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
                        setTimeout(function() {
                            $('#SpeLoader' + No).addClass('d-none');
                            $('#Specialization' + No).removeClass('d-none');
                            $("#Specialization" + No).empty();
                            $("#Specialization" + No).append(
                                '<option value="" selected disabled >Select Specialization</option>'
                            );

                            $.each(res, function(key, value) {
                                $("#Specialization" + No).append('<option value="' + value +
                                    '">' + key +
                                    '</option>');
                            });
                            $("#Specialization" + No).append('<option value="0">Other</option>');
                        }, 500);

                    } else {
                        $("#Specialization" + No).empty();
                    }
                }
            });
        }

        //====================================== Add Replacement MRF to the Database==========================//
        $('#addRepMrfForm').on('submit', function(e) {
            e.preventDefault();
            var form = this;
            for (instance in CKEDITOR.instances) {
                CKEDITOR.instances[instance].updateElement();
            }
            var reqcond = checkRequired();
            if (reqcond == 1) {
                alert('Please fill required field...!');
            } else {
                $.ajax({
                    url: $(form).attr('action'),
                    method: $(form).attr('method'),
                    data: new FormData(form),
                    processData: false,
                    dataType: 'json',
                    contentType: false,
                    beforeSend: function() {
                        $(form).find('span.error-text').text('');
                        $('#loader').modal('show');
                    },


                    success: function(data) {
                        if (data.status == 400) {
                            $.each(data.error, function(prefix, val) {
                                $(form).find('span.' + prefix + '_error').text(val[0]);
                            });
                        } else {
                            $(form)[0].reset();
                            toastr.success(data.msg);
                            window.location.href = "{{ route('manpowerrequisition') }}";
                        }
                    }
                });
            }

        });

        function checkRequired() {
            var res = 0;
            $('.reqinp').each(function() {
                if ($(this).val() == '' || $(this).val() == null) {
                    $(this).addClass('errorfield');
                    res = 1;
                } else {
                    $(this).removeClass('errorfield');
                }
            });
            return res;
        }
    </script>
@endsection
