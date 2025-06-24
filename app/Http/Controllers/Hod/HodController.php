<?php

namespace App\Http\Controllers\Hod;

use App\Helpers\UserNotification;
use App\Http\Controllers\Controller;
use App\Mail\TechScreenByManagerMail;
use App\Models\jobapply;
use App\Models\jobcandidate;
use App\Models\jobpost;
use App\Models\screening;
use App\Models\User;
use App\Models\UserDepartmentMap;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;


class HodController extends Controller
{
    function index()
    {

        $check_pwd_change = User::where('id', Auth::user()->id)->first();
        if ($check_pwd_change->is_pwd_changed === 'N') {
            return redirect()->route('one_time_pwd_change');
        }

        $months = [1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April', 5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August', 9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'];
        $company_list = DB::table("core_company")->orderBy('company_code', 'desc')->pluck("company_code", "id");
        $department_list = DB::table('manpowerrequisition')
            ->select('core_department.id', 'core_department.department_name')
            ->join('core_department', 'core_department.id', '=', 'manpowerrequisition.DepartmentId')
            ->where('OnBehalf', Auth::user()->id)
            ->orWhere('manpowerrequisition.CreatedBy', Auth::user()->id)
            ->orderBy('core_department.department_name', 'asc')
            ->groupBy('core_department.id')
            ->get();

        $state_list = DB::table("states")->orderBy('StateName', 'asc')->pluck("StateName", "StateId");
        $institute_list = DB::table("master_institute")->orderBy('InstituteName', 'asc')->pluck("InstituteName", "InstituteId");
        $designation_list = DB::table("core_designation")->where('designation_name', '!=', '')->orderBy('designation_name', 'asc')->pluck("designation_name", "id");
        $employee_list = DB::table('master_employee')->orderBy('FullName', 'ASC')
            ->where('EmpStatus', 'A')
            ->select('EmployeeID', DB::raw('CONCAT(Fname, " ", Lname) AS FullName'))
            ->pluck("FullName", "EmployeeID");
        $recruiter_list = DB::table('users')->where('role', 'R')->where('Status', 'A')->pluck('name', 'id');
        $active_mrf = DB::table('manpowerrequisition')->where('Status', 'Approved')->where(function ($query) {
            $query->where('manpowerrequisition.CreatedBy', Auth::user()->id)
                ->orWhere('onBehalf', Auth::user()->id);
        })->pluck('JobCode', 'MRFId');

        $MRFIds = DB::table('manpowerrequisition')
            ->where('Status', 'Approved')
            ->where(function ($query) {
                $query->where('manpowerrequisition.CreatedBy', Auth::user()->id)
                    ->orWhere('onBehalf', Auth::user()->id);
            })
            ->pluck('MRFId'); // Extract only the MRFId values


        $events = [];
        $get_events = DB::table('event_calendar')->where('belong_to', Auth::user()->id)->groupBy('start_time', 'end_time', 'title', 'description', 'belong_to', 'type', 'event_type')->get();

        foreach ($get_events as $event) {
            $events[] = [
                'title' => $event->title,
                'description' => $event->description,
                'start' => $event->start_time,
                'end' => $event->end_time,
            ];
        }
        $userId = Auth::user()->id;
        $rejectedCandidateCount = DB::table('jobapply')
            ->selectRaw('COUNT(*) as count')
            ->join('core_department', 'core_department.id', '=', 'jobapply.Department')
            ->whereIn('jobapply.Department', function ($query) use ($userId) {
                $query->select('core_department.id')
                    ->from('manpowerrequisition')
                    ->join('core_department', 'core_department.id', '=', 'manpowerrequisition.DepartmentId')

                    ->where('manpowerrequisition.OnBehalf', $userId)
                    ->orWhere('manpowerrequisition.CreatedBy', $userId)
                    ->groupBy('core_department.id');
            })
            ->where('jobapply.Status', 'Rejected')
            ->count();

        //====================Active Pipeline Funnel Chart=================
        $result = [];
        $total_applicant = jobapply::whereIn('manpowerrequisition.MRFId', $MRFIds)
            ->join('jobpost', 'jobpost.JPId', '=', 'jobapply.JPId')
            ->join('manpowerrequisition', 'manpowerrequisition.MRFId', '=', 'jobpost.MRFId')
            ->count();
        $result['Applications'] = $total_applicant;
        $hr_screening = jobapply::whereIn('manpowerrequisition.MRFId', $MRFIds)->where('jobapply.Status', 'Selected')
            ->join('jobpost', 'jobpost.JPId', '=', 'jobapply.JPId')
            ->join('manpowerrequisition', 'manpowerrequisition.MRFId', '=', 'jobpost.MRFId')
            ->count();
        $result['HR Screening'] = $hr_screening;
        $technical_screening = jobapply::whereIn('manpowerrequisition.MRFId', $MRFIds)->where('screening.ScreenStatus', 'Shortlist')
            ->join('jobpost', 'jobpost.JPId', '=', 'jobapply.JPId')
            ->join('screening', 'screening.JAId', '=', 'jobapply.JAId')
            ->join('manpowerrequisition', 'manpowerrequisition.MRFId', '=', 'jobpost.MRFId')
            ->count();
        $result['Technical Screening'] = $technical_screening;
        $first_interview = jobapply::whereIn('manpowerrequisition.MRFId', $MRFIds)->where('screening.IntervStatus', 'Selected')
            ->join('jobpost', 'jobpost.JPId', '=', 'jobapply.JPId')
            ->join('screening', 'screening.JAId', '=', 'jobapply.JAId')
            ->join('manpowerrequisition', 'manpowerrequisition.MRFId', '=', 'jobpost.MRFId')
            ->count();
        $result['1st Interview'] = $first_interview;
        $second_interview = jobapply::whereIn('manpowerrequisition.MRFId', $MRFIds)->where('screening.IntervStatus', '2nd Round Interview')->where('screen2ndround.IntervStatus2', 'Selected')
            ->join('jobpost', 'jobpost.JPId', '=', 'jobapply.JPId')
            ->join('screening', 'screening.JAId', '=', 'jobapply.JAId')
            ->join('screen2ndround', 'screen2ndround.ScId', '=', 'screening.ScId')
            ->join('manpowerrequisition', 'manpowerrequisition.MRFId', '=', 'jobpost.MRFId')
            ->count();
        $result['2nd Interview'] = $second_interview;
        $offer = jobapply::whereIn('manpowerrequisition.MRFId', $MRFIds)->where('offerletterbasic.OfferLetterSent', 'Yes')
            ->join('jobpost', 'jobpost.JPId', '=', 'jobapply.JPId')
            ->join('offerletterbasic', 'offerletterbasic.JAId', '=', 'jobapply.JAId')
            ->join('manpowerrequisition', 'manpowerrequisition.MRFId', '=', 'jobpost.MRFId')
            ->count();
        $result['Offer'] = $offer;
        $Joined = jobapply::whereIn('manpowerrequisition.MRFId', $MRFIds)->where('candjoining.Joined', 'Yes')
            ->join('jobpost', 'jobpost.JPId', '=', 'jobapply.JPId')
            ->join('candjoining', 'candjoining.JAId', '=', 'jobapply.JAId')
            ->join('manpowerrequisition', 'manpowerrequisition.MRFId', '=', 'jobpost.MRFId')
            ->count();
        $result['Joined'] = $Joined;
        $dataPoints = array();
        foreach ($result as $key => $value) {
            if ($value != 0) {
                $dataPoints[] = ['label' => $key, 'y' => $value];
            }
        }

        //=======================Funnel Chart End======================================

        //==========================TAT for MRF=======================================

        $TAT = [];


        //==========================END  TAT for MRF=======================================

        return view('hod.index', compact('rejectedCandidateCount', 'events', 'months', 'company_list',
            'department_list', 'state_list', 'institute_list', 'designation_list', 'employee_list', 'recruiter_list', 'active_mrf', 'dataPoints'));
    }

    public function mrfbyme()
    {
        $mrf = DB::table('manpowerrequisition')
            ->Join('core_designation', 'manpowerrequisition.DesigId', '=', 'core_designation.id')
            ->where('manpowerrequisition.CreatedBy', Auth::user()->id)
            ->orWhere('manpowerrequisition.OnBehalf', Auth::user()->id)
            ->select('manpowerrequisition.MRFId', 'manpowerrequisition.Type',
                'manpowerrequisition.JobCode', 'manpowerrequisition.CreatedBy',
                'core_designation.designation_name', 'manpowerrequisition.Status',
                'manpowerrequisition.CreatedTime')
            ->orderBy('manpowerrequisition.MRFId', 'desc');

        return datatables()::of($mrf)
            ->addIndexColumn()
            ->addColumn('MRFDate', function ($mrf) {
                return date('d-m-Y', strtotime($mrf->CreatedTime));
            })
            ->addColumn('CreatedBy', function ($mrf) {
                if ($mrf->Type == 'N_HrManual' || $mrf->Type == 'R_HrManual') {
                    return 'HR';
                } else {
                    return getFullName($mrf->CreatedBy);
                }
            })
            ->editColumn('Type', function ($mrf) {
                if ($mrf->Type == 'N' || $mrf->Type == 'N_HrManual') {
                    return 'New MRF';
                } else {
                    return 'Replacement MRF';
                }
            })
            ->addColumn('actions', function ($mrf) {
                if ($mrf->Status == 'New') {
                    return '<button class="btn btn-xs  btn-outline-info font-13 view" data-id="' . $mrf->MRFId . '" id="viewBtn"><i class="fadeIn animated lni lni-eye"></i></button> <button class="btn btn-sm  btn-outline-primary font-13 edit" data-id="' . $mrf->MRFId . '" id="editBtn"><i class="fadeIn animated bx bx-pencil"></i></button>
                <button class="btn btn-xs btn btn-outline-danger font-13 delete" data-id="' . $mrf->MRFId . '" id="deleteBtn"><i class="fadeIn animated bx bx-trash"></i></button>';
                } else {
                    return '<button class="btn btn-xs  btn-outline-primary font-13 view" data-id="' . $mrf->MRFId . '" id="viewBtn"><i class="fadeIn animated lni lni-eye"></i></button>';
                }
            })
            ->addColumn('chk', function () {
                return '<input type="checkbox" class="select_all">';
            })
            ->rawColumns(['actions', 'chk'])
            ->make(true);
    }

    public function interviewschedule()
    {
        return view('hod.interviewschedule');
    }

    public function pending_screening()
    {
        return view('hod.pending_screening');
    }

public function show_resume(Request $request)
{
    $JCId = $request->JCId;
    $sql = jobcandidate::where('JCId', $JCId)->first();
    $resume = $sql->Resume;

    // Get file from S3
    $s3Path = 'Recruitment/Resume/' . $resume;
    $s3Client = Storage::disk('s3')->getClient();
    $bucket = config('filesystems.disks.s3.bucket');
    
    // Generate temporary signed URL valid for 5 minutes
    $command = $s3Client->getCommand('GetObject', [
        'Bucket' => $bucket,
        'Key' => $s3Path
    ]);
    
    $request = $s3Client->createPresignedRequest($command, '+5 minutes');
    $presignedUrl = (string)$request->getUri();

    $ext = substr($resume, strrpos($resume, '.') + 1);
    $x = '';
    
    if (strtolower($ext) == 'pdf') {
        $x = '<object width="760" height="500" data="' . $presignedUrl . '"></object>';
    } else {
        $google_url = html_entity_decode('https://docs.google.com/viewer?embedded=true&url=');
        $x = '<iframe src="' . $google_url . urlencode($presignedUrl) . '" width="100%" height="500" style="border: none;"></iframe>';
    }

    $x .= '<div class="modal-footer"><a href="' . $presignedUrl . '" class="btn btn-primary" download>Download Resume</a></div>';
    
    return $x;
}
    public function change_screen_status(Request $request)
    {
        $JAId = $request->JAId;
        $value = $request->value;
        $remark = $request->remark;
        $jobapply = jobapply::where('JAId', $JAId)->first();
        $JCId = $jobapply->JCId;
        $JPId = $jobapply->JPId;
        $jobcandidate = jobcandidate::where('JCId', $JCId)->first();
        $Name = $jobcandidate->FName . ' ' . $jobcandidate->LName;
        $jobpost = jobpost::where('JPId', $JPId)->first();
        $CreatedBy = $jobpost->CreatedBy;
        $sql = screening::where('JAId', $JAId)->update(['ScreenStatus' => $value, 'screening_remark' => $remark, 'ResScreened' => date('Y-m-d')]);
        $userId = Auth::user()->id;
        $manager_name = getFullName($userId);
        $recruiter_name = getFullName($CreatedBy);
        $recruiterEmail = getEmailID($CreatedBy);
        $details = [
            'subject' => 'Candidate Resumes Reviewed by ' . $manager_name . ' - ' . $jobpost->Title,
            'recruiter_name' => $recruiter_name,
            'manager_name' => $manager_name,
        ];

        if ($sql) {
            Mail::to($recruiterEmail)->cc('recruitment@vnrseeds.com')->send(new TechScreenByManagerMail($details));
            UserNotification::notifyUser($CreatedBy, 'Screening Status', 'Techical Screening of ' . $Name . ' is ' . $value);
            return response()->json(['status' => 200, 'msg' => 'Screening Status has been changed successfully.']);
        } else {
            return response()->json(['status' => 400, 'msg' => 'Something went wrong.']);
        }
    }

    public function hr_screening_rejected_list(Request $request)
    {
        $months = [1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April', 5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August', 9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'];
        $recruiter_list = DB::table('users')->where('role', 'R')->where('Status', 'A')->pluck('name', 'id');
        $department_list = DB::table('manpowerrequisition')
            ->select('core_department.id', 'core_department.department_name')
            ->join('core_department', 'core_department.id', '=', 'manpowerrequisition.DepartmentId')

            ->where('OnBehalf', Auth::user()->id)
            ->orWhere('manpowerrequisition.CreatedBy', Auth::user()->id)
            ->orderBy('core_department.department_name', 'asc')

            ->groupBy('core_department.id')
            ->get();
        $departments = [];
        foreach ($department_list as $department) {
            $departments[] = $department->id;
        }

        $Department = $request->Department;
        $Year = $request->Year;
        $Month = $request->Month;
        $Name = $request->Name;
        $Recruiter = $request->Recruiter;
        $usersQuery = jobapply::query();

        if ($Department == 'All' || $Department == '') {
            $usersQuery->whereIn('jobapply.Department', $departments);

        } else {
            $usersQuery->where('Department', $Department);
        }
        if ($Year != '') {
            $usersQuery->whereYear('jobapply.ApplyDate', '=', $Year);
        }
        if ($Name != '') {
            $usersQuery->where("jobcandidates.FName", 'like', "%$Name%");
        }
        if ($Recruiter != '') {
            $usersQuery->where('jobapply.SelectedBy', $Recruiter);
        }
        $candidate_list = $usersQuery->select('jobcandidates.ReferenceNo AS ReferenceNo', 'jobpost.Title',
            'jobcandidates.FName', 'jobcandidates.MName', 'jobcandidates.LName',
            'jobapply.ApplyDate', 'jobcandidates.Resume', 'jobcandidates.CandidateImage',
            'jobcandidates.Email', 'jobcandidates.Phone', 'jobcandidates.JCId', 'jobapply.Type',
            'jobapply.ResumeSource', 'jobapply.OtherResumeSource', 'jobapply.RejectRemark',
            'jobapply.HrScreeningDate', 'jobapply.Status', 'jobapply.SelectedBy', 'core_department.department_code', 'jobapply.JAId')
            ->join('jobcandidates', 'jobcandidates.JCId', '=', 'jobapply.JCId')
            ->join('jobpost', 'jobpost.JPId', '=', 'jobapply.JPId')
            ->join('core_department', 'core_department.id', '=', 'jobapply.Department')
            ->where('jobapply.Status', 'Rejected')
            ->orderBy('jobapply.HrScreeningDate', 'desc');

        if ($Department != '' || $Year != '' || $Month != '' || $Name != '') {
            $candidate_list = $candidate_list->paginate(30);
            $candidate_list->appends(['Department' => $Department, 'Year' => $Year, 'Month' => $Month, 'Name' => $Name]);
        } else {
            $candidate_list = $candidate_list->paginate(30);
        }

        return view('hod.hr_screening_rejected_list', compact('months', 'department_list', 'candidate_list', 'recruiter_list'));
    }

    public function mrf_approval_list(Request $request)
    {
        $user = Auth::user();

        $mrf_list = DB::table('manpowerrequisition')
            ->where(function ($query) use ($user) {
                $query->where(function ($query) use ($user) {
                    $query->where('reporting_id', $user->id)
                        ->where('reporting_approve', 'N');
                })
                    ->orWhere(function ($query) use ($user) {
                        $query->where('hod_id', $user->id)
                            ->where('hod_approve', 'N');
                    })
                    ->orWhere(function ($query) use ($user) {
                        $query->where('management_id', $user->id)
                            ->where('management_approve', 'N');
                    });
            })
            ->get();

        return view('common.mrf_approval_list', compact('mrf_list'));
    }

    public function resume_databank(Request $request)
    {
        // Fetch dropdown lists (No change required here)
        $departments = UserDepartmentMap::where('user_id', Auth::user()->id)
            ->pluck('department_id')
            ->toArray();

        $source_list = DB::table("master_resumesource")
            ->where('Status', 'A')
            ->where('ResumeSouId', '!=', 7)
            ->pluck('ResumeSource', 'ResumeSouId');

        $education_list = DB::table("master_education")
            ->where('Status', 'A')
            ->orderBy('EducationCode', 'asc')
            ->pluck("EducationCode", "EducationId");

        $resume_list = DB::table("master_resumesource")
            ->where('Status', 'A')
            ->where('ResumeSouId', '!=', 7)
            ->orderBy('ResumeSouId', 'asc')
            ->pluck("ResumeSource", "ResumeSouId");

        $state_list = DB::table("states")
            ->where('StateId', '!=', 41)
            ->orderBy('StateName', 'asc')
            ->pluck("StateName", "StateId");

        $months = [1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April', 5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August', 9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'];

        $department_list = UserDepartmentMap::join('core_department', 'core_department.id', '=', 'user_department_map.department_id')
            ->where('user_id', Auth::user()->id)
            ->pluck('department_name', 'core_department.id')
            ->toArray();

        // Initialize query
        $usersQuery = jobapply::query();

        // Apply department filter with proper grouping
        $usersQuery->where(function ($query) use ($request, $departments) {
            if ($request->Department != '') {
                $query->where("jobapply.Department", $request->Department);
            } else {
                // Grouping the OR condition so it doesn't affect other filters
                $query->whereIn('jobapply.Department', $departments)
                    ->orWhereIn('jobcandidates.Suitable_For', $departments);
            }
        });

        // Apply Year and Month filters
        if ($request->Year != '') {
            if ($request->Month != '') {
                $startDate = "{$request->Year}-{$request->Month}-01";
                $endDate = date("Y-m-t", strtotime($startDate)); // Last day of the month
            } else {
                $startDate = "{$request->Year}-01-01";
                $endDate = "{$request->Year}-12-31";
            }
            $usersQuery->whereBetween('jobapply.ApplyDate', [$startDate, $endDate]);
        }

        // Apply other filters (no change needed for these)
        if ($request->Source != '') {
            $usersQuery->where("jobapply.ResumeSource", $request->Source);
        }
        if ($request->Gender != '') {
            $usersQuery->where("jobcandidates.Gender", $request->Gender);
        }
        if ($request->Education != '') {
            $usersQuery->where("jobcandidates.Education", $request->Education);
        }
        if ($request->State != '') {
            $usersQuery->where("jobcandidates.State", $request->State);
        }
        if ($request->Name != '') {
            $usersQuery->where("jobcandidates.FName", 'like', "%{$request->Name}%");
        }
        if ($request->Email != '') {
            $usersQuery->where("jobcandidates.Email", 'like', "%{$request->Email}%");
        }
        if ($request->Phone != '') {
            $usersQuery->where("jobcandidates.Phone", 'like', "%{$request->Phone}%");
        }
        if ($request->City != '') {
            $usersQuery->where("jobcandidates.City", 'like', "%{$request->City}%");
        }


        // Fetch the candidates list
        $candidate_list = $usersQuery->select(
            'jobapply.JAId', 'jobapply.ResumeSource', 'jobapply.Type', 'jobapply.ApplyDate',
            'jobapply.Status', 'jobapply.RejectRemark', 'jobapply.FwdTechScr', 'jobcandidates.JCId',
            'jobcandidates.ReferenceNo', 'jobcandidates.FName', 'jobcandidates.MName', 'jobcandidates.LName',
            'jobcandidates.FatherName', 'jobcandidates.Email', 'jobcandidates.Phone', 'jobcandidates.City',
            'jobcandidates.Education', 'jobcandidates.TotalYear', 'jobcandidates.TotalMonth', 'jobcandidates.Specialization',
            'jobcandidates.Professional', 'jobcandidates.JobStartDate', 'jobcandidates.JobEndDate',
            'jobcandidates.PresentCompany', 'jobcandidates.Designation', 'jobcandidates.Verified',
            'jobcandidates.CandidateImage', 'jobcandidates.BlackList', 'jobcandidates.BlackListRemark',
            'jobcandidates.UnBlockRemark', 'jobapply.JPId', 'jobpost.DesigId', 'jobcandidates.ProfileViewed',
            'jobcandidates.manual_entry_by', 'jobcandidates.manual_entry_by_name', 'jobapply.Status as hr_screening_status',
            'jobapply.RejectRemark as hr_screening_remark', 'jobapply.Type', 'jobapply.SelectedBy as hr_screening_by',
            'jobapply.FwdTechScr', 'screening.IntervStatus', 'jobapply.SLDPT'
        )
            ->join('jobcandidates', 'jobapply.JCId', '=', 'jobcandidates.JCId')
            ->leftJoin('jobpost', 'jobapply.JPId', '=', 'jobpost.JPId')
            ->leftJoin('screening', 'screening.JAId', '=', 'jobapply.JAId')
            ->orderBy('jobapply.ApplyDate', 'desc');

        // Paginate results
        if ($request->filled(['Department', 'Year', 'Month', 'Source', 'Gender', 'Education', 'Name', 'Email', 'Phone', 'ManualEntry', 'State'])) {
            $candidate_list = $candidate_list->paginate(10);
            $candidate_list->appends($request->except('page')); // Append query parameters for pagination
        } else {
            $candidate_list = $candidate_list->paginate(10);
        }

        // Return the view with the data
        return view('common.candidate_list_for_databank', compact(
            'candidate_list', 'education_list', 'resume_list', 'state_list', 'source_list', 'department_list', 'months'
        ));
    }

    public function shared_profile(Request $request)
    {
        return view('common.shared_profile');
    }

}
