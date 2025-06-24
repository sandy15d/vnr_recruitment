<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="{{ URL::to('/') }}/assets/js/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous">
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&amp;display=swap" rel="stylesheet">
    <link href="{{ URL::to('/') }}/assets/css/app.css" rel="stylesheet">
    <title>Offer Letter</title>
    <style>
        body {
            width: 100%;
            height: 100%;
            margin: 0;
            padding: 0;
            font: 12pt "Tahoma";
        }

        p {
            font-family: "Cambria", serif;
            font-size: 17px;
        }

    </style>
</head>
@php

$JAId = base64_decode($_REQUEST['jaid']);
$sql = DB::table('offerletterbasic')
    ->select('*')
    ->where('JAId', $JAId)
    ->first();

@endphp

<body class="bg-lock-screen">
    @if ($sql->Answer == 'Accepted')
        <div class="section-authentication-signin d-flex align-items-center justify-content-center ">
            <div class="container">
                <div class="row">
                    <div class="col-7 mx-auto">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Dear Candidate,</h5>
                                <p class="card-text">Thank you for accepting the job offer.</p>
                                <p class="card-text">Link to complete you joining documentation formalities has been
                                    sent to your Email Id.</p>
                                <p class="card-text mb-0">In case of any further query kindly contact,</p>
                                <p class="card-text">Contact No: 0771-4350005</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="section-authentication-signin d-flex align-items-center justify-content-center ">
            <div class="container">
                <div class="row">
                    <div class="col-7 mx-auto">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Dear Candidate,</h5>
                                <p class="card-text">Thank you for giving your response to the job offer made to
                                    you.</p>
                                <p class="card-text mb-0">In case of any further query kindly contact,</p>
                                <p class="card-text">Contact No: 0771-4350005</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <script>
        $(document).ready(function() {
            window.history.pushState(null, "", window.location.href);
            window.onpopstate = function() {
                window.history.pushState(null, "", window.location.href);
            };
            $(document).bind("contextmenu", function(e) {
                return false;
            });
        });
    </script>
</body>

</html>
