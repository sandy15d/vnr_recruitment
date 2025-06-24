<?php


use App\Models\Admin\communication_controll;
use App\Models\Admin\master_elg;
use App\Models\Admin\master_employee;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

if (!function_exists('getFullName')) {

    /**
     * Get full name by retrieving employee details
     *
     * @param integer|null $employeeId The ID of the employee. Null values return empty strings.
     * @return string The full name of the employee or empty string if no match was found.
     */
    function getFullName($employeeId): string
    {
        // if null, return empty string to avoid unnecessary database query
        if ($employeeId === null) {
            return '';
        }

        // if 1, return "Admin" to avoid database query altogether
        if ($employeeId === 1) {
            return 'Admin';
        }

        // use Laravel's Query Builder to retrieve employee data more efficiently
        $employee = DB::table('master_employee')->select('Title', 'Fname', 'Sname', 'Lname')->where('EmployeeID', $employeeId)->first();

        // if employee data not found, return empty string
        if ($employee === null) {
            return '';
        }

        // combine employee name fields into full name and return properly formatted
        $fullNameParts = array_filter([$employee->Title, $employee->Fname, $employee->Sname, $employee->Lname], function ($part) {
            return $part !== null && trim($part) !== "";
        });
        $fullName = ucwords(strtolower(implode(" ", $fullNameParts)));

        return $fullName;
    }
}

if (!function_exists('getFullNameByEmail')) {

    /**
     * Get full name by retrieving employee details
     *
     * @param integer|null $employeeId The ID of the employee. Null values return empty strings.
     * @return string The full name of the employee or empty string if no match was found.
     */
    function getFullNameByEmail($email): string
    {
        // if null, return empty string to avoid unnecessary database query
        if ($email === null || $email === '') {
            return '';
        }


        // use Laravel's Query Builder to retrieve employee data more efficiently
        $employee = DB::table('master_employee')->select('Title', 'Fname', 'Sname', 'Lname')->where('Email', $email)->first();

        // if employee data not found, return empty string
        if ($employee === null) {
            return '';
        }

        // combine employee name fields into full name and return properly formatted
        $fullNameParts = array_filter([$employee->Title, $employee->Fname, $employee->Sname, $employee->Lname], function ($part) {
            return $part !== null && trim($part) !== "";
        });
        $fullName = ucwords(strtolower(implode(" ", $fullNameParts)));

        return $fullName;
    }
}

function getEmailID($empid)
{
    $user = User::find($empid);

    return optional($user)->email ?? '';
}


function getEmployeeEmailId($empid)
{
    if ($empid == null) {
        return "";
    } else {
        $Name = master_employee::where('EmployeeID', $empid)->select('Email')->first();
        if ($Name == null) {
            return "";
        } else {
            return $Name->Email;
        }
    }
}

function getEmpIdByEmpCode($empCode)
{
    if (!$empCode) {
        return "";
    }

    $employeeId = DB::table('master_employee')
        ->select("EmployeeID")
        ->where('EmpCode', $empCode)
        ->value('EmployeeID');

    return $employeeId ? $employeeId : "";
}


function getEmployeeDesignation($empid)
{
    if (!$empid) {
        return '';
    }

    $query = DB::table('master_employee')
        ->join('core_designation', 'core_designation.id', '=', 'master_employee.DesigId')
        ->select('designation_name')
        ->where('EmployeeID', $empid)
        ->first();

    return $query ? $query->designation_name : '';
}


function getcompany_code($companyId)
{
    if ($companyId === null) {
        return "";
    }

    $company_code = DB::table('core_company')
        ->where('id', $companyId)
        ->value('company_code');

    return $company_code ?: "";
}


function getcompany_name(?int $companyId): string
{
    $company = DB::table('core_company')
        ->select('company_name')
        ->where('id', $companyId)
        ->first();

    return $company?->company_name ?? '';
}

function getDepartmentCode($DeptId)
{
    if (!$DeptId || $DeptId == 0) {
        return "";
    }

    $result = DB::table('core_department')
        ->select('department_code')
        ->where('id', $DeptId)
        ->first();

    return $result->department_code ?? '';
}


function getDepartmentShortCode($DeptId)
{
    if ($DeptId == null) {
        return "";
    } else {
        $ShortCode = DB::table('core_department')->select('department_code')->where('id', $DeptId)->first();
        if (is_null($ShortCode)) {
            return '';
        } else {
            return $ShortCode->department_code;
        }
    }
}

function getDepartment($DeptId)
{
    if ($DeptId == null) {
        return "";
    } else {
        $Department = DB::table('core_department')->select('department_name')->where('id', $DeptId)->first();
        if (is_null($Department)) {
            return '';
        } else {
            return $Department->department_name;
        }
    }
}

function getSubDepartment($SubDepartment)
{
    if ($SubDepartment == null) {
        return "";
    } else {
        $subDepartment = DB::table('core_sub_department')->select('sub_department_name')->where('id', $SubDepartment)->first();
        if (is_null($subDepartment)) {
            return '';
        } else {
            return $subDepartment->sub_department_name;
        }
    }
}

function getDesignationCode($DesigId)
{
    if ($DesigId == null || $DesigId == 0) {
        return "";
    } else {
        $DesigCode = DB::table('core_designation')->select('designation_name')->where('id', $DesigId)->first();
        if (is_null($DesigCode)) {
            return '';
        } else {
            return $DesigCode->designation_name;
        }
    }
}

function getDesignation($DesigId)
{
    if ($DesigId == null) {
        return "";
    } else {
        $DesigName = DB::table('core_designation')->select('designation_name')->where('id', $DesigId)->first();
        if (is_null($DesigName)) {
            return '';
        } else {
            return $DesigName->designation_name;
        }
    }
}

function getCandidateFullDesignation($JAId)
{
    // Fetch the required data using Query Builder
    $query = DB::table('offerletterbasic')
        ->select(
            'core_designation.designation_name',
            'offerletterbasic.DesigSuffix',
            'core_department.department_name',
            'core_sub_department.sub_department_name',
            'core_section.section_name'
        )
        ->join('core_department', 'core_department.id', '=', 'offerletterbasic.Department')
        ->join('core_designation', 'core_designation.id', '=', 'offerletterbasic.Designation')
        ->leftJoin('core_sub_department', 'core_sub_department.id', '=', 'offerletterbasic.SubDepartment')
        ->leftJoin('core_section', 'core_section.id', '=', 'offerletterbasic.Section')
        ->where('JAId', $JAId)
        ->first();

    // Return the designation name if DesigSuffix is null or empty
    if (empty($query->DesigSuffix)) {
        return $query->designation_name;
    }

    // Map DesigSuffix to the corresponding name
    $suffixMapping = [
        'Department' => $query->department_name,
        'SubDepartment' => $query->sub_department_name,
        'Section' => $query->section_name,
    ];

    // Append the relevant suffix if it exists in the mapping
    return $query->designation_name . (isset($suffixMapping[$query->DesigSuffix])
            ? ' - ' . $suffixMapping[$query->DesigSuffix]
            : '');
}


function getGradeValue($GradeId)
{
    if ($GradeId == null) {
        return "";
    } else {
        $query = DB::table('core_grade')->select('grade_name')->where('id', $GradeId)->first();
        if (is_null($query)) {
            return '';
        } else {
            return $query->grade_name;
        }
    }
}

function getHQ($HqId)
{
    if ($HqId == null || $HqId == 0) {
        return "";
    } else {
        $HqName = DB::table('core_city_village')->select('city_village_name')->where('id', $HqId)->first();
        if (is_null($HqName)) {
            return '';
        } else {
            return $HqName->city_village_name;
        }
    }
}

function getStateCode($StateId)
{
    if ($StateId == null || $StateId == 0) {
        return "";
    } else {
        $StateCode = DB::table('states')->select('StateCode')->where('StateId', $StateId)->first();
        if (is_null($StateCode)) {
            return '';
        } else {
            return $StateCode->StateCode;
        }
    }
}

function getStateName($StateId)
{
    if ($StateId == null) {
        return "";
    } else {
        $StateName = DB::table('states')->select('StateName')->where('StateId', $StateId)->first();
        if (is_null($StateName)) {
            return '';
        } else {
            return $StateName->StateName;
        }
    }
}

function getDistrictName($DistrictId)
{
    if ($DistrictId == null) {
        return "";
    } else {
        $DistrictName = DB::table('master_district')->select('DistrictName')->where('DistrictId', $DistrictId)->first();
        if (is_null($DistrictName)) {
            return '';
        } else {
            return $DistrictName->DistrictName;
        }
    }
}

function convertData($body_content)
{

    $body_content = stripslashes($body_content);
    $body_content = addslashes($body_content);
    return $body_content;
}

function ActiveMRFCount($Uid)
{
    $sql = DB::table('manpowerrequisition')
        ->where('CountryId', session('Set_Country'))
        ->where('Status', 'Approved')
        ->where('Status', '!=', 'Close')
        ->where('Allocated', $Uid)
        ->get();
    return $sql->count();
}

function CheckReplacementMRF($empid)
{
    $sql = DB::table('manpowerrequisition')->select('MRFId')->where('RepEmployeeID', $empid)->first();

    if (is_null($sql)) {
        return '0';
    } else {
        return '1';
    }
}

function CheckReportee($empid)
{
    $isReportee = DB::table('master_employee')
        ->where('RepEmployeeID', $empid)
        ->exists();

    return $isReportee ? '1' : '0';
}

function CheckJobPostCreated($mrfid)
{
    $sql = DB::table('jobpost')->select('JPId')->where('MRFId', $mrfid)->first();

    if (is_null($sql)) {
        return '0';
    } else {
        return '1';
    }
}

function GetJobPostId($mrfid)
{
    $sql = DB::table('jobpost')->select('JPId')->where('MRFId', $mrfid)->first();

    if (is_null($sql)) {
        return '0';
    } else {
        return $sql->JPId;
    }
}

function getEducationById($eid)
{
    if ($eid == null) {
        return "";
    } else {
        $Education = DB::table('master_education')->select('EducationName')->where('EducationId', $eid)->first();

        if (is_null($Education)) {
            return '';
        } else {
            return $Education->EducationName;
        }
    }
}

function getEducationCodeById($eid)
{
    if ($eid == null) {
        return "";
    } else {
        $Education = DB::table('master_education')->select('EducationCode')->where('EducationId', $eid)->first();

        if (is_null($Education)) {
            return '';
        } else {
            return $Education->EducationCode;
        }
    }
}

function getSpecializationbyId($sid)
{
    if ($sid == null) {
        return "";
    } else {
        $query = DB::table('master_specialization')->select('Specialization')->where('SpId', $sid)->first();
        if (is_null($query)) {
            return " ";
        } else {
            return $query->Specialization;
        }
    }
}

function getCollegeById($id)
{
    if ($id == null) {
        return "";
    } else {
        $institute = DB::table('master_institute')->select('InstituteName')->where('InstituteId', $id)->first();
        if (is_null($institute)) {
            return '';
        } else {
            return $institute->InstituteName;
        }
    }
}

function getCollegeCode($id)
{
    if ($id == null) {
        return "";
    } else {
        $institute = DB::table('master_institute')->select('InstituteCode')->where('InstituteId', $id)->first();
        if (is_null($institute)) {
            return '';
        } else {
            return $institute->InstituteCode;
        }
    }
}

function SendOTP($mobile, $otp)
{
    $apiKey = urlencode('n41ZZY/VUcc-mNbSqC0yi0Gb8NEjX9CAbpAyTwRsjb');
    $sender = urlencode('RECVNR');
    $message = "Your Verification Code is: $otp -vnr";
    $data = array('apikey' => $apiKey, 'numbers' => $mobile, "sender" => $sender, "message" => $message);
    $ch = curl_init('https://api.textlocal.in/send/');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($ch);
    curl_close($ch);
    if (strpos($result, 'failure') !== false) {
        return "failure";
    } else {
        return "success";
    }
    curl_close($ch);
}

function CheckJobPostExpiry($jpid)
{
    $sql = DB::table('jobpost')->select('LastDate')->where('JobPostType', 'Campus')->where('JPId', $jpid)->first();

    $LastDate = $sql->LastDate;
    if ($LastDate < date('Y-m-d')) {
        return 'expired';
    } else {
        return 'notexpired';
    }
}

function ResumeSourceCount($JPId)
{
    $sql = DB::table('jobapply')
        ->Join('master_resumesource', 'jobapply.ResumeSource', '=', 'master_resumesource.ResumeSouId')
        ->where('jobapply.JPId', $JPId)
        ->select(DB::raw('COUNT(jobapply.JAId) AS Applied'), 'master_resumesource.ResumeSource')
        ->groupBy('jobapply.ResumeSource')
        ->get();
    $x = '';
    foreach ($sql as $item) {
        $x .= '<span class="badge rounded-pill bg-warning text-dark" style="font-size:12px;">' . $item->ResumeSource . ':' . $item->Applied . '</span> ';
    }
    return $x;
}

function getResumeSourceById($id)
{
    if ($id == null) {
        return "";
    } else {
        $ResumeSource = DB::table('master_resumesource')->select('ResumeSource')->where('ResumeSouId', $id)->first();
        if (is_null($ResumeSource)) {
            return '';
        } else {
            return $ResumeSource->ResumeSource;
        }
    }
}

function getHqStateCode($StateId)
{
    if ($StateId == null || $StateId == 0) {
        return "";
    } else {
        $StateCode = DB::table('core_state')->select('state_name')->where('id', $StateId)->first();
        return $StateCode->state_name;
    }
}

function CheckCommControl($Id)
{
    $comm = communication_controll::find($Id);
    return $comm->is_active ? '1' : '0';
}


function has_permission($resultArray, $pageName)
{
    foreach ($resultArray as $key => $value) {
        if ($value['PageName'] == $pageName) {
            return true;
        }
    }
    return false;
}

function CheckDuplicate($firstName, $phone, $email, $dob, $fatherName)
{
    $sql = DB::table('jobcandidates')
        ->where('Phone', '=', $phone)
        ->orWhere('Email', '=', $email)
        /* ->orWhere(function ($query) use ($firstName, $dob, $fatherName) {
			$query->where([['FName', '=', $firstName], ['DOB', '=', $dob], ['FatherName', '=', $fatherName]]);
		}) */
        ->count();

    return $sql;
}

function getTwheeMxDayMonth($departmentId, $gradeId, $verticalId)
{
    $maxTravelRecord = master_elg::where([
        ['DepartmentId', $departmentId],
        ['GradeId', $gradeId],
        ['VerticalId', $verticalId]
    ])->select('TW_InHQ_D', 'TW_InHQ_M')->first();

    if (is_null($maxTravelRecord)) {
        return '';
    } else {
        $maxDistanceInDay = $maxTravelRecord->TW_InHQ_D;
        $maxDistanceInMonth = $maxTravelRecord->TW_InHQ_M;

        return "Max: {$maxDistanceInDay}K.M/Day - {$maxDistanceInMonth}K.M/Month";
    }
}

function set_submenu($sub_menu_name)
{
    $session_sub_menu = session()->get('sub_menu');
    if ($session_sub_menu == $sub_menu_name) {
        return 'active';
    }
    return "";
}

function formatInterviewPanel($panelData)
{
    $panel = explode(',', $panelData);
    $panelNames = [];

    foreach ($panel as $row) {
        $panelNames[] = getFullName($row);
    }

    return implode(', ', $panelNames);
}


function formatDateTime($date, $time)
{
    $formattedDate = date('d-m-Y', strtotime($date));
    $formattedTime = date('h:i:s a', strtotime($time));

    return $formattedDate . ' ' . $formattedTime;
}

function setEventInCalendar($title, $description, $start, $end, $belongTo, $type)
{
    $event = DB::table('event_calendar')->where([
        'title' => $title,
        'description' => $description,
        'start_time' => $start,
        'end_time' => $end,
        'belong_to' => $belongTo,
        'type' => $type,
    ])->first();

    if (!$event) {
        DB::table('event_calendar')->insert([
            'title' => $title,
            'description' => $description,
            'start_time' => $start,
            'end_time' => $end,
            'belong_to' => $belongTo,
            'type' => $type
        ]);
    }
}

function get_subject_name($subjectId)
{
    if ($subjectId == '0') {
        return 'FIRO B';
    } else {
        $subject = \App\Models\TestModule\SubjectMaster::find($subjectId);
        return $subject->subject_name;
    }
}

function get_candidate_name($JCId)
{
    $fullname = \App\Models\jobcandidate::find($JCId);

    return $fullname->FName . ' ' . $fullname->MName . ' ' . $fullname->LName;
}

function get_candidate_email($JCId)
{
    $email = \App\Models\jobcandidate::find($JCId);
    return $email->Email;
}

function get_reference_number($JCId)
{
    $reference = \App\Models\jobcandidate::find($JCId);

    return $reference->ReferenceNo;
}

function check_exam_paper_status($JCId, $ExamId, $PaperId)
{
    $status = DB::table('candidate_assessment_status')->where([
        'jcid' => $JCId,
        'exam_id' => $ExamId,
        'paper_id' => $PaperId
    ])->first();
    if ($status) {
        return true;
    } else {
        return false;
    }
}

function check_firob_status($JCId)
{
    $status = DB::table('jobcandidates')->where('JCId', $JCId)->first();
    if ($status->FIROB_Test == 1) {
        return true;
    } else {
        return false;
    }
}

if (!function_exists('calculateAge')) {
    /**
     * Calculate the age based on the date of birth.
     *
     * @param string $dateOfBirth Date of birth in 'Y-m-d' format.
     * @return int Age in years.
     */
    function calculateAge(string $dateOfBirth): int
    {
        return Carbon::parse($dateOfBirth)->age;
    }
}

if (!function_exists('get_vehicle_policy_name')) {
    function get_vehicle_policy_name($vehiclePolicyId)
    {
        $query = DB::table('policy_master')->where('PolicyId', $vehiclePolicyId)->first();
        if (!$query) {
            return '';
        }
        return $query->PolicyName;
    }
}

if (!function_exists('get_mrf_location')) {
    function get_mrf_location($MRFId)
    {
        $query = DB::table('mrf_location_position')->where('MRFId', $MRFId)->get();

        $locations = []; // Initialize an empty array to hold locations

        foreach ($query as $row) {
            // Fetch district and state names and append to locations array
            $locations[] = getDistrictName($row->City) . '(' . getStateName($row->State) . ')';
        }

        // Return a comma-separated string of locations
        return implode(', ', $locations);
    }
}

