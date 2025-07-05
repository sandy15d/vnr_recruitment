<?php

namespace App\Http\Controllers;

use App\Http\Requests\CaseStudyAnswerControllerStoreRequest;
use App\Http\Requests\CaseStudyAnswerControllerUpdateRequest;
use App\Models\CaseStudyAnswer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CaseStudyAnswerController extends Controller
{
    public function index(Request $request): Response
    {
        $caseStudyAnswers = CaseStudyAnswer::all();

        return view('case_study.answers');
    }

    public function store(CaseStudyAnswerControllerStoreRequest $request): Response
    {
        $answer = Answer::create($request->validated());

        return redirect()->route('case_study.answer');
    }

    public function update(CaseStudyAnswerControllerUpdateRequest $request, CaseStudyAnswer $caseStudyAnswer): Response
    {
        $answer->save();

        return redirect()->route('case_study.answer');
    }

    public function destroy(Request $request, CaseStudyAnswer $caseStudyAnswer): Response
    {
        return redirect()->route('case_study.answer');
    }
}
