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
                            <input type="text" class="form-control" value="{{ $Rec->Email }}" readonly name="eMailId"
                                id="eMailId">
                        </div>
                        <div class="mb-3">
                            <input type="text" class="form-control" placeholder="Subject" name="Subject" id="Subject">
                        </div>
                        <div class="mb-3">
                            <textarea class="form-control" placeholder="Message" rows="10" cols="10" name="eMailMsg" id="eMailMsg"></textarea>
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
@endsection
@section('script_section')
    <script>
        $(document).ready(function () {
            $("#suitable_department").select2({
                placeholder: "Please select department"
            });
            $("#CandLogTable").DataTable({
                pageLength: 10,
                searching: false,
                bLengthChange: false,
                ordering: false,

            });
            $(document).on('click', '#HrScreening', function () {

                $('#HrScreeningModal').modal('show');
            });

            $(document).on("change", '#Irrelevant_Candidate', function () {
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
        $(document).on('click', '#MoveCandidate', function () {
            var JAId = $(this).data('id');
            $('#MoveCandidate_JAId').val(JAId);
            $('#MoveCandidategModal').modal('show');
        });
        $(document).on('change', '#MoveCompany', function () {
            var CompanyId = $(this).val();
            $.ajax({
                type: "GET",
                url: "{{ route('getDepartment') }}?CompanyId=" + CompanyId,
                success: function (res) {
                    if (res) {
                        $("#MoveDepartment").empty();
                        $("#MoveDepartment").append(
                            '<option value="">Select Department</option>');
                        $.each(res, function (key, value) {
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
        $('#MoveCandidateForm').on('submit', function (e) {
            e.preventDefault();
            var form = this;
            $.ajax({
                url: $(form).attr('action'),
                method: $(form).attr('method'),
                data: new FormData(form),
                processData: false,
                dataType: 'json',
                contentType: false,
                success: function (data) {
                    if (data.status == 400) {
                        toastr.error(data.msg);
                    } else {
                        toastr.success(data.msg);
                        window.location.reload();
                    }
                }
            });
        });
        $('#ScreeningForm').on('submit', function (e) {
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
                    success: function (data) {
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
        $(document).on('click', '#BlackListCandidate', function () {
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
                    success: function (data) {
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

        $(document).on('click', '#UnBlockCandidate', function () {
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
                    success: function (data) {
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

        $(document).on('click', '#SuitableFor', function () {
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
                success: function (response) {
                    if (response.status === 200) {
                        // Populate fields with the fetched data
                        $('#Irrelevant_Candidate').val(response.data.Irrelevant_Candidate).trigger('change');

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
                error: function () {
                    // Handle AJAX request failure
                    alert('Failed to communicate with the server. Please try again later.');
                }
            });

            // Show the modal after making the AJAX request
            $("#suitable_modal").modal('show');
        });


        $("#SuitableForm").on().submit(function (e) {
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
                    success: function (data) {
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
            $('.reqinp_scr').each(function () {
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
            $('.reqinp_suit').each(function () {
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
                success: function (data) {
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
                success: function (data) {
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
                success: function (data) {
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
                success: function (data) {
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
                success: function (data) {
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
                beforeSend: function () {
                    $('#EmpLoader').removeClass('d-none');
                    $('#review_to').addClass('d-none');
                },

                success: function (res) {
                    if (res) {
                        $('#EmpLoader').addClass('d-none');
                        $('#review_to').removeClass('d-none');
                        $("#review_to").empty();

                        $.each(res, function (key, value) {
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
                success: function (res) {

                    if (res) {
                        EducationList = '<option value="">Select</option>';
                        $.each(res, function (key, value) {
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
                success: function (res) {

                    if (res) {
                        CollegeList = '<option value="">Select</option>';
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
                success: function (data) {
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
                success: function (data) {
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
                success: function (data) {

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
                success: function (data) {

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
                success: function (data) {

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

        function GetCurrentAddress() {
            var JCId = $('#JCId').val();
            $.ajax({
                url: "{{ route('Candidate_CurrentAddress') }}",
                type: "POST",
                data: {
                    JCId: JCId
                },
                dataType: "json",
                success: function (data) {
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
                success: function (data) {
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
                success: function (data) {
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
                success: function (data) {
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


        $(document).on('click', '.dlchk', function () {
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

        $(document).on('click', '#addMember', function () {
            MemberCount++;
            familymember(MemberCount);
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
        });

        $(document).on('click', '#removeQualification', function () {
            if (confirm('Are you sure you want to delete this record?')) {
                $(this).closest('tr').remove();
                EducationCount--;
            }
        });

        $(document).on('click', '#addExperience', function () {
            WorkExpCount++;
            WorkExperience(WorkExpCount);
        });

        $(document).on('click', '#removeWorkExp', function () {
            if (confirm('Are you sure you want to delete this record?')) {
                $(this).closest('tr').remove();
                WorkExpCount--;
            }
        });

        $(document).on('click', '#addTraining', function () {
            TrainingCount++;
            Training(TrainingCount);
        });

        $(document).on('click', '#removeTraining', function () {
            if (confirm('Are you sure you want to delete this record?')) {
                $(this).closest('tr').remove();
                TrainingCount--;
            }
        });

        $(document).on('click', '#addPreOrgRef', function () {
            RefCount++;
            PreviousOrgReference(RefCount);
        });

        $(document).on('click', '#removePreOrgRef', function () {
            if (confirm('Are you sure you want to delete this record?')) {
                $(this).closest('tr').remove();
                RefCount--;
            }
        });

        $(document).on('click', '#addVnrRef', function () {
            VRefCount++;
            VNRReference(VRefCount);
        });

        $(document).on('click', '#removeVnrRef', function () {
            if (confirm('Are you sure you want to delete this record?')) {
                $(this).closest('tr').remove();
                VRefCount--;
            }
        });

        $(document).on('click', '#addLanguage', function () {
            LanguageCount++;
            LaguageProficiency(LanguageCount);
        });

        $(document).on('click', '#removeLanguage', function () {
            if (confirm('Are you sure you want to delete this record?')) {
                $(this).closest('tr').remove();
                LanguageCount--;
            }
        });

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


        $(document).on('change', '#Religion', function () {
            var Religion = $(this).val();
            if (Religion == 'Others') {
                $('#OtherReligion').removeClass('d-none');
            } else {
                $('#OtherReligion').addClass('d-none');
            }
        });

        $(document).on('change', '#Category', function () {
            var Category = $(this).val();
            if (Category == 'Other') {
                $('#OtherCategory').removeClass('d-none');
            } else {
                $('#OtherCategory').addClass('d-none');
            }
        });

        $(document).on('change', '#MaritalStatus', function () {
            var MaritalStatus = $(this).val();
            if (MaritalStatus == 'Married') {
                $('#MDate').removeClass('d-none');
                $('#Spouse').removeClass('d-none');
            } else {
                $('#MDate').addClass('d-none');
                $('#Spouse').addClass('d-none');
            }
        });

        $('#CandidatePersonalForm').on('submit', function (e) {
            e.preventDefault();
            var form = this;
            $.ajax({
                url: $(form).attr('action'),
                method: $(form).attr('method'),
                data: new FormData(form),
                processData: false,
                dataType: 'json',
                contentType: false,
                success: function (data) {
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

        $('#CandidateProfileForm').on('submit', function (e) {
            e.preventDefault();
            var form = this;
            $.ajax({
                url: $(form).attr('action'),
                method: $(form).attr('method'),
                data: new FormData(form),
                processData: false,
                dataType: 'json',
                contentType: false,
                success: function (data) {
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

        $('#EmergencyContactForm').on('submit', function (e) {
            e.preventDefault();
            var form = this;
            $.ajax({
                url: $(form).attr('action'),
                method: $(form).attr('method'),
                data: new FormData(form),
                processData: false,
                dataType: 'json',
                contentType: false,
                success: function (data) {
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

        $('#BankInfoForm').on('submit', function (e) {
            e.preventDefault();
            var form = this;
            $.ajax({
                url: $(form).attr('action'),
                method: $(form).attr('method'),
                data: new FormData(form),
                processData: false,
                dataType: 'json',
                contentType: false,
                success: function (data) {
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

        $('#FamilyInfoForm').on('submit', function (e) {
            e.preventDefault();
            var form = this;
            $.ajax({
                url: $(form).attr('action'),
                method: $(form).attr('method'),
                data: new FormData(form),
                processData: false,
                dataType: 'json',
                contentType: false,
                success: function (data) {
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

        $('#CurrentAddressForm').on('submit', function (e) {
            e.preventDefault();
            var form = this;
            $.ajax({
                url: $(form).attr('action'),
                method: $(form).attr('method'),
                data: new FormData(form),
                processData: false,
                dataType: 'json', 
                contentType: false,
                success: function (data) {
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

        $('#PermanentAddressForm').on('submit', function (e) {
            e.preventDefault();
            var form = this;
            $.ajax({
                url: $(form).attr('action'),
                method: $(form).attr('method'),
                data: new FormData(form),
                processData: false,
                dataType: 'json',
                contentType: false,
                success: function (data) {
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

        $('#EducationInfoForm').on('submit', function (e) {
            e.preventDefault();
            var form = this;
            $.ajax({
                url: $(form).attr('action'),
                method: $(form).attr('method'),
                data: new FormData(form),
                processData: false,
                dataType: 'json',
                contentType: false,
                success: function (data) {
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

        $('#WorkExpForm').on('submit', function (e) {
            e.preventDefault();
            var form = this;
            $.ajax({
                url: $(form).attr('action'),
                method: $(form).attr('method'),
                data: new FormData(form),
                processData: false,
                dataType: 'json',
                contentType: false,
                success: function (data) {
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

        $('#CurrentEmpForm').on('submit', function (e) {
            e.preventDefault();
            var form = this;
            $.ajax({
                url: $(form).attr('action'),
                method: $(form).attr('method'),
                data: new FormData(form),
                processData: false,
                dataType: 'json',
                contentType: false,
                success: function (data) {
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

        $('#CurrentSalaryForm').on('submit', function (e) {
            e.preventDefault();
            var form = this;
            $.ajax({
                url: $(form).attr('action'),
                method: $(form).attr('method'),
                data: new FormData(form),
                processData: false,
                dataType: 'json',
                contentType: false,
                success: function (data) {
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

        $('#TrainingForm').on('submit', function (e) {
            e.preventDefault();
            var form = this;
            $.ajax({
                url: $(form).attr('action'),
                method: $(form).attr('method'),
                data: new FormData(form),
                processData: false,
                dataType: 'json',
                contentType: false,
                success: function (data) {
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

        $('#PreOrgRefForm').on('submit', function (e) {
            e.preventDefault();
            var form = this;
            $.ajax({
                url: $(form).attr('action'),
                method: $(form).attr('method'),
                data: new FormData(form),
                processData: false,
                dataType: 'json',
                contentType: false,
                success: function (data) {
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

        $('#VNRRefForm').on('submit', function (e) {
            e.preventDefault();
            var form = this;
            $.ajax({
                url: $(form).attr('action'),
                method: $(form).attr('method'),
                data: new FormData(form),
                processData: false,
                dataType: 'json',
                contentType: false,
                success: function (data) {
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

        $('#OtherSeedForm').on('submit', function (e) {
            e.preventDefault();
            var form = this;
            $.ajax({
                url: $(form).attr('action'),
                method: $(form).attr('method'),
                data: new FormData(form),
                processData: false,
                dataType: 'json',
                contentType: false,
                success: function (data) {
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

        $('#BusinessForm').on('submit', function (e) {
            e.preventDefault();
            var form = this;
            $.ajax({
                url: $(form).attr('action'),
                method: $(form).attr('method'),
                data: new FormData(form),
                processData: false,
                dataType: 'json',
                contentType: false,
                success: function (data) {
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

        $('#about_form').on('submit', function (e) {
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
                success: function (data) {
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

        $('#SendMailForm').on('submit', function (e) {
            e.preventDefault();
            var form = this;
            $.ajax({
                url: $(form).attr('action'),
                method: $(form).attr('method'),
                data: new FormData(form),
                processData: false,
                dataType: 'json',
                contentType: false,
                beforeSend: function () {
                    $('#send_mail_btn').html('<i class="fa fa-spinner fa-spin"></i> Sending...');
                },
                success: function (data) {
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

        $("#permanent_chk").change(function () {
            if (!this.checked) {
                $("#permanent_div").addClass("d-none");
            } else {
                $("#permanent_div").removeClass("d-none");
            }
        });

        $("#temporary_chk").change(function () {
            if (!this.checked) {
                $("#temporary_div").addClass("d-none");
                $("#temporary_div1").addClass("d-none");
            } else {
                $("#temporary_div").removeClass("d-none");
                $("#temporary_div1").removeClass("d-none");
            }
        });

        $("#administrative_chk").change(function () {
            if (!this.checked) {
                $("#administrative_div").addClass("d-none");
            } else {
                $("#administrative_div").removeClass("d-none");
            }
        });

        $("#functional_chk").change(function () {
            if (!this.checked) {
                $("#functional_div").addClass("d-none");
            } else {
                $("#functional_div").removeClass("d-none");
            }
        });

        $(document).on('change', '#Grade', function () {
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
                success: function (res) {
                    if (res.status == 200) {
                        $("#Designation").empty();
                        $("#Designation").append(
                            '<option value="" selected>Select Designation</option>');
                        $.each(res.grade_designation_list, function (key, value) {
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

        $(document).on('change', '#Designation', function () {
            let DesigId = $(this).val();
            $.ajax({
                type: "GET",
                url: "{{ route('get_mw_by_designation') }}?DesigId=" + DesigId,
                success: function (res) {
                    if (res.status == 200) {
                        $("#MW").val(res.category)
                    } else {
                        $("#MW").empty();
                    }
                }
            });
        });

        $(document).on('click', '#offerltredit', function () {
            var JAId = $(this).data('id');
            $.ajax({
                type: "GET",
                url: "{{ route('get_offerltr_basic_detail') }}?JAId=" + JAId,
                success: function (res) {
                    if (res.status == 200) {
                        $('#Of_JAId').val(JAId);
                        $('#JCId').val(res.candidate_detail.JCId);
                        $('#SelectedForC').val(res.candidate_detail.SelectedForC);
                        $('#SelectedForD').val(res.candidate_detail.SelectedForD);
                        $("#Grade").empty();
                        $("#Grade").append(
                            '<option value="">Select Grade</option>');
                        $.each(res.grade_list, function (key, value) {
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
                        $.each(res.sub_department_list, function (key, value) {
                            $("#SubDepartment").append('<option value="' + value + '">' + key +
                                '</option>');
                        });

                        $('#SubDepartment').val(res.candidate_detail.SubDepartment);

                        $("#Section").empty();
                        $("#Section").append(
                            '<option value="">Select Section</option>');
                        $.each(res.section_list, function (key, value) {
                            $("#Section").append('<option value="' + value + '">' + key +
                                '</option>');
                        });

                        $('#Section').val(res.candidate_detail.Section);

                        $("#Designation").empty();
                        $("#Designation").append(
                            '<option value="">Select Designation</option>');
                        $.each(res.grade_designation_list, function (key, value) {
                            $("#Designation").append('<option value="' + value + '">' + key +
                                '</option>');
                        });

                        $('#Designation').val(res.candidate_detail.Designation);

                        $('#DesigSuffix').val(res.candidate_detail.DesigSuffix);
                        $("#DesignationRep").empty();
                        $("#DesignationRep").append(
                            '<option value="">Select Reporting Designation</option>');
                        $.each(res.designation_list, function (key, value) {
                            $("#DesignationRep").append('<option value="' + value + '">' + key +
                                '</option>');
                        });

                        $('#DesignationRep').val(res.candidate_detail.reporting_only_desig);


                        $("#Vertical").empty();
                        $("#Vertical").append(
                            '<option value="">Select Vertical</option>');
                        $.each(res.vertical_list, function (key, value) {
                            $("#Vertical").append('<option value="' + value + '">' + key +
                                '</option>');
                        });
                        if(res.candidate_detail.Department ==15){
                            $("#bu_tr").removeClass('d-none');
                            $("#zone_tr").removeClass('d-none');
                            $("#region_tr").removeClass('d-none');
                            $("#territory_tr").removeClass('d-none');
                            $.each(res.bu_list, function (key, value) {
                                $("#BU").append('<option value="' + value + '">' + key +
                                    '</option>');
                            });
                            $("#BU").val(res.candidate_detail.BU);
                            $.each(res.zone_list, function (key, value) {
                                $("#Zone").append('<option value="' + value + '">' + key +
                                    '</option>');
                            });
                            $("#Zone").val(res.candidate_detail.Zone);

                            $.each(res.region_list, function (key, value) {
                                $("#Region").append('<option value="' + value + '">' + key +
                                    '</option>');
                            });
                            $("#Region").val(res.candidate_detail.Region);

                            $.each(res.territory_list, function (key, value) {
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
                        $.each(res.department_list, function (key, value) {
                            $("#AdministrativeDepartment").append('<option value="' + value +
                                '">' + key +
                                '</option>');
                        });


                        $("#FunctionalDepartment").empty();
                        $("#FunctionalDepartment").append(
                            '<option value="">Select Department</option>');
                        $.each(res.department_list, function (key, value) {
                            $("#FunctionalDepartment").append('<option value="' + value + '">' +
                                key +
                                '</option>');
                        });

                        $("#AdministrativeEmployee").empty();
                        $("#AdministrativeEmployee").append(
                            '<option value="">Select Employee</option>');
                        $.each(res.employee_list, function (key, value) {
                            $("#AdministrativeEmployee").append('<option value="' + key + '">' +
                                value +
                                '</option>');
                        });

                        $("#FunctionalEmployee").empty();
                        $("#FunctionalEmployee").append(
                            '<option value="">Select Employee</option>');
                        $.each(res.employee_list, function (key, value) {
                            $("#FunctionalEmployee").append('<option value="' + key + '">' +
                                value +
                                '</option>');
                        });

                        $("#AftDesignation").empty();
                        $("#AftDesignation").append(
                            '<option value="0">Select Designation</option>');
                        $.each(res.designation_list, function (key, value) {
                            $("#AftDesignation").append('<option value="' + value + '">' + key +
                                '</option>');
                        });

                        $("#AftGrade").empty();
                        $("#AftGrade").append(
                            '<option value="">Select Grade</option>');
                        $.each(res.grade_list, function (key, value) {
                            $("#AftGrade").append('<option value="' + value + '">' + key +
                                '</option>');
                        });

                        $("#Of_PermState").empty();
                        $("#Of_PermState").append(
                            '<option value="">Select State</option>');
                        $.each(res.state_list, function (key, value) {
                            $("#Of_PermState").append('<option value="' + value + '">' + key +
                                '</option>');
                        });

                        $("#PermHQ").empty();
                        $("#PermHQ").append(
                            '<option value="">Select HQ</option>');
                        $.each(res.perm_headquarter_list, function (key, value) {
                            $("#PermHQ").append('<option value="' + value + '">' + key +
                                '</option>');
                        });

                        $("#TempState").empty();
                        $("#TempState").append(
                            '<option value="">Select State</option>');
                        $.each(res.state_list, function (key, value) {
                            $("#TempState").append('<option value="' + value + '">' + key +
                                '</option>');
                        });

                        $("#TempState1").empty();
                        $("#TempState1").append(
                            '<option value="">Select State</option>');
                        $.each(res.state_list, function (key, value) {
                            $("#TempState1").append('<option value="' + value + '">' + key +
                                '</option>');
                        });

                        $("#TempHQ").empty();
                        $("#TempHQ").append(
                            '<option value="">Select HQ</option>');
                        $.each(res.temp_headquarter_list, function (key, value) {
                            $("#TempHQ").append('<option value="' + value + '">' + key +
                                '</option>');
                        });

                        $("#TempHQ1").empty();
                        $("#TempHQ1").append(
                            '<option value="">Select HQ</option>');
                        $.each(res.temp1_headquarter_list, function (key, value) {
                            $("#TempHQ1").append('<option value="' + value + '">' + key +
                                '</option>');
                        });

                        $("#vehicle_policy").empty();
                        $("#vehicle_policy").append('<option value="">Select Policy</option>');
                        $("#vehicle_policy").append('<option value="NA">NA</option>');
                        $.each(res.vehicle_policy_list, function (key, value) {
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

        $('#OfferLtrModal').on('hidden.bs.modal', function () {
            $('#offerletterbasicform')[0].reset();
        });

        $(document).on('change', '#AdministrativeDepartment', function () {
            var DepartmentId = $(this).val();
            $.ajax({
                type: "GET",
                url: "{{ route('getReportingManager') }}?DepartmentId=" + DepartmentId,
                success: function (res) {
                    if (res) {
                        $("#AdministrativeEmployee").empty();
                        $("#AdministrativeEmployee").append(
                            '<option value="">Select Reporting</option>');
                        $.each(res, function (key, value) {
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

        $(document).on('change', '#FunctionalDepartment', function () {
            var DepartmentId = $(this).val();
            $.ajax({
                type: "GET",
                url: "{{ route('getReportingManager') }}?DepartmentId=" + DepartmentId,
                success: function (res) {
                    if (res) {
                        $("#FunctionalEmployee").empty();
                        $("#FunctionalEmployee").append(
                            '<option value="">Select Reporting</option>');
                        $.each(res, function (key, value) {
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

        $(document).on('change', '#Of_PermState', function () {
            var state_id = $(this).val();
            $.ajax({
                type: "GET",
                url: "{{ route('getCityVillageByState') }}?state_id=" + state_id,
                success: function (res) {
                    if (res) {
                        $("#PermHQ").empty();
                        $("#PermHQ").append(
                            '<option value="">Select Headquarter</option>');
                        $.each(res, function (key, value) {
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
        $(document).on('change', '#TempState', function () {
            var state_id = $(this).val();
            $.ajax({
                type: "GET",
                url: "{{ route('getCityVillageByState') }}?state_id=" + state_id,
                success: function (res) {
                    if (res) {
                        $("#TempHQ").empty();
                        $("#TempHQ").append(
                            '<option value="">Select Headquarter</option>');
                        $.each(res, function (key, value) {
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
        $(document).on('change', '#TempState1', function () {
            var state_id = $(this).val();
            $.ajax({
                type: "GET",
                url: "{{ route('getCityVillageByState') }}?state_id=" + state_id,
                success: function (res) {
                    if (res) {
                        $("#TempHQ1").empty();
                        $("#TempHQ1").append(
                            '<option value="">Select Headquarter</option>');
                        $.each(res, function (key, value) {
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

        $('#offerletterbasicform').on('submit', function (e) {
            e.preventDefault();
            var form = this;
            $.ajax({
                url: $(form).attr('action'),
                method: $(form).attr('method'),
                data: new FormData(form),
                processData: false,
                dataType: 'json',
                contentType: false,
                beforeSend: function () {
                    $(form).find('span.error-text').text('');
                    // $("#loader").modal('show');
                },
                success: function (data) {
                    if (data.status == 400) {
                        //  $("#loader").modal('hide');
                        $.each(data.error, function (prefix, val) {
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

        $('#reviewForm').on('submit', function (e) {
            e.preventDefault();
            var form = this;
            $.ajax({
                url: $(form).attr('action'),
                method: $(form).attr('method'),
                data: new FormData(form),
                processData: false,
                dataType: 'json',
                contentType: false,
                beforeSend: function () {
                    $(form).find('span.error-text').text('');
                    $('#review_modal').modal('hide');
                    $("#loader").modal('show');
                },
                success: function (data) {
                    if (data.status == 400) {
                        $("#loader").modal('hide');
                        $('#review_modal').modal('show');
                        $.each(data.error, function (prefix, val) {
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

        $('#ref_chk_form').on('submit', function (e) {
            e.preventDefault();
            var form = this;
            $.ajax({
                url: $(form).attr('action'),
                method: $(form).attr('method'),
                data: new FormData(form),
                processData: false,
                dataType: 'json',
                contentType: false,
                beforeSend: function () {
                    $(form).find('span.error-text').text('');
                    $('#ref_modal').modal('hide');
                    $("#loader").modal('show');
                },
                success: function (data) {
                    if (data.status == 400) {
                        $("#loader").modal('hide');
                        $('#ref_modal').modal('show');
                        $.each(data.error, function (prefix, val) {
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

        $(document).on('click', '#offerltrgen', function () {
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
                success: function (res) {
                    var x = '';
                    $.each(res.data, function (key, value) {
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
                success: function (res) {
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
                success: function (res) {
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
                success: function (res) {
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
                success: function (res) {
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
                success: function (res) {
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
                success: function (res) {
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
                success: function (res) {
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
                    beforeSend: function () {
                        $('#loader').modal('show');
                    },
                    success: function (data) {
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
                    beforeSend: function () {
                        $('#loader').modal('show');
                    },
                    success: function (data) {
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
                    beforeSend: function () {
                        $('#loader').modal('show');
                    },
                    success: function (data) {
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

            }).then(function (result) {
                if (result.value) {
                    $.post(url, {
                        JAId: JAId
                    }, function (data) {
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
            $.get(url, {JAId: JAId}, function (data) {
                if (data.status == 200) {
                    $('#view_review').modal('show');
                    let x = '';
                    let i = 1;
                    let reason = '';
                    $.each(data.data, function (key, value) {
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

        $(document).on('click', '#save_language', function () {
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
            }, function (data) {
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

                success: function (data) {
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

        $(document).on('change', '#GPRS', function () {
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
        (function (a) {
            a.createModal = function (b) {
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
                a("#myModal").modal('show').on("hidden.bs.modal", function () {
                    a(this).remove()
                })
            }
        })(jQuery);

        /*
         * Here is how you use it
         */
        $(function () {
            $('.view-pdf').on('click', function () {
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
                success: function (data) {
                    if (data.status == 400) {
                        toastr.error(data.msg);
                    } else {
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
                    toastr.error(errorsHtml)
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
                success: function (data) {
                    if (data.status == 200) {
                        toastr.success(data.msg);
                        window.location.reload();
                    } else {
                        toastr.error(data.msg);
                    }

                },
                error: function (data) {
                    var errors = data.responseJSON;
                    var errorsHtml = '';
                    $.each(errors.errors, function (key, value) {
                        errorsHtml += value[0] + '<br>';
                    });
                    toastr.error(errorsHtml)
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
                success: function (data) {
                    if (data.status == 400) {
                        toastr.error(data.msg);
                    } else {
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
                    toastr.error(errorsHtml)
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
                success: function (data) {
                    if (data.status == 400) {
                        toastr.error(data.msg);
                    } else {
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
                    toastr.error(errorsHtml)
                }
            });
        });

        $(document).on('click', '#AadhaarUpload', function () {
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
                success: function (data) {
                    if (data.status == 400) {
                        toastr.error(data.msg);

                    } else {

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

                }
            });
        });

        $(document).on('click', '#PANCardUpload', function () {
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
                success: function (data) {
                    if (data.status == 400) {
                        toastr.error(data.msg);

                    } else {

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

                }
            });
        });

        $(document).on('click', '#PassportUpload', function () {
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
                success: function (data) {
                    if (data.status == 200) {

                        toastr.success(data.msg);
                        window.location.reload();
                    } else {
                        toastr.error(data.msg);

                    }

                },
                error: function (data) {
                    var errors = data.responseJSON;
                    var errorsHtml = '';
                    $.each(errors.errors, function (key, value) {
                        errorsHtml += value[0] + '<br>';
                    });
                    toastr.error(errorsHtml);

                }
            });
        });

        $(document).on('click', '#DLCardUpload', function () {
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
                success: function (data) {
                    if (data.status == 400) {
                        toastr.error(data.msg);

                    } else {

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

                }
            });
        });

        $(document).on('click', '#PFForm2Upload', function () {
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
                success: function (data) {
                    if (data.status == 400) {
                        toastr.error(data.msg);

                    } else {

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

                }
            });
        });

        $(document).on('click', '#PFForm11Upload', function () {
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
                success: function (data) {
                    if (data.status == 400) {
                        toastr.error(data.msg);

                    } else {

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

                }
            });
        });

        $(document).on('click', '#GratuityUpload', function () {
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
                success: function (data) {
                    if (data.status == 400) {
                        toastr.error(data.msg);

                    } else {

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

                }
            });
        });

        $(document).on('click', '#ESICFormUpload', function () {
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
                success: function (data) {
                    if (data.status == 400) {
                        toastr.error(data.msg);

                    } else {

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

                }
            });
        });

        $(document).on('click', '#ESIC_FamilyUpload', function () {
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
                success: function (data) {
                    if (data.status == 400) {
                        toastr.error(data.msg);

                    } else {

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

                }
            });
        });

        $(document).on('click', '#HealthUpload', function () {
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
                success: function (data) {
                    if (data.status == 400) {
                        toastr.error(data.msg);

                    } else {

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

                }
            });
        });

        $(document).on('click', '#EthicalUpload', function () {
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
                success: function (data) {
                    if (data.status == 400) {
                        toastr.error(data.msg);

                    } else {

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

                }
            });
        });

        $(document).on('click', '#BloodGroupUpload', function () {
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
                success: function (data) {
                    if (data.status == 400) {
                        toastr.error(data.msg);

                    } else {

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

                }
            });
        });

        $(document).on('click', '#BankPassBookUpload', function () {
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
                success: function (data) {
                    if (data.status == 400) {
                        toastr.error(data.msg);

                    } else {

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

                }
            });
        });

        $(document).on('click', '#TestPaperUpload', function () {
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
                success: function (data) {
                    if (data.status == 400) {
                        toastr.error(data.msg);

                    } else {

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

                }
            });
        });

        $(document).on('click', '#IntervAssessmentUpload', function () {
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
                success: function (data) {
                    if (data.status == 400) {
                        toastr.error(data.msg);

                    } else {

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

        $(document).on('click', '#SaveVerification', function () {
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
                success: function (data) {
                    if (data.status == 400) {
                        toastr.error(data.msg);
                    } else {
                        toastr.success(data.msg);
                        window.location.reload();

                    }
                },

            });
        });

        $(document).on('click', '#SaveTwoWheelRC', function () {
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
                        success: function (data) {
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
                    success: function (data) {
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

        $(document).on('click', '#SaveFourWheelRC', function () {
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
                        success: function (data) {
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
                    success: function (data) {
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

        $(document).on('click', '#SaveJoined', function () {
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
                        success: function (data) {
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
                    success: function (data) {
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

        $(document).on('click', '#SaveClosure', function () {
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
                        success: function (data) {
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
                    success: function (data) {
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

        $(document).on('click', '#PositionCodeSave', function () {
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
                success: function (data) {
                    if (data.status == 400) {
                        toastr.error(data.msg);
                    } else {
                        toastr.success(data.msg);
                        window.location.reload();

                    }
                },

            });
        });

        $(document).on('click', '#ProcessToEss', function () {
            var JAId = $('#JAId').val();
            $.ajax({
                url: '<?= route('processDataToEss') ?>',
                method: 'POST',
                data: {
                    JAId: JAId
                },
                dataType: 'json',
                beforeSend: function () {
                    $("#loader").modal('show');
                },
                success: function (data) {
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
                success: function (data) {
                    if (data.status == 400) {
                        toastr.error(data.msg);
                    } else {
                        toastr.success(data.msg);
                        window.location.reload();
                    }
                },

            });
        }

        $(document).on("click", "#open_joining_form", function () {
            var JCId = $('#JCId').val();

            if (confirm("Are you sure you want to open joining form?")) {
                $.ajax({
                    url: '<?= route('open_joining_form') ?>',
                    method: 'POST',
                    data: {
                        JCId: JCId,
                    },
                    dataType: 'json',
                    success: function (data) {
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

        $(document).on("click", "#disable_offer_letter", function () {
            var JAId = $('#JAId').val();
            if (confirm("Are you sure you want to disable offer letter?")) {
                $.ajax({
                    url: '<?= route('disable_offer_letter') ?>',
                    method: 'POST',
                    data: {
                        JAId: JAId,
                    },
                    dataType: 'json',
                    success: function (data) {
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

        $(document).on("click", "#enable_offer_letter", function () {
            var JAId = $('#JAId').val();
            if (confirm("Are you sure you want to enable offer letter?")) {
                $.ajax({
                    url: '<?= route('enable_offer_letter') ?>',
                    method: 'POST',
                    data: {
                        JAId: JAId,
                    },
                    dataType: 'json',
                    success: function (data) {
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

        $(document).on('change', '#ol_action', function () {
            if ($(this).val() == 'Accepted') {
                $("#ol_action_div").removeClass('d-none');
            } else {
                $("#ol_action_div").addClass('d-none');
            }
        });

        $('#responseform').on('submit', function (e) {
            e.preventDefault();
            var form = this;
            $.ajax({
                url: $(form).attr('action'),
                method: $(form).attr('method'),
                data: new FormData(form),
                processData: false,
                dataType: 'json',
                contentType: false,
                beforeSend: function () {
                    $('#loader').show();
                },
                success: function (data) {
                    $('#loader').hide();
                    if (data.status == 400) {
                        toastr.error(data.msg);
                        setTimeout(function () {
                                window.location.reload();
                            },
                            500);
                    } else {
                        $(form)[0].reset();
                        setTimeout(function () {
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

        $(document).on('click', '#save_TwoWheel', function () {
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
                success: function (data) {
                    if (data.status == 400) {
                        toastr.error(data.msg);
                    } else {
                        toastr.success(data.msg);
                        window.location.reload();

                    }
                },

            });
        });
        $(document).on('click', '#save_FourWheel', function () {
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
                success: function (data) {
                    if (data.status == 400) {
                        toastr.error(data.msg);
                    } else {
                        toastr.success(data.msg);
                        window.location.reload();

                    }
                },

            });
        });
        $(document).on('change', '#Vertical', function () {
            var Vertical = $(this).val();
            $.ajax({
                type: "GET",
                url: "{{ route('getBUByVertical') }}",
                data: {
                    vertical_id : Vertical
                },
                success: function (res) {
                    if (res) {
                        $("#BU").empty();
                        $("#BU").append(
                            '<option value="">Select BU</option>');
                        $.each(res, function (key, value) {
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
        $(document).on('change', '#BU', function () {
            var BU = $(this).val();
            $.ajax({
                type: "GET",
                url: "{{ route('getZoneByBU') }}",
                data: {
                    bu_id : BU
                },
                success: function (res) {
                    if (res) {
                        $("#Zone").empty();
                        $("#Zone").append(
                            '<option value="">Select Zone</option>');
                        $.each(res, function (key, value) {
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

        $(document).on('change', '#Zone', function () {
            var Zone = $(this).val();
            $.ajax({
                type: "GET",
                url: "{{ route('getRegionByZone') }}",
                data: {
                    zone_id : Zone
                },
                success: function (res) {
                    if (res) {
                        $("#Region").empty();
                        $("#Region").append(
                            '<option value="">Select Region</option>');
                        $.each(res, function (key, value) {
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
        $(document).on('change', '#Region', function () {
            var Region = $(this).val();
            $.ajax({
                type: "GET",
                url: "{{ route('getTerritoryByRegion') }}",
                data: {
                    region_id : Region
                },
                success: function (res) {
                    if (res) {
                        $("#Territory").empty();
                        $("#Territory").append(
                            '<option value="">Select Territory</option>');
                        $.each(res, function (key, value) {
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