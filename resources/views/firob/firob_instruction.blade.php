@php
    $JCId = base64_decode(request()->query('jcid'));
    $query = DB::table('jobcandidates')
        ->where('JCId', $JCId)
        ->select('FName', 'MName', 'LName', 'ReferenceNo', 'CandidateImage')
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
    <link rel="shortcut icon" href="{{ URL::to('/') }}/assets/firob/img/favicon.ico">
    <title>FIRO B</title>
    <link rel="stylesheet" href="{{ URL::to('/') }}/assets/firob/css/style.default.css" type="text/css"/>
    <link href="{{ URL::to('/') }}/assets/firob/css/font-awesome.min.css" rel="stylesheet"/>
    <link href="{{ URL::to('/') }}/assets/firob/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="{{ URL::to('/') }}/assets/firob/css/custom.css" rel="stylesheet"/>

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
                            <ul class="nav navbar-nav pull-right exam-paper ">
                                <li class="">
                                    <table>
                                        <tr>
                                            @if ($query->CandidateImage == null)
                                                <td style="padding: 5px 15px; border: 2px solid #666"><i
                                                        class="fa fa-user fa-4x"></i></td>
                                            @else
                                                <td style="border: 2px solid #f09a3e; "><img
                                                        src="{{ url('file-view/Picture/' . $query->CandidateImage) }}"
                                                        style="width: 80px;" height="80px;"/></td>
                                            @endif


                                            <td>
                                                <table>
                                                    <tr>
                                                        <td style="padding: 5px 5px;">Candidate Name</td>
                                                        <td> : <span
                                                                style="color: #f09a3e; font-weight: bold">{{ $query->FName }}
                                                                {{ $query->MName }} {{ $query->LName }}</span></td>
                                                    </tr>
                                                    <tr>
                                                        <td style="padding: 5px 5px;">Reference No</td>
                                                        <td> : <span
                                                                style="color: #f09a3e; font-weight: bold">{{ $query->ReferenceNo }}</span>
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
    <div class="clear"></div>
    <div>
        <div id="heading-breadcrumbs">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <h1 class="pull-left">General Instructions</h1>
                        <div class="pull-right" style="padding: 0">
                            <label style="color: #fff;"> Choose Your Language</label>
                            <select class="form-control" onChange="changeIndtruct(this.value)">
                                <option value="en">English</option>
                                <option value="hi">Hindi</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="content">
            <div class="container">
                <section>
                    <div class="row">
                        <div class="col-md-12 exam-confirm">
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <div class="col-md-12" id="en">
                                        <h4 class="text-center" style="font-weight: bold;">Thank you for
                                            participating in the FIRO-B assessment.</h4>
                                        <h4 class="text-center">Please read the following instructions carefully
                                            before you begin:</h4>
                                        <h4><strong><u>General Instructions:</u></strong></h4>
                                        <ol style="text-align: justify">
                                            <li>A time limit of 20 minutes is allocated for completing the
                                                assessment. Please
                                                manage your time effectively.
                                            </li>
                                            <li>There are no right or wrong answers.</li>
                                            <li>Be as honest as possible when responding to the questions. Your true
                                                preferences
                                                will provide the most accurate insights.
                                            </li>
                                            <li>Complete the assessment in a quiet and focused environment to ensure
                                                accurate
                                                results.
                                            </li>
                                            <li>The assessment consists of a series of statements. You will be asked
                                                to rate each
                                                statement based on how accurately it reflects your feelings and
                                                behaviours.
                                            </li>
                                            <li>Select the related number against the statement (e.g. 1 for Usually
                                                and 2 for Often or
                                                1 for Most People and 2 for Many people.
                                            </li>
                                            <li>Please read each statement carefully before selecting your response.
                                            </li>
                                            <li>If you are unsure about a statement, trust your initial instincts
                                                and provide the
                                                response that feels most accurate to you.
                                            </li>
                                            <li>The countdown timer located in the top right corner of the screen
                                                will show the time
                                                remaining for you to complete the examination. Once the timer
                                                reaches zero, the
                                                examination will automatically close.
                                            </li>
                                            <li>Your responses are strictly confidential. Your results will be
                                                shared only with those
                                                authorized to view them.
                                            </li>

                                            <li>
                                                The Questions Palette, visible on the right side of the screen, will
                                                indicate the status
                                                of each question through one of the following symbols:
                                                <ul type="none">
                                                    <li><img
                                                            src="{{ URL::to('/') }}/assets/firob/img/QuizIcons/Logo1.png"/>
                                                        Not Seen &emsp14;&emsp14;&emsp14;<img
                                                            src="{{ URL::to('/') }}/assets/firob/img/QuizIcons/Logo2.png"/>
                                                        Not Answered &emsp14;&emsp14;&emsp14;<img
                                                            src="{{ URL::to('/') }}/assets/firob/img/QuizIcons/Logo3.png"/>
                                                        Answered<br/><br/></li>


                                                </ul>
                                            </li>


                                        </ol>
                                        <h4><strong><u>Navigating to a Question:</u></strong></h4>


                                        <p> To answer a question, follow these steps:</p>
                                        <ol>
                                            <li>Click on the question number within the Question Palette on the
                                                right side of your
                                                screen to navigate directly to the selected question. Please note
                                                that using this option
                                                will not save your answer for the current question.
                                            </li>
                                            <li>Click on <strong>Save & Next</strong> to save your answer
                                                for the current question and proceed to the next question.
                                            </li>

                                        </ol>


                                        <h4><strong><u>Instructions for responding to multiple-choice
                                                    questions:</u></strong></h4>
                                        <ol>
                                            <li>To select your answer, click on one of the option buttons.</li>
                                            <li>To deselect your chosen answer, use the &#39;Clear Response&#39;
                                                button.
                                            </li>
                                            <li>To change your chosen answer, click on the button of another option.
                                            </li>
                                            <li>If you wish to change your response to a previously answered
                                                question, first select
                                                that question for review and then follow the appropriate procedure
                                                for that question
                                                type.
                                            </li>
                                        </ol>

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

                                    <div class="col-md-12" id="hi" style="display: none">
                                        <h4 class="text-center" style="font-weight: bold;">FIRO-B मूल्यांकन में भाग लेने
                                            के लिए धन्यवाद।</h4>
                                        <h4 class="text-center">शुरू करे से पहले कृपया आप निम्नलिखित निर्देशों को ध्यान
                                            से पढ़ें:</h4>
                                        <h4><strong><u>सामान्य निर्देश:</u></strong></h4>
                                        <ol style="text-align: justify">
                                            <li> मूल्यांकन पूरा करने के लिए 20 मिनट का समय दिया गया है। कृपया अपने समय
                                                का उचित उपयोग करें।
                                            </li>
                                            <li>यहाँ कोई सही या गलत उत्तर नहीं है।</li>
                                            <li>कृपया सवालों के जवाब देते समय ईमानदार रहें। आपकी वास्तविक पसंद हमें सबसे
                                                सटीक एवं प्रासंगिक जानकारी प्रदान करेगी।
                                            </li>
                                            <li>सटीक परिणाम सुनिश्चित करने के लिए परीक्षण को एक शांत और ध्यान केंद्रित
                                                पर्यावरण में पूरा करें।
                                            </li>
                                            <li>मूल्यांकन में एक सिरीज के कथनों का संचयन होता है। प्रत्येक कथन को आपको
                                                उनके भावनाओं और व्यवहार को कितने सटीकता से प्रकट करता है उसके आधार पर
                                                रेट करने का अनुरोध किया जाएगा।
                                            </li>
                                            <li>नंबर का चयन करें (उदाहरण के लिए, 1 आमतौर पर और 2 अक्सर के लिए या 1
                                                अधिकांश लोगों के लिए और 2 बहुत सारे लोगों के लिए।
                                            </li>
                                            <li>कृपया प्रत्येक कथन को आपके उत्तर का चयन करने से पहले ध्यान से पढ़ें।
                                            </li>
                                            <li>यदि किसी कथन के बारे में आपको संदेह हो, तो अपनी प्रारंभिक प्रवृत्ति पर
                                                भरोसा
                                                करें और उस उत्तर का चयन करें जो आपके लिए सबसे सटीक लगता है।
                                            </li>
                                            <li>स्क्रीन के ऊपरी दाएं कोने पर स्थित टाइमर आपको मूल्यांकन पूरा करने के लिए
                                                शेष बचे हुए समय को दिखाएगा। एक बार टाइमर शून्य पर पहुँच जाता है, तो
                                                मूल्यांकन अपने आप से समाप्त हो जाएगा।
                                            </li>
                                            <li>आपके उत्तर अत्यंत गोपनीय हैं। आपके परिणाम को केवल उनके द्वारा अधिकृत
                                                व्यक्तियों के साथ साझा किया जाएगा।
                                            </li>

                                            <li>
                                                स्क्रीन के दाएं ओर दिखाई देने वाले प्रश्न पैलेट द्वारा प्रत्येक प्रश्न
                                                की स्थिति को
                                                निम्नलिखित प्रतीकों में से दिखाया जाएगा:
                                                <ul type="none">
                                                    <li><img
                                                            src="{{ URL::to('/') }}/assets/firob/img/QuizIcons/Logo1.png"/>
                                                         नहीं देखा &emsp14;&emsp14;&emsp14;<img
                                                            src="{{ URL::to('/') }}/assets/firob/img/QuizIcons/Logo2.png"/>
                                                         जवाब नहीं दिया &emsp14;&emsp14;&emsp14;<img
                                                            src="{{ URL::to('/') }}/assets/firob/img/QuizIcons/Logo3.png"/>
                                                        उत्तर दिया<br/><br/></li>


                                                </ul>
                                            </li>


                                        </ol>
                                        <h4><strong><u>प्रश्नों तक पहुँचना या नेविगेट करना।</u></strong></h4>


                                        <p>सवालो का उत्तर देने के लिए इन चरणों का पालन करें:</p>
                                        <ol>
                                            <li>अपने स्क्रीन के दाएं ओर स्थित प्रश्न पैलेट में चयनित प्रश्न तक सीधे जाने
                                                के लिए
                                                प्रश्न संख्या पर क्लिक करें। कृपया ध्यान दें कि इस विकल्प का उपयोग करने
                                                से
                                                आपका वर्तमान प्रश्न का उत्तर सहेजा नहीं जाएगा।
                                            </li>
                                            <li>वर्तमान प्रश्न के उत्तर को सहेजने और अगले प्रश्न पर आगे बढ़ने के लिए
                                                सहेजें
                                                और आगे पर क्लिक करें।
                                            </li>

                                        </ol>


                                        <h4><strong><u>बहुविकल्पी प्रश्नों का उत्तर देने के निर्देश:</u></strong></h4>
                                        <ol>
                                            <li>अपने उत्तर का चयन करने के लिए, किसी एक विकल्प बटन पर क्लिक करें।</li>
                                            <li>अपने चयनित उत्तर को अचयनित करने के लिए स्पष्ट उत्तर बटन का उपयोग करें।
                                            </li>
                                            <li>अपने चयनित उत्तर को बदलने के लिए, अन्य विकल्प के बटन पर क्लिक करें।
                                            </li>
                                            <li>यदि आप किसी पूर्व में दिये गए सवाल के उत्तर को बदलना चाहते हैं, तो पहले
                                                उस
                                                सवाल को समीक्षा के लिए चुनें और फिर उस प्रकार के सवाल के लिए उपयुक्त
                                                प्रक्रिया का पालन करें।
                                            </li>
                                        </ol>
                                        <label>
                                            <input type="checkbox" id="hi_ch">&nbsp;&nbsp;मैं यह घोषणा करता/करती हूँ कि
                                            मैंने प्रदान की गई सभी निर्देशों को पूरी तरह से पढ़ा और समझ
                                            लिया है।</label>
                                        <hr>
                                        <div class="col-md-4 col-md-offset-4 text-center">
                                            <a onClick="check_instruction('hi')"
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
    <div id="copyright">
        <div class="container">
            <div class="col-md-12">
               {{-- <p class="text-center">&copy; {{ date('Y') }} VNR Seeds Pvt. Ltd.</p>--}}
                <p class="text-center">&copy; {{ date('Y') }} All Rights Reserved</p>
            </div>
        </div>
    </div>
</div>
<script src="{{ URL::to('/') }}/assets/js/jquery.min.js"></script>
<script src="{{ URL::to('/') }}/assets/js/bootstrap.bundle.min.js"></script>
<script type="text/javascript">
    function changeIndtruct(q) {
        $('#' + q).css("display", "block");
        if (q == 'hi') {
            $('#en').css("display", "none");
        } else {
            $('#hi').css("display", "none");
        }
    }

    function check_instruction(id) {

        if ($('#' + id + '_ch').prop("checked") == false) {
            if (id == 'en') {
                alert('Please accept terms and conditions before proceeding.');
            } else {
                alert('आगे बढ़ने से पहले नियम और शर्तें स्वीकार करें।');
            }
        } else {
            window.location.href = "{{ route('firob_test') }}?jcid={{ request()->query('jcid') }}&exam_id={{ request()->query('exam_id') }}";
        }
    }
</script>
</body>

</html>
