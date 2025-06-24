<?php

namespace App\Http\Controllers\TestModule;

use App\Http\Controllers\Controller;
use App\Imports\TestCandidateImport;
use App\Models\jobcandidate;
use App\Models\TestModule\ExamMaster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Maatwebsite\Excel\Facades\Excel;
use Exception;
use Mpdf\Mpdf;

class TestCandidateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $candidates = jobcandidate::leftJoin('set_candidate_exam','jobcandidates.JCId', '=', 'set_candidate_exam.JCId')
            ->select('jobcandidates.*','set_candidate_exam.exam_id')
            ->where('for_test','Y')
            ->paginate(10);

        $exam_list = ExamMaster::where('status','A')->get();
      return view('testmodule.candidates_for_test', compact('candidates','exam_list'));
    }

    public function import(Request $request)
    {
        try {
            $file = $request->file('import_file');
            $currentDate = date('Y-m-d-H-i');
            $filename = 'cv_' . $currentDate . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('assets/uploads'), $filename);

            Excel::import(new TestCandidateImport(), public_path('assets/uploads/' . $filename));
            return response()->json(['status' => 200, 'message' => 'Candidates Imported Successfully.']);
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'error' => $e->getMessage()]);
        }
    }

    public function candidate_assessment_report()
    {
        $candidate_list = DB::table('set_candidate_exam')
            ->join('jobcandidates', 'set_candidate_exam.JCId', '=', 'jobcandidates.JCId')
            ->join('exam_masters', 'set_candidate_exam.exam_id', '=', 'exam_masters.id')
            ->select(['jobcandidates.*', 'exam_masters.exam_name','exam_masters.id as exam_id'])
            ->paginate(10);
        return view('testmodule.candidate_assessment_report', compact('candidate_list'));
    }

    public function candidate_assessment_result()
    {

        $JCId = $_GET['JCId'];
        $Paper = $_GET['Paper'];
        $paper_name = DB::table('subject_master')->where('id', $Paper)->first(['subject_name']);
        $total_mark = '20';
        $mark_obtained = DB::table('candidate_assessment')
            ->where('jcid', $JCId)
            ->where('paper_id', $Paper)
            ->where('mark', '1')
            ->count('mark');
        $candidate_detail = DB::table('jobcandidates')->where('JCId', $JCId)->first(['ReferenceNo','FName', 'MName', 'LName', 'Email', 'Phone']);
        ini_set('memory_limit', -1);

        $pdf = new mPDF(['utf-8', 'A4-C']);

        $pdf->SetDefaultBodyCSS('font-family', 'freeserif');
        $pdf->setAutoBottomMargin = 'stretch';
        $pdf->SetHTMLFooter('
                <div style="text-align: center; font-weight:bold; margin-top:10px; height:20px;">
                <div style="float: left; width: 100%; text-align: center;"><br><br>Page {PAGENO} of {nbpg}</div>
                </div>
        ');

        $html = View::make('testmodule.candidate_assessment_result')->render();
        $pdf->SetTitle('Candidate_Assessment_Result');
        $pdf->WriteHTML($html,);
        $pdf->Output('Candidate_Assessment_Result.pdf', 'I');

    }
}
