<?php

namespace App\Http\Controllers;

use App\Models\jobapply;
use App\Models\OfferLetter;
use Illuminate\Http\Request;
use App\Models\CandidateJoining;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


class ProcessToEss extends Controller
{


    public function processDataToEss(Request $request)
    {
        DB::beginTransaction();

        $connection = DB::connection('mysql2');
        $JAId = $request->JAId;
        $JCId = jobapply::where('JAId', $JAId)->first()->JCId;
        $EmpCode = CandidateJoining::where('JAId', $JAId)->value('EmpCode');
        $CompanyId = OfferLetter::where('JAId', $JAId)->value('Company');
        $offer_basic = OfferLetter::where('JAId', $JAId)->first();
        // check if the data is already processed to ESS
        $check = $connection->table('employee_general')->where('EmpCode', $EmpCode)->where('CompanyId', $CompanyId)->first();

        if ($check) {
            DB::rollBack();
            return response()->json(['status' => 400, 'msg' => 'EmpCode already existed and processed to ESS']);
        } else {

            $ctc_query = DB::table('candidate_ctc')->where('JAId', $JAId)->first();
            $education_query = DB::table('candidateeducation')->where('JCId', $JCId)->get();
            $family_query = DB::table('jf_family_det')->where('JCId', $JCId)->get();
            $lang_query = DB::table('jf_language')->where('JCId', $JCId)->get();
            $address_query = DB::table('jf_contact_det')->where('JCId', $JCId)->first();
            $elg_query = DB::table('candidate_entitlement')->where('JAId', $JAId)->first();
            $pf_esic_query = DB::table('jf_pf_esic')->where('JCId', $JCId)->first();
            $jobcandidate = DB::table('jobcandidates')->select('jobcandidates.*', 'jobcandidates.Designation as PresentDesignation', 'offerletterbasic.*', 'candjoining.JoinOnDt', 'candjoining.PositionCode', 'candjoining.PosSeq', 'candjoining.PosVR', 'about_answer.DLNo', 'about_answer.LValidity')->join('jobapply', 'jobapply.JCId', '=', 'jobcandidates.JCId')->join('offerletterbasic', 'offerletterbasic.JAId', '=', 'jobapply.JAId')->leftjoin('candjoining', 'candjoining.JAId', '=', 'jobapply.JAId')->leftjoin('about_answer', 'about_answer.JCId', 'jobcandidates.JCId')->where('jobcandidates.JCId', $JCId)->first();

            $workexp_query = DB::table('jf_work_exp')->where('JCId', $JCId)->get();
            $training_query = DB::table('jf_tranprac')->select('*')->where('JCId', $JCId)->get();
            $pre_ref = DB::table('jf_reference')->where('JCId', $JCId)->where('from', 'Previous Organization')->get();
            $vnr_ref = DB::table('jf_reference')->where('JCId', $JCId)->where('from', 'VNR')->get();
            $vehicle_information_query = DB::table('vehicle_information')->where('JCId', $JCId)->first();

             $connection->table('employee_ctc')->insert(['EmpCode' => $EmpCode, 'CompanyId' => $CompanyId, 'basic' => $ctc_query->basic, 'hra' => $ctc_query->hra, 'bonus' => $ctc_query->bonus, 'special_alw' => $ctc_query->special_alw, 'grsM_salary' => $ctc_query->grsM_salary, 'emplyPF' => $ctc_query->emplyPF, 'emplyESIC' => $ctc_query->emplyESIC, 'netMonth' => $ctc_query->netMonth, 'lta' => $ctc_query->lta, 'childedu' => $ctc_query->childedu, 'anualgrs' => $ctc_query->anualgrs, 'gratuity' => $ctc_query->gratuity, 'emplyerPF' => $ctc_query->emplyerPF, 'emplyerESIC' => $ctc_query->emplyerESIC, 'medical' => $ctc_query->medical, 'total_ctc' => $ctc_query->total_ctc, 'fixed_ctc' => $ctc_query->fixed_ctc, 'performance_pay' => $ctc_query->performance_pay, 'total_gross_ctc' => $ctc_query->total_gross_ctc, 'car_allowance_amount' => $ctc_query->car_allowance_amount, 'communication_allowance_amount' => $ctc_query->communication_allowance_amount, 'total_gross_ctc' => $ctc_query->total_gross_ctc]);

            $edu_array = [];
            foreach ($education_query as $key => $value) {
                $temp = array();
                $temp['EmpCode'] = $EmpCode;
                $temp['CompanyId'] = $CompanyId;
                $temp['Qualification'] = $value->Qualification;
                $temp['Course'] = $value->Course == null ? '' : getEducationCodeById($value->Course);
                $temp['Specialization'] = $value->Specialization == null ? '' : getSpecializationbyId($value->Specialization);
                $temp['Institute'] = $value->Institute == null ? '' : ($value->Institute == 637 ? $value->OtherInstitute : getCollegeById($value->Institute));
                $temp['YearOfPassing'] = $value->YearOfPassing ?? '';
                $temp['CGPA'] = $value->CGPA ?? '';
                $edu_array[] = $temp;
            }

             $connection->table('employee_education')->insert($edu_array);

           $connection->table('employee_address')->insert(['EmpCode' => $EmpCode, 'CompanyId' => $CompanyId, 'pre_address' => $address_query->pre_address, 'pre_state' => getStateName($address_query->pre_state), 'pre_dist' => getDistrictName($address_query->pre_dist), 'pre_city' => $address_query->pre_city, 'pre_pin' => $address_query->pre_pin, 'perm_address' => $address_query->perm_address, 'perm_state' => getStateName($address_query->perm_state), 'perm_dist' => getDistrictName($address_query->perm_dist), 'perm_city' => $address_query->perm_city, 'perm_pin' => $address_query->perm_pin,]);


            // Prepare the data array with initial values
            $data = [
                'EmpCode' => $EmpCode,
                'CompanyId' => $CompanyId,
                'LoadCityA' => $elg_query->LoadCityA ?? '',
                'LoadCityB' => $elg_query->LoadCityB ?? '',
                'LoadCityC' => $elg_query->LoadCityC ?? '',
                'DAHq' => $elg_query->DAHq ?? '',
                'DAHq_Rmk' => $elg_query->DAHq_Rmk ?? '',
                'DAOut' => $elg_query->DAOut ?? '',
                'DAOut_Rmk' => $elg_query->DAOutRmk ?? '',
                'Train' => $elg_query->Train ?? '',
                'Train_Class' => $elg_query->Train_Class ?? '',
                'Train_Remark' => $elg_query->Train_Remark ?? '',
                'Flight' => $elg_query->Flight ?? '',
                'Flight_Class' => $elg_query->Flight_Class ?? '',
                'Flight_Remark' => $elg_query->Flight_Remark ?? '',
                'Laptop' => $elg_query->Laptop ?? '',
                'Laptop_Remark' => $elg_query->Laptop_Remark ?? '',
                'HealthIns' => $elg_query->HealthIns ?? '',
                'Helth_CheckUp' => $elg_query->Helth_CheckUp ?? '',
                'Helth_CheckUp_Remark' => $elg_query->Helth_CheckUp_Remark ?? '',
                'MExpense' => $elg_query->MExpense ?? '',
                'MTerm' => $elg_query->MTerm ?? '',
                'GPRS' => $elg_query->GPRS ?? '',
                'Mobile_Allow' => $elg_query->Mobile_Allow ?? '',
                'Mobile' => $elg_query->Mobile ?? '',
                'Mobile_Remb' => $elg_query->Mobile_Remb ?? '',
                'Mobile_Remb_Period_Rmk' => $elg_query->Mobile_Remb_Period_Rmk ?? '',
                'Mobile_RembPost' => $elg_query->Mobile_RembPost ?? '',
                'Mobile_RembPost_Period' => $elg_query->Mobile_RembPost_Period ?? '',
                'Mobile_RembPost_Period_Rmk' => $elg_query->Mobile_RembPost_Period_Rmk ?? '',
                'Term_Insurance' => $elg_query->Term_Insurance ?? '',
                'Term_Insurance_Rmk' => $elg_query->Term_Insurance_Rmk ?? '',
                'Vehicle_Policy' => $elg_query->Vehicle_Policy ?? '',
                'CostOfVehicle' => $elg_query->CostOfVehicle ?? '',
            ];

            // Add TwoWheel conditionally
            if ($offer_basic->two_wheel_rc == 'N' && $offer_basic->two_wheel_flat_rate != '') {
                $data['TwoWheel'] = $offer_basic->two_wheel_flat_rate;
                $data['TwoWheel_Rmk'] = '';
                $data['two_wheel_flat_rate'] = 'Yes';
            } elseif ($offer_basic->two_wheel_rc == 'Y') {
                $data['TwoWheel'] = $elg_query->TwoWheel ?? '';
                $data['TwoWheel_Rmk'] = $elg_query->TwoWheelRmk ?? '';
                $data['two_wheel_flat_rate'] = 'No';
            }


            // Add FourWheel conditionally

            if ($offer_basic->four_wheel_rc == 'N' && $offer_basic->four_wheel_flat_rate != '') {
                $data['FourWheel'] = $offer_basic->four_wheel_flat_rate;
                $data['FourWheel_Rmk'] = '';
                $data['four_wheel_flat_rate'] = 'Yes';
            } elseif ($offer_basic->two_wheel_rc == 'Y') {
                $data['FourWheel'] = $elg_query->FourWheel ?? '';
                $data['FourWheel_Rmk'] = $elg_query->FourWheelRmk ?? '';
                $data['two_wheel_flat_rate'] = 'No';
            }


            // Insert the data into the database
             $connection->table('employee_elg')->insert($data);


            $family_array = [];
            foreach ($family_query as $key => $value) {
                $temp = array();
                $temp['EmpCode'] = $EmpCode;
                $temp['CompanyId'] = $CompanyId;
                $temp['Relation'] = $value->relation;
                $temp['Name'] = $value->name;
                $temp['Dob'] = $value->dob ?? '';
                $temp['Qualification'] = $value->qualification ?? '';
                $temp['Occupation'] = $value->occupation ?? '';
                $family_array[] = $temp;
            }
            $connection->table('employee_family')->insert($family_array);

            $language_array = [];
            foreach ($lang_query as $key => $value) {
                $temp = array();
                $temp['EmpCode'] = $EmpCode;
                $temp['CompanyId'] = $CompanyId;
                $temp['language'] = $value->language;
                $temp['read'] = $value->read;
                $temp['write'] = $value->write;
                $temp['speak'] = $value->speak;
                $language_array[] = $temp;
            }

            $connection->table('employee_language')->insert($language_array);


           $connection->table('employee_pf')->insert(['EmpCode' => $EmpCode, 'CompanyId' => $CompanyId, 'UAN' => $pf_esic_query->UAN ?? '', 'pf_acc_no' => $pf_esic_query->PFNumber ?? '', 'esic_no' => $pf_esic_query->ESICNumber ?? '', 'bank_name' => $pf_esic_query->BankName ?? '', 'branch_name' => $pf_esic_query->BranchName ?? '', 'acc_number' => $pf_esic_query->AccountNumber ?? '', 'ifsc_code' => $pf_esic_query->IFSCCode ?? '', 'pan' => $pf_esic_query->PAN ?? '', 'passport' => $pf_esic_query->Passport ?? '',]);

            if ($jobcandidate->Professional == 'P') {
                $work_array = [];
                $work_array[0]['EmpCode'] = $EmpCode;
                $work_array[0]['CompanyId'] = $CompanyId;
                $work_array[0]['company'] = $jobcandidate->PresentCompany;
                $work_array[0]['desgination'] = $jobcandidate->PresentDesignation;
                $work_array[0]['job_start'] = $jobcandidate->JobStartDate;
                $work_array[0]['job_end'] = $jobcandidate->JobEndDate;
                $work_array[0]['gross_mon_sal'] = $jobcandidate->GrossSalary;
                $work_array[0]['annual_ctc'] = $jobcandidate->CTC;

                foreach ($workexp_query as $key => $value) {
                    $temp = array();
                    $temp['EmpCode'] = $EmpCode;
                    $temp['CompanyId'] = $CompanyId;
                    $temp['company'] = $value->company;
                    $temp['desgination'] = $value->desgination;
                    $temp['job_start'] = $value->job_start;
                    $temp['job_end'] = $value->job_end;
                    $temp['gross_mon_sal'] = $value->gross_mon_sal;
                    $temp['annual_ctc'] = $value->annual_ctc;
                    $work_array[] = $temp;
                }

                $SendWorkExp = $connection->table('employee_workexp')->insert($work_array);

                $pre_ref_array = [];
                foreach ($pre_ref as $key => $value) {
                    $temp = array();
                    $temp['EmpCode'] = $EmpCode;
                    $temp['CompanyId'] = $CompanyId;
                    $temp['name'] = $value->name;
                    $temp['designation'] = $value->designation;
                    $temp['company'] = $value->company;
                    $temp['contact'] = $value->contact;
                    $temp['email'] = $value->email;
                    $pre_ref_array[] = $temp;
                }
                $connection->table('employee_preref')->insert($pre_ref_array);
            }

            if ($training_query->count() > 0) {
                if ($training_query[0]->training != null || $training_query[0]->training != '') {
                    $training_array = [];
                    foreach ($training_query as $key => $value) {
                        $temp = array();
                        $temp['EmpCode'] = $EmpCode;
                        $temp['CompanyId'] = $CompanyId;
                        $temp['training'] = $value->training;
                        $temp['organization'] = $value->organization;
                        $temp['from'] = $value->from;
                        $temp['to'] = $value->to;
                        $training_array[] = $temp;
                    }

                    $SendTraining = $connection->table('employee_training')->insert($training_array);
                }
            }

            if ($vnr_ref->count() > 0) {
                if ($vnr_ref[0]->name != null || $vnr_ref[0]->name != '') {
                    $vnr_array = [];
                    foreach ($vnr_ref as $key => $value) {
                        $temp = array();
                        $temp['EmpCode'] = $EmpCode;
                        $temp['CompanyId'] = $CompanyId;
                        $temp['name'] = $value->name;
                        $temp['designation'] = $value->designation;
                        $temp['company'] = $value->company == 'Other' ? $value->other_company : $value->company;
                        $temp['contact'] = $value->contact;
                        $temp['email'] = $value->email;
                        $temp['rel_with_person'] = $value->rel_with_person;
                        $vnr_array[] = $temp;
                    }
                    $SendVNRRef = $connection->table('employee_vnrref')->insert($vnr_array);
                }
            }

            if ($vehicle_information_query != null) {
                if ($vehicle_information_query->model_name != null || $vehicle_information_query->model_name != '') {
                    $connection->table('employee_vehicle')->insert([
                        'EmpCode' => $EmpCode,
                        'CompanyId' => $CompanyId,
                        'brand' => $vehicle_information_query->brand,
                        'model_no' => $vehicle_information_query->model_no,
                        'dealer_name' => $vehicle_information_query->dealer_name,
                        'dealer_contact' => $vehicle_information_query->dealer_contact,
                        'purchase_date' => $vehicle_information_query->purchase_date,
                        'price' => $vehicle_information_query->price,
                        'registration_no' => $vehicle_information_query->registration_no,
                        'registration_date' => $vehicle_information_query->registration_date,
                        'bill_no' => $vehicle_information_query->bill_no,
                        'invoice' => $vehicle_information_query->invoice,
                        'fuel_type' => $vehicle_information_query->fuel_type,
                        'ownership' => $vehicle_information_query->ownership,
                        'vehicle_image' => $vehicle_information_query->vehicle_image,
                        'rc_file' => $vehicle_information_query->rc_file,
                        'insurance' => $vehicle_information_query->insurance,
                        'current_odo_meter' => $vehicle_information_query->current_odo_meter,
                        'odo_meter' => $vehicle_information_query->odo_meter,

                        'four_brand' => $vehicle_information_query->four_brand,
                        'four_model_no' => $vehicle_information_query->four_model_no,
                        'four_dealer_name' => $vehicle_information_query->four_dealer_name,
                        'four_dealer_contact' => $vehicle_information_query->four_dealer_contact,
                        'four_purchase_date' => $vehicle_information_query->four_purchase_date,
                        'four_price' => $vehicle_information_query->four_price,
                        'four_registration_no' => $vehicle_information_query->four_registration_no,
                        'four_registration_date' => $vehicle_information_query->four_registration_date,
                        'four_bill_no' => $vehicle_information_query->four_bill_no,
                        'four_invoice' => $vehicle_information_query->four_invoice,
                        'four_fuel_type' => $vehicle_information_query->four_fuel_type,
                        'four_ownership' => $vehicle_information_query->four_ownership,
                        'four_vehicle_image' => $vehicle_information_query->four_vehicle_image,
                        'four_rc_file' => $vehicle_information_query->four_rc_file,
                        'four_insurance' => $vehicle_information_query->four_insurance,
                        'four_current_odo_meter' => $vehicle_information_query->four_current_odo_meter,
                        'four_odo_meter' => $vehicle_information_query->four_odo_meter,
                        'remark' => $vehicle_information_query->remark
                    ]);
                }
            }

            $ConfirmationDate = '';
            $JoinOnDt = $jobcandidate->JoinOnDt;
            if ($jobcandidate->ServiceCondition == 'Probation') {
                //add 6months to join date
                $ConfirmationDate = date('Y-m-d', strtotime($JoinOnDt . ' + 6 months'));
            } elseif ($jobcandidate->ServiceCondition == 'Training') {
                //Add 12 months to join date
                $ConfirmationDate = date('Y-m-d', strtotime($JoinOnDt . ' + 12 months'));
            } elseif ($jobcandidate->ServiceCondition == 'nopnot') {
                $ConfirmationDate = $jobcandidate->JoinOnDt;
            }
           $connection->table('employee_general')->insert([
                'EmpCode' => $EmpCode,
                'CandidateId' => $JCId,
                'DataMove' => 'N',
                'EmpPass' => '',
                'EmpType' => 'E',
                'EmpStatus' => 'A',
                'NameTitle' => $jobcandidate->Title,
                'FName' => $jobcandidate->FName,
                'MName' => $jobcandidate->MName,
                'LName' => $jobcandidate->LName,
                'DOB' => $jobcandidate->DOB,
                'Gender' => $jobcandidate->Gender,
                'Aadhar' => $jobcandidate->Aadhaar,
                'Email1' => $jobcandidate->Email,
                'Email2' => $jobcandidate->Email2 ?? '',
                'Contact1' => $jobcandidate->Phone,
                'Contact2' => $jobcandidate->Phone2 ?? '',
                'Religion' => $jobcandidate->Religion == 'Others' ? $jobcandidate->OtherReligion : $jobcandidate->Religion,
                'Caste' => $jobcandidate->Caste == 'Other' ? $jobcandidate->OtherCaste : $jobcandidate->Caste,
                'MaritalStatus' => $jobcandidate->MaritalStatus,
                'marriage_dt' => $jobcandidate->MarriageDate ?? '',
                'DrivingLicense' => $jobcandidate->DLNo ?? '',
                'LValidity' => $jobcandidate->LValidity ?? '',
                'BloodGroup' => $jobcandidate->bloodgroup,
                'Skill' => $offer_basic->MW,
                'BU' => $offer_basic->BU,
                'Zone' => $offer_basic->Zone,
                'Region' => $offer_basic->Region,
                'Territory' => $offer_basic->Territory,
                'EmgContName_One' => $address_query->cont_one_name,
                'EmgContRelation_One' => $address_query->cont_one_relation,
                'EmgContPhone_One' => $address_query->cont_one_number,
                'EmgContName_Two' => $address_query->cont_two_name ?? '',
                'EmgContRelation_Two' => $address_query->cont_two_relation ?? '',
                'EmgContPhone_Two' => $address_query->cont_two_number ?? '',
                'CompanyId' => $CompanyId,
                'Grade' => $jobcandidate->Grade,
                'DepartmentId' => $jobcandidate->Department,
                'DesigId' => $jobcandidate->Designation,
                'SubDepartment' => $jobcandidate->SubDepartment ?? '',
                'Section' => $jobcandidate->Section ?? '',
                'DesigSuffix' => $jobcandidate->DesigSuffix ?? '',
                'PositionCode' => $jobcandidate->PositionCode ?? '',
                'PosSeq' => $jobcandidate->PosSeq ?? '',
                'PosVR' => $jobcandidate->PosVR ?? '',
                'Vertical' => $jobcandidate->VerticalId ?? '',

                'T_StateHq' => $jobcandidate->T_StateHq ?? '',
                'T_LocationHq' => $jobcandidate->T_LocationHq ?? '',
                'F_StateHq' => $jobcandidate->F_StateHq ?? '',
                'F_LocationHq' => $jobcandidate->F_LocationHq ?? '',

                'F_ReportingManager' => $jobcandidate->F_ReportingManager ?? '',
                'A_ReportingManager' => $jobcandidate->A_ReportingManager ?? '',
                'ServiceCondition' => $jobcandidate->ServiceCondition ?? '',
                'OrientationPeriod' => $jobcandidate->OrientationPeriod ?? '',
                'Stipend' => $jobcandidate->Stipend ?? '',
                'AFT_Grade' => $jobcandidate->AFT_Grade ?? '',
                'AFT_Designation' => $jobcandidate->AFT_Designation ?? '',
                'ServiceBond' => $jobcandidate->ServiceBond ?? '',
                'ServiceBondYears' => $jobcandidate->ServiceBondYears ?? '',
                'ServiceBondRefund' => $jobcandidate->ServiceBondRefund ?? '',
                'JoinOnDt' => $jobcandidate->JoinOnDt ?? '',
                'ConfirmationDate' => $ConfirmationDate ?? '',
                'NoticePeriod' => $jobcandidate->NoticePeriod ?? '',
                'ProbationNoticePeriod' => $jobcandidate->ProbationNoticePeriod ?? '',
                'PositionId' => '',
                'CreatedBy' => Auth::user()->id,
                'CreatedDate' => now(),
                'YearId' => '0',
               


            ]);
            DB::commit();
             $query = DB::table('candjoining')->where('JAId', $JAId)->update(['ForwardToESS' => 'Yes']);
            return response()->json(array('status'=>200,'message'=>'Employee Created Successfully'));
        }
    }
}
