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
            min-height: 297mm;
            padding: 10mm;
            margin: 10mm auto;
            border: 1px black solid;
            border-radius: 5px;
            background: white;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }

        .subpage {
            padding: 0.5cm;

            /*  height: 297mm; */

        }



        @page {
            size: A4;
            margin: 0;
        }

        @media print {

            html,
            body {
                width: 210mm;
                height: 297mm;
                /*  display: none; */
            }

            .page {
                margin: 0;
                border: initial;
                border-radius: initial;
                width: initial;
                min-height: initial;
                box-shadow: initial;
                background: initial;
                page-break-after: always;
            }

            .noprint {
                display: none !important;
            }
        }



        .table td,
        .table th {
            padding: .25rem;
            vertical-align: top;

            font-family: "Cambria", serif;
        }

    </style>
</head>

<body class="bg-lock-screen">
    <div class="conatiner">
        <div class="page">
            <div class="subpage">
                <form action="{{ route('reference_chk_response') }}" method="POST" id="referenceform">
                    <input type="hidden" name="JAId" id="JAId" value="{{ $JAId }}">
                    <table class="table table-bordered table-striped">
                        <thead class="text-center">
                            <th>SN</th>
                            <th>Particulars</th>
                            <th>Your Comment</th>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="align-middle text-center">1</td>
                                <td class="align-middle text-center" style="width: 50%">Company Name &
                                    Address
                                </td>
                                <td class="align-middle text-center">
                                    {{ $res->Company ?? '' }}</td>
                            </tr>

                            <tr>
                                <td class="align-middle text-center">2</td>
                                <td class="align-middle text-center">Period of
                                    Employement
                                </td>
                                <td class="align-middle text-center">
                                    <table class="table table-borderless">
                                        <td class="align-middle text-center">From</td>
                                        <td class="align-middle text-center">{{ $res->FromDate ?? '' }}
                                        </td>
                                        <td class="align-middle text-center">To</td>
                                        <td class="align-middle text-center">{{ $res->ToDate ?? '' }}
                                        </td>
                                    </table>
                                </td>
                            </tr>

                            <tr>
                                <td class="align-middle text-center">3</td>
                                <td class="align-middle text-center">Position held &
                                    Department</td>
                                <td class="align-middle text-center">
                                    {{ $res->Designation ?? '' }}
                                </td>
                            </tr>

                            <tr>
                                <td class="align-middle text-center">4</td>
                                <td class="align-middle text-center">Name of Reporting
                                    Manager</td>
                                <td class="align-middle text-center">
                                    {{ $res->ReportMgr ?? '' }}
                                </td>
                            </tr>

                            <tr>
                                <td class="align-middle text-center">5</td>
                                <td class="align-middle text-center">Nature of
                                    Employement
                                </td>
                                <td class="align-middle text-center">
                                    <center>
                                        {{ $res->EmpType ?? '' }}

                                    </center>

                                </td>

                            </tr>

                            <tr>

                                <td class="align-middle text-center">6</td>

                                <td class="text-center">Agency Detail <br>(if
                                    Temporary or

                                    contractual)</td>

                                <td class="align-middle text-center">
                                    {{ $res->Agency ?? '' }}
                                </td>

                            </tr>

                            <tr>

                                <td class="align-middle text-center">7</td>

                                <td class="align-middle text-center">Remuneration details

                                </td>

                                <td class="align-middle text-center">

                                    <table class="table table-borderless">

                                        <tr>

                                            <td class="align-middle text-center">Gross Per Month:</td>

                                            <td class="align-middle text-center">{{ $res->NetMonth ?? '' }}
                                            </td>

                                        </tr>

                                        <td class="align-middle text-center">CTC:</td>

                                        <td class="align-middle text-center">{{ $res->CTC ?? '' }}</td>

                                    </table>

                                </td>

                            </tr>

                            <tr>

                                <td class="align-middle text-center">8</td>

                                <th class="align-middle text-center" colspan="2">Please rate his/her

                                    service on following records</th>

                               

                            </tr>

                            <tr>

                                <td class="align-middle text-center">A</td>

                                <td class="align-middle text-center">Ability to work as
                                    team

                                    member</td>

                                <td class="align-middle text-center">
                                    {{ $res->AbilityTeam }} </td>


                            </tr>

                            <tr>

                                <td class="align-middle text-center">B</td>

                                <td class="align-middle text-center">Trustworthiness /

                                    Loyalty to the

                                    organization</td>

                               

                                        <td class="align-middle text-center"> {{ $res->Loyal }}

                                        </td>
                                   

                            </tr>

                            <tr>

                                <td class="align-middle text-center">C</td>

                                <td class="align-middle text-center">Leadership qualities

                                    organization</td>

                              

                                        <td class="align-middle text-center"> {{ $res->Leadership }}
                                        </td>

                                 
                            </tr>

                            <tr>

                                <td class="align-middle text-center">D</td>

                                <td class="align-middle text-center">Relationship with

                                    Seniors and Co-workers

                                </td>

                               

                                        <td class="align-middle text-center"> {{ $res->Relationship }}

                                        </td>

                              

                            </tr>

                            <tr>

                                <td class="align-middle text-center">E</td>

                                <td class="align-middle text-center">Character & Conduct

                                </td>

                             
                                        <td class="align-middle text-center"> {{ $res->CharacterConduct }}
                                        </td>

                                 

                            </tr>

                            <tr>

                                <td class="align-middle text-center">9</td>

                                <td class="align-middle text-center">Strengths – job
                                    related

                                    and personality</td>

                                <td class="align-middle text-center">
                                    {{ $res->Strength ?? '' }}
                                </td>

                            </tr>

                            <tr>

                                <td class="align-middle text-center">10</td>

                                <td class="align-middle text-center">Areas of weakness
                                    and/or

                                    areas needing improvemen</td>

                                <td class="align-middle text-center">
                                    {{ $res->Weakness ?? '' }}
                                </td>

                            </tr>

                            <tr>

                                <td class="align-middle text-center">11</td>

                                <td class="align-middle text-center">Reason of leaving
                                    the

                                    present employment

                                </td>

                                <td class="align-middle text-center">
                                    {{ $res->LeaveReason ?? '' }}
                            </tr>

                            <tr>

                                <td class="align-middle text-center">12</td>

                                <td class="align-middle text-center">Would you rehire
                                    him if

                                    the

                                    opportunity arose? And if not

                                    why?

                                </td>

                                <td class="align-middle text-center">
                                    {{ $res->Rehire ?? '' }}</td>

                            </tr>

                        </tbody>
                    </table>

                    <p>Any Other information relatied to employee you may wish to give us...</p>

                    <p>{{ $res->AnyOther ?? '' }}</p>

                    <br>

                    <table class="table table-bordered">
                        <tr>
                            <td class="align-middle text-center">Verifier Name</td>
                            <td class="align-middle text-center">
                                {{ $res->VerifierName ?? '' }}</td>
                        </tr>

                        <tr>

                            <td class="align-middle text-center">Designation in the

                                organization</td>

                            <td class="align-middle text-center">
                                {{ $res->VDesig ?? '' }}</td>

                        </tr>

                        <tr>

                            <td class="align-middle text-center">Contact No</td>

                            <td class="align-middle text-center">
                                {{ $res->Contact ?? '' }}</td>

                        </tr>

                        <tr>

                            <td class="align-middle text-center">E-mail ID</td>

                            <td class="align-middle text-center">
                                {{ $res->Email ?? '' }}</td>

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
    </div>

</body>

</html>
