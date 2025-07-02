<?php

namespace App\Http\Controllers\Common;

use App\Mail\InterviewAppSubmitMail;
use App\Mail\JoiningFormMail;
use App\Mail\JoiningFormSubmitMail;
use App\Mail\NewUserMail;
use App\Mail\VehicleInfoMail;
use App\Models\jobpost;
use App\Models\jobapply;
use App\Models\screening;
use App\Models\jobcandidate;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Helpers\CandidateActivityLog;
use App\Helpers\UserNotification;
use App\Imports\ApplicationImport;
use App\Mail\TechScrMail;
use App\Models\Admin\master_employee;
use Illuminate\Support\Facades\Mail;

use Image;
use Maatwebsite\Excel\Facades\Excel;
use Exception;
use Illuminate\Support\Facades\Storage;

class JobApplicationController extends Controller
{

    public function job_response()
    {
        $company_list = DB::table("core_company")->orderBy('company_code', 'desc')->pluck("company_code", "id");
        $months = [1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April', 5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August', 9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'];
        $source_list = DB::table("master_resumesource")->where('Status', 'A')->Where('ResumeSouId', '!=', '7')->pluck('ResumeSource', 'ResumeSouId');
        $state_list = DB::table("states")->where('StateId', '!=', '41')->orderBy('StateName', 'asc')->pluck("StateName", "StateId");
        return view('common.job_response', compact('company_list', 'months', 'source_list', 'state_list'));
    }

    public function getJobResponseSummary(Request $request)
    {


        $usersQuery = jobpost::query();
        $Company = $request->Company;
        $Department = $request->Department;
        $Year = $request->Year;
        $Month = $request->Month;

        if (Auth::user()->role == 'R') {
            $usersQuery->where('jobpost.CreatedBy', Auth::user()->id);
        }
        if ($Company != '') {
            $usersQuery->where("jobpost.CompanyId", $Company);
        }
        if ($Department != '') {
            $usersQuery->where("jobpost.DepartmentId", $Department);
        }
        if ($Year != '') {
            $usersQuery->whereBetween('jobpost.CreatedTime', [$Year . '-01-01', $Year . '-12-31']);
        }
        if ($Month != '') {
            if ($Year != '') {
                $usersQuery->whereBetween('jobpost.CreatedTime', [$Year . '-' . $Month . '-01', $Year . '-' . $Month . '-31']);
            } else {
                $usersQuery->whereBetween('jobpost.CreatedTime', [date('Y') . '-' . $Month . '-01', date('Y') . '-' . $Month . '-31']);
            }
        }

        $data = $usersQuery->select(
            'jobpost.JPId',
            'jobapply.Company',
            'jobapply.Department',
            'jobpost.JobCode',
            'jobpost.DesigId',
            'jobapply.ResumeSource',
            DB::raw('COUNT(jobapply.JAId) AS Response')
        )
            ->Join('jobapply', 'jobpost.JPId', '=', 'jobapply.JPId')
            ->join('manpowerrequisition', 'manpowerrequisition.MRFId', '=', 'jobpost.MRFId')
            ->where('manpowerrequisition.CountryId', session('Set_Country'))
            ->where('jobapply.Type', '!=', 'Campus')
            ->where('jobpost.Status', '=', 'Open')
            ->groupBy('jobpost.JPId');


        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('chk', function () {
                return '<input type="checkbox" class="select_all">';
            })
            ->editColumn('Department', function ($data) {
                return getDepartment($data->Department);
            })
            ->editColumn('Designation', function ($data) {
                if ($data->DesigId != 0 || $data->DesigId != null) {
                    return getDesignation($data->DesigId);
                } else {
                    return '';
                }
            })
            ->editColumn('Response', function ($data) {
                return '<a href="javascript:void(0);" class="btn btn-xs btn-warning getCandidate" data-id="' . $data->JPId . '">' . $data->Response . '</a>';
            })
            ->addColumn('Source', function ($data) {
                return ResumeSourceCount($data->JPId, $data->ResumeSource);
            })
            ->rawColumns(['chk', 'Response', 'Source'])
            ->make(true);
    }


    public function job_applications(Request $request)
    {
        $company_list = DB::table("core_company")->orderBy('company_code', 'desc')->pluck("company_code", "id");
        $months = [1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April', 5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August', 9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'];
        $source_list = DB::table("master_resumesource")->where('Status', 'A')->Where('ResumeSouId', '!=', '7')->pluck('ResumeSource', 'ResumeSouId');
        $education_list = DB::table("master_education")->where('Status', 'A')->orderBy('EducationCode', 'asc')->pluck("EducationCode", "EducationId");
        $resume_list = DB::table("master_resumesource")->where('Status', 'A')->where('ResumeSouId', '!=', '7')->orderBy('ResumeSouId', 'asc')->pluck("ResumeSource", "ResumeSouId");
        $state_list = DB::table("states")->where('StateId', '!=', '41')->orderBy('StateName', 'asc')->pluck("StateName", "StateId");
        $job = jobpost::query();
        if (Auth::user()->role == 'R') {
            $job->where('jobpost.CreatedBy', Auth::user()->id);
        }
        $jobpost_list = $job->join('manpowerrequisition', 'manpowerrequisition.MRFId', '=', 'jobpost.MRFId')->select('JPId', 'jobpost.JobCode')
            ->where('manpowerrequisition.CountryId', session('Set_Country'))
            ->where('jobpost.Status', 'Open')
            ->get();

        $Company = $request->Company;
        $Department = $request->Department;
        $Year = $request->Year;
        $Month = $request->Month;
        $Source = $request->Source;
        $Gender = $request->Gender;
        $Education = $request->Education;
        $State = $request->State;
        $Name = $request->Name;
        $Email = $request->Email;
        $Phone = $request->Phone;
        $City = $request->City;
        $ManualEntry = $request->ManualEntry;
        $Experience = $request->Experience;  //Experience filter

        $usersQuery = jobapply::query();
        if ($Company != '') {
            $usersQuery->where("jobapply.Company", $Company);
        }
        if ($Department != '') {
            $usersQuery->where("jobapply.Department", $Department);
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
        if ($Source != '') {
            $usersQuery->where("jobapply.ResumeSource", $Source);
        }
        if ($Gender != '') {
            $usersQuery->where("jobcandidates.Gender", $Gender);
        }
        if ($Education != '') {
            $usersQuery->where("jobcandidates.Education", $Education);
        }
        if ($State != '') {
            $usersQuery->where("jobcandidates.State", $State);
        }
        if ($Name != '') {
            $usersQuery->where("jobcandidates.FName", 'like', "%$Name%");
        }

        if ($Email != '') {
            $usersQuery->where("jobcandidates.Email", 'like', "%$Email%");
        }

        if ($Phone != '') {
            $usersQuery->where("jobcandidates.Phone", 'like', "%$Phone%");
        }

        if ($City != '') {
            $usersQuery->where("jobcandidates.City", 'like', "%$City%");
        }

        if ($ManualEntry != '') {
            if ($ManualEntry == 1) {
                $usersQuery->whereNotNull('jobcandidates.manual_entry_by');
            } elseif ($ManualEntry == 0) {
                $usersQuery->whereNull('jobcandidates.manual_entry_by');
            }
        }
        //Experience filter
        if ($Experience != '') {
            //  $usersQuery->where("jobcandidates.Professional", '==','P');
            $usersQuery->where("jobcandidates.TotalYear", '>=', $Experience);
        }

        $candidate_list = $usersQuery->select(
            'jobapply.JAId',
            'jobapply.ResumeSource',
            'jobapply.Type',
            'jobapply.ApplyDate',
            'jobapply.Status',
            'jobapply.RejectRemark',
            'jobapply.FwdTechScr',
            'jobcandidates.JCId',
            'jobcandidates.ReferenceNo',
            'jobcandidates.FName',
            'jobcandidates.MName',
            'jobcandidates.LName',
            'jobcandidates.FatherName',
            'jobcandidates.Email',
            'jobcandidates.Phone',
            'jobcandidates.City',
            'jobcandidates.Education',
            'jobcandidates.TotalYear',
            'jobcandidates.TotalMonth',
            'jobcandidates.Specialization',
            'jobcandidates.Professional',
            'jobcandidates.JobStartDate',
            'jobcandidates.JobEndDate',
            'jobcandidates.PresentCompany',
            'jobcandidates.Designation',
            'jobcandidates.Verified',
            'jobcandidates.CandidateImage',
            'jobcandidates.BlackList',
            'jobcandidates.BlackListRemark',
            'jobcandidates.UnBlockRemark',
            'jobapply.JPId',
            'jobpost.DesigId',
            'jobcandidates.ProfileViewed',
            'jobcandidates.manual_entry_by',
            'jobcandidates.manual_entry_by_name',
            'jobapply.Status as hr_screening_status',
            'jobapply.RejectRemark as hr_screening_remark',
            'jobapply.Type',
            'jobapply.SelectedBy as hr_screening_by',
            'jobapply.FwdTechScr'
        )
            ->Join('jobcandidates', 'jobapply.JCId', '=', 'jobcandidates.JCId')
            ->leftJoin('jobpost', 'jobapply.JPId', '=', 'jobpost.JPId')
            ->where('jobapply.Type', '!=', 'Campus')
            ->where('jobcandidates.Nationality', session('Set_Country'))
            ->orderBy('jobapply.ApplyDate', 'desc');



        $total_candidate = $candidate_list->count();

        //Experience filter


        if ($Department != '' || $Company != '' || $Year != '' || $Month != '' || $Source != '' || $Gender != '' || $Education != '' || $Experience != '' || $Name != '' || $Email != '' || $Phone != '' || $ManualEntry != '' || $State != '') {
            $candidate_list = $candidate_list->paginate(10);
            $candidate_list->appends(['Company' => $Company, 'Department' => $Department, 'Year' => $Year, 'Month' => $Month, 'Source' => $Source, 'Gender' => $Gender, 'Education' => $Education, 'Name' => $Name, 'Email' => $Email, 'Phone' => $Phone, 'ManualEntry' => $ManualEntry, 'State' => $State, 'Experience' => $Experience]);
        } else {
            $candidate_list = $candidate_list->paginate(10);
        }
        $total_available = DB::table('jobapply')
            ->join('jobcandidates', 'jobapply.JCId', '=', 'jobcandidates.JCId')
            ->where('jobcandidates.Nationality', session('Set_Country'))
            ->where('Type', '!=', 'Campus')
            ->where('Status', null);
        $total_available = $total_available->count();

        $total_hr_scr = DB::table('jobapply')
            ->where('Type', '!=', 'Campus')
            ->join('jobcandidates', 'jobapply.JCId', '=', 'jobcandidates.JCId')
            ->where('jobcandidates.Nationality', session('Set_Country'))
            ->where('Status', '!=', null);
        $total_hr_scr = $total_hr_scr->count();

        $total_fwd = DB::table('jobapply')
            ->join('jobcandidates', 'jobapply.JCId', '=', 'jobcandidates.JCId')
            ->where('jobcandidates.Nationality', session('Set_Country'))
            ->where('Type', '!=', 'Campus')
            ->where('FwdTechScr', 'Yes');
        $total_fwd = $total_fwd->count();
        return view('common.job_applications', compact('company_list', 'months', 'source_list', 'education_list', 'state_list', 'candidate_list', 'total_candidate', 'total_available', 'total_hr_scr', 'total_fwd', 'jobpost_list', 'resume_list'));
    }

    public function job_applications_not_viewed(Request $request)
    {
        $company_list = DB::table("core_company")->orderBy('company_code', 'desc')->pluck("company_code", "id");
        $months = [1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April', 5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August', 9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'];
        $source_list = DB::table("master_resumesource")->where('Status', 'A')->where('ResumeSouId', '!=', '7')->pluck('ResumeSource', 'ResumeSouId');
        $education_list = DB::table("master_education")->where('Status', 'A')->orderBy('EducationCode', 'asc')->pluck("EducationCode", "EducationId");
        $resume_list = DB::table("master_resumesource")->where('Status', 'A')->where('ResumeSouId', '!=', '7')->orderBy('ResumeSouId', 'asc')->pluck("ResumeSource", "ResumeSouId");
        $state_list = DB::table("states")->where('StateId', '!=', '41')->orderBy('StateName', 'asc')->pluck("StateName", "StateId");
        $job = jobpost::query();
        if (Auth::user()->role == 'R') {
            $job->where('jobpost.CreatedBy', Auth::user()->id);
        }
        $jobpost_list = $job->join('manpowerrequisition', 'manpowerrequisition.MRFId', '=', 'jobpost.MRFId')->select('JPId', 'jobpost.JobCode')
            ->where('manpowerrequisition.CountryId', session('Set_Country'))
            ->where('jobpost.Status', 'Open')
            ->get();

        $filters = [
            'Company' => $request->Company,
            'Department' => $request->Department,
            'Year' => $request->Year,
            'Month' => $request->Month,
            'Source' => $request->Source,
            'Gender' => $request->Gender,
            'Education' => $request->Education,
            'Name' => $request->Name,
            'Email' => $request->Email,
            'Phone' => $request->Phone,
            'ManualEntry' => $request->ManualEntry,
            'JobCode' => $request->JobCode,
            'State' => $request->State,
            'City' => $request->City
        ];

        // Initialize the query
        $usersQuery = JobApply::query()
            ->select([
                'jobapply.JAId',
                'jobapply.ResumeSource',
                'jobapply.Type',
                'jobapply.ApplyDate',
                'jobapply.Status',
                'jobapply.RejectRemark',
                'jobapply.FwdTechScr',
                'jobcandidates.JCId',
                'jobcandidates.ReferenceNo',
                'jobcandidates.FName',
                'jobcandidates.MName',
                'jobcandidates.LName',
                'jobcandidates.FatherName',
                'jobcandidates.Email',
                'jobcandidates.Phone',
                'jobcandidates.City',
                'jobcandidates.Education',
                'jobcandidates.Specialization',
                'jobcandidates.Professional',
                'jobcandidates.JobStartDate',
                'jobcandidates.JobEndDate',
                'jobcandidates.PresentCompany',
                'jobcandidates.Designation',
                'jobcandidates.Verified',
                'jobcandidates.CandidateImage',
                'jobcandidates.BlackList',
                'jobcandidates.BlackListRemark',
                'jobcandidates.UnBlockRemark',
                'jobapply.JPId',
                'jobcandidates.ProfileViewed',
                'jobcandidates.manual_entry_by',
                'jobcandidates.manual_entry_by_name',
                'jobpost.DesigId',
                'jobcandidates.TotalYear',
                'jobcandidates.TotalMonth'
            ])
            ->join('jobcandidates', 'jobapply.JCId', '=', 'jobcandidates.JCId')
            ->leftJoin('jobpost', 'jobapply.JPId', '=', 'jobpost.JPId')
            ->where('jobapply.Type', '!=', 'Campus')
            ->where('jobcandidates.ProfileViewed', 'N')
            ->whereNotNull('jobapply.ApplyDate')
            ->where('jobapply.ApplyDate', '!=', '0000-00-00')
            ->where('jobcandidates.Nationality', session('Set_Country'))
            ->orderBy('jobapply.ApplyDate', 'desc');

        // Apply filters if they are provided
        foreach ($filters as $key => $value) {
            if (empty($value)) continue;

            switch ($key) {
                case 'Company':
                case 'Department':
                case 'Source':
                    $usersQuery->where("jobapply.$key", $value);
                    break;

                case 'Year':
                    $year = (int) $value;
                    $startDate = "$year-01-01 00:00:00";
                    $endDate = "$year-12-31 23:59:59";

                    if (!empty($filters['Month'])) {
                        $month = str_pad($filters['Month'], 2, '0', STR_PAD_LEFT);
                        $startDate = "$year-$month-01 00:00:00";
                        $endDate = date("Y-m-t 23:59:59", strtotime($startDate));
                    }

                    $usersQuery->whereBetween('jobapply.ApplyDate', [$startDate, $endDate]);
                    break;

                case 'Month':
                    if (empty($filters['Year'])) {
                        $year = date('Y');
                        $month = str_pad($value, 2, '0', STR_PAD_LEFT);
                        $startDate = "$year-$month-01 00:00:00";
                        $endDate = date("Y-m-t 23:59:59", strtotime($startDate));
                        $usersQuery->whereBetween('jobapply.ApplyDate', [$startDate, $endDate]);
                    }
                    break;

                case 'Gender':
                case 'Education':
                case 'State':
                    $usersQuery->where("jobcandidates.$key", $value);
                    break;

                case 'Name':
                    $usersQuery->where("jobcandidates.FName", 'like', "%$value%");
                    break;

                case 'Email':
                    $usersQuery->where("jobcandidates.Email", 'like', "%$value%");
                    break;

                case 'Phone':
                    $usersQuery->where("jobcandidates.Phone", 'like', "%$value%");
                    break;

                case 'City':
                    $usersQuery->where("jobcandidates.City", 'like', "%$value%");
                    break;

                case 'JobCode':
                    $usersQuery->where("jobapply.JPId", $value);
                    break;

                case 'ManualEntry':
                    if ($value == 1) {
                        $usersQuery->whereNotNull('jobcandidates.manual_entry_by');
                    } elseif ($value == 0) {
                        $usersQuery->whereNull('jobcandidates.manual_entry_by');
                    }
                    break;
            }
        }

        // Counting total candidates
        $total_candidate = $usersQuery->count();

        // Paginating the results
        $candidate_list = $usersQuery->paginate(10);

        // Adding filter parameters to the pagination links
        $candidate_list->appends($filters);

        return view('common.job_applications_not_viewed', compact('company_list', 'months', 'source_list', 'education_list', 'candidate_list', 'total_candidate', 'state_list', 'jobpost_list', 'resume_list'));
    }

    public function update_hrscreening(Request $request)
    {

        $JAId = $request->Hr_Screening_JAId;
        $Status = $request->Status;

        $query = jobapply::find($JAId);
        $query->HrScreeningDate = $request->HrScreeningDate;
        $query->Status = $Status;
        $query->RejectRemark = $request->RejectRemark ?? '';
        $query->SelectedBy = Auth::user()->id;

        $query->save();

        $JCId = $query->JCId;

        $candidate = jobcandidate::find($JCId);
        $Aadhaar = $candidate->Aadhaar;
        if (!$query) {
            return response()->json(['status' => 400, 'msg' => 'Something went wrong..!!']);
        } else {
            CandidateActivityLog::addToCandLog($JCId, $Aadhaar, 'Candidate HR Screening Status- ' . $Status);
            return response()->json(['status' => 200, 'msg' => 'HR Screening Status has been changed successfully.']);
        }
    }

    public function SendForTechScreening(Request $request)
    {

        $JAId = $request->JAId;
        $sql = 0;
        for ($i = 0; $i < Count($JAId); $i++) {
            $query = jobapply::find($JAId[$i]);
            $query->FwdTechScr = 'Yes';
            $query->SelectedBy = Auth::user()->id;
            $query->save();

            $res = new screening;
            $res->JAId = $query->JAId;
            $res->ScrCmp = $query->Company;
            $res->ScrDpt = $query->Department;
            $res->ScreeningBy = implode(',', $request->ScreeningBy);
            $res->CreatedBy = Auth::user()->id;
            $res->ReSentForScreen = $request->ResumeSent;
            $res->CreatedTime = now();
            $res->save();

            $sql = DB::table('previous_screening')->insert([
                'JAId' => $query->JAId,
                'ReSentForScreen' => $request->ResumeSent,
                'ScrCmp' => $query->Company,
                'ScrDpt' => $query->Department,
                'ScreeningBy' => implode(', ', $request->ScreeningBy),
                'CreatedBy' => Auth::user()->id,
                'CreatedTime' => now()
            ]);

            $JCId = $query->JCId;
            $candidate = jobcandidate::find($JCId);
            $Aadhaar = $candidate->Aadhaar;
            $jobapply = jobapply::find($JAId[$i]);
            $JPId = $jobapply->JPId;
            $jobpost = jobpost::find($JPId);
            $title = $jobpost->Title;
            $employee = master_employee::where('EmployeeID', $request->ScreeningBy)->first();

            $Email = $employee->Email;
            $details = [
                "subject" => 'Candidate Resumes Available for Review for job post ' . $title,
                "Title" => $title,

            ];
            CandidateActivityLog::addToCandLog($JCId, $Aadhaar, 'Candidate Send For Technical Screening');
            if (CheckCommControl(17) == 1) {  // send technical screening mail
                Mail::to($Email)->send(new TechScrMail($details)); // Need to active when s/w is live
            }
            $sql = 1;
        }

        if ($sql == 0) {
            return response()->json(['status' => 400, 'msg' => 'Something went wrong..!!']);
        } else {

            return response()->json(['status' => 200, 'msg' => 'Candidate Successfully Forwaded for Technical Screening.']);
        }
    }

    public function MapCandidateToJob(Request $request)
    {
        $JAId = $request->AddJobPost_JAId;
        $JPId = $request->JPId;
        $jobpost = jobpost::find($JPId);
        $Company = $jobpost->CompanyId;
        $Department = $jobpost->DepartmentId;
        $title = $jobpost->Title;

        $query = jobapply::find($JAId);
        $query->JPId = $JPId;
        $query->Company = $Company;
        $query->Department = $Department;
        $query->save();

        $JCId = $query->JCId;
        $candidate = jobcandidate::find($JCId);
        $Aadhaar = $candidate->Aadhaar;
        if (!$query) {
            return response()->json(['status' => 400, 'msg' => 'Something went wrong..!!']);
        } else {
            CandidateActivityLog::addToCandLog($JCId, $Aadhaar, 'Candidate Mapped to JobPost, ' . $title);
            return response()->json(['status' => 200, 'msg' => 'Candidate Successfully Mapped to JobPost.']);
        }
    }

    public function MoveCandidate(Request $request)
    {
        $JAId = $request->MoveCandidate_JAId;
        $Company = $request->MoveCompany;
        $Department = $request->MoveDepartment;

        $query = jobapply::find($JAId);
        $query->JPId = '0';
        $query->Company = $Company;
        $query->Department = $Department;
        $query->Status = null;
        $query->SelectedBy = null;
        $query->FwdTechScr = 'No';
        $query->save();

        $sql = DB::table('screening')->where('JAId', $JAId)->delete();

        $JCId = $query->JCId;
        $candidate = jobcandidate::find($JCId);
        $Aadhaar = $candidate->Aadhaar;
        if (!$query) {
            return response()->json(['status' => 400, 'msg' => 'Something went wrong..!!']);
        } else {
            CandidateActivityLog::addToCandLog($JCId, $Aadhaar, 'Candidate Moved To, ' . getcompany_code($Company) . ' ,' . getDepartmentCode($Department) . ' Department');
            return response()->json(['status' => 200, 'msg' => 'Candidate Moved Successfully.']);
        }
    }

    public function job_application_manual_entry_form()
    {
        $resume_list = DB::table("master_resumesource")->where('Status', 'A')->where('ResumeSouId', '!=', '7')->orderBy('ResumeSouId', 'asc')->pluck("ResumeSource", "ResumeSouId");
        return view('common.job_application_manual_entry_form', compact('resume_list'));
    }

    public function job_application_manual(Request $request)
    {

        $check = jobcandidate::where('Email', $request->Email)->first();
        if ($check != null) {
            return response()->json(['status' => 400, 'msg' => 'Candidate already exists..!!']);
        }
        $query = new jobcandidate;
        $query->Title = $request->Title;
        $query->FName = $request->FName;
        $query->MName = $request->MName;
        $query->LName = $request->LName;
        $query->Gender = $request->Gender;
        $query->FatherTitle = $request->FatherTitle;
        $query->FatherName = $request->FatherName;
        $query->Email = $request->Email;
        $query->Phone = $request->Phone;
        $query->Aadhaar = $request->Aadhaar ?? null;
        $query->Nationality = $request->Nationality;
        $query->Professional = $request->Professional;
        $query->save();
        $JCId = $query->JCId;

        $Resume = 'resume_' . $JCId . '.' . $request->Resume->extension();
        $request->Resume->storeAs('Recruitment/Resume', $Resume, 's3');
        $CandidateImage = '';
        if ($request->CandidateImage != '' || $request->CandidateImage != null) {
            $CandidateImage = $JCId . '.' . $request->CandidateImage->extension();
            $request->CandidateImage->storeAs('Recruitment/Picture', $CandidateImage, 's3');
        }

        $ReferenceNo = rand(1000, 9999) . date('Y') . $JCId;

        $query1 = jobcandidate::find($JCId);
        $query1->ReferenceNo = $ReferenceNo;
        $query1->Resume = $Resume;
        $query1->CandidateImage = $CandidateImage;
        $query1->CreatedTime = $request->ApplyDate;
        $query1->save();

        $jobApply = new jobapply;
        $jobApply->JCId = $JCId;
        $jobApply->JPId = '0';   //Without Any Job Post
        $jobApply->Type = 'Manual Entry';
        $jobApply->ResumeSource = $request->ResumeSource;
        $jobApply->OtherResumeSource = $request->OtherResumeSource;
        $jobApply->Company = '0';
        $jobApply->Department = '0';
        $jobApply->CreatedBy = Auth::user()->id;
        $jobApply->ApplyDate = $request->ApplyDate;
        $jobApply->save();

        CandidateActivityLog::addToCandLog($JCId, $request->Aadhaar, 'Candidate Manual Entry');
        if (!$jobApply) {
            return response()->json(['status' => 400, 'msg' => 'Something went wrong..!!']);
        } else {
            return response()->json(['status' => 200, 'msg' => ' successfully created.']);
        }
    }

    public function getManualEntryCandidate()
    {
        $usersQuery = jobapply::query();

        if (Auth::user()->role == 'R') {
            $usersQuery->where('jobapply.CreatedBy', Auth::user()->id);
        }

        $data = $usersQuery->select('*')->where('Type', 'Manual Entry')
            ->Join('jobcandidates', 'jobapply.JCId', '=', 'jobcandidates.JCId');

        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('chk', function ($data) {
                return '<input type="checkbox" class="japchks" data-id="' . $data->JCId . '" name="selectCand" id="selectCand" value="' . $data->JCId . '">';
            })
            ->addColumn('Name', function ($data) {

                return $data->FName . ' ' . $data->MName . ' ' . $data->LName;
            })
            ->editColumn('ApplyDate', function ($data) {
                return Carbon::parse($data->ApplyDate)->format('d-m-Y');
            })
            ->addColumn('Link', function ($data) {
                $JCId = base64_encode($data->JCId);
                $x = '<input type="text" id="link' . $data->JCId . '" value="' . url('jobportal/jobapply?jcid=' . $JCId . '') . '">  <button onclick="copylink(' . $data->JCId . ')" class="btn btn-sm btn-primary"> Copy</button>';
                return $x;
            })
            ->rawColumns(['chk', 'Link'])
            ->make(true);
    }

    public function BlacklistCandidate(Request $request)
    {
        $JCId = $request->JCId;
        $Remark = $request->Remark;

        $query = jobcandidate::find($JCId);
        $query->BlackList = 1;
        $query->BlackListRemark = $Remark;
        $query->save();
        if (!$query) {
            return response()->json(['status' => 400, 'msg' => 'Something went wrong..!!']);
        } else {
            CandidateActivityLog::addToCandLog($JCId, $query->Aadhaar, 'Candidate is BlackListed because ' . $Remark);
            return response()->json(['status' => 200, 'msg' => 'Candidate Blaclisted successfully']);
        }
    }

    public function UnBlockCandidate(Request $request)
    {
        $JCId = $request->JCId;
        $Remark = $request->Remark;

        $query = jobcandidate::find($JCId);
        $query->BlackList = 0;
        $query->UnBlockRemark = $Remark;
        $query->save();
        if (!$query) {
            return response()->json(['status' => 400, 'msg' => 'Something went wrong..!!']);
        } else {
            CandidateActivityLog::addToCandLog($JCId, $query->Aadhaar, 'Candidate is Unblocked because ' . $Remark);
            return response()->json(['status' => 200, 'msg' => 'Candidate Unblocked successfully']);
        }
    }

    public function getJobResponseCandidateByJPId(Request $request)
    {
        $JPId = $request->JPId;
        $Gender = $request->Gender;
        $Source = $request->Source;
        $HR_Screening_Status = $request->HR_Screening_Status;
        $State = $request->State;
        $City = $request->City;
        $usersQuery = jobapply::query();
        if ($Gender != '') {
            $usersQuery->where("jobcandidates.Gender", $Gender);
        }
        if ($Source != '') {
            $usersQuery->where("jobapply.ResumeSource", $Source);
        }
        if ($State != '') {
            $usersQuery->where("jobcandidates.State", $State);
        }
        if ($City != '') {
            $usersQuery->where("jobcandidates.City", 'LIKE', '%' . $City . '%');
        }
        if ($HR_Screening_Status != '') {
            if ($HR_Screening_Status == 'Notview') {
                $usersQuery->whereNull("jobapply.Status");
            } else {
                $usersQuery->where("jobapply.Status", $HR_Screening_Status);
            }
        }


        $data = $usersQuery->select(
            'jobapply.*',
            'jobcandidates.*',
            'master_education.EducationCode',
            'master_specialization.Specialization',
            'jobpost.JPId',
            'jobpost.Title',
            'jobpost.DesigId',
            'master_resumesource.ResumeSource',
            'jobapply.Status as hr_screening_status',
            'jobapply.RejectRemark as hr_screening_remark',
            'jobapply.Type',
            'jobapply.SelectedBy as hr_screening_by',
            'jobapply.FwdTechScr',
            'screening.ScreeningBy as tech_screening_by',
            'screening.ScreenStatus as tech_screening_status',
            'screening.screening_remark'
        )
            ->Join('jobcandidates', 'jobapply.JCId', '=', 'jobcandidates.JCId')
            ->leftJoin('jobpost', 'jobapply.JPId', '=', 'jobpost.JPId')
            ->leftJoin('screening', 'jobapply.JAId', '=', 'screening.JAId')
            ->leftJoin('master_education', 'jobcandidates.Education', '=', 'master_education.EducationId')
            ->leftJoin('master_specialization', 'jobcandidates.Specialization', '=', 'master_specialization.SpId')
            ->leftJoin('master_resumesource', 'jobapply.ResumeSource', '=', 'master_resumesource.ResumeSouId')
            ->where('jobapply.JPId', $JPId)
            ->paginate(10);

        $links = $data->links('vendor.pagination.custom');

        $links = str_replace("<a", "<a class='page_click page-link' ", $links);

        return response(array('data' => $data, 'page_link' => (string)$links), 200);
    }

    public function cropImage(Request $request)
    {
        $file = $request->file('CandidateImage');
        $newImageName = 'UIMG' . date('YmdHis') . uniqid() . '.jpg';
        $move = $file->storeAs('Recruitment/Pictures', $newImageName, 's3');
        if (!$move) {
            return response()->json(['status' => 0, 'msg' => 'Something went wrong!']);
        } else {
            return response()->json(['status' => 1, 'msg' => 'success']);
        }
    }


    public function candidate_interview_form()
    {
        $state_list = DB::table("states")->orderBy('StateName', 'asc')->pluck("StateName", "StateId");
        $district_list = DB::table("master_district")->orderBy('DistrictName', 'asc')->pluck("DistrictName", "DistrictId");
        $education_list = DB::table("master_education")->orderBy('EducationCode', 'asc')->pluck("EducationCode", "EducationId");
        $specialization_list = DB::table("master_specialization")->orderBy('Specialization', 'asc')->pluck("Specialization", "SpId");

        return view('jobportal.candidate_interview_form', compact('state_list', 'district_list', 'education_list', 'specialization_list'));
    }

    public function SaveInterviewConfirmation(Request $request)
    {
        $JCId = $request->JCId;
        $candidate = jobcandidate::find($JCId);
        $candidate->InterviewConfirm = 'Y';
        $candidate->save();
        return response()->json(['status' => 200, 'msg' => 'Interview Confirmation Saved Successfully']);
    }

    public function CandidateJoiningForm(Request $Request)
    {
        return view('jobportal.candidate_joining_form');
    }

    public function onboarding(Request $request)
    {
        $state_list = DB::table("states")->orderBy('StateName', 'asc')->pluck("StateName", "StateId");
        $district_list = DB::table("master_district")->orderBy('DistrictName', 'asc')->pluck("DistrictName", "DistrictId");
        $education_list = DB::table("master_education")->orderBy('EducationCode', 'asc')->pluck("EducationCode", "EducationId");
        $specialization_list = DB::table("master_specialization")->orderBy('Specialization', 'asc')->pluck("Specialization", "SpId");
        $institute_list = DB::table("master_institute")->orderBy('InstituteName', 'asc')->pluck("InstituteName", "InstituteId");
        return view('jobportal.onboarding', compact('state_list', 'district_list', 'education_list', 'specialization_list', 'institute_list'));
    }

    public function SavePersonalInfo(Request $request)
    {
        $JCId = $request->JCId;
        $Title = $request->Title;
        $FName = $request->FName;
        $MName = $request->MName ?? null;
        $LName = $request->LName ?? null;
        $DOB = $request->DOB;
        $Gender = $request->Gender;
        //   $Nationality = $request->Nationality;
        $Religion = $request->Religion;
        $OtherReligion = $request->OtherReligion ?? null;
        $Category = $request->Category;
        $OtherCategory = $request->OtherCategory ?? null;
        $MaritalStatus = $request->MaritalStatus;
        $MarriageDate = $request->MarriageDate ?? null;
        $SpouseName = $request->SpouseName ?? null;
        $CandidateImage = $request->old_image ?? null;

        if (isset($request->CandidateImage)) {
            $CandidateImage = $JCId . '.' . $request->CandidateImage->extension();
            $request->CandidateImage->storeAs('Recruitment/Picture', $CandidateImage, 's3');
        }


        $query = DB::table('jobcandidates')
            ->where('JCId', $JCId)
            ->update(
                [
                    'Title' => $Title,
                    'FName' => $FName,
                    'MName' => $MName,
                    'LName' => $LName,
                    'DOB' => $DOB,
                    'Gender' => $Gender,
                    //   'Nationality' => $Nationality,
                    'Religion' => $Religion,
                    'OtherReligion' => $OtherReligion,
                    'Caste' => $Category,
                    'OtherCaste' => $OtherCategory,
                    'MaritalStatus' => $MaritalStatus,
                    'MarriageDate' => $MarriageDate,
                    'SpouseName' => $SpouseName,
                    'CandidateImage' => $CandidateImage,
                    'LastUpdated' => now(),

                ]
            );


        if (!$query) {
            return response()->json(['status' => 400, 'msg' => 'Something went wrong..!!']);
        } else {

            return response()->json(['status' => 200, 'msg' => 'Data has been changed successfully']);
        }
    }

    public function SaveContact(Request $request)
    {
        $JCId = $request->JCId;
        $Email = $request->Email1;
        $Email2 = $request->Email2 ?? null;
        $Contact = $request->Contact1;
        $Contact2 = $request->Contact2 ?? null;
        $PreAddress = $request->PreAddress;
        $PreState = $request->PreState;
        $PreDistrict = $request->PreDistrict;
        $PreCity = $request->PreCity;
        $PrePin = $request->PrePin;
        $PermAddress = $request->PermAddress;
        $PermState = $request->PermState;
        $PermDistrict = $request->PermDistrict;
        $PermCity = $request->PermCity;
        $PermPin = $request->PermPin;

        $query = DB::table('jobcandidates')
            ->where('JCId', $JCId)
            ->update(
                [
                    'Email' => $Email,
                    'Email2' => $Email2,
                    'Phone' => $Contact,
                    'Phone2' => $Contact2,
                    'LastUpdated' => now(),

                ]
            );

        $chk = DB::table('jf_contact_det')->where('JCId', $JCId)->first();

        if ($chk == null) {
            $query1 = DB::table('jf_contact_det')
                ->insert(
                    [
                        'JCId' => $JCId,
                        'pre_address' => $PreAddress,
                        'pre_state' => $PreState,
                        'pre_dist' => $PreDistrict,
                        'pre_city' => $PreCity,
                        'pre_pin' => $PrePin,
                        'perm_address' => $PermAddress,
                        'perm_state' => $PermState,
                        'perm_dist' => $PermDistrict,
                        'perm_city' => $PermCity,
                        'perm_pin' => $PermPin,
                        'LastUpdated' => now(),

                    ]
                );
        } else {
            $query1 = DB::table('jf_contact_det')
                ->where('JCId', $JCId)
                ->update(
                    [
                        'pre_address' => $PreAddress,
                        'pre_state' => $PreState,
                        'pre_dist' => $PreDistrict,
                        'pre_city' => $PreCity,
                        'pre_pin' => $PrePin,
                        'perm_address' => $PermAddress,
                        'perm_state' => $PermState,
                        'perm_dist' => $PermDistrict,
                        'perm_city' => $PermCity,
                        'perm_pin' => $PermPin,
                        'LastUpdated' => now(),

                    ]
                );
        }

        if (isset($request->EmgName1)) {
            $EmgName1 = $request->EmgName1;
            $EmgRel1 = $request->EmgRel1;
            $EmgContact1 = $request->EmgContact1;

            $EmgName2 = $request->EmgName2 ?? null;
            $EmgRel2 = $request->EmgRel2 ?? null;
            $EmgContact2 = $request->EmgContact2 ?? null;
            $sql = DB::table('jf_contact_det')->where('JCId', $JCId)->update(
                [
                    'cont_one_name' => $EmgName1,
                    'cont_one_relation' => $EmgRel1,
                    'cont_one_number' => $EmgContact1,
                    'cont_two_name' => $EmgName2,
                    'cont_two_relation' => $EmgRel2,
                    'cont_two_number' => $EmgContact2,
                    'LastUpdated' => now(),

                ]
            );
        }

        if (!$query || !$query1) {
            return response()->json(['status' => 400, 'msg' => 'Something went wrong..!!']);
        } else {
            return response()->json(['status' => 200, 'msg' => 'Data has been changed successfully']);
        }
    }

    public function SaveEducation(Request $request)
    {

        DB::beginTransaction();
        $JCId = $request->JCId;
        $Qualification = $request->Qualification;
        $Course = $request->Course;
        $Specialization = $request->Specialization;
        $Institute = $request->Collage;
        $PassingYear = $request->PassingYear;
        $CGPA = $request->Percentage;
        $OtherInstitute = $request->OtherInstitute;
        $File_Attachment = $request->Attachment;
        try {
            $query = DB::table('candidateeducation')->where('JCId', $JCId)->delete();

            $educationArray = array();
            for ($i = 0; $i < count($Qualification); $i++) {

                $educationRecord = [
                    'JCId' => $JCId,
                    'Qualification' => $Qualification[$i],
                    'Course' => $Course[$i],
                    'Specialization' => $Specialization[$i],
                    'Institute' => $Institute[$i],
                    'OtherInstitute' => $OtherInstitute[$i],
                    'YearOfPassing' => $PassingYear[$i],
                    'CGPA' => $CGPA[$i],
                    'LastUpdated' => now()
                ];
                // Insert the education record and get its ID
                $query = DB::table('candidateeducation')->insert($educationRecord);
                $educationId = DB::getPdo()->lastInsertId();
                if (isset($File_Attachment[$i])) {
                    $filename = $JCId . '_' . $Qualification[$i] . '.' . $File_Attachment[$i]->extension();
                    // Check if file exists in S3 and delete it
                    if (Storage::disk('s3')->exists('Recruitment/Documents/' . $filename)) {
                        Storage::disk('s3')->delete('Recruitment/Documents/' . $filename);
                    }

                    // Upload new file to S3
                    Storage::disk('s3')->put('Recruitment/Documents/' . $filename, file_get_contents($File_Attachment[$i]));
                    DB::table('candidateeducation')->where('CEId', $educationId)->update(['File_Attachment' => $filename]);
                }
            }
            DB::commit();
            return response()->json(['status' => 200, 'msg' => 'Data has been changed successfully']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status' => 400, 'msg' => 'Something went wrong..!!']);
        }
    }

    public function SaveFamily(Request $request)
    {

        $JCId = $request->JCId;
        $Relation = $request->Relation;
        $RelationName = $request->RelationName;
        $RelationDOB = $request->RelationDOB;
        $RelationQualification = $request->RelationQualification;
        $RelationOccupation = $request->RelationOccupation;

        $query = DB::table('jf_family_det')->where('JCId', $JCId)->delete();

        $FamilyArray = array();
        for ($i = 0; $i < count($Relation); $i++) {
            $FamilyArray[$i] = array(
                'JCId' => $JCId,
                'relation' => $Relation[$i],
                'name' => $RelationName[$i],
                'dob' => $RelationDOB[$i],
                'qualification' => $RelationQualification[$i],
                'occupation' => $RelationOccupation[$i],
            );
        }

        $query1 = DB::table('jf_family_det')->insert($FamilyArray);
        if (!$query1) {
            return response()->json(['status' => 400, 'msg' => 'Something went wrong..!!']);
        } else {

            return response()->json(['status' => 200, 'msg' => 'Data has been changed successfully']);
        }
    }

    public function SaveExperience(Request $request)
    {

        $JCId = $request->JCId;
        $ProfCheck = $request->ProfCheck;
        $CurrCompany = $request->CurrCompany ?? null;
        $CurrDesignation = $request->CurrDesignation ?? null;
        $CurrJoinDate = $request->CurrJoinDate ?? null;
        $CurrCTC = $request->CurrCTC ?? null;
        $CurrSalary = $request->CurrSalary ?? null;
        $NoticePeriod = $request->NoticePeriod ?? null;
        $ResignReason = $request->ResignReason ?? null;
        $ExpCTC = $request->ExpCTC ?? null;
        $JobResponsibility = $request->JobResponsibility ?? null;
        $DAHeadquarter = $request->DAHeadquarter ?? null;
        $DAOutsideHeadquarter = $request->DAOutsideHeadquarter ?? null;
        $PetrolAllowances = $request->PetrolAllowances ?? null;
        $ReportingManager = $request->ReportingManager ?? null;
        $RepDesignation = $request->RepDesignation ?? null;

        $HotelEligibility = $request->HotelEligibility ?? null;
        $ToatalExpYears = $request->ToatalExpYears ?? null;
        $TotalExpMonth = $request->TotalExpMonth ?? null;

        $WorkExpCompany = $request->WorkExpCompany ?? null;
        $WorkExpDesignation = $request->WorkExpDesignation ?? null;
        $WorkExpGrossMonthlySalary = $request->WorkExpGrossMonthlySalary ?? null;
        $WorkExpAnualCTC = $request->WorkExpAnualCTC ?? null;
        $WorkExpJobStartDate = $request->WorkExpJobStartDate ?? null;
        $WorkExpJobEndDate = $request->WorkExpJobEndDate ?? null;
        $WorkExpReasonForLeaving = $request->WorkExpReasonForLeaving ?? null;

        $TrainingNature = $request->TrainingNature ?? null;
        $TrainingOrganization = $request->TrainingOrganization ?? null;
        $TrainingFromDate = $request->TrainingFromDate ?? null;
        $TrainingToDate = $request->TrainingToDate ?? null;
        //  ====================================================
        $UAN = $request->UAN ?? null;
        $PF = $request->PF ?? null;
        $ESIC = $request->ESIC ?? null;
        //========================================================
        $query = DB::table('jobcandidates')->where('JCId', $JCId)->update(
            [
                'Professional' => $ProfCheck,
                'PresentCompany' => $CurrCompany,
                'Designation' => $CurrDesignation,
                'JobStartDate' => $CurrJoinDate,
                'JobResponsibility' => $JobResponsibility,
                'ResignReason' => $ResignReason,
                'NoticePeriod' => $NoticePeriod,
                'DAHq' => $DAHeadquarter,
                'GrossSalary' => $CurrSalary,
                'DAOutHq' => $DAOutsideHeadquarter,
                'PetrolAlw' => $PetrolAllowances,
                'Reporting' => $ReportingManager,
                'RepDesig' => $RepDesignation,
                'HotelElg' => $HotelEligibility,
                'ExpectedCTC' => $ExpCTC,
                'CTC' => $CurrCTC,
                'TotalYear' => $ToatalExpYears,
                'TotalMonth' => $TotalExpMonth,
                'Medical' => $request->Medical,
                'GrpTermIns' => $request->GrpTermIns ?? null,
                'GrpPersonalAccIns' => $request->GrpPersonalAccIns ?? null,
                'MobileHandset' => $request->MobileHandset ?? null,
                'MobileBill' => $request->MobileBill ?? null,
                'TravelElg' => $request->TravelElg ?? null,
                'LastUpdated' => now()
            ]
        );
        $query3 = DB::table('jf_pf_esic')->where('JCId', $JCId)->update(
            [
                'UAN' => $UAN,
                'PFNumber' => $PF,
                'ESICNumber' => $ESIC,
                'LastUpdated' => now()
            ]
        );


        $chk = DB::table('pre_job_details')->where('JCId', $JCId)->first();
        if ($chk != null) {
            $benefit = DB::table('pre_job_details')->where('JCId', $JCId)->update(
                [
                    'OnRollRepToMe' => $request->OnRollRepToMe ?? null,
                    'ThirdPartyRepToMe' => $request->ThirdPartyRepToMe ?? null,
                    'TerritoryDetails' => $request->TerritoryDetails ?? null,
                    'VegCurrTurnOver' => $request->VegCurrTurnOver ?? null,
                    'VegPreTurnOver' => $request->VegPreTurnOver ?? null,
                    'FieldCurrTurnOver' => $request->FieldCurrTurnOver ?? null,
                    'FieldPreTurnOver' => $request->FieldPreTurnOver ?? null,
                    'MonthlyIncentive' => $request->MonthlyIncentive ?? null,
                    'QuarterlyIncentive' => $request->QuarterlyIncentive ?? null,
                    'HalfYearlyIncentive' => $request->HalfYearlyIncentive ?? null,
                    'AnnuallyIncentive' => $request->AnnuallyIncentive ?? null,
                    'AnyOtheIncentive' => $request->AnyOtheIncentive ?? null,

                    'TwoWheelChk' => (!isset($request->TwoWheelChk) || $request->TwoWheelChk == null) ? 0 : 1,
                    'TwoWheelOwnerType' => $request->TwoWheelOwnerType ?? null,
                    'TwoWheelAmount' => $request->TwoWheelAmount ?? null,
                    'TwoWheelPetrol' => $request->TwoWheelPetrol ?? null,
                    'TwoWheelPetrolTerm' => $request->TwoWheelPetrolTerm ?? null,
                    'FourWheelChk' => (!isset($request->FourWheelChk) || $request->FourWheelChk == null) ? 0 : 1,
                    'FourWheelOwnerType' => $request->FourWheelOwnerType ?? null,
                    'FourWheelAmount' => $request->FourWheelAmount ?? null,
                    'FourWheelPetrol' => $request->FourWheelPetrol ?? null,
                    'FourWheelPetrolTerm' => $request->FourWheelPetrolTerm ?? null,
                    'OtherBenifit' => $request->OtherBenifit ?? null,
                    'LastUpdated' => now()
                ]
            );
        } else {
            $benefit = DB::table('pre_job_details')->insert(
                [
                    'JCId' => $JCId,
                    'OnRollRepToMe' => $request->OnRollRepToMe ?? null,
                    'ThirdPartyRepToMe' => $request->ThirdPartyRepToMe ?? null,
                    'TerritoryDetails' => $request->TerritoryDetails ?? null,
                    'VegCurrTurnOver' => $request->VegCurrTurnOver ?? null,
                    'VegPreTurnOver' => $request->VegPreTurnOver ?? null,
                    'FieldCurrTurnOver' => $request->FieldCurrTurnOver ?? null,
                    'FieldPreTurnOver' => $request->FieldPreTurnOver ?? null,
                    'MonthlyIncentive' => $request->MonthlyIncentive ?? null,
                    'QuarterlyIncentive' => $request->QuarterlyIncentive ?? null,
                    'HalfYearlyIncentive' => $request->HalfYearlyIncentive ?? null,
                    'AnnuallyIncentive' => $request->AnnuallyIncentive ?? null,
                    'AnyOtheIncentive' => $request->AnyOtheIncentive ?? null,
                    'TwoWheelChk' => (!isset($request->TwoWheelChk) || $request->TwoWheelChk == null) ? 0 : 1,
                    'TwoWheelOwnerType' => $request->TwoWheelOwnerType ?? null,
                    'TwoWheelAmount' => $request->TwoWheelAmount ?? null,
                    'TwoWheelPetrol' => $request->TwoWheelPetrol ?? null,
                    'TwoWheelPetrolTerm' => $request->TwoWheelPetrolTerm ?? null,
                    'FourWheelChk' => (!isset($request->FourWheelChk) || $request->FourWheelChk == null) ? 0 : 1,
                    'FourWheelOwnerType' => $request->FourWheelOwnerType ?? null,
                    'FourWheelAmount' => $request->FourWheelAmount ?? null,
                    'FourWheelPetrol' => $request->FourWheelPetrol ?? null,
                    'FourWheelPetrolTerm' => $request->FourWheelPetrolTerm ?? null,
                    'OtherBenifit' => $request->OtherBenifit ?? null,
                    'LastUpdated' => now()
                ]
            );
        }

        if ($WorkExpCompany[0] != null || $WorkExpCompany[0] != '') {
            $delete_work_exp = DB::table('jf_work_exp')->where('JCId', $JCId)->delete();
            $experienceArray = array();
            for ($i = 0; $i < count($WorkExpCompany); $i++) {
                $experienceArray[$i] = array(
                    'JCId' => $JCId,
                    'company' => $WorkExpCompany[$i],
                    'desgination' => $WorkExpDesignation[$i],
                    'gross_mon_sal' => $WorkExpGrossMonthlySalary[$i],
                    'annual_ctc' => $WorkExpAnualCTC[$i],
                    'job_start' => $WorkExpJobStartDate[$i],
                    'job_end' => $WorkExpJobEndDate[$i],
                    'reason_fr_leaving' => $WorkExpReasonForLeaving[$i],
                    'LastUpdated' => now()
                );
            }

            $query1 = DB::table('jf_work_exp')->insert($experienceArray);
        }


        if ($TrainingNature[0] != null || $TrainingNature[0] != '') {
            $del_training = DB::table('jf_tranprac')->where('JCId', $JCId)->delete();
            $trainingArray = array();
            for ($i = 0; $i < count($TrainingNature); $i++) {
                $trainingArray[$i] = array(
                    'JCId' => $JCId,
                    'training' => $TrainingNature[$i],
                    'organization' => $TrainingOrganization[$i],
                    'from' => $TrainingFromDate[$i],
                    'to' => $TrainingToDate[$i],
                );
            }
            $query2 = DB::table('jf_tranprac')->insert($trainingArray);
        }

        if (!$query) {
            return response()->json(['status' => 400, 'msg' => 'Something went wrong..!!']);
        } else {
            return response()->json(['status' => 200, 'msg' => 'Data has been changed successfully']);
        }
    }


    public function SaveAbout(Request $request)
    {

        $JCId = $request->JCId;
        $AboutAim = $request->AboutAim;
        $AboutHobbi = $request->AboutHobbi;
        $About5Year = $request->About5Year;
        $AboutAssets = $request->AboutAssets;
        $AboutImprovement = $request->AboutImprovement;
        $AboutStrength = $request->AboutStrength;
        $AboutDeficiency = $request->AboutDeficiency;
        $CriminalChk = $request->CriminalChk;
        $AboutCriminal = $request->AboutCriminal ?? null;
        $LicenseChk = $request->LicenseChk;
        $DLNo = $request->DLNo ?? null;
        $LValidity = $request->LValidity ?? null;

        $chk = DB::table('about_answer')->where('JCId', $JCId)->first();
        if ($chk == null) {
            $about = DB::table('about_answer')->insert(
                [
                    'JCId' => $JCId,
                    'AboutAim' => $AboutAim,
                    'AboutHobbi' => $AboutHobbi,
                    'About5Year' => $About5Year,
                    'AboutAssets' => $AboutAssets,
                    'AboutImprovement' => $AboutImprovement,
                    'AboutStrength' => $AboutStrength,
                    'AboutDeficiency' => $AboutDeficiency,
                    'CriminalChk' => $CriminalChk,
                    'AboutCriminal' => $AboutCriminal,
                    'LicenseChk' => $LicenseChk,
                    'DLNo' => $DLNo,
                    'LValidity' => $LValidity,
                    'LastUpdated' => now()
                ]
            );
        } else {
            $about = DB::table('about_answer')->where('JCId', $JCId)->update(
                [
                    'AboutAim' => $AboutAim,
                    'AboutHobbi' => $AboutHobbi,
                    'About5Year' => $About5Year,
                    'AboutAssets' => $AboutAssets,
                    'AboutImprovement' => $AboutImprovement,
                    'AboutStrength' => $AboutStrength,
                    'AboutDeficiency' => $AboutDeficiency,
                    'CriminalChk' => $CriminalChk,
                    'AboutCriminal' => $AboutCriminal,
                    'LicenseChk' => $LicenseChk,
                    'DLNo' => $DLNo,
                    'LValidity' => $LValidity,
                    'LastUpdated' => now()
                ]
            );
        }

        if (!$about) {
            return response()->json(['status' => 400, 'msg' => 'Something went wrong..!!']);
        } else {
            return response()->json(['status' => 200, 'msg' => 'Data has been changed successfully']);
        }
    }

    public function SaveOther(Request $request)
    {

        // dd($request->all());
        $JCId = $request->JCId;
        $language_array = $request->language_array;
        $PerOrgArray = $request->PerOrgArray;
        $VnrRefArray = $request->VnrRefArray;
        $VnrBusinessRefArray = $request->VnrBusinessRefArray;
        $VnrOtherSeedArray = $request->VnrOtherSeedArray;

        if ($language_array[0]['language'] != null) {
            $query = DB::table('jf_language')->where('JCId', $JCId)->delete();
            foreach ($language_array as $key => $value) {
                $lang = DB::table('jf_language')->insert(['JCId' => $JCId, 'language' => $value['language'], 'read' => $value['read'], 'write' => $value['write'], 'speak' => $value['speak']]);
            }
        }

        if (isset($PerOrgArray)) {
            if ($PerOrgArray[0]['PreOrgName'] != null || $PerOrgArray[0]['PreOrgName'] != '') {
                $query1 = DB::table('jf_reference')->where('JCId', $JCId)->where('from', 'Previous Organization')->delete();
                foreach ($PerOrgArray as $key => $value) {
                    $preRef = DB::table('jf_reference')->insert(['JCId' => $JCId, 'from' => 'Previous Organization', 'name' => $value['PreOrgName'], 'company' => $value['PreOrgCompany'], 'designation' => $value['PreOrgDesignation'], 'email' => $value['PreOrgEmail'], 'contact' => $value['PreOrgContact']]);
                }
            }
        }

        if (isset($VnrRefArray)) {
            if ($VnrRefArray[0]['VnrRefName'] != null && $VnrRefArray[0]['VnrRefName'] != '') {
                $query2 = DB::table('jf_reference')->where('JCId', $JCId)->where('from', 'VNR')->delete();
                foreach ($VnrRefArray as $key => $value) {
                    $vnrRef = DB::table('jf_reference')->insert(['JCId' => $JCId, 'from' => 'VNR', 'name' => $value['VnrRefName'], 'rel_with_person' => $value['VnrRefRelWithPerson'], 'designation' => $value['VnrRefDesignation'], 'email' => $value['VnrRefEmail'], 'contact' => $value['VnrRefContact'], 'company' => $value['VnrRefCompany'], 'other_company' => $value['OtherCompany'], 'location' => $value['VnrRefLocation']]);
                }
            }
        }

        if (isset($VnrBusinessRefArray)) {
            if ($VnrBusinessRefArray[0]['VnrRefBusiness_Name'] != null && $VnrBusinessRefArray[0]['VnrRefBusiness_Name'] != '') {
                $query2 = DB::table('vnr_business_ref')->where('JCId', $JCId)->delete();
                foreach ($VnrBusinessRefArray as $key => $value) {
                    $vnrRef = DB::table('vnr_business_ref')->insert(['JCId' => $JCId, 'Name' => $value['VnrRefBusiness_Name'], 'Mobile' => $value['VnrRefBusiness_Contact'], 'Email' => $value['VnrRefBusiness_Email'], 'BusinessRelation' => $value['VnrRefBusinessRelation'], 'Location' => $value['VnrRefBusiness_Location'], 'PersonRelation' => $value['VnrRefBusiness_RelWithPerson']]);
                }
            }
        }

        if (isset($VnrOtherSeedArray)) {
            if ($VnrOtherSeedArray[0]['OtherSeedName'] != null && $VnrOtherSeedArray[0]['OtherSeedName'] != '') {
                $query2 = DB::table('relation_other_seed_cmp')->where('JCId', $JCId)->delete();
                foreach ($VnrOtherSeedArray as $key => $value) {
                    $vnrRef = DB::table('relation_other_seed_cmp')->insert(['JCId' => $JCId, 'Name' => $value['OtherSeedName'], 'Mobile' => $value['OtherSeedMobile'], 'Email' => $value['OtherSeedEMail'], 'company_name' => $value['OtherSeedCompany'], 'Designation' => $value['OtherSeedDesignation'], 'Location' => $value['OtherSeedLocation'], 'Relation' => $value['OtherSeedRelation']]);
                }
            }
        }

        if (isset($request->UAN)) {
            $chk = DB::table('jf_pf_esic')->where('JCId', $JCId)->first();
            if ($chk != null) {
                $query3 = DB::table('jf_pf_esic')->where('JCId', $JCId)->update(['UAN' => $request->UAN, 'ESIC_Chk' => $request->ESIC_Chk, 'PFNumber' => $request->PF, 'ESICNumber' => $request->ESIC ?? null, 'LastUpdated' => now()]);
            } else {
                $query3 = DB::table('jf_pf_esic')->insert(['JCId' => $JCId, 'UAN' => $request->UAN, 'ESIC_Chk' => $request->ESIC_Chk, 'PFNumber' => $request->PF, 'ESICNumber' => $request->ESIC ?? null, 'LastUpdated' => now()]);
            }
        }

        $sql = DB::table('jobcandidates')->where('JCId', $JCId)->update(['VNR_Acq' => $request->VNR_Acq, 'VNR_Acq_Business' => $request->VNR_Acq_Business, 'OtherSeedRelation' => $request->OtherSeedRelation, 'LastUpdated' => now()]);
        if (!$lang) {
            return response()->json(['status' => 400, 'msg' => 'Something went wrong..!!']);
        } else {
            return response()->json(['status' => 200, 'msg' => 'Data has been changed successfully']);
        }
    }

    public function OfferLtrFileUpload(Request $request)
    {
        $request->validate(['OfferLtr' => 'required|mimes:pdf|max:2048']);
        $JCId = $request->JCId;
        $filename = 'Previous_Offer_Letter_' . $JCId . '.' . $request->OfferLtr->extension();

        // Delete existing file from S3 if it exists
        if (Storage::disk('s3')->exists('Recruitment/Documents/' . $filename)) {
            Storage::disk('s3')->delete('Recruitment/Documents/' . $filename);
        }

        $request->OfferLtr->storeAs('Recruitment/Documents', $filename, 's3');
        $chk = DB::table('jf_docs')->where('JCId', $JCId)->first();
        if ($chk == null) {
            $offer = DB::table('jf_docs')->insert(
                [
                    'JCId' => $JCId,
                    'OfferLtr' => $filename,
                    'LastUpdated' => now()
                ]
            );
        } else {
            $offer = DB::table('jf_docs')->where('JCId', $JCId)->update(
                [
                    'OfferLtr' => $filename,
                    'LastUpdated' => now()
                ]
            );
        }

        if (!$offer) {
            return response()->json(['status' => 400, 'msg' => 'Something went wrong..!!']);
        } else {
            return response()->json(['status' => 200, 'msg' => 'File has been uploaded successfully']);
        }
    }

    public function RelievingLtrFileUpload(Request $request)
    {
        $request->validate(['RelievingLtr' => 'required|mimes:pdf']);
        $JCId = $request->JCId;
        $filename = 'Previous_Relieving_Letter_' . $JCId . '.' . $request->RelievingLtr->extension();

        if (Storage::disk('s3')->exists('Recruitment/Documents/' . $filename)) {
            Storage::disk('s3')->delete('Recruitment/Documents/' . $filename);
        }

        $request->RelievingLtr->storeAs('Recruitment/Documents', $filename, 's3');
        $chk = DB::table('jf_docs')->where('JCId', $JCId)->first();
        if ($chk == null) {
            $offer = DB::table('jf_docs')->insert(
                [
                    'JCId' => $JCId,
                    'RelievingLtr' => $filename,
                    'LastUpdated' => now()
                ]
            );
        } else {
            $offer = DB::table('jf_docs')->where('JCId', $JCId)->update(
                [
                    'RelievingLtr' => $filename,
                    'LastUpdated' => now()
                ]
            );
        }

        if (!$offer) {
            return response()->json(['status' => 400, 'msg' => 'Something went wrong..!!']);
        } else {
            return response()->json(['status' => 200, 'msg' => 'File has been uploaded successfully']);
        }
    }

    public function SalarySlipFileUpload(Request $request)
    {
        $request->validate(['SalarySlip' => 'required|mimes:pdf|max:2048']);
        $JCId = $request->JCId;
        $filename = 'Previous_Salaray_Slip_' . $JCId . '.' . $request->SalarySlip->extension();
        if (Storage::disk('s3')->exists('Recruitment/Documents/' . $filename)) {
            Storage::disk('s3')->delete('Recruitment/Documents/' . $filename);
        }

        $request->SalarySlip->storeAs('Recruitment/Documents', $filename, 's3');
        $chk = DB::table('jf_docs')->where('JCId', $JCId)->first();
        if ($chk == null) {
            $offer = DB::table('jf_docs')->insert(
                [
                    'JCId' => $JCId,
                    'SalarySlip' => $filename,
                    'LastUpdated' => now()
                ]
            );
        } else {
            $offer = DB::table('jf_docs')->where('JCId', $JCId)->update(
                [
                    'SalarySlip' => $filename,
                    'LastUpdated' => now()
                ]
            );
        }

        if (!$offer) {
            return response()->json(['status' => 400, 'msg' => 'Something went wrong..!!']);
        } else {
            return response()->json(['status' => 200, 'msg' => 'File has been uploaded successfully']);
        }
    }

    public function AppraisalLtrFileUpload(Request $request)
    {
        $request->validate(['AppraisalLtr' => 'required|mimes:pdf|max:2048']);
        $JCId = $request->JCId;
        $filename = 'Previous_Appraisal_Letter_' . $JCId . '.' . $request->AppraisalLtr->extension();
        if (Storage::disk('s3')->exists('Recruitment/Documents/' . $filename)) {
            Storage::disk('s3')->delete('Recruitment/Documents/' . $filename);
        }

        $request->AppraisalLtr->storeAs('Recruitment/Documents', $filename, 's3');
        $chk = DB::table('jf_docs')->where('JCId', $JCId)->first();
        if ($chk == null) {
            $offer = DB::table('jf_docs')->insert(
                [
                    'JCId' => $JCId,
                    'AppraisalLtr' => $filename,
                    'LastUpdated' => now()
                ]
            );
        } else {
            $offer = DB::table('jf_docs')->where('JCId', $JCId)->update(
                [
                    'AppraisalLtr' => $filename,
                    'LastUpdated' => now()
                ]
            );
        }

        if (!$offer) {
            return response()->json(['status' => 400, 'msg' => 'Something went wrong..!!']);
        } else {
            return response()->json(['status' => 200, 'msg' => 'File has been uploaded successfully']);
        }
    }

    public function VaccinationCertFileUpload(Request $request)
    {
        $request->validate(['VaccinationCert' => 'required|mimes:pdf|max:2048']);
        $JCId = $request->JCId;
        $filename = 'VaccinationCertificate_' . $JCId . '.' . $request->VaccinationCert->extension();
        if (Storage::disk('s3')->exists('Recruitment/Documents/' . $filename)) {
            Storage::disk('s3')->delete('Recruitment/Documents/' . $filename);
        }

        $request->VaccinationCert->storeAs('Recruitment/Documents', $filename, 's3');
        $chk = DB::table('jf_docs')->where('JCId', $JCId)->first();
        if ($chk == null) {
            $offer = DB::table('jf_docs')->insert(
                [
                    'JCId' => $JCId,
                    'VaccinationCert' => $filename,
                    'LastUpdated' => now()
                ]
            );
        } else {
            $offer = DB::table('jf_docs')->where('JCId', $JCId)->update(
                [
                    'VaccinationCert' => $filename,
                    'LastUpdated' => now()
                ]
            );
        }

        if (!$offer) {
            return response()->json(['status' => 400, 'msg' => 'Something went wrong..!!']);
        } else {
            return response()->json(['status' => 200, 'msg' => 'File has been uploaded successfully']);
        }
    }

    public function PFeNominationFileUpload(Request $request)
    {
        $request->validate(['PFeNomination' => 'required|mimes:pdf|max:2048']);
        $JCId = $request->JCId;
        $filename = 'PFeNomination_' . $JCId . '.' . $request->PFeNomination->extension();
        if (Storage::disk('s3')->exists('Recruitment/Documents/' . $filename)) {
            Storage::disk('s3')->delete('Recruitment/Documents/' . $filename);
        }

        $request->PFeNomination->storeAs('Recruitment/Documents', $filename, 's3');
        $chk = DB::table('jf_docs')->where('JCId', $JCId)->first();
        if ($chk == null) {
            $offer = DB::table('jf_docs')->insert(
                [
                    'JCId' => $JCId,
                    'PFeNomination' => $filename,
                    'LastUpdated' => now()
                ]
            );
        } else {
            $offer = DB::table('jf_docs')->where('JCId', $JCId)->update(
                [
                    'PFeNomination' => $filename,
                    'LastUpdated' => now()
                ]
            );
        }

        if (!$offer) {
            return response()->json(['status' => 400, 'msg' => 'Something went wrong..!!']);
        } else {
            return response()->json(['status' => 200, 'msg' => 'File has been uploaded successfully']);
        }
    }

    public function ResignationFileUpload(Request $request)
    {
        $request->validate(['Resignation' => 'required|mimes:pdf|max:2048']);
        $JCId = $request->JCId;
        $filename = 'Resignation_' . $JCId . '.' . $request->Resignation->extension();
        if (Storage::disk('s3')->exists('Recruitment/Documents/' . $filename)) {
            Storage::disk('s3')->delete('Recruitment/Documents/' . $filename);
        }

        $request->Resignation->storeAs('Recruitment/Documents', $filename, 's3');
        $chk = DB::table('jf_docs')->where('JCId', $JCId)->first();
        if ($chk == null) {
            $offer = DB::table('jf_docs')->insert(
                [
                    'JCId' => $JCId,
                    'Resignation' => $filename,
                    'LastUpdated' => now()
                ]
            );
        } else {
            $offer = DB::table('jf_docs')->where('JCId', $JCId)->update(
                [
                    'Resignation' => $filename,
                    'LastUpdated' => now()
                ]
            );
        }

        if (!$offer) {
            return response()->json(['status' => 400, 'msg' => 'Something went wrong..!!']);
        } else {
            return response()->json(['status' => 200, 'msg' => 'File has been uploaded successfully']);
        }
    }

    public function ResignationAcceptFileUpload(Request $request)
    {
        $request->validate(['Resignation_Accept' => 'required|mimes:pdf|max:2048']);
        $JCId = $request->JCId;
        $filename = 'Resignation_Accept_' . $JCId . '.' . $request->Resignation_Accept->extension();
        if (Storage::disk('s3')->exists('Recruitment/Documents/' . $filename)) {
            Storage::disk('s3')->delete('Recruitment/Documents/' . $filename);
        }

        $request->Resignation_Accept->storeAs('Recruitment/Documents', $filename, 's3');
        $chk = DB::table('jf_docs')->where('JCId', $JCId)->first();
        if ($chk == null) {
            $query = DB::table('jf_docs')->insert(
                [
                    'JCId' => $JCId,
                    'Resignation_Accept' => $filename,
                    'LastUpdated' => now()
                ]
            );
        } else {
            $query = DB::table('jf_docs')->where('JCId', $JCId)->update(
                [
                    'Resignation_Accept' => $filename,
                    'LastUpdated' => now()
                ]
            );
        }

        if (!$query) {
            return response()->json(['status' => 400, 'msg' => 'Something went wrong..!!']);
        } else {
            return response()->json(['status' => 200, 'msg' => 'File has been uploaded successfully']);
        }
    }

    public function Epfo_JointFileUpload(Request $request)
    {
        $request->validate(['Epfo_Joint' => 'required|mimes:pdf|max:2048']);
        $JCId = $request->JCId;
        $filename = 'Epfo_Joint_' . $JCId . '.' . $request->Epfo_Joint->extension();
        if (Storage::disk('s3')->exists('Recruitment/Documents/' . $filename)) {
            Storage::disk('s3')->delete('Recruitment/Documents/' . $filename);
        }

        $request->Epfo_Joint->storeAs('Recruitment/Documents', $filename, 's3');
        $chk = DB::table('jf_docs')->where('JCId', $JCId)->first();
        if ($chk == null) {
            $offer = DB::table('jf_docs')->insert(
                [
                    'JCId' => $JCId,
                    'Epfo_Joint' => $filename,
                    'LastUpdated' => now()
                ]
            );
        } else {
            $offer = DB::table('jf_docs')->where('JCId', $JCId)->update(
                [
                    'Epfo_Joint' => $filename,
                    'LastUpdated' => now()
                ]
            );
        }

        if (!$offer) {
            return response()->json(['status' => 400, 'msg' => 'Something went wrong..!!']);
        } else {
            return response()->json(['status' => 200, 'msg' => 'File has been uploaded successfully']);
        }
    }

    public function Form16FileUpload(Request $request)
    {
        $request->validate(['Form16' => 'required|mimes:pdf|max:2048']);
        $JCId = $request->JCId;
        $filename = 'Form16_' . $JCId . '.' . $request->Form16->extension();
        if (Storage::disk('s3')->exists('Recruitment/Documents/' . $filename)) {
            Storage::disk('s3')->delete('Recruitment/Documents/' . $filename);
        }

        $request->Form16->storeAs('Recruitment/Documents', $filename, 's3');
        $chk = DB::table('jf_docs')->where('JCId', $JCId)->first();
        if ($chk == null) {
            $offer = DB::table('jf_docs')->insert(
                [
                    'JCId' => $JCId,
                    'Form16' => $filename,
                    'LastUpdated' => now()
                ]
            );
        } else {
            $offer = DB::table('jf_docs')->where('JCId', $JCId)->update(
                [
                    'Form16' => $filename,
                    'LastUpdated' => now()
                ]
            );
        }

        if (!$offer) {
            return response()->json(['status' => 400, 'msg' => 'Something went wrong..!!']);
        } else {
            return response()->json(['status' => 200, 'msg' => 'File has been uploaded successfully']);
        }
    }

    public function CheckDocumentUpload(Request $request)
    {
        $JCId = $request->JCId;
        $chk = DB::table('jf_docs')->where('JCId', $JCId)->first();
        $query = jobcandidate::find($JCId);
        $Professional = $query->Professional;
        if ($Professional == 'P') {
            if ($chk->OfferLtr == null || $chk->SalarySlip == null) {
                return response()->json(['status' => 400, 'msg' => 'Please upload all documents']);
            } else {
                return response()->json(['status' => 200, 'msg' => 'All documents uploaded successfully']);
            }
        } else {
            return response()->json(['status' => 200, 'msg' => 'All documents uploaded successfully']);
        }
    }

    public function FinalSubmitInterviewApplicationForm(Request $request)
    {
        $JCId = $request->JCId;

        // Update the 'InterviewSubmit' status and 'LastUpdated' timestamp in one go
        $isUpdated = DB::table('jobcandidates')
            ->where('JCId', $JCId)
            ->update([
                'InterviewSubmit' => '1',
                'LastUpdated' => now()
            ]);

        if (!$isUpdated) {
            return response()->json(['status' => 400, 'msg' => 'Something went wrong..!!']);
        }

        // Fetch candidate and related job details in a single query
        $candidateData = DB::table('jobcandidates')
            ->join('jobapply', 'jobcandidates.JCId', '=', 'jobapply.JCId')
            ->join('jobpost', 'jobapply.JPId', '=', 'jobpost.JPId')
            ->where('jobcandidates.JCId', $JCId)
            ->select(
                'jobpost.CreatedBy',
                'jobcandidates.FName',
                'jobcandidates.MName',
                'jobcandidates.LName',
                'jobcandidates.Aadhaar',
                'jobcandidates.ReferenceNo'
            )
            ->first();

        // Prepare recruiter's email and candidate's full name
        $recruiterId = $candidateData->CreatedBy;
        $recruiterEmail = getEmailID($recruiterId);
        $fullName = $this->formatFullName($candidateData);

        // Notification and email details
        $details = [
            'subject' => 'Interview Application Form Completed - ' . $fullName,
            'recruiter_name' => getFullName($recruiterId),
            'candidate_name' => $fullName,
            'reference_no' => $candidateData->ReferenceNo,
        ];

        // Notify recruiter and send an email
        UserNotification::notifyUser($recruiterId, 'Pre Interview Form', "$fullName has submitted the pre-interview form");
        Mail::to($recruiterEmail)
            ->cc('recruitment@vnrseeds.com')
            ->send(new InterviewAppSubmitMail($details));

        // Log candidate activity
        CandidateActivityLog::addToCandLog($JCId, $candidateData->Aadhaar, 'Pre Interview Form has been submitted');

        return response()->json(['status' => 200, 'msg' => 'Interview Form has been submitted successfully']);
    }

    /**
     * Format the full name of the candidate
     *
     * @param object $candidateData
     * @return string
     */
    private function formatFullName($candidateData)
    {
        $nameParts = array_filter([
            $candidateData->FName,
            $candidateData->MName,
            $candidateData->LName
        ], fn($part) => !empty(trim($part)));

        return ucwords(strtolower(implode(' ', $nameParts)));
    }


    public function JoiningFormSubmit(Request $request)
    {
        $JCId = $request->JCId;
        $query = DB::table('jobcandidates')->where('JCId', $JCId)->update(
            [
                'FinalSubmit' => '1',
                'LastUpdated' => now()
            ]
        );
        // Fetch candidate and related job details in a single query
        $candidateData = DB::table('jobcandidates')
            ->join('jobapply', 'jobcandidates.JCId', '=', 'jobapply.JCId')
            ->join('jobpost', 'jobapply.JPId', '=', 'jobpost.JPId')
            ->where('jobcandidates.JCId', $JCId)
            ->select(
                'jobpost.CreatedBy',
                'jobcandidates.FName',
                'jobcandidates.MName',
                'jobcandidates.LName',
                'jobcandidates.Aadhaar',
                'jobcandidates.ReferenceNo'
            )
            ->first();

        // Prepare recruiter's email and candidate's full name
        $recruiterId = $candidateData->CreatedBy;
        $recruiterEmail = getEmailID($recruiterId);
        $fullName = $this->formatFullName($candidateData);

        // Notification and email details
        $details = [
            'subject' => 'Joining/Onboarding Forms Completed - ' . $fullName,
            'recruiter_name' => getFullName($recruiterId),
            'candidate_name' => $fullName,
            'reference_no' => $candidateData->ReferenceNo,
        ];
        UserNotification::notifyUser($recruiterId, 'Joining Form', $fullName . ' has submitted the joining form');
        Mail::to($recruiterEmail)
            ->cc('recruitment@vnrseeds.com')
            ->send(new JoiningFormSubmitMail($details));
        if ($query) {
            CandidateActivityLog::addToCandLog($JCId, $candidateData->Aadhaar, 'Joining Form has been submitted');
            return response()->json(['status' => 200, 'msg' => 'Joining Form has been submitted successfully']);
        } else {
            return response()->json(['status' => 400, 'msg' => 'Something went wrong..!!']);
        }
    }

    public function AadhaarUpload(Request $request)
    {
        $request->validate(['AadhaarCard' => 'required|mimes:pdf|max:2048']);
        $JCId = $request->JCId;
        $filename = 'Aadhar_' . $JCId . '.' . $request->AadhaarCard->extension();
        if (Storage::disk('s3')->exists('Recruitment/Documents/' . $filename)) {
            Storage::disk('s3')->delete('Recruitment/Documents/' . $filename);
        }

        $request->AadhaarCard->storeAs('Recruitment/Documents', $filename, 's3');
        $query = DB::table('jobcandidates')->where('JCId', $JCId)->update(['Aadhaar' => $request->Aadhaar]);
        $chk = DB::table('jf_docs')->where('JCId', $JCId)->first();
        if ($chk == null) {
            $query = DB::table('jf_docs')->insert(
                [
                    'JCId' => $JCId,
                    'Aadhar' => $filename,
                    'LastUpdated' => now()
                ]
            );
        } else {
            $query = DB::table('jf_docs')->where('JCId', $JCId)->update(
                [
                    'Aadhar' => $filename,
                    'LastUpdated' => now()
                ]
            );
        }

        if (!$query) {
            return response()->json(['status' => 400, 'msg' => 'Something went wrong..!!']);
        } else {
            return response()->json(['status' => 200, 'msg' => 'File has been uploaded successfully']);
        }
    }

    public function PanCardUpload(Request $request)
    {
        $request->validate(['PANCard' => 'required|mimes:pdf|max:2048']);
        $JCId = $request->JCId;
        $filename = 'PanCard_' . $JCId . '.' . $request->PANCard->extension();

           if (Storage::disk('s3')->exists('Recruitment/Documents/' . $filename)) {
            Storage::disk('s3')->delete('Recruitment/Documents/' . $filename);
        }

        $request->PANCard->storeAs('Recruitment/Documents', $filename, 's3');
        $chk = DB::table('jf_docs')->where('JCId', $JCId)->first();
        if ($chk == null) {
            $query = DB::table('jf_docs')->insert(
                [
                    'JCId' => $JCId,
                    'PanCard' => $filename,
                    'LastUpdated' => now()
                ]
            );
        } else {
            $query = DB::table('jf_docs')->where('JCId', $JCId)->update(
                [
                    'PanCard' => $filename,
                    'LastUpdated' => now()
                ]
            );
        }
        $chk = DB::table('jf_pf_esic')->where('JCId', $JCId)->first();
        if ($chk == null) {
            $query1 = DB::table('jf_pf_esic')->insert(
                [
                    'JCId' => $JCId,
                    'PAN' => $request->PanCardNumber,
                    'LastUpdated' => now()
                ]
            );
        } else {
            $query1 = DB::table('jf_pf_esic')->where('JCId', $JCId)->update(
                [
                    'PAN' => $request->PanCardNumber,
                    'LastUpdated' => now()
                ]
            );
        }

        if (!$query) {
            return response()->json(['status' => 400, 'msg' => 'Something went wrong..!!']);
        } else {
            return response()->json(['status' => 200, 'msg' => 'File has been uploaded successfully']);
        }
    }

    public function PassportUpload(Request $request)
    {
        $request->validate(['Passport' => 'required|mimes:pdf|max:2048']);
        $JCId = $request->JCId;
        $filename = 'Passport_' . $JCId . '.' . $request->Passport->extension();

         if (Storage::disk('s3')->exists('Recruitment/Documents/' . $filename)) {
            Storage::disk('s3')->delete('Recruitment/Documents/' . $filename);
        }

        $request->Passport->storeAs('Recruitment/Documents', $filename, 's3');
        $chk = DB::table('jf_docs')->where('JCId', $JCId)->first();
        if ($chk == null) {
            $query = DB::table('jf_docs')->insert(
                [
                    'JCId' => $JCId,
                    'Passport' => $filename,
                    'LastUpdated' => now()
                ]
            );
        } else {
            $query = DB::table('jf_docs')->where('JCId', $JCId)->update(
                [
                    'Passport' => $filename,
                    'LastUpdated' => now()
                ]
            );
        }
        $chk1 = DB::table('jf_pf_esic')->where('JCId', $JCId)->first();
        if ($chk1 == null) {
            $query1 = DB::table('jf_pf_esic')->insert(
                [
                    'JCId' => $JCId,
                    'Passport' => $request->PassportNumber,
                    'LastUpdated' => now()
                ]
            );
        } else {
            $query1 = DB::table('jf_pf_esic')->where('JCId', $JCId)->update(
                [
                    'Passport' => $request->PassportNumber,
                    'LastUpdated' => now()
                ]
            );
        }
        if (!$query) {
            return response()->json(['status' => 400, 'msg' => 'Something went wrong..!!']);
        } else {
            return response()->json(['status' => 200, 'msg' => 'File has been uploaded successfully']);
        }
    }

    public function DlUpload(Request $request)
    {
        $request->validate(['DLCard' => 'required|mimes:pdf|max:2048']);
        $JCId = $request->JCId;
        $filename = 'DL_' . $JCId . '.' . $request->DLCard->extension();

        if (Storage::disk('s3')->exists('Recruitment/Documents/' . $filename)) {
            Storage::disk('s3')->delete('Recruitment/Documents/' . $filename);
        }

        $request->DLCard->storeAs('Recruitment/Documents', $filename, 's3');
        $chk = DB::table('jf_docs')->where('JCId', $JCId)->first();
        if ($chk == null) {
            $query = DB::table('jf_docs')->insert(
                [
                    'JCId' => $JCId,
                    'DL' => $filename,
                    'LastUpdated' => now()
                ]
            );
        } else {
            $query = DB::table('jf_docs')->where('JCId', $JCId)->update(
                [
                    'DL' => $filename,
                    'LastUpdated' => now()
                ]
            );
        }

        if (!$query) {
            return response()->json(['status' => 400, 'msg' => 'Something went wrong..!!']);
        } else {
            return response()->json(['status' => 200, 'msg' => 'File has been uploaded successfully']);
        }
    }

    public function PF_Form2Upload(Request $request)
    {
        $request->validate(['PFForm2' => 'required|mimes:pdf|max:2048']);
        $JCId = $request->JCId;
        $filename = 'PF_Form2_' . $JCId . '.' . $request->PFForm2->extension();

           if (Storage::disk('s3')->exists('Recruitment/Documents/' . $filename)) {
            Storage::disk('s3')->delete('Recruitment/Documents/' . $filename);
        }

        $request->PFForm2->storeAs('Recruitment/Documents', $filename, 's3');
        $chk = DB::table('jf_docs')->where('JCId', $JCId)->first();
        if ($chk == null) {
            $query = DB::table('jf_docs')->insert(
                [
                    'JCId' => $JCId,
                    'PF_Form2' => $filename,
                    'LastUpdated' => now()
                ]
            );
        } else {
            $query = DB::table('jf_docs')->where('JCId', $JCId)->update(
                [
                    'PF_Form2' => $filename,
                    'LastUpdated' => now()
                ]
            );
        }

        if (!$query) {
            return response()->json(['status' => 400, 'msg' => 'Something went wrong..!!']);
        } else {
            return response()->json(['status' => 200, 'msg' => 'File has been uploaded successfully']);
        }
    }

    public function PF_Form11Upload(Request $request)
    {
        $request->validate(['PF_Form11' => 'required|mimes:pdf|max:2048']);
        $JCId = $request->JCId;
        $filename = 'PF_Form11_' . $JCId . '.' . $request->PF_Form11->extension();

           if (Storage::disk('s3')->exists('Recruitment/Documents/' . $filename)) {
            Storage::disk('s3')->delete('Recruitment/Documents/' . $filename);
        }

        $request->PF_Form11->storeAs('Recruitment/Documents', $filename, 's3');
        $chk = DB::table('jf_docs')->where('JCId', $JCId)->first();
        if ($chk == null) {
            $query = DB::table('jf_docs')->insert(
                [
                    'JCId' => $JCId,
                    'PF_Form11' => $filename,
                    'LastUpdated' => now()
                ]
            );
        } else {
            $query = DB::table('jf_docs')->where('JCId', $JCId)->update(
                [
                    'PF_Form11' => $filename,
                    'LastUpdated' => now()
                ]
            );
        }

        if (!$query) {
            return response()->json(['status' => 400, 'msg' => 'Something went wrong..!!']);
        } else {
            return response()->json(['status' => 200, 'msg' => 'File has been uploaded successfully']);
        }
    }

    public function GratuityUpload(Request $request)
    {
        $request->validate(['GratuityForm' => 'required|mimes:pdf|max:2048']);
        $JCId = $request->JCId;
        $filename = 'Gratuity_' . $JCId . '.' . $request->GratuityForm->extension();

          if (Storage::disk('s3')->exists('Recruitment/Documents/' . $filename)) {
            Storage::disk('s3')->delete('Recruitment/Documents/' . $filename);
        }

        $request->GratuityForm->storeAs('Recruitment/Documents', $filename, 's3');
        $chk = DB::table('jf_docs')->where('JCId', $JCId)->first();
        if ($chk == null) {
            $query = DB::table('jf_docs')->insert(
                [
                    'JCId' => $JCId,
                    'Gratutity' => $filename,
                    'LastUpdated' => now()
                ]
            );
        } else {
            $query = DB::table('jf_docs')->where('JCId', $JCId)->update(
                [
                    'Gratutity' => $filename,
                    'LastUpdated' => now()
                ]
            );
        }

        if (!$query) {
            return response()->json(['status' => 400, 'msg' => 'Something went wrong..!!']);
        } else {
            return response()->json(['status' => 200, 'msg' => 'File has been uploaded successfully']);
        }
    }

    public function ESICUpload(Request $request)
    {
        $request->validate(['ESICForm' => 'required|mimes:pdf|max:2048']);
        $JCId = $request->JCId;
        $filename = 'ESIC_' . $JCId . '.' . $request->ESICForm->extension();

          if (Storage::disk('s3')->exists('Recruitment/Documents/' . $filename)) {
            Storage::disk('s3')->delete('Recruitment/Documents/' . $filename);
        }

        $request->ESICForm->storeAs('Recruitment/Documents', $filename, 's3');
        $chk = DB::table('jf_docs')->where('JCId', $JCId)->first();
        if ($chk == null) {
            $query = DB::table('jf_docs')->insert(
                [
                    'JCId' => $JCId,
                    'ESIC' => $filename,
                    'LastUpdated' => now()
                ]
            );
        } else {
            $query = DB::table('jf_docs')->where('JCId', $JCId)->update(
                [
                    'ESIC' => $filename,
                    'LastUpdated' => now()
                ]
            );
        }

        if (!$query) {
            return response()->json(['status' => 400, 'msg' => 'Something went wrong..!!']);
        } else {
            return response()->json(['status' => 200, 'msg' => 'File has been uploaded successfully']);
        }
    }

    public function FamilyUpload(Request $request)
    {
        $request->validate(['ESIC_Family' => 'required|mimes:pdf|max:2048']);
        $JCId = $request->JCId;
        $filename = 'ESIC_Family_' . $JCId . '.' . $request->ESIC_Family->extension();

          if (Storage::disk('s3')->exists('Recruitment/Documents/' . $filename)) {
            Storage::disk('s3')->delete('Recruitment/Documents/' . $filename);
        }

        $request->ESIC_Family->storeAs('Recruitment/Documents', $filename, 's3');
        $chk = DB::table('jf_docs')->where('JCId', $JCId)->first();
        if ($chk == null) {
            $query = DB::table('jf_docs')->insert(
                [
                    'JCId' => $JCId,
                    'ESIC_Family' => $filename,
                    'LastUpdated' => now()
                ]
            );
        } else {
            $query = DB::table('jf_docs')->where('JCId', $JCId)->update(
                [
                    'ESIC_Family' => $filename,
                    'LastUpdated' => now()
                ]
            );
        }

        if (!$query) {
            return response()->json(['status' => 400, 'msg' => 'Something went wrong..!!']);
        } else {
            return response()->json(['status' => 200, 'msg' => 'File has been uploaded successfully']);
        }
    }

    public function HealthUpload(Request $request)
    {
        $request->validate(['Health' => 'required|mimes:pdf|max:2048']);
        $JCId = $request->JCId;
        $filename = 'Health_' . $JCId . '.' . $request->Health->extension();

          if (Storage::disk('s3')->exists('Recruitment/Documents/' . $filename)) {
            Storage::disk('s3')->delete('Recruitment/Documents/' . $filename);
        }

        $request->Health->storeAs('Recruitment/Documents', $filename, 's3');
        $chk = DB::table('jf_docs')->where('JCId', $JCId)->first();
        if ($chk == null) {
            $query = DB::table('jf_docs')->insert(
                [
                    'JCId' => $JCId,
                    'Health' => $filename,
                    'LastUpdated' => now()
                ]
            );
        } else {
            $query = DB::table('jf_docs')->where('JCId', $JCId)->update(
                [
                    'Health' => $filename,
                    'LastUpdated' => now()
                ]
            );
        }

        if (!$query) {
            return response()->json(['status' => 400, 'msg' => 'Something went wrong..!!']);
        } else {
            return response()->json(['status' => 200, 'msg' => 'File has been uploaded successfully']);
        }
    }

    public function EthicalUpload(Request $request)
    {
        $request->validate(['Ethical' => 'required|mimes:pdf|max:2048']);
        $JCId = $request->JCId;
        $filename = 'Ethical_' . $JCId . '.' . $request->Ethical->extension();

           if (Storage::disk('s3')->exists('Recruitment/Documents/' . $filename)) {
            Storage::disk('s3')->delete('Recruitment/Documents/' . $filename);
        }

        $request->Ethical->storeAs('Recruitment/Documents', $filename, 's3');
        $chk = DB::table('jf_docs')->where('JCId', $JCId)->first();
        if ($chk == null) {
            $query = DB::table('jf_docs')->insert(
                [
                    'JCId' => $JCId,
                    'Ethical' => $filename,
                    'LastUpdated' => now()
                ]
            );
        } else {
            $query = DB::table('jf_docs')->where('JCId', $JCId)->update(
                [
                    'Ethical' => $filename,
                    'LastUpdated' => now()
                ]
            );
        }

        if (!$query) {
            return response()->json(['status' => 400, 'msg' => 'Something went wrong..!!']);
        } else {
            return response()->json(['status' => 200, 'msg' => 'File has been uploaded successfully']);
        }
    }

    public function BloodGroupUpload(Request $request)
    {
        $request->validate(['BloodGroup' => 'required|mimes:pdf|max:2048']);
        $JCId = $request->JCId;
        $filename = 'BloodGroup_' . $JCId . '.' . $request->BloodGroup->extension();

         if (Storage::disk('s3')->exists('Recruitment/Documents/' . $filename)) {
            Storage::disk('s3')->delete('Recruitment/Documents/' . $filename);
        }

        $request->BloodGroup->storeAs('Recruitment/Documents', $filename, 's3');
        $chk = DB::table('jf_docs')->where('JCId', $JCId)->first();
        if ($chk == null) {
            $query = DB::table('jf_docs')->insert(
                [
                    'JCId' => $JCId,
                    'BloodGroup' => $filename,
                    'LastUpdated' => now()
                ]
            );
        } else {
            $query = DB::table('jf_docs')->where('JCId', $JCId)->update(
                [
                    'BloodGroup' => $filename,
                    'LastUpdated' => now()
                ]
            );
        }
        $chk = DB::table('jobcandidates')->where('JCId', $JCId)->first();
        if ($chk == null) {
            $query = DB::table('jobcandidates')->insert(
                [
                    'JCId' => $JCId,
                    'bloodgroup' => $request->Blood,
                    'LastUpdated' => now()
                ]
            );
        } else {
            $query = DB::table('jobcandidates')->where('JCId', $JCId)->update(
                [
                    'bloodgroup' => $request->Blood,
                    'LastUpdated' => now()
                ]
            );
        }
        if (!$query) {
            return response()->json(['status' => 400, 'msg' => 'Something went wrong..!!']);
        } else {
            return response()->json(['status' => 200, 'msg' => 'File has been uploaded successfully']);
        }
    }

    public function Invst_DeclUpload(Request $request)
    {
        $request->validate(['Invst_Decl' => 'required|mimes:pdf|max:2048']);
        $JCId = $request->JCId;
        $filename = 'Invst_Decl_' . $JCId . '.' . $request->Invst_Decl->extension();

         if (Storage::disk('s3')->exists('Recruitment/Documents/' . $filename)) {
            Storage::disk('s3')->delete('Recruitment/Documents/' . $filename);
        }

        $request->Invst_Decl->storeAs('Recruitment/Documents', $filename, 's3');
        $chk = DB::table('jf_docs')->where('JCId', $JCId)->first();
        if ($chk == null) {
            $query = DB::table('jf_docs')->insert(
                [
                    'JCId' => $JCId,
                    'Invst_Decl' => $filename,
                    'LastUpdated' => now()
                ]
            );
        } else {
            $query = DB::table('jf_docs')->where('JCId', $JCId)->update(
                [
                    'Invst_Decl' => $filename,
                    'LastUpdated' => now()
                ]
            );
        }

        if (!$query) {
            return response()->json(['status' => 400, 'msg' => 'Something went wrong..!!']);
        } else {
            return response()->json(['status' => 200, 'msg' => 'File has been uploaded successfully']);
        }
    }

    public function BankUpload(Request $request)
    {
        $request->validate(['BankPassBook' => 'required|mimes:pdf|max:2048']);
        $JCId = $request->JCId;
        $filename = 'BankDoc_' . $JCId . '.' . $request->BankPassBook->extension();

          if (Storage::disk('s3')->exists('Recruitment/Documents/' . $filename)) {
            Storage::disk('s3')->delete('Recruitment/Documents/' . $filename);
        }

        $request->BankPassBook->storeAs('Recruitment/Documents', $filename, 's3');
        $chk = DB::table('jf_docs')->where('JCId', $JCId)->first();
        if ($chk == null) {
            $query = DB::table('jf_docs')->insert(
                [
                    'JCId' => $JCId,
                    'BankDoc' => $filename,
                    'LastUpdated' => now()
                ]
            );
        } else {
            $query = DB::table('jf_docs')->where('JCId', $JCId)->update(
                [
                    'BankDoc' => $filename,
                    'LastUpdated' => now()
                ]
            );
        }
        $chk = DB::table('jf_pf_esic')->where('JCId', $JCId)->first();

        if ($chk == null) {
            $query1 = DB::table('jf_pf_esic')->insert(
                [
                    'JCId' => $JCId,
                    'BankName' => $request->BankName,
                    'BranchName' => $request->BranchName,
                    'AccountNumber' => $request->AccNumber,
                    'IFSCCode' => $request->IFSC,
                    'LastUpdated' => now()
                ]
            );
        } else {
            $query1 = DB::table('jf_pf_esic')->where('JCId', $JCId)->update(
                [
                    'BankName' => $request->BankName,
                    'BranchName' => $request->BranchName,
                    'AccountNumber' => $request->AccNumber,
                    'IFSCCode' => $request->IFSC,
                    'LastUpdated' => now()
                ]
            );
        }
        if (!$query) {
            return response()->json(['status' => 400, 'msg' => 'Something went wrong..!!']);
        } else {
            return response()->json(['status' => 200, 'msg' => 'File has been uploaded successfully']);
        }
    }

    public function TestPaperUpload(Request $request)
    {
        $request->validate(['Test_Paper' => 'required|mimes:pdf|max:2048']);
        $JCId = $request->JCId;
        $filename = 'Test_Paper_' . $JCId . '.' . $request->Test_Paper->extension();

         if (Storage::disk('s3')->exists('Recruitment/Documents/' . $filename)) {
            Storage::disk('s3')->delete('Recruitment/Documents/' . $filename);
        }

        $request->Test_Paper->storeAs('Recruitment/Documents', $filename, 's3');
        $chk = DB::table('jf_docs')->where('JCId', $JCId)->first();
        if ($chk == null) {
            $query = DB::table('jf_docs')->insert(
                [
                    'JCId' => $JCId,
                    'Test_Paper' => $filename,
                    'LastUpdated' => now()
                ]
            );
        } else {
            $query = DB::table('jf_docs')->where('JCId', $JCId)->update(
                [
                    'Test_Paper' => $filename,
                    'LastUpdated' => now()
                ]
            );
        }

        if (!$query) {
            return response()->json(['status' => 400, 'msg' => 'Something went wrong..!!']);
        } else {
            return response()->json(['status' => 200, 'msg' => 'File has been uploaded successfully']);
        }
    }

    public function IntervAssessmentUpload(Request $request)
    {
        $request->validate(['IntervAssessment' => 'required|mimes:pdf|max:2048']);
        $JCId = $request->JCId;
        $filename = 'IntervAssessment_' . $JCId . '.' . $request->IntervAssessment->extension();

           if (Storage::disk('s3')->exists('Recruitment/Documents/' . $filename)) {
            Storage::disk('s3')->delete('Recruitment/Documents/' . $filename);
        }

        $request->IntervAssessment->storeAs('Recruitment/Documents', $filename, 's3');
        $chk = DB::table('jf_docs')->where('JCId', $JCId)->first();
        if ($chk == null) {
            $query = DB::table('jf_docs')->insert(
                [
                    'JCId' => $JCId,
                    'IntervAssessment' => $filename,
                    'LastUpdated' => now()
                ]
            );
        } else {
            $query = DB::table('jf_docs')->where('JCId', $JCId)->update(
                [
                    'IntervAssessment' => $filename,
                    'LastUpdated' => now()
                ]
            );
        }

        if (!$query) {
            return response()->json(['status' => 400, 'msg' => 'Something went wrong..!!']);
        } else {
            return response()->json(['status' => 200, 'msg' => 'File has been uploaded successfully']);
        }
    }

    public function CheckDocumentUpload_JoiningForm(Request $request)
    {
        $JCId = $request->JCId;
        $chk = DB::table('jf_docs')->where('JCId', $JCId)->first();
        if ($chk == null) {
            return response()->json(['status' => 400, 'msg' => 'Please Upload Documents...!!']);
        }
        $query = jobcandidate::find($JCId);
        $Professional = $query->Professional;

        $documents = [
            'Aadhar' => 'Aadhar',
            'BankDoc' => 'Bank Passbook',
            'PF_Form11' => 'PF Form 11',
            'Gratutity' => 'Gratutity',
            'Health' => 'Health',
            'BloodGroup' => 'Blood Group',
            'Ethical' => 'Ethical',
            'Invst_Decl' => 'Investment Declaration'
        ];

        // Add additional documents if the user is Professional
        if ($Professional == 'P') {
            $documents = array_merge($documents, [
                'PFeNomination' => 'PF Nomination',
                'Form16' => 'Form 16',
                'Resignation' => 'Self Resignation Declaration',
                'Resignation_Accept' => 'Resignation Acceptance by previous employer'

            ]);
        }

        $missingDocs = [];

        // Check each document
        foreach ($documents as $field => $label) {
            if (is_null($chk->$field)) {
                $missingDocs[] = $label;
            }
        }

        if (empty($missingDocs)) {
            return response()->json(['status' => 200, 'msg' => 'All documents uploaded successfully...']);
        } else {
            $msg = implode(', ', $missingDocs) . ' documents are not uploaded';
            return response()->json(['status' => 400, 'msg' => $msg]);
        }
    }


    public function get_duplicate_record(Request $request)
    {
        $FName = $request->FName;
        $Email = $request->Email;
        $Dob = $request->Dob;
        $Phone = $request->Phone;
        $FatherName = $request->FatherName;
        $candidate_list = DB::select("SELECT jobcandidates.*,jobpost.Title as jobtitle,jobapply.ApplyDate,jobapply.JAId  FROM `jobcandidates` LEFT JOIN jobapply ON jobapply.JCId = jobcandidates.JCId LEFT JOIN jobpost ON jobpost.JPId = jobapply.JPId WHERE  `Phone` = '$Phone' or `Email` = '$Email' or ('FName' = '$FName' and 'DOB' = '$Dob' and 'FatherName' = '$FatherName')");

        return view('common.duplicate_record', compact('candidate_list'));
    }

    public function delete_duplicate_record(Request $request)
    {
        $JCId = $request->JCId;
        $query = jobcandidate::find($JCId)->delete();
        $query1 = jobapply::where('JCId', $JCId)->delete();
        $query2 = DB::table('candidate_log')->where('JCId', $JCId)->delete();
        if ($query) {
            return response()->json(['status' => 200, 'msg' => 'Record has been deleted successfully']);
        } else {
            return response()->json(['status' => 400, 'msg' => 'Something went wrong..!!']);
        }
    }


    public function import(Request $request)
    {
        try {
            $file = $request->file('import_file');
            $currentDate = date('Y-m-d-H-i');
            $filename = 'cv_' . $currentDate . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('assets/uploads'), $filename);
            $import = new ApplicationImport();
            Excel::import(new ApplicationImport, public_path('assets/uploads/' . $filename));
            return response()->json(['status' => 200, 'message' => 'CVs Imported Successfully.']);
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'error' => $e->getMessage()]);
        }
    }

    public function CandidateVehicleForm(Request $Request)
    {
        return view('jobportal.candidate_vehicle_form');
    }

    public function SaveVehicleForm(Request $request)
    {
        $JCId = $request->JCId;

        // Collect vehicle data from the request
        $vehicleData = $request->only([
            'JCId',
            'vehicle_type',
            'brand',
            'model_name',
            'model_no',
            'dealer_name',
            'dealer_contact',
            'purchase_date',
            'price',
            'registration_no',
            'registration_date',
            'bill_no',
            'fuel_type',
            'ownership',
            'current_odo_meter',
            'remark',
            // New fields to be added
            'four_vehicle_type',
            'four_brand',
            'four_model_name',
            'four_model_no',
            'four_dealer_name',
            'four_dealer_contact',
            'four_purchase_date',
            'four_price',
            'four_registration_no',
            'four_registration_date',
            'four_bill_no',
            'four_fuel_type',
            'four_ownership',
            'four_current_odo_meter'
        ]);

      

        // List of file fields with new fields added
        $fileFields = [
            'invoice' => 'vehicle_invoice',
            'vehicle_image' => 'vehicle_image',
            'rc_file' => 'rc_file',
            'insurance' => 'insurance',
            'odo_meter' => 'odo_meter',
            'four_invoice' => 'four_vehicle_invoice',
            'four_vehicle_image' => 'four_vehicle_image',
            'four_rc_file' => 'four_rc_file',
            'four_insurance' => 'four_insurance',
            'four_odo_meter' => 'four_odo_meter'
        ];

        foreach ($fileFields as $field => $prefix) {
            if ($request->hasFile($field)) {
                // Upload the new file and store its name
                $fileName = $JCId . '_' . $prefix . '.' . $request->file($field)->extension();
                $request->file($field)->storeAs('Recruitment/vehicle_upload', $fileName, 's3');
                $vehicleData[$field] = $fileName;
            } elseif ($request->input("old_{$field}")) {
                // If no new file is uploaded, retain the old file name
                $vehicleData[$field] = $request->input("old_{$field}");
            }
        }

        // Check if the record exists in the vehicle_information table
        $vehicleRecord = DB::table('vehicle_information')->where('JCId', $JCId)->first();

        if ($vehicleRecord) {
            // If record exists, update it
            DB::table('vehicle_information')->where('JCId', $JCId)->update($vehicleData);
            $message = 'Vehicle Information Updated Successfully.';
        } else {
            // If no record exists, insert a new one
            DB::table('vehicle_information')->insert($vehicleData);
            $message = 'Vehicle Information Uploaded Successfully.';
        }

        // Update the jobcandidates table
        DB::table('jobcandidates')
            ->where('JCId', $JCId)
            ->update(['vehicle_form_submit' => 'Y']);

        return response()->json(['status' => 200, 'msg' => $message]);
    }


    public function SendVehicleForm(Request $request)
    {
        $JCId = $request->JCId;
        $sendId = base64_encode($JCId);
        $query = DB::table('jobcandidates')
            ->select(
                'Title',
                'jobcandidates.FName',
                'jobcandidates.MName',
                'jobcandidates.LName',
                'jobcandidates.Email'
            )
            ->where('JCId', $JCId)->first();
        $details = [
            "candidate_name" => $query->Title . ' ' . $query->FName . ' ' . $query->MName . ' ' . $query->LName,
            "subject" => "Mandatory Information and Document Submission Request for Further Processing",
            "link" => route('candidate-vehicle-form') . '?jcid=' . $sendId
        ];
        Mail::to($query->Email)->send(new VehicleInfoMail($details));
        return response()->json(['status' => 200, 'msg' => 'Vehicle Information Form Sent Successfully']);
    }
}
