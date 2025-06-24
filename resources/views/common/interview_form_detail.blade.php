@php

    use Illuminate\Support\Carbon;

    $sendingId = request()->query('jaid');
    $JAId = base64_decode($sendingId);
    $Rec = DB::table('jobapply')
        ->join('jobcandidates', 'jobapply.JCId', '=', 'jobcandidates.JCId')
        ->leftJoin('jobpost', 'jobapply.JPId', '=', 'jobpost.JPId')
        ->leftJoin('jf_contact_det', 'jobcandidates.JCId', '=', 'jf_contact_det.JCId')
        ->leftJoin('jf_pf_esic', 'jobcandidates.JCId', '=', 'jf_pf_esic.JCId')
        ->where('JAId', $JAId)
        ->select('jobapply.*', 'jobcandidates.*', 'jobpost.Title as JobTitle', 'jobpost.JobCode', 'jf_contact_det.pre_address', 'jf_contact_det.pre_city', 'jf_contact_det.pre_state', 'jf_contact_det.pre_pin', 'jf_contact_det.pre_dist', 'jf_contact_det.perm_address', 'jf_contact_det.perm_city', 'jf_contact_det.perm_state', 'jf_contact_det.perm_pin', 'jf_contact_det.perm_dist', 'jf_contact_det.cont_one_name', 'jf_contact_det.cont_one_relation', 'jf_contact_det.cont_one_number', 'jf_contact_det.cont_two_name', 'jf_contact_det.cont_two_relation', 'jf_contact_det.cont_two_number', 'jf_pf_esic.UAN', 'jf_pf_esic.PFNumber', 'jf_pf_esic.ESICNumber', 'jf_pf_esic.BankName', 'jf_pf_esic.BranchName', 'jf_pf_esic.IFSCCode', 'jf_pf_esic.AccountNumber', 'jf_pf_esic.PAN')
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

    $VnrBusinessRef = DB::table('vnr_business_ref')
        ->where('JCId', $JCId)
        ->get();

    $OtherSeed = DB::table('relation_other_seed_cmp')
        ->where('JCId', $JCId)
        ->get();

    $AboutAns = DB::table('about_answer')
        ->where('JCId', $JCId)
        ->first();

    $lang = DB::table('jf_language')
        ->where('JCId', $JCId)
        ->get();

    $OtherDetail = DB::table('pre_job_details')
        ->where('JCId', $JCId)
        ->first();
    $Year = Carbon::now()->year;
@endphp
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Interview Application Form</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.min.js"></script>
    <link href="https://fonts.cdnfonts.com/css/roboto" rel="stylesheet">
    <script src="{{ URL::to('/') }}/assets/js/jquery.min.js"></script>
    <style>
        body {
            width: 100%;
            height: 100%;
            margin: 0;
            padding: 0;
            background-color: #ffffff;
            font: 14px "roboto";
        }

        * {
            box-sizing: border-box;
            -moz-box-sizing: border-box;
        }



        hr {
            display: block;
            height: 2px;
            background: transparent;
            width: 100%;
            border: none;
            border-top: solid 1px #000000;
        }



        .table tr,
        .table td,
        .table th {
            padding: .25rem;
            vertical-align: top;
            border: 1px black solid;
            height: 30px;
        }
    </style>
</head>

<body>
    <div class="row">
        <p style="margin-bottom: 0px;">Post Applied for (किस पद के लिए आवेदन):
            <span style="font-size: 20px;"> {{ $Rec->JobTitle }}</span>
        </p>
    </div>
    <div class="row">
        <div style="width: 80%;float: left;">
            <div class="col-12">
                <table class="table" style="border: 1px solid black;">
                    <tr style="border: 1px solid black;">
                        <td style="border: 1px solid black;">Name (नाम):</td>
                        <td style="border: 1px solid black; font-weight:bold;">{{ $Rec->Title }} {{ $Rec->FName }}
                            {{ $Rec->MName }}
                            {{ $Rec->LName }}
                        </td>

                    </tr>
                    <tr style="border: 1px solid black;">
                        <td style="border: 1px solid black;">
                            Date of Birth(जन्म तिथि):
                        </td>
                        <td style="border: 1px solid black; font-weight:bold;">

                            {{ date('d-M-Y', strtotime($Rec->DOB)) }}

                        </td>
                    </tr>
                    <tr style="border: 1px solid black;">
                        <td style="border: 1px solid black;">
                            Age(आयु):
                        </td>
                        <td style="border: 1px solid black; font-weight:bold;">

                            {{ \Carbon\Carbon::parse($Rec->DOB)->diff(\Carbon\Carbon::now())->format('%y years, %m months and %d days') }}

                        </td>
                    </tr>
                    <tr style="border: 1px solid black;">
                        <td style="border: 1px solid black;">Gender(लिंग):
                        </td>
                        <td style="border: 1px solid black; font-weight:bold;">

                            @if ($Rec->Gender == 'M')
                                Male
                            @elseif($Rec->Gender == 'F')
                                Female
                            @else
                                Other
                            @endif

                        </td>
                    </tr>
                    <tr style="border: 1px solid black;">
                        <td style="border: 1px solid black;">
                            Nationality(राष्ट्रीयता):
                        </td>
                        <td style="border: 1px solid black;font-weight:bold;">

                            @if ($Rec->Nationality == 1)
                                Indian
                            @else
                                Other
                            @endif

                        </td>
                    </tr>
                    <tr style="border: 1px solid black;">
                        <td style="border: 1px solid black;">Religion (धर्म):</td>
                        <td style="border: 1px solid black;font-weight:bold;">

                            @if ($Rec->Religion == 'Others')
                                {{ $Rec->OtherReligion }}
                            @else
                                {{ $Rec->Religion }}
                            @endif

                        </td>
                    </tr>
                    <tr style="border: 1px solid black;">
                        <td style="border: 1px solid black;">*Category(वर्ग)</td>
                        <td style="border: 1px solid black;font-weight:bold;">

                            @if ($Rec->Caste == 'Other')
                                {{ $Rec->OtherCaste }}
                            @else
                                {{ $Rec->Caste }}
                            @endif


                        </td>
                    </tr>
                    <tr style="border: 1px solid black;">
                        <td style="border: 1px solid black;">
                            Marital Status(वैवाहिक स्थिति):
                        </td>
                        <td style="border: 1px solid black;font-weight:bold;">

                            {{ $Rec->MaritalStatus }}

                        </td>
                    </tr>




                </table>
            </div>
        </div>
        <div style="width: 20%;float: left;margin-left:20px;">
            @if ($Rec->CandidateImage == null)
                <img src="{{ URL::to('/') }}/assets/images/user1.png" width="100" height="140" />
            @else
                <img src="{{ Storage::disk('s3')->url('Recruitment/Picture/' . $Rec->CandidateImage) }}" width="100"
                    height="140" />
            @endif
        </div>
    </div>
    <p>*Information collected here is for Govt. Statistical data use only. यहां एकत्रित की गई जानकारी केवल
        सरकारी सांख्यिकीय डेटा के उपयोग हेतु है।</p>
    <p class="fw-bold">Contact Details (संपर्क विवरण) :</p>
    <div class="row">
        <div class="col-12">
            <table class="table table-borderless">

                <tr style="border: 1px solid black;">
                    <td colspan="2"><span class="font-weight-bold">E-mail ID (ई- मेल) : </span>
                        <span style="font-weight: bold">
                            {{ $Rec->Email }} @if ($Rec->Email2 != null)
                                , {{ $Rec->Email2 }}
                            @endif
                        </span>
                    </td>
                </tr>
                <tr style="border: 1px solid black;">
                    <td colspan="2"><span class="font-weight-bold">Mobile /Phone No(मोबाइल / दूरभाष संख्या)
                            :
                        </span><span style="font-weight: bold">
                            {{ $Rec->Phone }} @if ($Rec->Phone2 != null)
                                , {{ $Rec->Phone2 }}
                            @endif
                        </span>
                    </td>
                </tr>
            </table>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <table class="table" style="border: 1px solid black;">
                <tr style="border: 1px solid black;">
                    <td class="text-center fw-bold" style="border: 1px solid black;">Present Address <br> पत्र व्यव्हार
                        का पता</td>
                    <td class="text-center fw-bold">Permanent Address <br>स्थायी पता</td>
                </tr>
                <tr style="border: 1px solid black;">
                    <td style="border: 1px solid black;">
                        {{ $Rec->pre_address }}, {{ $Rec->pre_city }},
                        {{ getDistrictName($Rec->pre_dist) }}, {{ getStateName($Rec->pre_state) }},
                        {{ $Rec->pre_pin ?? '-' }}
                    </td>
                    <td style="border: 1px solid black;">
                        {{ $Rec->perm_address }}, {{ $Rec->perm_city }},
                        {{ getDistrictName($Rec->perm_dist) }}, {{ getStateName($Rec->perm_state) }},
                        {{ $Rec->perm_pin }}
                    </td>
                </tr>


            </table>
        </div>
    </div>
    <div class="row">
        <table class="table">
            <tr>
                <td>Total Experience:</td>
                <td>
                    @if ($Rec->Professional == 'P')
                        {{ $Rec->TotalYear }}
                        Years {{ $Rec->TotalMonth }} Months
                    @else
                        Fresher
                    @endif
                </td>
            </tr>
            <tr>
                <td>कुल कार्य अनुभव :</td>
                <td>
                    @if ($Rec->Professional == 'P')
                        {{ $Rec->TotalYear }} साल {{ $Rec->TotalMonth }} महीना
                    @endif
                </td>
            </tr>
        </table>
    </div>

    @if ($Rec->Reference == 'Y')
        <div class="row">
            <div class="col-12">
                <p style="margin-bottom: 0px;font-weight:bold;"><b>Reference Details:</b></p>
                <table class="table table-borderless">
                    <tr style="border: 1px solid black;">
                        <th style="border: 1px solid black;">Name</th>
                        <th style="border: 1px solid black;">Company</th>
                        <th style="border: 1px solid black;">Designation</th>
                        <th style="border: 1px solid black;">Contact No</th>
                        <th style="border: 1px solid black;">Email ID</th>
                    </tr>
                    <tr style="border: 1px solid black;">
                        <td style="border: 1px solid black;">{{ $Rec->RefPerson }}</td>
                        <td style="border: 1px solid black;">{{ $Rec->RefCompany }}</td>
                        <td style="border: 1px solid black;">{{ $Rec->RefDesignation }}</td>
                        <td style="border: 1px solid black;">{{ $Rec->RefContact }}</td>
                        <td style="border: 1px solid black;">{{ $Rec->RefMail }}</td>
                    </tr>

                </table>
            </div>
        </div>
    @endif
    <div class="row">
        <div class="col-12">
            <p style="margin-bottom: 0px;"><b>Please mention the source through which you came to know
                    about this job opening:</b></p>
            <p style="marign-bottom:0px;">कृपया उस स्त्रोत का नाम बताये जहाँ से आपको इस नौकरी के विषय में
                पता चला:</p>
            <div class="row">
                <div class="col-4">
                    <input type="radio" @if ($Rec->ResumeSource == 1) checked="checked" @endif> &emsp; Company
                    Career
                    Site
                </div>
                <div class="col">
                    <input type="radio" @if ($Rec->ResumeSource == 2) checked="checked" @endif> &emsp;
                    Naukari.com
                </div>
                <div class="col">
                    <input type="radio" @if ($Rec->ResumeSource == 3) checked="checked" @endif> &emsp; LinkedIn
                </div>
                <div class="col">
                    <input type="radio" @if ($Rec->ResumeSource == 4) checked="checked" @endif> &emsp; Walk-in
                </div>
            </div>
            <div class="row">
                <div class="col-4">
                    <input type="radio" @if ($Rec->ResumeSource == 5) checked="checked" @endif> &emsp; Ref. from
                    VNR
                    Employee
                </div>
                <div class="col-4">
                    <input type="radio" @if ($Rec->ResumeSource == 6) checked="checked" @endif> &emsp; Placement
                    Agencies
                </div>
                <div class="col-4">
                    <input type="radio" @if ($Rec->ResumeSource == 8) checked="checked" @endif> &emsp;
                    Any other
                </div>
            </div>
            <p style="margin-top: 10px;font-weight:bold">* Please provide Name & Contact nos. of person, if came through
                any referral or Consultancy:
            </p>
            <p style="font-weight: bold"> {{ $Rec->OtherResumeSource }}</p>
        </div>
    </div>
    <!--page break-->
    <p style="page-break-after: always;">&nbsp;</p>

    <div class="row">
        <p style="font-weight: bold">Details of Current Employment (वर्तमान नौकरी का विवरण)</p>
        <div class="col-12">
            <table class="table" style="border: 1px solid black;">
                <tr style="border: 1px solid black;">
                    <td style="border: 1px solid black;width:70%;">Name of Company (नियोक्ता / कंपनी
                        का नाम
                        ):

                    </td>
                    <td style="font-weight: bold">
                        @if ($Rec->Professional == 'P')
                            {{ $Rec->PresentCompany }}
                        @else
                            Fresher
                        @endif
                    </td>
                </tr>
                <tr style="border: 1px solid black;">
                    <td style="border: 1px solid black;width:60%;">Date of Joining (कार्यग्रहण तिथि ):


                    </td>
                    <td style="font-weight: bold">
                        @if ($Rec->Professional == 'P')
                            {{ date('d-m-Y', strtotime($Rec->JobStartDate)) }}
                        @endif

                    </td>
                </tr>
                <tr style="border: 1px solid black;">
                    <td style="border: 1px solid black;width:60%;">Designation (पद ):

                    </td>
                    <td style="font-weight: bold">
                        {{ $Rec->Designation }}
                    </td>
                </tr>

                <tr style="border: 1px solid black;">
                    <td style="border: 1px solid black;width:60%;">Annual Package(CTC) (वेतन सालाना):
                    </td>
                    <td style="font-weight: bold">

                        {{ $Rec->CTC ?? '' }}

                    </td>
                </tr>
                <tr style="border: 1px solid black;">
                    <td style="border: 1px solid black;width:60%;">Salary (Per Month)(वेतन मासिक):


                    </td>
                    <td style="font-weight: bold">

                        {{ $Rec->GrossSalary ?? '' }}

                    </td>
                </tr>

                <tr style="border: 1px solid black;">
                    <td style="border: 1px solid black;width:60%;">Notice Period in Current Organization <br> (वर्तमान
                        कंपनी में कार्य छोड़ने का समय):

                    </td>
                    <td style="font-weight: bold">

                        {{ $Rec->NoticePeriod ?? '' }}


                    </td>
                </tr>
                <tr style="border: 1px solid black;">
                    <td style="border: 1px solid black;width:70%;">State the reason for which you are seeking for the
                        job
                        change (उन कारणों का विवरण दे जिनके कारन आप नए नौकरियों के अवसर तलाश रहे हैं):


                    </td>
                    <td style="font-weight: bold">
                        {{ $Rec->ResignReason }}
                    </td>
                </tr>
                {{--<tr style="border: 1px solid black;">
                    <td style="border: 1px solid black;width:60%;">Expected Annual Package(CTC) (अपेक्षित सालाना वेतन):


                    </td>
                    <td style="font-weight: bold">
                        {{ $Rec->ExpectedCTC }}
                    </td>
                </tr>--}}

            </table>
        </div>
    </div>

    <div class="row mb-1">
        <p style="margin-bottom:5px;font-weight:bold"><b>Present job responsibilities, in brief(वर्तमान कार्य का
                संक्षिप्त वर्णन):</b></p>
        <div class="col-12">
            <u> {{ Str::limit($Rec->JobResponsibility, 1000) }}</u>

        </div>
    </div>

    <div class="row">
        <p style="margin-bottom: 0px;font-weight: bold;"><b>Other allowances details (to be filled accurately)*</b></p>
        <p>अन्य भत्तों का वर्णन (कृपया सही विवरण दे)*</p>
        <div class="col-md-12 ">
            <table class="table" style="border: 1px solid black;">

                <tr style="border: 1px solid black;">
                    <td style="border: 1px solid black;font-weight: bold;">DA@ headquarter</td>
                    <td style="border: 1px solid black;font-weight: bold;">DA Outside headquarter</td>
                    <td style="border: 1px solid black;font-weight: bold;">Petrol Allowances</td>

                    <td style="border: 1px solid black;font-weight: bold">Hotel Eligibility</td>
                </tr>
                <tr style="height:30px;">
                    <td style="border: 1px solid black;">
                        {{ $Rec->DAHq }}
                    </td>
                    <td style="border: 1px solid black;">
                        {{ $Rec->DAOutHq }}
                    </td>
                    <td style="border: 1px solid black;">
                        {{ $Rec->PetrolAlw }}
                    </td>

                    <td style="border: 1px solid black;">
                        {{ $Rec->HotelElg }}
                    </td>
                </tr>
            </table>
        </div>
    </div>

    <div class="row">
        <p style="margin-bottom: 0px;font-weight: bold;"><b>Previous Employment Records:</b> अन्य कार्योनुभव के विवरण
            (वर्तमान को छोड़कर )
        </p>
        <div class="col-md-12">

            <table class="table table-borderless">
                <thead>
                    <tr style="border: 1px solid black;">
                        <th style="border: 1px solid black;">Name of the Employer</th>
                        <th style="border: 1px solid black;">Designation</th>
                        <th style="border: 1px solid black;">Job Start Date</th>
                        <th style="border: 1px solid black;">Job End Date</th>
                        <th style="border: 1px solid black;">Gross Monthly Salary</th>
                        <th style="border: 1px solid black;">Annual CTC</th>
                        <th style="border: 1px solid black;">Reason for Leave</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($Experience as $item)
                        <tr style="border: 1px solid black;">
                            <td style="border: 1px solid black;">{{ $item->company }}</td>
                            <td style="border: 1px solid black;">{{ $item->desgination }}</td>
                            <td style="border: 1px solid black;">{{ $item->job_start }}</td>
                            <td style="border: 1px solid black;">{{ $item->job_end }}</td>
                            <td style="border: 1px solid black;">{{ $item->gross_mon_sal }}</td>
                            <td style="border: 1px solid black;">{{ $item->annual_ctc }}</td>
                            <td style="border: 1px solid black;">{{ $item->reason_fr_leaving }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <p style="font-weight:bold "> Educational Details (शैक्षाणिक योग्यताये):</p>
            <table class="table" style="border: 1px solid black;">
                <thead>
                    <tr style="border: 1px solid black;">
                        <th style="border: 1px solid black;">Qualification</th>
                        <th style="border: 1px solid black;">Year of Passing</th>
                        <th style="border: 1px solid black;">%</th>
                        <th style="border: 1px solid black;">University / College</th>
                        <th style="border: 1px solid black;">Course</th>
                        <th style="border: 1px solid black;">Specialization</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($Education as $item)
                        <tr style="border: 1px solid black;">
                            <td style="border: 1px solid black;">{{ $item->Qualification }}</td>

                            <td style="border: 1px solid black;">{{ $item->YearOfPassing ?? '-' }}</td>
                            <td style="border: 1px solid black;">{{ $item->CGPA ?? '-' }}</td>
                            <td style="border: 1px solid black;">
                                @if ($item->Institute != null)
                                    {{ getCollegeById($item->Institute) }}
                                    @if ($item->Institute == 637)
                                        ({{ $item->OtherInstitute ?? '-' }})
                                    @endif
                                @else
                                    -
                                @endif
                            </td>
                            <td style="border: 1px solid black;">
                                @if ($item->Course != null)
                                    {{ getEducationCodeById($item->Course) }}
                                @else
                                    -
                                @endif
                            </td>
                            <td style="border: 1px solid black;">
                                @if (is_null($item->Specialization))
                                    -
                                @else
                                    {{ getSpecializationbyId($item->Specialization) }}
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
  {{--  <p style="page-break-after: always;">&nbsp;</p>
--}}
    <div class="row">
        <div class="table-responsive col-lg-12">
            <p style="font-weight: bold;margin-bottom: 5px;">Language Proficiency :</p>
            <table class="table">
                <thead>
                    <tr style="border: 1px solid black;">
                        <td style="border: 1px solid black;text-align:center;font-weight:bold;">Language</td>
                        <td style="border: 1px solid black;text-align:center;font-weight:bold;">Reading</td>
                        <td style="border: 1px solid black;text-align:center;font-weight:bold;">Writing</td>
                        <td style="border: 1px solid black;text-align:center;font-weight:bold;">Speaking</td>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($lang as $item)
                        <tr style="border: 1px solid black;">
                            <td style="border: 1px solid black;text-align:center;">{{ $item->language }}</td>
                            <td style="border: 1px solid black;text-align:center;">
                                {{ $item->read == 1 ? 'Yes' : 'No' }}</td>
                            <td style="border: 1px solid black;text-align:center;">
                                {{ $item->write == 1 ? 'Yes' : 'No' }}</td>
                            <td style="border: 1px solid black;text-align:center;">
                                {{ $item->speak == 1 ? 'Yes' : 'No' }}</td>
                        </tr>
                    @endforeach
                </tbody>
                </tbody>
            </table>
        </div>

        <div class="col-md-12">
            <p style="margin-bottom:0px; font-weight:bold;"><b>Family Details(परिवार का विवरण )</b></p>
            <table class="table table-borderless">
                <thead>
                    <tr style="border: 1px solid black;text-align: center">
                        <th style="border: 1px solid black;text-align: center">Relationship</th>
                        <th style="border: 1px solid black;text-align: center">Name</th>
                        <th style="border: 1px solid black;text-align: center">Date of Birth</th>
                        <th style="border: 1px solid black;text-align: center">Qualification</th>
                        <th style="border: 1px solid black;text-align: center">Occupation</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($FamilyInfo as $item)
                        <tr style="border: 1px solid black;">
                            <td style="border: 1px solid black;">{{ $item->relation }}</td>
                            <td style="border: 1px solid black;">{{ $item->name }}</td>
                            <td style="border: 1px solid black;">{{ date('d-M-Y', strtotime($item->dob)) }}</td>
                            <td style="border: 1px solid black;">{{ $item->qualification }}</td>
                            <td style="border: 1px solid black;">{{ $item->occupation }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <p style="font-weight: bold;">About Yourself:</p>
    <div class="row">
        <div class="col-lg-12">
            <p style="font-weight: bold;text-align: justify;margin-bottom: 5px;">a) What is your aim in life?</p>
            <p style="margin-left: 20px;">{{ $AboutAns->AboutAim ?? '' }}</p>

            <p style="font-weight: bold;text-align: justify;margin-bottom: 5px;">b) What are your hobbies and interest?
            </p>
            <p style="margin-left: 20px;">{{ $AboutAns->AboutHobbi ?? '' }}</p>

            <p style="font-weight: bold;text-align: justify;margin-bottom: 5px;">c) Where do you see yourself 5 Years
                from now?</p>
            <p style="margin-left: 20px;">{{ $AboutAns->About5Year ?? '' }}</p>

            <p style="font-weight: bold;text-align: justify;margin-bottom: 5px;">d) What are your greatest personal
                assets (qualities,
                skills,
                abilities) which make you successful in the jobs you take up?</p>
            <p style="margin-left: 20px;">{{ $AboutAns->AboutAssets ?? '' }}</p>

            <p style="font-weight: bold;text-align: justify;margin-bottom: 5px;">e) What are your Strengths?</p>
            <p style="margin-left: 20px;">{{ $AboutAns->AboutStrength ?? '' }}
            </p>

            <p style="font-weight: bold;text-align: justify;margin-bottom: 5px;">f) What are your areas where you think
                you need to improve yourself?
            </p>
            <p style="margin-left: 20px;">{{ $AboutAns->AboutImprovement ?? '' }}
            </p>

            <p style="font-weight: bold;text-align: justify;margin-bottom: 5px;">g) In the past or at present, have/are
                you suffered
                /suffering from,
                any form of physical disability or any minor or major illness or deficiency?</p>
            <p style="margin-left: 20px;">{{ $AboutAns->AboutDeficiency ?? '' }}
            </p>

            <p style="font-weight: bold;text-align: justify;margin-bottom: 5px;">h) Have You Been criminally
                prosecuted? </p>
            <p class=" mb-0" style="margin-left: 20px;">
                {{ $AboutAns != null && $AboutAns->CriminalChk == 'Y' ? 'Yes' : 'No' }}

                @if ($AboutAns != null && $AboutAns->AboutCriminal == 'Y')
                    <p style="margin-left: 20px;">
                        {{ $AboutAns->AboutCriminal ?? '' }}
                    </p>
                @endif


            <p style="font-weight: bold;text-align: justify;margin-bottom: 5px;">i) Do you have a valid driving
                licence? </p>
            <p class=" mb-0" style="margin-left: 20px;">
                {{ $AboutAns != null && $AboutAns->LicenseChk == 'Y' ? 'Yes' : 'No' }}



                @if ($AboutAns != null && $AboutAns->LicenseChk == 'Y')
                    <p style="margin-left: 20px;"><span class="fw-bold">Drivining
                            License:</span>
                        {{ $AboutAns->DLNo ?? '' }} <span style="margin-left: 20px;" class="fw-bold">Validity:
                            {{ $AboutAns->LValidity ?? '' }}</span></p>
                @endif

        </div>
    </div>

    <p style="page-break-after: always;">&nbsp;</p>
    <div class="row" style="margin-bottom:0px; marign-top:50px;">
        <p style="font-weight: bold;margin-bottom:5px;">Other Info:</p>
        @if ($Rec->Professional == 'P')
            <div class="col-12">
                <p style="margin-bottom: 0px;font-weight:bold;"><b>Please give the reference who had worked with
                        you in
                        the previous organization </b></p>
                <table class="table" style="border: 1px solid black;">
                    <thead>
                        <tr style="border: 1px solid black;">
                            <th style="border: 1px solid black;text-align:center">Name</th>
                            <th style="border: 1px solid black;text-align:center">Company</th>
                            <th style="border: 1px solid black;text-align:center">Designation</th>
                            <th style="border: 1px solid black;text-align:center">Contact</th>
                            <th style="border: 1px solid black;text-align:center">Email</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($PreRef as $item)
                            <tr style="border: 1px solid black;">
                                <td style="border: 1px solid black;">{{ $item->name }}</td>
                                <td style="border: 1px solid black;">{{ $item->company }}</td>
                                <td style="border: 1px solid black;">{{ $item->designation }}</td>
                                <td style="border: 1px solid black;">{{ $item->contact }}</td>
                                <td style="border: 1px solid black;">{{ $item->email }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        <div class="col-12">
            <p style="margin-bottom: 0px;font-weight: bold;"><b>Do you have any acquaintances or relatives working
                    with
                    VNR Group Companies?</b></p>
            <p class="mb-0">क्या आपका कोई परिचित या रिश्तेदार वीएनआर ग्रुप कंपनियों के साथ
                काम कर रहा है? :</p>
            <p class="mb-0" style="margin-left: 20px;">
                {{ $Rec != null && $Rec->VNR_Acq == 'Y' ? 'Yes' : 'No' }}
                @if ($Rec->VNR_Acq == 'Y')
                    <table class="table" style="width:100%;border: 1px solid black;">
                        <thead>
                            <tr style="border: 1px solid black;">
                                <th style="border: 1px solid black;">Name</th>
                                <th style="border: 1px solid black;">Mobile </th>
                                <th style="border: 1px solid black;">Email</th>
                                <th style="border: 1px solid black;">VNR Group /<br>Company Name</th>
                                <th style="border: 1px solid black;">Designation</th>
                                <th style="border: 1px solid black;">Location</th>
                                <th style="border: 1px solid black;">Your Relationship <br>with person mentioned
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($VnrRef as $item)
                                <tr style="border: 1px solid black;">
                                    <td style="border: 1px solid black;">{{ $item->name }}</td>
                                    <td style="border: 1px solid black;">{{ $item->contact }}</td>
                                    <td style="border: 1px solid black;">{{ $item->email }}</td>
                                    <td style="border: 1px solid black;">{{ $item->company }}
                                        {{ $item->company == 'Other' ? '/ ' . $item->other_company : '' }}
                                    </td>
                                    </td>
                                    <td style="border: 1px solid black;">{{ $item->designation }}</td>
                                    <td style="border: 1px solid black;">{{ $item->location }}</td>
                                    <td style="border: 1px solid black;">{{ $item->rel_with_person }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif

        </div>

        <div class="col-12">
            <p style="margin-bottom: 0px;font-weight: bold"><b>Do you have any acquaintances or relatives
                    associated with
                    VNR as business associates (like Dealer, Distributor, Retailer, Organizer, Vendor
                    etc.)?</b></p>
            <p class="mb-0">क्या आपका कोई परिचित या रिश्तेदार VNR से व्यावसायिक सहयोगी (जैसे
                डीलर, वितरक, खुदरा विक्रेता, आयोजक, विक्रेता आदि) के रूप में जुड़ा है?:</p>
            <p class="mb-0" style="margin-left: 20px;">
                {{ $Rec != null && $Rec->VNR_Acq_Business == 'Y' ? 'Yes' : 'No' }}
                @if ($Rec->VNR_Acq_Business == 'Y')
                    <table class="table" style="width:100%;border: 1px solid black;">
                        <thead>
                            <tr style="border: 1px solid black;">
                                <th style="border: 1px solid black;">Name</th>
                                <th style="border: 1px solid black;">Mobile </th>
                                <th style="border: 1px solid black;">Email</th>
                                <th style="border: 1px solid black;">Business Relation <br>With VNR</th>
                                <th style="border: 1px solid black;">Location of Business /
                                    acquaintances</th>
                                <th style="border: 1px solid black;">Your Relationship <br>with person mentioned
                                </th>
                            </tr>
                        </thead>
                        <tbody style="border: 1px solid black;">
                            @foreach ($VnrBusinessRef as $item)
                                <tr>
                                    <td style="border: 1px solid black;">{{ $item->Name ?? '' }}</td>
                                    <td style="border: 1px solid black;">{{ $item->Mobile ?? '' }}</td>
                                    <td style="border: 1px solid black;">{{ $item->Email ?? '' }}</td>
                                    <td style="border: 1px solid black;">{{ $item->BusinessRelation ?? '' }}</td>
                                    </td>
                                    <td style="border: 1px solid black;">{{ $item->Location ?? '' }}</td>
                                    <td style="border: 1px solid black;">{{ $item->PersonRelation ?? '' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif

        </div>

        <div class="col-12">
            <p style="margin-bottom: 0px; font-weight: bold;"><b>Is any of your relatives or acquaintances is/are
                    working
                    or associated with any other Seed Company?</b></p>
            <p class="mb-0">क्या आपका कोई रिश्तेदार या परिचित किसी अन्य सीड कंपनी से जुड़ा
                है/काम कर रहा है?</p>
            <p class="mb-0" style="margin-left: 20px;">
                {{ $Rec != null && $Rec->OtherSeedRelation == 'Y' ? 'Yes' : 'No' }}
                @if ($Rec->OtherSeedRelation == 'Y')
                    <table class="table" style="width:100%;border:1px solid black;">
                        <thead>
                            <tr class="text-center">
                                <th style="border: 1px solid black;">Name</th>
                                <th style="border: 1px solid black;">Mobile </th>
                                <th style="border: 1px solid black;">Email</th>
                                <th style="border: 1px solid black;">Company Name</th>
                                <th style="border: 1px solid black;">Designation</th>
                                <th style="border: 1px solid black;">Location</th>
                                <th style="border: 1px solid black;">Your Relationship <br>with person mentioned
                                </th>
                            </tr>
                        </thead>
                        <tbody class="text-center">
                            @foreach ($OtherSeed as $item)
                                <tr>
                                    <td style="border: 1px solid black;">{{ $item->Name ?? '' }}</td>
                                    <td style="border: 1px solid black;">{{ $item->Mobile ?? '' }}</td>
                                    <td style="border: 1px solid black;">{{ $item->Email ?? '' }}</td>
                                    <td style="border: 1px solid black;">{{ $item->company_name ?? '' }}</td>
                                    <td style="border: 1px solid black;">{{ $item->Designation ?? '' }}</td>
                                    <td style="border: 1px solid black;">{{ $item->Location ?? '' }}</td>
                                    <td style="border: 1px solid black;">{{ $item->Relation ?? '' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif

        </div>
    </div>

    @if ($Rec->Professional == 'P' && $Rec->Department == 6)
        <p style="page-break-after: always;">&nbsp;</p>
        <div class="row" style="margin-bottom:0px; marign-top:50px;">
            <div class="col-lg-12">
                <p style="font-weight: bold;">Reporting Details:</p>
                <table class="table">
                    <tr style="border: 1px solid black;">
                        <td style="width: 50%;border: 1px solid black; font-weight: bold;">Reporting Manager Name</td>
                        <td style="border: 1px solid black;">{{ $Rec->Reporting ?? '' }}</td>
                    </tr>
                    <tr style="border: 1px solid black;">
                        <td style="border: 1px solid black; font-weight: bold;">Reporting Manager's Designation</td>
                        <td style="border: 1px solid black;">{{ $Rec->RepDesig ?? '' }}</td>
                    </tr>
                </table>

                <table class="table" style="border: 1px solid black;">
                    <tr style="border: 1px solid black;">
                        <td rowspan="2" style="border: 1px solid black; width:50%;font-weight: bold;"
                            class="fw-bold">No of employees <br>
                            directly reporting to you</td>
                        <td style="border: 1px solid black;font-weight: bold;">On roll employees</td>
                        <td style="border: 1px solid black;font-weight: bold;">Third party employees</td>
                    </tr>
                    <tr>

                        <td style="border: 1px solid black;text-align: center;">
                            {{ $OtherDetail->OnRollRepToMe ?? '' }}</td>
                        <td style="border: 1px solid black;text-align: center;">
                            {{ $OtherDetail->ThirdPartyRepToMe ?? '' }}
                        </td>

                    </tr>

                </table>
            </div>


            <div class="col-lg-12">
                <p style="font-weight: bold;margin-bottom: 5px;">Working Territory Details (mention the name of
                    District or Area's
                    Covered)</p>
                <p>{{ $OtherDetail->TerritoryDetails ?? '' }}</p>
            </div>

            <div class="col-lg-12">
                <p style="font-weight: bold;margin-bottom: 5px;">Business Turnover Details:</p>
                <table class="table text-center" style="border: 1px solid black;">
                    <tr style="border: 1px solid black;">
                        <th style="border: 1px solid black;text-align: center;">Business Turnover</th>
                        <th style="border: 1px solid black;text-align: center;">Current Year <br>(in lakh's)</th>
                        <th style="border: 1px solid black;text-align: center;">Previous Year <br>(in lakh's)</th>
                    </tr>
                    <tr style="border: 1px solid black;">
                        <td class="fw-bold">Vegetable Business</td>
                        <td style="border: 1px solid black;">{{ $OtherDetail->VegCurrTurnOver ?? '' }}</td>
                        <td style="border: 1px solid black;">{{ $OtherDetail->VegPreTurnOver ?? '' }}</td>
                    </tr>
                    <tr style="border: 1px solid black;">
                        <td class="fw-bold">Field Crop Business</td>
                        <td style="border: 1px solid black;">{{ $OtherDetail->FieldCurrTurnOver ?? '' }}</td>
                        <td style="border: 1px solid black;">{{ $OtherDetail->FieldPreTurnOver ?? '' }}</td>
                    </tr>
                </table>
            </div>

            <div class="col-lg-12">
                <p style="font-weight:bold;margin-bottom:4px;">Incentive Plan Details:</p>
                <table class="table text-center" style="border: 1px solid black;">
                    <tr style="border: 1px solid black;">
                        <th style="border: 1px solid black;text-align: center;">Incentive Payment Duration</th>
                        <th style="border: 1px solid black;text-align: center;">Incentive Amount (in Rs.)</th>
                    </tr>
                    <tr style="border: 1px solid black;">
                        <td class="fw-bold">Monthly</td>
                        <td style="border: 1px solid black;">{{ $OtherDetail->MonthlyIncentive ?? '' }}</td>
                    </tr>
                    <tr style="border: 1px solid black;">
                        <td class="fw-bold">Quarterly</td>
                        <td style="border: 1px solid black;">{{ $OtherDetail->QuarterlyIncentive ?? '' }}</td>
                    </tr>
                    <tr style="border: 1px solid black;">
                        <td class="fw-bold">Half Yearly</td>
                        <td style="border: 1px solid black;">{{ $OtherDetail->HalfYearlyIncentive ?? '' }}</td>
                    </tr>
                    <tr style="border: 1px solid black;">
                        <td class="fw-bold">Annually</td>
                        <td style="border: 1px solid black;">{{ $OtherDetail->AnnuallyIncentive ?? '' }}</td>
                    </tr>
                </table>
            </div>

            <p style="font-weight:bold;margin-bottom:4px;">Any other details related to incentive plan:</p>
            <p>{{ $OtherDetail->AnyOtheIncentive }}</p>

            <div class="row">
                <div class="col-lg-12">
                    <p style="font-weight:bold;">Company Vehicle Policy (select whichever is appliable to
                        you):
                    </p>

                    @if ($OtherDetail->TwoWheelChk != null && $OtherDetail->TwoWheelChk == '1')

                        <span style="font-weight: bold;"> 2 Wheeler</span>
                        <table class="table">
                            <tr style="border: 1px solid black;">
                                <td style="border: 1px solid black;">Ownership Type</td>
                                <td style="border: 1px solid black;">
                                    {{ $OtherDetail->TwoWheelOwnerType == 'W' ? 'Own' : 'Provided by Company' }}
                                    @if ($OtherDetail->TwoWheelOwnerType == 'C')
                                        - Rs. {{ $OtherDetail->TwoWheelAmount ?? '' }}
                                    @endif
                                </td>
                            </tr>

                            <tr style="border: 1px solid black;">
                                <td style="border: 1px solid black;">Petrol Allowances</td>
                                <td style="border: 1px solid black;">
                                    Rs. {{ $OtherDetail->TwoWheelPetrol ?? '' }}
                                    {{ $OtherDetail->TwoWheelPetrolTerm ?? '' }}
                                </td>
                            </tr>
                        </table>
                    @endif

                    @if ($OtherDetail->FourWheelChk != null && $OtherDetail->FourWheelChk == '1')
                        <span style="font-weight: bold;"> 4 Wheeler</span>
                        <table class="table">
                            <tr style="border: 1px solid black;">
                                <td style="border: 1px solid black;">Ownership Type</td>
                                <td style="border: 1px solid black;">
                                    {{ $OtherDetail->FourWheelOwnerType == 'W' ? 'Own' : 'Provided by Company' }}
                                    @if ($OtherDetail->FourWheelOwnerType == 'C')
                                        - Rs. {{ $OtherDetail->FourWheelAmount ?? '' }}
                                    @endif
                                </td>
                            </tr>

                            <tr style="border: 1px solid black;">
                                <td style="border: 1px solid black;">Petrol Allowances</td>
                                <td style="border: 1px solid black;">
                                    Rs. {{ $OtherDetail->FourWheelPetrol ?? '' }}
                                    {{ $OtherDetail->FourWheelPetrolTerm ?? '' }}
                                </td>
                            </tr>
                        </table>
                    @endif

                </div>
            </div>
        </div>





        <div class="row" style="margin-bottom:0px; marign-top:50px;">
            <div class="row">
                <div class="col-lg-12">
                    <p style="font-weight:bold;margin-bottom:4px;">Other Benefit details (select whichever is
                        applicable and
                        mention
                        the details):</p>
                    <table class="table text-center" style="border: 1px solid black">
                        <tr style="border: 1px solid black;">
                            <th style="border: 1px solid black;text-align: center;">Particulars</th>
                            <th style="border: 1px solid black;text-align: center;">Amount</th>
                        </tr>
                        <tr style="border: 1px solid black;">
                            <td style="border: 1px solid black;">DA @ Headquarter</td>
                            <td style="border: 1px solid black;">Rs. {{ $Rec->DAHq ?? '' }}/- Per Day </td>
                        </tr>
                        <tr style="border: 1px solid black;">
                            <td style="border: 1px solid black;">DA outside Headquarter</td>
                            <td style="border: 1px solid black;">Rs. {{ $Rec->DAOutHq ?? '' }}/- Per Day </td>
                        </tr>
                        <tr style="border: 1px solid black;">
                            <td style="border: 1px solid black;">Lodging eligibility</td>
                            <td style="border: 1px solid black;">Rs. {{ $Rec->HotelElg ?? '' }}/- Per Day </td>
                        </tr>
                        <tr style="border: 1px solid black;">
                            <td style="border: 1px solid black;">Medical Insurance</td>
                            <td style="border: 1px solid black;">Rs. {{ $Rec->Medical ?? '' }} </td>
                        </tr>
                        <tr style="border: 1px solid black;">
                            <td style="border: 1px solid black;">Group Term Life Insurance</td>
                            <td style="border: 1px solid black;">Rs. {{ $Rec->GrpTermIns ?? '' }} </td>
                        </tr>
                        <tr style="border: 1px solid black;">
                            <td style="border: 1px solid black;">Group Accidental Insurance</td>
                            <td style="border: 1px solid black;">Rs. {{ $Rec->GrpPersonalAccIns ?? '' }} </td>
                        </tr>
                        <tr style="border: 1px solid black;">
                            <td style="border: 1px solid black;">Mobile Handset</td>
                            <td style="border: 1px solid black;">Rs. {{ $Rec->MobileHandset ?? '' }} </td>
                        </tr>
                        <tr style="border: 1px solid black;">
                            <td style="border: 1px solid black;">Mobile Bill reimbursement</td>
                            <td style="border: 1px solid black;">Rs. {{ $Rec->MobileBill ?? '' }} </td>
                        </tr>
                        <tr style="border: 1px solid black;">
                            <td style="border: 1px solid black;">Travel eligibilities</td>
                            <td style="border: 1px solid black;">{{ $Rec->TravelElg ?? '' }} </td>
                        </tr>
                    </table>


                </div>

                <div class="col-lg-12">
                    <p class="fw-bold mb-1">Any other benefit:</p>
                    <p>{{ $OtherDetail->OtherBenifit ?? '' }}</p>
                </div>
            </div>


        </div>



    @endif
    <p style="page-break-after: always;">&nbsp;</p>
    <br>
    <div style="margin-top: 50px;text-align:center;">
        <p style="margin-bottom:0px;font-size: 16px;"><b>DECLARATION:</b></p>
        <p><b>घोषणा</b></p>
    </div>
    <div class="row">
        <div class="col-12">
            <p style="text-align: justify;">
                I {{ $Rec->FName }}
                {{ $Rec->MName }}
                {{ $Rec->LName }} hereby declare that all the information’s and facts set forth in this application and any
                supplemental information is true and complete to the best of my knowledge. I understand
                that, if employed, falsified statements on this application shall be considered sufficient
                cause for immediate discharge. I hereby authorize investigation of all statements contained
                herein and employers listed above to give you any and all information concerning my
                employment, and any pertinent information they may have, and release all parties from all
                liability for any damage that may result from furnishing same. I understand that neither the
                completion of this application nor any other part of my consideration for employment
                establishes any obligation for the company to hire me. I understand that I am required to
                abide by all rules and regulations of the company
            </p>
        </div>
    </div>
    <br><br>
    <div style="font-size: 12px;">
        <div style="float: left; width: 60%; text-align: left;">Place : ______________</div>
        <div style="float: left; width: 40%; text-align: right;">___________________</div>
    </div>
    <div style="font-size: 12px;">
        <div style="float: left; width: 60%; text-align: left;">Date : ______________</div>
        <div style="float: left; width: 40%; text-align: right;">Signature of Applicant <br>आवेदक के हस्ताक्षर</div>
    </div>
</body>

</html>
