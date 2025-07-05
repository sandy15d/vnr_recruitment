@php
$JAId = base64_decode($_REQUEST['jaid']);

$res = DB::table('candidate_ref')
    ->where('JAId', $JAId)
    ->first();
@endphp
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <script src="{{ URL::to('/') }}/assets/js/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js">
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&amp;display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ URL::to('/') }}/assets/css/sweetalert2.min.css" />
    <link rel="stylesheet" href="{{ URL::to('/') }}/assets/css/toastr.min.css" />
    <link href="{{ URL::to('/') }}/assets/css/app.css" rel="stylesheet">
    <title>Reference Check Form</title>
    <style>
        body {
            width: 100%;
            height: 100%;
            margin: 0;
            padding: 0;
            font: 12pt "Tahoma";
        }

        * {
            box-sizing: border-box;
            -moz-box-sizing: border-box;
        }



        .page {
            width: 210mm;
            min-height: 370mm;
            padding: 10mm;
            margin: 10mm auto;
            border: 1px black solid;
            border-radius: 5px;
            background: white;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }

        .subpage {
            padding: 0.5cm;
            height: 370mm;
        }

        p {
            font-family: "Cambria", serif;
            font-size: 17px;
        }



        ol,
        li {
            text-align: justify;
            font-family: "Cambria", serif;
            font-size: 17px;
            margin-bottom: 5px;
        }



        @page {
            size: A4;
            margin: 0;
        }

        .table td,
        .table th {
            padding: .25rem;
            vertical-align: top;
            font-family: "Cambria", serif;
        }

        mark {
            background-color: yellow;
            color: black;
        }

    </style>
</head>

<body class="bg-lock-screen">
    <div class="conatiner">
        <div class="page">
            <div class="subpage">
                <form action="{{ route('reference_chk_response') }}" method="POST" id="referenceform">
                    <input type="hidden" name="JAId" id="JAId" value="{{ $JAId }}">
                    <table class="table table-bordered" style="border: 2px solid black">
                        <thead class="text-center">
                            <th style="border:2px solid black">SN</th>
                            <th style="border:2px solid black">Particulars</th>
                            <th style="border:2px solid black">Your Comment</th>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="align-middle text-center" style="border:2px solid black">1</td>
                                <td class="align-middle text-center" style="border:2px solid black">Company Name &
                                    Address
                                </td>
                                <td class="align-middle text-center" style="border:2px solid black"><input type="text"
                                        class="form-control" id="Company" name="Company"
                                        value="{{ $res->Company ?? '' }}" required></td>
                            </tr>

                            <tr>
                                <td class="align-middle text-center" style="border:2px solid black">2</td>
                                <td class="align-middle text-center" style="border:2px solid black">Period of
                                    Employement
                                </td>
                                <td class="align-middle text-center" style="border:2px solid black">
                                    <table class="table table-borderless">
                                        <td class="align-middle text-center">From</td>
                                        <td class="align-middle text-center"><input type="date" class="form-control"
                                                id="FromDate" name="FromDate" style="width:166px;"
                                                value="{{ $res->FromDate ?? '' }}" required>
                                        </td>
                                        <td class="align-middle text-center">To</td>
                                        <td class="align-middle text-center"><input type="date" class="form-control"
                                                style="width:166px;" id="ToDate" name="ToDate"
                                                value="{{ $res->ToDate ?? '' }}" required>
                                        </td>
                                    </table>
                                </td>
                            </tr>

                            <tr>
                                <td class="align-middle text-center" style="border:2px solid black">3</td>
                                <td class="align-middle text-center" style="border:2px solid black">Position held &
                                    Department</td>
                                <td class="align-middle text-center" style="border:2px solid black"><input type="text"
                                        class="form-control" id="Designation" name="Designation"
                                        value="{{ $res->Designation ?? '' }}" required>
                                </td>
                            </tr>

                            <tr>
                                <td class="align-middle text-center" style="border:2px solid black">4</td>
                                <td class="align-middle text-center" style="border:2px solid black">Name of Reporting
                                    Manager</td>
                                <td class="align-middle text-center" style="border:2px solid black"><input type="text"
                                        class="form-control" id="ReportMgr" name="ReportMgr"
                                        value="{{ $res->ReportMgr ?? '' }}" required>
                                </td>
                            </tr>

                            <tr>
                                <td class="align-middle text-center" style="border:2px solid black">5</td>
                                <td class="align-middle text-center" style="border:2px solid black">Nature of
                                    Employement
                                </td>
                                <td class="align-middle text-center" style="border:2px solid black">
                                    <center>
                                        <label>
                                            <input type="radio" name="EmpType" id="EmpType" value="Permanent"
                                                class="d-inline-block" @if ($res != null)
                                            {{ $res->EmpType == 'Permanent' ? 'checked' : '' }}

                                            @endif
                                            >Permanent</label>

                                        &emsp;



                                        <label>

                                            <input type="radio" name="EmpType" id="EmpType" value="Temporary"
                                                class="d-inline-block" @if ($res != null)
                                            {{ $res->EmpType == 'Temporary' ? 'checked' : '' }}
                                            @endif
                                            > Temporary</label>

                                        &emsp;

                                        <label>

                                            <input type="radio" name="EmpType" id="EmpType" value="Contractual"
                                                class="d-inline-block" @if ($res != null)
                                            <?= $res->EmpType == 'Contractual' ? 'checked' : '' ?>
                                            @endif
                                            > Contractual

                                        </label>

                                    </center>

                                </td>

                            </tr>

                            <tr>

                                <td class="align-middle text-center" style="border:2px solid black">6</td>

                                <td class="text-center" style="border:2px solid black">Agency Detail <br>(if
                                    Temporary or

                                    contractual)</td>

                                <td class="align-middle text-center" style="border:2px solid black"><input type="text"
                                        class="form-control" id="Agency" name="Agency"
                                        value="{{ $res->Agency ?? '' }}">
                                </td>

                            </tr>

                            <tr>

                                <td class="align-middle text-center" style="border:2px solid black">7</td>

                                <td class="align-middle text-center" style="border:2px solid black">Remuneration details

                                </td>

                                <td class="align-middle text-center" style="border:2px solid black">

                                    <table class="table table-borderless">

                                        <tr>

                                            <td class="align-middle text-center">Gross Per Month:</td>

                                            <td class="align-middle text-center"><input type="text"
                                                    class="form-control" id="NetMonth" name="NetMonth"
                                                    value="{{ $res->NetMonth ?? '' }}" required>

                                            </td>

                                        </tr>

                                        <td class="align-middle text-center">CTC:</td>

                                        <td class="align-middle text-center"><input type="text" class="form-control"
                                                id="CTC" name="CTC" value="{{ $res->CTC ?? '' }}" required></td>

                                    </table>

                                </td>

                            </tr>

                            <tr>

                                <td class="align-middle text-center" style="border:2px solid black">8</td>

                                <th class="align-middle text-center" style="border:2px solid black">Please rate his/her

                                    service on following records</th>

                                <td class="align-middle text-center" style="border:2px solid black">

                                    <table class="table table-borderless">

                                        <th class="align-middle text-center text-center">Excellent</th>

                                        <th class="align-middle text-center text-center">Good</th>

                                        <th class="align-middle text-center text-center">Average</th>

                                        <th class="align-middle text-center text-center">Poor</th>

                                    </table>

                                </td>

                            </tr>

                            <tr>

                                <td class="align-middle text-center" style="border:2px solid black">A</td>

                                <td class="align-middle text-center" style="border:2px solid black">Ability to work as
                                    team

                                    member</td>

                                <td class="align-middle text-center" style="border:2px solid black">

                                    <table class="table table-borderless">

                                        <td class="align-middle text-center"> &nbsp;<input type="radio"
                                                name="AbilityTeam" id="AbilityTeam" class="text-center"
                                                value="Excellent" @if ($res != null)
                                            {{ $res->AbilityTeam == 'Excellent' ? 'checked' : '' }}
                                            @endif
                                            >

                                        </td>

                                        <td class="align-middle text-center"> <input type="radio" name="AbilityTeam"
                                                id="AbilityTeam" class="text-center" value="Good" @if ($res != null)
                                            <?= $res->AbilityTeam == 'Good' ? 'checked' : '' ?>
                                            @endif
                                            >

                                        </td>

                                        <td class="align-middle text-center"> <input type="radio" name="AbilityTeam"
                                                id="AbilityTeam" class="text-center" value="Average" @if ($res != null)
                                            <?= $res->AbilityTeam == 'Average' ? 'checked' : '' ?>
                                            @endif
                                            >

                                        </td>

                                        <td class="align-middle text-center"> <input type="radio" name="AbilityTeam"
                                                id="AbilityTeam" class="text-center" value="Poor" @if ($res != null)
                                            <?= $res->AbilityTeam == 'Poor' ? 'checked' : '' ?>
                                            @endif
                                            >

                                        </td>

                                    </table>

                                </td>

                            </tr>

                            <tr>

                                <td class="align-middle text-center" style="border:2px solid black">B</td>

                                <td class="align-middle text-center" style="border:2px solid black">Trustworthiness /

                                    Loyalty to the

                                    organization</td>

                                <td class="align-middle text-center" style="border:2px solid black">

                                    <table class="table table-borderless">

                                        <td class="align-middle text-center"> &nbsp;<input type="radio" name="Loyal"
                                                id="Loyal" class="text-center" value="Excellent" @if ($res != null)
                                            <?= $res->Loyal == 'Excellent' ? 'checked' : '' ?>
                                            @endif
                                            >

                                        </td>

                                        <td class="align-middle text-center"> <input type="radio" name="Loyal"
                                                id="Loyal" class="text-center" value="Good" @if ($res != null)
                                            <?= $res->Loyal == 'Good' ? 'checked' : '' ?>
                                            @endif
                                            >

                                        </td>

                                        <td class="align-middle text-center"> <input type="radio" name="Loyal"
                                                id="Loyal" class="text-center" value="Average" @if ($res != null)
                                            <?= $res->Loyal == 'Average' ? 'checked' : '' ?>
                                            @endif
                                            >

                                        </td>

                                        <td class="align-middle text-center"> <input type="radio" name="Loyal"
                                                id="Loyal" class="text-center" value="Poor" @if ($res != null)
                                            <?= $res->Loyal == 'Poor' ? 'checked' : '' ?>
                                            @endif
                                            >

                                        </td>

                                    </table>

                                </td>

                            </tr>

                            <tr>

                                <td class="align-middle text-center" style="border:2px solid black">C</td>

                                <td class="align-middle text-center" style="border:2px solid black">Leadership qualities

                                    organization</td>

                                <td class="align-middle text-center" style="border:2px solid black">

                                    <table class="table table-borderless">

                                        <td class="align-middle text-center"> &nbsp;<input type="radio"
                                                name="Leadership" id="Leadership" class="text-center"
                                                value="Excellent" @if ($res != null)
                                            <?= $res->Leadership == 'Excellent' ? 'checked' : '' ?>
                                            @endif
                                            >

                                        </td>

                                        <td class="align-middle text-center"> <input type="radio" name="Leadership"
                                                id="Leadership" class="text-center" value="Good" @if ($res != null)
                                            <?= $res->Leadership == 'Good' ? 'checked' : '' ?>
                                            @endif
                                            >

                                        </td>

                                        <td class="align-middle text-center"> <input type="radio" name="Leadership"
                                                id="Leadership" class="text-center" value="Average" @if ($res != null)
                                            <?= $res->Leadership == 'Average' ? 'checked' : '' ?>
                                            @endif
                                            >

                                        </td>

                                        <td class="align-middle text-center"> <input type="radio" name="Leadership"
                                                id="Leadership" class="text-center" value="Poor" @if ($res != null)
                                            <?= $res->Leadership == 'Poor' ? 'checked' : '' ?>
                                            @endif
                                            >

                                        </td>

                                    </table>

                                </td>

                            </tr>

                            <tr>

                                <td class="align-middle text-center" style="border:2px solid black">D</td>

                                <td class="align-middle text-center" style="border:2px solid black">Relationship with

                                    Seniors and Co-workers

                                </td>

                                <td class="align-middle text-center" style="border:2px solid black">

                                    <table class="table table-borderless">

                                        <td class="align-middle text-center"> &nbsp;<input type="radio"
                                                name="Relationship" id="Relationship" class="text-center"
                                                value="Excellent" @if ($res != null)
                                            <?= $res->Relationship == 'Excellent' ? 'checked' : '' ?>
                                            @endif
                                            >

                                        </td>

                                        <td class="align-middle text-center"> <input type="radio" name="Relationship"
                                                id="Relationship" class="text-center" value="Good" @if ($res != null)
                                            <?= $res->Relationship == 'Good' ? 'checked' : '' ?>
                                            @endif
                                            >

                                        </td>

                                        <td class="align-middle text-center"> <input type="radio" name="Relationship"
                                                id="Relationship" class="text-center" value="Average" @if ($res != null)
                                            <?= $res->Relationship == 'Average' ? 'checked' : '' ?>
                                            @endif
                                            >

                                        </td>

                                        <td class="align-middle text-center"> <input type="radio" name="Relationship"
                                                id="Relationship" class="text-center" value="Poor" @if ($res != null)
                                            <?= $res->Relationship == 'Poor' ? 'checked' : '' ?>
                                            @endif
                                            >

                                        </td>

                                    </table>

                                </td>

                            </tr>

                            <tr>

                                <td class="align-middle text-center" style="border:2px solid black">E</td>

                                <td class="align-middle text-center" style="border:2px solid black">Character & Conduct

                                </td>

                                <td class="align-middle text-center">

                                    <table class="table table-borderless">

                                        <td class="align-middle text-center"> &nbsp;<input type="radio"
                                                name="CharacterConduct" id="CharacterConduct" class="text-center"
                                                value="Excellent" @if ($res != null)
                                            <?= $res->CharacterConduct == 'Excellent' ? 'checked' : '' ?>
                                            @endif
                                            >

                                        </td>

                                        <td class="align-middle text-center"> <input type="radio"
                                                name="CharacterConduct" id="CharacterConduct" class="text-center"
                                                value="Good" @if ($res != null)
                                            <?= $res->CharacterConduct == '' ? 'checked' : '' ?>
                                            @endif
                                            >

                                        </td>

                                        <td class="align-middle text-center"> <input type="radio"
                                                name="CharacterConduct" id="CharacterConduct" class="text-center"
                                                value="Average" @if ($res != null)
                                            <?= $res->CharacterConduct == '' ? 'checked' : '' ?>
                                            @endif
                                            >

                                        </td>

                                        <td class="align-middle text-center"> <input type="radio"
                                                name="CharacterConduct" id="CharacterConduct" class="text-center"
                                                value="Poor" @if ($res != null)
                                            <?= $res->CharacterConduct == '' ? 'checked' : '' ?>
                                            @endif
                                            >

                                        </td>

                                    </table>

                                </td>

                            </tr>

                            <tr>

                                <td class="align-middle text-center" style="border:2px solid black">9</td>

                                <td class="align-middle text-center" style="border:2px solid black">Strengths – job
                                    related

                                    and personality</td>

                                <td class="align-middle text-center" style="border:2px solid black"><input type="text"
                                        class="form-control" id="Strength" name="Strength"
                                        value="{{ $res->Strength ?? '' }}">

                                </td>

                            </tr>

                            <tr>

                                <td class="align-middle text-center" style="border:2px solid black">10</td>

                                <td class="align-middle text-center" style="border:2px solid black">Areas of weakness
                                    and/or

                                    areas needing improvemen</td>

                                <td class="align-middle text-center" style="border:2px solid black"><input type="text"
                                        class="form-control" id="Weakness" name="Weakness"
                                        value="{{ $res->Weakness ?? '' }}">

                                </td>

                            </tr>

                            <tr>

                                <td class="align-middle text-center" style="border:2px solid black">11</td>

                                <td class="align-middle text-center" style="border:2px solid black">Reason of leaving
                                    the

                                    present employment

                                </td>

                                <td class="align-middle text-center" style="border:2px solid black"><input type="text"
                                        class="form-control" id="LeaveReason" name="LeaveReason"
                                        value="{{ $res->LeaveReason ?? '' }}"></td>

                            </tr>

                            <tr>

                                <td class="align-middle text-center" style="border:2px solid black">12</td>

                                <td class="align-middle text-center" style="border:2px solid black">Would you rehire
                                    him if

                                    the

                                    opportunity arose? And if not

                                    why?

                                </td>

                                <td class="align-middle text-center" style="border:2px solid black"><input type="text"
                                        class="form-control" id="Rehire" name="Rehire"
                                        value="{{ $res->Rehire ?? '' }}" required></td>

                            </tr>

                        </tbody>
                    </table>

                    <p>Any Other information relatied to employee you may wish to give us...</p>

                    <textarea class="form-control" id="AnyOther" name="AnyOther"
                        style="border:2px solid black">{{ $res->AnyOther ?? '' }}</textarea>

                    <br>

                    <table class="table table-bordered" style="border:2px solid black">
                        <tr>
                            <td class="align-middle text-center" style="border:2px solid black">Verifier Name</td>
                            <td class="align-middle text-center" style="border:2px solid black"><input type="text"
                                    class="form-control" id="VerifierName" name="VerifierName"
                                    value="{{ $res->VerifierName ?? '' }}" required></td>
                        </tr>

                        <tr>

                            <td class="align-middle text-center" style="border:2px solid black">Designation in the

                                organization</td>

                            <td class="align-middle text-center" style="border:2px solid black"><input type="text"
                                    class="form-control" id="VDesig" name="VDesig"
                                    value="{{ $res->VDesig ?? '' }}" required></td>

                        </tr>

                        <tr>

                            <td class="align-middle text-center" style="border:2px solid black">Contact No</td>

                            <td class="align-middle text-center" style="border:2px solid black"><input type="text"
                                    class="form-control" id="Contact" name="Contact"
                                    value="{{ $res->Contact ?? '' }}" required></td>

                        </tr>

                        <tr>

                            <td class="align-middle text-center" style="border:2px solid black">E-mail ID</td>

                            <td class="align-middle text-center" style="border:2px solid black"><input type="text"
                                    class="form-control" id="Email" name="Email" value="{{ $res->Email ?? '' }}"
                                    required></td>

                        </tr>

                    </table>

                    <br>

                    <div class="text-center">
                        <center>
                            @if ($res == null)
                            <input type="submit" class="btn btn-primary btn-md" value="Save">
                            <input type="reset" class="btn btn-danger">
                            @endif
                          
                            
                        </center>

                    </div>
                </form>
            </div>
        </div>

        <div class="modal" id="loader" data-bs-backdrop="static" data-bs-keyboard="false"
            style="background-color: rgba(0,0,0,.0001)">
            <div class="modal-dialog modal-dialog-centered">
                <div class="spinner-border text-danger" style="width: 5rem; height: 5rem;" role="status">
                </div>
            </div>
        </div>
    </div>
    <script src="{{ URL::to('/') }}/assets/js/sweetalert2.min.js"></script>
    <script src="{{ URL::to('/') }}/assets/js/toastr.min.js"></script>
    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $('#referenceform').on('submit', function(e) {
            var JAId = $('#JAId').val();
            var SendId = btoa(JAId);
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
                    $('#loader').show();
                },
                success: function(data) {
                    if (data.status == 400) {
                        toastr.error(data.msg);
                    } else {
                        $(form)[0].reset();
                        $('#loader').hide();
                        toastr.success(data.msg);
                        setTimeout(function() {
                            window.close();
                        }, 2000);
                    }
                }
            });
        });
    </script>
</body>

</html>
