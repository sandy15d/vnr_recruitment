@php

    $jpid = request()->query('jpid');
    $jpid = base64_decode($jpid);
    $query = DB::table('jobpost')
        ->leftJoin('core_department', 'core_department.id', '=', 'jobpost.DepartmentId')
        ->Where('JPId', $jpid)
        ->select('jobpost.*', 'core_department.department_name')
        ->get();

@endphp
    <!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--favicon-->
    <link rel="icon" href="{{ URL::to('/') }}/assets/images/favicon-32x32.png" type="image/png"/>
    <!--plugins-->
    <link href="{{ URL::to('/') }}/assets/plugins/simplebar/css/simplebar.css" rel="stylesheet"/>
    <link href="{{ URL::to('/') }}/assets/plugins/select2/css/select2.min.css" rel="stylesheet"/>
    <link href="{{ URL::to('/') }}/assets/plugins/select2/css/select2-bootstrap4.css" rel="stylesheet"/>
    <link href="{{ URL::to('/') }}/assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css" rel="stylesheet"/>
    <link href="{{ URL::to('/') }}/assets/plugins/metismenu/css/metisMenu.min.css" rel="stylesheet"/>
    <!-- loader-->
    <link href="{{ URL::to('/') }}/assets/css/pace.min.css" rel="stylesheet"/>
    <script src="{{ URL::to('/') }}/assets/js/pace.min.js"></script>

    <!-- Bootstrap CSS -->
    <link href="{{ URL::to('/') }}/assets/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ URL::to('/') }}/assets/css/sweetalert2.min.css"/>
    <link rel="stylesheet" href="{{ URL::to('/') }}/assets/css/toastr.min.css"/>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&amp;display=swap" rel="stylesheet">
    <link href="{{ URL::to('/') }}/assets/css/app.css" rel="stylesheet">
    <link href="{{ URL::to('/') }}/assets/css/icons.css" rel="stylesheet">
    <link href="{{ URL::to('/') }}/assets/css/ijaboCropTool.min.css" rel="stylesheet">
    <title>Job Apply</title>
    <style>
        .borderless td,
        .borderless th {
            border: none;
        }

        .table > :not(caption) > * > * {
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
        @if($query[0]->PostingView =='Show')
        <div class="container-fluid">
            <div class="col-lg-8  mx-auto">
                <div class="col mx-auto">
                    <div class="card">
                        <div class="card-body">
                            <div class="border p-4 rounded">
                                <div class="text-center">
                                    {{-- <h5 class="">Application for : {{ $query[0]->Title }}</h5> --}}
                                    <h5 class="">Application for : {{ $query[0]->department_name }} Department
                                    </h5>
                                </div>
                                <hr style="margin: 10px 0px 10px 0px;">
                                <p class="text-danger" style="font-size: 14px; margin-bottom:0px;">Note: * All
                                    field are mandatory</p>
                                <p style="font-size: 14px; margin-bottom:0px;">Mention your name as per yor Aadhaar
                                    card only.</p>
                                <hr style="margin: 10px 0px 10px 0px;">
                                <form action="{{ route('job_apply') }}" id="jobApplyForm" name="jobApplyForm"
                                      method="POST">
                                    @csrf
                                    <input type="hidden" name="JPId" value="{{ $jpid }}">
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
                                                            <label><input type="radio" name="Title"
                                                                          value="Mr." class="reqinp" checked>
                                                                Mr.</label>&emsp;
                                                            <label><input type="radio" name="Title"
                                                                          value="Ms.">
                                                                Ms.</label>&emsp;
                                                            <label><input type="radio" name="Title"
                                                                          value="Mrs.">
                                                                Mrs.</label>&emsp;
                                                            <label><input type="radio" name="Title"
                                                                          value="Dr.">
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
                                                                   placeholder="First Name as per Aadhaar"
                                                                   onblur="return convertCase(this)"
                                                                   onkeypress="return isLetterKey(event)">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td valign="middle">Middle Name</td>
                                                        <td>
                                                            <input type="text"
                                                                   class="form-control form-control-sm" name="MName"
                                                                   onblur="return convertCase(this)"
                                                                   placeholder="Middle Name as per Aadhaar"
                                                                   onkeypress="return isLetterKey(event)">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td valign="middle">Last Name
                                                        </td>
                                                        <td>
                                                            <input type="text"
                                                                   class="form-control form-control-sm"
                                                                   name="LName" onblur="return convertCase(this)"
                                                                   placeholder="Last Name as per Aadhaar"
                                                                   onkeypress="return isLetterKey(event)">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td valign="middle">Date Of Birth<font color="#FF0000">*
                                                            </font>
                                                        </td>
                                                        <td>
                                                            <input type="date"
                                                                   class="form-control form-control-sm reqinp"
                                                                   name="DOB" id="DOB">
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
                                                                        <select name="FatherTitle"
                                                                                id="FatherTitle"
                                                                                class="form-select form-select-sm d-inline"
                                                                                style="width: 80%;">
                                                                            <option value="Mr.">Mr.</option>
                                                                            <option value="Late">Late</option>
                                                                        </select>
                                                                    </td>
                                                                    <td>
                                                                        <input type="text"
                                                                               class="form-control form-control-sm reqinp"
                                                                               name="FatherName" id="FatherName"
                                                                               onblur="return convertCase(this)"
                                                                               placeholder="Father Name as per Aadhaar"
                                                                               onkeypress="return isLetterKey(event)">
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
                                                            <input type="email"
                                                                   class="form-control form-control-sm reqinp"
                                                                   name="Email" id="Email"
                                                                   placeholder="Email Id"
                                                                   style="text-transform: lowercase">
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
                                                                   placeholder="Phone No">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td valign="middle">Nationality<font color="#FF0000">*
                                                            </font>
                                                        </td>
                                                        <td>
                                                            <select name="Nationality" id="Nationality"
                                                                    class="form-select form-select-sm reqinp"
                                                                    onchange="getState(this.value);getCollege(this.value)">
                                                                <option value="">Select</option>
                                                                @foreach ($country_list as $key => $value)
                                                                    <option value="{{ $key }}">
                                                                        {{ $value }}</option>
                                                                @endforeach
                                                            </select>
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
                                                                               onblur="return convertCase(this)">
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td colspan="3">
                                                                        <input type="text" name="AddressLine2"
                                                                               id="AddressLine2"
                                                                               class="form-control form-control-sm"
                                                                               placeholder="Address Line 2"
                                                                               onblur="return convertCase(this)">
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td colspan="3">
                                                                        <input type="text" name="AddressLine3"
                                                                               id="AddressLine3"
                                                                               class="form-control form-control-sm"
                                                                               placeholder="Address Line 3"
                                                                               onblur="return convertCase(this)">
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>
                                                                        <div class="spinner-border text-primary d-none"
                                                                             role="status" id="StateLoader"><span
                                                                                class="visually-hidden">Loading...</span>
                                                                        </div>
                                                                        <select name="State" id="State"
                                                                                class="form-select form-select-sm reqinp"
                                                                                onchange="getLocation(this.value)">
                                                                            <option value="">Select State
                                                                            </option>
                                                                            {{-- @foreach ($state_list as $key => $value)
                                                                                <option
                                                                                    value="{{ $key }}">
                                                                                    {{ $value }}</option>

                                                                            @endforeach --}}
                                                                        </select>
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
                                                                        </select>
                                                                    </td>

                                                                </tr>
                                                                <tr>
                                                                    <td>
                                                                        <input type="text" name="City"
                                                                               id="City"
                                                                               class="form-control form-control-sm reqinp"
                                                                               placeholder="City / Village"
                                                                               onblur="return convertCase(this)">
                                                                    </td>
                                                                    <td colspan="2">
                                                                        <input type="text" name="PinCode"
                                                                               id="PinCode"
                                                                               class="form-control form-control-sm reqinp"
                                                                               placeholder="Pin Code" maxlength="6"
                                                                               onkeypress="return isNumberKey(event)">
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>

                                                   {{-- <tr>
                                                        <td valign="middle">Aadhaar No.
                                                        </td>
                                                        <td>
                                                            <input type="text" name="Aadhaar" id="Aadhaar"
                                                                   maxlength="12"
                                                                   onkeypress="return isNumberKey(event)"
                                                                   class="form-control form-control-sm"
                                                                   placeholder="Enter Your Aadhaar No.">
                                                        </td>
                                                    </tr>--}}
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
                                                                    </td>
                                                                    <td>
                                                                        <div class="spinner-border text-primary d-none"
                                                                             role="status" id="SpeLoader"><span
                                                                                class="visually-hidden">Loading...</span>
                                                                        </div>
                                                                        <select name="Specialization"
                                                                                id="Specialization"
                                                                                class="form-select form-select-sm reqinp">
                                                                            <option value="">Select
                                                                                Specialization
                                                                            </option>
                                                                        </select>
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
                                                                   placeholder="Enter Your CGPA / Percent">
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
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td valign="middle">University/College<font
                                                                color="#FF0000">
                                                                *
                                                            </font>
                                                        </td>
                                                        <td>
                                                            <div class="spinner-border text-primary d-none"
                                                                 role="status" id="CollegeLoader"><span
                                                                    class="visually-hidden">Loading...</span>
                                                            </div>
                                                            <select name="College" id="College"
                                                                    class="form-select form-select-sm reqinp single-select">
                                                                <option value="">Select</option>
                                                                {{-- @foreach ($institute_list as $key => $value)
                                                                    <option value="{{ $key }}">
                                                                        {{ $value }}</option>
                                                                @endforeach --}}
                                                            </select>
                                                        </td>
                                                    </tr>

                                                    <tr id="other_clg_tr" class="d-none">
                                                        <td>Other College</td>
                                                        <td>
                                                            <input type="text" name="OtherCollege"
                                                                   id="OtherCollege"
                                                                   class="form-control form-control-sm"
                                                                   placeholder="Enter Your College/University Name">
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
                                                                               onclick="showProFromOrNot()">
                                                                        <label class="form-check-label"
                                                                               for="Professional">I am a working
                                                                            professional</label>

                                                                    </td>
                                                                    <td class="form-check form-check-inline">
                                                                        <input class="form-check-input reqinp"
                                                                               type="radio" name="ProfCheck"
                                                                               id="Fresher" value="F"
                                                                               onclick="showProFromOrNot()" checked>
                                                                        <label class="form-check-label"
                                                                               for="Fresher">I am a
                                                                            Fresher</label>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                    <tr id="work_exp" class="d-none">
                                                        <td></td>
                                                        <td>
                                                            <table>
                                                                <tr>
                                                                    <td>Present Company<font color="#FF0000">*
                                                                        </font>
                                                                    </td>
                                                                    <td><input type="text"
                                                                               name="PresentCompany"
                                                                               id="PresentCompany"
                                                                               class="form-control-sm form-control"
                                                                               onblur="return convertCase(this)">
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Designation<font color="#FF0000">*
                                                                        </font>
                                                                    </td>
                                                                    <td><input type="text" name="Designation"
                                                                               id="Designation"
                                                                               class="form-control-sm form-control"
                                                                               onblur="return convertCase(this)">
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Job Start Date<font color="#FF0000">*
                                                                        </font>
                                                                    </td>
                                                                    <td><input type="date" name="JobStartDate"
                                                                               id="JobStartDate"
                                                                               class="form-control-sm form-control">
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Job End Date</td>
                                                                    <td>
                                                                        <div class="row">
                                                                            <div>
                                                                                <input type="date"
                                                                                       name="JobEndDate"
                                                                                       id="JobEndDate"
                                                                                       class="form-control form-control-sm">
                                                                            </div>
                                                                            <div
                                                                                class="form-check form-check-inline">
                                                                                <input class="form-check-input"
                                                                                       type="checkbox" id="StillEmp"
                                                                                       name="StillEmp" value="Y"
                                                                                       style="margin-left:0px;">
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
                                                                               onkeypress="return isNumberKey(event)">
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>CTC(Annual)<font color="#FF0000">* </font>
                                                                    </td>
                                                                    <td>
                                                                        <input type="text" name="CTC"
                                                                               id="CTC"
                                                                               class="form-control form-control-sm"
                                                                               onkeypress="return isNumberKey(event)">
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Notice period in current Company</td>
                                                                    <td>
                                                                        <input type="text" name="NoticePeriod"
                                                                               id="NoticePeriod"
                                                                               class="form-control form-control-sm"
                                                                               onblur="return convertCase(this)">
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Reason for leaving</td>
                                                                    <td>
                                                                        <input type="text" name="ResignReason"
                                                                               id="ResignReason"
                                                                               class="form-control form-control-sm"
                                                                               onblur="return convertCase(this)">
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Total Work Expreience (Over All)</td>
                                                                    <td>
                                                                        <table>
                                                                            <tr>
                                                                                <td><input type="text"
                                                                                           class="form-control form-control-sm"
                                                                                           placeholder="Years" id="ToatalExpYears" name="ToatalExpYears"></td>
                                                                                <td><input type="text"
                                                                                           class="form-control form-control-sm"
                                                                                           placeholder="Months" id="TotalExpMonth" name="TotalExpMonth"></td>
                                                                            </tr>
                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>

                                                    </tr>
                                                    <tr>
                                                        <td valign="middle">Reference<font color="#FF0000">*
                                                            </font>
                                                        </td>
                                                        <td>
                                                            <table style="width: 100%">
                                                                <tr>
                                                                    <td class="form-check form-check-inline">

                                                                        <input class="form-check-input"
                                                                               type="radio" name="RefCheck"
                                                                               id="YesRef" value="Y"
                                                                               onclick="showRefFormOrNot()">
                                                                        <label class="form-check-label"
                                                                               for="YesRef">Yes</label>

                                                                    </td>
                                                                    <td class="form-check form-check-inline">
                                                                        <input class="form-check-input"
                                                                               type="radio" name="RefCheck"
                                                                               id="NoRef" value="N"
                                                                               onclick="showRefFormOrNot()" checked>
                                                                        <label class="form-check-label"
                                                                               for="NoRef">No</label>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                    <tr id="reference_tr" class="d-none">
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
                                                                               onblur="return convertCase(this)"
                                                                               onkeypress="return isLetterKey(event)">
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Company<font color="#FF0000">*
                                                                        </font>
                                                                    </td>
                                                                    <td><input type="text" name="RefCompany"
                                                                               id="RefCompany"
                                                                               class="form-control-sm form-control"
                                                                               onblur="return convertCase(this)">
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Designation<font color="#FF0000">*
                                                                        </font>
                                                                    </td>
                                                                    <td><input type="text"
                                                                               name="RefDesignation"
                                                                               id="RefDesignation"
                                                                               class="form-control-sm form-control"
                                                                               onblur="return convertCase(this)">
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Contact No</td>
                                                                    <td>
                                                                        <input type="text" name="RefContact"
                                                                               id="RefContact" maxlength="13"
                                                                               class="form-control form-control-sm"
                                                                               onkeypress="return isNumberKey(event)">
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Email ID<font color="#FF0000">*
                                                                        </font>
                                                                    </td>
                                                                    <td>
                                                                        <input type="email" name="RefMail"
                                                                               id="RefMail"
                                                                               class="form-control form-control-sm">
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>

                                                    </tr>
                                                    <tr>
                                                        <td></td>
                                                        <td><strong>From where do you came to know about
                                                                this
                                                                job opportunity<br>(please select any one)</strong>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td></td>
                                                        <td>
                                                            @php
                                                                $counter = 1;
                                                            @endphp
                                                            @foreach ($resume_list as $key => $value)
                                                                @php

                                                                    if ($counter % 3 == 0) {
                                                                        echo '<br>';
                                                                    }
                                                                    $counter++;

                                                                @endphp
                                                                <div class="form-check form-check-inline">
                                                                    <input class="form-check-input" type="radio"
                                                                           name="resumesource"
                                                                           id="{{ $key }}"
                                                                           value="{{ $key }}"
                                                                           onclick="checkResumeSource({{ $key }});">
                                                                    <label class="form-check-label"
                                                                           for="{{ $key }}">{{ $value }}</label>
                                                                </div>
                                                            @endforeach
                                                        </td>
                                                    </tr>
                                                    <tr id="othersource_tr" class="d-none">
                                                        <td></td>
                                                        <td>
                                                                <textarea name="OtherResumeSource"
                                                                          id="OtherResumeSource" cols="30" rows="3"
                                                                          class="form-control form-control-sm"
                                                                          placeholder="Please provide Name & Contact nos. of Person, if came through any referral or Consultancy"></textarea>

                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Upload Resume</td>
                                                        <td><input type="file" name="Resume" id="Resume"
                                                                   class="form-control form-control-sm reqinp"
                                                                   accept=".pdf">
                                                            <p class="text-primary">Please upload PDF
                                                                Only.</p>
                                                        </td>
                                                    </tr>
                                                </table>

                                            </div>
                                            <div class="col-lg-3 col-sm-12">
                                                <div style="border: 1px solid #195999;vertical-align:top"
                                                     class=" mt-3 d-inline-block" style="width: 150; height: 150;">
                                                        <span id="preview">
                                                            <center>
                                                                <img src="{{ URL::to('/') }}/assets/images/user.png"
                                                                     style="width: 150px; height: 150px;"
                                                                     id="img1" name="img1" class="img1"/>
                                                            </center>
                                                        </span>
                                                    <center>
                                                        <label>
                                                            <input type="file" name="CandidateImage"
                                                                   id="CandidateImage" class="btn btn-sm mb-1 "
                                                                   style="width: 100px;display: none;"
                                                                   accept="image/png, image/gif, image/jpeg"><span
                                                                class="btn btn-sm btn-light shadow-sm text-primary">Upload
                                                                    Photo</span>
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
        @else
        <div class="border p-4 rounded">
            <div class="text-center">
                <div class="mb-4 text-center">
                    <img src="{{ URL::to('/') }}/assets/images/vnrlogo.png" height="80"
                        alt="" />
                </div>
                <h5 class="text-danger">“The last date of registration has been expired.”</h5>

            </div>
        </div>
        @endif
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
<script src="{{ URL::to('/') }}/assets/js/jquery.min.js"></script>
<script src="{{ URL::to('/') }}/assets/plugins/select2/js/select2.min.js"></script>
<script src="{{ URL::to('/') }}/assets/plugins/simplebar/js/simplebar.min.js"></script>
<script src="{{ URL::to('/') }}/assets/plugins/metismenu/js/metisMenu.min.js"></script>
<script src="{{ URL::to('/') }}/assets/js/sweetalert2.min.js"></script>
<script src="{{ URL::to('/') }}/assets/js/toastr.min.js"></script>
<script src="{{ URL::to('/') }}/assets/js/ijaboCropTool.min.js"></script>


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
        return str.replace(/(?:^|\s)\w/g, function (match) {

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
        $('.reqinp').each(function () {
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
            $('#CTC').addClass('reqinp');

        } else if ($('#Professional').prop("checked") == false) {
            $('#work_exp').addClass('d-none');
            $('#PresentCompany').removeClass('reqinp');
            $('#Designation').removeClass('reqinp');
            $('#JobStartDate').removeClass('reqinp');
            $('#GrossSalary').removeClass('reqinp');
            $('#CTC').removeClass('reqinp');
        }
    }


    function showRefFormOrNot() {
        if ($('#YesRef').prop("checked") == true) {
            $('#reference_tr').removeClass('d-none');
            $('#RefPerson').addClass('reqinp');
            $('#RefDesignation').addClass('reqinp');
            $('#RefCompany').addClass('reqinp');
            $('#RefMail').addClass('reqinp');
        } else if ($('#YesRef').prop("checked") == false) {
            $('#reference_tr').addClass('d-none');
            $('#RefPerson').removeClass('reqinp');
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
            beforeSend: function () {
                $('#LocLoader').removeClass('d-none');
                $('#District').addClass('d-none');
            },

            success: function (res) {

                if (res) {
                    setTimeout(function () {
                            $('#LocLoader').addClass('d-none');
                            $('#District').removeClass('d-none');
                            $("#District").empty();
                            $("#District").append(
                                '<option value="" selected disabled >Select District</option>');

                            $.each(res, function (key, value) {
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
            beforeSend: function () {
                $('#SpeLoader').removeClass('d-none');
                $('#Specialization').addClass('d-none');
            },

            success: function (res) {

                if (res) {
                    setTimeout(function () {
                            $('#SpeLoader').addClass('d-none');
                            $('#Specialization').removeClass('d-none');
                            $("#Specialization").empty();
                            $("#Specialization").append(
                                '<option value="" selected disabled >Select Specialization</option>'
                            );

                            $.each(res, function (key, value) {
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


    $(document).ready(function () {
        $('.single-select').select2({
            theme: 'bootstrap4',
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' :
                'style',
            placeholder: $(this).data('placeholder'),
            allowClear: Boolean($(this).data('allow-clear')),
        });

        $(document).on('change', '#CandidateImage', function (e) {
            const [file] = e.target.files;
            if (file) {
                img1.src = URL.createObjectURL(file);
            }
        });


        $(function () {
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

        $('#Phone').focusout(function () {
            var count = $(this).val().length;
            if (count != 10) {
                alert('Phone number should be of 10 digits');
                $(this).addClass('errorfield');
            } else {
                $(this).removeClass('errorfield');
            }
        });

        $('#Aadhaar').focusout(function () {
            var count = $(this).val().length;
            if (count != 12) {
                alert('Aadhaar Number should be of 12 digits');
                $(this).addClass('errorfield');
            } else {
                $(this).removeClass('errorfield');
            }
        });

    });


    function getState(CountryId) {
        var CountryId = CountryId;
        $.ajax({
            type: "GET",
            url: "{{ route('getState1') }}?CountryId=" + CountryId,
            async: false,
            beforeSend: function () {
                $('#StateLoader').removeClass('d-none');
                $('#State').addClass('d-none');
            },

            success: function (res) {
                if (res) {
                    setTimeout(function () {
                            $('#StateLoader').addClass('d-none');
                            $('#State').removeClass('d-none');
                            $("#State").empty();
                            $("#State").append(
                                '<option value="" selected disabled >Select State</option>');
                            $.each(res, function (key, value) {
                                $("#State").append('<option value="' + value + '">' + key +
                                    '</option>');
                            });
                        },
                        500);

                }
            }
        });
    }

    function getCollege(CountryId) {
        var CountryId = CountryId;
        $.ajax({
            type: "GET",
            url: "{{ route('getCollege1') }}?CountryId=" + CountryId,
            async: false,
            beforeSend: function () {
                $('#CollegeLoader').removeClass('d-none');
                $('#College').addClass('d-none');
            },

            success: function (res) {
                if (res) {
                    setTimeout(function () {
                            $('#CollegeLoader').addClass('d-none');
                            $('#College').removeClass('d-none');
                            $("#College").empty();
                            $("#College").append(
                                '<option value="" selected disabled >Select College</option><option value="637">Other</option>'
                            );
                            $.each(res, function (key, value) {
                                $("#College").append('<option value="' + value + '">' + key +
                                    '</option>');
                            });
                        },
                        500);

                }
            }
        });
    }

    $(document).on('change', '#College', function () {
        var College = $(this).val();
        if (College == 637) {
            $('#other_clg_tr').removeClass('d-none');
        } else {
            $('#other_clg_tr').addClass('d-none');
        }
    });


    $('#jobApplyForm').on('submit', function (e) {
        // debugger;
        e.preventDefault();
        var form = this;
        //   form.append('img1', $('.img1').attr('src'));
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
                beforeSend: function () {
                    $(form).find('span.error-text').text('');
                    $("#loader").modal('show');
                },
                success: function (data) {
                    if (data.status == 400) {
                        $("#loader").modal('hide');
                        $.each(data.error, function (prefix, val) {
                            $(form).find('span.' + prefix +
                                '_error').text(val[0]);
                        });
                    } else if (data.status == 409) {
                        $("#loader").modal('hide');
                        toastr.error(data.msg);
                    } else {
                        $(form)[0].reset();
                        $('#loader').modal('hide');
                        toastr.success(data.msg);
                        var JCId = btoa(data.jcid); //base64 Encode
                        window.location.href =
                            "{{ route('verification') }}?jcid=" + JCId;
                    }
                }
            });
        }

    });
</script>
</body>

</html>
