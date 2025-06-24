<?php

namespace App\Http\Controllers\Admin;


use App\Models\master_mrf;
use App\Helpers\LogActivity;
use Illuminate\Http\Request;
use App\Helpers\UserNotification;
use Carbon\Carbon;
use App\Mail\MrfStatusChangeMail;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\CandidateJoining;
use App\Models\jobapply;
use App\Models\LogBookActivity;
use App\Models\OfferLetter;
use App\Models\screen2ndround;
use App\Models\screening;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;


class AdminController extends Controller
{
    function index()
    {
        $active_mrf = master_mrf::where('Type', '!=', 'SIP_HrManual')->where('Status', 'Approved')->orderBy('MRFID', 'desc')->pluck("JobCode", "MRFId");
        $recruiter = User::where('role', 'R')->where('Status', 'A')->pluck("name", "id");

        $result = [];
        $total_applicant = jobapply::where('JPId', '!=', '0')->where('JPId', '!=', null)->count();
        $result['Applications'] = $total_applicant;
        $hr_screening = jobapply::where('Status', 'Selected')->count();
        $result['HR Screening'] = $hr_screening;
        $technical_screening = screening::where('ScreenStatus', 'Shortlist')->count();
        $result['Technical Screening'] = $technical_screening;
        $first_interview = screening::where('IntervStatus', 'Selected')->count();
        $result['1st Interview'] = $first_interview;
        $second_interview = screen2ndround::where('IntervStatus2', 'Selected')->count();
        $result['2nd Interview'] = $second_interview;
        $offer = OfferLetter::where('OfferLetterSent', 'Yes')->count();
        $result['Offer'] = $offer;
        $Joined = CandidateJoining::where('Joined', 'Yes')->count();
        $result['Joined'] = $Joined;
        $dataPoints = array();
        foreach ($result as $key => $value) {
            if ($value != 0) {
                $dataPoints[] = ['label' => $key, 'y' => $value];
            }
        }

        $mrf_open_days = DB::table('jobpost')->select('JobCode', DB::raw("DATEDIFF(CURRENT_DATE(), CreatedTime) as date_difference"))->where('Status', '=', 'Open')->get()->toArray();
        $dataPoints1 = array();
        foreach ($mrf_open_days as $key => $value) {
            $dataPoints1[] = ['label' => $value->JobCode, 'y' => $value->date_difference];
        }

        $events = [];
        $get_events = DB::table('event_calendar')->select('*')->where('event_type', 'system')->where(function ($query) {
            $query->where('type', 'R')->orWhere('type', 'A');
        })->groupBy('start_time', 'end_time', 'title', 'description', 'belong_to', 'type', 'event_type')->get();

        foreach ($get_events as $event) {
            $events[] = ['title' => $event->title, 'description' => $event->description, 'start' => $event->start_time, 'end' => $event->end_time,];
        }

        $NewMRF = DB::table('manpowerrequisition')->where('CountryId', session('Set_Country'))->where('Status', 'New')->orWhere(function ($query) {
            $query->where('Status', 'Approved')->whereNull('Allocated');
        })->count();

        $ActiveMRF = DB::table('manpowerrequisition')->where('CountryId', session('Set_Country'))->where('MRFId', '!=', 0)->where('Allocated', '!=', null)->where('Status', 'Approved')->count();

        $TotalPosition = DB::table('manpowerrequisition')->where('Status', 'Approved')->select(DB::raw('SUM(Positions) as total'))->get()->first()->total;
        /* $filledPositions = DB::table('mrf_position_filled')
            ->join('manpowerrequisition', 'mrf_position_filled.MRFId', '=', 'manpowerrequisition.MRFId')
            ->where('manpowerrequisition.Status', 'Approved')
            ->select(DB::raw('SUM(mrf_position_filled.Filled) as filled_total'))
            ->get()
            ->first()
            ->filled_total; */

       // $remainingPositions = $TotalPosition - $filledPositions;
        $remainingPositions = $TotalPosition;
        $sql = DB::table('users')->where('role', 'R')->where('Status', 'A')->get();

        $TotalCandidate = $count = DB::table('jobcandidates')->whereNotIn('JCId', function ($query) {
            $query->select('JCId')->from('trainee_apply');
        })->distinct('email')->count();

        $TotalApplicants1 = DB::table('manpowerrequisition')->join('jobpost', 'jobpost.MRFId', '=', 'manpowerrequisition.MRFId')->join('jobapply', 'jobapply.JPId', '=', 'jobpost.JPId')->where('manpowerrequisition.Status', 'Approved')->count('jobapply.JAId');
        $TotalApplicants2 = DB::table('manpowerrequisition')->join('jobpost', 'jobpost.MRFId', '=', 'manpowerrequisition.MRFId')->join('trainee_apply', 'trainee_apply.JPId', '=', 'jobpost.JPId')->where('manpowerrequisition.Status', 'Approved')->count('trainee_apply.TId');
        $TotalApplicants = $TotalApplicants1 + $TotalApplicants2;

        $SelectedCandidate1 = DB::table('screening')->join('jobapply', 'jobapply.JAId', '=', 'screening.JAId')->join('jobpost', 'jobpost.JPId', '=', 'jobapply.JPId')->join('manpowerrequisition', 'manpowerrequisition.MRFId', '=', 'jobpost.MRFId')->where('manpowerrequisition.Status', 'Approved')->where('screening.SelectedForC', '!=', '0')->select(DB::raw('COUNT(DISTINCT screening.JAId) as total'))->first()->total;
        $SelectedCandidate2 = DB::table('trainee_apply')->join('jobpost', 'jobpost.JPId', '=', 'trainee_apply.JPId')->join('manpowerrequisition', 'manpowerrequisition.MRFId', '=', 'jobpost.MRFId')->where('manpowerrequisition.Status', 'Approved')->where('trainee_apply.IntervStatus', 'Selected')->select(DB::raw('COUNT(DISTINCT trainee_apply.TId) as total'))->first()->total;
        $SelectedCandidate = $SelectedCandidate1 + $SelectedCandidate2;


        $upcomming_interview = DB::table('screening')
            ->join('jobapply', 'screening.JAId', '=', 'jobapply.JAId')
            ->join('jobpost', 'jobapply.JPId', '=', 'jobpost.JPId')
            ->join('jobcandidates', 'jobapply.JCId', '=', 'jobcandidates.JCId')
            ->leftJoin('screen2ndround', 'screen2ndround.ScId', '=', 'screening.ScId')
            ->where(function ($query) {
                $query->where('screening.ScreenStatus', 'Shortlist')
                    ->orWhere('screening.IntervStatus', '2nd Round Interview');
            })
            ->where('jobpost.Status', 'Open')
            ->where('jobpost.JobPostType', 'Regular')
            ->where(function ($query) {
                $query->whereDate('IntervDt', '>=', Carbon::today())
                    ->orWhereDate('IntervDt2', '>=', Carbon::today());
            })
            ->where(function ($query) {
                $query->whereNull('screening.IntervStatus')
                    ->orWhereNull('screen2ndround.IntervStatus2');
            })->count();

        $pending_tech_scr = DB::table('screening')->join('jobapply', 'jobapply.JAId', '=', 'screening.JAId')->join('jobpost', 'jobpost.JPId', '=', 'jobapply.JPId')->where('jobpost.Status', '=', 'Open')->whereNull('screening.ScreenStatus')->count();


        $OLPending = DB::table('offerletterbasic')->join('jobapply', 'jobapply.JAId', '=', 'offerletterbasic.JAId')
            ->join('jobcandidates', 'jobcandidates.JCId', '=', 'jobapply.JCId')
            ->leftjoin('jobpost', 'jobpost.JPId', '=', 'jobapply.JPId')
            ->leftjoin('manpowerrequisition', 'manpowerrequisition.MRFId', '=', 'jobpost.MRFId')
            /*->where('manpowerrequisition.Status', 'Approved')*/
            ->where('OfferLetterSent', 'Yes')
            ->whereNull('Answer')
            ->count();
        $upcommingJoining = DB::table('candjoining')->where('Answer', 'Accepted')->whereNull('Joined')->count();

        $resume_source_pie_chart = DB::table('jobapply')->select('master_resumesource.ResumeSource', DB::raw('COUNT(JAId) as total'))->join('master_resumesource', 'master_resumesource.ResumeSouId', '=', 'jobapply.ResumeSource')->groupBy('jobapply.ResumeSource')->get();

        $resume_source_pie_chart_data = [];
        foreach ($resume_source_pie_chart as $key => $value) {
            $resume_source_pie_chart_data[$key]['name'] = $value->ResumeSource;
            $resume_source_pie_chart_data[$key]['y'] = $value->total;
        }

        $data = [];
        $months = [1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April', 5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August', 9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'];

        for ($i = 1; $i <= 12; $i++) {
            $sql1 = "SELECT COUNT(MRFId) as Open,(SELECT COUNT(MRFId) FROM manpowerrequisition WHERE Status='Close' AND (YEAR(CloseDt)=YEAR(CURRENT_DATE)) AND (MONTH(CloseDt)=$i)) as Close  FROM `manpowerrequisition` WHERE (YEAR(AllocatedDt)=YEAR(CURRENT_DATE)) AND (MONTH(AllocatedDt)=$i)";
            $result = DB::select($sql1);
            $data[$i]['Month'] = $months[$i];
            $data[$i]['Open'] = $result[0]->Open;
            $data[$i]['Close'] = $result[0]->Close;
        }

        $mrf_summary_chart = array_values($data);

        // Retrieve recruiters with the 'recruiter' role
        $recruiters = \App\Models\Admin\master_user::where('role', 'R')->where('status', 'A')->get();

        // Initialize a one-dimensional array to store recruiter tasks
        $recruiter_tasks = [];

        // Loop through each recruiter
        foreach ($recruiters as $recruiter) {

            // Count the number of active tasks (status is Approved) for the current recruiter
            $active_tasks = DB::table('manpowerrequisition')->where('Allocated', $recruiter->id)->where('Status', 'Approved')->count();

            // Count the number of Open Position (total position on active mrf) current recruiter
            $total_position_recruiter = DB::table('manpowerrequisition')->where('Status', 'Approved')->where('Allocated', $recruiter->id)->select(DB::raw('SUM(Positions) as total'))->get()->first()->total;
            $filledPositions = DB::table('mrf_position_filled')
                ->join('manpowerrequisition', 'mrf_position_filled.MRFId', '=', 'manpowerrequisition.MRFId')
                ->where('manpowerrequisition.Allocated', $recruiter->id)
                ->where('manpowerrequisition.Status', 'Approved')
                ->select(DB::raw('SUM(mrf_position_filled.Filled) as filled_total'))
                ->get()
                ->first()
                ->filled_total;


            $interview_recruiter1 = DB::table('manpowerrequisition')->join('jobpost', 'jobpost.MRFId', '=', 'manpowerrequisition.MRFId')->join('jobapply', 'jobapply.JPId', '=', 'jobpost.JPId')->join('screening', 'screening.JAId', '=', 'jobapply.JAId')->where('manpowerrequisition.Status', 'Approved')->where('manpowerrequisition.Allocated', $recruiter->id)->whereNotNull('screening.IntervStatus')->count('jobapply.JAId');
            $interview_recruiter2 = DB::table('manpowerrequisition')->join('jobpost', 'jobpost.MRFId', '=', 'manpowerrequisition.MRFId')->join('trainee_apply', 'trainee_apply.JPId', '=', 'jobpost.JPId')->where('manpowerrequisition.Status', 'Approved')->where('manpowerrequisition.Allocated', $recruiter->id)->whereNotNull('trainee_apply.IntervStatus')->count('trainee_apply.TId');
            $interview_recruiter = $interview_recruiter1 + $interview_recruiter2;

            $job_offered1 = DB::table('manpowerrequisition')->join('jobpost', 'jobpost.MRFId', '=', 'manpowerrequisition.MRFId')->join('jobapply', 'jobapply.JPId', '=', 'jobpost.JPId')->join('offerletterbasic', 'offerletterbasic.JAId', '=', 'jobapply.JAId')->where('manpowerrequisition.Status', 'Approved')->where('manpowerrequisition.Allocated', $recruiter->id)->where('offerletterbasic.OfferLetterSent', '=', 'Yes')->count('jobapply.JAId');
            $job_offered2 = DB::table('manpowerrequisition')->join('jobpost', 'jobpost.MRFId', '=', 'manpowerrequisition.MRFId')->join('trainee_apply', 'trainee_apply.JPId', '=', 'jobpost.JPId')->where('manpowerrequisition.Status', 'Approved')->where('manpowerrequisition.Allocated', $recruiter->id)->where('trainee_apply.IntervStatus', 'Selected')->count('trainee_apply.TId');
            $job_offered = $job_offered1 + $job_offered2;

            $offer_accepted1 = DB::table('manpowerrequisition')->join('jobpost', 'jobpost.MRFId', '=', 'manpowerrequisition.MRFId')->join('jobapply', 'jobapply.JPId', '=', 'jobpost.JPId')->join('offerletterbasic', 'offerletterbasic.JAId', '=', 'jobapply.JAId')->where('manpowerrequisition.Status', 'Approved')->where('manpowerrequisition.Allocated', $recruiter->id)->where('offerletterbasic.Answer', '=', 'Accepted')->count('jobapply.JAId');
            $offer_accepted2 = DB::table('manpowerrequisition')->join('jobpost', 'jobpost.MRFId', '=', 'manpowerrequisition.MRFId')->join('trainee_apply', 'trainee_apply.JPId', '=', 'jobpost.JPId')->where('manpowerrequisition.Status', 'Approved')->where('manpowerrequisition.Allocated', $recruiter->id)->whereNotNull('trainee_apply.Doj')->count('trainee_apply.TId');
            $offer_accepted = $offer_accepted1 + $offer_accepted2;

            $joined_recruiter1 = DB::table('manpowerrequisition')->join('jobpost', 'jobpost.MRFId', '=', 'manpowerrequisition.MRFId')->join('jobapply', 'jobapply.JPId', '=', 'jobpost.JPId')->join('candjoining', 'candjoining.JAId', '=', 'jobapply.JAId')->where('manpowerrequisition.Status', 'Approved')->where('manpowerrequisition.Allocated', $recruiter->id)->where('candjoining.Joined', 'Yes')->count('jobapply.JAId');
            $joined_recruiter2 = DB::table('manpowerrequisition')->join('jobpost', 'jobpost.MRFId', '=', 'manpowerrequisition.MRFId')->join('trainee_apply', 'trainee_apply.JPId', '=', 'jobpost.JPId')->where('manpowerrequisition.Status', 'Approved')->where('manpowerrequisition.Allocated', $recruiter->id)->whereNotNull('trainee_apply.Doj')->count('trainee_apply.TId');
            $joined_recruiter = $joined_recruiter1 + $joined_recruiter2;
            // Store the task counts in the one-dimensional array for the current recruiter
            $recruiter_tasks[] = ['name' => strtolower($recruiter->name), 'Active' => $active_tasks, 'total_position' => $total_position_recruiter, 'filled_position' => $filledPositions, 'interview' => $interview_recruiter, 'job_offered' => $job_offered, 'offer_accepted' => $offer_accepted, 'joined' => $joined_recruiter, 'Id' => $recruiter->id,];
        }


        return view('admin.index', compact('active_mrf', 'dataPoints', 'recruiter', 'dataPoints1', 'events', 'NewMRF', 'TotalApplicants', 'ActiveMRF', 'OLPending', 'remainingPositions', 'upcomming_interview', 'SelectedCandidate', 'TotalCandidate', 'pending_tech_scr', 'upcommingJoining', 'recruiter_tasks', 'resume_source_pie_chart', 'mrf_summary_chart', 'resume_source_pie_chart_data'));
    }

    function mrf()
    {
        $company_list = DB::table("core_company")->orderBy('company_code', 'desc')->pluck("company_code", "id");
        $department_list = DB::table("core_department")->where('is_active', '1')->orderBy('department_name', 'asc')->pluck("department_name", "id");
        $state_list = DB::table("states")->orderBy('StateName', 'asc')->pluck("StateName", "StateId");
        $institute_list = DB::table("master_institute")->orderBy('InstituteName', 'asc')->pluck("InstituteName", "InstituteId");
        $designation_list = DB::table("core_designation")->where('designation_name', '!=', '')->orderBy('designation_name', 'asc')->pluck("designation_name", "id");
        $employee_list = DB::table('master_employee')->orderBy('FullName', 'ASC')->where('EmpStatus', 'A')->select('EmployeeID', DB::raw('CONCAT(Fname, " ", Lname) AS FullName'))->pluck("FullName", "EmployeeID");
        $months = [1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April', 5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August', 9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'];
        $userlist = DB::table("users")->where('role', 'H')->orderBy('name', 'asc')->pluck("name", "id");
        return view('admin.mrf', compact('company_list', 'department_list', 'state_list', 'userlist', 'institute_list', 'designation_list', 'employee_list', 'months'));
    }

    function active_mrf()
    {
        $company_list = DB::table("core_company")->orderBy('company_code', 'desc')->pluck("company_code", "id");
        $department_list = DB::table("core_department")->where('is_active', '1')->orderBy('department_name', 'asc')->pluck("department_name", "id");
        $state_list = DB::table("states")->orderBy('StateName', 'asc')->pluck("StateName", "StateId");
        $institute_list = DB::table("master_institute")->orderBy('InstituteName', 'asc')->pluck("InstituteName", "InstituteId");
        $designation_list = DB::table("core_designation")->where('designation_name', '!=', '')->orderBy('designation_name', 'asc')->pluck("designation_name", "id");
        $employee_list = DB::table('master_employee')->orderBy('FullName', 'ASC')->where('EmpStatus', 'A')->select('EmployeeID', DB::raw('CONCAT(Fname, " ", Lname) AS FullName'))->pluck("FullName", "EmployeeID");
        $months = [1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April', 5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August', 9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'];
        $recruiters = User::whereRole('R')->orderBy('name', 'asc')->get();
        $userlist = DB::table("users")->where('role', 'H')->orderBy('name', 'asc')->pluck("name", "id");
        return view('admin.activemrf', compact('company_list', 'department_list', 'userlist', 'state_list', 'institute_list', 'designation_list', 'employee_list', 'months', 'recruiters'));
    }

    function closedmrf()
    {
        $company_list = DB::table("core_company")->orderBy('company_code', 'desc')->pluck("company_code", "id");
        $department_list = DB::table("core_department")->where('is_active', '1')->orderBy('department_name', 'asc')->pluck("department_name", "id");
        $state_list = DB::table("states")->orderBy('StateName', 'asc')->pluck("StateName", "StateId");
        $institute_list = DB::table("master_institute")->orderBy('InstituteName', 'asc')->pluck("InstituteName", "InstituteId");
        $designation_list = DB::table("core_designation")->where('designation_name', '!=', '')->orderBy('designation_name', 'asc')->pluck("designation_name", "id");
        $employee_list = DB::table('master_employee')->orderBy('FullName', 'ASC')->where('EmpStatus', 'A')->select('EmployeeID', DB::raw('CONCAT(Fname, " ", Lname) AS FullName'))->pluck("FullName", "EmployeeID");
        $months = [1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April', 5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August', 9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'];
        $recruiters = User::whereRole('R')->orderBy('name', 'asc')->get();
        return view('admin.closedmrf', compact('company_list', 'department_list', 'state_list', 'institute_list', 'designation_list', 'employee_list', 'months', 'recruiters'));
    }

    function getNewMrf(Request $request)
    {

        $usersQuery = master_mrf::query();
        $Company = $request->Company;
        $Department = $request->Department;
        $Year = $request->Year;
        $Month = $request->Month;

        if ($Company != '') {

            $usersQuery->where("manpowerrequisition.CompanyId", $Company);
        }
        if ($Department != '') {
            $usersQuery->where("manpowerrequisition.DepartmentId", $Department);
        }
        if ($Year != '') {
            $usersQuery->whereBetween('manpowerrequisition.CreatedTime', [$Year . '-01-01', $Year . '-12-31']);
        }
        if ($Month != '') {
            if ($Year != '') {
                $usersQuery->whereBetween('manpowerrequisition.CreatedTime', [$Year . '-' . $Month . '-01', $Year . '-' . $Month . '-31']);
            } else {
                $usersQuery->whereBetween('manpowerrequisition.CreatedTime', [date('Y') . '-' . $Month . '-01', date('Y') . '-' . $Month . '-31']);
            }
        }

        $mrf = $usersQuery->select('*')->where('manpowerrequisition.CountryId', session('Set_Country'))->where('manpowerrequisition.Status', 'New')->orWhere(function ($query) {
            $query->where('manpowerrequisition.Status', 'Approved')->whereNull('manpowerrequisition.Allocated');
        })->orderBy('CreatedTime', 'DESC')->select(['manpowerrequisition.*']);


        return datatables()->of($mrf)->addIndexColumn()->addColumn('chk', function () {
            return '<input type="checkbox" class="select_all">';
        })->editColumn('Type', function ($mrf) {
            if ($mrf->Type == 'N' || $mrf->Type == 'N_HrManual') {
                return 'New MRF';
            } elseif ($mrf->Type == 'SIP' || $mrf->Type == 'SIP_HrManual') {
                return 'SIP/Internship MRF';
            } elseif ($mrf->Type == 'Campus' || $mrf->Type == 'Campus_HrManual') {
                return 'Campus MRF';
            } elseif ($mrf->Type == 'R' || $mrf->Type == 'R_HrManual') {
                return 'Replacement MRF';
            }
        })->editColumn('DepartmentId', function ($mrf) {
            return getDepartmentCode($mrf->DepartmentId);
        })->editColumn('DesigId', function ($mrf) {
            if ($mrf->Type == 'SIP' || $mrf->Type == 'SIP_HrManual') {
                return 'SIP Trainee';
            } else {
                return getDesignationCode($mrf->DesigId);
            }
        })
            ->editColumn('LocationIds', function ($mrf) {
                $loc = '';


                $location = DB::table('mrf_location_position')->where('MRFId', $mrf->MRFId)->get()->toArray();
                $loc = '';
                foreach ($location as $key => $value) {
                    $loc .= getDistrictName($value->City) . ' ';
                    $loc .= getStateCode($value->State) . ' - ';
                    $loc .= $value->Nop;
                    $loc . '<br>';
                }

                return $loc;
            })->addColumn('MRFDate', function ($mrf) {
                return date('d-m-Y', strtotime($mrf->CreatedTime));
            })->addColumn('CreatedBy', function ($mrf) {

                return getFullName($mrf->CreatedBy);
            })->addColumn('Status', function ($mrf) {
                $list = array('New' => 'New', 'Approved' => 'Approved', 'Hold' => 'On Hold', 'Rejected' => 'Rejected');

                $x = '<select name="mrfstatus" id="mrfstatus' . $mrf->MRFId . '" class="form-control form-select form-select-sm  d-inline" disabled onchange="chngmrfsts(' . $mrf->MRFId . ',this.value)" style="width: 100px; ">';
                foreach ($list as $key => $value) {
                    if ($mrf->Status == $key) {
                        $x .= '<option value=' . $key . ' selected>' . $value . '</option>';
                    } else {
                        $x .= '<option value=' . $key . '>' . $value . '</option>';
                    }
                }
                $x .= '</select>  <i class="fa fa-pencil-square-o text-success d-inline" aria-hidden="true" id="msedit' . $mrf->MRFId . '" onclick="editmstst(' . $mrf->MRFId . ',this)" style="font-size: 16px;cursor: pointer;"></i>';
                return $x;
            })->addColumn('Allocated', function ($mrf) {
                if ($mrf->Status == 'Approved') {
                    $user_list = DB::table('users')->where('role', 'R')->where('Status', 'A')->orderBy('name', 'ASC')->get();


                    $x = '<select name="allocate" id="allocate' . $mrf->MRFId . '" class="form-control form-select form-select-sm  d-inline" disabled style="width: 100px;" onchange="allocatemrf(' . $mrf->MRFId . ',this.value)"><option value="">Select</option>';
                    foreach ($user_list as $list) {
                        if ($mrf->Allocated == $list->id) {
                            $x .= '<option value=' . $list->id . ' selected>' . substr($list->name, 0, strrpos($list->name, ' ')) . '</option>';
                        } else {
                            $x .= '<option value=' . $list->id . '>' . substr($list->name, 0, strrpos($list->name, ' ')) . '</option>';
                        }
                    }
                    $x .= '</select> <i class="fa fa-pencil-square-o text-success d-inline" aria-hidden="true" id="mrfedit' . $mrf->MRFId . '" onclick="editmrf(' . $mrf->MRFId . ')" style="font-size: 16px;cursor: pointer;"></i>';
                    return $x;
                } else {
                    return '';
                }
            })->editColumn('reporting_approve', function ($mrf) {
                $reporting_list = array('N' => 'No', 'Y' => 'Approved', 'R' => 'Reject');
                $reporting = '<select name="reporting_approve" id="reporting_approve' . $mrf->MRFId . '" class="form-control form-select form-select-sm  d-inline" disabled onchange="chngreportingApprove(' . $mrf->MRFId . ',this.value)" style="width: 100px; ">';
                foreach ($reporting_list as $key => $value) {
                    if ($mrf->reporting_approve == $key) {
                        $reporting .= '<option value=' . $key . ' selected>' . $value . '</option>';
                    } else {
                        $reporting .= '<option value=' . $key . '>' . $value . '</option>';
                    }
                }
                $reporting .= '</select>  <i class="fa fa-pencil-square-o text-success d-inline" aria-hidden="true" id="reportingApproveEdit' . $mrf->MRFId . '" onclick="reportingApproveEditStatus(' . $mrf->MRFId . ',this)" style="font-size: 16px;cursor: pointer;"></i>';
                return $reporting;
            })->editColumn('hod_approve', function ($mrf) {
                $hod_list = array('N' => 'No', 'Y' => 'Approved', 'R' => 'Reject');
                $hod = '<select name="hod_approve" id="hod_approve' . $mrf->MRFId . '" class="form-control form-select form-select-sm  d-inline" disabled onchange="chnghodApprove(' . $mrf->MRFId . ',this.value)" style="width: 100px; ">';
                foreach ($hod_list as $key => $value) {
                    if ($mrf->hod_approve == $key) {
                        $hod .= '<option value=' . $key . ' selected>' . $value . '</option>';
                    } else {
                        $hod .= '<option value=' . $key . '>' . $value . '</option>';
                    }
                }
                $hod .= '</select>  <i class="fa fa-pencil-square-o text-success d-inline" aria-hidden="true" id="hodApproveEdit' . $mrf->MRFId . '" onclick="hodApproveEditStatus(' . $mrf->MRFId . ',this)" style="font-size: 16px;cursor: pointer;"></i>';
                return $hod;
            })->editColumn('management_approve', function ($mrf) {
                $management_list = array('N' => 'No', 'Y' => 'Approved', 'R' => 'Reject');
                $management = '<select name="management_approve" id="management_approve' . $mrf->MRFId . '" class="form-control form-select form-select-sm  d-inline" disabled onchange="chngmanagementApprove(' . $mrf->MRFId . ',this.value)" style="width: 100px; ">';
                foreach ($management_list as $key => $value) {
                    if ($mrf->management_approve == $key) {
                        $management .= '<option value=' . $key . ' selected>' . $value . '</option>';
                    } else {
                        $management .= '<option value=' . $key . '>' . $value . '</option>';
                    }
                }
                $management .= '</select>  <i class="fa fa-pencil-square-o text-success d-inline" aria-hidden="true" id="managementApproveEdit' . $mrf->MRFId . '" onclick="managementApproveEditStatus(' . $mrf->MRFId . ',this)" style="font-size: 16px;cursor: pointer;"></i>';
                return $management;
            })->addColumn('Details', function ($mrf) {
                return '<i class="fa fa-eye text-info" style="font-size: 16px;cursor: pointer;" id="viewMRF" data-id=' . $mrf->MRFId . '></i>';
            })->rawColumns(['chk', 'Status', 'Allocated', 'Details', 'reporting_approve', 'hod_approve', 'management_approve'])->make(true);
    }

    function getActiveMrf(Request $request)
    {

        $usersQuery = master_mrf::query();
        $Company = $request->Company;
        $Department = $request->Department;
        $Year = $request->Year;
        $Month = $request->Month;
        $Recruiter = $request->Recruiter;
        $MRFType = $request->MRFType;
        if ($Company != '') {
            $usersQuery->where("manpowerrequisition.CompanyId", $Company);
        }
        if ($Department != '') {
            $usersQuery->where("manpowerrequisition.DepartmentId", $Department);
        }
        if ($Year != '') {
            $usersQuery->whereBetween('manpowerrequisition.CreatedTime', [$Year . '-01-01', $Year . '-12-31']);
        }
        if ($Month != '') {
            if ($Year != '') {
                $usersQuery->whereBetween('manpowerrequisition.CreatedTime', [$Year . '-' . $Month . '-01', $Year . '-' . $Month . '-31']);
            } else {
                $usersQuery->whereBetween('manpowerrequisition.CreatedTime', [date('Y') . '-' . $Month . '-01', date('Y') . '-' . $Month . '-31']);
            }
        }

        if ($Recruiter != '') {
            $usersQuery->where("manpowerrequisition.Allocated", $Recruiter);
        }

        if ($MRFType != '') {
            if ($MRFType == 'New') {
                $usersQuery->where("manpowerrequisition.Type", 'N')->orWhere("manpowerrequisition.Type", 'N_HrManual');
            } elseif ($MRFType == 'SIP') {
                $usersQuery->where("manpowerrequisition.Type", 'SIP')->orWhere("manpowerrequisition.Type", 'SIP_HrManual');
            } elseif ($MRFType == 'Campus') {
                $usersQuery->where("manpowerrequisition.Type", 'Campus')->orWhere("manpowerrequisition.Type", 'Campus_HrManual');
            } elseif ($MRFType == 'Replacement') {
                $usersQuery->where("manpowerrequisition.Type", 'R')->orWhere("manpowerrequisition.Type", 'R_HrManual');
            }
        }

        $mrf = $usersQuery->select('*')->where('CountryId', session('Set_Country'))->where('Status', 'Approved')->where('Allocated', '!=', null)->orderBy('CreatedTime', 'DESC');

        return datatables()->of($mrf)->addIndexColumn()->addColumn('chk', function () {
            return '<input type="checkbox" class="select_all">';
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
            ->editColumn('department_code', function ($mrf) {
                return getDepartmentCode($mrf->DepartmentId);
            })
            ->editColumn('sub_department', function ($mrf) {
                return getSubDepartment($mrf->SubDepartment);
            })
            ->editColumn('DesigId', function ($mrf) {
                if ($mrf->DesigId == '' or $mrf->DesigId == null) {
                    return '';
                } else {
                    return getDesignationCode($mrf->DesigId);
                }
            })->editColumn('LocationIds', function ($mrf) {
                // $location = unserialize($mrf->LocationIds);
                $location = DB::table('mrf_location_position')->where('MRFId', $mrf->MRFId)->get()->toArray();
                $loc = '';
                foreach ($location as $key => $value) {
                    $loc .= getDistrictName($value->City) . ' ';
                    $loc .= getStateCode($value->State) . ' - ';
                    $loc .= $value->Nop;
                    $loc . '<br>';
                }
                return $loc;
            })->addColumn('MRFDate', function ($mrf) {
                return date('d-m-Y', strtotime($mrf->CreatedTime));
            })->addColumn('CreatedBy', function ($mrf) {

                return getFullName($mrf->CreatedBy);
            })->addColumn('Status', function ($mrf) {
                $list = array('New' => 'New', 'Approved' => 'Approved', 'Hold' => 'On Hold', 'Rejected' => 'Rejected');

                $x = '<select name="mrfstatus" id="mrfstatus' . $mrf->MRFId . '" class="form-control form-select form-select-sm  d-inline" disabled onchange="chngmrfsts(' . $mrf->MRFId . ',this.value)" style="width: 100px; ">';
                foreach ($list as $key => $value) {
                    if ($mrf->Status == $key) {
                        $x .= '<option value=' . $key . ' selected>' . $value . '</option>';
                    } else {
                        $x .= '<option value=' . $key . '>' . $value . '</option>';
                    }
                }
                $x .= '</select>  <i class="fa fa-pencil-square-o text-success d-inline" aria-hidden="true" id="msedit' . $mrf->MRFId . '" onclick="editmstst(' . $mrf->MRFId . ',this)" style="font-size: 16px;cursor: pointer;"></i>';
                return $x;
            })->addColumn('Allocated', function ($mrf) {
                if ($mrf->Status == 'Approved') {
                    $user_list = DB::table('users')->where('role', 'R')->where('Status', 'A')->orderBy('name', 'ASC')->get();

                    $x = '<select name="allocate" id="allocate' . $mrf->MRFId . '" class="form-control form-select form-select-sm  d-inline" disabled style="width: 100px;" onchange="allocatemrf(' . $mrf->MRFId . ',this.value)"><option value="">Select</option>';
                    foreach ($user_list as $list) {
                        if ($mrf->Allocated == $list->id) {
                            $x .= '<option value=' . $list->id . ' selected>' . substr($list->name, 0, strrpos($list->name, ' ')) . '</option>';
                        } else {
                            $x .= '<option value=' . $list->id . '>' . substr($list->name, 0, strrpos($list->name, ' ')) . '</option>';
                        }
                    }
                    $x .= '</select> <i class="fa fa-pencil-square-o text-success d-inline" aria-hidden="true" id="mrfedit' . $mrf->MRFId . '" onclick="editmrf(' . $mrf->MRFId . ')" style="font-size: 16px;cursor: pointer;"></i>';
                    return $x;
                } else {
                    return '';
                }
            })->addColumn('Details', function ($mrf) {
                return '<i class="fa fa-eye text-success" style="font-size: 16px;cursor: pointer;" id="viewMRF" data-id=' . $mrf->MRFId . '></i>';
            })->rawColumns(['chk', 'Status', 'Allocated', 'Details'])->make(true);
    }

    function getCloseMrf(Request $request)
    {

        $usersQuery = master_mrf::query();
        $Company = $request->Company;
        $Department = $request->Department;
        $Year = $request->Year;
        $Month = $request->Month;
        $Recruiter = $request->Recruiter;
        $MRFType = $request->MRFType;
        if ($Company != '') {

            $usersQuery->where("manpowerrequisition.CompanyId", $Company);
        }
        if ($Department != '') {
            $usersQuery->where("manpowerrequisition.DepartmentId", $Department);
        }
        if ($Year != '') {
            $usersQuery->whereBetween('manpowerrequisition.CreatedTime', [$Year . '-01-01', $Year . '-12-31']);
        }
        if ($Month != '') {
            if ($Year != '') {
                $usersQuery->whereBetween('manpowerrequisition.CreatedTime', [$Year . '-' . $Month . '-01', $Year . '-' . $Month . '-31']);
            } else {
                $usersQuery->whereBetween('manpowerrequisition.CreatedTime', [date('Y') . '-' . $Month . '-01', date('Y') . '-' . $Month . '-31']);
            }
        }
        if ($Recruiter != '') {
            $usersQuery->where("manpowerrequisition.Allocated", $Recruiter);
        }

        if ($MRFType != '') {
            if ($MRFType == 'New') {
                $usersQuery->where("manpowerrequisition.Type", 'N')->orWhere("manpowerrequisition.Type", 'N_HrManual');
            } elseif ($MRFType == 'SIP') {
                $usersQuery->where("manpowerrequisition.Type", 'SIP')->orWhere("manpowerrequisition.Type", 'SIP_HrManual');
            } elseif ($MRFType == 'Campus') {
                $usersQuery->where("manpowerrequisition.Type", 'Campus')->orWhere("manpowerrequisition.Type", 'Campus_HrManual');
            } elseif ($MRFType == 'Replacement') {
                $usersQuery->where("manpowerrequisition.Type", 'R')->orWhere("manpowerrequisition.Type", 'R_HrManual');
            }
        }
        $mrf = $usersQuery->select('*')->where('CountryId', session('Set_Country'))->where('Status', 'Close')->orderBy('CreatedTime', 'DESC');

        return datatables()->of($mrf)->addIndexColumn()->addColumn('chk', function () {
            return '<input type="checkbox" class="select_all">';
        })->editColumn('Type', function ($mrf) {
            if ($mrf->Type == 'N' || $mrf->Type == 'N_HrManual') {
                return 'New MRF';
            } elseif ($mrf->Type == 'SIP' || $mrf->Type == 'SIP_HrManual') {
                return 'SIP/Internship MRF';
            } elseif ($mrf->Type == 'Campus' || $mrf->Type == 'Campus_HrManual') {
                return 'Campus MRF';
            } elseif ($mrf->Type == 'R' || $mrf->Type == 'R_HrManual') {
                return 'Replacement MRF';
            }
        })->editColumn('DepartmentId', function ($mrf) {
            return getDepartmentCode($mrf->DepartmentId);
        })
            ->editColumn('sub_department', function ($mrf) {
                return getSubDepartment($mrf->SubDepartment);
            })
            ->editColumn('DesigId', function ($mrf) {
            return getDesignationCode($mrf->DesigId);
        })->addColumn('MRFDate', function ($mrf) {
            return date('d-m-Y', strtotime($mrf->CreatedTime));
        })->addColumn('AllocatedDt', function ($mrf) {
            return date('d-m-Y', strtotime($mrf->AllocatedDt));
        })->addColumn('CloseDt', function ($mrf) {
            return date('d-m-Y', strtotime($mrf->CloseDt));
        })->addColumn('CreatedBy', function ($mrf) {

            return getFullName($mrf->CreatedBy);
        })->addColumn('Allocated', function ($mrf) {
            return getFullName($mrf->Allocated);
        })->addColumn('Position_Filled', function ($mrf) {
            return $mrf->Hired;
        })->addColumn('Details', function ($mrf) {
            return '<i class="fa fa-eye text-success" style="font-size: 16px;cursor: pointer;" id="viewMRF" data-id=' . $mrf->MRFId . '></i>';
        })->addColumn('daystofill', function ($mrf) {
            $daysDifference = \Carbon\Carbon::parse($mrf->AllocatedDt)->diffInDays(\Carbon\Carbon::parse($mrf->CloseDt));
            return $daysDifference . ' Days';
        })
            ->addColumn('Recruiter', function ($mrf) {
                return getFullName($mrf->Allocated);
            })
            ->addColumn('OnBehalf', function ($mrf) {
                return getFullName($mrf->OnBehalf);
            })
            ->rawColumns(['chk', 'Allocated', 'Details', 'daystofill'])->make(true);
    }


    function updateMRFStatus(Request $request)
    {
        $MRF = master_mrf::find($request->MRFId);
        $MRF->Status = $request->va;
        $MRF->RemarkHr = $request->RemarkHr;
        $MRF->UpdatedBy = Auth::user()->id;
        $MRF->LastUpdated = now();
        $query = $MRF->save();
        if (!$query) {
            return response()->json(['status' => 400, 'msg' => 'Something went wrong..!!']);
        } else {
            $jobCode = $MRF->JobCode;
            LogActivity::addToLog('MRF ' . $jobCode . ' is ' . $request->va, 'Update');
            $CreatedBy = $MRF->CreatedBy;


            if ($MRF->Type == 'N' || $MRF->Type == 'N_HrManual') {
                $type = 'New';
            } elseif ($MRF->Type == 'SIP' || $MRF->Type == 'SIP_HrManual') {
                $type = 'SIP/Internship';
            } elseif ($MRF->Type == 'Campus' || $MRF->Type == 'Campus_HrManual') {
                $type = 'Campus';
            } elseif ($MRF->Type == 'R' || $MRF->Type == 'R_HrManual') {
                $type = 'Replacement';
            }

            $details = ["subject" => 'MRF (' . $type . ') - ' . $jobCode . ', Status - ' . $request->va, "Status" => $request->va, "Type" => $type,];
            if ($request->va != 'New') {
                if (CheckCommControl(4) == 1 || CheckCommControl(4) == 1) {  //Action taken by admin on MRF
                    Mail::to(getEmailID($CreatedBy))->send(new MrfStatusChangeMail($details)); // Need to active when s/w is live

                    UserNotification::notifyUser($CreatedBy, 'MRF Status Change', 'MRF (' . $type . ') - ' . $jobCode . ', Status - ' . $request->va);
                }
            }
            return response()->json(['status' => 200, 'msg' => 'MRF Status has been changed successfully.']);
        }
    }

    function allocateMRF(Request $request)
    {
        $MRF = master_mrf::find($request->MRFId);
        $MRF->Allocated = $request->va;
        $MRF->UpdatedBy = Auth::user()->id;
        $MRF->AllocatedDt = now();
        $MRF->LastUpdated = now();
        $query = $MRF->save();

        $query1 = DB::table('jobpost')->where('MRFId', $request->MRFId)->update(['CreatedBy' => $request->va, 'LastUpdated' => now(), 'UpdatedBy' => Auth::user()->id,]);


        if (!$query) {
            return response()->json(['status' => 400, 'msg' => 'Something went wrong..!!']);
        } else {
            $jobCode = $MRF->JobCode;
            LogActivity::addToLog('MRF ' . $jobCode . ' is allocated to ' . getFullName($request->va), 'Update');
            UserNotification::notifyUser($request->va, 'MRF Allocated', $jobCode);
            return response()->json(['status' => 200, 'msg' => 'Task has been allocated to recruiter successfully.']);
        }
    }


    function getTaskList(Request $request)
    {
        $sql = DB::table('manpowerrequisition')->where('CountryId', session('Set_Country'))->where('Allocated', $request->Uid)->where('Status', '=', 'Approved')->get();

        return datatables()->of($sql)->addIndexColumn()->addColumn('actions', function ($sql) {
            return '<button class="btn btn-sm  btn-outline-primary font-13 edit" data-id="' . $sql->MRFId . '" id="editBtn"><i class="fadeIn animated bx bx-pencil"></i></button>';
        })->addColumn('status', function ($sql) {
            if ($sql->Status != 'Close') {
                return 'Active';
            } else {
                return 'Closed';
            }
        })->addColumn('days_to_fill', function ($sql) {
            if ($sql->Status == 'Close') {
                return \Carbon\Carbon::parse($sql->AllocatedDt)->diff($sql->CloseDt)->format('%d days');
            } else {
                return '';
            }
        })->rawColumns(['actions', 'days_to_fill'])->make(true);
    }


    function getRecruiterName(Request $request)
    {
        $result = getFullName($request->Uid);
        return response()->json(['details' => $result]);
    }

    public function userlogs()
    {
        $months = [1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April', 5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August', 9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'];
        $user = User::pluck('name', 'id');
        return view('admin.userlogs', compact('months', 'user'));
    }

    public function getAllLogs(Request $request)
    {
        $usersQuery = LogBookActivity::query();
        $User = $request->User;
        $Year = $request->Year;
        $Month = $request->Month;

        if ($User != '') {
            $usersQuery->where("user_id", $User);
        }

        if ($Year != '') {
            $usersQuery->whereBetween('created_at', [$Year . '-01-01', $Year . '-12-31']);
        }
        if ($Month != '') {
            if ($Year != '') {
                $usersQuery->whereBetween('created_at', [$Year . '-' . $Month . '-01', $Year . '-' . $Month . '-31']);
            } else {
                $usersQuery->whereBetween('created_at', [date('Y') . '-' . $Month . '-01', date('Y') . '-' . $Month . '-31']);
            }
        }

        $query = $usersQuery->select('*')->orderBy('id', 'DESC');
        return datatables()->of($query)->addIndexColumn()->editColumn('date', function ($query) {
            return date('d-m-Y', strtotime($query->created_at));
        })->make(true);
    }

    function changeMRFStatusOnBehalfReporting(Request $request)
    {
        $MRF = master_mrf::find($request->MRFId);
        $MRF->on_behalf_reporting = 'Y';
        $MRF->reporting_approve = $request->va;
        $MRF->reporting_remark = $request->RemarkHr;
        $MRF->reporting_approve_date = date('Y-m-d');
        $MRF->UpdatedBy = Auth::user()->id;
        $MRF->LastUpdated = now();
        $query = $MRF->save();
        if (!$query) {
            return response()->json(['status' => 400, 'msg' => 'Something went wrong..!!']);
        } else {
            return response()->json(['status' => 200, 'msg' => 'Save changes successfully.']);
        }
    }

    function changeMRFStatusOnBehalfHod(Request $request)
    {
        $MRF = master_mrf::find($request->MRFId);
        $MRF->on_behalf_hod = 'Y';
        $MRF->hod_approve = $request->va;
        $MRF->hod_remark = $request->RemarkHr;
        $MRF->hod_approve_date = date('Y-m-d');
        $MRF->UpdatedBy = Auth::user()->id;
        $MRF->LastUpdated = now();
        $query = $MRF->save();
        if (!$query) {
            return response()->json(['status' => 400, 'msg' => 'Something went wrong..!!']);
        } else {
            return response()->json(['status' => 200, 'msg' => 'Save changes successfully.']);
        }
    }

    function changeMRFStatusOnBehalfManagement(Request $request)
    {
        $MRF = master_mrf::find($request->MRFId);
        $MRF->on_behalf_management = 'Y';
        $MRF->management_approve = $request->va;
        $MRF->management_remark = $request->RemarkHr;
        $MRF->management_approve_date = date('Y-m-d');
        $MRF->UpdatedBy = Auth::user()->id;
        $MRF->LastUpdated = now();
        $query = $MRF->save();
        if (!$query) {
            return response()->json(['status' => 400, 'msg' => 'Something went wrong..!!']);
        } else {
            return response()->json(['status' => 200, 'msg' => 'Save changes successfully.']);
        }
    }
}
