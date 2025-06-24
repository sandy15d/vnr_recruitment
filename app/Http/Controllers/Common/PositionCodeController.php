<?php

namespace App\Http\Controllers\Common;

use App\Models\PositionCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Admin\master_company;
use Illuminate\Support\Facades\Http;
use App\Models\Admin\master_employee;

use App\Models\Admin\master_designation;


class PositionCodeController extends Controller
{
    public function show_position_code()
    {
        $company_list = master_company::pluck("company_code", "id");
        return view('common.position_code', compact('company_list'));
    }

    public function SyncPositionCode()
    {
        ini_set('max_execution_time', '0');
        $query =  PositionCode::truncate();
        $response = Http::get('https://vnrseeds.co.in/RcdDetails.php?action=Details&val=getPositionCode')->json();
        $data = array();
        foreach ($response['PositionCode_List'] as $key => $value) {
            $temp = array();
            $temp['employee_id'] = $value['EmployeeID'];
            $temp['emp_code'] = $value['EmpCode'];
            $temp['company_id'] = $value['CompanyId'];
            $temp['department_id'] = $value['DepartmentId'];
            $temp['designation_id'] = $value['DesigId'];
            $temp['grade_id'] = $value['GradeId'];
            $temp['vertical'] = $value['PosVR'];
            $temp['position_code'] = $value['PositionCode'];
            $temp['sequence'] = $value['PosSeq'];


            array_push($data, $temp);
        }
        $query = PositionCode::insert($data);

        $query1 =  master_employee::truncate();
        $response = Http::get('https://vnrseeds.co.in/RcdDetails.php?action=Details&val=Employee')->json();
        $data = array();
        foreach ($response['employee_list'] as $key => $value) {
            if ($value['DateJoining'] == '0000-00-00' or $value['DateJoining'] == '') {
                $value['DateJoining'] = NULL;
            }
            if ($value['DateOfSepration'] == '0000-00-00' or $value['DateOfSepration'] == '') {
                $value['DateOfSepration'] = NULL;
            }
            $temp = array();
            $temp['EmployeeID'] = $value['EmployeeID'];
            $temp['EmpCode'] = $value['EmpCode'];
            $temp['EmpStatus'] = $value['EmpStatus'];
            $temp['Fname'] = $value['Fname'];
            $temp['Sname'] = $value['Sname'];
            $temp['Lname'] = $value['Lname'];
            $temp['CompanyId'] = $value['CompanyId'];
            $temp['GradeId'] = $value['GradeId'];
            $temp['DepartmentId'] = $value['DepartmentId'];
            $temp['DesigId'] = $value['DesigId'];
            $temp['RepEmployeeID'] = $value['RepEmployeeID'];
            $temp['DOJ'] = $value['DateJoining'];
            $temp['DateOfSepration'] = $value['DateOfSepration'];
            $temp['Contact'] = $value['Contact'];
            $temp['Email'] = $value['Email'];
            $temp['Gender'] = $value['Gender'];
            $temp['Married'] = $value['Married'];
            $temp['DR'] = $value['DR'];
            $temp['Location'] = $value['HqId'];
            $temp['CTC'] = $value['Tot_CTC'];
            $temp['Title'] = $value['Title'];
            $temp['CountryId'] = 1;
            array_push($data, $temp);
        }
        $query1 = master_employee::insert($data);

        if ($query1) {
            return response()->json(['status' => 200, 'msg' => 'Position Code data has been Synchronized.']);
        } else {
            return response()->json(['status' => 400, 'msg' => 'Something went wrong..!!']);
        }
    }

    public function show_all_position_code(Request $request)
    {

        $userQuery = PositionCode::query();
        $Company = $request->Company;
        $Department = $request->Department;
        $Designation = $request->Designation;
        if ($Company != '') {

            $userQuery->where("master_position_code.company_id", $Company);
        }
        if ($Department != '') {
            $userQuery->where("master_position_code.department_id", $Department);
        }
        if ($Designation != '') {
            $userQuery->where("master_position_code.designation_id", $Designation);
        }
        if ($request->Status != '') {
            $userQuery->where("master_employee.EmpStatus", $request->Status);
        }

        $sql = $userQuery->select('master_position_code.*', 'master_employee.Fname', 'master_employee.EmpStatus')->join('master_employee', 'master_employee.EmployeeID', '=', 'master_position_code.employee_id')->orderBy('department_id', 'asc')->get();
        return datatables()->of($sql)
            ->addIndexColumn()
            ->addColumn('fullname', function ($sql) {
                return getFullName($sql->employee_id);
            })
            ->addColumn('Company', function ($sql) {
                return getcompany_code($sql->company_id);
            })
            ->addColumn('Department', function ($sql) {
                return getDepartmentCode($sql->department_id);
            })
            ->addColumn('Designation', function ($sql) {
                return getDesignationCode($sql->designation_id);
            })
            ->addColumn('Grade', function ($sql) {
                return getGradeValue($sql->grade_id);
            })
            ->make(true);
    }

    public function unused_position_code(Request $request)
    {

        $userQuery = DB::table('position_codes');
        $Company = $request->Company;
        $Department = $request->Department;
        $Designation = $request->Designation;
        if ($Company != '') {

            $userQuery->where("company_id", $Company);
        }
        if ($Department != '') {
            $userQuery->where("department_id", $Department);
        }
        if ($Designation != '') {
            $userQuery->where("designation_id", $Designation);
        }


        $sql = $userQuery->select('*')->where('is_available', 'Yes')->orderBy('department_id', 'asc')->get();
        return datatables()->of($sql)
            ->addIndexColumn()

            ->addColumn('Company', function ($sql) {
                return getcompany_code($sql->company_id);
            })
            ->addColumn('Department', function ($sql) {
                return getDepartmentCode($sql->department_id);
            })
            ->addColumn('Designation', function ($sql) {
                return getDesignationCode($sql->designation_id);
            })
            ->addColumn('Grade', function ($sql) {
                return getGradeValue($sql->grade_id);
            })
            ->make(true);
    }

    public function add_position_code(Request $request)
    {

        $Company = $request->Company;
        $Department = $request->Department;
        $Designation = $request->Designation;
        $Grade = $request->Grade;
        $Vertical = $request->Vertical;
        $ShortCode = master_designation::find($Designation)->Desig_ShortCode;
        if ($ShortCode) {
            $check = DB::table('position_codes')->where('company_id', $Company)->where('department_id', $Department)->where('designation_id', $Designation)->where('vertical', $Vertical)->first();

            if ($check) {
                $max_seq = DB::table('position_codes')->where('company_id', $Company)->where('department_id', $Department)->where('vertical', $Vertical)->max('sequence');
                $seq = $max_seq + 1;
                $query = DB::table('position_codes')->insert([
                    'company_id' => $Company,
                    'department_id' => $Department,
                    'designation_id' => $Designation,
                    'grade_id' => $Grade,
                    'vertical' => $Vertical,
                    'position_code' => getDepartmentShortCode($Department) . '_' . $Vertical . '_' . $ShortCode . '_' . $seq,
                    'sequence' => $seq,
                    'is_available' => 'Yes',
                    'created_at' => date('Y-m-d H:i:s'),
                ]);
            } else {

                $seq = 1;
                $query = DB::table('position_codes')->insert([
                    'company_id' => $request->Company,
                    'department_id' => $request->Department,
                    'designation_id' => $request->Designation,
                    'grade_id' => $request->Grade,
                    'vertical' => $request->Vertical,
                    'position_code' => getDepartmentCode($Department) . '_' . $Vertical . '_' . $ShortCode . '_' . $seq,
                    'sequence' => 1,
                    'is_available' => 'Yes',
                    'created_at' => date('Y-m-d H:i:s'),
                ]);
            }
            if ($query) {
                return response()->json(['status' => 200, 'msg' => 'Position Code has been added.']);
            } else {
                return response()->json(['status' => 400, 'msg' => 'Something went wrong..!!']);
            }
        } else {
            return response()->json(['status' => 400, 'msg' => 'Designation Short Code not found..!!']);
        }
    }
}
