<?php

namespace App\Http\Controllers\Common;

use App\Models\jobpost;
use App\Models\jobapply;
use App\Models\screening;
use App\Models\master_mrf;
use App\Models\OfferLetter;
use App\Helpers\LogActivity;
use App\Models\jobcandidate;
use Illuminate\Http\Request;
use App\Models\screen2ndround;
use App\Helpers\UserNotification;
use App\Mail\CampusInterviewMail;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Models\Recruiter\master_post;


use Illuminate\Support\Facades\Validator;


class CampusController extends Controller
{
    function campus_mrf_allocated()
    {
        $company_list = DB::table("core_company")->orderBy('company_code', 'desc')->pluck("company_code", "id");
        $department_list = DB::table("core_department")->where('is_active', '1')->orderBy('department_name', 'asc')->pluck("department_name", "id");
        $state_list = DB::table("states")->orderBy('StateName', 'asc')->pluck("StateName", "StateId");
        $institute_list = DB::table("master_institute")->orderBy('InstituteName', 'asc')->pluck("InstituteName", "InstituteId");
        $designation_list = DB::table("core_designation")->where('designation_name', '!=', '')->orderBy('designation_name', 'asc')->pluck("designation_name", "id");
        $employee_list = DB::table('master_employee')->orderBy('FullName', 'ASC')
            ->where('EmpStatus', 'A')
            ->select('EmployeeID', DB::raw('CONCAT(Fname, " ", Lname) AS FullName'))
            ->pluck("FullName", "EmployeeID");
        $months = [1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April', 5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August', 9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'];


        $close = DB::table('manpowerrequisition');
        if (Auth::user()->role == 'R') {

            $close->where('Allocated', Auth::user()->id);
        }
        $close->where(function ($query) {
            $query->where('Type', 'Campus')
                ->orWhere('Type', 'Campus_HrManual');
        })
            ->where('status', 'Close')
            ->select('MRFId')
            ->get();
        $CloseMRF = $close->count();


        $open = DB::table('manpowerrequisition');
        if (Auth::user()->role == 'R') {
            $open->where('Allocated', Auth::user()->id);
        }
        $open->where(function ($query1) {
            $query1->where('Type', 'Campus')
                ->orWhere('Type', 'Campus_HrManual');
        })
            ->where('status', 'Approved')
            ->select('MRFId')
            ->get();
        $OpenMRF = $open->count();
        return view('recruiter.campus_mrf_allocated ', compact('company_list', 'department_list', 'state_list', 'institute_list', 'designation_list', 'employee_list', 'months', 'CloseMRF', 'OpenMRF'));
    }

    function getAllCampusAllocatedMrf(Request $request)
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
            $usersQuery->where('manpowerrequisition.Status', 'Approved');
        } else {
            $usersQuery->where('manpowerrequisition.Status', 'Close');
        }

        $mrf = $usersQuery->select('*')
            ->Join('core_designation', 'manpowerrequisition.DesigId', '=', 'core_designation.id', 'left')
            ->Join('core_department', 'manpowerrequisition.DepartmentId', '=', 'core_department.id')
            ->where(function ($query) {
                $query->where('manpowerrequisition.Type', 'Campus')
                    ->orWhere('manpowerrequisition.Type', 'Campus_HrManual');
            });


        return datatables()->of($mrf)
            ->addIndexColumn()
            ->addColumn('chk', function () {
                return '<input type="checkbox" class="select_all">';
            })
            /*     ->editColumn('Type', function ($mrf) {
                if ($mrf->Type == 'N' || $mrf->Type == 'N_HrManual') {
                    return 'New MRF';
                } elseif ($mrf->Type == 'SIP' || $mrf->Type == 'SIP_HrManual') {
                    return 'SIP/Internship MRF';
                } elseif ($mrf->Type == 'Campus' || $mrf->Type == 'Campus_HrManual') {
                    return 'Campus MRF';
                } elseif ($mrf->Type == 'R' || $mrf->Type == 'R_HrManual') {
                    return 'Replacement MRF';
                }
            }) */

            ->editColumn('Collage', function ($mrf) {
                $collage = unserialize($mrf->EducationInsId);

                return getCollegeById($collage);
            })
            ->editColumn('LocationIds', function ($mrf) {
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
            })
            ->addColumn('JobPost', function ($mrf) {
                $check = CheckJobPostCreated($mrf->MRFId);
                if ($check == 1) {
                    return 'Created';
                } else {
                    return '<a  href="javascript:void(0);" data-bs-toggle="modal"
                    data-bs-target="#createpostmodal" onclick="getDetailForJobPost(' . $mrf->MRFId . ')"><i class="fa fa-plus-square-o"></i>Create</a>';
                }
            })
            ->addColumn('details', function ($mrf) {
                return '<i  class="fadeIn animated lni lni-eye  text-success view" aria-hidden="true" data-id="' . $mrf->MRFId . '" id="viewMRF"  style="font-size: 18px;cursor: pointer;"></i>';
            })
            ->addColumn('Link', function ($mrf) {
                $JPId = GetJobPostId($mrf->MRFId);
                if ($JPId > 0) {
                    $PostId = base64_encode($JPId);

                    $x = '<input type="text" id="link' . $JPId . '" value="' . url('jobportal/campus_placement_registration?job=' . $PostId . '') . '" >  <button onclick="copylink(' . $JPId . ')" class="btn btn-sm btn-primary"> Copy</button>';
                    return $x;
                } else {
                    return 'Job Post Not Created Yet';
                }
            })
            ->rawColumns(['chk', 'details', 'JobShow', 'JobPost', 'Link'])
            ->make(true);
    }

    public function createJobPost_Campus(Request $request)
    {

        $MRFId = $request->MRFId;
        $KeyPosition = $request->KeyPosition;
        $JobCode = $request->JobCode;
        $State = $request->State;
        $City = $request->City;
        $ManPower = $request->ManPower;
        $Education = $request->Education;
        $Specialization = $request->Specialization;


        $locArray = array();
        if ($State != '') {
            for ($lc = 0; $lc < Count($State); $lc++) {
                $location = array(
                    "state" => $State[$lc],
                    "city" => $City[$lc] == '' ? '' : $City[$lc],
                    "nop" => $ManPower[$lc],
                );
                array_push($locArray, $location);
            }
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
        }

        $KpArray_str = serialize($KpArray);

        $MRFDetails = master_mrf::find($MRFId);
        $Company = $MRFDetails['CompanyId'];
        $Department = $MRFDetails['DepartmentId'];
        $Desig = $MRFDetails['DesigId'];
        $Title = getDesignation($MRFDetails['DesigId']);

        $Status = 'Open';

        $SQL = new master_post;
        $SQL->MRFId = $MRFId;
        $SQL->CompanyId = $Company;
        $SQL->DepartmentId = $Department;
        $SQL->DesigId = $Desig;
        $SQL->JobCode = $JobCode;
        $SQL->Title = $Title;
        $SQL->PayPackage = $request->PayPackage;
        $SQL->LastDate = $request->LastDate;
        $SQL->ReqQualification = $EduArray_str;
        $SQL->Description = convertData($request->JobInfo);
        $SQL->Location = $locArray_str;
        $SQL->KeyPositionCriteria = $KpArray_str;
        $SQL->PostingView = 'Hidden';
        $SQL->JobPostType = 'Campus';
        $SQL->Status = $Status;
        $SQL->CreatedBy = Auth::user()->id;
        $query = $SQL->save();


        $sql1 = master_mrf::find($MRFId);
        $sql1->info = convertData($request->JobInfo);
        $sql1->KeyPositionCriteria = $KpArray_str;
        $sql1->LocationIds = $locArray_str;
        $sql1->EducationId = $EduArray_str;
        $sql1->UpdatedBy = Auth::user()->id;
        $sql1->LastUpdated = now();
        $query = $sql1->save();

        if (!$query) {
            return response()->json(['status' => 400, 'msg' => 'Something went wrong..!!']);
        } else {
            LogActivity::addToLog('Campus JobPost ' . $JobCode . ' is created by ' . getFullName(Auth::user()->id), 'Create');
            UserNotification::notifyUser(1, 'Job Post Create', $JobCode);
            return response()->json(['status' => 200, 'msg' => 'Campus JobPost has been successfully created.']);
        }
    }

    public function campus_applications()
    {
        $company_list = DB::table("core_company")->orderBy('company_code', 'desc')->pluck("company_code", "id");
        $months = [1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April', 5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August', 9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'];
        return view('common.campus_applications', compact('company_list', 'months'));
    }

    public function campus_hiring_tracker()
    {
        $company_list = DB::table("core_company")->orderBy('company_code', 'desc')->pluck("company_code", "id");
        $months = [1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April', 5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August', 9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'];
        return view('common.campus_hiring_tracker', compact('company_list', 'months'));
    }

    public function getCampusSummary(Request $request)
    {

        $usersQuery = jobpost::query();
        $Company = $request->Company;
        $Department = $request->Department;
        $Year = $request->Year;
        $Month = $request->Month;

        if (Auth::user()->role == 'R') {
            $usersQuery->where(function ($query) {
                $query->where('jobpost.CreatedBy', Auth::user()->id)
                    ->orWhere('manpowerrequisition.Allocated', Auth::user()->id);
            });
            //$usersQuery->where('jobpost.CreatedBy', Auth::user()->id);
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


        $data = $usersQuery->select('jobpost.JPId', 'jobapply.Company', 'jobapply.Department', 'jobpost.JobCode', 'manpowerrequisition.EducationInsId', 'jobpost.DesigId', DB::raw('COUNT(jobapply.JAId) AS StudentApplied'))
            ->Join('manpowerrequisition', 'manpowerrequisition.MRFId', '=', 'jobpost.MRFId')
            ->Join('jobapply', 'jobpost.JPId', '=', 'jobapply.JPId')
            ->where('jobapply.Type', 'Campus')
            ->groupBy('jobpost.JPId');


        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('chk', function () {
                return '<input type="checkbox" class="select_all">';
            })
            ->editColumn('College', function ($data) {

                $College = unserialize($data->EducationInsId);
                return getCollegeById($College);
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
            ->editColumn('StudentApplied', function ($data) {
                return '<a href="javascript:void(0);" class="btn btn-xs btn-warning" onclick="return getCandidate(' . $data->JPId . ');">' . $data->StudentApplied . '</a>';
            })
            ->rawColumns(['chk', 'StudentApplied'])
            ->make(true);
    }

    public function getCampusCandidates(Request $request)
    {
        $data = DB::table('jobapply')
            ->Join('jobcandidates', 'jobapply.JCId', '=', 'jobcandidates.JCId')
            ->where('jobapply.JPId', $request->JPId);

        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('chk', function ($data) {
                if ($data->PlacementDate == null || $data->PlacementDate == '' || $data->Status == 'Selected') {
                    return '';
                } else {
                    return "<input type='checkbox' class='japchks' data-id='$data->JAId' name='selectCand' id='selectCand' value='$data->JAId'>";
                }
            })
            ->editColumn('ReferenceNo', function ($data) {
                $sendingId = base64_encode($data->JAId);
                return '<a href="candidate_detail?jaid=' . $sendingId . '" target="_blank">' . $data->ReferenceNo . '</a>';
            })
            ->addColumn('University', function ($data) {
                return getCollegeCode($data->College);
            })
            ->addColumn('StudentName', function ($data) {

                return $data->FName . ' ' . $data->MName . ' ' . $data->LName;
            })
            ->addColumn('Qualification', function ($data) {
                $x = getEducationCodeById($data->Education);

                return $x;
            })
            ->addColumn('Specialization', function ($data) {
                if ($data->Specialization != 0) {
                    $x = getSpecializationbyId($data->Specialization);
                } else {
                    $x = '';
                }

                return $x;
            })
            ->editColumn('State', function ($data) {
                return getStateName($data->State);
            })
            ->addColumn('PlacementDate', function ($data) {
                if ($data->PlacementDate != null) {
                    $x = '<input type="date" class="frminp d-inline-block form-control form-control-sm" readonly style="width:130px;" id="PlacementDate' . $data->JCId . '" value="' . $data->PlacementDate . '"><i class="fa fa-pencil-square-o text-primary" aria-hidden="true" id="PDateEdit" onclick="PDateEnbl(' . $data->JCId . ',this)" style="font-size:16px; cursor:pointer;"></i><button class="btn btn-sm frmbtn btn-primary" style="display:none;" id="PDateSave' . $data->JCId . '" onclick="SavePlacementDate(' . $data->JCId . ',this)">Save</button><button class="btn btn-sm frmbtn btn-danger" style="display: none;" id="PDateCanc' . $data->JCId . '" onclick="window.location.reload();">Cancel</button>';
                } else {
                    $x = '<input type="checkbox" class="camcand" name="camcand" data-id="' . $data->JAId . '" name="selectCand_date" id="selectCand_date" value="' . $data->JAId . '"/> <input type="date" class="frminp d-inline-block form-control form-control-sm" readonly style="width:130px;" id="PlacementDate' . $data->JCId . '" value="' . $data->PlacementDate . '"><i class="fa fa-pencil-square-o text-primary" aria-hidden="true" id="PDateEdit" onclick="PDateEnbl(' . $data->JCId . ',this)" style="font-size:16px; cursor:pointer;"></i><button class="btn btn-sm frmbtn btn-primary" style="display:none;" id="PDateSave' . $data->JCId . '" onclick="SavePlacementDate(' . $data->JCId . ',this)">Save</button><button class="btn btn-sm frmbtn btn-danger" style="display: none;" id="PDateCanc' . $data->JCId . '" onclick="window.location.reload();">Cancel</button>';
                }
                return $x;
            })
            ->addColumn('Action', function ($data) {
                if ($data->PlacementDate != null || $data->PlacementDate != '' || $data->Status == 'Selected') {
                    return '';
                } else {
                    return '<a href="javascript:void(0);" class="text-danger" onclick="return deleteCandidate(' . $data->JAId . ')" title="Delete Candidate"><i class="fa fa-trash"></i></a>';
                }
            })
            ->rawColumns(['chk', 'Action', 'PlacementDate', 'ReferenceNo'])
            ->make(true);
    }

    public function SavePlacementDate(Request $request)
    {
        $sql = jobcandidate::find($request->JCId);

        $sql->PlacementDate = $request->PlacementDate;
        $sql->UpdatedBy = Auth::user()->id;
        $sql->LastUpdated = now();
        $query = $sql->save();
        if (!$query) {
            return response()->json(['status' => 400, 'msg' => 'Something went wrong..!!']);
        } else {
            return response()->json(['status' => 200, 'msg' => 'Campus Placement Date has been changed successfully.']);
        }
    }

    public function getPostTitle(Request $request)
    {
        $sql = jobpost::find($request->JPId);
        if ($sql->DesigId == 0 || $sql->DesigId == null) {
            if ($sql->Status == 'Close') {
                return $sql->JobCode . '<span class="text-danger"> (This Job Post is closed.)</span>';
            } else {
                return $sql->JobCode;
            }
        } else {
            return (getDesignation($sql->DesigId));
        }
    }

    public function SendForScreening(Request $request)
    {
        $JAId = $request->JAId;
        $sql = 0;
        for ($i = 0; $i < Count($JAId); $i++) {
            $query = jobapply::find($JAId[$i]);
            $query->Status = 'Selected';       //HR Screening
            $query->FwdTechScr = 'Yes';
            $query->SelectedBy = Auth::user()->id;
            $query->save();


            $res = new screening;
            $res->JAId = $query->JAId;
            $res->ScrCmp = $query->Company;
            $res->ScrDpt = $query->Department;
            $res->ScreeningBy = Auth::user()->id;
            $res->CreatedBy = Auth::user()->id;
            $res->ReSentForScreen = now();
            $res->CreatedTime = now();
            $res->save();


            //Need to send mail for Firo B   , do it later after firo b is completed
            $sql = 1;
        }

        if ($sql == 0) {
            return response()->json(['status' => 400, 'msg' => 'Something went wrong..!!']);
        } else {
            return response()->json(['status' => 200, 'msg' => 'Candidate Successfully Forwaded for Technical Screening.']);
        }
    }


    public function SetAllCampusDate(Request $request)
    {
        $JAId = $request->JAId;
        $CampusDate = $request->CampusDate;
        $sql = 0;
        for ($i = 0; $i < Count($JAId); $i++) {
            $query = jobapply::find($JAId[$i]);
            $str = jobcandidate::find($query->JCId);
            $str->PlacementDate = $CampusDate;
            $str->UpdatedBy = Auth::user()->id;
            $str->LastUpdated = now();
            $str->save();
            $sql = 1;
        }

        if ($sql == 0) {
            return response()->json(['status' => 400, 'msg' => 'Something went wrong..!!']);
        } else {
            return response()->json(['status' => 200, 'msg' => 'Campus Placement Date has been changed successfully.']);
        }
    }

    public function SetAllCampusTechScrStatus(Request $request)
    {
        $JAId = $request->JAId;
        $sql = 0;
        for ($i = 0; $i < Count($JAId); $i++) {

            $query = screening::where('JAId', $JAId[$i])->first();

            $query->ScreenStatus = $request->techStatus;
            $query->UpdatedBy = Auth::user()->id;
            $query->LastUpdated = now();
            $query->save();

            $sql = 1;
        }

        if ($sql == 0) {
            return response()->json(['status' => 400, 'msg' => 'Something went wrong..!!']);
        } else {
            return response()->json(['status' => 200, 'msg' => 'Candidate Technical Screening Status Successfully Changed']);
        }
    }

    public function campus_screening_tracker()
    {
        $company_list = DB::table("core_company")->orderBy('company_code', 'desc')->pluck("company_code", "id");
        $months = [1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April', 5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August', 9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'];
        return view('common.campus_screening_tracker', compact('company_list', 'months'));
    }

    public function getCampusScreeningCandidates(Request $request)
    {

        $usersQuery = screening::query();
        $Company = $request->Company;
        $Department = $request->Department;
        $Year = $request->Year;
        $Month = $request->Month;
        $Name = $request->Name;
        $Status = $request->Status;

        if (Auth::user()->role == 'R') {
            $usersQuery->where('screening.ScreeningBy', Auth::user()->id);
        }
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
        if ($Name != '') {
            $usersQuery->where("jc.FName", 'like', "%$Name%")->orWhere("jc.LName", 'like', "%$Name%")->orWhere("jc.Phone", 'like', "%$Name%");
        }

        if ($Status != '') {
            if ($Status == 'Pending') {
                $usersQuery->where('screening.ScreenStatus', null);
            } else {
                $usersQuery->where('screening.ScreenStatus', $Status);
            }
        }
        $data = $usersQuery->select('screening.*', 'jc.ReferenceNo', 'jc.FName', 'jc.MName', 'jc.LName', 'jp.DesigId', 'jc.College')
            ->Join('jobapply as ja', 'ja.JAId', '=', 'screening.JAId')
            ->Join('jobcandidates as jc', 'ja.JCId', '=', 'jc.JCId')
            ->Join('jobpost as jp', 'ja.JPId', '=', 'jp.JPId')
            ->where('ja.Type', 'Campus')
            ->where('jp.Status', 'Open')
            ->where('ja.Status', 'Selected');

        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('chk', function ($data) {
                if ($data->ScreenStatus == '' || $data->ScreenStatus == null) {
                    return "<input type='checkbox' class='japchks' data-id='$data->JAId' name='selectCand' id='selectCand' value='$data->JAId'>";
                } else {

                    return '';
                }
            })
            ->editColumn('Department', function ($data) {
                return getDepartment($data->ScrDpt);
            })
            ->editColumn('Designation', function ($data) {
                return getDesignationCode($data->DesigId);
            })
            ->editColumn('University', function ($data) {
                return getCollegeCode($data->College);
            })
            ->addColumn('StudentName', function ($data) {
                return $data->FName . ' ' . $data->MName . ' ' . $data->LName;
            })
            ->addColumn('GDResult', function ($data) {
                $x = '<select id="GDResult' . $data->JAId . '" class="form-control form-select form-select-sm  d-inline" disabled style="width: 100px;" onchange="ChngGDResult(' . $data->JAId . ',this.value)">';

                $x .= '<option value="" selected></option>';
                $x .= '<option value="Selected"';
                $x .= ($data->GDResult == 'Selected') ? 'selected' : '';
                $x .= '>Selected</option>';

                $x .= '<option value="Rejected"';
                $x .= ($data->GDResult == 'Rejected') ? 'selected' : '';
                $x .= '>Rejected</option>';


                $x .= '</select> <i class="fa fa-pencil-square-o text-success d-inline" aria-hidden="true" id="GDResEdit' . $data->JAId . '" onclick="editGDRes(' . $data->JAId . ')" style="font-size: 16px;cursor: pointer;"></i>';
                return $x;
            })
            ->addColumn('TestScore', function ($data) {
                $x = '<input type="text" name="TestScore' . $data->JAId . '" id="TestScore' . $data->JAId . '" value="' . $data->TestScore . '" class="frminp" style="width:80px;" disabled> <i class="fa fa-pencil-square-o text-success d-inline" aria-hidden="true" id="TestScoreEdit' . $data->JAId . '" onclick="editTestScore(' . $data->JAId . ')" style="font-size: 16px;cursor: pointer;"></i><button class="btn btn-sm frmbtn btn-success d-none" id="SaveScore' . $data->JAId . '" onclick="return SaveTestScore(' . $data->JAId . ')">Save</button>';
                return $x;
            })
            ->editColumn('ScreenStatus', function ($data) {
                $x = '<select id="ScreenStatus' . $data->JAId . '" class="form-control form-select form-select-sm  d-inline" disabled style="width: 100px;" onchange="ChngScreenStatus(' . $data->JAId . ',this.value)">';

                $x .= '<option value="" selected></option>';
                $x .= '<option value="Shortlist"';
                $x .= ($data->ScreenStatus == 'Shortlist') ? 'selected' : '';
                $x .= '>Shortlist</option>';

                $x .= '<option value="Reject"';
                $x .= ($data->ScreenStatus == 'Reject') ? 'selected' : '';
                $x .= '>Reject</option>';


                $x .= '</select> <i class="fa fa-pencil-square-o text-success d-inline" aria-hidden="true" id="ScreenStatusEdit' . $data->JAId . '" onclick="editScreenStatus(' . $data->JAId . ')" style="font-size: 16px;cursor: pointer;"></i>';
                return $x;
            })
            ->rawColumns(['chk', 'GDResult', 'TestScore', 'ScreenStatus'])
            ->make(true);
    }

    public function getCampusHiringCandidates(Request $request)
    {

        $usersQuery = screening::query();
        $Company = $request->Company;
        $Department = $request->Department;
        $Year = $request->Year;
        $Month = $request->Month;

        if (Auth::user()->role == 'R') {
            $usersQuery->where('screening.ScreeningBy', Auth::user()->id);
        }
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

        $data = $usersQuery->select('screening.*', 'jc.ReferenceNo', 'jc.FName', 'jc.MName', 'jc.LName', 'jp.DesigId', 'jc.College', 'sc.IntervDt2', 'sc.IntervLoc2', 'sc.IntervPanel2', 'sc.IntervStatus2')
            ->Join('jobapply as ja', 'ja.JAId', '=', 'screening.JAId')
            ->Join('jobcandidates as jc', 'ja.JCId', '=', 'jc.JCId')
            ->Join('jobpost as jp', 'ja.JPId', '=', 'jp.JPId')
            ->join('screen2ndround as sc', 'screening.ScId', '=', 'sc.ScId', 'left')
            ->where('ja.Type', 'Campus')
            ->where('jp.Status', 'Open')
            ->where('screening.ScreenStatus', 'Shortlist');


        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('chk', function () {
                return '<input type="checkbox" class="select_all">';
            })
            ->editColumn('Department', function ($data) {
                return getDepartment($data->ScrDpt);
            })
            ->editColumn('Designation', function ($data) {
                return getDesignationCode($data->DesigId);
            })
            ->editColumn('University', function ($data) {
                return getCollegeCode($data->College);
            })
            ->addColumn('StudentName', function ($data) {
                return $data->FName . ' ' . $data->MName . ' ' . $data->LName;
            })
            ->editColumn('IntervEdit', function ($data) {
                return '<i class="fa fa-pencil-square-o text-success d-inline" aria-hidden="true" id="editInt' . $data->JAId . '" onclick="editInt(' . $data->JAId . ',' . $data->ScId . ')" style="font-size: 16px;cursor: pointer;"></i>';
            })
            ->editColumn('IntervDt2', function ($data) {
                if ($data->IntervDt2 != null) {
                    return $data->IntervDt2;
                } else {
                    return '';
                }
            })
            ->editColumn('IntervLoc2', function ($data) {
                if ($data->IntervLoc2 != null) {
                    return $data->IntervLoc2;
                } else {
                    return '';
                }
            })
            ->editColumn('IntervPanel2', function ($data) {
                if ($data->IntervPanel2 != null) {
                    return $data->IntervPanel2;
                } else {
                    return '';
                }
            })
            ->editColumn('IntervStatus2', function ($data) {
                if ($data->IntervStatus2 != null) {
                    return $data->IntervStatus2;
                } else {
                    return '';
                }
            })
            ->editColumn('IntervEdit2', function ($data) {
                if ($data->IntervStatus == '2nd Round Interview') {
                    return '<i class="fa fa-pencil-square-o text-success d-inline" aria-hidden="true" id="editInt_2nd' . $data->JAId . '" onclick="editInt_2nd(' . $data->JAId . ',' . $data->ScId . ')" style="font-size: 16px;cursor: pointer;"></i>';
                } else {
                    return '';
                }
            })
            ->editColumn('CompanyEdit', function ($data) {
                if ($data->IntervStatus == 'Selected' || $data->IntervStatus2 == 'Selected') {
                    return '<i class="fa fa-pencil-square-o text-success d-inline" aria-hidden="true" id="companyedit' . $data->JAId . '" onclick="editCompany(' . $data->JAId . ',' . $data->ScId . ')" style="font-size: 16px;cursor: pointer;"></i>';
                } else {
                    return '';
                }
            })
            ->editColumn('SelectedForC', function ($data) {
                if ($data->SelectedForC != null) {
                    return getcompany_code($data->SelectedForC);
                } else {
                    return '';
                }
            })
            ->editColumn('SelectedForD', function ($data) {
                if ($data->SelectedForD != null) {
                    return getDepartmentCode($data->SelectedForD);
                } else {
                    return '';
                }
            })
            ->rawColumns(['chk', 'IntervEdit', 'IntervEdit2', 'CompanyEdit'])
            ->make(true);
    }

    public function ChngGDResult(Request $request)
    {
        $query = screening::where('JAId', $request->JAId)
            ->update(['GDResult' => $request->va]);
        if (!$query) {
            return response()->json(['status' => 400, 'msg' => 'Something went wrong..!!']);
        } else {

            return response()->json(['status' => 200, 'msg' => 'GD Result Status has been changed successfully.']);
        }
    }

    public function SaveTestScore(Request $request)
    {
        $query = screening::where('JAId', $request->JAId)
            ->update(['TestScore' => $request->Score]);
        if (!$query) {
            return response()->json(['status' => 400, 'msg' => 'Something went wrong..!!']);
        } else {
            return response()->json(['status' => 200, 'msg' => 'Test Score has been changed successfully.']);
        }
    }

    public function ChngScreenStatus(Request $request)
    {
        $query = screening::where('JAId', $request->JAId)
            ->update(['ScreenStatus' => $request->va, 'ResScreened' => now(), 'LastUpdated' => now(), 'UpdatedBy' => Auth::user()->id]);
        $jobapply = jobapply::find($request->JAId);
        $JCId = $jobapply->JCId;
        $jobcandidates = jobcandidate::find($JCId);
        $firobid = base64_encode($JCId);
        $JPId = $jobapply->JPId;

        $jobpost = jobpost::find($JPId);
        $Title = $jobpost->Title;
        $sendingId = base64_encode($request->JAId);
        $CandidateEmail = $jobcandidates->Email;

        if ($request->va == 'Shortlist') {
            $details = [
                "subject" => 'Interview Call Letter',
                "name" => $jobcandidates->FName . ' ' . $jobcandidates->MName . ' ' . $jobcandidates->LName,
                "reference_no" => $jobcandidates->ReferenceNo,
                "title" => $jobpost->Title,
                'interview_form' => route("candidate-interview-form", "jaid=$sendingId"),
                'firob' => route("firo_b", "jcid=$firobid")
            ];
            Mail::to($CandidateEmail)->send(new CampusInterviewMail($details));
        }
        if (!$query) {
            return response()->json(['status' => 400, 'msg' => 'Something went wrong..!!']);
        } else {

            return response()->json(['status' => 200, 'msg' => 'Screening Status has been changed successfully.']);
        }
    }

    public function getCandidateName(Request $request)
    {
        $sql = DB::table('jobapply')
            ->Join('jobcandidates', 'jobapply.JCId', '=', 'jobcandidates.JCId')
            ->where('jobapply.JAId', $request->JAId)
            ->select('jobcandidates.FName', 'jobcandidates.MName', 'jobcandidates.LName')
            ->get();

        return $sql[0]->FName . ' ' . $sql[0]->MName . ' ' . $sql[0]->LName;
    }

    public function SaveFirstInterview_Campus(Request $request)
    {
        $sql = screening::find($request->ScId);
        $sql->InterAtt = 'Yes';
        $sql->IntervDt = $request->IntervDt;
        $sql->IntervLoc = $request->IntervLoc;
        $sql->IntervPanel = $request->IntervPanel;
        $sql->IntervStatus = $request->IntervStatus;
        $sql->save();
        if (!$sql) {
            return response()->json(['status' => 400, 'msg' => 'Something went wrong..!!']);
        } else {
            return response()->json(['status' => 200, 'msg' => '1st Interview Data has been changed successfully.']);
        }
    }

    public function SaveSecondInterview_Campus(Request $request)
    {
        $sql = new screen2ndround;
        $sql->InterAtt2 = 'Yes';
        $sql->ScId = $request->ScId_2nd;
        $sql->IntervDt2 = $request->IntervDt2;
        $sql->IntervLoc2 = $request->IntervLoc2;
        $sql->IntervPanel2 = $request->IntervPanel2;
        $sql->IntervStatus2 = $request->IntervStatus2;
        $sql->CreatedTime = now();
        $sql->CreatedBy = Auth::user()->id;
        $sql->save();
        if (!$sql) {
            return response()->json(['status' => 400, 'msg' => 'Something went wrong..!!']);
        } else {
            return response()->json(['status' => 200, 'msg' => '2nd Interview Data has been changed successfully.']);
        }
    }

    public function Save_Cmp_Dpt_Campus(Request $request)
    {
        $sql = screening::find($request->ScId_cmp);
        $sql->SelectedForC = $request->SelectedForC;
        $sql->SelectedForD = $request->SelectedForD;
        $sql->save();

        $JAId = $sql->JAId;

        $query = new OfferLetter;
        $query->JAId = $JAId;
        $query->Company = $request->SelectedForC;
        $query->Department = $request->SelectedForD;
        $query->CreatedTime = now();
        $query->Year = date('Y');
        $query->CreatedBy = Auth::user()->id;
        $query->save();


        if (!$sql) {
            return response()->json(['status' => 400, 'msg' => 'Something went wrong..!!']);
        } else {
            return response()->json(['status' => 200, 'msg' => 'Data has been changed successfully.']);
        }
    }

    public function campus_hiring_costing()
    {
        $company_list = DB::table("core_company")->orderBy('company_code', 'desc')->pluck("company_code", "id");
        return view('common.hiring_costing', compact('company_list'));
    }

    public function getCampusCosting(Request $request)
    {

        $usersQuery = jobpost::query();
        $Company = $request->Company;
        $Department = $request->Department;


        if (Auth::user()->role == 'R') {
            $usersQuery->where('jobpost.CreatedBy', Auth::user()->id);
        }
        if ($Company != '') {
            $usersQuery->where("jobpost.CompanyId", $Company);
        }
        if ($Department != '') {
            $usersQuery->where("jobpost.DepartmentId", $Department);
        }


        $data = $usersQuery->select('jobpost.JPId', 'jobpost.JobCode', 'manpowerrequisition.EducationInsId', 'campus_costing.total', 'jobpost.DepartmentId', 'jobpost.DesigId')
            ->Join('manpowerrequisition', 'manpowerrequisition.MRFId', '=', 'jobpost.MRFId')
            ->leftJoin('campus_costing', 'campus_costing.JPId', '=', 'jobpost.JPId')
            ->where('JobPostType', 'Campus')
            ->groupBy('jobpost.JPId');


        return datatables()->of($data)
            ->addIndexColumn()
            ->editColumn('College', function ($data) {

                $College = unserialize($data->EducationInsId);
                return getCollegeById($College);
            })
            ->editColumn('Department', function ($data) {
                return getDepartment($data->DepartmentId);
            })
            ->editColumn('Designation', function ($data) {
                if ($data->DesigId != 0 || $data->DesigId != null) {
                    return getDesignation($data->DesigId);
                } else {
                    return '';
                }
            })
            ->addColumn('Action', function ($data) {
                //data-bs-toggle="modal" data-bs-target="#expense_modal"
                return '<a href="javascript:void(0);" class="btn btn-xs btn-warning" onclick="getCosting(' . $data->JPId . ')"  >Edit/View</a>';
            })
            ->rawColumns(['Action'])
            ->make(true);
    }

    public function updateCosting(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'Appeared' => 'required',
            'Hired' => 'required',
            'RT1' => 'required',
            'RT2' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 0, 'error' => $validator->errors()->toArray()]);
        } else {
            $chk = DB::table('campus_costing')->where('JPId', $request->JPId)->first();
            if ($chk == null) {
                $sql = DB::table('campus_costing')->insert([
                    'JPId' => $request->JPId,
                    'FromDate' => $request->FromDate,
                    'ToDate' => $request->ToDate,
                    'Appeared' => $request->Appeared,
                    'Hired' => $request->Hired,
                    'RT1' => $request->RT1,
                    'RT2' => $request->RT2,
                    'RT3' => $request->RT3,
                    'RT4' => $request->RT4,
                    'AvgCost' => $request->AvgCost,
                    'Total' => $request->Total,
                    'CreatedBy' => Auth::user()->id,
                    'CreatedTime' => now(),
                ]);
            } else {
                $sql = DB::table('campus_costing')->where('JPId', $request->JPId)->update([

                    'FromDate' => $request->FromDate,
                    'ToDate' => $request->ToDate,
                    'Appeared' => $request->Appeared,
                    'Hired' => $request->Hired,
                    'RT1' => $request->RT1,
                    'RT2' => $request->RT2,
                    'RT3' => $request->RT3,
                    'RT4' => $request->RT4,
                    'AvgCost' => $request->AvgCost,
                    'Total' => $request->Total,
                    'CreatedBy' => Auth::user()->id,
                    'CreatedTime' => now(),
                ]);
            }


            if (!$sql) {
                return response()->json(['status' => 400, 'msg' => 'Something went wrong..!!']);
            } else {
                return response()->json(['status' => 200, 'msg' => 'Data has been successfully created.']);
            }
        }
    }

    public function getCostingDetail(Request $request)
    {
        $data = DB::table('campus_costing')->where('JPId', $request->JPId)->first();
        if ($data != null) {
            return response()->json(['status' => 200, 'data' => $data]);
        } else {
            return response()->json(['status' => 400]);
        }
    }

    public function deleteCampusCandidate(Request $request)
    {
        $JAId = $request->input('JAId');

        // Delete From Trainee Apply Table Then Candidate Table
        $JCId = DB::table('jobapply')->where('JAId', $JAId)->value('JCId');

        if ($JCId) {
            // Delete from jobcandidates
            DB::table('jobcandidates')->where('JCId', $JCId)->delete();

            // Delete from jobapply
            DB::table('jobapply')->where('JAId', $JAId)->delete();

            return response()->json(['status' => 200, 'msg' => 'Candidate Successfully Deleted.']);
        } else {
            return response()->json(['status' => 400, 'msg' => 'Candidate not found.']);
        }
    }
}
