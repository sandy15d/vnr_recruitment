<?php

namespace App\Http\Controllers\Recruiter;

use App\Http\Controllers\Controller;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RecruiterController extends Controller
{
    function index()
    {
        $active_mrf = DB::table('manpowerrequisition')->where('CountryId', session('Set_Country'))->where('Allocated', Auth::user()->id)->where('Status', 'Approved')->count();

        $OpenJobPosting = DB::table('jobpost')
            ->join('manpowerrequisition', 'jobpost.MRFId', '=', 'manpowerrequisition.MRFId')
            ->where('CountryId', session('Set_Country'))
            ->where('JobPostType', 'Regular')
            ->where('PostingView', 'Show')
            ->where('jobpost.Status', 'Open')
            ->where('jobpost.CreatedBy', Auth::user()->id)
            ->count();

        $PendingTechScr = DB::table('jobapply')
            ->Join('jobpost', 'jobpost.JPId', '=', 'jobapply.JPId')
            ->Join('manpowerrequisition', 'jobpost.MRFId', '=', 'manpowerrequisition.MRFId')
            ->Join('screening', 'jobapply.JAId', '=', 'screening.JAId')
            ->where('manpowerrequisition.Allocated', Auth::user()->id)
            ->where('jobpost.Status', 'Open')
            ->where('jobapply.Status', 'Selected')
            ->whereNull('screening.ScreenStatus')
            ->count();

        $PendingJoining = DB::table('candjoining')
            ->join('jobapply', 'jobapply.JAId', '=', 'candjoining.JAId')
            ->join('jobpost', 'jobpost.JPId', '=', 'jobapply.JPId')
            ->where('jobpost.CreatedBy', Auth::user()->id)
            ->where('Answer', 'Accepeted')
            ->where('Joined', 'No')
            ->count();

        $upcomming_interview = DB::table('screening')
            ->join('jobapply', 'screening.JAId', '=', 'jobapply.JAId')
            ->join('jobpost', 'jobapply.JPId', '=', 'jobpost.JPId')
            ->join('jobcandidates', 'jobapply.JCId', '=', 'jobcandidates.JCId')
            ->leftJoin('screen2ndround', 'screen2ndround.ScId', '=', 'screening.ScId')
            ->where(function ($query) {
                $query->where('screening.ScreenStatus', 'Shortlist')
                    ->orWhere('screening.IntervStatus', '2nd Round Interview');
            })
            ->where('jobpost.CreatedBy', Auth::user()->id)
            ->where('jobpost.Status', 'Open')
            ->where('jobpost.JobPostType', 'Regular')
            ->where(function ($query) {
                $query->whereDate('IntervDt', '>=', Carbon::today())
                    ->orWhereDate('IntervDt2', '>=', Carbon::today());
            })
            ->where(function ($query) {
                $query->whereNull('screening.IntervStatus')
                    ->orWhereNull('screen2ndround.IntervStatus2');
            })->count();
        $TotalPosition = DB::table('manpowerrequisition')->where('Allocated', Auth::user()->id)->where('Status', 'Approved')->select(DB::raw('SUM(Positions) as total'))->get()->first()->total;
        $filledPositions = DB::table('mrf_position_filled')
            ->join('manpowerrequisition', 'mrf_position_filled.MRFId', '=', 'manpowerrequisition.MRFId')
            ->where('manpowerrequisition.Allocated', Auth::user()->id)
            ->where('manpowerrequisition.Status', 'Approved')
            ->select(DB::raw('SUM(mrf_position_filled.Filled) as filled_total'))
            ->get()
            ->first()
            ->filled_total;

        $remainingPositions = $TotalPosition - $filledPositions;

        $events = [];
        $get_events = DB::table('event_calendar')->where('belong_to', Auth::user()->id)->groupBy('start_time', 'end_time', 'title', 'description', 'belong_to', 'type', 'event_type')->get();
        foreach ($get_events as $event) {
            $events[] = [
                'title' => $event->title,
                'description' => $event->description,
                'start' => $event->start_time,
                'end' => $event->end_time,
            ];
        }
        return view('recruiter.index', ["allocatedmrf" => $active_mrf, "JobPosting" => $OpenJobPosting, 'PendingTechScr' => $PendingTechScr, 'PendingJoining' => $PendingJoining, "events" => $events, "upcomming_interview" => $upcomming_interview, "OpenPosition" => $remainingPositions]);
    }
}
