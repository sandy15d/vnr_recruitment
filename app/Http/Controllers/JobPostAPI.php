<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JobPostAPI extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $regular_job = DB::table('jobpost')
            ->Join('manpowerrequisition', 'manpowerrequisition.MRFId', '=', 'jobpost.MRFId')
            ->where('manpowerrequisition.CompanyId', 1)
            ->Where('jobpost.Status', 'Open')
            ->Where('jobpost.PostingView', 'Show')
            ->Where('jobpost.JobPostType', 'Regular')
            ->orderBy('JPId', 'desc')
            ->get();

        // create an array of the results having jobcode, title and description
        $regular_job = $regular_job->map(function ($item, $key) {
            return [
                'jobcode' => $item->JobCode,
                'title' => $item->Title,
                'description' => $this->limitHtmlContent($item->Description, 100),
                'department' => getDepartment($item->DepartmentId),
                'location' => get_mrf_location($item->MRFId),
                'jpid' => $item->JPId,
                'link' => route('job_apply_form', ['jpid' => base64_encode($item->JPId)])
            ];
        });

        return response()->json(array(
            'regular_job' => $regular_job,
        ));
    }

    /**
     * Limit HTML content to a specified character length, preserving basic tags.
     *
     * @param string $content The HTML content to limit.
     * @param int $limit The character limit for the content.
     * @return string
     */
    function limitHtmlContent($content, $limit = 100)
    {
        // Remove unwanted tags but preserve basic formatting tags
        $content = strip_tags($content, '<p><b><i><strong><em>');

        // Limit the content length while keeping HTML structure intact
        if (strlen($content) > $limit) {
            $content = mb_strimwidth($content, 0, $limit, "...");
        }

        return $content;
    }

    public function job_detail($JPId)
    {
        // Fetch job details and related manpower requisition details with necessary columns only
        $regular_job = DB::table('jobpost')
            ->join('manpowerrequisition', 'manpowerrequisition.MRFId', '=', 'jobpost.MRFId')
            ->where('jobpost.JPId', $JPId)
            ->get()
            ->map(function ($item) {
                // Build the job detail array with necessary transformations
                return [
                    'jobcode' => $item->JobCode,
                    'title' => $item->Title,
                    'description' => $item->Description,
                    'department' => getDepartment($item->DepartmentId),
                    'location' => get_mrf_location($item->MRFId),
                    'desired_candidate_profile' => unserialize($item->KeyPositionCriteria) ?: [],
                    'qualification' => $this->getQualifications(unserialize($item->EducationId)),
                    'work_experience' => $item->WorkExp,
                    'salary' => 'Best as per industry standards',
                    'link' => route('job_apply_form', ['jpid' => base64_encode($item->JPId)])
                ];
            });

        return $regular_job;
    }

    /**
     * Helper function to format education qualifications.
     *
     * @param array|null $educationData
     * @return array
     */
    private function getQualifications($educationData)
    {
        if (!$educationData) {
            return [];
        }

        return array_map(function ($education) {
            $educationStr = getEducationById($education['e']);
            if (!empty($education['s'])) {
                $educationStr .= ' - ' . getSpecializationbyId($education['s']);
            }
            return $educationStr;
        }, $educationData);
    }

}
