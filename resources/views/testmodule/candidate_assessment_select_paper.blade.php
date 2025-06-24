@php
    $JCId = base64_decode(request()->jcid);
    $exam_id = base64_decode(request()->exam_id);
    $candidate = DB::table('jobcandidates')
             ->where('JCId', $JCId)
             ->select('FName', 'MName', 'LName', 'ReferenceNo')
             ->first();

    $exam_list =  DB::table('exam_masters')
    ->join('subject_master', function ($join) {
        $join->whereRaw('FIND_IN_SET(subject_master.id, exam_masters.test_paper)');
    })
    ->select('subject_master.id', 'subject_master.subject_name')
    ->where('exam_masters.id', $exam_id)
    ->where('subject_master.status', 'A')
    ->get();

    //Check if exam having firo b

$check_firob = DB::table('exam_masters')->where('exam_masters.id',$exam_id)->whereRaw('FIND_IN_SET(0, exam_masters.test_paper)')->count();
// If 'Firo B' is present, create an object with the same structure as other items in the exam list
if ($check_firob > 0) {
    $firo_b_subject = (object)['id' => 0, 'subject_name' => 'Firo B'];
    $exam_list->push($firo_b_subject);
}




@endphp
        <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Candidate Assessment</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="{{ URL::to('/') }}/assets/firob/css/font-awesome.min.css" rel="stylesheet"/>

    <link href="{{ URL::to('/') }}/assets/firob/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="{{ URL::to('/') }}/assets/firob/css/custom.css" rel="stylesheet"/>
    <link href="{{ URL::to('/') }}/assets/firob/css/style.default.css" rel="stylesheet"/>

    <style>
        .pagination > .active > a {
            border-color: #570db8 !important;
            color: #fff !important;
            border-width: 1px;
        }

        html,
        body {
            margin: 0;
            padding: 0;
            height: 100%;
        }

        #copyright {
            position: absolute;
            bottom: 0;
            width: 100%;

        }

    </style>
</head>

<body>
<div id="all">
    <div class="top-bar">
        <div class="container">
            <div class="col-md-12">
                <div class="top-links"></div>
            </div>
        </div>
    </div>
    <div class="clear"></div>
    <header class="main-header">
        <div class="navbar" data-spy="affix" data-offset-top="200">
            <div class="navbar navbar-default yamm" role="navigation" id="navbar">
                <div class="container">
                    <div class="navbar-header">
                        <h2 style="color: white;">Candidate Assessment Test</h2>

                    </div>
                    <div class="col-md-5 pull-right">
                        <div class="navbar-collapse">
                            <ul class="nav navbar-nav pull-right">
                                <li>
                                    <table>
                                        <tr>
                                            <td style="padding: 5px 15px; border: 2px solid #666"><i
                                                        class="fa fa-user fa-4x"></i></td>
                                            <td>
                                                <table>
                                                    <tr>
                                                        <td style="padding: 0px 5px;">Candidate Name</td>
                                                        <td> : <span
                                                                    style="color: #fff; font-weight: bold">{{$candidate->FName}} {{$candidate->MName}} {{$candidate->LName}}</span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td style="padding: 0px 5px;">Reference No</td>
                                                        <td> : <span
                                                                    style="color: #fff; font-weight: bold">{{$candidate->ReferenceNo}}</span>
                                                        </td>
                                                    </tr>

                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </li>
                            </ul>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <div id="content">
        <div class="container">
            <section class="">
                <div class="row" style="margin-top: 30px;">
                    <div class="col-md-12 exam-confirm">
                        <div class="panel panel-default">
                            <div class="panel-body">

                                <div class="row">
                                    <div class="col-lg-12">
                                        <table class="table table-bordered">
                                            <thead class="table-primary">
                                            <tr>
                                                <th class="text-center">S.No</th>
                                                <th class="text-center">Exam</th>
                                                <th class="text-center">Action</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($exam_list as $list)
                                                <tr>
                                                    <td class="text-center">{{$loop->iteration}}</td>
                                                    <td>{{$list->subject_name}}</td>
                                                    <td class="text-center">
                                                        @if($list->id == 0)
                                                            @if(check_firob_status($JCId))
                                                                Completed
                                                            @else
                                                                <a href="{{route('firo_b_instruction',['jcid'=> request()->jcid, 'exam_id' => request()->exam_id])}}" class="btn btn-primary">Start</a>
                                                            @endif

                                                        @else
                                                            @if(check_exam_paper_status($JCId, $exam_id,$list->id))
                                                                Completed
                                                            @else
                                                                <a href="{{route('candidate_assessment_instruction', ['jcid' => request()->jcid, 'paper' => base64_encode($list->id), 'exam_id' => request()->exam_id])}}"
                                                                   class="btn btn-primary">Start</a>
                                                            @endif

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
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>
</body>
</html>
