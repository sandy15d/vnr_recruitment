@php
    $userId = auth()->user()->id;

      $query = DB::table('screening')
          ->leftJoin('screen2ndround', 'screen2ndround.ScId', '=', 'screening.ScId')
          ->leftJoin('jobapply', 'screening.JAId', '=', 'jobapply.JAId')
          ->leftJoin('jobpost', 'jobapply.JPId', '=', 'jobpost.JPId')
          ->leftJoin('jobcandidates', 'jobapply.JCId', '=', 'jobcandidates.JCId')
          ->where('jobpost.Status', 'Open')

          ->where(function ($query) {
              $query->where('screening.IntervDt', '>=', date('Y-m-d'))->orWhere(function ($query) {
                  $query->where('screening.IntervStatus', '=', '2nd Round Interview')->where('screen2ndround.IntervDt2', '>=', date('Y-m-d'));
              });
          })
        ->where(function ($query) use ($userId) {
          $query->where(function ($query) use ($userId) {
              $query->whereRaw('FIND_IN_SET(?, screening.IntervPanel) > 0', [$userId]);
          })->orWhere(function ($query) use ($userId) {
              $query->whereRaw('FIND_IN_SET(?, screen2ndround.IntervPanel2) > 0', [$userId]);
          });
      })

          ->orderBy('screening.IntervDt', 'asc')
          ->select('jobcandidates.FName', 'jobcandidates.MName', 'jobcandidates.LName', 'jobpost.Title', 'screening.IntervDt', 'screen2ndround.IntervDt2', 'screening.IntervTime', 'screen2ndround.IntervTime2', 'screening.IntervPanel', 'screen2ndround.IntervPanel2', 'screening.IntervStatus')
          ->get();

@endphp
@extends('layouts.master')
@section('title', 'Interview Schedule')
@section('PageContent')
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Interview Schedule</div>
        </div>
        <!--end breadcrumb-->

        <div class="card  border-top border-0 border-4 border-primary">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover table-condensed table-bordered" style="width: 100%">
                        <thead class="bg-primary text-light text-center">
                            <tr>
                                <td class="td-sm">S.No</td>
                                <td>Candidate Name</td>
                                <td>Interview for Post</td>
                                <td>Date of Interview</td>
                                <td>Timings</td>
                                <td>Panel Member</td>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $i = 1;
                            @endphp
                            @foreach ($query as $item => $value)
                                <tr>
                                    <td class="text-center">{{ $i }}</td>
                                    <td>{{ $value->FName }} {{ $value->MName }} {{ $value->LName }}</td>
                                    <td>{{ $value->Title }}</td>
                                    @if ($value->IntervStatus === '2nd Round Interview')
                                        <td class="text-center">
                                            {{ date('d-m-Y', strtotime($value->IntervDt2)) }}
                                        </td>
                                        <td class="text-center">{{ date('h:i:s a', strtotime($value->IntervTime2)) }}</td>
                                        <td>
                                            @php
                                                $panel_member = explode(',',$value->IntervPanel2);
                                                foreach ($panel_member as $row) {
                                                    $panel[] = getFullName($row);
                                                }
                                                echo implode(', ',$panel);
                                            @endphp
                                        </td>
                                    @else
                                        <td class="text-center">
                                            {{ date('d-m-Y', strtotime($value->IntervDt)) }}
                                        </td>
                                        <td class="text-center">{{ date('h:i:s a', strtotime($value->IntervTime)) }}</td>
                                        <td>
                                            @php
                                            $panel_member = explode(',',$value->IntervPanel);
                                            foreach ($panel_member as $row) {
                                                $panel[] = getFullName($row);
                                            }
                                            echo implode(', ',$panel);
                                        @endphp
                                        </td>
                                    @endif


                                </tr>
                                @php
                                    $i++;
                                @endphp
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
