@php

$jcid = request()->query('jcid');
$jcid = base64_decode($jcid);
$query = DB::table('jobcandidates')
    ->Where('JCId', $jcid)
    ->first();

@endphp
<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--favicon-->
    <link rel="icon" href="{{ URL::to('/') }}/assets/images/favicon-32x32.png" type="image/png" />
    <!--plugins-->
    <link href="{{ URL::to('/') }}/assets/plugins/simplebar/css/simplebar.css" rel="stylesheet" />
    <link href="{{ URL::to('/') }}/assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css" rel="stylesheet" />
    <link href="{{ URL::to('/') }}/assets/plugins/metismenu/css/metisMenu.min.css" rel="stylesheet" />
    <!-- loader-->
    <link href="{{ URL::to('/') }}/assets/css/pace.min.css" rel="stylesheet" />
    <script src="{{ URL::to('/') }}/assets/js/pace.min.js"></script>
    <!-- Bootstrap CSS -->
    <link href="{{ URL::to('/') }}/assets/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ URL::to('/') }}/assets/css/sweetalert2.min.css" />
    <link rel="stylesheet" href="{{ URL::to('/') }}/assets/css/toastr.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&amp;display=swap" rel="stylesheet">
    <link href="{{ URL::to('/') }}/assets/css/app.css" rel="stylesheet">
    <link href="{{ URL::to('/') }}/assets/css/icons.css" rel="stylesheet">
    <script src="{{ URL::to('/') }}/assets/js/jquery.min.js"></script>
    <title>Job Apply</title>
    <style>
        .borderless td,
        .borderless th {
            border: none;
        }

        .table>:not(caption)>*>* {
            padding: 2px 1px;
        }

        .errorfield {
            border: 2px solid #E8290B;
        }

    </style>
</head>


<body class="bg-login">
    <!--wrapper-->
    <div class="wrapper">
        <div>
            <div class="container-fluid">
                <div class="col-lg-8  mx-auto">
                    <div class="col mx-auto">
                        <div class="card">
                            <div class="card-body">
                                <div class="border p-4 rounded">
                                    <div class="text-center">
                                        <h5 class="">Job Application Form</h5>
                                    </div>
                                    <hr style="margin: 10px 0px 10px 0px;">
                                    <p class="text-danger" style="font-size: 14px; margin-bottom:0px;">Note: * All
                                        field are mandatory</p>
                                    <p style="font-size: 14px; margin-bottom:0px;">Mention your name as per yor Aadhaar
                                        card only.</p>
                                    <hr style="margin: 10px 0px 10px 0px;">
                                    <form action="{{ route('job_apply_manual') }}" id="jobApplyForm"
                                        name="jobApplyForm" method="POST">
                                        @csrf
                                        <input type="hidden" name="JCId" value="{{ $jcid }}">
                                        <div class="form-body">
                                            <div class="row">
                                                <div class="col-lg-9 col-sm-12 table-responsive">
                                                    <table class=" table borderless d-inline-block">
                                                        <tr>
                                                            <td valign="middle" style="width: 150px !important">Title
                                                                <font color="#FF0000">*
                                                                </font>
                                                            </td>
                                                            <td style="width:800px !important">
                                                                <label><input type="radio" name="Title" value="Mr."
                                                                        {{ $query->Title == 'Mr.' ? 'checked' : '' }}>
                                                                    Mr.</label>&emsp;
                                                                <label><input type="radio" name="Title" value="Ms."
                                                                        {{ $query->Title == 'Ms.' ? 'checked' : '' }}>
                                                                    Ms.</label>&emsp;
                                                                <label><input type="radio" name="Title" value="Mrs."
                                                                        {{ $query->Title == 'Mrs.' ? 'checked' : '' }}>
                                                                    Mrs.</label>&emsp;
                                                                <label><input type="radio" name="Title" value="Dr."
                                                                        {{ $query->Title == 'Dr.' ? 'checked' : '' }}>
                                                                    Dr.</label>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td valign="middle" style="width: 300px;">First Name<font
                                                                    color="#FF0000">*</font>
                                                            </td>
                                                            <td>
                                                                <input type="text"
                                                                    class="form-control form-control-sm reqinp"
                                                                    name="FName" id="FName"
                                                                    value="{{ $query->FName }}" readonly
                                                                    onblur="return convertCase(this)" onkeypress="return isLetterKey(event)">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td valign="middle">Middle Name</td>
                                                            <td>
                                                                <input type="text" class="form-control form-control-sm"
                                                                    name="MName" value="{{ $query->MName }}"
                                                                    onblur="return convertCase(this)" onkeypress="return isLetterKey(event)">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td valign="middle">Last Name<font color="#FF0000">*</font>
                                                            </td>
                                                            <td>
                                                                <input type="text"
                                                                    class="form-control form-control-sm"
                                                                    name="LName" value="{{ $query->LName }}"
                                                                    readonly onblur="return convertCase(this)" onkeypress="return isLetterKey(event)">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td valign="middle">Date Of Birth<font color="#FF0000">*
                                                                </font>
                                                            </td>
                                                            <td>
                                                                <input type="date"
                                                                    class="form-control form-control-sm reqinp"
                                                                    name="DOB" id="DOB"
                                                                    value="{{ $query->DOB ?? '' }}">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td valign="middle">Gender<font color="#FF0000">*
                                                                </font>
                                                            </td>
                                                            <td>
                                                                <select name="Gender" id="Gender"
                                                                    class="form-select form-select-sm reqinp">
                                                                    <option value="">Select</option>
                                                                    <option value="M">Male</option>
                                                                    <option value="F">Female</option>
                                                                    <option value="O">Other</option>
                                                                </select>
                                                                <script>
                                                                    $('#Gender').val('{{ $query->Gender }}');
                                                                </script>
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
                                                                                class="form-select form-select-sm d-inline"
                                                                                style="width: 80%;">
                                                                                <option value="Mr.">Mr.</option>
                                                                                <option value="Late">Late</option>
                                                                            </select>
                                                                            <script>
                                                                                $('#FatherTitle').val('{{ $query->FatherTitle }}');
                                                                            </script>
                                                                        </td>
                                                                        <td>
                                                                            <input type="text"
                                                                                class="form-control form-control-sm reqinp"
                                                                                name="FatherName" id="FatherName"
                                                                                onblur="return convertCase(this)"
                                                                                value="{{ $query->FatherName }}" onkeypress="return isLetterKey(event)">
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
                                                                <input type="text"
                                                                    class="form-control form-control-sm reqinp"
                                                                    name="Email" id="Email"
                                                                    value="{{ $query->Email }}">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td valign="middle">Phone No.<font color="#FF0000">*
                                                                </font>
                                                            </td>
                                                            <td>
                                                                <input type="text"
                                                                    class="form-control form-control-sm"
                                                                    name="Phone" id="Phone"
                                                                    onkeypress="return isNumberKey(event)"
                                                                    value="{{ $query->Phone }}">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td valign="middle">Address<font color="#FF0000">*
                                                                </font>
                                                            </td>
                                                            <td>
                                                                <table style="width: 100%">
                                                                    <tr>
                                                                        <td colspan="3">
                                                                            <input type="text" name="AddressLine1"
                                                                                id="AddressLine1"
                                                                                class="form-control form-control-sm reqinp"
                                                                                placeholder="Address Line 1"
                                                                                onblur="return convertCase(this)"
                                                                                value="{{ $query->AddressLine1 ?? '' }}">
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td colspan="3">
                                                                            <input type="text" name="AddressLine2"
                                                                                id="AddressLine2"
                                                                                class="form-control form-control-sm"
                                                                                placeholder="Address Line 2"
                                                                                onblur="return convertCase(this)"
                                                                                value="{{ $query->AddressLine2 ?? '' }}">
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td colspan="3">
                                                                            <input type="text" name="AddressLine3"
                                                                                id="AddressLine3"
                                                                                class="form-control form-control-sm"
                                                                                placeholder="Address Line 3"
                                                                                onblur="return convertCase(this)"
                                                                                value="{{ $query->AddressLine3 ?? '' }}">
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>
                                                                            <select name="State" id="State"
                                                                                class="form-select form-select-sm reqinp"
                                                                                onchange="getLocation(this.value)">
                                                                                <option value="">Select State</option>
                                                                                @foreach ($state_list as $key => $value)
                                                                                    <option
                                                                                        value="{{ $key }}">
                                                                                        {{ $value }}</option>

                                                                                @endforeach
                                                                            </select>
                                                                            <script>
                                                                                $('#State').val('{{ $query->State }}');
                                                                            </script>
                                                                        </td>
                                                                        <td>
                                                                            <div class="spinner-border text-primary d-none"
                                                                                role="status" id="LocLoader"><span
                                                                                    class="visually-hidden">Loading...</span>
                                                                            </div>
                                                                            <select name="District" id="District"
                                                                                class="form-select form-select-sm reqinp">
                                                                                <option value="">Select District
                                                                                </option>
                                                                                @foreach ($district_list as $key => $value)
                                                                                    <option
                                                                                        value="{{ $key }}">
                                                                                        {{ $value }}</option>

                                                                                @endforeach
                                                                            </select>
                                                                            <script>
                                                                                $('#District').val('{{ $query->District }}');
                                                                            </script>
                                                                        </td>

                                                                    </tr>
                                                                    <tr>
                                                                        <td>
                                                                            <input type="text" name="City" id="City"
                                                                                class="form-control form-control-sm reqinp"
                                                                                placeholder="City / Village"
                                                                                onblur="return convertCase(this)"
                                                                                value="{{ $query->City ?? '' }}">
                                                                        </td>
                                                                        <td colspan="2">
                                                                            <input type="text" name="PinCode"
                                                                                id="PinCode"
                                                                                class="form-control form-control-sm reqinp"
                                                                                placeholder="Pin Code" maxlength="6"
                                                                                onkeypress="return isNumberKey(event)"
                                                                                value="{{ $query->PinCode ?? '' }}">
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td valign="middle">Aadhaar No.<font color="#FF0000">*
                                                                </font>
                                                            </td>
                                                            <td>
                                                                <input type="text" name="Aadhaar" id="Aadhaar"
                                                                    maxlength="12"
                                                                    onkeypress="return isNumberKey(event)"
                                                                    class="form-control form-control-sm"
                                                                    value="{{ $query->Aadhaar }}">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td valign="middle">Highest Qualification<font
                                                                    color="#FF0000">*
                                                                </font>
                                                            </td>
                                                            <td>
                                                                <table style="width: 100%">
                                                                    <tr>
                                                                        <td>
                                                                            <select name="Education" id="Education"
                                                                                class="form-select form-select-sm reqinp"
                                                                                onchange="getSpecialization(this.value)">
                                                                                <option value="">Select Education
                                                                                </option>
                                                                                @foreach ($education_list as $key => $value)
                                                                                    <option
                                                                                        value="{{ $key }}">
                                                                                        {{ $value }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                            <script>
                                                                                $('#Education').val('{{ $query->Education }}');
                                                                            </script>
                                                                        </td>
                                                                        <td>
                                                                            <div class="spinner-border text-primary d-none"
                                                                                role="status" id="SpeLoader"><span
                                                                                    class="visually-hidden">Loading...</span>
                                                                            </div>
                                                                            <select name="Specialization"
                                                                                id="Specialization"
                                                                                class="form-select form-select-sm reqinp">
                                                                                <option value="">Select Specialization
                                                                                </option>
                                                                                @foreach ($specialization_list as $key => $value)
                                                                                    <option
                                                                                        value="{{ $key }}">
                                                                                        {{ $value }}</option>

                                                                                @endforeach
                                                                            </select>
                                                                            <script>
                                                                                $('#Specialization').val('{{ $query->Specialization }}');
                                                                            </script>
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td valign="middle">CGPA / Percent<font color="#FF0000">*
                                                                </font>
                                                            </td>
                                                            <td>
                                                                <input type="text" name="CGPA" id="CGPA"
                                                                    class="form-control form-control-sm"
                                                                    value="{{ $query->CGPA ?? '' }}">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td valign="middle">Year of Passing<font color="#FF0000">*
                                                                </font>
                                                            </td>
                                                            <td>
                                                                <select name="PassingYear" id="PassingYear"
                                                                    class="form-select form-select-sm reqinp">
                                                                    <option value="">Select</option>
                                                                    @for ($i = 1980; $i <= date('Y'); $i++)
                                                                        <option value={{ $i }}>
                                                                            {{ $i }}</option>
                                                                    @endfor
                                                                </select>
                                                                <script>
                                                                    $('#PassingYear').val('{{ $query->PassingYear }}');
                                                                </script>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td valign="middle">University/College<font color="#FF0000">
                                                                    *
                                                                </font>
                                                            </td>
                                                            <td>
                                                                <select name="College" id="College"
                                                                    class="form-select form-select-sm reqinp">
                                                                    <option value="">Select</option>
                                                                    @foreach ($institute_list as $key => $value)
                                                                        <option value="{{ $key }}">
                                                                            {{ $value }}</option>
                                                                    @endforeach
                                                                </select>
                                                                <script>
                                                                    $('#College').val('{{ $query->College }}');
                                                                </script>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td valign="middle">Work Experience<font color="#FF0000">*
                                                                </font>
                                                            </td>
                                                            <td>
                                                                <table style="width: 100%">
                                                                    <tr>
                                                                        <td class="form-check form-check-inline">

                                                                            <input class="form-check-input reqinp"
                                                                                type="radio" name="ProfCheck"
                                                                                id="Professional" value="P"
                                                                                onclick="showProFromOrNot()"
                                                                                @php
                                                                                    if ($query != null && $query->Professional == 'P') {
                                                                                        echo 'checked';
                                                                                    }
                                                                                @endphp>
                                                                            <label class="form-check-label"
                                                                                for="Professional">I am a working
                                                                                professional</label>

                                                                        </td>
                                                                        <td class="form-check form-check-inline">
                                                                            <input class="form-check-input reqinp"
                                                                                type="radio" name="ProfCheck"
                                                                                id="Fresher" value="F"
                                                                                onclick="showProFromOrNot()"
                                                                                @php
                                                                                    if ($query != null && $query->Professional == 'F') {
                                                                                        echo 'checked';
                                                                                    }
                                                                                @endphp>

                                                                            <label class="form-check-label"
                                                                                for="Fresher">I am a
                                                                                Fresher</label>
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                        <tr id="work_exp"
                                                            class="{{ $query != null && $query->Professional == 'P' ? '' : 'd-none' }}">
                                                            <td></td>
                                                            <td>
                                                                <table>
                                                                    <tr>
                                                                        <td>Present Company<font color="#FF0000">*
                                                                            </font>
                                                                        </td>
                                                                        <td><input type="text" name="PresentCompany"
                                                                                id="PresentCompany"
                                                                                class="form-control-sm form-control"
                                                                                onblur="return convertCase(this)"
                                                                                value="{{ $query->PresentCompany ?? '' }}">
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Designation<font color="#FF0000">*
                                                                            </font>
                                                                        </td>
                                                                        <td><input type="text" name="Designation"
                                                                                id="Designation"
                                                                                class="form-control-sm form-control"
                                                                                onblur="return convertCase(this)"
                                                                                value="{{ $query->Designation ?? '' }}">
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Job Start Date<font color="#FF0000">*
                                                                            </font>
                                                                        </td>
                                                                        <td><input type="date" name="JobStartDate"
                                                                                id="JobStartDate"
                                                                                class="form-control-sm form-control"
                                                                                value="{{ $query->JobStartDate ?? null }}">
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Job End Date</td>
                                                                        <td>
                                                                            <div class="row">
                                                                                <div>
                                                                                    <input type="date" name="JobEndDate"
                                                                                        id="JobEndDate"
                                                                                        class="form-control form-control-sm"
                                                                                        value="{{ $query->JobEndDate ?? null }}">
                                                                                </div>
                                                                                <div
                                                                                    class="form-check form-check-inline">
                                                                                    <input class="form-check-input"
                                                                                        type="checkbox" id="StillEmp"
                                                                                        name="StillEmp" value="Y"
                                                                                        style="margin-left:0px;"
                                                                                        {{ $query->StillEmp == 'Y' ? 'checked' : '' }}>
                                                                                    <label class="form-check-label"
                                                                                        for="StillEmp">If still
                                                                                        employed,
                                                                                        tick here</label>
                                                                                </div>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Gross Salary(per month)
                                                                            <font color="#FF0000">* </font>
                                                                        </td>
                                                                        <td>
                                                                            <input type="text" name="GrossSalary"
                                                                                id="GrossSalary"
                                                                                class="form-control form-control-sm"
                                                                                onkeypress="return isNumberKey(event)"
                                                                                value="{{ $query->GrossSalary ?? '' }}">
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>CTC(Annual)<font color="#FF0000">* </font>
                                                                        </td>
                                                                        <td>
                                                                            <input type="text" name="CTC" id="CTC"
                                                                                class="form-control form-control-sm"
                                                                                onkeypress="return isNumberKey(event)"
                                                                                value="{{ $query->CTC ?? '' }}">
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Notice period in current Company</td>
                                                                        <td>
                                                                            <input type="text" name="NoticePeriod"
                                                                                id="NoticePeriod"
                                                                                class="form-control form-control-sm"
                                                                                onblur="return convertCase(this)"
                                                                                value="{{ $query->NoticePeriod ?? '' }}">
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Reason for leaving</td>
                                                                        <td>
                                                                            <input type="text" name="ResignReason"
                                                                                id="ResignReason"
                                                                                class="form-control form-control-sm"
                                                                                onblur="return convertCase(this)"
                                                                                value="{{ $query->ResignReason ?? '' }}">
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </td>

                                                        </tr>
                                                        <tr>
                                                            <td valign="middle">Reference<font color="#FF0000">* </font>
                                                            </td>
                                                            <td>
                                                                <table style="width: 100%">
                                                                    <tr>
                                                                        <td class="form-check form-check-inline">

                                                                            <input class="form-check-input" type="radio"
                                                                                name="RefCheck" id="YesRef" value="Y"
                                                                                onclick="showRefFormOrNot()"
                                                                                @php
                                                                                    if ($query != null && $query->Reference == 'Y') {
                                                                                        echo 'checked';
                                                                                    }

                                                                                @endphp>
                                                                            <label class="form-check-label"
                                                                                for="YesRef">Yes</label>

                                                                        </td>
                                                                        <td class="form-check form-check-inline">
                                                                            <input class="form-check-input" type="radio"
                                                                                name="RefCheck" id="NoRef" value="N"
                                                                                onclick="showRefFormOrNot()"
                                                                                @php
                                                                                    if ($query != null && $query->Reference == 'N') {
                                                                                        echo 'checked';
                                                                                    }
                                                                                @endphp>
                                                                            <label class="form-check-label"
                                                                                for="NoRef">No</label>
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                        <tr id="reference_tr"
                                                            class="{{ $query != null && $query->Reference == 'Y' ? '' : 'd-none' }}">
                                                            <td></td>
                                                            <td>
                                                                <table>
                                                                    <tr>
                                                                        <td>Person Name<font color="#FF0000">*
                                                                            </font>
                                                                        </td>
                                                                        <td><input type="text" name="RefPerson"
                                                                                id="RefPerson"
                                                                                class="form-control-sm form-control"
                                                                                onblur="return convertCase(this)" value="{{$query->RefPerson ?? ''}}">
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Company<font color="#FF0000">*
                                                                            </font>
                                                                        </td>
                                                                        <td><input type="text" name="RefCompany"
                                                                                id="RefCompany"
                                                                                class="form-control-sm form-control"
                                                                                onblur="return convertCase(this)" value="{{$query->RefCompany ?? ''}}">
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Designation<font color="#FF0000">*
                                                                            </font>
                                                                        </td>
                                                                        <td><input type="text" name="RefDesignation"
                                                                                id="RefDesignation"
                                                                                class="form-control-sm form-control"
                                                                                onblur="return convertCase(this)" value="{{$query->RefDesignation ?? ''}}">
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Contact No</td>
                                                                        <td>
                                                                            <input type="text" name="RefContact"
                                                                                id="RefContact" maxlength="13"
                                                                                class="form-control form-control-sm"
                                                                                onkeypress="return isNumberKey(event)" value="{{$query->RefContact ?? ''}}">
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Email ID<font color="#FF0000">*
                                                                            </font>
                                                                        </td>
                                                                        <td>
                                                                            <input type="email" name="RefMail"
                                                                                id="RefMail"
                                                                                class="form-control form-control-sm" value="{{$query->RefMail ?? ''}}">
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </td>

                                                        </tr>

                                                        <tr>
                                                            <td>Upload Resume</td>
                                                            <td>


                                                                @if ($query->Resume != null)
                                                                        @php
                                                                            if ($query->Resume) {
                                                                                $resumeUrl = Storage::disk('s3')->url('Recruitment/Resume/' . $query->Resume);
                                                                                echo "<a class='link-primary' style='font-size:14px;' href='$resumeUrl' target='_blank'>Resume</a>  <small class='text-secondary'>  Click to view uploaded resume.</small>";
                                                                            }
                                                                        @endphp
                                                                @else
                                                                    <input type="file" name="Resume" id="Resume"
                                                                        class="form-control form-control-sm"
                                                                        accept=".pdf,.docx">
                                                                    <p class="text-primary">Plese upload PDF/Word
                                                                        Document
                                                                        Only.</p>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    </table>

                                                </div>
                                                <div class="col-lg-3 col-sm-12">
                                                    <div style="border: 1px solid #195999;vertical-align:top"
                                                        class=" mt-3 d-inline-block" style="width: 150; height: 150;">
                                                        <span id="preview">
                                                            <center>
                                                                @if ($query->CandidateImage != null)
                                                                    <img src="{{ Storage::disk('s3')->url('Recruitment/Picture/' . $query->CandidateImage) }}"
                                                                        style="width: 150px; height: 150px;"
                                                                        id="img1" />
                                                                @else
                                                                    <img src="{{ URL::to('/') }}/assets/images/user.png"
                                                                        style="width: 150px; height: 150px;"
                                                                        id="img1" />
                                                                @endif

                                                            </center>
                                                        </span>
                                                        <center>
                                                            <label>
                                                                <input type="file" name="CandidateImage"
                                                                    id="CandidateImage" class="btn btn-sm mb-1 "
                                                                    style="width: 100px;display: none;"
                                                                    accept="image/png, image/gif, image/jpeg"><span
                                                                    class="btn btn-sm btn-light shadow-sm text-primary">Upload
                                                                    photo</span>
                                                            </label>
                                                        </center>
                                                    </div>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="text-center">
                                                <button type="submit" class="btn btn-primary">Submit</button>
                                                <button type="reset" class="btn btn-danger">Cancle</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end row-->
            </div>
        </div>
    </div>
    <div class="modal" id="loader" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered" style="width:220px;">
            <div class="modal-content" style="border-radius:10px;">

                <div class="modal-body">
                    <img alt="" src="{{ URL::to('/') }}/assets/images/loader.gif">
                </div>
            </div>
        </div>
    </div>

    <script src="{{ URL::to('/') }}/assets/js/bootstrap.bundle.min.js"></script>
    <!--plugins-->

    <script src="{{ URL::to('/') }}/assets/plugins/simplebar/js/simplebar.min.js"></script>
    <script src="{{ URL::to('/') }}/assets/plugins/metismenu/js/metisMenu.min.js"></script>

    <script src="{{ URL::to('/') }}/assets/js/sweetalert2.min.js"></script>
    <script src="{{ URL::to('/') }}/assets/js/toastr.min.js"></script>



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
        function isLetterKey(evt) {
            var charCode = (evt.which) ? evt.which : event.keyCode;
            if ((charCode < 65 || charCode > 90) && (charCode < 97 || charCode > 122) && charCode !== 32) {
                return false;
            }
            return true;
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

        function showProFromOrNot() {
            if ($('#Professional').prop("checked") == true) {
                $('#work_exp').removeClass('d-none');
                $('#PresentCompany').addClass('reqinp');
                $('#Designation').addClass('reqinp');
                $('#JobStartDate').addClass('reqinp');
                $('#GrossSalary').addClass('reqinp');

            } else if ($('#Professional').prop("checked") == false) {
                $('#work_exp').addClass('d-none');
                $('#PresentCompany').removeClass('reqinp');
                $('#Designation').removeClass('reqinp');
                $('#JobStartDate').removeClass('reqinp');
                $('#GrossSalary').removeClass('reqinp');
            }
        }

        function showRefFormOrNot() {
            if ($('#YesRef').prop("checked") == true) {
                $('#reference_tr').removeClass('d-none');
                $('#PersonName').addClass('reqinp');
                $('#RefDesignation').addClass('reqinp');
                $('#RefCompany').addClass('reqinp');
                $('#RefMail').addClass('reqinp');
            } else if ($('#YesRef').prop("checked") == false) {
                $('#reference_tr').addClass('d-none');
                $('#PersonName').removeClass('reqinp');
                $('#RefDesignation').removeClass('reqinp');
                $('#RefCompany').removeClass('reqinp');
                $('#RefMail').removeClass('reqinp');
            }
        }

        function checkResumeSource(id) {
            if (id == 5 || id == 6 || id == 8) {
                $('#othersource_tr').removeClass('d-none');
            } else {
                $('#othersource_tr').addClass('d-none');
            }

        }

        function getLocation(StateId) {
            var StateId = StateId;
            $.ajax({
                type: "GET",
                url: "{{ route('getDistrict') }}?StateId=" + StateId,
                async: false,
                beforeSend: function() {
                    $('#LocLoader').removeClass('d-none');
                    $('#District').addClass('d-none');
                },

                success: function(res) {

                    if (res) {
                        setTimeout(function() {
                                $('#LocLoader').addClass('d-none');
                                $('#District').removeClass('d-none');
                                $("#District").empty();
                                $("#District").append(
                                    '<option value="" selected disabled >Select District</option>');

                                $.each(res, function(key, value) {
                                    $("#District").append('<option value="' + value + '">' + key +
                                        '</option>');
                                });
                            },
                            500);


                    } else {
                        $("#District").empty();
                    }
                }
            });
        }

        function getSpecialization(EducationId) {
            var EducationId = EducationId;
            $.ajax({
                type: "GET",
                url: "{{ route('getSpecialization') }}?EducationId=" + EducationId,
                async: false,
                beforeSend: function() {
                    $('#SpeLoader').removeClass('d-none');
                    $('#Specialization').addClass('d-none');
                },

                success: function(res) {

                    if (res) {
                        setTimeout(function() {
                                $('#SpeLoader').addClass('d-none');
                                $('#Specialization').removeClass('d-none');
                                $("#Specialization").empty();
                                $("#Specialization").append(
                                    '<option value="" selected disabled >Select Specialization</option>'
                                );

                                $.each(res, function(key, value) {
                                    $("#Specialization").append('<option value="' + value + '">' +
                                        key +
                                        '</option>');
                                });
                                $("#Specialization").append('<option value="0">Other</option>');
                            },
                            500);

                    } else {
                        $("#Specialization").empty();
                    }
                }
            });
        }


        $(document).ready(function() {

            $(document).on('change', '#CandidateImage', function(e) {
                const [file] = e.target.files;
                if (file) {
                    img1.src = URL.createObjectURL(file);
                }
            });

            $(function() {
                var dtToday = new Date();
                var month = dtToday.getMonth() + 1; // jan=0; feb=1 .......
                var day = dtToday.getDate();
                var year = dtToday.getFullYear() - 18;
                if (month < 10)
                    month = '0' + month.toString();
                if (day < 10)
                    day = '0' + day.toString();
                var minDate = year + '-' + month + '-' + day;
                var maxDate = year + '-' + month + '-' + day;
                $('#DOB').attr('max', maxDate);
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

        });

        $('#jobApplyForm').on('submit', function(e) {
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
                            var JCId = btoa(data.jcid); //base64 Encode
                            window.location.href = "{{ route('verification') }}?jcid=" + JCId;
                        }
                    }
                });
            }

        });
    </script>
</body>

</html>
