<?php

namespace App\Http\Controllers\Hod;

use App\Http\Controllers\Controller;

use App\Models\Admin\master_employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use DataTables;


class MyTeamController extends Controller
{
    function myteam()
    {
        /* $department_list = DB::table('manpowerrequisition')
             ->select('core_department.DepartmentId', 'core_department.department_name', 'core_company.company_code')
             ->join('core_department', 'core_department.DepartmentId', '=', 'manpowerrequisition.DepartmentId')
             ->join('core_company', 'core_company.CompanyId', '=', 'core_department.CompanyId')
             ->where('OnBehalf', Auth::user()->id)
             ->orWhere('manpowerrequisition.CreatedBy', Auth::user()->id)
             ->orderBy('core_department.DepartmentName', 'asc')
             ->orderBy('core_company.company_code', 'asc')
             ->groupBy('master_department.DepartmentId')
             ->get();
         $recruiter_list = DB::table('users')->where('role', 'R')->where('Status', 'A')->pluck('name', 'id');
         $months = [1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April', 5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August', 9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'];*/
        return view('hod.myteam'/*, compact('department_list', 'recruiter_list', 'months')*/);
    }

    function repmrf()
    {
        return view('hod.replacementmrf');
    }

    public function getAllMyTeamMember(Request $request)
    {


        $userQuery = master_employee::query();

        $employee = $userQuery->leftJoin('core_company as c', 'master_employee.CompanyId', '=', 'c.id')
            ->leftJoin('master_employee as e1', 'e1.EmployeeID', '=', 'master_employee.RepEmployeeID')
            ->leftJoin('core_department as d', 'd.id', '=', 'master_employee.DepartmentId')
            ->leftJoin('core_designation as dg', 'dg.id', '=', 'master_employee.DesigId')
            ->leftJoin('core_grade as g', 'g.id', '=', 'master_employee.GradeId')
            ->leftJoin('master_headquater as h', 'h.HqId', '=', 'master_employee.Location')
            ->where('master_employee.RepEmployeeID', Auth::user()->id)
            ->where('master_employee.EmpStatus', 'A')
            ->select([
                'master_employee.*',
                'e1.Fname as RFname',
                'e1.Sname as RSname',
                'e1.Lname as RLname',
                'c.company_code',
                'd.department_name',
                'dg.designation_name',
                'g.grade_name as GradeValue',
                'h.HqName'
            ]);


        return datatables()::of($employee)
            ->addIndexColumn()
            ->addColumn('fullname', function ($employee) {
                //Check if employee has reportee
                $chek_rep = CheckReportee($employee->EmployeeID);
                if ($chek_rep == 1) {
                    return '<a href="javascript:void(0)" data-id="' . $employee->EmployeeID . '" class="getMyTeam">' . $employee->Fname . ' ' . $employee->Sname . ' ' . $employee->Lname . ' <i class="fa fa-plus-circle"></i></a> ';
                } else {
                    return '<a href="javascript:void(0)" data-id="' . $employee->EmployeeID . '" class="getMyTeam">' . $employee->Fname . ' ' . $employee->Sname . ' ' . $employee->Lname . '</a>';
                }

            })
            ->addColumn('chk', function () {
                return '<input type="checkbox" class="select_all">';
            })
            ->addColumn('Reporting', function ($employee) {
                return $employee->RFname . ' ' . $employee->RSname . ' ' . $employee->RLname;
            })
            ->addColumn('MStatus', function ($employee) {
                $check = CheckReplacementMRF($employee->EmployeeID);
                if ($check == 0) {
                    return '<a href="javascript:void(0)" data-id="' . $employee->EmployeeID . '" class="btn btn-sm btn-primary addRepMRF">Raise MRF</a>';
                } else {
                    return 'MRF Submitted';
                }
            })
            ->rawColumns(['chk', 'fullname', 'MStatus'])
            ->make(true);
    }


    public function getMyTeam(Request $request)
    {


        $emp = DB::select("SELECT e.*, e1.Fname AS RFname, e1.Sname AS RSname, e1.Lname AS RLname, c.company_code, d.department_name, dg.designation_name, g.grade_name as GradeValue, h.HqName
                        FROM master_employee AS e
                        LEFT JOIN core_company AS c ON e.CompanyId = c.id
                        LEFT JOIN master_employee AS e1 ON e1.EmployeeID = e.RepEmployeeID
                        LEFT JOIN core_department AS d ON d.id = e.DepartmentId
                        LEFT JOIN core_designation AS dg ON dg.id = e.DesigId
                        LEFT JOIN core_grade AS g ON g.id = e.GradeId
                        LEFT JOIN master_headquater AS h ON h.HqId = e.Location
                        WHERE ((e.EmpStatus = 'D' AND e.DateOfSepration >= '2021-01-01') OR e.EmpStatus = 'A')
                        AND e.RepEmployeeID = " . $request->EmployeeID . "
                        ORDER BY (CASE WHEN EXISTS (SELECT MRFId FROM manpowerrequisition WHERE RepEmployeeID = e.EmployeeID) THEN 1 ELSE 0 END) ASC
                    ");
        return datatables()::of($emp)
            ->addIndexColumn()
            ->addColumn('fullname', function ($emp) {
                return $emp->Fname . ' ' . $emp->Sname . ' ' . $emp->Lname;
            })
            ->addColumn('chk1', function () {
                return '<input type="checkbox" class="select_all">';
            })
            ->addColumn('Reporting', function ($emp) {
                return $emp->RFname . ' ' . $emp->RSname . ' ' . $emp->RLname;
            })
            ->addColumn('Status', function ($emp) {
                if ($emp->EmpStatus == 'A') {
                    return 'Active';
                } elseif ($emp->EmpStatus == 'D') {
                    return 'Resigned';
                } elseif ($emp->EmpStatus == 'De') {
                    return 'Resigned';
                }
            })
            ->addColumn('MStatus', function ($emp) {

                $check = CheckReplacementMRF($emp->EmployeeID);
                if ($check == 0) {
                    return '<a href="javascript:void(0)" data-id="' . $emp->EmployeeID . '" class="btn btn-sm btn-primary addRepMRF">Raise MRF</a>';
                } else {
                    return 'MRF Submitted';
                }

            })
            ->rawColumns(['chk1', 'fullname', 'MStatus'])
            ->make(true);
    }


    function my_resigned_team()
    {
        return view('hod.my_resigned_team');
    }

    public function getResignedMember(Request $request)
    {
        $status = $request->Status;

        $userQuery = master_employee::query();

        $employee = $userQuery->leftJoin('core_company as c', 'master_employee.CompanyId', '=', 'c.id')
            ->leftJoin('master_employee as e1', 'e1.EmployeeID', '=', 'master_employee.RepEmployeeID')
            ->leftJoin('core_department as d', 'd.id', '=', 'master_employee.DepartmentId')
            ->leftJoin('core_designation as dg', 'dg.id', '=', 'master_employee.DesigId')
            ->leftJoin('core_grade as g', 'g.id', '=', 'master_employee.GradeId')
            ->leftJoin('master_headquater as h', 'h.HqId', '=', 'master_employee.Location')
            ->where('master_employee.RepEmployeeID', Auth::user()->id)
            ->where('master_employee.EmpStatus', 'D')
            ->where('master_employee.DateOfSepration', '>=', '2021-01-01')
            ->select([
                'master_employee.*',
                'e1.Fname as RFname',
                'e1.Sname as RSname',
                'e1.Lname as RLname',
                'c.company_code',
                'd.department_name',
                'dg.designation_name',
                'g.grade_name as GradeValue',
                'h.HqName'
            ]);

        /*        if ($status == 'A') {
                    $userQuery->where('master_employee.EmpStatus', 'A');
                } elseif ($status == 'D') {
                    $userQuery->where('master_employee.EmpStatus', 'D')
                        ->where('master_employee.DateOfSepration', '>=', '2021-01-01');
                } else {
                    $userQuery->where(function ($query) {
                        $query->where(function ($query) {
                            $query->where('master_employee.EmpStatus', 'D')
                                ->where('master_employee.DateOfSepration', '>=', '2021-01-01');
                        })->orWhere('master_employee.EmpStatus', 'A');
                    });
                }*/

        return datatables()::of($employee)
            ->addIndexColumn()
            ->addColumn('fullname', function ($employee) {
                /* //Check if employee has reportee
                 $chek_rep = CheckReportee($employee->EmployeeID);
                 if ($chek_rep == 1) {
                     return '<a href="javascript:void(0)" data-id="' . $employee->EmployeeID . '" class="getMyTeam">' . $employee->Fname . ' ' . $employee->Sname . ' ' . $employee->Lname . ' <i class="fa fa-plus-circle"></i></a> ';
                 } else {*/
                return '<a href="javascript:void(0)" data-id="' . $employee->EmployeeID . '" class="getMyTeam">' . $employee->Fname . ' ' . $employee->Sname . ' ' . $employee->Lname . '</a>';
                /*   }*/

            })
            ->addColumn('chk', function () {
                return '<input type="checkbox" class="select_all">';
            })
            ->addColumn('Reporting', function ($employee) {
                return $employee->RFname . ' ' . $employee->RSname . ' ' . $employee->RLname;
            })
            ->addColumn('Status', function ($employee) {
                if ($employee->EmpStatus == 'A' || $employee->EmpStatus == 'De') {
                    return 'Active';
                } elseif ($employee->EmpStatus == 'D') {
                    return 'Resigned';
                }
            })
            ->addColumn('MStatus', function ($employee) {
                $check = CheckReplacementMRF($employee->EmployeeID);
                if ($check == 0) {
                    return '<a href="javascript:void(0)" data-id="' . $employee->EmployeeID . '" class="btn btn-sm btn-primary addRepMRF">Raise MRF</a>';
                } else {
                    return 'MRF Submitted';
                }
            })
            ->rawColumns(['chk', 'fullname', 'MStatus'])
            ->make(true);
    }
}
