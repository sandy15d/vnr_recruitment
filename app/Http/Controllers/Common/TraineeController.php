<?php

namespace App\Http\Controllers\Common;

use App\Exports\TraineeStipendImport;
use App\Imports\ApplicationImport;
use App\Mail\InterviewMail;
use App\Mail\InterviewMailOnline;
use App\Mail\TraineeFiroBMail;
use DateTime;
use App\Models\jobpost;
use App\Models\master_mrf;
use App\Models\jobcandidate;
use Illuminate\Http\Request;
use App\Models\trainee_apply;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Helpers\CandidateActivityLog;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;


class TraineeController extends Controller
{
    public function trainee_mrf_allocated()
    {
        $company_list = DB::table("core_company")->orderBy('company_code', 'desc')->pluck("company_code", "id");
        $department_list = DB::table("master_department")->where('DeptStatus', 'A')->orderBy('DepartmentName', 'asc')->pluck("DepartmentName", "DepartmentId");
        $state_list = DB::table("states")->orderBy('StateName', 'asc')->pluck("StateName", "StateId");
        $institute_list = DB::table("master_institute")->orderBy('InstituteName', 'asc')->pluck("InstituteName", "InstituteId");
        $designation_list = DB::table("master_designation")->where('DesigName', '!=', '')->orderBy('DesigName', 'asc')->pluck("DesigName", "DesigId");
        $employee_list = DB::table('master_employee')->orderBy('FullName', 'ASC')->where('EmpStatus', 'A')->select('EmployeeID', DB::raw('CONCAT(Fname, " ", Lname) AS FullName'))->pluck("FullName", "EmployeeID");
        $months = [1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April', 5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August', 9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'];


        $close = DB::table('manpowerrequisition');
        if (Auth::user()->role == 'R') {

            $close->where('Allocated', Auth::user()->id);
        }
        $close->where(function ($query) {
            $query->where('Type', 'SIP')->orWhere('Type', 'SIP_HrManual');
        })->where('status', 'Close')->select('MRFId')->get();
        $CloseMRF = $close->count();


        $open = DB::table('manpowerrequisition');
        if (Auth::user()->role == 'R') {
            $open->where('Allocated', Auth::user()->id);
        }
        $open->where(function ($query1) {
            $query1->where('Type', 'SIP')->orWhere('Type', 'SIP_HrManual');
        })->where('status', '!=', 'Close')->select('MRFId')->get();
        $OpenMRF = $open->count();
        return view('recruiter.trainee_mrf_allocated ', compact('company_list', 'department_list', 'state_list', 'institute_list', 'designation_list', 'employee_list', 'months', 'CloseMRF', 'OpenMRF'));
    }

    public function getAllTraineeAllocatedMrf(Request $request)
    {

        $usersQuery = master_mrf::query();
        $Company = $request->Company;
        $Department = $request->Department;
        $Year = $request->Year;
        $Month = $request->Month;

        if (Auth::user()->role == 'R') {
            $usersQuery->where('Allocated', Auth::user()->id);
        }
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

        if ($request->MrfStatus == 'Open') {
            $usersQuery->where('manpowerrequisition.Status', '!=', 'Close');
        } else {
            $usersQuery->where('manpowerrequisition.Status', 'Close');
        }

        $mrf = $usersQuery->select('*')
            ->Join('core_department', 'manpowerrequisition.DepartmentId', '=', 'core_department.id')->where(function ($query) {
                $query->where('manpowerrequisition.Type', 'SIP')->orWhere('manpowerrequisition.Type', 'SIP_HrManual');
            });


        return datatables()->of($mrf)->addIndexColumn()->addColumn('chk', function () {
            return '<input type="checkbox" class="select_all">';
        })->editColumn('LocationIds', function ($mrf) {
            if ($mrf->LocationIds != '') {

                $location = DB::table('mrf_location_position')->where('MRFId', $mrf->MRFId)->get()->toArray();
                $loc = '';
                foreach ($location as $key => $value) {
                    $loc .= getDistrictName($value->City) . ' ';
                    $loc .= getStateCode($value->State) . ' - ';
                    $loc .= $value->Nop;
                    $loc . '<br>';
                }
                return $loc;
            } else {
                return '';
            }
        })->addColumn('JobPost', function ($mrf) {
            $check = CheckJobPostCreated($mrf->MRFId);
            if ($check == 1) {
                return 'Created';
            } else {
                return '<a  href="javascript:void(0);" data-bs-toggle="modal"
                    data-bs-target="#createpostmodal" onclick="getDetailForJobPost(' . $mrf->MRFId . ')" class="text-danger"><i class="fa fa-plus-square-o"></i>Create</a>';
            }
        })->addColumn('JobShow', function ($mrf) {
            $check = CheckJobPostCreated($mrf->MRFId);
            if ($check == 1) {
                $sql = Db::table('jobpost')->select('PostingView', 'JPId')->where('MRFId', $mrf->MRFId)->first();
                $PostView = $sql->PostingView;

                $x = '<select name="PostingView" id="postStatus' . $mrf->MRFId . '" class="form-control form-select form-select-sm  d-inline" disabled style="width: 100px;" onchange="ChngPostingView(' . $sql->JPId . ',this.value)">';

                if ($PostView == 'Show') {
                    $x .= '<option value="Show" selected>Show</option><option value="Hidden">Hidden</option>';
                } else {
                    $x .= '<option value="Show">Show</option><option value="Hidden" selected>Hidden</option>';
                }

                $x .= '</select> <i class="fa fa-pencil-square-o text-success d-inline" aria-hidden="true" id="mrfedit' . $mrf->MRFId . '" onclick="editmrf(' . $mrf->MRFId . ')" style="font-size: 16px;cursor: pointer;"></i>';
                return $x;
            } else {
                return '';
            }
        })->addColumn('details', function ($mrf) {
            $x = '';
            $x .= '<i  class="fadeIn animated lni lni-eye  text-success view" aria-hidden="true" data-id="' . $mrf->MRFId . '" id="viewMRF" title="View MRF" style="font-size: 18px;cursor: pointer;"></i> ';

            return $x;
        })->addColumn('Link', function ($mrf) {
            $JPId = GetJobPostId($mrf->MRFId);
            if ($JPId > 0) {
                $PostId = base64_encode($JPId);

                $x = '<input type="text" id="link' . $JPId . '" value="' . url('jobportal/trainee_apply_form?jpid=' . $PostId . '') . '" class="linkbox" style="width:140px;">  <button onclick="copylink(' . $JPId . ')" class="btn btn-sm btn-primary"> Copy</button>';
                return $x;
            } else {
                return 'Job Post Not Created Yet';
            }
        })->rawColumns(['chk', 'details', 'JobPost', 'JobShow', 'Link'])->make(true);
    }

    public function trainee_applications()
    {
        $company_list = DB::table("core_company")->orderBy('company_code', 'desc')->pluck("company_code", "id");
        $months = [1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April', 5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August', 9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'];
        $institute_list = DB::table("master_institute")->orderBy('InstituteName', 'asc')->pluck("InstituteName", "InstituteId");
        return view('common.trainee_applications', compact('company_list', 'months', 'institute_list'));
    }

    public function getTraineeSummary(Request $request)
    {

        $usersQuery = jobpost::query();
        $Company = $request->Company;
        $Department = $request->Department;
        $Year = $request->Year;
        $Month = $request->Month;
        $Status = $request->Status;

        if (Auth::user()->role === 'R') {
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

        if ($Status != 'All') {
            $usersQuery->where('jobpost.Status', $Status);
        }


        $data = $usersQuery->select('jobpost.JPId', 'trainee_apply.Company', 'trainee_apply.Department', 'JobCode', DB::raw('COUNT(*) AS TraineeApplied'))->Join('trainee_apply', 'jobpost.JPId', '=', 'trainee_apply.JPId')->join('jobcandidates', 'trainee_apply.JCId', '=', 'jobcandidates.JCId')->groupBy('jobpost.JPId');


        return datatables()->of($data)->addIndexColumn()->addColumn('chk', function () {
            return '<input type="checkbox" class="select_all">';
        })->editColumn('Department', function ($data) {
            return getDepartment($data->Department);
        })->editColumn('TraineeApplied', function ($data) {
            return '<a href="javascript:void(0);" class="btn btn-xs btn-warning" onclick="return getCandidate(' . $data->JPId . ')">' . $data->TraineeApplied . '</a>';
        })->rawColumns(['chk', 'TraineeApplied'])->make(true);
    }

    public function getTraieeCandidates(Request $request)
    {
        $usersQuery = trainee_apply::query();
        $Institute = $request->Institute;

        if ($Institute != '') {
            $usersQuery->where('jobcandidates.College', $Institute);
        }
        $data = $usersQuery->Join('jobcandidates', 'trainee_apply.JCId', '=', 'jobcandidates.JCId')->leftJoin('master_institute', 'master_institute.InstituteId', '=', 'jobcandidates.College')->Join('jobpost', 'trainee_apply.JPId', '=', 'jobpost.JPId')->where('trainee_apply.JPId', $request->JPId)->orderBy('jobcandidates.FName', 'asc')->get();
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('chk', function ($data) {
                if ($data->FwdTechScr == 1 || $data->Status == 'Close') {
                    return '';
                } else {
                    return "<input type='checkbox' class='japchks' data-id='$data->TId' name='selectCand' id='selectCand' value='$data->TId'>";
                }
            })
            ->addColumn('CandidateName', function ($data) {
                $name = $data->FName;
                if (!empty($data->MName)) {
                    $name .= ' ' . $data->MName;
                }
                if (!empty($data->LName)) {
                    $name .= ' ' . $data->LName;
                }
                return "<a href='" . route('trainee_detail') . '?jcid=' . base64_encode($data->JCId) . "' target='_blank'>" . $name . "</a>";
            })
            ->addColumn('Qualification', function ($data) {
                $x = getEducationById($data->Education);
                if ($data->Specialization != 0) {
                    $x .= '-' . getSpecializationbyId($data->Specialization);
                }
                return $x;
            })->editColumn('Collage', function ($data) {
                return $data->InstituteName;
            })
            ->addColumn('Gender', function ($data) {
                if ($data->Gender == 'M') {
                    return 'Male';
                } elseif ($data->Gender == 'F') {
                    return 'Female';
                } else {
                    return 'Other';
                }
            })
            ->addColumn('Address', function ($data) {
                return $data->AddressLine1 . ' ' . $data->AddressLine2 . ' ' . $data->AddressLine3 . ' ' . $data->City . ' ,District- ' . getDistrictName($data->District) . ' PinCode -' . $data->PinCode;
            })
            ->editColumn('ApplyDate', function ($data) {
                return date('d-m-Y', strtotime($data->ApplyDate));
            })
            ->addColumn('Action', function ($data) {
                if ($data->FwdTechScr == 1 || $data->Status == 'Close') {
                    return '';
                } else {
                    return '<a href="javascript:void(0);" class="text-danger" onclick="return deleteCandidate(' . $data->TId . ')" title="Delete Candidate"><i class="fa fa-trash"></i></a>';
                }
            })
            ->rawColumns(['chk', 'CandidateName', 'Action'])->make(true);
    }

    public function SendTraineeForScreening(Request $request)
    {
        $TId = $request->TId;
        $sql = 0;
        for ($i = 0; $i < Count($TId); $i++) {

            $query = trainee_apply::find($TId[$i]);
            $query->FwdTechScr = 1;
            $query->ResSentDate = $request->ResumeSent;
            $query->ScrCmp = $request->TechScrCompany;
            $query->ScreeningBy = $request->ScreeningBy;
            $query->save();
            $sql = 1;
        }

        if ($sql == 0) {
            return response()->json(['status' => 400, 'msg' => 'Something went wrong..!!']);
        } else {
            return response()->json(['status' => 200, 'msg' => 'Candidate Successfully Forwaded for Technical Screening.']);
        }
    }

    public function trainee_screening_tracker()
    {
        $company_list = DB::table("core_company")->orderBy('company_code', 'desc')->pluck("company_code", "id");
        $months = [1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April', 5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August', 9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'];
        return view('common.trainee_screening_tracker', compact('company_list', 'months'));
    }

    public function getTraineeScreeningCandidates(Request $request)
    {

        $usersQuery = trainee_apply::query();
        $Company = $request->Company;
        $Department = $request->Department;
        $Year = $request->Year;
        $Month = $request->Month;
        $Name = $request->Name;
        $Status = $request->Status;

        if (Auth::user()->role == 'R') {
            $usersQuery->where('jobpost.CreatedBy', Auth::user()->id);
        }
        if ($Company != '') {
            $usersQuery->where("trainee_apply.Company", $Company);
        }
        if ($Department != '') {
            $usersQuery->where("trainee_apply.Department", $Department);
        }
        if ($Year != '') {
            $usersQuery->whereBetween('trainee_apply.ApplyDate', [$Year . '-01-01', $Year . '-12-31']);
        }
        if ($Month != '') {
            if ($Year != '') {
                $usersQuery->whereBetween('trainee_apply.ApplyDate', [$Year . '-' . $Month . '-01', $Year . '-' . $Month . '-31']);
            } else {
                $usersQuery->whereBetween('trainee_apply.ApplyDate', [date('Y') . '-' . $Month . '-01', date('Y') . '-' . $Month . '-31']);
            }
        }

        if ($Name != '') {
            $usersQuery->where("jc.FName", 'like', "%$Name%")->orWhere("jc.LName", 'like', "%$Name%")->orWhere("jc.Phone", 'like', "%$Name%");
        }

        if ($Status != '') {
            if ($Status == 'Pending') {
                $usersQuery->where('trainee_apply.ScreenStatus', null);
            } else {
                $usersQuery->where('trainee_apply.ScreenStatus', $Status);
            }
        }
        $data = $usersQuery->select('trainee_apply.*', 'jc.ReferenceNo', 'jc.FName', 'jc.MName', 'jc.LName', 'jobpost.JobCode')->Join('jobcandidates as jc', 'trainee_apply.JCId', '=', 'jc.JCId')->Join('jobpost', 'jobpost.JPId', '=', 'trainee_apply.JPId')->where('trainee_apply.FwdTechScr', '1')->where('jobpost.Status', 'Open');

        return datatables()->of($data)->addIndexColumn()->addColumn('chk', function ($data) {
            if ($data->ScreenStatus == null) {
                return '<input type="checkbox" class="select_all" data-id="' . $data->TId . '" name="selectCand" id="selectCand" value="' . $data->TId . '">';
            } else {
                return '';
            }
        })->editColumn('Department', function ($data) {
            return getDepartment($data->Department);
        })->addColumn('CandidateName', function ($data) {

            $name = $data->FName . ' ' . $data->MName . ' ' . $data->LName;
            return "<a href='" . route('trainee_detail') . '?jcid=' . base64_encode($data->JCId) . "' target='_blank'>" . $name . "</a>";
        })
            ->editColumn('ScreenStatus', function ($data) {
                $x = '<select id="ScreenStatus' . $data->TId . '" class="form-control form-select form-select-sm  d-inline" disabled style="width: 100px;" onchange="ChngScreenStatus(' . $data->TId . ',this.value)">';

                $x .= '<option value="" selected></option>';
                $x .= '<option value="Shortlist"';
                $x .= ($data->ScreenStatus == 'Shortlist') ? 'selected' : '';
                $x .= '>Shortlist</option>';
                $x .= '<option value="Reject"';
                $x .= ($data->ScreenStatus == 'Reject') ? 'selected' : '';
                $x .= '>Reject</option>';
                $x .= '</select> <i class="fa fa-pencil-square-o text-success d-inline" aria-hidden="true" id="ScreenStatusEdit' . $data->TId . '" onclick="editScreenStatus(' . $data->TId . ')" style="font-size: 16px;cursor: pointer;"></i>';
                return $x;
            })
            ->addColumn('SendFIROB', function ($data) {
                $id = $data->TId;
                $sendFiroB = $data->Send_FiroB;

                $x = '<select id="SendFIROB' . $id . '" class="form-control form-select form-select-sm d-inline" disabled style="width: 100px;" onchange="ChngSendFIROB(' . $id . ', this.value)">';
                $x .= '<option value="" selected></option>';
                $x .= '<option value="Y"' . ($sendFiroB == 'Y' ? ' selected' : '') . '>Yes</option>';
                $x .= '<option value="N"' . ($sendFiroB == 'N' ? ' selected' : '') . '>No</option>';
                $x .= '</select>';
                $x .= ' <i class="fa fa-pencil-square-o text-success d-inline" aria-hidden="true" id="SendFIROBEdit' . $id . '" onclick="editSendFIROB(' . $id . ')" style="font-size: 16px; cursor: pointer;"></i>';

                return $x;
            })
            ->editColumn('ScreenBy', function ($data) {
                $x = getFullName($data->ScreeningBy);
                return $x;
            })->editColumn('IntervEdit', function ($data) {
                if ($data->ScreenStatus == 'Shortlist') {
                    return '<i class="fa fa-pencil-square-o text-success d-inline" aria-hidden="true" id="editInt' . $data->TId . '" onclick="editInt(' . $data->TId . ')" style="font-size: 16px;cursor: pointer;"></i>';
                } else {
                    return '';
                }
            })
            ->editColumn('IntervDt', function ($data) {
                if ($data->IntervDt != null) {
                    return date('d-M-Y', strtotime($data->IntervDt));
                } else {
                    return '';
                }
            })
            ->rawColumns(['chk', 'ScreenStatus', 'ScreenBy', 'IntervEdit', 'CandidateName', 'SendFIROB'])->make(true);
    }

    public function ChngTraineeScreenStatus(Request $request)
    {
        $query = trainee_apply::where('TId', $request->TId)->update(['ScreenStatus' => $request->va, 'LastUpdated' => now(), 'UpdatedBy' => Auth::user()->id]);
        if (!$query) {
            return response()->json(['status' => 400, 'msg' => 'Something went wrong..!!']);
        } else {
            return response()->json(['status' => 200, 'msg' => 'Screening Status has been changed successfully.']);
        }
    }


    public function getTraineeName(Request $request)
    {
        $sql = DB::table('trainee_apply')->Join('jobcandidates', 'trainee_apply.JCId', '=', 'jobcandidates.JCId')->where('trainee_apply.TId', $request->TId)->select('jobcandidates.FName', 'jobcandidates.MName', 'jobcandidates.LName')->get();

        return $sql[0]->FName . ' ' . $sql[0]->MName . ' ' . $sql[0]->LName;
    }

    function getInterviewDetailsTrainee(Request $request)
    {
        $TId = $request->TId;
        $sql = DB::table('trainee_apply')->where('TId', $TId)->first();
        return response()->json(['status' => 200, 'data' => $sql]);
    }

    public function SaveTraineeInterview(Request $request)
    {
        $sql = trainee_apply::find($request->TId);
        $sql->IntervDt = $request->IntervDt;
        $sql->IntervLoc = $request->IntervLoc;
        $sql->IntervPanel = $request->IntervPanel;
        $sql->IntervStatus = $request->IntervStatus;
        $sql->save();
        if (!$sql) {
            return response()->json(['status' => 400, 'msg' => 'Something went wrong..!!']);
        } else {
            return response()->json(['status' => 200, 'msg' => 'Interview Data has been changed successfully.']);
        }
    }

    public function active_trainee()
    {
        $company_list = DB::table("core_company")->orderBy('company_code', 'desc')->pluck("company_code", "id");
        $months = [1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April', 5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August', 9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'];
        $state_list = DB::table("core_state")->orderBy('state_name', 'ASC')->pluck("id", "state_name");
        return view('common.active_trainee', compact('company_list', 'months', 'state_list'));
    }

    public function get_active_trainee(Request $request)
    {
        $usersQuery = trainee_apply::query();
        $Company = $request->Company;
        $Department = $request->Department;
        $Year = $request->Year;
        $Month = $request->Month;
        $Name = $request->Name;

        if (Auth::user()->role == 'R') {
            $usersQuery->where('jobpost.CreatedBy', Auth::user()->id);
        }
        if ($Company != '') {
            $usersQuery->where("trainee_apply.Company", $Company);
        }
        if ($Department != '') {
            $usersQuery->where("trainee_apply.Department", $Department);
        }
        if ($Year != '') {
            $usersQuery->whereBetween('trainee_apply.ApplyDate', [$Year . '-01-01', $Year . '-12-31']);
        }
        if ($Month != '') {
            if ($Year != '') {
                $usersQuery->whereBetween('trainee_apply.ApplyDate', [$Year . '-' . $Month . '-01', $Year . '-' . $Month . '-31']);
            } else {
                $usersQuery->whereBetween('trainee_apply.ApplyDate', [date('Y') . '-' . $Month . '-01', date('Y') . '-' . $Month . '-31']);
            }
        }
        if ($Name != '') {
            $usersQuery->where("jc.FName", 'like', "%$Name%")->orWhere("jc.LName", 'like', "%$Name%")->orWhere("jc.Phone", 'like', "%$Name%");
        }
        $data = $usersQuery->select('trainee_apply.*', 'jc.College', 'jc.ReferenceNo', 'jc.FName', 'jc.MName', 'jc.LName', 'jobpost.JobCode')->Join('jobcandidates as jc', 'trainee_apply.JCId', '=', 'jc.JCId')->Join('jobpost', 'jobpost.JPId', '=', 'trainee_apply.JPId')->where('trainee_apply.IntervStatus', 'Selected')->where('trainee_apply.TrainingComplete', '0');

        return datatables()->of($data)->addIndexColumn()->addColumn('chk', function () {
            return '<input type="checkbox" class="select_all">';
        })->editColumn('Department', function ($data) {
            return getDepartment($data->Department);
        })->addColumn(
            'CandidateName',
            function ($data) {
                $name = $data->FName . ' ' . $data->MName . ' ' . $data->LName;
                return "<a href='" . route('trainee_detail') . '?jcid=' . base64_encode($data->JCId) . "' target='_blank'>" . $name . "</a>";
            }
        )->addColumn('Action', function ($data) {
            return ' <i class="fa fa-pencil-square-o text-primary d-inline" aria-hidden="true" onclick="edit_detail(' . $data->TId . ',' . $data->Department . ')"  style="font-size: 16px;cursor: pointer; margin-right:20px;"></i>  <i class="fas fa-rupee-sign text-danger d-inline" aria-hidden="true" onclick="addexpense(' . $data->TId . ')" style="font-size: 16px;cursor: pointer; margin-right:20px;"></i> <i class="fas fa-eye text-success d-inline" aria-hidden="true" onclick="view_expense(' . $data->TId . ')" style="font-size: 16px;cursor: pointer;"></i>';
        })->editColumn('OtherBenefit', function ($data) {
            if ($data->OtherBenefit != null) {
                return $data->OtherBenefit;
            } else {
                return '-';
            }
        })
            ->editColumn('State', function ($data) {
                return getHqStateCode($data->HQ_State);
            })
            ->editColumn('Reporting', function ($data) {
                return getFullName($data->Reporting_Manager);
            })
            ->editColumn('College', function ($data) {
                return getCollegeCode($data->College);
            })
            ->rawColumns(['chk', 'Action', 'OtherBenefit', 'CandidateName', 'College'])->make(true);
    }

    public function getTraineeDetail(Request $request)
    {
        $TId = $request->TId;
        $query = DB::table('trainee_apply')->Join('jobcandidates', 'jobcandidates.JCId', '=', 'trainee_apply.JCId')->where('TId', $TId)->first();
        return $query;
    }

    public function save_trainee_detail(Request $request)
    {

        $TId = $request->TId;
        $Stipend = $request->Stipend;
        $Doj = $request->Doj;
        $Doc = $request->Doc;
        $OtherBenefit = convertData($request->OtherBenefit);
        $State = $request->State;
        $City = $request->City;
        $Joined = $request->Joined;
        $ReportingManager = $request->ReportingManager;
        $TrainingComplete = $request->TrainingComplete;
        $query = trainee_apply::where('TId', $TId)->update([
            'Stipend' => $Stipend,
            'Doj' => $Doj,
            'Doc' => $Doc,
            'OtherBenefit' => $OtherBenefit,
            'TrainingComplete' => $TrainingComplete,
            'HQ_State' => $State,
            'HQ_City' => $City,
            'Reporting_Manager' => $ReportingManager,
            'Candidate_Joined' => $Joined,
            'LastUpdated' => date('Y-m-d H:i:s'),
            'UpdatedBy' => Auth::user()->id
        ]);
        if (!$query) {
            return response()->json(['status' => 400, 'msg' => 'Something went wrong..!!']);
        } else {
            return response()->json(['status' => 200, 'msg' => 'Trainee Detail has been changed successfully.']);
        }
    }

    public function old_trainee()
    {
        $company_list = DB::table("core_company")->orderBy('company_code', 'desc')->pluck("company_code", "id");
        $months = [1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April', 5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August', 9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'];
        $job = jobpost::query();
        if (Auth::user()->role == 'R') {
            $job->where('CreatedBy', Auth::user()->id);
        }
        $jobpost_list = $job->select('JPId', 'JobCode')->where('Status', 'Open')->where('JobPostType', 'Regular')->get();
        return view('common.old_trainee', compact('company_list', 'months', 'jobpost_list'));
    }

    public function get_old_trainee(Request $request)
    {
        $usersQuery = trainee_apply::query();
        $Company = $request->Company;
        $Department = $request->Department;
        $Year = $request->Year;
        $Month = $request->Month;
        $Name = $request->Name;
        if (Auth::user()->role == 'R') {
            $usersQuery->where('jobpost.CreatedBy', Auth::user()->id);
        }
        if ($Company != '') {
            $usersQuery->where("trainee_apply.Company", $Company);
        }
        if ($Department != '') {
            $usersQuery->where("trainee_apply.Department", $Department);
        }
        if ($Year != '') {
            $usersQuery->whereBetween('trainee_apply.ApplyDate', [$Year . '-01-01', $Year . '-12-31']);
        }
        if ($Month != '') {
            if ($Year != '') {
                $usersQuery->whereBetween('trainee_apply.ApplyDate', [$Year . '-' . $Month . '-01', $Year . '-' . $Month . '-31']);
            } else {
                $usersQuery->whereBetween('trainee_apply.ApplyDate', [date('Y') . '-' . $Month . '-01', date('Y') . '-' . $Month . '-31']);
            }
        }
        if ($Name != '') {
            $usersQuery->where("jc.FName", 'like', "%$Name%")->orWhere("jc.LName", 'like', "%$Name%")->orWhere("jc.Phone", 'like', "%$Name%");
        }
        $data = $usersQuery->select('trainee_apply.*', 'jc.ReferenceNo', 'jc.FName', 'jc.MName', 'jc.LName', 'jobpost.JobCode')->Join('jobcandidates as jc', 'trainee_apply.JCId', '=', 'jc.JCId')->Join('jobpost', 'jobpost.JPId', '=', 'trainee_apply.JPId')->where('trainee_apply.IntervStatus', 'Selected')->where('trainee_apply.TrainingComplete', '1');

        return datatables()->of($data)->addIndexColumn()->addColumn('chk', function ($data) {
            if ($data->MappedToJob == 0) {
                return '<input type="checkbox" class="select_all japchks" name="selectCand" onclick="checkAllorNot()" value="' . $data->TId . '">';
            } else {
                return '';
            }
        })->editColumn('Department', function ($data) {
            return getDepartment($data->Department);
        })->addColumn('CandidateName', function ($data) {
            $name = $data->FName . ' ' . $data->MName . ' ' . $data->LName;
            return "<a href='" . route('trainee_detail') . '?jcid=' . base64_encode($data->JCId) . "' target='_blank'>" . $name . "</a>";
        })->addColumn('Action', function ($data) {
            return '<i class="fas fa-eye text-success d-inline" aria-hidden="true" onclick="view_expense(' . $data->TId . ')" style="font-size: 16px;cursor: pointer;"></i>';
        })->editColumn('OtherBenefit', function ($data) {
            if ($data->OtherBenefit != null) {
                return $data->OtherBenefit;
            } else {
                return '-';
            }
        })
            ->editColumn('State', function ($data) {
                return getHqStateCode($data->HQ_State);
            })
            ->editColumn('Reporting', function ($data) {
                return getFullName($data->Reporting_Manager);
            })
            ->rawColumns(['chk', 'Action', 'OtherBenefit', 'CandidateName'])->make(true);
    }

    public function add_expense(Request $request)
    {


        $TId = $request->Add_TId;
        $Stipend = $request->Stipend;
        $Expense = $request->Expense;
        $Year = explode('-', $request->Month)[0];
        $Month = explode('-', $request->Month)[1];
        $Total = $Stipend + $Expense;
        $query = DB::table('trainee_stipend')->insert(['TId' => $TId, 'Stipend' => $Stipend, 'Year' => $Year, 'Month' => $Month, 'Expense' => $Expense, 'Total' => $Total, 'UpdatedBy' => Auth::user()->id]);
        if ($query) {
            return response()->json(['status' => 200, 'msg' => 'Expense has been added successfully.']);
        } else {
            return response()->json(['status' => 400, 'msg' => 'Something went wrong..!!']);
        }
    }


    public function get_expense_list(Request $request)
    {
        $sql = DB::table('trainee_stipend')->where('TId', $request->TId)->get();

        return datatables()->of($sql)->addIndexColumn()->editColumn('Month', function ($data) {
            $dateObj = DateTime::createFromFormat('!m', $data->Month);
            $monthName = $dateObj->format('F');
            return $monthName;
        })
            ->addColumn('Action', function ($data) {
                return '<i class="fas fa-trash text-danger d-inline" aria-hidden="true" onclick="delete_stipend(' . $data->Id . ',' . $data->TId . ')" style="font-size: 16px;cursor: pointer;"></i>';
            })
            ->rawColumns(['Action'])->make(true);
    }

    public function map_trainee_to_job(Request $request)
    {
        $TId = $request->TId;
        $JPId = $request->JPId;

        $jobpost = jobpost::find($JPId);
        $Company = $jobpost->CompanyId;
        $Department = $jobpost->DepartmentId;
        $title = $jobpost->Title;

        $sql = 0;
        for ($i = 0; $i < count($TId); $i++) {
            $data = trainee_apply::find($TId[$i]);
            $JCId = $data->JCId;

            $sql_query = DB::table('jobapply')->insert(['JCId' => $JCId, 'JPId' => $JPId, 'Company' => $Company, 'Department' => $Department, 'ResumeSource' => 1, 'ApplyDate' => now(), 'CreatedBy' => Auth::user()->id]);
            $query = DB::table('trainee_apply')->where('TId', $TId[$i])->update(['MappedToJob' => 1, 'LastUpdated' => now(), 'UpdatedBy' => Auth::user()->id]);
            $candidate = jobcandidate::find($JCId);
            $Aadhaar = $candidate->Aadhaar;
            CandidateActivityLog::addToCandLog($JCId, $Aadhaar, 'Candidate Mapped to JobPost, ' . $title);
            $sql = 1;
        }
        if ($sql == 0) {
            return response()->json(['status' => 400, 'msg' => 'Something went wrong..!!']);
        } else {
            return response()->json(['status' => 200, 'msg' => 'Candidate Successfully Mapped again JobPost.']);
        }
    }

    public function trainee_detail()
    {
        return view('common.trainee_detail');
    }

    public function SetAllTraineeInterviewDetails(Request $request)
    {
        $Tid = $request->Tid;
        $Status = $request->Status;
        $Interview_Date = $request->Interview_Date ?? null;
        $Interview_Location = $request->Interview_Location ?? null;
        $Interview_Panel = $request->Interview_Panel ?? null;

        foreach ($Tid as $key => $value) {
            $sql = trainee_apply::find($value);
            $sql->IntervDt = $Interview_Date;
            $sql->IntervLoc = $Interview_Location;
            $sql->IntervPanel = $Interview_Panel;
            $sql->ScreenStatus = $Status;
            $sql->save();
        }
        if (!$sql) {
            return response()->json(['status' => 400, 'msg' => 'Something went wrong..!!']);
        } else {
            return response()->json(['status' => 200, 'msg' => 'Candidate Interview Details has been changed successfully.']);
        }
    }

    public function deleteTraineeCandidate(Request $request)
    {
        $TId = $request->input('TId');

        // Delete From Trainee Apply Table Then Candidate Table
        $JCId = DB::table('trainee_apply')->where('TId', $TId)->value('JCId');

        if ($JCId) {
            // Delete from jobcandidates
            DB::table('jobcandidates')->where('JCId', $JCId)->delete();

            // Delete from trainee_apply
            DB::table('trainee_apply')->where('TId', $TId)->delete();

            return response()->json(['status' => 200, 'msg' => 'Candidate Successfully Deleted.']);
        } else {
            return response()->json(['status' => 400, 'msg' => 'Candidate not found.']);
        }
    }

    public function SendFirobToTrainee(Request $request)
    {
        $query = trainee_apply::where('TId', $request->TId)->update(['Send_FiroB' => $request->va, 'LastUpdated' => now(), 'UpdatedBy' => Auth::user()->id]);
        $JCId = trainee_apply::where('TId', $request->TId)->value('JCId');
        $jobcandidates = jobcandidate::find($JCId);
        $CandidateEmail = $jobcandidates->Email;
        $firobid = base64_encode($JCId);
        if (!$query) {
            return response()->json(['status' => 400, 'msg' => 'Something went wrong..!!']);
        } else {
            $details = [
                "subject" => 'Internship selection process: Online Test',
                "name" => $jobcandidates->Title . ' ' . $jobcandidates->FName . ' ' . $jobcandidates->MName . ' ' . $jobcandidates->LName,
                "reference_no" => $jobcandidates->ReferenceNo,
                'firob' => route("firo_b", "jcid=$firobid"),
            ];
            if ($request->va == 'Y') {
                Mail::to($CandidateEmail)->send(new TraineeFiroBMail($details));
            }
            return response()->json(['status' => 200, 'msg' => 'Firob Sent successfully.']);
        }
    }

    public function import_trainee_expense(Request $request)
    {
        try {
            $file = $request->file('import_file');
            $currentDate = date('Y-m-d-H-i');
            $filename = 'trainee_expense_' . $currentDate . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('assets/uploads'), $filename);
            Excel::import(new TraineeStipendImport(), public_path('assets/uploads/' . $filename));
            return response()->json(['status' => 200, 'message' => 'Trainee Expense Imported Successfully.']);
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'error' => $e->getMessage()]);
        }
    }


    public function deleteStipend(Request $request)
    {
        $id = $request->id;
        $query = DB::table('trainee_stipend')->where('id', $id)->delete();
        if ($query) {
            return response()->json(['status' => 200, 'msg' => 'Stipend Deleted Successfully.']);
        } else {
            return response()->json(['status' => 400, 'msg' => 'Something went wrong.']);
        }
    }
}
