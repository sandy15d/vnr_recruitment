<?php

namespace App\Http\Controllers\Common;


use App\Helpers\CandidateActivityLog;
use App\Helpers\UserNotification;
use App\Mail\CandidateOfferStatusMail;
use App\Models\screening;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Mail\JoiningFormMail;
use App\Mail\OfferLetterMail;
use App\Mail\ReviewMail;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin\master_employee;
use App\Models\CandidateJoining;
use App\Models\jobapply;
use App\Models\jobpost;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\View;
use Mpdf\Mpdf;


class OfferLtrController extends Controller
{
    public function offer_letter(Request $request)
    {

        $company_list = DB::table("core_company")->orderBy('company_code', 'desc')->pluck("company_code", "id");
        $months = [1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April', 5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August', 9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'];
        $Company = $request->Company;
        $Department = $request->Department;
        $Year = $request->Year;
        $Month = $request->Month;
        $Gender = $request->Gender;
        $Status = $request->Status;
        $Name = $request->Name;
        $usersQuery = screening::query();

        if (Auth::user()->role == 'R') {

            //$usersQuery->where('jobpost.CreatedBy', Auth::user()->id);
            $usersQuery->where(function ($query) {
                $query->where('jobpost.CreatedBy', Auth::user()->id)
                    ->orWhere('manpowerrequisition.Allocated', Auth::user()->id);
            });
        }

        if ($Company != '') {
            $usersQuery->where("screening.SelectedForC", $Company);
        }
        if ($Department != '') {
            $usersQuery->where("screening.SelectedForD", $Department);
        }
        if ($Year != '') {
            $usersQuery->whereBetween('jobapply.ApplyDate', [$Year . '-01-01', $Year . '-12-31']);
        }
        if ($Month != '') {
            if ($Year != '') {
                $usersQuery->whereBetween('jobapply.ApplyDate', [$Year . '-' . $Month . '-01', $Year . '-' . $Month . '-31']);
            } else {
                $usersQuery->whereBetween('jobapply.ApplyDate', [date('Y') . '-' . $Month . '-01', date('Y') . '-' . $Month . '-31']);
            }
        }

        if ($Gender != '') {
            $usersQuery->where("jobcandidates.Gender", $Gender);
        }
        if ($Status != '') {
            if ($Status == 'Pending') {
                $usersQuery->where('offerletterbasic.OfferLetterSent', 'Yes')->where("offerletterbasic.Answer", null);
            } else {
                $usersQuery->where("offerletterbasic.Answer", $Status);
            }
        }

        if ($Name != '') {
            $usersQuery->where("jobcandidates.FName", 'like', "%$Name%");
        }

        $candidate_list = $usersQuery->select(
            'jobapply.JAId',
            'jobcandidates.FName',
            'jobcandidates.MName',
            'jobcandidates.LName',
            'jobcandidates.ReferenceNo',
            'jobcandidates.CandidateImage',
            'screening.SelectedForC',
            'screening.SelectedForD',
            'offerletterbasic.OfferLetterSent',
            'offerletterbasic.JoiningFormSent',
            'offerletterbasic.Answer',
            'offerletterbasic.OfferLtrGen',
            'offerletterbasic.OfferLetter',
            'candjoining.EmpCode',
            'candjoining.JoinOnDt',
            'offerletterbasic.SendReview',
            'jobpost.JobCode'
        )
            ->Join('jobapply', 'screening.JAId', '=', 'jobapply.JAId')
            ->Join('jobpost', 'jobpost.JPId', '=', 'jobapply.JPId')
            ->Join('manpowerrequisition', 'manpowerrequisition.MRFId', '=', 'jobpost.MRFId')
            ->Join('jobcandidates', 'jobapply.JCId', '=', 'jobcandidates.JCId')
            ->leftJoin('offerletterbasic', 'jobapply.JAId', '=', 'offerletterbasic.JAId')
            ->leftJoin('candjoining', 'jobapply.JAId', '=', 'candjoining.JAId')
            /*            ->leftJoin('offerletter_review', function ($join) {
                            $join->on('jobapply.JAId', '=', 'offerletter_review.JAId')
                                ->where('offerletter_review.CreatedTime', '=', function ($query) {
                                    $query->selectRaw('MAX(CreatedTime)')
                                        ->from('offerletter_review')
                                        ->whereColumn('JAId', 'jobapply.JAId')
                                        ->orderByDesc('ReviewId')
                                        ->limit(1);
                                });
                        })*/
            ->where('manpowerrequisition.CountryId', session('Set_Country'))
            ->whereNotNull('screening.SelectedForC')
            ->whereNotNull('screening.SelectedForD')
            ->where('screening.SelectedForC', '!=', '0')
            ->where('screening.SelectedForD', '!=', '0')
            ->where('offerletterbasic.Hr_Closure', '!=', 'Yes')
            ->where('candjoining.JoinOnDt', '=', null)
            ->where(function ($query) {
                $query->where('candjoining.JoinOnDt', '=', null)
                    ->orWhere('jobpost.Status', '=', 'Open');
            })
            ->orderBy('ScId', 'DESC');
        if ($Department != '' || $Company != '' || $Year != '' || $Month != '' || $Gender != '' || $Name != '' || $Status != '') {
            $candidate_list = $candidate_list->paginate(20);
            $candidate_list->appends(['Company' => $Company, 'Department' => $Department, 'Year' => $Year, 'Month' => $Month, 'Gender' => $Gender, 'Name' => $Name, 'Status' => $Status]);
        } else {
            $candidate_list = $candidate_list->paginate(20);
        }
        return view('offer_letter.offer_letter', compact('company_list', 'months', 'candidate_list'));
    }

    public function get_offerltr_basic_detail(Request $request)
    {
        $JAId = $request->JAId;

        $candidate_detail = DB::table('screening')->select(
            'offerletterbasic.*',
            'candidate_ctc.grsM_salary',
            'candidate_ctc.communication_allowance',
            'screening.SelectedForC',
            'screening.SelectedForD',
            'core_department.department_name',
            'jobcandidates.JCId',
            'jobcandidates.FName',
            'jobcandidates.MName',
            'jobcandidates.LName',
            'jobcandidates.FatherName'
        )
            ->join('jobapply', 'jobapply.JAId', '=', 'screening.JAId')
            ->join('jobcandidates', 'jobcandidates.JCId', '=', 'jobapply.JCId')
            ->leftJoin('offerletterbasic', 'offerletterbasic.JAId', '=', 'jobapply.JAId')
            ->leftJoin('core_department', 'core_department.id', '=', 'screening.SelectedForD')
            ->leftJoin('candidate_ctc', 'candidate_ctc.JAId', '=', 'jobapply.JAId')
            ->where('screening.JAId', $JAId)
            ->first();
        $company = $candidate_detail->SelectedForC;
        $Department = $candidate_detail->SelectedForD;
        if ($company == 1) {
            $grade_list = DB::table("master_grade")->where('GradeStatus', 'A')->where('CompanyId', $company)->where('GradeId', '>=', '61')->orderBy('GradeValue', 'ASC')->pluck("GradeId", "GradeValue");
        } else {
            $grade_list = DB::table("master_grade")->where('GradeStatus', 'A')->where('CompanyId', $company)->orderBy('GradeValue', 'desc')->orderBy('GradeValue', 'ASC')->pluck("GradeId", "GradeValue");
        }


        $grade_designation_list = DB::table('core_designation_department_mapping')
            ->join('core_designation', 'core_designation.id', '=', 'core_designation_department_mapping.designation_id')
            ->where('department_id', $Department)
            ->pluck("core_designation.id", "designation_name");
        $designation_list = DB::table("core_designation")
            ->leftJoin('core_designation_department_mapping', 'core_designation_department_mapping.designation_id', '=', 'core_designation.id')->where('is_active', '1')->where('department_id', $Department)->orderBy('designation_name', 'ASC')->pluck("core_designation.id", "designation_name");
        $vertical_list = DB::table("core_vertical")->orderBy('vertical_name', 'ASC')->pluck("id", "vertical_name");
        $department_list = DB::table("core_department")->where('is_active', '1')->orderBy('department_name', 'ASC')->pluck("id", "department_name");
        // Retrieve the 'fun_vertical_dept_id' for the given department
        $fun_vertical_dept_ids = DB::table('core_fun_vertical_dept_mapping')
            ->where('department_id', $Department)
            ->pluck('id');


        // Retrieve distinct sub_departments related to the found fun_vertical_dept_ids
        $sub_departments_ids = DB::table('core_department_subdepartment_mapping')
            ->whereIn('fun_vertical_dept_id', $fun_vertical_dept_ids)
            ->distinct()
            ->pluck('sub_department_id');
        $sub_department_list = DB::table('core_sub_department')->whereIn('id', $sub_departments_ids)->pluck('id', 'sub_department_name');

        $section_list = DB::table('core_department_section_mapping')->join('core_section', 'core_section.id', '=', 'core_department_section_mapping.section_id')->where('core_department_section_mapping.department_id', $Department)->pluck('core_section.id', 'core_section.section_name');
        $employee_list = master_employee::select('EmployeeID', DB::raw('CONCAT(Fname, " ",Sname, " ",Lname," - ",EmpCode) AS name'))
            ->where('CompanyId', $company)
            ->where('EmpStatus', 'A')
            ->where('EmployeeId','!=',223)
            ->pluck('name', 'EmployeeID');
        $perm_headquarter_list = DB::table("core_city_village")->where('id', $candidate_detail->F_LocationHq)->pluck("id", "city_village_name");
        $temp_headquarter_list = DB::table("core_city_village")->where('id', $candidate_detail->T_LocationHq)->pluck("id", "city_village_name");
        $temp1_headquarter_list = DB::table("core_city_village")->where('id', $candidate_detail->T_LocationHq1)->pluck("id", "city_village_name");
        $state_list = DB::table("core_state")->where('is_active', 1)->where('country_id', 1)->orderBy('state_name', 'ASC')->pluck("id", "state_name");
      
        $vehicle_policy_list = DB::table('policy_master')->where('DeptId', $Department)->where('CompanyId', $company)->orderBy('PolicyName', 'ASC')->pluck("PolicyId", "PolicyName");
        $bu_list = DB::table('core_business_unit')->where('is_active', 1)->where('business_type',1)->where('vertical_id',$candidate_detail->VerticalId)->orderBy('business_unit_name', 'ASC')->pluck("id", "business_unit_name");
        $zone_list = DB::table('core_bu_zone_mapping')->join('core_zone','core_zone.id','=','core_bu_zone_mapping.zone_id')->where('business_unit_id',$candidate_detail->BU)->orderBy('zone_name', 'asc')
            ->pluck("core_zone.id", "zone_name");
        $region_list = DB::table('core_zone_region_mapping')->join('core_region','core_region.id','=','core_zone_region_mapping.region_id')->where('zone_id', $candidate_detail->Zone)->orderBy('region_name', 'ASC')->pluck("core_region.id", "region_name");
        $territory_list = DB::table('core_region_territory_mapping')->join('core_territory','core_territory.id','=','core_region_territory_mapping.territory_id')->where('region_id', $candidate_detail->Region)->orderBy('territory_name', 'ASC')->pluck("core_territory.id", "territory_name");
        return response(array(
            'candidate_detail' => $candidate_detail,
            'grade_list' => $grade_list,
            'department_list' => $department_list,
            'sub_department_list' => $sub_department_list,
            'designation_list' => $designation_list,
            'section_list' => $section_list,
            'employee_list' => $employee_list,
            'perm_headquarter_list' => $perm_headquarter_list,
            'temp_headquarter_list' => $temp_headquarter_list,
            'state_list' => $state_list,
            'vertical_list' => $vertical_list,
            'grade_designation_list' => $grade_designation_list,
            'vehicle_policy_list' => $vehicle_policy_list,
            'bu_list' => $bu_list,
            'zone_list' => $zone_list,
            'region_list' => $region_list,
            'territory_list' => $territory_list,
            'status' => 200
        ));
    }

    public function get_designation_by_grade_department(Request $request)
    {
        $DepartmentId = $request->DepartmentId;
        $GradeId = $request->GradeId;
        $grade_designation_list = DB::table('master_grade_designation')
            ->select('core_designation.id', 'core_designation.designation_name')
            ->join('core_designation', 'core_designation.id', '=', 'master_grade_designation.designation_id')
            ->where('department_id', $DepartmentId)
            ->where(function ($query) use ($GradeId) {
                $query->where('grade_1', $GradeId)
                    ->orWhere('grade_2', $GradeId)
                    ->orWhere('grade_3', $GradeId)
                    ->orWhere('grade_4', $GradeId)
                    ->orWhere('grade_5', $GradeId);
            })
            ->pluck("id", "designation_name");
        return response(array('grade_designation_list' => $grade_designation_list, 'status' => 200));
    }

    public function update_offerletter_basic(Request $request)
    {


        $JAId = $request->Of_JAId;
        $jobApply = jobapply::find($JAId);
        $JCId = $jobApply->JCId;
        $Company = $request->SelectedForC;
        $Department = $request->SelectedForD;
        $SubDepartment = $request->SubDepartment;
        $Section = $request->Section;
        $Grade = $request->Grade;
        $Designation = $request->Designation;
        $DesigSuffix = $request->DesigSuffix;
        $Vertical = $request->Vertical;
        $MW = $request->MW;
        $permanent_chk = $request->permanent_chk ?? 0;
        $PermState = $request->Of_PermState;
        $PermHQ = $request->PermHQ;
        $PermCity = $request->Of_PermCity;
        $temporary_chk = $request->temporary_chk ?? 0;
        $TempState = $request->TempState;
        $TempHQ = $request->TempHQ ?? null;
        $TempCity = $request->TempCity;
        $TemporaryMonth = $request->TemporaryMonth ?? null;

        $TempState1 = $request->TempState1 ?? null;
        $TempHQ1 = $request->TempHQ1 ?? null;
        $TempCity1 = $request->TempCity1 ?? null;
        $TemporaryMonth1 = $request->TemporaryMonth1 ?? null;

        $administrative_chk = $request->administrative_chk ?? 0;
        $AdministrativeDepartment = $request->AdministrativeDepartment;
        $AdministrativeEmployee = $request->AdministrativeEmployee;
        $functional_chk = $request->functional_chk ?? 0;
        $FunctionalDepartment = $request->FunctionalDepartment;
        $FunctionalEmployee = $request->FunctionalEmployee ?? null;
        /*  $CTC = $request->CTC;*/
        $grsM_salary = $request->grsM_salary;
        $PF_Wage_Limit = $request->PF_Wage_Limit;
        $ServiceCond = $request->ServiceCond;
        $OrientationPeriod = $request->OrientationPeriod ?? null;
        $Stipend = $request->Stipend ?? null;
        $AftGrade = $request->AftGrade ?? 0;
        $AftDesignation = $request->AftDesignation ?? 0;
        $ServiceBond = $request->ServiceBond ?? null;
        $ServiceBondDuration = $request->ServiceBondDuration;
        $ServiceBondRefund = $request->ServiceBondRefund;
        $MedicalCheckup = $request->MedicalCheckup;
        $SignAuth = $request->SignAuth;
        $Remark = $request->Remark;
        $RepChk = $request->repchk;
        $DesignationRep = $request->DesignationRep;
        $NoticePeriod = null;
        $Region = $request->Region;
        $Zone = $request->Zone;
        $Territory = $request->Territory;
        $BU = $request->BU;
        $ProbationNoticePeriod = null;
        $MinBasicSalary = $request->MinBasicSalary;
        $RepLineVisibility = $request->RepLineVisibility;
        $VehiclePolicy = $request->vehicle_policy;
        $mobile_allow = $request->mobile_allow;
        //checkbox value
        $GPRS = isset($request->GPRS) ? 1 : 0;
        $Mobile_Remb = $request->Mobile_Remb;
        $Communication_Allowance = $request->Communication_Allowance ?? 'N';
        if ($Company == 1) {
            if ($ServiceCond == 'nopnot') {
                if ($Department == 6 || $Department == 3) {
                    $NoticePeriod = 90;
                } else {
                    $NoticePeriod = 30;
                }
            } else {
                if ($Department == 6 || $Department == 3) {
                    $ProbationNoticePeriod = 30;
                    $NoticePeriod = 90;
                } elseif ($Department == 2) {
                    $ProbationNoticePeriod = 90;
                    $NoticePeriod = 90;
                } else {
                    $ProbationNoticePeriod = 15;
                    $NoticePeriod = 30;
                }
            }
        } else {
            $ProbationNoticePeriod = 30;
            $NoticePeriod = 60;
        }

        $query = DB::table('offerletterbasic')
            ->where('JAId', $JAId)
            ->update(
                [
                    'Grade' => $Grade,
                    'VerticalId' => $Vertical,
                    'Region' => $Region ?? 0,
                    'Zone' => $Zone ?? 0,
                    'Territory' => $Territory ?? 0,
                    'BU' => $BU ?? 0,
                    'SubDepartment' => $SubDepartment ?? 0,
                    'Section' => $Section ?? 0,
                    'Designation' => $Designation,
                    'DesigSuffix' => $DesigSuffix,
                    'MW' => $MW,
                    'MinBasicSalary' => $MinBasicSalary,
                    'PF_Wage_Limit' => $PF_Wage_Limit,
                    'TempS' => $temporary_chk,
                    'T_StateHq' => $TempState,
                    'T_LocationHq' => $TempHQ,
                    'T_City' => $TempCity ?? null,
                    'TempM' => $TemporaryMonth,

                    'T_StateHq1' => $TempState1,
                    'T_LocationHq1' => $TempHQ1,
                    'T_City1' => $TempCity1,
                    'TempM1' => $TemporaryMonth1,

                    'FixedS' => $permanent_chk,
                    'F_StateHq' => $PermState,
                    'F_LocationHq' => $PermHQ,
                    'F_City' => $PermCity ?? null,
                    'Functional_R' => $functional_chk,
                    'Functional_Dpt' => $FunctionalDepartment,
                    'F_ReportingManager' => $FunctionalEmployee,
                    'Admins_R' => $administrative_chk,
                    'Admins_Dpt' => $AdministrativeDepartment,
                    'A_ReportingManager' => $AdministrativeEmployee,
                    /*'CTC' => $CTC,*/
                    'ServiceCondition' => $ServiceCond,
                    'OrientationPeriod' => $OrientationPeriod,
                    'Stipend' => $Stipend,
                    'AFT_Grade' => $AftGrade,
                    'AFT_Designation' => $AftDesignation,
                    'ServiceBond' => $ServiceBond,
                    'ServiceBondRefund' => $ServiceBondRefund,
                    'ServiceBondYears' => $ServiceBondDuration,

                    'NoticePeriod' => $NoticePeriod,
                    'ProbationNoticePeriod' => $ProbationNoticePeriod,
                    'PreMedicalCheckUp' => $MedicalCheckup,
                    'Remarks' => $Remark,
                    'SigningAuth' => $SignAuth,
                    'OfferLetter' => 1,
                    'repchk' => $RepChk,
                    'reporting_only_desig' => $DesignationRep,
                    'RepLineVisibility' => $RepLineVisibility,
                    'Vehicle_Policy' => $VehiclePolicy,
                    'Mobile_Handset' => $mobile_allow,
                    'GPRS' => $GPRS,
                    'Mobile_Remb' => $Mobile_Remb,
                    'LastUpdated' => now(),
                    'UpdatedBy' => Auth::user()->id
                ]
            );

        $check_ctc = DB::table('candidate_ctc')->where('JAId', $JAId)->first();
        $get_bonus = DB::table('minimum_wage_master')->where('Category', $MW)->where('CompanyId', $Company)->orderByDesc('CrDate')->first();

        $bonus = 0;
        $bonusM = 0;
        $basic = 0;
        $hra = 0;
        $special = 0;
        $pf = 0;
        $employer_pf = 0;
        $net_monthly = 0;
        $anualgrs = 0;
        $gratuity = 0;
        $total_ctc = 0;
        $medical = 0;
        $emplyESIC = 0;
        $emplyerESIC = 0;
        $variable_pay = 0;
        $final_ctc = 0;
        if (date("m") == 01 || date("m") == 02 || date("m") == 03 || date("m") == 10 || date("m") == 11 || date("m") == 12) {
            $bonus = $get_bonus->PerMonthOct;
        } else {
            $bonus = $get_bonus->PerMonthApr;
        }

        if ($bonus > 0) {
            $bonusTable = [
                1 => 0.20,
                2 => 0.20,
                3 => 0.0833
            ];
            $bonusM = round(($bonus * ($bonusTable[$Company] ?? 0)));
        }

        if ($PF_Wage_Limit === 'Actual') {

            //calculate Basic salary 50% of Gross Monthly salary
            $basic = round($grsM_salary * 0.5);
            if ($basic >= $MinBasicSalary) {
                $basic = $basic;
            } else {
                $basic = $MinBasicSalary;
            }
            if ($basic > 21000) {
                $bonusM = 0;
            }
            //calculate HRA, 40% of basic
            $hra = round($basic * 0.4);
            $check_hra = $grsM_salary - ($basic + $bonusM);
            if ($hra > $check_hra) {
                $hra = round($check_hra);
            }
            //calculate Special Allowance, Gross Salary – (Basic Salary+ HRA + Bonus)
            $special = $grsM_salary - ($basic + $hra + $bonusM);
            if ($special < 0) {
                $special = 0;
            }
            $anualgrs = round($grsM_salary * 12);
            //calculate pf, 12% of basic
            $pf = round($basic * 0.12);

            if ($grsM_salary <= 21000) {
                $emplyESIC = round($grsM_salary * 0.75 / 100);
                $emplyerESIC = round($anualgrs * 3.25 / 100);
                $medical = 3000;
            } else {
                $medical = 15000;
            }
            $employer_pf = round($pf * 12);
            $net_monthly = round($grsM_salary - ($pf + $emplyESIC));

            $gratuity = round(($basic * 15) / 26);
            $fixed_ctc = round($anualgrs + $gratuity + $employer_pf + $emplyerESIC + $medical);
            $variable_pay = round($anualgrs * 5 / 100);
            $total_ctc = $fixed_ctc + $variable_pay;
        }
        if ($PF_Wage_Limit === 'Ceiling') {
            //calculate Basic salary 50% of Gross Monthly salary
            $basic = round($grsM_salary * 0.5);
            if ($basic >= $MinBasicSalary) {
                $basic = $basic;
            } else {
                $basic = $MinBasicSalary;
            }
            if ($basic > 21000) {
                $bonusM = 0;
            }
            //calculate HRA, 40% of basic
            $hra = round($basic * 0.4);
            $check_hra = $grsM_salary - ($basic + $bonusM);
            if ($hra > $check_hra) {
                $hra = round($check_hra);
            }
            //calculate Special Allowance, Gross Salary – (Basic Salary+ HRA + Bonus)
            $special = $grsM_salary - ($basic + $hra + $bonusM);
            if ($special < 0) {
                $special = 0;
            }
            $anualgrs = round($grsM_salary * 12);
            //calculate pf, 12% of basic
            if ($basic >= 15000) {
                $pf = round(15000 * 0.12);
            } else {
                $pf = round($basic * 0.12);
            }


            if ($grsM_salary <= 21000) {
                $emplyESIC = round($grsM_salary * 0.75 / 100);
                $emplyerESIC = round($anualgrs * 3.25 / 100);
                $medical = 3000;
            } else {
                $medical = 15000;
            }
            $employer_pf = round($pf * 12);
            $net_monthly = round($grsM_salary - ($pf + $emplyESIC));

            $gratuity = round(($basic * 15) / 26);
            $fixed_ctc = round($anualgrs + $gratuity + $employer_pf + $emplyerESIC + $medical);
            $variable_pay = round($anualgrs * 5 / 100);
            $total_ctc = $fixed_ctc + $variable_pay;
        }
        $car_allowance = 0;
        $policy_conn = DB::connection('mysql3');
        if ($VehiclePolicy == 13) {
            $car_allowance_data = $policy_conn
                ->table(
                    'hrm_master_eligibility_policy_tbl' . $VehiclePolicy
                )
                ->where('GradeId', $Grade)
                ->first();
            if ($car_allowance_data == null) {
                $car_allowance = 0;
            } else {
                $car_allowance = intval($car_allowance_data->Fn36) * 12;
            }
        }

        $Communication_Allowance_Amount = $Communication_Allowance == 'Y' ? 4800 : 0;
        $total_gross_ctc = $total_ctc + $car_allowance + $Communication_Allowance_Amount;

        if ($check_ctc === null) {


            $query1 = DB::table('candidate_ctc')->insert(
                [
                    'JAId' => $JAId,
                    'bonus' => $bonusM,
                    'grsM_salary' => $grsM_salary,
                    'basic' => $basic,
                    'hra' => $hra,
                    'special_alw' => $special,
                    'emplyPF' => $pf,
                    'emplyerPF' => $employer_pf,
                    'netMonth' => $net_monthly,
                    'anualgrs' => $anualgrs,
                    'gratuity' => $gratuity,
                    'emplyESIC' => $emplyESIC,
                    'emplyerESIC' => $emplyerESIC,
                    'medical' => $medical,
                    'fixed_ctc' => $fixed_ctc,
                    'performance_pay' => $variable_pay,
                    'total_ctc' => $total_ctc,
                    'communication_allowance' => $Communication_Allowance,
                    'communication_allowance_amount' => $Communication_Allowance_Amount,
                    'car_allowance_amount' => $car_allowance,
                    'total_gross_ctc' => $total_gross_ctc,


                    'created_by' => Auth::user()->id,
                    'created_on' => now()
                ]
            );
        } else {
            $query2 = DB::table('candidate_ctc')->where('JAId', $JAId)
                ->update([
                    'bonus' => $bonusM,
                    'grsM_salary' => $grsM_salary,
                    'basic' => $basic,
                    'hra' => $hra,
                    'special_alw' => $special,
                    'emplyPF' => $pf,
                    'emplyerPF' => $employer_pf,
                    'netMonth' => $net_monthly,
                    'anualgrs' => $anualgrs,
                    'gratuity' => $gratuity,
                    'emplyESIC' => $emplyESIC,
                    'emplyerESIC' => $emplyerESIC,
                    'medical' => $medical,
                    'fixed_ctc' => $fixed_ctc,
                    'performance_pay' => $variable_pay,
                    'total_ctc' => $total_ctc,
                    'communication_allowance' => $Communication_Allowance,
                    'communication_allowance_amount' => $Communication_Allowance_Amount,
                    'car_allowance_amount' => $car_allowance,
                    'total_gross_ctc' => $total_gross_ctc,
                ]);
        }

        $sql2 = DB::table('candidate_entitlement')->where('JAId', $JAId)->first();
        if ($sql2 === null) {
            $query3 = DB::table('candidate_entitlement')->insert(
                [
                    'JAId' => $JAId,
                    'Created_by' => Auth::user()->id,
                    'Created_on' => now()
                ]
            );
        }

        $check = DB::table('master_eligibility')->where('CompanyId', $Company)->where('DepartmentId', $Department)->where('GradeId', $Grade)->where('VerticalId', $Vertical)->count();

        if ($check > 0) {
            $get_elg = DB::table('master_eligibility')->where('CompanyId', $Company)->where('DepartmentId', $Department)->where('GradeId', $Grade)->where('VerticalId', $Vertical)->first();
        } else {
            $get_elg = DB::table('master_eligibility')->where('CompanyId', $Company)->where('DepartmentId', $Department)->where('GradeId', $Grade)->where('VerticalId', 0)->first();
        }
        $dob = DB::table('jobcandidates')->where('JCId', $JCId)->value('DOB');
        $age = calculateAge($dob);

        $updateData = [
            'LoadCityA' => $get_elg->CategoryA,
            'LoadCityB' => $get_elg->CategoryB,
            'LoadCityC' => $get_elg->CategoryC,
            'DAOut' => $get_elg->DA_OutSiteHQ,
            'DAOut_Rmk' => $get_elg->DA_OutSiteHQ_Rmk,
            'DAHq' => $get_elg->DA_InSiteHQ,
            'DAHq_Rmk' => $get_elg->DA_InSiteHQ_Rmk,
            'TwoWheel' => $get_elg->TW_Km,
            'TwoWheel_Rmk' => !empty($get_elg->TW_InHQ_M) ? ($Department == 2 || in_array($Grade, [72, 73, 74, 75, 76, 77])) ? 'Min: ' . $get_elg->TW_InHQ_M . ' Km/Month ' . $get_elg->TW_InHQ_D : 'Max: ' . $get_elg->TW_InHQ_M . ' Km/Month ' . $get_elg->TW_InHQ_D : $get_elg->TW_InHQ_D,
            'FourWheel' => $get_elg->FW_Km,
            'FourWheel_Rmk' => !empty($get_elg->FW_InHQ_M) ? ($Department == 2 || in_array($Grade, [72, 73, 74, 75, 76, 77])) ? 'Min: ' . $get_elg->FW_InHQ_M . ' Km/Month ' . $get_elg->FW_InHQ_D : 'Max: ' . $get_elg->FW_InHQ_M . ' Km/Month ' . $get_elg->FW_InHQ_D : $get_elg->FW_InHQ_D,
            'Train' => $get_elg->Train_YN,
            'Train_Class' => ($get_elg->Train_Class == 'AC' || $get_elg->Train_Class == 'AC-I') ? 'AC-I' : $get_elg->Train_Class,
            'Train_Remark' => $get_elg->Train_Class_Rmk,
            'Flight' => $get_elg->Flight_YN,
            'Flight_Class' => $get_elg->Flight_Class,
            'Flight_Remark' => $get_elg->Flight_Class_Rmk,
            'Mobile_Allow' => $mobile_allow,
            'GPRS' => $GPRS,
            'Mobile' => ($mobile_allow == 'Y' && $GPRS == '1') ? $get_elg->Mobile_WithGPS : ($mobile_allow == 'Y' && $GPRS == '0' ? $get_elg->Mobile : null),
            'Mobile_Remb' => $Mobile_Remb,
            'MExpense' => ($Mobile_Remb == 'prepaid' || $Mobile_Remb == 'both') ? $get_elg->Mobile_Remb : null,
            'MTerm' => ($Mobile_Remb == 'prepaid' || $Mobile_Remb == 'both') ? $get_elg->Mobile_Remb_Period : null,
            'Mobile_Remb_Period_Rmk' => ($Mobile_Remb == 'prepaid' || $Mobile_Remb == 'both') ? $get_elg->Mobile_Remb_Period_Rmk : null,
            'Mobile_RembPost' => ($Mobile_Remb == 'postpaid' || $Mobile_Remb == 'both') ? $get_elg->Mobile_RembPost : null,
            'Mobile_RembPost_Period' => ($Mobile_Remb == 'postpaid' || $Mobile_Remb == 'both') ? $get_elg->Mobile_RembPost_Period : null,
            'Mobile_RembPost_Period_Rmk' => ($Mobile_Remb == 'postpaid' || $Mobile_Remb == 'both') ? $get_elg->Mobile_RembPost_Period_Rmk : null,
            'Laptop' => $get_elg->Laptop_Amt,
            'Laptop_Remark' => $get_elg->Laptop_Remark,
            'HealthIns' => $get_elg->Mediclaim_Coverage_Slabs,
            'Helth_CheckUp' => $age >= 40 ? $get_elg->Helth_CheckUp : null,
            'Helth_CheckUp_Remark' => $age >= 40 ? $get_elg->Helth_CheckUp_Rmk : null,
            'Vehicle_Policy' => $VehiclePolicy,
            'CostOfVehicle' => $get_elg->Vehicle_Value_Limit,
            'Term_Insurance' => $get_elg->Term_Insurance,
            'Term_Insurance_Rmk' => $get_elg->Term_Insurance_Rmk,
            'LastUpdated' => now(),
        ];
        $update_cand_elg = DB::table('candidate_entitlement')->where('JAId', $JAId)->update($updateData);


        if (!$query) {
            return response()->json(['status' => 400, 'msg' => 'Something went wrong..!!']);
        } else {
            $sql = DB::table('jobapply')->join('jobcandidates', 'jobcandidates.JCId', '=', 'jobapply.JCId')->select('jobapply.JCId', 'Aadhaar')->where('JAId', $JAId)->first();
            CandidateActivityLog::addToCandLog($sql->JCId, $sql->Aadhaar, 'Offer Letter Basic Details Updated');
            return response()->json(['status' => 200, 'msg' => 'Data has been changed successfully']);
        }
    }

    public function insert_ctc(Request $request)
    {
        $jaid = $request->jaid;
        $basic = $request->basic;
        $hra = $request->hra;
        $bonus = $request->bonus;
        $special_alw = $request->special_alw;
        $grsM_salary = $request->grsM_salary;
        $emplyPF = $request->emplyPF;
        $emplyESIC = $request->emplyESIC;
        $netMonth = $request->netMonth;
        $lta = $request->lta;
        $childedu = $request->childedu;
        $anualgrs = $request->anualgrs;
        $gratuity = $request->gratuity;
        $emplyerPF = $request->emplyerPF;
        $emplyerESIC = $request->emplyerESIC;
        $medical = $request->medical;
        $total_ctc = $request->total_ctc;
        $query1 = DB::table('candidate_ctc')->where('JAId', $jaid)->update(
            [
                'ctc_date' => now(),
                'basic' => $basic,
                'hra' => $hra,
                'bonus' => $bonus,
                'special_alw' => $special_alw,
                'grsM_salary' => $grsM_salary,
                'emplyPF' => $emplyPF,
                'emplyESIC' => $emplyESIC,
                'netMonth' => $netMonth,
                'lta' => $lta,
                'childedu' => $childedu,
                'anualgrs' => $anualgrs,
                'gratuity' => $gratuity,
                'emplyerPF' => $emplyerPF,
                'emplyerESIC' => $emplyerESIC,
                'medical' => $medical,
                'total_ctc' => $total_ctc,
                'created_on' => now(),
                'created_by' => Auth::user()->id
            ]
        );

        $query = DB::table('offerletterbasic')->where('JAId', $jaid)->update(
            [
                'CTC' => $total_ctc,
                'LastUpdated' => now(),
                'UpdatedBy' => Auth::user()->id
            ]
        );
        if ($emplyESIC > 0) {
            $query2 = DB::table('candidate_entitlement')->where('JAId', $jaid)->update(
                [
                    'HealthIns' => null,
                    'LastUpdated' => now(),
                ]
            );
        }
        if ($query1) {
            $sql = DB::table('jobapply')->join('jobcandidates', 'jobcandidates.JCId', '=', 'jobapply.JCId')->select('jobapply.JCId', 'Aadhaar')->where('JAId', $jaid)->first();
            CandidateActivityLog::addToCandLog($sql->JCId, $sql->Aadhaar, 'CTC Details Updated');
            return response()->json(['status' => 200, 'msg' => 'CTC Data has been changed successfully']);
        } else {
            return response()->json(['status' => 400, 'msg' => 'Something went wrong..!!']);
        }
    }

    public function insert_ent(Request $request)
    {
        $jaid = $request->jaid;
        $LoadCityA = $request->LoadCityA;
        $LoadCityB = $request->LoadCityB;
        $LoadCityC = $request->LoadCityC;
        $DAOut = $request->DAOut;
        $DAHq = $request->DAHq;
        $TwoWheel = $request->TwoWheel;
        $FourWheel = $request->FourWheel;
        $Train = $request->Train;
        $Train_Class = $request->Train_Class;
        $Flight = $request->Flight;
        $Flight_Class = $request->Flight_Class;
        $Flight_Remark = $request->Flight_Remark;
        $Mobile = $request->Mobile;
        $MExpense = $request->MExpense;
        $MTerm = $request->MTerm;
        $Laptop = $request->Laptop;
        $HealthIns = $request->HealthIns;
        $tline = $request->tline;
        $two_wheel_line = $request->two_wheel_line;
        $four_wheel_line = $request->four_wheel_line;
        $GPRS = $request->GPRS;

        $query1 = DB::table('candidate_entitlement')->where('JAId', $jaid)->update(
            [
                'EntDate' => now(),
                'LoadCityA' => $LoadCityA,
                'LoadCityB' => $LoadCityB,
                'LoadCityC' => $LoadCityC,
                'DAOut' => $DAOut,
                'DAHq' => $DAHq,
                'TwoWheel' => $TwoWheel,
                'FourWheel' => $FourWheel,
                'Train' => $Train,
                'Train_Class' => $Train_Class,
                'Flight' => $Flight,
                'Flight_Class' => $Flight_Class,
                'Flight_Remark' => $Flight_Remark,
                'Mobile' => $Mobile,
                'MExpense' => $MExpense,
                'MTerm' => $MTerm,
                'GPRS' => $GPRS,
                'Laptop' => $Laptop,
                'HealthIns' => $HealthIns,
                'TravelLine' => $tline,
                'TwoWheelLine' => $two_wheel_line,
                'FourWheelLine' => $four_wheel_line,

                'created_on' => now(),
                'created_by' => Auth::user()->id
            ]
        );

        if ($query1) {
            $sql = DB::table('jobapply')->join('jobcandidates', 'jobcandidates.JCId', '=', 'jobapply.JCId')->select('jobapply.JCId', 'Aadhaar')->where('JAId', $jaid)->first();
            CandidateActivityLog::addToCandLog($sql->JCId, $sql->Aadhaar, 'Eligibility Details Updated');
            return response()->json(['status' => 200, 'msg' => 'Entitlement Data has been changed successfully']);
        } else {
            return response()->json(['status' => 400, 'msg' => 'Something went wrong..!!']);
        }
    }

    public function offer_letter_generate(Request $request)
    {

        return view('offer_letter.offer_ltr_gen');
    }

    public function offer_ltr_gen(Request $request)
    {
        $JAId = $request->jaid;
        $RemarkHr = $request->RemarkHr ?? '';
        $jobapply = DB::table('jobapply')->where('JAId', $JAId)->first();
        $JCId = $jobapply->JCId;
        $sql = DB::table('screening')->where('JAId', $JAId)->first();
        $SelectedForC = $sql->SelectedForC;
        $SelectedForD = $sql->SelectedForD;
        $chk = DB::table('offerletterbasic_history')->select('Seq')->where('JAId', $JAId)->latest('CreatedTime')->first();
        if (is_null($chk)) {
            $seq = '0';
        } else {
            $seq = $chk->Seq;
        }
        if ($seq == 0) {
            $postfix_ltr = '';
        } elseif ($seq == 1) {
            $postfix_ltr = 'A';
        } elseif ($seq == 2) {
            $postfix_ltr = 'B';
        } elseif ($seq == 3) {
            $postfix_ltr = 'C';
        } elseif ($seq == 4) {
            $postfix_ltr = 'D';
        } elseif ($seq == 5) {
            $postfix_ltr = 'E';
        }

        $Month = date('M');
        $Year = date('Y');
        $LtrNo = getcompany_code($SelectedForC) . '_OL/' . getDepartmentCode($SelectedForD) . '/' . $Month . '-' . $Year . '/' . $JAId . $postfix_ltr;
        $LtrDate = now();
        $update_query = DB::table('offerletterbasic')->where('JAId', $JAId)->update(
            [
                'LtrNo' => $LtrNo,
                'LtrDate' => $LtrDate,
                'OfferLtrGen' => 1,
                'LastUpdated' => Auth::user()->id,
                'UpdatedBy' => now()
            ]
        );

        $ofltr = DB::table('offerletterbasic')->where('JAId', $JAId)->first();
        $ctc = DB::table('candidate_ctc')->where('JAId', $JAId)->first();
        $ent = DB::table('candidate_entitlement')->where('JAId', $JAId)->first();
        if ($chk == null) {
            $max_seq = 1;
        } else {
            $max_seq = $chk->Seq + 1;
        }

        $query1 = DB::table('offerletterbasic_history')->insert(
            [
                'Seq' => $max_seq,
                'JAId' => $JAId,
                'RevisionRemark' => $RemarkHr,
                'Company' => $ofltr->Company,
                'Grade' => $ofltr->Grade,
                'Department' => $ofltr->Department,
                'Designation' => $ofltr->Designation,
                'VerticalId' => $ofltr->VerticalId,
                'Zone' => $ofltr->Zone,
                'Region' => $ofltr->Region,
                'LtrNo' => $ofltr->LtrNo,
                'LtrDate' => $ofltr->LtrDate,
                'TempS' => $ofltr->TempS,

                'T_StateHq' => $ofltr->T_StateHq,
                'T_LocationHq' => $ofltr->T_LocationHq,
                'T_City' => $ofltr->T_City,
                'TempM' => $ofltr->TempM,

                'T_StateHq1' => $ofltr->T_StateHq1,
                'T_LocationHq1' => $ofltr->T_LocationHq1,
                'T_City1' => $ofltr->T_City1,
                'TempM1' => $ofltr->TempM1,

                'FixedS' => $ofltr->FixedS,
                'F_StateHq' => $ofltr->F_StateHq,
                'F_LocationHq' => $ofltr->F_LocationHq,
                'F_City' => $ofltr->F_City,
                'Functional_R' => $ofltr->Functional_R,
                'Functional_Dpt' => $ofltr->Functional_Dpt,
                'F_ReportingManager' => $ofltr->F_ReportingManager,
                'Admins_R' => $ofltr->Admins_R,
                'Admins_Dpt' => $ofltr->Admins_Dpt,
                'A_ReportingManager' => $ofltr->A_ReportingManager,
                'CTC' => $ofltr->CTC,
                'ServiceCondition' => $ofltr->ServiceCondition,
                'OrientationPeriod' => $ofltr->OrientationPeriod,
                'Stipend' => $ofltr->Stipend,
                'AFT_Grade' => $ofltr->AFT_Grade,
                'AFT_Designation' => $ofltr->AFT_Designation,
                'ServiceBond' => $ofltr->ServiceBond,
                'ServiceBondRefund' => $ofltr->ServiceBondRefund,
                'ServiceBondYears' => $ofltr->ServiceBondYears,
                'PreMedicalCheckUp' => $ofltr->PreMedicalCheckUp,
                'Remarks' => $ofltr->Remarks,
                'SigningAuth' => $ofltr->SigningAuth,

                'basic' => $ctc->basic,
                'hra' => $ctc->hra,
                'bonus' => $ctc->bonus,
                'special_alw' => $ctc->special_alw,
                'grsM_salary' => $ctc->grsM_salary,
                'emplyPF' => $ctc->emplyPF,
                'emplyESIC' => $ctc->emplyESIC,
                'netMonth' => $ctc->netMonth,
                'lta' => $ctc->lta,
                'childedu' => $ctc->childedu,
                'anualgrs' => $ctc->anualgrs,
                'gratuity' => $ctc->gratuity,
                'emplyerPF' => $ctc->emplyerPF,
                'emplyerESIC' => $ctc->emplyerESIC,
                'medical' => $ctc->medical,
                'total_ctc' => $ctc->total_ctc,

                'LoadCityA' => $ent->LoadCityA,
                'LoadCityB' => $ent->LoadCityB,
                'LoadCityC' => $ent->LoadCityC,
                'DAOut' => $ent->DAOut,
                'DAHq' => $ent->DAHq,
                'TwoWheel' => $ent->TwoWheel,
                'FourWheel' => $ent->FourWheel,
                'Train' => $ent->Train,
                'Train_Class' => $ent->Train_Class,
                'Flight' => $ent->Flight,
                'Flight_Class' => $ent->Flight_Class,
                'Flight_Remark' => $ent->Flight_Remark,
                'Mobile' => $ent->Mobile,
                'MExpense' => $ent->MExpense,
                'MTerm' => $ent->MTerm,
                'GPRS' => $ent->GPRS,
                'Laptop' => $ent->Laptop,
                'HealthIns' => $ent->HealthIns,
                'Helth_CheckUp' => $ent->Helth_CheckUp,
                'TravelLine' => $ent->TravelLine,
                'TwoWheelLine' => $ent->TwoWheelLine,
                'FourWheelLine' => $ent->FourWheelLine,
                'CreatedTime' => now(),
                'CreatedBy' => Auth::user()->id
            ]
        );

        if ($update_query) {
            $sql = DB::table('jobapply')->join('jobcandidates', 'jobcandidates.JCId', '=', 'jobapply.JCId')->select('jobapply.JCId', 'Aadhaar')->where('JAId', $JAId)->first();
            CandidateActivityLog::addToCandLog($sql->JCId, $sql->Aadhaar, 'Offer Letter Generated');
            return response()->json(['status' => 200, 'msg' => 'Offer Letter Generated Successfully']);
        } else {
            return response()->json(['status' => 400, 'msg' => 'Something went wrong..!!']);
        }
    }

    public function offer_ltr_print(Request $request)
    {
        //return view('offer_letter.offer_ltr_print');
        $jaid = $_GET['jaid'];
        $sql = DB::table('jobapply')->select(
            'jobcandidates.Title',
            'jobcandidates.FName',
            'jobcandidates.MName',
            'jobcandidates.LName',
            'offerletterbasic.SigningAuth',
        )->join('jobcandidates', 'jobapply.JCId', '=', 'jobcandidates.JCId')->leftJoin('offerletterbasic', 'jobapply.JAId', 'offerletterbasic.JAId')->where('jobapply.JAId', $jaid)->first();
        $candidate_name = $sql->FName . ' ' . $sql->MName . ' ' . $sql->LName;
        $signing_auth = $sql->SigningAuth;
        ini_set('memory_limit', -1);

        // $pdf = new mPDF(['utf-8', 'A4-C']);
        $pdf = new mPDF(['utf-8', 'A4-C', 'default_font_size' => 11]);

        $pdf->SetDefaultBodyCSS('font-family', 'freeserif');
        $pdf->setAutoBottomMargin = 'stretch';
        /* $pdf->WriteHTML('<div style="margin-bottom:30px;">&nbsp;</div>');*/

        $pdf->SetHTMLFooter('
                <div style="text-align: center; font-weight:bold;  height:80px;">
                <div style="float: left; width: 100%; text-align: center;"><br><br>Page {PAGENO} of {nbpg}</div>
                </div>
        ');

        $html = View::make('offer_letter.offer_ltr_print')->render();
        $pdf->SetTitle('Offer Letter');
        $pdf->WriteHTML($html);
        $pdf->Output('Offer Letter.pdf', 'I');
    }

    function offerLtrHistory(Request $request)
    {
        $JAId = $request->jaid;
        $query = DB::table('offerletterbasic_history')->select('offerletterbasic_history.*', DB::raw('DATE_FORMAT(offerletterbasic_history.LtrDate, "%d-%b-%Y") as OfDate'))->where('JAId', $JAId)->get();
        return response()->json(['status' => 200, 'data' => $query]);
    }

    public function offer_ltr_history(Request $request)
    {
        return view('offer_letter.offer_ltr_history');
    }

    public function getDetailForReview(Request $request)
    {
        $JAId = $request->JAId;
        $query = DB::table('jobapply')->join('jobcandidates', 'jobcandidates.JCId', '=', 'jobapply.JCId')->join('jobpost', 'jobpost.JPId', '=', 'jobapply.JPId')->select('jobcandidates.FName', 'jobcandidates.MName', 'jobcandidates.LName', 'jobpost.Title')->where('JAId', $JAId)->first();
        return response()->json(['status' => 200, 'data' => $query]);
    }

    public function saveJoinDate(Request $request)
    {
        $JAId = $request->JAId;
        $JoinDate = $request->JoinDate;
        $chk = DB::table('candjoining')->select('*')->where('JAId', $JAId)->count();
        if ($chk > 0) {
            $update_query = DB::table('candjoining')->where('JAId', $JAId)->update(
                [
                    'JoinOnDt' => $JoinDate,
                    'UpdatedBy' => Auth::user()->id,
                    'LastUpdated' => now()
                ]
            );  //update
        } else {
            $update_query = DB::table('candjoining')->insert(
                [
                    'JAId' => $JAId,
                    'JoinOnDt' => $JoinDate,
                    'CreatedBy' => Auth::user()->id,
                    'CreatedTime' => now()
                ]
            );  //insert
        }
        if ($update_query) {
            return response()->json(['status' => 200, 'msg' => 'Join Date Updated Successfully']);
        } else {
            return response()->json(['status' => 400, 'msg' => 'Something went wrong..!!']);
        }
    }

    public function candidate_offer_letter(Request $request)
    {
        return view('jobportal.offer_letter');
    }

    public function SendOfferLtr(Request $request)
    {
        $JAId = $request->JAId;
        $sendId = base64_encode($JAId);
        $query = DB::table('jobapply')->join('jobcandidates', 'jobcandidates.JCId', '=', 'jobapply.JCId')->join('jobpost', 'jobpost.JPId', '=', 'jobapply.JPId')->join('offerletterbasic', 'offerletterbasic.JAId', '=', 'jobapply.JAId')
            ->join('core_designation', 'core_designation.id', '=', 'offerletterbasic.Designation')
            ->select('jobcandidates.ReferenceNo', 'jobcandidates.FName', 'jobcandidates.MName', 'jobcandidates.LName', 'jobcandidates.Email', 'jobpost.Title', 'offerletterbasic.Company', 'offerletterbasic.Grade', 'core_designation.designation_name')->where('jobapply.JAId', $JAId)->first();
        $update = DB::table('offerletterbasic')->where('JAId', $JAId)->update(
            [
                'OfferLetterSent' => 'Yes',
                'UpdatedBy' => Auth::user()->id,
                'LastUpdated' => now()
            ]
        );

        $chk = DB::table('candjoining')->select('*')->where('JAId', $JAId)->count();
        if ($chk > 0) {
            $candJoin = DB::table('candjoining')->where('JAId', $JAId)->update(
                [
                    'LinkValidityStart' => now(),
                    'LinkValidityEnd' => now()->addDays(7),
                    'LinkStatus' => 'A',
                    'UpdatedBy' => Auth::user()->id,
                    'LastUpdated' => now()
                ]
            );  //update
        } else {
            $candJoin = DB::table('candjoining')->insert(
                [
                    'JAId' => $JAId,
                    'LinkValidityStart' => now(),
                    'LinkValidityEnd' => now()->addDays(7),
                    'LinkStatus' => 'A',
                    'CreatedBy' => Auth::user()->id,
                    'CreatedTime' => now()
                ]
            );  //insert
        }
        if ($update && $candJoin) {

            $details = [
                "candidate_name" => $query->FName . ' ' . $query->MName . ' ' . $query->LName,
                "reference_no" => $query->ReferenceNo,
                "job_title" => $query->designation_name,
                "company" => getcompany_name($query->Company),
                "grade" => getGradeValue($query->Grade),
                // "subject" => "Job Offer Letter for the post of " . $query->Title,
                "subject" => "Job Offer Letter ",
                "offer_link" => route('candidate-offer-letter') . '?jaid=' . $sendId
            ];

            Mail::to($query->Email)->send(new OfferLetterMail($details));
            $sql = DB::table('jobapply')->join('jobcandidates', 'jobcandidates.JCId', '=', 'jobapply.JCId')->select('jobapply.JCId', 'Aadhaar')->where('JAId', $JAId)->first();
            CandidateActivityLog::addToCandLog($sql->JCId, $sql->Aadhaar, 'Offer Letter Send to Candidate');
            return response()->json(['status' => 200, 'msg' => 'Offer Letter Sent Successfully']);
        } else {
            return response()->json(['status' => 400, 'msg' => 'Something went wrong..!!']);
        }
    }

    public function OfferResponse(Request $request)
    {
        $Answer = $request->Answer;
        $JAId = $request->JAId;
        $JoinOnDt = $request->JoinOnDt ?? null;
        $Place = $request->Place ?? null;
        $Date = $request->Date ?? null;
        $RejReason = $request->RejReason ?? null;

        $query = DB::table('offerletterbasic')->where('JAId', $JAId)->update(
            [
                'Answer' => $Answer,
                'RejReason' => $RejReason,
                'LastUpdated' => now()
            ]
        );

        $query1 = DB::table('candjoining')->where('JAId', $JAId)->update(
            [
                'Answer' => $Answer,
                'JoinOnDt' => $JoinOnDt,
                'Place' => $Place,
                'Date' => $Date,
                'RejReason' => $RejReason,
                'LastUpdated' => now()
            ]
        );

        if ($Answer == 'Accepted') {
            $sendId = base64_encode($JAId);
            $row = DB::table('jobapply')->join('jobcandidates', 'jobcandidates.JCId', '=', 'jobapply.JCId')
                ->join('offerletterbasic', 'offerletterbasic.JAId', '=', 'jobapply.JAId')
                ->join('jobpost', 'jobpost.JPId', '=', 'jobapply.JPId')
                ->select(
                    'jobcandidates.ReferenceNo',
                    'jobcandidates.FName',
                    'jobcandidates.MName',
                    'jobcandidates.LName',
                    'jobcandidates.Email',
                    'offerletterbasic.Designation',
                    'jobpost.CreatedBy'
                )
                ->where('jobapply.JAId', $JAId)->first();
            $details = [
                "candidate_name" => $row->FName . ' ' . $row->MName . ' ' . $row->LName,
                "reference_no" => $row->ReferenceNo,
                "subject" => "Welcome to VNR Family! Complete your pre-Onboarding documentation.",
                "link" => route('candidate-joining-form') . '?jaid=' . $sendId,
                "designation" => getDesignation($row->Designation)
            ];
            $recruiterId = $row->CreatedBy;
            $recruiterEmail = getEmailID($recruiterId);
            $details_offer = [
                "candidate_name" => $row->FName . ' ' . $row->MName . ' ' . $row->LName,
                'recruiter_name' => getFullName($recruiterId),
                "reference_no" => $row->ReferenceNo,
                'subject' => 'Offer Letter Status - ' . $Answer . ' ,by ' . $row->FName . ' ' . $row->MName . ' ' . $row->LName,
                'status' => $Answer
            ];
            DB::table('event_calendar')->insert([
                'title' => 'Joining',
                'description' => $row->FName . ' ' . $row->MName . ' ' . $row->LName . ' Will be join VNR as ' . getDesignation($row->Designation),
                'start_time' => $JoinOnDt,
                'end_time' => $JoinOnDt,
                'belong_to' => $row->CreatedBy,
                'type' => 'R'

            ]);
            Mail::to($row->Email)->send(new JoiningFormMail($details));
            Mail::to($recruiterEmail)->cc('recruitment@vnrseeds.com')->send(new CandidateOfferStatusMail($details_offer));
            $update = DB::table('offerletterbasic')->where('JAId', $JAId)->update(
                [
                    'JoiningFormSent' => 'Yes',
                    'LastUpdated' => now()
                ]
            );
        }
        if ($query && $query1) {
            $sql = DB::table('jobapply')->join('jobcandidates', 'jobcandidates.JCId', '=', 'jobapply.JCId')->select('jobapply.JCId', 'Aadhaar')->where('JAId', $JAId)->first();
            CandidateActivityLog::addToCandLog($sql->JCId, $sql->Aadhaar, 'Candidate Response to Offer Letter-' . $Answer);
            if ($Answer == 'Rejected') {
                CandidateActivityLog::addToCandLog($sql->JCId, $sql->Aadhaar, 'Candidate Offer Letter Rejection Reason -' . $RejReason);
            }
            return response()->json(['status' => 200, 'msg' => 'Response Submitted Successfully']);
        } else {
            return response()->json(['status' => 400, 'msg' => 'Something went wrong..!!']);
        }
    }

    public function OfferLetterResponse(Request $request)
    {
        return view('jobportal.offer_response_msg');
    }

    public function offerReopen(Request $request)
    {
        $JAId = $request->JAId;
        $query = DB::table('offerletterbasic')->where('JAId', $JAId)->update(
            [
                'Answer' => null,
                'OfferLetterSent' => null,
                'LastUpdated' => now()
            ]
        );

        $query1 = DB::table('candjoining')->where('JAId', $JAId)->update(
            [
                'LinkValidityStart' => null,
                'LinkValidityEnd' => null,
                'LinkStatus' => 'D',
                'Answer' => '',
                'JoinOnDt' => null,
                'Place' => '',
                'Date' => null,
                'RejReason' => '',
                'LastUpdated' => now()
            ]
        );

        if ($query && $query1) {
            return response()->json(['status' => 200, 'msg' => 'Offer Letter Reopen Successfully']);
        } else {
            return response()->json(['status' => 400, 'msg' => 'Something went wrong..!!']);
        }
    }

    public function send_for_review(Request $request)
    {
        $JAId = $request->ReviewJaid;
        $Company = $request->ReviewCompany;
        $Employee = $request->review_to;


        $update_query = DB::table('offerletterbasic')->where('JAId', $JAId)->update(
            [
                'SendReview' => '1',
                'LastUpdated' => now()
            ]
        );

        $getData = DB::table('offerletterbasic')->where('JAId', $JAId)->first();
        $Final = array();
        for ($i = 0; $i < Count($Employee); $i++) {
            $data = array(
                'JAId' => $JAId,
                'EmpCompany' => $Company,
                'OfferLetterNo' => $getData->LtrNo,
                'EmpId' => $Employee[$i],
                'EmpMail' => getEmployeeEmailId($Employee[$i]),
                'CreatedTime' => date('Y-m-d')
            );

            array_push($Final, $data);
        }

        $query = DB::table('offerletter_review')->insert($Final);

        $getData = DB::table('jobapply')->join('jobcandidates', 'jobcandidates.JCId', '=', 'jobapply.JCId')->join('jobpost', 'jobpost.JPId', '=', 'jobapply.JPId')->select('jobcandidates.FName', 'jobcandidates.MName', 'jobcandidates.LName', 'jobpost.Title')->where('jobapply.JAId', $JAId)->first();
        if ($update_query && $query) {
            for ($j = 0; $j < count($Employee); $j++) {
                $Fullname = $getData->FName . ' ' . $getData->MName . ' ' . $getData->LName;
                $details = [
                    "candidate_name" => $Fullname,
                    //"subject" => "For review - Offer Letter of " . $Fullname . " for the post of " . $getData->Title,
                    "subject" => "For review - Offer Letter of " . $Fullname,
                    "offer_link" => route('offer-letter-review') . '?jaid=' . $JAId . '&E=' . $Employee[$j]
                ];

                Mail::to(getEmployeeEmailId($Employee[$j]))->send(new ReviewMail($details));
            }
            $query = DB::table('jobapply')->join('jobcandidates', 'jobcandidates.JCId', '=', 'jobapply.JCId')->select('jobcandidates.JCId', 'jobcandidates.Aadhaar')->where('JAId', $JAId)->first();
            CandidateActivityLog::addToCandLog($query->JCId, $query->Aadhaar, 'Offer Letter Sent for Review');
            return response()->json(['status' => 200, 'msg' => 'Offer Letter Sent for Review Successfully']);
        } else {
            return response()->json(['status' => 400, 'msg' => 'Something went wrong..!!']);
        }
    }

    public function viewReview(Request $request)
    {
        $JAId = $request->JAId;
        $query = DB::table('offerletter_review')->join('master_employee', 'master_employee.EmployeeID', '=', 'offerletter_review.EmpId')->where('JAId', $JAId)->select('offerletter_review.*', DB::raw("CONCAT(master_employee.Fname,' ',master_employee.Lname) AS full_name"))->get();
        return response()->json(['status' => 200, 'data' => $query]);
    }

    public function offer_letter_review(Request $request)
    {
        return view('jobportal.review_offer_letter');
    }

    public function ReviewResponse(Request $request)
    {
        $JAId = $request->JAId;
        $EmpId = $request->EmpId;
        $Answer = $request->Answer;
        $RejReason = $request->RejReason ?? null;
        $query = DB::table('offerletter_review')->where('JAId', $JAId)->where('EmpId', $EmpId)->where('Status', null)->update(
            [
                'Status' => $Answer,
                'RejReason' => $RejReason,
                'ActionDate' => now(),
            ]
        );
        if ($query) {
            $sql = jobapply::where('JAId', $JAId)->join('jobcandidates', 'jobcandidates.JCId', '=', 'jobapply.JCId')->select('jobapply.*', 'jobcandidates.FName', 'jobcandidates.LName')->first();
            $JPId = $sql->JPId;
            $sql2 = jobpost::where('JPId', $JPId)->first();
            $Receuiter = $sql2->CreatedBy;
            UserNotification::notifyUser($Receuiter, 'Offer Letter Reviewed', 'Offer Letter of ' . $sql->FName . ' ' . $sql->LName . ' for the post of ' . $sql2->Title . ' has been reviewed.');
            return response()->json(['status' => 200, 'msg' => 'Response Submitted Successfully']);
        } else {
            return response()->json(['status' => 400, 'msg' => 'Something went wrong..!!']);
        }
    }

    public function saveEmpCode(Request $request)
    {
        $JAId = $request->JAId;
        $EmpCode = $request->EmpCode;

        $update_query = DB::table('candjoining')->where('JAId', $JAId)->update(
            [
                'EmpCode' => $EmpCode,
                'UpdatedBy' => Auth::user()->id,
                'LastUpdated' => now()
            ]
        );  //update

        if ($update_query) {
            return response()->json(['status' => 200, 'msg' => 'Employee Code Updated Successfully']);
        } else {
            return response()->json(['status' => 400, 'msg' => 'Something went wrong..!!']);
        }
    }

    public function candidate_joining(Request $request)
    {
        $company_list = DB::table("core_company")->orderBy('company_code', 'desc')->pluck("company_code", "id");
        $months = [1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April', 5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August', 9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'];
        $Company = $request->Company;
        $Department = $request->Department;
        $Year = $request->Year;
        $Month = $request->Month;
        $Gender = $request->Gender;
        $Status = $request->Status;
        $Name = $request->Name;

        $usersQuery = CandidateJoining::query();

        if (Auth::user()->role == 'R') {

            // $usersQuery->where('jobpost.CreatedBy', Auth::user()->id);
            $usersQuery->where(function ($query) {
                $query->where('jobpost.CreatedBy', Auth::user()->id)
                    ->orWhere('manpowerrequisition.Allocated', Auth::user()->id);
            });
        }

        if ($Company != '') {
            $usersQuery->where("screening.SelectedForC", $Company);
        }
        if ($Department != '') {
            $usersQuery->where("screening.SelectedForD", $Department);
        }
        if ($Year != '') {
            $usersQuery->whereBetween('jobapply.ApplyDate', [$Year . '-01-01', $Year . '-12-31']);
        }
        if ($Month != '') {
            if ($Year != '') {
                $usersQuery->whereBetween('jobapply.ApplyDate', [$Year . '-' . $Month . '-01', $Year . '-' . $Month . '-31']);
            } else {
                $usersQuery->whereBetween('jobapply.ApplyDate', [date('Y') . '-' . $Month . '-01', date('Y') . '-' . $Month . '-31']);
            }
        }


        if ($Status != '') {
            $usersQuery->where("candjoining.Joined", $Status);
        }

        if ($Name != '') {
            $usersQuery->where("jobcandidates.FName", 'like', "%$Name%");
        }

        $candidate_list = $usersQuery->select(
            'jobapply.JAId',
            'jobcandidates.FName',
            'jobcandidates.MName',
            'jobcandidates.LName',
            'jobcandidates.ReferenceNo',
            'jobcandidates.FinalSubmit',
            'screening.SelectedForC',
            'screening.SelectedForD',
            'candjoining.Verification',
            'candjoining.Joined',
            'candjoining.JoinOnDt',
            'candjoining.ForwardToESS'
        )
            ->Join('jobapply', 'candjoining.JAId', '=', 'jobapply.JAId')
            ->Join('jobpost', 'jobpost.JPId', '=', 'jobapply.JPId')
            ->Join('screening', 'screening.JAId', '=', 'jobapply.JAId')
            ->Join('offerletterbasic', 'offerletterbasic.JAId', '=', 'jobapply.JAId')
            ->Join('manpowerrequisition', 'manpowerrequisition.MRFId', '=', 'jobpost.MRFId')
            ->Join('jobcandidates', 'jobapply.JCId', '=', 'jobcandidates.JCId')
            /*  ->where('manpowerrequisition.Status', 'Approved') */
            ->where('manpowerrequisition.CountryId', session('Set_Country'))
            ->where('offerletterbasic.Answer', 'Accepted')
            ->where('candjoining.ForwardToESS', '=', 'No')
            ->where(function ($query) {
                $query->where('candjoining.Joined', '=', 'Yes')
                    ->orWhereNull('candjoining.Joined');
            })
            ->orderBy('candjoining.JoinOnDt', 'asc')
            ->paginate(20);
        return view('onboarding.candidate_joining', compact('company_list', 'months', 'candidate_list'));
    }

    public function SendJoiningForm(Request $request)
    {
        $JAId = $request->JAId;
        $sendId = base64_encode($JAId);
        $query = DB::table('jobapply')->join('jobcandidates', 'jobcandidates.JCId', '=', 'jobapply.JCId')
            ->join('jobpost', 'jobpost.JPId', '=', 'jobapply.JPId')
            ->join('offerletterbasic', 'offerletterbasic.JAId', '=', 'jobapply.JAId')
            ->select(
                'jobcandidates.ReferenceNo',
                'jobcandidates.FName',
                'jobcandidates.MName',
                'jobcandidates.LName',
                'jobcandidates.Email',
                'jobpost.Title',
                'offerletterbasic.Company',
                'offerletterbasic.Designation'
            )
            ->where('jobapply.JAId', $JAId)->first();
        $update = DB::table('offerletterbasic')->where('JAId', $JAId)->update(
            [
                'JoiningFormSent' => 'Yes',
                'LastUpdated' => now()
            ]
        );

        $details = [
            "candidate_name" => $query->FName . ' ' . $query->MName . ' ' . $query->LName,
            "designation" => getDesignation($query->Designation),
            "reference_no" => $query->ReferenceNo,
            "subject" => "Complete your Onboarding Process",
            "link" => route('candidate-joining-form') . '?jaid=' . $sendId
        ];
        Mail::to($query->Email)->send(new JoiningFormMail($details));
        return response()->json(['status' => 200, 'msg' => 'Joining Form Sent Successfully']);
    }

    public function update_two_wheel(Request $request)
    {
        // Retrieve request data
        $JAId = $request->input('JAId');
        $TwoWheel = $request->input('TwoWheel');

        // Perform the update using DB facade with correct syntax
        $update = DB::table('candidate_entitlement')
            ->where('JAId', $JAId)
            ->update(['TwoWheel' => $TwoWheel]);

        // Check if the update operation executed successfully
        if ($update !== false) {
            return response()->json(['status' => 200, 'msg' => 'Two Wheel Updated Successfully']);
        } else {
            return response()->json(['status' => 400, 'msg' => 'Two Wheel Not Updated']);
        }
    }


    public function update_four_wheel(Request $request)
    {
        $JAId = $request->JAId;
        $FourWheel = $request->FourWheel;
        // Perform the update using DB facade with correct syntax
        $update = DB::table('candidate_entitlement')
            ->where('JAId', $JAId)
            ->update(['$FourWheel' => $FourWheel]);

        // Check if the update operation executed successfully
        if ($update !== false) {
            return response()->json(['status' => 200, 'msg' => 'Four Wheel Updated Successfully']);
        } else {
            return response()->json(['status' => 400, 'msg' => 'Four Wheel Not Updated']);
        }
    }
}
