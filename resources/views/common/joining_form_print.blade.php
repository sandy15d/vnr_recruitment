@php
    use Illuminate\Support\Carbon;
    $sendingId = request()->query('jaid');
    $JAId = base64_decode($sendingId);
    $Rec = DB::table('jobapply')
        ->join('jobcandidates', 'jobapply.JCId', '=', 'jobcandidates.JCId')
        ->leftJoin('jobpost', 'jobapply.JPId', '=', 'jobpost.JPId')
        ->leftJoin('jf_contact_det', 'jobcandidates.JCId', '=', 'jf_contact_det.JCId')
        ->leftJoin('jf_pf_esic', 'jobcandidates.JCId', '=', 'jf_pf_esic.JCId')
        ->leftJoin('offerletterbasic', 'offerletterbasic.JAId', '=', 'jobapply.JAId')
        ->leftJoin('core_designation', 'core_designation.id', 'offerletterbasic.Designation')
        ->where('jobapply.JAId', $JAId)
        ->select('jobapply.*', 'core_designation.designation_name as DesigName', 'jobcandidates.*', 'jobpost.Title as JobTitle', 'jobpost.JobCode', 'jf_contact_det.pre_address', 'jf_contact_det.pre_city', 'jf_contact_det.pre_state', 'jf_contact_det.pre_pin', 'jf_contact_det.pre_dist', 'jf_contact_det.perm_address', 'jf_contact_det.perm_city', 'jf_contact_det.perm_state', 'jf_contact_det.perm_pin', 'jf_contact_det.perm_dist', 'jf_contact_det.cont_one_name', 'jf_contact_det.cont_one_relation', 'jf_contact_det.cont_one_number', 'jf_contact_det.cont_two_name', 'jf_contact_det.cont_two_relation', 'jf_contact_det.cont_two_number', 'jf_pf_esic.UAN', 'jf_pf_esic.PFNumber', 'jf_pf_esic.ESICNumber', 'jf_pf_esic.BankName', 'jf_pf_esic.BranchName', 'jf_pf_esic.IFSCCode', 'jf_pf_esic.AccountNumber', 'jf_pf_esic.PAN', 'jf_pf_esic.Passport')
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
$vehicle_info = DB::table('vehicle_information')->where('JCId',$Rec->JCId)->first();
    $Year = Carbon::now()->year;
@endphp
    <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Joining Form</title>
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
        <span style="font-size: 20px;"> {{ $Rec->DesigName }}</span>
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

                @if ($Rec->MaritalStatus == 'Married')
                    <tr style="border: 1px solid black;">
                        <td style="border: 1px solid black;">
                            Spouse Name(पति/पत्नी का नाम):
                        </td>
                        <td style="border: 1px solid black;font-weight:bold;">

                            {{ $Rec->SpouseName }}

                        </td>
                    </tr>

                    <tr style="border: 1px solid black;">
                        <td style="border: 1px solid black;">
                            Marriage Anniversary <br> (विवाह की तिथि):
                        </td>
                        <td style="border: 1px solid black;font-weight:bold;">

                            {{ date('d-M-Y', strtotime($Rec->MarriageDate)) }}

                        </td>
                    </tr>
                @endif


            </table>
        </div>
    </div>
    <div style="width: 20%;float: left;margin-left:20px;">
        @if ($Rec->CandidateImage == null)
            <img src="{{ URL::to('/') }}/assets/images/user1.png" width="100" height="140"/>
        @else
            <img src="{{ url('file-view/Picture/' . $Rec->CandidateImage) }}" width="100"
                 height="140"/>
        @endif
    </div>
</div>
<p>*Information collected here is for Govt. Statistical data use only. यहां एकत्रित की गई जानकारी केवल
    सरकारी सांख्यिकीय डेटा के उपयोग हेतु है।</p>
<p style="font-weight: bold;">Contact Details (संपर्क विवरण) :</p>
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
                    का पता
                </td>
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
    <table class="table" style="border: 1px solid black;">
        <tr style="border: 1px solid black;">
            <td style="border: 1px solid black;">Total Experience:</td>
            <td style="border: 1px solid black;">
                @if ($Rec->Professional == 'P')
                    {{ $Rec->TotalYear }}
                    Years {{ $Rec->TotalMonth }} Months
                @else
                    Fresher
                @endif
            </td>
        </tr>
        <tr style="border: 1px solid black;">
            <td style="border: 1px solid black;">कुल कार्य अनुभव :</td>
            <td style="border: 1px solid black;">
                @if ($Rec->Professional == 'P')
                    {{ $Rec->TotalYear }} साल {{ $Rec->TotalMonth }} महीना
                @endif
            </td>
        </tr>
    </table>
</div>


<div class="row">
    <div class="col-lg-12">
        <p style="font-weight: bold;">Emergency Contact Details (आपात्कालीन सम्पर्क विवरण):</p>
        <table class="table table-bordered text-center">
            <tr style="border: 1px solid black;">
                <th style="border: 1px solid black; text-align:center;">Contact Person</th>
                <th style="border: 1px solid black; text-align:center;">Relationship</th>
                <th style="border: 1px solid black; text-align:center;">Contact No.</th>
            </tr>
            <tbody>
            <tr style="border: 1px solid black;">
                <td style="border: 1px solid black;">{{ $Rec->cont_one_name }}</td>
                <td style="border: 1px solid black;">{{ $Rec->cont_one_relation }}</td>
                <td style="border: 1px solid black;">{{ $Rec->cont_one_number }}</td>
            </tr>
            @if ($Rec->cont_two_name != null)
                <tr style="border: 1px solid black;">
                    <td style="border: 1px solid black;">{{ $Rec->cont_two_name }}</td>
                    <td style="border: 1px solid black;">{{ $Rec->cont_two_relation }}</td>
                    <td style="border: 1px solid black;">{{ $Rec->cont_two_number }}</td>
            @endif
            </tbody>
        </table>
    </div>
</div>


<div class="row">
    <p style="font-weight: bold;"><b>Details of Current Employment (वर्तमान नौकरी का विवरण)</b></p>
    <div class="col-12">
        <table class="table table-borderless">
            <tr style="border: 1px solid black;">
                <td style="width: 70%"><span class="font-weight-bold">Name of Company (नियोक्ता / कंपनी
                            का नाम
                            ): </span>

                </td>
                <td style="border: 1px solid black;"><b>
                        @if ($Rec->Professional == 'P')
                            {{ $Rec->PresentCompany }}
                        @else
                            Fresher
                        @endif
                    </b></td>
            </tr>
            <tr style="border: 1px solid black;">
                <td style="border: 1px solid black;"><span class="font-weight-bold">Date of Joining (कार्यग्रहण
                            तिथि ):
                        </span>

                </td>
                <td style="border: 1px solid black;"><b>
                        @if ($Rec->Professional == 'P')
                            {{ date('d-m-Y', strtotime($Rec->JobStartDate)) }}
                        @endif

                    </b></td>
            </tr>
            <tr style="border: 1px solid black;">
                <td style="border: 1px solid black;"><span class="font-weight-bold">Designation (पद ): </span>

                </td>
                <td style="border: 1px solid black;"><b>
                        {{ $Rec->Designation }}
                    </b></td>
            </tr>

            <tr style="border: 1px solid black;">
                <td style="border: 1px solid black;"><span class="font-weight-bold">Annual Package(CTC) (वेतन
                            सालाना):
                </td>
                <td style="border: 1px solid black;">
                    <b>
                        {{ $Rec->CTC ?? '' }}
                    </b>
                </td>
            </tr>
            <tr style="border: 1px solid black;">
                <td style="border: 1px solid black;"><span class="font-weight-bold">Salary (Per Month)(वेतन
                            मासिक):


                </td>
                <td style="border: 1px solid black;">
                    <b>
                        {{ $Rec->GrossSalary ?? '' }}
                    </b>
                </td>
            </tr>

            <tr style="border: 1px solid black;">
                <td style="border: 1px solid black;"><span class="font-weight-bold">Notice Period in Current
                            Organization <br> (वर्तमान
                            कंपनी में कार्य छोड़ने का समय):
                        </span>
                </td>
                <td style="border: 1px solid black;">
                    <b>
                        {{ $Rec->NoticePeriod ?? '' }}
                    </b>

                </td>
            </tr>
            <tr style="border: 1px solid black;">
                <td style="border: 1px solid black;"><span class="font-weight-bold">State the reason for which you
                            are seeking for the
                            job
                            change (उन कारणों का विवरण दे जिनके कारन आप नए नौकरियों के अवसर तलाश रहे हैं):
                        </span>

                </td>
                <td style="border: 1px solid black;"><b>
                        {{ $Rec->ResignReason }}
                    </b></td>
            </tr>
            <tr style="border: 1px solid black;">
                <td style="border: 1px solid black;"><span class="font-weight-bold">Expected Annual Package(CTC)
                            (अपेक्षित सालाना वेतन):
                        </span>

                </td>
                <td style="border: 1px solid black;"><b>
                        {{ $Rec->ExpectedCTC }}
                    </b></td>
            </tr>

        </table>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <p style="font-weight: bold;">Social benefit details related to previous employment</p>
        <table class="table table-bordered">
            <tr style="border: 1px solid black;">
                <td style="width: 50%">UAN</td>
                <td style="border: 1px solid black;">{{ $Rec->UAN ?? '-' }}</td>
            </tr>
            <tr style="border: 1px solid black;">
                <td style="border: 1px solid black;">EPFO Code No</td>
                <td style="border: 1px solid black;">{{ $Rec->PFNumber ?? '-' }}</td>
            </tr>
            <tr style="border: 1px solid black;">

                <td style="border: 1px solid black;">ESIC</td>
                <td style="border: 1px solid black;">{{ $Rec->ESICNumber ?? '-' }}</td>
            </tr>
        </table>
    </div>
</div>

<div class="row">
    <p style="margin-bottom: 0px;font-weight: bold;"><b>Training & Practical Experience (Other than regular
            jobs):</b>
        प्रशिक्षण और व्यावहारिक अनुभव (नियमित नौकरियों के अलावा)
    </p>
    <div class="col-md-12">

        <table class="table table-borderless">
            <thead>
            <tr style="border: 1px solid black;">
                <th style="border: 1px solid black; text-align:center;">Name of the Training</th>
                <th style="border: 1px solid black; text-align:center;">Training provided by
                    <br>Organization/Institute
                </th>
                <th style="border: 1px solid black; text-align:center;">From Date</th>
                <th style="border: 1px solid black; text-align:center;">To Date</th>

            </tr>
            </thead>
            <tbody>
            @foreach ($Training as $item)
                <tr style="border: 1px solid black;">
                    <td style="border: 1px solid black;">{{ $item->training }}</td>
                    <td style="border: 1px solid black;">{{ $item->organization }}</td>
                    <td style="border: 1px solid black;">{{ $item->from }}</td>
                    <td style="border: 1px solid black;">{{ $item->to }}</td>

                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>


<div class="row">
    <p style="margin-bottom: 0px;font-weight: bold;"><b>Previous Employment Records:</b> अन्य अन्य कार्योनुभव के
        विवरण
        (वर्तमान को छोड़कर )
    </p>
    <div class="col-md-12">

        <table class="table table-borderless">
            <thead>
            <tr style="border: 1px solid black;">
                <th style="border: 1px solid black; text-align:center;">Name of the Employer</th>
                <th style="border: 1px solid black; text-align:center;">Designation</th>
                <th style="border: 1px solid black; text-align:center;">Job Start Date</th>
                <th style="border: 1px solid black; text-align:center;">Job End Date</th>
                <th style="border: 1px solid black; text-align:center;">Gross Monthly Salary</th>
                <th style="border: 1px solid black; text-align:center;">Annual CTC</th>
                <th style="border: 1px solid black; text-align:center;">Reason for Leave</th>
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
        <p style="font-weight: bold;"> Educational Details (शैक्षाणिक योग्यताये)</p>
        <table class="table text-center">
            <thead>
            <tr style="border: 1px solid black;">
                <th style="border: 1px solid black; text-align:center;">Qualification</th>
                <th style="border: 1px solid black; text-align:center;">Year of Passing</th>
                <th style="border: 1px solid black; text-align:center;">%</th>
                <th style="border: 1px solid black; text-align:center;">University / College</th>
                <th style="border: 1px solid black; text-align:center;">Course</th>
                <th style="border: 1px solid black; text-align:center;">Specialization</th>
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

<div class="row">
    <div class="table-responsive col-lg-12">
        <p style="font-weight: bold;">Language Proficiency</p>
        <table class="table">
            <thead>
            <tr style="border: 1px solid black;">
                <td style="border: 1px solid black;">Language</td>
                <td style="border: 1px solid black;">Reading</td>
                <td style="border: 1px solid black;">Writing</td>
                <td style="border: 1px solid black;">Speaking</td>
            </tr>
            </thead>
            <tbody>
            @foreach ($lang as $item)
                <tr style="border: 1px solid black;">
                    <td style="border: 1px solid black;">{{ $item->language }}</td>
                    <td style="border: 1px solid black;">{{ $item->read == 1 ? 'Yes' : 'No' }}</td>
                    <td style="border: 1px solid black;">{{ $item->write == 1 ? 'Yes' : 'No' }}</td>
                    <td style="border: 1px solid black;">{{ $item->speak == 1 ? 'Yes' : 'No' }}</td>
                </tr>
            @endforeach
            </tbody>
            </tbody>
        </table>
    </div>
</div>
<p style="page-break-after: always;">&nbsp;</p>
<div class="row">
    <div class="col-md-12">
        <p style="margin-bottom:0px ;font-weight:bold;"><b>Family Details(परिवार का विवरण )</b></p>
        <table class="table table-borderless">
            <thead>
            <tr class="text-center" style="border: 1px solid black; text-align:center;">
                <th style="border: 1px solid black; text-align:center;">Relationship</th>
                <th style="border: 1px solid black; text-align:center;">Name</th>
                <th style="border: 1px solid black; text-align:center;">Date of Birth</th>
                <th style="border: 1px solid black; text-align:center;">Qualification</th>
                <th style="border: 1px solid black; text-align:center;">Occupation</th>
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


<div class="row" style="margin-bottom:0px; marign-top:50px;">

    <div class="row">
        <p style="font-weight: bold;">Other Info</p>
        @if ($Rec->Professional == 'P')
            <div class="col-12">
                <p style="margin-bottom: 0px; font-weight: bold;"><b>Please give the reference who had worked with
                        you in
                        the previous organization </b></p>
                <table class="table table-borderless">
                    <thead>
                    <tr class="text-center" style="border: 1px solid black; text-align:center;">
                        <th style="border: 1px solid black; text-align:center;">Name</th>
                        <th style="border: 1px solid black; text-align:center;">Company</th>
                        <th style="border: 1px solid black; text-align:center;">Designation</th>
                        <th style="border: 1px solid black; text-align:center;">Contact</th>
                        <th style="border: 1px solid black; text-align:center;">Email</th>
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
            <p style="margin-bottom: 0px;"><b>Do you have any acquaintances or relatives working with
                    VNR Group Companies?</b></p>
            <p>क्या आपका कोई परिचित या रिश्तेदार वीएनआर ग्रुप कंपनियों के साथ
                काम कर रहा है? :</p>
            <p style="font-weight: bold;" style="margin-left: 20px;">
                {{ $Rec != null && $Rec->VNR_Acq == 'Y' ? 'Yes' : 'No' }}
            </p>
            @if ($Rec->VNR_Acq == 'Y')
                <table class="table table-borderless" style="width:100%">
                    <thead>
                    <tr class="text-center" style="border: 1px solid black;">
                        <th style="border: 1px solid black; text-align:center;">Name</th>
                        <th style="border: 1px solid black; text-align:center;">Mobile</th>
                        <th style="border: 1px solid black; text-align:center;">Email</th>
                        <th style="border: 1px solid black; text-align:center;">VNR Group /<br>Company Name
                        </th>
                        <th style="border: 1px solid black; text-align:center;">Designation</th>
                        <th style="border: 1px solid black; text-align:center;">Location</th>
                        <th style="border: 1px solid black; text-align:center;">Your Relationship <br>with
                            person
                            mentioned
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
            <p style="margin-bottom: 0px;"><b>Do you have any acquaintances or relatives associated
                    with
                    VNR as business associates (like Dealer, Distributor, Retailer, Organizer, Vendor
                    etc.)?</b></p>
            <p class="mb-0">क्या आपका कोई परिचित या रिश्तेदार VNR से व्यावसायिक सहयोगी (जैसे
                डीलर, वितरक, खुदरा विक्रेता, आयोजक, विक्रेता आदि) के रूप में जुड़ा है?:</p>
            <p class="mb-0" style="margin-left: 20px;">
                {{ $Rec != null && $Rec->VNR_Acq_Business == 'Y' ? 'Yes' : 'No' }}
            </p>
            @if ($Rec->VNR_Acq_Business == 'Y')
                <table class="table table-borderless" style="width:100%">
                    <thead>
                    <tr class="text-center" style="border: 1px solid black;">
                        <th style="border: 1px solid black; text-align:center;">Name</th>
                        <th style="border: 1px solid black; text-align:center;">Mobile</th>
                        <th style="border: 1px solid black; text-align:center;">Email</th>
                        <th style="border: 1px solid black; text-align:center;">Business Relation <br>With VNR
                        </th>
                        <th style="border: 1px solid black; text-align:center;">Location of Business /
                            acquaintances
                        </th>

                        <th style="border: 1px solid black; text-align:center;">Your Relationship <br>with
                            person
                            mentioned
                        </th>
                    </tr>
                    </thead>
                    <tbody class="text-center">
                    @foreach ($VnrBusinessRef as $item)
                        <tr style="border: 1px solid black;">
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
            <p style="margin-bottom: 0px;"><b>Is any of your relatives or acquaintances is/are working
                    or associated with any other Seed Company?</b></p>
            <p style="margin-bottom: 0px;">क्या आपका कोई रिश्तेदार या परिचित किसी अन्य सीड कंपनी से जुड़ा
                है/काम कर रहा है?</p>
            <p class="mb-0" style="margin-left: 20px;">
                {{ $Rec != null && $Rec->OtherSeedRelation == 'Y' ? 'Yes' : 'No' }}
            </p>

            @if ($Rec->OtherSeedRelation == 'Y')
                <table class="table table-borderless" style="width:100%">
                    <thead>
                    <tr class="text-center">
                        <th style="border: 1px solid black; text-align:center;">Name</th>
                        <th style="border: 1px solid black; text-align:center;">Mobile</th>
                        <th style="border: 1px solid black; text-align:center;">Email</th>
                        <th style="border: 1px solid black; text-align:center;">Company Name</th>
                        <th style="border: 1px solid black; text-align:center;">Designation</th>
                        <th style="border: 1px solid black; text-align:center;">Location</th>
                        <th style="border: 1px solid black; text-align:center;">Your Relationship <br>with
                            person
                            mentioned
                        </th>
                    </tr>
                    </thead>
                    <tbody class="text-center">
                    @foreach ($OtherSeed as $item)
                        <tr style="border: 1px solid black;">
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
</div>


<p style="page-break-after: always;">&nbsp;</p>
<br>

<p style="margin-bottom:0px;margin-top: 50px;text-align: center;font-weight:bold;font-size: 16px;">
    <b>DECLARATION</b></p>
<p style="text-align: center;font-weight:bold;"><b>घोषणा</b></p>

<div class="row">
    <div class="col-12">
        <p style="text-align: justify;">
            I {{ $Rec->FName }}
            {{ $Rec->MName }}
            {{ $Rec->LName }} hereby declare that all the information’s and facts set forth in this application and any
            supplemental information is true and complete to the best of my knowledge. I understand
            that, during my employment, any falsified statements on this application shall be considered
            sufficient cause for my immediate discharge from the employment of the Company. I hereby
            authorize investigation of all statements contained herein and employers listed above to
            give you any and all information concerning my employment, and any pertinent information
            they may have, and release all parties from all liability for any damage that may result
            from furnishing same. I understand that I am required to abide by all rules and regulations
            of the company.
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


<p style="page-break-after: always;">&nbsp;</p>
<br>
<p style="margin-bottom:10px;margin-top: 50px; font-weight: bold;text-align: center;"><b>Document Details:</b></p>
<div class="row">
    <div class="col-12">
        <table class="table table-bordered">
            <tr style="border: 1px solid black;">
                <td style="width: 2%; border: 1px solid black;">1</td>
                <td style="width: 20%;border: 1px solid black;">Bank Name</td>
                <td style="border: 1px solid black;">{{ $Rec->BankName ?? '' }}</td>
            </tr>
            <tr style="border: 1px solid black;">
                <td style="border: 1px solid black;">2</td>
                <td style="border: 1px solid black;">Bank Branch</td>
                <td style="border: 1px solid black;">{{ $Rec->BranchName ?? '' }}</td>
            </tr>
            <tr style="border: 1px solid black;">
                <td style="border: 1px solid black;">3</td>
                <td style="border: 1px solid black;">Account Number</td>
                <td style="border: 1px solid black;">{{ $Rec->AccountNumber ?? '' }}</td>
            </tr>
            <tr style="border: 1px solid black;">
                <td style="border: 1px solid black;">4</td>
                <td style="border: 1px solid black;">IFSC Code</td>
                <td style="border: 1px solid black;">{{ $Rec->IFSCCode ?? '' }}</td>
            </tr>
            <tr style="border: 1px solid black;">
                <td style="border: 1px solid black;">5</td>
                <td style="border: 1px solid black;">PAN Number</td>
                <td style="border: 1px solid black;">{{ $Rec->PAN ?? '' }}</td>
            </tr>
            <tr style="border: 1px solid black;">
                <td style="border: 1px solid black;">6</td>
                <td style="border: 1px solid black;">Aadhaar Card</td>
                <td style="border: 1px solid black;">{{ $Rec->Aadhaar ?? '' }}</td>
            </tr>
            <tr style="border: 1px solid black;">
                <td style="border: 1px solid black;">7</td>
                <td style="border: 1px solid black;">Passport</td>
                <td style="border: 1px solid black;">{{ $Rec->Passport ?? '' }}</td>
            </tr>
            <tr style="border: 1px solid black;">
                <td style="border: 1px solid black;">8</td>
                <td style="border: 1px solid black;">Driving License</td>
                <td style="border: 1px solid black;">{{ $AboutAns->DLNo ?? '' }}</td>
                </tr>
            </table>
        </div>
    </div>
<p style="margin-bottom:10px;margin-top: 10px; font-weight: bold;text-align: center;"><b>2 Wheeler Details:</b></p>
<div class="row">
    <div class="col-12">
        <table class="table table-bordered">
            <tr style="border: 1px solid black;">

                <td style="width: 20%;border: 1px solid black;">Ownership</td>
                <td style="border: 1px solid black;">{{ $vehicle_info->ownership ?? '' }}</td>

                <td style="border: 1px solid black;">Brand</td>
                <td style="border: 1px solid black;">{{ $vehicle_info->brand ?? '' }}</td>
            </tr>
            <tr style="border: 1px solid black;">

                <td style="border: 1px solid black;">Model</td>
                <td style="border: 1px solid black;">{{ $vehicle_info->model_name ?? '' }}</td>


                <td style="border: 1px solid black;">Model No</td>
                <td style="border: 1px solid black;">{{ $vehicle_info->model_no ?? '' }}</td>
            </tr>
            <tr style="border: 1px solid black;">

                <td style="border: 1px solid black;">Dealer Name</td>
                <td style="border: 1px solid black;">{{ $vehicle_info->dealer_name ?? '' }}</td>


                <td style="border: 1px solid black;">Dealer Contact</td>
                <td style="border: 1px solid black;">{{ $vehicle_info->dealer_contact ?? '' }}</td>
            </tr>
            <tr style="border: 1px solid black;">

                <td style="border: 1px solid black;">Purchase Date</td>
                <td style="border: 1px solid black;">{{ $vehicle_info->purchase_date ?? '' }}</td>


                <td style="border: 1px solid black;">Price</td>
                <td style="border: 1px solid black;">{{ $vehicle_info->price ?? '' }}</td>
            </tr>
            <tr style="border: 1px solid black;">

                <td style="border: 1px solid black;">Invoice No</td>
                <td style="border: 1px solid black;">{{ $vehicle_info->bill_no ?? '' }}</td>


                <td style="border: 1px solid black;">Fuel Type</td>
                <td style="border: 1px solid black;">{{ $vehicle_info->fuel_type ?? '' }}</td>
            </tr>
            <tr style="border: 1px solid black;">

                <td style="border: 1px solid black;">Registration Number</td>
                <td style="border: 1px solid black;">{{ $vehicle_info->registration_no ?? '' }}</td>


                <td style="border: 1px solid black;">Registration Date</td>
                <td style="border: 1px solid black;">{{ $vehicle_info->registration_date ?? '' }}</td>
            </tr>
            <tr style="border: 1px solid black;">

                <td style="border: 1px solid black;">Current ODO Meter</td>
                <td style="border: 1px solid black;" colspan="3">{{ $vehicle_info->current_odo_meter ?? '' }}</td>
            </tr>

        </table>
    </div>
</div>

<p style="margin-bottom:10px;margin-top: 10px; font-weight: bold;text-align: center;"><b>4 Wheeler Details:</b></p>
<div class="row">
    <div class="col-12">
        <table class="table table-bordered">
            <tr style="border: 1px solid black;">

                <td style="width: 20%;border: 1px solid black;">Ownership</td>
                <td style="border: 1px solid black;">{{ $vehicle_info->four_ownership ?? '' }}</td>

                <td style="border: 1px solid black;">Brand</td>
                <td style="border: 1px solid black;">{{ $vehicle_info->four_brand ?? '' }}</td>
            </tr>
            <tr style="border: 1px solid black;">

                <td style="border: 1px solid black;">Model</td>
                <td style="border: 1px solid black;">{{ $vehicle_info->four_model_name ?? '' }}</td>


                <td style="border: 1px solid black;">Model No</td>
                <td style="border: 1px solid black;">{{ $vehicle_info->four_model_no ?? '' }}</td>
            </tr>
            <tr style="border: 1px solid black;">

                <td style="border: 1px solid black;">Dealer Name</td>
                <td style="border: 1px solid black;">{{ $vehicle_info->four_dealer_name ?? '' }}</td>


                <td style="border: 1px solid black;">Dealer Contact</td>
                <td style="border: 1px solid black;">{{ $vehicle_info->four_dealer_contact ?? '' }}</td>
            </tr>
            <tr style="border: 1px solid black;">

                <td style="border: 1px solid black;">Purchase Date</td>
                <td style="border: 1px solid black;">{{ $vehicle_info->four_purchase_date ?? '' }}</td>


                <td style="border: 1px solid black;">Price</td>
                <td style="border: 1px solid black;">{{ $vehicle_info->four_price ?? '' }}</td>
            </tr>
            <tr style="border: 1px solid black;">

                <td style="border: 1px solid black;">Invoice No</td>
                <td style="border: 1px solid black;">{{ $vehicle_info->four_bill_no ?? '' }}</td>


                <td style="border: 1px solid black;">Fuel Type</td>
                <td style="border: 1px solid black;">{{ $vehicle_info->four_fuel_type ?? '' }}</td>
            </tr>
            <tr style="border: 1px solid black;">

                <td style="border: 1px solid black;">Registration Number</td>
                <td style="border: 1px solid black;">{{ $vehicle_info->four_registration_no ?? '' }}</td>


                <td style="border: 1px solid black;">Registration Date</td>
                <td style="border: 1px solid black;">{{ $vehicle_info->four_registration_date ?? '' }}</td>
            </tr>
            <tr style="border: 1px solid black;">

                <td style="border: 1px solid black;">Current ODO Meter</td>
                <td style="border: 1px solid black;" colspan="3">{{ $vehicle_info->four_current_odo_meter ?? '' }}</td>


            </tr>

        </table>
    </div>
</div>
</body>
