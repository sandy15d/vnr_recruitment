<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Mail\WishingMail;
use App\Models\jobcandidate;
use App\Models\jobpost;
use App\Models\master_mrf;
use App\Models\User;
use App\Spotlight\ManpowerRequisition;
use Citco\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use ZipArchive;

class TestController extends Controller
{
    /*
    // Fetch the 'LocationIds' and 'MRFId' columns from the manpowerrequisition table
    public function index()
    {

        $locationIds = DB::table('manpowerrequisition')->pluck('LocationIds', 'MRFId')->toArray(); // Convert collection to array

        // Use array_map to process each locationId with its corresponding MRFId
        $data = array_map(function ($locationId, $mrfId) {
            // Attempt to unserialize the data safely
            $records = @unserialize($locationId);

            // Check if unserialization failed, indicating corrupted data
            if ($records === false && $locationId !== serialize(false)) {
                // Log the error for debugging purposes
                \Log::error("Failed to unserialize data for MRFId: $mrfId with data: $locationId");
                return null; // Return null to indicate an unserialization failure
            }

            // Return the MRFId and the unserialized records
            return ['MRFId' => $mrfId, 'records' => $records];
        }, $locationIds, array_keys($locationIds));

        // Filter out null entries (unsuccessful unserializations)
        $data = array_filter($data);
        // Insert the unserialized data into the mrf_location_position table
        foreach ($data as $item) {
            $mrfId = $item['MRFId'];
            $records = $item['records'];
            // Check if records are an array of multiple entries
            if (is_array($records) && !empty($records)) {
                foreach ($records as $record) {
                    // Ensure the required fields exist in each record
                    if (isset($record['state'], $record['city'], $record['nop'])) {
                        // Insert the data into mrf_location_position table
                        DB::table('mrf_location_position')->insert([
                            'MRFId' => $mrfId,
                            'State' => $record['state'],
                            'City'  => $record['city'],
                            'Nop'   => $record['nop'],
                        ]);
                    } else {
                        \Log::warning("Missing required fields in records for MRFId: $mrfId");
                    }
                }
            } else {
                \Log::warning("Records for MRFId: $mrfId are not properly structured as an array.");
            }
        }
    }
    */


    public function core_department_map()
    {
        $old_department_list = DB::table('master_department')
            ->leftJoin('core_company', 'core_company.id', '=', 'master_department.CompanyId')
            ->select(['master_department.*', 'core_company.company_name'])
            ->orderBy('core_company.company_name')
            ->get();
        $new_department_list = DB::table('core_department')->orderBy('department_name')->get();
        // Fetch departments mapping
        $mapped_departments = DB::table('department_map_master')
            ->select('New', 'Old', 'Sub')
            ->get()
            ->keyBy('Old'); // Using 'Old' as the key for easy lookup


        $sub_department_list = DB::table('core_sub_department')->orderBy('sub_department_name')->get();
        return view("mapping.department_map", compact('old_department_list', 'new_department_list', 'mapped_departments', 'sub_department_list'));
    }


    function mapCoreDepartment(Request $request)
    {
        try {
            $condition = [
                'Old' => $request->Old,
                'CompanyId' => $request->CompanyId,
            ];

            $dataToUpdate = [
                'New' => $request->New,
                'Sub' => $request->Sub,
            ];

            $success = DB::table('department_map_master')->updateOrInsert($condition, array_merge($condition, $dataToUpdate));
            if ($success) {
                return response()->json(['status' => 200, 'msg' => 'Department map updated successfully.']);
            } else {
                return response()->json(['status' => 500, 'msg' => 'Failed to update the department map.']);
            }
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'msg' => 'An error occurred: ' . $e->getMessage()]);
        }
    }

    public function core_designation_map()
    {
        $old_designation_list = DB::table('master_designation')
            ->leftJoin('core_company', 'core_company.id', '=', 'master_designation.CompanyId')
            ->leftJoin('master_department', 'master_department.DepartmentId', '=', 'master_designation.DepartmentId')
            ->select(['master_designation.*', 'core_company.company_code', 'master_department.DepartmentName'])
            ->where('DesigStatus', 'A')
            ->orderBy('core_company.company_code')
            ->OrderBy('DesigName')->paginate(25);
        $new_designation_list = DB::table('core_designation')->OrderBy('designation_name')->get();
        $mapped_designations = DB::table('designation_map_master')
            ->select('New', 'Old')
            ->get()
            ->keyBy('Old');

        return view('mapping.designation_map', compact('old_designation_list', 'new_designation_list', 'mapped_designations'));
    }


    function mapCoreDesignation(Request $request)
    {
        try {
            $condition = [
                'Old' => $request->Old,
            ];

            $dataToUpdate = [
                'New' => $request->New,
            ];

            $success = DB::table('designation_map_master')->updateOrInsert($condition, array_merge($condition, $dataToUpdate));
            if ($success) {
                return response()->json(['status' => 200, 'msg' => 'Department map updated successfully.']);
            } else {
                return response()->json(['status' => 500, 'msg' => 'Failed to update the designation map.']);
            }
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'msg' => 'An error occurred: ' . $e->getMessage()]);
        }
    }


    public function core_job_map()
    {
        $job_post_list = DB::table('jobpost')
            ->select('core_company.company_code', 'master_department.DepartmentName', 'jobpost.*')
            ->leftJoin('master_department', 'master_department.DepartmentId', '=', 'jobpost.DepartmentId')
            ->leftJoin('core_company', 'core_company.id', '=', 'jobpost.CompanyId')
            /*->whereIn('JPId',[568,569])*/
            ->orderBy('core_company.company_code')
            ->orderBy('master_department.DepartmentName')
            ->paginate(50);
        $new_department_list = DB::table('core_department')->orderBy('department_name')->get();
        // Fetch departments mapping
        $mapped_jobpost = DB::table('jobpost_map_master')
            ->select('JPId', 'Department', 'SubDepartment', 'Designation')
            ->get()
            ->keyBy('JPId');

        $new_designation_list = DB::table('core_designation')->OrderBy('designation_name')->get();
        $sub_department_list = DB::table('core_sub_department')->orderBy('sub_department_name')->get();
        return view('mapping.job_map', compact('job_post_list', 'new_department_list', 'mapped_jobpost', 'sub_department_list', 'new_designation_list'));
    }

    function mapCoreJobPost(Request $request)
    {
        try {
            $condition = [
                'JPId' => $request->JPId,
            ];

            $dataToUpdate = [
                'Department' => $request->Department,
                'SubDepartment' => $request->SubDepartment,
                'Designation' => $request->Designation,
            ];

            $success = DB::table('jobpost_map_master')->updateOrInsert($condition, array_merge($condition, $dataToUpdate));
            if ($success) {
                return response()->json(['status' => 200, 'msg' => 'Jobpost map updated successfully.']);
            } else {
                return response()->json(['status' => 500, 'msg' => 'Failed to update the jobpost map.']);
            }
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'msg' => 'An error occurred: ' . $e->getMessage()]);
        }
    }


    public function sync_data1()
    {
        ini_set('max_execution_time', '0');
        ini_set('memory_limit', '-1');

        $jobPostList = DB::table('jobpost_map_master')->select(['JPId', 'Department', 'SubDepartment', 'Designation'])->get();


        $updates = [];

        foreach ($jobPostList as $jobPost) {
            // Ensure all required fields are available to avoid errors
            if (isset($jobPost->JPId, $jobPost->Department)) {
                $updates[] = [
                    'JPId' => $jobPost->JPId,
                    'Department' => $jobPost->Department,
                    'SubDepartment' => $jobPost->SubDepartment,
                    'Designation' => $jobPost->Designation,
                    'isMapped' => 1,
                ];
            }
        }

        if (!empty($updates)) {
            // Use a bulk update query for better performance
            foreach ($updates as $update) {

                /*     DB::table('jobapply')
                    ->where('JPId', $update['JPId'])
                    ->where('isMapped', 0)
                    ->update([
                        'Department' => $update['Department'],
                        'SubDepartment' => $update['SubDepartment'],
                        'isMapped' => $update['isMapped'],
                    ]);

                DB::table('trainee_apply')
                    ->where('JPId', $update['JPId'])
                    ->where('isMapped', 0)
                    ->update([
                        'Department' => $update['Department'],
                        'SubDepartment' => $update['SubDepartment'],
                        'isMapped' => $update['isMapped'],
                    ]);


                DB::table('jobpost')->where('JPId', $update['JPId'])
                    ->where('isMapped', 0)
                    ->update([
                        'DepartmentId' => $update['Department'],
                        'SubDepartment' => $update['SubDepartment'],
                        'DesigId' => $update['Designation'],
                        'isMapped' => $update['isMapped'],
                    ]);

                DB::table('manpowerrequisition')
                    ->join('jobpost', 'jobpost.MRFId', '=', 'manpowerrequisition.MRFId')
                    ->where('jobpost.JPId', $update['JPId'])
                    ->where('manpowerrequisition.isMapped', 0)
                    ->update([
                        'manpowerrequisition.DepartmentId' => $update['Department'],
                        'manpowerrequisition.SubDepartment' => $update['SubDepartment'],
                        'manpowerrequisition.DesigId' => $update['Designation'],
                        'manpowerrequisition.isMapped' => $update['isMapped'],
                    ]);

                DB::table('offerletterbasic')
                    ->join('jobapply', 'jobapply.JAId', '=', 'offerletterbasic.JAId')
                    ->where('jobapply.JPId', $update['JPId'])
                    ->where('offerletterbasic.isMapped', 0)
                    ->update([
                        'offerletterbasic.Department' => $update['Department'],
                        'offerletterbasic.SubDepartment' => $update['SubDepartment'],
                        'offerletterbasic.Designation' => $update['Designation'],
                        'offerletterbasic.isMapped' => $update['isMapped'],
                    ]);
                DB::table('offerletterbasic_history')
                    ->join('jobapply', 'jobapply.JAId', '=', 'offerletterbasic_history.JAId')
                    ->where('jobapply.JPId', $update['JPId'])
                    ->where('offerletterbasic_history.isMapped', 0)
                    ->update([
                        'offerletterbasic_history.Department' => $update['Department'],
                        'offerletterbasic_history.SubDepartment' => $update['SubDepartment'],
                        'offerletterbasic_history.Designation' => $update['Designation'],
                        'offerletterbasic_history.isMapped' => $update['isMapped'],
                    ]);*/

                DB::table('screening')
                    ->join('jobapply', 'jobapply.JAId', '=', 'screening.JAId')
                    ->where('jobapply.JPId', $update['JPId'])
                    ->where('screening.isMapped', 0)
                    ->update([
                        'screening.ScrDpt' => $update['Department'],
                        'screening.isMapped' => $update['isMapped'],
                    ]);

                /* DB::table('previous_screening')
                    ->join('jobapply', 'jobapply.JAId', '=', 'previous_screening.JAId')
                    ->where('jobapply.JPId', $update['JPId'])
                    ->where('previous_screening.isMapped', 0)
                    ->update([
                        'previous_screening.ScrDpt' => $update['Department'],
                        'previous_screening.isMapped' => $update['isMapped'],
                    ]);*/
            }
        }

        return count($updates); // Optional: Return the count of updated records
    }


    public function sync_data2()
    {
        ini_set('max_execution_time', '0');
        ini_set('memory_limit', '-1');

        $departmentMappedList = DB::table('department_map_master')->select(['CompanyId', 'Old', 'New', 'Sub'])->get();
        $updates = [];
        foreach ($departmentMappedList as $department) {
            $updates[] = [
                'Old' => $department->Old,
                'Department' => $department->New,
                'SubDepartment' => $department->Sub,
                'isMapped' => 1,
            ];
        }

        if (!empty($updates)) {
            foreach ($updates as $update) {
                /* DB::table('jobapply')
                    ->where('isMapped', 0)
                    ->where('Department', $update['Old'])
                    ->update([
                        'Department' => $update['Department'],
                        'SubDepartment' => $update['SubDepartment'],
                        'isMapped' => $update['isMapped'],
                    ]);

                DB::table('manpowerrequisition')
                    ->where('DepartmentId', $update['Old'])
                    ->where('isMapped', 0)
                    ->update([
                        'DepartmentId' => $update['Department'],
                        'SubDepartment' => $update['SubDepartment'],
                        'isMapped' => $update['isMapped'],
                    ]);

                DB::table('previous_screening')
                    ->where('ScrDpt', $update['Old'])
                    ->where('isMapped', 0)
                    ->update([
                        'ScrDpt' => $update['Department'],
                        'SubDepartment' => $update['SubDepartment'],
                        'isMapped' => $update['isMapped'],
                    ]);*/
                DB::table('offerletterbasic')
                    ->where('Functional_Dpt', $update['Old'])
                    ->where('isMapped', 0)
                    ->update([
                        'Functional_Dpt' => $update['Department'],
                        'isMapped' => $update['isMapped'],
                    ]);
            }
        }
        return count($updates); // Optional: Return the count of updated records
    }

    public function sync_data3()
    {
        try {
            // Begin transaction
            DB::beginTransaction();

            // Fetch all department mappings
            $mappings = DB::table('department_map_master')->pluck('New', 'Old')->toArray();

            // Fetch all job candidates
            $jobCandidates = jobcandidate::whereNotNull('Suitable_For')->get();

            foreach ($jobCandidates as $jobCandidate) {
                // Split Suitable_For into an array
                $currentValues = explode(',', $jobCandidate->Suitable_For);

                // Map the values based on the mappings
                $updatedValues = array_map(function ($value) use ($mappings) {
                    $trimmedValue = trim($value);
                    return $mappings[$trimmedValue] ?? $trimmedValue;
                }, $currentValues);

                // Join the updated values back into a string
                $jobCandidate->Suitable_For = implode(',', $updatedValues);

                // Save the updated record
                $jobCandidate->save();
            }

            // Commit transaction
            DB::commit();

            return response()->json(['message' => 'Suitable_For updated successfully.'], 200);
        } catch (\Exception $e) {
            // Rollback transaction in case of error
            DB::rollBack();

            return response()->json(['error' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }


    public function index()
    {
        // Select resumes from the database with explicit column selection
        $resumes = DB::table('jobcandidates')
            ->join('jobapply', 'jobcandidates.JCId', '=', 'jobapply.JCId')
            ->where('jobapply.Department', 15)
            ->where('jobcandidates.Education', 7)
            ->whereNull('jobapply.Status')
            ->select('jobcandidates.Resume', 'jobcandidates.JCId')
            ->get();
    
        if ($resumes->isEmpty()) {
            return response()->json(['error' => 'No resumes found'], 404);
        }
        
        // Create unique zip file name
        $fileName = 'resumes_' . time() . '.zip';
        $zipFileName = storage_path('app/' . $fileName);
    
        $zip = new ZipArchive;
        if ($zip->open($zipFileName, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
            foreach ($resumes as $resume) {
                if (empty($resume->Resume)) {
                    Log::error('Resume file missing for JCId: ' . $resume->JCId);
                    continue;
                }
    
                $filePath = public_path('uploads/Resume/' . $resume->Resume);
                if (file_exists($filePath)) {
                    $zip->addFile($filePath, basename($filePath));
                } else {
                    Log::error('Resume file not found: ' . $filePath);
                }
            }
            $zip->close();
    
            return response()->download($zipFileName)->deleteFileAfterSend(true);
        }
    
        return response()->json(['error' => 'Failed to create zip file'], 500);
    }
}
