@php
$JAId = base64_decode(request()->query('jaid'));
$query = DB::table('jobapply')
    ->join('jobcandidates', 'jobcandidates.JCId', '=', 'jobapply.JCId')
    ->where('JAId', $JAId)
    ->select('jobcandidates.FName', 'jobcandidates.MName', 'jobcandidates.LName', 'jobcandidates.ReferenceNo')
    ->first();
@endphp
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <title>VNR -On Boarding</title>
    <link rel="stylesheet" href="{{ URL::to('/') }}/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ URL::to('/') }}/assets/css/mystyle.css">
    <link rel="stylesheet" href="{{ URL::to('/') }}/assets/css/sweetalert2.min.css" />
    <link rel="stylesheet" href="{{ URL::to('/') }}/assets/css/toastr.min.css" />
</head>

<body class="account-page">
    <div class="main-wrapper">
        <div class="account-content">
            <div class="container">
                <div class="account-box mt-5">
                    <div class="account-wrapper">
                        <div class="lock-user">
                            <h4>Welcome To VNR</h4>
                       {{--      <h5>{{ $query->FName }} {{ $query->MName }} {{ $query->LName }}</h5> --}}
                        </div>
                        <form id="refchkform">
                            @csrf
                            <div class="form-group">
                                <input type="hidden" name="JAId" value="{{ $JAId }}">
                                <input type="hidden" name="Ref" id="Ref" value="{{ $query->ReferenceNo }}">
                                <p>Enter the reference number as mentioned in the mail to login to On-boarding portal</p>
                                <label>Reference No:</label>
                                <input class="form-control" type="text" id="reference_no" name="reference_no">
                            </div>
                            <div class="form-group text-center">
                                <button class="btn btn-primary account-btn" type="submit">Enter</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ URL::to('/') }}/assets/js/jquery.min.js"></script>
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
                        window.location.href = "{{ route('onboarding') }}?jaid={{ request()->query('jaid') }}";
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
