<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class ImportController extends Controller
{

  /*   public function Import()
    {
        $data = 'a:1:{i:0;s:7:"Sleeper";}';
        $test = unserialize($data);
        dd($test);
    } */
    /* public function Import()
    {
        DB::beginTransaction();
        $connection = DB::connection('mysql3');

        $getMRF = $connection->table('manpowerrequisition')->select('*')->get();
        $mrf_array = array();
        foreach ($getMRF as $key => $value) {
            $temp = array();
            $temp['MRFId'] = $value->MRFId;
            $temp['JobCode'] = $value->JobCode;
            $temp['Type'] = $value->Type;
            $temp['RepEmployeeID'] = $value->RepEmployeeID;
            $temp['Reason'] = $value->Reason ?? '';
            $temp['CompanyId'] = $value->CompanyId ?? '';
            $temp['DepartmentId'] = $value->DepartmentId ?? '';
            $temp['DesigId'] = $value->DesigId ?? '';
            $temp['GradeId'] = $value->GradeId ?? '';
            $temp['Positions'] = $value->Positions ?? '';
            $temp['CountryId'] = 1;
            $temp['LocationIds'] = $value->LocationIds ?? '';
            $temp['Reporting'] = $value->Reporting ?? '';
            $temp['ExistCTC'] = $value->ExistCTC ?? '';
            $temp['MinCTC'] = $value->MinCTC ?? '';
            $temp['MaxCTC'] = $value->MaxCTC ?? '';
            $temp['Stipend'] = null;
            $temp['TwoWheeler'] = null;
            $temp['DA'] = null;
            $temp['WorkExp'] = $value->WorkExp ?? '';
            $temp['Tr_Frm_Date'] = null;
            $temp['Tr_To_Date'] = null;
            $temp['Remarks'] = $value->Remarks ?? '';
            $temp['Info'] = $value->Info ?? '';
            $temp['EducationId'] = $value->EducationId ?? '';
            $temp['EducationInsId'] = $value->EducationInsId ?? '';
            $temp['KeyPositionCriteria'] = $value->KeyPositionCriteria ?? '';
            $temp['Status'] = $value->Status ?? '';
            $temp['RemarkHr'] = $value->RemarkHr ?? '';
            $temp['Allocated'] = $value->Allocated ?? '';
            $temp['AllocatedDt'] = $value->AllocatedDt ?? '';
            $temp['CloseDt'] = $value->CloseDt ?? '';
            $temp['CloseReason'] = null;
            $temp['Hired'] = null;
            $temp['OnBehalf'] = $value->OnBehalf ?? '';
            $temp['CreatedTime'] = $value->CreatedTime ?? '';
            $temp['CreatedBy'] = $value->CreatedBy ?? '';
            $temp['LastUpdated'] = $value->LastUpdated ?? '';
            $temp['UpdatedBy'] = $value->UpdatedBy ?? '';

            $mrf_array[] = $temp;
        }
        $importMRF = DB::table('manpowerrequisition')->insert($mrf_array);

        $getJobPost = $connection->table('jobpost')->select('*')->get();
        $jobpost_array = array();
        foreach ($getJobPost as $key => $value) {
            $temp = array();
            $temp['JPId'] = $value->JPId;
            $temp['MRFId'] = $value->MRFId;
            $temp['CompanyId'] = $value->CompanyId;
            $temp['DepartmentId'] = $value->DepartmentId;
            $temp['DesigId'] = $value->DesigId;
            $temp['JobCode'] = $value->JobCode;
            $temp['Title'] = $value->Title;
            $temp['ReqQualification'] = $value->ReqQualification;
            $temp['Description'] = $value->Description;
            $temp['PayPackage'] = $value->PayPackage;
            $temp['State'] = $value->State;
            $temp['Location'] = $value->Location;
            $temp['KeyPositionCriteria'] = $value->KeyPositionCriteria;
            $temp['PostingView'] = $value->PostingView;
            $temp['Status'] = $value->Status;
            $temp['JobPostType'] = ($value->JobPostType == 'Campus') ? 'Campus' : 'Regular';
            $temp['LastDate'] = $value->LastDate;
            $temp['CreatedTime'] = $value->CreatedTime;
            $temp['CreatedBy'] = $value->CreatedBy;
            $temp['LastUpdated'] = $value->LastUpdated;
            $temp['UpdatedBy'] = $value->UpdatedBy;
            $jobpost_array[] = $temp;
        }
        $importJobPost = DB::table('jobpost')->insert($jobpost_array);



        $getCandidates = $connection->table('jobcandidates')->select('*')->skip(0)->take(500)->get();
        $candidates_array = array();
        foreach ($getCandidates as $key => $value) {
            $temp = array();
            $temp['JCId'] = $value->JCId;
            $temp['ReferenceNo'] = $value->RollNo ?? '';
            $temp['Title'] = $value->NameTitle;
            $temp['FName'] = $value->FName;
            $temp['MName'] = $value->MName;
            $temp['LName'] = $value->LName;
            $temp['DOB'] = $value->DOB;
            $temp['Gender'] = $value->Gender;
            $temp['FatherTitle'] = 'Mr.';
            $temp['FatherName'] = $value->FatherName;
            $temp['Email'] = $value->Email1;
            $temp['Phone'] = $value->Contact1;
            $temp['Email2'] = $value->Email2;
            $temp['Phone2'] = $value->Contact2;
            $temp['AddressLine1'] = $value->Address;
            $temp['AddressLine2'] = '';
            $temp['AddressLine3'] = '';
            $temp['State'] = $value->State;
            $temp['District'] = $value->Dist;
            $temp['City'] = $value->City;
            $temp['PinCode'] = $value->PinCode;
            $temp['Aadhaar'] = $value->Aadhar;
            $temp['Education'] = null;
            $temp['Specialization'] = null;
            $temp['CGPA'] = null;
            $temp['PassingYear'] = null;
            $temp['College'] = null;
            $temp['OtherCollege'] = null;
            $temp['StudentId'] = null;
            $temp['Nationality'] = 1;
            $temp['Religion'] = null;
            $temp['OtherReligion'] = null;
            $temp['Caste'] = $value->Caste ?? null;
            $temp['OtherCaste'] = $value->OtherCaste ?? null;
            $temp['MaritalStatus'] = $value->MaritalStatus ?? null;
            $temp['MarriageDate'] = $value->marriage_dt ?? null;
            $temp['SpouseName'] = null;
            $temp['Professional'] = $value->ProfessOrFresher;
            $temp['PresentCompany'] = $value->CurCompany;
            $temp['Designation'] = $value->CurDesignation;
            $temp['JobStartDate'] = $value->JobStartDate;
            $temp['JobEndDate'] = $value->JobEndDate;
            $temp['StillEmp'] = $value->StillEmployed;
            $temp['GrossSalary'] = $value->GMonthlySalary;
            $temp['CTC'] = $value->AnnualCTC;
            $temp['NoticePeriod'] = null;
            $temp['ResignReason'] = null;
            $temp['Reporting'] = null;
            $temp['RepDesig'] = null;
            $temp['JobResponsibility'] = null;
            $temp['DAHq'] = null;
            $temp['DAOutHq'] = null;
            $temp['PetrolAlw'] = null;
            $temp['PhoneAlw'] = null;
            $temp['HotelElg'] = null;
            $temp['Medical'] = null;
            $temp['GrpTermIns'] = null;
            $temp['GrpPersonalAccIns'] = null;
            $temp['MobileHandset'] = null;
            $temp['MobileBill'] = null;
            $temp['TravelElg'] = null;
            $temp['ExpectedCTC'] = $value->ExpCTC;
            $temp['TotalYear'] = null;
            $temp['TotalMonth'] = null;
            $temp['Reference'] = null;
            $temp['RefPerson'] = null;
            $temp['RefCompany'] = null;
            $temp['RefDesignation'] = null;
            $temp['RefContact'] = null;
            $temp['RefMail'] = null;
            $temp['Resume'] = null;
            $temp['CandidateImage'] = $value->CandidateImg;
            $temp['EmailOTP'] = $value->EmailOTP;
            $temp['SmsOTP'] = $value->SMSOTP;
            $temp['Verified'] = $value->Verified;
            $temp['PlacementDate'] = null;
            $temp['BlackList'] = 0;
            $temp['BlackListRemark'] = null;
            $temp['UnBlockRemark'] = null;
            $temp['FIROB_Test'] = 0;
            $temp['InterviewSubmit'] = $value->InterviewSubmit;
            $temp['FinalSubmit'] = $value->FinalSubmit;
            $temp['VNR_Acq'] = 'N';
            $temp['VNR_Acq_Business'] = 'N';
            $temp['OtherSeedRelation'] = 'N';
            $temp['CreatedTime'] = $value->CreatedTime;
            $temp['LastUpdated'] = $value->LastUpdated;
            $temp['UpdatedBy'] = $value->UpdatedBy;
            $candidates_array[] = $temp;
        }
        $importCandidates = DB::table('jobcandidates')->insert($candidates_array);


        $getCandidates = $connection->table('jobcandidates')->select('*')->skip(500)->take(500)->get();
        $candidates_array = array();
        foreach ($getCandidates as $key => $value) {
            $temp = array();
            $temp['JCId'] = $value->JCId;
            $temp['ReferenceNo'] = $value->RollNo ?? '';
            $temp['Title'] = $value->NameTitle;
            $temp['FName'] = $value->FName;
            $temp['MName'] = $value->MName;
            $temp['LName'] = $value->LName;
            $temp['DOB'] = $value->DOB;
            $temp['Gender'] = $value->Gender;
            $temp['FatherTitle'] = 'Mr.';
            $temp['FatherName'] = $value->FatherName;
            $temp['Email'] = $value->Email1;
            $temp['Phone'] = $value->Contact1;
            $temp['Email2'] = $value->Email2;
            $temp['Phone2'] = $value->Contact2;
            $temp['AddressLine1'] = $value->Address;
            $temp['AddressLine2'] = '';
            $temp['AddressLine3'] = '';
            $temp['State'] = $value->State;
            $temp['District'] = $value->Dist;
            $temp['City'] = $value->City;
            $temp['PinCode'] = $value->PinCode;
            $temp['Aadhaar'] = $value->Aadhar;
            $temp['Education'] = null;
            $temp['Specialization'] = null;
            $temp['CGPA'] = null;
            $temp['PassingYear'] = null;
            $temp['College'] = null;
            $temp['OtherCollege'] = null;
            $temp['StudentId'] = null;
            $temp['Nationality'] = 1;
            $temp['Religion'] = null;
            $temp['OtherReligion'] = null;
            $temp['Caste'] = $value->Caste ?? null;
            $temp['OtherCaste'] = $value->OtherCaste ?? null;
            $temp['MaritalStatus'] = $value->MaritalStatus ?? null;
            $temp['MarriageDate'] = $value->marriage_dt ?? null;
            $temp['SpouseName'] = null;
            $temp['Professional'] = $value->ProfessOrFresher;
            $temp['PresentCompany'] = $value->CurCompany;
            $temp['Designation'] = $value->CurDesignation;
            $temp['JobStartDate'] = $value->JobStartDate;
            $temp['JobEndDate'] = $value->JobEndDate;
            $temp['StillEmp'] = $value->StillEmployed;
            $temp['GrossSalary'] = $value->GMonthlySalary;
            $temp['CTC'] = $value->AnnualCTC;
            $temp['NoticePeriod'] = null;
            $temp['ResignReason'] = null;
            $temp['Reporting'] = null;
            $temp['RepDesig'] = null;
            $temp['JobResponsibility'] = null;
            $temp['DAHq'] = null;
            $temp['DAOutHq'] = null;
            $temp['PetrolAlw'] = null;
            $temp['PhoneAlw'] = null;
            $temp['HotelElg'] = null;
            $temp['Medical'] = null;
            $temp['GrpTermIns'] = null;
            $temp['GrpPersonalAccIns'] = null;
            $temp['MobileHandset'] = null;
            $temp['MobileBill'] = null;
            $temp['TravelElg'] = null;
            $temp['ExpectedCTC'] = $value->ExpCTC;
            $temp['TotalYear'] = null;
            $temp['TotalMonth'] = null;
            $temp['Reference'] = null;
            $temp['RefPerson'] = null;
            $temp['RefCompany'] = null;
            $temp['RefDesignation'] = null;
            $temp['RefContact'] = null;
            $temp['RefMail'] = null;
            $temp['Resume'] = null;
            $temp['CandidateImage'] = $value->CandidateImg;
            $temp['EmailOTP'] = $value->EmailOTP;
            $temp['SmsOTP'] = $value->SMSOTP;
            $temp['Verified'] = $value->Verified;
            $temp['PlacementDate'] = null;
            $temp['BlackList'] = 0;
            $temp['BlackListRemark'] = null;
            $temp['UnBlockRemark'] = null;
            $temp['FIROB_Test'] = 0;
            $temp['InterviewSubmit'] = $value->InterviewSubmit;
            $temp['FinalSubmit'] = $value->FinalSubmit;
            $temp['VNR_Acq'] = 'N';
            $temp['VNR_Acq_Business'] = 'N';
            $temp['OtherSeedRelation'] = 'N';
            $temp['CreatedTime'] = $value->CreatedTime;
            $temp['LastUpdated'] = $value->LastUpdated;
            $temp['UpdatedBy'] = $value->UpdatedBy;

            $candidates_array[] = $temp;
        }
        $importCandidates = DB::table('jobcandidates')->insert($candidates_array);

        $getCandidates = $connection->table('jobcandidates')->select('*')->skip(1000)->take(500)->get();
        $candidates_array = array();
        foreach ($getCandidates as $key => $value) {
            $temp = array();
            $temp['JCId'] = $value->JCId;
            $temp['ReferenceNo'] = $value->RollNo ?? '';
            $temp['Title'] = $value->NameTitle;
            $temp['FName'] = $value->FName;
            $temp['MName'] = $value->MName;
            $temp['LName'] = $value->LName;
            $temp['DOB'] = $value->DOB;
            $temp['Gender'] = $value->Gender;
            $temp['FatherTitle'] = 'Mr.';
            $temp['FatherName'] = $value->FatherName;
            $temp['Email'] = $value->Email1;
            $temp['Phone'] = $value->Contact1;
            $temp['Email2'] = $value->Email2;
            $temp['Phone2'] = $value->Contact2;
            $temp['AddressLine1'] = $value->Address;
            $temp['AddressLine2'] = '';
            $temp['AddressLine3'] = '';
            $temp['State'] = $value->State;
            $temp['District'] = $value->Dist;
            $temp['City'] = $value->City;
            $temp['PinCode'] = $value->PinCode;
            $temp['Aadhaar'] = $value->Aadhar;
            $temp['Education'] = null;
            $temp['Specialization'] = null;
            $temp['CGPA'] = null;
            $temp['PassingYear'] = null;
            $temp['College'] = null;
            $temp['OtherCollege'] = null;
            $temp['StudentId'] = null;
            $temp['Nationality'] = 1;
            $temp['Religion'] = null;
            $temp['OtherReligion'] = null;
            $temp['Caste'] = $value->Caste ?? null;
            $temp['OtherCaste'] = $value->OtherCaste ?? null;
            $temp['MaritalStatus'] = $value->MaritalStatus ?? null;
            $temp['MarriageDate'] = $value->marriage_dt ?? null;
            $temp['SpouseName'] = null;
            $temp['Professional'] = $value->ProfessOrFresher;
            $temp['PresentCompany'] = $value->CurCompany;
            $temp['Designation'] = $value->CurDesignation;
            $temp['JobStartDate'] = $value->JobStartDate;
            $temp['JobEndDate'] = $value->JobEndDate;
            $temp['StillEmp'] = $value->StillEmployed;
            $temp['GrossSalary'] = $value->GMonthlySalary;
            $temp['CTC'] = $value->AnnualCTC;
            $temp['NoticePeriod'] = null;
            $temp['ResignReason'] = null;
            $temp['Reporting'] = null;
            $temp['RepDesig'] = null;
            $temp['JobResponsibility'] = null;
            $temp['DAHq'] = null;
            $temp['DAOutHq'] = null;
            $temp['PetrolAlw'] = null;
            $temp['PhoneAlw'] = null;
            $temp['HotelElg'] = null;
            $temp['Medical'] = null;
            $temp['GrpTermIns'] = null;
            $temp['GrpPersonalAccIns'] = null;
            $temp['MobileHandset'] = null;
            $temp['MobileBill'] = null;
            $temp['TravelElg'] = null;
            $temp['ExpectedCTC'] = $value->ExpCTC;
            $temp['TotalYear'] = null;
            $temp['TotalMonth'] = null;
            $temp['Reference'] = null;
            $temp['RefPerson'] = null;
            $temp['RefCompany'] = null;
            $temp['RefDesignation'] = null;
            $temp['RefContact'] = null;
            $temp['RefMail'] = null;
            $temp['Resume'] = null;
            $temp['CandidateImage'] = $value->CandidateImg;
            $temp['EmailOTP'] = $value->EmailOTP;
            $temp['SmsOTP'] = $value->SMSOTP;
            $temp['Verified'] = $value->Verified;
            $temp['PlacementDate'] = null;
            $temp['BlackList'] = 0;
            $temp['BlackListRemark'] = null;
            $temp['UnBlockRemark'] = null;
            $temp['FIROB_Test'] = 0;
            $temp['InterviewSubmit'] = $value->InterviewSubmit;
            $temp['FinalSubmit'] = $value->FinalSubmit;
            $temp['VNR_Acq'] = 'N';
            $temp['VNR_Acq_Business'] = 'N';
            $temp['OtherSeedRelation'] = 'N';
            $temp['CreatedTime'] = $value->CreatedTime;
            $temp['LastUpdated'] = $value->LastUpdated;
            $temp['UpdatedBy'] = $value->UpdatedBy;

            $candidates_array[] = $temp;
        }
        $importCandidates = DB::table('jobcandidates')->insert($candidates_array);

        $getCandidates = $connection->table('jobcandidates')->select('*')->skip(1500)->take(500)->get();
        $candidates_array = array();
        foreach ($getCandidates as $key => $value) {
            $temp = array();
            $temp['JCId'] = $value->JCId;
            $temp['ReferenceNo'] = $value->RollNo ?? '';
            $temp['Title'] = $value->NameTitle;
            $temp['FName'] = $value->FName;
            $temp['MName'] = $value->MName;
            $temp['LName'] = $value->LName;
            $temp['DOB'] = $value->DOB;
            $temp['Gender'] = $value->Gender;
            $temp['FatherTitle'] = 'Mr.';
            $temp['FatherName'] = $value->FatherName;
            $temp['Email'] = $value->Email1;
            $temp['Phone'] = $value->Contact1;
            $temp['Email2'] = $value->Email2;
            $temp['Phone2'] = $value->Contact2;
            $temp['AddressLine1'] = $value->Address;
            $temp['AddressLine2'] = '';
            $temp['AddressLine3'] = '';
            $temp['State'] = $value->State;
            $temp['District'] = $value->Dist;
            $temp['City'] = $value->City;
            $temp['PinCode'] = $value->PinCode;
            $temp['Aadhaar'] = $value->Aadhar;
            $temp['Education'] = null;
            $temp['Specialization'] = null;
            $temp['CGPA'] = null;
            $temp['PassingYear'] = null;
            $temp['College'] = null;
            $temp['OtherCollege'] = null;
            $temp['StudentId'] = null;
            $temp['Nationality'] = 1;
            $temp['Religion'] = null;
            $temp['OtherReligion'] = null;
            $temp['Caste'] = $value->Caste ?? null;
            $temp['OtherCaste'] = $value->OtherCaste ?? null;
            $temp['MaritalStatus'] = $value->MaritalStatus ?? null;
            $temp['MarriageDate'] = $value->marriage_dt ?? null;
            $temp['SpouseName'] = null;
            $temp['Professional'] = $value->ProfessOrFresher;
            $temp['PresentCompany'] = $value->CurCompany;
            $temp['Designation'] = $value->CurDesignation;
            $temp['JobStartDate'] = $value->JobStartDate;
            $temp['JobEndDate'] = $value->JobEndDate;
            $temp['StillEmp'] = $value->StillEmployed;
            $temp['GrossSalary'] = $value->GMonthlySalary;
            $temp['CTC'] = $value->AnnualCTC;
            $temp['NoticePeriod'] = null;
            $temp['ResignReason'] = null;
            $temp['Reporting'] = null;
            $temp['RepDesig'] = null;
            $temp['JobResponsibility'] = null;
            $temp['DAHq'] = null;
            $temp['DAOutHq'] = null;
            $temp['PetrolAlw'] = null;
            $temp['PhoneAlw'] = null;
            $temp['HotelElg'] = null;
            $temp['Medical'] = null;
            $temp['GrpTermIns'] = null;
            $temp['GrpPersonalAccIns'] = null;
            $temp['MobileHandset'] = null;
            $temp['MobileBill'] = null;
            $temp['TravelElg'] = null;
            $temp['ExpectedCTC'] = $value->ExpCTC;
            $temp['TotalYear'] = null;
            $temp['TotalMonth'] = null;
            $temp['Reference'] = null;
            $temp['RefPerson'] = null;
            $temp['RefCompany'] = null;
            $temp['RefDesignation'] = null;
            $temp['RefContact'] = null;
            $temp['RefMail'] = null;
            $temp['Resume'] = null;
            $temp['CandidateImage'] = $value->CandidateImg;
            $temp['EmailOTP'] = $value->EmailOTP;
            $temp['SmsOTP'] = $value->SMSOTP;
            $temp['Verified'] = $value->Verified;
            $temp['PlacementDate'] = null;
            $temp['BlackList'] = 0;
            $temp['BlackListRemark'] = null;
            $temp['UnBlockRemark'] = null;
            $temp['FIROB_Test'] = 0;
            $temp['InterviewSubmit'] = $value->InterviewSubmit;
            $temp['FinalSubmit'] = $value->FinalSubmit;
            $temp['VNR_Acq'] = 'N';
            $temp['VNR_Acq_Business'] = 'N';
            $temp['OtherSeedRelation'] = 'N';
            $temp['CreatedTime'] = $value->CreatedTime;
            $temp['LastUpdated'] = $value->LastUpdated;
            $temp['UpdatedBy'] = $value->UpdatedBy;

            $candidates_array[] = $temp;
        }
        $importCandidates = DB::table('jobcandidates')->insert($candidates_array);

        $getCandidates = $connection->table('jobcandidates')->select('*')->skip(2000)->take(500)->get();
        $candidates_array = array();
        foreach ($getCandidates as $key => $value) {
            $temp = array();
            $temp['JCId'] = $value->JCId;
            $temp['ReferenceNo'] = $value->RollNo ?? '';
            $temp['Title'] = $value->NameTitle;
            $temp['FName'] = $value->FName;
            $temp['MName'] = $value->MName;
            $temp['LName'] = $value->LName;
            $temp['DOB'] = $value->DOB;
            $temp['Gender'] = $value->Gender;
            $temp['FatherTitle'] = 'Mr.';
            $temp['FatherName'] = $value->FatherName;
            $temp['Email'] = $value->Email1;
            $temp['Phone'] = $value->Contact1;
            $temp['Email2'] = $value->Email2;
            $temp['Phone2'] = $value->Contact2;
            $temp['AddressLine1'] = $value->Address;
            $temp['AddressLine2'] = '';
            $temp['AddressLine3'] = '';
            $temp['State'] = $value->State;
            $temp['District'] = $value->Dist;
            $temp['City'] = $value->City;
            $temp['PinCode'] = $value->PinCode;
            $temp['Aadhaar'] = $value->Aadhar;
            $temp['Education'] = null;
            $temp['Specialization'] = null;
            $temp['CGPA'] = null;
            $temp['PassingYear'] = null;
            $temp['College'] = null;
            $temp['OtherCollege'] = null;
            $temp['StudentId'] = null;
            $temp['Nationality'] = 1;
            $temp['Religion'] = null;
            $temp['OtherReligion'] = null;
            $temp['Caste'] = $value->Caste ?? null;
            $temp['OtherCaste'] = $value->OtherCaste ?? null;
            $temp['MaritalStatus'] = $value->MaritalStatus ?? null;
            $temp['MarriageDate'] = $value->marriage_dt ?? null;
            $temp['SpouseName'] = null;
            $temp['Professional'] = $value->ProfessOrFresher;
            $temp['PresentCompany'] = $value->CurCompany;
            $temp['Designation'] = $value->CurDesignation;
            $temp['JobStartDate'] = $value->JobStartDate;
            $temp['JobEndDate'] = $value->JobEndDate;
            $temp['StillEmp'] = $value->StillEmployed;
            $temp['GrossSalary'] = $value->GMonthlySalary;
            $temp['CTC'] = $value->AnnualCTC;
            $temp['NoticePeriod'] = null;
            $temp['ResignReason'] = null;
            $temp['Reporting'] = null;
            $temp['RepDesig'] = null;
            $temp['JobResponsibility'] = null;
            $temp['DAHq'] = null;
            $temp['DAOutHq'] = null;
            $temp['PetrolAlw'] = null;
            $temp['PhoneAlw'] = null;
            $temp['HotelElg'] = null;
            $temp['Medical'] = null;
            $temp['GrpTermIns'] = null;
            $temp['GrpPersonalAccIns'] = null;
            $temp['MobileHandset'] = null;
            $temp['MobileBill'] = null;
            $temp['TravelElg'] = null;
            $temp['ExpectedCTC'] = $value->ExpCTC;
            $temp['TotalYear'] = null;
            $temp['TotalMonth'] = null;
            $temp['Reference'] = null;
            $temp['RefPerson'] = null;
            $temp['RefCompany'] = null;
            $temp['RefDesignation'] = null;
            $temp['RefContact'] = null;
            $temp['RefMail'] = null;
            $temp['Resume'] = null;
            $temp['CandidateImage'] = $value->CandidateImg;
            $temp['EmailOTP'] = $value->EmailOTP;
            $temp['SmsOTP'] = $value->SMSOTP;
            $temp['Verified'] = $value->Verified;
            $temp['PlacementDate'] = null;
            $temp['BlackList'] = 0;
            $temp['BlackListRemark'] = null;
            $temp['UnBlockRemark'] = null;
            $temp['FIROB_Test'] = 0;
            $temp['InterviewSubmit'] = $value->InterviewSubmit;
            $temp['FinalSubmit'] = $value->FinalSubmit;
            $temp['VNR_Acq'] = 'N';
            $temp['VNR_Acq_Business'] = 'N';
            $temp['OtherSeedRelation'] = 'N';
            $temp['CreatedTime'] = $value->CreatedTime;
            $temp['LastUpdated'] = $value->LastUpdated;
            $temp['UpdatedBy'] = $value->UpdatedBy;

            $candidates_array[] = $temp;
        }
        $importCandidates = DB::table('jobcandidates')->insert($candidates_array);

        $getCandidates = $connection->table('jobcandidates')->select('*')->skip(2500)->take(500)->get();
        $candidates_array = array();
        foreach ($getCandidates as $key => $value) {
            $temp = array();
            $temp['JCId'] = $value->JCId;
            $temp['ReferenceNo'] = $value->RollNo ?? '';
            $temp['Title'] = $value->NameTitle;
            $temp['FName'] = $value->FName;
            $temp['MName'] = $value->MName;
            $temp['LName'] = $value->LName;
            $temp['DOB'] = $value->DOB;
            $temp['Gender'] = $value->Gender;
            $temp['FatherTitle'] = 'Mr.';
            $temp['FatherName'] = $value->FatherName;
            $temp['Email'] = $value->Email1;
            $temp['Phone'] = $value->Contact1;
            $temp['Email2'] = $value->Email2;
            $temp['Phone2'] = $value->Contact2;
            $temp['AddressLine1'] = $value->Address;
            $temp['AddressLine2'] = '';
            $temp['AddressLine3'] = '';
            $temp['State'] = $value->State;
            $temp['District'] = $value->Dist;
            $temp['City'] = $value->City;
            $temp['PinCode'] = $value->PinCode;
            $temp['Aadhaar'] = $value->Aadhar;
            $temp['Education'] = null;
            $temp['Specialization'] = null;
            $temp['CGPA'] = null;
            $temp['PassingYear'] = null;
            $temp['College'] = null;
            $temp['OtherCollege'] = null;
            $temp['StudentId'] = null;
            $temp['Nationality'] = 1;
            $temp['Religion'] = null;
            $temp['OtherReligion'] = null;
            $temp['Caste'] = $value->Caste ?? null;
            $temp['OtherCaste'] = $value->OtherCaste ?? null;
            $temp['MaritalStatus'] = $value->MaritalStatus ?? null;
            $temp['MarriageDate'] = $value->marriage_dt ?? null;
            $temp['SpouseName'] = null;
            $temp['Professional'] = $value->ProfessOrFresher;
            $temp['PresentCompany'] = $value->CurCompany;
            $temp['Designation'] = $value->CurDesignation;
            $temp['JobStartDate'] = $value->JobStartDate;
            $temp['JobEndDate'] = $value->JobEndDate;
            $temp['StillEmp'] = $value->StillEmployed;
            $temp['GrossSalary'] = $value->GMonthlySalary;
            $temp['CTC'] = $value->AnnualCTC;
            $temp['NoticePeriod'] = null;
            $temp['ResignReason'] = null;
            $temp['Reporting'] = null;
            $temp['RepDesig'] = null;
            $temp['JobResponsibility'] = null;
            $temp['DAHq'] = null;
            $temp['DAOutHq'] = null;
            $temp['PetrolAlw'] = null;
            $temp['PhoneAlw'] = null;
            $temp['HotelElg'] = null;
            $temp['Medical'] = null;
            $temp['GrpTermIns'] = null;
            $temp['GrpPersonalAccIns'] = null;
            $temp['MobileHandset'] = null;
            $temp['MobileBill'] = null;
            $temp['TravelElg'] = null;
            $temp['ExpectedCTC'] = $value->ExpCTC;
            $temp['TotalYear'] = null;
            $temp['TotalMonth'] = null;
            $temp['Reference'] = null;
            $temp['RefPerson'] = null;
            $temp['RefCompany'] = null;
            $temp['RefDesignation'] = null;
            $temp['RefContact'] = null;
            $temp['RefMail'] = null;
            $temp['Resume'] = null;
            $temp['CandidateImage'] = $value->CandidateImg;
            $temp['EmailOTP'] = $value->EmailOTP;
            $temp['SmsOTP'] = $value->SMSOTP;
            $temp['Verified'] = $value->Verified;
            $temp['PlacementDate'] = null;
            $temp['BlackList'] = 0;
            $temp['BlackListRemark'] = null;
            $temp['UnBlockRemark'] = null;
            $temp['FIROB_Test'] = 0;
            $temp['InterviewSubmit'] = $value->InterviewSubmit;
            $temp['FinalSubmit'] = $value->FinalSubmit;
            $temp['VNR_Acq'] = 'N';
            $temp['VNR_Acq_Business'] = 'N';
            $temp['OtherSeedRelation'] = 'N';
            $temp['CreatedTime'] = $value->CreatedTime;
            $temp['LastUpdated'] = $value->LastUpdated;
            $temp['UpdatedBy'] = $value->UpdatedBy;

            $candidates_array[] = $temp;
        }

        $importCandidates = DB::table('jobcandidates')->insert($candidates_array);

        $getCandidates = $connection->table('jobcandidates')->select('*')->skip(3000)->take(500)->get();
        $candidates_array = array();
        foreach ($getCandidates as $key => $value) {
            $temp = array();
            $temp['JCId'] = $value->JCId;
            $temp['ReferenceNo'] = $value->RollNo ?? '';
            $temp['Title'] = $value->NameTitle;
            $temp['FName'] = $value->FName;
            $temp['MName'] = $value->MName;
            $temp['LName'] = $value->LName;
            $temp['DOB'] = $value->DOB;
            $temp['Gender'] = $value->Gender;
            $temp['FatherTitle'] = 'Mr.';
            $temp['FatherName'] = $value->FatherName;
            $temp['Email'] = $value->Email1;
            $temp['Phone'] = $value->Contact1;
            $temp['Email2'] = $value->Email2;
            $temp['Phone2'] = $value->Contact2;
            $temp['AddressLine1'] = $value->Address;
            $temp['AddressLine2'] = '';
            $temp['AddressLine3'] = '';
            $temp['State'] = $value->State;
            $temp['District'] = $value->Dist;
            $temp['City'] = $value->City;
            $temp['PinCode'] = $value->PinCode;
            $temp['Aadhaar'] = $value->Aadhar;
            $temp['Education'] = null;
            $temp['Specialization'] = null;
            $temp['CGPA'] = null;
            $temp['PassingYear'] = null;
            $temp['College'] = null;
            $temp['OtherCollege'] = null;
            $temp['StudentId'] = null;
            $temp['Nationality'] = 1;
            $temp['Religion'] = null;
            $temp['OtherReligion'] = null;
            $temp['Caste'] = $value->Caste ?? null;
            $temp['OtherCaste'] = $value->OtherCaste ?? null;
            $temp['MaritalStatus'] = $value->MaritalStatus ?? null;
            $temp['MarriageDate'] = $value->marriage_dt ?? null;
            $temp['SpouseName'] = null;
            $temp['Professional'] = $value->ProfessOrFresher;
            $temp['PresentCompany'] = $value->CurCompany;
            $temp['Designation'] = $value->CurDesignation;
            $temp['JobStartDate'] = $value->JobStartDate;
            $temp['JobEndDate'] = $value->JobEndDate;
            $temp['StillEmp'] = $value->StillEmployed;
            $temp['GrossSalary'] = $value->GMonthlySalary;
            $temp['CTC'] = $value->AnnualCTC;
            $temp['NoticePeriod'] = null;
            $temp['ResignReason'] = null;
            $temp['Reporting'] = null;
            $temp['RepDesig'] = null;
            $temp['JobResponsibility'] = null;
            $temp['DAHq'] = null;
            $temp['DAOutHq'] = null;
            $temp['PetrolAlw'] = null;
            $temp['PhoneAlw'] = null;
            $temp['HotelElg'] = null;
            $temp['Medical'] = null;
            $temp['GrpTermIns'] = null;
            $temp['GrpPersonalAccIns'] = null;
            $temp['MobileHandset'] = null;
            $temp['MobileBill'] = null;
            $temp['TravelElg'] = null;
            $temp['ExpectedCTC'] = $value->ExpCTC;
            $temp['TotalYear'] = null;
            $temp['TotalMonth'] = null;
            $temp['Reference'] = null;
            $temp['RefPerson'] = null;
            $temp['RefCompany'] = null;
            $temp['RefDesignation'] = null;
            $temp['RefContact'] = null;
            $temp['RefMail'] = null;
            $temp['Resume'] = null;
            $temp['CandidateImage'] = $value->CandidateImg;
            $temp['EmailOTP'] = $value->EmailOTP;
            $temp['SmsOTP'] = $value->SMSOTP;
            $temp['Verified'] = $value->Verified;
            $temp['PlacementDate'] = null;
            $temp['BlackList'] = 0;
            $temp['BlackListRemark'] = null;
            $temp['UnBlockRemark'] = null;
            $temp['FIROB_Test'] = 0;
            $temp['InterviewSubmit'] = $value->InterviewSubmit;
            $temp['FinalSubmit'] = $value->FinalSubmit;
            $temp['VNR_Acq'] = 'N';
            $temp['VNR_Acq_Business'] = 'N';
            $temp['OtherSeedRelation'] = 'N';
            $temp['CreatedTime'] = $value->CreatedTime;
            $temp['LastUpdated'] = $value->LastUpdated;
            $temp['UpdatedBy'] = $value->UpdatedBy;

            $candidates_array[] = $temp;
        }
        $importCandidates = DB::table('jobcandidates')->insert($candidates_array);


        $getCandidates = $connection->table('jobcandidates')->select('*')->skip(3500)->take(500)->get();
        $candidates_array = array();
        foreach ($getCandidates as $key => $value) {
            $temp = array();
            $temp['JCId'] = $value->JCId;
            $temp['ReferenceNo'] = $value->RollNo ?? '';
            $temp['Title'] = $value->NameTitle;
            $temp['FName'] = $value->FName;
            $temp['MName'] = $value->MName;
            $temp['LName'] = $value->LName;
            $temp['DOB'] = $value->DOB;
            $temp['Gender'] = $value->Gender;
            $temp['FatherTitle'] = 'Mr.';
            $temp['FatherName'] = $value->FatherName;
            $temp['Email'] = $value->Email1;
            $temp['Phone'] = $value->Contact1;
            $temp['Email2'] = $value->Email2;
            $temp['Phone2'] = $value->Contact2;
            $temp['AddressLine1'] = $value->Address;
            $temp['AddressLine2'] = '';
            $temp['AddressLine3'] = '';
            $temp['State'] = $value->State;
            $temp['District'] = $value->Dist;
            $temp['City'] = $value->City;
            $temp['PinCode'] = $value->PinCode;
            $temp['Aadhaar'] = $value->Aadhar;
            $temp['Education'] = null;
            $temp['Specialization'] = null;
            $temp['CGPA'] = null;
            $temp['PassingYear'] = null;
            $temp['College'] = null;
            $temp['OtherCollege'] = null;
            $temp['StudentId'] = null;
            $temp['Nationality'] = 1;
            $temp['Religion'] = null;
            $temp['OtherReligion'] = null;
            $temp['Caste'] = $value->Caste ?? null;
            $temp['OtherCaste'] = $value->OtherCaste ?? null;
            $temp['MaritalStatus'] = $value->MaritalStatus ?? null;
            $temp['MarriageDate'] = $value->marriage_dt ?? null;
            $temp['SpouseName'] = null;
            $temp['Professional'] = $value->ProfessOrFresher;
            $temp['PresentCompany'] = $value->CurCompany;
            $temp['Designation'] = $value->CurDesignation;
            $temp['JobStartDate'] = $value->JobStartDate;
            $temp['JobEndDate'] = $value->JobEndDate;
            $temp['StillEmp'] = $value->StillEmployed;
            $temp['GrossSalary'] = $value->GMonthlySalary;
            $temp['CTC'] = $value->AnnualCTC;
            $temp['NoticePeriod'] = null;
            $temp['ResignReason'] = null;
            $temp['Reporting'] = null;
            $temp['RepDesig'] = null;
            $temp['JobResponsibility'] = null;
            $temp['DAHq'] = null;
            $temp['DAOutHq'] = null;
            $temp['PetrolAlw'] = null;
            $temp['PhoneAlw'] = null;
            $temp['HotelElg'] = null;
            $temp['Medical'] = null;
            $temp['GrpTermIns'] = null;
            $temp['GrpPersonalAccIns'] = null;
            $temp['MobileHandset'] = null;
            $temp['MobileBill'] = null;
            $temp['TravelElg'] = null;
            $temp['ExpectedCTC'] = $value->ExpCTC;
            $temp['TotalYear'] = null;
            $temp['TotalMonth'] = null;
            $temp['Reference'] = null;
            $temp['RefPerson'] = null;
            $temp['RefCompany'] = null;
            $temp['RefDesignation'] = null;
            $temp['RefContact'] = null;
            $temp['RefMail'] = null;
            $temp['Resume'] = null;
            $temp['CandidateImage'] = $value->CandidateImg;
            $temp['EmailOTP'] = $value->EmailOTP;
            $temp['SmsOTP'] = $value->SMSOTP;
            $temp['Verified'] = $value->Verified;
            $temp['PlacementDate'] = null;
            $temp['BlackList'] = 0;
            $temp['BlackListRemark'] = null;
            $temp['UnBlockRemark'] = null;
            $temp['FIROB_Test'] = 0;
            $temp['InterviewSubmit'] = $value->InterviewSubmit;
            $temp['FinalSubmit'] = $value->FinalSubmit;
            $temp['VNR_Acq'] = 'N';
            $temp['VNR_Acq_Business'] = 'N';
            $temp['OtherSeedRelation'] = 'N';
            $temp['CreatedTime'] = $value->CreatedTime;
            $temp['LastUpdated'] = $value->LastUpdated;
            $temp['UpdatedBy'] = $value->UpdatedBy;

            $candidates_array[] = $temp;
        }
        $importCandidates = DB::table('jobcandidates')->insert($candidates_array);


        $getJobApply = $connection->table('jobapply')->leftJoin('jobpost', 'jobpost.JPId', '=', 'jobapply.JPId')->select('jobapply.*', 'jobpost.CompanyId')->get();
        $jobapply_array = array();
        foreach ($getJobApply as $key => $value) {
            $temp = array();
            $temp['JAId'] = $value->JAId;
            $temp['JCId'] = $value->JCId;
            $temp['JPId'] = $value->JPId;
            $temp['Type'] = $value->Type;
            $temp['ResumeSource'] = $value->ResumeSouId;
            $temp['Company'] = $value->CompanyId;
            $temp['Department'] = $value->DepartmentId;
            $temp['ApplyDate'] = $value->CreatedTime;
            $temp['Status'] = $value->Status;
            $temp['SelectedBy'] = $value->SelectedBy;
            $temp['FwdTechScr'] = ($value->Status == 'Selected') ? 'Yes' : 'No';
            $temp['CreatedBy'] = $value->CreatedBy;
            $jobapply_array[] = $temp;
        }
        $importJobApply = DB::table('jobapply')->insert($jobapply_array);


        $getScreening = $connection->table('screening')->join('master_employee', 'master_employee.EmpCode', '=', 'screening.ScreeningBy')->select('screening.*', 'master_employee.EmployeeID')->groupBy('screening.ScId')->get();

        $screening_array = array();
        foreach ($getScreening as $key => $value) {
            $temp = array();
            $temp['SCId'] = $value->ScId;
            $temp['JAId'] = $value->JAId;
            $temp['ReSentForScreen'] = $value->ReSentForScreen;
            $temp['ScrCmp'] = $value->ScrCmp;
            $temp['ScrDpt'] = $value->ScrDpt;
            $temp['ScreeningBy'] = $value->EmployeeID;
            $temp['ResScreened'] = $value->ResScreened;
            $temp['ScreenStatus'] = $value->ScreenStatus;
            $temp['RejectionRem'] = $value->RejectionRem;
            $temp['SendInterMail'] = $value->SendInterMail;
            $temp['InterAtt'] = $value->InterAtt;
            $temp['IntervDt'] = $value->IntervDt;
            $temp['IntervTime'] = $value->IntervTime;
            $temp['IntervLoc'] = $value->IntervLoc;
            $temp['IntervPanel'] = $value->IntervPanel;
            $temp['travelEligibility'] = $value->travelEligibility;
            $temp['IntervStatus'] = $value->IntervStatus;
            $temp['SelectedForC'] = $value->SelectedForC;
            $temp['SelectedForD'] = $value->SelectedForD;
            $temp['Remarks'] = $value->Remarks;
            $temp['CreatedTime'] = $value->CreatedTime;
            $temp['CreatedBy'] = $value->CreatedBy;
            $temp['LastUpdated'] = $value->LastUpdated;
            $temp['UpdatedBy'] = $value->UpdatedBy;
            $screening_array[] = $temp;
        }

        $importScreening = DB::table('screening')->insert($screening_array);


        $get2ndScreening = $connection->table('screen2ndround')->get();
        $screen2ndround_array = array();
        foreach ($get2ndScreening as $key => $value) {
            $temp = array();
            $temp['SScId'] = $value->SScId;
            $temp['ScId'] = $value->ScId;
            $temp['InterAtt2'] = $value->InterAtt2;
            $temp['IntervDt2'] = $value->IntervDt2;
            $temp['IntervLoc2'] = $value->IntervLoc2;
            $temp['IntervPanel2'] = $value->IntervPanel2;
            $temp['IntervStatus2'] = $value->IntervStatus2;
            $temp['Remarks'] = $value->Remarks;
            $temp['CreatedTime'] = $value->CreatedTime;
            $temp['CreatedBy'] = $value->CreatedBy;
            $temp['LastUpdated'] = $value->LastUpdated;
            $temp['UpdatedBy'] = $value->UpdatedBy;
            $screen2ndround_array[] = $temp;
        }

        $import2ndScreening = DB::table('screen2ndround')->insert($screen2ndround_array);


        $getOfBasic = $connection->table('offerletterbasic')->get();

        $offerletterbasic_array = array();
        $offerletterbasic_array1 = array();
        foreach ($getOfBasic as $key => $value) {
            $temp = array();
            $JCId  = DB::table('jobapply')->where('JAId', $value->JAId)->select('JCId')->first();
            if (isset($JCId->JCId)) {
                $JCId = $JCId->JCId;
            } else {

                $JCId = 0;
            }
            $Month = date('M', strtotime($value->CreatedTime));
            $Year = date('Y', strtotime($value->CreatedTime));
            $temp['OfLeId'] = $value->OfLeId;
            $temp['JAId'] = $value->JAId;
            $temp['Company'] = $value->Company;
            $temp['Grade'] = $value->Grade;
            $temp['Department'] = $value->Department;
            $temp['Designation'] = $value->Designation;
            $temp['LtrNo'] = getcompany_code($value->Company) . '_OL/' . getDepartmentCode($value->Department) . '/' . $Month . '-' . $Year . '/' . $JCId;
            $temp['LtrDate'] = $value->LastUpdated;
            $temp['TempS'] = $value->TempS;
            $temp['T_StateHq'] = $value->T_StateHq;
            $temp['T_LocationHq'] = $value->T_LocationHq;
            $temp['T_City'] = $value->T_City;
            $temp['TempM'] = $value->TempM;
            $temp['FixedS'] = $value->FixedS;
            $temp['F_StateHq'] = $value->F_StateHq;
            $temp['F_LocationHq'] = $value->F_LocationHq;
            $temp['F_City'] = $value->F_City;
            $temp['Functional_R'] = $value->Functional_R;
            $temp['Functional_Dpt'] = $value->Functional_Dpt;
            $temp['F_ReportingManager'] = $value->F_ReportingManager;
            $temp['Admins_R'] = $value->Admins_R;
            $temp['Admins_Dpt'] = $value->Admins_Dpt;
            $temp['A_ReportingManager'] = $value->A_ReportingManager;
            $temp['CTC'] = $value->CTC;
            $temp['ServiceCondition'] = $value->ServiceCondition;
            $temp['OrientationPeriod'] = $value->OrientationPeriod;
            $temp['Stipend'] = $value->Stipend;
            $temp['AFT_Grade'] = $value->AFT_Grade;
            $temp['AFT_Designation'] = $value->AFT_Designation;
            $temp['ServiceBond'] = $value->ServiceBond;
            $temp['ServiceBondYears'] = $value->ServiceBondYears;
            $temp['ServiceBondRefund'] = $value->ServiceBondRefund;
            $temp['PreMedicalCheckUp'] = $value->PreMedicalCheckUp;
            $temp['Remarks'] = $value->Remarks;
            $temp['SigningAuth'] = $value->SigningAuth;
            $temp['OfferLetter'] = $value->OfferLetter;
            $temp['CompDetails'] = $value->CompDetails;
            $temp['EligDetails'] = $value->EligDetails;
            $temp['OfferLtrGen'] = $value->OfferLtrGen;
            $temp['OfferLetterSent'] = $value->OfferLetterSent;
            $temp['JoiningFormSent'] = $value->JoiningFormSent;
            $temp['Answer'] = $value->Answer;
            $temp['RejReason'] = $value->RejReason;
            $temp['Reopen'] = $value->Reopen;
            $temp['SendReview'] = $value->SendReview;
            $temp['SendForRefChk'] = $value->SendForRefChk;
            $temp['CreatedTime'] = $value->CreatedTime;
            $temp['Year'] = date('Y', strtotime($value->CreatedTime));
            $temp['CreatedBy'] = $value->CreatedBy;
            $temp['LastUpdated'] = $value->LastUpdated;
            $temp['UpdatedBy'] = $value->UpdatedBy;
            $offerletterbasic_array[] = $temp;
        }

        $importOfBasic = DB::table('offerletterbasic')->insert($offerletterbasic_array);

        foreach ($getOfBasic as $key => $value) {
            $temp = array();
            $JCId  = DB::table('jobapply')->where('JAId', $value->JAId)->select('JCId')->first();
            if (isset($JCId->JCId)) {
                $JCId = $JCId->JCId;
            } else {

                $JCId = 0;
            }
            $Month = date('M', strtotime($value->CreatedTime));
            $Year = date('Y', strtotime($value->CreatedTime));
            $temp['Seq'] = 1;
            $temp['JAId'] = $value->JAId;
            $temp['RevisionRemark'] = '';
            $temp['Company'] = $value->Company;
            $temp['Grade'] = $value->Grade;
            $temp['Department'] = $value->Department;
            $temp['Designation'] = $value->Designation;
            $temp['LtrNo'] = getcompany_code($value->Company) . '_OL/' . getDepartmentCode($value->Department) . '/' . $Month . '-' . $Year . '/' . $JCId;
            $temp['LtrDate'] = $value->LastUpdated;
            $temp['TempS'] = $value->TempS;
            $temp['T_StateHq'] = $value->T_StateHq;
            $temp['T_LocationHq'] = $value->T_LocationHq;
            $temp['T_City'] = $value->T_City;
            $temp['TempM'] = $value->TempM;
            $temp['FixedS'] = $value->FixedS;
            $temp['F_StateHq'] = $value->F_StateHq;
            $temp['F_LocationHq'] = $value->F_LocationHq;
            $temp['F_City'] = $value->F_City;
            $temp['Functional_R'] = $value->Functional_R;
            $temp['Functional_Dpt'] = $value->Functional_Dpt;
            $temp['F_ReportingManager'] = $value->F_ReportingManager;
            $temp['Admins_R'] = $value->Admins_R;
            $temp['Admins_Dpt'] = $value->Admins_Dpt;
            $temp['A_ReportingManager'] = $value->A_ReportingManager;
            $temp['CTC'] = $value->CTC;
            $temp['ServiceCondition'] = $value->ServiceCondition;
            $temp['OrientationPeriod'] = $value->OrientationPeriod;
            $temp['Stipend'] = $value->Stipend;
            $temp['AFT_Grade'] = $value->AFT_Grade;
            $temp['AFT_Designation'] = $value->AFT_Designation;
            $temp['ServiceBond'] = $value->ServiceBond;
            $temp['ServiceBondYears'] = $value->ServiceBondYears;
            $temp['ServiceBondRefund'] = $value->ServiceBondRefund;
            $temp['PreMedicalCheckUp'] = $value->PreMedicalCheckUp;
            $temp['Remarks'] = $value->Remarks;
            $temp['SigningAuth'] = $value->SigningAuth;

            $temp['CreatedBy'] = $value->CreatedBy;

            $offerletterbasic_array1[] = $temp;
        }
        $importOFHistory = DB::table('offerletterbasic_history')->insert($offerletterbasic_array1);

        $getCandJoining = $connection->table('candjoining')->get();

        $candjoining_array = array();
        foreach ($getCandJoining as $key => $value) {
            $temp = array();
            $temp['CJId'] = $value->CJId;
            $temp['JAId'] = $value->JAId;
            $temp['LinkValidityStart'] = date('Y-m-d', strtotime($value->LinkValidityStart));
            $temp['LinkValidityEnd'] = date('Y-m-d', strtotime($value->LinkValidityEnd));
            $temp['LinkStatus'] = $value->LinkStatus;
            $temp['JoinOnDt'] = $value->JoinOnDt;
            $temp['Place'] = $value->Place;
            $temp['Date'] = $value->Date;
            $temp['RefCheck'] = $value->RefCheck;
            $temp['EmpCode'] = $value->EmpCode;
            $temp['Verification'] = $value->Verification;
            $temp['Joined'] = ($value->ForwardToESS == 'Joined') ? 'Yes' : 'No';
            $temp['ForwardToESS'] = ($value->ForwardToESS == 'Joined') ? 'Yes' : 'No';
            $temp['CreatedTime'] = $value->CreatedTime;
            $temp['CreatedBy'] = $value->CreatedBy;
            $temp['LastUpdated'] = $value->LastUpdated;
            $temp['UpdatedBy'] = $value->UpdatedBy;
            $candjoining_array[] = $temp;
        }
        $importCandidateJoining = DB::table('candjoining')->insert($candjoining_array);


        $getCTC = $connection->table('candidate_ctc')->get();
        $ctc_arry = array();

        foreach ($getCTC as $key => $value) {
            $temp = array();
            $temp['CTCId'] = $value->CTCId;
            $temp['JAId'] = $value->JAId;
            $temp['ctcLetterNo'] = $value->ctcLetterNo;
            $temp['ctc_date'] = $value->ctc_date;
            $temp['basic'] = $value->basic;
            $temp['hra'] = $value->hra;
            $temp['bonus'] = $value->bonus;
            $temp['special_alw'] = $value->special_alw;
            $temp['grsM_salary'] = $value->grsM_salary;
            $temp['emplyPF'] = $value->emplyPF;
            $temp['emplyESIC'] = $value->emplyESIC;
            $temp['netMonth'] = $value->netMonth;
            $temp['lta'] = $value->lta;
            $temp['childedu'] = $value->childedu;
            $temp['anualgrs'] = $value->anualgrs;
            $temp['gratuity'] = $value->gratuity;
            $temp['emplyerPF'] = $value->emplyerPF;
            $temp['emplyerESIC'] = $value->emplyerESIC;
            $temp['medical'] = $value->medical;
            $temp['total_ctc'] = $value->total_ctc;
            $temp['created_on'] = $value->created_on;
            $temp['created_by'] = $value->created_by;
            $temp['status'] = $value->status;
            $ctc_arry[] = $temp;
        }
        $importCTC = DB::table('candidate_ctc')->insert($ctc_arry);


        foreach ($getCTC as $key => $value) {
            $updateCTCHistory = DB::table('offerletterbasic_history')->where('JAId', $value->JAId)->update([
                'basic' => $value->basic,
                'hra' => $value->hra,
                'bonus' => $value->bonus,
                'special_alw' => $value->special_alw,
                'grsM_salary' => $value->grsM_salary,
                'emplyPF' => $value->emplyPF,
                'emplyESIC' => $value->emplyESIC,
                'netMonth' => $value->netMonth,
                'lta' => $value->lta,
                'childedu' => $value->childedu,
                'anualgrs' => $value->anualgrs,
                'gratuity' => $value->gratuity,
                'emplyerPF' => $value->emplyerPF,
                'emplyerESIC' => $value->emplyerESIC,
                'medical' => $value->medical,
                'total_ctc' => $value->total_ctc,

            ]);
        }


        $getENT = $connection->table('candidate_entitlement')->get();
        $ent_array = array();
        foreach ($getENT as $key => $value) {
            $temp = array();
            $temp['ENTId'] = $value->ENTId;
            $temp['JAId'] = $value->JAId;
            $temp['EntLetterNo'] = $value->EntLetterNo;
            $temp['EntDate'] = $value->EntDate;
            $temp['LoadCityA'] = $value->LoadCityA;
            $temp['LoadCityB'] = $value->LoadCityB;
            $temp['LoadCityC'] = $value->LoadCityC;
            $temp['DAOut'] = $value->DAOut;
            $temp['DAHq'] = $value->DAHq;
            $temp['TwoWheel'] = $value->TwoWheel;
            $temp['FourWheel'] = $value->FourWheel;
            $temp['Train'] = ($value->TravelMode == 'Bus/Train') ? 'Y' : 'N';
            if ($value->TravelClass == 'Sleeper') {
                $Train_Class = 'Sleeper';
            } elseif ($value->TravelClass == '3 AC') {
                $Train_Class = 'AC-III';
            } elseif ($value->TravelClass == '2 AC') {
                $Train_Class = 'AC-II';
            } else {
                $Train_Class = 'AC-I';
            }

            $temp['Train_Class'] = $Train_Class;

            $temp['Flight'] = ($value->Flight != null) ? 'Y' : 'N';
            $temp['Flight_Class'] = 'Economy';
            if ($value->Flight == 'flight_approval_based') {
                $Flight_Remark = 'Flight Approval Based';
            } elseif ($value->Flight == 'flight_need_based') {
                $Flight_Remark = 'Flight Need Approval';
            } else {
                $Flight_Remark = '';
            }
            $temp['Flight_Remark'] = $Flight_Remark;
            $temp['Mobile'] = $value->Mobile;
            $temp['MExpense'] = $value->MExpense;
            $temp['MTerm'] = $value->MTerm;
            $temp['GPRS'] = $value->GPRS;
            $temp['Laptop'] = $value->Laptop;

            $temp['HealthIns'] = ((int)($value->HealthIns) * 100000);

            $temp['TravelLine'] = $value->TravelLine;
            $temp['TwoWheelLine'] = $value->TwoWheelLine;
            $temp['FourWheelLine'] = $value->FourWheelLine;
            $temp['Created_on'] = $value->Created_on;
            $temp['Created_by'] = $value->Created_by;
            $temp['Status'] = $value->Status;

            $ent_array[] = $temp;
        }
        $importENT = DB::table('candidate_entitlement')->insert($ent_array);

        foreach ($getENT as $key => $value) {


            if ($value->TravelClass == 'Sleeper') {
                $Train_Class = 'Sleeper';
            } elseif ($value->TravelClass == '3 AC') {
                $Train_Class = 'AC-III';
            } elseif ($value->TravelClass == '2 AC') {
                $Train_Class = 'AC-II';
            } else {
                $Train_Class = 'AC-I';
            }

            if ($value->Flight == 'flight_approval_based') {
                $Flight_Remark = 'Flight Approval Based';
            } elseif ($value->Flight == 'flight_need_based') {
                $Flight_Remark = 'Flight Need Approval';
            } else {
                $Flight_Remark = '';
            }
            $updateEntHistory = DB::table('offerletterbasic_history')->where('JAId', $value->JAId)->update([
                'LoadCityA' => $value->LoadCityA,
                'LoadCityB' => $value->LoadCityB,
                'LoadCityC' => $value->LoadCityC,
                'DAOut' => $value->DAOut,
                'DAHq' => $value->DAHq,
                'TwoWheel' => $value->TwoWheel,
                'FourWheel' => $value->FourWheel,
                'Train' => ($value->TravelMode == 'Bus/Train') ? 'Y' : 'N',
                'Train_Class' => $Train_Class,
                'Flight' => ($value->Flight != null) ? 'Y' : 'N',
                'Flight_Class' => 'Economy',
                'Flight_Remark' => $Flight_Remark,
                'Mobile' => $value->Mobile,
                'MExpense' => $value->MExpense,
                'MTerm' => $value->MTerm,
                'GPRS' => $value->GPRS,
                'Laptop' => $value->Laptop,
                'HealthIns' => ((int)($value->HealthIns) * 100000),
                'TravelLine' => $value->TravelLine,
                'TwoWheelLine' => $value->TwoWheelLine,
                'FourWheelLine' => $value->FourWheelLine,
            ]);
        }




        $getAppointing = $connection->table('appointing')
            ->leftJoin('service_agreement', 'appointing.JAId', '=', 'service_agreement.JAId')
            ->leftJoin('service_bond', 'appointing.JAId', '=', 'service_bond.JAId')
            ->select('appointing.AppointId', 'appointing.JAId', 'appointing.A_Date', 'appointing.AppLetter', 'appointing.CreatedTime', 'appointing.CreatedBy', 'appointing.LastUpdated', 'appointing.UpdatedBy', 'service_agreement.ServAgr', 'service_agreement.A_Date as agr_date', 'service_bond.B_Date', 'service_bond.ServBond')
            ->get();

        $appointing_array = array();
        foreach ($getAppointing as $key => $value) {
            $temp = array();
            $query = DB::table('screening')->where('screening.JAId', $value->JAId)->join('candjoining', 'candjoining.JAId', '=', 'screening.JAId')->join('offerletterbasic', 'offerletterbasic.JAId', '=', 'screening.JAId')->select('candjoining.JoinOnDt', 'screening.SelectedForC', 'screening.SelectedForD', 'offerletterbasic.ServiceBond')->first();
            $Company = $query->SelectedForC;
            $Department = $query->SelectedForD;
            $JoinOnDt = $query->JoinOnDt;
            $ServiceBond = $query->ServiceBond;
            $temp['AppointId'] = $value->AppointId;
            $temp['JAId'] = $value->JAId;
            $temp['A_Date'] = $value->A_Date;
            $temp['AppLetterNo'] = getcompany_code($Company) . '_AL/' . getDepartmentCode($Department) . '/' . date('M-Y', strtotime($JoinOnDt)) . '/' . $value->JAId;
            $temp['AppLtrGen'] = $value->AppLetter;

            $temp['Agr_Date'] = $value->A_Date;
            $temp['AgrLtrNo'] = getcompany_code($Company) . '_SA/' . getDepartmentCode($Department) . '/' . date('M-Y', strtotime($JoinOnDt)) . '/' . $value->JAId;
            $temp['AgrLtrGen'] = 'Yes';

            if ($ServiceBond == 'Yes') {
                $temp['B_Date'] = $value->A_Date;
                $temp['BLtrNo'] = getcompany_code($Company) . '_SB/' . getDepartmentCode($Department) . '/' . date('M-Y', strtotime($JoinOnDt)) . '/' . $value->JAId;
                $temp['BLtrGen'] = 'Yes';
            } else {
                $temp['B_Date'] = null;
                $temp['BLtrNo'] = null;
                $temp['BLtrGen'] = null;
            }



            $temp['CreatedTime'] = $value->CreatedTime;
            $temp['CreatedBy'] = $value->CreatedBy;
            $temp['LastUpdated'] = $value->LastUpdated;
            $temp['UpdatedBy'] = $value->UpdatedBy;
            $appointing_array[] = $temp;
        }
        $importAppointing = DB::table('appointing')->insert($appointing_array);

        $getEducation = $connection->table('candidateeducation')
            ->leftJoin('master_specialization', 'candidateeducation.Specialization', '=', 'master_specialization.Specialization')
            ->select('candidateeducation.CEId', 'candidateeducation.JCId', 'candidateeducation.Qualification', 'candidateeducation.Course', 'candidateeducation.Institute', 'candidateeducation.YearOfPassing', 'candidateeducation.CGPA', 'candidateeducation.CreatedTime', 'candidateeducation.LastUpdated', 'master_specialization.SpId')
            ->where('candidateeducation.Qualification', '!=', '')->groupBy('candidateeducation.CEId')->get();

        $edu_array = array();
        foreach ($getEducation as $key => $value) {
            $temp = array();
            $temp['CEId'] = $value->CEId;
            $temp['JCId'] = $value->JCId;
            $temp['Qualification'] = $value->Qualification;
            $temp['Course'] = $value->Course;
            $temp['Specialization'] = $value->SpId;
            $temp['Institute'] = $value->Institute;
            $temp['YearOfPassing'] = $value->YearOfPassing;
            $temp['CGPA'] = $value->CGPA;
            $temp['LastUpdated'] = $value->CreatedTime ?? $value->LastUpdated;
            $edu_array[] = $temp;
        }
        $importEducation = DB::table('candidateeducation')->insert($edu_array);

        $getAns = $connection->select("select JCId, max(case when (aqid='1') then answer else NULL end) as 'AboutAim',
        max(case when (aqid='2') then answer else NULL end) as 'AboutHobbi',
        max(case when (aqid='3') then answer else NULL end) as 'About5Year',
         max(case when (aqid='4') then answer else NULL end) as 'AboutAssets',
        max(case when (aqid='5') then answer else NULL end) as 'AboutImprovement',
        max(case when (aqid='6') then answer else NULL end) as 'AboutStrength'
        from jf_about_ans
        group by JCId
        order by JCId");
        $ans_array = array();

        foreach ($getAns as $key => $value) {
            $temp = array();
            $temp['JCId'] = $value->JCId;
            $temp['AboutAim'] = $value->AboutAim;
            $temp['AboutHobbi'] = $value->AboutHobbi;
            $temp['About5Year'] = $value->About5Year;
            $temp['AboutAssets'] = $value->AboutAssets;
            $temp['AboutImprovement'] = $value->AboutImprovement;
            $temp['AboutStrength'] = $value->AboutStrength;
            $ans_array[] = $temp;
        }
        $importAns = DB::table('about_answer')->insert($ans_array);


        $getPFESIC = $connection->select("SELECT * FROM `jf_pf_esic` WHERE UAN !='' OR pf_acc_no !='' OR esic_no !=''");
        $pf_esic_array = array();
        foreach ($getPFESIC as $key => $value) {
            $temp = array();
            $temp['JCId'] = $value->JCId;
            $temp['UAN'] = $value->UAN;
            $temp['PFNumber'] = $value->pf_acc_no;
            $temp['ESICNumber'] = $value->esic_no;
            $pf_esic_array[] = $temp;
        }
        $importPF = DB::table('jf_pf_esic')->insert($pf_esic_array);

        if ($importENT) {
            DB::commit();
            return response()->json(['status' => 200, 'msg' => 'Data Imported Successfully..!!']);
        } else {
            DB::rollBack();
            return response()->json(['status' => 400, 'msg' => 'Something went wrong..!!']);
        }
    } */

    /*   public function Import()
    {
        $connection = DB::connection('mysql3');

        $getCandidates = $connection->table('jobcandidates')->select('*')->skip(0)->take(500)->get();
        foreach ($getCandidates as $key => $value) {
            if (\File::exists(public_path('uploads/Resume/' . 'resume_' . $value->JCId . '.pdf'))) {
                $resume = 'resume_' . $value->JCId . '.pdf';
            } elseif (\File::exists(public_path('uploads/Resume/' . 'resume_' . $value->JCId . '.doc'))) {
                $resume = 'resume_' . $value->JCId . '.doc';
            } elseif (\File::exists(public_path('uploads/Resume/' . 'resume_' . $value->JCId . '.docx'))) {
                $resume = 'resume_' . $value->JCId . '.docx';
            } else {
                $resume = null;
            }
            $query = DB::table('jobcandidates')->where('JCId', $value->JCId)->update(['Resume' => $resume]);
        }

        $getCandidates = $connection->table('jobcandidates')->select('*')->skip(500)->take(500)->get();
        foreach ($getCandidates as $key => $value) {
            if (\File::exists(public_path('uploads/Resume/' . 'resume_' . $value->JCId . '.pdf'))) {
                $resume = 'resume_' . $value->JCId . '.pdf';
            } elseif (\File::exists(public_path('uploads/Resume/' . 'resume_' . $value->JCId . '.doc'))) {
                $resume = 'resume_' . $value->JCId . '.doc';
            } elseif (\File::exists(public_path('uploads/Resume/' . 'resume_' . $value->JCId . '.docx'))) {
                $resume = 'resume_' . $value->JCId . '.docx';
            } else {
                $resume = null;
            }
            $query = DB::table('jobcandidates')->where('JCId', $value->JCId)->update(['Resume' => $resume]);
        }

        $getCandidates = $connection->table('jobcandidates')->select('*')->skip(1000)->take(500)->get();
        foreach ($getCandidates as $key => $value) {
            if (\File::exists(public_path('uploads/Resume/' . 'resume_' . $value->JCId . '.pdf'))) {
                $resume = 'resume_' . $value->JCId . '.pdf';
            } elseif (\File::exists(public_path('uploads/Resume/' . 'resume_' . $value->JCId . '.doc'))) {
                $resume = 'resume_' . $value->JCId . '.doc';
            } elseif (\File::exists(public_path('uploads/Resume/' . 'resume_' . $value->JCId . '.docx'))) {
                $resume = 'resume_' . $value->JCId . '.docx';
            } else {
                $resume = null;
            }
            $query = DB::table('jobcandidates')->where('JCId', $value->JCId)->update(['Resume' => $resume]);
        }

        $getCandidates = $connection->table('jobcandidates')->select('*')->skip(1500)->take(500)->get();
        foreach ($getCandidates as $key => $value) {
            if (\File::exists(public_path('uploads/Resume/' . 'resume_' . $value->JCId . '.pdf'))) {
                $resume = 'resume_' . $value->JCId . '.pdf';
            } elseif (\File::exists(public_path('uploads/Resume/' . 'resume_' . $value->JCId . '.doc'))) {
                $resume = 'resume_' . $value->JCId . '.doc';
            } elseif (\File::exists(public_path('uploads/Resume/' . 'resume_' . $value->JCId . '.docx'))) {
                $resume = 'resume_' . $value->JCId . '.docx';
            } else {
                $resume = null;
            }
            $query = DB::table('jobcandidates')->where('JCId', $value->JCId)->update(['Resume' => $resume]);
        }

        $getCandidates = $connection->table('jobcandidates')->select('*')->skip(2000)->take(500)->get();
        foreach ($getCandidates as $key => $value) {
            if (\File::exists(public_path('uploads/Resume/' . 'resume_' . $value->JCId . '.pdf'))) {
                $resume = 'resume_' . $value->JCId . '.pdf';
            } elseif (\File::exists(public_path('uploads/Resume/' . 'resume_' . $value->JCId . '.doc'))) {
                $resume = 'resume_' . $value->JCId . '.doc';
            } elseif (\File::exists(public_path('uploads/Resume/' . 'resume_' . $value->JCId . '.docx'))) {
                $resume = 'resume_' . $value->JCId . '.docx';
            } else {
                $resume = null;
            }
            $query = DB::table('jobcandidates')->where('JCId', $value->JCId)->update(['Resume' => $resume]);
        }

        $getCandidates = $connection->table('jobcandidates')->select('*')->skip(2500)->take(500)->get();
        foreach ($getCandidates as $key => $value) {
            if (\File::exists(public_path('uploads/Resume/' . 'resume_' . $value->JCId . '.pdf'))) {
                $resume = 'resume_' . $value->JCId . '.pdf';
            } elseif (\File::exists(public_path('uploads/Resume/' . 'resume_' . $value->JCId . '.doc'))) {
                $resume = 'resume_' . $value->JCId . '.doc';
            } elseif (\File::exists(public_path('uploads/Resume/' . 'resume_' . $value->JCId . '.docx'))) {
                $resume = 'resume_' . $value->JCId . '.docx';
            } else {
                $resume = null;
            }
            $query = DB::table('jobcandidates')->where('JCId', $value->JCId)->update(['Resume' => $resume]);
        }

        $getCandidates = $connection->table('jobcandidates')->select('*')->skip(3000)->take(500)->get();
        foreach ($getCandidates as $key => $value) {
            if (\File::exists(public_path('uploads/Resume/' . 'resume_' . $value->JCId . '.pdf'))) {
                $resume = 'resume_' . $value->JCId . '.pdf';
            } elseif (\File::exists(public_path('uploads/Resume/' . 'resume_' . $value->JCId . '.doc'))) {
                $resume = 'resume_' . $value->JCId . '.doc';
            } elseif (\File::exists(public_path('uploads/Resume/' . 'resume_' . $value->JCId . '.docx'))) {
                $resume = 'resume_' . $value->JCId . '.docx';
            } else {
                $resume = null;
            }
            $query = DB::table('jobcandidates')->where('JCId', $value->JCId)->update(['Resume' => $resume]);
        }

        $getCandidates = $connection->table('jobcandidates')->select('*')->skip(3500)->take(500)->get();
        foreach ($getCandidates as $key => $value) {
            if (\File::exists(public_path('uploads/Resume/' . 'resume_' . $value->JCId . '.pdf'))) {
                $resume = 'resume_' . $value->JCId . '.pdf';
            } elseif (\File::exists(public_path('uploads/Resume/' . 'resume_' . $value->JCId . '.doc'))) {
                $resume = 'resume_' . $value->JCId . '.doc';
            } elseif (\File::exists(public_path('uploads/Resume/' . 'resume_' . $value->JCId . '.docx'))) {
                $resume = 'resume_' . $value->JCId . '.docx';
            } else {
                $resume = null;
            }
            $query = DB::table('jobcandidates')->where('JCId', $value->JCId)->update(['Resume' => $resume]);
        }
    } */
}
