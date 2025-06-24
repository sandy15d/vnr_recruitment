@php

@endphp
@extends('layouts.master')
@section('title', 'Job Application Manual Entry')
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

    </style>
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb  mb-3">
            <div class="mb-1 d-flex justify-content-between">
                <div class="breadcrumb-title pe-3">Job Application Manual Form Entry</div>
                <div class="">
                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                        data-bs-target="#application_form_modal"><i class="bx bx-user mr-1"></i>New Application</button>
                </div>
            </div>

        </div>
        <!--end breadcrumb-->
        <div class="card border-top border-0 border-4 border-primary">
            <div class="card-body">
                <table class="table table-hover table-striped table-condensed align-middle text-center table-bordered"
                    id="JobApplications" style="width: 100%">
                    <thead class="text-center bg-primary text-light">
                        <tr class="text-center">
                            <td>#</td>
                            <td class="th-sm">S.No</td>
                            <td>Reference No</td>
                            <td>Name</td>
                            <td>Contact</td>
                            <td>Email</td>
                            <td>Link</td>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="modal fade" id="application_form_modal" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog  modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-info bg-gradient">
                    <h5 class="modal-title text-white">Job Application Form (Manual Entry)</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="job_application_manual" method="POST" id="jobapplicationform">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-9 col-sm-12 table-responsive">
                                <table class=" table borderless d-inline-block">
                                    <tr>
                                        <td valign="middle">Source of Resume<font color="#FF0000">*
                                            </font>
                                        </td>
                                        <td>
                                            <select name="ResumeSource" id="ResumeSource"
                                                class="form-select form-select-sm reqinp"
                                                onchange="checkResumeSource(this.value);">
                                                <option value="">Select</option>
                                                @foreach ($resume_list as $key => $value)
                                                    <option value="{{ $key }}">{{ $value }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                    </tr>
                                    <tr id="othersource_tr" class="d-none">
                                        <td></td>
                                        <td>
                                            <textarea name="OtherResumeSource" id="OtherResumeSource" cols="30" rows="3"
                                                class="form-control form-control-sm"
                                                placeholder="Please provide Name & Contact nos. of Person, if came through any referral or Consultancy"></textarea>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td valign="middle" style="width: 150px !important">Title
                                            <font color="#FF0000">*
                                            </font>
                                        </td>
                                        <td style="width:800px !important">
                                            <label><input type="radio" name="Title" value="Mr." class="reqinp"
                                                    checked>
                                                Mr.</label>&emsp;
                                            <label><input type="radio" name="Title" value="Ms.">
                                                Ms.</label>&emsp;
                                            <label><input type="radio" name="Title" value="Mrs.">
                                                Mrs.</label>&emsp;
                                            <label><input type="radio" name="Title" value="Dr.">
                                                Dr.</label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td valign="middle" style="width: 300px;">First Name<font color="#FF0000">*</font>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control form-control-sm reqinp" name="FName"
                                                id="FName" onblur="return convertCase(this)">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td valign="middle">Middle Name</td>
                                        <td>
                                            <input type="text" class="form-control form-control-sm" name="MName"
                                                onblur="return convertCase(this)">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td valign="middle">Last Name<font color="#FF0000">*</font>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control form-control-sm reqinp" name="LName"
                                                onblur="return convertCase(this)">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td valign="middle">Gender<font color="#FF0000">*
                                            </font>
                                        </td>
                                        <td>
                                            <select name="Gender" id="Gender" class="form-select form-select-sm reqinp">
                                                <option value="">Select</option>
                                                <option value="M">Male</option>
                                                <option value="F">Female</option>
                                                <option value="O">Other</option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td valign="middle">Father's Name<font color="#FF0000">*
                                            </font>
                                        </td>
                                        <td>
                                            <table style="width: 100%">
                                                <tr>
                                                    <td>
                                                        <select name="FatherTitle" id="FatherTitle"
                                                            class="form-select form-select-sm d-inline" style="width: 80%;">
                                                            <option value="Mr.">Mr.</option>
                                                            <option value="Late">Late</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control form-control-sm reqinp"
                                                            name="FatherName" id="FatherName"
                                                            onblur="return convertCase(this)">
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td valign="middle">Email ID<font color="#FF0000">*
                                            </font>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control form-control-sm reqinp" name="Email"
                                                id="Email">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td valign="middle">Phone No.<font color="#FF0000">*
                                            </font>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control form-control-sm" name="Phone"
                                                id="Phone" onkeypress="return isNumberKey(event)">
                                        </td>
                                    </tr>

                                    <tr>
                                        <td valign="middle">Aadhaar No.<font color="#FF0000">*
                                            </font>
                                        </td>
                                        <td>
                                            <input type="text" name="Aadhaar" id="Aadhaar" maxlength="12"
                                                onkeypress="return isNumberKey(event)"
                                                class="form-control form-control-sm">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Upload Resume</td>
                                        <td><input type="file" name="Resume" id="Resume"
                                                class="form-control form-control-sm reqinp" accept=".pdf,.docx">
                                            <p class="text-primary">Plese upload PDF/Word Document
                                                Only.</p>
                                        </td>
                                    </tr>
                                </table>

                            </div>
                            <div class="col-lg-3 col-sm-12">
                                <div style="border: 1px solid #195999;vertical-align:top" class=" mt-3 d-inline-block"
                                    style="width: 150; height: 150;">
                                    <span id="preview">
                                        <center>
                                            <img src="{{ URL::to('/') }}/assets/images/user.png"
                                                style="width: 150px; height: 150px;" id="img1" />
                                        </center>
                                    </span>
                                    <center>
                                        <label>
                                            <input type="file" name="CandidateImage" id="CandidateImage"
                                                class="btn btn-sm mb-1 " style="width: 100px;display: none;"
                                                accept="image/png, image/gif, image/jpeg"><span
                                                class="btn btn-sm btn-light shadow-sm text-primary">Upload
                                                photo</span>
                                        </label>
                                    </center>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="SaveApplication">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal" id="loader" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered" style="width:220px;">
            <img alt="" src="{{ URL::to('/') }}/assets/images/loader.gif">
        </div>
    </div>
@endsection
@section('script_section')
    <script>
        function isNumberKey(evt) {
            var charCode = (evt.which) ? evt.which : event.keyCode
            if (charCode > 31 && (charCode < 48 || charCode > 57))
                return false;

            return true;
        }

        function convertCase(evt) {
            var text = $(evt).val();
            $(evt).val(camelCase(text));
        }

        function camelCase(str) {
            return str.replace(/(?:^|\s)\w/g, function(match) {

                return match.toUpperCase();
            });
        }

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

        function checkResumeSource(id) {

            if (id == 5 || id == 6 || id == 8) {
                $('#othersource_tr').removeClass('d-none');
            } else {
                $('#othersource_tr').addClass('d-none');
            }

        }
        $(document).ready(function() {
            $(document).on('change', '#CandidateImage', function(e) {
                const [file] = e.target.files;
                if (file) {
                    img1.src = URL.createObjectURL(file);
                }
            });
            $('#Phone').focusout(function() {
                var count = $(this).val().length;
                if (count != 10) {
                    alert('Phone number should be of 10 digits');
                    $(this).addClass('errorfield');
                } else {
                    $(this).removeClass('errorfield');
                }
            });
            $('#Aadhaar').focusout(function() {
                var count = $(this).val().length;
                if (count != 12) {
                    alert('Aadhaar Number should be of 12 digits');
                    $(this).addClass('errorfield');
                } else {
                    $(this).removeClass('errorfield');
                }
            });

            $('#JobApplications').DataTable({
                processing: true,
                info: true,
                ajax: "{{ route('getManualEntryCandidate') }}",
                columns: [{
                        data: 'chk',
                        name: 'chk'
                    },
                    {
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
                        data: 'Phone',
                        name: 'Phone',

                    },
                    {
                        data: 'Email',
                        name: 'Email'
                    },
                    {
                        data: 'Link',
                        name: 'Link'
                    },
                ],

            });
        });

        $('#jobapplicationform').on('submit', function(e) {
            e.preventDefault();
            var form = this;
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
                            window.location.reload();
                        }
                    }
                });
            }

        });

        function copylink(id) {
            var copyText = document.getElementById("link" + id);
            copyText.select();
            copyText.setSelectionRange(0, 99999)
            document.execCommand("copy");
            alert("Copied Link: " + copyText.value);
        }
    </script>
@endsection
