<?php

namespace App\Http\Controllers;

use App\Models\Admin\master_department;
use App\Models\Admin\master_elg;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ElgController extends Controller
{
    public function lodging()
    {
        $department_list = master_department::where('is_active', '1')->pluck("department_name", "id");
        return view('admin.lodging', compact('department_list'));
    }

    public function travel()
    {
        $department_list = master_department::where('is_active', '1')->pluck("department_name", "id");
        return view('admin.travel', compact('department_list'));
    }

    public function getAllLodging(Request $request)
    {
        $usersQuery = master_elg::query();
        $Department = $request->Department;
        if ($Department != '') {
            $usersQuery->where("master_eligibility.DepartmentId", $Department);
        }
        $elg = $usersQuery->select(['master_eligibility.*', 'core_company.company_code', 'core_department.department_code', 'core_vertical.vertical_name'])
            ->leftjoin('core_company', 'master_eligibility.CompanyId', '=', 'core_company.id')
            ->leftjoin('core_department', 'master_eligibility.DepartmentId', '=', 'core_department.id')
            ->leftjoin('core_vertical', 'master_eligibility.VerticalId', '=', 'core_vertical.id')
            ->orderBy('core_department.id', 'ASC')
            ->orderBy('master_eligibility.GradeId', 'ASC');


        return datatables()->of($elg)
            ->addIndexColumn()
            ->make(true);
    }


    public function getAllTravel(Request $request)
    {
        $usersQuery = master_elg::query();
        $Department = $request->Department;
        if ($Department != '') {
            $usersQuery->where("master_eligibility.DepartmentId", $Department);
        }
        $elg = $usersQuery->select(['master_eligibility.*', 'core_company.company_code', 'core_department.department_name', 'core_vertical.vertical_name'])
            ->leftjoin('core_company', 'master_eligibility.CompanyId', '=', 'core_company.id')
            ->leftjoin('core_department', 'master_eligibility.DepartmentId', '=', 'core_department.id')
            ->leftjoin('core_vertical', 'master_eligibility.VerticalId', '=', 'core_vertical.id')
            ->orderBy('core_department.id', 'ASC')
            ->orderBy('master_eligibility.GradeId', 'ASC');


        return datatables()->of($elg)
            ->addIndexColumn()
            ->make(true);
    }

    public function syncELg()
    {
        try {
            // Truncate the tables
            master_elg::truncate();
            DB::table('policy_master')->truncate();

            // Fetch data from the external API
            $response = Http::get('https://vnrseeds.co.in/RcdDetails.php?action=Details&val=elg')->json();

            // Check if the required data is present in the response
            if (!isset($response['eligibility_list'], $response['policy_list'])) {
                return response()->json(['status' => 500, 'msg' => 'Invalid API response.']);
            }

            // Insert eligibility data in chunks
            $eligibilityData = $response['eligibility_list'];
            $policyData = $response['policy_list'];

            $chunkSize = 500; // Adjust chunk size based on database limits

            if (!empty($eligibilityData)) {
                foreach (array_chunk($eligibilityData, $chunkSize) as $chunk) {
                    master_elg::insert($chunk);
                }
            }

            // Insert policy data in chunks
            if (!empty($policyData)) {
                foreach (array_chunk($policyData, $chunkSize) as $chunk) {
                    DB::table('policy_master')->insert($chunk);
                }
            }

            return response()->json(['status' => 200, 'msg' => 'Eligibility data has been synchronized.']);
        } catch (\Exception $e) {
            Log::error('Error in syncELg: ' . $e->getMessage());
            return response()->json(['status' => 500, 'msg' => 'Something went wrong.', 'error' => $e->getMessage()]);
        }
    }
}
