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
    <link rel="stylesheet" href="{{ URL::to('/') }}/assets/css/sweetalert2.min.css"/>
    <link rel="stylesheet" href="{{ URL::to('/') }}/assets/css/toastr.min.css"/>
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

        table,
        th,
        tr,
        td {
            border: 2px solid black;
        }

        .table td,
        .table th {
            padding: .25rem;
            vertical-align: top;
            border-top: 1px solid #060606;
            font-family: "Cambria", serif;
        }

        mark {
            background-color: yellow;
            color: black;
        }

        .generate {
            width: 210mm;
            min-height: 20mm;
            padding: 10mm;
            margin: 10mm auto;

            border: 1px black solid;
            border-radius: 5px;
            background: white;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }
    </style>
    <style type="text/css" media="print">
        * {
            display: none;
        }
    </style>
</head>
@php

    $JAId = $_REQUEST['jaid'];
    $EmpId = $_REQUEST['E'];
    $sql = DB::table('offerletterbasic')
        ->leftJoin('jobapply', 'offerletterbasic.JAId', '=', 'jobapply.JAId')
        ->leftJoin('jobcandidates', 'jobapply.JCId', '=', 'jobcandidates.JCId')
        ->leftJoin('jf_contact_det', 'jobcandidates.JCId', '=', 'jf_contact_det.JCId')
        ->leftJoin('jf_family_det', 'jobcandidates.JCId', '=', 'jf_family_det.JCId')
        ->select('offerletterbasic.*','jobcandidates.DOB', 'jobcandidates.Title', 'jobcandidates.FName', 'jobcandidates.MName', 'jobcandidates.LName', 'jobcandidates.FatherTitle', 'jobcandidates.FatherName', 'jobcandidates.Gender', 'jobapply.ApplyDate', 'jf_contact_det.perm_address', 'jf_contact_det.perm_city', 'jf_contact_det.perm_dist', 'jf_contact_det.perm_state', 'jf_contact_det.perm_pin')
        ->where('jobapply.JAId', $JAId)
        ->first();
$age =   \Carbon\Carbon::parse($sql->DOB)->diff(\Carbon\Carbon::now())->format('%y');
    $ctc = DB::table('candidate_ctc')
        ->select('*')
        ->where('JAId', $JAId)
        ->first();

    $elg = DB::table('candidate_entitlement')
        ->select('*')
        ->where('JAId', $JAId)
        ->first();
    $candJoin = DB::table('candjoining')
        ->select('*')
        ->where('JAId', $JAId)
        ->first();
    $LinkValidityEnd = $candJoin->LinkValidityEnd ?? date('Y-m-d');
    $months_word = ['One' => '1 (One)', 'Two' => '2 (Two)', 'Three' => '3 (Three)', 'Four' => '4 (Four)', 'Five' => '5 (Five)', 'Six' => '6 (Six)', 'Seven' => '7 (Seven)', 'Eight' => '8 (Eight)', 'Nine' => '9 (Nine)', 'Ten' => '10 (Ten)', 'Eleven' => '11 (Eleven)', 'Twelve' => '12 (Twelve)'];
    $check = DB::table('offerletter_review')
        ->where('JAId', $JAId)
        ->where('EmpId', $EmpId)
        ->where('OfferLetterNo',$sql->LtrNo)
        ->first();
$policy_conn = DB::connection('mysql3');
@endphp

<body>

<div class="container">

    <div id="offer_letter">
        <div class="page">
            <div class="subpage">
                <p style="font-size: 16px;"><b>Ref:</b> {{ $sql->LtrNo }}
                    <span style="float:right"><b>Date:</b>
                            @if ($sql->LtrDate == null)
                            {{ date('d-m-Y') }}
                        @else
                            {{ date('d-m-Y', strtotime($sql->LtrDate)) }}
                        @endif
                        </span>
                </p>
                <br>
                <p><b>To,</b></p>
                <p style="margin-bottom: 0px;">
                    <b>{{ $sql->Title }} {{ $sql->FName }} {{ $sql->MName }} {{ $sql->LName }}</b>
                </p>
                <b>
                    <p style="margin-bottom: 0px;">{{ $sql->perm_address }}</p>
                    <p style="margin-bottom: 0px;">{{ $sql->perm_city }},
                        Dist-{{ getDistrictName($sql->perm_dist) }},{{ getStateName($sql->perm_state) }},
                        {{ $sql->perm_pin }}
                    </p>
                </b><br/>
                <p class="text-center"><b><u>Subject: Offer for Employment</u></b></p>
                <b>
                    <p>Dear {{ $sql->Title }} {{ $sql->FName }} {{ $sql->MName }} {{ $sql->LName }},</p>
                </b>
                <p>We are pleased to offer you the position of <b>{{ getCandidateFullDesignation($sql->JAId) }}</b> at
                    <b>Grade - {{ getGradeValue($sql->Grade) }}</b> in
                    <b>{{ getDepartment($sql->Department) }}</b>
                    Department of {{ getcompany_name($sql->Company) }} (<strong>"Company"</strong>)
                </p>
                <p>This offer is subject to following terms and conditions:</p>
                <ol>
                    @if ($sql->ServiceCondition == 'Training' && $sql->OrientationPeriod != null && $sql->Stipend != null)
                        <li>You shall report at
                            <strong>{{ optional($sql)->F_City ? $sql->F_City . ',' : '' }}
                                {{ getHq($sql->F_LocationHq) }}
                                ({{ getHqStateCode($sql->F_StateHq) }})</strong>,
                            for an orientation program of {{ $sql->OrientationPeriod }} months.
                            After completion of the orientation period, you shall be on a Training period of 12
                            months and during the period of training, you may be allocated various assignments at
                            different locations.
                            However, you may be required to (i) relocate to other locations in India; and/or (ii)
                            undertake such travel in India, (iii) overseas locations, from time to time, as may be
                            necessary in the interests of the Company's business.
                        </li>
                    @elseif($sql->TempS == 1 && $sql->FixedS == 1)
                        <li>For minimum {{ $months_word[$sql->TempM] }} months, your temporary headquarter will
                            be
                            <strong>{{ getHq($sql->T_LocationHq) }} ({{ getHqStateCode($sql->T_StateHq) }})
                            </strong>

                            @if ($sql->T_StateHq1 > 0)
                                or
                                <strong>{{ getHq($sql->T_LocationHq1) }} ({{ getHqStateCode($sql->T_StateHq1) }})
                                </strong>
                            @endif
                            which may be increased if needed after {{ $months_word[$sql->TempM] }} months and
                            then
                            your principal place of employment shall be at
                            <strong>{{ optional($sql)->F_City ? $sql->F_City . ',' : '' }}
                                {{ getHq($sql->F_LocationHq) }}
                                ({{ getHqStateCode($sql->F_StateHq) }})</strong>.
                            However, you may be
                            required to (i) relocate to other locations in India; and/or (ii) undertake such travel
                            in India, (iii) overseas locations, from time to time, as may be necessary in the
                            interests of the Company's business.
                        </li>
                    @elseif($sql->TempS == 1 && $sql->FixedS == 0)
                        <li>For minimum {{ $months_word[$sql->TempM] }} months, your temporary headquarter will
                            be
                            <strong>{{ getHq($sql->T_LocationHq) }}
                                ({{ getHqStateCode($sql->T_StateHq) }})</strong>
                            @if ($sql->T_StateHq1 > 0)
                                or
                                <strong>{{ getHq($sql->T_LocationHq1) }} ({{ getHqStateCode($sql->T_StateHq1) }})
                                </strong>
                            @endif which may be increased if needed after
                            {{ $months_word[$sql->TempM] }} months
                            However, you may be required to (i) relocate to other locations in India; and/or (ii)
                            undertake such travel in India, (iii) overseas locations, from time to time, as may be
                            necessary in the interests of the Company's business.
                        </li>
                    @else
                        <li>Your principal place of employment shall be at
                            <strong>{{ optional($sql)->F_City ? $sql->F_City . ',' : '' }}
                                {{ getHq($sql->F_LocationHq) }}({{ getHqStateCode($sql->F_StateHq) }})</strong>.
                            However, you may be
                            required to (i) relocate to other locations in India; and/or (ii) undertake such travel
                            in India, (iii) overseas locations, from time to time, as may be necessary in the
                            interests of the Company's business.
                        </li>
                    @endif
                    @if($sql->RepLineVisibility == 'Y')
                        @if ($sql->repchk == 'RepWithoutEmp')
                            <li>You will report to
                                <strong>{{ getDesignation($sql->reporting_only_desig) }}</strong> and will
                                work under the supervision of such officers as may be decided upon by the management
                                of
                                the Company, from time to time.
                            </li>
                        @else
                            @if ($sql->Functional_R != 0 && $sql->Admins_R != 0)
                                <li>For administrative purpose you shall be reporting to
                                    <strong>{{ getFullName($sql->A_ReportingManager) }},
                                        {{ getEmployeeDesignation($sql->A_ReportingManager) }}</strong>
                                    and for technical purpose you shall be reporting to
                                    <strong>{{ getFullName($sql->F_ReportingManager) }},
                                        {{ getEmployeeDesignation($sql->F_ReportingManager) }}</strong>
                                    and will work under the supervision of such officers as may be decided upon by the
                                    Management from time to time.
                                </li>
                            @else
                                <li>You will report to
                                    <strong>{{ getFullName($sql->A_ReportingManager) }},
                                        {{ getEmployeeDesignation($sql->A_ReportingManager) }}</strong> and will
                                    work under the supervision of such officers as may be decided upon by the management
                                    of
                                    the Company, from time to time.
                                </li>
                            @endif
                        @endif
                    @endif

                    @if ($sql->ServiceCondition == 'Training' && $sql->OrientationPeriod != null && $sql->Stipend != null)
                        <li>After completion of the Orientation period, You shall be on training for a period of 1
                            year from the Appointment Date <strong>(“Training Period”)</strong> and after completion
                            of the Training
                            Period, you will be confirmed subject to your satisfactory performance.
                        </li>
                    @elseif ($sql->ServiceCondition == 'Training' && $sql->AFT_Grade != 0)
                        <li>You shall be on training for a period of 1 year from the Appointment Date
                            <strong>(“Training Period”)</strong> and after completion of the Training Period, you
                            will be confirmed on the post of <b>{{ getDesignation($sql->AFT_Designation) }}</b>
                            at Grade
                            <b>{{ getGradeValue($sql->AFT_Grade) }}</b> subject to your satisfactory performance.
                            .
                        </li>
                    @elseif ($sql->ServiceCondition == 'Training')
                        <li>You shall be on training for a period of 1 (One) year from the Appointment Date
                            <strong>(“Training Period”)</strong> and after completion of the Training Period, you
                            will be confirmed subject to your satisfactory performance.
                        </li>
                    @elseif ($sql->ServiceCondition == 'Probation')
                        <li>You shall be on probation for a period of 6 (Six) months from the Appointment Date
                            <strong>(“Probation Period”)</strong> and after completion of the Probation Period, you
                            will be confirmed
                            subject to your satisfactory performance.
                        </li>
                    @endif

                    @if ($sql->ServiceBond == 'Yes')
                        <li>At the time of your appointment, you shall sign a service bond providing your consent to
                            serve the company for a minimum period of
                            <b>{{ $months_word[$sql->ServiceBondYears] }} </b>years
                            from the Appointment Date. In the event of dishonor of this service bond, you shall be
                            liable to pay the company a sum of <b>{{ $sql->ServiceBondRefund }} %</b> of your
                            annual
                            CTC as per the prevailing CTC rate {as on date of leaving}
                        </li>
                    @endif


                    @if ($sql->Company == 1)
                        {{-- VSPL --}}
                        @if ($sql->ServiceCondition == 'nopnot')
                                @if ($sql->Department == 15 || $sql->Department == 17 || $sql->Department == 2 || $sql->Department == 3 || $sql->Department == 10)
                                {{-- Salses && PD --}}
                                <li>During your employment Period, either you or the Company may terminate this
                                    employment by giving 3 (Three) months’ notice in writing or salary in lieu
                                    of
                                    such notice period.
                                </li>
                            @else
                                <li>During your employment Period, either you or the Company may terminate this
                                    employment by giving 1 (One) months’ notice in writing or salary in lieu
                                    of
                                    such notice period.
                                </li>
                            @endif
                        @else
                                @if ($sql->Department == 15 || $sql->Department == 17)
                                {{-- Salses && PD --}}
                                <li>During the {{ $sql->ServiceCondition }} Period, either you or the Company may
                                    terminate this
                                    employment by giving 1 (One) months’ notice in writing or salary in lieu
                                    of such notice period. Pursuant to your confirmation, the aforementioned notice
                                    period shall be
                                    of @if ($sql->Department == 15 || $sql->Department == 17 || $sql->Department == 10)
                                        3 (Three) months
                                    @else
                                        1 (One) month
                                    @endif in writing or the salary in lieu thereof.
                                </li>
                                @elseif($sql->Department == 2 || $sql->Department == 3 )
                                {{-- R&D --}}
                                <li>During the {{ $sql->ServiceCondition }} Period, either you or the Company may
                                    terminate this
                                    employment by giving 1 (One) month notice in writing or salary in lieu
                                    of such notice period. Pursuant to your confirmation, the aforementioned notice
                                    period shall be of 3 (Three) months’
                                    in writing or the salary in lieu thereof.
                                </li>
                                @elseif($sql->Department == 14)
                                {{-- QA --}}
                                <li>During the {{ $sql->ServiceCondition }} Period, either you or the Company may
                                    terminate this
                                    employment by giving 1 (One) month notice in writing or salary in lieu
                                    of such notice period. Pursuant to your confirmation, the aforementioned notice
                                    period shall be of 3 (Three) month
                                    in writing or the salary in lieu thereof.
                                </li>
                            @else
                                <li>During the {{ $sql->ServiceCondition }} Period, either you or the Company may
                                    terminate this
                                    employment by giving 15 days’ notice in writing or salary in lieu
                                    of such notice period. Pursuant to your confirmation, the aforementioned notice
                                    period shall be of 1 (One) month in writing or the salary in lieu thereof.
                                </li>
                            @endif
                        @endif
                    @elseif ($sql->Company == 3)
                        {{-- VNPL --}}
                        <li>In case of discontinuation of service, during the period of
                            {{ $sql->ServiceCondition }} the
                            notice period will be one month and after confirmation of the service the notice period
                            will be of
                            two month.
                        </li>
                    @endif

                    @if ($sql->ServiceCondition == 'Training' && $sql->OrientationPeriod != null && $sql->Stipend != null)
                        <li>During the period of Orientation, you shall receive a consolidated stipend of Rs.
                            {{ $sql->Stipend }}/- per month.
                            After completion of your Orientation period, your annual CTC and entitlements details
                            shall be as mentioned in the Annexures A and B attached hereto.
                        </li>
                    @else
                        <li>Your annual CTC and entitlements details shall be as mentioned in the Annexures A and B
                            attached hereto.
                        </li>
                    @endif

                    <li>You shall look after all the duties & responsibilities assigned to you from time to time,
                        based on the business requirement. It may be subject to changes at the sole discretion of
                        the Company.
                    </li>
                    <li>Your employment with the Company will be governed by the Company’s service rules and
                        conditions which will be detailed in your appointment letter, issued pursuant to your
                        acceptance of this offer.
                    </li>
                    <li>The validity of this offer letter and continuation of your service is subject to your being
                        found physically, mentally, and medically fit and remaining so during your service.
                    </li>


                </ol>
                <p>We are glad that very soon you will be part of our team. We look forward to your long and
                    meaningful
                    association with us. </p>
                <p>Yours Sincerely,</p><br><br>
                <p style="margin-bottom: 0px;"><b>Authorized Signatory</b></p>
                <p><b>{{ $sql->SigningAuth }} </b>
                </p>
                <hr style="height:1px;border-width:0;color:black;background-color:black;">
                <p>I,  {{ $sql->FName }} {{ $sql->MName }} {{ $sql->LName }},
                    @if ($sql->Gender == 'M')
                        S/o.
                    @else
                        D/o.
                    @endif
                    {{ $sql->FatherTitle }} {{ ucwords(strtolower($sql->FatherName)) }} have read and
                    understood the above
                    terms
                    and
                    conditions and
                    I agree to abide by them. I will join on Date: ................ failing which I have no lien on
                    this
                    employment.
                </p>
                <p style="margin-bottom: 0px;">----------------------
                    &emsp;&emsp;&emsp;&emsp;&emsp;&emsp;---------------------&emsp;&emsp;&emsp;&emsp;&emsp;-------------------------
                </p>
                <p>Place
                    &emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;Date&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;
                     {{ $sql->FName }} {{ $sql->MName }} {{ $sql->LName }}
                </p>
            </div>
        </div>
    </div>

    <div id="ctc">
        <div class="page">
            <div class="subpage">
                <p style="font-size:16px;"><b>Ref:</b> {{ $sql->LtrNo }}
                    <span style="float: right"><b>Date: </b>
                            @if ($sql->LtrDate == null)
                            {{ date('d-m-Y') }}
                        @else
                            {{ date('d-m-Y', strtotime($sql->LtrDate)) }}
                        @endif
                        </span>
                </p><br>
                <p class="text-center"><b>ANNEXURE A – COMPENSATION STRUCTURE</b></p>
                <br>
                <center>
                    <table class="table">
                        <tr>
                            <th class="text-center">Emolument Head</th>
                            <th class="text-center">Amount (in Rs.)</th>
                        </tr>
                        <tr>
                            <td colspan="2" class="text-center">(A) Monthly Components</td>
                        </tr>
                        <tr>
                            <td>Basic</td>
                            <td class="text-center">{{ $ctc->basic ?? '' }}</td>
                        </tr>
                        @if($ctc->hra)
                            <tr>
                                <td>HRA</td>
                                <td class="text-center">{{ $ctc->hra ?? '' }}</td>
                            </tr>
                        @endif
                        @if ($ctc->bonus)
                            <tr>
                                <td>Bonus<sup>1</sup></td>
                                <td class="text-center">{{ $ctc->bonus ?? '' }}</td>
                            </tr>
                        @endif
                        @if ($ctc->special_alw)
                            <tr>
                                <td>Special Allowance</td>
                                <td class="text-center">{{ $ctc->special_alw ?? '' }}</td>
                            </tr>
                        @endif
                        <tr>
                            <th>Gross Monthly Salary</th>
                            <td class="text-center" style="font-weight: bold;">{{ $ctc->grsM_salary ?? '' }}</td>
                        </tr>
                        <tr>
                            <td>Employee's PF Contribution</td>
                            <td class="text-center">{{ $ctc->emplyPF ?? '' }}</td>
                        </tr>
                        <tr class="{{ $ctc->grsM_salary > 21000 ? 'd-none' : '' }}">
                            <td>Employee’s ESIC Contribution</td>
                            <td class="text-center">{{ $ctc->emplyESIC ?? '' }}</td>
                        </tr>
                        <tr>
                            <th>Net Monthly Salary</th>
                            <td class="text-center" style="font-weight: bold;">{{ $ctc->netMonth ?? '' }} </td>
                        </tr>
                        <tr>
                            <td class="text-center" colspan="2">(B) Annual Components (Tax saving
                                components
                                which shall
                                be
                                reimbursed on production of documents at the end of financial year)
                            </td>
                        </tr>
                        <tr>
                            <td>Leave Travel Allowance</td>
                            <td class="text-center">{{ $ctc->lta }} </td>
                        </tr>
                        <tr>
                            <td>Child Education Allowance</td>
                            <td class="text-center">{{ $ctc->childedu }}</td>
                        </tr>
                        <tr>
                            <th>Annual Gross Salary</th>
                            <td class="text-center" style="font-weight: bold;">{{ $ctc->anualgrs ?? '' }}</td>
                        </tr>
                        <tr>
                            <td colspan="2" class="text-center">(C) Other Annual Components ( Statutory
                                Components)
                            </td>
                        </tr>
                        <tr>
                            <td>Estimated Gratuity<sup>2</sup></td>
                            <td class="text-center">{{ $ctc->gratuity ?? '' }}</td>
                        </tr>
                        <tr>
                            <td>Employer’s PF contribution</td>
                            <td class="text-center">{{ $ctc->emplyerPF ?? '' }}</td>
                        </tr>
                        <tr class="{{ $ctc->grsM_salary > 21000 ? 'd-none' : '' }}">
                            <td>Employer’s ESIC contribution</td>
                            <td class="text-center">{{ $ctc->emplyerESIC ?? '' }} </td>
                        </tr>
                        <tr>
                            <td>Insurance Policy Premium</td>
                            <td class="text-center">{{ $ctc->medical ?? '' }}</td>
                        </tr>
                        @if ($sql->LtrDate < '2022-06-22' || $sql->Company != 1)
                            <tr>
                                <th>Total Cost to Company</th>
                                <td class="text-center">{{ $ctc->fixed_ctc ?? '' }} </td>
                            </tr>
                        @else
                            <tr>
                                <th>Fixed CTC</th>
                                <td class="text-center" style="font-weight: bold;">{{ $ctc->fixed_ctc ?? '' }} </td>
                            </tr>

                            <tr>
                                <td>Performance Pay<sup>3</sup></td>
                                <td id="variable_pay" class="text-center">
                                  {{ $ctc->performance_pay ?? '' }}
                                </td>
                            </tr>

                            <tr>
                                <th>Total CTC</th>
                                <td class="text-center" id="total_ctc" style="font-weight: bold;">
                                    {{ $ctc->total_ctc ?? '' }}
                                </td>
                            </tr>
                            @if (($sql->Vehicle_Policy == 13 && $sql->Grade >= 70) || $ctc->communication_allowance == 'Y')
                            <tr>
                                <td style="text-align:center" colspan="2">(D) Perks
                                </td>
                            </tr>
                            @if ($sql->Vehicle_Policy == 13 && $sql->Grade >= 70)
                                <tr>
                                    <td>Car Allowance<sup>4</sup></td>
                                    <td class="text-center">
                                        @php
                                            $car_allowance_data = $policy_conn
                                                ->table('hrm_master_eligibility_policy_tbl' . $sql->Vehicle_Policy)
                                                ->where('GradeId', $sql->Grade)
                                                ->first();
                                            if ($car_allowance_data == null) {
                                                $car_allowance = 0;
                                            } else {
                                                $car_allowance = $car_allowance_data->Fn36 * 12;
                                            }
    
                                        @endphp
                                        {{ $car_allowance }}
                                    </td>
                                </tr>
                            @endif
                            @if ($ctc->communication_allowance == 'Y')
                                <tr>
                                    <td>Communication Allowance</td>
                                    <td class="text-center">{{ $ctc->communication_allowance_amount }}</td>
                                </tr>
                            @endif
    
                            <tr>
                                <th>Total Gross CTC</th>
                                <td class="text-center" id="final_ctc" style="font-weight: bold;">
                                    {{ $ctc->total_gross_ctc ?? '' }}
                                </td>
                            </tr>
                        @endif
                        @endif

                    </table>
                </center>
                <p><b>Notes:</b></p>
                <ol type="1">
                    <li>Bonus shall be paid as per The Code of Wages Act, 2019</li>
                    <li>The Gratuity to be paid as per The Code on Social Security, 2020.</li>
                    @if ($sql->Company == 1)
                        <li>Performance Pay</li>
                    @endif
                </ol>
                @if ($sql->Company == 1)
                    <ul type="none">
                        <li>
                            <ol type="a">
                                <li>Performance Pay is an annually paid variable component of CTC, paid in July
                                    salary.
                                </li>
                                <li>This amount is indicative of the target variable pay, actual pay-out will vary
                                    based on the performance of Company and Individual.
                                </li>
                                <li>It is linked with Company performance (as per fiscal year) and Individual
                                    Performance (as per appraisal period for minimum 6 months working, pro-rata
                                    basis if <1 year working).
                                </li>
                                <li>The calculation shall be based on the pre-defined performance measures at both,
                                    Company & Individual level.
                                </li>
                            </ol>
                        </li>
                    </ul>
                    <p>For more details refer to the Company’s Performance Pay policy.</p>
                @endif


                <br><br>
                <p style="margin-bottom:2px;">----------------------------<span
                        style="float: right">----------------------------</span></p>
                <p style="margin-bottom: 0px;"><b>Authorized Signatory</b><span
                        style="float: right">{{ $sql->FName }} {{ $sql->MName }}
                        {{ $sql->LName }}</span>
                </p>
                <p><b>{{ $sql->SigningAuth }} </b>
                </p>
            </div>

        </div>
    </div>

    <div id="entitlement">
        <div class="page">
            <div class="subpage">
                <p style="margin-bottom:80px;"></p>
                <p style="font-size:16px;"><b>Ref:</b> {{ $sql->LtrNo }}
                    <span style="float: right"><b>Date:</b> {{ $sql->LtrDate }}</span>
                </p><br>
                <p class="text-center"><b>ANNEXURE B – ENTITLEMENTS</b></p>
                <br>
                <p style="margin-bottom: 0;"><b>* Lodging Entitlements :</b> (Actual with upper limits per day)</p>
                <table class="table" style="width: 100%;font-size:16px;">
                    <tr>
                        <td>City Category</td>
                        <td style="text-align: center;">A</td>
                        <td style="text-align: center;">B</td>
                        <td style="text-align: center;">C</td>
                    </tr>
                    <tr>
                        <td>Amount (in Rs.)</td>
                        <td style="text-align: center;">{{ $elg->LoadCityA }}</td>
                        <td style="text-align: center;">{{ $elg->LoadCityB }}</td>
                        <td style="text-align: center;">{{ $elg->LoadCityC }}</td>
                    </tr>
                </table>
                @if(!empty($elg->DAHq) || !empty($elg->DAOut))
                    <p style="margin-bottom: 0;"><b>* Daily Allowances :</b></p>
                    <table class="table" style="width: 100%;font-size:16px;">
                        @if (!empty($elg->DAHq))
                            <tr>
                                <td>DA@HQ : {{$elg->DAHq_Rmk}}</td>
                                <td>{{$elg->DAHq}}</td>
                            </tr>
                        @endif
                        @if (!empty($elg->DAOut))
                            <tr>
                                <td>@if ($sql->Department == 2 || $sql->Department == 3)
                                        Fooding Expense (For outside HQ travel with night halt)
                                    @else
                                        DA Outside HQ
                                    @endif : {{$elg->DAOut_Rmk}}</td>
                                <td>{{$elg->DAOut}}</td>
                            </tr>
                        @endif
                    </table>
                @endif
                <p style="margin-bottom: 0;"><b>* Travel Eligibility:</b> (For Official Purpose Only)</p>
                @if($sql->Vehicle_Policy >0 && $sql->Vehicle_Policy != 'NA')
                    @if(!in_array($sql->Vehicle_Policy, [3, 8, 9, 10]))
                        @if(!empty($elg->TwoWheel) && $elg->TwoWheel != 'NA')
                            <table class="table" style="width: 100%;font-size:16px;">
                                <tr>
                                    <td style="width:40%;font-size:16px;">2 Wheeler :</td>
                                    @if($sql->Department == 2 || $sql->Department == 3 && ($sql->Grade == 61 ||$sql->Grade == 62 ||$sql->Grade == 63||$sql->Grade == 64 ))
                                        <td>Rs. {{$elg->TwoWheel}}  {{$elg->TwoWheel_Rmk}}</td>
                                    @else
                                        <td>Rs. {{$elg->TwoWheel}} /Km {{$elg->TwoWheel_Rmk}}</td>
                                    @endif

                                </tr>
                            </table>
                        @endif
                    @endif
                    <table class="table" style="width: 100%;font-size:16px;">
                        <tr>
                            <td>Policy Name:</td>
                            <td>{{get_vehicle_policy_name($sql->Vehicle_Policy)}}</td>
                        </tr>
                        @php
                            $MinKm = '';
                            $MaxKm = '';
                            $YrMaxKm = '';

                            // Fetch policy data
                            $policy_data = $policy_conn->table('hrm_master_eligibility_policy_tbl' . $sql->Vehicle_Policy)
                                ->where('GradeId', $sql->Grade)
                                ->first();

                            // Ensure policy data exists
                            if ($policy_data) {
                                // Conditions based on Vehicle_Policy
                                if (in_array($sql->Vehicle_Policy, [3, 4, 6, 8, 9, 10, 11, 12])) {
                                    if (in_array($sql->Vehicle_Policy, [4, 11])) {
                                        // Handling Fn8 and Fn32 for policies 4 and 11
                                        $MaxKm = !empty($policy_data->Fn8) ? preg_replace('/[^0-9.]/', '', $policy_data->Fn8)
                                                : (!empty($policy_data->Fn32) ? preg_replace('/[^0-9.]/', '', $policy_data->Fn32) : '');
                                    } else {
                                        // Handling Fn7 and Fn9 for other specified policies
                                        $MinKm = !empty($policy_data->Fn7) ? preg_replace('/[^0-9.]/', '', $policy_data->Fn7) : '';
                                        $MaxKm = !empty($policy_data->Fn9) ? preg_replace('/[^0-9.]/', '', $policy_data->Fn9) : '';
                                    }
                                } elseif (in_array($sql->Vehicle_Policy, [1, 2, 5, 7])) {
                                    // Handling Fn8 and Fn9 for these policies
                                    $MaxKm = !empty($policy_data->Fn9) ? preg_replace('/[^0-9.]/', '', $policy_data->Fn9)
                                            : (!empty($policy_data->Fn8) ? preg_replace('/[^0-9.]/', '', $policy_data->Fn8) : '');
                                }
                            }

                            // Calculating YrMaxKm based on conditions
                            $YrMaxKm = in_array($sql->Vehicle_Policy, [3, 4, 6, 8, 9, 10, 11]) ? $MaxKm : intval($MaxKm) * 12;
                        @endphp

                        <tr>
                            <td colspan="2">Policy Details (<b style="color: blue;">Running KM Plan:
                                    @if(in_array($sql->Vehicle_Policy, ['3', '6', '8', '9', '10', '11']))
                                        Monthly , Max: {{$YrMaxKm}} KM
                                    @else
                                        Yearly , Max: {{$YrMaxKm}} KM
                                    @endif
                                </b>)
                            </td>
                        </tr>
                        @php
                            $checkVpIds = [1, 2, 5, 7];
                        @endphp

                        @if(in_array($sql->Vehicle_Policy, $checkVpIds))
                            @php

                                $fieldIds = "(1, 3, 4, 2, 5, 15, 23, 16, 24, 17, 25, 18, 26, 8)";
                            // Fetch policies with matching field_ids
                            $policies = $policy_conn->table('hrm_master_eligibility_mapping_tblfield as m')
                                ->join('hrm_master_eligibility_field as f', 'm.FieldId', '=', 'f.FieldId')
                                ->select('m.MappId', 'm.PolicyId', 'm.FieldId', 'm.FOrder', 'm.Sts', 'f.FiledName')
                                ->where('m.PolicyId', $sql->Vehicle_Policy)
                                ->whereIn('m.FieldId', explode(', ', trim($fieldIds, '()')))
                                ->where('m.Sts', 1)
                                ->orderBy('m.FOrder')
                                ->get();
                            @endphp
                        @else
                            @php
                                // Fetch policies without matching field_ids
                                $policies = $policy_conn->table('hrm_master_eligibility_mapping_tblfield as m')
                                    ->join('hrm_master_eligibility_field as f', 'm.FieldId', '=', 'f.FieldId')
                                    ->select('m.MappId', 'm.PolicyId', 'm.FieldId', 'm.FOrder', 'm.Sts', 'f.FiledName')
                                    ->where('m.PolicyId', $sql->Vehicle_Policy)
                                    ->where('m.Sts', 1)
                                    ->orderBy('m.FOrder')
                                    ->get();
                            @endphp
                        @endif

                        @foreach($policies as $field)
                            @php
                                // Fetch additional data for each field
                                $sdata =  $policy_conn->table('hrm_master_eligibility_policy_tbl'.$sql->Vehicle_Policy)
                                    ->where('GradeId', $sql->Grade)
                                    ->first();
                            @endphp
                            @if($sdata != null && $sdata->{'Fn' . $field->FieldId} != null)
                                <tr>
                                    <td style="width:40%;font-size:16px;">&nbsp;{{ $field->FiledName }}:</td>
                                    <td style="width:60%;" align="center">
                                        &nbsp;{{ $sdata->{'Fn' . $field->FieldId} ?? '' }}</td>
                                </tr>
                            @endif

                        @endforeach


                    </table>
                @else
                    @if(!empty($elg->TwoWheel) || !empty($elg->FourWheel))

                        <table class="table" style="width: 100%;font-size:16px;">
                            @if(!empty($elg->TwoWheel) && $elg->TwoWheel != 'NA')
                                <tr>
                                    <td>2 Wheeler :</td>
                                    @if($sql->Department == 2  || $sql->Department == 3 && ($sql->Grade == 61 ||$sql->Grade == 62 ||$sql->Grade == 63||$sql->Grade == 64 ))
                                        <td>Rs. {{$elg->TwoWheel}}  {{$elg->TwoWheel_Rmk}}</td>
                                    @else
                                        <td>Rs. {{$elg->TwoWheel}} /Km {{$elg->TwoWheel_Rmk}}</td>
                                    @endif

                                </tr>
                            @endif
                            @if(!empty($elg->FourWheel) && $elg->FourWheel != 'NA')
                                <tr>
                                    <td>4 Wheeler :</td>
                                    <td>Rs. {{$elg->FourWheel}} /Km {{$elg->FourWheel_Rmk}}</td>
                                </tr>
                            @endif

                        </table>
                    @endif
                @endif
             {{--   @if(!empty($elg->CostOfVehicle) && $elg->CostOfVehicle != 'NA')
                    <table class="table" style="width: 100%;font-size:16px;">
                        <tr>
                            <td style="width: 40%">Vehicle Entitlement value :</td>
                            <td>{{$elg->CostOfVehicle}}</td>
                        </tr>
                    </table>
                @endif--}}

                @if($elg->Train == 'Y' || $elg->Flight == 'Y')
                    <table class="table" style="width: 100%;font-size:16px;">
                        <tr>
                            <td>Mode/Class of Travel Outside HQ :</td>
                            <td>
                                @if($elg->Flight == 'Y')
                                    <b>Flight - </b> {{$elg->Flight_Class}} {{$elg->Flight_Remark}} <br/>
                                @endif
                                @if($elg->Train == 'Y')
                                    <b>Train/Bus - </b> {{$elg->Train_Class}} {{$elg->Train_Remark}}</b>
                                @endif
                            </td>
                        </tr>
                    </table>
                @endif
                @if(($elg->Mobile_Allow =='Y' && !empty($elg->Mobile)) || (!empty($elg->Mobile_Remb) && (!empty($elg->MExpense) || !empty($elg->Mobile_RembPost))))
                    <p style="margin-bottom: 0;"><b>* Mobile Eligibility :</b></p>
                    <table class="table" style="width: 100%;font-size:16px;">
                        @if (!empty($elg->Mobile_Remb) && (!empty($elg->MExpense) || !empty($elg->Mobile_RembPost)))
                            <tr>
                                <td> Mobile expenses Reimbursement :</td>
                                <td>
                                    @if(!empty($elg->MExpense))
                                        <b>Prepaid:</b> Rs. {{$elg->MExpense}}
                                        /{{$elg->MTerm}} {{$elg->Mobile_Remb_Period_Rmk}} <br/>
                                    @endif
                                    @if(!empty($elg->Mobile_RembPost))
                                        <b>Postpaid:</b> Rs. {{$elg->Mobile_RembPost}}
                                        /{{$elg->Mobile_RembPost_Period}} {{$elg->Mobile_RembPost_Period_Rmk}}
                                    @endif
                                </td>
                            </tr>
                        @endif
                        @if ($elg->Mobile_Allow == 'Y' && !empty($elg->Mobile))
                            <tr>
                                <td>Mobile Handset Eligibility :</td>
                                <td>Rs. {{$elg->Mobile}}
                                    @if($elg->GPRS =='1')
                                        <b> (Once in 2 yrs)</b>
                                    @else
                                        <b>(Once in 3 yrs)</b>
                                    @endif
                                    (Subject to submission of bills)
                                </td>
                            </tr>
                        @endif
                    </table>
                @endif
                <p style="margin-bottom: 0;"><b>* Insurance :</b></p>
                <table class="table" style="width: 100%;font-size:16px;">
                    <tr class="{{ $ctc->grsM_salary < 21000 ? 'd-none' : '' }}">
                        <td>Health Insurance (Sum Insured):</td>
                        <td style="text-align: center;">Rs. {{$elg->HealthIns}}</td>

                    </tr>
                    <tr>
                        <td>Group Term Life Insurance (Sum Insured) :</td>
                        <td style="text-align: center;"> Rs.
                            {{$elg->Term_Insurance}}
                        </td>
                    </tr>
                    @if($age >=40)
                        <tr>
                            <td>Executive Health Check-up: <b>(Min. Age > 40Yrs, once in 2yrs)</b></td>
                            <td style="text-align: center;">Rs. {{$elg->Helth_CheckUp}}</td>
                        </tr>
                    @endif
                </table>
                <br>

                <br>
                <b>Note: </b>
                <ul>
                    <li>The above 2 Wheeler & 4 Wheeler travel eligibility is subject to submission of vehicle details belonging in the name of employee only.</li>
                    <li>The vehicle must be under the name of employee only in all cases.</li>
                </ul>
                <p class="text-center"><b><u>LIST OF DOCUMENTS REQUIRED DURING APPOINTMENT</u> (whichever is
                        applicable)</b></p>
                <ol>

                    <li style="font-size:14px;">3 colored formal Passport Size Photos with white background
                    </li>
                    <li style="font-size:14px;">Blood Group Test report</li>
                    <li style="font-size:14px;">Copy of educational certificates (10th / 12th / Graduation
                        / Post Graduation, etc.)
                    </li>
                    <li style="font-size:14px;">Previous Employers documents (Service Certificates) -if any
                    </li>
                    <li style="font-size:14px;">Pay slip/ CTC structure of recent previous company -if any
                    </li>
                    <li style="font-size:14px;">Form 16 / Pervious Taxation Record -if any</li>
                    <li style="font-size:14px;">Relieving letter from previous company/ Resignation
                        Acceptance Letter (if any)
                    </li>
                    <li style="font-size: 14px;">Compulsory Documents (Self-attested): Driving License, PAN
                        Card, Bank Passbook (Preferred only BOB or SBI), E-Aadhar Card
                    </li>
                    <li style="font-size:14px;">Copy of Signed Offer Letter (Hardcopy)</li>
                    <li style="font-size:14px;">Covid Vaccination Certificate</li>
                    @if ($ctc->grsM_salary < 21000)
                        <li style="font-size:14px;">Aadhar card of each family members</li>
                    @endif
                </ol>
                <br><br><br><br>
                <p style="margin-bottom:2px;">----------------------------<span
                        style="float: right">----------------------------</span></p>
                <p style="margin-bottom: 0px;"><b>Authorized Signatory</b><span
                        style="float: right">{{ $sql->FName }} {{ $sql->MName }}
                        {{ $sql->LName }}</span>
                </p>
                <p><b> {{ $sql->SigningAuth }}</b>
                </p>
            </div>


        </div>
    </div>

    @if ($check->Status == null)
        <div class="generate" id="generate">
            <form method="POST" action="{{ route('ReviewResponse') }}" id="responseform">
                @csrf
                <input type="hidden" name="JAId" value="{{ $JAId }}" id="JAId">
                <input type="hidden" name="EmpId" value="{{ $EmpId }}" id="EmpId">
                <div class="mb-5 ">
                    <center>
                        <b>Offer Letter Review Reply</b><br><br>
                        <label class="btn btn-sm text-success font-weight-bold"
                               onclick="$('#jnfrmFormDiv').show(500);$('#Rejreason').hide(500);$('#RejReason').removeAttr('required'); $('#JoinOnDt').attr('required', 'true');">
                            <input type="radio" name="Answer" value="Accepted">
                            <i class="fa fa-check" aria-hidden="true"></i> Approve Offer
                        </label>
                        <label class="btn btn-sm text-danger font-weight-bold"
                               onclick="$('#jnfrmFormDiv').hide(500);$('#Rejreason').show(500);$('#RejReason').attr('required', 'true');$('#JoinOnDt').removeAttr('required'); ">
                            <input type="radio" name="Answer" value="Rejected">
                            <i class="fa fa-times" aria-hidden="true"></i> Reject Offer
                        </label>
                        <br>

                        <span id="Rejreason" style="display: none;">
                                <br>
                                Please Mention Reason for Rejection:
                                <br>
                                <textarea class="form-control d-inline-block frminp reqinp" name="RejReason"
                                          id="RejReason" style="width: 400px;"></textarea>
                                <br>
                            </span>
                        <button class="btn btn-sm btn-primary px-5" type="submit">
                            Submit
                        </button>
                    </center>
                </div>
            </form>

        </div>
    @else
        <div class="generate mb-4" id="generate">
            <center>
                <p style="font-size:18px;color:indigo">
                    @if ($check->Status == 'Accepted')
                        <b>Your response "{{ $check->Status }}" is duly submitted.</b>

                    @else
                        <b>Your response "{{$check->Status}}" with reason "{{$check->RejReason}}" is duly submitted.</b>
                    @endif


                </p>
            </center>
        </div>
    @endif

</div>

<script src="{{ URL::to('/') }}/assets/js/sweetalert2.min.js"></script>
<script src="{{ URL::to('/') }}/assets/js/toastr.min.js"></script>
<script>
    $(document).ready(function () {
        window.history.pushState(null, "", window.location.href);
        window.onpopstate = function () {
            window.history.pushState(null, "", window.location.href);
        };
        $(document).bind("contextmenu", function (e) {
            return false;
        });

        function killCopy(e) {
            return false
        }

        function reEnable() {
            return true
        }

        document.onselectstart = new Function("return false");
        if (window.sidebar) {
            document.onmousedown = killCopy;
            document.onclick = reEnable;
        }
        $('#responseform').on('submit', function (e) {
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
                success: function (data) {
                    if (data.status == 400) {
                        toastr.error(data.msg);
                    } else {
                        $(form)[0].reset();
                        toastr.success(data.msg);
                        setTimeout(function () {
                            window.location.reload();
                        }, 1000);

                    }
                }
            });
        });
    });
</script>
</body>

</html>
