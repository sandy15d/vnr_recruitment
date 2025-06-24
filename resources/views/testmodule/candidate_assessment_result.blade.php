@php
    $JCId = request()->get('JCId');
    $Paper = request()->get('Paper');
    $paper_name = DB::table('subject_master')->where('id', $Paper)->first(['subject_name']);
    $total_mark = '20';
    $mark_obtained = DB::table('candidate_assessment')
        ->where('jcid', $JCId)
        ->where('paper_id', $Paper)
        ->where('mark', '1')
        ->count('mark');
    $candidate_detail = DB::table('jobcandidates')->where('JCId', $JCId)->first(['ReferenceNo','FName', 'MName', 'LName', 'Email', 'Phone']);

    $question_list = DB::table('question_banks')
        ->join('candidate_assessment', function ($join) {
            $join->on('question_banks.subject_id', '=', 'candidate_assessment.paper_id')
            ->on('question_banks.id', '=', 'candidate_assessment.q_id');
        })
        ->select('candidate_assessment.question_id', 'question_banks.*', 'candidate_assessment.answer', 'candidate_assessment.mark')
        ->where('candidate_assessment.jcid', $JCId)
        ->where('candidate_assessment.paper_id', $Paper)
        ->groupBy('candidate_assessment.question_id')
        ->get();

@endphp
        <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Candidate Assessment Test</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

</head>

<body>
<div class="main-page">
    <div class="sub-page">
        <div class="row">
            <p class="text-center fw-bold" style="margin-bottom: 0">Candidate Assessment Test</p>
            <p class="text-center fw-bold">Exam: {{$paper_name->subject_name}}</p>
        </div>


        <div>
            <div style="float: left; width: 33%; text-align: left;"><strong>Candidate:</strong> {{$candidate_detail->FName}} {{$candidate_detail->MName}} {{$candidate_detail->LName}}</div>
            <div style="float: left; width: 33%; text-align: center;"><strong>Ref.No:</strong> {{$candidate_detail->ReferenceNo}}</div>
            <div style="float: right; width: 33%; text-align: right;"><strong>Marks Obtained:</strong> {{$mark_obtained}}</div>
        </div>
        <hr style="margin-top: 0"/>

        @php
            $no = 1;
        @endphp
        @foreach ($question_list as $item)

            <div id="{{$loop->iteration}}" class="tab-content div-question mb1">
                <div class="question-height">
                    <table style="margin-bottom: 10px;">
                        <tr>
                            <td style="vertical-align: top;">Q.{{$no}} &nbsp;</td>
                            <td> {!! $item->question !!}</td>
                        </tr>
                    </table>

                    @if($item->question_type == 'Descriptive')
                        <p class="question-answer">Answer: {!! $item->answer !!}</p>
                    @elseif($item->question_type =='True/False')
                        <table class="table table-borderless mb1">
                            <tbody>
                            <tr>
                                <td>
                                    <table>
                                        <tr>
                                            <td style="vertical-align: top;">A)</td>
                                            <td>
                                            <span style="color: {{$item->answer == 'option_a' ? 'blue' : ''}};font-weight: {{$item->answer == 'option_a' ? 'bold' : 'normal'}}">
                                                {!! $item->option_a !!}
                                                <i style="display: {{$item->correct_option == 'option_a' ? 'inline' : 'none'}}">*</i>
                                            </span>
                                            </td>

                                        </tr>
                                    </table>
                                </td>
                                <td>
                                    <table>
                                        <tr>
                                            <td style="vertical-align: top;">A)</td>
                                            <td>
                                            <span style="color: {{$item->answer == 'option_a' ? 'blue' : ''}};font-weight: {{$item->answer == 'option_a' ? 'bold' : 'normal'}}">
                                                {!! $item->option_a !!}
                                                <i style="display: {{$item->correct_option == 'option_a' ? 'inline' : 'none'}}">*</i>
                                            </span>
                                            </td>

                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    @elseif($item->question_type =='MCQ')
                        <table class="table table-borderless mb1">
                            <tbody>
                            <tr>
                                <td>
                                    <table>
                                        <tr>
                                            <td style="vertical-align: top;">A)</td>
                                            <td>
                                            <span style="color: {{$item->answer == 'option_a' ? 'blue' : ''}};font-weight: {{$item->answer == 'option_a' ? 'bold' : 'normal'}}">
                                                {!! $item->option_a !!}
                                                <i style="display: {{$item->correct_option == 'option_a' ? 'inline' : 'none'}}">*</i>
                                            </span>
                                            </td>

                                        </tr>
                                    </table>
                                </td>
                                <td>
                                    <table>
                                        <tr>
                                            <td style="vertical-align: top;">B)</td>
                                            <td>
                                            <span style="color: {{$item->answer == 'option_b' ? 'blue' : ''}};font-weight: {{$item->answer == 'option_b' ? 'bold' : 'normal'}}">
                                                {!! $item->option_b !!}
                                            </span>
                                                <i style="display: {{$item->correct_option == 'option_b' ? 'inline' : 'none'}}">*</i>
                                            </td>

                                        </tr>
                                    </table>
                                </td>
                                <td>
                                    <table>
                                        <tr>
                                            <td style="vertical-align: top;">C)</td>
                                            <td>
                                            <span style="color: {{$item->answer == 'option_c' ? 'blue' : ''}};font-weight: {{$item->answer == 'option_c' ? 'bold' : 'normal'}}">
                                                {!! $item->option_c !!}
                                            </span>
                                                <i style="display: {{$item->correct_option == 'option_c' ? 'inline' : 'none'}}">*</i>
                                            </td>

                                        </tr>
                                    </table>
                                </td>
                                <td>
                                    <table>
                                        <tr>
                                            <td style="vertical-align: top;">D)</td>
                                            <td>
                                            <span style="color: {{$item->answer == 'option_d' ? 'blue' : ''}};font-weight: {{$item->answer == 'option_d' ? 'bold' : 'normal'}}">
                                                {!! $item->option_d !!}
                                                <i style="display: {{$item->correct_option == 'option_d' ? 'inline' : 'none'}}">*</i>
                                            </span>
                                            </td>

                                        </tr>
                                    </table>
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


    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
        crossorigin="anonymous"></script>
</body>

</html>
