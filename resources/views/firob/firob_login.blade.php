@php
$JCId = base64_decode(request()->query('jcid'));
$query = DB::table('jobcandidates')
    ->where('JCId', $JCId)
    ->select('ReferenceNo')
    ->first();
@endphp
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="Quiz/img/favicon.ico">
    <title>FIRO B</title>
    <link href="{{ URL::to('/') }}/assets/firob/css/font-awesome.min.css" rel="stylesheet" />
    <link href="{{ URL::to('/') }}/assets/firob/css/custom.css" rel="stylesheet" />
    <link href="{{ URL::to('/') }}/assets/firob/css/bootstrap.min.css" rel="stylesheet" />
    <link href="{{ URL::to('/') }}/assets/firob/css/style.default.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{ URL::to('/') }}/assets/css/sweetalert2.min.css" />
    <link rel="stylesheet" href="{{ URL::to('/') }}/assets/css/toastr.min.css" />
</head>

<body>
    <div id="all">

        <div class="clear"></div>
        <div id="heading-breadcrumbs">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div style="color: #FFF; padding: 0 0 10px 0;">
                            <div style="font-size: 22px;">FIRO B</div>
                            <div>Fundamental Interpersonal Relations Orientation-Behavior Assessment</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="home-carousel" style="height:550px;">
                <div class="area-bg" style="height:100%">
                    <div id="content">
                        <div class="container">
                            <div class="row">
                                <div class="col-md-4 col-md-offset-4">
                                    <div class="box-part">
                                        <h4>Login</h4>
                                        <hr>
                                        <form id="refchkform">
                                            @csrf
                                            <div class="form-group">
                                                <label>Reference No</label>
                                                <input type="hidden" name="Ref" id="Ref"
                                                    value="{{ $query->ReferenceNo }}">
                                                <input id="reference_no" name="reference_no" type="text"  class="form-control" />
                                            </div>
                                            <div class="form-group">
                                                <h6 style="text-align:center; color:red;">Click Login To proceed<img
                                                        src="{{ URL::to('/') }}/assets/firob/img/newicon.gif" /></h6>
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
                       {{-- <p class="text-center">&copy; VNR Seeds Pvt. Ltd.</p>--}}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ URL::to('/') }}/assets/js/jquery.min.js"></script>
    <script src="{{ URL::to('/') }}/assets/js/bootstrap.bundle.min.js"></script>
    <script src="{{ URL::to('/') }}/assets/js/sweetalert2.min.js"></script>
    <script src="{{ URL::to('/') }}/assets/js/toastr.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#refchkform').submit(function(e) {
                e.preventDefault();
                var reference_no = $('#reference_no').val();
                var Ref = $('#Ref').val();
                if (reference_no == '') {
                    toastr.error('Please enter reference no.');
                    return false;
                } else {
                    if (reference_no == Ref) {
                        window.location.href = "{{ route('firo_b_instruction') }}?jcid={{ request()->query('jcid') }}";
                    } else {
                        toastr.error('Reference no. is not correct.');
                        return false;
                    }
                }
            });
        });
    </script>
</body>

</html>
