@extends('layouts.master')
@section('title', 'Assessment Report')
@section('PageContent')
    <div class="page-content">
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Assessment Report</div>
        </div>
        <hr/>
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-condensed" id="que_bank_table" style="width: 100%">
                                <thead class="bg-success text-light">
                                <tr>
                                    <th>S.No</th>
                                    <th>Reference No</th>
                                    <th>Candidate</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Exam</th>
                                    <th>Result</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($candidate_list as $candidate)
                                    <tr>
                                        <td>{{$candidate_list->firstItem() + $loop->index}}</td>
                                        <td>{{$candidate->ReferenceNo}}</td>
                                        <td>{{$candidate->FName}} {{$candidate->MName}} {{$candidate->LName}}</td>
                                        <td>{{$candidate->Email}}</td>
                                        <td>{{$candidate->Phone}}</td>
                                        <td>{{$candidate->exam_name}}</td>
                                        <td>
                                            @php
                                                /*$paper_list = \Illuminate\Support\Facades\DB::table('candidate_assessment_status')
                                               ->select('subject_name', 'candidate_assessment.paper_id', DB::raw('SUM(mark) as result'))
                                                ->join('candidate_assessment', function ($join) {
                                                    $join->on('candidate_assessment.paper_id', '=', 'candidate_assessment_status.paper_id')
                                                         ->on('candidate_assessment.jcid', '=', 'candidate_assessment_status.jcid');
                                                })
                                                ->join('subject_master', 'candidate_assessment_status.paper_id', '=', 'subject_master.id')
                                                                                    ->where('candidate_assessment.jcid',$candidate->JCId)
                                                                                    ->where('candidate_assessment.exam_id',$candidate->exam_id)
                                                                                    ->where('completed','Y')

                                                ->get();*/
                                                $paper_list = \Illuminate\Support\Facades\DB::table('candidate_assessment_status')
                                                ->join('subject_master','candidate_assessment_status.paper_id','subject_master.id')
                                                ->where('jcid',$candidate->JCId)
                                                ->where('exam_id',$candidate->exam_id)
                                                ->where('completed','Y')
                                                ->select(['subject_name','paper_id'])
                                                ->get();
                                            @endphp
                                            <table class="table">
                                                <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Paper</th>
                                                    <th>Marks</th>
                                                    <th style="text-align: right;">View</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($paper_list as $paper)
                                                    <tr>
                                                        <td>{{$loop->iteration}}</td>
                                                        <td>{{$paper->subject_name}}</td>
                                                        <td></td>
                                                        <td style="text-align: right">
                                                            <a href="{{route('candidate_assessment_result',['JCId'=>$candidate->JCId,'Exam'=>$candidate->exam_id,'Paper'=>$paper->paper_id])}}"
                                                               target="_blank" class="text-center">
                                                                <i class="fa fa-eye"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>

                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            {{$candidate_list->links()}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script_section')
    <script>

    </script>
@endsection
