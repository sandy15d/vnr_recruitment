<?php

namespace App\Http\Controllers\Recruiter;

use App\Helpers\LogActivity;
use App\Helpers\UserNotification;
use App\Http\Controllers\Controller;
use App\Models\master_mrf;
use App\Models\Recruiter\master_post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Validator;

class MrfAllocatedController extends Controller
{
    function mrf_allocated()
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

        $CloseActive = DB::table('manpowerrequisition')
            /*  ->where('Type', '!=', 'Campus')
             ->where('Type', '!=', 'Campus_HrManual')
             ->where('Type', '!=', 'SIP')
             ->where('Type', '!=', 'SIP_HrManual') */
            ->where('CountryId', session('Set_Country'))
            ->where('Allocated', Auth::user()->id)
            ->where('Status', 'Close')
            ->get();
        $CloseMRF = $CloseActive->count();

        $OpenMRFSQL = DB::table('manpowerrequisition')
            /*    ->where('Type', '!=', 'Campus')
               ->where('Type', '!=', 'Campus_HrManual')
               ->where('Type', '!=', 'SIP')
               ->where('Type', '!=', 'SIP_HrManual') */
            ->where('CountryId', session('Set_Country'))
            ->where('Allocated', Auth::user()->id)
            ->where('Status', '!=', 'Close')
            ->get();
        $OpenMRF = $OpenMRFSQL->count();
        return view('recruiter.mrf_allocated', compact('company_list', 'department_list', 'state_list', 'institute_list', 'designation_list', 'employee_list', 'months', 'CloseMRF', 'OpenMRF'));
    }


   
    function getAllAllocatedMRF(Request $request)
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

        if ($request->MrfStatus == 'Open') {
            $usersQuery->where('manpowerrequisition.Status', '!=', 'Close');
        } else {
            $usersQuery->where('manpowerrequisition.Status', 'Close');
        }

        $mrf = $usersQuery->select('manpowerrequisition.*', 'core_department.department_name', 'core_designation.designation_name', 'jobpost.Title', DB::raw('COALESCE(SUM(mrf_position_filled.Filled), 0) as total_filled'))
            ->Join('core_designation', 'manpowerrequisition.DesigId', '=', 'core_designation.id', 'left')
            ->Join('core_department', 'manpowerrequisition.DepartmentId', '=', 'core_department.id')
            ->leftJoin('jobpost', 'manpowerrequisition.MRFId', '=', 'jobpost.MRFId')
            ->leftJoin('mrf_position_filled', 'mrf_position_filled.MRFId', '=', 'manpowerrequisition.MRFId')
            ->where('Allocated', Auth::user()->id)
            ->where('CountryId', session('Set_Country'))
            ->groupBy('manpowerrequisition.MRFId');


        return datatables()->of($mrf)
            ->addIndexColumn()
            ->addColumn('chk', function () {
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
            ->editColumn('LocationIds', function ($mrf) {
                $location = DB::table('mrf_location_position')->where('MRFId', $mrf->MRFId)->get()->toArray();
                $loc = '';
                foreach ($location as $key => $value) {
                    $loc .= getDistrictName($value->City) . ' ';
                    $loc .= getStateCode($value->State) . ' - ';
                    $loc .= $value->Nop;
                    $loc . '<br>';
                }
                return $loc;

            })
            ->addColumn('JobPost', function ($mrf) {
                if ($mrf->Type == 'Campus' || $mrf->Type == 'Campus_HrManual' || $mrf->Type == 'SIP' || $mrf->Type == 'SIP_HrManual') {
                    return '-';
                } else {
                    $check = CheckJobPostCreated($mrf->MRFId);
                    if ($check == 1) {
                        return 'Created  <a  href="javascript:void(0);" data-bs-toggle="modal"
                    data-bs-target="#updatepostmodal" onclick="getDetailForJobPost(' . $mrf->MRFId . ')"><i class="fa fa-pencil-square-o text-primary d-inline" style="font-size: 16px;cursor: pointer;"></i></a>';
                    } else {
                        return '<a  href="javascript:void(0);" data-bs-toggle="modal"
                    data-bs-target="#createpostmodal" onclick="getDetailForJobPost(' . $mrf->MRFId . ')"><i class="fa fa-plus-square-o"></i>Create</a>';
                    }
                }
            })
            ->addColumn('JobShow', function ($mrf) {
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

                    $x .= '</select> <i class="fa fa-pencil-square-o text-primary d-inline" aria-hidden="true" id="mrfedit' . $mrf->MRFId . '" onclick="editmrf(' . $mrf->MRFId . ')" style="font-size: 16px;cursor: pointer;"></i>';
                    return $x;
                } else {
                    return '';
                }
            })
            ->addColumn('position_filled', function ($mrf) {

                return "<a href='javascript:void(0);' data-bs-toggle='modal'
            data-bs-target='#mrf_position_modal' style='font-size: 16px;'
            onclick='getPositionFilled(\"$mrf->MRFId\",\"$mrf->JobCode\");'>$mrf->total_filled</a>";

            })
            ->addColumn('Action', function ($mrf) {
                $x = '';
                $x .= '<i  class="fadeIn animated lni lni-eye  text-primary view" aria-hidden="true" data-id="' . $mrf->MRFId . '" id="viewMRF" title="View MRF" style="font-size: 18px;cursor: pointer;"></i> ';
                if ($mrf->Status == 'Approved') {
                    $x .= '   <i  class="fadeIn animated bx bx-window-close  text-danger closemrf" aria-hidden="true" data-id="' . $mrf->MRFId . '" id="closemrf"  style="font-size: 18px;cursor: pointer;" title="Close MRF"></i>';
                }
                return $x;
            })
            ->addColumn('MRFDate', function ($mrf) {
              return date('d-m-Y', strtotime($mrf->CreatedTime));
            })
            ->addColumn('CloseDt', function ($mrf) {
                if ($mrf->CloseDt != null) {
                    return date('d-m-Y', strtotime($mrf->CloseDt));
                } else {
                    return '';
                }
              
              })
            ->rawColumns(['chk', 'Action', 'JobShow', 'JobPost', 'LocationIds', 'position_filled'])
            ->make(true);
    }

    public function getDetailForJobPost(Request $request)
    {
        $MRFId = $request->MRFId;
        $MRFDetails = master_mrf::find($MRFId);
        $job_post = DB::table('jobpost')->where('MRFId', $MRFId)->value('Title');
        $KPDetail = unserialize($MRFDetails->KeyPositionCriteria);
        $Designation = $MRFDetails['DesigId'] == 0 ? 'SIP Trainee' : getDesignationCode($MRFDetails['DesigId']);
        $EducationDetail = unserialize($MRFDetails->EducationId);
        $LocationDetail = unserialize($MRFDetails->LocationIds);
        return response()->json(['MRFDetails' => $MRFDetails, 'KPDetails' => $KPDetail, 'Designation' => $Designation, 'LocationDetails' => $LocationDetail, 'EducationDetails' => $EducationDetail, 'jobTitle' => $job_post]);
    }
    public function updateJobPost(Request $request)
    {

        $MRFId = $request->MRFId;
        $KeyPosition = $request->KeyPosition;
        $JobCode = $request->JobCode;
        $Title = $request->jobTitle;
        $JPId = DB::table('jobpost')->where('MRFId', $MRFId)->value('JPId');

        // if ($JPId) {
        //     echo "JPId is: " . $JPId; 
        // } else {
        //     echo "JobPost nahi mila is MRFId ke liye.";
        // }
        $KpArray = array();
        if (!empty($KeyPosition)) {
            foreach ($KeyPosition as $KP) {
                $KpArray[] = addslashes($KP);
            }
        }
        $KpArray_str = serialize($KpArray);

        $MRFDetails = master_mrf::find($MRFId);
        if (!$MRFDetails) {
            return response()->json(['status' => 404, 'msg' => 'MRF not found']);
        }

        $Company = $MRFDetails['CompanyId'];
        $Department = $MRFDetails['DepartmentId'];
        $Desig = $MRFDetails['DesigId'] == 0 ? 0 : $MRFDetails['DesigId'];
        $Education = $MRFDetails['EducationId'];
        $Location = $MRFDetails['Location'];
        $Status = 'Open';

        $type = ($MRFDetails['Type'] == 'SIP_HrManual' || $MRFDetails['Type'] == 'SIP') ? 'SIP' : 'Regular';

        $SQL = master_post::find($JPId);
        if (!$SQL) {
            return response()->json(['status' => 404, 'msg' => 'Job Post not found']);
        }

        $SQL->MRFId = $MRFId;
        $SQL->CompanyId = $Company;
        $SQL->DepartmentId = $Department;
        $SQL->DesigId = $Desig;
        $SQL->JobCode = $JobCode;
        $SQL->Title = $Title;
        $SQL->ReqQualification = $Education;
        $SQL->Description = convertData($request->JobInfo);
        $SQL->Location = $Location;
        $SQL->KeyPositionCriteria = $KpArray_str;
        $SQL->JobPostType = $type;
        $SQL->Status = $Status;
        $SQL->UpdatedBy = Auth::user()->id;
        $SQL->LastUpdated = now();
        $query = $SQL->save();

        // Update MRF table as well
        $sql1 = master_mrf::find($MRFId);
        $sql1->info = convertData($request->JobInfo);
        $sql1->KeyPositionCriteria = $KpArray_str;
        $sql1->UpdatedBy = Auth::user()->id;
        $sql1->LastUpdated = now();
        $query2 = $sql1->save();

        if (!$query || !$query2) {
            return response()->json(['status' => 400, 'msg' => 'Something went wrong while updating..!!']);
        } else {
            LogActivity::addToLog('JobPost ' . $JobCode . ' is updated by ' . getFullName(Auth::user()->id), 'Update');
            UserNotification::notifyUser(1, 'Job Post Updated', $JobCode);
            return response()->json(['status' => 200, 'msg' => 'JobPost has been successfully updated.']);
        }
    }

    public function createJobPost(Request $request)
    {

        $MRFId = $request->MRFId;
        $KeyPosition = $request->KeyPosition;
        $JobCode = $request->JobCode;
        $Title = $request->jobTitle;
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

        $Desig = $MRFDetails['DesigId'] == 0 ? 0 : $MRFDetails['DesigId'];

        $Education = $MRFDetails['EducationId'];
        $Location = $MRFDetails['Location'];
        $Status = 'Open';

        if ($MRFDetails['Type'] == 'SIP_HrManual' || $MRFDetails['Type'] == 'SIP') {
            $type = 'SIP';
        } else {
            $type = 'Regular';
        }


        $SQL = new master_post;
        $SQL->MRFId = $MRFId;
        $SQL->CompanyId = $Company;
        $SQL->DepartmentId = $Department;
        $SQL->DesigId = $Desig;
        $SQL->JobCode = $JobCode;
        $SQL->Title = $Title;
        $SQL->ReqQualification = $Education;
        $SQL->Description = convertData($request->JobInfo);
        $SQL->Location = $Location;
        $SQL->KeyPositionCriteria = $KpArray_str;
        $SQL->PostingView = 'Hidden';
        $SQL->JobPostType = $type;
        $SQL->Status = $Status;
        $SQL->CreatedBy = Auth::user()->id;
        $query = $SQL->save();


        $sql1 = master_mrf::find($MRFId);
        $sql1->info = convertData($request->JobInfo);
        $sql1->KeyPositionCriteria = $KpArray_str;
        $sql1->UpdatedBy = Auth::user()->id;
        $sql1->LastUpdated = now();
        $query = $sql1->save();

        if (!$query) {
            return response()->json(['status' => 400, 'msg' => 'Something went wrong..!!']);
        } else {
            LogActivity::addToLog('New JobPost ' . $JobCode . ' is created by ' . getFullName(Auth::user()->id), 'Create');
            UserNotification::notifyUser(1, 'Job Post Create', $JobCode);
            return response()->json(['status' => 200, 'msg' => 'New JobPost has been successfully created.']);
        }
    }


    public function ChngPostingView(Request $request)
    {
        $SQL = master_post::find($request->JPId);
        $SQL->PostingView = $request->va;
        $SQL->UpdatedBy = Auth::user()->id;
        $SQL->LastUpdated = now();
        $query = $SQL->save();
        if (!$query) {
            return response()->json(['status' => 400, 'msg' => 'Something went wrong..!!']);
        } else {
            $jobCode = $SQL->JobCode;
            LogActivity::addToLog('Job Posting ' . $jobCode . ' is now ' . $request->va . ' in Ess/Site', 'Update');
            return response()->json(['status' => 200, 'msg' => 'Job Posting Viewing Status has been changed successfully.']);
        }
    }

    public function close_mrf(Request $request)
    {
        $MRFId = $request->MrId;
        $validator = Validator::make($request->all(), [
            'hired' => 'required',
            'reason' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 400, 'error' => $validator->errors()->toArray()]);
        } else {

            $mrf = master_mrf::find($MRFId);
            $JobCode = $mrf->JobCode;
            $mrf->Status = 'Close';
            $mrf->CloseDt = now();
            $mrf->CloseReason = $request->reason;
            $mrf->Hired = $request->hired;
            $mrf->UpdatedBy = Auth::user()->id;
            $mrf->LastUpdated = now();
            $mrf->save();
            $jobpost = master_post::where('MRFId', $MRFId)->first();
            $jobpost->Status = 'Close';
            $jobpost->PostingView = 'Hidden';
            $jobpost->UpdatedBy = Auth::user()->id;
            $jobpost->LastUpdated = now();
            $jobpost->save();
            if (!$mrf) {
                return response()->json(['status' => 400, 'msg' => 'Something went wrong..!!']);
            } else {
                LogActivity::addToLog('MRF ' . $JobCode . ' is closed by ' . getFullName(Auth::user()->id), 'Update');
                return response()->json(['status' => 200, 'msg' => 'MRF is closed successfully.']);
            }
        }
    }

    public function get_mrf_position_filled_detail(Request $request)
    {
        $MRFId = $request->MRFId;
        $result = DB::table('mrf_position_filled')->where('MRFId', $MRFId)->get();

        $html = '';
        $index = 1;
        foreach ($result as $row) {
            $html .= '<tr>';
            $html .= '<td class="text-center">' . $index++ . '</td>';
            $html .= '<td class="text-center">' . date('d-m-Y', strtotime($row->FilledDate)) . '</td>';
            $html .= '<td class="text-center">' . htmlspecialchars($row->Filled) . '</td>';
            $html .= '<td>' . htmlspecialchars($row->Remark) . '</td>';
            $html .= '</tr>';
        }

        return response()->json(['html' => $html]);
    }

    public function save_mrf_position_filled(Request $request)
    {

        $MRFId = $request->MRFId;
        $Filled = $request->Filled;
        $Remark = $request->Remark;

        $query = DB::table('mrf_position_filled')->insert([
            'MRFId' => $MRFId,
            'Filled' => $Filled,
            'Remark' => $Remark,
            'FilledDate' => now(),
        ]);
        if (!$query) {
            return response()->json(['status' => 400, 'msg' => 'Something went wrong..!!']);
        }
        return response()->json(['status' => 200]);
    }
}
