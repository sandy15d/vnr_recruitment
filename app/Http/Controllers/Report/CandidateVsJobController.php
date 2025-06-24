<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Models\jobapply;
use Illuminate\Http\Request;

class CandidateVsJobController extends Controller
{
    public function index()
    {
        $jobpost_list = jobapply::where('jobapply.JPId', '!=', 0)->join('jobpost','jobpost.JPId','=','jobapply.JPId')->groupBy('jobapply.JPId')->get();
        return view('reports.candidate_vs_job', compact('jobpost_list'));
        
    }
}
