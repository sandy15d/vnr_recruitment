@php
    $query = DB::table('firob')
        ->where('FiroF', 'A')
        ->orWhere('FiroF', 'B')
        ->orWhere('FiroF', 'C')
        ->orWhere('FiroF', 'D')
        ->orderBy('FiroF', 'ASC')
        ->orderBy('FiroO', 'ASC')
        ->get();
    $JCId = base64_decode(request()->query('jcid'));
    $chk = DB::table('jobcandidates')
        ->select('FIROB_Test','FName','MName','LName','ReferenceNo', 'CandidateImage')
        ->where('JCId', $JCId)
        ->first();
    $exam_id = base64_decode(request()->query('exam_id'));

@endphp
    <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>FIRO B</title>
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
            /*   position: absolute; */
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
                        <h2>FIRO B</h2>
                    </div>
                    <div class="col-md-5 pull-right">
                        <div class="navbar-collapse">
                            <ul class="nav navbar-nav pull-right">
                                <li>
                                    <table>
                                        <tr>
                                            @if ($chk->CandidateImage == null)
                                                <td style="padding: 5px 15px; border: 2px solid #666"><i
                                                        class="fa fa-user fa-4x"></i></td>
                                            @else
                                                <td style="border: 2px solid #f09a3e; "><img
                                                        src="{{ url('file-view/Picture/' . $chk->CandidateImage) }}"
                                                        style="width: 80px;" height="80px;"/></td>

                                            @endif
                                            <td>
                                                <table>
                                                    <tr>
                                                        <td style="padding: 0px 5px;">Candidate Name</td>
                                                        <td> : <span
                                                                style="color: #fff; font-weight: bold">{{$chk->FName}} {{$chk->MName}} {{$chk->LName}}</span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td style="padding: 0px 5px;">Reference No</td>
                                                        <td> : <span
                                                                style="color: #fff; font-weight: bold">{{$chk->ReferenceNo}}</span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td style="padding: 0px 5px;">Remaining Time</td>
                                                        <td>
                                                            : <span class="timer-title time-started"
                                                                    style="font-size: 20px;">00:00:00</span>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </li>
                            </ul>
                            <input type="hidden" id="hdfTestDuration" value="30"/>
                            <input type="hidden" name="JCId" id="JCId" value="{{ $JCId }}">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <div id="content">
        <div class="container">
            <section>
                @if ($chk->FIROB_Test == '1')
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <div class="col-md-12 text-center">
                                        <h4>You have already submitted your FIROB Assessment Test.</h4>
                                        <a class="btn btn-default btn-lg" id="btnClosePage">Close!</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @else

                    <div class="row exam-paper">
                        <div class="col-md-8" id="quest" style="padding: 0">
                            <table style="width: 100%">
                                <tr>
                                    <td>
                                        <div class="panel panel-default">
                                            <div class="panel-body mb0">
                                                @php
                                                    $no = 1;

                                                @endphp
                                                @foreach ($query as $item)

                                                    @php
                                                        if ($item->FiroF == 'A') {
                                                            $sql = DB::table('firob_object')
                                                                ->where('FiroF', 'A')
                                                                ->first();
                                                        } elseif ($item->FiroF == 'B') {
                                                            $sql = DB::table('firob_object')
                                                                ->where('FiroF', 'B')
                                                                ->first();
                                                        } elseif ($item->FiroF == 'C') {
                                                            $sql = DB::table('firob_object')
                                                                ->where('FiroF', 'C')
                                                                ->first();
                                                        } elseif ($item->FiroF == 'D') {
                                                            $sql = DB::table('firob_object')
                                                                ->where('FiroF', 'D')
                                                                ->first();
                                                        }
                                                    @endphp

                                                    <div id="page{{ str_pad($no, 2, '0', STR_PAD_LEFT) }}"
                                                         class="tab-content div-question mb0"
                                                         style="display: {{ $no == 1 ? 'block' : 'none' }}">

                                                        <h3><b><u><i><?php echo 'FORM - ' . $item->FiroF; ?></i></u></b>
                                                        </h3>
                                                        <div class="row"
                                                             style="font-size:14px;font-weight:bold;color:#005984;">
                                                            <div class="col-md-4 col-sm-6 col-xs-12">
                                                                (1) <?php echo $sql->e1 . '/ ' . $sql->h1; ?>
                                                            </div>
                                                            <div class="col-md-4 col-sm-6 col-xs-12">
                                                                (2) <?php echo $sql->e2 . '/ ' . $sql->h3; ?>
                                                            </div>
                                                            <div class="col-md-4 col-sm-6 col-xs-12">
                                                                (3) <?php echo $sql->e3 . '/ ' . $sql->h3; ?>
                                                            </div>
                                                            <div class="col-md-4 col-sm-6 col-xs-12">
                                                                (4) <?php echo $sql->e4 . '/ ' . $sql->h4; ?>
                                                            </div>
                                                            <div class="col-md-4 col-sm-6 col-xs-12">
                                                                (5) <?php echo $sql->e5 . '/ ' . $sql->h5; ?>
                                                            </div>
                                                            <div class="col-md-4 col-sm-6 col-xs-12">
                                                                (6) <?php echo $sql->e6 . '/ ' . $sql->h6; ?>
                                                            </div>
                                                        </div>

                                                        <hr>
                                                        <input class="hdfQuestionID" value="{{ $no }}"
                                                               type="hidden">

                                                        <div class="question-height">
                                                            <h4 class="question-title"> Question
                                                                {{ $no }}:
                                                                <br>
                                                                    <?php echo '<font size="4">' . $item->FiroE . '</font>/ <font size="3">' . $item->FiroH . '</font>'; ?>


                                                            </h4>
                                                            <br>
                                                            <table class="table table-borderless mb0">
                                                                <tbody>
                                                                <tr>
                                                                    <td>1 ) <input
                                                                            id="rOption{{ $no }}_1"
                                                                            name="radiospage{{ str_pad($no, 2, '0', STR_PAD_LEFT) }}"
                                                                            value="1" type="radio"></td>
                                                                    <td>2 ) <input
                                                                            id="rOption{{ $no }}_2"
                                                                            name="radiospage{{ str_pad($no, 2, '0', STR_PAD_LEFT) }}"
                                                                            value="2" type="radio"></td>
                                                                    <td>3 ) <input
                                                                            id="rOption{{ $no }}_3"
                                                                            name="radiospage{{ str_pad($no, 2, '0', STR_PAD_LEFT) }}"
                                                                            value="3" type="radio"></td>
                                                                    <td>4 ) <input
                                                                            id="rOption{{ $no }}_4"
                                                                            name="radiospage{{ str_pad($no, 2, '0', STR_PAD_LEFT) }}"
                                                                            value="4" type="radio"></td>
                                                                    <td>5 ) <input
                                                                            id="rOption{{ $no }}_5"
                                                                            name="radiospage{{ str_pad($no, 2, '0', STR_PAD_LEFT) }}"
                                                                            value="5" type="radio"></td>
                                                                    <td>6 ) <input
                                                                            id="rOption{{ $no }}_6"
                                                                            name="radiospage{{ str_pad($no, 2, '0', STR_PAD_LEFT) }}"
                                                                            value="6" type="radio"></td>
                                                                </tr>
                                                                </tbody>
                                                            </table>

                                                        </div>
                                                    </div>
                                                    @php
                                                        $no++;

                                                    @endphp
                                                @endforeach


                                                <div class="col-md-12"
                                                     style="border-top:1px solid #808080;padding-top:10px;">
                                                    <button
                                                        class="mb5 full-width btn btn-sm btn-success btn-save-answer">
                                                        Save
                                                        &amp; Next
                                                    </button>&nbsp;&nbsp;
                                                    <button
                                                        class="mb5 full-width btn btn-sm btn-default btn-reset-answer">
                                                        Clear
                                                        Response
                                                    </button>&nbsp;&nbsp;
                                                </div>
                                            </div>
                                            <div class="panel-footer">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <button
                                                            class="btn btn-success btn-submit-all-answers pull-right"
                                                            style="display: none">Submit
                                                        </button>&nbsp;&nbsp;
                                                        {{-- <a href="javascript:void(0);" class="btn btn-default pull-left"
                                                        id="btnPrevQue">
                                                        << Back </a>&nbsp;&nbsp; <a href="javascript:void(0);"
                                                                class="btn btn-default pull-left"
                                                                id="btnNextQue">Next >></a>&nbsp;&nbsp; --}}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="full_screen pull-right"
                                             style="cursor: pointer; background-color: #000; color: #fff; padding: 5px;">
                                            <i class="fa fa-angle-right fa-2x"></i>
                                        </div>
                                        <div class="collapse_screen hidden pull-right"
                                             style="cursor: pointer; background-color: #000; color: #fff; padding: 5px;">
                                            <i class="fa fa-angle-left fa-2x"></i>
                                        </div>
                                    </td>
                                </tr>
                            </table>

                        </div>
                        <div class="col-md-4" id="pallette">
                            <div class="panel panel-default mb0">
                                <div class="panel-body">
                                    <table class="table table-borderless mb0">
                                        <tr>
                                            <td class="full-width"><a
                                                    class="test-ques-stats que-not-attempted lblNotVisited">0</a>
                                                Not Visited
                                            </td>
                                            <td class="full-width"><a
                                                    class="test-ques-stats que-not-answered lblNotAttempted">0</a>
                                                Not Answered
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="full-width"><a
                                                    class="test-ques-stats que-save lblTotalSaved">0</a> Answered
                                            </td>

                                        </tr>

                                    </table>
                                </div>
                            </div>
                            <div class="panel panel-default ">
                                <div class="panel-body ">
                                    <ul class="pagination test-questions">
                                        <li class="active" data-seq="1"><a
                                                class="test-ques que-not-answered" href="javascript:void(0);"
                                                data-href="page01">01</a></li>
                                        <li data-seq="1"><a class="test-ques que-not-attempted"
                                                            href="javascript:void(0);" data-href="page02">02</a></li>
                                        <li data-seq="1"><a class="test-ques que-not-attempted"
                                                            href="javascript:void(0);" data-href="page03">03</a></li>
                                        <li data-seq="1"><a class="test-ques que-not-attempted"
                                                            href="javascript:void(0);" data-href="page04">04</a></li>
                                        <li data-seq="1"><a class="test-ques que-not-attempted"
                                                            href="javascript:void(0);" data-href="page05">05</a></li>
                                        <li data-seq="1"><a class="test-ques que-not-attempted"
                                                            href="javascript:void(0);" data-href="page06">06</a></li>
                                        <li data-seq="1"><a class="test-ques que-not-attempted"
                                                            href="javascript:void(0);" data-href="page07">07</a></li>
                                        <li data-seq="1"><a class="test-ques que-not-attempted"
                                                            href="javascript:void(0);" data-href="page08">08</a></li>
                                        <li data-seq="1"><a class="test-ques que-not-attempted"
                                                            href="javascript:void(0);" data-href="page09">09</a></li>
                                        <li data-seq="1"><a class="test-ques que-not-attempted"
                                                            href="javascript:void(0);" data-href="page10">10</a></li>
                                        <li data-seq="1"><a class="test-ques que-not-attempted"
                                                            href="javascript:void(0);" data-href="page11">11</a></li>
                                        <li data-seq="1"><a class="test-ques que-not-attempted"
                                                            href="javascript:void(0);" data-href="page12">12</a></li>
                                        <li data-seq="1"><a class="test-ques que-not-attempted"
                                                            href="javascript:void(0);" data-href="page13">13</a></li>
                                        <li data-seq="1"><a class="test-ques que-not-attempted"
                                                            href="javascript:void(0);" data-href="page14">14</a></li>
                                        <li data-seq="1"><a class="test-ques que-not-attempted"
                                                            href="javascript:void(0);" data-href="page15">15</a></li>
                                        <li data-seq="1"><a class="test-ques que-not-attempted"
                                                            href="javascript:void(0);" data-href="page16">16</a></li>
                                        <li data-seq="1"><a class="test-ques que-not-attempted"
                                                            href="javascript:void(0);" data-href="page17">17</a></li>
                                        <li data-seq="1"><a class="test-ques que-not-attempted"
                                                            href="javascript:void(0);" data-href="page18">18</a></li>
                                        <li data-seq="1"><a class="test-ques que-not-attempted"
                                                            href="javascript:void(0);" data-href="page19">19</a></li>
                                        <li data-seq="1"><a class="test-ques que-not-attempted"
                                                            href="javascript:void(0);" data-href="page20">20</a></li>
                                        <li data-seq="1"><a class="test-ques que-not-attempted"
                                                            href="javascript:void(0);" data-href="page21">21</a></li>
                                        <li data-seq="1"><a class="test-ques que-not-attempted"
                                                            href="javascript:void(0);" data-href="page22">22</a></li>
                                        <li data-seq="1"><a class="test-ques que-not-attempted"
                                                            href="javascript:void(0);" data-href="page23">23</a></li>
                                        <li data-seq="1"><a class="test-ques que-not-attempted"
                                                            href="javascript:void(0);" data-href="page24">24</a></li>
                                        <li data-seq="1"><a class="test-ques que-not-attempted"
                                                            href="javascript:void(0);" data-href="page25">25</a></li>
                                        <li data-seq="1"><a class="test-ques que-not-attempted"
                                                            href="javascript:void(0);" data-href="page26">26</a></li>
                                        <li data-seq="1"><a class="test-ques que-not-attempted"
                                                            href="javascript:void(0);" data-href="page27">27</a></li>
                                        <li data-seq="1"><a class="test-ques que-not-attempted"
                                                            href="javascript:void(0);" data-href="page28">28</a></li>
                                        <li data-seq="1"><a class="test-ques que-not-attempted"
                                                            href="javascript:void(0);" data-href="page29">29</a></li>
                                        <li data-seq="1"><a class="test-ques que-not-attempted"
                                                            href="javascript:void(0);" data-href="page30">30</a></li>
                                        <li data-seq="1"><a class="test-ques que-not-attempted"
                                                            href="javascript:void(0);" data-href="page31">31</a></li>
                                        <li data-seq="1"><a class="test-ques que-not-attempted"
                                                            href="javascript:void(0);" data-href="page32">32</a></li>
                                        <li data-seq="1"><a class="test-ques que-not-attempted"
                                                            href="javascript:void(0);" data-href="page33">33</a></li>
                                        <li data-seq="1"><a class="test-ques que-not-attempted"
                                                            href="javascript:void(0);" data-href="page34">34</a></li>
                                        <li data-seq="1"><a class="test-ques que-not-attempted"
                                                            href="javascript:void(0);" data-href="page35">35</a></li>
                                        <li data-seq="1"><a class="test-ques que-not-attempted"
                                                            href="javascript:void(0);" data-href="page36">36</a></li>
                                        <li data-seq="1"><a class="test-ques que-not-attempted"
                                                            href="javascript:void(0);" data-href="page37">37</a></li>
                                        <li data-seq="1"><a class="test-ques que-not-attempted"
                                                            href="javascript:void(0);" data-href="page38">38</a></li>
                                        <li data-seq="1"><a class="test-ques que-not-attempted"
                                                            href="javascript:void(0);" data-href="page39">39</a></li>
                                        <li data-seq="1"><a class="test-ques que-not-attempted"
                                                            href="javascript:void(0);" data-href="page40">40</a></li>
                                        <li data-seq="1"><a class="test-ques que-not-attempted"
                                                            href="javascript:void(0);" data-href="page41">41</a></li>
                                        <li data-seq="1"><a class="test-ques que-not-attempted"
                                                            href="javascript:void(0);" data-href="page42">42</a></li>
                                        <li data-seq="1"><a class="test-ques que-not-attempted"
                                                            href="javascript:void(0);" data-href="page43">43</a></li>
                                        <li data-seq="1"><a class="test-ques que-not-attempted"
                                                            href="javascript:void(0);" data-href="page44">44</a></li>
                                        <li data-seq="1"><a class="test-ques que-not-attempted"
                                                            href="javascript:void(0);" data-href="page45">45</a></li>
                                        <li data-seq="1"><a class="test-ques que-not-attempted"
                                                            href="javascript:void(0);" data-href="page46">46</a></li>
                                        <li data-seq="1"><a class="test-ques que-not-attempted"
                                                            href="javascript:void(0);" data-href="page47">47</a></li>
                                        <li data-seq="1"><a class="test-ques que-not-attempted"
                                                            href="javascript:void(0);" data-href="page48">48</a></li>
                                        <li data-seq="1"><a class="test-ques que-not-attempted"
                                                            href="javascript:void(0);" data-href="page49">49</a></li>
                                        <li data-seq="1"><a class="test-ques que-not-attempted"
                                                            href="javascript:void(0);" data-href="page50">50</a></li>
                                        <li data-seq="1"><a class="test-ques que-not-attempted"
                                                            href="javascript:void(0);" data-href="page51">51</a></li>
                                        <li data-seq="1"><a class="test-ques que-not-attempted"
                                                            href="javascript:void(0);" data-href="page52">52</a></li>
                                        <li data-seq="1"><a class="test-ques que-not-attempted"
                                                            href="javascript:void(0);" data-href="page53">53</a></li>
                                        <li data-seq="1"><a class="test-ques que-not-attempted"
                                                            href="javascript:void(0);" data-href="page54">54</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 exam-confirm" style="display:none;">
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <div class="col-md-12 text-center">
                                        <h4> Are you sure you want to submit for final marking? <br/>No changes
                                            will be allowed after submission. <br/></h4>
                                        <a class="btn btn-default btn-lg" id="btnYesSubmitConfirm">Ok</a> <a
                                            class="btn btn-default btn-lg" id="btnNoSubmitConfirm">Cancel</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 exam-thankyou" style="display:none;">
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <div class="col-md-12 text-center">
                                        <h4>Thank you, Submitted Successfully.</h4>
                                        <a class="btn btn-default btn-lg" id="btnClosePage">Close!</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                @endif
            </section>
        </div>
    </div>

    <div id="copyright" style="background-color:#012B55">
        <div class="container">
            <div class="col-md-12">
                <p class="text-center">© All Rights Reserved - VNR Recruitment</p>
            </div>
        </div>
    </div>
</div>
<input type="hidden" name="exam_id" id="exam_id" value="{{ $exam_id }}">
<script src="{{ URL::to('/') }}/assets/js/jquery.min.js"></script>
<script src="{{ URL::to('/') }}/assets/js/bootstrap.bundle.min.js"></script>

<script>
    $('.full_screen').click(function () {
        //alert('ff');
        $('#quest').removeClass('col-md-8');
        $('#quest').addClass('col-md-12');
        //pallette
        $('#pallette').addClass('hidden');
        $('.full_screen').addClass('hidden');
        $('.collapse_screen').removeClass('hidden');
    });

    $('.collapse_screen').click(function () {
        $('#quest').removeClass('col-md-12');
        $('#quest').addClass('col-md-8');
        //pallette
        $('#pallette').removeClass('hidden');
        $('.full_screen').removeClass('hidden');
        $('.collapse_screen').addClass('hidden');

    });

    var myInterval,
        AttemptedAns = [],
        TotalTime = 0;

    function NextQuestion(e) {
        var t = $(".test-questions").find("li.active");
        if ((CheckNextPrevButtons(), t.is(":last-child"))) return !1;
        $(".test-questions").find("li").removeClass("active"),
            t.next().addClass("active"),
            OpenCurrentQue(t.next().find("a")),
        e &&
        (t.find("a").addClass("que-not-answered"),
            t.find("a").removeClass("que-not-attempted"));
        var a = t.attr("data-seq");
        $(".nav-tab-sections").find("li").removeClass("active"),
            $(".nav-tab-sections")
                .find("li[data-id=" + a + "]")
                .addClass("active"),
            CheckQueAttemptStatus();
        //var t1 = $(".test-questions").find("li.active");
        // var a = t1.find("a").removeClass("que-not-attempted").addClass("que-not-answered");
    }

    function PrevQuestion(e) {
        var t = $(".test-questions").find("li.active");
        if ((CheckNextPrevButtons(), t.is(":first-child"))) return !1;
        $(".test-questions").find("li").removeClass("active"),
            t.prev().addClass("active"),
            OpenCurrentQue(t.prev().find("a"));
        var a = t.attr("data-seq");
        $(".nav-tab-sections").find("li").removeClass("active"),
            $(".nav-tab-sections")
                .find("li[data-id=" + a + "]")
                .addClass("active"),
            CheckQueAttemptStatus();
    }

    function CheckNextPrevButtons() {
        var e = $(".test-questions").find("li.active");
        $("#btnPrevQue").removeAttr("disabled"),
            $("#btnNextQue").removeAttr("disabled"),
            e.is(":first-child") ?
                $("#btnPrevQue").attr("disabled", "disabled") :
                e.is(":last-child") &&
                $("#btnNextQue").attr("disabled", "disabled");
    }

    function pad(e, t) {
        for (var a = e + ""; a.length < t;) a = "0" + a;
        return a;
    }

    function OpenCurrentQue(e) {
        $(".tab-content").hide(),
            $("#lblQueNumber").text(e.text()),
            $("#" + e.attr("data-href")).show();
        var t = e.parent().attr("data-seq");
        $(".nav-tab-sections").find("li").removeClass("active"),
            $(".nav-tab-sections")
                .find("li[data-id=" + t + "]")
                .addClass("active"),
            CheckQueAttemptStatus();
    }

    function CoundownTimer(e) {
        var t = 60 * e;
        myInterval = setInterval(function () {
            (myTimeSpan = 1e3 * t),
                $(".timer-title").text(GetTime(myTimeSpan)),
                t < 600 ?
                    ($(".timer-title").addClass("time-ending"),
                        $(".timer-title").removeClass("time-started")) :
                    ($(".timer-title").addClass("time-started"),
                        $(".timer-title").removeClass("time-ending")),
                t > 0 ? (t -= 1) : CleartTimer();
        }, 1e3);
    }

    function CleartTimer() {
        clearInterval(myInterval),
            $("title").text("Time Out"),
            $("#btnYesSubmitConfirm").trigger("click");
    }

    function GetTime(e) {
        parseInt((e % 1e3) / 100);
        var t = parseInt((e / 1e3) % 60),
            a = parseInt((e / 6e4) % 60),
            n = parseInt((e / 36e5) % 24);
        return (
            (n = n < 10 ? "0" + n : n) +
            ":" +
            (a = a < 10 ? "0" + a : a) +
            ":" +
            (t < 10 ? "0" + t : t)
        );
    }

    function pretty_time_string(e) {
        return (e < 10 ? "0" : "") + e;
    }

    function CheckQueExists(e) {
        $.each(AttemptedAns, function (t, a) {
            void 0 !== a && a[1] == e && AttemptedAns.splice(t, 1);
        });
    }

    function CheckQueAttemptStatus() {
        var e = 0,
            t = 0,
            a = 0,
            n = 0,
            s = 0,
            i = 0;
        $(".test-questions")
            .find("li")
            .each(function () {
                var r = $(this);
                (e += 1),
                    r.children().hasClass("que-save") ?
                        (a += 1) :
                        r.children().hasClass("que-save-mark") ?
                            (n += 1) :
                            r.children().hasClass("que-mark") ?
                                (s += 1) :
                                r.children().hasClass("que-not-answered") ?
                                    (t += 1) :
                                    (i += 1);
            }),
            $(".lblTotalQuestion").text(e),
            $(".lblNotAttempted").text(t),
            $(".lblTotalSaved").text(a),
            $(".lblTotalSaveMarkForReview").text(n),
            $(".lblTotalMarkForReview").text(s),
            $(".lblNotVisited").text(i);
    }

    $(document).ready(function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $("#page01").show();
        $(".exam-paper").show();
        CoundownTimer(parseInt($("#hdfTestDuration").val()));
        CheckNextPrevButtons();
        CheckQueAttemptStatus();
        $("#btnPrevQue").click(function () {
            PrevQuestion(!0);
        });
        $("#btnNextQue").click(function () {
            NextQuestion(!0);
        });

        /* $(".test-ques").click(function() {
            var e = $(".test-questions").find("li.active").find("a");
            $(".test-questions").find("li").removeClass("active"),
                $(this).parent().addClass("active"),
                $(this).hasClass("que-save") ||
                $(this).hasClass("que-save-mark") ||
                $(this).hasClass("que-mark") ||
                ($(this).addClass("que-not-answered"),
                    $(this).removeClass("que-not-attempted")),
                e.hasClass("que-save") ||
                e.hasClass("que-save-mark") ||
                e.hasClass("que-mark") ||
                (e.addClass("que-not-answered"),
                    e.removeClass("que-not-attempted")),
                OpenCurrentQue($(this));
        }); */
        let Count = 1;
        $(".btn-save-answer").click(function (e) {
            e.preventDefault();
            var t = $(".test-questions").find("li.active"),
                a = t.find("a").attr("data-href"),
                n =
                    ($("#" + a)
                        .find(".hdfQuestionID")
                        .val(),
                        $("#" + a)
                            .find(".hdfPaperSetID")
                            .val(),
                        $("#" + a)
                            .find(".hdfCurrectAns")
                            .val(),
                        !1);
            if (
                ($("input[name='radios" + a + "']").each(function () {
                    $(this).is(":checked") && (n = !0);
                }),
                0 == n)
            ) {
                alert("Please choose an option");
                return !1;
            }
            $("input[name='radios" + a + "']:checked").val(),
                t.find("a").removeClass("que-save-mark"),
                t.find("a").removeClass("que-mark"),
                t.find("a").addClass("que-save"),
                t.find("a").removeClass("que-not-answered"),
                t.find("a").removeClass("que-not-attempted"),
                NextQuestion(!1),
                CheckQueAttemptStatus();


            var Que = $("#" + a)
                .find(".hdfQuestionID")
                .val();
            var Ans = "";
            $("#" + a)
                .find("input[name='radios" + a + "']")
                .each(function () {
                    var e = $(this);
                    if (e.is(":checked")) {
                        Ans = e.val();
                    }
                });

            var url = '<?= route('firob_save_answer') ?>';
            $.post(url, {

                JCId: $('#JCId').val(),
                Que: Que,
                Ans: Ans,
            }, function (data) {
                if (data.status == 200) {
                    console.log(data.msg);
                } else {
                    console.log(data.msg);
                }
            }, 'json');

            Count++;
            if (Count == 55) {
                $(".btn-submit-all-answers").css("display", "block");
            }
        });

        $(".btn-reset-answer").click(function (e) {
            e.preventDefault();
            var t = $(".test-questions").find("li.active"),
                a = t.find("a").attr("data-href");
            $("#" + a).attr("data-queid"),
                t.find("a").removeClass("saved-que"),
                $("input[name='radios" + a + "']:checked").each(function () {
                    $(this).prop("checked", !1).change();
                }),
                $("input[name='chk" + a + "']").each(function () {
                    $(this).prop("checked", !1).change();
                }),
                $("input[type=checkbox]").prop("checked", !1).change(),
                $("input[type=text]").val(""),
                (a = t.find("a").attr("data-href")),
                $("#" + a)
                    .find(".hdfQuestionID")
                    .val(),
                $("#" + a)
                    .find(".hdfPaperSetID")
                    .val(),
                $("#" + a)
                    .find(".hdfCurrectAns")
                    .val(),
                $("#" + a)
                    .find(".hdfCurrectAns")
                    .val(),
                t.find("a").removeClass("que-save-mark"),
                t.find("a").removeClass("que-mark"),
                t.find("a").removeClass("que-save"),
                t.find("a").removeClass("que-not-attempted"),
                t.find("a").addClass("que-not-answered"),
                //NextQuestion(!1),
                CheckQueAttemptStatus();
        });

        $(".btn-submit-all-answers").click(function (e) {
            e.preventDefault(),
                $(this),
                $(".test-questions")
                    .find("li")
                    .each(function () {
                        var e = $(this),
                            t = !1;
                        if (
                            (e.children().hasClass("que-save") ?
                                (t = !0) :
                                e.children().hasClass("que-save-mark") &&
                                (t = !0),
                                t)
                        ) {
                            var a = e.find("a").attr("data-href");

                            $("#" + a)
                                .find(".hdfCurrectAns")
                                .val();
                            $("#" + a)
                                .find("input[name='radios" + a + "']")
                                .each(function () {
                                    var e = $(this);
                                    e.is(":checked") && e.val();
                                });
                        }
                    }),
                $(".exam-paper").hide(),
                $(".exam-confirm").show(),
                CheckQueAttemptStatus();
        });

        $("#btnYesSubmitConfirm").on("click", function (e) {
            e.preventDefault(),
                $(".exam-confirm").hide();

            var url = '<?= route('firob_submit_exam') ?>';
            $.post(url, {
                JCId: $('#JCId').val(),
            }, function (data) {
                if (data.status == 200) {
                    $(".exam-thankyou").show();
                } else {
                    console.log(data.msg);
                }
            }, 'json');
        });

        $("#btnNoSubmitConfirm").on("click", function (e) {
            e.preventDefault(),
                $(".exam-paper").show(),
                $(".exam-confirm").hide();
        });
        $("#btnClosePage").on("click", function (e) {

            if ($("#exam_id").val() != '') {
                window.location.href = "{{ route('candidate_assessment_select_paper') }}?jcid={{ request()->query('jcid') }}&exam_id={{ request()->query('exam_id') }}";
            } else {
                window.location.href = "{{ route('firo_b') }}?jcid={{ request()->query('jcid') }}";
                }

            });
        });
    </script>

</body>

</html>
