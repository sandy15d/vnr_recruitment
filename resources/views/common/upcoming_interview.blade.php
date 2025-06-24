@php
    use Carbon\Carbon;

    $interviews = DB::table('screening')
        ->join('jobapply', 'screening.JAId', '=', 'jobapply.JAId')
        ->join('jobpost', 'jobapply.JPId', '=', 'jobpost.JPId')
        ->join('jobcandidates', 'jobapply.JCId', '=', 'jobcandidates.JCId')
        ->leftJoin('screen2ndround', 'screen2ndround.ScId', '=', 'screening.ScId')
        ->where(function ($query) {
            $query->where('screening.ScreenStatus', 'Shortlist')
                  ->orWhere('screening.IntervStatus', '2nd Round Interview');
        })
        ->where('jobpost.Status', 'Open')
        ->where('jobpost.JobPostType', 'Regular')
        ->where(function ($query) {
            $query->whereDate('IntervDt', '>=', Carbon::today())
                  ->orWhereDate('IntervDt2', '>=', Carbon::today());
        })
        ->where(function ($query) {
            $query->whereNull('screening.IntervStatus')
                  ->orWhereNull('screen2ndround.IntervStatus2');
        });

    if (Auth::user()->role == 'R') {
        $interviews->where('jobpost.CreatedBy', Auth::user()->id);
    }

    $interviews = $interviews->orderByRaw('CASE
                        WHEN CONCAT(screen2ndround.IntervDt2 ," ", screen2ndround.IntervTime2) IS NOT NULL THEN CONCAT(screen2ndround.IntervDt2 ," ", screen2ndround.IntervTime2)
                        ELSE CONCAT(screening.IntervDt ," ", screening.IntervTime)
                     END ASC')
        ->select('jobcandidates.ReferenceNo','screening.JAId', 'jobcandidates.FName', 'jobcandidates.MName', 'jobcandidates.LName', 'jobpost.Title', 'screening.IntervDt',
         'screening.IntervTime', 'screening.IntervPanel', 'screening.ScrDpt', 'screening.InterviewMode',
        'screen2ndround.IntervDt2', 'screen2ndround.IntervTime2', 'screen2ndround.IntervPanel2', 'screen2ndround.InterviewMode2')
        ->get();
@endphp

@extends('layouts.master')
@section('title', 'Upcoming Interviews')
@section('PageContent')
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Upcoming Interviews</div>
        </div>
        <!--end breadcrumb-->

        <div class="card  border-top border-0 border-4 border-primary">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover table-condensed table-bordered" style="width: 100%">
                        <thead class="bg-primary text-light text-center">
                        <tr>
                            <td class="td-sm">S.No</td>
                            <th>Date/Time</th>
                            <td>Candidate Name</td>
                            <td>Department</td>
                            <td>For Post</td>
                            <td>Interview Mode</td>
                            <td>Panel Members</td>
                        </tr>
                        </thead>
                        <tbody>
                        @php
                            $i = 1;
                        @endphp
                        @foreach ($interviews as $item => $value)
                        @php
                                $sendingId = base64_encode($value->JAId);

                            @endphp
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td class="text-center">
                                    @if($value->IntervDt2 != null)
                                        {{ formatDateTime($value->IntervDt2, $value->IntervTime2) }}
                                    @else
                                        {{ formatDateTime($value->IntervDt, $value->IntervTime) }}
                                    @endif
                                </td>
                                <td style="text-align:left;"><a class="text-success"
                                                                href="{{ route('candidate_detail') }}?jaid={{ $sendingId }}"
                                                                target="_blank">{{ $value->FName }} {{ $value->MName }} {{ $value->LName }}</a></td>
                                <td class="text-center">{{ getDepartmentCode($value->ScrDpt) }}</td>
                                <td>{{ $value->Title }}</td>
                                <td class="text-center">
                                    @if($value->InterviewMode2 != null)
                                        {{ $value->InterviewMode2 }}
                                    @else
                                        {{ $value->InterviewMode }}
                                    @endif

                                </td>
                                <td>
                                    @if($value->IntervPanel2 != null)
                                        {{ formatInterviewPanel($value->IntervPanel2) }}
                                    @else
                                        {{ formatInterviewPanel($value->IntervPanel) }}
                                    @endif
                                </td>
                            </tr>
                        @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script_section')

@endsection
