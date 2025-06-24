<?php

namespace App\Http\Controllers\Report;

use App\Exports\AllCandidateData;
use App\Exports\JobResponseCandidates;
use App\Exports\MRFDetail;
use App\Models\Admin\master_user;
use App\Models\jobapply;
use App\Models\master_mrf;
use App\Models\OfferLetter;
use App\Models\jobcandidate;
use App\Models\trainee_apply;
use Illuminate\Http\Request;
use App\Models\CandidateJoining;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

use App\Models\Admin\resumesource_master;
use App\Models\User;
use Maatwebsite\Excel\Facades\Excel;
use Exception;

class Reports extends Controller
{
    public function Firob_Reports(Request $request)
    {
        $query = DB::table('jobcandidates')
            ->join('firob_user', 'firob_user.userid', '=', 'jobcandidates.JCId')
            ->where('FIROB_Test', 1)
            ->where('SubDate', '>=', now()->subMonths(1))
            ->orderBy('SubDate', 'desc');

        // If there's a search query, filter by userid
        if ($request->filled('reference_no')) {
            $userid = DB::table('jobcandidates')->where('ReferenceNo', $request->reference_no)->first()->JCId;

            $query->where('firob_user.userid', $userid);
        }

        $report_list = $query->groupBy('firob_user.userid')->get();

        return view('reports.firob_reports', compact('report_list'));
    }


    public function reports_download()
    {
        return view('reports.reports_download');
    }

    public function mrfs_report()
    {

        session()->put('submenu', 'mrfs_report');
        $company_list = DB::table("core_company")->orderBy('company_code', 'desc')->pluck("company_code", "id");
        $department_list = DB::table("core_department")->where('is_active', 'A')->orderBy('department_name', 'asc')->pluck("department_name", "id");
        $months = [1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April', 5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August', 9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'];
        return view('reports.mrfs_report', compact(['company_list', 'department_list', 'months']));
    }

    public function getMrfReport(Request $request)
    {

        $usersQuery = master_mrf::query();
        $Company = $request->Company;
        $Department = $request->Department;
        $Year = $request->Year;
        $Month = $request->Month;
        if ($Company != '') {
            $usersQuery->where("CompanyId", $Company);
        }
        if ($Department != '') {
            $usersQuery->where("DepartmentId", $Department);
        }
        if ($Year != '') {
            $usersQuery->whereBetween('CreatedTime', [$Year . '-01-01', $Year . '-12-31']);
        }
        if ($Month != '') {
            if ($Year != '') {
                $usersQuery->whereBetween('CreatedTime', [$Year . '-' . $Month . '-01', $Year . '-' . $Month . '-31']);
            } else {
                $usersQuery->whereBetween('CreatedTime', [date('Y') . '-' . $Month . '-01', date('Y') . '-' . $Month . '-31']);
            }
        }

        $mrf = $usersQuery->select('*')->orderBy('CreatedTime', 'desc');
        return datatables()->of($mrf)->addIndexColumn()->editColumn('Type', function ($mrf) {
            if ($mrf->Type == 'N' || $mrf->Type == 'N_HrManual') {
                return 'New MRF';
            } elseif ($mrf->Type == 'SIP' || $mrf->Type == 'SIP_HrManual') {
                return 'SIP/Internship MRF';
            } elseif ($mrf->Type == 'Campus' || $mrf->Type == 'Campus_HrManual') {
                return 'Campus MRF';
            } elseif ($mrf->Type == 'R' || $mrf->Type == 'R_HrManual') {
                return 'Replacement MRF';
            }
        })->editColumn('ReplacementFor', function ($mrf) {
            if ($mrf->RepEmployeeID == '0') {
                return '-';
            } else {
                return getFullName($mrf->RepEmployeeID);
            }
        })->editColumn('Department', function ($mrf) {
            return getDepartmentCode($mrf->DepartmentId);
        })->editColumn('Designation', function ($mrf) {
            return getDesignation($mrf->DesigId);
        })->editColumn('CreatedBy', function ($mrf) {
            return getFullName($mrf->CreatedBy);
        })->editColumn('OnBehalf', function ($mrf) {
            return getFullName($mrf->OnBehalf);
        })->editColumn('Allocated', function ($mrf) {
            return getFullName($mrf->Allocated);
        })->editColumn('CreatedTime', function ($mrf) {
            return date('d-m-Y', strtotime($mrf->CreatedTime));
        })->editColumn('Status', function ($mrf) {
            if ($mrf->Status == 'Approved') {
                return 'Active';
            } elseif ($mrf->Status == 'Close') {
                return 'Close';
            } elseif ($mrf->Status == 'Rejected') {
                return 'Rejected';
            } else {
                return 'Pending';
            }
        })->editColumn('CloseDt', function ($mrf) {
            if ($mrf->Status == 'Close') {
                return date('d-m-Y', strtotime($mrf->CloseDt));
            } else {
                return '-';
            }
        })
            ->addColumn('Position_Filled', function ($mrf) {
                $query = DB::table('mrf_position_filled')->where('MRFId', $mrf->MRFId)->value('Filled');
                return $query;
            })
            ->make(true);
    }

    public function application_source_report()
    {
        session()->put('submenu', 'application_source_report');
        $company_list = DB::table("core_company")->orderBy('company_code', 'desc')->pluck("company_code", "id");
        $department_list = DB::table("core_department")->where('is_active', 'A')->orderBy('department_name', 'asc')->pluck("department_name", "id");
        return view('reports.application_source_report', compact(['company_list', 'department_list']));
    }

    public function getApplicationSource(Request $request)
    {

        $usersQuery = resumesource_master::query();
        $Company = $request->Company;
        $Department = $request->Department;

        if ($Company != '') {
            $usersQuery->where("jobapply.Company", $Company);
        }
        if ($Department != '') {
            $usersQuery->where("jobapply.Department", $Department);
        }

        $query = $usersQuery->select('jobapply.Department', 'master_resumesource.ResumeSource', DB::raw("'COUNT'(JAId) as total"))->join('jobapply', 'jobapply.ResumeSource', '=', 'resumesource_master.ResumeSouId')->groupBy('jobapply.ResumeSource');

        return datatables()->of($query)->addIndexColumn()->editColumn('Department', function ($query) {
            return getDepartment($query->Department);
        })->make(true);
    }

    public function hr_screening_report()
    {
        session()->put('submenu', 'hr_screening_report');
        $company_list = DB::table("core_company")->orderBy('company_code', 'desc')->pluck("company_code", "id");
        $department_list = DB::table("core_department")->where('is_active', 'A')->orderBy('department_name', 'asc')->pluck("department_name", "id");
        $months = [1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April', 5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August', 9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'];
        return view('reports.hr_screening_report', compact(['company_list', 'department_list', 'months']));
    }

    public function get_hr_screening_report(Request $request)
    {
        $usersQuery = jobcandidate::query();
        $Company = $request->Company;
        $Department = $request->Department;
        $Year = $request->Year;
        $Month = $request->Month;
        if ($Company != '') {
            $usersQuery->where("jobapply.Company", $Company);
        }
        if ($Department != '') {
            $usersQuery->where("jobapply.Department", $Department);
        }
        if ($Year != '') {
            $usersQuery->whereBetween('jobapply.HrScreeningDate', [$Year . '-01-01', $Year . '-12-31']);
        }
        if ($Month != '') {
            if ($Year != '') {
                $usersQuery->whereBetween('jobapply.HrScreeningDate', [$Year . '-' . $Month . '-01', $Year . '-' . $Month . '-31']);
            } else {
                $usersQuery->whereBetween('jobapply.HrScreeningDate', [date('Y') . '-' . $Month . '-01', date('Y') . '-' . $Month . '-31']);
            }
        }

        $mrf = $usersQuery->select('ReferenceNo', 'FName', 'MName', 'LName', 'Department', 'jobapply.Status', 'JobCode', 'master_resumesource.ResumeSource', 'OtherResumeSource', 'users.name', 'jobapply.RejectRemark', 'jobapply.HrScreeningDate')->join('jobapply', 'jobapply.JCId', '=', 'jobcandidates.JCId')->leftJoin('jobpost', 'jobpost.JPId', '=', 'jobapply.JPId')->leftJoin('master_resumesource', 'master_resumesource.ResumeSouId', '=', 'jobapply.ResumeSource')->leftJoin('users', 'users.id', '=', 'jobapply.SelectedBy')->whereNotNull('jobapply.Status')->orderBy('JAId', 'desc');
        return datatables()->of($mrf)->addIndexColumn()->editColumn('Name', function ($mrf) {
            return $mrf->FName . ' ' . $mrf->MName . ' ' . $mrf->LName;
        })->editColumn('Department', function ($mrf) {
            return getDepartmentCode($mrf->Department);
        })->editColumn('Remark', function ($mrf) {
            return $mrf->RejectRemark;
        })->editColumn('ScreeningDate', function ($mrf) {
            if ($mrf->HrScreeningDate != null) {
                return date('d-m-Y', strtotime($mrf->HrScreeningDate));
                //return $mrf->HrScreeningDate;
            } else {
                return null;
            }
        })->make(true);
    }

    public function tech_screening_report()
    {
        session()->put('submenu', 'tech_screening_report');
        $company_list = DB::table("core_company")->orderBy('company_code', 'desc')->pluck("company_code", "id");
        $department_list = DB::table("core_department")->where('is_active', 'A')->orderBy('department_name', 'asc')->pluck("department_name", "id");
        $months = [1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April', 5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August', 9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'];
        return view('reports.tech_screening_report', compact(['company_list', 'department_list', 'months']));
    }

    public function get_tech_screening_report(Request $request)
    {
        $usersQuery = jobapply::query();
        $Company = $request->Company;
        $Department = $request->Department;
        $Year = $request->Year;
        $Month = $request->Month;
        if ($Company != '') {
            $usersQuery->where("screening.ScrCmp", $Company);
        }
        if ($Department != '') {
            $usersQuery->where("screening.ScrDpt", $Department);
        }
        if ($Year != '') {
            $usersQuery->whereBetween('screening.ReSentForScreen', [$Year . '-01-01', $Year . '-12-31']);
        }
        if ($Month != '') {
            if ($Year != '') {
                $usersQuery->whereBetween('screening.ReSentForScreen', [$Year . '-' . $Month . '-01', $Year . '-' . $Month . '-31']);
            } else {
                $usersQuery->whereBetween('screening.ReSentForScreen', [date('Y') . '-' . $Month . '-01', date('Y') . '-' . $Month . '-31']);
            }
        }

        $mrf = $usersQuery->select('jobcandidates.ReferenceNo', 'screening.ScrDpt', 'jobpost.JobCode', 'jobpost.DesigId', 'jobcandidates.FName', 'jobcandidates.MName', 'jobcandidates.LName', 'screening.ReSentForScreen', 'screening.ScreeningBy', 'screening.ResScreened', 'screening.ScreenStatus', 'screening.RejectionRem', 'master_resumesource.ResumeSource', 'OtherResumeSource', 'users.name')->join('jobcandidates', 'jobcandidates.JCId', '=', 'jobapply.JCId')->leftJoin('jobpost', 'jobpost.JPId', '=', 'jobapply.JPId')->leftJoin('screening', 'screening.JAId', '=', 'jobapply.JAId')->leftJoin('master_resumesource', 'master_resumesource.ResumeSouId', '=', 'jobapply.ResumeSource')->leftJoin('users', 'users.id', '=', 'jobapply.SelectedBy')->where('jobapply.FwdTechScr', 'Yes')->orderBy('jobapply.JAId', 'desc');
        return datatables()->of($mrf)->addIndexColumn()->editColumn('Name', function ($mrf) {
            return $mrf->FName . ' ' . $mrf->MName . ' ' . $mrf->LName;
        })->editColumn('Department', function ($mrf) {
            return getDepartmentCode($mrf->ScrDpt);
        })->editColumn('Designation', function ($mrf) {
            return getDesignationCode($mrf->DesigId);
        })->editColumn('ReSentForScreen', function ($mrf) {
            return date('d-m-Y', strtotime($mrf->ReSentForScreen));
        })->editColumn('ScreeningBy', function ($mrf) {
            return getFullName($mrf->ScreeningBy);
        })->editColumn('ResScreened', function ($mrf) {
            return date('d-m-Y', strtotime($mrf->ResScreened));
        })->make(true);
    }

    public function interview_tracker_report()
    {
        session()->put('submenu', 'interview_tracker_report');
        $company_list = DB::table("core_company")->orderBy('company_code', 'desc')->pluck("company_code", "id");
        $department_list = DB::table("core_department")->where('is_active', 'A')->orderBy('department_name', 'asc')->pluck("department_name", "id");
        $months = [1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April', 5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August', 9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'];
        return view('reports.interview_tracker_report', compact(['company_list', 'department_list', 'months']));
    }

    public function get_interview_tracker_report(Request $request)
    {
        $usersQuery = jobapply::query();
        $Company = $request->Company;
        $Department = $request->Department;
        $Year = $request->Year;
        $Month = $request->Month;
        if ($Company != '') {
            $usersQuery->where("screening.ScrCmp", $Company);
        }
        if ($Department != '') {
            $usersQuery->where("screening.ScrDpt", $Department);
        }
        if ($Year != '') {
            $usersQuery->whereBetween('screening.ReSentForScreen', [$Year . '-01-01', $Year . '-12-31']);
        }
        if ($Month != '') {
            if ($Year != '') {
                $usersQuery->whereBetween('screening.ReSentForScreen', [$Year . '-' . $Month . '-01', $Year . '-' . $Month . '-31']);
            } else {
                $usersQuery->whereBetween('screening.ReSentForScreen', [date('Y') . '-' . $Month . '-01', date('Y') . '-' . $Month . '-31']);
            }
        }

        $mrf = $usersQuery->select('jobcandidates.ReferenceNo', 'jobapply.JAId', 'screening.ScrDpt', 'jobpost.JobCode', 'jobpost.DesigId', 'jobcandidates.FName', 'jobcandidates.LName', 'screening.IntervDt', 'screening.IntervLoc', 'screening.IntervStatus', 'screen2ndround.IntervDt2', 'screen2ndround.IntervLoc2', 'screen2ndround.IntervStatus2', 'screening.SelectedForC', 'screening.SelectedForD', 'intervcost.Travel', 'intervcost.Lodging', 'intervcost.Relocation', 'intervcost.Other', 'master_resumesource.ResumeSource', 'OtherResumeSource', 'users.name')->join('jobcandidates', 'jobcandidates.JCId', '=', 'jobapply.JCId')->leftJoin('jobpost', 'jobpost.JPId', '=', 'jobapply.JPId')->leftJoin('screening', 'screening.JAId', '=', 'jobapply.JAId')->leftJoin('screen2ndround', 'screen2ndround.ScId', '=', 'screening.ScId')->leftJoin('intervcost', 'intervcost.JAId', '=', 'jobapply.JAId')->leftJoin('master_resumesource', 'master_resumesource.ResumeSouId', '=', 'jobapply.ResumeSource')->leftJoin('users', 'users.id', '=', 'jobapply.SelectedBy')->where('screening.ScreenStatus', 'Shortlist')->orderBy('jobapply.JAId', 'desc');
        return datatables()->of($mrf)->addIndexColumn()->editColumn('Name', function ($mrf) {
            return $mrf->FName . ' ' . $mrf->MName . ' ' . $mrf->LName;
        })->editColumn('Department', function ($mrf) {
            return getDepartmentCode($mrf->ScrDpt);
        })->editColumn('Designation', function ($mrf) {
            return getDesignationCode($mrf->DesigId);
        })->editColumn('IntervDt', function ($mrf) {
            if ($mrf->IntervDt == '' || $mrf->IntervDt == null || $mrf->IntervDt == '0000-00-00') {
                return '';
            } else {
                return date('d-m-Y', strtotime($mrf->IntervDt));
            }
        })->editColumn('IntervDt2', function ($mrf) {
            if ($mrf->IntervDt2 == '' || $mrf->IntervDt2 == null || $mrf->IntervDt2 == '0000-00-00') {
                return '';
            } else {
                return date('d-m-Y', strtotime($mrf->IntervDt2));
            }
        })->editColumn('SelectedForC', function ($mrf) {
            return getcompany_code($mrf->SelectedForC);
        })->editColumn('SelectedForD', function ($mrf) {
            return getDepartmentCode($mrf->SelectedForD);
        })->make(true);
    }

    public function job_offer_report()
    {
        session()->put('submenu', 'job_offer_report');
        $company_list = DB::table("core_company")->orderBy('company_code', 'desc')->pluck("company_code", "id");
        $department_list = DB::table("core_department")->where('is_active', 'A')->orderBy('department_name', 'asc')->pluck("department_name", "id");
        $months = [1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April', 5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August', 9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'];
        return view('reports.job_offer_report', compact(['company_list', 'department_list', 'months']));
    }

    public function get_job_offer_report(Request $request)
    {
        $usersQuery = OfferLetter::query();
        $Company = $request->Company;
        $Department = $request->Department;
        $Year = $request->Year;
        $Month = $request->Month;
        if ($Company != '') {
            $usersQuery->where("offerletterbasic.Company", $Company);
        }
        if ($Department != '') {
            $usersQuery->where("offerletterbasic.Department", $Department);
        }
        if ($Year != '') {
            $usersQuery->whereBetween('offerletterbasic.LtrDate', [$Year . '-01-01', $Year . '-12-31']);
        }
        if ($Month != '') {
            if ($Year != '') {
                $usersQuery->whereBetween('offerletterbasic.LtrDate', [$Year . '-' . $Month . '-01', $Year . '-' . $Month . '-31']);
            } else {
                $usersQuery->whereBetween('offerletterbasic.LtrDate', [date('Y') . '-' . $Month . '-01', date('Y') . '-' . $Month . '-31']);
            }
        }

        $mrf = $usersQuery->select(
            'offerletterbasic.Company',
            'offerletterbasic.Department',
            'offerletterbasic.Designation',
            'jobpost.JobCode',
            'jobcandidates.ReferenceNo',
            'jobcandidates.FName',
            'jobcandidates.MName',
            'jobcandidates.LName',
            'offerletter_review.CreatedTime as review_date',
            'offerletter_review.EmpId',
            'offerletter_review.Status as review_status',
            'candjoining.LinkValidityStart as of_sent_dt',
            'offerletterbasic.Answer',
            'offerletterbasic.RejReason',
            'candjoining.JoinOnDt',
            'master_resumesource.ResumeSource',
            'OtherResumeSource',
            'users.name',
            'core_region.region_name as RegionName',
            'core_zone.zone_name as ZoneName',
            'core_vertical.vertical_name as VerticalName'
        )
            ->join('jobapply', 'jobapply.JAId', '=', 'offerletterbasic.JAId')
            ->join('jobcandidates', 'jobcandidates.JCId', '=', 'jobapply.JCId')
            ->leftJoin('jobpost', 'jobpost.JPId', '=', 'jobapply.JPId')
            ->leftJoin('offerletter_review', 'offerletter_review.JAId', '=', 'offerletterbasic.JAId')
            ->leftJoin('candjoining', 'candjoining.JAId', '=', 'offerletterbasic.JAId')
            ->leftJoin('master_resumesource', 'master_resumesource.ResumeSouId', '=', 'jobapply.ResumeSource')
            ->leftJoin('users', 'users.id', '=', 'jobapply.SelectedBy')
            ->leftJoin('core_region', 'core_region.id', '=', 'offerletterbasic.Region')
            ->leftJoin('core_zone', 'core_zone.id', '=', 'offerletterbasic.Zone')
            ->leftJoin('core_vertical', 'core_vertical.id', '=', 'offerletterbasic.VerticalId')
            ->where('LtrNo', '!=', null)
            ->groupBy('offerletterbasic.JAId')
            ->orderBy('offerletterbasic.JAId', 'desc');
        return datatables()->of($mrf)->addIndexColumn()->editColumn('Company', function ($mrf) {
            return getcompany_code($mrf->Company);
        })->editColumn('Department', function ($mrf) {
            return getDepartmentCode($mrf->Department);
        })->editColumn('Name', function ($mrf) {
            return $mrf->FName . ' ' . $mrf->MName . ' ' . $mrf->LName;
        })->editColumn('Designation', function ($mrf) {
            return getDesignationCode($mrf->Designation);
        })->editColumn('review_date', function ($mrf) {
            if ($mrf->review_date == '' || $mrf->review_date == null || $mrf->review_date == '0000-00-00') {
                return '';
            } else {
                return date('d-m-Y', strtotime($mrf->review_date));
            }
        })->editColumn('of_sent_dt', function ($mrf) {
            if ($mrf->of_sent_dt == '' || $mrf->of_sent_dt == null || $mrf->of_sent_dt == '0000-00-00') {
                return '';
            } else {
                return date('d-m-Y', strtotime($mrf->of_sent_dt));
            }
        })->editColumn('JoinOnDt', function ($mrf) {
            if ($mrf->JoinOnDt == '' || $mrf->JoinOnDt == null || $mrf->JoinOnDt == '0000-00-00') {
                return '';
            } else {
                return date('d-m-Y', strtotime($mrf->JoinOnDt));
            }
        })->editColumn('review_by', function ($mrf) {
            return getFullName($mrf->EmpId);
        })->editColumn('SelectedForD', function ($mrf) {
            return getDepartmentCode($mrf->SelectedForD);
        })
            ->editColumn('review_status', function ($mrf) {
                if ($mrf->review_status == 'Accepted') {
                    return 'Approved';
                } elseif ($mrf->review_status == 'Rejected') {
                    return 'Rejected';
                } else {
                    return 'Pending';
                }
            })
            ->editColumn('Answer', function ($mrf) {
                if ($mrf->Answer == 'Accepted') {
                    return 'Accepted';
                } elseif ($mrf->Answer == 'Rejected') {
                    return 'Rejected';
                } else {
                    return 'Pending';
                }
            })
            ->make(true);
    }

    public function candidate_joining_report()
    {
        session()->put('submenu', 'candidate_joining_report');
        $candidate_list = DB::table('candjoining')->select('jobcandidates.ReferenceNo', 'jobcandidates.FName', 'jobcandidates.LName', 'offerletterbasic.Company', 'offerletterbasic.Department', 'offerletterbasic.Designation', 'jobpost.JobCode', 'candjoining.Joined', 'candjoining.JoinOnDt', 'jobcandidates.FinalSubmit', 'candjoining.Verification', 'candjoining.ForwardToESS')->join('jobapply', 'jobapply.JAId', '=', 'candjoining.JAId')->join('jobcandidates', 'jobcandidates.JCId', '=', 'jobapply.JCId')->join('offerletterbasic', 'offerletterbasic.JAId', '=', 'candjoining.JAId')->join('jobpost', 'jobpost.JPId', '=', 'jobapply.JPId')->get();
        $company_list = DB::table("core_company")->orderBy('company_code', 'desc')->pluck("company_code", "id");
        $department_list = DB::table("core_department")->where('is_active', 'A')->orderBy('department_name', 'asc')->pluck("department_name", "id");
        $months = [1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April', 5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August', 9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'];
        return view('reports.candidate_joining_report', compact(['company_list', 'department_list', 'months']));
    }

    public function get_candidate_joining_report(Request $request)
    {
        $usersQuery = CandidateJoining::query();
        $Company = $request->Company;
        $Department = $request->Department;
        $Year = $request->Year;
        $Month = $request->Month;
        if ($Company != '') {
            $usersQuery->where("offerletterbasic.Company", $Company);
        }
        if ($Department != '') {
            $usersQuery->where("offerletterbasic.Department", $Department);
        }
        if ($Year != '') {
            $usersQuery->whereBetween('candjoining.JoinOnDt', [$Year . '-01-01', $Year . '-12-31']);
        }
        if ($Month != '') {
            if ($Year != '') {
                $usersQuery->whereBetween('candjoining.JoinOnDt', [$Year . '-' . $Month . '-01', $Year . '-' . $Month . '-31']);
            } else {
                $usersQuery->whereBetween('candjoining.JoinOnDt', [date('Y') . '-' . $Month . '-01', date('Y') . '-' . $Month . '-31']);
            }
        }

        $mrf = $usersQuery->select(
            'jobcandidates.ReferenceNo',
            'jobcandidates.FName',
            'jobcandidates.MName',
            'jobcandidates.LName',
            'offerletterbasic.Company',
            'offerletterbasic.Department',
            'offerletterbasic.Designation',
            'offerletterbasic.ServiceBond',
            'offerletterbasic.ServiceBondYears',
            'jobapply.ResumeSource',
            'jobapply.OtherResumeSource',
            'jobpost.JobCode',
            'candjoining.Joined',
            'candjoining.JoinOnDt',
            'jobcandidates.FinalSubmit',
            'candjoining.Verification',
            'candjoining.ForwardToESS',
            'master_resumesource.ResumeSource as r1',
            '   .name',
            'master_region.RegionName',
            'master_zone.ZoneName',
            'master_vertical.VerticalName'
        )->join('jobapply', 'jobapply.JAId', '=', 'candjoining.JAId')
            ->join('jobcandidates', 'jobcandidates.JCId', '=', 'jobapply.JCId')
            ->join('offerletterbasic', 'offerletterbasic.JAId', '=', 'candjoining.JAId')
            ->join('jobpost', 'jobpost.JPId', '=', 'jobapply.JPId')
            ->leftJoin('master_resumesource', 'master_resumesource.ResumeSouId', '=', 'jobapply.ResumeSource')
            ->leftJoin('users', 'users.id', '=', 'jobapply.SelectedBy')
            ->leftJoin('master_region', 'master_region.RegionId', '=', 'offerletterbasic.Region')
            ->leftJoin('master_zone', 'master_zone.ZoneId', '=', 'offerletterbasic.Zone')
            ->leftJoin('master_vertical', 'master_vertical.Id', '=', 'offerletterbasic.VerticalId')
            ->where('offerletterbasic.Answer', 'Accepted')->orderby('candjoining.JoinOnDt', 'desc');
        return datatables()->of($mrf)->addIndexColumn()->editColumn('Company', function ($mrf) {
            return getcompany_code($mrf->Company);
        })->editColumn('Department', function ($mrf) {
            return getDepartmentCode($mrf->Department);
        })->editColumn('Name', function ($mrf) {
            return $mrf->FName . ' ' . $mrf->MName . ' ' . $mrf->LName;
        })->editColumn('Designation', function ($mrf) {
            return getDesignationCode($mrf->Designation);
        })->editColumn('JoinOnDt', function ($mrf) {
            if ($mrf->JoinOnDt == '' || $mrf->JoinOnDt == null || $mrf->JoinOnDt == '0000-00-00') {
                return '';
            } else {
                return date('d-m-Y', strtotime($mrf->JoinOnDt));
            }
        })->editColumn('FinalSubmit', function ($mrf) {
            if ($mrf->FinalSubmit == '1') {
                return 'Yes';
            } else {
                return 'No';
            }
        })->editColumn('Verification', function ($mrf) {
            if ($mrf->Verification == 'Verified') {
                return 'Yes';
            } else {
                return 'No';
            }
        })->editColumn('hiring_from', function ($mrf) {
            if ($mrf->ResumeSource == '7') {
                return 'Campus';
            } else {
                return 'Non Campus';
            }
        })->make(true);
    }

    public function getActiveMRFWiesData(Request $request)
    {
        $MRFId = $request->MRFId;
        $result = [];
        $total_applicant = jobapply::where('manpowerrequisition.MRFId', $MRFId)->join('jobpost', 'jobpost.JPId', '=', 'jobapply.JPId')->join('manpowerrequisition', 'manpowerrequisition.MRFId', '=', 'jobpost.MRFId')->count();
        $result['Applications'] = $total_applicant;
        $hr_screening = jobapply::where('manpowerrequisition.MRFId', $MRFId)->where('jobapply.Status', 'Selected')->join('jobpost', 'jobpost.JPId', '=', 'jobapply.JPId')->join('manpowerrequisition', 'manpowerrequisition.MRFId', '=', 'jobpost.MRFId')->count();
        $result['HR Screening'] = $hr_screening;
        $technical_screening = jobapply::where('manpowerrequisition.MRFId', $MRFId)->where('screening.ScreenStatus', 'Shortlist')->join('jobpost', 'jobpost.JPId', '=', 'jobapply.JPId')->join('screening', 'screening.JAId', '=', 'jobapply.JAId')->join('manpowerrequisition', 'manpowerrequisition.MRFId', '=', 'jobpost.MRFId')->count();
        $result['Technical Screening'] = $technical_screening;

        $first_interview = jobapply::where('manpowerrequisition.MRFId', $MRFId)->where('screening.IntervStatus', 'Selected')->join('jobpost', 'jobpost.JPId', '=', 'jobapply.JPId')->join('screening', 'screening.JAId', '=', 'jobapply.JAId')->join('manpowerrequisition', 'manpowerrequisition.MRFId', '=', 'jobpost.MRFId')->count();
        $result['1st Interview'] = $first_interview;

        $second_interview = jobapply::where('manpowerrequisition.MRFId', $MRFId)->where('screening.IntervStatus', '2nd Round Interview')->where('screen2ndround.IntervStatus2', 'Selected')->join('jobpost', 'jobpost.JPId', '=', 'jobapply.JPId')->join('screening', 'screening.JAId', '=', 'jobapply.JAId')->join('screen2ndround', 'screen2ndround.ScId', '=', 'screening.ScId')->join('manpowerrequisition', 'manpowerrequisition.MRFId', '=', 'jobpost.MRFId')->count();
        $result['2nd Interview'] = $second_interview;

        $offer = jobapply::where('manpowerrequisition.MRFId', $MRFId)->where('offerletterbasic.OfferLetterSent', 'Yes')->join('jobpost', 'jobpost.JPId', '=', 'jobapply.JPId')->join('offerletterbasic', 'offerletterbasic.JAId', '=', 'jobapply.JAId')->join('manpowerrequisition', 'manpowerrequisition.MRFId', '=', 'jobpost.MRFId')->count();
        $result['Offer'] = $offer;

        $Joined = jobapply::where('manpowerrequisition.MRFId', $MRFId)->where('candjoining.Joined', 'Yes')->join('jobpost', 'jobpost.JPId', '=', 'jobapply.JPId')->join('candjoining', 'candjoining.JAId', '=', 'jobapply.JAId')->join('manpowerrequisition', 'manpowerrequisition.MRFId', '=', 'jobpost.MRFId')->count();
        $result['Joined'] = $Joined;
        $final = array();
        foreach ($result as $key => $value) {
            if ($value != 0) {
                $final[] = ['label' => $key, 'y' => $value];
            }
        }
        return $final;
    }

    public function mrf_status_open_days(Request $request)
    {
        $UserId = $request->UserId;
        $mrf_open_days = DB::table('jobpost')->select('JobCode', DB::raw("DATEDIFF(CURRENT_DATE(), CreatedTime) as date_difference"))->where('Status', '=', 'Open')/*   ->where('JobPostType', '=', 'Regular') */->where('CreatedBy', '=', $UserId)->get()->toArray();
        $dataPoints1 = array();
        foreach ($mrf_open_days as $key => $value) {
            $dataPoints1[] = ['label' => $value->JobCode, 'y' => $value->date_difference];
        }

        return $dataPoints1;
    }

    public function mrf_report()
    {
        $company_list = DB::table("core_company")->orderBy('company_code', 'desc')->pluck("company_code", "id");
        $months = [1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April', 5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August', 9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'];
        $recruiters = User::whereRole('R')->where('Status', 'A')->get();
        return view('reports.mrf_status_report', compact('company_list', 'months', 'recruiters'));
    }

    function get_mrf_report_data(Request $request)
    {
        // Initialize query builder
        $usersQuery = DB::table('manpowerrequisition as mrf')
            ->selectRaw('ROW_NUMBER() OVER (ORDER BY mrf.MRFId) AS `S.No`')
            ->select([
                'ja.Department',
                'mrf.JobCode',
                'md.department_name',
                'mrf.Type',
                'mrf.Positions',
                DB::raw('COUNT(ja.JAId) AS `Total`'),
                DB::raw('SUM(CASE WHEN ja.Status IS NOT NULL THEN 1 ELSE 0 END) AS `HR_Screening`'),
                DB::raw('SUM(CASE WHEN ja.Status = "Selected" THEN 1 ELSE 0 END) AS `HR_FWD`'),
                DB::raw('SUM(CASE WHEN ja.FwdTechScr = "Yes" THEN 1 ELSE 0 END) AS `Technical_Screening`'),
                DB::raw('SUM(CASE WHEN s.InterAtt = "Yes" THEN 1 ELSE 0 END) AS `Interviewed`'),
                DB::raw('SUM(CASE WHEN s.IntervStatus = "Selected" OR sr.IntervStatus2 = "Selected" THEN 1 ELSE 0 END) AS `Selected`'),
                DB::raw('SUM(CASE WHEN ob.OfferLetterSent = "Yes" THEN 1 ELSE 0 END) AS `Offered`'),
                DB::raw('SUM(CASE WHEN ob.Answer = "Accepted" THEN 1 ELSE 0 END) AS `Accepted`'),
                DB::raw('SUM(CASE WHEN ob.Answer = "Rejected" THEN 1 ELSE 0 END) AS `Rejected`'),
                DB::raw('SUM(CASE WHEN cd.Joined = "Yes" THEN 1 ELSE 0 END) AS `Joined`'),
                DB::raw('SUM(CASE WHEN ob.Answer = "Accepted" AND cd.Joined IS NULL THEN 1 ELSE 0 END) AS Yet_to_Joined')
            ])
            ->join('core_department as md', 'md.id', '=', 'mrf.DepartmentId')
            ->leftJoin('jobpost AS jp', 'jp.MRFId', '=', 'mrf.MRFId')
            ->leftJoin('jobapply AS ja', 'ja.JPId', '=', 'jp.JPId')
            ->leftJoin('screening AS s', 's.JAId', '=', 'ja.JAId')
            ->leftJoin('screen2ndround AS sr', 'sr.ScId', '=', 's.ScId')
            ->leftJoin('offerletterbasic AS ob', 'ob.JAId', '=', 'ja.JAId')
            ->leftJoin('candjoining AS cd', 'cd.JAId', '=', 'ja.JAId')
            ->where('mrf.Type', '!=', 'Campus_HrManual')
            ->groupBy('mrf.MRFId', 'ja.Department', 'mrf.JobCode', 'md.department_name', 'mrf.Type', 'mrf.Positions');
    
        // Apply filters
        if ($request->filled('Company')) {
            $usersQuery->where('mrf.CompanyId', $request->Company);
        }
    
        if ($request->filled('Department')) {
            $usersQuery->where('mrf.DepartmentId', $request->Department);
        }
    
        if ($request->filled('Year')) {
            $usersQuery->whereBetween('mrf.CreatedTime', [$request->Year . '-01-01 00:00:00', $request->Year . '-12-31 23:59:59']);
        }else{
            $usersQuery->whereBetween('mrf.CreatedTime', [date('Y'). '-01-01 00:00:00', date('Y'). '-12-31 23:59:59']);
        }
    
        if ($request->filled('Month')) {
            $year = $request->filled('Year') ? $request->Year : date('Y');
            $month = str_pad($request->Month, 2, '0', STR_PAD_LEFT);
            $lastDay = date('t', strtotime("$year-$month-01"));
            $usersQuery->whereBetween('mrf.CreatedTime', ["$year-$month-01 00:00:00", "$year-$month-$lastDay 23:59:59"]);
        }
    
        if ($request->filled('Status')) {
            $usersQuery->where('mrf.Status', $request->Status);
        }
    
        if ($request->filled('Recruiter')) {
            $usersQuery->where('jp.CreatedBy', $request->Recruiter);
        }
    
        // Cache the query results for 10 minutes
        $mrf = $usersQuery->get();
    
        return datatables()->of($mrf)
            ->addIndexColumn()
            ->editColumn('Type', function ($row) {
                $typeMap = [
                    'N' => 'New MRF',
                    'N_HrManual' => 'New MRF',
                    'R' => 'Replacement MRF',
                    'R_HrManual' => 'Replacement MRF',
                    'S' => 'SIP MRF',
                    'SIP_HrManual' => 'SIP MRF',
                ];
                return $typeMap[$row->Type] ?? 'Campus MRF';
            })
            ->make(true);
    }

    public function recruiter_report()
    {
        $recruiters = User::whereRole('R')->get();
        return view('reports.recruiter_report', compact('recruiters'));
    }

    function get_recruiter_wise_data(Request $request)
    {
        $from_date = $request->from_date;
        $to_date = $request->to_date;
        $recruiter = $request->recruiter;
        $screening = master_user::query();
        $suitable = master_user::query();
        if ($from_date != '' && $to_date != '') {
            $screening->whereBetween('jobapply.HrScreeningDate', [$from_date, $to_date]);
            $suitable->whereBetween('jobcandidates.Suitable_Chk_Date', [$from_date, $to_date]);
        }
        if ($recruiter != '') {
            $screening->where('jobapply.SelectedBy', $recruiter);
            $suitable->where('jobcandidates.Suitable_Chk_By', $recruiter);
        }
        $screening_data = $screening->select('users.name as recruiter', DB::raw("COALESCE(COUNT('jobapply.SelectedBy'), 0) as total_screening"))->leftJoin('jobapply', 'users.id', 'jobapply.SelectedBy')->groupBy('users.id')->where('users.role', 'R')->get()->toArray();

        $suitable_data = $suitable->select('users.name as recruiter', DB::raw('COALESCE(COUNT(jobcandidates.Suitable_For), 0) as total_suitable'))->leftJoin('jobcandidates', 'users.id', '=', 'jobcandidates.Suitable_Chk_By')->where('users.role', 'R')->groupBy('users.id', 'users.name')->get()->toArray();

        $result = [];
        foreach ($screening_data as $screeningItem) {
            $recruiter = $screeningItem['recruiter'];
            $result[$recruiter] = ['recruiter' => $recruiter, 'total_screening' => (int)$screeningItem['total_screening'], 'total_suitable' => 0,];
        }

        foreach ($suitable_data as $suitableItem) {
            $recruiter = $suitableItem['recruiter'];
            if (isset($result[$recruiter])) {
                $result[$recruiter]['total_suitable'] = (int)$suitableItem['total_suitable'];
            } else {
                $result[$recruiter] = ['recruiter' => $recruiter, 'total_screening' => 0, 'total_suitable' => (int)$suitableItem['total_suitable'],];
            }
        }

        // Convert the result into a numeric array
        $result = array_values($result);
        return datatables()->of($result)->addIndexColumn()->make(true);
    }

    public function manual_entry_report()
    {
        $user_list = DB::table('jobcandidates')->select('manual_entry_by_name as user', DB::raw('COUNT(manual_entry_by_name) as total', 'manual_entry_by'))->whereNotNull('manual_entry_by_name')->groupBy('manual_entry_by_name')->get();

        return view('reports.manual_entry_report', compact('user_list'));
    }

    public function candidate_wise_trainee_report()
    {
        session()->put('submenu', 'candidate_wise_trainee_report');
        $college_list = DB::table("master_institute")->where('Status', 'A')->orderBy('InstituteName', 'asc')->pluck("InstituteName", "InstituteId");
        $company_list = DB::table("core_company")->orderBy('company_code', 'desc')->pluck("company_code", "id");
        return view('reports.candidate_wise_trainee_report', compact(['college_list', 'company_list']));
    }

    function get_candidate_wise_trainee_data(Request $request)
    {
        $userQuery = trainee_apply::query();
        $College = $request->College;
        $ScreenStatus = $request->ScreenStatus;
        $IntervStatus = $request->IntervStatus;
        $Year = $request->Year;
        $Company = $request->Company;
        $Department = $request->Department;
        if ($College != '') {
            $userQuery->where('jobcandidates.College', $College);
        }
        if ($ScreenStatus != '') {
            if ($ScreenStatus == 'Pending') {
                $userQuery->whereNull('trainee_apply.ScreenStatus');
            } else {
                $userQuery->where('trainee_apply.ScreenStatus', $ScreenStatus);
            }
        }

        if ($IntervStatus != '') {
            if ($IntervStatus == 'Pending') {
                $userQuery->whereNull('trainee_apply.IntervStatus');
            } else {
                $userQuery->where('trainee_apply.IntervStatus', $IntervStatus);
            }
        }

        if ($Year != '') {
            $userQuery->whereYear('trainee_apply.ApplyDate', $Year);
        }

        if ($Company != '') {
            $userQuery->where('trainee_apply.Company', $Company);
        }
        if ($Department != '') {
            $userQuery->where('trainee_apply.Department', $Department);
        }

        $data = $userQuery->select(
            'jobcandidates.ReferenceNo',
            'jobcandidates.Title',
            'jobcandidates.FName',
            'jobcandidates.MName',
            'jobcandidates.LName',
            'jobcandidates.Gender',
            'master_education.EducationCode',
            'master_specialization.Specialization',
            'master_institute.InstituteName',
            'trainee_apply.ScreenStatus',
            'IntervStatus',
            'Candidate_Joined',
            'DOc',
            'Doj',
            'ApplyDate'
        )->join('jobcandidates', 'jobcandidates.JCId', '=', 'trainee_apply.JCId')->leftJoin('master_education', 'master_education.EducationId', '=', 'jobcandidates.Education')->leftJoin('master_specialization', 'master_specialization.SpId', '=', 'jobcandidates.Specialization')->leftJoin('master_institute', 'master_institute.InstituteId', '=', 'jobcandidates.College');
        return datatables()->of($data)->addIndexColumn()->addColumn('Name', function ($data) {
            return $data->FName . " " . $data->MName . " " . $data->LName;
        })->editColumn('Gender', function ($data) {
            return $data->Gender == 'M' ? 'Male' : 'Female';
        })->editColumn('Education', function ($data) {
            return $data->EducationCode . " - " . $data->Specialization;
        })->addColumn('College', function ($data) {
            return $data->InstituteName;
        })->addColumn('Joined', function ($data) {
            if ($data->Candidate_Joined != null) {
                return $data->Candidate_Joined == 'Y' ? 'Yes' : 'No';
            } else {
                return "";
            }
        })
            ->editColumn('ApplyDate', function ($data) {
                return date('d-M-Y', strtotime($data->ApplyDate));
            })
            ->make(true);
    }

    public function college_wise_trainee_report()
    {
        session()->put('submenu', 'college_wise_trainee_report');
        $college_list = DB::table("master_institute")->where('Status', 'A')->orderBy('InstituteName', 'asc')->pluck("InstituteName", "InstituteId");
        $company_list = DB::table("core_company")->orderBy('company_code', 'desc')->pluck("company_code", "id");
        return view('reports.college_wise_trainee_report', compact(['college_list', 'company_list']));
    }

    public function get_college_wise_trainee_data(Request $request)
    {
        $userQuery = trainee_apply::query();
        $College = $request->College;
        $ScreenStatus = $request->ScreenStatus;
        $IntervStatus = $request->IntervStatus;
        $Year = $request->Year;
        $Company = $request->Company;
        $Department = $request->Department;
        if ($College != '') {
            $userQuery->where('jobcandidates.College', $College);
        }

        if ($Year != '') {
            $userQuery->whereYear('trainee_apply.ApplyDate', $Year);
        }

        if ($Company != '') {
            $userQuery->where('trainee_apply.Company', $Company);
        }
        if ($Department != '') {
            $userQuery->where('trainee_apply.Department', $Department);
        }
        $data = $userQuery->select('jobcandidates.College', 'master_institute.InstituteName', DB::raw('COUNT(trainee_apply.TId) as total_applicants'), DB::raw('SUM(CASE WHEN trainee_apply.ScreenStatus = "Shortlist" THEN 1 ELSE 0 END) as screening_shortlist'), DB::raw('SUM(CASE WHEN trainee_apply.ScreenStatus = "Reject" THEN 1 ELSE 0 END) as screening_reject'), DB::raw('SUM(CASE WHEN trainee_apply.ScreenStatus IS NULL THEN 1 ELSE 0 END) as screening_pending'), DB::raw('SUM(CASE WHEN trainee_apply.IntervStatus = "Selected" THEN 1 ELSE 0 END) as interview_selected'), DB::raw('SUM(CASE WHEN trainee_apply.IntervStatus = "Rejected" THEN 1 ELSE 0 END) as interview_rejected'), DB::raw('SUM(CASE WHEN (trainee_apply.IntervStatus IS NULL AND trainee_apply.ScreenStatus ="Shortlist") THEN 1 ELSE 0 END) as interview_pending'), DB::raw('SUM(CASE WHEN trainee_apply.Candidate_Joined = "Yes" OR trainee_apply.Doj IS NOT NULL THEN 1 ELSE 0 END) as total_joined_candidates'))->join('jobcandidates', 'jobcandidates.JCId', '=', 'trainee_apply.JCId')->leftJoin('master_institute', 'master_institute.InstituteId', '=', 'jobcandidates.College')->groupBy('jobcandidates.College');
        return datatables()->of($data)->addIndexColumn()->addColumn('College', function ($data) {
            return $data->InstituteName;
        })->addColumn('Joined', function ($data) {
            if ($data->Candidate_Joined != null) {
                return $data->Candidate_Joined == 'Y' ? 'Yes' : 'No';
            } else {
                return "";
            }
        })->make(true);
    }

    public function active_trainee_report()
    {
        session()->put('submenu', 'active_trainee_report');
        $college_list = DB::table("master_institute")->where('Status', 'A')->orderBy('InstituteName', 'asc')->pluck("InstituteName", "InstituteId");
        $company_list = DB::table("core_company")->orderBy('company_code', 'desc')->pluck("company_code", "id");
        return view('reports.active_trainee_report', compact(['college_list', 'company_list']));
    }

    public function get_active_trainee_data(Request $request)
    {
        $userQuery = trainee_apply::query();
        $College = $request->College;
        $Year = $request->Year;
        $Company = $request->Company;
        $Department = $request->Department;
        if ($College != '') {
            $userQuery->where('jc.College', $College);
        }
        if ($Year != '') {
            $userQuery->whereYear('trainee_apply.Doj', $Year);
        }

        if ($Company != '') {
            $userQuery->where('trainee_apply.Company', $Company);
        }
        if ($Department != '') {
            $userQuery->where('trainee_apply.Department', $Department);
        }
        $data = $userQuery->select(
            'trainee_apply.TId',
            'jc.ReferenceNo',
            'jc.Title',
            'jc.FName',
            'jc.MName',
            'jc.LName',
            'jc.Gender',
            'me.EducationCode',
            'ms.Specialization',
            'mi.InstituteName',
            'trainee_apply.ScreenStatus',
            'IntervStatus',
            'Candidate_Joined',
            'DOc',
            'Doj',
            'HQ_State',
            'HQ_City',
            'Reporting_Manager',
            'md.department_name',
            'trainee_apply.Stipend as Stipend_per_month',
            'trainee_apply.OtherBenefit'
        )
            ->join('jobcandidates as jc', 'jc.JCId', '=', 'trainee_apply.JCId')
            ->leftJoin('master_education as me', 'me.EducationId', '=', 'jc.Education')
            ->leftJoin('master_specialization as ms', 'ms.SpId', '=', 'jc.Specialization')
            ->leftJoin('master_institute as mi', 'mi.InstituteId', '=', 'jc.College')
            ->leftJoin('core_department as md', 'md.id', '=', 'trainee_apply.Department')
            ->leftJoin('trainee_stipend as ts', 'trainee_apply.Tid', '=', 'ts.TId')
            ->where(function ($query) {
                $query->whereNotNull('trainee_apply.Doj')
                    ->orWhere('trainee_apply.Candidate_Joined', 'Y');
            })
            ->groupBy('trainee_apply.TId')
            ->selectRaw('MAX(CASE WHEN ts.Month = ? THEN ts.Stipend END) AS January_Stipend', ['01'])
            ->selectRaw('MAX(CASE WHEN ts.Month = ? THEN ts.Expense END) AS January_Expense', ['01'])
            ->selectRaw('MAX(CASE WHEN ts.Month = ? THEN ts.Stipend END) AS February_Stipend', ['02'])
            ->selectRaw('MAX(CASE WHEN ts.Month = ? THEN ts.Expense END) AS February_Expense', ['02'])
            ->selectRaw('MAX(CASE WHEN ts.Month = ? THEN ts.Stipend END) AS March_Stipend', ['03'])
            ->selectRaw('MAX(CASE WHEN ts.Month = ? THEN ts.Expense END) AS March_Expense', ['03'])
            ->selectRaw('MAX(CASE WHEN ts.Month = ? THEN ts.Stipend END) AS April_Stipend', ['04'])
            ->selectRaw('MAX(CASE WHEN ts.Month = ? THEN ts.Expense END) AS April_Expense', ['04'])
            ->selectRaw('MAX(CASE WHEN ts.Month = ? THEN ts.Stipend END) AS May_Stipend', ['05'])
            ->selectRaw('MAX(CASE WHEN ts.Month = ? THEN ts.Expense END) AS May_Expense', ['05'])
            ->selectRaw('MAX(CASE WHEN ts.Month = ? THEN ts.Stipend END) AS June_Stipend', ['06'])
            ->selectRaw('MAX(CASE WHEN ts.Month = ? THEN ts.Expense END) AS June_Expense', ['06'])
            ->selectRaw('MAX(CASE WHEN ts.Month = ? THEN ts.Stipend END) AS July_Stipend', ['07'])
            ->selectRaw('MAX(CASE WHEN ts.Month = ? THEN ts.Expense END) AS July_Expense', ['07'])
            ->selectRaw('MAX(CASE WHEN ts.Month = ? THEN ts.Stipend END) AS August_Stipend', ['08'])
            ->selectRaw('MAX(CASE WHEN ts.Month = ? THEN ts.Expense END) AS August_Expense', ['08'])
            ->selectRaw('MAX(CASE WHEN ts.Month = ? THEN ts.Stipend END) AS September_Stipend', ['09'])
            ->selectRaw('MAX(CASE WHEN ts.Month = ? THEN ts.Expense END) AS September_Expense', ['09'])
            ->selectRaw('MAX(CASE WHEN ts.Month = ? THEN ts.Stipend END) AS October_Stipend', ['10'])
            ->selectRaw('MAX(CASE WHEN ts.Month = ? THEN ts.Expense END) AS October_Expense', ['10'])
            ->selectRaw('MAX(CASE WHEN ts.Month = ? THEN ts.Stipend END) AS November_Stipend', ['11'])
            ->selectRaw('MAX(CASE WHEN ts.Month = ? THEN ts.Expense END) AS November_Expense', ['11'])
            ->selectRaw('MAX(CASE WHEN ts.Month = ? THEN ts.Stipend END) AS December_Stipend', ['12'])
            ->selectRaw('MAX(CASE WHEN ts.Month = ? THEN ts.Expense END) AS December_Expense', ['12'])
            ->get();
        return datatables()->of($data)->addIndexColumn()
            ->addColumn('Name', function ($data) {
                return $data->FName . " " . $data->MName . " " . $data->LName;
            })->editColumn('Gender', function ($data) {
                return $data->Gender == 'M' ? 'Male' : 'Female';
            })->editColumn('Education', function ($data) {
                return $data->EducationCode . " - " . $data->Specialization;
            })->addColumn('College', function ($data) {
                return $data->InstituteName;
            })
            ->editColumn('Doj', function ($data) {
                return date('d-m-Y', strtotime($data->Doj));
            })
            ->make(true);
    }

    public function all_candidate_report()
    {

        return Excel::download(new AllCandidateData, 'candiate.xlsx');
    }

    public function mrf_tat()
    {
        $active_mrf = master_mrf::where('Status', 'Approved')->orderBy('JobCode')->pluck('JobCode', 'MRFId');
        return view("reports.mrf_tat", compact('active_mrf'));
    }

    public function get_mrf_tat_data(Request $request)
    {
        $file_name = 'mrf_detail_' . $request->MRFId . '.xlsx';
        try {
            Excel::store(new MRFDetail($request->MRFId), $file_name, 'public');
            $urlPath = url('') . '/assets/temp/' . $file_name;
            return response()->json([
                'file' => $urlPath, // Provide the public URL of the file
                'status' => 200
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while generating the file: ' . $e->getMessage()]);
        }
    }

    public function job_response_data_download(Request $request)
    {
        $JPId = $request->JPId;
        return Excel::download(new JobResponseCandidates($JPId), 'Candidate_Report.xlsx');
    }
}
