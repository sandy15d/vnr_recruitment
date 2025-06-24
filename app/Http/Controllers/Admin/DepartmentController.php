<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Admin\master_department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use DataTables;
use Illuminate\Support\Facades\Log;

class DepartmentController extends Controller
{
    public function department()
    {
        return view('admin.department');
    }

    public function getAllDepartment()
    {
        $department = master_department::where('is_active', '1');
        return datatables()->of($department)
            ->addIndexColumn()
            ->make(true);
    }
    public function getSubDepartmentByDepartment(Request $request)
    {
        // Validate that 'Department' is provided in the request
        $department = $request->input('Department');
        if (!$department) {
            return response()->json(['error' => 'Department is required.'], 400);
        }

        // Retrieve the 'fun_vertical_dept_id' for the given department
        $fun_vertical_dept_ids = DB::table('core_fun_vertical_dept_mapping')
            ->where('department_id', $department)
            ->pluck('id');

        // If no department mappings found, return an empty array or handle it
        if ($fun_vertical_dept_ids->isEmpty()) {
            return response()->json(['sub_departments' => []]);
        }

        // Retrieve distinct sub_departments related to the found fun_vertical_dept_ids
        $sub_departments_ids = DB::table('core_department_subdepartment_mapping')
            ->whereIn('fun_vertical_dept_id', $fun_vertical_dept_ids)
            ->distinct()
            ->pluck('sub_department_id');
        $sub_departments = DB::table('core_sub_department')->whereIn('id', $sub_departments_ids)->select(['id', 'sub_department_name'])->get();
        // Return the sub_departments as a response
        return response()->json(['sub_departments' => $sub_departments]);
    }

}
