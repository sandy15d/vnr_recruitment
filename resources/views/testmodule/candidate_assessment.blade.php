@php
    $JCId = base64_decode(request()->jcid);
    $exam_id = base64_decode(request()->exam_id);
    $paper = base64_decode(request()->paper);

    $exam_detail = DB::table('exam_masters')->where('id', $exam_id)->first();
    $paper_name = DB::table('subject_master')->where('id', $paper)->value('subject_name');

    $candidate = DB::table('jobcandidates')
             ->where('JCId', $JCId)
             ->select('FName', 'MName', 'LName', 'ReferenceNo')
             ->first();
    $questions = DB::table('question_banks')->where('subject_id', $paper)->get();
    $total_que = count($questions);
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
    <script src="{{ URL::to('/') }}/assets/ckeditor/ckeditor.js"></script>
    <link rel="stylesheet" href="{{ URL::to('/') }}/assets/css/sweetalert2.min.css"/>
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
                            <input type="hidden" id="hdfTestDuration" value="{{$exam_detail->time}}"/>
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
                                            @foreach ($questions as $item)

                                                <div id="{{$loop->iteration}}"
                                                     class="tab-content div-question mb0"
                                                     style="display: {{ $no == 1 ? 'block' : 'none' }}">

                                                    <input class="hdfQuestionID" value="{{ $no }}" type="hidden">
                                                    <input type="hidden" class="qBankId" value="{{ $item->id }}">
                                                    <input class="hdfQuestionType" value="{{ $item->question_type }}"
                                                           type="hidden">

                                                    <div class="question-height">
                                                        <h4 class="question-title"> Question {{ $no }}:
                                                            <br> <br>
                                                                <?php echo '<font size="4">' . $item->question . '</font>'; ?>


                                                        </h4>
                                                        <br>
                                                        @if($item->question_type == 'Descriptive')
                                                            <textarea class="form-control text_editor" rows="5"
                                                                      id="answer{{ $no }}"
                                                                      name="answer{{$no}}"></textarea>
                                                        @elseif($item->question_type =='True/False')
                                                            <table class="table table-borderless mb0">
                                                                <tbody>
                                                                <tr>
                                                                    <td>A ) <input
                                                                            id="rOption{{ $no }}_true"
                                                                            name="answer{{$no}}"
                                                                            value="True" type="radio">
                                                                        <label
                                                                            for="rOption{{ $no }}_true">True</label>
                                                                    </td>
                                                                    <td>B ) <input
                                                                            id="rOption{{ $no }}_false"
                                                                            name="answer{{$no}}"
                                                                            value="False" type="radio">
                                                                        <label
                                                                            for="rOption{{ $no }}_false">False</label>
                                                                    </td>


                                                                </tr>
                                                                </tbody>
                                                            </table>
                                                        @elseif($item->question_type =='MCQ')
                                                            <table class="table table-borderless mb0">
                                                                <tbody>
                                                                <tr>
                                                                    <td>A ) <input
                                                                            id="rOption{{ $no }}_a"
                                                                            name="answer{{$no}}"
                                                                            value="option_a" type="radio">
                                                                        <label
                                                                            for="rOption{{ $no }}_a">{!! $item->option_a !!}</label>
                                                                    </td>
                                                                    <td>B ) <input
                                                                            id="rOption{{ $no }}_b"
                                                                            name="answer{{$no}}"
                                                                            value="option_b" type="radio">
                                                                        <label
                                                                            for="rOption{{ $no }}_b">{!! $item->option_b !!}</label>
                                                                    </td>
                                                                    <td>C ) <input
                                                                            id="rOption{{ $no }}_c"
                                                                            name="answer{{$no}}"
                                                                            value="option_c" type="radio">
                                                                        <label
                                                                            for="rOption{{ $no }}_c">{!! $item->option_c !!}</label>
                                                                    </td>


                                                                    <td>D ) <input
                                                                            id="rOption{{ $no }}_d"
                                                                            name="answer{{$no}}"
                                                                            value="option_d" type="radio">
                                                                        <label
                                                                            for="rOption{{ $no }}_d">{!! $item->option_d !!}</label>
                                                                    </td>

                                                                </tr>
                                                                </tbody>
                                                            </table>
                                                        @endif


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
                                    @foreach($questions as $row)
                                        @if($loop->first)
                                            <li class="active" data-seq="{{$loop->iteration}}">
                                                <a
                                                    class="test-ques que-not-answered" href="javascript:void(0);"
                                                    data-href="{{$loop->iteration}}">{{$loop->iteration}}</a>

                                            </li>
                                        @else
                                            <li data-seq="{{$loop->iteration}}"><a class="test-ques que-not-attempted"
                                                                                   href="javascript:void(0);"
                                                                                   data-href="{{$loop->iteration}}">{{$loop->iteration}}</a>
                                            </li>
                                        @endif
                                    @endforeach
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

            </section>
        </div>
    </div>

    {{--<div id="copyright" style="background-color:#012B55">
        <div class="container">
            <div class="col-md-12">
                <p class="text-center">© All Rights Reserved - VNR Recruitment</p>
            </div>
        </div>
    </div>--}}
</div>


<input type="hidden" name="total_que" id="total_que" value="{{$total_que}}"/>
<script src="{{ URL::to('/') }}/assets/js/jquery.min.js"></script>
<script src="{{ URL::to('/') }}/assets/js/bootstrap.bundle.min.js"></script>
<script src="{{ URL::to('/') }}/assets/js/sweetalert2.min.js"></script>
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
        console.log(t);
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

        /*        $(".test-ques").click(function () {
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
                });*/
        let Count = 0;
        $(".btn-save-answer").click(function (e) {
            e.preventDefault();
            var t = $(".test-questions").find("li.active"),
                a = t.find("a").attr("data-href"),
                n =
                    ($("#" + a).find(".hdfQuestionID").val(),
                        $("#" + a).find(".hdfPaperSetID").val(),
                        $("#" + a).find(".hdfCurrectAns").val(),
                        !1);
            var Type = $("#" + a).find(".hdfQuestionType").val();
            if (Type == 'MCQ' || Type == 'True/False') {
                if (
                    ($("input[name='answer" + a + "']").each(function () {
                        $(this).is(":checked") && (n = !0);
                    }),
                    0 == n)
                ) {
                    alert("Please choose an option");
                    return !1;
                }
            } else if (Type == 'Descriptive') {
                $("#answer" + a).val() == '' ? alert('Please write an answer') : (n = !0);
            }

            $("input[name='answer" + a + "']:checked").val(),
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
            var qBankId = $("#" + a)
                .find(".qBankId")
                .val();

            var Ans = "";
            if (Type == 'MCQ' || Type == 'True/False') {
                $("#" + a).find("input[name='answer" + a + "']").each(function () {
                    var e = $(this);
                    if (e.is(":checked")) {
                        Ans = e.val();
                    }
                });
            } else {
                Ans = $("#answer" + a).val();
            }


            var url = '<?= route('candidate_assessment_save') ?>';
            $.post(url, {
                JCId: $('#JCId').val(),
                qBankId: qBankId,
                Que: Que,
                Ans: Ans,
                Exam_Id: {{ $exam_id }},
                Type: Type,
                Paper: {{ $paper }}
            }, function (data) {
                if (data.status == 200) {
                    console.log(data.msg);
                } else {
                    console.log(data.msg);
                }
            }, 'json');

            Count++;
            if (Count == {{ $total_que }}) {
                $(".btn-submit-all-answers").css("display", "block");
            }
        });

        $(".btn-reset-answer").click(function (e) {
            e.preventDefault();
            var t = $(".test-questions").find("li.active"),
                a = t.find("a").attr("data-href");
            $("#" + a).attr("data-queid"),
                t.find("a").removeClass("saved-que"),
                $("input[name='answer" + a + "']:checked").each(function () {
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

            var url = '<?= route('candidate_assessment_final_submit') ?>';
            $.post(url, {
                JCId: $('#JCId').val(),
                Exam_Id: {{ $exam_id }},
                Paper: {{ $paper }}
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
            window.location.href = "{{ route('candidate_assessment_select_paper') }}?jcid={{ request()->query('jcid') }}&exam_id={{ request()->query('exam_id') }}";
        });
    });
    var MaxSwitch = "{{$exam_detail->max_alert}}";
    var isAlertDisplayed = false;
    $(window).blur(function () {
        MaxSwitch--;
        Swal.fire({
            title: "Not Allowed!",
            text: "You can't switch to another tab while exam is on going.\nYou have " + MaxSwitch + " chances left.",
            icon: "warning"
        });

        stopExam("Window Changed");
    });


    function stopExam(reason) {
        if (MaxSwitch < 0) {
            var url = '<?= route('candidate_assessment_stop') ?>';
            $.post(url, {
                JCId: $('#JCId').val(),
                Exam_Id: {{ $exam_id }},
                Paper: {{ $paper }},
                reason: reason
            }, function (data) {
                if (data.status == 200) {
                    window.location.href = "{{ route('candidate_assessment_select_paper') }}?jcid={{ request()->query('jcid') }}&exam_id={{ request()->query('exam_id') }}";
                } else {
                    console.log(data.msg);
                }
            }, 'json');
        }
    }

    // Set the time limit for the assessment reminder (in milliseconds)
    var reminderTime = {{$exam_detail->time_reminder}} * 60 * 1000;

    // Function to display the reminder message
    function showReminder() {
        MaxSwitch++;
       Swal.fire({
           title:'Reminder',
           text:"{{$exam_detail->time_reminder}} minutes left",
           icon:'info'
       });
    }

    // Set a timeout to display the reminder after the specified time
    setTimeout(showReminder, reminderTime);

    /*    // Optionally, you can also stop the assessment when the time limit is reached
        var assessmentTimeLimit = reminderTime + 10000; // Example: 10 minutes + 10 seconds
        setTimeout(function() {
            // Stop the assessment or perform any other action when the time limit is reached
            alert("Time's up! Please submit your assessment.");
        }, assessmentTimeLimit);*/
</script>

</body>

</html>
