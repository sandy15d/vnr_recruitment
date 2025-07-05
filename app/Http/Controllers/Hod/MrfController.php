<?php

namespace App\Http\Controllers\Hod;

use App\Exports\MRFExport;
use App\Helpers\LogActivity;
use App\Helpers\UserNotification;
use App\Http\Controllers\Controller;
use App\Mail\MrfCreationMail;
use App\Models\master_mrf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;


class MrfController extends Controller
{
    public $company_list;
    public $department_list;
    public $state_list;
    public $institute_list;

    function manpowerrequisition()
    {
        $company_list = DB::table("core_company")->orderBy('company_code', 'desc')->pluck("company_code", "id");
        $department_list = DB::table("core_department")->where('is_active', '1')->orderBy('department_name', 'asc')->pluck("department_name", "id");
        $department_list1 = DB::table('manpowerrequisition')
            ->select('core_department.id', 'core_department.department_name')
            ->join('core_department', 'core_department.id', '=', 'manpowerrequisition.DepartmentId')

            ->where('OnBehalf', Auth::user()->id)
            ->orWhere('manpowerrequisition.CreatedBy', Auth::user()->id)
            ->orderBy('core_department.department_name', 'asc')
            ->groupBy('core_department.id')
            ->get();
        $recruiter_list = DB::table('users')->where('role', 'R')->where('Status', 'A')->pluck('name', 'id');
        $months = [1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April', 5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August', 9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'];
        $state_list = DB::table("states")->orderBy('StateName', 'asc')->pluck("StateName", "StateId");
        $institute_list = DB::table("master_institute")->orderBy('InstituteName', 'asc')->pluck("InstituteName", "InstituteId");
        $designation_list = DB::table("core_designation")->where('designation_name', '!=', '')->orderBy('designation_name', 'asc')->pluck("designation_name", "id");
        $employee_list = DB::table('master_employee')->orderBy('FullName', 'ASC')
            ->where('EmpStatus', 'A')
            ->select('EmployeeID', DB::raw('CONCAT(Fname, " ", Lname) AS FullName'))
            ->pluck("FullName", "EmployeeID");
        return view('hod.manpowerrequisition', compact('company_list', 'department_list', 'department_list1', 'state_list', 'institute_list', 'designation_list', 'employee_list', 'recruiter_list', 'months'));
    }


    public function __construct()
    {
        $this->company_list = DB::table("core_company")->orderBy('company_code', 'desc')->pluck("company_code", "id");
        $this->department_list = DB::table("core_department")->where('is_active', '1')->orderBy('department_name', 'asc')->pluck("department_name", "id");
        $this->state_list = DB::table("states")->orderBy('StateName', 'asc')->pluck("StateName", "StateId");
        $this->institute_list = DB::table("master_institute")->join('states', 'states.StateId', '=', 'master_institute.StateId')->where('states.CountryId', session('Set_Country'))->orderBy('InstituteName', 'asc')->pluck("InstituteName", "InstituteId");
        $this->country_list = DB::table("core_country")->orderBy('id', 'asc')->pluck("country_name", "id");
    }


    function new_mrf()
    {

        $params = array(
            'company_list' => $this->company_list,
            'department_list' => $this->department_list,
            'state_list' => $this->state_list,
            "institute_list" => $this->institute_list,
            "country_list" => $this->country_list,
        );
        return view('hod.hod_new_mrf', compact('params'));
    }


    function sip_mrf()
    {
        $company_list = $this->company_list;
        $department_list = $this->department_list;
        $state_list = $this->state_list;
        $institute_list = $this->institute_list;
        return view('hod.hod_sip_mrf', compact('company_list', 'department_list', 'state_list', 'institute_list'));
    }

    function campus_mrf()
    {
        $company_list = $this->company_list;
        $department_list = $this->department_list;
        $state_list = $this->state_list;
        $institute_list = $this->institute_list;
        return view('hod.hod_campus_mrf', compact('company_list', 'department_list', 'state_list', 'institute_list'));
    }


    public function addNewMrf(Request $request)
    {
        $reporting = DB::table('master_employee_hierarchy')->where('Employee', Auth::user()->id)->first(['Reporting']);
        $hod = DB::table('master_employee_hierarchy')->where('Employee', Auth::user()->id)->first(['HOD']);
        $management = DB::table('master_employee_hierarchy')->where('Employee', Auth::user()->id)->first(['Management']);
        $validator = Validator::make($request->all(), [
            'Reason' => 'required',
            'Company' => 'required',
            'Department' => 'required',
            'Designation' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 400, 'error' => $validator->errors()->toArray()]);
        } else {

            $State = $request->State;
            $City = $request->City;
            $ManPower = $request->ManPower;
            $Education = $request->Education;
            $Specialization = $request->Specialization;
            $KeyPosition = $request->KeyPosition;
            $locArray = [];
            if (!empty($State) && is_array($State)) {
                // Iterate over the array with index
                for ($lc = 0; $lc < count($State); $lc++) {
                    // Fetch corresponding values safely using null coalescing to avoid undefined index errors
                    $location = [
                        "State" => $State[$lc] ?? '',  // Use null coalescing for safety
                        "City" => $City[$lc] ?? '',    // Default to empty string if not set
                        "Nop" => $ManPower[$lc] ?? 0,  // Default to 0 if manpower is not set
                    ];
                    // Add location to the array
                    array_push($locArray, $location);
                }
            }

            $Eduarray = array();
            if ($Education != '') {
                for ($count = 0; $count < Count($Education); $count++) {

                    $e = array(
                        "e" => $Education[$count],
                        "s" => $Specialization[$count]
                    );
                    array_push($Eduarray, $e);
                }
            }
            $EduArray_str = serialize($Eduarray);

            $KpArray = array();
            if ($KeyPosition != '') {
                for ($i = 0; $i < Count($KeyPosition); $i++) {
                    $KP = addslashes($KeyPosition[$i]);
                    array_push($KpArray, $KP);
                }
                $KpArray = serialize($KpArray);
            }

            $UniversityArray = array();
            if (isset($request->University)) {
                $UniversityArray = serialize($request->University);
            } else {
                $UniversityArray = null;
            }

            $MRF = new master_mrf;
            $MRF->Type = 'N';
            $MRF->Reason = $request->Reason;
            $MRF->CountryId = session('Set_Country');
            $MRF->CompanyId = $request->Company;
            $MRF->DepartmentId = $request->Department;
            $MRF->DesigId = $request->Designation;
            $MRF->Positions = array_sum($ManPower);
            $MRF->LocationIds = $locArray_str;
            $MRF->MinCTC = $request->MinCTC;
            $MRF->MaxCTC = $request->MaxCTC;
            $MRF->WorkExp = $request->WorkExp;
            $MRF->Remarks = $request->Remark;
            $MRF->Info = convertData($request->JobInfo);
            $MRF->EducationId = $EduArray_str;
            $MRF->EducationInsId = $UniversityArray;
            $MRF->KeyPositionCriteria = $KpArray;
            $MRF->CreatedBy = Auth::user()->id;
            $MRF->Status = 'New';
            $MRF->reporting_id = $reporting->Reporting;
            $MRF->hod_id = $hod->HOD;
            $MRF->management_id = $management->Management;
            $MRF->CountryId = session('Set_Country');
            if (Auth::user()->is_management == 'Y') {
                $MRF->reporting_approve = 'Y';
                $MRF->hod_approve = 'Y';
                $MRF->management_approve = 'Y';
            }
            $MRF->save();

            $InsertId = $MRF->MRFId;

            // Insert into the database
            foreach ($locArray as $loc) {
                // Use the correct array access syntax for 'City'
                DB::table('mrf_location_position')->insert([
                    'MRFId' => $InsertId,
                    'State' => $loc['State'],
                    'City' => $loc['City'],  // Corrected array access
                    'Nop' => $loc['Nop'],
                ]);
            }
            $jobCode = getcompany_code($request->Company) . '/' . getDepartmentCode($request->Department) . '/' . getDesignationCode($request->Designation) . '/' . $InsertId . '-' . date('Y');
            $query1 = DB::table('manpowerrequisition')
                ->where('MRFId', $InsertId)
                ->update(['JobCode' => $jobCode]);
            if (!$query1) {
                return response()->json(['status' => 400, 'msg' => 'Something went wrong..!!']);
            } else {
                if (Auth::user()->is_management == 'N') {
                    if ($reporting->Reporting != 0) {
                        $reporting_mail = getEmailID($reporting->Reporting);
                        if ($reporting_mail != "") {
                            if (CheckCommControl(18) == 1) {  //if Send for MRF Approval By User communication control is on
                                $mrf_details = [
                                    "Employee" => getFullName(Auth::user()->id),
                                    "subject" => 'MRF Approval Request',
                                ];
                                Mail::to($reporting_mail)->send(new MrfCreationMail($mrf_details));
                            }
                        }
                    }
                }
                LogActivity::addToLog('New MRF ' . $jobCode . ' is created by ' . getFullName(Auth::user()->id), 'Create');
                UserNotification::notifyUser(1, 'MRF', 'New MRF ' . $jobCode . ' is created by ' . getFullName(Auth::user()->id));
                $details = [
                    "subject" => 'New MRF ' . $jobCode . ' is created by ' . getFullName(Auth::user()->id),
                    "Employee" => getFullName(Auth::user()->id),
                ];

                //  if (CheckCommControl(2) == 1) {  //if MRF created by employee communication control is on
                Mail::to("khushboo.sahu@vnrseeds.com")->send(new MrfCreationMail($details));
                // }


                return response()->json(['status' => 200, 'msg' => 'New MRF has been successfully created.']);
            }
        }
    }


    public function addSipMrf(Request $request)
    {
        $reporting = DB::table('master_employee_hierarchy')->where('Employee', Auth::user()->id)->first(['Reporting']);
        $hod = DB::table('master_employee_hierarchy')->where('Employee', Auth::user()->id)->first(['HOD']);
        $management = DB::table('master_employee_hierarchy')->where('Employee', Auth::user()->id)->first(['Management']);
        $validator = Validator::make($request->all(), [
            'Reason' => 'required',
            'Company' => 'required',
            'Department' => 'required',

        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 400, 'error' => $validator->errors()->toArray()]);
        } else {

            $State = $request->State;
            $City = $request->City;
            $ManPower = $request->ManPower;
            $Education = $request->Education;
            $Specialization = $request->Specialization;
            $KeyPosition = $request->KeyPosition;

            $locArray = [];
            if (!empty($State) && is_array($State)) {
                // Iterate over the array with index
                for ($lc = 0; $lc < count($State); $lc++) {
                    // Fetch corresponding values safely using null coalescing to avoid undefined index errors
                    $location = [
                        "State" => $State[$lc] ?? '',  // Use null coalescing for safety
                        "City" => $City[$lc] ?? '',    // Default to empty string if not set
                        "Nop" => $ManPower[$lc] ?? 0,  // Default to 0 if manpower is not set
                    ];
                    // Add location to the array
                    array_push($locArray, $location);
                }
            }

            $Eduarray = array();
            if ($Education != '') {
                for ($count = 0; $count < Count($Education); $count++) {

                    $e = array(
                        "e" => $Education[$count],
                        "s" => $Specialization[$count]
                    );
                    array_push($Eduarray, $e);
                }
            }
            $EduArray_str = serialize($Eduarray);

            $KpArray = array();
            if ($KeyPosition != '') {
                for ($i = 0; $i < Count($KeyPosition); $i++) {
                    $KP = addslashes($KeyPosition[$i]);
                    array_push($KpArray, $KP);
                }
                $KpArray = serialize($KpArray);
            }

            $UniversityArray = array();
            if (isset($request->University)) {
                $UniversityArray = serialize($request->University);
            } else {
                $UniversityArray = null;
            }
            $MRF = new master_mrf;
            $MRF->Type = 'SIP';
            $MRF->Reason = $request->Reason;
            $MRF->CompanyId = $request->Company;
            $MRF->DepartmentId = $request->Department;
            $MRF->Positions = array_sum($ManPower);
            $MRF->LocationIds = $locArray_str;
            $MRF->Stipend = $request->Stipend;
            $MRF->TwoWheeler = $request->two_wheeler;
            $MRF->DA = $request->da;
            $MRF->MinCTC = $request->MinCTC;
            $MRF->MaxCTC = $request->MaxCTC;
            $MRF->Remarks = $request->Remark;
            $MRF->Info = convertData($request->JobInfo);
            $MRF->EducationId = $EduArray_str;
            $MRF->EducationInsId = $UniversityArray;
            $MRF->KeyPositionCriteria = $KpArray;
            $MRF->Tr_Frm_Date = $request->Tr_Frm_Date;
            $MRF->Tr_To_Date = $request->Tr_To_Date;
            $MRF->CreatedBy = Auth::user()->id;
            $MRF->Status = 'New';
            $MRF->reporting_id = $reporting->Reporting;
            $MRF->hod_id = $hod->HOD;
            $MRF->management_id = $management->Management;
            $MRF->CountryId = session('Set_Country');
            if (Auth::user()->is_management == 'Y') {
                $MRF->reporting_approve = 'Y';
                $MRF->hod_approve = 'Y';
                $MRF->management_approve = 'Y';
            }
            $MRF->save();

            $InsertId = $MRF->MRFId;
            // Insert into the database
            foreach ($locArray as $loc) {
                // Use the correct array access syntax for 'City'
                DB::table('mrf_location_position')->insert([
                    'MRFId' => $InsertId,
                    'State' => $loc['State'],
                    'City' => $loc['City'],  // Corrected array access
                    'Nop' => $loc['Nop'],
                ]);
            }
            $jobCode = getcompany_code($request->Company) . '/' . getDepartmentCode($request->Department) . '/SIP/' . $InsertId . '-' . date('Y');
            $query1 = DB::table('manpowerrequisition')
                ->where('MRFId', $InsertId)
                ->update(['JobCode' => $jobCode]);

            if (!$query1) {
                return response()->json(['status' => 400, 'msg' => 'Something went wrong..!!']);
            } else {
                if (Auth::user()->is_management == 'N') {
                    if ($reporting->Reporting != 0) {
                        $reporting_mail = getEmailID($reporting->Reporting);
                        if ($reporting_mail != "") {
                            if (CheckCommControl(18) == 1) {  //if Send for MRF Approval By User communication control is on
                                $mrf_details = [
                                    "Employee" => getFullName(Auth::user()->id),
                                    "subject" => 'MRF Approval Request',
                                ];
                                Mail::to($reporting_mail)->send(new MrfCreationMail($mrf_details));
                            }
                        }
                    }
                }
                LogActivity::addToLog('SIP MRF ' . $jobCode . ' is created by ' . getFullName(Auth::user()->id), 'Create');
                UserNotification::notifyUser(1, 'MRF', 'SIP MRF ' . $jobCode . ' is created by ' . getFullName(Auth::user()->id));
                $details = [
                    "subject" => 'SIP MRF ' . $jobCode . ' is created by ' . getFullName(Auth::user()->id),
                    "Employee" => getFullName(Auth::user()->id),
                ];
                // if (CheckCommControl(2) == 1) {  //if MRF created by employee communication control is on
                Mail::to("khushboo.sahu@vnrseeds.com")->send(new MrfCreationMail($details));
                // }

                return response()->json(['status' => 200, 'msg' => 'SIP/Internship MRF has been successfully created.']);
            }
        }
    }

    public function addRepMrf(Request $request)
    {
        $reporting = DB::table('master_employee_hierarchy')->where('Employee', Auth::user()->id)->first(['Reporting']);
        $hod = DB::table('master_employee_hierarchy')->where('Employee', Auth::user()->id)->first(['HOD']);
        $management = DB::table('master_employee_hierarchy')->where('Employee', Auth::user()->id)->first(['Management']);
        $sql = DB::table('master_employee')->select('CompanyId', 'GradeId', 'DepartmentId', 'DesigId')->where('EmployeeID', $request->ReplacementFor)->first();
        $CompanyId = $sql->CompanyId;
        $GradeId = $sql->GradeId;
        $DepartmentId = $sql->DepartmentId;
        $DesigId = $sql->DesigId;

        $State = $request->State;
        $City = $request->City;

        $Education = $request->Education;
        $Specialization = $request->Specialization;
        $KeyPosition = $request->KeyPosition;

        $locArray = array();
        if ($State != '') {
            $location = array(
                "state" => $State,
                "city" => $City,
                "nop" => '1',
            );
            array_push($locArray, $location);
        }
        $locArray_str = serialize($locArray);

        $Eduarray = array();
        if ($Education != '') {
            for ($count = 0; $count < Count($Education); $count++) {

                $e = array(
                    "e" => $Education[$count],
                    "s" => $Specialization[$count]
                );
                array_push($Eduarray, $e);
            }
        }
        $EduArray_str = serialize($Eduarray);

        $KpArray = array();
        if ($KeyPosition != '') {
            for ($i = 0; $i < Count($KeyPosition); $i++) {
                $KP = addslashes($KeyPosition[$i]);
                array_push($KpArray, $KP);
            }
            $KpArray = serialize($KpArray);
        }

        $UniversityArray = array();
        if (isset($request->University)) {
            $UniversityArray = serialize($request->University);
        } else {
            $UniversityArray = null;
        }


        $MRF = new master_mrf;
        $MRF->Type = 'R';
        $MRF->Reason = "For Replacment Of " . getFullName($request->ReplacementFor);
        $MRF->CompanyId = $CompanyId;
        $MRF->DepartmentId = $DepartmentId;
        $MRF->DesigId = $DesigId;
        $MRF->GradeId = $GradeId;
        $MRF->RepEmployeeID = $request->ReplacementFor;
        $MRF->Positions = 1;
        $MRF->LocationIds = $locArray_str;
        $MRF->Reporting = '';
        $MRF->ExistCTC = $request->ExCTC;
        $MRF->MinCTC = $request->MinCTC;
        $MRF->MaxCTC = $request->MaxCTC;
        $MRF->WorkExp = $request->WorkExp;
        $MRF->Remarks = $request->Remark;
        $MRF->Info = convertData($request->JobInfo);
        $MRF->EducationId = $EduArray_str;
        $MRF->EducationInsId = $UniversityArray;
        $MRF->KeyPositionCriteria = $KpArray;
        $MRF->CreatedBy = Auth::user()->id;
        $MRF->Status = 'New';
        $MRF->Reporting = 0;
        $MRF->reporting_id = $reporting->Reporting;
        $MRF->hod_id = $hod->HOD;
        $MRF->management_id = $management->Management;
        $MRF->CountryId = session('Set_Country');
        if (Auth::user()->is_management == 'Y') {
            $MRF->reporting_approve = 'Y';
            $MRF->hod_approve = 'Y';
            $MRF->management_approve = 'Y';
        }
        $query = $MRF->save();

        $InsertId = $MRF->MRFId;
        // Insert into the database
        foreach ($locArray as $loc) {
            // Use the correct array access syntax for 'City'
            DB::table('mrf_location_position')->insert([
                'MRFId' => $InsertId,
                'State' => $loc['State'],
                'City' => $loc['City'],  // Corrected array access
                'Nop' => $loc['Nop'],
            ]);
        }
        $jobCode = getcompany_code($CompanyId) . '/' . getDepartmentCode($DepartmentId) . '/' . getDesignationCode($DesigId) . '/' . $InsertId . '-' . date('Y');
        $query1 = DB::table('manpowerrequisition')
            ->where('MRFId', $InsertId)
            ->update(['JobCode' => $jobCode]);

        if (!$query1) {
            return response()->json(['status' => 400, 'msg' => 'Something went wrong..!!']);
        } else {
            if (Auth::user()->is_management == 'N') {
                if ($reporting->Reporting != 0) {
                    $reporting_mail = getEmailID($reporting->Reporting);
                    if ($reporting_mail != "") {
                        if (CheckCommControl(18) == 1) {  //if Send for MRF Approval By User communication control is on
                            $mrf_details = [
                                "Employee" => getFullName(Auth::user()->id),
                                "subject" => 'MRF Approval Request',
                            ];
                            Mail::to($reporting_mail)->send(new MrfCreationMail($mrf_details));
                        }
                    }
                }
            }
            LogActivity::addToLog('Replacement MRF ' . $jobCode . ' is created by ' . getFullName(Auth::user()->id), 'Create');
            UserNotification::notifyUser(1, 'MRF', 'Replacement MRF ' . $jobCode . ' is created by ' . getFullName(Auth::user()->id));
            $details = [
                "subject" => 'Replacement MRF ' . $jobCode . ' is created by ' . getFullName(Auth::user()->id),
                "Employee" => getFullName(Auth::user()->id),
            ];

            if (CheckCommControl(2) == 1) {  //if MRF created by employee communication control is on
                Mail::to("khushboo.sahu@vnrseeds.com")->send(new MrfCreationMail($details));
            }
            return response()->json(['status' => 200, 'msg' => 'New MRF has been successfully created.']);
        }
    }


    public function addCampusMrf(Request $request)
    {
        $reporting = DB::table('master_employee_hierarchy')->where('Employee', Auth::user()->id)->first(['Reporting']);
        $hod = DB::table('master_employee_hierarchy')->where('Employee', Auth::user()->id)->first(['HOD']);
        $management = DB::table('master_employee_hierarchy')->where('Employee', Auth::user()->id)->first(['Management']);
        $validator = Validator::make($request->all(), [
            'Reason' => 'required',
            'Company' => 'required',
            'Department' => 'required',
            'Designation' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 400, 'error' => $validator->errors()->toArray()]);
        } else {

            $State = $request->State;
            $City = $request->City;
            $ManPower = $request->ManPower;
            $Education = $request->Education;
            $Specialization = $request->Specialization;
            $KeyPosition = $request->KeyPosition;

            $locArray = [];
            if (!empty($State) && is_array($State)) {
                // Iterate over the array with index
                for ($lc = 0; $lc < count($State); $lc++) {
                    // Fetch corresponding values safely using null coalescing to avoid undefined index errors
                    $location = [
                        "State" => $State[$lc] ?? '',  // Use null coalescing for safety
                        "City" => $City[$lc] ?? '',    // Default to empty string if not set
                        "Nop" => $ManPower[$lc] ?? 0,  // Default to 0 if manpower is not set
                    ];
                    // Add location to the array
                    array_push($locArray, $location);
                }
            }

            $Eduarray = array();
            if ($Education != '') {
                for ($count = 0; $count < Count($Education); $count++) {

                    $e = array(
                        "e" => $Education[$count],
                        "s" => $Specialization[$count]
                    );
                    array_push($Eduarray, $e);
                }
            }
            $EduArray_str = serialize($Eduarray);

            $KpArray = array();
            if ($KeyPosition != '') {
                for ($i = 0; $i < Count($KeyPosition); $i++) {
                    $KP = addslashes($KeyPosition[$i]);
                    array_push($KpArray, $KP);
                }
                $KpArray = serialize($KpArray);
            }

            $UniversityArray = array();
            if (isset($request->University)) {
                $UniversityArray = serialize($request->University);
            } else {
                $UniversityArray = null;
            }
            $MRF = new master_mrf;
            $MRF->Type = 'Campus';
            $MRF->Reason = $request->Reason;
            $MRF->CompanyId = $request->Company;
            $MRF->DepartmentId = $request->Department;
            $MRF->DesigId = $request->Designation;
            $MRF->Positions = array_sum($ManPower);
            $MRF->LocationIds = $locArray_str;
            $MRF->MinCTC = $request->MinCTC;
            $MRF->MaxCTC = $request->MaxCTC;
            $MRF->WorkExp = $request->WorkExp;
            $MRF->Remarks = $request->Remark;
            $MRF->Info = convertData($request->JobInfo);
            $MRF->EducationId = $EduArray_str;
            $MRF->EducationInsId = $UniversityArray;
            $MRF->KeyPositionCriteria = $KpArray;
            $MRF->CreatedBy = Auth::user()->id;
            $MRF->Status = 'New';
            $MRF->reporting_id = $reporting->Reporting;
            $MRF->hod_id = $hod->HOD;
            $MRF->management_id = $management->Management;
            $MRF->CountryId = session('Set_Country');
            if (Auth::user()->is_management == 'Y') {
                $MRF->reporting_approve = 'Y';
                $MRF->hod_approve = 'Y';
                $MRF->management_approve = 'Y';
            }
            $MRF->save();

            $InsertId = $MRF->MRFId;
            // Insert into the database
            foreach ($locArray as $loc) {
                // Use the correct array access syntax for 'City'
                DB::table('mrf_location_position')->insert([
                    'MRFId' => $InsertId,
                    'State' => $loc['State'],
                    'City' => $loc['City'],  // Corrected array access
                    'Nop' => $loc['Nop'],
                ]);
            }
            $jobCode = getcompany_code($request->Company) . '/' . getDepartmentCode($request->Department) . '/' . getDesignationCode($request->Designation) . '/' . $InsertId . '-' . date('Y');
            $query1 = DB::table('manpowerrequisition')
                ->where('MRFId', $InsertId)
                ->update(['JobCode' => $jobCode]);

            if (!$query1) {
                return response()->json(['status' => 400, 'msg' => 'Something went wrong..!!']);
            } else {
                if (Auth::user()->is_management == 'N') {
                    if ($reporting->Reporting != 0) {
                        $reporting_mail = getEmailID($reporting->Reporting);
                        if ($reporting_mail != "") {
                            if (CheckCommControl(18) == 1) {  //if Send for MRF Approval By User communication control is on
                                $mrf_details = [
                                    "Employee" => getFullName(Auth::user()->id),
                                    "subject" => 'MRF Approval Request',
                                ];
                                Mail::to($reporting_mail)->send(new MrfCreationMail($mrf_details));
                            }
                        }
                    }
                }
                LogActivity::addToLog('Campus Hiring MRF ' . $jobCode . ' is created by ' . getFullName(Auth::user()->id), 'Create');
                UserNotification::notifyUser(1, 'MRF', 'Campus MRF ' . $jobCode . ' is created by ' . getFullName(Auth::user()->id));
                $details = [
                    "subject" => 'Campus Hiring MRF ' . $jobCode . ' is created by ' . getFullName(Auth::user()->id),
                    "Employee" => getFullName(Auth::user()->id),
                ];
                if (CheckCommControl(2) == 1) {  //if MRF created by employee communication control is on
                    Mail::to("khushboo.sahu@vnrseeds.com")->send(new MrfCreationMail($details));
                }
                return response()->json(['status' => 200, 'msg' => 'Campus Hiring MRF has been successfully created.']);
            }
        }
    }

    public function getAllMRFCreatedByMe(Request $request)
    {
        $filters = $request->only(['Department', 'Type', 'Status', 'Year', 'Month', 'Recruiter']);

        $mrfQuery = master_mrf::query()
            ->leftJoin('core_designation', 'manpowerrequisition.DesigId', '=', 'core_designation.id')
            ->leftJoin('jobpost', 'jobpost.MRFId', '=', 'manpowerrequisition.MRFId')
            ->leftJoin('jobapply', 'jobapply.JPId', '=', 'jobpost.JPId')
            ->where('manpowerrequisition.CountryId', session('Set_Country'))
            ->where(function ($query) use ($filters) {
                $query->where('manpowerrequisition.CreatedBy', Auth::user()->id)
                    ->orWhere('onBehalf', Auth::user()->id);
            })
            ->select(
                'manpowerrequisition.MRFId',
                'manpowerrequisition.Type',
                'manpowerrequisition.JobCode',
                'manpowerrequisition.CreatedBy',
                'core_designation.designation_name',
                'manpowerrequisition.Status',
                'manpowerrequisition.CreatedTime',
                'manpowerrequisition.CloseDt',
                'manpowerrequisition.Allocated',
                'reporting_approve',
                'hod_approve',
                'management_approve'
            )
            ->orderBy('manpowerrequisition.MRFId', 'desc')
            ->groupBy('manpowerrequisition.MRFId');

        if ($filters['Department']) {
            $mrfQuery->where('manpowerrequisition.DepartmentId', $filters['Department']);
        }

        if ($filters['Type']) {
            $mrfQuery->where('manpowerrequisition.Type', $filters['Type']);
        }

        /*   if ($filters['Status']) {
            $mrfQuery->where('manpowerrequisition.Status', $filters['Status']);
        } */

        if ($filters['Year']) {
            $mrfQuery->whereYear('manpowerrequisition.CreatedTime', $filters['Year']);
        }

        if ($filters['Month']) {
            $mrfQuery->whereMonth('manpowerrequisition.CreatedTime', $filters['Month']);
        }

        if ($filters['Recruiter']) {
            $mrfQuery->where('manpowerrequisition.Allocated', $filters['Recruiter']);
        }

        // Set 'Approved' and 'New' as default statuses, exclude 'Close'
        $status = $filters['Status'] ?? ['Approved', 'New'];
        $mrfQuery->where('manpowerrequisition.Status', $status);
        return datatables()->of($mrfQuery)
            ->addIndexColumn()
            ->addColumn('MRFDate', function ($mrf) {
                return date('d-m-Y', strtotime($mrf->CreatedTime));
            })
            ->addColumn('MRFCloseDate', function ($mrf) {
                return $mrf->CloseDt ? date('d-m-Y', strtotime($mrf->CloseDt)) : '';
            })
            ->editColumn('Type', function ($mrf) {
                $types = [
                    'N' => 'New',
                    'N_HrManual' => 'New',
                    'SIP' => 'SIP/Internship',
                    'SIP_HrManual' => 'SIP/Internship',
                    'Campus' => 'Campus',
                    'Campus_HrManual' => 'Campus',
                    'R' => 'Replacement',
                    'R_HrManual' => 'Replacement',
                ];
                return $types[$mrf->Type] ?? '';
            })
            ->addColumn('actions', function ($mrf) {
                if ($mrf->Status == 'New') {
                    return '<i class="fa fa-pen text-danger" style="font-size: 16px;cursor: pointer;" id="viewMRF" data-id=' . $mrf->MRFId . '></i> <i class="fadeIn animated bx bx-trash text-danger" style="font-size:16px;cursor:pointer" id="deleteMrf" data-id=' . $mrf->MRFId . '></i>';
                } else {
                    return '';
                }
            })
            ->editColumn('details', function ($mrf) {
                return '<a href="' . route('candidate_association', ['mrf' => base64_encode($mrf->MRFId), 'filter' => 'total']) . '" target="_blank"><i class="fa fa-eye text-success" style="font-size: 16px; cursor: pointer;" data-id=' . $mrf->MRFId . '></i></a>';
            })
            ->editColumn('recruiter', function ($mrf) {
                return $mrf->Allocated ? getFullName($mrf->Allocated) : '';
            })
            ->addColumn('chk', function () {
                return '<input type="checkbox" class="select_all">';
            })
            ->addColumn('delete', function ($mrf) {
                if ($mrf->Status !== 'New') {
                    return '';
                }

                if ($mrf->reporting_approve === 'Y' || $mrf->hod_approve === 'Y' || $mrf->management_approve === 'Y') {
                    return '';
                }

                return '<i class="fadeIn animated bx bx-trash text-danger" style="font-size: 16px; cursor: pointer" id="deleteMrf" data-id=' . $mrf->MRFId . '></i>';
            })
            ->editColumn('reporting_approve', function ($mrf) {
                return $mrf->reporting_approve == 'Y' ? 'Yes' : 'No';
            })
            ->editColumn('hod_approve', function ($mrf) {
                return $mrf->hod_approve == 'Y' ? 'Yes' : 'No';
            })
            ->editColumn('management_approve', function ($mrf) {
                return $mrf->management_approve == 'Y' ? 'Yes' : 'No';
            })
            ->addColumn('actions1', function ($mrf) {

                return '<i class="fa fa-eye text-info" style="font-size: 16px;cursor: pointer;" id="viewMRF" data-id=' . $mrf->MRFId . '></i>';
            })
            ->editColumn('Status', function ($mrf) {
                if ($mrf->Status == 'Approved') {
                    return 'Active';
                } else {
                    return $mrf->Status;
                }
            })
            ->rawColumns(['actions', 'details', 'chk', 'delete', 'actions1'])
            ->make(true);
    }

    public function approveMRF(Request $request)
    {
        $mrf = master_mrf::find($request->MRFId);

        if (Auth::user()->id == $mrf->reporting_id) {
            $mrf->reporting_approve = 'Y';
            $mrf->reporting_remark = $request->remarks;
            $mrf->reporting_approve_date = date('Y-m-d');
        }
        if (Auth::user()->id == $mrf->hod_id) {
            $mrf->hod_approve = 'Y';
            $mrf->hod_remark = $request->remarks;
            $mrf->hod_approve_date = date('Y-m-d');
        }
        if (Auth::user()->id == $mrf->management_id) {
            $mrf->management_approve = 'Y';
            $mrf->management_remark = $request->remarks;
            $mrf->management_approve_date = date('Y-m-d');
        }
        $mrf->save();
        return response()->json(['status' => 200]);
    }

    public function rejectMRF(Request $request)
    {
        $mrf = master_mrf::find($request->MRFId);

        if (Auth::user()->id == $mrf->reporting_id) {
            $mrf->reporting_approve = 'R';
            $mrf->reporting_remark = $request->remarks;
            $mrf->reporting_approve_date = date('Y-m-d');
        }
        if (Auth::user()->id == $mrf->hod_id) {
            $mrf->hod_approve = 'R';
            $mrf->hod_remark = $request->remarks;
            $mrf->hod_approve_date = date('Y-m-d');
        }
        if (Auth::user()->id == $mrf->management_id) {
            $mrf->management_approve = 'R';
            $mrf->management_remark = $request->remarks;
            $mrf->management_approve_date = date('Y-m-d');
        }
        $mrf->save();
        return response()->json(['status' => 200]);
    }
}
