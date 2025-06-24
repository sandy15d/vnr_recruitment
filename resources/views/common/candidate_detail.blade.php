
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
        ->leftJoin('candidate_entitlement','candidate_entitlement.JAId','=','offerletterbasic.JAId')
        ->select('offerletterbasic.*', 'candjoining.JoinOnDt', 'appointing.A_Date', 'appointing.Agr_Date',
        'appointing.B_Date', 'appointing.ConfLtrDate', 'candjoining.EmpCode', 'candjoining.Verification',
        'candjoining.Joined', 'candjoining.PositionCode', 'candjoining.ForwardToESS', 'candjoining.NoJoiningRemark',
        'candjoining.RejReason as RejReason1','candidate_entitlement.TwoWheel','candidate_entitlement.FourWheel')
        ->where('offerletterbasic.JAId', $JAId)
        ->first();

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
    $Year = Carbon::now()->year;
    $sql = DB::table('offerletterbasic_history')
        ->where('JAId', $JAId)
        ->get();
    $lang = DB::table('jf_language')
        ->where('JCId', $JCId)
        ->get();
    $count = count($sql);
    $OtherSeed = DB::table('relation_other_seed_cmp')
        ->where('JCId', $JCId)
        ->get();
    $VnrBusinessRef = DB::table('vnr_business_ref')
        ->where('JCId', $JCId)
        ->get();
    $AboutAns = DB::table('about_answer')
        ->where('JCId', $JCId)
        ->first();
    $Docs = DB::table('jf_docs')
        ->where('JCId', $JCId)
        ->first();
    $vehicle_info = DB::table('vehicle_information')
        ->where('JCId', $JCId)
        ->first();
    $country_list = DB::table('core_country')->pluck('country_name', 'id');
    $candidate_log = DB::table('candidate_log')
        ->where('JCId', $JCId)
        ->get();

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