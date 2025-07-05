<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\master_designation;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use DataTables;

class DesignationController extends Controller
{
    public function designation()
    {
        return view('admin.designation');
    }

    public function getAllDesignation()
    {


        $designation = DB::table('master_grade_designation')
            ->select('core_designation.designation_name', 'core_designation.designation_code', 'core_department.department_name', 'minimum_wage_category.Category')
            ->leftJoin('core_department','core_department.id','=','master_grade_designation.department_id')
            ->leftJoin('core_designation','core_designation.id','=','master_grade_designation.designation_id')
            ->leftJoin('minimum_wage_category','minimum_wage_category.Id','=','master_grade_designation.mw')
            ->where('company_id',session('Set_Company'))
            ->whereNotNull('core_designation.designation_name');

        return datatables()->of($designation)
            ->addIndexColumn()
            ->make(true);
    }

    public function syncDesignation()
    {

     /*   $query = master_designation::truncate();
        $response = Http::get('https://vnrseeds.co.in/RcdDetails.php?action=Details&val=Designation')->json();
        $data = array();
        foreach ($response['Designation_list'] as $key => $value) {

            $temp = array();
            $temp['DesigId'] = $value['DesigId'];
            $temp['DesigName'] = $value['DesigName'];
            $temp['DesigCode'] = $value['DesigCode'];
            $temp['Desig_ShortCode'] = $value['Desig_ShortCode'];
            $temp['CompanyId'] = $value['CompanyId'];
            $temp['DesigStatus'] = $value['DesigStatus'];
            array_push($data, $temp);
        }
        $query = master_designation::insert($data);
        $response1 = Http::get('https://vnrseeds.co.in/RcdDetails.php?action=Details&val=DeptDesig')->json();

        foreach ($response1['Department_Designation_list'] as $key => $value) {
            $data1 = DB::table('master_designation')
                ->where('DesigId', $value['DesigId'])
                ->update(['DepartmentId' => $value['DepartmentId']]);
        }*/

        $query2 = DB::table('master_grade_designation')->truncate();
        $response2 = Http::get('https://vnrseeds.co.in/RcdDetails.php?action=Details&val=grade_desig')->json();
        $data2 = array();
        foreach ($response2['grade_designation_list'] as $key => $value) {

            $temp2 = array();
            $temp2['department_id'] = $value['DepartmentId'];
            $temp2['designation_id'] = $value['DesigId'];
            $temp2['company_id'] = $value['CompanyId'];
            $temp2['grade_1'] = $value['GradeId'];
            $temp2['grade_2'] = $value['GradeId_2'];
            $temp2['grade_3'] = $value['GradeId_3'];
            $temp2['grade_4'] = $value['GradeId_4'];
            $temp2['grade_5'] = $value['GradeId_5'];
            $temp2['status'] = $value['DGDStatus'];
            $temp2['mw'] = $value['MW'];
            array_push($data2, $temp2);
        }
        $query2 = DB::table('master_grade_designation')->insert($data2);
        if ($query2) {
            return response()->json(['status' => 200, 'msg' => 'Designation data has been Synchronized.']);
        } else {
            return response()->json(['status' => 500, 'msg' => 'Something went wrong..!!']);
        }
    }
}
