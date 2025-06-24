@php
    $JCId = base64_decode(Request::segment(3));
    $exam_id = base64_decode(Request::segment(2));
    $reference_no = DB::table('jobcandidates')->where('JCId', $JCId)
             ->select('ReferenceNo')
             ->value('ReferenceNo');

@endphp
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Candidate Assessment</title>

    <link href="{{ URL::to('/') }}/assets/firob/css/custom.css" rel="stylesheet"/>
    <link href="{{ URL::to('/') }}/assets/firob/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="{{ URL::to('/') }}/assets/firob/css/style.default.css" rel="stylesheet"/>

    <link rel="stylesheet" href="{{ URL::to('/') }}/assets/css/toastr.min.css"/>
</head>

<body>
<div id="heading-breadcrumbs">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div style="color: #FFF; padding: 0 0 10px 0;">
                    <div style="font-size: 22px;">Candidate Assessment Test</div>
                </div>
            </div>
        </div>
    </div>
    <div class="home-carousel">
        <div class="area-bg">
            <div id="content">
                <div class="container">
                    <div class="row">
                        <div class="col-md-4 col-md-offset-4">
                            <div class="box-part">
                                <h4>Login</h4>
                                <hr>
                                <form id="login_form">
                                    @csrf
                                    <div class="form-group">
                                        <label>Reference No</label>
                                        <input type="hidden" name="Ref" id="Ref"
                                               value="{{$reference_no}}">
                                        <input id="reference_no" name="reference_no" type="text" class="form-control"
                                               placeholder="Enter Your Reference No"/>
                                    </div>
                                    <div class="form-group">

                                        <button class="btn btn-primary btn-block btn-lg" type="submit">Login</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="copyright">
        <div class="container">
            <div class="col-md-12">
                {{--<p class="text-center">&copy; VNR Seeds Pvt. Ltd.</p>--}}
                <p class="text-center">&copy; All Rights Reserved</p>
            </div>
        </div>
    </div>
</div>

<script src="{{ URL::to('/') }}/assets/js/jquery.min.js"></script>
<script src="{{ URL::to('/') }}/assets/js/bootstrap.bundle.min.js"></script>

<script src="{{ URL::to('/') }}/assets/js/toastr.min.js"></script>
<script>
    $(document).ready(function () {
        $('#login_form').submit(function (e) {
            e.preventDefault();
            let reference_no = $('#reference_no').val();
            let Ref = $('#Ref').val();

            if (reference_no === '') {
                displayErrorMessage('Please enter Reference No.');
            } else if (reference_no === Ref) {
                redirectToInstructionPage();
            } else {
                displayErrorMessage('Reference no. is not correct.');
            }
        });

        function displayErrorMessage(message) {
            toastr.error(message);
        }

        function redirectToInstructionPage() {
            let url = "{{ route('candidate_assessment_select_paper') }}";
            window.location.href = url + '?jcid=' + '{{Request::segment(3)}}&exam_id=' + '{{Request::segment(2)}}';
        }
    });
</script>

</body>

</html>
