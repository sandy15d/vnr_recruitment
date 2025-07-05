<?php

namespace App\Http\Controllers;


use App\Models\CaseStudyQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CaseStudyQuestionController extends Controller
{
    public function index()
    {
        $questions = CaseStudyQuestion::all();
        return view('case_study.questions', compact('questions'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'question' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 0, 'error' => $validator->errors()->toArray()]);
        }

        $query = new CaseStudyQuestion();
        $query->question = $request->question;
        $query->save();
        return response()->json(['status' => 200, 'message' => 'Question Added Successfully.']);
    }

    public function edit()
    {

    }

    public function destroy($id)
    {
        CaseStudyQuestion::find($id)->delete();
        return response()->json(['status' => 200, 'message' => 'Question Deleted Successfully.']);
    }

}
