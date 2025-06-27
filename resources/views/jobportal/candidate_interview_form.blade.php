@php
    use Illuminate\Support\Carbon;
$Year = Carbon::now()->year;
$sendingId = request()->query('jaid');
$JAId = base64_decode($sendingId);
$Rec = DB::table('jobapply')
    ->join('jobcandidates', 'jobapply.JCId', '=', 'jobcandidates.JCId')
    ->leftJoin('jobpost', 'jobapply.JPId', '=', 'jobpost.JPId')
    ->leftJoin('jf_contact_det', 'jobcandidates.JCId', '=', 'jf_contact_det.JCId')
    ->leftJoin('jf_pf_esic', 'jobcandidates.JCId', '=', 'jf_pf_esic.JCId')
    ->leftJoin('core_department','core_department.id','=','jobapply.Department')
    ->where('JAId', $JAId)
    ->select('jobapply.*','core_department.department_name', 'jobcandidates.*', 'jobpost.Title as JobTitle', 'jobpost.JobCode', 'jf_contact_det.pre_address', 'jf_contact_det.pre_city', 'jf_contact_det.pre_state', 'jf_contact_det.pre_pin', 'jf_contact_det.pre_dist', 'jf_contact_det.perm_address', 'jf_contact_det.perm_city', 'jf_contact_det.perm_state', 'jf_contact_det.perm_pin', 'jf_contact_det.perm_dist', 'jf_contact_det.cont_one_name', 'jf_contact_det.cont_one_relation', 'jf_contact_det.cont_one_number', 'jf_contact_det.cont_two_name', 'jf_contact_det.cont_two_relation', 'jf_contact_det.cont_two_number', 'jf_pf_esic.UAN', 'jf_pf_esic.PFNumber', 'jf_pf_esic.ESICNumber', 'jf_pf_esic.BankName', 'jf_pf_esic.BranchName', 'jf_pf_esic.IFSCCode', 'jf_pf_esic.AccountNumber', 'jf_pf_esic.PAN')
    ->first();


$JCId = $Rec->JCId;

$FamilyInfo = DB::table('jf_family_det')
    ->where('JCId', $JCId)
    ->get();
$Education = DB::table('candidateeducation')
    ->where('JCId', $JCId)
    ->get();
$Experience = DB::table('jf_work_exp')
    ->where('JCId', $JCId)
    ->get();

$Training = DB::table('jf_tranprac')
    ->where('JCId', $JCId)
    ->get();

$PreRef = DB::table('jf_reference')
    ->where('JCId', $JCId)
    ->where('from', 'Previous Organization')
    ->get();

$VnrRef = DB::table('jf_reference')
    ->where('JCId', $JCId)
    ->where('from', 'VNR')
    ->get();

$OtherDetail = DB::table('pre_job_details')
    ->where('JCId', $JCId)
    ->first();

$AboutAns = DB::table('about_answer')
    ->where('JCId', $JCId)
    ->first();

$Docs = DB::table('jf_docs')
    ->where('JCId', $JCId)
    ->first();
$country_list = DB::table('core_country')->pluck('country_name', 'id');
$institute_list = DB::table("master_institute")->join('states','states.StateId','=','master_institute.StateId')->where('states.CountryId',$Rec->Nationality)->orderBy('InstituteName', 'asc')->pluck("InstituteName", "InstituteId");
@endphp
        <!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="{{ URL::to('/') }}/assets/plugins/smart-wizard/css/smart_wizard_all.min.css" rel="stylesheet"
          type="text/css"/>
    <link href="{{ URL::to('/') }}/assets/plugins/select2/css/select2.min.css" rel="stylesheet"/>
    <link href="{{ URL::to('/') }}/assets/plugins/select2/css/select2-bootstrap4.css" rel="stylesheet"/>
    <link href="{{ URL::to('/') }}/assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&amp;display=swap" rel="stylesheet">
    <link href="{{ URL::to('/') }}/assets/css/app.css" rel="stylesheet">
    <link href="{{ URL::to('/') }}/assets/css/icons.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ URL::to('/') }}/assets/css/sweetalert2.min.css"/>
    <link rel="stylesheet" href="{{ URL::to('/') }}/assets/css/toastr.min.css"/>
    <link rel="stylesheet" href="{{ URL::to('/') }}/assets/css/BsMultiSelect.css"/>
    <script src="https://kit.fontawesome.com/b0b5b1cf9f.js" crossorigin="anonymous"></script>
    <script src="{{ URL::to('/') }}/assets/ckeditor/ckeditor.js"></script>
    <script src="{{ URL::to('/') }}/assets/js/jquery.min.js"></script>
    <title>Interview Form</title>
    <style>
        .errorfield {
            border: 1px solid red !important;
        }

    </style>
</head>

<body>
<div class="wrapper">
    <div class="page-content">
        <input type="hidden" name="JCId" id="JCId" value="{{ $Rec->JCId }}">
        <input type="hidden" name="CountryId" id="CountryId" value="{{ $Rec->Nationality }}">
        @if($Rec->InterviewConfirm ==='N')
            <section>
                <div class="row">
                    <div class="col-md-12">
                        <div class="card panel-default">
                            <div class="card-body">
                                <div class="col-md-12" id="en">

                                    <h4 class="text-center">Please read the following instructions carefully
                                        before you begin:</h4>
                                    <h4><strong><u>General Instructions:</u></strong></h4>
                                    <ol style="text-align: justify">
                                        <li>To ensure a smooth and seamless interview process, please follow these instructions for filling out the online interview application form.
                                        </li>
                                        <li>For the best user experience, we strongly recommend using a personal computer (PC) or laptop with a stable internet connection.</li>
                                        <li>The form may not function optimally on mobile devices due to potential compatibility issues. Use the Google Chrome or Mozilla Firefox web browsers for the most reliable performance.
                                        </li>
                                        <li>Before you begin, make sure you have all the necessary documents and information readily available, such as your resume, cover letter, and any other supporting documents.
                                        </li>
                                        <li>The photo's file size should be limited to 50 kb or less. You can utilize any online tool to compress the image size if necessary.</li>
                                    </ol>
                                    <p>If you encounter any technical issues while filling out the form, please contact our HR department for assistance.</p>
                                    <label>
                                        <input type="checkbox" id="i_confirm">&nbsp;&nbsp;I give my consent to participate in the interview on the communicated scheduled date to me.</label>
                                    <hr>
                                    <div class=" text-center">
                                        <button class="btn btn-primary btn-block" id="proceed_btn">Proceed</button>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </section>
        @else
            @if ($Rec->InterviewSubmit === 1)
                <div class="wrapper">
                    <div
                            class="section-authentication-signin d-flex align-items-center justify-content-center my-5 my-lg-0">
                        <div class="container-fluid">
                            <div class="row row-cols-1 row-cols-lg-2 row-cols-xl-2">
                                <div class="col mx-auto">
                                    <div class="card bg-success text-white">
                                        <div class="card-body">
                                            <h4 class="card-title text-white">Dear Candidate,
                                            </h4>
                                            <h5 class="card-text text-white">You have successfully submitted your
                                                Pre Interview Form.</h5>
                                            <p class="card-text mb-0"> In case of any further query kindly contact
                                                HR- Recruitment team.</p>
                                            <p class="card-text">Contact No: 0771- 4350005</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="row">

                    <div class="col-xl-12 mx-auto">
                        <div class="card">
                            <div class="card-body">
                                {{--  <h5 class="text-center mb-2">Pre Interview Form For {{ $Rec->JobTitle }}</h5> --}}
                                <h5 class="text-center mb-2">Pre Interview Form For {{$Rec->department_name}}
                                    Department</h5>
                                <div id="smartwizard" class="sw sw-justified sw-theme-default">
                                    <ul class="nav">
                                        <li class="nav-item">
                                            <a class="nav-link inactive done" href="#personal"> <strong>Personal
                                                    Info</strong></a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link inactive done" href="#contact">
                                                <strong>Contact</strong></a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link inactive active" href="#education">
                                                <strong>Education</strong></a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link inactive active" href="#family">
                                                <strong>Family</strong></a>
                                        </li>

                                        <li class="nav-item">
                                            <a class="nav-link inactive done" href="#experience">
                                                <strong>Experience</strong></a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link inactive done" href="#about">
                                                <strong>About Yourself</strong></a>
                                        </li>

                                        <li class="nav-item">
                                            <a class="nav-link inactive active" href="#other">
                                                <strong>Other</strong></a>
                                        </li>


                                        <li class="nav-item">
                                            <a class="nav-link inactive done" href="#document">
                                                <strong>Documents</strong></a>
                                        </li>

                                        <li class="nav-item">
                                            <a class="nav-link inactive active" href="#final"> <strong>Final
                                                    Submit</strong></a>
                                        </li>
                                    </ul>

                                    <div class="tab-content">

                                        <div id="personal" class="tab-pane" role="tabpanel"
                                             aria-labelledby="step-1"
                                             style="position: static; left: auto; width: 1019px; display: none;">
                                            <h5>Personal Information</h5>
                                            <div class="col-lg-8 mx-auto mt-4">
                                                <div class="col mx-auto">
                                                    <form action="{{ route('SavePersonalInfo') }}" id="personal_form"
                                                          method="POST">
                                                        <div class="row">
                                                            <div class="col-md-8 col-sm-12">
                                                                <div class="form-group row">
                                                                    <label class="col-form-label col-md-3">Title</label>
                                                                    <div class="col-md-9">
                                                                        <label><input type="radio" name="Title"
                                                                                      value="Mr."
                                                                                    {{ $Rec->Title == 'Mr.' ? 'checked' : '' }}>
                                                                            Mr.</label>&emsp;
                                                                        <label><input type="radio" name="Title"
                                                                                      value="Ms."
                                                                                    {{ $Rec->Title == 'Ms.' ? 'checked' : '' }}>
                                                                            Ms.</label>&emsp;
                                                                        <label><input type="radio" name="Title"
                                                                                      value="Mrs."
                                                                                    {{ $Rec->Title == 'Mrs.' ? 'checked' : '' }}>
                                                                            Mrs.</label>&emsp;
                                                                        <label><input type="radio" name="Title"
                                                                                      value="Dr."
                                                                                    {{ $Rec->Title == 'Dr.' ? 'checked' : '' }}>
                                                                            Dr.</label>

                                                                    </div>
                                                                </div>
                                                                <div class="form-group row">
                                                                    <label class="col-form-label col-md-3">First
                                                                        Name</label>
                                                                    <div class="col-md-9 col-sm-12">
                                                                        <input type="text"
                                                                               class="form-control form-control-sm"
                                                                               value="{{ $Rec->FName }}" name="FName"
                                                                               id="FName" onkeypress="return isLetterKey(event)">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row">
                                                                    <label class="col-form-label col-md-3">Middle
                                                                        Name</label>
                                                                    <div class="col-md-9">
                                                                        <input type="text"
                                                                               class="form-control form-control-sm"
                                                                               name="MName" id="MName"
                                                                               value="{{ $Rec->MName }}" onkeypress="return isLetterKey(event)">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row">
                                                                    <label class="col-form-label col-md-3">Last
                                                                        Name</label>
                                                                    <div class="col-md-9">
                                                                        <input type="text"
                                                                               class="form-control form-control-sm"
                                                                               id="LName" name="LName"
                                                                               value="{{ $Rec->LName }}" onkeypress="return isLetterKey(event)">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row">
                                                                    <label class="col-form-label col-md-3">Date of
                                                                        Birth</label>
                                                                    <div class="col-md-9">
                                                                        <input type="date"
                                                                               class="form-control form-control-sm reqinp"
                                                                               id="DOB" name="DOB"
                                                                               value="{{ $Rec->DOB }}">
                                                                    </div>
                                                                </div>

                                                                <div class="form-group row">
                                                                    <label for=""
                                                                           class="col-form-label col-md-3">Gender</label>
                                                                    <div class="col-md-9">
                                                                        <select name="Gender" id="Gender"
                                                                                class="form-select form-select-sm reqinp">
                                                                            <option value="">Select</option>
                                                                            <option value="M">Male</option>
                                                                            <option value="F">Female</option>
                                                                            <option value="O">Other</option>
                                                                        </select>
                                                                    </div>
                                                                    <script>
                                                                        $('#Gender').val('{{ $Rec->Gender }}');
                                                                    </script>
                                                                </div>
                                                                <div class="form-group row">
                                                                    <label
                                                                            class="col-form-label col-md-3">Nationality</label>
                                                                    <div class="col-md-9">
                                                                        <select name="Nationality" id="Nationality"
                                                                                class="form-select form-select-sm"
                                                                                disabled>
                                                                            <option value="">Select</option>
                                                                            @foreach ($country_list as $key => $value)
                                                                                <option value="{{ $key }}">
                                                                                    {{ $value }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                        <script>
                                                                            $('#Nationality').val('{{ $Rec->Nationality }}');
                                                                        </script>
                                                                    </div>

                                                                </div>
                                                                <div class="form-group row">
                                                                    <label
                                                                            class="col-form-label col-md-3">Religion</label>
                                                                    <div class="col-md-9">
                                                                        <select name="Religion" id="Religion"
                                                                                class="form-select form-select-sm reqinp">
                                                                            <option value="Hinduism">Hinduism
                                                                            </option>
                                                                            <option value="Islam">Islam</option>
                                                                            <option value="Christianity">
                                                                                Christianity
                                                                            </option>
                                                                            <option value="Sikhism">Sikhism</option>
                                                                            <option value="Buddhism">Buddhism
                                                                            </option>
                                                                            <option value="Jainism">Jainism</option>
                                                                            <option value="Others">Others</option>
                                                                        </select>
                                                                        <input type="text" name="OtherReligion"
                                                                               id="OtherReligion"
                                                                               class="form-control form-control-sm d-none mt-2"
                                                                               placeholder="Other Religion">
                                                                        <script>
                                                                            $('#Religion').val('{{ $Rec->Religion }}');
                                                                        </script>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row">
                                                                    <label class="col-form-label col-md-3">Caste</label>
                                                                    <div class="col-md-9">
                                                                        <select name="Category" id="Category"
                                                                                class="form-select form-select-sm reqinp">
                                                                            <option value="ST">ST</option>
                                                                            <option value="SC">SC</option>
                                                                            <option value="OBC">OBC</option>
                                                                            <option value="General">General</option>
                                                                            <option value="Other">Other</option>
                                                                        </select>
                                                                        <input type="text" name="OtherCategory"
                                                                               id="OtherCategory"
                                                                               class="form-control form-control-sm d-none mt-2"
                                                                               placeholder="Other Category">
                                                                    </div>
                                                                    <script>
                                                                        $('#Category').val('{{ $Rec->Caste }}');
                                                                    </script>
                                                                </div>
                                                                <div class="form-group row">
                                                                    <label class="col-form-label col-md-3">Marital
                                                                        Status</label>
                                                                    <div class="col-md-9">
                                                                        <select name="MaritalStatus" id="MaritalStatus"
                                                                                class="form-select form-select-sm reqinp">
                                                                            <option value=""></option>
                                                                            <option value="Single">Single</option>
                                                                            <option value="Married">Married</option>
                                                                            <option value="Divorced">Divorced
                                                                            </option>
                                                                            <option value="Widowed">Widowed</option>
                                                                        </select>
                                                                    </div>
                                                                    <script>
                                                                        $('#MaritalStatus').val('{{ $Rec->MaritalStatus }}');
                                                                    </script>
                                                                </div>

                                                                <div class="form-group row {{ $Rec->MaritalStatus == 'Married' ? '' : 'd-none' }}"
                                                                     id="MDate">
                                                                    <label class="col-form-label col-md-3">Marriage
                                                                        Date</label>
                                                                    <div class="col-md-9">
                                                                        <input type="date" name="MarriageDate"
                                                                               id="MarriageDate"
                                                                               class="form-select form-select-sm"
                                                                               value="{{ $Rec->MarriageDate }}">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row {{ $Rec->MaritalStatus == 'Married' ? '' : 'd-none' }}"
                                                                     id="Spouse">
                                                                    <label class="col-form-label col-md-3">spouse
                                                                        Name</label>
                                                                    <div class="col-md-9">
                                                                        <input class="form-control form-control-sm"
                                                                               type="text" id="SpouseName"
                                                                               name="SpouseName"
                                                                               value="{{ $Rec->SpouseName }}" style="text-transform:capitalize">
                                                                    </div>
                                                                </div>

                                                            </div>
                                                            <div class="col-lg-4 col-md-4 col-sm-12">
                                                                <div style="border: 1px solid #195999;vertical-align:top"
                                                                     class=" mt-3 d-inline-block"
                                                                     style="width: 150; height: 150;">
                                                                    <span id="preview">
                                                                        <center>
                                                                            <input type="hidden" name="old_image"
                                                                                   id="old_image"
                                                                                   value="{{ $Rec->CandidateImage }}">
                                                                            @if ($Rec->CandidateImage != null)
                                                                                <img src="{{ url('file-view/Picture/' . $Rec->CandidateImage) }}"
                                                                                     style="width: 150px; height: 150px;"
                                                                                     id="img1"/>
                                                                            @else
                                                                                <img src="{{ URL::to('/') }}/assets/images/user.png"
                                                                                     style="width: 150px; height: 150px;"
                                                                                     id="img1"/>
                                                                            @endif
                                                                        </center>
                                                                    </span>
                                                                    <center>
                                                                        <label>
                                                                            <input type="file" name="CandidateImage"
                                                                                   id="CandidateImage"
                                                                                   class="btn btn-sm mb-1"
                                                                                   style="width: 100px;display: none;"
                                                                                   accept="image/png, image/gif, image/jpeg"><span
                                                                                    class="btn btn-sm btn-light shadow-sm text-primary">Upload
                                                                                Photo</span>
                                                                        </label>
                                                                    </center>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="submit-section text-center mt-4">
                                                            <button class="btn btn-primary submit-btn">Save
                                                                Details
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                        <div id="contact" class="tab-pane" role="tabpanel"
                                             aria-labelledby="step-2"
                                             style="position: static; left: auto; width: 1017px; display: none;">
                                            <h5> Contact Information:</h5>
                                            <div class="row mt-4">
                                                <div class="col-lg-12">
                                                    <form action="{{ route('SaveContact') }}" id="contact_form"
                                                          method="POST">
                                                        <div class="row">
                                                            <div class="col-md-12 col-sm-12">
                                                                <div class="form-group row">
                                                                    <label class="col-form-label col-md-1">Email
                                                                        1:</label>
                                                                    <div class="col-md-2">
                                                                        <input type="text"
                                                                               class="form-control form-control-sm reqinp_con"
                                                                               id="Email1" name="Email1"
                                                                               value="{{ $Rec->Email ?? '' }}">
                                                                    </div>
                                                                    <label class="col-form-label col-md-1">Email
                                                                        2:</label>
                                                                    <div class="col-md-2">
                                                                        <input type="text"
                                                                               class="form-control form-control-sm"
                                                                               id="Email2" name="Email2"
                                                                               value="{{ $Rec->Email2 ?? '' }}">
                                                                    </div>
                                                                    <label class="col-form-label col-md-1">Contact
                                                                        1:</label>
                                                                    <div class="col-md-2">
                                                                        <input type="text"
                                                                               class="form-control form-control-sm reqinp_con"
                                                                               id="Contact1" name="Contact1"
                                                                               value="{{ $Rec->Phone ?? '' }}">
                                                                    </div>
                                                                    <label class="col-form-label col-md-1">Contact
                                                                        2:</label>
                                                                    <div class="col-md-2">
                                                                        <input type="text"
                                                                               class="form-control form-control-sm"
                                                                               id="Contact2" name="Contact2"
                                                                               value="{{ $Rec->Phone2 ?? '' }}">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <hr class="mt-2"
                                                                style="border-top: 3px dotted #2d0eb3;background-color: transparent;"/>

                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <p class="fw-bold">Present Address</p>
                                                                    <div class="form-group row">
                                                                        <label
                                                                                class="col-form-label col-md-1">Address</label>
                                                                        <div class="col-md-6">
                                                                            <input type="text"
                                                                                   class="form-control form-control-sm reqinp_con"
                                                                                   id="PreAddress" name="PreAddress"
                                                                                   value="{{ $Rec->pre_address }}">
                                                                        </div>

                                                                    </div>
                                                                    <div class="form-group row">
                                                                        <label
                                                                                class="col-form-label col-md-1">State</label>
                                                                        <div class="col-md-2">
                                                                            <select name="PreState" id="PreState"
                                                                                    class="form-select form-select-sm reqinp_con"
                                                                                    onchange="getLocation(this.value);">
                                                                                <option value="">Select State
                                                                                </option>
                                                                                @foreach ($state_list as $key => $value)
                                                                                    <option
                                                                                            value="{{ $key }}">
                                                                                        {{ $value }}
                                                                                    </option>
                                                                                @endforeach
                                                                            </select>
                                                                            <script>
                                                                                $('#PreState').val('{{ $Rec->pre_state }}');
                                                                            </script>
                                                                        </div>
                                                                        <label
                                                                                class="col-form-label col-md-1">District</label>
                                                                        <div class="col-md-2">
                                                                            <div class="spinner-border text-primary d-none"
                                                                                 role="status" id="PreDistLoader">
                                                                                <span
                                                                                        class="visually-hidden">Loading...</span>
                                                                            </div>
                                                                            <select name="PreDistrict" id="PreDistrict"
                                                                                    class="form-select form-select-sm reqinp_con">
                                                                                @foreach ($district_list as $key => $value)
                                                                                    <option
                                                                                            value="{{ $key }}">
                                                                                        {{ $value }}
                                                                                    </option>
                                                                                @endforeach
                                                                            </select>
                                                                            <script>
                                                                                $('#PreDistrict').val('{{ $Rec->pre_dist }}');
                                                                            </script>
                                                                        </div>
                                                                        <label
                                                                                class="col-form-label col-md-1">City</label>
                                                                        <div class="col-md-2">
                                                                            <input type="text" name="PreCity"
                                                                                   id="PreCity"
                                                                                   class="form-control form-control-sm reqinp_con"
                                                                                   value="{{ $Rec->pre_city }}">
                                                                        </div>
                                                                        <label class="col-form-label col-md-1"
                                                                               style="float: right">Pin Code</label>
                                                                        <div class="col-md-2">
                                                                            <input type="text" name="PrePin" id="PrePin"
                                                                                   class="form-control form-control-sm reqinp_con"
                                                                                   value="{{ $Rec->pre_pin }}">
                                                                        </div>
                                                                    </div>

                                                                </div>
                                                                <p><input type="checkbox" name="AddChk" id="AddChk"
                                                                          onchange="ticksameadd()">
                                                                    Tick if your
                                                                    Present address and Permanent address are the
                                                                    same
                                                                </p>
                                                                <div class="col-md-12">
                                                                    <p class="fw-bold">Permanent Address</p>
                                                                    <div class="form-group row">
                                                                        <label class="col-form-label col-md-1">Address
                                                                            1</label>
                                                                        <div class="col-md-2">
                                                                            <input type="text"
                                                                                   class="form-control form-control-sm reqinp_con"
                                                                                   id="PermAddress" name="PermAddress"
                                                                                   value="{{ $Rec->perm_address }}">
                                                                        </div>

                                                                    </div>

                                                                    <div class="form-group row">
                                                                        <label
                                                                                class="col-form-label col-md-1">State</label>
                                                                        <div class="col-md-2">
                                                                            <select name="PermState" id="PermState"
                                                                                    class="form-select form-select-sm reqinp_con"
                                                                                    onchange="getLocation1(this.value);">
                                                                                <option value="">Select State
                                                                                </option>
                                                                                @foreach ($state_list as $key => $value)
                                                                                    <option
                                                                                            value="{{ $key }}">
                                                                                        {{ $value }}
                                                                                    </option>
                                                                                @endforeach
                                                                            </select>
                                                                            <script>
                                                                                $('#PermState').val('{{ $Rec->perm_state }}');
                                                                            </script>
                                                                        </div>
                                                                        <label
                                                                                class="col-form-label col-md-1">District</label>
                                                                        <div class="col-md-2">
                                                                            <div class="spinner-border text-primary d-none"
                                                                                 role="status" id="PermDistLoader">
                                                                                <span
                                                                                        class="visually-hidden">Loading...</span>
                                                                            </div>
                                                                            <select name="PermDistrict"
                                                                                    id="PermDistrict"
                                                                                    class="form-select form-select-sm reqinp_con">
                                                                                @foreach ($district_list as $key => $value)
                                                                                    <option
                                                                                            value="{{ $key }}">
                                                                                        {{ $value }}
                                                                                    </option>
                                                                                @endforeach
                                                                            </select>
                                                                            <script>
                                                                                $('#PermDistrict').val('{{ $Rec->perm_dist }}');
                                                                            </script>
                                                                        </div>
                                                                        <label
                                                                                class="col-form-label col-md-1">City</label>
                                                                        <div class="col-md-2">
                                                                            <input type="text" name="PermCity"
                                                                                   id="PermCity"
                                                                                   class="form-control form-control-sm reqinp_con"
                                                                                   value="{{ $Rec->perm_city }}">
                                                                        </div>
                                                                        <label class="col-form-label col-md-1"
                                                                               style="float: right">Pin Code</label>
                                                                        <div class="col-md-2">
                                                                            <input type="text" name="PermPin"
                                                                                   id="PermPin"
                                                                                   class="form-control form-control-sm reqinp_con"
                                                                                   value="{{ $Rec->perm_pin }}">
                                                                        </div>
                                                                    </div>

                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="submit-section text-center mt-4">
                                                            <button class="btn btn-primary submit-btn">Save
                                                                Details
                                                            </button>
                                                        </div>
                                                    </form>

                                                </div>
                                            </div>
                                        </div>

                                        <div id="education" class="tab-pane" role="tabpanel"
                                             aria-labelledby="step-3"
                                             style="position: static; left: auto; width: 1017px; display: none;">
                                            <h5>Educational Information:</h5>
                                            <p class="text-danger"><b>Important Note:</b> Please ensure that you list all your educational details in ascending order.</p>
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <form id="EducationInfoForm"
                                                          action="{{ route('SaveEducation') }}" method="POST">
                                                        <div class="table-responsive">
                                                            <table class="table table-bordered">
                                                                <thead class="text-center">
                                                                <tr>
                                                                    <th>Qualification</th>
                                                                    <th style="width: 20%">Course</th>
                                                                    <th style="width: 20%">Specialization
                                                                    </th>
                                                                    <th>Board/University</th>
                                                                    <th>Passing Year</th>
                                                                    <th style="width: 10%">Percentage</th>
                                                                    <th>Attchment</th>
                                                                    <th></th>
                                                                </tr>
                                                                </thead>
                                                                <tbody id="EducationData">
                                                                <tr>
                                                                    <td style="width: 12%">
                                                                        <select name="Qualification[]"
                                                                                id="Qualification1" class="form-select form-select-sm edureq">
                                                                            <option value="">Select</option>
                                                                            <option value="Below 10th">Below 10th
                                                                            </option>
                                                                            <option value="10th">10th</option>
                                                                            <option value="12th">12th</option>
                                                                            <option value="Graduation">Graduation
                                                                            </option>
                                                                            <option value="Post_Graduation">Post
                                                                                Graduation
                                                                            </option>
                                                                            <option value="Doctorate">Doctorate</option>
                                                                            <option value="Diploma">Diploma</option>
                                                                            <option value="PG_Diploma">PG Diploma
                                                                            </option>
                                                                            <option value="Other">Other</option>

                                                                        </select>
                                                                    </td>
                                                                    <td style="width: 10%">
                                                                        <select name="Course[]" id="Course1"
                                                                                class="form-select form-select-sm edureq"
                                                                                onchange="getSpecialization(this.value,1)">
                                                                            <option value="">Select</option>

                                                                            @foreach ($education_list as $key => $value)
                                                                                <option
                                                                                        value="{{ $key }}">
                                                                                    {{ $value }}
                                                                                </option>
                                                                            @endforeach
                                                                        </select>
                                                                    </td>
                                                                    <td style="width: 15%">
                                                                        <select name="Specialization[]"
                                                                                id="Specialization1"
                                                                                class="form-select form-select-sm edureq">
                                                                            <option value="">Select</option>
                                                                            <option value="0">Other</option>
                                                                            @foreach ($specialization_list as $key => $value)
                                                                                <option
                                                                                        value="{{ $key }}">
                                                                                    {{ $value }}
                                                                                </option>
                                                                            @endforeach
                                                                        </select>
                                                                    </td>
                                                                    <td style="width: 20%">
                                                                        <select name="Collage[]" id="Collage1"
                                                                                class="form-select form-select-sm edureq"
                                                                                onchange="getOtherInstitute(1);">
                                                                            <option value="">Select</option>
                                                                            <option value="637">Other</option>
                                                                            @foreach ($institute_list as $key => $value)
                                                                                <option
                                                                                        value="{{ $key }}">
                                                                                    {{ $value }}
                                                                                </option>
                                                                            @endforeach
                                                                        </select>
                                                                        <input type="text" name="OtherInstitute[]"
                                                                               id="OtherInstitute1"
                                                                               class="form-control form-control-sm mt-1 d-none">
                                                                    </td>
                                                                    <td>
                                                                        <select name="PassingYear[]"
                                                                                id="PassingYear1"
                                                                                class="form-select form-select-sm edureq">
                                                                            <option value="">Select</option>
                                                                            @for ($i = 1980; $i <= $Year; $i++)
                                                                                <option
                                                                                        value="{{ $i }}">
                                                                                    {{ $i }}
                                                                                </option>
                                                                            @endfor
                                                                        </select>
                                                                    </td>
                                                                    <td>
                                                                        <input type="text" name="Percentage[]"
                                                                               id="Percentage1"
                                                                               class="form-control form-control-sm edureq">
                                                                    </td>
                                                                    <td>
                                                                        <input type="file" name="Attachment[]" id="Attachment1" class="form-control form-control-sm">
                                                                    </td>
                                                                    <td></td>
                                                                </tr>

                                                                </tbody>
                                                            </table>
                                                        </div>

                                                        <input type="button" value="Add Qualification" id="addEducation"
                                                               class="btn btn-warning btn-sm">

                                                        <div class="submit-section text-center">
                                                            <button class="btn btn-primary submit-btn">Save
                                                                Details
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                        <div id="family" class="tab-pane" role="tabpanel"
                                             aria-labelledby="step-3"
                                             style="position: static; left: auto; width: 1017px; display: none;">
                                            <h5>Family Information</h5>
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <form id="FamilyInfoForm" action="{{ route('SaveFamily') }}"
                                                          method="POST">
                                                        <div class="table-responsive">
                                                            <table class="table table-bordered">
                                                                <thead class="text-center">
                                                                <tr>
                                                                    <td style="width: 20%">Relation</td>
                                                                    <td style="width: 20%">Name</td>
                                                                    <td style="10%">DOB</td>
                                                                    <td style="width: 20%">Qualification</td>
                                                                    <td style="width: 20%">Occupation</td>
                                                                    <td style="width: 10%">Delete</td>
                                                                </tr>
                                                                </thead>
                                                                <tbody id="FamilyData">
                                                                <tr>
                                                                    <td>
                                                                        <input type="text" name="Relation[]"
                                                                               id="Relation1" value="Father"
                                                                               class="form-control form-control-sm"
                                                                               readonly>
                                                                    </td>
                                                                    <td>
                                                                        <input type="text" name="RelationName[]"
                                                                               id="RelationName1"
                                                                               class="form-control form-control-sm reqinp_fam" style="text-transform: capitalize;">
                                                                    </td>
                                                                    <td>
                                                                        <input type="date" name="RelationDOB[]"
                                                                               id="RelationDOB1"
                                                                               class="form-control form-control-sm reqinp_fam">
                                                                    </td>
                                                                    <td>
                                                                        <select name="RelationQualification[]"
                                                                                id="RelationQualification1"
                                                                                class="form-control form-select form-select-sm reqinp_fam">
                                                                            <option value="" selected=""
                                                                                    disabled="">
                                                                                Select Education
                                                                            </option>
                                                                            @foreach ($education_list as $key => $value)
                                                                                <option
                                                                                        value="{{ $value }}">
                                                                                    {{ $value }}
                                                                                </option>
                                                                            @endforeach
                                                                        </select>
                                                                    </td>
                                                                    <td>
                                                                        <input type="text"
                                                                               name="RelationOccupation[]"
                                                                               id="RelationOccupation1"
                                                                               class="form-control form-control-sm reqinp_fam" style="text-transform: capitalize;">
                                                                    </td>
                                                                    <td></td>
                                                                </tr>
                                                                <tr>
                                                                    <td>
                                                                        <input type="text" name="Relation[]"
                                                                               id="Relation2" value="Mother"
                                                                               class="form-control form-control-sm"
                                                                               readonly>
                                                                    </td>
                                                                    <td>
                                                                        <input type="text" name="RelationName[]"
                                                                               id="RelationName2"
                                                                               class="form-control form-control-sm reqinp_fam" style="text-transform: capitalize;">
                                                                    </td>
                                                                    <td>
                                                                        <input type="date" name="RelationDOB[]"
                                                                               id="RelationDOB2"
                                                                               class="form-control form-control-sm reqinp_fam">
                                                                    </td>
                                                                    <td>
                                                                        <select name="RelationQualification[]"
                                                                                id="RelationQualification2"
                                                                                class="form-control form-select form-select-sm reqinp_fam">
                                                                            <option value="" selected=""
                                                                                    disabled="">
                                                                                Select Education
                                                                            </option>
                                                                            @foreach ($education_list as $key => $value)
                                                                                <option
                                                                                        value="{{ $value }}">
                                                                                    {{ $value }}
                                                                                </option>
                                                                            @endforeach
                                                                        </select>
                                                                    </td>
                                                                    <td>
                                                                        <input type="text"
                                                                               name="RelationOccupation[]"
                                                                               id="RelationOccupation2"
                                                                               class="form-control form-control-sm reqinp_fam" style="text-transform: capitalize;">
                                                                    </td>
                                                                    <td></td>
                                                                </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                        <input type="button" value="Add Member" id="addMember"
                                                               class="btn btn-warning btn-sm">
                                                        <div class="submit-section text-center">
                                                            <button class="btn btn-primary submit-btn">Save
                                                                Details
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                        <div id="experience" class="tab-pane" role="tabpanel"
                                             aria-labelledby="step-2" style="display: none;">
                                            <div class="row">
                                                <div class="col-lg-12 table-responsive">
                                                    <form id="ExperienceForm" action="{{ route('SaveExperience') }}"
                                                          method="POST">
                                                        <div style="text-align: center">
                                                            <p class="fw-bold mt-2">Are you a working
                                                                Professional or
                                                                Fresher?</p>
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input" type="radio"
                                                                       name="ProfCheck" id="Professional" value="P"
                                                                       onclick="showProFromOrNot('prof')"
                                                                        {{ $Rec->Professional == 'P' ? 'checked' : '' }}>
                                                                <label class="form-check-label" for="Professional">I am
                                                                    a Working
                                                                    professional</label>
                                                            </div>
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input" type="radio"
                                                                       name="ProfCheck" id="Fresher" value="F"
                                                                       onclick="showProFromOrNot('fres')"
                                                                        {{ $Rec->Professional == 'F' ? 'checked' : '' }}>
                                                                <label class="form-check-label" for="Fresher"> I am
                                                                    a
                                                                    Fresher</label>
                                                            </div>
                                                        </div>

                                                        <div class="row {{ $Rec->Professional == 'F' ? 'd-none' : '' }}"
                                                             id="professional_div">
                                                            <p class="fw-bold mb-3">Details of Current
                                                                Employement:
                                                            </p>

                                                            <div class="row">
                                                                <div class="col-lg-12">
                                                                    <div class="form-group row">
                                                                        <label class="col-form-label col-md-2">Name
                                                                            of
                                                                            Company</label>
                                                                        <div class="col-md-3">
                                                                            <input type="text" name="CurrCompany"
                                                                                   id="CurrCompany"
                                                                                   class="form-control form-control-sm"
                                                                                   value="{{ $Rec->PresentCompany }}">
                                                                        </div>

                                                                        <label class="col-form-label col-md-2">Date
                                                                            of
                                                                            Joining</label>
                                                                        <div class="col-md-3">
                                                                            <input type="date" name="CurrJoinDate"
                                                                                   id="CurrJoinDate"
                                                                                   class="form-control form-control-sm"
                                                                                   value="{{ $Rec->JobStartDate }}">
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group row">
                                                                        <label
                                                                                class="col-form-label col-md-2">Designation
                                                                            / Position</label>
                                                                        <div class="col-md-3">
                                                                            <input type="text" name="CurrDesignation"
                                                                                   id="CurrDesignation"
                                                                                   class="form-control form-control-sm"
                                                                                   value="{{ $Rec->Designation }}">
                                                                        </div>

                                                                        <label class="col-form-label col-md-2">Annual
                                                                            Package (CTC)</label>
                                                                        <div class="col-md-3">
                                                                            <input type="text" name="CurrCTC"
                                                                                   id="CurrCTC"
                                                                                   class="form-control form-control-sm"
                                                                                   value="{{ $Rec->CTC }}">
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group row">
                                                                        <label class="col-form-label col-md-2">Salary
                                                                            (Per Month)</label>
                                                                        <div class="col-md-3">
                                                                            <input type="text" name="CurrSalary"
                                                                                   id="CurrSalary"
                                                                                   class="form-control form-control-sm"
                                                                                   value="{{ $Rec->GrossSalary }}">
                                                                        </div>

                                                                        <label class="col-form-label col-md-2">Notice
                                                                            Perid <br> in Current
                                                                            Organization</label>
                                                                        <div class="col-md-3">
                                                                            <input type="text" name="NoticePeriod"
                                                                                   id="NoticePeriod"
                                                                                   class="form-control form-control-sm"
                                                                                   value="{{ $Rec->NoticePeriod }}">
                                                                        </div>
                                                                    </div>

                                                                    <div class="form-group row">
                                                                        <label class="col-form-label col-md-2">State
                                                                            the reason <br> for which you are
                                                                            seeking
                                                                            <br> for the job change:</label>
                                                                        <div class="col-md-3">
                                                                            <input type="text" name="ResignReason"
                                                                                   id="ResignReason"
                                                                                   class="form-control form-control-sm"
                                                                                   value="{{ $Rec->ResignReason }}">
                                                                        </div>

                                                                        <label class="col-form-label col-md-2">Expected
                                                                            Annual Package (CTC)</label>
                                                                        <div class="col-md-3">
                                                                            <input type="text" name="ExpCTC" id="ExpCTC"
                                                                                   class="form-control form-control-sm"
                                                                                   value="{{ $Rec->ExpectedCTC }}">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <hr class="mt-2"
                                                                style="border-top: 3px dotted #2d0eb3;background-color: transparent;"/>
                                                            <div class="row">
                                                                <p class="fw-bold mt-2">Present Job
                                                                    Responsibility:
                                                                </p>
                                                                <div class="col-lg-12">
                                                                    <div class="form-group row">
                                                                        <div class="col-md-10">
                                                                            <textarea name="JobResponsibility"
                                                                                      id="JobResponsibility"
                                                                                      class="form-control form-control-sm">{{ $Rec->JobResponsibility }}</textarea>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="row">
                                                                <p class="fw-bold mt-1">Reporting Details:
                                                                </p>
                                                                <div class="col-lg-12">
                                                                    <div class="form-group row">
                                                                        <label
                                                                                class="col-form-label col-md-2">Reporting
                                                                            Manager Name:</label>
                                                                        <div class="col-md-3">
                                                                            <input type="text" name="ReportingManager"
                                                                                   id="ReportingManager"
                                                                                   class="form-control form-control-sm"
                                                                                   value="{{ $Rec->Reporting }}">
                                                                        </div>

                                                                        <label
                                                                                class="col-form-label col-md-2">Reporting
                                                                            Manager Designation</label>
                                                                        <div class="col-md-3">
                                                                            <input type="text" name="RepDesignation"
                                                                                   id="RepDesignation"
                                                                                   class="form-control form-control-sm"
                                                                                   value="{{ $Rec->RepDesig }}">
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group row">
                                                                        <p class="fw-bold mt-1">No. of
                                                                            employee directly reporting to
                                                                            you:</p>
                                                                        <label class="col-form-label col-md-2">On
                                                                            Roll
                                                                            Employees</label>
                                                                        <div class="col-md-3">
                                                                            <input type="text" name="OnRollRepToMe"
                                                                                   id="OnRollRepToMe"
                                                                                   class="form-control form-control-sm"
                                                                                   value="{{ $OtherDetail->OnRollRepToMe ?? '' }}">
                                                                        </div>

                                                                        <label class="col-form-label col-md-2">Third
                                                                            party employees</label>
                                                                        <div class="col-md-3">
                                                                            <input type="text" name="ThirdPartyRepToMe"
                                                                                   id="ThirdPartyRepToMe"
                                                                                   class="form-control form-control-sm"
                                                                                   value="{{ $OtherDetail->ThirdPartyRepToMe ?? '' }}">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="row">
                                                                <p class="fw-bold mt-2">Working Territory Details
                                                                    (mention the name of District or Area's
                                                                    Covered):
                                                                </p>
                                                                <div class="col-lg-12">
                                                                    <div class="form-group row">
                                                                        <div class="col-md-10">
                                                                            <textarea name="TerritoryDetails"
                                                                                      id="TerritoryDetails"
                                                                                      class="form-control form-control-sm">{{ $OtherDetail->TerritoryDetails ?? '' }}</textarea>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <hr class="mt-2"
                                                                style="border-top: 3px dotted #2d0eb3;background-color: transparent;"/>
                                                            <div class="row">
                                                                <p class="fw-bold mt-2">Business Turnover
                                                                    Details:
                                                                </p>
                                                                <div class="col-lg-7">
                                                                    <div class="form-group row">
                                                                        <div class="col-md-10 table-responsive">
                                                                            <table
                                                                                    class="table table-bordered text-center">
                                                                                <tr>
                                                                                    <th>Business Turnover</th>
                                                                                    <th>Current Year <br>(in Lakh's)
                                                                                    </th>
                                                                                    <th>Previous Year <br>(in
                                                                                        Lakh's)
                                                                                    </th>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td>Vegitable Business</td>
                                                                                    <td>
                                                                                        <input type="text"
                                                                                               name="VegCurrTurnOver"
                                                                                               id="VegCurrTurnOver"
                                                                                               class="form-control form-control-sm"
                                                                                               value="{{ $OtherDetail->VegCurrTurnOver ?? '' }}">
                                                                                    </td>
                                                                                    <td>
                                                                                        <input type="text"
                                                                                               name="VegPreTurnOver"
                                                                                               id="VegPreTurnOver"
                                                                                               class="form-control form-control-sm"
                                                                                               value="{{ $OtherDetail->VegPreTurnOver ?? '' }}">
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td>Field Crop Business</td>
                                                                                    <td>
                                                                                        <input type="text"
                                                                                               name="FieldCurrTurnOver"
                                                                                               id="FieldCurrTurnOver"
                                                                                               class="form-control form-control-sm"
                                                                                               value="{{ $OtherDetail->FieldCurrTurnOver ?? '' }}">
                                                                                    </td>
                                                                                    <td>
                                                                                        <input type="text"
                                                                                               name="FieldPreTurnOver"
                                                                                               id="FieldPreTurnOver"
                                                                                               class="form-control form-control-sm"
                                                                                               value="{{ $OtherDetail->FieldPreTurnOver ?? '' }}">
                                                                                    </td>
                                                                                </tr>
                                                                            </table>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="row">
                                                                <p class="fw-bold mt-2">Incentive Plan Details:
                                                                </p>
                                                                <div class="col-lg-7">
                                                                    <div class="form-group row">
                                                                        <div class="col-md-10 table-responsive">
                                                                            <table
                                                                                    class="table table-bordered text-center">
                                                                                <thead>
                                                                                <tr>
                                                                                    <th>Incentive Payment
                                                                                        Duration
                                                                                    </th>
                                                                                    <th>Incentive Amount (in
                                                                                        Rs.)
                                                                                    </th>
                                                                                </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                <tr>
                                                                                    <td>Monthly</td>
                                                                                    <td>
                                                                                        <input type="text"
                                                                                               name="MonthlyIncentive"
                                                                                               id="MonthlyIncentive"
                                                                                               class="form-control form-control-sm"
                                                                                               value="{{ $OtherDetail->MonthlyIncentive ?? '' }}">
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td>Quarterly</td>
                                                                                    <td>
                                                                                        <input type="text"
                                                                                               name="QuarterlyIncentive"
                                                                                               id="QuarterlyIncentive"
                                                                                               class="form-control form-control-sm"
                                                                                               value="{{ $OtherDetail->QuarterlyIncentive ?? '' }}">
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td>Half Yearly</td>
                                                                                    <td>
                                                                                        <input type="text"
                                                                                               name="HalfYearlyIncentive"
                                                                                               id="HalfYearlyIncentive"
                                                                                               class="form-control form-control-sm"
                                                                                               value="{{ $OtherDetail->HalfYearlyIncentive ?? '' }}">
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td>Annually</td>
                                                                                    <td>
                                                                                        <input type="text"
                                                                                               name="AnnuallyIncentive"
                                                                                               id="AnnuallyIncentive"
                                                                                               class="form-control form-control-sm"
                                                                                               value="{{ $OtherDetail->AnnuallyIncentive ?? '' }}">
                                                                                    </td>
                                                                                </tr>
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="row">
                                                                <p class="fw-bold mt-2">Any oher details related
                                                                    to
                                                                    incentive plan:
                                                                </p>
                                                                <div class="col-lg-12">
                                                                    <div class="form-group row">
                                                                        <div class="col-md-10">
                                                                            <textarea name="AnyOtheIncentive"
                                                                                      id="AnyOtheIncentive"
                                                                                      class="form-control form-control-sm">{{ $OtherDetail->AnyOtheIncentive ?? '' }}</textarea>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <hr class="mt-2"
                                                                style="border-top: 3px dotted #2d0eb3;background-color: transparent;"/>
                                                            <div class="row">
                                                                <p class="fw-bold mt-2">Company Vehicle Policy
                                                                    (select whichever is applicable to you):
                                                                </p>
                                                                <div class="col-lg-12">
                                                                    <div class="form-group row">
                                                                        <div class="col-md-6">
                                                                            <input type="checkbox" name="TwoWheelChk"
                                                                                   id="TwoWheelChk" onclick="TwoWheel()"
                                                                                   class="form-check-input"
                                                                                   @php
                                                                                       if ($OtherDetail != null) {
                                                                                               if ($OtherDetail->TwoWheelChk == 1) {
                                                                                                   echo 'checked';
                                                                                               }
                                                                                           }
                                                                                   @endphp value="0">
                                                                            <label class="form-check-label"
                                                                                   for="TwoWheelChk">2 Wheeler</label>
                                                                            <table
                                                                                    class="table table-bordered {{ $OtherDetail != null && $OtherDetail->TwoWheelChk == 1 ? '' : 'd-none' }}"
                                                                                    id="twowheel">
                                                                                <tr>
                                                                                    <td>Ownership Type</td>
                                                                                    <td>
                                                                                        <div
                                                                                                class="form-check form-check-inline">
                                                                                            <input
                                                                                                    class="form-check-input"
                                                                                                    type="radio"
                                                                                                    id="TwoWheelOwnVehicle"
                                                                                                    value="W"
                                                                                                    name="TwoWheelOwnerType"
                                                                                                    @php
                                                                                                        if ($OtherDetail != null) {
                                                                                                                if ($OtherDetail->TwoWheelOwnerType == 'W') {
                                                                                                                    echo 'checked';
                                                                                                                }
                                                                                                            }

                                                                                                    @endphp>
                                                                                            <label
                                                                                                    class="form-check-label"
                                                                                                    for="TwoWheelOwnVehicle">Own
                                                                                                Vehicle</label>
                                                                                        </div>
                                                                                        <div
                                                                                                class="form-check form-check-inline">
                                                                                            <input
                                                                                                    class="form-check-input"
                                                                                                    type="radio"
                                                                                                    id="TwoWheelCompanyVehicle"
                                                                                                    value="C"
                                                                                                    name="TwoWheelOwnerType"
                                                                                                    @php
                                                                                                        if ($OtherDetail != null) {
                                                                                                                if ($OtherDetail->TwoWheelOwnerType == 'C') {
                                                                                                                    echo 'checked';
                                                                                                                }
                                                                                                            }

                                                                                                    @endphp>
                                                                                            <label
                                                                                                    class="form-check-label"
                                                                                                    for="TwoWheelCompanyVehicle">Provided
                                                                                                by Company</label>
                                                                                        </div>
                                                                                        Rs. <input type="text"
                                                                                                   name="TwoWheelAmount"
                                                                                                   id="TwoWheelAmount"
                                                                                                   class="d-inline form-control form-control-sm"
                                                                                                   style="width: 100px;"
                                                                                                   value="{{ $OtherDetail->TwoWheelAmount ?? '' }}">
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td>Petrol Allowances</td>
                                                                                    <td>
                                                                                        Rs. <input type="text"
                                                                                                   name="TwoWheelPetrol"
                                                                                                   id="TwoWheelPetrol"
                                                                                                   class="d-inline form-control form-control-sm"
                                                                                                   style="width: 100px;"
                                                                                                   value="{{ $OtherDetail->TwoWheelPetrol ?? '' }}">
                                                                                        <div
                                                                                                class="form-check form-check-inline">
                                                                                            <input
                                                                                                    class="form-check-input"
                                                                                                    type="radio"
                                                                                                    id="perKm"
                                                                                                    value="Per KM"
                                                                                                    name="TwoWheelPetrolTerm"
                                                                                                    @php
                                                                                                        if ($OtherDetail != null) {
                                                                                                                if ($OtherDetail->TwoWheelPetrolTerm == 'Per KM') {
                                                                                                                    echo 'checked';
                                                                                                                }
                                                                                                            }

                                                                                                    @endphp>
                                                                                            <label
                                                                                                    class="form-check-label"
                                                                                                    for="perKm">Per
                                                                                                KM</label>
                                                                                        </div>
                                                                                        <div
                                                                                                class="form-check form-check-inline">
                                                                                            <input
                                                                                                    class="form-check-input"
                                                                                                    type="radio"
                                                                                                    id="TwoWheelperMonth"
                                                                                                    value="Per Month"
                                                                                                    name="TwoWheelPetrolTerm"
                                                                                                    @php
                                                                                                        if ($OtherDetail != null) {
                                                                                                                if ($OtherDetail->TwoWheelPetrolTerm == 'Per Month') {
                                                                                                                    echo 'checked';
                                                                                                                }
                                                                                                            }
                                                                                                    @endphp>

                                                                                            <label
                                                                                                    class="form-check-label"
                                                                                                    for="TwoWheelperMonth">Per
                                                                                                Month</label>
                                                                                        </div>
                                                                                    </td>
                                                                                </tr>

                                                                            </table>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="col-lg-12">
                                                                    <div class="form-group row">
                                                                        <div class="col-md-6">
                                                                            <input type="checkbox" name="FourWheelChk"
                                                                                   id="FourWheelChk"
                                                                                   onclick="FourWheel()"
                                                                                   class="form-check-input"
                                                                                    @php
                                                                                        if ($OtherDetail != null) {
                                                                                                if ($OtherDetail->FourWheelChk == 1) {
                                                                                                    echo 'checked';
                                                                                                }
                                                                                            }
                                                                                    @endphp> <label
                                                                                    class="form-check-label"
                                                                                    for="FourWheelChk">4 Wheeler</label>
                                                                            <table
                                                                                    class="table table-bordered {{ $OtherDetail != null && $OtherDetail->FourWheelChk == 1 ? '' : 'd-none' }}"
                                                                                    id="fourwheel">
                                                                                <tr>
                                                                                    <td>Ownership Type</td>
                                                                                    <td>
                                                                                        <div
                                                                                                class="form-check form-check-inline">
                                                                                            <input
                                                                                                    class="form-check-input"
                                                                                                    type="radio"
                                                                                                    id="FourWheelOwnVehicle"
                                                                                                    value="W"
                                                                                                    name="FourWheelOwnerType"
                                                                                                    @php
                                                                                                        if ($OtherDetail != null) {
                                                                                                                if ($OtherDetail->FourWheelOwnerType == 'W') {
                                                                                                                    echo 'checked';
                                                                                                                }
                                                                                                            }
                                                                                                    @endphp>

                                                                                            <label
                                                                                                    class="form-check-label"
                                                                                                    for="FourWheelOwnVehicle">Own
                                                                                                Vehicle</label>
                                                                                        </div>
                                                                                        <div
                                                                                                class="form-check form-check-inline">
                                                                                            <input
                                                                                                    class="form-check-input"
                                                                                                    type="radio"
                                                                                                    id="FourWheelCompanyVehicle"
                                                                                                    value="C"
                                                                                                    name="FourWheelOwnerType"
                                                                                                    @php
                                                                                                        if ($OtherDetail != null) {
                                                                                                                if ($OtherDetail->FourWheelOwnerType == 'C') {
                                                                                                                    echo 'checked';
                                                                                                                }
                                                                                                            }
                                                                                                    @endphp>
                                                                                            <label
                                                                                                    class="form-check-label"
                                                                                                    for="FourWheelCompanyVehicle">Provided
                                                                                                by Company</label>
                                                                                        </div>
                                                                                        Rs. <input type="text"
                                                                                                   name="FourWheelAmount"
                                                                                                   id="FourWheelAmount"
                                                                                                   class="d-inline form-control form-control-sm"
                                                                                                   style="width: 100px;"
                                                                                                   value="{{ $OtherDetail->FourWheelAmount ?? '' }}">
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td>Petrol Allowances</td>
                                                                                    <td>
                                                                                        Rs. <input type="text"
                                                                                                   name="FourWheelPetrol"
                                                                                                   id="FourWheelPetrol"
                                                                                                   class="d-inline form-control form-control-sm"
                                                                                                   style="width: 100px;"
                                                                                                   value="{{ $OtherDetail->FourWheelPetrol ?? '' }}">
                                                                                        <div
                                                                                                class="form-check form-check-inline">
                                                                                            <input
                                                                                                    class="form-check-input"
                                                                                                    type="radio"
                                                                                                    id="FourWheelperKm"
                                                                                                    value="Per KM"
                                                                                                    name="FourWheelPetrolTerm"
                                                                                                    @php
                                                                                                        if ($OtherDetail != null) {
                                                                                                                if ($OtherDetail->FourWheelPetrolTerm == 'Per KM') {
                                                                                                                    echo 'checked';
                                                                                                                }
                                                                                                            }
                                                                                                    @endphp>

                                                                                            <label
                                                                                                    class="form-check-label"
                                                                                                    for="FourWheelperKm">Per
                                                                                                KM</label>
                                                                                        </div>
                                                                                        <div
                                                                                                class="form-check form-check-inline">
                                                                                            <input
                                                                                                    class="form-check-input"
                                                                                                    type="radio"
                                                                                                    id="FourWheelperMonth"
                                                                                                    value="Per Month"
                                                                                                    name="FourWheelPetrolTerm"
                                                                                                    @php
                                                                                                        if ($OtherDetail != null) {
                                                                                                                if ($OtherDetail->FourWheelPetrolTerm == 'Per Month') {
                                                                                                                    echo 'checked';
                                                                                                                }
                                                                                                            }
                                                                                                    @endphp>

                                                                                            <label
                                                                                                    class="form-check-label"
                                                                                                    for="FourWheelperMonth">Per
                                                                                                Month</label>
                                                                                        </div>
                                                                                    </td>
                                                                                </tr>

                                                                            </table>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <hr class="mt-2"
                                                                style="border-top: 3px dotted #2d0eb3;background-color: transparent;"/>
                                                            <div class="row">
                                                                <div class="col-lg-12">
                                                                    <p class="fw-bold mt-3">Other Benefit details
                                                                        (select whichever is applicable and mention
                                                                        the
                                                                        details)
                                                                    </p>
                                                                    <div class="row">
                                                                        <div class="col-lg-12">
                                                                            <div class="form-group row">
                                                                                <label class="col-form-label col-md-2">
                                                                                    <input type="checkbox" name="DAChk"
                                                                                           id="DAChk"
                                                                                           class="otherbenefit"
                                                                                           data-payload="DAHeadquarter">
                                                                                    DA
                                                                                    @ Headquarter
                                                                                </label>
                                                                                <div class="col-md-3">
                                                                                    <input type="text"
                                                                                           name="DAHeadquarter"
                                                                                           id="DAHeadquarter"
                                                                                           class="form-control form-control-sm"
                                                                                           value="{{ $Rec->DAHq }}"
                                                                                           readonly>
                                                                                </div>

                                                                                <label class="col-form-label col-md-2">
                                                                                    <input type="checkbox"
                                                                                           name="DAOutChk" id="DAOutChk"
                                                                                           class="otherbenefit"
                                                                                           data-payload="DAOutsideHeadquarter">
                                                                                    DA
                                                                                    outside Headquarter</label>
                                                                                <div class="col-md-3">
                                                                                    <input type="text"
                                                                                           name="DAOutsideHeadquarter"
                                                                                           id="DAOutsideHeadquarter"
                                                                                           class="form-control form-control-sm"
                                                                                           value="{{ $Rec->DAOutHq }}"
                                                                                           readonly>
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group row">
                                                                                <label class="col-form-label col-md-2">
                                                                                    <input type="checkbox"
                                                                                           name="HotelElgChk"
                                                                                           id="HotelElgChk"
                                                                                           class="otherbenefit"
                                                                                           data-payload="HotelEligibility">
                                                                                    Lodging
                                                                                    / Hotel Eligibility</label>
                                                                                <div class="col-md-3">
                                                                                    <input type="text"
                                                                                           name="HotelEligibility"
                                                                                           id="HotelEligibility"
                                                                                           class="form-control form-control-sm"
                                                                                           value="{{ $Rec->HotelElg }}"
                                                                                           readonly>
                                                                                </div>

                                                                                <label class="col-form-label col-md-2">
                                                                                    <input type="checkbox"
                                                                                           name="PetrolChk"
                                                                                           id="PetrolChk"
                                                                                           class="otherbenefit"
                                                                                           data-payload="PetrolAllowances">
                                                                                    Petrol
                                                                                    Allowances</label>
                                                                                <div class="col-md-3">
                                                                                    <input type="text"
                                                                                           name="PetrolAllowances"
                                                                                           id="PetrolAllowances"
                                                                                           class="form-control form-control-sm"
                                                                                           value="{{ $Rec->PetrolAlw }}"
                                                                                           readonly>
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group row">
                                                                                {{-- <label class="col-form-label col-md-2">
                                                                                        <input type="checkbox"
                                                                                            name="PhoneChk" id="PhoneChk"
                                                                                            class="otherbenefit"
                                                                                            data-payload="PhoneAllowances">
                                                                                        Phone
                                                                                        Allowances</label>
                                                                                    <div class="col-md-3">
                                                                                        <input type="text"
                                                                                            name="PhoneAllowances"
                                                                                            id="PhoneAllowances"
                                                                                            class="form-control form-control-sm"
                                                                                            value="{{ $Rec->PhoneAlw }}"
                                                                                            readonly>
                                                                                    </div> --}}

                                                                                <label class="col-form-label col-md-2">
                                                                                    <input type="checkbox"
                                                                                           name="MedicalChk"
                                                                                           id="MedicalChk"
                                                                                           class="otherbenefit"
                                                                                           data-payload="Medical">
                                                                                    Medical
                                                                                    Insurance</label>
                                                                                <div class="col-md-3">
                                                                                    <input type="text" name="Medical"
                                                                                           id="Medical"
                                                                                           class="form-control form-control-sm"
                                                                                           value="{{ $Rec->Medical }}"
                                                                                           readonly>
                                                                                </div>
                                                                                <label class="col-form-label col-md-2">
                                                                                    <input type="checkbox"
                                                                                           name="GrpTermChk"
                                                                                           id="GrpTermChk"
                                                                                           class="otherbenefit"
                                                                                           data-payload="GrpTermIns">
                                                                                    Group
                                                                                    Term Insurance:</label>
                                                                                <div class="col-md-3">
                                                                                    <input type="text" name="GrpTermIns"
                                                                                           id="GrpTermIns"
                                                                                           class="form-control form-control-sm"
                                                                                           value="{{ $Rec->GrpTermIns }}"
                                                                                           readonly>
                                                                                </div>
                                                                            </div>

                                                                            <div class="form-group row">

                                                                                <label class="col-form-label col-md-2">
                                                                                    <input type="checkbox"
                                                                                           name="GrpAccChk"
                                                                                           id="GrpAccChk"
                                                                                           class="otherbenefit"
                                                                                           data-payload="GrpPersonalAccIns">
                                                                                    Group
                                                                                    Personal Accident <br>
                                                                                    Insurance</label>
                                                                                <div class="col-md-3">
                                                                                    <input type="text"
                                                                                           name="GrpPersonalAccIns"
                                                                                           id="GrpPersonalAccIns"
                                                                                           class="form-control form-control-sm"
                                                                                           value="{{ $Rec->GrpPersonalAccIns }}"
                                                                                           readonly>
                                                                                </div>
                                                                                <label class="col-form-label col-md-2">
                                                                                    <input type="checkbox"
                                                                                           name="TravelElgChk"
                                                                                           id="TravelElgChk"
                                                                                           class="otherbenefit"
                                                                                           data-payload="TravelElg">
                                                                                    Travel
                                                                                    Eligibilities:</label>
                                                                                <div class="col-md-3">
                                                                                    <select name="TravelElg"
                                                                                            id="TravelElg"
                                                                                            class="form-select form-select-sm"
                                                                                            readonly>
                                                                                        <option value="" selected>
                                                                                        </option>
                                                                                        <option value="Sleeper">
                                                                                            Sleeper
                                                                                        </option>
                                                                                        <option value="3 AC">3 AC
                                                                                        </option>
                                                                                        <option value="2 AC">2 AC
                                                                                        </option>
                                                                                        <option value="Flight Economy">
                                                                                            Flight Economy
                                                                                        </option>
                                                                                    </select>
                                                                                    <script>
                                                                                        $('#TravelElg').val('{{ $Rec->TravelElg }}');
                                                                                    </script>
                                                                                </div>

                                                                            </div>
                                                                            <div class="form-group row">
                                                                                <label class="col-form-label col-md-2">
                                                                                    <input type="checkbox"
                                                                                           name="MobileChk"
                                                                                           id="MobileChk"
                                                                                           class="otherbenefit"
                                                                                           data-payload="MobileHandset">
                                                                                    Mobile
                                                                                    Handset:</label>
                                                                                <div class="col-md-3">
                                                                                    <input type="text"
                                                                                           name="MobileHandset"
                                                                                           id="MobileHandset"
                                                                                           class="form-control form-control-sm"
                                                                                           value="{{ $Rec->MobileHandset }}"
                                                                                           readonly>
                                                                                </div>
                                                                                <label class="col-form-label col-md-2">
                                                                                    <input type="checkbox"
                                                                                           name="MobileBillChk"
                                                                                           id="MobileBillChk"
                                                                                           class="otherbenefit"
                                                                                           data-payload="MobileBill">
                                                                                    Mobile
                                                                                    Bill reimbursement</label>
                                                                                <div class="col-md-3">
                                                                                    <input type="text" name="MobileBill"
                                                                                           id="MobileBill"
                                                                                           class="form-control form-control-sm"
                                                                                           value="{{ $Rec->MobileBill }}"
                                                                                           readonly>
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group row">

                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="row">
                                                                <p class="fw-bold mt-2">Any oher benefits:
                                                                </p>
                                                                <div class="col-lg-12">
                                                                    <div class="form-group row">
                                                                        <div class="col-md-10">
                                                                            <textarea name="OtherBenifit"
                                                                                      id="OtherBenifit"
                                                                                      class="form-control form-control-sm">{{ $OtherDetail->OtherBenifit ?? '' }}</textarea>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <hr class="mt-2"
                                                                style="border-top: 3px dotted #2d0eb3;background-color: transparent;"/>
                                                            <div class="row">
                                                                <div class="col-lg-12">

                                                                    <div class="form-group row">
                                                                        <p class="fw-bold mt-3">Total Experience:
                                                                        </p>
                                                                        <div class="col-md-1">
                                                                            <input type="text" name="ToatalExpYears"
                                                                                   id="ToatalExpYears"
                                                                                   class="form-control form-control-sm"
                                                                                   value="{{ $Rec->TotalYear }}">
                                                                        </div>
                                                                        <label class="col-form-label col-md-1"
                                                                               style="width: 50px;">Years</label>
                                                                        <div class="col-md-1">
                                                                            <input type="text" name="TotalExpMonth"
                                                                                   id="TotalExpMonth"
                                                                                   class="form-control form-control-sm"
                                                                                   value="{{ $Rec->TotalMonth }}">
                                                                        </div>
                                                                        <label
                                                                                class="col-form-label col-md-1">Months</label>

                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <hr class="mt-2"
                                                                style="border-top: 3px dotted #2d0eb3;background-color: transparent;"/>
                                                            <div class="row">
                                                                <p class="fw-bold mt-3">Previous Employement
                                                                    Records
                                                                    (Except
                                                                    the present)
                                                                </p>
                                                                <div class="col-lg-12 table-responsive">
                                                                    <table class="table table-bordered">
                                                                        <thead class="text-center">
                                                                        <tr>
                                                                            <td>Company</td>
                                                                            <td>Designation</td>
                                                                            <td>Gross Monthly Salary</td>
                                                                            <td>Anual CTC</td>
                                                                            <td>Job Start Date</td>
                                                                            <td>Job End Date</td>
                                                                            <td>Reason for Leaving</td>
                                                                            <td></td>
                                                                        </tr>
                                                                        </thead>
                                                                        <tbody id="WorkExpData">
                                                                        <tr>
                                                                            <td>
                                                                                <input type="text"
                                                                                       name="WorkExpCompany[]"
                                                                                       id="WorkExpCompany1"
                                                                                       class="form-control form-control-sm">
                                                                            </td>
                                                                            <td>
                                                                                <input type="text"
                                                                                       name="WorkExpDesignation[]"
                                                                                       id="WorkExpDesignation1"
                                                                                       class="form-control form-control-sm">
                                                                            </td>
                                                                            <td>
                                                                                <input type="text"
                                                                                       name="WorkExpGrossMonthlySalary[]"
                                                                                       id="WorkExpGrossMonthlySalary1"
                                                                                       class="form-control form-control-sm">
                                                                            </td>
                                                                            <td>
                                                                                <input type="text"
                                                                                       name="WorkExpAnualCTC[]"
                                                                                       id="WorkExpAnualCTC1"
                                                                                       class="form-control form-control-sm">
                                                                            </td>
                                                                            <td>
                                                                                <input type="date"
                                                                                       name="WorkExpJobStartDate[]"
                                                                                       id="WorkExpJobStartDate1"
                                                                                       class="form-control form-control-sm datepicker">
                                                                            </td>
                                                                            <td>
                                                                                <input type="date"
                                                                                       name="WorkExpJobEndDate[]"
                                                                                       id="WorkExpJobEndDate1"
                                                                                       class="form-control form-control-sm datepicker">
                                                                            </td>
                                                                            <td>
                                                                                <input type="text"
                                                                                       name="WorkExpReasonForLeaving[]"
                                                                                       id="WorkExpReasonForLeaving1"
                                                                                       class="form-control form-control-sm">
                                                                            </td>
                                                                            <td>

                                                                            </td>
                                                                        </tr>
                                                                        </tbody>
                                                                    </table>
                                                                    <input type="button" value="Add Experience"
                                                                           id="addExperience"
                                                                           class="btn btn-warning btn-sm">
                                                                </div>
                                                            </div>
                                                            <hr class="mt-2"
                                                                style="border-top: 3px dotted #2d0eb3;background-color: transparent;"/>
                                                        </div>

                                                        <div class="row" id="training_div">
                                                            <p class="fw-bold mt-3">Training & Practical
                                                                Experience
                                                                (Other than
                                                                regular jobs)</p>

                                                            <div class=" col-lg-12 table-responsive mt-3">
                                                                <table class="table table-bordered">
                                                                    <thead class="text-center">
                                                                    <tr>
                                                                        <td>Nature of Training</td>
                                                                        <td>Organization / Institution</td>
                                                                        <td>From Date</td>
                                                                        <td>To Date</td>
                                                                        <td></td>
                                                                    </tr>
                                                                    </thead>
                                                                    <tbody id="TrainingData">
                                                                    <tr>
                                                                        <td>
                                                                            <input type="text"
                                                                                   name="TrainingNature[]"
                                                                                   id="TrainingNature1"
                                                                                   class="form-control form-control-sm">
                                                                        </td>
                                                                        <td>
                                                                            <input type="text"
                                                                                   name="TrainingOrganization[]"
                                                                                   id="TrainingOrganization1"
                                                                                   class="form-control form-control-sm">
                                                                        </td>
                                                                        <td>
                                                                            <input type="date"
                                                                                   name="TrainingFromDate[]"
                                                                                   id="TrainingFromDate1"
                                                                                   class="form-control form-control-sm datepicker">
                                                                        </td>
                                                                        <td>
                                                                            <input type="date"
                                                                                   name="TrainingToDate[]"
                                                                                   id="TrainingToDate1"
                                                                                   class="form-control form-control-sm datepicker">
                                                                        </td>
                                                                        <td></td>
                                                                    </tr>
                                                                    </tbody>
                                                                </table>
                                                                <input type="button" value="Add Training"
                                                                       id="addTraining" class="btn btn-warning btn-sm">
                                                            </div>


                                                        </div>

                                                        <div class="submit-section text-center">
                                                            <button class="btn btn-primary submit-btn">Save Details
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                        <div id="about" class="tab-pane" role="tabpanel" aria-labelledby="step-2"
                                             style="position: static; left: auto; width: 1017px; display: none;">
                                            <form action="{{ route('SaveAbout') }}" id="about_form" method="POST">
                                                <div class="col-lg-12">
                                                    <h6>Q1. What is your aim in life? </h6>
                                                    <div class="form-group row mb-2">
                                                        <div class="col-md-12">
                                                            <input type="text" name="AboutAim" id="AboutAim"
                                                                   class="form-control form-control-sm reqinp_abt"
                                                                   value="{{ $AboutAns->AboutAim ?? '' }}">
                                                        </div>
                                                    </div>
                                                    <h6>Q2. What are you hobbies and interest? </h6>
                                                    <div class="form-group row mb-2">
                                                        <div class="col-md-12">

                                                            <input type="text" name="AboutHobbi" id="AboutHobbi"
                                                                   class="form-control form-control-sm reqinp_abt"
                                                                   value="{{ $AboutAns->AboutHobbi ?? '' }}">
                                                        </div>
                                                    </div>
                                                    <h6>Q3. Where do you see yourself 5 Years from now? </h6>
                                                    <div class="form-group row mb-2">
                                                        <div class="col-md-12">

                                                            <input type="text" name="About5Year" id="About5Year"
                                                                   class="form-control form-control-sm reqinp_abt"
                                                                   value="{{ $AboutAns->About5Year ?? '' }}">
                                                        </div>
                                                    </div>
                                                    <h6>Q4. What are your greatest personal assets (qualities,
                                                        skills,
                                                        abilities) which make you successful
                                                        in the jobs you take up? </h6>
                                                    <div class="form-group row mb-2">
                                                        <div class="col-md-12">

                                                            <input type="text" name="AboutAssets" id="AboutAssets"
                                                                   class="form-control form-control-sm reqinp_abt"
                                                                   value="{{ $AboutAns->AboutAssets ?? '' }}">
                                                        </div>
                                                    </div>
                                                    <h6>Q5. What are your areas where you think you need to improve
                                                        yourself? </h6>
                                                    <div class="form-group row mb-2">
                                                        <div class="col-md-12">

                                                            <input type="text" name="AboutImprovement"
                                                                   id="AboutImprovement"
                                                                   class="form-control form-control-sm reqinp_abt"
                                                                   value="{{ $AboutAns->AboutImprovement ?? '' }}">
                                                        </div>
                                                    </div>
                                                    <h6>Q6. What are your Strengths? </h6>
                                                    <div class="form-group row mb-2">
                                                        <div class="col-md-12">

                                                            <input type="text" name="AboutStrength" id="AboutStrength"
                                                                   class="form-control form-control-sm reqinp_abt"
                                                                   value="{{ $AboutAns->AboutStrength ?? '' }}">
                                                        </div>
                                                    </div>


                                                    <h6>Q7. In the past or at present, have/are you suffered
                                                        /suffering
                                                        from, any form of physical disability
                                                        or any minor or major illness or deficiency? </h6>
                                                    <div class="form-group row mb-2">
                                                        <div class="col-md-12">

                                                            <input type="text" name="AboutDeficiency"
                                                                   id="AboutDeficiency"
                                                                   class="form-control form-control-sm reqinp_abt"
                                                                   value="{{ $AboutAns->AboutDeficiency ?? '' }}">
                                                        </div>
                                                    </div>
                                                    <h6>Q8. Have you Been criminally prosecuted? if so, give details
                                                        separately. </h6>
                                                    <div style="text-align: left">
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input crime" type="radio"
                                                                   name="CriminalChk" id="YesCriminal" value="Y"
                                                                   data-value="Y" @php
                                                                if ($AboutAns != null) {
                                                                        if ($AboutAns->CriminalChk == 'Y') {
                                                                            echo 'checked';
                                                                        }
                                                                    }
                                                            @endphp>
                                                            <label class="form-check-label"
                                                                   for="YesCriminal">Yes</label>
                                                        </div>
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input crime" type="radio"
                                                                   name="CriminalChk" id="NoCriminal" value="N"
                                                                   data-value="N" @php
                                                                if ($AboutAns != null) {
                                                                        if ($AboutAns->CriminalChk == 'N') {
                                                                            echo 'checked';
                                                                        }
                                                                    }
                                                            @endphp>
                                                            <label class="form-check-label" for="NoCriminal">
                                                                No</label>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row mb-2

                                                            @php
                                                                if($AboutAns != null){
                                                                    if($AboutAns->CriminalChk == 'Y'){
                                                                        echo '';
                                                                    }else{
                                                                        echo 'd-none';
                                                                    }
                                                                }
                                                            @endphp "
                                                         id="crime_div">
                                                        <div class="col-md-12">
                                                            <input type="text" name="AboutCriminal" id="AboutCriminal"
                                                                   class="form-control form-control-sm"
                                                                   value="{{ $AboutAns->AboutCriminal ?? '' }}">
                                                        </div>
                                                    </div>

                                                    <h6>Q9. Do You have a valid driving licence? </h6>
                                                    <div style="text-align: left">
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input dlchk" type="radio"
                                                                   name="LicenseChk" id="YesLicense" value="Y"
                                                                   data-value="Y" @php
                                                                if ($AboutAns != null) {
                                                                        if ($AboutAns->LicenseChk == 'Y') {
                                                                            echo 'checked';
                                                                        }
                                                                    }
                                                            @endphp>
                                                            <label class="form-check-label"
                                                                   for="YesLicense">Yes</label>
                                                        </div>
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input dlchk" type="radio"
                                                                   name="LicenseChk" id="NoLicense" value="N"
                                                                   data-value="N" @php
                                                                if ($AboutAns != null) {
                                                                        if ($AboutAns->LicenseChk == 'N') {
                                                                            echo 'checked';
                                                                        }
                                                                    }
                                                            @endphp>
                                                            <label class="form-check-label" for="NoLicense">
                                                                No</label>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row mb-2">
                                                        <div class="form-group row
                                                            @php
                                                                if($AboutAns != null){
                                                                    if($AboutAns->LicenseChk == 'Y'){
                                                                        echo '';
                                                                    }else{
                                                                        echo 'd-none';
                                                                    }
                                                                }
                                                            @endphp "
                                                             id="dl_div">
                                                            <label class="col-form-label col-md-1">License
                                                                No:</label>
                                                            <div class="col-md-2 col-sm-12">
                                                                <input type="text" class="form-control form-control-sm"
                                                                       id="DLNo" name="DLNo"
                                                                       value="{{ $AboutAns->DLNo ?? '' }}">
                                                            </div>
                                                            <label class="col-form-label col-md-1">Validity:</label>
                                                            <div class="col-md-2 col-sm-12">
                                                                <input type="date" class="form-control form-control-sm"
                                                                       name="LValidity" id="LValidity"
                                                                       value="{{ $AboutAns->LValidity ?? '' }}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="submit-section text-center">
                                                    <button class="btn btn-primary submit-btn">Save Details
                                                    </button>
                                                </div>
                                            </form>
                                        </div>

                                        <div id="other" class="tab-pane" role="tabpanel" aria-labelledby="step-2"
                                             style="position: static; left: auto; width: 1017px; display: none;">
                                            <form action="{{ route('SaveOther') }}" method="POST" id="OtherForm">
                                                @if ($Rec->Professional == 'P')
                                                    <div class="row">
                                                        <div class="col-lg-12">
                                                            <p class="fw-bold mb-3">Please give reference who had
                                                                worked
                                                                with you
                                                                in the previous organization: </p>
                                                            <div class="table-responsive">
                                                                <table class="table table-bordered">
                                                                    <thead class="text-center">
                                                                    <tr>
                                                                        <td>Name</td>
                                                                        <td>Name of Company</td>
                                                                        <td>Designation</td>
                                                                        <td>Email Id</td>
                                                                        <td>Contact No</td>
                                                                        <td></td>
                                                                    </tr>
                                                                    </thead>
                                                                    <tbody id="PreOrgRefData">
                                                                    <tr>
                                                                        <td>
                                                                            <input type="text" name="PreOrgName[]"
                                                                                   id="PreOrgName1"
                                                                                   class="form-control form-control-sm reqinp_other">
                                                                        </td>
                                                                        <td>
                                                                            <input type="text"
                                                                                   name="PreOrgCompany[]"
                                                                                   id="PreOrgCompany1"
                                                                                   class="form-control form-control-sm reqinp_other">
                                                                        </td>
                                                                        <td>
                                                                            <input type="text"
                                                                                   name="PreOrgDesignation[]"
                                                                                   id="PreOrgDesignation1"
                                                                                   class="form-control form-control-sm reqinp_other">
                                                                        </td>
                                                                        <td>
                                                                            <input type="text" name="PreOrgEmail[]"
                                                                                   id="PreOrgEmail1"
                                                                                   class="form-control form-control-sm reqinp_other">
                                                                        </td>
                                                                        <td>
                                                                            <input type="text"
                                                                                   name="PreOrgContact[]"
                                                                                   id="PreOrgContact1"
                                                                                   class="form-control form-control-sm reqinp_other">
                                                                        </td>

                                                                        <td>
                                                                            <div class="d-flex order-actions"><a
                                                                                        href="javascript:;"
                                                                                        class="ms-3"
                                                                                        id="removePreOrgRef"><i
                                                                                            class="bx bxs-trash text-danger"></i></a>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                    </tbody>
                                                                </table>
                                                                <input type="button" value="Add" id="addPreOrgRef"
                                                                       class="btn btn-warning btn-sm">
                                                            </div>
                                                        </div>
                                                        <hr class="mt-2"
                                                            style="border-top: 3px dotted #2d0eb3;background-color: transparent;"/>
                                                    </div>
                                                @endif

                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <p class="fw-bold mt-3">Do you have any acquaintances or
                                                            relatives working with VNR Group Companies?</p>
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="radio" name="AcqChk"
                                                                   id="AcqYes" value="Y" onclick="showAcqOrNot('Y')"
                                                                    {{ $Rec->VNR_Acq == 'Y' ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="AcqYes">Yes</label>
                                                        </div>
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="radio" name="AcqChk"
                                                                   id="AcqNo" value="N"
                                                                   {{ $Rec->VNR_Acq == 'N' ? 'checked' : '' }}
                                                                   onclick="showAcqOrNot('N')">
                                                            <label class="form-check-label" for="AcqNo"> No</label>
                                                        </div>
                                                        <div class="table-responsive {{ $Rec->VNR_Acq == 'N' ? 'd-none' : '' }}"
                                                             id="AcqDiv">
                                                            <table class="table table-bordered">
                                                                <thead class="text-center">
                                                                <tr>
                                                                    <td>Name</td>
                                                                    <td>Mobile No</td>
                                                                    <td>Email</td>
                                                                    <td>VNR Group <br>Company Name</td>
                                                                    <td>Designation</td>
                                                                    <td>Location</td>
                                                                    <td>Your Relationship <br>with person mentioned
                                                                    </td>
                                                                    <td></td>
                                                                </tr>
                                                                </thead>
                                                                <tbody id="VNRRefData">
                                                                <tr>
                                                                    <td>
                                                                        <input type="text" name="VnrRefName[]"
                                                                               id="VnrRefName1"
                                                                               class="form-control form-control-sm">
                                                                    </td>
                                                                    <td>
                                                                        <input type="text" name="VnrRefContact[]"
                                                                               id="VnrRefContact1"
                                                                               class="form-control form-control-sm">
                                                                    </td>

                                                                    <td>
                                                                        <input type="text" name="VnrRefEmail[]"
                                                                               id="VnrRefEmail1"
                                                                               class="form-control form-control-sm">
                                                                    </td>
                                                                    <td>
                                                                        <select name="VnrRefCompany[]"
                                                                                id="VnrRefCompany1"
                                                                                class="form-select form-select-sm"
                                                                                onchange="GetOtherCompany(1);">
                                                                            <option value="">Select</option>
                                                                            <option value="VNR Seeds Pvt. Ltd.">VNR
                                                                                Seeds Pvt. Ltd.
                                                                            </option>
                                                                            <option value="VNR Nursery Pvt. Ltd.">
                                                                                VNR Nursery Pvt. Ltd.
                                                                            </option>
                                                                            <option value="Other">Other</option>
                                                                        </select>
                                                                        <br>
                                                                        <input type="text" name="OtherCompany[]"
                                                                               id="OtherCompany1"
                                                                               class="d-none form-control form-control-sm"
                                                                               placeholder="Other Company Name">
                                                                    </td>
                                                                    <td>
                                                                        <input type="text"
                                                                               name="VnrRefDesignation[]"
                                                                               id="VnrRefDesignation1"
                                                                               class="form-control form-control-sm">
                                                                    </td>
                                                                    <td>
                                                                        <input type="text" name="VnrRefLocation[]"
                                                                               id="VnrRefLocation1"
                                                                               class="form-control form-control-sm">
                                                                    </td>
                                                                    <td>
                                                                        <input type="text"
                                                                               name="VnrRefRelWithPerson[]"
                                                                               id="VnrRefRelWithPerson1"
                                                                               class="form-control form-control-sm">
                                                                    </td>
                                                                    <td>
                                                                        <div class="d-flex order-actions"><a
                                                                                    href="javascript:;"
                                                                                    class="ms-3"
                                                                                    id="removeVnrRef"><i
                                                                                        class="bx bxs-trash text-danger"></i></a>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                                </tbody>
                                                            </table>
                                                            <input type="button" value="Add" id="addVnrRef"
                                                                   class="btn btn-warning btn-sm">

                                                        </div>
                                                        <hr class="mt-2"
                                                            style="border-top: 3px dotted #2d0eb3;background-color: transparent;"/>
                                                    </div>

                                                    <div class="col-lg-12">
                                                        <p class="fw-bold mt-3">Do you have any acquaintances or
                                                            relatives associated with VNR as business associates (like
                                                            Dealer, Distributor, Retailer, Organizer, Vendor etc.)?</p>
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="radio"
                                                                   name="AcqChkBusiness" id="Acq_Business_Yes" value="Y"
                                                                   onclick="showAcqBusiness('Y')"
                                                                    {{ $Rec->VNR_Acq_Business == 'Y' ? 'checked' : '' }}>
                                                            <label class="form-check-label"
                                                                   for="Acq_Business_Yes">Yes</label>
                                                        </div>
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="radio"
                                                                   name="AcqChkBusiness" id="Acq_Business_No" value="N"
                                                                   {{ $Rec->VNR_Acq_Business == 'N' ? 'checked' : '' }}
                                                                   onclick="showAcqBusiness('N')">
                                                            <label class="form-check-label" for="Acq_Business_No">
                                                                No</label>
                                                        </div>
                                                        <div class="table-responsive {{ $Rec->VNR_Acq_Business == 'N' ? 'd-none' : '' }}"
                                                             id="AcqBusinessDiv">
                                                            <table class="table table-bordered">
                                                                <thead class="text-center">
                                                                <tr>
                                                                    <td>Name</td>
                                                                    <td>Mobile No</td>
                                                                    <td>Email</td>
                                                                    <td>Business relation with <br>VNR</td>
                                                                    <td>Location of Business / <br>acquaintances
                                                                    </td>

                                                                    <td>Your Relationship <br>with person mentioned
                                                                    </td>
                                                                    <td></td>
                                                                </tr>
                                                                </thead>
                                                                <tbody id="VNR_Business_AcqData">
                                                                <tr>
                                                                    <td>
                                                                        <input type="text"
                                                                               name="VnrRefBusiness_Name[]"
                                                                               id="VnrRefBusiness_Name1"
                                                                               class="form-control form-control-sm">
                                                                    </td>
                                                                    <td>
                                                                        <input type="text"
                                                                               name="VnrRefBusiness_Contact[]"
                                                                               id="VnrRefBusiness_Contact1"
                                                                               class="form-control form-control-sm">
                                                                    </td>

                                                                    <td>
                                                                        <input type="text"
                                                                               name="VnrRefBusiness_Email[]"
                                                                               id="VnrRefBusiness_Email1"
                                                                               class="form-control form-control-sm">
                                                                    </td>
                                                                    <td>
                                                                        <select name="VnrRefBusinessRelation[]"
                                                                                id="VnrRefBusinessRelation1"
                                                                                class="form-select form-select-sm">
                                                                            <option value="">Select</option>
                                                                            <option value="Dealer">Dealer</option>
                                                                            <option value="Distributor">Distributor
                                                                            </option>
                                                                            <option value="Retailer">Retailer
                                                                            </option>
                                                                            <option value="Organizer">Organizer
                                                                            </option>
                                                                            <option value="Vendor">Vendor</option>

                                                                        </select>
                                                                        <br>
                                                                    </td>

                                                                    <td>
                                                                        <input type="text"
                                                                               name="VnrRefBusiness_Location[]"
                                                                               id="VnrRefBusiness_Location1"
                                                                               class="form-control form-control-sm">
                                                                    </td>
                                                                    <td>
                                                                        <input type="text"
                                                                               name="VnrRefBusiness_RelWithPerson[]"
                                                                               id="VnrRefBusiness_RelWithPerson1"
                                                                               class="form-control form-control-sm">
                                                                    </td>


                                                                    <td>
                                                                        <div class="d-flex order-actions"><a
                                                                                    href="javascript:;"
                                                                                    class="ms-3"
                                                                                    id="removeVnrRef_Business"><i
                                                                                        class="bx bxs-trash text-danger"></i></a>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                                </tbody>
                                                            </table>
                                                            <input type="button" value="Add" id="addVnrRef_Business"
                                                                   class="btn btn-warning btn-sm">

                                                        </div>
                                                        <hr class="mt-2"
                                                            style="border-top: 3px dotted #2d0eb3;background-color: transparent;"/>
                                                    </div>


                                                    <div class="col-lg-12">
                                                        <p class="fw-bold mt-3">Is any of your relatives or
                                                            acquaintances is/are working or associated with any other
                                                            Seed Company?</p>
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="radio"
                                                                   name="OtherSeedRelation" id="OtherSeedYes" value="Y"
                                                                   onclick="showOtherSeed('Y')"
                                                                    {{ $Rec->OtherSeedRelation == 'Y' ? 'checked' : '' }}>
                                                            <label class="form-check-label"
                                                                   for="OtherSeedYes">Yes</label>
                                                        </div>
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="radio"
                                                                   name="OtherSeedRelation" id="OtherSeedNo" value="N"
                                                                   {{ $Rec->OtherSeedRelation == 'N' ? 'checked' : '' }}
                                                                   onclick="showOtherSeed('N')">
                                                            <label class="form-check-label" for="OtherSeedNo">
                                                                No</label>
                                                        </div>
                                                        <div class="table-responsive {{ $Rec->OtherSeedRelation == 'N' ? 'd-none' : '' }}"
                                                             id="OtherSeedDiv">
                                                            <table class="table table-bordered">
                                                                <thead class="text-center">
                                                                <tr>
                                                                    <td>Name</td>
                                                                    <td>Mobile No</td>
                                                                    <td>Email</td>
                                                                    <td>Company Name</td>
                                                                    <td>Designation</td>
                                                                    <td>Location</td>
                                                                    <td>Your Relationship <br>with person mentioned
                                                                    </td>
                                                                    <td></td>
                                                                </tr>
                                                                </thead>
                                                                <tbody id="OtherSeed">
                                                                <tr>
                                                                    <td><input type="text" name="OtherSeedName[]"
                                                                               id="OtherSeedName1"
                                                                               class="form-control form-control-sm">
                                                                    </td>
                                                                    <td><input type="text" name="OtherSeedMobile[]"
                                                                               id="OtherSeedMobile1"
                                                                               class="form-control form-control-sm">
                                                                    </td>
                                                                    <td><input type="text" name="OtherSeedEMail[]"
                                                                               id="OtherSeedEMail1"
                                                                               class="form-control form-control-sm">
                                                                    </td>
                                                                    <td><input type="text" name="OtherSeedCompany[]"
                                                                               id="OtherSeedCompany1"
                                                                               class="form-control form-control-sm">
                                                                    </td>
                                                                    <td><input type="text"
                                                                               name="OtherSeedDesignation[]"
                                                                               id="OtherSeedDesignation1"
                                                                               class="form-control form-control-sm">
                                                                    </td>
                                                                    <td><input type="text"
                                                                               name="OtherSeedLocation[]"
                                                                               id="OtherSeedLocation1"
                                                                               class="form-control form-control-sm">
                                                                    </td>
                                                                    <td><input type="text"
                                                                               name="OtherSeedRelation[]"
                                                                               id="OtherSeedRelation1"
                                                                               class="form-control form-control-sm">
                                                                    </td>
                                                                    <td>
                                                                        <div class="d-flex order-actions"><a
                                                                                    href="javascript:;"
                                                                                    class="ms-3"
                                                                                    id="removeOtherSeed"><i
                                                                                        class="bx bxs-trash text-danger"></i></a>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                                </tbody>
                                                            </table>
                                                            <input type="button" value="Add" id="addOtherSeed"
                                                                   class="btn btn-warning btn-sm">

                                                        </div>
                                                        <hr class="mt-2"
                                                            style="border-top: 3px dotted #2d0eb3;background-color: transparent;"/>
                                                    </div>

                                                    <div class="col-7 mt-4">
                                                        <p class="fw-bold">Language Proficiency</p>
                                                        <div class="table-responsive">
                                                            <table class="table table-bordered text-center"
                                                                   style="vertical-align: middle">
                                                                <thead class="text-center">
                                                                <tr>
                                                                    <td>Language</td>
                                                                    <td>Reading</td>
                                                                    <td>Writing</td>
                                                                    <td>Speaking</td>
                                                                    <td style="width:30px;"></td>
                                                                </tr>
                                                                </thead>
                                                                <tbody id="LanguageData">
                                                                <tr>
                                                                    <td>
                                                                        <input type="text" id="Language1"
                                                                               class="form-control form-control-sm"
                                                                               value="Hindi" readonly>
                                                                    </td>
                                                                    <td>
                                                                        <input type="checkbox" id="Read1" value="0">
                                                                    </td>
                                                                    <td>
                                                                        <input type="checkbox" id="Write1"
                                                                               value="0">
                                                                    </td>
                                                                    <td>
                                                                        <input type="checkbox" id="Speak1"
                                                                               value="0">
                                                                    </td>
                                                                    <td></td>
                                                                </tr>
                                                                <tr>
                                                                    <td>
                                                                        <input type="text" id="Language2"
                                                                               class="form-control form-control-sm"
                                                                               value="English" readonly>
                                                                    </td>
                                                                    <td>
                                                                        <input type="checkbox" id="Read2" value="0">
                                                                    </td>
                                                                    <td>
                                                                        <input type="checkbox" id="Write2"
                                                                               value="0">
                                                                    </td>
                                                                    <td>
                                                                        <input type="checkbox" id="Speak2"
                                                                               value="0">
                                                                    </td>
                                                                    <td></td>
                                                                </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                        <input type="button" value="Add Language" id="addLanguage"
                                                               class="btn btn-warning btn-sm">
                                                    </div>


                                                    <div class="submit-section text-center">
                                                        <button class="btn btn-primary submit-btn" id="save_other">Save
                                                            Details
                                                        </button>
                                                    </div>

                                                </div>
                                            </form>
                                        </div>

                                        <div id="document" class="tab-pane" role="tabpanel"
                                             aria-labelledby="step-2"
                                             style="position: static; left: auto; width: 1017px; display: none;">
                                            <div class="row">
                                                <h6>Provide documents of the company you previously worked for:</h6>
                                                <p class="text-danger fw-bold">Note: Please upload pdf file only. File
                                                    Size must be less then 2MB.
                                                </p>
                                                <div class="col-lg-9 table-responsive">
                                                    <table class="table table-bordered">
                                                        <thead class="text-center">

                                                        <th>Document Name</th>
                                                        <th>Upload Document</th>
                                                        <th>View</th>
                                                        </thead>
                                                        <tbody>
                                                        @if ($Rec->Professional == 'P')
                                                            <tr>
                                                                <td style="width: 25%">Offer or appointment letter
                                                                    (previous company) <font
                                                                            class="text-danger">*</font></td>
                                                                <td style="width: 60%">
                                                                    <input type="file" name="OfferLtr" id="OfferLtr"
                                                                           class="form-control form-control-sm d-inline"
                                                                           style="width: 50%" accept="application/pdf">
                                                                    <button class="btn btn-warning btn-sm d-inline"
                                                                            id="OfferLtrUpload">Upload
                                                                    </button>
                                                                </td>
                                                                <td style="width: 10%; text-align:center">
                                                                    @if ($Docs != null && $Docs->OfferLtr != null)
                                                                        <a href="{{ Storage::disk('s3')->url('Recruitment/Documents/' . $Docs->OfferLtr) }}"
                                                                           target="_blank"
                                                                           class="btn btn-primary btn-sm">View</a>
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                            <tr>

                                                                <td>Resignation or Relieving Letter (previous
                                                                    company)
                                                                </td>
                                                                <td>
                                                                    <input type="file" name="RelievingLtr"
                                                                           id="RelievingLtr"
                                                                           class="form-control form-control-sm d-inline"
                                                                           style="width: 50%" accept="application/pdf">
                                                                    <button class="btn btn-warning btn-sm d-inline"
                                                                            id="RelievingLtrUpload">Upload
                                                                    </button>
                                                                </td>
                                                                <td style="width: 10%; text-align:center">
                                                                    @if ($Docs != null && $Docs->RelievingLtr != null)
                                                                        <a href="{{ Storage::disk('s3')->url('Recruitment/Documents/' . $Docs->RelievingLtr) }}"
                                                                           target="_blank"
                                                                           class="btn btn-primary btn-sm">View</a>
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Last drawn salary pay slip (previous company) <font
                                                                            class="text-danger">*</font>
                                                                </td>
                                                                <td>
                                                                    <input type="file" name="SalarySlip"
                                                                           id="SalarySlip"
                                                                           class="form-control form-control-sm d-inline"
                                                                           style="width: 50%" accept="application/pdf">
                                                                    <button class="btn btn-warning btn-sm d-inline"
                                                                            id="SalarySlipUpload">Upload
                                                                    </button>
                                                                </td>
                                                                <td style="width: 10%; text-align:center">
                                                                    @if ($Docs != null && $Docs->SalarySlip != null)
                                                                        <a href="{{ Storage::disk('s3')->url('Recruitment/Documents/' . $Docs->SalarySlip) }}"
                                                                           target="_blank"
                                                                           class="btn btn-primary btn-sm">View</a>
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                            <tr>

                                                                <td>Increment or appraisal letter with revised CTC
                                                                    details
                                                                </td>
                                                                <td>
                                                                    <input type="file" name="AppraisalLtr"
                                                                           id="AppraisalLtr"
                                                                           class="form-control form-control-sm d-inline"
                                                                           style="width: 50%" accept="application/pdf">
                                                                    <button class="btn btn-warning btn-sm d-inline"
                                                                            id="AppraisalLtrUpload">Upload
                                                                    </button>
                                                                </td>
                                                                <td style="width: 10%; text-align:center">
                                                                    @if ($Docs != null && $Docs->AppraisalLtr != null)
                                                                        <a href="{{ Storage::disk('s3')->url('Recruitment/Documents/' . $Docs->AppraisalLtr) }}"
                                                                           target="_blank"
                                                                           class="btn btn-primary btn-sm">View</a>
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                        @endif
                                                        <tr>

                                                            <td>COVID Vaccine Certificate (Final Certificate)
                                                            </td>
                                                            <td>
                                                                <input type="file" name="VaccinationCert"
                                                                       id="VaccinationCert"
                                                                       class="form-control form-control-sm d-inline"
                                                                       style="width: 50%" accept="application/pdf">
                                                                <button class="btn btn-warning btn-sm d-inline"
                                                                        id="VaccinationCertUpload">Upload
                                                                </button>
                                                            </td>
                                                            <td style="width: 10%; text-align:center">
                                                                @if ($Docs != null && $Docs->VaccinationCert != null)
                                                                    <a href="{{ Storage::disk('s3')->url('Recruitment/Documents/' . $Docs->VaccinationCert) }}"
                                                                       target="_blank"
                                                                       class="btn btn-primary btn-sm">View</a>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </div>


                                            </div>
                                            <div class="submit-section text-center">
                                                <button class="btn btn-primary submit-btn" id="save_documents">Save
                                                    Details
                                                </button>
                                            </div>

                                        </div>

                                        <div id="final" class="tab-pane" role="tabpanel" aria-labelledby="step-2"
                                             style="position: static; left: auto; width: 1017px; display: none;">
                                            <div class="row text-center">
                                                <h4>DECLARATION</h4>
                                                <p style="text-align: justify;font-size:16px;">
                                                    I hereby declare that all the information’s and facts set forth
                                                    in
                                                    this application and any supplemental
                                                    information is true and complete to the best of my knowledge. I
                                                    understand that, if employed, falsified
                                                    statements on this application shall be considered sufficient
                                                    cause
                                                    for immediate discharge. I hereby
                                                    authorize investigation of all statements contained herein and
                                                    employers listed above to give you any
                                                    and all information concerning my employment, and any pertinent
                                                    information they may have, and
                                                    release all parties from all liability for any damage that may
                                                    result from furnishing same. I understand
                                                    that neither the completion of this application nor any other
                                                    part
                                                    of my consideration for employment
                                                    establishes any obligation for the company to hire me. I
                                                    understand
                                                    that I am required to abide by all
                                                    rules and regulations of the company.
                                                </p>
                                                <p class="mb-0"><span
                                                            style="float: left;font-size:16px; margin-left:50px;">Place:</span>
                                                    <span
                                                            style="float: right; margin-right:50px;">_________________________</span>
                                                </p>
                                                <p><span
                                                            style="float: left;font-size:16px; margin-left:50px;">Date:</span>
                                                    <span
                                                            style="float: right; margin-right:50px; font-size:16px;">Signature
                                                        of applicant</span>
                                                </p>

                                            </div>
                                            <div class="submit-section text-center">
                                                <button class="btn btn-success submit-btn" id="final_submit">Submit
                                                    Pre
                                                    Interview Application Form
                                                </button>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @endif


    </div>
</div>
<script src="{{ URL::to('/') }}/assets/js/bootstrap.bundle.min.js"></script>
<script src="{{ URL::to('/') }}/assets/plugins/select2/js/select2.min.js"></script>
<script src="{{ URL::to('/') }}/assets/js/sweetalert2.min.js"></script>
<script src="{{ URL::to('/') }}/assets/js/toastr.min.js"></script>
<script src="{{ URL::to('/') }}/assets/js/BsMultiSelect.min.js"></script>
<script src="{{ URL::to('/') }}/assets/plugins/smart-wizard/js/jquery.smartWizard.min.js"></script>
<script>
            function isLetterKey(evt) {
            var charCode = (evt.which) ? evt.which : event.keyCode;
            if ((charCode < 65 || charCode > 90) && (charCode < 97 || charCode > 122) && charCode !== 32) {
                return false;
            }
            return true;
        }
    $(document).ready(function () {
        $('#smartwizard').smartWizard({
            selected: 0,
            theme: 'default',
            autoAdjustHeight: true,
            justified: true,
            backButtonSupport: true,
            transition: {
                animation: 'slide-horizontal', // Effect on navigation, none/fade/slide-horizontal/slide-vertical/slide-swing
            },
            toolbarSettings: {
                toolbarPosition: 'bottom', // none, top, bottom, both
                showNextButton: false, // show/hide a Next button
                showPreviousButton: false, // show/hide a Previous button
            },
            keyboardSettings: {
                keyNavigation: false, // Enable/Disable keyboard navigation(left and right keys are used if enabled)

            },
        });
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $(".tab-content").height('auto');
        GetQualification();
        GetFamily();
        getWorkExp();
        getPreOrgRef();
        getVnrRef();
        getVnrRef_Business();
        getOtherSeed();
        getEducationList();
        getAllSP();
        getCollegeList();
        getYearList();
        getTraining();
        getLanguageProficiency();
        $(document).on('change', '#CandidateImage', function (e) {
            const [file] = e.target.files;
            if (file) {
                img1.src = URL.createObjectURL(file);
            }
        });
        $(".tab-content").height('auto');


        $(document).on('click', '#proceed_btn', function (event) {
            event.preventDefault(); // Prevent the default behavior of the button click
            var JCId = $('#JCId').val();

            var ajaxUrl = "{{ route('SaveInterviewConfirmation') }}";
            if ($('#i_confirm').prop('checked')) {
                $.ajax({
                    url: ajaxUrl,
                    method: 'POST',
                    data: {
                        JCId: JCId
                    },
                    dataType: 'json',
                    success: function (data) {
                        if (data.status == 400) {
                            toastr.error("Something went wrong...Please try again");
                        } else {
                            window.location.reload();
                        }
                    },
                    error: function () {
                        toastr.error("An error occurred during the AJAX request");
                    }
                });
            } else {
                toastr.error("Please confirm before proceeding.");
            }
        });

    });
</script>

<script>
    var MemberCount = 1;
    var EducationCount = 1;
    var WorkExpCount = 1;
    var TrainingCount = 1;
    var RefCount = 1;
    var VRefCount = 1;
    var VRef_Business_Count = 1;
    var OtherSeedCount = 1;
    var LanguageCount = 2;
    var EducationList = '';
    var SpecializationList = '';

    var QualiList = `<option value="">Select</option>
    <option value="Below 10th">Below 10th</option>
    <option value="10th">10th</option>
    <option value="12th">12th</option>
    <option value="Graduation">Graduation</option>
    <option value="Post_Graduation">Post Graduation</option>
    <option value="Doctorate">Doctorate</option>
    <option value="Diploma">Diploma</option>
    <option value="PG_Diploma">PG Diploma</option>
    <option value="Other">Other</option>`;
    var CollegeList = '';
    var YearList = '';


    function getEducationList() {
        $.ajax({
            type: "GET",
            url: "{{ route('getEducation') }}",
            async: false,
            success: function (res) {

                if (res) {
                    EducationList = '<option value="">Select</option>';
                    $.each(res, function (key, value) {
                        EducationList = EducationList + '<option value="' + value + '">' + key +
                            '</option>';
                    });

                }
            }
        });
    } //getEducationList

    function getCollegeList() {
        var CountryId = $('#CountryId').val();
        $.ajax({
            type: "GET",
            url: "{{ route('getCollege1') }}?CountryId=" + CountryId,
            async: false,
            success: function (res) {

                if (res) {
                    CollegeList = '<option value="">Select</option>';
                    CollegeList += '<option value="637">Other</option>';
                    $.each(res, function (key, value) {
                        CollegeList = CollegeList + '<option value="' + value + '">' + key +
                            '</option>';
                    });

                }
            }
        });
    } //getCollegeList

    function getAllSP() {
        $.ajax({
            type: "GET",
            url: "{{ route('getAllSP') }}",
            async: false,
            success: function (res) {
                if (res) {
                    SpecializationList = '<option value="">Select</option>';
                    $.each(res, function (key, value) {
                        SpecializationList = SpecializationList + '<option value="' + key + '">' +
                            value +
                            '</option>';
                    });
                }
            }
        });
    } //getAllSP

    function getYearList() {
        var year = new Date().getFullYear();
        YearList = '<option value="">Select</option>';
        for (var i = 1980; i <= year; i++) {
            YearList = YearList + '<option value="' + i + '">' + i + '</option>';
        }
    } //getYearList

    function getSpecialization(EducationId, No) {
        var EducationId = EducationId;
        var No = No;
        $.ajax({
            type: "GET",
            url: "{{ route('getSpecialization') }}?EducationId=" + EducationId,
            async: false,

            success: function (res) {

                if (res) {

                    $("#Specialization" + No).empty();
                    $("#Specialization" + No).append(
                        '<option value="" selected disabled >Select Specialization</option>');
                    $.each(res, function (key, value) {
                        $("#Specialization" + No).append('<option value="' + value + '">' + key +
                            '</option>');
                    });
                    $("#Specialization" + No).append('<option value="0">Other</option>');


                } else {
                    $("#Specialization" + No).empty();
                }
            }
        });
    } //getSpecialization

    function Qualification(num) {
        var a = '';
        a += '<tr>';
        a +=  '<td>' + '<select class="form-select form-select-sm edureq" name="Qualification[]" id="Qualification' + num +
            '">' + QualiList +
            '</select>' +
            '</td>' +
            '<td>' + '<select class="form-select form-select-sm edureq" name="Course[]" id="Course' + num +
            '" onchange="getSpecialization(this.value,' + num + ')">' + EducationList +
            '</select>' +
            '</td>' +
            '<td>' + '<select class="form-select form-select-sm edureq" name="Specialization[]" id="Specialization' + num +
            '">' + SpecializationList +
            '</select>' +
            '</td>' +
            '<td>' + '<select class="form-select form-select-sm edureq" name="Collage[]" id="Collage' + num +
            '" onchange="getOtherInstitute(' + num + ')">' + CollegeList +
            '</select>' +
            '<input type="text" name="OtherInstitute[]" id="OtherInstitute' + num + '" class="form-control form-control-sm mt-1 d-none">' +
            '</td>' +
            '<td>' + '<select class="form-select form-select-sm edureq" name="PassingYear[]" id="PassingYear' + num +
            '">' +
            YearList +
            '</select>' +
            '</td>' +
            '<td>' + '<input type="text" name="Percentage[]" id="Percentage' + num +
            '" class="form-control form-control-sm edureq">' + '</td>' +

            '<td>' +'<input type="file" name = "Attachment[]" id="Attachment' + num + '" class="form-control form-control-sm">' + '</td>' +
            '<td>' +
            '<div class="d-flex order-actions"><a href="javascript:;" class="ms-3" id="removeQualification"><i class="bx bxs-trash text-danger"></i></a></div>' +
            '</td>';

        a += '</tr>';

        $('#EducationData').append(a);
    } //Qualification


    function WorkExperience(num) {
        var b = '';
        b += '<tr>';
        b += '<td>' + '<input type="text" name="WorkExpCompany[]" id="WorkExpCompany' + num +
            '" class="form-control form-control-sm">' + '</td>' +
            '<td>' + '<input type="text" name="WorkExpDesignation[]" id="WorkExpDesignation' + num +
            '" class="form-control form-control-sm">' + '</td>' +
            '<td>' + '<input type="text" name="WorkExpGrossMonthlySalary[]" id="WorkExpGrossMonthlySalary' + num +
            '" class="form-control form-control-sm">' + '</td>' +
            '<td>' + '<input type="text" name="WorkExpAnualCTC[]" id="WorkExpAnualCTC' + num +
            '" class="form-control form-control-sm">' + '</td>' +
            '<td>' + '<input type="date" name="WorkExpJobStartDate[]" id="WorkExpJobStartDate' + num +
            '" class="form-control form-control-sm">' + '</td>' +
            '<td>' + '<input type="date" name="WorkExpJobEndDate[]" id="WorkExpJobEndDate' + num +
            '" class="form-control form-control-sm">' + '</td>' +
            '<td>' + '<input type="text" name="WorkExpReasonForLeaving[]" id="WorkExpReasonForLeaving' + num +
            '" class="form-control form-control-sm">' + '</td>' +
            '<td>' +
            '<div class="d-flex order-actions"><a href="javascript:;" class="ms-3" id="removeWorkExp"><i class="bx bxs-trash text-danger"></i></a></div>' +
            '</td>';
        b += '</tr>';
        $('#WorkExpData').append(b);
    } //WorkExperience


    function getTraining() {
        $('#Training_JCId').val($('#JCId').val());
        var JCId = $('#JCId').val();
        $.ajax({
            url: "{{ route('Candidate_Training') }}",
            type: "POST",
            data: {
                JCId: JCId
            },
            dataType: "json",
            success: function (data) {
                if (data.status == 200) {
                    TrainingCount = data.data.length;
                    for (var i = 1; i <= TrainingCount; i++) {
                        if (i >= 2) {
                            Training(i);
                        }
                        $('#TrainingNature' + i).val(data.data[i - 1].training);
                        $('#TrainingOrganization' + i).val(data.data[i - 1].organization);
                        $('#TrainingFromDate' + i).val(data.data[i - 1].from);
                        $('#TrainingToDate' + i).val(data.data[i - 1].to);

                    }
                }
            }
        });
    } //getTraining


    function Training(num) {
        var b = '';
        b += '<tr>';
        b += '<td>' + '<input type="text" name="TrainingNature[]" id="TrainingNature' + num +
            '" class="form-control form-control-sm">' + '</td>' +
            '<td>' + '<input type="text" name="TrainingOrganization[]" id="TrainingOrganization' + num +
            '" class="form-control form-control-sm">' + '</td>' +
            '<td>' + '<input type="date" name="TrainingFromDate[]" id="TrainingFromDate' + num +
            '" class="form-control form-control-sm">' + '</td>' +
            '<td>' + '<input type="date" name="TrainingToDate[]" id="TrainingToDate' + num +
            '" class="form-control form-control-sm">' + '</td>' +
            '<td>' +
            '<div class="d-flex order-actions"><a href="javascript:;" class="ms-3" id="removeTraining"><i class="bx bxs-trash text-danger"></i></a></div>' +
            '</td>';
        b += '</tr>';
        $('#TrainingData').append(b);
    } //Training


    $(document).on('click', '#addTraining', function () {
        TrainingCount++;
        Training(TrainingCount);
        $(".tab-content").height('auto');
    });

    $(document).on('click', '#removeTraining', function () {
        if (confirm('Are you sure you want to delete this record?')) {
            $(this).closest('tr').remove();
            TrainingCount--;
        }
    });

    function familymember(number) {

        var x = '';
        x += '<tr>';
        x += '<td>' + '<select class="form-select form-select-sm reqinp_fam" name="Relation[]" id="Relation' + number +
            '">' +
            '<option value=""></option>' +
            '<option value="Father">Father</option>' +
            '<option value="Mother">Mother</option>' + '<option value="Brother">Brother</option>' +
            '<option value="Sister">Sister</option>' +
            '<option value="Spouse">Spouse</option>' + '<option value="Son">Son</option>' +
            '<option value="Daughter">Daughter</option>' + '</select>' +
            '</td>';
        x += '<td>' +
            '<input type="text" name="RelationName[]" id="RelationName' + number +
            '" class="form-control form-control-sm reqinp_fam" style="text-transform: capitalize;">' +
            '</td>';
        x += '<td>' +
            '<input type="date" name="RelationDOB[]" id="RelationDOB' + number +
            '" class="form-control form-control-sm reqinp_fam">' +
            '</td>';
        x += '<td>' +

            ' <select  name="RelationQualification[]" id="RelationQualification' +
            number +
            '" class="form-control form-select form-select-sm reqinp_fam" >' +
            '  <option value="" selected disabled>Select Education</option>' + EducationList +
            '</select>' +
            '</td>';
        x += '<td>' +
            '<input type="text" name="RelationOccupation[]" id="RelationOccupation' + number +
            '" class="form-control form-control-sm reqinp_fam" style="text-transform: capitalize;">' +
            '</td>';
        x += '<td>' + '<button class="btn btn-sm btn-danger" id="removeMember">Delete</button>' + '</td>';
        x += '</tr>';
        $('#FamilyData').append(x);
    } //familymember

    $(document).on('click', '#addMember', function () {
        MemberCount++;
        familymember(MemberCount);
        $(".tab-content").height('auto');
    }); // addMember

    $(document).on('click', '#removeMember', function () {
        if (confirm('Are you sure you want to delete this member?')) {
            $(this).closest('tr').remove();
            MemberCount--;
        }
    });

    $(document).on('click', '#addEducation', function () {
        EducationCount++;
        Qualification(EducationCount);
        $(".tab-content").height('auto');
    });

    $(document).on('click', '#removeQualification', function () {
        if (confirm('Are you sure you want to delete this record?')) {
            $(this).closest('tr').remove();
            EducationCount--;
            $(".tab-content").height('auto');
        }
    });

    $(document).on('change', '#Religion', function () {
        var Religion = $(this).val();
        if (Religion == 'Others') {
            $('#OtherReligion').removeClass('d-none');
            $('#OtherReligion').addClass('reqinp');
            $(".tab-content").height('auto');
        } else {
            $('#OtherReligion').addClass('d-none');
            $('#OtherReligion').removeClass('reqinp');
            $(".tab-content").height('auto');
        }
    });

    $(document).on('change', '#Category', function () {
        var Category = $(this).val();
        if (Category == 'Other') {
            $('#OtherCategory').removeClass('d-none');
            $('#OtherCategory').addClass('reqinp');
            $(".tab-content").height('auto');
        } else {
            $('#OtherCategory').addClass('d-none');
            $('#OtherCategory').removeClass('reqinp');
            $(".tab-content").height('auto');
        }
    });

    $(document).on('change', '#MaritalStatus', function () {
        var MaritalStatus = $(this).val();
        if (MaritalStatus == 'Married') {
            $('#MDate').removeClass('d-none');
            $('#Spouse').removeClass('d-none');
            $('#MarriageDate').addClass('reqinp');
            $('#SpouseName').addClass('reqinp');
            $(".tab-content").height('auto');
        } else {
            $('#MDate').addClass('d-none');
            $('#Spouse').addClass('d-none');
            $('#MarriageDate').removeClass('reqinp');
            $('#SpouseName').removeClass('reqinp');
            $(".tab-content").height('auto');
        }
    });

    function getLocation(StateId) {
        var StateId = StateId;
        $.ajax({
            type: "GET",
            url: "{{ route('getDistrict') }}?StateId=" + StateId,
            async: false,
            beforeSend: function () {
                $('#PreDistLoader').removeClass('d-none');
                $('#PreDistrict').addClass('d-none');
            },
            success: function (res) {
                if (res) {
                    setTimeout(function () {
                            $('#PreDistLoader').addClass('d-none');
                            $('#PreDistrict').removeClass('d-none');
                            $("#PreDistrict").empty();
                            $("#PreDistrict").append(
                                '<option value="" selected disabled >Select District</option>');
                            $.each(res, function (key, value) {
                                $("#PreDistrict").append('<option value="' + value + '">' +
                                    key +
                                    '</option>');
                            });
                        },
                        500);
                } else {
                    $("#PreDistrict").empty();
                }
            }
        });
    } //getLocation

    function getLocation1(StateId) {
        var StateId = StateId;
        $.ajax({
            type: "GET",
            url: "{{ route('getDistrict') }}?StateId=" + StateId,
            async: false,
            beforeSend: function () {
                $('#PermDistLoader').removeClass('d-none');
                $('#PermDistrict').addClass('d-none');
            },
            success: function (res) {
                if (res) {
                    setTimeout(function () {
                            $('#PermDistLoader').addClass('d-none');
                            $('#PermDistrict').removeClass('d-none');
                            $("#PermDistrict").empty();
                            $("#PermDistrict").append(
                                '<option value="" selected disabled >Select District</option>');
                            $.each(res, function (key, value) {
                                $("#PermDistrict").append('<option value="' + value + '">' +
                                    key +
                                    '</option>');
                            });
                        },
                        500);
                } else {
                    $("#PermDistrict").empty();
                }
            }
        });
    } // getLocation1

    function GetQualification() {
        var JCId = $('#JCId').val();
        $.ajax({
            url: "{{ route('Candidate_Education') }}",
            type: "POST",
            data: {
                JCId: JCId,
            },
            dataType: "json",
            success: function (data) {
                if (data.status == 200) {
                    $('#Edu_JCId').val($('#JCId').val());
                    EducationCount = data.data.length;
                    for (var i = 1; i <= EducationCount; i++) {
                        if (i >= 2) {
                            Qualification(i);
                        }
                        $('#Qualification' + i).val(data.data[i - 1].Qualification);
                        $('#Course' + i).val(data.data[i - 1].Course);
                        $('#Specialization' + i).val(data.data[i - 1].Specialization);
                        $('#Collage' + i).val(data.data[i - 1].Institute);
                        $('#PassingYear' + i).val(data.data[i - 1].YearOfPassing);
                        $('#Percentage' + i).val(data.data[i - 1].CGPA);
                        if (data.data[i - 1].Institute == '637') {
                            $('#OtherInstitute' + i).removeClass('d-none');
                            // $('#OtherInstitute' + i).addClass('reqinp');
                            $('#OtherInstitute' + i).val(data.data[i - 1].OtherInstitute);
                        }

                    }
                }
            }
        });
    } //GetQualification

    function GetFamily() {
        var JCId = $('#JCId').val();
        $.ajax({
            url: "{{ route('Candidate_Family') }}",
            type: "POST",
            data: {
                JCId: JCId
            },
            dataType: "json",
            success: function (data) {
                if (data.status == 200) {
                    $('#Family_JCId').val($('#JCId').val());
                    MemberCount = data.data.length;
                    for (var i = 1; i <= MemberCount; i++) {
                        if (i >= 3) {
                            familymember(i);
                        }

                        $('#Relation' + i).val(data.data[i - 1].relation);
                        $('#RelationName' + i).val(data.data[i - 1].name);
                        $('#RelationDOB' + i).val(data.data[i - 1].dob);
                        $('#RelationQualification' + i).val(data.data[i - 1].qualification);
                        $('#RelationOccupation' + i).val(data.data[i - 1].occupation);
                    }
                }
            }
        });
    }

    function getWorkExp() {
        var JCId = $('#JCId').val();
        $.ajax({
            url: "{{ route('Candidate_Experience') }}",
            type: "POST",
            data: {
                JCId: JCId
            },
            dataType: "json",
            success: function (data) {
                if (data.status == 200) {
                    $('#Work_JCId').val($('#JCId').val());
                    WorkExpCount = data.data.length;
                    for (var i = 1; i <= WorkExpCount; i++) {
                        if (i >= 2) {
                            WorkExperience(i);
                        }
                        $('#WorkExpCompany' + i).val(data.data[i - 1].company);
                        $('#WorkExpDesignation' + i).val(data.data[i - 1].desgination);
                        $('#WorkExpGrossMonthlySalary' + i).val(data.data[i - 1].gross_mon_sal);
                        $('#WorkExpAnualCTC' + i).val(data.data[i - 1].annual_ctc);
                        $('#WorkExpJobStartDate' + i).val(data.data[i - 1].job_start);
                        $('#WorkExpJobEndDate' + i).val(data.data[i - 1].job_end);
                        $('#WorkExpReasonForLeaving' + i).val(data.data[i - 1].reason_fr_leaving);

                    }
                }
            }
        });
    } //getWorkExp

    $(document).on('click', '#addExperience', function () {
        WorkExpCount++;
        WorkExperience(WorkExpCount);
        $(".tab-content").height('auto');
    });

    $(document).on('click', '#removeWorkExp', function () {
        if (confirm('Are you sure you want to delete this record?')) {
            $(this).closest('tr').remove();
            WorkExpCount--;
            $(".tab-content").height('auto');
        }
    });

    function getPreOrgRef() {

        var JCId = $('#JCId').val();
        $.ajax({
            url: "{{ route('Candidate_PreOrgRef') }}",
            type: "POST",
            data: {
                JCId: JCId
            },
            dataType: "json",
            success: function (data) {
                if (data.status == 200) {
                    RefCount = data.data.length;
                    for (var i = 1; i <= RefCount; i++) {
                        if (i >= 2) {
                            PreviousOrgReference(i);
                        }
                        $('#PreOrgName' + i).val(data.data[i - 1].name);
                        $('#PreOrgCompany' + i).val(data.data[i - 1].company);
                        $('#PreOrgDesignation' + i).val(data.data[i - 1].designation);
                        $('#PreOrgEmail' + i).val(data.data[i - 1].email);
                        $('#PreOrgContact' + i).val(data.data[i - 1].contact);
                    }
                }
            }
        });
    } //getPreOrgRef

    function PreviousOrgReference(num) {
        var b = '';
        b += '<tr>';
        b += '<td>' + '<input type="text" name="PreOrgName[]" id="PreOrgName' + num +
            '" class="form-control form-control-sm">' + '</td>' +
            '<td>' + '<input type="text" name="PreOrgCompany[]" id="PreOrgCompany' + num +
            '" class="form-control form-control-sm">' + '</td>' +
            '<td>' + '<input type="text" name="PreOrgDesignation[]" id="PreOrgDesignation' + num +
            '" class="form-control form-control-sm">' + '</td>' +
            '<td>' + '<input type="text" name="PreOrgEmail[]" id="PreOrgEmail' + num +
            '" class="form-control form-control-sm">' + '</td>' +
            '<td>' + '<input type="text" name="PreOrgContact[]" id="PreOrgContact' + num +
            '" class="form-control form-control-sm">' + '</td>' +

            '<td>' +
            '<div class="d-flex order-actions"><a href="javascript:;" class="ms-3" id="removePreOrgRef"><i class="bx bxs-trash text-danger"></i></a></div>' +
            '</td>';
        b += '</tr>';
        $('#PreOrgRefData').append(b);
    } //PreviousOrgReference

    $(document).on('click', '#addPreOrgRef', function () {
        RefCount++;
        PreviousOrgReference(RefCount);
        $(".tab-content").height('auto');
    });

    $(document).on('click', '#removePreOrgRef', function () {
        if (confirm('Are you sure you want to delete this record?')) {
            $(this).closest('tr').remove();
            RefCount--;
            $(".tab-content").height('auto');
        }
    });

    function getVnrRef() {
        $('#Vnr_JCId').val($('#JCId').val());
        var JCId = $('#JCId').val();
        $.ajax({
            url: "{{ route('Candidate_VnrRef') }}",
            type: "POST",
            data: {
                JCId: JCId
            },
            dataType: "json",
            success: function (data) {
                if (data.status == 200) {
                    VRefCount = data.data.length;
                    for (var i = 1; i <= VRefCount; i++) {
                        if (i >= 2) {
                            VNRReference(i);
                        }
                        $('#VnrRefName' + i).val(data.data[i - 1].name);
                        $('#VnrRefRelWithPerson' + i).val(data.data[i - 1].rel_with_person);
                        $('#VnrRefEmail' + i).val(data.data[i - 1].email);
                        $('#VnrRefContact' + i).val(data.data[i - 1].contact);
                        $('#VnrRefDesignation' + i).val(data.data[i - 1].designation);
                        $('#VnrRefCompany' + i).val(data.data[i - 1].company);
                        $('#OtherCompany' + i).val(data.data[i - 1].other_company);
                        $('#VnrRefLocation' + i).val(data.data[i - 1].location);
                        if (data.data[i - 1].company == 'Other') {
                            $('#OtherCompany' + i).removeClass('d-none');
                        }
                    }
                }
            }
        });
    } //getVnrRef

    function VNRReference(num) {
        var b = '';
        b += '<tr>';
        b += '<td>' + '<input type="text" name="VnrRefName[]" id="VnrRefName' + num +
            '" class="form-control form-control-sm">' + '</td>' +
            '<td>' + '<input type="text" name="VnrRefContact[]" id="VnrRefContact' + num +
            '" class="form-control form-control-sm">' + '</td>' +
            '<td>' + '<input type="text" name="VnrRefEmail[]" id="VnrRefEmail' + num +
            '" class="form-control form-control-sm">' + '</td>' +
            '<td>' + '<select name="VnrRefCompany[]" id="VnrRefCompany' + num +
            '" class="form-select form-select-sm" onchange="GetOtherCompany(' + num + ')">' +
            '<option value="">Select</option>' + '<option value="VNR Seeds Pvt. Ltd.">VNR Seeds Pvt. Ltd.</option>' +
            '<option value="VNR Nursery Pvt. Ltd.">VNR Nursery Pvt. Ltd.</option>' +
            '<option value="Other">Other</option>' +
            '</select> <br> <input type="text" name="OtherCompany[]" id="OtherCompany' + num +
            '" class="d-none form-control form-control-sm" placeholder="Enter Other Company Name">' + '</td>' +
            '<td>' + '<input type="text" name="VnrRefDesignation[]" id="VnrRefDesignation' + num +
            '" class="form-control form-control-sm">' + '</td>' +
            '<td>' + '<input type="text" name="VnrRefLocation[]" id="VnrRefLocation' + num +
            '" class="form-control form-control-sm">' + '</td>' +
            '<td>' + '<input type="text" name="VnrRefRelWithPerson[]" id="VnrRefRelWithPerson' + num +
            '" class="form-control form-control-sm">' + '</td>' +
            '<td>' +
            '<div class="d-flex order-actions"><a href="javascript:;" class="ms-3" id="removeVnrRef"><i class="bx bxs-trash text-danger"></i></a></div>' +
            '</td>';
        b += '</tr>';
        $('#VNRRefData').append(b);
    } //VNRReference

    $(document).on('click', '#addVnrRef', function () {
        VRefCount++;
        VNRReference(VRefCount);
        $(".tab-content").height('auto');
    });

    $(document).on('click', '#removeVnrRef', function () {
        if (confirm('Are you sure you want to delete this record?')) {
            $(this).closest('tr').remove();
            VRefCount--;
            $(".tab-content").height('auto');
        }
    });

    function getVnrRef_Business() {
        $('#Vnr_JCId').val($('#JCId').val());
        var JCId = $('#JCId').val();
        $.ajax({
            url: "{{ route('Candidate_VnrRef_Business') }}",
            type: "POST",
            data: {
                JCId: JCId
            },
            dataType: "json",
            success: function (data) {
                if (data.status == 200) {
                    VRef_Business_Count = data.data.length;
                    for (var i = 1; i <= VRef_Business_Count; i++) {
                        if (i >= 2) {
                            VNRReference_Business(i);
                        }
                        $('#VnrRefBusiness_Name' + i).val(data.data[i - 1].Name);
                        $('#VnrRefBusiness_Contact' + i).val(data.data[i - 1].Mobile);
                        $('#VnrRefBusiness_Email' + i).val(data.data[i - 1].Email);
                        $('#VnrRefBusinessRelation' + i).val(data.data[i - 1].BusinessRelation);
                        $('#VnrRefBusiness_Location' + i).val(data.data[i - 1].Location);
                        $('#VnrRefBusiness_RelWithPerson' + i).val(data.data[i - 1].PersonRelation);

                    }
                }
            }
        });
    } //getVnrRef

    function VNRReference_Business(num) {
        var b = '';
        b += '<tr>';
        b += '<td>' + '<input type="text" name="VnrRefBusiness_Name[]" id="VnrRefBusiness_Name' + num +
            '" class="form-control form-control-sm">' + '</td>' +
            '<td>' + '<input type="text" name="VnrRefBusiness_Contact[]" id="VnrRefBusiness_Contact' + num +
            '" class="form-control form-control-sm">' + '</td>' +
            '<td>' + '<input type="text" name="VnrRefBusiness_Email[]" id="VnrRefBusiness_Email' + num +
            '" class="form-control form-control-sm">' + '</td>' +
            '<td>' + '<select name="VnrRefBusinessRelation[]" id="VnrRefBusinessRelation' + num +
            '" class="form-select form-select-sm">' +
            '<option value="">Select</option>' + '<option value="Dealer">Dealer</option>' +
            '<option value="Distributor">Distributor</option>' +
            '<option value="Retailer">Retailer</option>' +
            '<option value="Organizer">Organizer</option>' +
            '<option value="Vendor">Vendor</option>' +
            '</select>' + '</td>' +
            '<td>' + '<input type="text" name="VnrRefBusiness_Location[]" id="VnrRefBusiness_Location' + num +
            '" class="form-control form-control-sm">' + '</td>' +
            '<td>' + '<input type="text" name="VnrRefBusiness_RelWithPerson[]" id="VnrRefBusiness_RelWithPerson' + num +
            '" class="form-control form-control-sm">' + '</td>' +
            '<td>' +
            '<div class="d-flex order-actions"><a href="javascript:;" class="ms-3" id="removeVnrRef"><i class="bx bxs-trash text-danger"></i></a></div>' +
            '</td>';
        b += '</tr>';
        $('#VNR_Business_AcqData').append(b);
    } //VNRReference


    $(document).on('click', '#addVnrRef_Business', function () {
        VRef_Business_Count++;

        VNRReference_Business(VRef_Business_Count);
        $(".tab-content").height('auto');
    });

    $(document).on('click', '#removeVnrRef_Business', function () {
        if (confirm('Are you sure you want to delete this record?')) {
            $(this).closest('tr').remove();
            VRef_Business_Count--;
            $(".tab-content").height('auto');
        }
    });


    function getOtherSeed() {

        var JCId = $('#JCId').val();
        $.ajax({
            url: "{{ route('Candidate_Other_Seed_Relation') }}",
            type: "POST",
            data: {
                JCId: JCId
            },
            dataType: "json",
            success: function (data) {
                if (data.status == 200) {
                    OtherSeedCount = data.data.length;
                    for (var x = 1; x <= OtherSeedCount; x++) {
                        if (x >= 2) {
                            OtherSeed(x);
                        }
                        $('#OtherSeedName' + x).val(data.data[x - 1].Name);
                        $('#OtherSeedMobile' + x).val(data.data[x - 1].Mobile);
                        $('#OtherSeedEMail' + x).val(data.data[x - 1].Email);
                        $('#OtherSeedCompany' + x).val(data.data[x - 1].company_name);
                        $('#OtherSeedDesignation' + x).val(data.data[x - 1].Designation);
                        $('#OtherSeedLocation' + x).val(data.data[x - 1].Location);
                        $('#OtherSeedRelation' + x).val(data.data[x - 1].Relation);

                    }
                }
            }
        });
    }

    function OtherSeed(num) {
        var c = '';
        c += '<tr>';
        c += '<td>' + '<input type="text" name="OtherSeedName[]" id="OtherSeedName' + num +
            '" class="form-control form-control-sm">' + '</td>' +
            '<td>' + '<input type="text" name="OtherSeedMobile[]" id="OtherSeedMobile' + num +
            '" class="form-control form-control-sm">' + '</td>' +
            '<td>' + '<input type="text" name="OtherSeedEMail[]" id="OtherSeedEMail' + num +
            '" class="form-control form-control-sm">' + '</td>' +
            '<td>' + '<input type="text" name="OtherSeedCompany[]" id="OtherSeedCompany' + num +
            '" class="form-control form-control-sm">' + '</td>' +
            '<td>' + '<input type="text" name="OtherSeedDesignation[]" id="OtherSeedDesignation' + num +
            '" class="form-control form-control-sm">' + '</td>' +
            '<td>' + '<input type="text" name="OtherSeedLocation[]" id="OtherSeedLocation' + num +
            '" class="form-control form-control-sm">' + '</td>' +
            '<td>' + '<input type="text" name="OtherSeedRelation[]" id="OtherSeedRelation' + num +
            '" class="form-control form-control-sm">' + '</td>' +
            '<td>' +
            '<div class="d-flex order-actions"><a href="javascript:;" class="ms-3" id="removeOtherSeed"><i class="bx bxs-trash text-danger"></i></a></div>' +
            '</td>';
        c += '</tr>';
        $('#OtherSeed').append(c);
    }

    $(document).on('click', '#addOtherSeed', function () {
        OtherSeedCount++;

        OtherSeed(OtherSeedCount);
        $(".tab-content").height('auto');
    });

    $(document).on('click', '#removeOtherSeed', function () {
        if (confirm('Are you sure you want to delete this record?')) {
            $(this).closest('tr').remove();
            OtherSeedCount--;
            $(".tab-content").height('auto');
        }
    });

    function GetOtherCompany(num) {
        var VnrRefCompany = $('#VnrRefCompany' + num).val();
        if (VnrRefCompany == 'Other') {
            $('#OtherCompany' + num).removeClass('d-none');
        } else {
            $('#OtherCompany' + num).addClass('d-none');
        }
    }

    function getLanguageProficiency() {
        $('#Language_JCId').val($('#JCId').val());
        var JCId = $('#JCId').val();
        $.ajax({
            url: "{{ route('Candidate_Language') }}",
            type: "POST",
            data: {
                JCId: JCId
            },
            dataType: "json",
            success: function (data) {
                if (data.status == 200) {
                    LanguageCount = data.data.length;

                    for (var i = 1; i <= LanguageCount; i++) {

                        if (i > 2) {
                            LaguageProficiency(i);
                        }

                        $('#Language' + i).val(data.data[i - 1].language);

                        if (data.data[i - 1].read == 1) {
                            $('#Read' + i).prop('checked', true);
                            $('#Read' + i).val(1);
                        }
                        if (data.data[i - 1].write == 1) {
                            $('#Write' + i).prop('checked', true);
                            $('#Write' + i).val(1);
                        }
                        if (data.data[i - 1].speak == 1) {
                            $('#Speak' + i).prop('checked', true);
                            $('#Speak' + i).val(1);
                        }


                    }
                }
            }
        });
    } //getLanguageProficiency

    function LaguageProficiency(num) {
        var b = '';
        b += '<tr class="text-center">';
        b += '<td>' + '<input type="text" name="Language[]" id="Language' + num +
            '" class="form-control form-control-sm">' + '</td>' +
            '<td>' + '<input type="checkbox" name="Read[]" id="Read' + num + '" value="0">' + '</td>' +
            '<td>' + '<input type="checkbox" name="Write[]" id="Write' + num + '" value="0">' + '</td>' +
            '<td>' + '<input type="checkbox" name="Speak[]" id="Speak' + num + '" value="0">' + '</td>' +
            '<td>' +

            '<div class="d-flex order-actions"><a href="javascript:;" class="ms-3" id="removeLanguage"><i class="bx bxs-trash text-danger"></i></a></div>' +
            '</td>';

        b += '</tr>';
        $('#LanguageData').append(b);
    } //LanguageProficiency

    $(document).on('click', '#addLanguage', function () {
        LanguageCount++;
        LaguageProficiency(LanguageCount);
        $('.tab-content').height('auto');
    });

    $(document).on('click', '#removeLanguage', function () {
        if (confirm('Are you sure you want to delete this record?')) {
            $(this).closest('tr').remove();
            LanguageCount--;
            $('.tab-content').height('auto');
        }
    });

    function showProFromOrNot(val) {

        if (val == 'prof') {
            $('#professional_div').removeClass('d-none');
            $('#CurrCompany').addClass('reqinp_exp');
            $('#CurrJoinDate').addClass('reqinp_exp');
            $('#CurrDesignation').addClass('reqinp_exp');
            $('#CurrCTC').addClass('reqinp_exp');
            $('#CurrSalary').addClass('reqinp_exp');
            $('#NoticePeriod').addClass('reqinp_exp');
            $('#ExpCTC').addClass('reqinp_exp');
            $('#JobResponsibility').addClass('reqinp_exp');
            $('#ToatalExpYears').addClass('reqinp_exp');
            $('#TotalExpMonth').addClass('reqinp_exp');
            $('#ReportingManager').addClass('reqinp_exp');
            $('#RepDesignation').addClass('reqinp_exp');

            $(".tab-content").height('auto');
        } else {

            $('#professional_div').addClass('d-none');
            $('#CurrCompany').removeClass('reqinp_exp');
            $('#CurrJoinDate').removeClass('reqinp_exp');
            $('#CurrDesignation').removeClass('reqinp_exp');
            $('#CurrCTC').removeClass('reqinp_exp');
            $('#CurrSalary').removeClass('reqinp_exp');
            $('#NoticePeriod').removeClass('reqinp_exp');
            $('#ExpCTC').removeClass('reqinp_exp');
            $('#JobResponsibility').removeClass('reqinp_exp');
            $('#ToatalExpYears').removeClass('reqinp_exp');
            $('#TotalExpMonth').removeClass('reqinp_exp');
            $('#ReportingManager').removeClass('reqinp_exp');
            $('#RepDesignation').removeClass('reqinp_exp');
            $(".tab-content").height('auto');
        }

    }

    function showAcqOrNot(val) {
        if (val == 'Y') {
            $('#AcqDiv').removeClass('d-none');
            $("#VnrRefName1").addClass('reqinp_other');
            $("#VnrRefContact1").addClass('reqinp_other');
            $("#VnrRefCompany1").addClass('reqinp_other');
            $("#VnrRefDesignation1").addClass('reqinp_other');
            $("#VnrRefLocation1").addClass('reqinp_other');
            $("#VnrRefRelWithPerson1").addClass('reqinp_other');
            $(".tab-content").height('auto');
        } else {
            $('#AcqDiv').addClass('d-none');
            $("#VnrRefName1").removeClass('reqinp_other');
            $("#VnrRefContact1").removeClass('reqinp_other');
            $("#VnrRefCompany1").removeClass('reqinp_other');
            $("#VnrRefDesignation1").removeClass('reqinp_other');
            $("#VnrRefLocation1").removeClass('reqinp_other');
            $("#VnrRefRelWithPerson1").removeClass('reqinp_other');
            $(".tab-content").height('auto');
        }

    }

    function showAcqBusiness(val) {
        if (val == 'Y') {
            $('#AcqBusinessDiv').removeClass('d-none');
            $("#VnrRefBusiness_Name1").addClass('reqinp_other');
            $("#VnrRefBusiness_Contact1").addClass('reqinp_other');
            $("#VnrRefBusinessRelation1").addClass('reqinp_other');
            $("#VnrRefBusiness_Location1").addClass('reqinp_other');
            $("#VnrRefBusiness_RelWithPerson1").addClass('reqinp_other');
            $(".tab-content").height('auto');
        } else {
            $('#AcqBusinessDiv').addClass('d-none');
            $("#VnrRefBusiness_Name1").removeClass('reqinp_other');
            $("#VnrRefBusiness_Contact1").removeClass('reqinp_other');
            $("#VnrRefBusinessRelation1").removeClass('reqinp_other');
            $("#VnrRefBusiness_Location1").removeClass('reqinp_other');
            $("#VnrRefBusiness_RelWithPerson1").removeClass('reqinp_other');
            $(".tab-content").height('auto');
        }

    }

    function showOtherSeed(val) {
        if (val == 'Y') {
            $('#OtherSeedDiv').removeClass('d-none');
            $("#OtherSeedName1").addClass('reqinp_other');
            $("#OtherSeedMobile1").addClass('reqinp_other');
            $("#OtherSeedCompany1").addClass('reqinp_other');
            $("#OtherSeedLocation1").addClass('reqinp_other');
            $("#OtherSeedRelation1").addClass('reqinp_other');
            $(".tab-content").height('auto');
        } else {
            $('#OtherSeedDiv').addClass('d-none');
            $("#OtherSeedName1").removeClass('reqinp_other');
            $("#OtherSeedMobile1").removeClass('reqinp_other');
            $("#OtherSeedCompany1").removeClass('reqinp_other');
            $("#OtherSeedLocation1").removeClass('reqinp_other');
            $("#OtherSeedRelation1").removeClass('reqinp_other');
            $(".tab-content").height('auto');
        }

    }

    function TwoWheel() {
        if ($('#TwoWheelChk').prop('checked')) {
            $('#twowheel').removeClass('d-none');
            $('#TwoWheelChk').val('1');
            $('#TwoWheelPetrol').addClass('reqinp_exp');
            $('.tab-content').height('auto');
        } else {
            $('#twowheel').addClass('d-none');
            $('#TwoWheelChk').val('0');
            $('#TwoWheelPetrol').removeClass('reqinp_exp');
            $('.tab-content').height('auto');
        }
    }

    function FourWheel() {

        if ($('#FourWheelChk').prop('checked')) {
            $('#fourwheel').removeClass('d-none');
            $('#FourWheelChk').val('1');
            $('#FourWheelPetrol').addClass('reqinp_exp');
            $('.tab-content').height('auto');
        } else {
            $('#fourwheel').addClass('d-none');
            $('#FourWheelChk').val('0');
            $('#FourWheelPetrol').removeClass('reqinp_exp');
            $('.tab-content').height('auto');
        }

    }

    $(document).on('click', '.otherbenefit', function () {
        if ($(this).prop('checked')) {
            $('#' + $(this).data('payload')).addClass('reqinp_exp').prop('readonly', false);
        } else {
            $('#' + $(this).data('payload')).removeClass('reqinp_exp').prop('readonly', true);
        }
    });


    $(document).on('click', '.dlchk', function () {
        var val = $(this).data('value');
        // debugger;
        if (val == 'Y') {
            $('#dl_div').removeClass('d-none');
            $('#DLNo').addClass('reqinp_abt');
            $('#LValidity').addClass('reqinp_abt');
            $('.tab-content').height('auto');
        } else {
            $('#dl_div').addClass('d-none');
            $('#DLNo').removeClass('reqinp_abt');
            $('#LValidity').removeClass('reqinp_abt');
            $('.tab-content').height('auto');
        }
    });

    $(document).on('click', '.crime', function () {
        var val = $(this).data('value');
        if (val == 'Y') {
            $('#crime_div').removeClass('d-none');
            $('#AboutCriminal').addClass('reqinp_abt');
            $('.tab-content').height('auto');
        } else {
            $('#crime_div').addClass('d-none');
            $('#AboutCriminal').removeClass('reqinp_abt');
            $('.tab-content').height('auto');
        }
    });


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

    function checkRequired_contact() {
        var res = 0;
        $('.reqinp_con').each(function () {
            if ($(this).val() == '' || $(this).val() == null) {
                $(this).addClass('errorfield');
                res = 1;
            } else {
                $(this).removeClass('errorfield');
            }
        });
        return res;
    }

    function checkRequired_family() {
        var res = 0;
        $('.reqinp_fam').each(function () {
            if ($(this).val() == '' || $(this).val() == null) {
                $(this).addClass('errorfield');
                res = 1;
            } else {
                $(this).removeClass('errorfield');
            }
        });
        return res;
    }

    function checkRequired_experience() {
        var res = 0;
        $('.reqinp_exp').each(function () {
            if ($(this).val() == '' || $(this).val() == null) {
                $(this).addClass('errorfield');
                res = 1;
            } else {
                $(this).removeClass('errorfield');
            }
        });
        return res;
    }

    function checkRequired_about() {
        var res = 0;
        $('.reqinp_abt').each(function () {
            if ($(this).val() == '' || $(this).val() == null) {
                $(this).addClass('errorfield');
                res = 1;
            } else {
                $(this).removeClass('errorfield');
            }
        });
        return res;
    }

    function checkRequired_other() {
        var res = 0;
        $('.reqinp_other').each(function () {
            if ($(this).val() == '' || $(this).val() == null) {
                $(this).addClass('errorfield');
                res = 1;
            } else {
                $(this).removeClass('errorfield');
            }
        });
        return res;
    }
    function checkRequired_Education(){
        var edres = 0;
        $('.edureq').each(function(){
            if ($(this).val() == '' || $(this).val() == null) {
                $(this).addClass('errorfield');
                edres = 1;
            } else {
                $(this).removeClass('errorfield');
            }
        });
        return edres;
    }

    $('#personal_form').on('submit', function (e) {
        e.preventDefault();
        var form = this;
        var formData = new FormData(this);
        var res = checkRequired();
        formData.append('JCId', $('#JCId').val());
        var old_image = $('#old_image').val();
        /* if (document.getElementById("CandidateImage").files.length == 0 && old_image == '') {
                toastr.error('Please upload your photo');
            } else { */
        if (res == 0) {
            $.ajax({
                url: $(form).attr('action'),
                method: $(form).attr('method'),
                data: formData,
                processData: false,
                dataType: 'json',
                contentType: false,
                beforeSend: function () {
                    $('#smartwizard').smartWizard("loader", "show");
                },
                success: function (data) {
                    if (data.status == 400) {
                        $('#smartwizard').smartWizard("loader", "hide");
                        toastr.error(data.msg);
                    } else {

                        $('#smartwizard').smartWizard("loader", "hide");
                        $('#smartwizard').smartWizard("goToStep", 1);
                        toastr.success(data.msg);

                        $('.tab-content').height('auto');
                        return true;
                    }
                }
            });
        } else {
            toastr.error('Please fill all required fields');
        }
        // }
    });

    $('#contact_form').on('submit', function (e) {
        e.preventDefault();
        var form = this;
        var formData = new FormData(this);
        var res = checkRequired_contact();
        formData.append('JCId', $('#JCId').val());
        if (res == 0) {
            $.ajax({
                url: $(form).attr('action'),
                method: $(form).attr('method'),
                data: formData,
                processData: false,
                dataType: 'json',
                contentType: false,
                beforeSend: function () {
                    $('#smartwizard').smartWizard("loader", "show");
                },
                success: function (data) {
                    if (data.status == 400) {
                        toastr.error(data.msg);
                        $('#smartwizard').smartWizard("loader", "hide");
                    } else {

                        $('#smartwizard').smartWizard("loader", "hide");
                        toastr.success(data.msg);
                        $('#smartwizard').smartWizard("goToStep", 2);

                        $('.tab-content').height('auto');
                        return true;
                    }
                }
            });
        } else {
            toastr.error('Please fill all required fields');
        }
    });

    $('#EducationInfoForm').on('submit', function (e) {
        e.preventDefault();
        var form = this;
        var formData = new FormData(this);
        var checkReq = checkRequired_Education();
        formData.append('JCId', $('#JCId').val());
        var numberOfAttachments = $('#Attachment1').length;
        for (var i = 1; i <= numberOfAttachments; i++) {
            var fileInput = $('#Attachment' + i)[0].files;
            if (fileInput.length > 0) {
                for (var j = 0; j < fileInput.length; j++) {
                    formData.append('Attachment' + i + '[]', fileInput[j]);
                }
            }
        }
        if(checkReq == 0){
            $.ajax({
                url: $(form).attr('action'),
                method: $(form).attr('method'),
                data: formData,
                processData: false,
                dataType: 'json',
                contentType: false,
                beforeSend: function () {
                    $('#smartwizard').smartWizard("loader", "show");
                },
                success: function (data) {
                    if (data.status == 400) {
                        toastr.error(data.msg);
                        $('#smartwizard').smartWizard("loader", "hide");
                    } else {

                        $('#smartwizard').smartWizard("loader", "hide");
                        toastr.success(data.msg);
                        $('#smartwizard').smartWizard("goToStep", 3);

                        $('.tab-content').height('auto');

                    }
                }
            });
        }else{
            toastr.error('Please fill all required fields');
        }
    });

    $('#FamilyInfoForm').on('submit', function (e) {
        e.preventDefault();
        var form = this;
        var formData = new FormData(this);
        var res = checkRequired_family();
        formData.append('JCId', $('#JCId').val());
        if (res == 0) {
            $.ajax({
                url: $(form).attr('action'),
                method: $(form).attr('method'),
                data: formData,
                processData: false,
                dataType: 'json',
                contentType: false,
                beforeSend: function () {
                    $('#smartwizard').smartWizard("loader", "show");
                },
                success: function (data) {
                    if (data.status == 400) {
                        toastr.error(data.msg);
                        $('#smartwizard').smartWizard("loader", "hide");
                    } else {

                        $('#smartwizard').smartWizard("loader", "hide");
                        toastr.success(data.msg);
                        $('#smartwizard').smartWizard("goToStep", 4);

                        $('.tab-content').height('auto');

                    }
                }
            });
        } else {
            toastr.error('Please fill all required fields');
        }
    });

    $('#ExperienceForm').on('submit', function (e) {
        e.preventDefault();
        var form = this;
        var formData = new FormData(this);
        var res = checkRequired_experience();
        formData.append('JCId', $('#JCId').val());
        if (res == 0) {
            $.ajax({
                url: $(form).attr('action'),
                method: $(form).attr('method'),
                data: formData,
                processData: false,
                dataType: 'json',
                contentType: false,
                beforeSend: function () {
                    $('#smartwizard').smartWizard("loader", "show");
                },
                success: function (data) {
                    if (data.status == 400) {
                        toastr.error(data.msg);
                        $('#smartwizard').smartWizard("loader", "hide");
                    } else {
                        $('#smartwizard').smartWizard("loader", "hide");
                        toastr.success(data.msg);
                        $('#smartwizard').smartWizard("goToStep", 5);
                        $('.tab-content').height('auto');

                    }
                }
            });
        } else {
            toastr.error('Please fill all required fields');
        }
    });

    $('#about_form').on('submit', function (e) {
        e.preventDefault();
        var form = this;
        var formData = new FormData(this);
        var res = checkRequired_about();
        formData.append('JCId', $('#JCId').val());
        if (res == 0) {
            $.ajax({
                url: $(form).attr('action'),
                method: $(form).attr('method'),
                data: formData,
                processData: false,
                dataType: 'json',
                contentType: false,
                beforeSend: function () {
                    $('#smartwizard').smartWizard("loader", "show");
                },
                success: function (data) {
                    if (data.status == 400) {
                        toastr.error(data.msg);
                        $('#smartwizard').smartWizard("loader", "hide");
                    } else {
                        $('#smartwizard').smartWizard("loader", "hide");
                        toastr.success(data.msg);
                        $('#smartwizard').smartWizard("goToStep", 6);
                        $('.tab-content').height('auto');
                    }
                }
            });
        } else {
            toastr.error('Please fill all required fields');
        }

    });

    for (i = 1; i <= 10; i++) {

        $(document).on('change', '#Read' + i, function () {

            if ($(this).prop('checked')) {
                $(this).val('1');
            } else {
                $(this).val('0');
            }
        });

        $(document).on('change', '#Write' + i, function () {
            if ($(this).prop('checked')) {
                $(this).val('1');
            } else {
                $(this).val('0');
            }
        });


        $(document).on('change', '#Speak' + i, function () {
            if ($(this).prop('checked')) {
                $(this).val('1');
            } else {
                $(this).val('0');
            }
        });


    }

    $(document).on('click', '#save_other', function (e) {
        e.preventDefault();
        var form = this;
        var language_array = [];
        var PerOrgArray = [];
        var VnrRefArray = [];
        var VnrBusinessRefArray = [];
        var VnrOtherSeedArray = [];

        for (i = 1; i <= 10; i++) {
            var lang = $('#Language' + i).val();
            var read = $('#Read' + i).val();
            var write = $('#Write' + i).val();
            var speak = $('#Speak' + i).val();
            language_array.push({
                'language': lang,
                'read': read,
                'write': write,
                'speak': speak
            });
        }

        for (j = 1; j <= 5; j++) {
            var PreOrgName = $('#PreOrgName' + j).val();
            var PreOrgCompany = $('#PreOrgCompany' + j).val();
            var PreOrgDesignation = $('#PreOrgDesignation' + j).val();
            var PreOrgEmail = $('#PreOrgEmail' + j).val();
            var PreOrgContact = $('#PreOrgContact' + j).val();
            PerOrgArray.push({
                'PreOrgName': PreOrgName,
                'PreOrgCompany': PreOrgCompany,
                'PreOrgDesignation': PreOrgDesignation,
                'PreOrgEmail': PreOrgEmail,
                'PreOrgContact': PreOrgContact
            });
        }

        for (k = 1; k <= 5; k++) {
            var VnrRefName = $('#VnrRefName' + k).val();
            var VnrRefContact = $('#VnrRefContact' + k).val();
            var VnrRefEmail = $('#VnrRefEmail' + k).val();
            var VnrRefCompany = $('#VnrRefCompany' + k).val();
            var OtherCompany = $('#OtherCompany' + k).val();
            var VnrRefDesignation = $('#VnrRefDesignation' + k).val();
            var VnrRefLocation = $('#VnrRefLocation' + k).val();
            var VnrRefRelWithPerson = $('#VnrRefRelWithPerson' + k).val();
            VnrRefArray.push({
                'VnrRefName': VnrRefName,
                'VnrRefContact': VnrRefContact,
                'VnrRefEmail': VnrRefEmail,
                'VnrRefCompany': VnrRefCompany,
                'OtherCompany': OtherCompany,
                'VnrRefDesignation': VnrRefDesignation,
                'VnrRefLocation': VnrRefLocation,
                'VnrRefRelWithPerson': VnrRefRelWithPerson,
            });
        }

        for (a = 1; a <= 5; a++) {
            var VnrRefBusiness_Name = $('#VnrRefBusiness_Name' + a).val();
            var VnrRefBusiness_Contact = $('#VnrRefBusiness_Contact' + a).val();
            var VnrRefBusiness_Email = $('#VnrRefBusiness_Email' + a).val();
            var VnrRefBusinessRelation = $('#VnrRefBusinessRelation' + a).val();
            var VnrRefBusiness_Location = $('#VnrRefBusiness_Location' + a).val();
            var VnrRefBusiness_RelWithPerson = $('#VnrRefBusiness_RelWithPerson' + a).val();

            VnrBusinessRefArray.push({
                'VnrRefBusiness_Name': VnrRefBusiness_Name,
                'VnrRefBusiness_Contact': VnrRefBusiness_Contact,
                'VnrRefBusiness_Email': VnrRefBusiness_Email,
                'VnrRefBusinessRelation': VnrRefBusinessRelation,
                'VnrRefBusiness_Location': VnrRefBusiness_Location,
                'VnrRefBusiness_RelWithPerson': VnrRefBusiness_RelWithPerson,
            });
        }

        for (b = 1; b <= 5; b++) {
            var OtherSeedName = $('#OtherSeedName' + b).val();
            var OtherSeedMobile = $('#OtherSeedMobile' + b).val();
            var OtherSeedEMail = $('#OtherSeedEMail' + b).val();
            var OtherSeedCompany = $('#OtherSeedCompany' + b).val();
            var OtherSeedDesignation = $('#OtherSeedDesignation' + b).val();
            var OtherSeedLocation = $('#OtherSeedLocation' + b).val();
            var OtherSeedRelation = $('#OtherSeedRelation' + b).val();

            VnrOtherSeedArray.push({
                'OtherSeedName': OtherSeedName,
                'OtherSeedMobile': OtherSeedMobile,
                'OtherSeedEMail': OtherSeedEMail,
                'OtherSeedCompany': OtherSeedCompany,
                'OtherSeedDesignation': OtherSeedDesignation,
                'OtherSeedLocation': OtherSeedLocation,
                'OtherSeedRelation': OtherSeedRelation,

            });
        }
        var res = 0;
        var res = checkRequired_other();
        if (res == 0) {
            var url = '<?= route('SaveOther') ?>';
            $.post(url, {
                    language_array: language_array,
                    PerOrgArray: PerOrgArray,
                    VnrRefArray: VnrRefArray,
                    VnrBusinessRefArray: VnrBusinessRefArray,
                    VnrOtherSeedArray: VnrOtherSeedArray,
                    VNR_Acq: $("input[name='AcqChk']:checked").val(),
                    VNR_Acq_Business: $("input[name='AcqChkBusiness']:checked").val(),
                    OtherSeedRelation: $("input[name='OtherSeedRelation']:checked").val(),
                    JCId: $('#JCId').val()
                },

                function (data) {
                    if (data.status == 200) {
                        toastr.success(data.msg);
                        $('#smartwizard').smartWizard("next");
                        $('.tab-content').height('auto');
                    } else {
                        toastr.error(data.msg);
                    }
                }, 'json');
        } else {
            toastr.error('Please fill all required fields');
        }


    });

    $(document).on('click', '#OfferLtrUpload', function () {
        var JCId = $('#JCId').val();
        var url = '<?= route('OfferLtrFileUpload') ?>';
        var OfferLtr = $('#OfferLtr')[0].files;
        var formData = new FormData();
        formData.append('JCId', JCId);
        formData.append('OfferLtr', OfferLtr[0]);
        $.ajax({
            url: url,
            method: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json',
            beforeSend: function () {
                $('#smartwizard').smartWizard("loader", "show");
            },
            success: function (data) {
                if (data.status == 400) {
                    toastr.error(data.msg);
                    $('#smartwizard').smartWizard("loader", "hide");
                } else {
                    $('#smartwizard').smartWizard("loader", "hide");
                    toastr.success(data.msg);
                    window.location.reload();

                }
            },
            error: function (data) {
                var errors = data.responseJSON;
                var errorsHtml = '';
                $.each(errors.errors, function (key, value) {
                    errorsHtml += value[0] + '<br>';
                });
                toastr.error(errorsHtml);
                $('#smartwizard').smartWizard("loader", "hide");
            }
        });
    });

    $(document).on('click', '#RelievingLtrUpload', function () {
        var JCId = $('#JCId').val();
        var url = '<?= route('RelievingLtrFileUpload') ?>';
        var RelievingLtr = $('#RelievingLtr')[0].files;
        var formData = new FormData();
        formData.append('JCId', JCId);
        formData.append('RelievingLtr', RelievingLtr[0]);
        $.ajax({
            url: url,
            method: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json',
            beforeSend: function () {
                $('#smartwizard').smartWizard("loader", "show");
            },
            success: function (data) {
                if (data.status == 400) {
                    toastr.error(data.msg);
                    $('#smartwizard').smartWizard("loader", "hide");
                } else {
                    $('#smartwizard').smartWizard("loader", "hide");
                    toastr.success(data.msg);
                    window.location.reload();

                }
            },
            error: function (data) {
                var errors = data.responseJSON;
                var errorsHtml = '';
                $.each(errors.errors, function (key, value) {
                    errorsHtml += value[0] + '<br>';
                });
                toastr.error(errorsHtml);
                $('#smartwizard').smartWizard("loader", "hide");
            }
        });
    });

    $(document).on('click', '#SalarySlipUpload', function () {
        var JCId = $('#JCId').val();
        var url = '<?= route('SalarySlipFileUpload') ?>';
        var SalarySlip = $('#SalarySlip')[0].files;
        var formData = new FormData();
        formData.append('JCId', JCId);
        formData.append('SalarySlip', SalarySlip[0]);
        $.ajax({
            url: url,
            method: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json',
            beforeSend: function () {
                $('#smartwizard').smartWizard("loader", "show");
            },
            success: function (data) {
                if (data.status == 200) {
                    $('#smartwizard').smartWizard("loader", "hide");
                    toastr.success(data.msg);
                    window.location.reload();
                } else {
                    toastr.error(data.msg);
                    $('#smartwizard').smartWizard("loader", "hide");
                }

            },
            error: function (data) {
                var errors = data.responseJSON;
                var errorsHtml = '';
                $.each(errors.errors, function (key, value) {
                    errorsHtml += value[0] + '<br>';
                });
                toastr.error(errorsHtml);
                $('#smartwizard').smartWizard("loader", "hide");
            }
        });
    });

    $(document).on('click', '#AppraisalLtrUpload', function () {
        var JCId = $('#JCId').val();
        var url = '<?= route('AppraisalLtrFileUpload') ?>';
        var AppraisalLtr = $('#AppraisalLtr')[0].files;
        var formData = new FormData();
        formData.append('JCId', JCId);
        formData.append('AppraisalLtr', AppraisalLtr[0]);
        $.ajax({
            url: url,
            method: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json',
            beforeSend: function () {
                $('#smartwizard').smartWizard("loader", "show");
            },
            success: function (data) {
                if (data.status == 400) {
                    toastr.error(data.msg);
                    $('#smartwizard').smartWizard("loader", "hide");
                } else {
                    $('#smartwizard').smartWizard("loader", "hide");
                    toastr.success(data.msg);
                    window.location.reload();

                }
            },
            error: function (data) {
                var errors = data.responseJSON;
                var errorsHtml = '';
                $.each(errors.errors, function (key, value) {
                    errorsHtml += value[0] + '<br>';
                });
                toastr.error(errorsHtml);
                $('#smartwizard').smartWizard("loader", "hide");
            }
        });
    });

    $(document).on('click', '#VaccinationCertUpload', function () {
        var JCId = $('#JCId').val();
        var url = '<?= route('VaccinationCertFileUpload') ?>';
        var VaccinationCert = $('#VaccinationCert')[0].files;
        var formData = new FormData();
        formData.append('JCId', JCId);
        formData.append('VaccinationCert', VaccinationCert[0]);
        $.ajax({
            url: url,
            method: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json',
            beforeSend: function () {
                $('#smartwizard').smartWizard("loader", "show");
            },
            success: function (data) {
                if (data.status == 400) {
                    toastr.error(data.msg);
                    $('#smartwizard').smartWizard("loader", "hide");
                } else {
                    $('#smartwizard').smartWizard("loader", "hide");
                    toastr.success(data.msg);
                    window.location.reload();

                }
            },
            error: function (data) {
                var errors = data.responseJSON;
                var errorsHtml = '';
                $.each(errors.errors, function (key, value) {
                    errorsHtml += value[0] + '<br>';
                });
                toastr.error(errorsHtml);
                $('#smartwizard').smartWizard("loader", "hide");
            }
        });
    });

    $(document).on('click', '#save_documents', function () {
        var url = '<?= route('CheckDocumentUpload') ?>';
        $.post(url, {
            JCId: $('#JCId').val()
        }, function (data) {
            if (data.status == 200) {
                toastr.success(data.msg);
                $('#smartwizard').smartWizard("next");
                $('.tab-content').height('auto');
            } else {
                toastr.error(data.msg);
            }
        }, 'json');

    });

    $(document).on('click', '#final_submit', function () {
        var url = '<?= route('FinalSubmitInterviewApplicationForm') ?>';
        $.post(url, {
            JCId: $('#JCId').val()
        }, function (data) {
            if (data.status == 200) {
                toastr.success(data.msg);
                window.location.reload();
            } else {
                toastr.error(data.msg);
            }
        }, 'json');

    });

    function ticksameadd() {
        if ($('#AddChk').prop("checked") == true) {
            $("#PermAddress").val($("#PreAddress").val());
            $("#PermState").val($("#PreState").val());
            $("#PermDistrict").val($("#PreDistrict").val());
            $("#PermCity").val($("#PreCity").val());
            $("#PermPin").val($("#PrePin").val());
        }

    }

    window.onload = (event) => {
        $('.tab-content').height('auto');
    };

    function getOtherInstitute(num) {
        var Collage = $('#Collage' + num).val();
        console.log(Collage);
        if (Collage == '637') {
            $('#OtherInstitute' + num).removeClass('d-none');
            $('.tab-content').height('auto');
        } else {
            $('#OtherInstitute' + num).addClass('d-none');
            $('.tab-content').height('auto');
        }
    }
</script>
</body>

</html>
