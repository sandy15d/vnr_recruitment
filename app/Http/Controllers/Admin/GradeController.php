<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Admin\master_grade;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use DataTables;

class GradeController extends Controller
{
    public function grade()
    {
        return view('admin.grade');
    }

    public function getAllGrade()
    {
        $grade = DB::table('core_grade')
            ->join('core_company', 'core_grade.company_id', '=', 'core_company.id')
            ->where('core_company.id', '=', session('Set_Company'))
            ->select(['core_grade.*', 'core_company.company_code'])
            ->orderBy('core_grade.is_active', 'desc')
            ->get();

        return datatables()->of($grade)
            ->addIndexColumn()
            ->addColumn('status', function ($grade) {
                if ($grade->is_active == 1) {
                    return '<span class="badge badge-success">Active</span>';
                } else {
                    return '<span class="badge badge-danger">Inactive</span>';
                }
            })
            ->rawColumns(['status'])
            ->make(true);
    }
}
