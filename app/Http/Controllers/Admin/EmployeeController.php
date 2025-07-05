<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Admin\master_employee;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use DataTables;

class EmployeeController extends Controller
{

    public function employee()
    {
        return view('admin.employee');
    }

    public function getAllEmployeeData()
    {
            $employee = DB::table('master_employee as e')
                ->join('core_company as c', 'e.CompanyId', '=', 'c.id')
                ->join('master_employee as e1', 'e1.EmployeeID', '=', 'e.RepEmployeeID')
                ->join('core_department as d', 'd.id', '=', 'e.DepartmentId')
                ->leftJoin('core_designation as dg', 'dg.id', '=', 'e.DesigId')
                ->join('core_grade as g', 'g.id', '=', 'e.GradeId')
                ->where('e.CountryId', '=', session('Set_Country'))
                ->select(['e.*', 'e1.Fname as RFname', 'e1.Sname as RSname', 'e1.Lname as RLname', 'c.company_code', 'd.department_name', 'dg.designation_name', 'g.grade_name']);

        return datatables()->of($employee)
            ->addIndexColumn()
            ->addColumn('chk', function () {
                return '<input type="checkbox" class="select_all">';
            })
            ->addColumn('fullname', function ($employee) {
                return $employee->Fname . ' ' . $employee->Sname . ' ' . $employee->Lname;
            })
            ->addColumn('Reporting', function ($employee) {
                return $employee->RFname . ' ' . $employee->RSname . ' ' . $employee->RLname;
            })
            ->rawColumns(['chk'])
            ->make(true);
    }

    public function syncEmployee()
    {

        $query = master_employee::truncate();
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
        $query = master_employee::insert($data);

        $response1 = Http::get('https://vnrseeds.co.in/RcdDetails.php?action=Details&val=pms_hierarchy')->json();
        $data1 = array();
        foreach ($response1['pms_hierarchy'] as $key => $value) {
            $temp1 = array();
            $temp1['Company'] = $value['Company'];
            $temp1['Department'] = $value['Department'];
            $temp1['Employee'] = $value['Employee'];
            $temp1['Reporting'] = $value['Reporting'];
            $temp1['HOD'] = $value['HOD'];
            $temp1['Management'] = $value['Management'];
            array_push($data1, $temp1);
        }
        DB::table('master_employee_hierarchy')->truncate();
        $query1 = DB::table('master_employee_hierarchy')->insert($data1);
        if ($query) {
            return response()->json(['status' => 200, 'msg' => 'Employee data has been Synchronized.']);
        } else {
            return response()->json(['status' => 400, 'msg' => 'Something went wrong..!!']);
        }
    }
}
