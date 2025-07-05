<?php

namespace App\Http\Controllers\Common;

use App\Models\master_mrf;
use App\Helpers\LogActivity;
use App\Helpers\UserNotification;
use Illuminate\Http\Request;
use App\Mail\MrfCreationMail;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

use Illuminate\Support\Facades\Validator;


class ManualEntryController extends Controller
{
    public $company_list;
    public $department_list;
    public $state_list;
    public $institute_list;
    public $userlist;

    public function __construct()
    {
        $this->company_list = DB::table("core_company")->orderBy('company_code', 'desc')->pluck("company_code", "id");
        $this->department_list = DB::table("core_department")->where('is_active', '1')->orderBy('department_name', 'asc')->pluck("department_name", "id");
        $this->state_list = DB::table("states")->orderBy('StateName', 'asc')->pluck("StateName", "StateId");
        $this->institute_list = DB::table("master_institute")->Join('states', 'states.StateId', '=', 'master_institute.StateId')->where('CountryId', session('Set_Country'))->orderBy('InstituteName', 'asc')->pluck("InstituteName", "InstituteId");
        $this->userlist = DB::table("users")
            ->leftJoin('master_employee', 'users.id', '=', 'master_employee.EmployeeId')
            ->leftJoin('core_department', 'master_employee.DepartmentId', '=', 'core_department.id')
            ->where('users.role', 'H')
            ->orderBy('users.name', 'asc')
            ->select(DB::raw("CONCAT(users.name, ' (', master_employee.EmpCode, ' - ', core_department.department_name, ')') as name"), 'users.id')
            ->pluck('name', 'id');
    }

    function recruiter_mrf_entry()
    {
        $institute_list = DB::table("master_institute")->orderBy('InstituteName', 'asc')->pluck("InstituteName", "InstituteId");
        $company_list = DB::table("core_company")->orderBy('company_code', 'desc')->pluck("company_code", "id");
        $department_list = DB::table("core_department")->where('is_active', '1')->orderBy('department_name', 'asc')->pluck("department_name", "id");
        $state_list = DB::table("states")->orderBy('StateName', 'asc')->pluck("StateName", "StateId");
        $institute_list = DB::table("master_institute")->orderBy('InstituteName', 'asc')->pluck("InstituteName", "InstituteId");
        $designation_list = DB::table("core_designation")->where('designation_name', '!=', '')->orderBy('designation_name', 'asc')->pluck("designation_name", "id");
        $userlist = DB::table("users")
        ->leftJoin('master_employee', 'users.id', '=', 'master_employee.EmployeeId')
        ->leftJoin('core_department', 'master_employee.DepartmentId', '=', 'core_department.id')
        ->where('users.role', 'H')
        ->orderBy('master_employee.Fname', 'asc')
        ->select(DB::raw("CONCAT(users.name, ' (', master_employee.EmpCode, ' - ', core_department.department_name, ')') as name"), 'users.id')
        ->pluck('name', 'id');
        return view('common.recruiter_mrf_entry', compact('company_list', 'department_list', 'state_list', 'institute_list', 'designation_list', 'userlist'));
    }

    public function get_all_manual_mrf_created_by_me(Request $request)
    {
        $usersQuery = master_mrf::query();
        $Company = $request->Company;
        $Department = $request->Department;
        $Year = $request->Year;
        $Status = $request->Status;
        if ($Company != '') {
            $usersQuery->where("manpowerrequisition.CompanyId", $Company);
        }
        /* else{
            $usersQuery->where("manpowerrequisition.CompanyId", session('Set_Company'));
        } */
        if ($Department != '') {
            $usersQuery->where("manpowerrequisition.DepartmentId", $Department);
        }
        if ($Year != '') {
            $usersQuery->whereBetween('manpowerrequisition.CreatedTime', [$Year . '-01-01', $Year . '-12-31']);
        }
        /* else{
            $usersQuery->whereBetween('manpowerrequisition.CreatedTime', [date('Y') . '-01-01', date('Y') . '-12-31']);
        } */

        if ($Status != '') {
            $usersQuery->where("manpowerrequisition.Status", $Status);
        }
        /* else{
            $usersQuery->where("manpowerrequisition.Status", 'Approved');
        } */

        if (Auth::user()->role == 'R') {

            $usersQuery->where('CreatedBy', Auth::user()->id);
        }

        $mrf = $usersQuery->select('manpowerrequisition.MRFId', 'manpowerrequisition.Type', 'manpowerrequisition.JobCode', 'manpowerrequisition.CreatedBy', 'core_designation.designation_name', 'manpowerrequisition.Status', 'manpowerrequisition.CreatedTime')
            ->Join('core_designation', 'manpowerrequisition.DesigId', '=', 'core_designation.id', 'left')->orderBy('manpowerrequisition.CreatedTime', 'desc');

        return datatables()->of($mrf)
            ->addIndexColumn()
            ->addColumn('MRFDate', function ($mrf) {
                return date('d-m-Y', strtotime($mrf->CreatedTime));
            })

            ->editColumn('Type', function ($mrf) {
                if ($mrf->Type == 'N' || $mrf->Type == 'N_HrManual') {
                    return 'New MRF';
                } elseif ($mrf->Type == 'SIP' || $mrf->Type == 'SIP_HrManual') {
                    return 'SIP/Internship MRF';
                } elseif ($mrf->Type == 'Campus' || $mrf->Type == 'Campus_HrManual') {
                    return 'Campus MRF';
                } elseif ($mrf->Type == 'R' || $mrf->Type == 'R_HrManual') {
                    return 'Replacement MRF';
                }
            })
            ->addColumn('actions', function ($mrf) {

                return '<i class="bx bx-show text-success" style="font-size: 16px;cursor: pointer;" id="viewMRF" data-id=' . $mrf->MRFId . '></i>';
            })
            ->addColumn('delete', function ($mrf) {
                if ($mrf->Status == 'New') {
                    return '<i class="fadeIn animated bx bx-trash text-danger" style="font-size:16px;cursor:pointer" id="deleteMrf" data-id=' . $mrf->MRFId . '></i>';
                } else {
                    return '';
                }
            })
            ->addColumn('chk', function () {
                return '<input type="checkbox" class="select_all">';
            })
            ->editColumn('designation_name', function ($mrf) {
                if ($mrf->Type == 'SIP' || $mrf->Type == 'SIP_HrManual') {
                    return 'SIP/Internship';
                } else {
                    return $mrf->designation_name;
                }
            })
            ->rawColumns(['actions', 'delete', 'chk'])
            ->make(true);
    }

    function new_mrf_manual()
    {

        $params = array(
            'company_list' => $this->company_list,
            'department_list' => $this->department_list,
            'state_list' => $this->state_list,
            "institute_list" => $this->institute_list,
            "userlist" => $this->userlist,
        );
        return view('common.new_mrf_manual', compact('params'));
    }


    function sip_mrf_manual()
    {
        $company_list = $this->company_list;
        $department_list = $this->department_list;
        $state_list = $this->state_list;
        $institute_list = $this->institute_list;
        $userlist = $this->userlist;
        return view('common.sip_mrf_manual', compact('company_list', 'department_list', 'state_list', 'institute_list', 'userlist'));
    }

    function campus_mrf_manual()
    {
        $company_list = $this->company_list;
        $department_list = $this->department_list;
        $state_list = $this->state_list;
        $institute_list = $this->institute_list;
        $userlist = $this->userlist;
        return view('common.campus_mrf_manual', compact('company_list', 'department_list', 'state_list', 'institute_list', 'userlist'));
    }

    function replacement_mrf_manual()
    {
        $company_list = $this->company_list;
        $department_list = $this->department_list;
        $state_list = $this->state_list;
        $institute_list = $this->institute_list;
        $userlist = $this->userlist;

        return view('common.replacement_mrf_manual', compact('company_list', 'department_list', 'state_list', 'institute_list', 'userlist'));
    }


    public function add_new_mrf_manual(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'Reason' => 'required',
            'Company' => 'required',
            'Department' => 'required',
            'Designation' => 'required',
            'OnBehalf' => 'required',
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


            $locArray =[];
            // Check if $State is a non-empty array
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



            //$locArray_str = serialize($locArray);

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
                $UniversityArray = '';
            }

            $MRF = new master_mrf;
            $MRF->Type = 'N_HrManual';
            $MRF->Reason = $request->Reason;
            $MRF->CompanyId = $request->Company;
            $MRF->DepartmentId = $request->Department;
            $MRF->DesigId = $request->Designation;
            $MRF->Positions = array_sum($ManPower);
           // $MRF->LocationIds = $locArray_str;
            $MRF->MinCTC = $request->MinCTC;
            $MRF->MaxCTC = $request->MaxCTC;
            $MRF->WorkExp = $request->WorkExp;
            $MRF->Remarks = $request->Remark;
            $MRF->Info = convertData($request->JobInfo);
            $MRF->EducationId = $EduArray_str;
            $MRF->EducationInsId = $UniversityArray;
            $MRF->KeyPositionCriteria = $KpArray;
            $MRF->OnBehalf = $request->OnBehalf;
            $MRF->CreatedBy =  Auth::user()->id;
            $MRF->Status = 'New';
            $MRF->CountryId = session('Set_Country');
            $MRF->save();

            $InsertId = $MRF->MRFId;

            $jobCode = getcompany_code($request->Company) . '/' . getDepartmentCode($request->Department) . '/' . getDesignationCode($request->Designation) . '/' . $InsertId . '-' . date('Y');
            $query1 = DB::table('manpowerrequisition')
                ->where('MRFId', $InsertId)
                ->update(['JobCode' => $jobCode]);

            //=========Insert location and no of position in mrf_location_position table
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
            if (!$query1) {
                return response()->json(['status' => 400, 'msg' => 'Something went wrong..!!']);
            } else {
                LogActivity::addToLog('New Manual MRF ' . $jobCode . ' is created by ' . getFullName(Auth::user()->id), 'Create');
                UserNotification::notifyUser(1, 'MRF', 'New Manual MRF ' . $jobCode . ' is created by ' . getFullName(Auth::user()->id));
                $details = [
                    "subject" => 'New Manual MRF ' . $jobCode . ' is created by ' . getFullName(Auth::user()->id),
                    "Employee" => getFullName(Auth::user()->id),
                ];
                if (CheckCommControl(3) == 1) {  //if MRF created by Recruiter  communication control is on
                    Mail::to("khushboo.sahu@vnrseeds.com")->send(new MrfCreationMail($details));
                }
                return response()->json(['status' => 200, 'msg' => 'New Manual MRF has been successfully created.']);
            }
        }
    }


    public function add_sip_mrf_manual(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'Reason' => 'required',
            'Company' => 'required',
            'Department' => 'required',
            'OnBehalf' => 'required',

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
            $locArray =[];
            // Check if $State is a non-empty array
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
            }

            $KpArray_str = serialize($KpArray);


            $UniversityArray = array();
            if (isset($request->University)) {
                $UniversityArray = serialize($request->University);
            } else {
                $UniversityArray = '';
            }
            $MRF = new master_mrf;
            $MRF->Type = 'SIP_HrManual';
            $MRF->Reason = $request->Reason;
            $MRF->CompanyId = $request->Company;
            $MRF->DepartmentId = $request->Department;
            $MRF->Positions = array_sum($ManPower);
          //  $MRF->LocationIds = $locArray_str;
            $MRF->Stipend = $request->Stipend;
            $MRF->TwoWheeler = $request->two_wheeler;
            $MRF->DA = $request->da;
            $MRF->MinCTC = $request->MinCTC;
            $MRF->MaxCTC = $request->MaxCTC;
            $MRF->Remarks = $request->Remark;
            $MRF->OnBehalf = $request->OnBehalf;
            $MRF->Info = convertData($request->JobInfo);
            $MRF->EducationId = $EduArray_str;
            $MRF->EducationInsId = $UniversityArray;
            $MRF->Tr_Frm_Date = $request->Tr_Frm_Date;
            $MRF->Tr_To_Date = $request->Tr_To_Date;
            $MRF->KeyPositionCriteria = $KpArray_str;
            $MRF->CreatedBy =  Auth::user()->id;
            $MRF->Status = 'New';
            $MRF->CountryId = session('Set_Country');

            $MRF->save();

            $InsertId = $MRF->MRFId;

            $jobCode = getcompany_code($request->Company) . '/' . getDepartmentCode($request->Department) . '/SIP/' . $InsertId . '-' . date('Y');
            $query1 = DB::table('manpowerrequisition')
                ->where('MRFId', $InsertId)
                ->update(['JobCode' => $jobCode]);
            //=========Insert location and no of position in mrf_location_position table
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
            if (!$query1) {
                return response()->json(['status' => 400, 'msg' => 'Something went wrong..!!']);
            } else {
                LogActivity::addToLog('Manual SIP MRF ' . $jobCode . ' is created by ' . getFullName(Auth::user()->id), 'Create');
                UserNotification::notifyUser(1, 'MRF', 'SIP Manual MRF ' . $jobCode . ' is created by ' . getFullName(Auth::user()->id));
                $details = [
                    "subject" => 'Manual SIP MRF ' . $jobCode . ' is created by ' . getFullName(Auth::user()->id),
                    "Employee" => getFullName(Auth::user()->id),
                ];
                if (CheckCommControl(3) == 1) {  //if MRF created by Recruiter  communication control is on
                    Mail::to("khushboo.sahu@vnrseeds.com")->send(new MrfCreationMail($details));
                }
                return response()->json(['status' => 200, 'msg' => 'SIP/Internship MRF has been successfully created.']);
            }
        }
    }

    public function add_replacement_mrf_manual(Request $request)
    {
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
        $locArray =[];
        // Check if $State is a non-empty array
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
        }

        $KpArray_str = serialize($KpArray);

        $UniversityArray = array();
        if (isset($request->University)) {
            $UniversityArray = serialize($request->University);
        } else {
            $UniversityArray = '';
        }


        $MRF = new master_mrf;
        $MRF->Type = 'R_HrManual';
        $MRF->Reason = $request->Reason;
        $MRF->CompanyId = $CompanyId;
        $MRF->DepartmentId = $DepartmentId;
        $MRF->DesigId = $DesigId;
        $MRF->GradeId = $GradeId;
        $MRF->RepEmployeeID = $request->ReplacementFor;
        $MRF->Positions = 1;
        //$MRF->LocationIds = $locArray_str;
        $MRF->ExistCTC = $request->ExCTC;
        $MRF->MinCTC = $request->MinCTC;
        $MRF->MaxCTC = $request->MaxCTC;
        $MRF->WorkExp = $request->WorkExp;
        $MRF->Remarks = $request->Remark;
        $MRF->OnBehalf = $request->OnBehalf;
        $MRF->Info = convertData($request->JobInfo);
        $MRF->EducationId = $EduArray_str;
        $MRF->EducationInsId = $UniversityArray;
        $MRF->KeyPositionCriteria = $KpArray_str;
        $MRF->CreatedBy =  Auth::user()->id;
        $MRF->Status = 'New';
        $MRF->Reporting = 0;
        $MRF->CountryId = session('Set_Country');

        $query = $MRF->save();

        $InsertId = $MRF->MRFId;

        $jobCode = getcompany_code($CompanyId) . '/' . getDepartmentCode($DepartmentId) . '/' . getDesignationCode($DesigId) . '/' . $InsertId . '-' . date('Y');
        $query1 = DB::table('manpowerrequisition')
            ->where('MRFId', $InsertId)
            ->update(['JobCode' => $jobCode]);
        //=========Insert location and no of position in mrf_location_position table
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
        if (!$query1) {
            return response()->json(['status' => 400, 'msg' => 'Something went wrong..!!']);
        } else {
            LogActivity::addToLog('Manual Replacement MRF ' . $jobCode . ' is created by ' . getFullName(Auth::user()->id), 'Create');
            UserNotification::notifyUser(1, 'MRF', 'Replacement Manual MRF ' . $jobCode . ' is created by ' . getFullName(Auth::user()->id));
            $details = [
                "subject" => 'Manual Replacement MRF ' . $jobCode . ' is created by ' . getFullName(Auth::user()->id),
                "Employee" => getFullName(Auth::user()->id),
            ];
            if (CheckCommControl(3) == 1) {  //if MRF created by Recruiter  communication control is on
                Mail::to("khushboo.sahu@vnrseeds.com")->send(new MrfCreationMail($details));
            }
            return response()->json(['status' => 200, 'msg' => 'Manual Replacement MRF has been successfully created.']);
        }
    }

    public function add_campus_mrf_manual(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'Reason' => 'required',
            'Company' => 'required',
            'Department' => 'required',
            'Designation' => 'required',
            'OnBehalf' => 'required',
            'University' => 'required',
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
            $locArray =[];
            // Check if $State is a non-empty array
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
            }

            $KpArray_str = serialize($KpArray);


            $UniversityArray = array();
            if (isset($request->University)) {
                $UniversityArray = serialize($request->University);
            } else {
                $UniversityArray = '';
            }
            $MRF = new master_mrf;
            $MRF->Type = 'Campus_HrManual';
            $MRF->Reason = $request->Reason;
            $MRF->CompanyId = $request->Company;
            $MRF->DepartmentId = $request->Department;
            $MRF->DesigId = $request->Designation;
            $MRF->Positions = array_sum($ManPower);
            //$MRF->LocationIds = $locArray_str;
            // $MRF->MinCTC = $request->MinCTC;
            $MRF->MaxCTC = $request->MaxCTC;
            $MRF->WorkExp = $request->WorkExp;
            $MRF->Remarks = $request->Remark;
            $MRF->OnBehalf = $request->OnBehalf;
            $MRF->Info = convertData($request->JobInfo);
            $MRF->EducationId = $EduArray_str;
            $MRF->EducationInsId = $UniversityArray;
            $MRF->KeyPositionCriteria = $KpArray_str;
            $MRF->CountryId = session('Set_Country');
            $MRF->CreatedBy =  Auth::user()->id;
            $MRF->Status = 'New';

            $MRF->save();

            $InsertId = $MRF->MRFId;

            $jobCode = getcompany_code($request->Company) . '/' . getDepartmentCode($request->Department) . '/' . getDesignationCode($request->Designation) . '/' . $InsertId . '-' . date('Y');
            $query1 = DB::table('manpowerrequisition')
                ->where('MRFId', $InsertId)
                ->update(['JobCode' => $jobCode]);
            //=========Insert location and no of position in mrf_location_position table
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
            if (!$query1) {
                return response()->json(['status' => 400, 'msg' => 'Something went wrong..!!']);
            } else {
                LogActivity::addToLog('Manual Campus Hiring MRF ' . $jobCode . ' is created by ' . getFullName(Auth::user()->id), 'Create');
                UserNotification::notifyUser(1, 'MRF', 'Campus Manual MRF ' . $jobCode . ' is created by ' . getFullName(Auth::user()->id));
                $details = [
                    "subject" => 'Manual Campus Hiring MRF ' . $jobCode . ' is created by ' . getFullName(Auth::user()->id),
                    "Employee" => getFullName(Auth::user()->id),
                ];
                if (CheckCommControl(3) == 1) {  //if MRF created by Recruiter  communication control is on
                    Mail::to("khushboo.sahu@vnrseeds.com")->send(new MrfCreationMail($details));
                    Mail::to(getEmailID($request->OnBehalf))->send(new MrfCreationMail($details));
                }
                return response()->json(['status' => 200, 'msg' => 'Manual Campus Hiring MRF has been successfully created.']);
            }
        }
    }
}
