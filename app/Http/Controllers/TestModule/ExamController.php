<?php

namespace App\Http\Controllers\TestModule;

use App\Http\Controllers\Controller;
use App\Mail\CandidateAssessmentMail;
use App\Mail\InterviewMail;
use App\Models\TestModule\ExamMaster;
use App\Models\TestModule\SubjectMaster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;

class ExamController extends Controller
{

    public function index()
    {
        $exam_list = ExamMaster::where('status', 'A')->paginate(10);
        return view('testmodule.exam_list', compact('exam_list'));
    }

    public function create()
    {
        $test_papers = SubjectMaster::where('status', 'A')->get()->toArray();
        // add FIRO B to test_papers and its id is 0
        array_unshift($test_papers, array('id' => 0, 'subject_name' => 'FIRO B'));

        return view('testmodule.exam_create', compact('test_papers'));
    }


    public function show(string $id)
    {
        //
    }

    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'exam_name' => 'required',
                'test_paper' => 'required',
                'time' => 'required',
                'time_reminder' => 'required',
                'instruction' => 'required',
            ],
        );
        if ($validator->fails()) {
            return response()->json(['status' => 400, 'error' => $validator->errors()->toArray()]);
        }
        $test_paper = implode(',', $request->test_paper);
        $ExamMaster = new ExamMaster();
        $ExamMaster->exam_name = $request->exam_name;
        $ExamMaster->test_paper = $test_paper;
        $ExamMaster->time = $request->time;
        $ExamMaster->time_reminder = $request->time_reminder;
        $ExamMaster->instruction = $request->instruction;
        $ExamMaster->max_alert = $request->max_alert;
        $ExamMaster->save();
        return response()->json(['status' => 200, 'message' => 'Exam Added Successfully.']);

    }

    public function edit(string $id)
    {
        $test_papers = SubjectMaster::where('status', 'A')->get()->toArray();
        // add FIRO B to test_papers and its id is 0
        array_unshift($test_papers, array('id' => 0, 'subject_name' => 'FIRO B'));
        $exam_detail = ExamMaster::find($id);

        return view('testmodule.exam_edit', compact('id', 'test_papers', 'exam_detail'));
    }

    public function update(Request $request, string $id)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'exam_name' => 'required',
                'test_paper' => 'required',
                'time' => 'required',
                'time_reminder' => 'required',
                'instruction' => 'required',
            ],
        );
        if ($validator->fails()) {
            return response()->json(['status' => 400, 'error' => $validator->errors()->toArray()]);
        }
        $test_paper = implode(',', $request->test_paper);
        $ExamMaster = ExamMaster::find($id);
        $ExamMaster->exam_name = $request->exam_name;
        $ExamMaster->test_paper = $test_paper;
        $ExamMaster->time = $request->time;
        $ExamMaster->time_reminder = $request->time_reminder;
        $ExamMaster->instruction = $request->instruction;
        $ExamMaster->max_alert = $request->max_alert;
        $ExamMaster->status = $request->status;
        $ExamMaster->save();
        return response()->json(['status' => 200, 'message' => 'Exam Updated Successfully.']);
    }


    public function destroy(string $id)
    {
        $query = ExamMaster::find($id);
        $query->delete();
        return response()->json(['status' => 200, 'message' => 'Question Deleted Successfully.']);
    }

    public function setExam(Request $request)
    {
        $JCId = $request->JCId;
        $exam_id = $request->exam;

        // Check if a record with the given JCId exists
        $existingRecord = DB::table('set_candidate_exam')
            ->where('JCId', $JCId)
            ->first();

        if ($existingRecord) {
            // Update the existing record
            $updated = DB::table('set_candidate_exam')
                ->where('JCId', $JCId)
                ->update([
                    'exam_id' => $exam_id,
                    'set_by' => auth()->id(),
                    'set_date' => now()
                ]);

            if ($updated) {
                return response()->json(['status' => 200, 'msg' => 'Exam Updated Successfully.']);
            } else {
                return response()->json(['status' => 400, 'msg' => 'Failed to update exam.']);
            }
        } else {
            // Create a new record
            $created = DB::table('set_candidate_exam')->insert([
                'JCId' => $JCId,
                'exam_id' => $exam_id,
                'set_by' => auth()->id(),
                'set_date' => now()
            ]);

            if ($created) {
                return response()->json(['status' => 200, 'msg' => 'Exam Set Successfully.']);
            } else {
                return response()->json(['status' => 400, 'msg' => 'Failed to set exam.']);
            }
        }
    }

    public function candidate_assessment_login($id, $exam_id)
    {
        return view('testmodule.candidate_assessment_login');
    }

    public function candidate_assessment_select_paper()
    {
        return view('testmodule.candidate_assessment_select_paper');
    }

    public function candidate_assessment_instruction()
    {
        return view('testmodule.candidate_assessment_instruction');
    }

    public function candidate_assessment()
    {
        return view('testmodule.candidate_assessment');
    }

    public function sendCandidateAssessmentMail(Request $request)
    {
        $JCIds = $request->JCIds;

        foreach ($JCIds as $JCId) {
            //get Exam_Id from set_candidate_exam table
            $exam_id = DB::table('set_candidate_exam')
                ->where('JCId', $JCId)
                ->value('exam_id');
            //encrypt Exam_Id , JCId to base64
            $exam_id = base64_encode($exam_id);
            $candidate_id = base64_encode($JCId);
            $email = get_candidate_email($JCId);
            $details = [
                'candidate_name' => get_candidate_name($JCId),
                'reference_no' => get_reference_number($JCId),
                'subject' => 'Invitation for Pre Interview Assessment',
                'exam_id' => $exam_id,
                'candidate_id' => $candidate_id,
                'candidate_link' => url('/candidate_assessment_login/' . $exam_id . '/' . $candidate_id),
            ];
            Mail::to($email)->send(new CandidateAssessmentMail($details));
            //Set Sent Mail to Y in JobCandidate
            DB::table('jobcandidates')->where('jcid', $JCId)->update(['mail_sent_for_test' => 'Y']);
        }
        return response()->json(['status' => 200, 'msg' => 'Mail sent successfully.']);

    }

    public function candidate_assessment_save(Request $request)
    {
        $JCId = $request->JCId;
        $Que = $request->Que;
        $Ans = $request->Ans;
        $Exam_Id = $request->Exam_Id;
        $Type = $request->Type;
        $Paper = $request->Paper;
        $qBankId = $request->qBankId;
        //check answer for question_id
        $mark = "";
        if ($Type != 'Descriptive') {
            $chk = DB::table('question_banks')->where('id', $qBankId)->where('correct_option', $Ans)->exists();
            if ($chk) {
                $mark = 1;
            } else {
                $mark = 0;
            }
        }

        //check if record exists by jcid,exam_id,paper_id,question_id
        $chk = DB::table('candidate_assessment')->where('jcid', $JCId)
            ->where('exam_id', $Exam_Id)->where('paper_id', $Paper)->where('q_id', $qBankId)->exists();
        if ($chk) {
           //update record
            $query = DB::table('candidate_assessment')->where('jcid', $JCId)
                ->where('exam_id', $Exam_Id)->where('paper_id', $Paper)
                ->where('q_id', $qBankId)->update(['answer' => $Ans, 'mark' => $mark]);
        }else{
            $query = DB::table('candidate_assessment')->insert(['jcid' => $JCId, 'exam_id' => $Exam_Id, 'paper_id' => $Paper,
                'question_id' => $Que,'q_id'=>$qBankId, 'question_type' => $Type, 'answer' => $Ans, 'mark' => $mark]);
        }

        if (!$query) {
            return response()->json(['status' => 400, 'msg' => 'Something went wrong..!!']);
        } else {
            return response()->json(['status' => 200, 'msg' => ' successfully created.']);
        }
    }

    public function candidate_assessment_final_submit(Request $request)
    {
        $JCId = $request->JCId;
        $Exam_Id = $request->Exam_Id;
        $Paper = $request->Paper;
        $query = DB::table('candidate_assessment_status')->insert(['jcid' => $JCId, 'exam_id' => $Exam_Id, 'paper_id' => $Paper, 'completed' => 'Y', 'reason' => 'Test Completed']);
        if (!$query) {
            return response()->json(['status' => 400, 'msg' => 'Something went wrong..!!']);
        } else {
            return response()->json(['status' => 200, 'msg' => ' successfully created.']);
        }
    }

    public function candidate_assessment_stop(Request $request)
    {
        $JCId = $request->JCId;
        $Exam_Id = $request->Exam_Id;
        $Paper = $request->Paper;
        $reason = $request->reason;
        $query = DB::table('candidate_assessment_status')->insert(['jcid' => $JCId, 'exam_id' => $Exam_Id, 'paper_id' => $Paper, 'completed' => 'Y', 'reason' => $reason]);
        if (!$query) {
            return response()->json(['status' => 400, 'msg' => 'Something went wrong..!!']);
        } else {
            return response()->json(['status' => 200, 'msg' => ' successfully created.']);
        }
    }

}
