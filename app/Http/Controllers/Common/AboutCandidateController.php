<?php

namespace App\Http\Controllers\Common;

use App\Helpers\CandidateActivityLog;
use App\Models\jobcandidate;
use Illuminate\Support\Facades\Session;
use Mpdf\Mpdf;
use App\Helpers\UserNotification;
use App\Http\Controllers\Controller;
use App\Mail\RefCheckMail;
use App\Models\Appointing;
use App\Models\jf_contact_det;
use App\Models\jf_pf_esic;
use App\Models\jobapply;
use App\Models\jobpost;
use App\Models\OfferLetter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Storage;

class AboutCandidateController extends Controller
{
    public function candidate_detail()
    {
        $state_list = DB::table("states")->orderBy('StateName', 'asc')->pluck("StateName", "StateId");
        $district_list = DB::table("master_district")->orderBy('DistrictName', 'asc')->pluck("DistrictName", "DistrictId");
        $education_list = DB::table("master_education")->orderBy('EducationCode', 'asc')->pluck("EducationCode", "EducationId");
        $specialization_list = DB::table("master_specialization")->orderBy('Specialization', 'asc')->pluck("Specialization", "SpId");
        $institute_list = DB::table("master_institute")->orderBy('InstituteName', 'asc')->pluck("InstituteName", "InstituteId");
        $company_list = DB::table("core_company")->orderBy('id', 'asc')->pluck("company_code", "id");
        $department_list = DB::table("core_department")->orderBy('department_name', 'asc')->pluck("department_name", "id");
        return view('common.candidate_detail', compact('state_list', 'district_list', 'education_list', 'institute_list', 'specialization_list', 'company_list', 'department_list'));
    }

    public function interview_form_detail()
    {
        ini_set('memory_limit', -1);
        $state_list = DB::table("states")->orderBy('StateName', 'asc')->pluck("StateName", "StateId");
        $district_list = DB::table("master_district")->orderBy('DistrictName', 'asc')->pluck("DistrictName", "DistrictId");
        $education_list = DB::table("master_education")->orderBy('EducationCode', 'asc')->pluck("EducationCode", "EducationId");
        $specialization_list = DB::table("master_specialization")->orderBy('Specialization', 'asc')->pluck("Specialization", "SpId");
        $institute_list = DB::table("master_institute")->orderBy('InstituteName', 'asc')->pluck("InstituteName", "InstituteId");
        $jaid = base64_decode($_GET['jaid']);

        $sql = DB::table('jobapply')->select('jobcandidates.FName', 'jobcandidates.MName', 'jobcandidates.LName',)->join('jobcandidates', 'jobapply.JCId', '=', 'jobcandidates.JCId')->where('jobapply.JAId', $jaid)->first();
        $candidate_name = $sql->FName . ' ' . $sql->MName . ' ' . $sql->LName;
        $mpdf = new mPDF(['utf-8', 'A4-C']);
        $mpdf->SetHTMLHeader('
            <div style="font-size: 14px; font-weight: bold;">
            <div style="float: left; width: 33%; text-align: left;">Version 1.5</div>
            <div style="float: left; width: 33%; text-align: center;">Application Form</div>
            <div style="float: left; width: 33%; text-align: right;">VNR Seeds Pvt. Ltd.</div>
            </div>
            <hr style="margin-top:0px;margin-bottom:20px;">
        ');

        $html = View::make('common.interview_form_detail', compact('state_list', 'district_list', 'education_list', 'institute_list', 'specialization_list'))->render();
        $mpdf->SetHTMLFooter('
            <div style="text-align: center; font-weight: bold;">
            <hr>
            <div style="font-size: 12px; font-weight: bold;">
            <div style="float: left; width: 33%; text-align: left;">Date of Release: 09 September 2019</div>
            <div style="float: left; width: 33%; text-align: center;">Released by HRD</div>
            <div style="float: left; width: 33%; text-align: right;">Page {PAGENO} of {nbpg}</div>
            </div>
            </div>
        ');

        $mpdf->SetDefaultBodyCSS('font-family', 'freeserif');
        $mpdf->SetTitle('Interview Application Form');

        $mpdf->WriteHTML($html);
        //

        $mpdf->Output($candidate_name . ' Interview Application Form.pdf', 'I');
    }

    public function joining_form_print()
    {
        ini_set('memory_limit', -1);
        $state_list = DB::table("states")->orderBy('StateName', 'asc')->pluck("StateName", "StateId");
        $district_list = DB::table("master_district")->orderBy('DistrictName', 'asc')->pluck("DistrictName", "DistrictId");
        $education_list = DB::table("master_education")->orderBy('EducationCode', 'asc')->pluck("EducationCode", "EducationId");
        $specialization_list = DB::table("master_specialization")->orderBy('Specialization', 'asc')->pluck("Specialization", "SpId");
        $institute_list = DB::table("master_institute")->orderBy('InstituteName', 'asc')->pluck("InstituteName", "InstituteId");

        $jaid = base64_decode($_GET['jaid']);
        $sql = DB::table('jobapply')->select('jobcandidates.FName', 'jobcandidates.MName', 'jobcandidates.LName',)->join('jobcandidates', 'jobapply.JCId', '=', 'jobcandidates.JCId')->where('jobapply.JAId', $jaid)->first();
        $candidate_name = $sql->FName . ' ' . $sql->MName . ' ' . $sql->LName;
        $joining_pdf = new mPDF(['utf-8', 'A4-C',]);
        $joining_pdf->SetHTMLHeader('
            <div style="font-size: 14px; font-weight: bold;">
            <div style="float: left; width: 33%; text-align: left;">Version 1.5</div>
            <div style="float: left; width: 33%; text-align: center;">Joining Form</div>
            <div style="float: left; width: 33%; text-align: right;">VNR Seeds Pvt. Ltd.</div>
            </div>
            <hr style="margin-top:0px;margin-bottom:20px;">
        ');

        $html = View::make('common.joining_form_print', compact('state_list', 'district_list', 'education_list', 'institute_list', 'specialization_list'))->render();
        $joining_pdf->SetHTMLFooter('
            <div style="text-align: center; font-weight: bold;">
            <hr>
            <div style="font-size: 12px; font-weight: bold;">
            <div style="float: left; width: 33%; text-align: left;">Date of Release: 09 September 2019</div>
            <div style="float: left; width: 33%; text-align: center;">Released by HRD</div>
            <div style="float: left; width: 33%; text-align: right;">Page {PAGENO} of {nbpg}</div>
            </div>
            </div>
        ');

        $joining_pdf->SetDefaultBodyCSS('font-family', 'freeserif');
        $joining_pdf->SetTitle('Joining Form');
        $joining_pdf->WriteHTML($html);
        $joining_pdf->Output($candidate_name . ' Joining_Form.pdf', 'I');
    }

    public function Candidate_PersonalData(Request $request)
    {
        $JCId = $request->JCId;
        $query = "SELECT * FROM `jobcandidates` WHERE `JCId` = '$JCId'";
        $result = DB::select($query);
        if (!$result) {
            return response()->json(['status' => 400, 'msg' => 'Something went wrong..!!']);
        } else {

            return response()->json(['status' => 200, 'data' => $result[0]]);
        }
    }

    public function Candidate_PersonalData_Save(Request $request)
    {

        $JCId = $request->P_JCId;
        $Aadhaar = $request->Aadhaar;
        $Gender = $request->Gender;
        $Nationality = $request->Nationality;
        $Religion = $request->Religion;
        $OtherReligion = $request->OtherReligion;
        $MaritalStatus = $request->MaritalStatus;
        $MarriageDate = $request->MarriageDate;
        $SpouseName = $request->SpouseName;
        $Category = $request->Category;
        $OtherCategory = $request->OtherCategory;

        $query = DB::table('jobcandidates')->where('JCId', $JCId)->update(['Aadhaar' => $Aadhaar, 'Gender' => $Gender, 'Nationality' => $Nationality, 'Religion' => $Religion, 'OtherReligion' => $OtherReligion ?? null, 'MaritalStatus' => $MaritalStatus, 'MarriageDate' => $MarriageDate ?? null, 'SpouseName' => $SpouseName, 'Caste' => $Category, 'OtherCaste' => $OtherCategory ?? null, 'FatherTitle' => $request->FatherTitle, 'FatherName' => $request->FatherName, 'LastUpdated' => now()

        ]);

        if (!$query) {
            return response()->json(['status' => 400, 'msg' => 'Something went wrong..!!']);
        } else {

            return response()->json(['status' => 200, 'msg' => 'Data has been changed successfully']);
        }
    }


    public function Candidate_ProfileData(Request $request)
    {
        $JCId = $request->JCId;
        $query = "SELECT JCId,FName,LName,MName,DOB,Phone,Email,CandidateImage FROM `jobcandidates` WHERE `JCId` = '$JCId'";
        $result = DB::select($query);
        if (!$result) {
            return response()->json(['status' => 400, 'msg' => 'Something went wrong..!!']);
        } else {

            return response()->json(['status' => 200, 'data' => $result[0]]);
        }
    }

    public function Candidate_ProfileData_Save(Request $request)
    {
        $JCId = $request->Pro_JCId;
        $FName = $request->FName;
        $MName = $request->MName;
        $LName = $request->LName;
        $DOB = $request->DOB;
        $Phone = $request->Mobile;
        $Email = $request->EMail;

        if ($request->hasFile('Resume')) {
            $Resume = 'resume_' . $JCId . '.' . $request->file('Resume')->extension();
            $request->file('Resume')->storeAs('Recruitment/Resume', $Resume, 's3');
            DB::table('jobcandidates')->where('JCId', $JCId)->update(['Resume' => $Resume]);
        }

        if ($request->hasFile('CandidateImage')) {
            $filename = $JCId . '.' . $request->file('CandidateImage')->extension();
            DB::table('jobcandidates')->where('JCId', $JCId)->update(['CandidateImage' => $filename, 'LastUpdated' => now()]);

            // Delete existing image from S3 if it exists
            if (Storage::disk('s3')->exists('Recruitment/Picture/' . $filename)) {
                Storage::disk('s3')->delete('Recruitment/Picture/' . $filename);
            }
            
            // Upload new image to S3 bucket
            $request->file('CandidateImage')->storeAs('Recruitment/Picture', $filename, 's3');
        }

        $query = DB::table('jobcandidates')->where('JCId', $JCId)->update(['FName' => $FName, 'MName' => $MName, 'LName' => $LName, 'DOB' => $DOB, 'Phone' => $Phone, 'Email' => $Email, 'LastUpdated' => now()]);

        if (!$query) {
            return response()->json(['status' => 400, 'msg' => 'Something went wrong..!!']);
        }

        return response()->json(['status' => 200, 'msg' => 'Data has been changed successfully']);
    }


    public function Candidate_EmergencyContact(Request $request)
    {
        $JCId = $request->JCId;
        $query = "SELECT * FROM jf_contact_det WHERE JCId='$JCId'";
        $result = DB::select($query);
        if (!$result) {
            return response()->json(['status' => 400, 'msg' => 'No Record Found..!!']);
        } else {

            return response()->json(['status' => 200, 'data' => $result[0]]);
        }
    }

    public function Candidate_EmergencyContact_Save(Request $request)
    {
        $JCId = $request->Emr_JCId;
        $query = DB::table('jf_contact_det')->where('JCId', $JCId)->first();
        if ($query !== null) {
            $query1 = DB::table('jf_contact_det')->where('JCId', $JCId)->update(['cont_one_name' => $request->PrimaryName, 'cont_one_number' => $request->PrimaryPhone, 'cont_one_relation' => $request->PrimaryRelation, 'cont_two_name' => $request->SecondaryName, 'cont_two_number' => $request->SecondaryPhone, 'cont_two_relation' => $request->SecondaryRelation]);
        } else {
            $query1 = new jf_contact_det;
            $query1->JCId = $JCId;
            $query1->cont_one_name = $request->PrimaryName;
            $query1->cont_one_number = $request->PrimaryPhone;
            $query1->cont_one_relation = $request->PrimaryRelation;
            $query1->cont_two_name = $request->SecondaryName ?? null;
            $query1->cont_two_number = $request->SecondaryPhone ?? null;
            $query1->cont_two_relation = $request->SecondaryRelation ?? null;
            $query1->LastUpdated = now();
            $query1->save();
        }
        if (!$query1) {
            return response()->json(['status' => 400, 'msg' => 'Something went wrong..!!']);
        } else {

            return response()->json(['status' => 200, 'msg' => 'Data has been changed successfully']);
        }
    }

    public function Candidate_BankInfo(Request $request)
    {
        $JCId = $request->JCId;
        $query = "SELECT * FROM jf_pf_esic WHERE JCId='$JCId'";
        $result = DB::select($query);
        if (!$result) {
            return response()->json(['status' => 400, 'msg' => 'No Record Found..!!']);
        } else {

            return response()->json(['status' => 200, 'data' => $result[0]]);
        }
    }

    public function Candidate_BankInfo_Save(Request $request)
    {
        $JCId = $request->Bank_JCId;
        $query = DB::table('jf_pf_esic')->where('JCId', $JCId)->first();
        if ($query !== null) {
            $query1 = DB::table('jf_pf_esic')->where('JCId', $JCId)->update(['UAN' => $request->UAN, 'BankName' => $request->BankName, 'BranchName' => $request->BranchName, 'AccountNumber' => $request->AccountNumber, 'IFSCCode' => $request->IFSCCode, 'PFNumber' => $request->PFNumber, 'ESICNumber' => $request->ESICNumber, 'PAN' => $request->PAN, 'Passport' => $request->Passport, 'LastUpdated' => now()]);
        } else {
            $query1 = new jf_pf_esic;
            $query1->JCId = $JCId;
            $query1->UAN = $request->UAN;
            $query1->BankName = $request->BankName;
            $query1->BranchName = $request->BranchName;
            $query1->AccountNumber = $request->AccountNumber;
            $query1->IFSCCode = $request->IFSCCode;
            $query1->PFNumber = $request->PFNumber;
            $query1->ESICNumber = $request->ESICNumber;
            $query1->PAN = $request->PAN;
            $query1->Passport = $request->Passport;
            $query1->LastUpdated = now();
            $query1->save();
        }
        if (!$query1) {
            return response()->json(['status' => 400, 'msg' => 'Something went wrong..!!']);
        } else {

            return response()->json(['status' => 200, 'msg' => 'Data has been changed successfully']);
        }
    }

    public function Candidate_Family(Request $request)
    {
        $JCId = $request->JCId;
        $query = "SELECT * FROM jf_family_det WHERE JCId='$JCId'";
        $result = DB::select($query);
        if (!$result) {
            return response()->json(['status' => 400, 'msg' => 'No Record Found..!!']);
        } else {

            return response()->json(['status' => 200, 'data' => $result]);
        }
    }

    public function Candidate_Family_Save(Request $request)
    {
        $JCId = $request->Family_JCId;
        $Relation = $request->Relation;
        $RelationName = $request->RelationName;
        $RelationDOB = $request->RelationDOB;
        $RelationQualification = $request->RelationQualification;
        $RelationOccupation = $request->RelationOccupation;

        $query = DB::table('jf_family_det')->where('JCId', $JCId)->delete();

        $FamilyArray = array();
        for ($i = 0; $i < count($Relation); $i++) {
            $FamilyArray[$i] = array('JCId' => $JCId, 'relation' => $Relation[$i], 'name' => $RelationName[$i], 'dob' => $RelationDOB[$i], 'qualification' => $RelationQualification[$i], 'occupation' => $RelationOccupation[$i],);
        }

        $query1 = DB::table('jf_family_det')->insert($FamilyArray);
        if (!$query1) {
            return response()->json(['status' => 400, 'msg' => 'Something went wrong..!!']);
        } else {

            return response()->json(['status' => 200, 'msg' => 'Data has been changed successfully']);
        }
    }

    public function Candidate_CurrentAddress(Request $request)
    {
        $JCId = $request->JCId;
        $query = "SELECT * FROM jf_contact_det WHERE JCId='$JCId'";
        $result = DB::select($query);
        if (!$result) {
            return response()->json(['status' => 400, 'msg' => 'No Record Found..!!']);
        } else {

            return response()->json(['status' => 200, 'data' => $result[0]]);
        }
    }

    public function Candidate_CurrentAddress_Save(Request $request)
    {
        $JCId = $request->Current_JCId;
        $query = DB::table('jf_contact_det')->where('JCId', $JCId)->first();
        if ($query !== null) {
            $query1 = DB::table('jf_contact_det')->where('JCId', $JCId)->update(['pre_address' => $request->PreAddress, 'pre_city' => $request->PreCity, 'pre_state' => $request->PreState, 'pre_pin' => $request->PrePinCode, 'pre_dist' => $request->PreDistrict, 'LastUpdated' => now()]);
        } else {
            $query1 = new jf_contact_det;
            $query1->JCId = $JCId;
            $query1->pre_address = $request->PreAddress;
            $query1->pre_city = $request->PreCity;
            $query1->pre_state = $request->PreState;
            $query1->pre_pin = $request->PrePinCode;
            $query1->pre_dist = $request->PreDistrict;
            $query1->LastUpdated = now();
            $query1->save();
        }
        if (!$query1) {
            return response()->json(['status' => 400, 'msg' => 'Something went wrong..!!']);
        } else {

            return response()->json(['status' => 200, 'msg' => 'Data has been changed successfully']);
        }
    }

    public function Candidate_PermanentAddress(Request $request)
    {
        $JCId = $request->JCId;
        $query = "SELECT * FROM jf_contact_det WHERE JCId='$JCId'";
        $result = DB::select($query);
        if (!$result) {
            return response()->json(['status' => 400, 'msg' => 'No Record Found..!!']);
        } else {

            return response()->json(['status' => 200, 'data' => $result[0]]);
        }
    }

    public function Candidate_PermanentAddress_Save(Request $request)
    {

        $JCId = $request->Permanent_JCId;
        $query = DB::table('jf_contact_det')->where('JCId', $JCId)->first();
        if ($query !== null) {
            $query1 = DB::table('jf_contact_det')->where('JCId', $JCId)->update(['perm_address' => $request->PermAddress, 'perm_city' => $request->PermCity, 'perm_state' => $request->PermState, 'perm_pin' => $request->PermPinCode, 'perm_dist' => $request->PermDistrict, 'LastUpdated' => now()]);
        } else {
            $query1 = new jf_contact_det;
            $query1->JCId = $JCId;
            $query1->perm_address = $request->PermAddress;
            $query1->perm_city = $request->PermCity;
            $query1->perm_state = $request->PermState;
            $query1->perm_pin = $request->PermPinCode;
            $query1->perm_dist = $request->PermDistrict;
            $query1->LastUpdated = now();
            $query1->save();
        }
        if (!$query1) {
            return response()->json(['status' => 400, 'msg' => 'Something went wrong..!!']);
        } else {

            return response()->json(['status' => 200, 'msg' => 'Data has been changed successfully']);
        }
    }

    public function Candidate_Education(Request $request)
    {
        $JCId = $request->JCId;
        $query = "SELECT * FROM candidateeducation WHERE JCId='$JCId'";
        $result = DB::select($query);
        if (!$result) {
            return response()->json(['status' => 400, 'msg' => 'No Record Found..!!']);
        } else {

            return response()->json(['status' => 200, 'data' => $result]);
        }
    }

    public function Candidate_Education_Save(Request $request)
    {

        $JCId = $request->Edu_JCId;
        $Qualification = $request->Qualification;
        $Course = $request->Course;
        $Specialization = $request->Specialization;
        $Institute = $request->Collage;
        $PassingYear = $request->PassingYear;
        $CGPA = $request->Percentage;

        $query = DB::table('candidateeducation')->where('JCId', $JCId)->delete();

        $educationArray = array();
        for ($i = 0; $i < count($Qualification); $i++) {
            $educationArray[$i] = array('JCId' => $JCId, 'Qualification' => $Qualification[$i], 'Course' => $Course[$i], 'Specialization' => $Specialization[$i], 'Institute' => $Institute[$i], 'YearOfPassing' => $PassingYear[$i], 'CGPA' => $CGPA[$i], 'LastUpdated' => now());
        }

        $query1 = DB::table('candidateeducation')->insert($educationArray);
        if (!$query1) {
            return response()->json(['status' => 400, 'msg' => 'Something went wrong..!!']);
        } else {

            return response()->json(['status' => 200, 'msg' => 'Data has been changed successfully']);
        }
    }

    public function Candidate_Experience(Request $request)
    {
        $JCId = $request->JCId;
        $query = "SELECT * FROM jf_work_exp WHERE JCId='$JCId'";
        $result = DB::select($query);
        if (!$result) {
            return response()->json(['status' => 400, 'msg' => 'No Record Found..!!']);
        } else {

            return response()->json(['status' => 200, 'data' => $result]);
        }
    }

    public function Candidate_Experience_Save(Request $request)
    {
        $JCId = $request->Work_JCId;
        $company_name = $request->WorkExpCompany;
        $Designation = $request->WorkExpDesignation;
        $GrossMonthSalary = $request->WorkExpGrossMonthlySalary;
        $AnualCTC = $request->WorkExpAnualCTC;
        $FromDate = $request->WorkExpJobStartDate;
        $ToDate = $request->WorkExpJobEndDate;
        $ReasonForLeaving = $request->WorkExpReasonForLeaving;
        $query = DB::table('jf_work_exp')->where('JCId', $JCId)->delete();

        $experienceArray = array();
        for ($i = 0; $i < count($company_name); $i++) {
            $experienceArray[$i] = array('JCId' => $JCId, 'company' => $company_name[$i], 'desgination' => $Designation[$i], 'gross_mon_sal' => $GrossMonthSalary[$i], 'annual_ctc' => $AnualCTC[$i], 'job_start' => $FromDate[$i], 'job_end' => $ToDate[$i], 'reason_fr_leaving' => $ReasonForLeaving[$i], 'LastUpdated' => now());
        }

        $query1 = DB::table('jf_work_exp')->insert($experienceArray);
        if (!$query1) {
            return response()->json(['status' => 400, 'msg' => 'Something went wrong..!!']);
        } else {

            return response()->json(['status' => 200, 'msg' => 'Data has been changed successfully']);
        }
    }

    public function Candidate_CurrentEmployement_Save(Request $request)
    {
        $JCId = $request->Curr_JCId;
        $PresentCompany = $request->Currcompany_name;

        $Designation = $request->CurrDesignation;
        $JobStartDate = $request->CurrDateOfJoining;
        $Reporting = $request->CurrReportingTo;
        $RepDesig = $request->CurrRepDesig;
        $JobResponsibility = $request->CurrJobResponsibility;
        $ResignReason = $request->CurrReason;
        $NoticePeriod = $request->CurrNoticePeriod;
        $query = DB::table('jobcandidates')->where('JCId', $JCId)->update(['PresentCompany' => $PresentCompany,

            'Designation' => $Designation, 'JobStartDate' => $JobStartDate, 'Reporting' => $Reporting, 'RepDesig' => $RepDesig, 'JobResponsibility' => $JobResponsibility, 'NoticePeriod' => $NoticePeriod, 'ResignReason' => $ResignReason, 'LastUpdated' => now()]);
        if (!$query) {
            return response()->json(['status' => 400, 'msg' => 'Something went wrong..!!']);
        } else {

            return response()->json(['status' => 200, 'msg' => 'Data has been changed successfully']);
        }
    }

    public function Candidate_CurrentSalary_Save(Request $request)
    {
        $JCId = $request->Sal_JCId;
        $DAHq = $request->CurrDA;
        $GrossSalary = $request->CurrSalary;
        $DAOutHq = $request->DAOutHq;
        $PetrolAlw = $request->PetrolAlw;
        $PhoneAlw = $request->PhoneAlw;
        $HotelElg = $request->HotelElg;
        $CTC = $request->CurrCTC;
        $query = DB::table('jobcandidates')->where('JCId', $JCId)->update(['DAHq' => $DAHq, 'GrossSalary' => $GrossSalary, 'DAOutHq' => $DAOutHq, 'PetrolAlw' => $PetrolAlw, 'PhoneAlw' => $PhoneAlw, 'HotelElg' => $HotelElg, 'CTC' => $CTC, 'LastUpdated' => now()]);
        if (!$query) {
            return response()->json(['status' => 400, 'msg' => 'Something went wrong..!!']);
        } else {

            return response()->json(['status' => 200, 'msg' => 'Data has been changed successfully']);
        }
    }

    public function Candidate_Training(Request $request)
    {
        $JCId = $request->JCId;
        $query = "SELECT * FROM jf_tranprac WHERE JCId='$JCId'";
        $result = DB::select($query);
        if (!$result) {
            return response()->json(['status' => 400, 'msg' => 'No Record Found..!!']);
        } else {

            return response()->json(['status' => 200, 'data' => $result]);
        }
    }

    public function Candidate_Training_Save(Request $request)
    {
        $JCId = $request->Training_JCId;
        $training = $request->TrainingNature;
        $organization = $request->TrainingOrganization;
        $from = $request->TrainingFromDate;
        $to = $request->TrainingToDate;
        $query = DB::table('jf_tranprac')->where('JCId', $JCId)->delete();

        $trainingArray = array();
        for ($i = 0; $i < count($training); $i++) {
            $trainingArray[$i] = array('JCId' => $JCId, 'training' => $training[$i], 'organization' => $organization[$i], 'from' => $from[$i], 'to' => $to[$i],);
        }

        $query1 = DB::table('jf_tranprac')->insert($trainingArray);
        if (!$query1) {
            return response()->json(['status' => 400, ' msg' => 'Something went wrong..!!']);
        } else {

            return response()->json(['status' => 200, 'msg' => 'Data has been changed successfully']);
        }
    }

    public function Candidate_PreOrgRef(Request $request)
    {
        $JCId = $request->JCId;
        $query = "SELECT * FROM jf_reference WHERE JCId='$JCId' AND `from` ='Previous Organization'";
        $result = DB::select($query);
        if (!$result) {
            return response()->json(['status' => 400, 'msg' => 'No Record Found..!!']);
        } else {

            return response()->json(['status' => 200, 'data' => $result]);
        }
    }

    public function Candidate_PreOrgRef_Save(Request $request)
    {
        $JCId = $request->PreOrgRef_JCId;
        $from = 'Previous Organization';
        $name = $request->PreOrgName;
        $company = $request->PreOrgCompany;
        $designation = $request->PreOrgDesignation;
        $email = $request->PreOrgEmail;
        $contact = $request->PreOrgContact;
        $PreOrgRefArray = DB::table('jf_reference')->where('JCId', $JCId)->where('from', $from)->delete();
        $PreOrgRefArray = array();
        for ($i = 0; $i < count($name); $i++) {
            $PreOrgRefArray[$i] = array('JCId' => $JCId, 'from' => $from, 'name' => $name[$i], 'company' => $company[$i], 'designation' => $designation[$i], 'email' => $email[$i], 'contact' => $contact[$i],);
        }

        $query1 = DB::table('jf_reference')->insert($PreOrgRefArray);

        if (!$query1) {
            return response()->json(['status' => 400, 'msg' => 'Something went wrong..!!']);
        } else {

            return response()->json(['status' => 200, 'msg' => 'Data has been changed successfully']);
        }
    }

    public function Candidate_VnrRef(Request $request)
    {
        $JCId = $request->JCId;
        $query = "SELECT * FROM jf_reference WHERE JCId='$JCId' AND `from`='VNR'";
        $result = DB::select($query);
        if (!$result) {
            return response()->json(['status' => 400, 'msg' => 'No Record Found..!!']);
        } else {

            return response()->json(['status' => 200, 'data' => $result]);
        }
    }

    public function Candidate_VnrRef_Save(Request $request)
    {
        //  dd($request->all());
        $JCId = $request->Vnr_JCId;
        $from = 'VNR';
        $name = $request->VnrRefName;
        $designation = $request->VnrRefDesignation;
        $email = $request->VnrRefEmail;
        $contact = $request->VnrRefContact;
        $rel_with_person = $request->VnrRefRelWithPerson;
        $VnrRefCompany = $request->VnrRefCompany;
        $OtherCompany = $request->OtherCompany;
        $VnrRefLocation = $request->VnrRefLocation;
        $query = DB::table('jf_reference')->where('JCId', $JCId)->where('from', $from)->delete();
        $array = array();
        for ($i = 0; $i < count($name); $i++) {
            $array[$i] = array('JCId' => $JCId,

                'from' => $from, 'name' => $name[$i], 'designation' => $designation[$i], 'email' => $email[$i], 'contact' => $contact[$i], 'company' => $VnrRefCompany[$i], 'other_company' => $OtherCompany[$i], 'location' => $VnrRefLocation[$i], 'rel_with_person' => $rel_with_person[$i],);
        }

        $query1 = DB::table('jf_reference')->insert($array);


        if (!$query1) {
            return response()->json(['status' => 400, ' msg' => 'Something went wrong..!!']);
        } else {

            return response()->json(['status' => 200, 'msg' => 'Data has been changed successfully']);
        }
    }

    public function Candidate_Language(Request $request)
    {
        $JCId = $request->JCId;
        $query = "SELECT * FROM jf_language WHERE JCId='$JCId'";
        $result = DB::select($query);
        if (!$result) {
            return response()->json(['status' => 400, 'msg' => 'No Record Found..!!']);
        } else {

            return response()->json(['status' => 200, 'data' => $result]);
        }
    }

    public function Candidate_Language_Save(Request $request)
    {


        $JCId = $request->JCId;
        $languagearray = $request->language_array;


        $query = DB::table('jf_language')->where('JCId', $JCId)->delete();
        foreach ($languagearray as $key => $value) {
            $query1 = DB::table('jf_language')->insert(['JCId' => $JCId, 'language' => $value['language'], 'read' => $value['read'], 'write' => $value['write'], 'speak' => $value['speak']]);
        }
        if (!$query1) {
            return response()->json(['status' => 400, 'msg' => 'Something went wrong..!!']);
        } else {

            return response()->json(['status' => 200, 'msg' => 'Data has been changed successfully']);
        }
    }

    public function Candidate_VnrRef_Business(Request $request)
    {
        $JCId = $request->JCId;
        $query = "SELECT * FROM vnr_business_ref WHERE JCId='$JCId'";
        $result = DB::select($query);
        if (!$result) {
            return response()->json(['status' => 400, 'msg' => 'No Record Found..!!']);
        } else {

            return response()->json(['status' => 200, 'data' => $result]);
        }
    }

    public function Candidate_VnrRef_Business_Save(Request $request)
    {
        $JCId = $request->Business_JCId;
        $VnrRefBusiness_Name = $request->VnrRefBusiness_Name;
        $VnrRefBusiness_Contact = $request->VnrRefBusiness_Contact;
        $VnrRefBusiness_Email = $request->VnrRefBusiness_Email;
        $VnrRefBusinessRelation = $request->VnrRefBusinessRelation;
        $VnrRefBusiness_Location = $request->VnrRefBusiness_Location;
        $VnrRefBusiness_RelWithPerson = $request->VnrRefBusiness_RelWithPerson;
        $query = DB::table('vnr_business_ref')->where('JCId', $JCId)->delete();
        $array = array();
        for ($i = 0; $i < count($VnrRefBusiness_Name); $i++) {
            $array[$i] = array('JCId' => $JCId, 'Name' => $VnrRefBusiness_Name[$i], 'Mobile' => $VnrRefBusiness_Contact[$i], 'Email' => $VnrRefBusiness_Email[$i], 'BusinessRelation' => $VnrRefBusinessRelation[$i], 'Location' => $VnrRefBusiness_Location[$i], 'PersonRelation' => $VnrRefBusiness_RelWithPerson[$i],

            );
        }
        $query1 = DB::table('vnr_business_ref')->insert($array);

        if (!$query1) {
            return response()->json(['status' => 400, ' msg' => 'Something went wrong..!!']);
        } else {

            return response()->json(['status' => 200, 'msg' => 'Data has been changed successfully']);
        }
    }

    public function Candidate_Other_Seed_Relation(Request $request)
    {
        $JCId = $request->JCId;
        $query = "SELECT * FROM relation_other_seed_cmp WHERE JCId='$JCId'";
        $result = DB::select($query);
        if (!$result) {
            return response()->json(['status' => 400, 'msg' => 'No Record Found..!!']);
        } else {

            return response()->json(['status' => 200, 'data' => $result]);
        }
    }

    public function Candidate_Other_Seed_Relation_Save(Request $request)
    {
        $JCId = $request->OtherSeed_JCId;
        $OtherSeedName = $request->OtherSeedName;
        $OtherSeedMobile = $request->OtherSeedMobile;
        $OtherSeedEMail = $request->OtherSeedEMail;
        $OtherSeedCompany = $request->OtherSeedCompany;
        $OtherSeedDesignation = $request->OtherSeedDesignation;
        $OtherSeedLocation = $request->OtherSeedLocation;
        $OtherSeedRelation = $request->OtherSeedRelation;

        $query = DB::table('relation_other_seed_cmp')->where('JCId', $JCId)->delete();
        $array = array();
        for ($i = 0; $i < count($OtherSeedName); $i++) {
            $array[$i] = array('JCId' => $JCId, 'Name' => $OtherSeedName[$i], 'Mobile' => $OtherSeedMobile[$i], 'Email' => $OtherSeedEMail[$i], 'company_name' => $OtherSeedCompany[$i], 'Designation' => $OtherSeedDesignation[$i], 'Location' => $OtherSeedLocation[$i], 'Relation' => $OtherSeedRelation[$i],

            );
        }
        $query1 = DB::table('relation_other_seed_cmp')->insert($array);

        if (!$query1) {
            return response()->json(['status' => 400, ' msg' => 'Something went wrong..!!']);
        } else {

            return response()->json(['status' => 200, 'msg' => 'Data has been changed successfully']);
        }
    }

    public function appointment_letter()
    {
        return view('onboarding.appointment_letter');
    }

    public function appointment_ltr_print()
    {
        $jaid = $_GET['jaid'];
        $sql = DB::table('jobapply')->select('jobcandidates.Title', 'jobcandidates.FName', 'jobcandidates.MName', 'jobcandidates.LName',)->join('jobcandidates', 'jobapply.JCId', '=', 'jobcandidates.JCId')->where('jobapply.JAId', $jaid)->first();
        $candidate_name = $sql->FName . ' ' . $sql->MName . ' ' . $sql->LName;
        ini_set('memory_limit', -1);

        $pdf = new mPDF(['utf-8', 'A4-C']);

        $pdf->SetDefaultBodyCSS('font-family', 'freeserif');
        $pdf->setAutoBottomMargin = 'stretch';
        $pdf->WriteHTML('<div style="margin-bottom:35px;">&nbsp;</div>');

        $pdf->SetHTMLFooter('
                <div style="text-align: center; font-weight:bold;  height:70px;">
                <div style="float: left; width: 100%; text-align: right;"><br><br>Page {PAGENO} of {nbpg}</div>
                </div>
        ');

        $html = View::make('onboarding.appointment_ltr_print')->render();
        $pdf->SetTitle('Appointment Letter');
        $pdf->WriteHTML($html,);
        $pdf->Output('Appointment Letter.pdf', 'I');
    }


    public function appointmentGen(Request $request)
    {
        $JAId = base64_decode($request->JAId);
        $query = DB::table('appointing')->insert(['JAId' => $JAId, 'A_Date' => date('Y-m-d'), 'CreatedTime' => date('Y-m-d H:i:s'), 'CreatedBy' => Auth::user()->id]);
        if ($query) {
            return response()->json(['status' => 200, 'msg' => 'Appointment Letter Generated Successfully']);
        } else {
            return response()->json(['status' => 400, 'msg' => 'Something went wrong..!!']);
        }
    }

    public function appointment_letter_generate(Request $request)
    {
        $JAId = $request->JAId;
        $ltrno = $request->ltrno;
        $query = DB::table('appointing')->where('JAId', $JAId)->update(['AppLetterNo' => $ltrno, 'A_Date' => date('Y-m-d'), 'AppLtrGen' => 'Yes', 'LastUpdated' => date('Y-m-d H:i:s'), 'UpdatedBy' => Auth::user()->id]);
        if ($query) {
            return response()->json(['status' => 200, 'msg' => 'Appointment Letter Generated Successfully']);
        } else {
            return response()->json(['status' => 400, 'msg' => 'Something went wrong..!!']);
        }
    }

    public function service_agreement_generate(Request $request)
    {
        $JAId = $request->JAId;
        $ltrno = $request->ltrno;
        $query = DB::table('appointing')->where('JAId', $JAId)->update(['AgrLtrNo' => $ltrno, 'Agr_Date' => date('Y-m-d'), 'AgrLtrGen' => 'Yes', 'LastUpdated' => date('Y-m-d H:i:s'), 'UpdatedBy' => Auth::user()->id]);
        if ($query) {
            return response()->json(['status' => 200, 'msg' => 'Service Agreement Generated Successfully']);
        } else {
            return response()->json(['status' => 400, 'msg' => 'Something went wrong..!!']);
        }
    }

    public function service_agreement()
    {
        return view('onboarding.service_agreement');
    }

    public function service_agreement_print_e_first()
    {
        $jaid = base64_decode($_GET['jaid']);
        $sql = DB::table('jobapply')->select('jobcandidates.Title', 'jobcandidates.FName', 'jobcandidates.MName', 'jobcandidates.LName',)->join('jobcandidates', 'jobapply.JCId', '=', 'jobcandidates.JCId')->where('jobapply.JAId', $jaid)->first();
        $candidate_name = $sql->FName . ' ' . $sql->MName . ' ' . $sql->LName;
        $sql = DB::table('jobapply')->leftJoin('appointing', 'appointing.JAId', '=', 'jobapply.JAId')->leftJoin('offerletterbasic', 'offerletterbasic.JAId', '=', 'jobapply.JAId')->leftJoin('jobcandidates', 'jobapply.JCId', '=', 'jobcandidates.JCId')->leftJoin('candjoining', 'jobapply.JAId', '=', 'candjoining.JAId')->leftJoin('jf_contact_det', 'jobcandidates.JCId', '=', 'jf_contact_det.JCId')->leftJoin('jf_family_det', 'jobcandidates.JCId', '=', 'jf_family_det.JCId')->select('appointing.*', 'offerletterbasic.*', 'candjoining.JoinOnDt', 'jobcandidates.Title', 'jobcandidates.FName', 'jobcandidates.MName', 'jobcandidates.LName', 'jobcandidates.FatherTitle', 'jobcandidates.FatherName', 'jobcandidates.Gender', 'jobcandidates.Aadhaar', 'jobcandidates.Email', 'jf_contact_det.perm_address', 'jf_contact_det.perm_city', 'jf_contact_det.perm_dist', 'jf_contact_det.perm_state', 'jf_contact_det.perm_pin')->where('jobapply.JAId', $jaid)->first();

        ini_set('memory_limit', -1);
        $mpdfConfig = array('mode' => 'utf-8', 'format' => 'A4', 'margin_header' => 10,     // 30mm not pixel
            'margin_footer' => 10,     // 10mm
            'orientation' => 'P');
        $pdf = new mPDF($mpdfConfig);

        $pdf->SetDefaultBodyCSS('font-family', 'freeserif');


        $pdf->WriteHTML('<div style="margin-bottom:8px;">&nbsp;</div>');
        $html = '<div style="height:700px;"></div>
                 <div style="text-align: center; font-weight:bold; margin-top:10px; ">
                 <div style="float: left; width: 50%; text-align: left;">Ref:' . $sql->AgrLtrNo . '</div>
                 <div style="float: right; width: 50%; text-align: right;">Date: ' . date('d-m-Y', strtotime($sql->Agr_Date)) . '</div>
        </div>
        <b>
            <p style="text-align: center;font-weight:bold;">Service Agreement</p>
        </b>

        <p>For, ' . getcompany_name($sql->Company) . ',</p>
        <div style="text-align: center; font-weight:bold; margin-top:50px; height:70px;">
        <div style="float: left; width: 50%; text-align: left;">___________________<br>Authorized Signatory</div>

        <div style="float: right; width: 50%; text-align: right;">_________________<br>' . $candidate_name . '</div>
        </div>

        ';

        $pdf->WriteHTML($html);
        $pdf->Output('Service Agreement.pdf', 'I');
    }

    public function service_agreement_print()
    {
        $jaid = base64_decode($_GET['jaid']);
        $sql = DB::table('jobapply')->select('jobcandidates.Title', 'jobcandidates.FName', 'jobcandidates.MName', 'jobcandidates.LName',)->join('jobcandidates', 'jobapply.JCId', '=', 'jobcandidates.JCId')->where('jobapply.JAId', $jaid)->first();
        $candidate_name = $sql->FName . ' ' . $sql->MName . ' ' . $sql->LName;
        $sql = DB::table('jobapply')->leftJoin('appointing', 'appointing.JAId', '=', 'jobapply.JAId')->leftJoin('offerletterbasic', 'offerletterbasic.JAId', '=', 'jobapply.JAId')->leftJoin('jobcandidates', 'jobapply.JCId', '=', 'jobcandidates.JCId')->leftJoin('candjoining', 'jobapply.JAId', '=', 'candjoining.JAId')->leftJoin('jf_contact_det', 'jobcandidates.JCId', '=', 'jf_contact_det.JCId')->leftJoin('jf_family_det', 'jobcandidates.JCId', '=', 'jf_family_det.JCId')->select('appointing.*', 'offerletterbasic.*', 'candjoining.JoinOnDt', 'jobcandidates.Title', 'jobcandidates.FName', 'jobcandidates.MName', 'jobcandidates.LName', 'jobcandidates.FatherTitle', 'jobcandidates.FatherName', 'jobcandidates.Gender', 'jobcandidates.Aadhaar', 'jobcandidates.Email', 'jf_contact_det.perm_address', 'jf_contact_det.perm_city', 'jf_contact_det.perm_dist', 'jf_contact_det.perm_state', 'jf_contact_det.perm_pin')->where('jobapply.JAId', $jaid)->first();

        ini_set('memory_limit', -1);
        $mpdfConfig = array('mode' => 'utf-8', 'format' => 'Legal', 'margin_header' => 10,     // 30mm not pixel
            'margin_footer' => 10,     // 10mm
            'orientation' => 'P');
        $pdf = new mPDF($mpdfConfig);

        $pdf->SetDefaultBodyCSS('font-family', 'freeserif');
        $pdf->setAutoBottomMargin = 'stretch';
        $pdf->setAutoTopMargin = 'stretch';
        $pdf->SetHTMLFooter('
            <div style="text-align: center; font-weight:bold; margin-top:10px; height:90px;">
            <div style="float: left; width: 33%; text-align: left;">___________________<br>Authorized Signatory</div>
            <div style="float: left; width: 33%; text-align: center;"><br><br>Page {PAGENO} of {nbpg}</div>
            <div style="float: right; width: 33%; text-align: right;">_________________<br>' . $candidate_name . '</div>
            </div>

         ');


        $pdf->SetHTMLHeader('<div style="text-align: center; font-weight:bold;height:40px;">
        <div style="float: left; width: 50%; text-align: left;">Ref:' . $sql->AgrLtrNo . '</div>
        <div style="float: right; width: 50%; text-align: right;">Date: ' . date('d-m-Y', strtotime($sql->Agr_Date)) . '</div>
        </div>');

        $html = View::make('onboarding.service_agreement_print')->render();

        $pdf->WriteHTML($html);
        $pdf->Output('Service Agreement.pdf', 'I');
    }

    public function service_agreement_print_old_stamp()
    {
        $jaid = base64_decode($_GET['jaid']);
        $sql = DB::table('jobapply')->select('jobcandidates.Title', 'jobcandidates.FName', 'jobcandidates.MName', 'jobcandidates.LName',)->join('jobcandidates', 'jobapply.JCId', '=', 'jobcandidates.JCId')->where('jobapply.JAId', $jaid)->first();
        $candidate_name = $sql->FName . ' ' . $sql->MName . ' ' . $sql->LName;
        $sql = DB::table('jobapply')->leftJoin('appointing', 'appointing.JAId', '=', 'jobapply.JAId')->leftJoin('offerletterbasic', 'offerletterbasic.JAId', '=', 'jobapply.JAId')->leftJoin('jobcandidates', 'jobapply.JCId', '=', 'jobcandidates.JCId')->leftJoin('candjoining', 'jobapply.JAId', '=', 'candjoining.JAId')->leftJoin('jf_contact_det', 'jobcandidates.JCId', '=', 'jf_contact_det.JCId')->leftJoin('jf_family_det', 'jobcandidates.JCId', '=', 'jf_family_det.JCId')->select('appointing.*', 'offerletterbasic.*', 'candjoining.JoinOnDt', 'jobcandidates.Title', 'jobcandidates.FName', 'jobcandidates.MName', 'jobcandidates.LName', 'jobcandidates.FatherTitle', 'jobcandidates.FatherName', 'jobcandidates.Gender', 'jobcandidates.Aadhaar', 'jobcandidates.Email', 'jf_contact_det.perm_address', 'jf_contact_det.perm_city', 'jf_contact_det.perm_dist', 'jf_contact_det.perm_state', 'jf_contact_det.perm_pin')->where('jobapply.JAId', $jaid)->first();

        ini_set('memory_limit', -1);
        $mpdfConfig = array('mode' => 'utf-8', 'margin_header' => 10,     // 30mm not pixel
            'margin_footer' => 12,     // 10mm
            'orientation' => 'P');
        $pdf = new mPDF($mpdfConfig);

        $pdf->SetDefaultBodyCSS('font-family', 'freeserif');
        $pdf->setAutoBottomMargin = 'stretch';
        $pdf->setAutoTopMargin = 'stretch';
        $pdf->SetHTMLFooter('
            <div style="text-align: center; font-weight:bold; ">
            <div style="float: left; width: 33%; text-align: left;">___________________<br>Authorized Signatory</div>
            <div style="float: left; width: 33%; text-align: center;"><br><br>Page {PAGENO} of {nbpg}</div>
            <div style="float: right; width: 33%; text-align: right;">_________________<br>' . $candidate_name . '</div>
            </div>

        ');
        $pdf->WriteHTML('<div style="margin-bottom:8px;">&nbsp;</div>');

        $pdf->SetHTMLHeader('<div style="text-align: center; font-weight:bold;height:40px;">
        <div style="float: left; width: 50%; text-align: left;">Ref:' . $sql->AgrLtrNo . '</div>
        <div style="float: right; width: 50%; text-align: right;">Date: ' . date('d-m-Y', strtotime($sql->Agr_Date)) . '</div>
        </div>');

        $html = View::make('onboarding.service_agreement_print_old_stamp')->render();

        $pdf->WriteHTML($html);
        $pdf->Output('Service Agreement.pdf', 'I');
    }

    public function service_bond_generate(Request $request)
    {
        $JAId = $request->JAId;
        $ltrno = $request->ltrno;
        $query = DB::table('appointing')->where('JAId', $JAId)->update(['BLtrNo' => $ltrno, 'B_Date' => date('Y-m-d'), 'BLtrGen' => 'Yes', 'LastUpdated' => date('Y-m-d H:i:s'), 'UpdatedBy' => Auth::user()->id]);
        if ($query) {
            return response()->json(['status' => 200, 'msg' => 'Service Bond Generated Successfully']);
        } else {
            return response()->json(['status' => 400, 'msg' => 'Something went wrong..!!']);
        }
    }

    public function service_bond()
    {
        return view('onboarding.service_bond');
    }

    public function service_bond_print_e_first()
    {
        $jaid = base64_decode($_GET['jaid']);
        $sql = DB::table('jobapply')->select('jobcandidates.Title', 'jobcandidates.FName', 'jobcandidates.MName', 'jobcandidates.LName',)->join('jobcandidates', 'jobapply.JCId', '=', 'jobcandidates.JCId')->where('jobapply.JAId', $jaid)->first();
        $candidate_name = $sql->FName . ' ' . $sql->MName . ' ' . $sql->LName;
        $sql = DB::table('jobapply')->leftJoin('appointing', 'appointing.JAId', '=', 'jobapply.JAId')->leftJoin('offerletterbasic', 'offerletterbasic.JAId', '=', 'jobapply.JAId')->leftJoin('jobcandidates', 'jobapply.JCId', '=', 'jobcandidates.JCId')->leftJoin('candjoining', 'jobapply.JAId', '=', 'candjoining.JAId')->leftJoin('jf_contact_det', 'jobcandidates.JCId', '=', 'jf_contact_det.JCId')->leftJoin('jf_family_det', 'jobcandidates.JCId', '=', 'jf_family_det.JCId')->select('appointing.*', 'offerletterbasic.*', 'candjoining.JoinOnDt', 'jobcandidates.Title', 'jobcandidates.FName', 'jobcandidates.MName', 'jobcandidates.LName', 'jobcandidates.FatherTitle', 'jobcandidates.FatherName', 'jobcandidates.Gender', 'jobcandidates.Aadhaar', 'jobcandidates.Email', 'jf_contact_det.perm_address', 'jf_contact_det.perm_city', 'jf_contact_det.perm_dist', 'jf_contact_det.perm_state', 'jf_contact_det.perm_pin')->where('jobapply.JAId', $jaid)->first();

        ini_set('memory_limit', -1);
        $mpdfConfig = array('mode' => 'utf-8', 'format' => 'A4', 'margin_header' => 10,     // 30mm not pixel
            'margin_footer' => 10,     // 10mm
            'orientation' => 'P');
        $pdf = new mPDF($mpdfConfig);

        $pdf->SetDefaultBodyCSS('font-family', 'freeserif');


        $pdf->WriteHTML('<div style="margin-bottom:8px;">&nbsp;</div>');
        $html = '<div style="height:700px;"></div>
                 <div style="text-align: center; font-weight:bold; margin-top:10px; ">
                 <div style="float: left; width: 50%; text-align: left;">Ref:' . $sql->BLtrNo . '</div>
                 <div style="float: right; width: 50%; text-align: right;">Date: ' . date('d-m-Y', strtotime($sql->B_Date)) . '</div>
        </div>
        <b>
            <p style="text-align: center;font-weight:bold;">Service Bond (Annexure)</p>
        </b>

        <p>For, ' . getcompany_name($sql->Company) . ',</p>
        <div style="text-align: center; font-weight:bold; margin-top:50px; height:70px;">
        <div style="float: left; width: 50%; text-align: left;">___________________<br>Authorized Signatory</div>

        <div style="float: right; width: 50%; text-align: right;">_________________<br>' . $candidate_name . '</div>
        </div>

        ';

        $pdf->WriteHTML($html);
        $pdf->Output('Service Bond.pdf', 'I');
    }

    public function service_bond_print()
    {
        $jaid = base64_decode($_GET['jaid']);
        $sql = DB::table('jobapply')->select('jobcandidates.Title', 'jobcandidates.FName', 'jobcandidates.MName', 'jobcandidates.LName',)->join('jobcandidates', 'jobapply.JCId', '=', 'jobcandidates.JCId')->where('jobapply.JAId', $jaid)->first();
        $candidate_name = $sql->FName . ' ' . $sql->MName . ' ' . $sql->LName;
        $sql = DB::table('jobapply')->leftJoin('appointing', 'appointing.JAId', '=', 'jobapply.JAId')->leftJoin('offerletterbasic', 'offerletterbasic.JAId', '=', 'jobapply.JAId')->leftJoin('jobcandidates', 'jobapply.JCId', '=', 'jobcandidates.JCId')->leftJoin('candjoining', 'jobapply.JAId', '=', 'candjoining.JAId')->leftJoin('jf_contact_det', 'jobcandidates.JCId', '=', 'jf_contact_det.JCId')->leftJoin('jf_family_det', 'jobcandidates.JCId', '=', 'jf_family_det.JCId')->select('appointing.*', 'offerletterbasic.*', 'candjoining.JoinOnDt', 'jobcandidates.Title', 'jobcandidates.FName', 'jobcandidates.MName', 'jobcandidates.LName', 'jobcandidates.FatherTitle', 'jobcandidates.FatherName', 'jobcandidates.Gender', 'jobcandidates.Aadhaar', 'jobcandidates.Email', 'jf_contact_det.perm_address', 'jf_contact_det.perm_city', 'jf_contact_det.perm_dist', 'jf_contact_det.perm_state', 'jf_contact_det.perm_pin')->where('jobapply.JAId', $jaid)->first();

        ini_set('memory_limit', -1);
        $mpdfConfig = array('mode' => 'utf-8', 'format' => 'Legal', 'margin_header' => 10,     // 30mm not pixel
            'margin_footer' => 10,     // 10mm
            'orientation' => 'P');
        $pdf = new mPDF($mpdfConfig);

        $pdf->SetDefaultBodyCSS('font-family', 'freeserif');
        $pdf->setAutoBottomMargin = 'stretch';
        $pdf->setAutoTopMargin = 'stretch';

        $pdf->SetHTMLFooter('
        <div style="text-align: center; font-weight:bold; ">
        <div style="float: left; width: 33%; text-align: left;">___________________<br>Authorized Signatory</div>
        <div style="float: left; width: 33%; text-align: center;"><br><br>Page {PAGENO} of {nbpg}</div>
        <div style="float: right; width: 33%; text-align: right;">_________________<br>' . $candidate_name . '</div>
        </div>

    ');
        //  $pdf->WriteHTML('<div style="margin-bottom:8px;">&nbsp;</div>');

        $pdf->SetHTMLHeader('<div style="text-align: center; font-weight:bold;height:40px;">
    <div style="float: left; width: 50%; text-align: left;">Ref:' . $sql->BLtrNo . '</div>
    <div style="float: right; width: 50%; text-align: right;">Date: ' . date('d-m-Y', strtotime($sql->B_Date)) . '</div>
    </div>');
        $pdf->SetHTMLHeader('<div style="text-align: center; font-weight:bold;height:40px;">
        <div style="float: left; width: 50%; text-align: left;">Ref:' . $sql->BLtrNo . '</div>
        <div style="float: right; width: 50%; text-align: right;">Date: ' . date('d-m-Y', strtotime($sql->B_Date)) . '</div>
        </div>');

        $html = View::make('onboarding.service_bond_print')->render();

        $pdf->WriteHTML($html);
        $pdf->Output('Service Bond.pdf', 'I');
    }

    public function service_bond_print_old_stamp()
    {
        $jaid = base64_decode($_GET['jaid']);
        $sql = DB::table('jobapply')->select('jobcandidates.Title', 'jobcandidates.FName', 'jobcandidates.MName', 'jobcandidates.LName',)->join('jobcandidates', 'jobapply.JCId', '=', 'jobcandidates.JCId')->where('jobapply.JAId', $jaid)->first();
        $candidate_name = $sql->FName . ' ' . $sql->MName . ' ' . $sql->LName;
        $sql = DB::table('jobapply')->leftJoin('appointing', 'appointing.JAId', '=', 'jobapply.JAId')->leftJoin('offerletterbasic', 'offerletterbasic.JAId', '=', 'jobapply.JAId')->leftJoin('jobcandidates', 'jobapply.JCId', '=', 'jobcandidates.JCId')->leftJoin('candjoining', 'jobapply.JAId', '=', 'candjoining.JAId')->leftJoin('jf_contact_det', 'jobcandidates.JCId', '=', 'jf_contact_det.JCId')->leftJoin('jf_family_det', 'jobcandidates.JCId', '=', 'jf_family_det.JCId')->select('appointing.*', 'offerletterbasic.*', 'candjoining.JoinOnDt', 'jobcandidates.Title', 'jobcandidates.FName', 'jobcandidates.MName', 'jobcandidates.LName', 'jobcandidates.FatherTitle', 'jobcandidates.FatherName', 'jobcandidates.Gender', 'jobcandidates.Aadhaar', 'jobcandidates.Email', 'jf_contact_det.perm_address', 'jf_contact_det.perm_city', 'jf_contact_det.perm_dist', 'jf_contact_det.perm_state', 'jf_contact_det.perm_pin')->where('jobapply.JAId', $jaid)->first();

        ini_set('memory_limit', -1);
        $mpdfConfig = array('mode' => 'utf-8', 'margin_header' => 10,     // 30mm not pixel
            'margin_footer' => 12,     // 10mm
            'orientation' => 'P');
        $pdf = new mPDF($mpdfConfig);

        $pdf->SetDefaultBodyCSS('font-family', 'freeserif');
        $pdf->setAutoBottomMargin = 'stretch';
        $pdf->setAutoTopMargin = 'stretch';
        $pdf->SetHTMLFooter('
            <div style="text-align: center; font-weight:bold; ">
            <div style="float: left; width: 33%; text-align: left;">___________________<br>Authorized Signatory</div>
            <div style="float: left; width: 33%; text-align: center;"><br><br>Page {PAGENO} of {nbpg}</div>
            <div style="float: right; width: 33%; text-align: right;">_________________<br>' . $candidate_name . '</div>
            </div>

        ');

        $pdf->WriteHTML('<div style="margin-bottom:8px;">&nbsp;</div>');

        $pdf->SetHTMLHeader('<div style="text-align: center; font-weight:bold;height:40px;">
        <div style="float: left; width: 50%; text-align: left;">Ref:' . $sql->BLtrNo . '</div>
        <div style="float: right; width: 50%; text-align: right;">Date: ' . date('d-m-Y', strtotime($sql->B_Date)) . '</div>
        </div>');

        $html = View::make('onboarding.service_bond_print_old_stamp')->render();

        $pdf->WriteHTML($html);
        $pdf->Output('Service Bond.pdf', 'I');
    }

    public function conf_agreement_generate(Request $request)
    {
        $JAId = $request->JAId;

        $query = DB::table('appointing')->where('JAId', $JAId)->update(['ConfLtrDate' => date('Y-m-d'), 'ConfLtrGen' => 'Yes', 'LastUpdated' => date('Y-m-d H:i:s'), 'UpdatedBy' => Auth::user()->id]);
        if ($query) {
            return response()->json(['status' => 200, 'msg' => 'Confidentiality Agreement Generated Successfully']);
        } else {
            return response()->json(['status' => 400, 'msg' => 'Something went wrong..!!']);
        }
    }

    public function conf_agreement()
    {
        return view('onboarding.conf_agreement');
    }

    public function conf_agreement_print_e_first()
    {
        $jaid = base64_decode($_GET['jaid']);
        $sql = DB::table('jobapply')->select('jobcandidates.Title', 'jobcandidates.FName', 'jobcandidates.MName', 'jobcandidates.LName',)->join('jobcandidates', 'jobapply.JCId', '=', 'jobcandidates.JCId')->where('jobapply.JAId', $jaid)->first();
        $candidate_name = $sql->FName . ' ' . $sql->MName . ' ' . $sql->LName;
        $sql = DB::table('jobapply')->leftJoin('appointing', 'appointing.JAId', '=', 'jobapply.JAId')->leftJoin('offerletterbasic', 'offerletterbasic.JAId', '=', 'jobapply.JAId')->leftJoin('jobcandidates', 'jobapply.JCId', '=', 'jobcandidates.JCId')->leftJoin('candjoining', 'jobapply.JAId', '=', 'candjoining.JAId')->leftJoin('jf_contact_det', 'jobcandidates.JCId', '=', 'jf_contact_det.JCId')->leftJoin('jf_family_det', 'jobcandidates.JCId', '=', 'jf_family_det.JCId')->select('appointing.*', 'offerletterbasic.*', 'candjoining.JoinOnDt', 'jobcandidates.Title', 'jobcandidates.FName', 'jobcandidates.MName', 'jobcandidates.LName', 'jobcandidates.FatherTitle', 'jobcandidates.FatherName', 'jobcandidates.Gender', 'jobcandidates.Aadhaar', 'jobcandidates.Email', 'jf_contact_det.perm_address', 'jf_contact_det.perm_city', 'jf_contact_det.perm_dist', 'jf_contact_det.perm_state', 'jf_contact_det.perm_pin')->where('jobapply.JAId', $jaid)->first();

        ini_set('memory_limit', -1);
        $mpdfConfig = array('mode' => 'utf-8', 'format' => 'A4', 'margin_header' => 10,     // 30mm not pixel
            'margin_footer' => 10,     // 10mm
            'orientation' => 'P');
        $pdf = new mPDF($mpdfConfig);

        $pdf->SetDefaultBodyCSS('font-family', 'freeserif');


        $pdf->WriteHTML('<div style="margin-bottom:8px;">&nbsp;</div>');
        $html = '<div style="height:700px;"></div>
                 <div style="text-align: center; font-weight:bold; margin-top:10px; "></div>
        <b>
            <p style="text-align: center;font-weight:bold;">Confidentiality Agreement</p>
        </b>

        <p>For, ' . getcompany_name($sql->Company) . ',</p>
        <div style="text-align: center; font-weight:bold; margin-top:50px; height:70px;">
        <div style="float: left; width: 50%; text-align: left;">___________________<br>Authorized Signatory</div>

        <div style="float: right; width: 50%; text-align: right;">_________________<br>' . $candidate_name . '</div>
        </div>

        ';

        $pdf->WriteHTML($html);
        $pdf->Output('Confidentiality Agreement.pdf', 'I');
    }

    public function conf_agreement_print()
    {
        $jaid = base64_decode($_GET['jaid']);
        $sql = DB::table('jobapply')->select('jobcandidates.Title', 'jobcandidates.FName', 'jobcandidates.MName', 'jobcandidates.LName',)->join('jobcandidates', 'jobapply.JCId', '=', 'jobcandidates.JCId')->where('jobapply.JAId', $jaid)->first();
        $candidate_name = $sql->FName . ' ' . $sql->MName . ' ' . $sql->LName;
        ini_set('memory_limit', -1);
        $mpdfConfig = array('mode' => 'utf-8', 'format' => 'Legal', 'margin_header' => 10,     // 30mm not pixel
            'margin_footer' => 10,     // 10mm
            'orientation' => 'P');
        $pdf = new mPDF($mpdfConfig);
        $pdf->SetDefaultBodyCSS('font-family', 'freeserif');
        $pdf->setAutoBottomMargin = 'stretch';
        $pdf->setAutoTopMargin = 'stretch';
        $html = View::make('onboarding.conf_agreement_print')->render();
        $pdf->SetHTMLFooter('
                <div style="text-align: center; font-weight:bold; margin-top:10px; height:90px;">
                <div style="float: left; width: 33%; text-align: left;">___________________<br>Authorized Signatory</div>
                <div style="float: left; width: 33%; text-align: center;"><br><br>Page {PAGENO} of {nbpg}</div>
                <div style="float: right; width: 33%; text-align: right;">_________________<br>' . $candidate_name . '</div>
                </div>
        ');

        $pdf->WriteHTML($html);
        $pdf->Output('Confidentiality Agreement.pdf', 'I');

    }

    public function conf_agreement_print_old_stamp()
    {
        $jaid = base64_decode($_GET['jaid']);
        $sql = DB::table('jobapply')->select('jobcandidates.Title', 'jobcandidates.FName', 'jobcandidates.MName', 'jobcandidates.LName',)->join('jobcandidates', 'jobapply.JCId', '=', 'jobcandidates.JCId')->where('jobapply.JAId', $jaid)->first();
        $candidate_name = $sql->FName . ' ' . $sql->MName . ' ' . $sql->LName;
        $sql = DB::table('jobapply')->leftJoin('appointing', 'appointing.JAId', '=', 'jobapply.JAId')->leftJoin('offerletterbasic', 'offerletterbasic.JAId', '=', 'jobapply.JAId')->leftJoin('jobcandidates', 'jobapply.JCId', '=', 'jobcandidates.JCId')->leftJoin('candjoining', 'jobapply.JAId', '=', 'candjoining.JAId')->leftJoin('jf_contact_det', 'jobcandidates.JCId', '=', 'jf_contact_det.JCId')->leftJoin('jf_family_det', 'jobcandidates.JCId', '=', 'jf_family_det.JCId')->select('appointing.*', 'offerletterbasic.*', 'candjoining.JoinOnDt', 'jobcandidates.Title', 'jobcandidates.FName', 'jobcandidates.MName', 'jobcandidates.LName', 'jobcandidates.FatherTitle', 'jobcandidates.FatherName', 'jobcandidates.Gender', 'jobcandidates.Aadhaar', 'jobcandidates.Email', 'jf_contact_det.perm_address', 'jf_contact_det.perm_city', 'jf_contact_det.perm_dist', 'jf_contact_det.perm_state', 'jf_contact_det.perm_pin')->where('jobapply.JAId', $jaid)->first();

        ini_set('memory_limit', -1);
        $mpdfConfig = array('mode' => 'utf-8', 'margin_header' => 10,     // 30mm not pixel
            'margin_footer' => 12,     // 10mm
            'orientation' => 'P');
        $pdf = new mPDF($mpdfConfig);

        $pdf->SetDefaultBodyCSS('font-family', 'freeserif');
        $pdf->setAutoBottomMargin = 'stretch';
        $pdf->setAutoTopMargin = 'stretch';
        $pdf->SetHTMLFooter('
                <div style="text-align: center; font-weight:bold; margin-top:10px; height:90px;">
                <div style="float: left; width: 33%; text-align: left;">___________________<br>Authorized Signatory</div>
                <div style="float: left; width: 33%; text-align: center;"><br><br>Page {PAGENO} of {nbpg}</div>
                <div style="float: right; width: 33%; text-align: right;">_________________<br>' . $candidate_name . '</div>
                </div>
        ');
        $pdf->WriteHTML('<div style="margin-bottom:8px;">&nbsp;</div>');


        $html = View::make('onboarding.conf_agreement_print_old_stamp')->render();

        $pdf->WriteHTML($html);
        $pdf->Output('Service Bond.pdf', 'I');

    }

    public function send_for_ref_chk(Request $request)
    {
        $JAId = $request->ReferenceChkJAId;
        $RefMail = $request->RefChkMail;
        $query = DB::table('offerletterbasic')->where('JAId', $JAId)->update(['SendForRefChk' => 1]);
        $sql = DB::table('jobapply')->join('jobcandidates', 'jobcandidates.JCId', '=', 'jobapply.JCId')->select('jobcandidates.FName', 'jobcandidates.MName', 'jobcandidates.LName')->where('jobapply.JAId', $JAId)->first();
        if ($query) {
            $details = ["candidate_name" => $sql->FName . ' ' . $sql->MName . ' ' . $sql->LName, "subject" => "Employment Reference Check of " . $sql->FName . ' ' . $sql->MName . ' ' . $sql->LName, "form_link" => route('reference_check') . '?jaid=' . base64_encode($JAId),];
            Mail::to($RefMail)->send(new RefCheckMail($details));
            return response()->json(['status' => 200, 'msg' => 'Reference Check Mail Sent Successfully']);
        } else {
            return response()->json(['status' => 400, 'msg' => 'Something went wrong..!!']);
        }
    }

    public function reference_check()
    {
        return view('onboarding.reference_check');
    }

    public function reference_chk_response(Request $request)
    {
        $JAId = $request->JAId;
        $Company = $request->Company;
        $FromDate = $request->FromDate;
        $ToDate = $request->ToDate;
        $Designation = $request->Designation;
        $ReportMgr = $request->ReportMgr;
        $EmpType = $request->EmpType;
        $Agency = $request->Agency ?? '';
        $NetMonth = $request->NetMonth;
        $CTC = $request->CTC;
        $AbilityTeam = $request->AbilityTeam;
        $Loyal = $request->Loyal;
        $Leadership = $request->Leadership;
        $Relationship = $request->Relationship;
        $CharacterConduct = $request->CharacterConduct;
        $Strength = $request->Strength;
        $Weakness = $request->Weakness;
        $LeaveReason = $request->LeaveReason;
        $Rehire = $request->Rehire;
        $AnyOther = $request->AnyOther;
        $VerifierName = $request->VerifierName;
        $VDesig = $request->VDesig;
        $Contact = $request->Contact;
        $Email = $request->Email;
        $chk = DB::table('candidate_ref')->where('JAId', $JAId)->first();
        if ($chk == null) {

            $query = DB::table('candidate_ref')->insert(['JAId' => $JAId, 'Company' => $Company, 'FromDate' => $FromDate, 'ToDate' => $ToDate, 'Designation' => $Designation, 'ReportMgr' => $ReportMgr, 'EmpType' => $EmpType, 'Agency' => $Agency, 'NetMonth' => $NetMonth, 'CTC' => $CTC, 'AbilityTeam' => $AbilityTeam, 'Loyal' => $Loyal, 'Leadership' => $Leadership, 'Relationship' => $Relationship, 'CharacterConduct' => $CharacterConduct, 'Strength' => $Strength, 'Weakness' => $Weakness, 'LeaveReason' => $LeaveReason, 'Rehire' => $Rehire, 'AnyOther' => $AnyOther, 'VerifierName' => $VerifierName, 'VDesig' => $VDesig, 'Contact' => $Contact, 'Email' => $Email, 'CreatedTime' => now()]);
        } else {
            $query = DB::table('candidate_ref')->where('JAId', $JAId)->update(['Company' => $Company, 'FromDate' => $FromDate, 'ToDate' => $ToDate, 'Designation' => $Designation, 'ReportMgr' => $ReportMgr, 'EmpType' => $EmpType, 'Agency' => $Agency, 'NetMonth' => $NetMonth, 'CTC' => $CTC, 'AbilityTeam' => $AbilityTeam, 'Loyal' => $Loyal, 'Leadership' => $Leadership, 'Relationship' => $Relationship, 'CharacterConduct' => $CharacterConduct, 'Strength' => $Strength, 'Weakness' => $Weakness, 'LeaveReason' => $LeaveReason, 'Rehire' => $Rehire, 'AnyOther' => $AnyOther, 'VerifierName' => $VerifierName, 'VDesig' => $VDesig, 'Contact' => $Contact, 'Email' => $Email, 'CreatedTime' => now()]);
        }
        if ($query) {
            $sql = jobapply::where('JAId', $JAId)->join('jobcandidates', 'jobcandidates.JCId', '=', 'jobapply.JCId')->select('jobapply.*', 'jobcandidates.FName', 'jobcandidates.LName')->first();
            $JPId = $sql->JPId;
            $sql2 = jobpost::where('JPId', $JPId)->first();
            $Receuiter = $sql2->CreatedBy;
            UserNotification::notifyUser($Receuiter, 'Reference Check', 'Reference Check of ' . $sql->FName . ' ' . $sql->LName . ' has been completed.');
            return response()->json(['status' => 200, 'msg' => 'Reference Check Response Submitted Successfully']);
        } else {
            return response()->json(['status' => 400, 'msg' => 'Something went wrong..!!']);
        }
    }

    public function view_reference_check()
    {
        return view('onboarding.view_reference_check');
    }

    public function VerificationSave(Request $request)
    {
        $JAId = $request->JAId;
        $Verification = $request->Verification;
        $query = DB::table('candjoining')->where('JAId', $JAId)->update(['Verification' => $Verification]);
        if ($query) {
            return response()->json(['status' => 200, 'msg' => 'Verification Saved Successfully']);
        } else {
            return response()->json(['status' => 400, 'msg' => 'Something went wrong..!!']);
        }
    }

    public function JoinedSave(Request $request)
    {
        $JAId = $request->JAId;
        $Joined = $request->Joined;
        $RemarkHr = $request->RemarkHr;
        $query = DB::table('candjoining')->where('JAId', $JAId)->update(['Joined' => $Joined, 'NoJoiningRemark' => $RemarkHr]);
        if ($query) {
            return response()->json(['status' => 200, 'msg' => 'Data Saved Successfully']);
        } else {
            return response()->json(['status' => 400, 'msg' => 'Something went wrong..!!']);
        }
    }
    public function TwoWheelRCSave(Request $request)
    {
        $JAId = $request->JAId;
        $two_wheel_rc = $request->two_wheel_rc;
        $two_wheel_flat_rate = $request->two_wheel_flat_rate;
        $query = DB::table('offerletterbasic')->where('JAId', $JAId)->update(['two_wheel_rc' => $two_wheel_rc, 'two_wheel_flat_rate' => $two_wheel_flat_rate]);
        if ($query) {
            return response()->json(['status' => 200, 'msg' => 'Data Saved Successfully']);
        } else {
            return response()->json(['status' => 400, 'msg' => 'Something went wrong..!!']);
        }
    }
    public function FourWheelRCSave(Request $request)
    {
        $JAId = $request->JAId;
        $four_wheel_rc = $request->four_wheel_rc;
        $four_wheel_flat_rate = $request->four_wheel_flat_rate;
        $query = DB::table('offerletterbasic')->where('JAId', $JAId)->update(['four_wheel_rc' => $four_wheel_rc, 'four_wheel_flat_rate' => $four_wheel_flat_rate]);
        if ($query) {
            return response()->json(['status' => 200, 'msg' => 'Data Saved Successfully']);
        } else {
            return response()->json(['status' => 400, 'msg' => 'Something went wrong..!!']);
        }
    }
    public function AssignPositionCode(Request $request)
    {
        $JAId = $request->JAId;
        $PositionCode = $request->PositionCode;
        $getSeq = DB::table('position_codes')->where('position_code', $PositionCode)->select('sequence')->first();
        $getSeq = $getSeq->sequence;
        $query = DB::table('candjoining')->where('JAId', $JAId)->update(['PositionCode' => $PositionCode, 'PosSeq' => $getSeq, 'LastUpdated' => now(), 'UpdatedBy' => Auth::user()->id]);

        if ($query) {
            $sql = DB::table('position_codes')->where('position_code', $PositionCode)->update(['is_available' => 'No']);
            return response()->json(['status' => 200, 'msg' => 'Data Saved Successfully']);
        } else {
            return response()->json(['status' => 400, 'msg' => 'Something went wrong..!!']);
        }
    }

    public function process_to_ess_form()
    {
        return view('onboarding.process_to_ess_form');
    }

    public function changeOffLtrDate(Request $request)
    {
        $query = OfferLetter::where('JAId', $request->JAId)->update(['LtrDate' => $request->LtrDate]);
        if ($query) {
            return response()->json(['status' => 200, 'msg' => 'Offer Letter Date Changed Successfully']);
        } else {
            return response()->json(['status' => 400, 'msg' => 'Something went wrong..!!']);
        }
    }

    public function changeA_Date(Request $request)
    {
        $query = Appointing::where('JAId', $request->JAId)->update(['A_Date' => $request->A_Date]);
        if ($query) {
            return response()->json(['status' => 200, 'msg' => 'Appointment Letter Date Changed Successfully']);
        } else {
            return response()->json(['status' => 400, 'msg' => 'Something went wrong..!!']);
        }
    }

    public function changeAgr_Date(Request $request)
    {
        $query = Appointing::where('JAId', $request->JAId)->update(['Agr_Date' => $request->Agr_Date]);
        if ($query) {
            return response()->json(['status' => 200, 'msg' => 'Service Agreement Letter Date Changed Successfully']);
        } else {
            return response()->json(['status' => 400, 'msg' => 'Something went wrong..!!']);
        }
    }

    public function changeB_Date(Request $request)
    {
        $query = Appointing::where('JAId', $request->JAId)->update(['B_Date' => $request->B_Date]);
        if ($query) {
            return response()->json(['status' => 200, 'msg' => 'Service Bond Date Changed Successfully']);
        } else {
            return response()->json(['status' => 400, 'msg' => 'Something went wrong..!!']);
        }
    }

    public function changeConf_Date(Request $request)
    {
        $query = Appointing::where('JAId', $request->JAId)->update(['ConfLtrDate' => $request->ConfLtrDate]);
        if ($query) {
            return response()->json(['status' => 200, 'msg' => 'Confidentiality Agreement Letter Date Changed Successfully']);
        } else {
            return response()->json(['status' => 400, 'msg' => 'Something went wrong..!!']);
        }
    }


    public function open_joining_form(Request $request)
    {
        $query = jobcandidate::where('JCId', $request->JCId)->update(['FinalSubmit' => '0']);
        if ($query) {
            return response()->json(['status' => 200, 'msg' => 'Joining Form Opened Successfully']);
        } else {
            return response()->json(['status' => 400, 'msg' => 'Something went wrong..!!']);
        }
    }

    public function disable_offer_letter(Request $request)
    {
        $query = DB::table('offerletterbasic')->where('JAId', $request->JAId)->update(['disable_offer' => 'Y']);
        if ($query) {
            return response()->json(['status' => 200, 'msg' => 'OL Disabled Successfully']);
        } else {
            return response()->json(['status' => 400, 'msg' => 'Something went wrong..!!']);
        }
    }

    public function enable_offer_letter(Request $request)
    {
        $query = DB::table('offerletterbasic')->where('JAId', $request->JAId)->update(['disable_offer' => 'N']);
        if ($query) {
            return response()->json(['status' => 200, 'msg' => 'OL Enabled Successfully']);
        } else {
            return response()->json(['status' => 400, 'msg' => 'Something went wrong..!!']);
        }
    }

    public function get_mw_by_designation(Request $request)
    {
        // Get the 'DesigId' parameter from the request
        $DesigId = $request->input('DesigId');

        // Get the 'Set_Company' value from the session
        $CompanyId = session('Set_Company');

        // Query the database to retrieve the 'Category' based on 'DesigId' and 'CompanyId'
        $category = DB::table('master_grade_designation')->join('minimum_wage_category', 'minimum_wage_category.ID', '=', 'master_grade_designation.mw')->where('designation_id', $DesigId)->where('company_id', $CompanyId)->value('Category');

        // Return a JSON response with the result
        return response()->json(['status' => 200, 'category' => $category]);
    }


    public function ol_action_on_behalf_candidate(Request $request)
    {

        $JAId = $request->ol_action_jaid;
        $Status = $request->ol_action;
        $JoinOnDate = $request->ol_action_date ?? null;
        $Hr_remark = $request->ol_action_remark;

        //Update OfferLetter Basic
        $query = DB::table('offerletterbasic')->where('JAId', $JAId)->update(['Answer' => $Status, 'HR_Remark' => $Hr_remark, 'HR_Date' => date('Y-m-d'), 'LastUpdated' => now()]);
        //Update Candjoining
        $chk = DB::table('candjoining')->select('*')->where('JAId', $JAId)->count();
        if ($chk > 0) {
            $candJoin = DB::table('candjoining')->where('JAId', $JAId)->update(['LinkValidityStart' => now(), 'LinkValidityEnd' => now()->addDays(7), 'LinkStatus' => 'A', 'UpdatedBy' => Auth::user()->id, 'LastUpdated' => now()]);  //update
        } else {
            $candJoin = DB::table('candjoining')->insert(['JAId' => $JAId, 'LinkValidityStart' => now(), 'LinkValidityEnd' => now()->addDays(7), 'LinkStatus' => 'A', 'CreatedBy' => Auth::user()->id, 'CreatedTime' => now()]);  //insert
        }
        $query1 = DB::table('candjoining')->where('JAId', $JAId)->update(['Answer' => $Status, 'JoinOnDt' => $JoinOnDate, 'LastUpdated' => now()]);

        if ($Status == 'Accepted') {
            $row = DB::table('jobapply')->join('jobcandidates', 'jobcandidates.JCId', '=', 'jobapply.JCId')->join('offerletterbasic', 'offerletterbasic.JAId', '=', 'jobapply.JAId')->join('jobpost', 'jobpost.JPId', '=', 'jobapply.JPId')->select('jobcandidates.ReferenceNo', 'jobcandidates.FName', 'jobcandidates.MName', 'jobcandidates.LName', 'jobcandidates.Email', 'offerletterbasic.Designation', 'jobpost.CreatedBy')->where('jobapply.JAId', $JAId)->first();
            DB::table('event_calendar')->insert(['title' => 'Joining', 'description' => $row->FName . ' ' . $row->MName . ' ' . $row->LName . ' Will be join VNR as ' . getDesignation($row->Designation), 'start_time' => $JoinOnDate, 'end_time' => $JoinOnDate, 'belong_to' => $row->CreatedBy, 'type' => 'R'

            ]);
        }

        if ($query && $query1) {
            $sql = DB::table('jobapply')->join('jobcandidates', 'jobcandidates.JCId', '=', 'jobapply.JCId')->select('jobapply.JCId', 'Aadhaar')->where('JAId', $JAId)->first();
            CandidateActivityLog::addToCandLog($sql->JCId, $sql->Aadhaar, 'Candidate Response to Offer Letter-' . $Status);
            if ($Status == 'Rejected') {
                CandidateActivityLog::addToCandLog($sql->JCId, $sql->Aadhaar, 'Candidate Offer Letter Rejection Reason -' . $Hr_remark);
            }
            return response()->json(['status' => 200, 'msg' => 'Response Submitted Successfully']);
        } else {
            return response()->json(['status' => 400, 'msg' => 'Something went wrong..!!']);
        }
    }

    public function CandidateClosure(Request $request)
    {
        $JAId = $request->JAId;
        $Hr_Closure = $request->Hr_Closure;
        $RemarkHr = $request->RemarkHr;
        $query = DB::table('offerletterbasic')->where('JAId', $JAId)->update(['Hr_Closure' => $Hr_Closure, 'Hr_Closure_Remark' => $RemarkHr]);
        if ($query) {
            return response()->json(['status' => 200, 'msg' => 'Data Saved Successfully']);
        } else {
            return response()->json(['status' => 400, 'msg' => 'Something went wrong..!!']);
        }
    }

    public function get_candidate_suitablity(Request $request)
    {
        $JCId = $request->JCId;

        $query = jobcandidate::find($JCId);
        $data = [];
        $data['Suitable_For'] = $query->Suitable_For;
        $data['Suitable_Remark'] = $query->Suitable_Remark;
        $data['Irrelevant_Candidate'] = $query->Irrelevant_Candidate;

        return response()->json(['status' => 200, 'data' => $data]);
    }
}
