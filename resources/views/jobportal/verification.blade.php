@php
$jcid = $_REQUEST['jcid'];
$jcid = base64_decode($jcid);
@endphp
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="{{ URL::to('/') }}/assets/images/favicon-32x32.png" type="image/png" />
    <link href="{{ URL::to('/') }}/assets/plugins/simplebar/css/simplebar.css" rel="stylesheet" />
    <link href="{{ URL::to('/') }}/assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css" rel="stylesheet" />
    <link href="{{ URL::to('/') }}/assets/plugins/metismenu/css/metisMenu.min.css" rel="stylesheet" />
    <link href="{{ URL::to('/') }}/assets/css/pace.min.css" rel="stylesheet" />
    <script src="{{ URL::to('/') }}/assets/js/pace.min.js"></script>
    <link href="{{ URL::to('/') }}/assets/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ URL::to('/') }}/assets/css/sweetalert2.min.css" />
    <link rel="stylesheet" href="{{ URL::to('/') }}/assets/css/toastr.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&amp;display=swap" rel="stylesheet">
    <link href="{{ URL::to('/') }}/assets/css/app.css" rel="stylesheet">
    <link href="{{ URL::to('/') }}/assets/css/icons.css" rel="stylesheet">
    <title>OTP Verification</title>
</head>

<body class="bg-login">
    <div class="wrapper">
        <div class="section-authentication-signin d-flex align-items-center justify-content-center my-5 my-lg-0">
            <div class="container-fluid">
                <div class="row row-cols-1 row-cols-lg-2 row-cols-xl-2">
                    <div class="col mx-auto">
                        <div class="mb-4 text-center">
                            <h3 class="text-primary">Verification Process</h3>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <div class="border p-4 rounded">
                                    <div class="text-center">
                                        <p class="text-danger">Check your Email and Mobile for OTP. Please enter both
                                            OTP's for verifying</p>
                                    </div>
                                    <div class="
                                            form-body">
                                        <form class="row g-3" method="POST" action="{{ route('otpverify') }}"
                                            id="OTPVerifyForm">
                                            @csrf
                                            <input type="hidden" name="JCId" value="{{ $jcid }}">
                                            <div class="col-12">
                                                <label for="SmsOTP" class="form-label">OTP sent in Mobile<font
                                                        class="text-danger">*</font></label>
                                                <input type="text" name="SmsOTP" id="SmsOTP"
                                                    class="form-control form-control-sm">
                                                <span class="text-danger error-text SmsOTP_error"></span>
                                            </div>
                                            <div class="col-12">
                                                <label for="EmailOTP" class="form-label">OTP sent in Email<font
                                                        class="text-danger">*</font></label>
                                                <input type="text" name="EmailOTP" id="EmailOTP"
                                                    class="form-control form-control-sm">
                                                <span class="text-danger error-text EmailOTP_error"></span>
                                            </div>
                                            <div class="col-12">
                                                <div class="d-grid">
                                                    <button type="submit" class="btn btn-primary"><i
                                                            class="bx bx-check"></i>Verify</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ URL::to('/') }}/assets/js/bootstrap.bundle.min.js"></script>
    <script src="{{ URL::to('/') }}/assets/js/jquery.min.js"></script>
    <script src="{{ URL::to('/') }}/assets/plugins/simplebar/js/simplebar.min.js"></script>
    <script src="{{ URL::to('/') }}/assets/plugins/metismenu/js/metisMenu.min.js"></script>
    <script src="{{ URL::to('/') }}/assets/js/sweetalert2.min.js"></script>
    <script src="{{ URL::to('/') }}/assets/js/toastr.min.js"></script>
    <script>
        $('#OTPVerifyForm').on('submit', function(e) {
            e.preventDefault();
            var form = this;
            $.ajax({
                url: $(form).attr('action'),
                method: $(form).attr('method'),
                data: new FormData(form),
                processData: false,
                dataType: 'json',
                contentType: false,
                beforeSend: function() {
                    $(form).find('span.error-text').text('');
                },
                success: function(data) {
                    if (data.status == 400) {
                        $.each(data.error, function(prefix, val) {
                            $(form).find('span.' + prefix + '_error').text(val[0]);
                        });
                        toastr.error(data.msg);
                    } else {
                        $(form)[0].reset();
                        toastr.success(data.msg);
                        var JCId = data.JCId;
                        window.location.href = "{{ route('confirmation') }}?jcid=" + JCId;
                    }
                }
            });
        });
    </script>
</body>

</html>
