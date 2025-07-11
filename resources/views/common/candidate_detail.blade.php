@php

    use Illuminate\Support\Carbon;

    $sendingId = request()->query('jaid');
    $JAId = base64_decode($sendingId);
    $Rec = DB::table('jobapply')
        ->join('jobcandidates', 'jobapply.JCId', '=', 'jobcandidates.JCId')
        ->leftJoin('screening', 'screening.JAId', '=', 'jobapply.JAId')
        ->leftJoin('screen2ndround', 'screen2ndround.ScId', '=', 'screening.ScId')
        ->leftJoin('jobpost', 'jobapply.JPId', '=', 'jobpost.JPId')
        ->leftJoin('jf_contact_det', 'jobcandidates.JCId', '=', 'jf_contact_det.JCId')
        ->leftJoin('jf_pf_esic', 'jobcandidates.JCId', '=', 'jf_pf_esic.JCId')
        ->leftJoin('core_country', 'jobcandidates.Nationality', '=', 'core_country.id')
        ->leftJoin('appointing', 'appointing.JAId', '=', 'jobapply.JAId')
        ->where('jobapply.JAId', $JAId)
        ->select(
            'jobapply.*',
            'jobcandidates.*',
            'screening.ReSentForScreen',
            'screening.ResScreened',
            'screening.ScreenStatus',
            'screening.RejectionRem',
            'screening.IntervStatus',
            'screening.IntervDt',
            'screen2ndround.IntervDt2',
            'screen2ndround.IntervStatus2',
            'screening.SelectedForD',
            'jobpost.Title as JobTitle',
            'jobpost.JobCode',
            'jf_contact_det.pre_address',
            'jf_contact_det.pre_city',
            'jf_contact_det.pre_state',
            'jf_contact_det.pre_pin',
            'jf_contact_det.pre_dist',
            'jf_contact_det.perm_address',
            'jf_contact_det.perm_city',
            'jf_contact_det.perm_state',
            'jf_contact_det.perm_pin',
            'jf_contact_det.perm_dist',
            'jf_contact_det.cont_one_name',
            'jf_contact_det.cont_one_relation',
            'jf_contact_det.cont_one_number',
            'jf_contact_det.cont_two_name',
            'jf_contact_det.cont_two_relation',
            'jf_contact_det.cont_two_number',
            'jf_pf_esic.UAN',
            'jf_pf_esic.PFNumber',
            'jf_pf_esic.ESICNumber',
            'jf_pf_esic.BankName',
            'jf_pf_esic.BranchName',
            'jf_pf_esic.IFSCCode',
            'jf_pf_esic.AccountNumber',
            'jf_pf_esic.PAN',
            'jf_pf_esic.Passport',
            'core_country.country_name',
            'appointing.AppLtrGen',
            'appointing.AgrLtrGen',
            'appointing.BLtrGen',
            'appointing.ConfLtrGen',
        )
        ->first();

    $JCId = $Rec->JCId;
    $firobid = base64_encode($Rec->JCId);

    $OfBasic = DB::table('offerletterbasic')
        ->leftJoin('candjoining', 'candjoining.JAId', '=', 'offerletterbasic.JAId')
        ->leftJoin('appointing', 'appointing.JAId', '=', 'offerletterbasic.JAId')
        ->leftJoin('candidate_entitlement', 'candidate_entitlement.JAId', '=', 'offerletterbasic.JAId')
        ->select(
            'offerletterbasic.*',
            'candjoining.JoinOnDt',
            'appointing.A_Date',
            'appointing.Agr_Date',
            'appointing.B_Date',
            'appointing.ConfLtrDate',
            'candjoining.EmpCode',
            'candjoining.Verification',
            'candjoining.Joined',
            'candjoining.PositionCode',
            'candjoining.ForwardToESS',
            'candjoining.NoJoiningRemark',
            'candjoining.RejReason as RejReason1',
            'candidate_entitlement.TwoWheel',
            'candidate_entitlement.FourWheel',
        )
        ->where('offerletterbasic.JAId', $JAId)
        ->first();

    $FamilyInfo = DB::table('jf_family_det')->where('JCId', $JCId)->get();
    $Education = DB::table('candidateeducation')->where('JCId', $JCId)->get();
    $Experience = DB::table('jf_work_exp')->where('JCId', $JCId)->get();

    $Training = DB::table('jf_tranprac')->where('JCId', $JCId)->get();

    $PreRef = DB::table('jf_reference')->where('JCId', $JCId)->where('from', 'Previous Organization')->get();

    $VnrRef = DB::table('jf_reference')->where('JCId', $JCId)->where('from', 'VNR')->get();
    $Year = Carbon::now()->year;
    $sql = DB::table('offerletterbasic_history')->where('JAId', $JAId)->get();
    $lang = DB::table('jf_language')->where('JCId', $JCId)->get();
    $count = count($sql);
    $OtherSeed = DB::table('relation_other_seed_cmp')->where('JCId', $JCId)->get();
    $VnrBusinessRef = DB::table('vnr_business_ref')->where('JCId', $JCId)->get();
    $AboutAns = DB::table('about_answer')->where('JCId', $JCId)->first();
    $Docs = DB::table('jf_docs')->where('JCId', $JCId)->first();
    $vehicle_info = DB::table('vehicle_information')->where('JCId', $JCId)->first();
    $country_list = DB::table('core_country')->pluck('country_name', 'id');
    $candidate_log = DB::table('candidate_log')->where('JCId', $JCId)->get();

    if ($OfBasic != null && $OfBasic->Grade != null) {
        $position_code_list = DB::table('position_codes')
            ->where('company_id', $OfBasic->Company)
            ->where('department_id', $OfBasic->Department)
            ->where('grade_id', $OfBasic->Grade)
            ->where('is_available', 'Yes')
            ->pluck('position_code');
    } else {
        $position_code_list = [];
    }

@endphp
@extends('layouts.master')
@section('title', 'Candidate Detail')
@section('PageContent')
    <style>
        .table> :not(caption)>*>* {
            padding: 2px 1px;
        }

        .frminp {
            padding: 4px !important;
            height: 25px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 550;
        }

        .frmbtn {
            padding: 2px 4px !important;
            font-size: 11px;
            cursor: pointer;
        }

        .iframe-container {
            padding-bottom: 60%;
            padding-top: 30px;
            height: 0;
            overflow: hidden;
        }

        .iframe-container iframe,
        .iframe-container object,
        .iframe-container embed {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }
    </style>
    <div class="page-content">
        <input type="hidden" name="JAId" id="JAId" value="{{ $JAId }}">

        <input type="hidden" name="JCId" id="JCId" value="{{ $JCId }}">

        <div class="card mb-0">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="profile-view">
                            <div class="profile-img-wrap">
                                <div class="profile-img">
                                    @if ($Rec->CandidateImage == null)
                                        <img src="{{ URL::to('/') }}/assets/images/user1.png" />
                                    @else
                                        <img
                                            src="{{ url('file-view/Picture/' . $Rec->CandidateImage) }}" />
                                    @endif
                                </div>
                            </div>
                            <div class="profile-basic">
                                <div class="row">
                                    <div class="col-md-5">
                                        <div class="profile-info-left">
                                            <h6 class="user-name m-t-0 mb-0"> {{ $Rec->FName }} {{ $Rec->MName }}
                                                {{ $Rec->LName }}
                                                @if (Auth::user()->role == 'A' || Auth::user()->role == 'R')
                                                    <span>
                                                        <a data-bs-target="#profile_info" data-bs-toggle="modal"
                                                            class="edit-icon" onclick="GetProfileData();"
                                                            href="javascript:void(0);">
                                                            <i class="fa fa-pencil"></i>
                                                        </a>
                                                    </span>
                                                @endif
                                            </h6>
                                            <h6 class="staff-id">Applied For: {{ $Rec->JobTitle }}</h6>
                                            <h6 class="staff-id text-primary">MRF: {{ $Rec->JobCode }}</h6>

                                            <div class="staff-id">Reference No. : {{ $Rec->ReferenceNo }}</div>
                                            <div class="staff-id">Date of Registration :
                                                {{ date('d-M-Y', strtotime($Rec->CreatedTime)) }}</div>

                                            <div class="staff-id">Phone No.: <span
                                                    class="text-primary">{{ $Rec->Phone }}</span></div>


                                            <div class="staff-id">Email ID: <span
                                                    class="text-primary">{{ $Rec->Email }}</span></div>


                                            <div class="staff-msg">
                                                @if ($Rec->Resume != null)
                                                    <a class="btn btn-custom btn-sm" href="javascript:void(0);"
                                                        data-bs-toggle="modal" data-bs-target="#resume_modal">View
                                                        Resume</a>
                                                @endif
                                                @if (Auth::user()->role == 'A' || Auth::user()->role == 'R')
                                                    <a href="javascript:;"
                                                        class="btn btn-primary btn-sm compose-mail-btn">Send
                                                        Mail</a>
                                                @endif

                                            </div>

                                        </div>

                                    </div>
                                    @if (Auth::user()->role == 'A' || Auth::user()->role == 'R')
                                        <div class="col-md-7">
                                            <ul class="personal-info">
                                                <li>
                                                    <div class="title">Suitable For</div>
                                                    <div>
                                                        :&emsp;
                                                        @if ($Rec->Suitable_Chk_Date != null)
                                                            @php

                                                                $SuitableFor = explode(',', $Rec->Suitable_For);
                                                                foreach ($SuitableFor as $row) {
                                                                    $Su[] = getDepartment($row);
                                                                }
                                                                if ($Rec->Irrelevant_Candidate == 'Y') {
                                                                    echo 'Irrelevant Candidate';
                                                                }
                                                                echo implode(', ', $Su) .
                                                                    ' (Remarks : ' .
                                                                    $Rec->Suitable_Remark .
                                                                    ')';
                                                            @endphp
                                                        @endif
                                                        <i class='fa fa-pencil-square-o text-primary' aria-hidden='true'
                                                            style='font-size:14px;cursor: pointer;' id="SuitableFor"
                                                            data-id="{{ $Rec->JCId }}"></i>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="title">HR Screening</div>
                                                    <div>
                                                        @if ($Rec->JPId != 0)
                                                            :
                                                            &emsp;<?= '<b>' . $Rec->Status . '</b>' . "<i
                                                                class='fa fa-pencil-square-o text-primary'
                                                                aria-hidden='true' style='font-size:14px;cursor: pointer;'
                                                                id='HrScreening' data-id='$Rec->JAId'
                                                                data-applydate='$Rec->ApplyDate'></i>" ?>
                                                        @else
                                                            :
                                                        @endif
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="title">Move Candidate to</div>
                                                    <div>
                                                        :&emsp;<i class='fa fa-pencil-square-o text-primary'
                                                            aria-hidden='true' style='font-size:14px;cursor: pointer;'
                                                            id="MoveCandidate" data-id="{{ $Rec->JAId }}"></i>
                                                    </div>
                                                </li>
                                                <li>
                                                    @if ($Rec->BlackList == 0)
                                                        <div class="title">Blacklist Candidate :</div>
                                                        <div>
                                                            :&emsp;<i class='fa fa-pencil-square-o text-primary'
                                                                aria-hidden='true' style='font-size:14px;cursor: pointer;'
                                                                id="BlackListCandidate" data-id="{{ $Rec->JCId }}"></i>
                                                        </div>
                                                    @else
                                                        @if (Auth::user()->role == 'A')
                                                            <div class="title">Unblock Candidate :</div>
                                                            <div>
                                                                :&emsp;<i class='fa fa-pencil-square-o text-primary'
                                                                    aria-hidden='true'
                                                                    style='font-size:14px;cursor: pointer;'
                                                                    id="UnBlockCandidate"
                                                                    data-id="{{ $Rec->JCId }}"></i>
                                                            </div>
                                                        @endif
                                                    @endif
                                                </li>
                                            </ul>
                                        </div>
                                    @endif
                                    @if (Auth::user()->role == 'H')
                                        <div class="col-md-7">
                                            <ul class="personal-info">
                                                @if ($Rec->AddressLine1 != null)
                                                    <li style="margin-bottom: 0px">
                                                        <div class="title">Address:</div>
                                                        <div class="text  text-dark">{{ $Rec->AddressLine1 }},
                                                            {{ $Rec->AddressLine2 }}, {{ $Rec->AddressLine3 }}
                                                            <br>
                                                            {{ $Rec->City }}, {{ getStateName($Rec->State) }}
                                                        </div>
                                                    </li>
                                                @endif
                                                @if ($Rec->Education != null || $Rec->Education != 0)
                                                    <li style="margin-bottom: 0px">
                                                        <div class="title">Highest Education:</div>
                                                        <div class="text  text-dark">
                                                            {{ getEducationCodeById($Rec->Education) }}
                                                            ,
                                                            ( {{ getSpecializationbyId($Rec->Specialization) }})

                                                        </div>
                                                    </li>
                                                    <li>
                                                        <div class="title">CGPA/Percentage :</div>
                                                        <div class="text text-dark">{{ $Rec->CGPA }}</div>
                                                    </li>
                                                    <li>
                                                        <div class="title">Passing Year :</div>
                                                        <div class="text text-dark"> {{ $Rec->PassingYear }}</div>
                                                    </li>
                                                @endif
                                            </ul>
                                        </div>
                                    @endif
                                </div>
                            </div>


                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card tab-box">
            <div class="row user-tabs">
                <div class="col-lg-12 col-md-12 col-sm-12 line-tabs">
                    <ul class="nav nav-tabs nav-tabs-bottom" id="myTab">
                        <li class="nav-item"><a href="#cand_profile" data-bs-toggle="tab"
                                class="nav-link active">Profile</a></li>

                        <li class="nav-item"><a href="#cand_contact" data-bs-toggle="tab" class="nav-link">Contact</a>
                        </li>

                        <li class="nav-item"><a href="#cand_education" data-bs-toggle="tab"
                                class="nav-link">Education</a>
                        </li>

                        <li class="nav-item"><a href="#cand_experience" data-bs-toggle="tab"
                                class="nav-link">Employement</a></li>

                        <li class="nav-item"><a href="#cand_reference" data-bs-toggle="tab"
                                class="nav-link">Reference</a>
                        </li>

                        <li class="nav-item"><a href="#cand_other" data-bs-toggle="tab" class="nav-link"> Other
                            </a></li>
                        @if (Auth::user()->role == 'A' || Auth::user()->role == 'R')
                            <li class="nav-item"><a href="#vehicle_info" data-bs-toggle="tab" class="nav-link">Vehicle
                                    Info</a>
                            </li>
                            <li class="nav-item"><a href="#cand_document" data-bs-toggle="tab"
                                    class="nav-link">Documents</a>
                            </li>

                            <li class="nav-item"><a href="#job_offer" data-bs-toggle="tab" class="nav-link">Job
                                    Offer</a></li>

                            <li class="nav-item"><a href="#onboarding" data-bs-toggle="tab"
                                    class="nav-link">Onboarding</a>
                            </li>
                        @endif
                        <li class="nav-item"><a href="#cand_history" data-bs-toggle="tab" class="nav-link">History</a>
                        </li>
                        @if (Auth::user()->role == 'A')
                            <li class="nav-item">
                                <a href="#admin_change" data-bs-toggle="tab" class="nav-link">Changes</a>
                            </li>
                        @endif

                    </ul>
                </div>
            </div>
        </div>

        <div class="tab-content">

            <div id="cand_profile" class=" tab-pane fade pro-overview show active">
                <div class="row">
                    <div class="col-md-6 d-flex">
                        <div class="card profile-box flex-fill">
                            <div class="card-body">
                                <h6 class="card-title border-bot">Personal Informations
                                    @if (Auth::user()->role == 'A' || Auth::user()->role == 'R')
                                        <a href="#" class="edit-icon" data-bs-toggle="modal"
                                            data-bs-target="#personal_info_modal" onclick="GetPersonalData();"><i
                                                class="fa fa-pencil"></i>
                                        </a>
                                    @endif
                                </h6>

                                <ul class="personal-info">
                                    <li>
                                        <div class="title">Gender<span style="float: right">:</span></div>
                                        <div class="text">{{ $Rec->Gender == 'M' ? 'Male' : 'Female' }}</div>
                                    </li>

                                    <li>
                                        <div class="title">Aadhaar No.<span style="float: right">:</span></div>
                                        <div class="text">{{ $Rec->Aadhaar }}</div>
                                    </li>

                                    <li>
                                        <div class="title">Nationality<span style="float: right">:</span></div>
                                        <div class="text">{{ $Rec->country_name ?? '-' }}</div>
                                    </li>

                                    <li>
                                        <div class="title">Religion<span style="float: right">:</span></div>
                                        <div class="text">{{ $Rec->Religion ?? '-' }} @if ($Rec->Religion == 'Others')
                                                <span class="text-danger">({{ $Rec->OtherReligion }})</span>
                                            @endif
                                        </div>
                                    </li>

                                    <li>
                                        <div class="title">Marital Status<span style="float: right">:</span></div>
                                        <div class="text">{{ $Rec->MaritalStatus ?? '-' }}</div>
                                    </li>
                                    <li>
                                        <div class="title">Marriage Date<span style="float: right">:</span></div>
                                        <div class="text">
                                            @if ($Rec->MarriageDate != null)
                                                {{ date('d-M-Y', strtotime($Rec->MarriageDate)) }}
                                            @else
                                                -
                                            @endif
                                        </div>
                                    </li>
                                    <li>
                                        <div class="title">Spouse Name<span style="float: right">:</span></div>
                                        <div class="text">{{ $Rec->SpouseName ?? '-' }}</div>
                                    </li>
                                    <li>
                                        <div class="title">Category<span style="float: right">:</span></div>
                                        <div class="text">{{ $Rec->Caste ?? '-' }}@if ($Rec->Caste == 'Other')
                                                <span class="text-danger">({{ $Rec->OtherCaste }})</span>
                                            @endif
                                        </div>
                                    </li>


                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 d-flex">
                        <div class="card profile-box flex-fill">
                            <div class="card-body">
                                <h6 class="card-title border-bot">Emergency Contact
                                    @if (Auth::user()->role == 'A' || Auth::user()->role == 'R')
                                        <a href="#" class="edit-icon" data-bs-toggle="modal"
                                            data-bs-target="#emergency_contact_modal" onclick="GetEmergencyContact();"><i
                                                class="fa fa-pencil"></i>
                                        </a>
                                    @endif
                                </h6>
                                <h6 class="section-title">Primary</h6>
                                <ul class="personal-info">
                                    <li>
                                        <div class="title">Name</div>
                                        <div class="text">{{ $Rec->cont_one_name ?? '-' }}</div>
                                    </li>
                                    <li>
                                        <div class="title">Relationship</div>
                                        <div class="text">{{ $Rec->cont_one_relation ?? '-' }}</div>
                                    </li>
                                    <li>
                                        <div class="title">Phone No.</div>
                                        <div class="text">{{ $Rec->cont_one_number ?? '-' }}</div>
                                    </li>
                                </ul>

                                <hr>
                                <h6 class="section-title">Secondary</h6>
                                <ul class="personal-info">
                                    <li>
                                        <div class="title">Name</div>
                                        <div class="text">{{ $Rec->cont_two_name ?? '-' }}</div>
                                    </li>
                                    <li>
                                        <div class="title">Relationship</div>
                                        <div class="text">{{ $Rec->cont_two_relation ?? '-' }}</div>
                                    </li>
                                    <li>
                                        <div class="title">Phone No.</div>
                                        <div class="text">{{ $Rec->cont_two_number ?? '-' }}</div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-5 d-flex">
                        <div class="card profile-box flex-fill">
                            <div class="card-body">
                                <h6 class="card-title border-bot">Bank Informations & Other
                                    @if (Auth::user()->role == 'A' || Auth::user()->role == 'R')
                                        <a href="#" class="edit-icon" data-bs-toggle="modal"
                                            data-bs-target="#bank_info_modal" onclick="GetBankInfo();"><i
                                                class="fa fa-pencil"></i></a>
                                    @endif
                                </h6>
                                <ul class="personal-info">
                                    <li>
                                        <div class="title">Bank Name<span style="float: right">:</span></div>
                                        <div class="text">{{ $Rec->BankName ?? '-' }}</div>
                                    </li>
                                    <li>
                                        <div class="title">Branch Name<span style="float: right">:</span>
                                        </div>
                                        <div class="text">{{ $Rec->BranchName }}</div>
                                    </li>
                                    <li>
                                        <div class="title">Bank account No.<span style="float: right">:</span>
                                        </div>
                                        <div class="text">{{ $Rec->AccountNumber ?? '-' }}</div>
                                    </li>
                                    <li>
                                        <div class="title">IFSC Code<span style="float: right">:</span></div>
                                        <div class="text">{{ $Rec->IFSCCode ?? '-' }}</div>
                                    </li>
                                    <li>
                                        <div class="title">PAN No.<span style="float: right">:</span></div>
                                        <div class="text">{{ $Rec->PAN ?? '-' }}</div>
                                    </li>
                                    <li>
                                        <div class="title">PF Account No.<span style="float: right">:</span></div>
                                        <div class="text">{{ $Rec->PFNumber ?? '-' }}</div>
                                    </li>
                                    <li>
                                        <div class="title">UAN No.<span style="float: right">:</span></div>
                                        <div class="text">{{ $Rec->UAN ?? '-' }}</div>
                                    </li>
                                    <li>
                                        <div class="title">ESIC No.<span style="float: right">:</span></div>
                                        <div class="text">{{ $Rec->ESICNumber ?? '-' }}</div>
                                    </li>
                                    <li>
                                        <div class="title">Passport<span style="float: right">:</span></div>
                                        <div class="text">{{ $Rec->Passport ?? '-' }}</div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-7 d-flex">
                        <div class="card profile-box flex-fill">
                            <div class="card-body">
                                <h6 class="card-title border-bot">Family Informations
                                    @if (Auth::user()->role == 'A' || Auth::user()->role == 'R')
                                        <a href="#" class="edit-icon" data-bs-toggle="modal"
                                            data-bs-target="#family_info_modal" onclick="GetFamily();"><i
                                                class="fa fa-pencil"></i></a>
                                    @endif
                                </h6>
                                <div class="table-responsive">
                                    <table class="table table-nowrap">
                                        <thead class="text-center bg-success bg-gradient text-light font-weight-normal">
                                            <tr>
                                                <th>Relation</th>
                                                <th>Name</th>
                                                <th>DOB</th>
                                                <th>Qulification</th>
                                                <th>Occupation</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if ($FamilyInfo != null)
                                                @foreach ($FamilyInfo as $item)
                                                    <tr>
                                                        <td>{{ $item->relation }}</td>
                                                        <td>{{ $item->name }}</td>
                                                        <td>{{ date('d-M-Y', strtotime($item->dob)) }}</td>
                                                        <td>{{ $item->qualification }}</td>
                                                        <td>{{ $item->occupation }}</td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="6">Record not found</td>
                                                </tr>
                                            @endif
                                        </tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="cand_contact">
                <div class="row">
                    <div class="col-md-6 d-flex">
                        <div class="card profile-box flex-fill">
                            <div class="card-body">
                                <h6 class="card-title border-bot">Current Address
                                    @if (Auth::user()->role == 'A' || Auth::user()->role == 'R')
                                        <a href="#" class="edit-icon" data-bs-toggle="modal"
                                            data-bs-target="#current_address_modal" onclick="GetCurrentAddress();">
                                            <i class="fa fa-pencil"></i>
                                        </a>
                                    @endif
                                </h6>
                                <ul class="personal-info">
                                    <li>
                                        <div class="title">Address <span style="float: right">:</span></div>
                                        <div class="text">{{ $Rec->pre_address ?? '-' }}</div>
                                    </li>
                                    <li>
                                        <div class="title">City <span style="float: right">:</span></div>
                                        <div class="text">{{ $Rec->pre_city ?? '-' }}</div>
                                    </li>
                                    <li>
                                        <div class="title">District <span style="float: right">:</span></div>
                                        <div class="text">
                                            @if ($Rec->pre_dist != null)
                                                {{ getDistrictName($Rec->pre_dist) }}
                                            @else
                                                -
                                            @endif
                                        </div>
                                    </li>
                                    <li>
                                        <div class="title">State <span style="float: right">:</span></div>
                                        <div class="text">
                                            @if ($Rec->pre_state != null)
                                                {{ getStateName($Rec->pre_state) }}
                                            @else
                                                -
                                            @endif
                                        </div>
                                    </li>
                                    <li>
                                        <div class="title">PinCode <span style="float: right">:</span></div>
                                        <div class="text">{{ $Rec->pre_pin ?? '-' }}</div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 d-flex">
                        <div class="card profile-box flex-fill">
                            <div class="card-body">
                                <h6 class="card-title border-bot">Permanent Address
                                    @if (Auth::user()->role == 'A' || Auth::user()->role == 'R')
                                        <a href="#" class="edit-icon" data-bs-toggle="modal"
                                            data-bs-target="#permanent_address_modal" onclick="GetPermanentAddress();">
                                            <i class="fa fa-pencil"></i>
                                        </a>
                                    @endif
                                </h6>
                                <ul class="personal-info">
                                    <li>
                                        <div class="title">Address <span style="float: right">:</span></div>
                                        <div class="text">{{ $Rec->perm_address ?? '-' }}</div>
                                    </li>
                                    <li>
                                        <div class="title">City <span style="float: right">:</span></div>
                                        <div class="text">{{ $Rec->perm_city ?? '-' }}</div>
                                    </li>
                                    <li>
                                        <div class="title">District <span style="float: right">:</span></div>
                                        <div class="text">
                                            @if ($Rec->perm_dist != null)
                                                {{ getDistrictName($Rec->perm_dist) }}
                                            @else
                                                -
                                            @endif
                                        </div>
                                    </li>
                                    <li>
                                        <div class="title">State <span style="float: right">:</span></div>
                                        <div class="text">
                                            @if ($Rec->perm_state != null)
                                                {{ getStateName($Rec->perm_state) }}
                                            @else
                                                -
                                            @endif
                                        </div>
                                    </li>
                                    <li>
                                        <div class="title">PinCode <span style="float: right">:</span></div>
                                        <div class="text">{{ $Rec->perm_pin ?? '-' }}</div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="cand_education">
                <div class="col-md-12 d-flex">
                    <div class="card profile-box flex-fill">
                        <div class="card-body">
                            <h6 class="card-title border-bot">Educational Details
                                @if (Auth::user()->role == 'A' || Auth::user()->role == 'R')
                                    <a href="#" class="edit-icon" data-bs-toggle="modal"
                                        data-bs-target="#education_info_modal" onclick="GetQualification();">
                                        <i class="fa fa-pencil"></i>
                                    </a>
                                @endif
                            </h6>
                            <div class="table-responsive">
                                <table class="table text-center">
                                    <thead class="text-center bg-success bg-gradient text-light">
                                        <tr>
                                            <td>Qualification</td>
                                            <td>Course</td>
                                            <td>Specialization</td>
                                            <td>Board/University</td>
                                            <td>Passing Year</td>
                                            <td>Percentage/Grade</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($Education as $item)
                                            <tr>
                                                <td>{{ $item->Qualification }}</td>
                                                <td>
                                                    @if ($item->Course != null)
                                                        {{ getEducationById($item->Course) }}
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td>
                                                    @if (is_null($item->Specialization))
                                                        -
                                                    @else
                                                        {{ getSpecializationbyId($item->Specialization) }}
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($item->Institute != null)
                                                        {{ getCollegeById($item->Institute) }}
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td>{{ $item->YearOfPassing ?? '-' }}</td>
                                                <td>{{ $item->CGPA ?? '-' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="cand_experience">
                <div class="row">
                    <div class="col-md-6 d-flex">
                        <div class="card profile-box flex-fill">
                            <div class="card-body">
                                <h6 class="card-title border-bot">Current Employement
                                    @if (Auth::user()->role == 'A' || Auth::user()->role == 'R')
                                        <a href="#" class="edit-icon" data-bs-toggle="modal"
                                            data-bs-target="#current_emp_modal" onclick="GetCurrentEmployementData();"><i
                                                class="fa fa-pencil"></i></a>
                                    @endif
                                </h6>
                                <ul class="personal-info">
                                    <li>
                                        <div class="title" style="width: 150px;">Name of Company <span
                                                style="float: right">:</span></div>
                                        <div class="text">{{ $Rec->PresentCompany ?? '-' }}</div>
                                    </li>
                                    <li>
                                        <div class="title" style="width: 150px;">Date of Joining <span
                                                style="float: right">:</span></div>
                                        <div class="text">{{ $Rec->JobStartDate ?? '-' }}</div>
                                    </li>
                                    <li>
                                        <div class="title" style="width: 150px;">Designation <span
                                                style="float: right">:</span></div>
                                        <div class="text">{{ $Rec->Designation ?? '-' }}</div>
                                    </li>

                                    <li>
                                        <div class="title" style="width: 150px;">Reporting to<span
                                                style="float: right">:</span></div>
                                        <div class="text">{{ $Rec->Reporting ?? '-' }} ,
                                            {{ $Rec->RepDesig ?? '-' }} </div>
                                    </li>
                                    <li>
                                        <div class="title" style="width: 150px;">Job Responsibility <span
                                                style="float: right">:</span></div>
                                        <div class="text">{{ $Rec->JobResponsibility ?? '-' }}</div>
                                    </li>
                                    <li>
                                        <div class="title" style="width: 150px;">Job Change Reason<span
                                                style="float: right">:</span></div>
                                        <div class="text">{{ $Rec->ResignReason ?? '-' }}</div>
                                    </li>
                                    <li>
                                        <div class="title" style="width: 150px;">Notice Period<span
                                                style="float: right">:</span></div>
                                        <div class="text">{{ $Rec->NoticePeriod ?? '-' }}</div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 d-flex">
                        <div class="card profile-box flex-fill">
                            <div class="card-body">
                                <h6 class="card-title border-bot">Present Salary Details
                                    @if (Auth::user()->role == 'A' || Auth::user()->role == 'R')
                                        <a href="#" class="edit-icon" data-bs-toggle="modal"
                                            data-bs-target="#current_salary_modal" onclick="GetPresentSalaryDetails();"><i
                                                class="fa fa-pencil"></i></a>
                                    @endif
                                </h6>
                                <ul class="personal-info">
                                    <li>
                                        <div class="title" style="width: 200px;">Salary (Per Month)<span
                                                style="float: right">:</span></div>
                                        <div class="text">{{ $Rec->GrossSalary ?? '-' }}</div>
                                    </li>
                                    <li>
                                        <div class="title" style="width: 200px;">Annual Package (CTC)<span
                                                style="float: right">:</span></div>
                                        <div class="text">{{ $Rec->CTC ?? '-' }}</div>
                                    </li>
                                    <li>
                                        <div class="title" style="width: 200px;">DA@ headquarter<span
                                                style="float: right">:</span></div>
                                        <div class="text">{{ $Rec->DAHq ?? '-' }}</div>
                                    </li>
                                    <li>
                                        <div class="title" style="width: 200px;">DA outside headquarter <span
                                                style="float: right">:</span></div>
                                        <div class="text">{{ $Rec->DAOutHq ?? '-' }}</div>
                                    </li>
                                    <li>
                                        <div class="title" style="width: 200px;">Petrol Allowances <span
                                                style="float: right">:</span></div>
                                        <div class="text">{{ $Rec->PetrolAlw ?? '-' }}</div>
                                    </li>
                                    <li>
                                        <div class="title" style="width: 200px;">Phone Allowances <span
                                                style="float: right">:</span></div>
                                        <div class="text">{{ $Rec->PhoneAlw ?? '-' }}</div>
                                    </li>
                                    <li>
                                        <div class="title" style="width: 200px;">Hotel Eligibility<span
                                                style="float: right">:</span></div>
                                        <div class="text">{{ $Rec->HotelElg ?? '-' }}</div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 d-flex">
                    <div class="card flex-fill">
                        <div class="card-body">
                            <h6 class="card-title border-bot">Previous Employement Records <small>(except the
                                    present)</small>
                                @if (Auth::user()->role == 'A' || Auth::user()->role == 'R')
                                    <a href="#" class="edit-icon" data-bs-toggle="modal"
                                        data-bs-target="#work_exp_modal" onclick="getWorkExp();">
                                        <i class="fa fa-pencil"></i>
                                    </a>
                                @endif
                            </h6>

                            <div class="table-responsive">
                                <table class="table text-center">
                                    <thead class="text-center bg-success bg-gradient text-light">
                                        <tr>
                                            <td style="width: 5%">S.No.</td>
                                            <td style="width: 20%">Company Name</td>
                                            <td style="width: 15%">Designation</td>
                                            <td style="width: 10%">Gross Monthly Salary</td>
                                            <td style="width: 10%">Anual CTC</td>
                                            <td style="width: 10%">From</td>
                                            <td style="width: 10%">To</td>
                                            <td style="width: 20%">Reason for Leaving</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $i = 1;
                                        @endphp
                                        @foreach ($Experience as $item)
                                            <tr>
                                                <td>{{ $i }}</td>
                                                <td>{{ $item->company }}</td>
                                                <td>{{ $item->desgination }}</td>
                                                <td>{{ $item->gross_mon_sal }}</td>
                                                <td>{{ $item->annual_ctc }}</td>
                                                <td>{{ $item->job_start }}</td>
                                                <td>{{ $item->job_end }}</td>
                                                <td>{{ $item->reason_fr_leaving }}</td>
                                            </tr>
                                            @php
                                                $i++;
                                            @endphp
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="col-md-12 d-flex">
                    <div class="card  flex-fill">
                        <div class="card-body">
                            <h6 class="card-title border-bot">Training & Practical Experience <small>(Other than regular
                                    jobs)</small>
                                @if (Auth::user()->role == 'A' || Auth::user()->role == 'R')
                                    <a href="#" class="edit-icon" data-bs-toggle="modal"
                                        data-bs-target="#training_modal" onclick="getTraining();">
                                        <i class="fa fa-pencil"></i>
                                    </a>
                                @endif
                            </h6>

                            <div class="table-responsive">
                                <table class="table text-center">
                                    <thead class="text-center bg-success bg-gradient text-light">
                                        <tr>
                                            <td style="width: 5%">S.No.</td>
                                            <td style="width: 20%">Training Nature</td>
                                            <td style="width: 15%">Organization</td>
                                            <td style="width: 10%">From</td>
                                            <td style="width: 10%">To</td>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $i = 1;
                                        @endphp
                                        @foreach ($Training as $item)
                                            <tr>
                                                <td>{{ $i }}</td>
                                                <td>{{ $item->training }}</td>
                                                <td>{{ $item->organization }}</td>
                                                <td>{{ $item->from }}</td>
                                                <td>{{ $item->to }}</td>
                                            </tr>
                                            @php
                                                $i++;
                                            @endphp
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="cand_reference">
                <div class="col-md-12 d-flex">
                    <div class="card  flex-fill">
                        <div class="card-body">
                            <h6 class="card-title border-bot">Previous Organization Reference
                                @if (Auth::user()->role == 'A' || Auth::user()->role == 'R')
                                    <a href="#" class="edit-icon" data-bs-toggle="modal"
                                        data-bs-target="#pre_org_ref_modal" onclick="getPreOrgRef();">
                                        <i class="fa fa-pencil"></i>
                                    </a>
                                @endif
                            </h6>

                            <div class="table-responsive">
                                <table class="table">
                                    <thead class="text-center bg-success bg-gradient text-light">
                                        <tr>
                                            <td>S.No.</td>
                                            <td>Name</td>
                                            <td>Company</td>
                                            <td>Designation</td>
                                            <td>Contact No.</td>
                                            <td>Email</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $i = 1;
                                        @endphp
                                        @foreach ($PreRef as $item)
                                            <tr>
                                                <td>{{ $i }}</td>
                                                <td>{{ $item->name }}</td>
                                                <td>{{ $item->company }}</td>
                                                <td>{{ $item->designation }}</td>
                                                <td>{{ $item->contact }}</td>
                                                <td>{{ $item->email }}</td>
                                            </tr>
                                            @php
                                                $i++;
                                            @endphp
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 d-flex">
                        <div class="card  flex-fill">
                            <div class="card-body">
                                <h6 class="card-title border-bot">Acquaintances or relatives working with
                                    VNR Group Companies
                                    @if (Auth::user()->role == 'A' || Auth::user()->role == 'R')
                                        <a href="#" class="edit-icon" data-bs-toggle="modal"
                                            data-bs-target="#vnr_ref_modal" onclick="getVnrRef();"><i
                                                class="fa fa-pencil"></i></a>
                                    @endif

                                </h6>

                                <table class="table table-bordered">
                                    <thead class="text-center bg-success bg-gradient text-light">
                                        <tr class="text-center">
                                            <th>Name</th>
                                            <th>Mobile</th>
                                            <th>Email</th>
                                            <th>VNR Group /<br>Company Name</th>
                                            <th>Designation</th>
                                            <th>Location</th>
                                            <th>Your Relationship <br>with person mentioned</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-center">
                                        @foreach ($VnrRef as $item)
                                            <tr>
                                                <td>{{ $item->name }}</td>
                                                <td>{{ $item->contact }}</td>
                                                <td>{{ $item->email }}</td>
                                                <td>{{ $item->company }}
                                                    {{ $item->company == 'Other' ? '/ ' . $item->other_company : '' }}
                                                </td>
                                                </td>
                                                <td>{{ $item->designation }}</td>
                                                <td>{{ $item->location }}</td>
                                                <td>{{ $item->rel_with_person }}</td>


                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 d-flex">
                        <div class="card  flex-fill">
                            <div class="card-body">
                                <h6 class="card-title border-bot">Acquaintances or relatives associated with
                                    VNR as business associates
                                    @if (Auth::user()->role == 'A' || Auth::user()->role == 'R')
                                        <a href="#" class="edit-icon" data-bs-toggle="modal"
                                            data-bs-target="#vnr_business_ref_modal" onclick="getVnrRef_Business();"><i
                                                class="fa fa-pencil"></i></a>
                                    @endif
                                </h6>

                                <table class="table">
                                    <thead class="text-center bg-success bg-gradient text-light">
                                        <tr class="text-center">
                                            <th>Name</th>
                                            <th>Mobile</th>
                                            <th>Email</th>
                                            <th>Business Relation <br>With VNR</th>
                                            <th>Location of Business /
                                                acquaintances
                                            </th>
                                            <th>Your Relationship <br>with person mentioned</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-center">
                                        @foreach ($VnrBusinessRef as $item)
                                            <tr>
                                                <td>{{ $item->Name ?? '' }}</td>
                                                <td>{{ $item->Mobile ?? '' }}</td>
                                                <td>{{ $item->Email ?? '' }}</td>
                                                <td>{{ $item->BusinessRelation ?? '' }}</td>
                                                </td>
                                                <td>{{ $item->Location ?? '' }}</td>
                                                <td>{{ $item->PersonRelation ?? '' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 d-flex">
                        <div class="card  flex-fill">
                            <div class="card-body">
                                <h6 class="card-title border-bot">Relatives or acquaintances is/are working
                                    or associated with any other Seed Company
                                    @if (Auth::user()->role == 'A' || Auth::user()->role == 'R')
                                        <a href="#" class="edit-icon" data-bs-toggle="modal"
                                            data-bs-target="#other_seed_modal" onclick="getOtherSeed();"><i
                                                class="fa fa-pencil"></i></a>
                                    @endif
                                </h6>

                                <table class="table">
                                    <thead class="text-center bg-success bg-gradient text-light">
                                        <tr class="text-center">
                                            <th>Name</th>
                                            <th>Mobile</th>
                                            <th>Email</th>
                                            <th>Company Name</th>
                                            <th>Designation</th>
                                            <th>Location</th>
                                            <th>Your Relationship <br>with person mentioned</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-center">
                                        @foreach ($OtherSeed as $item)
                                            <tr>
                                                <td>{{ $item->Name ?? '' }}</td>
                                                <td>{{ $item->Mobile ?? '' }}</td>
                                                <td>{{ $item->Email ?? '' }}</td>
                                                <td>{{ $item->company_name ?? '' }}</td>
                                                <td>{{ $item->Designation ?? '' }}</td>
                                                <td>{{ $item->Location ?? '' }}</td>
                                                <td>{{ $item->Relation ?? '' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="cand_other">
                <div class="row">
                    <div class="col-md-12 d-flex">
                        <div class="card flex-fill">
                            <div class="card-body">
                                <h6 class="card-title border-bot">Language Proficiency
                                    @if (Auth::user()->role == 'A' || Auth::user()->role == 'R')
                                        <a href="#" class="edit-icon" data-bs-toggle="modal"
                                            data-bs-target="#language_modal" onclick="getLanguageProficiency();">
                                            <i class="fa fa-pencil"></i>
                                        </a>
                                    @endif
                                </h6>
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead class="text-center bg-success bg-gradient text-light">
                                            <tr>
                                                <td>S.No.</td>
                                                <td>Language</td>
                                                <td>Reading</td>
                                                <td>Writing</td>
                                                <td>Speaking</td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $i = 1;
                                            @endphp
                                            @foreach ($lang as $item)
                                                <tr>
                                                    <td>{{ $i }}</td>
                                                    <td>{{ $item->language }}</td>
                                                    <td>{{ $item->read == 1 ? 'Yes' : 'No' }}</td>
                                                    <td>{{ $item->write == 1 ? 'Yes' : 'No' }}</td>
                                                    <td>{{ $item->speak == 1 ? 'Yes' : 'No' }}</td>
                                                </tr>
                                                @php
                                                    $i++;
                                                @endphp
                                            @endforeach
                                        </tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="row">
                    <div class="col-md-12 d-flex">
                        <div class="card profile-box flex-fill">
                            <div class="card-body">
                                <h6 class="card-title border-bot">About Yourself
                                    @if (Auth::user()->role == 'A' || Auth::user()->role == 'R')
                                        <a href="#" class="edit-icon" data-bs-toggle="modal"
                                            data-bs-target="#about_modal"><i class="fa fa-pencil"></i></a>
                                    @endif
                                </h6>

                                <div class="table-responsive">
                                    <table class="table">
                                        <tbody>
                                            <tr style="background-color: #F1F8E9">
                                                <td class="fw-bold">
                                                    Q1. What is your aim in life?
                                                </td>
                                            </tr>
                                            <tr style="background-color: #F9FBE7">
                                                <td>
                                                    &ensp;&ensp;&ensp;{{ $AboutAns->AboutAim ?? '' }}
                                                </td>
                                            </tr>
                                            <tr style="background-color: #F1F8E9">
                                                <td class="fw-bold">
                                                    Q2. What are you hobbies and interest?
                                                </td>
                                            </tr>
                                            <tr style="background-color: #F9FBE7">
                                                <td>
                                                    &ensp;&ensp;&ensp;{{ $AboutAns->AboutHobbi ?? '' }}
                                                </td>
                                            </tr>
                                            <tr style="background-color: #F1F8E9">
                                                <td class="fw-bold">
                                                    Q3. Where do you see yourself 5 Years from now?
                                                </td>
                                            </tr>
                                            <tr style="background-color: #F9FBE7">
                                                <td>
                                                    &ensp;&ensp;&ensp;{{ $AboutAns->About5Year ?? '' }}
                                                </td>
                                            </tr>
                                            <tr style="background-color: #F1F8E9">
                                                <td class="fw-bold">
                                                    Q4. What are your greatest personal assets (qualities, skills,
                                                    abilities) which make you successful in the jobs you take up?
                                                </td>
                                            </tr>
                                            <tr style="background-color: #F9FBE7">
                                                <td>
                                                    &ensp;&ensp;&ensp;{{ $AboutAns->AboutAssets ?? '' }}
                                                </td>
                                            </tr>
                                            <tr style="background-color: #F1F8E9">
                                                <td class="fw-bold">
                                                    Q5. What are your areas where you think you need to improve yourself?
                                                </td>
                                            </tr>
                                            <tr style="background-color: #F9FBE7">
                                                <td>
                                                    &ensp;&ensp;&ensp;{{ $AboutAns->AboutImprovement ?? '' }}
                                                </td>
                                            </tr>
                                            <tr style="background-color: #F1F8E9">
                                                <td class="fw-bold">
                                                    Q6. What are your Strengths?
                                                </td>
                                            </tr>
                                            <tr style="background-color: #F9FBE7">
                                                <td>
                                                    &ensp;&ensp;&ensp;{{ $AboutAns->AboutStrength ?? '' }}
                                                </td>
                                            </tr>
                                            <tr style="background-color: #F1F8E9">
                                                <td class="fw-bold">
                                                    Q7. In the past or at present, have/are you suffered /suffering from,
                                                    any form of physical disability or any minor or major illness or
                                                    deficiency?
                                                </td>
                                            </tr>
                                            <tr style="background-color: #F9FBE7">
                                                <td>
                                                    &ensp;&ensp;&ensp;{{ $AboutAns->AboutDeficiency ?? '' }}
                                                </td>
                                            </tr>
                                            <tr style="background-color: #F1F8E9">
                                                <td class="fw-bold">
                                                    Q8. Have you Been criminally prosecuted? if so, give details separately.
                                                </td>
                                            </tr>
                                            <tr style="background-color: #F9FBE7">
                                                <td>
                                                    &ensp;&ensp;&ensp;
                                                    @if ($AboutAns != null)
                                                        {{ $AboutAns->CriminalChk == 'Y' ? 'Yes' : 'No' }}
                                                    @endif

                                                </td>
                                            </tr>
                                            <tr style="background-color: #F1F8E9">
                                                <td class="fw-bold">
                                                    Q9. Do You have a valid driving licence?
                                                </td>
                                            </tr>
                                            <tr style="background-color: #F9FBE7">
                                                <td>
                                                    &ensp;&ensp;&ensp;{{ $AboutAns->AboutDeficiency ?? '' }}
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="vehicle_info">
                <div class="row">
                    <div class="col-md-6 d-flex">
                        <div class="card flex-fill">
                            <div class="card-body">
                                <h6 class="card-title border-bot">2 Wheeler</h6>
                                <div class="table-responsive">
                                    <table class="table">
                                        <tr>
                                            <th>Ownership</th>
                                            <td>{{ $vehicle_info->ownership ?? '' }}</td>
                                            <th>Brand</th>
                                            <td>{{ $vehicle_info->brand ?? '' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Model</th>
                                            <td>{{ $vehicle_info->model_name ?? '' }}</td>
                                            <th>Model No</th>
                                            <td>{{ $vehicle_info->model_no ?? '' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Dealer Name</th>
                                            <td>{{ $vehicle_info->dealer_name ?? '' }}</td>
                                            <th>Dealer Contact</th>
                                            <td>{{ $vehicle_info->dealer_contact ?? '' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Purchase Date</th>
                                            <td>{{ $vehicle_info->purchase_date ?? '' }}</td>
                                            <th>Price</th>
                                            <td>{{ $vehicle_info->price ?? '' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Invoice No</th>
                                            <td>{{ $vehicle_info->bill_no ?? '' }}</td>
                                            <th>Fuel Type</th>
                                            <td>{{ $vehicle_info->fuel_type ?? '' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Registration Number</th>
                                            <td>{{ $vehicle_info->registration_no ?? '' }}</td>
                                            <th>Registration Date</th>
                                            <td>{{ $vehicle_info->registration_date ?? '' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Current ODO Meter</th>
                                            <td colspan="3">{{ $vehicle_info->current_odo_meter ?? '' }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 d-flex">
                        <div class="card flex-fill">
                            <div class="card-body">
                                <h6 class="card-title border-bot">4 Wheeler</h6>
                                <div class="table-responsive">
                                    <table class="table">
                                        <tr>
                                            <th>Ownership</th>
                                            <td>{{ $vehicle_info->four_ownership ?? '' }}</td>
                                            <th>Brand</th>
                                            <td>{{ $vehicle_info->four_brand ?? '' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Model</th>
                                            <td>{{ $vehicle_info->four_model_name ?? '' }}</td>
                                            <th>Model No</th>
                                            <td>{{ $vehicle_info->four_model_no ?? '' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Dealer Name</th>
                                            <td>{{ $vehicle_info->four_dealer_name ?? '' }}</td>
                                            <th>Dealer Contact</th>
                                            <td>{{ $vehicle_info->four_dealer_contact ?? '' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Purchase Date</th>
                                            <td>{{ $vehicle_info->four_purchase_date ?? '' }}</td>
                                            <th>Price</th>
                                            <td>{{ $vehicle_info->four_price ?? '' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Invoice No</th>
                                            <td>{{ $vehicle_info->four_bill_no ?? '' }}</td>
                                            <th>Fuel Type</th>
                                            <td>{{ $vehicle_info->four_fuel_type ?? '' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Registration Number</th>
                                            <td>{{ $vehicle_info->four_registration_no ?? '' }}</td>
                                            <th>Registration Date</th>
                                            <td>{{ $vehicle_info->four_registration_date ?? '' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Current ODO Meter</th>
                                            <td colspan="3">{{ $vehicle_info->four_current_odo_meter ?? '' }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="cand_document">
                <div class="row">
                    <div class="col-md-6 d-flex">
                        <div class="card profile-box flex-fill">
                            <div class="card-body">
                                <h6 class="card-title border-bot">Links</h6>
                                <ul class="personal-info">
                                    @if ($Rec->Type == 'Manual Entry')
                                        @php
                                            $JCId = base64_encode($Rec->JCId);
                                        @endphp
                                        <li>
                                            <div class="title" style="width: 150px;">Application Form <span
                                                    style="float: right">:</span></div>
                                            <div class="text"><input type="text" id="link{{ $Rec->JCId }}"
                                                    value="{{ url('jobportal/jobapply?jcid=' . $JCId . '') }}"
                                                    class="frminp d-inline">
                                                <button onclick="copylink({{ $Rec->JCId }})"
                                                    class="frmbtn btn btn-sm btn-secondary"> Copy Link
                                                </button>
                                            </div>
                                        </li>
                                    @endif

                                    @if ($OfBasic != null && $OfBasic->OfferLtrGen == '1')
                                        <li>
                                            <div class="title" style="width: 150px;">Offer Letter<span
                                                    style="float: right">:</span></div>
                                            <div class="text"><input type="text" name="" id="oflink"
                                                    class="frminp d-inline"
                                                    value="{{ route('candidate-offer-letter') }}?jaid={{ $sendingId }}">
                                                <button class="frmbtn btn btn-sm btn-secondary"
                                                    onclick="copyOfLink();">Copy
                                                    Link
                                                </button>
                                            </div>
                                        </li>
                                    @endif

                                    <li>
                                        <div class="title" style="width: 150px;">Interview Form<span
                                                style="float: right">:</span></div>
                                        <div class="text"><input type="text" name="" id="interviewlink"
                                                class="frminp d-inline"
                                                value="{{ route('candidate-interview-form') }}?jaid={{ $sendingId }}">
                                            <button class="frmbtn btn btn-sm btn-secondary"
                                                onclick="copyJIntFrmLink();">Copy
                                                Link
                                            </button>
                                        </div>
                                    </li>

                                    <li>
                                        <div class="title" style="width: 150px;">FIRO B Test<span
                                                style="float: right">:</span></div>
                                        <div class="text">

                                            <input type="text" name="" id="firoblink" class="frminp d-inline"
                                                value="{{ route('firo_b') }}?jcid={{ $firobid }}">
                                            <button class="frmbtn btn btn-sm btn-secondary"
                                                onclick="copyFiroBlink();">Copy
                                                Link
                                            </button>


                                        </div>
                                    </li>
                                    @if ($Rec->InterviewSubmit == 1 || ($OfBasic != null && $OfBasic->JoiningFormSent == 'Yes'))
                                        <li>
                                            <div class="title" style="width: 150px;">Joining Form<span
                                                    style="float: right">:</span></div>
                                            <div class="text"><input type="text" name="" id="jflink"
                                                    class="frminp d-inline"
                                                    value="{{ route('candidate-joining-form') }}?jaid={{ $sendingId }}">
                                                <button class="frmbtn btn btn-sm btn-secondary"
                                                    onclick="copyJFrmLink();">Copy
                                                    Link
                                                </button>

                                                @if ($Rec->FinalSubmit == 1 && $OfBasic->ForwardToESS == 'No')
                                                    <button class="frmbtn btn btn-primary btn-sm"
                                                        id="open_joining_form">Re-Open Joining Form
                                                    </button>
                                                @endif
                                            </div>
                                        </li>

                                    @endif
                                    <li>
                                        <div class="title" style="width: 150px;">Upload Documents<span
                                                style="float: right">:</span></div>
                                        <div class="text">
                                            <a href="#" class="edit-icon" data-bs-toggle="modal"
                                                style="float:left" data-bs-target="#document_modal">
                                                <i class="fa fa-pencil"></i>
                                            </a>
                                        </div>
                                    </li>
                                    {{-- <li>
                                         <div class="title" style="width: 150px;">Vehicle Form<span
                                                 style="float: right">:</span></div>
                                         <div class="text"><input type="text" name="" id="vflink"
                                                                  class="frminp d-inline"
                                                                  value="{{ route('candidate-vehicle-form') }}?jcid={{ $firobid }}">
                                             <button class="frmbtn btn btn-sm btn-secondary"
                                                     onclick="copyVFrmLink();">Copy
                                                 Link
                                             </button>

                                             <button class="frmbtn btn btn-primary btn-sm"
                                                     id="send_vehicle_form" onclick="sendVehicleForm({{ $Rec->JCId }});">
                                                 Send Vehicle Form
                                             </button>

                                         </div>
                                     </li> --}}
                                </ul>

                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 d-flex">
                        <div class="card profile-box flex-fill" style="min-height:17px;">
                            <div class="card-body">
                                <h6 class="card-title border-bot">Interview Documents

                                </h6>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead class="text-center bg-success bg-gradient text-light">
                                            <tr>
                                                <th style="width: 5%" class=" text-center">S.No.</th>
                                                <th class="text-center" style="width: 20%">Document Name</th>
                                                <th class="text-center">View</th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="text-center">1</td>
                                                <td style="width:50%">Interview Application Form</td>
                                                <td style="width: 10%; text-align:center" class="text-center">
                                                    @if ($Rec->InterviewSubmit == 1)
                                                        @php
                                                            $sendingId = base64_encode($Rec->JAId);
                                                        @endphp

                                                        <a href="{{ route('interview_form_detail') }}?jaid={{ $sendingId }}"
                                                            target="_blank">View</a>
                                                    @endif
                                                </td>
                                            </tr>

                                            <tr>
                                                <td class="text-center">2</td>
                                                <td>Firo B</td>
                                                <td style="width: 10%; text-align:center">
                                                    @if ($Rec->FIROB_Test == 1)
                                                        @php
                                                            $firobUserCount = DB::table('firob_user')
                                                                ->where('userid', $Rec->JCId)
                                                                ->count();

                                                        @endphp
                                                        @if ($firobUserCount == 54)
                                                            <span>
                                                                <a href="javascript:void(0);"
                                                                    onclick='window.open("{{ route('firob_result') }}?jcid={{ $Rec->JCId }}", "", "width=750,height=900");'>Result
                                                                    1</a>
                                                            </span>
                                                            |
                                                            <span>
                                                                <a href="javascript:void(0);"
                                                                    onclick='window.open("{{ route('firob_result_summery') }}?jcid={{ $Rec->JCId }}", "", "width=750,height=900");'>Result
                                                                    2</a>
                                                            </span>
                                                        @else
                                                            <a href="javascript:void(0);" class="text-danger"
                                                                onclick="delete_firob()">
                                                                <i class="fa fa-trash text-danger"></i> Reset..?
                                                            </a>
                                                        @endif
                                                    @endif

                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-center">3</td>
                                                <td>Test Papers</td>
                                                <td style="width: 10%; text-align:center">
                                                    @if ($Docs != null && $Docs->Test_Paper != null)
                                                        <a href="{{ url('file-view/Documents/' . $Docs->Test_Paper) }}"
                                                            class="view-pdf">View</a>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-center">4</td>
                                                <td>Interview Assessment Sheet</td>
                                                <td style="width: 10%; text-align:center">
                                                    @if ($Docs != null && $Docs->IntervAssessment != null)
                                                        <a href="{{ url('file-view/Documents/' . $Docs->IntervAssessment) }}"
                                                            class="view-pdf">View</a>
                                                    @endif
                                                </td>
                                            </tr>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 d-flex">
                        <div class="card profile-box flex-fill" style="min-height:17px;">
                            <div class="card-body">
                                <h6 class="card-title border-bot">Joining & Onboarding Documents

                                </h6>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead class="text-center bg-success bg-gradient text-light">
                                            <tr>
                                                <th style="width: 5%" class=" text-center">S.No.</th>
                                                <th class="text-center" style="width: 20%">Document Name</th>
                                                <th class="text-center">View</th>
                                                <th style="width: 5%" class=" text-center">S.No.</th>
                                                <th class="text-center" style="width: 20%">Document Name</th>
                                                <th class="text-center">View</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class=" text-center">1</td>
                                                <td>Offer Letter</td>
                                                <td style="width: 10%; text-align:center">
                                                    @if ($OfBasic != null && $OfBasic->OfferLtrGen == 1)
                                                        <a href="{{ route('offer_ltr_print') }}?jaid={{ $Rec->JAId }}"
                                                            class="btn btn-link btn-sm">View</a>
                                                    @endif
                                                </td>
                                                <td class=" text-center">2</td>
                                                <td>Joining Form</td>
                                                <td style="width: 10%; text-align:center">
                                                    @if ($Rec->FinalSubmit == 1)
                                                        <a href="{{ route('joining_form_print') }}?jaid={{ $sendingId }}"
                                                            target="_blank" class="btn btn-link btn-sm">View</a>
                                                    @endif
                                                </td>
                                            </tr>

                                            <tr>
                                                <td class=" text-center">3</td>
                                                <td>Appointment Letter</td>
                                                <td style="width: 10%; text-align:center" colspan="4">
                                                    @if ($Rec->AppLtrGen == 'Yes')
                                                        <a href="{{ route('appointment_ltr_print') }}?jaid={{ $Rec->JAId }}"
                                                            target="_blank">View</a>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class=" text-center">4</td>
                                                <td>Service Agreement (E Stamp)</td>
                                                <td style="width: 10%; text-align:center">
                                                    @if ($Rec->AgrLtrGen == 'Yes')
                                                        <a href="{{ route('service_agreement_print_e_first') }}?jaid={{ base64_encode($Rec->JAId) }}"
                                                            target="_blank">First Page</a> |
                                                        <a href="{{ route('service_agreement_print') }}?jaid={{ base64_encode($Rec->JAId) }}"
                                                            target="_blank">Rest All</a>
                                                    @endif
                                                </td>
                                                <td class=" text-center">5</td>
                                                <td>Service Agreement (Old Stamp)</td>
                                                <td style="width: 10%; text-align:center">
                                                    @if ($Rec->AgrLtrGen == 'Yes')
                                                        <a href="{{ route('service_agreement_print_old_stamp') }}?jaid={{ base64_encode($Rec->JAId) }}"
                                                            target="_blank">
                                                            View</a>
                                                    @endif
                                                </td>
                                            </tr>

                                            <tr>
                                                <td class=" text-center">6</td>
                                                <td>Service Bond (E Stamp)</td>
                                                <td style="width: 10%; text-align:center">
                                                    @if ($Rec->BLtrGen == 'Yes')
                                                        <a href="{{ route('service_bond_print_e_first') }}?jaid={{ base64_encode($Rec->JAId) }}"
                                                            target="_blank">First Page</a> |
                                                        <a href="{{ route('service_bond_print') }}?jaid={{ base64_encode($Rec->JAId) }}"
                                                            target="_blank">Rest All</a>
                                                    @endif
                                                </td>
                                                <td class=" text-center">7</td>
                                                <td>Service Bond(Old Stamp)</td>
                                                <td style="width: 10%; text-align:center">
                                                    @if ($Rec->BLtrGen == 'Yes')
                                                        <a href="{{ route('service_bond_print_old_stamp') }}?jaid={{ base64_encode($Rec->JAId) }}"
                                                            target="_blank">
                                                            View</a>
                                                    @endif
                                                </td>
                                            </tr>

                                            <tr>
                                                <td class=" text-center">9</td>
                                                <td>Confidentiality Agreement (E Stamp)</td>
                                                <td style="width: 10%; text-align:center">
                                                    @if ($Rec->ConfLtrGen == 'Yes')
                                                        <a href="{{ route('conf_agreement_print_e_first') }}?jaid={{ base64_encode($Rec->JAId) }}"
                                                            target="_blank">
                                                            First Page</a> |
                                                        <a href="{{ route('conf_agreement_print') }}?jaid={{ base64_encode($Rec->JAId) }}"
                                                            target="_blank">
                                                            Rest All</a>
                                                    @endif
                                                </td>
                                                <td class=" text-center">10</td>
                                                <td>Confidentiality Agreement (Old Stamp)</td>
                                                <td style="width: 10%; text-align:center">
                                                    @if ($Rec->ConfLtrGen == 'Yes')
                                                        <a href="{{ route('conf_agreement_print_old_stamp') }}?jaid={{ base64_encode($Rec->JAId) }}"
                                                            target="_blank">
                                                            View</a>
                                                    @endif
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 d-flex">
                        <div class="card profile-box flex-fill" style="min-height:17px;">
                            <div class="card-body">
                                <h6 class="card-title border-bot">Statutory Documents</h6>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead class="text-center bg-success bg-gradient text-light">
                                            <tr>
                                                <th style="width: 5%" class=" text-center">S.No.</th>
                                                <th class="text-center" style="width: 20%">Document Name</th>
                                                <th class="text-center">View</th>
                                                <th style="width: 5%" class=" text-center">S.No.</th>
                                                <th class="text-center" style="width: 20%">Document Name</th>
                                                <th class="text-center">View</th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class=" text-center">1</td>
                                                <td>PF Form 2</td>
                                                <td style="width: 10%; text-align:center">
                                                    @if ($Docs != null && $Docs->PF_Form2 != null)
                                                        <a href="{{ url('file-view/Documents/' . $Docs->PF_Form2) }}"
                                                            class="view-pdf">View</a>
                                                    @endif
                                                </td>
                                                <td class=" text-center">2</td>
                                                <td>PF Form 11</td>
                                                <td style="width: 10%; text-align:center">
                                                    @if ($Docs != null && $Docs->PF_Form11 != null)
                                                        <a href="{{ url('file-view/Documents/' . $Docs->PF_Form11) }}"
                                                            class="view-pdf">View</a>
                                                    @endif
                                                </td>

                                            </tr>
                                            <tr>
                                                <td class=" text-center">3</td>
                                                <td>Gratutity Form</td>
                                                <td style="width: 10%; text-align:center">
                                                    @if ($Docs != null && $Docs->Gratutity != null)
                                                        <a href="{{ url('file-view/Documents/' . $Docs->Gratutity) }}"
                                                            class="view-pdf">View</a>
                                                    @endif
                                                </td>
                                                <td class=" text-center">4</td>
                                                <td>ESIC</td>
                                                <td style="width: 10%; text-align:center">
                                                    @if ($Docs != null && $Docs->ESIC != null)
                                                        <a href="{{ url('file-view/Documents/' . $Docs->ESIC) }}"
                                                            class="view-pdf">View</a>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class=" text-center">5</td>
                                                <td>ESIC_Family</td>
                                                <td style="width: 10%; text-align:center">
                                                    @if ($Docs != null && $Docs->ESIC_Family != null)
                                                        <a href="{{ url('file-view/Documents/' . $Docs->ESIC_Family) }}"
                                                            class="view-pdf">View</a>
                                                    @endif
                                                </td>
                                                <td class=" text-center">6</td>
                                                <td>PF- E nomination Form</td>
                                                <td style="width: 10%; text-align:center">
                                                    @if ($Docs != null && $Docs->PFeNomination != null)
                                                        <a href="{{ url('file-view/Documents/' . $Docs->PFeNomination) }}"
                                                            class="view-pdf">View</a>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class=" text-center">7</td>
                                                <td>Form 16(From Pervious Employer)</td>
                                                <td style="width: 10%; text-align:center">
                                                    @if ($Docs != null && $Docs->Form16 != null)
                                                        <a href="{{ url('file-view/Documents/' . $Docs->Form16) }}"
                                                            class="view-pdf">View</a>
                                                    @endif
                                                </td>
                                                <td class=" text-center">8</td>
                                                <td>EPFO Joint Request</td>
                                                <td style="width: 10%; text-align:center">
                                                    @if ($Docs != null && $Docs->Epfo_Joint != null)
                                                        <a href="{{ url('file-view/Documents/' . $Docs->Epfo_Joint) }}"
                                                            class="view-pdf">View</a>
                                                    @endif
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 d-flex">
                        <div class="card profile-box flex-fill" style="min-height:17px;">
                            <div class="card-body">
                                <h6 class="card-title border-bot">Previous Employment Documents</h6>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead class="text-center bg-success bg-gradient text-light">
                                            <tr>
                                                <th style="width: 5%" class=" text-center">S.No.</th>
                                                <th class="text-center" style="width: 20%">Document Name</th>
                                                <th class="text-center">View</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class=" text-center">1</td>
                                                <td>Offer or Appointment letter</td>
                                                <td style="width: 10%; text-align:center">
                                                    @if ($Docs != null && $Docs->OfferLtr != null)
                                                        {{-- <a href="{{ url('file-view/Documents/' . $Docs->OfferLtr) }}"
                                                            class="view-pdf">View</a> --}}
                                                            <a href="{{ url('file-view/Documents/' . $Docs->OfferLtr) }}" class="view-pdf">View</a>

                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class=" text-center">2</td>
                                                <td>Relieving Letter</td>
                                                <td style="width: 10%; text-align:center">
                                                    @if ($Docs != null && $Docs->RelievingLtr != null)
                                                        <a href="{{ url('file-view/Documents/' . $Docs->RelievingLtr) }}"
                                                            class="view-pdf">View</a>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class=" text-center">3</td>
                                                <td>Pay/Salary slip</td>
                                                <td style="width: 10%; text-align:center">
                                                    @if ($Docs != null && $Docs->SalarySlip != null)
                                                        <a href="{{ url('file-view/Documents/' . $Docs->SalarySlip) }}"
                                                            class="view-pdf">View</a>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class=" text-center">4</td>
                                                <td>Appraisal or last increment letter</td>
                                                <td style="width: 10%; text-align:center">
                                                    @if ($Docs != null && $Docs->AppraisalLtr != null)
                                                        <a href="{{ url('file-view/Documents/' . $Docs->AppraisalLtr) }}"
                                                            class="view-pdf">View</a>
                                                    @endif
                                                </td>
                                            </tr>

                                            <tr>
                                                <td class=" text-center">4</td>
                                                <td>Resignation Acceptance by Recent Employer</td>
                                                <td style="width: 10%; text-align:center">
                                                    @if ($Docs != null && $Docs->Resignation_Accept != null)
                                                        <a href="{{ url('file-view/Documents/' . $Docs->Resignation_Accept) }}"
                                                            class="view-pdf">View</a>
                                                    @endif
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 d-flex">
                        <div class="card profile-box flex-fill" style="min-height:17px;">
                            <div class="card-body">
                                <h6 class="card-title border-bot">Educational Certificates</h6>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead class="text-center bg-success bg-gradient text-light">
                                            <th style="width: 5%" class=" text-center">S.No.</th>
                                            <th class="text-center" style="width: 20%">Document Name</th>
                                            <th class="text-center">View</th>
                                        </thead>
                                        <tbody>
                                            @foreach ($Education as $item)
                                                @if ($item->File_Attachment != null)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>{{ $item->Qualification }}</td>
                                                        <td>
                                                            <a href="{{ url('file-view/Documents/' . $item->File_Attachment) }}"
                                                                download>View</a>
                                                        </td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 d-flex">
                        <div class="card profile-box flex-fill" style="min-height:17px;">
                            <div class="card-body">
                                <h6 class="card-title border-bot">KYC Documents</h6>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead class="text-center bg-success bg-gradient text-light">
                                            <th style="width: 5%" class=" text-center">S.No.</th>
                                            <th class="text-center" style="width: 20%">Document Name</th>
                                            <th class="text-center">View</th>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class=" text-center">1</td>
                                                <td>Aadhaar Card</td>
                                                <td style="width: 10%; text-align:center">
                                                    @if ($Docs != null && $Docs->Aadhar != null)
                                                        <a href="{{ url('file-view/Documents/' . $Docs->Aadhar) }}"
                                                            class="view-pdf">View</a>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class=" text-center">2</td>
                                                <td>PAN Card</td>
                                                <td style="width: 10%; text-align:center">
                                                    @if ($Docs != null && $Docs->PanCard != null)
                                                        <a href="{{ url('file-view/Documents/' . $Docs->PanCard) }}"
                                                            class="view-pdf">View</a>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class=" text-center">3</td>
                                                <td>Driving Licence</td>
                                                <td style="width: 10%; text-align:center">
                                                    @if ($Docs != null && $Docs->DL != null)
                                                        <a href="{{ url('file-view/Documents/' . $Docs->DL) }}"
                                                            class="view-pdf">View</a>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class=" text-center">4</td>
                                                <td>Passport</td>
                                                <td style="width: 10%; text-align:center">
                                                    @if ($Docs != null && $Docs->Passport != null)
                                                        <a href="{{ url('file-view/Documents/' . $Docs->Passport) }}"
                                                            class="view-pdf">View</a>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class=" text-center">5</td>
                                                <td>Bank Passbook/Document</td>
                                                <td style="width: 10%; text-align:center">
                                                    @if ($Docs != null && $Docs->BankDoc != null)
                                                        <a href="{{ url('file-view/Documents/' . $Docs->BankDoc) }}"
                                                            class="view-pdf">View</a>
                                                    @endif
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 d-flex">
                        <div class="card profile-box flex-fill" style="min-height:17px;">
                            <div class="card-body">
                                <h6 class="card-title border-bot">Other Documents </h6>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead class="text-center bg-success bg-gradient text-light">
                                            <th style="width: 5%" class=" text-center">S.No.</th>
                                            <th class="text-center" style="width: 20%">Document Name</th>
                                            <th class="text-center">View</th>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class=" text-center">1</td>
                                                <td>Blood Group Certificate</td>
                                                <td style="width: 10%; text-align:center">
                                                    @if ($Docs != null && $Docs->BloodGroup != null)
                                                        <a href="{{ url('file-view/Documents/' . $Docs->BloodGroup) }}"
                                                            class="view-pdf">View</a>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class=" text-center">2</td>
                                                <td>Health Declaration Form</td>
                                                <td style="width: 10%; text-align:center">
                                                    @if ($Docs != null && $Docs->Health != null)
                                                        <a href="{{ url('file-view/Documents/' . $Docs->Health) }}"
                                                            class="view-pdf">View</a>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class=" text-center">3</td>
                                                <td>Vaccination Certificate</td>
                                                <td style="width: 10%; text-align:center">
                                                    @if ($Docs != null && $Docs->VaccinationCert != null)
                                                        <a href="{{ url('file-view/Documents/' . $Docs->VaccinationCert) }}"
                                                            class="view-pdf">View</a>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class=" text-center">4</td>
                                                <td>Declaration for Compliance to Ethical Financial Dealings</td>
                                                <td style="width: 10%; text-align:center">
                                                    @if ($Docs != null && $Docs->Ethical != null)
                                                        <a href="{{ url('file-view/Documents/' . $Docs->Ethical) }}"
                                                            class="view-pdf">View</a>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class=" text-center">5</td>
                                                <td>Investment Declaration</td>
                                                <td style="width: 10%; text-align:center">
                                                    @if ($Docs != null && $Docs->Invst_Decl != null)
                                                        <a href="{{ url('file-view/Documents/' . $Docs->Invst_Decl) }}"
                                                            class="view-pdf">View</a>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class=" text-center">6</td>
                                                <td>Self Declaration for Resignation</td>
                                                <td style="width: 10%; text-align:center">
                                                    @if ($Docs != null && $Docs->Resignation != null)
                                                        <a href="{{ url('file-view/Documents/' . $Docs->Resignation) }}"
                                                            class="view-pdf">View</a>
                                                    @endif
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 d-flex">
                        <div class="card profile-box flex-fill" style="min-height:17px;">
                            <div class="card-body">
                                <h6 class="card-title border-bot">2 Wheeler Documents</h6>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead class="text-center bg-success bg-gradient text-light">
                                            <th style="width: 5%" class=" text-center">S.No.</th>
                                            <th class="text-center" style="width: 20%">Document Name</th>
                                            <th class="text-center">View</th>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class=" text-center">1</td>
                                                <td>Invoice</td>
                                                <td style="width: 10%; text-align:center">
                                                    @if ($vehicle_info != null && $vehicle_info->invoice != null)
                                                        <a href="{{ url('file-view/vehicle_upload/' . $vehicle_info->invoice) }}"
                                                            class="view-pdf">View</a>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class=" text-center">2</td>
                                                <td>RC</td>
                                                <td style="width: 10%; text-align:center">
                                                    @if ($vehicle_info != null && $vehicle_info->rc_file != null)
                                                        <a href="{{ url('file-view/vehicle_upload/' . $vehicle_info->rc_file) }}"
                                                            class="view-pdf">View</a>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class=" text-center">3</td>
                                                <td>Vehicle Image</td>
                                                <td style="width: 10%; text-align:center">
                                                    @if ($vehicle_info != null && $vehicle_info->vehicle_image != null)
                                                        <a href="{{ url('file-view/vehicle_upload/' . $vehicle_info->vehicle_image) }}"
                                                            class="view-pdf">View</a>
                                                    @endif
                                                </td>
                                            </tr>

                                            <tr>
                                                <td class=" text-center">4</td>
                                                <td>Insurance</td>
                                                <td style="width: 10%; text-align:center">
                                                    @if ($vehicle_info != null && $vehicle_info->insurance != null)
                                                        <a href="{{ url('file-view/vehicle_upload/' . $vehicle_info->insurance) }}"
                                                            class="view-pdf">View</a>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class=" text-center">5</td>
                                                <td>Current Odo Meter</td>
                                                <td style="width: 10%; text-align:center">
                                                    @if ($vehicle_info != null && $vehicle_info->odo_meter != null)
                                                        <a href="{{ url('file-view/vehicle_upload/' . $vehicle_info->odo_meter) }}"
                                                            class="view-pdf">View</a>
                                                    @endif
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 d-flex">
                        <div class="card profile-box flex-fill" style="min-height:17px;">
                            <div class="card-body">
                                <h6 class="card-title border-bot">4 Wheeler Documents</h6>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead class="text-center bg-success bg-gradient text-light">
                                            <th style="width: 5%" class=" text-center">S.No.</th>
                                            <th class="text-center" style="width: 20%">Document Name</th>
                                            <th class="text-center">View</th>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class=" text-center">1</td>
                                                <td>Invoice</td>
                                                <td style="width: 10%; text-align:center">
                                                    @if ($vehicle_info != null && $vehicle_info->four_invoice != null)
                                                        <a href="{{ url('file-view/vehicle_upload/' . $vehicle_info->four_invoice) }}"
                                                            class="view-pdf">View</a>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class=" text-center">2</td>
                                                <td>RC</td>
                                                <td style="width: 10%; text-align:center">
                                                    @if ($vehicle_info != null && $vehicle_info->four_rc_file != null)
                                                        <a href="{{ url('file-view/vehicle_upload/' . $vehicle_info->four_rc_file) }}"
                                                            class="view-pdf">View</a>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class=" text-center">3</td>
                                                <td>Vehicle Image</td>
                                                <td style="width: 10%; text-align:center">
                                                    @if ($vehicle_info != null && $vehicle_info->four_vehicle_image != null)
                                                        <a href="{{ url('file-view/vehicle_upload/' . $vehicle_info->four_vehicle_image) }}"
                                                            class="view-pdf">View</a>
                                                    @endif
                                                </td>
                                            </tr>

                                            <tr>
                                                <td class=" text-center">4</td>
                                                <td>Insurance</td>
                                                <td style="width: 10%; text-align:center">
                                                    @if ($vehicle_info != null && $vehicle_info->four_insurance != null)
                                                        <a href="{{ url('file-view/vehicle_upload/' . $vehicle_info->four_insurance) }}"
                                                            class="view-pdf">View</a>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class=" text-center">5</td>
                                                <td>Current Odo Meter</td>
                                                <td style="width: 10%; text-align:center">
                                                    @if ($vehicle_info != null && $vehicle_info->four_odo_meter != null)
                                                        <a href="{{ url('file-view/vehicle_upload/' . $vehicle_info->four_odo_meter) }}"
                                                            class="view-pdf">View</a>
                                                    @endif
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="cand_history">
                <div class="row">
                    <div class="col-lg-5">
                        <div class="card profile-box flex-fill">
                            <div class="card-body">
                                <h6 class="card-title border-bot">Job Application History</h6>

                                <table class="table table-bordered table-striped text-left">
                                    <thead class="text-center bg-success bg-gradient text-light">
                                        <th style="width: 50%">Action</th>
                                        <th>Date</th>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Job Applied</td>
                                            <td> {{ date('d-M-Y', strtotime($Rec->ApplyDate)) }}</td>
                                        </tr>
                                        @if ($Rec->Status != null)
                                            <tr>
                                                <td>HR Screening Status</td>
                                                <td>{{ $Rec->Status }}</td>
                                            </tr>
                                            <tr>
                                                <td>HR Screening Date</td>
                                                <td>{{ date('d-M-Y', strtotime($Rec->HrScreeningDate)) }}</td>
                                            </tr>
                                            <!-- =====================================================-->
                                            @if ($Rec->Status == 'Rejected')
                                                <tr>
                                                    <td>Reason for Rejection</td>
                                                    <td>{{ $Rec->RejectRemark }}</td>
                                                    <!-- <td>{{ date('d-M-Y', strtotime($Rec->RejectRemark)) }}</td> -->
                                                </tr>
                                            @endif
                                            <!-- =============================================================== -->
                                        @endif


                                        @if ($Rec->Status == 'Selected')
                                            <tr>
                                                <td>Forwarded for Technical Screening</td>
                                                <td>{{ $Rec->FwdTechScr }}</td>
                                            </tr>
                                            @if ($Rec->FwdTechScr == 'Yes')
                                                <tr>
                                                    <td>Technical Screening Sent Date</td>
                                                    <td> {{ date('d-M-Y', strtotime($Rec->ReSentForScreen)) }}</td>
                                                </tr>
                                                <tr>
                                                    <td>Technical Screening Date</td>
                                                    <td>{{ $Rec->ResScreened ? date('d-M-Y', strtotime($Rec->ResScreened)) : ' ' }}
                                                    </td>
                                                    <!-- <td> {{ date('d-M-Y', strtotime($Rec->ResScreened)) }}</td> -->
                                                </tr>
                                                <tr>
                                                    <td>Technical Screening Status</td>
                                                    <td>{{ $Rec->ScreenStatus }}</td>
                                                </tr>

                                                <!-- =============================================================== -->
                                                @if ($Rec->ScreenStatus == 'Reject')
                                                    <tr>
                                                        <td>Reason for Rejection</td>
                                                        <td>{{ $Rec->RejectionRem }}</td>
                                                    </tr>
                                                @endif
                                                <!-- ================================================================== -->
                                            @endif
                                        @endif

                                        @if ($Rec->ScreenStatus == 'Shortlist')
                                            <tr>
                                                <td>Inderview Date</td>
                                                <td>{{ date('d-M-Y', strtotime($Rec->IntervDt)) }}</td>
                                            </tr>
                                            <tr>
                                                <td>Interview Status</td>
                                                <td>{{ $Rec->IntervStatus }}</td>
                                            </tr>
                                        @endif
                                        @if ($Rec->IntervStatus == '2nd Round Interview')
                                            <tr>
                                                <td>2nd Round Interview Date</td>
                                                <td>{{ date('d-M-Y', strtotime($Rec->IntervDt2)) }}</td>
                                            </tr>
                                            <tr>
                                                <td>2nd Round Interview Status</td>
                                                <td>{{ $Rec->IntervStatus2 }}</td>
                                            </tr>
                                        @endif
                                        @if ($Rec->SelectedForD != null)
                                            <tr>
                                                <td>Offer Letter Sent</td>
                                                <td>{{ $OfBasic->OfferLetterSent ?? '' }}</td>
                                            </tr>
                                            <tr>
                                                <td>Offer Letter Status</td>
                                                <td>{{ $OfBasic->Answer ?? '' }}</td>
                                            </tr>
                                        @endif

                                        @if ($OfBasic != null && $OfBasic->Answer == 'Rejected')
                                            <tr>
                                                <td>Offer Letter Rejected Reason</td>
                                                <td>

                                                    @if ($OfBasic->RejReason != '' || $OfBasic->RejReason != null)
                                                        {{ $OfBasic->RejReason }}
                                                    @else
                                                        {{ $OfBasic->RejReason1 }}
                                                    @endif
                                                </td>
                                            </tr>
                                        @endif
                                        @if ($OfBasic != null && $OfBasic->Answer == 'Accepted')
                                            <tr>
                                                <td>Joining Form Sent</td>
                                                <td>{{ $OfBasic->JoiningFormSent }}</td>
                                            </tr>
                                            <tr>
                                                <td>Joining Form Status</td>
                                                <td>{{ $Rec->FinalSubmit == 1 ? 'Submitted' : 'Not Submitted' }}
                                                </td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </div>
                    <div class="col-lg-7">
                        <div class="card">
                            <div class="card-body ">
                                <h6 class="card-title border-bot">Activity Log</h6>
                                <table class="table table-bordered table-striped " id="CandLogTable">
                                    <thead class="text-center bg-success bg-gradient text-light">
                                        <th>S.No.</th>
                                        <th style="width: 20%">Date</th>
                                        <th>Action</th>
                                    </thead>
                                    <tbody>
                                        @php
                                            $i = 0;
                                        @endphp
                                        @foreach ($candidate_log as $item => $value)
                                            <tr>
                                                <td class="text-center">{{ ++$i }}</td>
                                                <td class="text-center">{{ date('d-M-Y', strtotime($value->Date)) }}
                                                </td>
                                                <td>{{ $value->Description }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="job_offer">
                <div class="row">
                    <div class="col-md-5 d-flex">
                        <div class="card profile-box flex-fill">
                            <div class="card-body">
                                <h6 class="card-title border-bot">Offer Letter Basic Details
                                    {{-- @if ($OfBasic != null && ($OfBasic->Answer == '' || $OfBasic->Answer == 'Rejected')) --}}
                                    <a href="javascript:void(0);" class="edit-icon" data-bs-toggle="modal"
                                        data-bs-target="#OfferLtrModal" id="offerltredit"
                                        data-id="{{ $Rec->JAId }}" ">
                                            <i class="fa fa-pencil"></i>
                                        </a>
                                        {{-- @endif --}}
                                    </h6>
                                    <ul class="personal-info">
                                        <li>
                                            <div class="title" style="width: 150px;">Department<span
                                                    style="float: right">:</span></div>
                                            <div class="text">
                                                 @if ($OfBasic != null)
                                        {{ getDepartment($OfBasic->Department) ?? '-' }}
                                        ({{ getcompany_code($OfBasic->Company) }})
                                    @else
                                        -
                                        @endif
                            </div>
                            </li>
                            <li>
                                <div class="title" style="width: 150px;">Designation<span
                                        style="float: right">:</span></div>
                                <div class="text">
                                    @if ($OfBasic != null)
                                        @if ($OfBasic->Designation == 0)
                                            -
                                        @else
                                            {{ getDesignation($OfBasic->Designation) ?? '-' }}
                                        @endif
                                    @else
                                        -
                                    @endif
                                </div>
                            </li>
                            <li>
                                <div class="title" style="width: 150px;">Grade<span style="float: right">:</span>
                                </div>
                                <div class="text">
                                    @if ($OfBasic != null)
                                        @if ($OfBasic->Grade == 0)
                                            -
                                        @else
                                            {{ getGradeValue($OfBasic->Grade) ?? '-' }}
                                        @endif
                                    @else
                                        -
                                    @endif
                                </div>
                            </li>
                            <li>
                                <div class="title" style="width: 150px;">Reporting Mgr.<span
                                        style="float: right">:</span></div>
                                <div class="text">
                                    @if ($OfBasic != null)
                                        @if ($OfBasic->repchk == 'RepWithoutEmp')
                                            {{ getDesignation($OfBasic->reporting_only_desig) }}
                                        @else
                                            @if ($OfBasic->A_ReportingManager == '' || $OfBasic->A_ReportingManager == null)
                                                -
                                            @else
                                                {{ getFullName($OfBasic->A_ReportingManager) ?? '-' }}
                                            @endif
                                        @endif
                                    @else
                                        -
                                    @endif
                                </div>
                            </li>
                            <li>
                                <div class="title" style="width: 150px;">CTC<span style="float: right">:</span>
                                </div>
                                <div class="text">
                                    @if ($OfBasic != null)
                                        @if ($OfBasic->CTC == '' || $OfBasic->CTC == null)
                                            -
                                        @else
                                            {{ $OfBasic->CTC ?? '-' }}
                                        @endif
                                    @else
                                        -
                                    @endif
                                </div>
                            </li>
                            <li>
                                <div class="title" style="width: 150px;">Service Condition<span
                                        style="float: right">:</span></div>
                                <div class="text">
                                    @if ($OfBasic != null)
                                        @if ($OfBasic->ServiceCondition == 'Training')
                                            Training
                                        @elseif($OfBasic->ServiceCondition == 'Probation')
                                            Probation
                                        @elseif($OfBasic->ServiceCondition == 'nopnot')
                                            No Probation No Training
                                        @else
                                            -
                                        @endif
                                    @endif
                                </div>
                            </li>

                            <li>
                                <div class="title" style="width: 150px;">Service Bond<span
                                        style="float: right">:</span></div>
                                <div class="text">
                                    @if ($OfBasic != null)
                                        @if ($OfBasic->ServiceBond == 'Yes')
                                            Yes
                                        @elseif($OfBasic->ServiceBond == 'No')
                                            No
                                        @else
                                            -
                                        @endif
                                    @endif
                                </div>
                            </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="col-md-7 d-flex">
                    <div class="card profile-box flex-fill">
                        <div class="card-body">
                            <h6 class="card-title border-bot">Offer Letter Generation & Review
                                {{-- @if ($OfBasic != null && ($OfBasic->Answer == '' || $OfBasic->Answer == 'Rejected')) --}}
                                <a href="javascript:void(0);" class="edit-icon" id="offerltrgen"
                                    data-id="{{ $Rec->JAId }}">
                                    <i class="fa fa-pencil"></i>
                                </a>
                                {{-- @endif --}}
                            </h6>

                            <ul class="personal-info">
                                <li>
                                    <div class="title" style="width: 300px;">Offer Letter Generated<span
                                            style="float: right">:</span></div>
                                    <div class="text">
                                        @if ($OfBasic != null && $OfBasic->OfferLtrGen == 1)
                                            <span class="badge badge-success">Yes</span>
                                        @else
                                            <span class="badge badge-danger">No</span>
                                        @endif
                                        @if ($count > 1)
                                            ( <a href="javascript:vaoid(0);" class="offer-history-btn"
                                                data-bs-toggle="modal" data-bs-target="#HistoryModal"
                                                onclick="getOfHistory({{ $Rec->JAId }});"> View History</a>)
                                        @endif

                                    </div>
                                </li>
                                <li>
                                    <div class="title" style="width: 300px;">Send for Review<span
                                            style="float: right">:</span></div>
                                    <div class="text">

                                        @if ($OfBasic != null && $OfBasic->SendReview == 1)
                                            <span class="text-dark">Yes</span> ( <a href="javascript:void(0);"
                                                onclick="viewReview({{ $Rec->JAId }});" data-bs-toggle="modal"
                                                data-bs-target="#view_review">View</a>
                                            )
                                        @else
                                            <span class="text-danger">No</span>
                                        @endif
                                        @if ($OfBasic != null && $OfBasic->OfferLtrGen == 1)
                                            (<a href="javascript:void(0);" data-bs-toggle="modal"
                                                data-bs-target="#review_modal">
                                                Send Now</a>)
                                        @endif
                                    </div>
                                </li>
                                <li>
                                    <div class="title" style="width: 300px;">Send to Candidate<span
                                            style="float: right">:</span></div>
                                    <div class="text">
                                        @if ($OfBasic != null && $OfBasic->OfferLetterSent == 'Yes')
                                            <span class="text-dark">Yes</span>
                                        @else
                                            <span class="text-danger">No</span>
                                            @if ($OfBasic != null && $OfBasic->OfferLtrGen == 1)
                                                ( <a href="javascript:void(0);" class=""
                                                    onclick="sendOfferLtr({{ $Rec->JAId }});">
                                                    Send Now</a>)
                                            @endif
                                        @endif
                                    </div>
                                </li>
                                <li>
                                    <div class="title" style="width: 300px;">Candidate Response<span
                                            style="float: right">:</span></div>
                                    <div class="text"> <span
                                            class="text-danger">{{ $OfBasic->Answer ?? '-' }}</span>
                                        @if ($OfBasic != null && $OfBasic->Answer == 'Rejected')
                                            ( <a href="javascript:void(0);" class=""
                                                onclick="offerReopen({{ $Rec->JAId }});"> Offer Reopen</a>)
                                        @endif

                                        @if ($OfBasic != null && $OfBasic->OfferLtrGen == 1 && $OfBasic->Answer == null)
                                            <a class="btn btn-xs btn-warning" href="javascript:void(0);"
                                                onclick="OLAction({{ $JAId }});">OL Action on behalf of
                                                candidate</a>
                                        @endif
                                        @if ($OfBasic != null && $OfBasic->HR_Remark != '')
                                            (HR Remark:- {{ $OfBasic->HR_Remark }})
                                        @endif

                                    </div>
                                </li>

                                @if ($OfBasic != null && $OfBasic->OfferLtrGen == 1 && $OfBasic->Answer == 'Rejected')
                                    <li>
                                        <div class="title" style="width: 300px;">Rejection Reason<span
                                                style="float: right">:</span></div>
                                        <div class="text text-danger">
                                            @if ($OfBasic != null)
                                                {{ $OfBasic->RejReason ?? '-' }}
                                            @endif
                                        </div>
                                    </li>

                                @endif
                                @if ($OfBasic != null && $OfBasic->OfferLtrGen == 1)
                                    @if ($OfBasic != null && $OfBasic->OfferLtrGen == 1 && $OfBasic->Answer != 'Rejected')
                                        <li>
                                            <div class="title" style="width: 300px;">Date of Joining<span
                                                    style="float: right">:</span></div>

                                            <div class="text">
                                                <input type="date"
                                                    class="form-control frminp form-control-sm d-inline-block"
                                                    id="dateofJoin" name="" readonly=""
                                                    style="width: 130px;" value="{{ $OfBasic->JoinOnDt ?? '' }}">
                                                <i class="fa fa-pencil text-primary" aria-hidden="true"
                                                    id="joindtenable" onclick="joinDateEnbl()"
                                                    style="font-size: 16px;cursor: pointer;"></i>
                                                <button class="btn btn-sm frmbtn btn-primary" style="display: none;"
                                                    id="JoinSave" onclick="saveJoinDate()">Save
                                                </button>
                                                <button class="btn btn-sm frmbtn btn-danger" style="display: none;"
                                                    id="JoinCanc" onclick="window.location.reload();">Cancel
                                                </button>
                                            </div>

                                        </li>
                                    @endif
                                    @if ($OfBasic != null && $OfBasic->OfferLtrGen == 1 && $OfBasic->Answer != 'Rejected')
                                        <li>
                                            <div class="title" style="width: 300px;">Onboarding process (mail to
                                                candidate)<span style="float: right">:</span></div>
                                            <div class="text">
                                                @if ($OfBasic != null && $OfBasic->JoiningFormSent == 'Yes')
                                                    <span class="text-dark">Yes</span>
                                                @else
                                                    <span class="text-danger">No</span>
                                                    @if ($OfBasic != null && $OfBasic->OfferLtrGen == 1)
                                                        ( <a href="javascript:void(0);" class=""
                                                            onclick="sendJoiningForm({{ $Rec->JAId }});"> Send
                                                            Now</a>)
                                                    @endif
                                                @endif
                                        </li>
                                    @endif
                                @endif
                                @if ($OfBasic != null && $OfBasic->OfferLtrGen == 1 && $OfBasic->Answer != 'Rejected')
                                    <li>
                                        <div class="title" style="width: 300px;">Ref. Check <span
                                                style="float: right">:</span>
                                        </div>
                                        <div class="text">
                                            @if ($OfBasic != null && $OfBasic->SendForRefChk == 1)
                                                <span class="text-dark">Yes</span>
                                                (
                                                <a href="{{ route('view_reference_check') }}?jaid={{ base64_encode($Rec->JAId) }}"
                                                    target="_blank">View</a>)
                                            @else
                                                <span class="text-danger">No</span>( <a href="javascript:void(0);"
                                                    class="" data-bs-toggle="modal" data-bs-target="#ref_modal">
                                                    Send Now</a>)
                                            @endif
                                        </div>
                                    </li>
                                @endif
                                @if ($OfBasic != null)
                                    <li>
                                        <div class="title" style="width: 300px;">HR Closure<span
                                                style="float: right">:</span>
                                        </div>
                                        <div class="text  text-dark">
                                            <select name="Hr_Closure" id="Hr_Closure"
                                                class="form-select form-select-sm frminp d-inline" disabled
                                                style="width: 100px;">
                                                <option value=""></option>
                                                <option value="No"
                                                    {{ $OfBasic->Hr_Closure == 'No' ? 'selected' : '' }}>
                                                    No
                                                </option>
                                                <option value="Yes"
                                                    {{ $OfBasic->Hr_Closure == 'Yes' ? 'selected' : '' }}>Yes
                                                </option>
                                            </select>
                                            <i class="fa fa-pencil text-primary" aria-hidden="true" id="ClosureEnbl"
                                                onclick="ClosureEnbl()" style="font-size: 16px;cursor: pointer; "></i>
                                            <button class="btn btn-sm frmbtn btn-primary" style="display: none;"
                                                id="SaveClosure">Save
                                            </button>
                                            <button class="btn btn-sm frmbtn btn-danger" style="display: none;"
                                                id="ClosureCancle" onclick="window.location.reload();">Cancel
                                            </button>
                                        </div>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="tab-pane fade" id="onboarding">
            <div class="row">
                @if ($OfBasic != null && $OfBasic->Answer == 'Accepted')
                    <div class="col-md-6 d-flex">
                        <div class="card profile-box flex-fill">
                            <div class="card-body">
                                <h6 class="card-title border-bot">Joining Details </h6>
                                <ul class="personal-info">

                                    <li>
                                        <div class="title" style="width: 150px;"> Appointment Letter <span
                                                style="float: right">:</span></div>

                                        <div class="text  text-dark">
                                            @if ($Rec->AppLtrGen == 'No' || $Rec->AppLtrGen == null)
                                                <i class="fa fa-pencil text-primary" aria-hidden="true"
                                                    onclick="appointmentGen({{ $Rec->JAId }})"
                                                    style="font-size: 16px;cursor: pointer; ">Generate </i>
                                            @else
                                                <a href="{{ route('appointment_ltr_print') }}?jaid={{ $Rec->JAId }}"
                                                    target="_blank">View</a>
                                            @endif

                                        </div>
                                    </li>
                                    <li>
                                        <div class="title" style="width: 150px;"> Service Agreement <span
                                                style="float: right">:</span></div>
                                        <div class="text  text-dark">
                                            @if ($Rec->AgrLtrGen == 'No' || $Rec->AgrLtrGen == null)
                                                <i class="fa fa-pencil text-primary" aria-hidden="true"
                                                    onclick="ServiceAgrGen({{ $Rec->JAId }})"
                                                    style="font-size: 16px;cursor: pointer; ">Generate </i>
                                            @else
                                                <a href="{{ route('service_agreement_print_e_first') }}?jaid={{ base64_encode($Rec->JAId) }}"
                                                    target="_blank">First Page</a> |
                                                <a href="{{ route('service_agreement_print') }}?jaid={{ base64_encode($Rec->JAId) }}"
                                                    target="_blank">Rest All</a> | <a
                                                    href="{{ route('service_agreement_print_old_stamp') }}?jaid={{ base64_encode($Rec->JAId) }}"
                                                    target="_blank">
                                                    Old Stamp</a>
                                            @endif

                                        </div>
                                    </li>
                                    @if ($OfBasic != null && $OfBasic->ServiceBond == 'Yes')
                                        <li>
                                            <div class="title" style="width: 150px;"> Service Bond <span
                                                    style="float: right">:</span></div>
                                            <div class="text  text-dark">
                                                @if ($Rec->BLtrGen == 'No' || $Rec->BLtrGen == null)
                                                    <i class="fa fa-pencil text-primary" aria-hidden="true"
                                                        onclick="ServiceBondGen({{ $Rec->JAId }})"
                                                        style="font-size: 16px;cursor: pointer; ">Generate
                                                    </i>
                                                @else
                                                    <a href="{{ route('service_bond_print_e_first') }}?jaid={{ base64_encode($Rec->JAId) }}"
                                                        target="_blank">First Page</a> |
                                                    <a href="{{ route('service_bond_print') }}?jaid={{ base64_encode($Rec->JAId) }}"
                                                        target="_blank">Rest All</a> | <a
                                                        href="{{ route('service_bond_print_old_stamp') }}?jaid={{ base64_encode($Rec->JAId) }}"
                                                        target="_blank">
                                                        Old Stamp</a>
                                                @endif
                                            </div>
                                        </li>
                                    @endif
                                    @if (
                                        $OfBasic->Department == 2 ||
                                            $OfBasic->Department == 3 ||
                                            $OfBasic->Department == 17 ||
                                            $OfBasic->Department == 13 ||
                                            $OfBasic->Department == 11 ||
                                            $OfBasic->Department == 14)
                                        <li>
                                            <div class="title" style="width: 150px;"> Conf. Agreement <span
                                                    style="float: right">:</span></div>
                                            <div class="text  text-dark">
                                                @if ($Rec->ConfLtrGen == 'No' || $Rec->ConfLtrGen == null)
                                                    <i class="fa fa-pencil text-primary" aria-hidden="true"
                                                        onclick="ConfidentialityAgrGen({{ $Rec->JAId }})"
                                                        style="font-size: 16px;cursor: pointer; ">Generate
                                                    </i>
                                                @else
                                                    <a href="{{ route('conf_agreement_print_e_first') }}?jaid={{ base64_encode($Rec->JAId) }}"
                                                        target="_blank">
                                                        First Page</a> |
                                                    <a href="{{ route('conf_agreement_print') }}?jaid={{ base64_encode($Rec->JAId) }}"
                                                        target="_blank">
                                                        Rest All</a> | <a
                                                        href="{{ route('conf_agreement_print_old_stamp') }}?jaid={{ base64_encode($Rec->JAId) }}"
                                                        target="_blank">
                                                        Old Stamp</a>
                                                @endif

                                            </div>
                                        </li>

                                    @endif

                                    <li>
                                        <div class="title" style="width: 150px;"> Verify Joining Form <span
                                                style="float: right">:</span></div>
                                        <div class="text  text-dark">
                                            <select name="Verification" id="Verification"
                                                class="form-select form-select-sm frminp d-inline" disabled
                                                style="width: 100px;">
                                                <option value="Not Verified">Not Verified</option>
                                                <option value="Verified"
                                                    {{ $OfBasic->Verification == 'Verified' ? 'selected' : '' }}>
                                                    Verified
                                                </option>
                                            </select>
                                            <i class="fa fa-pencil text-primary" aria-hidden="true"
                                                id="VerificationEnable" onclick="VerificationEnable()"
                                                style="font-size: 16px;cursor: pointer; "></i>
                                            <button class="btn btn-sm frmbtn btn-primary" style="display: none;"
                                                id="SaveVerification">Save
                                            </button>
                                            <button class="btn btn-sm frmbtn btn-danger" style="display: none;"
                                                id="verificationCancle" onclick="window.location.reload();">
                                                Cancel
                                            </button>
                                        </div>
                                    </li>

                                    <li>
                                        <div class="title" style="width: 150px;"> 2 Wheeler RC<span
                                                style="float: right">:</span></div>
                                        <div class="text  text-dark">
                                            <select name="two_wheel_rc" id="two_wheel_rc"
                                                class="form-select form-select-sm frminp d-inline" disabled
                                                style="width: 100px;">
                                                <option value="">Select</option>
                                                <option value="N"
                                                    {{ $OfBasic->two_wheel_rc == 'N' ? 'selected' : '' }}>
                                                    No
                                                </option>
                                                <option value="Y"
                                                    {{ $OfBasic->two_wheel_rc == 'Y' ? 'selected' : '' }}>
                                                    Yes
                                                </option>
                                            </select>
                                            <i class="fa fa-pencil text-primary" aria-hidden="true"
                                                id="TwoWheelRCEnable" onclick="TwoWheelRCEnable()"
                                                style="font-size: 16px;cursor: pointer; "></i>
                                            <button class="btn btn-sm frmbtn btn-primary" style="display: none;"
                                                id="SaveTwoWheelRC">Save
                                            </button>
                                            <button class="btn btn-sm frmbtn btn-danger" style="display: none;"
                                                id="TwoWheelRCCancle" onclick="window.location.reload();">
                                                Cancel
                                            </button>
                                        </div>
                                    </li>
                                    @if ($OfBasic != null && $OfBasic->two_wheel_flat_rate != null)
                                        <li>
                                            <div class="title" style="width: 150px;">Two Wheel Flat Rate
                                                <span style="float: right">:</span>
                                            </div>
                                            <div class="text  text-danger">
                                                Rs. {{ $OfBasic->two_wheel_flat_rate }}/-
                                            </div>
                                        </li>
                                    @endif
                                    <li>
                                        <div class="title" style="width: 150px;"> 4 Wheeler RC <span
                                                style="float: right">:</span></div>
                                        <div class="text  text-dark">
                                            <select name="four_wheel_rc" id="four_wheel_rc"
                                                class="form-select form-select-sm frminp d-inline" disabled
                                                style="width: 100px;">
                                                <option value="">Select</option>
                                                <option value="N"
                                                    {{ $OfBasic->four_wheel_rc == 'N' ? 'selected' : '' }}>
                                                    No
                                                </option>
                                                <option value="Y"
                                                    {{ $OfBasic->four_wheel_rc == 'Y' ? 'selected' : '' }}>
                                                    Yes
                                                </option>
                                            </select>
                                            <i class="fa fa-pencil text-primary" aria-hidden="true"
                                                id="FourWheelRCEnable" onclick="FourWheelRCEnable()"
                                                style="font-size: 16px;cursor: pointer; "></i>
                                            <button class="btn btn-sm frmbtn btn-primary" style="display: none;"
                                                id="SaveFourWheelRC">Save
                                            </button>
                                            <button class="btn btn-sm frmbtn btn-danger" style="display: none;"
                                                id="FourWheelRCCancle" onclick="window.location.reload();">
                                                Cancel
                                            </button>
                                        </div>
                                    </li>
                                    @if ($OfBasic != null && $OfBasic->four_wheel_flat_rate != null)
                                        <li>
                                            <div class="title" style="width: 150px;">Four Wheel Flat Rate
                                                <span style="float: right">:</span>
                                            </div>
                                            <div class="text  text-danger">
                                                Rs. {{ $OfBasic->four_wheel_flat_rate }}/-
                                            </div>
                                        </li>
                                    @endif
                                    <li>
                                        <div class="title" style="width: 150px;"> Candidate Joined <span
                                                style="float: right">:</span></div>
                                        <div class="text  text-dark">
                                            <select name="Joined" id="Joined"
                                                class="form-select form-select-sm frminp d-inline" disabled
                                                style="width: 100px;">
                                                <option value=""></option>
                                                <option value="No" {{ $OfBasic->Joined == 'No' ? 'selected' : '' }}>
                                                    No
                                                </option>
                                                <option value="Yes"
                                                    {{ $OfBasic->Joined == 'Yes' ? 'selected' : '' }}>Yes
                                                </option>
                                            </select>
                                            <i class="fa fa-pencil text-primary" aria-hidden="true" id="JoinedEnbl"
                                                onclick="JoinedEnbl()" style="font-size: 16px;cursor: pointer; "></i>
                                            <button class="btn btn-sm frmbtn btn-primary" style="display: none;"
                                                id="SaveJoined">Save
                                            </button>
                                            <button class="btn btn-sm frmbtn btn-danger" style="display: none;"
                                                id="JoinedCancle" onclick="window.location.reload();">Cancel
                                            </button>
                                        </div>
                                    </li>
                                    @if ($OfBasic != null && $OfBasic->NoJoiningRemark != null)
                                        <li>
                                            <div class="title" style="width: 150px;">Reason for Not Joining
                                                <span style="float: right">:</span>
                                            </div>
                                            <div class="text  text-danger">
                                                {{ $OfBasic->NoJoiningRemark }}
                                            </div>
                                        </li>
                                    @endif

                                    @if ($OfBasic->Joined == 'Yes')
                                        <li>
                                            <div class="title" style="width: 150px;">Emp Code<span
                                                    style="float: right">:</span></div>
                                            <div class="text">
                                                <input type="text"
                                                    class="form-control frminp form-control-sm d-inline-block"
                                                    id="empCode" name="" readonly=""
                                                    style="width: 100px;" value="{{ $OfBasic->EmpCode ?? '' }}">
                                                <i class="fa fa-pencil text-primary" aria-hidden="true"
                                                    id="empCodeEnable" onclick="empCodeEnable()"
                                                    style="font-size: 16px;cursor: pointer; "></i>
                                                <button class="btn btn-sm frmbtn btn-primary" style="display: none;"
                                                    id="EmpCodeSave" onclick="saveEmpCode()">Save
                                                </button>
                                                <button class="btn btn-sm frmbtn btn-danger" style="display: none;"
                                                    id="empCancle" onclick="window.location.reload();">Cancel
                                                </button>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="title" style="width: 150px;">Position Code<span
                                                    style="float: right">:</span></div>
                                            <div class="text">
                                                {{-- <input type="text"
                                                        class="form-control frminp form-control-sm d-inline-block"
                                                        id="PositionCode" name="PositionCode" readonly=""
                                                        style="width: 100px;"
                                                        value="{{ $OfBasic->PositionCode ?? '' }}"> --}}

                                                <select name="PositionCode" id="PositionCode"
                                                    class="form-select form-select-sm d-inline-block"
                                                    style="width: 170px;" disabled>
                                                    <option value="">Select</option>
                                                    @foreach ($position_code_list as $key => $value)
                                                        <option value="{{ $value }}">{{ $value }}
                                                        </option>
                                                    @endforeach
                                                    @if ($OfBasic->PositionCode != '')
                                                        <option value="{{ $OfBasic->PositionCode }}" selected>
                                                            {{ $OfBasic->PositionCode }}</option>
                                                    @endif
                                                </select>

                                                <i class="fa fa-pencil text-primary" aria-hidden="true" id="PosEnbl"
                                                    onclick="PosEnbl()" style="font-size: 16px;cursor: pointer; "></i>
                                                <button class="btn btn-sm frmbtn btn-primary" style="display: none;"
                                                    id="PositionCodeSave" onclick="PositionCodeSave()">Save
                                                </button>
                                                <button class="btn btn-sm frmbtn btn-danger" style="display: none;"
                                                    id="posCancle" onclick="window.location.reload();">Cancel
                                                </button>
                                            </div>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif
                <div class="col-md-6 d-flex">
                    <div class="card profile-box flex-fill">
                        <div class="card-body">
                            <h6 class="card-title border-bot">Links

                            </h6>
                            <ul class="personal-info">


                                @if ($Rec->InterviewSubmit == 1 || ($OfBasic != null && $OfBasic->JoiningFormSent == 'Yes'))
                                    <li>
                                        <div class="title" style="width: 150px;">Joining Form<span
                                                style="float: right">:</span></div>
                                        <div class="text"><input type="text" name="" id="jflink"
                                                class="frminp d-inline"
                                                value="{{ route('candidate-joining-form') }}?jaid={{ $sendingId }}">
                                            <button class="frmbtn btn btn-sm btn-secondary"
                                                onclick="copyJFrmLink();">Copy
                                                Link
                                            </button>
                                        </div>
                                    </li>
                                @endif

                            </ul>
                            <br>
                            <br>
                            @if ($OfBasic != null)
                                @if ($OfBasic->Company == 1)
                                    @if ($OfBasic->ForwardToESS == 'No' && $OfBasic->Joined == 'Yes' && $OfBasic->EmpCode != '')
                                        <center>
                                            <button class="btn btn-sm btn-primary" id="ProcessToEss">Process
                                                Data to
                                                Ess
                                            </button>
                                        </center>
                                    @endif
                                @else
                                    @if ($OfBasic->ForwardToESS == 'No' && $OfBasic->Joined == 'Yes' && $OfBasic->EmpCode != '')
                                        <center>
                                            <button class="btn btn-sm btn-primary" id="ProcessToEss">Process
                                                Data to
                                                Ess
                                            </button>
                                        </center>
                                    @endif
                                @endif
                            @endif
                            @if ($OfBasic != null && $OfBasic->ForwardToESS == 'Yes')
                                <center>
                                    <h3 class="text-success">Data Forwarded to ESS</h3>
                                </center>
                            @endif
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="tab-pane fade" id="admin_change">
            <div class="row">
                <div class="col-7">
                    <div class="card profile-box flex-fill">
                        <div class="card-body">

                            <ul class="personal-info">
                                <li>
                                    <div class="title">Offer Letter Date<span style="float: right">:</span>
                                    </div>
                                    <div class="text">

                                        <input type="date" class="form-control frminp form-control-sm d-inline-block"
                                            id="off_date" name="" readonly="" style="width: 130px;"
                                            value="{{ $OfBasic->LtrDate ?? '' }}">
                                        <i class="fa fa-pencil text-primary" aria-hidden="true" id="off_date_enable"
                                            onclick="off_date_enable()" style="font-size: 16px;cursor: pointer; "></i>
                                        <button class="btn btn-sm frmbtn btn-primary" style="display: none;"
                                            id="save_off_date" onclick="save_off_date()">Save
                                        </button>
                                        <button class="btn btn-sm frmbtn btn-danger" style="display: none;"
                                            id="off_date_can" onclick="window.location.reload();">Cancel
                                        </button>

                                    </div>
                                </li>

                                <li>
                                    <div class="title">App. Ltr. Date<span style="float: right">:</span></div>
                                    <div class="text">
                                        <input type="date" class="form-control frminp form-control-sm d-inline-block"
                                            id="a_date" name="" readonly="" style="width: 130px;"
                                            value="{{ $OfBasic->A_Date ?? '' }}">
                                        <i class="fa fa-pencil text-primary" aria-hidden="true" id="a_date_enable"
                                            onclick="a_date_enable()" style="font-size: 16px;cursor: pointer; "></i>
                                        <button class="btn btn-sm frmbtn btn-primary" style="display: none;"
                                            id="save_a_date" onclick="save_a_date()">Save
                                        </button>
                                        <button class="btn btn-sm frmbtn btn-danger" style="display: none;"
                                            id="a_date_can" onclick="window.location.reload();">Cancel
                                        </button>
                                    </div>
                                </li>

                                <li>
                                    <div class="title">Service Agr. Date<span style="float: right">:</span>
                                    </div>
                                    <div class="text">
                                        <input type="date" class="form-control frminp form-control-sm d-inline-block"
                                            id="agr_date" name="" readonly="" style="width: 130px;"
                                            value="{{ $OfBasic->Agr_Date ?? '' }}">
                                        <i class="fa fa-pencil text-primary" aria-hidden="true" id="agr_date_enable"
                                            onclick="agr_date_enable()" style="font-size: 16px;cursor: pointer; "></i>
                                        <button class="btn btn-sm frmbtn btn-primary" style="display: none;"
                                            id="save_agr_date" onclick="save_agr_date()">Save
                                        </button>
                                        <button class="btn btn-sm frmbtn btn-danger" style="display: none;"
                                            id="agr_date_can" onclick="window.location.reload();">Cancel
                                        </button>
                                    </div>
                                </li>

                                <li>
                                    <div class="title">Service Bond Date<span style="float: right">:</span>
                                    </div>
                                    <div class="text">
                                        <input type="date" class="form-control frminp form-control-sm d-inline-block"
                                            id="b_date" name="" readonly="" style="width: 130px;"
                                            value="{{ $OfBasic->B_Date ?? '' }}">
                                        <i class="fa fa-pencil text-primary" aria-hidden="true" id="b_date_enable"
                                            onclick="b_date_enable()" style="font-size: 16px;cursor: pointer; "></i>
                                        <button class="btn btn-sm frmbtn btn-primary" style="display: none;"
                                            id="save_b_date" onclick="save_b_date()">Save
                                        </button>
                                        <button class="btn btn-sm frmbtn btn-danger" style="display: none;"
                                            id="b_date_can" onclick="window.location.reload();">Cancel
                                        </button>
                                    </div>
                                </li>
                                @if ($OfBasic != null)
                                    @if (
                                        $OfBasic->Department == 2 ||
                                            $OfBasic->Department == 3 ||
                                            $OfBasic->Department == 17 ||
                                            $OfBasic->Department == 13 ||
                                            $OfBasic->Department == 11 ||
                                            $OfBasic->Department == 14)
                                        <li>
                                            <div class="title">Conf. Agr. Date<span style="float: right">:</span>
                                            </div>
                                            <div class="text">
                                                <input type="date"
                                                    class="form-control frminp form-control-sm d-inline-block"
                                                    id="conf_date" name="" readonly=""
                                                    style="width: 130px;" value="{{ $OfBasic->ConfLtrDate ?? '' }}">
                                                <i class="fa fa-pencil text-primary" aria-hidden="true"
                                                    id="conf_date_enable" onclick="conf_date_enable()"
                                                    style="font-size: 16px;cursor: pointer; "></i>
                                                <button class="btn btn-sm frmbtn btn-primary" style="display: none;"
                                                    id="save_conf_date" onclick="save_conf_date()">Save
                                                </button>
                                                <button class="btn btn-sm frmbtn btn-danger" style="display: none;"
                                                    id="conf_date_can" onclick="window.location.reload();">
                                                    Cancel
                                                </button>
                                            </div>
                                        </li>
                                    @endif
                                @endif
                                <li>
                                    <div class="title">Disable Offer Letter<span style="float: right">:</span></div>
                                    @if ($OfBasic != null)
                                        <div class="text">
                                            @if ($OfBasic->OfferLtrGen == 1 && $OfBasic->OfferLetterSent == 'Yes' && $OfBasic->disable_offer == 'N')
                                                <button class="frmbtn btn btn-danger btn-sm"
                                                    id="disable_offer_letter">Disable OL
                                                </button>
                                            @else
                                                <button class="frmbtn btn btn-primary btn-sm"
                                                    id="enable_offer_letter">Enable OL
                                                </button>
                                            @endif
                                        </div>
                                    @endif
                                </li>
                                @if ($OfBasic != null)
                                    <li>
                                        <div class="title">Two Wheeler<span style="float: right">:</span></div>
                                        <div class="text">
                                            <input type="text"
                                                class="form-control frminp form-control-sm d-inline-block"
                                                id="TwoWheel" readonly style="width:250px;"
                                                value="{{ $OfBasic->TwoWheel ?? '' }}">

                                            <i class="fa fa-pencil text-primary" aria-hidden="true"
                                                id="TwoWheel_enable" onclick="TwoWheel_enable()"
                                                style="font-size: 16px;cursor: pointer; "></i>
                                            <button class="btn btn-sm frmbtn btn-primary" style="display: none;"
                                                id="save_TwoWheel">Save
                                            </button>
                                            <button class="btn btn-sm frmbtn btn-danger" style="display: none;"
                                                id="TwoWheel_can" onclick="window.location.reload();">Cancel
                                            </button>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="title">Four Wheeler<span style="float: right">:</span></div>
                                        <div class="text">
                                            <input type="text"
                                                class="form-control frminp form-control-sm d-inline-block"
                                                id="FourWheel" readonly style="width:250px;"
                                                value="{{ $OfBasic->FourWheel ?? '' }}">

                                            <i class="fa fa-pencil text-primary" aria-hidden="true"
                                                id="FourWheel_enable" onclick="FourWheel_enable()"
                                                style="font-size: 16px;cursor: pointer; "></i>
                                            <button class="btn btn-sm frmbtn btn-primary" style="display: none;"
                                                id="save_FourWheel">Save
                                            </button>
                                            <button class="btn btn-sm frmbtn btn-danger" style="display: none;"
                                                id="FourWheel_can" onclick="window.location.reload();">Cancel
                                            </button>
                                        </div>
                                    </li>
                                @endif

                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

    <div class="compose-mail-popup" style="display: none;">
        <div class="card">
            <div class="card-header bg-dark text-white py-2 cursor-pointer">
                <div class="d-flex align-items-center">
                    <div class="compose-mail-title">New Message</div>
                    <div class="compose-mail-close ms-auto">x</div>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('sendMailToCandidate') }}" method="POST" id="SendMailForm">
                    <div class="email-form">
                        <div class="mb-3">
                            <input type="hidden" name="CandidateName" id="CandidateName"
                                value="{{ $Rec->FName }} {{ $Rec->LName }}">
                            <input type="text" class="form-control" value="{{ $Rec->Email }}" readonly
                                name="eMailId" id="eMailId">
                        </div>
                        <div class="mb-3">
                            <input type="text" class="form-control" placeholder="Subject" name="Subject"
                                id="Subject">
                        </div>
                        <div class="mb-3">
                            <textarea class="form-control" placeholder="Message" rows="10" cols="10" name="eMailMsg"
                                id="eMailMsg"></textarea>
                        </div>
                        <div class="mb-0">
                            <div style="float: right">
                                <button class="btn btn-primary submit-btn" id="send_mail_btn">Send</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="overlay email-toggle-btn-mobile"></div>

    @include('common.modal.candidate_modal')
@endsection
@section('script_section')
    <script>
        $(document).ready(function() {
            $("#suitable_department").select2({
                placeholder: "Please select department"
            });
            $("#CandLogTable").DataTable({
                pageLength: 10,
                searching: false,
                bLengthChange: false,
                ordering: false,

            });
            $(document).on('click', '#HrScreening', function() {

                $('#HrScreeningModal').modal('show');
            });

            $(document).on("change", '#Irrelevant_Candidate', function() {
                var Irrelevant_Candidate = $(this).val();
                if (Irrelevant_Candidate == 'Y') {
                    $('#sui_dep_div').addClass('d-none');
                    $("#suitable_department").removeClass('reqinp_suit')
                } else {
                    $('#sui_dep_div').removeClass('d-none');
                    $("#suitable_department").addClass('reqinp_suit')
                }
            });


        });
        $(document).on('click', '#MoveCandidate', function() {
            var JAId = $(this).data('id');
            $('#MoveCandidate_JAId').val(JAId);
            $('#MoveCandidategModal').modal('show');
        });
        $(document).on('change', '#MoveCompany', function() {
            var CompanyId = $(this).val();
            $.ajax({
                type: "GET",
                url: "{{ route('getDepartment') }}?CompanyId=" + CompanyId,
                success: function(res) {
                    if (res) {
                        $("#MoveDepartment").empty();
                        $("#MoveDepartment").append(
                            '<option value="">Select Department</option>');
                        $.each(res, function(key, value) {
                            $("#MoveDepartment").append('<option value="' + value +
                                '">' +
                                key +
                                '</option>');
                        });
                        $('#MoveDepartment').val('<?= $_REQUEST['Department'] ?? '' ?>');
                    } else {
                        $("#MoveDepartment").empty();
                    }
                }
            });
        });
        $('#MoveCandidateForm').on('submit', function(e) {
            e.preventDefault();
            var form = this;
            $.ajax({
                url: $(form).attr('action'),
                method: $(form).attr('method'),
                data: new FormData(form),
                processData: false,
                dataType: 'json',
                contentType: false,
                success: function(data) {
                    if (data.status == 400) {
                        toastr.error(data.msg);
                    } else {
                        toastr.success(data.msg);
                        window.location.reload();
                    }
                }
            });
        });
        $('#ScreeningForm').on('submit', function(e) {
            e.preventDefault();
            var form = this;
            var reqcond = chkHrScreeninRequired();
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
                    success: function(data) {
                        if (data.status == 400) {
                            toastr.error(data.msg);
                        } else {
                            toastr.success(data.msg);
                            window.location.reload();
                        }
                    }
                });
            }
        });
        $(document).on('click', '#BlackListCandidate', function() {
            var JCId = $(this).data('id');
            var Remark = prompt("Please Enter Remark to BlackList Candidate");
            if (Remark != null) {
                $.ajax({
                    url: "{{ route('BlacklistCandidate') }}",
                    type: 'POST',
                    data: {
                        JCId: JCId,
                        Remark: Remark
                    },
                    dataType: 'json',
                    success: function(data) {
                        if (data.status == 200) {
                            toastr.success(data.msg);
                            window.location.reload();
                        } else {
                            toastr.error(data.msg);
                        }
                    }
                });
            } else {
                window.location.reload();
            }
        });

        $(document).on('click', '#UnBlockCandidate', function() {
            var JCId = $(this).data('id');
            var Remark = prompt("Please Enter Remark to Unblock Candidate");
            if (Remark != null) {
                $.ajax({
                    url: "{{ route('UnBlockCandidate') }}",
                    type: 'POST',
                    data: {
                        JCId: JCId,
                        Remark: Remark
                    },
                    dataType: 'json',
                    success: function(data) {
                        if (data.status == 200) {
                            toastr.success(data.msg);
                            window.location.reload();
                        } else {
                            toastr.error(data.msg);
                        }
                    }
                });
            } else {
                window.location.reload();
            }
        });

        $(document).on('click', '#SuitableFor', function() {
            // Get the JCId from the clicked element's data attribute
            var jobCandidateId = $(this).data('id');

            // Perform an AJAX request to fetch candidate suitability data
            $.ajax({
                url: "{{ route('get_candidate_suitablity') }}",
                type: "POST",
                data: {
                    JCId: jobCandidateId
                },
                dataType: "json",
                success: function(response) {
                    if (response.status === 200) {
                        // Populate fields with the fetched data
                        $('#Irrelevant_Candidate').val(response.data.Irrelevant_Candidate).trigger(
                            'change');

                        // Split the Suitable_For data into an array and set it in the select element
                        var suitableForArray = response.data.Suitable_For.split(', ');
                        $('#suitable_department').val(suitableForArray).trigger('change');

                        // Set the suitable remark
                        $('#suitable_remark').val(response.data.Suitable_Remark);
                    } else {
                        // Handle error response
                        alert('An error occurred while fetching data.');
                    }
                },
                error: function() {
                    // Handle AJAX request failure
                    alert('Failed to communicate with the server. Please try again later.');
                }
            });

            // Show the modal after making the AJAX request
            $("#suitable_modal").modal('show');
        });


        $("#SuitableForm").on().submit(function(e) {
            e.preventDefault();
            var form = this;
            var reqcond = chkSuitableRequired();
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
                    success: function(data) {
                        if (data.status == 400) {
                            toastr.error(data.msg);
                        } else {
                            toastr.success(data.msg);
                            window.location.reload();
                        }
                    }
                });
            }
        });

        function chkHrScreeninRequired() {
            var res = 0;
            $('.reqinp_scr').each(function() {
                if ($(this).val() == '' || $(this).val() == null) {
                    $(this).addClass('errorfield');
                    res = 1;
                } else {
                    $(this).removeClass('errorfield');
                }
            });
            return res;
        }

        function chkSuitableRequired() {
            var res = 0;
            $('.reqinp_suit').each(function() {
                if ($(this).val() == '' || $(this).val() == null) {
                    $(this).addClass('errorfield');
                    res = 1;
                } else {
                    $(this).removeClass('errorfield');
                }
            });
            return res;
        }





        function GetProfileData() {
            var JCId = $('#JCId').val();
            $.ajax({
                url: "{{ route('Candidate_ProfileData') }}",
                type: "POST",
                data: {
                    JCId: JCId
                },
                dataType: "json",
                success: function(data) {
                    if (data.status == 200) {
                        $('#Pro_JCId').val(data.data.JCId);
                        $('#FName').val(data.data.FName);
                        $('#MName').val(data.data.MName);
                        $('#LName').val(data.data.LName);
                        $('#DOB').val(data.data.DOB);
                        $('#Mobile').val(data.data.Phone);
                        $('#EMail').val(data.data.Email);


                    } else {
                        alert('error');
                    }
                }
            });
        }

        function GetPersonalData() {
            var JCId = $('#JCId').val();
            $.ajax({
                url: "{{ route('Candidate_PersonalData') }}",
                type: "POST",
                data: {
                    JCId: JCId
                },
                dataType: "json",
                success: function(data) {
                    if (data.status == 200) {
                        $('#P_JCId').val(data.data.JCId);
                        $("#FatherTitle").val(data.data.FatherTitle);
                        $("#FatherName").val(data.data.FatherName);
                        $('#Gender').val(data.data.Gender);
                        $('#Aadhaar').val(data.data.Aadhaar);
                        $('#Nationality').val(data.data.Nationality);
                        $('#Religion').val(data.data.Religion);
                        $('#OtherReligion').val(data.data.OtherReligion);
                        $('#MaritalStatus').val(data.data.MaritalStatus);
                        $('#MarriageDate').val(data.data.MarriageDate);
                        $('#SpouseName').val(data.data.SpouseName);
                        $('#Category').val(data.data.Caste);
                        $('#OtherCategory').val(data.data.OtherCaste);
                        $('#DrivingLicense').val(data.data.DrivingLicense);
                        $('#LValidity').val(data.data.LValidity);
                        if (data.data.MaritalStatus == 'Married') {
                            $('#MDate').removeClass('d-none');
                            $('#Spouse').removeClass('d-none');
                        } else {
                            $('#MDate').addClass('d-none');
                            $('#Spouse').addClass('d-none');
                        }
                    } else {
                        alert('error');
                    }
                }
            });
        }

        function GetEmergencyContact() {
            var JCId = $('#JCId').val();
            $.ajax({
                url: "{{ route('Candidate_EmergencyContact') }}",
                type: "POST",
                data: {
                    JCId: JCId
                },
                dataType: "json",
                success: function(data) {
                    $('#Emr_JCId').val($('#JCId').val());
                    $('#PrimaryName').val(data.data.cont_one_name);
                    $('#PrimaryRelation').val(data.data.cont_one_relation);
                    $('#PrimaryPhone').val(data.data.cont_one_number);
                    $('#SecondaryName').val(data.data.cont_two_name);
                    $('#SecondaryRelation').val(data.data.cont_two_relation);
                    $('#SecondaryPhone').val(data.data.cont_two_number);
                }
            });
        }

        function GetBankInfo() {
            var JCId = $('#JCId').val();
            $.ajax({
                url: "{{ route('Candidate_BankInfo') }}",
                type: "POST",
                data: {
                    JCId: JCId
                },
                dataType: "json",
                success: function(data) {
                    $('#Bank_JCId').val($('#JCId').val());
                    $('#BankName').val(data.data.BankName);
                    $('#BranchName').val(data.data.BranchName);
                    $('#AccountNumber').val(data.data.AccountNumber);
                    $('#IFSCCode').val(data.data.IFSCCode);
                    $('#PAN').val(data.data.PAN);
                    $('#UAN').val(data.data.UAN);
                    $('#PFNumber').val(data.data.PFNumber);
                    $('#ESICNumber').val(data.data.ESICNumber);
                    $('#Passport').val(data.data.Passport);
                }
            });
        }

        function GetFamily() {
            var JCId = $('#JCId').val();
            $.ajax({
                url: "{{ route('Candidate_Family') }}",
                type: "POST",
                data: {
                    JCId: JCId
                },
                dataType: "json",
                success: function(data) {
                    $('#Family_JCId').val($('#JCId').val());
                    MemberCount = data.data.length;
                    for (var i = 1; i <= MemberCount; i++) {

                        familymember(i);
                        $('#Relation' + i).val(data.data[i - 1].relation);
                        $('#RelationName' + i).val(data.data[i - 1].name);
                        $('#RelationDOB' + i).val(data.data[i - 1].dob);
                        $('#RelationQualification' + i).val(data.data[i - 1].qualification);
                        $('#RelationOccupation' + i).val(data.data[i - 1].occupation);
                    }
                }
            });
        }

        function getEmployee(ComapnyId) {
            var ComapnyId = ComapnyId;
            $.ajax({
                type: "GET",
                url: "{{ route('getEmpByCompany') }}?ComapnyId=" + ComapnyId,
                async: false,
                beforeSend: function() {
                    $('#EmpLoader').removeClass('d-none');
                    $('#review_to').addClass('d-none');
                },

                success: function(res) {
                    if (res) {
                        $('#EmpLoader').addClass('d-none');
                        $('#review_to').removeClass('d-none');
                        $("#review_to").empty();

                        $.each(res, function(key, value) {
                            $("#review_to").append('<option value="' + value + '">' + key +
                                '</option>');
                        });
                    } else {
                        $("#review_to").empty();
                    }
                }
            });
        }

        var MemberCount = 1;
        var EducationCount = 6;
        var WorkExpCount = 1;
        var TrainingCount = 1;
        var RefCount = 1;
        var VRefCount = 1;
        var LanguageCount = 2;
        var EducationList = '';
        var SpecializationList = '';
        var CollegeList = '';
        var YearList = '';
        getEducationList();
        getAllSP();
        getCollegeList();
        getYearList();

        function getEducationList() {
            $.ajax({
                type: "GET",
                url: "{{ route('getEducation') }}",
                async: false,
                success: function(res) {

                    if (res) {
                        EducationList = '<option value="">Select</option>';
                        $.each(res, function(key, value) {
                            EducationList = EducationList + '<option value="' + key + '">' + key +
                                '</option>';
                        });

                    }
                }
            });
        } //getEducationList

        function getCollegeList() {
            $.ajax({
                type: "GET",
                url: "{{ route('getCollege') }}",
                async: false,
                success: function(res) {

                    if (res) {
                        CollegeList = '<option value="">Select</option>';
                        $.each(res, function(key, value) {
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
                success: function(res) {
                    if (res) {
                        SpecializationList = '<option value="">Select</option>';
                        $.each(res, function(key, value) {
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

        function familymember(number) {

            var x = '';
            x += '<tr>';
            x += '<td>' + '<select class="form-select form-select-sm" name="Relation[]" id="Relation' + number + '">' +
                '<option value=""></option>' +
                '<option value="Father">Father</option>' +
                '<option value="Mother">Mother</option>' + '<option value="Brother">Brother</option>' +
                '<option value="Sister">Sister</option>' +
                '<option value="Spouse">Spouse</option>' + '<option value="Son">Son</option>' +
                '<option value="Daughter">Daughter</option>' + '</select>' +
                '</td>';
            x += '<td>' +
                '<input type="text" name="RelationName[]" id="RelationName' + number +
                '" class="form-control form-control-sm">' +
                '</td>';
            x += '<td>' +
                '<input type="date" name="RelationDOB[]" id="RelationDOB' + number +
                '" class="form-control form-control-sm">' +
                '</td>';
            x += '<td>' +

                ' <select  name="RelationQualification[]" id="RelationQualification' +
                number +
                '" class="form-control form-select form-select-sm" >' +
                '  <option value="" selected disabled>Select Education</option>' + EducationList +
                '</select>' +
                '</td>';
            x += '<td>' +
                '<input type="text" name="RelationOccupation[]" id="RelationOccupation' + number +
                '" class="form-control form-control-sm">' +
                '</td>';
            x += '<td>' + '<button class="btn btn-sm btn-danger" id="removeMember">Delete</button>' + '</td>';
            x += '</tr>';
            $('#FamilyData').append(x);
        } //familymember

        function GetQualification() {
            var JCId = $('#JCId').val();
            $('#Edu_JCId').val($('#JCId').val());
            $.ajax({
                url: "{{ route('Candidate_Education') }}",
                type: "POST",
                data: {
                    JCId: JCId,
                },
                dataType: "json",
                success: function(data) {
                    if (data.status == 200) {

                        EducationCount = data.data.length;
                        for (var i = 1; i <= EducationCount; i++) {
                            if (i >= 7) {
                                Qualification(i);
                            }
                            // $('#Qualification' + i).val(data.data[i - 1].Qualification);
                            $('#Course' + i).val(data.data[i - 1].Course);
                            $('#Specialization' + i).val(data.data[i - 1].Specialization);
                            $('#Collage' + i).val(data.data[i - 1].Institute);
                            $('#PassingYear' + i).val(data.data[i - 1].YearOfPassing);
                            $('#Percentage' + i).val(data.data[i - 1].CGPA);
                            if (data.data[i - 1].Institute == '637') {
                                $('#OtherInstitute' + i).removeClass('d-none');
                                $('#OtherInstitute' + i).addClass('reqinp');
                                $('#OtherInstitute' + i).val(data.data[i - 1].OtherInstitute);
                            }

                        }
                    }
                }

            });
        } //GetQualification

        function Qualification(num) {
            var a = '';
            a += '<tr>';
            a += '<td>' + '<input type="text" name="Qualification[]" id="Qualification' + num +
                '" class="form-control form-control-sm">' + '</td>' +
                '<td>' + '<select class="form-select form-select-sm" name="Course[]" id="Course' + num +
                '" onchange="getSpecialization(this.value,' + num + ')">' + EducationList +
                '</select>' +
                '</td>' +
                '<td>' + '<select class="form-select form-select-sm" name="Specialization[]" id="Specialization' + num +
                '">' + SpecializationList +
                '</select>' +
                '</td>' +
                '<td>' + '<select class="form-select form-select-sm" name="Collage[]" id="Collage' + num +
                '" onchange="getOtherInstitute(' + num + ')">' + CollegeList +
                '</select>' +
                '<input type="text" name="OtherInstitute[]" id="OtherInstitute' + num +
                '" class="form-control form-control-sm mt-1 d-none">' +
                '</td>' +
                '<td>' + '<select class="form-select form-select-sm" name="PassingYear[]" id="PassingYear' + num +
                '">' +
                YearList +
                '</select>' +
                '</td>' +
                '<td>' + '<input type="text" name="Percentage[]" id="Percentage' + num +
                '" class="form-control form-control-sm">' + '</td>' +
                '<td>' +
                '<div class="d-flex order-actions"><a href="javascript:;" class="ms-3" id="removeQualification"><i class="bx bxs-trash text-danger"></i></a></div>' +
                '</td>';

            a += '</tr>';

            $('#EducationData').append(a);
        } //Qualification

        function getWorkExp() {
            var JCId = $('#JCId').val();
            $.ajax({
                url: "{{ route('Candidate_Experience') }}",
                type: "POST",
                data: {
                    JCId: JCId
                },
                dataType: "json",
                success: function(data) {
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
            });
        } //getWorkExp

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
                success: function(data) {

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

        function getPreOrgRef() {
            $('#PreOrgRef_JCId').val($('#JCId').val());
            var JCId = $('#JCId').val();
            $.ajax({
                url: "{{ route('Candidate_PreOrgRef') }}",
                type: "POST",
                data: {
                    JCId: JCId
                },
                dataType: "json",
                success: function(data) {

                    RefCount = data.data.length;
                    for (var i = 1; i <= RefCount; i++) {
                        if (i >= 2) {
                            PreviousOrgReference(i);
                        }
                        $('#PreOrgName' + i).val(data.data[i - 1].name);
                        $('#PreOrgCompany' + i).val(data.data[i - 1].company);
                        $('#PreOrgEmail' + i).val(data.data[i - 1].email);
                        $('#PreOrgContact' + i).val(data.data[i - 1].contact);
                        $('#PreOrgDesignation' + i).val(data.data[i - 1].designation);


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
                '<td>' + '<input type="text" name="PreOrgEmail[]" id="PreOrgEmail' + num +
                '" class="form-control form-control-sm">' + '</td>' +
                '<td>' + '<input type="text" name="PreOrgContact[]" id="PreOrgContact' + num +
                '" class="form-control form-control-sm">' + '</td>' +
                '<td>' + '<input type="text" name="PreOrgDesignation[]" id="PreOrgDesignation' + num +
                '" class="form-control form-control-sm">' + '</td>' +
                '<td>' +
                '<div class="d-flex order-actions"><a href="javascript:;" class="ms-3" id="removePreOrgRef"><i class="bx bxs-trash text-danger"></i></a></div>' +
                '</td>';
            b += '</tr>';
            $('#PreOrgRefData').append(b);
        } //PreviousOrgReference

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
                success: function(data) {

                    RefCount = data.data.length;
                    for (var i = 1; i <= RefCount; i++) {
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
                '</select>  <input type="text" name="OtherCompany[]" id="OtherCompany' + num +
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

        function getVnrRef_Business() {
            $('#Business_JCId').val($('#JCId').val());
            var JCId = $('#JCId').val();
            $.ajax({
                url: "{{ route('Candidate_VnrRef_Business') }}",
                type: "POST",
                data: {
                    JCId: JCId
                },
                dataType: "json",
                success: function(data) {
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

        function getOtherSeed() {
            var JCId = $('#JCId').val();
            $('#OtherSeed_JCId').val(JCId);
            $.ajax({
                url: "{{ route('Candidate_Other_Seed_Relation') }}",
                type: "POST",
                data: {
                    JCId: JCId
                },
                dataType: "json",
                success: function(data) {
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
                success: function(data) {

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

        function getSpecialization(EducationId, No) {
            var EducationId = EducationId;
            var No = No;
            $.ajax({
                type: "GET",
                url: "{{ route('getSpecialization') }}?EducationId=" + EducationId,
                async: false,

                success: function(res) {

                    if (res) {

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
        } //getSpecialization

        function getLocation(StateId) {
            var StateId = StateId;
            $.ajax({
                type: "GET",
                url: "{{ route('getDistrict') }}?StateId=" + StateId,
                async: false,
                beforeSend: function() {
                    $('#PreDistLoader').removeClass('d-none');
                    $('#PreDistrict').addClass('d-none');
                },
                success: function(res) {
                    if (res) {
                        setTimeout(function() {
                                $('#PreDistLoader').addClass('d-none');
                                $('#PreDistrict').removeClass('d-none');
                                $("#PreDistrict").empty();
                                $("#PreDistrict").append(
                                    '<option value="" selected disabled >Select District</option>');
                                $.each(res, function(key, value) {
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
                beforeSend: function() {
                    $('#PermDistLoader').removeClass('d-none');
                    $('#PermDistrict').addClass('d-none');
                },
                success: function(res) {
                    if (res) {
                        setTimeout(function() {
                                $('#PermDistLoader').addClass('d-none');
                                $('#PermDistrict').removeClass('d-none');
                                $("#PermDistrict").empty();
                                $("#PermDistrict").append(
                                    '<option value="" selected disabled >Select District</option>');
                                $.each(res, function(key, value) {
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

        function GetCurrentAddress() {
            var JCId = $('#JCId').val();
            $.ajax({
                url: "{{ route('Candidate_CurrentAddress') }}",
                type: "POST",
                data: {
                    JCId: JCId
                },
                dataType: "json",
                success: function(data) {
                    $('#Current_JCId').val($('#JCId').val());
                    $('#PreAddress').val(data.data.pre_address);
                    $('#PreCity').val(data.data.pre_city);
                    $('#PreState').val(data.data.pre_state);
                    $('#PrePinCode').val(data.data.pre_pin);
                    $('#PreDistrict').val(data.data.pre_dist);
                }
            });
        } // GetCurrentAddress

        function GetPermanentAddress() {
            var JCId = $('#JCId').val();
            $.ajax({
                url: "{{ route('Candidate_PermanentAddress') }}",
                type: "POST",
                data: {
                    JCId: JCId
                },
                dataType: "json",
                success: function(data) {
                    $('#Permanent_JCId').val($('#JCId').val());
                    $('#PermAddress').val(data.data.perm_address);
                    $('#PermCity').val(data.data.perm_city);
                    $('#PermState').val(data.data.perm_state);
                    $('#PermPinCode').val(data.data.perm_pin);
                    $('#PermDistrict').val(data.data.perm_dist);
                }
            });
        } // GetPermanentAddress

        function GetCurrentEmployementData() {
            var JCId = $('#JCId').val();
            $.ajax({
                url: "{{ route('Candidate_PersonalData') }}",
                type: "POST",
                data: {
                    JCId: JCId
                },
                dataType: "json",
                success: function(data) {
                    $('#Curr_JCId').val($('#JCId').val());
                    $('#Currcompany_name').val(data.data.PresentCompany);
                    $('#CurrDepartment').val(data.data.PresentDepartment);
                    $('#CurrDesignation').val(data.data.Designation);
                    $('#CurrDateOfJoining').val(data.data.JobStartDate);
                    $('#CurrReportingTo').val(data.data.Reporting);
                    $('#CurrRepDesig').val(data.data.RepDesig);
                    $('#CurrJobResponsibility').val(data.data.JobResponsibility);
                    $('#CurrReason').val(data.data.ResignReason);
                    $('#CurrNoticePeriod').val(data.data.NoticePeriod);
                }
            });
        } // GetCurrentEmployementData

        function GetPresentSalaryDetails() {
            var JCId = $('#JCId').val();
            $.ajax({
                url: "{{ route('Candidate_PersonalData') }}",
                type: "POST",
                data: {
                    JCId: JCId
                },
                dataType: "json",
                success: function(data) {
                    $('#Sal_JCId').val($('#JCId').val());
                    $('#CurrSalary').val(data.data.GrossSalary);
                    $('#CurrCTC').val(data.data.CTC);
                    $('#CurrDA').val(data.data.DAHq);
                    $('#DAOutHq').val(data.data.DAOutHq);
                    $('#PetrolAlw').val(data.data.PetrolAlw);
                    $('#PhoneAlw').val(data.data.PhoneAlw);
                    $('#HotelElg').val(data.data.HotelElg);
                }
            });
        } // GetPresentSalaryDetails


        $(document).on('click', '.dlchk', function() {
            var val = $(this).data('value');

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

        $(document).on('click', '.crime', function() {
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

        $(document).on('click', '#addMember', function() {
            MemberCount++;
            familymember(MemberCount);
        }); // addMember

        $(document).on('click', '#removeMember', function() {
            if (confirm('Are you sure you want to delete this member?')) {
                $(this).closest('tr').remove();
                MemberCount--;
            }
        });

        $(document).on('click', '#addEducation', function() {
            EducationCount++;
            Qualification(EducationCount);
        });

        $(document).on('click', '#removeQualification', function() {
            if (confirm('Are you sure you want to delete this record?')) {
                $(this).closest('tr').remove();
                EducationCount--;
            }
        });

        $(document).on('click', '#addExperience', function() {
            WorkExpCount++;
            WorkExperience(WorkExpCount);
        });

        $(document).on('click', '#removeWorkExp', function() {
            if (confirm('Are you sure you want to delete this record?')) {
                $(this).closest('tr').remove();
                WorkExpCount--;
            }
        });

        $(document).on('click', '#addTraining', function() {
            TrainingCount++;
            Training(TrainingCount);
        });

        $(document).on('click', '#removeTraining', function() {
            if (confirm('Are you sure you want to delete this record?')) {
                $(this).closest('tr').remove();
                TrainingCount--;
            }
        });

        $(document).on('click', '#addPreOrgRef', function() {
            RefCount++;
            PreviousOrgReference(RefCount);
        });

        $(document).on('click', '#removePreOrgRef', function() {
            if (confirm('Are you sure you want to delete this record?')) {
                $(this).closest('tr').remove();
                RefCount--;
            }
        });

        $(document).on('click', '#addVnrRef', function() {
            VRefCount++;
            VNRReference(VRefCount);
        });

        $(document).on('click', '#removeVnrRef', function() {
            if (confirm('Are you sure you want to delete this record?')) {
                $(this).closest('tr').remove();
                VRefCount--;
            }
        });

        $(document).on('click', '#addLanguage', function() {
            LanguageCount++;
            LaguageProficiency(LanguageCount);
        });

        $(document).on('click', '#removeLanguage', function() {
            if (confirm('Are you sure you want to delete this record?')) {
                $(this).closest('tr').remove();
                LanguageCount--;
            }
        });

        $(document).on('click', '#addOtherSeed', function() {
            OtherSeedCount++;

            OtherSeed(OtherSeedCount);
            $(".tab-content").height('auto');
        });

        $(document).on('click', '#removeOtherSeed', function() {
            if (confirm('Are you sure you want to delete this record?')) {
                $(this).closest('tr').remove();
                OtherSeedCount--;
                $(".tab-content").height('auto');
            }
        });

        $(document).on('click', '#addVnrRef_Business', function() {
            VRef_Business_Count++;

            VNRReference_Business(VRef_Business_Count);
            $(".tab-content").height('auto');
        });

        $(document).on('click', '#removeVnrRef_Business', function() {
            if (confirm('Are you sure you want to delete this record?')) {
                $(this).closest('tr').remove();
                VRef_Business_Count--;
                $(".tab-content").height('auto');
            }
        });


        $(document).on('change', '#Religion', function() {
            var Religion = $(this).val();
            if (Religion == 'Others') {
                $('#OtherReligion').removeClass('d-none');
            } else {
                $('#OtherReligion').addClass('d-none');
            }
        });

        $(document).on('change', '#Category', function() {
            var Category = $(this).val();
            if (Category == 'Other') {
                $('#OtherCategory').removeClass('d-none');
            } else {
                $('#OtherCategory').addClass('d-none');
            }
        });

        $(document).on('change', '#MaritalStatus', function() {
            var MaritalStatus = $(this).val();
            if (MaritalStatus == 'Married') {
                $('#MDate').removeClass('d-none');
                $('#Spouse').removeClass('d-none');
            } else {
                $('#MDate').addClass('d-none');
                $('#Spouse').addClass('d-none');
            }
        });

        $('#CandidatePersonalForm').on('submit', function(e) {
            e.preventDefault();
            var form = this;
            $.ajax({
                url: $(form).attr('action'),
                method: $(form).attr('method'),
                data: new FormData(form),
                processData: false,
                dataType: 'json',
                contentType: false,
                success: function(data) {
                    console.log(data);
                    if (data.status == 400) {
                        toastr.error(data.msg);
                    } else {
                        $(form)[0].reset();
                        $('#createpostmodal').modal('hide');
                        toastr.success(data.msg);
                        window.location.reload();
                    }
                }
            });
        });

        $('#CandidateProfileForm').on('submit', function(e) {
            e.preventDefault();
            var form = this;
            $.ajax({
                url: $(form).attr('action'),
                method: $(form).attr('method'),
                data: new FormData(form),
                processData: false,
                dataType: 'json',
                contentType: false,
                success: function(data) {
                    console.log(data);
                    if (data.status == 400) {
                        toastr.error(data.msg);
                    } else {
                        $(form)[0].reset();
                        $('#createpostmodal').modal('hide');
                        toastr.success(data.msg);
                        window.location.reload();
                    }
                }
            });
        });

        $('#EmergencyContactForm').on('submit', function(e) {
            e.preventDefault();
            var form = this;
            $.ajax({
                url: $(form).attr('action'),
                method: $(form).attr('method'),
                data: new FormData(form),
                processData: false,
                dataType: 'json',
                contentType: false,
                success: function(data) {
                    if (data.status == 400) {
                        toastr.error(data.msg);
                    } else {
                        $(form)[0].reset();
                        $('#emergency_contact_modal').modal('hide');
                        toastr.success(data.msg);
                        window.location.reload();
                    }
                }
            });
        });

        $('#BankInfoForm').on('submit', function(e) {
            e.preventDefault();
            var form = this;
            $.ajax({
                url: $(form).attr('action'),
                method: $(form).attr('method'),
                data: new FormData(form),
                processData: false,
                dataType: 'json',
                contentType: false,
                success: function(data) {
                    if (data.status == 400) {
                        toastr.error(data.msg);
                    } else {
                        $(form)[0].reset();
                        $('#bank_info_modal').modal('hide');
                        toastr.success(data.msg);
                        window.location.reload();
                    }
                }
            });
        });

        $('#FamilyInfoForm').on('submit', function(e) {
            e.preventDefault();
            var form = this;
            $.ajax({
                url: $(form).attr('action'),
                method: $(form).attr('method'),
                data: new FormData(form),
                processData: false,
                dataType: 'json',
                contentType: false,
                success: function(data) {
                    if (data.status == 400) {
                        toastr.error(data.msg);
                    } else {
                        $(form)[0].reset();
                        $('#family_info_modal').modal('hide');
                        toastr.success(data.msg);
                        window.location.reload();
                    }
                }
            });
        });

        $('#CurrentAddressForm').on('submit', function(e) {
            e.preventDefault();
            var form = this;
            $.ajax({
                url: $(form).attr('action'),
                method: $(form).attr('method'),
                data: new FormData(form),
                processData: false,
                dataType: 'json',
                contentType: false,
                success: function(data) {
                    if (data.status == 400) {
                        toastr.error(data.msg);
                    } else {
                        $(form)[0].reset();
                        $('#current_address_modal').modal('hide');
                        toastr.success(data.msg);
                        window.location.reload();
                    }
                }
            });
        });

        $('#PermanentAddressForm').on('submit', function(e) {
            e.preventDefault();
            var form = this;
            $.ajax({
                url: $(form).attr('action'),
                method: $(form).attr('method'),
                data: new FormData(form),
                processData: false,
                dataType: 'json',
                contentType: false,
                success: function(data) {
                    if (data.status == 400) {
                        toastr.error(data.msg);
                    } else {
                        $(form)[0].reset();
                        $('#permanent_address_modal').modal('hide');
                        toastr.success(data.msg);
                        window.location.reload();
                    }
                }
            });
        });

        $('#EducationInfoForm').on('submit', function(e) {
            e.preventDefault();
            var form = this;
            $.ajax({
                url: $(form).attr('action'),
                method: $(form).attr('method'),
                data: new FormData(form),
                processData: false,
                dataType: 'json',
                contentType: false,
                success: function(data) {
                    if (data.status == 400) {
                        toastr.error(data.msg);
                    } else {
                        $(form)[0].reset();
                        $('#education_info_modal').modal('hide');
                        toastr.success(data.msg);
                        //  window.location.reload();
                    }
                }
            });
        });

        $('#WorkExpForm').on('submit', function(e) {
            e.preventDefault();
            var form = this;
            $.ajax({
                url: $(form).attr('action'),
                method: $(form).attr('method'),
                data: new FormData(form),
                processData: false,
                dataType: 'json',
                contentType: false,
                success: function(data) {
                    if (data.status == 400) {
                        toastr.error(data.msg);
                    } else {
                        $(form)[0].reset();
                        $('#work_exp_modal').modal('hide');
                        toastr.success(data.msg);
                        window.location.reload();
                    }
                }
            });
        });

        $('#CurrentEmpForm').on('submit', function(e) {
            e.preventDefault();
            var form = this;
            $.ajax({
                url: $(form).attr('action'),
                method: $(form).attr('method'),
                data: new FormData(form),
                processData: false,
                dataType: 'json',
                contentType: false,
                success: function(data) {
                    if (data.status == 400) {
                        toastr.error(data.msg);
                    } else {
                        $(form)[0].reset();
                        $('#current_emp_modal').modal('hide');
                        toastr.success(data.msg);
                        window.location.reload();
                    }
                }
            });
        });

        $('#CurrentSalaryForm').on('submit', function(e) {
            e.preventDefault();
            var form = this;
            $.ajax({
                url: $(form).attr('action'),
                method: $(form).attr('method'),
                data: new FormData(form),
                processData: false,
                dataType: 'json',
                contentType: false,
                success: function(data) {
                    if (data.status == 400) {
                        toastr.error(data.msg);
                    } else {
                        $(form)[0].reset();
                        $('#current_salary_modal').modal('hide');
                        toastr.success(data.msg);
                        window.location.reload();
                    }
                }
            });
        });

        $('#TrainingForm').on('submit', function(e) {
            e.preventDefault();
            var form = this;
            $.ajax({
                url: $(form).attr('action'),
                method: $(form).attr('method'),
                data: new FormData(form),
                processData: false,
                dataType: 'json',
                contentType: false,
                success: function(data) {
                    if (data.status == 400) {
                        toastr.error(data.msg);
                    } else {
                        $(form)[0].reset();
                        $('#training_modal').modal('hide');
                        toastr.success(data.msg);
                        window.location.reload();
                    }
                }
            });
        });

        $('#PreOrgRefForm').on('submit', function(e) {
            e.preventDefault();
            var form = this;
            $.ajax({
                url: $(form).attr('action'),
                method: $(form).attr('method'),
                data: new FormData(form),
                processData: false,
                dataType: 'json',
                contentType: false,
                success: function(data) {
                    if (data.status == 400) {
                        toastr.error(data.msg);
                    } else {
                        $(form)[0].reset();
                        $('#pre_org_ref_modal').modal('hide');
                        toastr.success(data.msg);
                        window.location.reload();
                    }
                }
            });
        });

        $('#VNRRefForm').on('submit', function(e) {
            e.preventDefault();
            var form = this;
            $.ajax({
                url: $(form).attr('action'),
                method: $(form).attr('method'),
                data: new FormData(form),
                processData: false,
                dataType: 'json',
                contentType: false,
                success: function(data) {
                    if (data.status == 400) {
                        toastr.error(data.msg);
                    } else {
                        $(form)[0].reset();
                        $('#vnr_ref_modal').modal('hide');
                        toastr.success(data.msg);
                        window.location.reload();
                    }
                }
            });
        });

        $('#OtherSeedForm').on('submit', function(e) {
            e.preventDefault();
            var form = this;
            $.ajax({
                url: $(form).attr('action'),
                method: $(form).attr('method'),
                data: new FormData(form),
                processData: false,
                dataType: 'json',
                contentType: false,
                success: function(data) {
                    if (data.status == 400) {
                        toastr.error(data.msg);
                    } else {
                        $(form)[0].reset();
                        $('#other_seed_modal').modal('hide');
                        toastr.success(data.msg);
                        window.location.reload();
                    }
                }
            });
        });

        $('#BusinessForm').on('submit', function(e) {
            e.preventDefault();
            var form = this;
            $.ajax({
                url: $(form).attr('action'),
                method: $(form).attr('method'),
                data: new FormData(form),
                processData: false,
                dataType: 'json',
                contentType: false,
                success: function(data) {
                    if (data.status == 400) {
                        toastr.error(data.msg);
                    } else {
                        $(form)[0].reset();
                        $('#vnr_business_ref_modal').modal('hide');
                        toastr.success(data.msg);
                        window.location.reload();
                    }
                }
            });
        });

        $('#about_form').on('submit', function(e) {
            e.preventDefault();
            var form = this;
            var formData = new FormData(this);
            formData.append('JCId', $('#JCId').val());
            $.ajax({
                url: $(form).attr('action'),
                method: $(form).attr('method'),
                data: formData,
                processData: false,
                dataType: 'json',
                contentType: false,
                success: function(data) {
                    if (data.status == 400) {
                        toastr.error(data.msg);
                    } else {
                        $(form)[0].reset();
                        $('#vnr_business_ref_modal').modal('hide');
                        toastr.success(data.msg);
                        window.location.reload();
                    }
                }
            });
        });

        $('#SendMailForm').on('submit', function(e) {
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
                    $('#send_mail_btn').html('<i class="fa fa-spinner fa-spin"></i> Sending...');
                },
                success: function(data) {
                    if (data.status == 400) {
                        toastr.error(data.msg);
                    } else {
                        $(form)[0].reset();
                        $('.compose-mail-popup').hide();
                        toastr.success(data.msg);
                    }
                }
            });
        });

        function printInterviewForm(url) {
            $("<iframe>") // create a new iframe element
                .hide() // make it invisible
                .attr("src", url) // point the iframe to the page you want to print
                .appendTo("body");
        }

        function printJoiningForm(url) {
            $("<iframe>") // create a new iframe element
                .hide() // make it invisible
                .attr("src", url) // point the iframe to the page you want to print
                .appendTo("body");
        }

        $("#permanent_chk").change(function() {
            if (!this.checked) {
                $("#permanent_div").addClass("d-none");
            } else {
                $("#permanent_div").removeClass("d-none");
            }
        });

        $("#temporary_chk").change(function() {
            if (!this.checked) {
                $("#temporary_div").addClass("d-none");
                $("#temporary_div1").addClass("d-none");
            } else {
                $("#temporary_div").removeClass("d-none");
                $("#temporary_div1").removeClass("d-none");
            }
        });

        $("#administrative_chk").change(function() {
            if (!this.checked) {
                $("#administrative_div").addClass("d-none");
            } else {
                $("#administrative_div").removeClass("d-none");
            }
        });

        $("#functional_chk").change(function() {
            if (!this.checked) {
                $("#functional_div").addClass("d-none");
            } else {
                $("#functional_div").removeClass("d-none");
            }
        });

        $(document).on('change', '#Grade', function() {
            var Grade = $(this).val();
            var value = 'nopnot';
            if (Grade >= 70) {
                $('.scon').css('display', 'none');
                $("input[name=ServiceCond][value=" + value + "]").prop('checked', true);
            } else {
                $('.scon').css('display', 'inline-block');
                $("input[name=ServiceCond][value=" + value + "]").prop('checked', false);
            }

            $.ajax({
                type: "GET",
                url: "{{ route('get_designation_by_grade_department') }}?GradeId=" + Grade +
                    "&DepartmentId=" + $('#SelectedForD').val(),
                success: function(res) {
                    if (res.status == 200) {
                        $("#Designation").empty();
                        $("#Designation").append(
                            '<option value="" selected>Select Designation</option>');
                        $.each(res.grade_designation_list, function(key, value) {
                            $("#Designation").append('<option value="' + value + '">' +
                                key +
                                '</option>');
                        });
                    } else {
                        $("#Designation").empty();
                    }
                }
            });
        });

        $(document).on('change', '#Designation', function() {
            let DesigId = $(this).val();
            $.ajax({
                type: "GET",
                url: "{{ route('get_mw_by_designation') }}?DesigId=" + DesigId,
                success: function(res) {
                    if (res.status == 200) {
                        $("#MW").val(res.category)
                    } else {
                        $("#MW").empty();
                    }
                }
            });
        });

        $(document).on('click', '#offerltredit', function() {
            var JAId = $(this).data('id');
            $.ajax({
                type: "GET",
                url: "{{ route('get_offerltr_basic_detail') }}?JAId=" + JAId,
                success: function(res) {
                    if (res.status == 200) {
                        $('#Of_JAId').val(JAId);
                        $('#JCId').val(res.candidate_detail.JCId);
                        $('#SelectedForC').val(res.candidate_detail.SelectedForC);
                        $('#SelectedForD').val(res.candidate_detail.SelectedForD);
                        $("#Grade").empty();
                        $("#Grade").append(
                            '<option value="">Select Grade</option>');
                        $.each(res.grade_list, function(key, value) {
                            $("#Grade").append('<option value="' + value + '">' + key +
                                '</option>');
                        });
                        $('#Grade').val(res.candidate_detail.Grade);
                        var name = res.candidate_detail.FName;
                        if (res.candidate_detail.MName != null) {
                            name += ' ' + res.candidate_detail.MName;
                        }
                        name += ' ' + res.candidate_detail.LName;
                        $('#CandidateName').val(name);
                        $('#Father').val(res.candidate_detail.FatherName);
                        $('#SelectedDepartment').val(res.candidate_detail.department_name);

                        $("#SubDepartment").empty();
                        $("#SubDepartment").append(
                            '<option value="">Select Sub Department</option>');
                        $.each(res.sub_department_list, function(key, value) {
                            $("#SubDepartment").append('<option value="' + value + '">' + key +
                                '</option>');
                        });

                        $('#SubDepartment').val(res.candidate_detail.SubDepartment);

                        $("#Section").empty();
                        $("#Section").append(
                            '<option value="">Select Section</option>');
                        $.each(res.section_list, function(key, value) {
                            $("#Section").append('<option value="' + value + '">' + key +
                                '</option>');
                        });

                        $('#Section').val(res.candidate_detail.Section);

                        $("#Designation").empty();
                        $("#Designation").append(
                            '<option value="">Select Designation</option>');
                        $.each(res.grade_designation_list, function(key, value) {
                            $("#Designation").append('<option value="' + value + '">' + key +
                                '</option>');
                        });

                        $('#Designation').val(res.candidate_detail.Designation);

                        $('#DesigSuffix').val(res.candidate_detail.DesigSuffix);
                        $("#DesignationRep").empty();
                        $("#DesignationRep").append(
                            '<option value="">Select Reporting Designation</option>');
                        $.each(res.designation_list, function(key, value) {
                            $("#DesignationRep").append('<option value="' + value + '">' + key +
                                '</option>');
                        });

                        $('#DesignationRep').val(res.candidate_detail.reporting_only_desig);


                        $("#Vertical").empty();
                        $("#Vertical").append(
                            '<option value="">Select Vertical</option>');
                        $.each(res.vertical_list, function(key, value) {
                            $("#Vertical").append('<option value="' + value + '">' + key +
                                '</option>');
                        });
                        if (res.candidate_detail.Department == 15) {
                            $("#bu_tr").removeClass('d-none');
                            $("#zone_tr").removeClass('d-none');
                            $("#region_tr").removeClass('d-none');
                            $("#territory_tr").removeClass('d-none');
                            $.each(res.bu_list, function(key, value) {
                                $("#BU").append('<option value="' + value + '">' + key +
                                    '</option>');
                            });
                            $("#BU").val(res.candidate_detail.BU);
                            $.each(res.zone_list, function(key, value) {
                                $("#Zone").append('<option value="' + value + '">' + key +
                                    '</option>');
                            });
                            $("#Zone").val(res.candidate_detail.Zone);

                            $.each(res.region_list, function(key, value) {
                                $("#Region").append('<option value="' + value + '">' + key +
                                    '</option>');
                            });
                            $("#Region").val(res.candidate_detail.Region);

                            $.each(res.territory_list, function(key, value) {
                                $("#Territory").append('<option value="' + value + '">' + key +
                                    '</option>');
                            });
                            $("#Territory").val(res.candidate_detail.Territory);
                        }


                        $('#Vertical').val(res.candidate_detail.VerticalId);
                        $("#RepLineVisibility").val(res.candidate_detail.RepLineVisibility);

                        $("#AdministrativeDepartment").empty();
                        $("#AdministrativeDepartment").append(
                            '<option value="">Select Department</option>');
                        $.each(res.department_list, function(key, value) {
                            $("#AdministrativeDepartment").append('<option value="' + value +
                                '">' + key +
                                '</option>');
                        });


                        $("#FunctionalDepartment").empty();
                        $("#FunctionalDepartment").append(
                            '<option value="">Select Department</option>');
                        $.each(res.department_list, function(key, value) {
                            $("#FunctionalDepartment").append('<option value="' + value + '">' +
                                key +
                                '</option>');
                        });

                        $("#AdministrativeEmployee").empty();
                        $("#AdministrativeEmployee").append(
                            '<option value="">Select Employee</option>');
                        $.each(res.employee_list, function(key, value) {
                            $("#AdministrativeEmployee").append('<option value="' + key + '">' +
                                value +
                                '</option>');
                        });

                        $("#FunctionalEmployee").empty();
                        $("#FunctionalEmployee").append(
                            '<option value="">Select Employee</option>');
                        $.each(res.employee_list, function(key, value) {
                            $("#FunctionalEmployee").append('<option value="' + key + '">' +
                                value +
                                '</option>');
                        });

                        $("#AftDesignation").empty();
                        $("#AftDesignation").append(
                            '<option value="0">Select Designation</option>');
                        $.each(res.designation_list, function(key, value) {
                            $("#AftDesignation").append('<option value="' + value + '">' + key +
                                '</option>');
                        });

                        $("#AftGrade").empty();
                        $("#AftGrade").append(
                            '<option value="">Select Grade</option>');
                        $.each(res.grade_list, function(key, value) {
                            $("#AftGrade").append('<option value="' + value + '">' + key +
                                '</option>');
                        });

                        $("#Of_PermState").empty();
                        $("#Of_PermState").append(
                            '<option value="">Select State</option>');
                        $.each(res.state_list, function(key, value) {
                            $("#Of_PermState").append('<option value="' + value + '">' + key +
                                '</option>');
                        });

                        $("#PermHQ").empty();
                        $("#PermHQ").append(
                            '<option value="">Select HQ</option>');
                        $.each(res.perm_headquarter_list, function(key, value) {
                            $("#PermHQ").append('<option value="' + value + '">' + key +
                                '</option>');
                        });

                        $("#TempState").empty();
                        $("#TempState").append(
                            '<option value="">Select State</option>');
                        $.each(res.state_list, function(key, value) {
                            $("#TempState").append('<option value="' + value + '">' + key +
                                '</option>');
                        });

                        $("#TempState1").empty();
                        $("#TempState1").append(
                            '<option value="">Select State</option>');
                        $.each(res.state_list, function(key, value) {
                            $("#TempState1").append('<option value="' + value + '">' + key +
                                '</option>');
                        });

                        $("#TempHQ").empty();
                        $("#TempHQ").append(
                            '<option value="">Select HQ</option>');
                        $.each(res.temp_headquarter_list, function(key, value) {
                            $("#TempHQ").append('<option value="' + value + '">' + key +
                                '</option>');
                        });

                        $("#TempHQ1").empty();
                        $("#TempHQ1").append(
                            '<option value="">Select HQ</option>');
                        $.each(res.temp1_headquarter_list, function(key, value) {
                            $("#TempHQ1").append('<option value="' + value + '">' + key +
                                '</option>');
                        });

                        $("#vehicle_policy").empty();
                        $("#vehicle_policy").append('<option value="">Select Policy</option>');
                        $("#vehicle_policy").append('<option value="NA">NA</option>');
                        $.each(res.vehicle_policy_list, function(key, value) {
                            $("#vehicle_policy").append('<option value="' + value + '">' + key +
                                '</option>');
                        });
                        $("#vehicle_policy").val(res.candidate_detail.Vehicle_Policy);
                        $("#mobile_allow").val(res.candidate_detail.Mobile_Handset);
                        if (res.candidate_detail.GPRS == 1) {
                            $("#GPRS").prop('checked', true);
                            $("#GPRS").val('1');
                        }
                        $("#Mobile_Remb").val(res.candidate_detail.Mobile_Remb);
                        $("#Communication_Allowance").val(res.candidate_detail.communication_allowance);
                        if (res.candidate_detail.FixedS == 1) {
                            $('#permanent_chk').prop('checked', true);
                            $("#permanent_div").removeClass("d-none");
                            $('#Of_PermState').val(res.candidate_detail.F_StateHq);
                            $('#PermHQ').val(res.candidate_detail.F_LocationHq);
                            $('#Of_PermCity').val(res.candidate_detail.F_City);
                        } else {
                            $("#permanent_div").addClass("d-none");
                        }
                        if (res.candidate_detail.TempS == 1) {
                            $('#temporary_chk').prop('checked', true);
                            $("#temporary_div").removeClass("d-none");
                            $("#temporary_div1").removeClass("d-none");
                            $('#TempState').val(res.candidate_detail.T_StateHq);
                            $('#TempHQ').val(res.candidate_detail.T_LocationHq);
                            $('#TempCity').val(res.candidate_detail.T_City);
                            $('#TemporaryMonth').val(res.candidate_detail.TempM);

                            $('#TempState1').val(res.candidate_detail.T_StateHq1);
                            $('#TempHQ1').val(res.candidate_detail.T_LocationHq1);
                            $('#TempCity1').val(res.candidate_detail.T_City1);
                            $('#TemporaryMonth1').val(res.candidate_detail.TempM1);
                        } else {
                            $("#temporary_div").addClass("d-none");
                            $("#temporary_div1").addClass("d-none");
                        }

                        if (res.candidate_detail.Functional_R == 1) {
                            $('#functional_chk').prop('checked', true);
                            $("#functional_div").removeClass("d-none");
                            $('#FunctionalDepartment').val(res.candidate_detail.Functional_Dpt);
                            $('#FunctionalEmployee').val(res.candidate_detail.F_ReportingManager);

                        } else {
                            $("#functional_div").addClass("d-none");
                        }

                        if (res.candidate_detail.Admins_R == 1) {
                            $('#administrative_chk').prop('checked', true);
                            $("#administrative_div").removeClass("d-none");
                            $('#AdministrativeDepartment').val(res.candidate_detail.Admins_Dpt);
                            $('#AdministrativeEmployee').val(res.candidate_detail.A_ReportingManager);

                        } else {
                            $("#administrative_div").addClass("d-none");
                        }

                        if (res.candidate_detail.repchk != '') {
                            $("input[name=repchk][value=" + res.candidate_detail.repchk +
                                "]").prop('checked', true);
                        }

                        if (res.candidate_detail.repchk == 'RepWithoutEmp') {
                            $('#rep_without_emp_tr').removeClass('d-none');
                            $('#rep_with_emp_tr').addClass('d-none');
                        } else {
                            $('#rep_without_emp_tr').addClass('d-none');
                            $('#rep_with_emp_tr').removeClass('d-none');
                        }
                        /*  $('#CTC').val(res.candidate_detail.CTC);*/
                        $("#grsM_salary").val(res.candidate_detail.grsM_salary);
                        $("#MW").val(res.candidate_detail.MW);
                        if (res.candidate_detail.MinBasicSalary != null) {
                            $("#MinBasicSalary").val(res.candidate_detail.MinBasicSalary);
                        } else {
                            $("#MinBasicSalary").val(15050);
                        }

                        $("#PF_Wage_Limit").val(res.candidate_detail.PF_Wage_Limit);
                        if (res.candidate_detail.ServiceCondition != '') {
                            $("input[name=ServiceCond][value=" + res.candidate_detail.ServiceCondition +
                                "]").prop('checked', true);
                        }


                        if (res.candidate_detail.ServiceCondition === 'Training') {
                            $('#training_tr').removeClass('d-none');
                            $("#OrientationPeriod").val(res.candidate_detail.OrientationPeriod);
                            $("#Stipend").val(res.candidate_detail.Stipend);
                            $("#AftDesignation").val(res.candidate_detail.AFT_Designation);
                            $("#AftGrade").val(res.candidate_detail.AFT_Grade);
                        } else {
                            $('#training_tr').addClass('d-none');
                        }

                        if (res.candidate_detail.ServiceBond != '') {
                            $("input[name=ServiceBond][value=" + res.candidate_detail.ServiceBond +
                                "]").prop('checked', true);
                        }

                        if (res.candidate_detail.ServiceBond === 'Yes') {
                            $('#bond_tr').removeClass('d-none');
                            $('#ServiceBondDuration').val(res.candidate_detail.ServiceBondYears);
                            $('#ServiceBondRefund').val(res.candidate_detail.ServiceBondRefund);
                        } else {
                            $('#bond_tr').addClass('d-none');
                        }

                        if (res.candidate_detail.PreMedicalCheckUp != '') {
                            $("input[name=MedicalCheckup][value=" + res.candidate_detail
                                .PreMedicalCheckUp +
                                "]").prop('checked', true);
                        }

                        $('#SignAuth').val(res.candidate_detail.SigningAuth);
                        $('#Remark').val(res.candidate_detail.Remarks);

                    } else {
                        alert('something went wrong..!!');
                    }
                }
            });
        });

        $('#OfferLtrModal').on('hidden.bs.modal', function() {
            $('#offerletterbasicform')[0].reset();
        });

        $(document).on('change', '#AdministrativeDepartment', function() {
            var DepartmentId = $(this).val();
            $.ajax({
                type: "GET",
                url: "{{ route('getReportingManager') }}?DepartmentId=" + DepartmentId,
                success: function(res) {
                    if (res) {
                        $("#AdministrativeEmployee").empty();
                        $("#AdministrativeEmployee").append(
                            '<option value="">Select Reporting</option>');
                        $.each(res, function(key, value) {
                            $("#AdministrativeEmployee").append('<option value="' + value +
                                '">' +
                                key +
                                '</option>');
                        });
                        $('#AdministrativeEmployee').val();
                    } else {
                        $("#AdministrativeEmployee").empty();
                    }
                }
            });
        });

        $(document).on('change', '#FunctionalDepartment', function() {
            var DepartmentId = $(this).val();
            $.ajax({
                type: "GET",
                url: "{{ route('getReportingManager') }}?DepartmentId=" + DepartmentId,
                success: function(res) {
                    if (res) {
                        $("#FunctionalEmployee").empty();
                        $("#FunctionalEmployee").append(
                            '<option value="">Select Reporting</option>');
                        $.each(res, function(key, value) {
                            $("#FunctionalEmployee").append('<option value="' + value + '">' +
                                key +
                                '</option>');
                        });
                        $('#FunctionalEmployee').val();
                    } else {
                        $("#FunctionalEmployee").empty();
                    }
                }
            });
        });

        $(document).on('change', '#Of_PermState', function() {
            var state_id = $(this).val();
            $.ajax({
                type: "GET",
                url: "{{ route('getCityVillageByState') }}?state_id=" + state_id,
                success: function(res) {
                    if (res) {
                        $("#PermHQ").empty();
                        $("#PermHQ").append(
                            '<option value="">Select Headquarter</option>');
                        $.each(res, function(key, value) {
                            $("#PermHQ").append('<option value="' + value + '">' +
                                key +
                                '</option>');
                        });
                        $('#PermHQ').val();
                    } else {
                        $("#PermHQ").empty();
                    }
                }
            });
        });
        $(document).on('change', '#TempState', function() {
            var state_id = $(this).val();
            $.ajax({
                type: "GET",
                url: "{{ route('getCityVillageByState') }}?state_id=" + state_id,
                success: function(res) {
                    if (res) {
                        $("#TempHQ").empty();
                        $("#TempHQ").append(
                            '<option value="">Select Headquarter</option>');
                        $.each(res, function(key, value) {
                            $("#TempHQ").append('<option value="' + value + '">' +
                                key +
                                '</option>');
                        });
                        $('#TempHQ').val();
                    } else {
                        $("#TempHQ").empty();
                    }
                }
            });
        });
        $(document).on('change', '#TempState1', function() {
            var state_id = $(this).val();
            $.ajax({
                type: "GET",
                url: "{{ route('getCityVillageByState') }}?state_id=" + state_id,
                success: function(res) {
                    if (res) {
                        $("#TempHQ1").empty();
                        $("#TempHQ1").append(
                            '<option value="">Select Headquarter</option>');
                        $.each(res, function(key, value) {
                            $("#TempHQ1").append('<option value="' + value + '">' +
                                key +
                                '</option>');
                        });
                        $('#TempHQ1').val();
                    } else {
                        $("#TempHQ1").empty();
                    }
                }
            });
        });

        $('#offerletterbasicform').on('submit', function(e) {
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
                    // $("#loader").modal('show');
                },
                success: function(data) {
                    if (data.status == 400) {
                        //  $("#loader").modal('hide');
                        $.each(data.error, function(prefix, val) {
                            $(form).find('span.' + prefix + '_error').text(val[0]);
                        });
                    } else {
                        toastr.success(data.msg);
                        $(form)[0].reset();
                        $('#loader').modal('hide');
                        $('#OfferLtrModal').modal('hide');
                    }
                }
            });
        });

        $('#reviewForm').on('submit', function(e) {
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
                    $('#review_modal').modal('hide');
                    $("#loader").modal('show');
                },
                success: function(data) {
                    if (data.status == 400) {
                        $("#loader").modal('hide');
                        $('#review_modal').modal('show');
                        $.each(data.error, function(prefix, val) {
                            $(form).find('span.' + prefix + '_error').text(val[0]);
                        });
                    } else {
                        $(form)[0].reset();
                        $('#loader').modal('hide');
                        $('#review_modal').modal('hide');
                        toastr.success(data.msg);
                        window.location.reload();
                    }
                }
            });
        });

        $('#ref_chk_form').on('submit', function(e) {
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
                    $('#ref_modal').modal('hide');
                    $("#loader").modal('show');
                },
                success: function(data) {
                    if (data.status == 400) {
                        $("#loader").modal('hide');
                        $('#ref_modal').modal('show');
                        $.each(data.error, function(prefix, val) {
                            $(form).find('span.' + prefix + '_error').text(val[0]);
                        });
                    } else {
                        $(form)[0].reset();
                        $('#loader').modal('hide');
                        $('#ref_modal').modal('hide');
                        toastr.success(data.msg);
                        window.location.reload();
                    }
                }
            });
        });

        $(document).on('click', '#offerltrgen', function() {
            var JAId = $(this).data('id');
            sendingId = btoa(JAId);
            window.open("{{ route('offer_letter_generate') }}?jaid=" + sendingId, '_blank');
        });

        /*function OfferLetterPrint(url) {
            $("<iframe>") // create a new iframe element
                .hide() // make it invisible
                .attr("src", url) // point the iframe to the page you want to print
                .appendTo("body");
        }*/

        function PrintAppointmentLetter(url) {
            $("<iframe>") // create a new iframe element
                .hide() // make it invisible
                .attr("src", url) // point the iframe to the page you want to print
                .appendTo("body");
        }

        function PrintAppointmentLetter_OldStamp(url) {
            $("<iframe>") // create a new iframe element
                .hide() // make it invisible
                .attr("src", url) // point the iframe to the page you want to print
                .appendTo("body");
        }

        function PrintServiceAgreementLetter(url) {
            $("<iframe>") // create a new iframe element
                .hide() // make it invisible
                .attr("src", url) // point the iframe to the page you want to print
                .appendTo("body");
        }

        function PrintServiceAgreementLetter_OldStamp(url) {
            $("<iframe>") // create a new iframe element
                .hide() // make it invisible
                .attr("src", url) // point the iframe to the page you want to print
                .appendTo("body");
        }

        function PrintServiceBondLetter(url) {
            $("<iframe>") // create a new iframe element
                .hide() // make it invisible
                .attr("src", url) // point the iframe to the page you want to print
                .appendTo("body");
        }

        function PrintServiceBondLetter_OldStamp(url) {
            $("<iframe>") // create a new iframe element
                .hide() // make it invisible
                .attr("src", url) // point the iframe to the page you want to print
                .appendTo("body");
        }

        function PrintConfidentialityAgreementLetter(url) {
            $("<iframe>") // create a new iframe element
                .hide() // make it invisible
                .attr("src", url) // point the iframe to the page you want to print
                .appendTo("body");
        }

        function PrintConfidentialityAgreementLetter_OldStamp(url) {
            $("<iframe>") // create a new iframe element
                .hide() // make it invisible
                .attr("src", url) // point the iframe to the page you want to print
                .appendTo("body");
        }

        function getOfHistory(JAId) {
            var JAId = JAId;
            var route = "{{ route('offer_ltr_history') }}";
            $.ajax({
                type: "GET",
                url: "{{ route('offerLtrHistory') }}?jaid=" + JAId,
                success: function(res) {
                    var x = '';
                    $.each(res.data, function(key, value) {
                        x += '<tr>';
                        x += '<td>' + value.OfDate + '</td>';
                        x += '<td>' + value.LtrNo + '</td>';
                        x += '<td><a href="' + route + '?LtrId=' + value.LtrId +
                            '" target="_blank">View Offer</a></td>';
                        x += '<td>' + value.RevisionRemark + '</td>';
                    });
                    $('#offerHistory').html(x);
                }
            });
        }

        function joinDateEnbl() {
            $('#dateofJoin').prop('readonly', false);
            $('#joindtenable').hide(500);
            $('#JoinSave').show(500);
            $('#JoinCanc').show(500);
        }

        function saveJoinDate() {
            var joinDate = $('#dateofJoin').val();
            var JAId = $('#JAId').val();
            $.ajax({
                type: "POST",
                url: "{{ route('saveJoinDate') }}?JAId=" + JAId + "&JoinDate=" + joinDate,
                success: function(res) {
                    if (res.status == 200) {
                        $('#joindtenable').show(500);
                        $('#JoinSave').hide(500);
                        $('#JoinCanc').hide(500);
                        $('#dateofJoin').prop('readonly', true);
                        toastr.success(res.msg);
                    } else {
                        toastr.error(res.msg);
                    }
                }
            });
        }

        function empCodeEnable() {
            $('#empCode').prop('readonly', false);
            $('#empCodeEnable').hide(500);
            $('#EmpCodeSave').show(500);
            $('#empCancle').show(500);
        }

        function saveEmpCode() {
            var EmpCode = $('#empCode').val();
            var JAId = $('#JAId').val();
            $.ajax({
                type: "POST",
                url: "{{ route('saveEmpCode') }}?JAId=" + JAId + "&EmpCode=" + EmpCode,
                success: function(res) {
                    if (res.status == 200) {
                        $('#empCodeEnable').show(500);
                        $('#EmpCodeSave').hide(500);
                        $('#empCancle').hide(500);
                        $('#empCode').prop('readonly', true);
                        toastr.success(res.msg);
                    } else {
                        toastr.error(res.msg);
                        window.location.reload();
                    }
                }
            });
        }

        function off_date_enable() {
            $('#off_date').prop('readonly', false);
            $('#off_date_enable').hide(500);
            $('#save_off_date').show(500);
            $('#off_date_can').show(500);
        }

        function a_date_enable() {
            $('#a_date').prop('readonly', false);
            $('#a_date_enable').hide(500);
            $('#save_a_date').show(500);
            $('#a_date_can').show(500);
        }

        function agr_date_enable() {
            $('#agr_date').prop('readonly', false);
            $('#agr_date_enable').hide(500);
            $('#save_agr_date').show(500);
            $('#agr_date_can').show(500);
        }

        function b_date_enable() {
            $('#b_date').prop('readonly', false);
            $('#b_date_enable').hide(500);
            $('#save_b_date').show(500);
            $('#b_date_can').show(500);
        }

        function conf_date_enable() {
            $('#conf_date').prop('readonly', false);
            $('#conf_date_enable').hide(500);
            $('#save_conf_date').show(500);
            $('#conf_date_can').show(500);
        }

        function save_off_date() {
            var LtrDate = $('#off_date').val();
            var JAId = $('#JAId').val();
            $.ajax({
                type: "POST",
                url: "{{ route('changeOffLtrDate') }}?JAId=" + JAId + "&LtrDate=" + LtrDate,
                success: function(res) {
                    if (res.status == 200) {
                        $('#off_date_enable').show(500);
                        $('#save_off_date').hide(500);
                        $('#off_date_can').hide(500);
                        $('#off_date').prop('readonly', true);
                        toastr.success(res.msg);
                    } else {
                        toastr.error(res.msg);
                        window.location.reload();
                    }
                }
            });
        }

        function save_a_date() {
            var A_Date = $('#a_date').val();
            var JAId = $('#JAId').val();
            $.ajax({
                type: "POST",
                url: "{{ route('changeA_Date') }}?JAId=" + JAId + "&A_Date=" + A_Date,
                success: function(res) {
                    if (res.status == 200) {
                        $('#a_date_enable').show(500);
                        $('#save_a_date').hide(500);
                        $('#a_date_can').hide(500);
                        $('#a_date').prop('readonly', true);
                        toastr.success(res.msg);
                    } else {
                        toastr.error(res.msg);
                        window.location.reload();
                    }
                }
            });
        }

        function save_agr_date() {
            var Agr_Date = $('#agr_date').val();
            var JAId = $('#JAId').val();
            $.ajax({
                type: "POST",
                url: "{{ route('changeAgr_Date') }}?JAId=" + JAId + "&Agr_Date=" + Agr_Date,
                success: function(res) {
                    if (res.status == 200) {
                        $('#agr_date_enable').show(500);
                        $('#save_agr_date').hide(500);
                        $('#agr_date_can').hide(500);
                        $('#agr_date').prop('readonly', true);
                        toastr.success(res.msg);
                    } else {
                        toastr.error(res.msg);
                        window.location.reload();
                    }
                }
            });
        }

        function save_b_date() {
            var B_Date = $('#b_date').val();
            var JAId = $('#JAId').val();
            $.ajax({
                type: "POST",
                url: "{{ route('changeB_Date') }}?JAId=" + JAId + "&B_Date=" + B_Date,
                success: function(res) {
                    if (res.status == 200) {
                        $('#b_date_enable').show(500);
                        $('#save_b_date').hide(500);
                        $('#b_date_can').hide(500);
                        $('#b_date').prop('readonly', true);
                        toastr.success(res.msg);
                    } else {
                        toastr.error(res.msg);
                        window.location.reload();
                    }
                }
            });
        }

        function save_conf_date() {
            var ConfLtrDate = $('#conf_date').val();
            var JAId = $('#JAId').val();
            $.ajax({
                type: "POST",
                url: "{{ route('changeConf_Date') }}?JAId=" + JAId + "&ConfLtrDate=" + ConfLtrDate,
                success: function(res) {
                    if (res.status == 200) {
                        $('#conf_date_enable').show(500);
                        $('#save_conf_date').hide(500);
                        $('#conf_date_can').hide(500);
                        $('#conf_date').prop('readonly', true);
                        toastr.success(res.msg);
                    } else {
                        toastr.error(res.msg);
                        window.location.reload();
                    }
                }
            });
        }

        function copylink(id) {
            var copyText = document.getElementById("link" + id);
            copyText.select();
            copyText.setSelectionRange(0, 99999)
            document.execCommand("copy");
            alert("Copied Link: " + copyText.value);
        }

        function copyOfLink() {
            var copyText = document.getElementById("oflink");
            copyText.select();
            copyText.setSelectionRange(0, 99999)
            document.execCommand("copy");
            alert("Copied Link: " + copyText.value);
        }

        function copyJFrmLink() {
            var copyText = document.getElementById("jflink");
            copyText.select();
            copyText.setSelectionRange(0, 99999)
            document.execCommand("copy");
            alert("Copied Link: " + copyText.value);
        }

        function copyVFrmLink() {
            var copyText = document.getElementById("vflink");
            copyText.select();
            copyText.setSelectionRange(0, 99999)
            document.execCommand("copy");
            alert("Copied Link: " + copyText.value);
        }

        function copyJIntFrmLink() {
            var copyText = document.getElementById("interviewlink");
            copyText.select();
            copyText.setSelectionRange(0, 99999)
            document.execCommand("copy");
            alert("Copied Link: " + copyText.value);
        }

        function copyFiroBlink() {
            var copyText = document.getElementById("firoblink");
            copyText.select();
            copyText.setSelectionRange(0, 99999)
            document.execCommand("copy");
            alert("Copied Link: " + copyText.value);
        }

        function sendOfferLtr(JAId) {
            var JAId = JAId;
            if (confirm("Are you sure you want to send Offer Letter?")) {
                $.ajax({
                    url: "{{ route('SendOfferLtr') }}",
                    type: "POST",
                    data: {
                        "JAId": JAId
                    },
                    beforeSend: function() {
                        $('#loader').modal('show');
                    },
                    success: function(data) {
                        if (data.status == 400) {
                            toastr.error(data.msg);
                        } else {
                            $('#loader').modal('hide');
                            toastr.success(data.msg);
                            window.location.reload();
                        }
                    }
                });
            }

        }

        function sendJoiningForm(JAId) {
            var JAId = JAId;
            if (confirm("Are you sure you want to send Joining Form?")) {
                $.ajax({
                    url: "{{ route('SendJoiningForm') }}",
                    type: "POST",
                    data: {
                        "JAId": JAId
                    },
                    beforeSend: function() {
                        $('#loader').modal('show');
                    },
                    success: function(data) {
                        if (data.status == 400) {
                            toastr.error(data.msg);
                        } else {
                            $('#loader').modal('hide');
                            toastr.success(data.msg);
                            window.location.reload();
                        }
                    }
                });
            }

        }

        function sendVehicleForm(JCId) {
            var JCId = JCId;
            if (confirm("Are you sure you want to send Vehicle Information Form?")) {
                $.ajax({
                    url: "{{ route('SendVehicleForm') }}",
                    type: "POST",
                    data: {
                        "JCId": JCId
                    },
                    beforeSend: function() {
                        $('#loader').modal('show');
                    },
                    success: function(data) {
                        if (data.status == 400) {
                            toastr.error(data.msg);
                        } else {
                            $('#loader').modal('hide');
                            toastr.success(data.msg);
                            window.location.reload();
                        }
                    }
                });
            }

        }

        function offerReopen(JAId) {
            var JAId = JAId;
            var url = '<?= route('offerReopen') ?>';
            swal.fire({
                title: 'Are you sure?',
                html: 'You want to <b>Open</b> this Offer',
                showCancelButton: true,
                showCloseButton: true,
                cancelButtonText: 'Cancel',
                confirmButtonText: 'Yes',
                cancelButtonColor: '#d33',
                confirmButtonColor: '#556ee6',
                width: 400,
                allowOutsideClick: false

            }).then(function(result) {
                if (result.value) {
                    $.post(url, {
                        JAId: JAId
                    }, function(data) {
                        if (data.status == 200) {
                            toastr.success(data.msg);
                            window.location.reload();
                        } else {
                            toastr.error(data.msg);
                        }
                    }, 'json');
                }
            });
        }

        function viewReview(JAId) {
            var url = '<?= route('viewReview') ?>';
            $.get(url, {
                JAId: JAId
            }, function(data) {
                if (data.status == 200) {
                    $('#view_review').modal('show');
                    let x = '';
                    let i = 1;
                    let reason = '';
                    $.each(data.data, function(key, value) {
                        if (value.RejReason == null) {
                            reason = '-';
                        } else {
                            reason = value.RejReason;
                        }
                        x += '<tr>';
                        x += '<td>' + i + '</td>';
                        x += '<td>' + value.OfferLetterNo + '</td>';
                        x += '<td>' + value.full_name + '</td>';
                        x += '<td>' + value.Status + '</td>';
                        x += '<td>' + reason + '</td>';
                        x += '</tr>';
                        i++;
                    });
                    $('#viewReviewData').html(x);
                } else {
                    toastr.error(data.msg);
                }
            }, 'json');
        }


        for (i = 1; i <= 10; i++) {

            $(document).on('change', '#Read' + i, function() {

                if ($(this).prop('checked')) {
                    $(this).val('1');
                } else {
                    $(this).val('0');
                }
            });

            $(document).on('change', '#Write' + i, function() {
                if ($(this).prop('checked')) {
                    $(this).val('1');
                } else {
                    $(this).val('0');
                }
            });


            $(document).on('change', '#Speak' + i, function() {
                if ($(this).prop('checked')) {
                    $(this).val('1');
                } else {
                    $(this).val('0');
                }
            });


        }

        $(document).on('click', '#save_language', function() {
            var language_array = [];
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

            var url = '<?= route('Candidate_Language_Save') ?>';
            $.post(url, {
                language_array: language_array,
                JCId: $('#JCId').val()
            }, function(data) {
                if (data.status == 200) {
                    toastr.success(data.msg);
                    window.location.reload();
                } else {
                    toastr.error(data.msg);
                }
            }, 'json');

        });


        function getOtherInstitute(num) {
            var Collage = $('#Collage' + num).val();
            console.log(Collage);
            if (Collage == '637') {
                $('#OtherInstitute' + num).removeClass('d-none');
            } else {
                $('#OtherInstitute' + num).addClass('d-none');
            }
        }

        function appointmentGen(JAId) {
            var JAId = btoa(JAId);
            $.ajax({
                url: "{{ route('appointmentGen') }}",
                type: "POST",
                data: {
                    "JAId": JAId
                },

                success: function(data) {
                    if (data.status == 400) {
                        toastr.error(data.msg);
                    } else {
                        window.open('{{ route('appointment_letter') }}?jaid=' + JAId, '_blank');
                    }
                }
            });
        }

        function ServiceAgrGen(JAId) {
            var JAId = btoa(JAId);
            window.open('{{ route('service_agreement') }}?jaid=' + JAId, '_blank');
        }

        function ServiceBondGen(JAId) {
            var JAId = btoa(JAId);
            window.open('{{ route('service_bond') }}?jaid=' + JAId, '_blank');
        }

        function ConfidentialityAgrGen(JAId) {
            var JAId = btoa(JAId);
            window.open('{{ route('conf_agreement') }}?jaid=' + JAId, '_blank');
        }

        $(document).on('change', '#GPRS', function() {
            if ($(this).prop('checked')) {
                $("#GPRS").val('1');
            } else {
                $("#GPRS").val('0');
            }
        });
    </script>

    <script>
        /*
         * This is the plugin
         */
        (function(a) {
            a.createModal = function(b) {
                defaults = {
                    title: "",
                    message: "Your Message Goes Here!",
                    closeButton: true,
                    scrollable: false
                };
                var b = a.extend({}, defaults, b);
                var c = (b.scrollable === true) ? 'style="max-height: 420px;overflow-y: auto;"' : "";
                html =
                    '<div class="modal fade custom-modal" id="myModal" data-bs-backdrop="static" data-bs-keyboard="false">';
                html += '<div class="modal-dialog">';
                html += '<div class="modal-content">';
                html +=
                    '<div class="modal-header"><button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button></div>';
                html += '<div class="modal-body" ' + c + ">";
                html += b.message;
                html += "</div>";
                html += '<div class="modal-footer">';

                html += "</div>";
                html += "</div>";
                html += "</div>";
                html += "</div>";
                a("body").prepend(html);
                a("#myModal").modal('show').on("hidden.bs.modal", function() {
                    a(this).remove()
                })
            }
        })(jQuery);

        /*
         * Here is how you use it
         */
        $(function() {
            $('.view-pdf').on('click', function() {
                var pdf_link = $(this).attr('href');

                var iframe = '<div class="iframe-container"><iframe src="' + pdf_link + '"></iframe></div>'
                $.createModal({
                    title: 'My Title',
                    message: iframe,
                    closeButton: true,
                    scrollable: false
                });
                return false;
            });
        })
    </script>

    <script>
        $(document).on('click', '#RelievingLtrUpload', function() {
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
                success: function(data) {
                    if (data.status == 400) {
                        toastr.error(data.msg);
                    } else {
                        toastr.success(data.msg);
                        window.location.reload();

                    }
                },
                error: function(data) {
                    var errors = data.responseJSON;
                    var errorsHtml = '';
                    $.each(errors.errors, function(key, value) {
                        errorsHtml += value[0] + '<br>';
                    });
                    toastr.error(errorsHtml)
                }
            });
        });

        $(document).on('click', '#SalarySlipUpload', function() {
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
                success: function(data) {
                    if (data.status == 200) {
                        toastr.success(data.msg);
                        window.location.reload();
                    } else {
                        toastr.error(data.msg);
                    }

                },
                error: function(data) {
                    var errors = data.responseJSON;
                    var errorsHtml = '';
                    $.each(errors.errors, function(key, value) {
                        errorsHtml += value[0] + '<br>';
                    });
                    toastr.error(errorsHtml)
                }
            });
        });

        $(document).on('click', '#AppraisalLtrUpload', function() {
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
                success: function(data) {
                    if (data.status == 400) {
                        toastr.error(data.msg);
                    } else {
                        toastr.success(data.msg);
                        window.location.reload();

                    }
                },
                error: function(data) {
                    var errors = data.responseJSON;
                    var errorsHtml = '';
                    $.each(errors.errors, function(key, value) {
                        errorsHtml += value[0] + '<br>';
                    });
                    toastr.error(errorsHtml)
                }
            });
        });

        $(document).on('click', '#VaccinationCertUpload', function() {
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
                success: function(data) {
                    if (data.status == 400) {
                        toastr.error(data.msg);
                    } else {
                        toastr.success(data.msg);
                        window.location.reload();

                    }
                },
                error: function(data) {
                    var errors = data.responseJSON;
                    var errorsHtml = '';
                    $.each(errors.errors, function(key, value) {
                        errorsHtml += value[0] + '<br>';
                    });
                    toastr.error(errorsHtml)
                }
            });
        });

        $(document).on('click', '#AadhaarUpload', function() {
            var JCId = $('#JCId').val();
            var url = '<?= route('AadhaarUpload') ?>';
            var AadhaarCard = $('#AadhaarCard')[0].files;
            var formData = new FormData();
            formData.append('JCId', JCId);
            formData.append('AadhaarCard', AadhaarCard[0]);
            $.ajax({
                url: url,
                method: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                dataType: 'json',
                success: function(data) {
                    if (data.status == 400) {
                        toastr.error(data.msg);

                    } else {

                        toastr.success(data.msg);
                        window.location.reload();

                    }
                },
                error: function(data) {
                    var errors = data.responseJSON;
                    var errorsHtml = '';
                    $.each(errors.errors, function(key, value) {
                        errorsHtml += value[0] + '<br>';
                    });
                    toastr.error(errorsHtml);

                }
            });
        });

        $(document).on('click', '#PANCardUpload', function() {
            var JCId = $('#JCId').val();
            var url = '<?= route('PanCardUpload') ?>';
            var PANCard = $('#PANCard')[0].files;
            var PanCardNumber = $('#PanCardNumber').val();
            var formData = new FormData();
            formData.append('JCId', JCId);
            formData.append('PANCard', PANCard[0]);
            formData.append('PanCardNumber', PanCardNumber);
            $.ajax({
                url: url,
                method: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                dataType: 'json',
                success: function(data) {
                    if (data.status == 400) {
                        toastr.error(data.msg);

                    } else {

                        toastr.success(data.msg);
                        window.location.reload();

                    }
                },
                error: function(data) {
                    var errors = data.responseJSON;
                    var errorsHtml = '';
                    $.each(errors.errors, function(key, value) {
                        errorsHtml += value[0] + '<br>';
                    });
                    toastr.error(errorsHtml);

                }
            });
        });

        $(document).on('click', '#PassportUpload', function() {
            var JCId = $('#JCId').val();
            var url = '<?= route('PassportUpload') ?>';
            var Passport = $('#Passport')[0].files;
            var PassportNumber = $('#PassportNumber').val();
            var formData = new FormData();
            formData.append('JCId', JCId);
            formData.append('Passport', Passport[0]);
            formData.append('PassportNumber', PassportNumber);
            $.ajax({
                url: url,
                method: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                dataType: 'json',
                success: function(data) {
                    if (data.status == 200) {

                        toastr.success(data.msg);
                        window.location.reload();
                    } else {
                        toastr.error(data.msg);

                    }

                },
                error: function(data) {
                    var errors = data.responseJSON;
                    var errorsHtml = '';
                    $.each(errors.errors, function(key, value) {
                        errorsHtml += value[0] + '<br>';
                    });
                    toastr.error(errorsHtml);

                }
            });
        });

        $(document).on('click', '#DLCardUpload', function() {
            var JCId = $('#JCId').val();
            var url = '<?= route('DlUpload') ?>';
            var DLCard = $('#DLCard')[0].files;
            var formData = new FormData();
            formData.append('JCId', JCId);
            formData.append('DLCard', DLCard[0]);
            $.ajax({
                url: url,
                method: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                dataType: 'json',
                success: function(data) {
                    if (data.status == 400) {
                        toastr.error(data.msg);

                    } else {

                        toastr.success(data.msg);
                        window.location.reload();

                    }
                },
                error: function(data) {
                    var errors = data.responseJSON;
                    var errorsHtml = '';
                    $.each(errors.errors, function(key, value) {
                        errorsHtml += value[0] + '<br>';
                    });
                    toastr.error(errorsHtml);

                }
            });
        });

        $(document).on('click', '#PFForm2Upload', function() {
            var JCId = $('#JCId').val();
            var url = '<?= route('PF_Form2Upload') ?>';
            var PFForm2 = $('#PFForm2')[0].files;
            var formData = new FormData();
            formData.append('JCId', JCId);
            formData.append('PFForm2', PFForm2[0]);
            $.ajax({
                url: url,
                method: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                dataType: 'json',
                success: function(data) {
                    if (data.status == 400) {
                        toastr.error(data.msg);

                    } else {

                        toastr.success(data.msg);
                        window.location.reload();

                    }
                },
                error: function(data) {
                    var errors = data.responseJSON;
                    var errorsHtml = '';
                    $.each(errors.errors, function(key, value) {
                        errorsHtml += value[0] + '<br>';
                    });
                    toastr.error(errorsHtml);

                }
            });
        });

        $(document).on('click', '#PFForm11Upload', function() {
            var JCId = $('#JCId').val();
            var url = '<?= route('PF_Form11Upload') ?>';
            var PF_Form11 = $('#PF_Form11')[0].files;
            var formData = new FormData();
            formData.append('JCId', JCId);
            formData.append('PF_Form11', PF_Form11[0]);
            $.ajax({
                url: url,
                method: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                dataType: 'json',
                success: function(data) {
                    if (data.status == 400) {
                        toastr.error(data.msg);

                    } else {

                        toastr.success(data.msg);
                        window.location.reload();

                    }
                },
                error: function(data) {
                    var errors = data.responseJSON;
                    var errorsHtml = '';
                    $.each(errors.errors, function(key, value) {
                        errorsHtml += value[0] + '<br>';
                    });
                    toastr.error(errorsHtml);

                }
            });
        });

        $(document).on('click', '#GratuityUpload', function() {
            var JCId = $('#JCId').val();
            var url = '<?= route('GratuityUpload') ?>';
            var GratuityForm = $('#GratuityForm')[0].files;
            var formData = new FormData();
            formData.append('JCId', JCId);
            formData.append('GratuityForm', GratuityForm[0]);
            $.ajax({
                url: url,
                method: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                dataType: 'json',
                success: function(data) {
                    if (data.status == 400) {
                        toastr.error(data.msg);

                    } else {

                        toastr.success(data.msg);
                        window.location.reload();

                    }
                },
                error: function(data) {
                    var errors = data.responseJSON;
                    var errorsHtml = '';
                    $.each(errors.errors, function(key, value) {
                        errorsHtml += value[0] + '<br>';
                    });
                    toastr.error(errorsHtml);

                }
            });
        });

        $(document).on('click', '#ESICFormUpload', function() {
            var JCId = $('#JCId').val();
            var url = '<?= route('ESICUpload') ?>';
            var ESICForm = $('#ESICForm')[0].files;
            var formData = new FormData();
            formData.append('JCId', JCId);
            formData.append('ESICForm', ESICForm[0]);
            $.ajax({
                url: url,
                method: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                dataType: 'json',
                success: function(data) {
                    if (data.status == 400) {
                        toastr.error(data.msg);

                    } else {

                        toastr.success(data.msg);
                        window.location.reload();

                    }
                },
                error: function(data) {
                    var errors = data.responseJSON;
                    var errorsHtml = '';
                    $.each(errors.errors, function(key, value) {
                        errorsHtml += value[0] + '<br>';
                    });
                    toastr.error(errorsHtml);

                }
            });
        });

        $(document).on('click', '#ESIC_FamilyUpload', function() {
            var JCId = $('#JCId').val();
            var url = '<?= route('FamilyUpload') ?>';
            var ESIC_Family = $('#ESIC_Family')[0].files;
            var formData = new FormData();
            formData.append('JCId', JCId);
            formData.append('ESIC_Family', ESIC_Family[0]);
            $.ajax({
                url: url,
                method: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                dataType: 'json',
                success: function(data) {
                    if (data.status == 400) {
                        toastr.error(data.msg);

                    } else {

                        toastr.success(data.msg);
                        window.location.reload();

                    }
                },
                error: function(data) {
                    var errors = data.responseJSON;
                    var errorsHtml = '';
                    $.each(errors.errors, function(key, value) {
                        errorsHtml += value[0] + '<br>';
                    });
                    toastr.error(errorsHtml);

                }
            });
        });

        $(document).on('click', '#HealthUpload', function() {
            var JCId = $('#JCId').val();
            var url = '<?= route('HealthUpload') ?>';
            var Health = $('#Health')[0].files;
            var formData = new FormData();
            formData.append('JCId', JCId);
            formData.append('Health', Health[0]);
            $.ajax({
                url: url,
                method: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                dataType: 'json',
                success: function(data) {
                    if (data.status == 400) {
                        toastr.error(data.msg);

                    } else {

                        toastr.success(data.msg);
                        window.location.reload();

                    }
                },
                error: function(data) {
                    var errors = data.responseJSON;
                    var errorsHtml = '';
                    $.each(errors.errors, function(key, value) {
                        errorsHtml += value[0] + '<br>';
                    });
                    toastr.error(errorsHtml);

                }
            });
        });

        $(document).on('click', '#EthicalUpload', function() {
            var JCId = $('#JCId').val();
            var url = '<?= route('EthicalUpload') ?>';
            var Ethical = $('#Ethical')[0].files;
            var formData = new FormData();
            formData.append('JCId', JCId);
            formData.append('Ethical', Ethical[0]);
            $.ajax({
                url: url,
                method: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                dataType: 'json',
                success: function(data) {
                    if (data.status == 400) {
                        toastr.error(data.msg);

                    } else {

                        toastr.success(data.msg);
                        window.location.reload();

                    }
                },
                error: function(data) {
                    var errors = data.responseJSON;
                    var errorsHtml = '';
                    $.each(errors.errors, function(key, value) {
                        errorsHtml += value[0] + '<br>';
                    });
                    toastr.error(errorsHtml);

                }
            });
        });

        $(document).on('click', '#BloodGroupUpload', function() {
            var JCId = $('#JCId').val();
            var url = '<?= route('BloodGroupUpload') ?>';
            var BloodGroup = $('#BloodGroup')[0].files;
            var formData = new FormData();
            formData.append('JCId', JCId);
            formData.append('BloodGroup', BloodGroup[0]);
            $.ajax({
                url: url,
                method: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                dataType: 'json',
                success: function(data) {
                    if (data.status == 400) {
                        toastr.error(data.msg);

                    } else {

                        toastr.success(data.msg);
                        window.location.reload();

                    }
                },
                error: function(data) {
                    var errors = data.responseJSON;
                    var errorsHtml = '';
                    $.each(errors.errors, function(key, value) {
                        errorsHtml += value[0] + '<br>';
                    });
                    toastr.error(errorsHtml);

                }
            });
        });

        $(document).on('click', '#BankPassBookUpload', function() {
            var JCId = $('#JCId').val();
            var url = '<?= route('BankUpload') ?>';
            var BankPassBook = $('#BankPassBook')[0].files;
            var BankName = $('#BankName').val();
            var AccNumber = $('#AccNumber').val();
            var IFSC = $('#IFSC').val();
            var BranchName = $('#BranchName').val();
            var formData = new FormData();
            formData.append('JCId', JCId);
            formData.append('BankPassBook', BankPassBook[0]);
            formData.append('BankName', BankName);
            formData.append('AccNumber', AccNumber);
            formData.append('IFSC', IFSC);
            formData.append('BranchName', BranchName);
            if (BankName == '') {
                toastr.error('Please Enter Bank Name');
                return false;
            }
            if (BranchName == '') {
                toastr.error('Please Enter Branch Name');
                return false;
            }
            if (AccNumber == '') {
                toastr.error('Please Enter Account Number');
                return false;
            }
            if (IFSC == '') {
                toastr.error('Please Enter IFSC Code');
                return false;
            }

            $.ajax({
                url: url,
                method: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                dataType: 'json',
                success: function(data) {
                    if (data.status == 400) {
                        toastr.error(data.msg);

                    } else {

                        toastr.success(data.msg);
                        window.location.reload();

                    }
                },
                error: function(data) {
                    var errors = data.responseJSON;
                    var errorsHtml = '';
                    $.each(errors.errors, function(key, value) {
                        errorsHtml += value[0] + '<br>';
                    });
                    toastr.error(errorsHtml);

                }
            });
        });

        $(document).on('click', '#TestPaperUpload', function() {
            var JCId = $('#JCId').val();
            var url = '<?= route('TestPaperUpload') ?>';
            var Test_Paper = $('#TestPaper')[0].files;
            var formData = new FormData();
            formData.append('JCId', JCId);
            formData.append('Test_Paper', Test_Paper[0]);
            $.ajax({
                url: url,
                method: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                dataType: 'json',
                success: function(data) {
                    if (data.status == 400) {
                        toastr.error(data.msg);

                    } else {

                        toastr.success(data.msg);
                        window.location.reload();

                    }
                },
                error: function(data) {
                    var errors = data.responseJSON;
                    var errorsHtml = '';
                    $.each(errors.errors, function(key, value) {
                        errorsHtml += value[0] + '<br>';
                    });
                    toastr.error(errorsHtml);

                }
            });
        });

        $(document).on('click', '#IntervAssessmentUpload', function() {
            var JCId = $('#JCId').val();
            var url = '<?= route('IntervAssessmentUpload') ?>';
            var IntervAssessment = $('#IntervAssessment')[0].files;
            var formData = new FormData();
            formData.append('JCId', JCId);
            formData.append('IntervAssessment', IntervAssessment[0]);
            $.ajax({
                url: url,
                method: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                dataType: 'json',
                success: function(data) {
                    if (data.status == 400) {
                        toastr.error(data.msg);

                    } else {

                        toastr.success(data.msg);
                        window.location.reload();

                    }
                },
                error: function(data) {
                    var errors = data.responseJSON;
                    var errorsHtml = '';
                    $.each(errors.errors, function(key, value) {
                        errorsHtml += value[0] + '<br>';
                    });
                    toastr.error(errorsHtml);

                }
            });
        });

        function VerificationEnable() {
            $('#Verification').prop('disabled', false);
            $('#VerificationEnable').hide(500);
            $('#SaveVerification').show(500);
            $('#verificationCancle').show(500);
        }

        function TwoWheelRCEnable() {
            $('#two_wheel_rc').prop('disabled', false);
            $('#TwoWheelRCEnable').hide(500);
            $('#SaveTwoWheelRC').show(500);
            $('#TwoWheelRCCancle').show(500);
        }

        function FourWheelRCEnable() {
            $('#four_wheel_rc').prop('disabled', false);
            $('#FourWheelRCEnable').hide(500);
            $('#SaveFourWheelRC').show(500);
            $('#FourWheelRCCancle').show(500);
        }

        $(document).on('click', '#SaveVerification', function() {
            var JAId = $('#JAId').val();
            var Verification = $('#Verification').val();
            $.ajax({
                url: '<?= route('VerificationSave') ?>',
                method: 'POST',
                data: {
                    JAId: JAId,
                    Verification: Verification
                },
                dataType: 'json',
                success: function(data) {
                    if (data.status == 400) {
                        toastr.error(data.msg);
                    } else {
                        toastr.success(data.msg);
                        window.location.reload();

                    }
                },

            });
        });

        $(document).on('click', '#SaveTwoWheelRC', function() {
            var JAId = $('#JAId').val();
            var two_wheel_rc = $('#two_wheel_rc').val();
            var two_wheel_flat_rate = '';
            if (two_wheel_rc == 'N') {
                // two_wheel_flat_rate = prompt("Please Enter Flat Rate for Two Wheeler");
                // if (two_wheel_flat_rate == null || two_wheel_flat_rate == '') {
                //     toastr.error('Please Enter Flat Rate for Two Wheeler');
                // } else {
                $.ajax({
                    url: '<?= route('TwoWheelRCSave') ?>',
                    method: 'POST',
                    data: {
                        JAId: JAId,
                        two_wheel_rc: two_wheel_rc,
                        two_wheel_flat_rate: 0
                    },
                    dataType: 'json',
                    success: function(data) {
                        if (data.status == 400) {
                            toastr.error(data.msg);
                        } else {
                            toastr.success(data.msg);
                            window.location.reload();

                        }
                    },

                });
                //}
            } else {
                $.ajax({
                    url: '<?= route('TwoWheelRCSave') ?>',
                    method: 'POST',
                    data: {
                        JAId: JAId,
                        two_wheel_rc: two_wheel_rc,
                        two_wheel_flat_rate: ''
                    },
                    dataType: 'json',
                    success: function(data) {
                        if (data.status == 400) {
                            toastr.error(data.msg);
                        } else {
                            toastr.success(data.msg);
                            window.location.reload();

                        }
                    },

                });
            }
        });

        $(document).on('click', '#SaveFourWheelRC', function() {
            var JAId = $('#JAId').val();
            var four_wheel_rc = $('#four_wheel_rc').val();
            var four_wheel_flat_rate = '';
            if (four_wheel_rc == 'N') {
                // four_wheel_flat_rate = prompt("Please Enter Flat Rate for Four Wheeler");
                // if (four_wheel_flat_rate == null || four_wheel_flat_rate == '') {
                //     toastr.error('Please Enter Flat Rate for Four Wheeler');
                // } else {
                $.ajax({
                    url: '<?= route('FourWheelRCSave') ?>',
                    method: 'POST',
                    data: {
                        JAId: JAId,
                        four_wheel_rc: four_wheel_rc,
                        four_wheel_flat_rate: 0
                    },
                    dataType: 'json',
                    success: function(data) {
                        if (data.status == 400) {
                            toastr.error(data.msg);
                        } else {
                            toastr.success(data.msg);
                            window.location.reload();

                        }
                    },

                });
                // }
            } else {
                $.ajax({
                    url: '<?= route('FourWheelRCSave') ?>',
                    method: 'POST',
                    data: {
                        JAId: JAId,
                        four_wheel_rc: four_wheel_rc,
                        four_wheel_flat_rate: ''
                    },
                    dataType: 'json',
                    success: function(data) {
                        if (data.status == 400) {
                            toastr.error(data.msg);
                        } else {
                            toastr.success(data.msg);
                            window.location.reload();

                        }
                    },

                });
            }
        });

        function JoinedEnbl() {
            $('#Joined').prop('disabled', false);
            $('#JoinedEnbl').hide(500);
            $('#SaveJoined').show(500);
            $('#JoinedCancle').show(500);
        }

        function ClosureEnbl() {
            $('#Hr_Closure').prop('disabled', false);
            $('#ClosureEnbl').hide(500);
            $('#SaveClosure').show(500);
            $('#ClosureCancle').show(500);
        }

        $(document).on('click', '#SaveJoined', function() {
            var JAId = $('#JAId').val();
            var Joined = $('#Joined').val();
            var RemarkHr = '';
            if (Joined == 'No') {
                RemarkHr = prompt("Please Enter Candidate Not Joining Remark");
                if (RemarkHr == null || RemarkHr == '') {
                    toastr.error('Please Enter Remark');
                } else {
                    $.ajax({
                        url: '<?= route('JoinedSave') ?>',
                        method: 'POST',
                        data: {
                            JAId: JAId,
                            Joined: Joined,
                            RemarkHr: RemarkHr
                        },
                        dataType: 'json',
                        success: function(data) {
                            if (data.status == 400) {
                                toastr.error(data.msg);
                            } else {
                                toastr.success(data.msg);
                                window.location.reload();

                            }
                        },

                    });
                }
            } else {
                $.ajax({
                    url: '<?= route('JoinedSave') ?>',
                    method: 'POST',
                    data: {
                        JAId: JAId,
                        Joined: Joined,
                        RemarkHr: ''
                    },
                    dataType: 'json',
                    success: function(data) {
                        if (data.status == 400) {
                            toastr.error(data.msg);
                        } else {
                            toastr.success(data.msg);
                            window.location.reload();

                        }
                    },

                });
            }
        });

        $(document).on('click', '#SaveClosure', function() {
            var JAId = $('#JAId').val();
            var Hr_Closure = $('#Hr_Closure').val();
            var RemarkHr = '';
            if (Hr_Closure == 'Yes') {
                RemarkHr = prompt("Please Enter Candidate Closure Remark");
                if (RemarkHr == null || RemarkHr == '') {
                    toastr.error('Please Enter Remark');
                } else {
                    $.ajax({
                        url: '<?= route('CandidateClosure') ?>',
                        method: 'POST',
                        data: {
                            JAId: JAId,
                            Hr_Closure: Hr_Closure,
                            RemarkHr: RemarkHr
                        },
                        dataType: 'json',
                        success: function(data) {
                            if (data.status == 400) {
                                toastr.error(data.msg);
                            } else {
                                toastr.success(data.msg);
                                window.location.reload();

                            }
                        },

                    });
                }
            } else {
                $.ajax({
                    url: '<?= route('CandidateClosure') ?>',
                    method: 'POST',
                    data: {
                        JAId: JAId,
                        Hr_Closure: Hr_Closure,
                        RemarkHr: ''
                    },
                    dataType: 'json',
                    success: function(data) {
                        if (data.status == 400) {
                            toastr.error(data.msg);
                        } else {
                            toastr.success(data.msg);
                            window.location.reload();

                        }
                    },

                });
            }
        });

        function PosEnbl() {
            $('#PositionCode').prop('disabled', false);
            $('#PosEnbl').hide(500);
            $('#PositionCodeSave').show(500);
            $('#posCancle').show(500);
        }

        $(document).on('click', '#PositionCodeSave', function() {
            var JAId = $('#JAId').val();
            var PositionCode = $('#PositionCode').val();
            $.ajax({
                url: '<?= route('AssignPositionCode') ?>',
                method: 'POST',
                data: {
                    JAId: JAId,
                    PositionCode: PositionCode
                },
                dataType: 'json',
                success: function(data) {
                    if (data.status == 400) {
                        toastr.error(data.msg);
                    } else {
                        toastr.success(data.msg);
                        window.location.reload();

                    }
                },

            });
        });

        $(document).on('click', '#ProcessToEss', function() {
            var JAId = $('#JAId').val();
            $.ajax({
                url: '<?= route('processDataToEss') ?>',
                method: 'POST',
                data: {
                    JAId: JAId
                },
                dataType: 'json',
                beforeSend: function() {
                    $("#loader").modal('show');
                },
                success: function(data) {
                    if (data.status == 400) {
                        $("#loader").modal('hide');
                        toastr.error(data.msg);
                    } else {

                        $("#loader").modal('hide');
                        toastr.success(data.msg);

                        window.location.reload();
                    }
                },

            });
        });

        function delete_firob() {
            var JCId = $('#JCId').val();
            $.ajax({
                url: '<?= route('deleteFirob') ?>',
                method: 'POST',
                data: {
                    JCId: JCId,
                },
                dataType: 'json',
                success: function(data) {
                    if (data.status == 400) {
                        toastr.error(data.msg);
                    } else {
                        toastr.success(data.msg);
                        window.location.reload();
                    }
                },

            });
        }

        $(document).on("click", "#open_joining_form", function() {
            var JCId = $('#JCId').val();

            if (confirm("Are you sure you want to open joining form?")) {
                $.ajax({
                    url: '<?= route('open_joining_form') ?>',
                    method: 'POST',
                    data: {
                        JCId: JCId,
                    },
                    dataType: 'json',
                    success: function(data) {
                        if (data.status == 400) {
                            toastr.error(data.msg);
                        } else {
                            toastr.success(data.msg);
                            window.location.reload();
                        }
                    },

                });
            }
        });

        $(document).on("click", "#disable_offer_letter", function() {
            var JAId = $('#JAId').val();
            if (confirm("Are you sure you want to disable offer letter?")) {
                $.ajax({
                    url: '<?= route('disable_offer_letter') ?>',
                    method: 'POST',
                    data: {
                        JAId: JAId,
                    },
                    dataType: 'json',
                    success: function(data) {
                        if (data.status == 400) {
                            toastr.error(data.msg);
                        } else {
                            toastr.success(data.msg);
                            window.location.reload();
                        }
                    },

                });
            }


        });

        $(document).on("click", "#enable_offer_letter", function() {
            var JAId = $('#JAId').val();
            if (confirm("Are you sure you want to enable offer letter?")) {
                $.ajax({
                    url: '<?= route('enable_offer_letter') ?>',
                    method: 'POST',
                    data: {
                        JAId: JAId,
                    },
                    dataType: 'json',
                    success: function(data) {
                        if (data.status == 400) {
                            toastr.error(data.msg);
                        } else {
                            toastr.success(data.msg);
                            window.location.reload();
                        }
                    },

                });
            }


        });



        function OLAction(JAId) {
            $("#OlActionModal").modal('show');
        }

        $(document).on('change', '#ol_action', function() {
            if ($(this).val() == 'Accepted') {
                $("#ol_action_div").removeClass('d-none');
            } else {
                $("#ol_action_div").addClass('d-none');
            }
        });

        $('#responseform').on('submit', function(e) {
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
                    $('#loader').show();
                },
                success: function(data) {
                    $('#loader').hide();
                    if (data.status == 400) {
                        toastr.error(data.msg);
                        setTimeout(function() {
                                window.location.reload();
                            },
                            500);
                    } else {
                        $(form)[0].reset();
                        setTimeout(function() {
                                window.location.reload();
                            },
                            500);
                    }
                }
            });
        });

        function FourWheel_enable() {
            $('#FourWheel').prop('readonly', false);
            $('#FourWheel_enable').hide(500);
            $('#save_FourWheel').show(500);
            $('#FourWheel_can').show(500);
        }

        function TwoWheel_enable() {
            $('#TwoWheel').prop('readonly', false);
            $('#TwoWheel_enable').hide(500);
            $('#save_TwoWheel').show(500);
            $('#TwoWheel_can').show(500);
        }

        $(document).on('click', '#save_TwoWheel', function() {
            var JAId = $('#JAId').val();
            var TwoWheel = $('#TwoWheel').val();
            $.ajax({
                url: '<?= route('update_two_wheel') ?>',
                method: 'POST',
                data: {
                    JAId: JAId,
                    TwoWheel: TwoWheel
                },
                dataType: 'json',
                success: function(data) {
                    if (data.status == 400) {
                        toastr.error(data.msg);
                    } else {
                        toastr.success(data.msg);
                        window.location.reload();

                    }
                },

            });
        });
        $(document).on('click', '#save_FourWheel', function() {
            var JAId = $('#JAId').val();
            var FourWheel = $('#FourWheel').val();
            $.ajax({
                url: '<?= route('update_four_wheel') ?>',
                method: 'POST',
                data: {
                    JAId: JAId,
                    FourWheel: FourWheel
                },
                dataType: 'json',
                success: function(data) {
                    if (data.status == 400) {
                        toastr.error(data.msg);
                    } else {
                        toastr.success(data.msg);
                        window.location.reload();

                    }
                },

            });
        });
        $(document).on('change', '#Vertical', function() {
            var Vertical = $(this).val();
            $.ajax({
                type: "GET",
                url: "{{ route('getBUByVertical') }}",
                data: {
                    vertical_id: Vertical
                },
                success: function(res) {
                    if (res) {
                        $("#BU").empty();
                        $("#BU").append(
                            '<option value="">Select BU</option>');
                        $.each(res, function(key, value) {
                            $("#BU").append('<option value="' + value +
                                '">' +
                                key +
                                '</option>');
                        });
                        $('#BU').val();
                    } else {
                        $("#BU").empty();
                    }
                }
            });
        });
        $(document).on('change', '#BU', function() {
            var BU = $(this).val();
            $.ajax({
                type: "GET",
                url: "{{ route('getZoneByBU') }}",
                data: {
                    bu_id: BU
                },
                success: function(res) {
                    if (res) {
                        $("#Zone").empty();
                        $("#Zone").append(
                            '<option value="">Select Zone</option>');
                        $.each(res, function(key, value) {
                            $("#Zone").append('<option value="' + value +
                                '">' +
                                key +
                                '</option>');
                        });
                        $('#Zone').val();
                    } else {
                        $("#Zone").empty();
                    }
                }
            });
        });

        $(document).on('change', '#Zone', function() {
            var Zone = $(this).val();
            $.ajax({
                type: "GET",
                url: "{{ route('getRegionByZone') }}",
                data: {
                    zone_id: Zone
                },
                success: function(res) {
                    if (res) {
                        $("#Region").empty();
                        $("#Region").append(
                            '<option value="">Select Region</option>');
                        $.each(res, function(key, value) {
                            $("#Region").append('<option value="' + value +
                                '">' +
                                key +
                                '</option>');
                        });
                        $('#Region').val();
                    } else {
                        $("#Region").empty();
                    }
                }
            });
        });
        $(document).on('change', '#Region', function() {
            var Region = $(this).val();
            $.ajax({
                type: "GET",
                url: "{{ route('getTerritoryByRegion') }}",
                data: {
                    region_id: Region
                },
                success: function(res) {
                    if (res) {
                        $("#Territory").empty();
                        $("#Territory").append(
                            '<option value="">Select Territory</option>');
                        $.each(res, function(key, value) {
                            $("#Territory").append('<option value="' + value +
                                '">' +
                                key +
                                '</option>');
                        });
                        $('#Territory').val();
                    } else {
                        $("#Territory").empty();
                    }
                }
            });
        });
    </script>
@endsection