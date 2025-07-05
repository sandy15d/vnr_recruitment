@php
    $JCId = base64_decode(request()->jcid);
    $exam_id = base64_decode(request()->exam_id);
    $paper = base64_decode(request()->paper);

    $exam_instruction = DB::table('exam_masters')->where('id', $exam_id)->value('instruction');
    $paper_name = DB::table('subject_master')->where('id', $paper)->value('subject_name');

  $candidate = DB::table('jobcandidates')
             ->where('JCId', $JCId)
             ->select('FName', 'MName', 'LName', 'ReferenceNo')
             ->first();
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
    <link href="{{ URL::to('/') }}/assets/firob/css/custom.css" rel="stylesheet"/>
    <link href="{{ URL::to('/') }}/assets/firob/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="{{ URL::to('/') }}/assets/firob/css/style.default.css" rel="stylesheet"/>
    <link href="{{ URL::to('/') }}/assets/firob/css/responsive.css" rel="stylesheet"/>
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
                        <h4 style="color: white;">Exam: {{$paper_name}}</h4>
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
                                <div class="col-md-12" id="en">

                                    <h4 class="text-center">Please read the following instructions carefully
                                        before you begin:</h4>
                                    <h4><strong><u>General Instructions:</u></strong></h4>

                                    {!! $exam_instruction !!}

                                    <label>
                                        <input type="checkbox" id="en_ch">&nbsp;&nbsp;I hereby declare that I have
                                        thoroughly read and fully understood all the provided
                                        instructions. </label>
                                    <hr>
                                    <div class="col-md-4 col-md-offset-4 text-center">
                                        <a onClick="check_instruction('en')"
                                           class="btn btn-primary btn-block">Proceed</a>
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
<script src="{{ URL::to('/') }}/assets/js/jquery.min.js"></script>
<script src="{{ URL::to('/') }}/assets/js/bootstrap.bundle.min.js"></script>
<script>
    function check_instruction(id) {

        if ($('#' + id + '_ch').prop("checked") == false) {
            if (id == 'en') {
                alert('Please accept terms and conditions before proceeding.');
            } else {
                alert('आगे बढ़ने से पहले नियम और शर्तें स्वीकार करें।');
            }
        } else {
            window.location.href = "{{ route('candidate_assessment') }}?jcid={{ request()->query('jcid') }}&exam_id={{ request()->query('exam_id') }}&paper={{ request()->query('paper') }}";
        }
    }
</script>
</body>
</html>
