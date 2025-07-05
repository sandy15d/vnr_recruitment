<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="{{ URL::to('/') }}/assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&amp;display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ URL::to('/') }}/assets/css/sweetalert2.min.css" />
    <link rel="stylesheet" href="{{ URL::to('/') }}/assets/css/toastr.min.css" />
    <script src="{{ URL::to('/') }}/assets/js/jquery.min.js"></script>
    <script src="https://kit.fontawesome.com/b0b5b1cf9f.js" crossorigin="anonymous"></script>
    <title>Offer Letter Generation</title>
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

            /*    height: 297mm; */

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
</head>
@php

    $JAId = base64_decode($_REQUEST['jaid']);

    $sql = DB::table('offerletterbasic')
        ->leftJoin('jobapply', 'offerletterbasic.JAId', '=', 'jobapply.JAId')
        ->leftJoin('jobcandidates', 'jobapply.JCId', '=', 'jobcandidates.JCId')
        ->leftJoin('jf_contact_det', 'jobcandidates.JCId', '=', 'jf_contact_det.JCId')
        ->leftJoin('jf_family_det', 'jobcandidates.JCId', '=', 'jf_family_det.JCId')
        ->select(
            'offerletterbasic.*',
            'jobcandidates.DOB',
            'jobcandidates.Title',
            'jobcandidates.FName',
            'jobcandidates.MName',
            'jobcandidates.LName',
            'jobcandidates.FatherTitle',
            'jobcandidates.FatherName',
            'jobcandidates.Gender',
            'jobapply.ApplyDate',
            'jf_contact_det.perm_address',
            'jf_contact_det.perm_city',
            'jf_contact_det.perm_dist',
            'jf_contact_det.perm_state',
            'jf_contact_det.perm_pin',
        )
        ->where('jobapply.JAId', $JAId)
        ->first();
    $age = \Carbon\Carbon::parse($sql->DOB)->diff(\Carbon\Carbon::now())->format('%y');

    $ctc = DB::table('candidate_ctc')->select('*')->where('JAId', $JAId)->first();

    $elg = DB::table('candidate_entitlement')->select('*')->where('JAId', $JAId)->first();

    $old_elg = DB::table('offerletterbasic_history')->where('JAId', $JAId)->limit(1)->orderBy('seq', 'desc')->first();

    $months_word = [
        'One' => '1 (One)',
        'Two' => '2 (Two)',
        'Three' => '3 (Three)',
        'Four' => '4 (Four)',
        'Five' => '5 (Five)',
        'Six' => '6 (Six)',
        'Seven' => '7 (Seven)',
        'Eight' => '8 (Eight)',
        'Nine' => '9 (Nine)',
        'Ten' => '10 (Ten)',
        'Eleven' => '11 (Eleven)',
        'Twelve' => '12 (Twelve)',
    ];
    $policy_conn = DB::connection('mysql3');

@endphp

<body>
    <div class="container">
        <input type="hidden" name="jaid" id="jaid" value="{{ $JAId }}">
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
                    <p style="margin-bottom: 0px;"><b>{{ $sql->Title }} {{ $sql->FName }} {{ $sql->MName }}
                            {{ $sql->LName }}</b>
                    </p>
                    <b>
                        <p style="margin-bottom: 0px;">{{ $sql->perm_address }}</p>
                        <p style="margin-bottom: 0px;">{{ $sql->perm_city }},
                            Dist-{{ getDistrictName($sql->perm_dist) }},{{ getStateName($sql->perm_state) }},
                            {{ $sql->perm_pin }}
                        </p>
                    </b><br />
                    <p class="text-center"><b><u>Subject: Offer for Employment</u></b></p>
                    <b>
                        <p>Dear {{ $sql->Title }} {{ $sql->FName }} {{ $sql->MName }} {{ $sql->LName }},</p>
                    </b>
                    <p>We are pleased to offer you the position of <b>{{ getCandidateFullDesignation($sql->JAId) }}</b>
                        at
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

                                @if ($sql->T_StateHq1 != '0')
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
                            <li>For initial {{ $months_word[$sql->TempM] }} months, your temporary headquarter will
                                be
                                <strong>{{ getHq($sql->T_LocationHq) }}
                                    ({{ getHqStateCode($sql->T_StateHq) }})</strong>
                                @if ($sql->T_StateHq1 != '0')
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
                                    {{ getHq($sql->F_LocationHq) }}
                                    ({{ getHqStateCode($sql->F_StateHq) }})</strong>.
                                However, you may be
                                required to (i) relocate to other locations in India; and/or (ii) undertake such travel
                                in India, (iii) overseas locations, from time to time, as may be necessary in the
                                interests of the Company's business.
                            </li>
                        @endif

                        @if ($sql->RepLineVisibility == 'Y')
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
                                        and will work under the supervision of such officers as may be decided upon by
                                        the
                                        Management from time to time.
                                    </li>
                                @else
                                    <li>You will report to
                                        <strong>{{ getFullName($sql->A_ReportingManager) }},
                                            {{ getEmployeeDesignation($sql->A_ReportingManager) }}</strong> and will
                                        work under the supervision of such officers as may be decided upon by the
                                        management
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
                                @if (
                                    $sql->Department == 15 ||
                                        $sql->Department == 17 ||
                                        $sql->Department == 2 ||
                                        $sql->Department == 3 ||
                                        $sql->Department == 10)
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
                                @elseif($sql->Department == 2 || $sql->Department == 3)
                                    {{-- R&D --}}
                                    <li>During the {{ $sql->ServiceCondition }} Period, either you or the Company may
                                        terminate this
                                        employment by giving 1 (One) month notice in writing or salary in lieu
                                        of such notice period. Pursuant to your confirmation, the aforementioned notice
                                        period shall be of 3 (Three) month's
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

                        {{--   <li>You shall look after all the duties & responsibilities assigned to you from time to time,
                           based on the business requirement. It may be subject to changes at the sole discretion of
                           the Company.
                       </li> --}}
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
                    {{-- <p>Yours Sincerely,</p><br><br>
                 <p style="margin-bottom: 0px;"><b>Authorized Signatory</b></p>
                 <p><b>{{ $sql->SigningAuth }} </b>
                 </p> --}}
                    <hr style="height:1px;border-width:0;color:black;background-color:black;">
                    <p>I, {{ $sql->Title }} {{ $sql->FName }} {{ $sql->MName }} {{ $sql->LName }},
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
                        {{ $sql->Title }} {{ $sql->FName }} {{ $sql->MName }} {{ $sql->LName }}
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

                    <table class="table" style="">
                        <form id="ctcform">
                            @csrf
                            <tr>
                                <th class="text-center">Emolument Head</th>
                                <th class="text-center">Amount (in Rs.)</th>
                            </tr>
                            <tr>
                                <td colspan="2" class="text-center">(A) Monthly Components</td>
                            </tr>
                            <tr>
                                <td>Basic</td>
                                <td><input type="text" class="form-control text-center" id="basic"
                                        style="height: 21px;border: 0px none;" value="{{ $ctc->basic ?? '' }}"
                                        onchange="calculate()">
                                </td>
                            </tr>
                            <tr>
                                <td>HRA</td>
                                <td><input type="text" class="form-control text-center" id="hra"
                                        style="height: 21px;border: 0px none;" value="{{ $ctc->hra ?? '' }}"
                                        onchange="calculate()">
                                </td>
                            </tr>
                            <tr>
                                <td>Bonus<sup>1</sup></td>
                                <td><input type="text" class="form-control text-center" id="bonus"
                                        style="height: 21px;border: 0px none;" value="{{ $ctc->bonus ?? '' }}"
                                        {{-- onchange="calculate()" --}} readonly>
                                    <input type="hidden" name="old_bonus" id="old_bonus"
                                        value="{{ $ctc->bonus ?? '' }}">
                                </td>
                            </tr>
                            <tr>
                                <td>Special Allowance</td>
                                <td><input type="text" class="form-control text-center" id="special_alw"
                                        style="height: 21px;border: 0px none;" value="{{ $ctc->special_alw ?? '' }}"
                                        onchange="calculate()">
                                </td>
                            </tr>
                            <tr>
                                <th>Gross Monthly Salary</th>
                                <td><input type="text" class="form-control text-center font-weight-bold"
                                        id="grsM_salary" style="height: 21px;border: 0px none;font-weight: bold;"
                                        value="{{ $ctc->grsM_salary ?? '' }}" disabled readonly>
                                </td>
                            </tr>
                            <tr>
                                <td>Employee's PF Contribution</td>
                                <td><input type="text" class="form-control text-center" id="emplyPF"
                                        style="height: 21px;border: 0px none;" value="{{ $ctc->emplyPF ?? '' }}"
                                        readonly>
                                </td>
                            </tr>

                            <tr id="esic_tr" class="{{ $ctc->grsM_salary > 21000 ? 'd-none' : '' }}">
                                <td>Employee’s ESIC Contribution</td>
                                <td><input type=" text" class="form-control text-center" id="emplyESIC"
                                        style="height: 21px;border: 0px none;" value="{{ $ctc->emplyESIC ?? '' }}"
                                        readonly>
                                </td>
                            </tr>
                            <tr>
                                <th>Net Monthly Salary</th>
                                <td><input type="text" class="form-control text-center font-weight-bold"
                                        id="netMonth" style="height: 21px;border: 0px none;font-weight: bold;"
                                        value="{{ $ctc->netMonth ?? '' }}" readonly>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-center" colspan="2">(B) Annual Components (Tax saving components
                                    which shall
                                    be
                                    reimbursed on production of documents at the end of financial year)
                                </td>
                            </tr>
                            <tr>
                                <td>Leave Travel Allowance</td>
                                <td><input type="text" class="form-control text-center" id="lta"
                                        style="height: 21px;border: 0px none;"
                                        value="
                                        <?php if ($ctc->lta == null || $ctc->lta == '') {
                                            echo '0';
                                        } else {
                                            echo $ctc->lta;
                                        } ?>"
                                        onchange="calculate()">
                                </td>
                            </tr>
                            <tr>
                                <td>Child Education Allowance</td>
                                <td><input type="text" class="form-control text-center" id="childedu"
                                        style="height: 21px;border: 0px none;"
                                        value="
                                        <?php if ($ctc->childedu == null || $ctc->childedu == '') {
                                            echo '0';
                                        } else {
                                            echo $ctc->childedu;
                                        } ?>"
                                        onchange="calculate()">
                                </td>
                            </tr>
                            <tr>
                                <th>Annual Gross Salary</th>
                                <td><input type="text" class="form-control text-center font-weight-bold"
                                        id="anualgrs" style="height: 21px;border: 0px none;font-weight: bold;"
                                        value="{{ $ctc->anualgrs ?? '' }}" readonly>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" class="text-center">(C) Other Annual Components ( Statutory
                                    Components)
                                </td>
                            </tr>
                            <tr>
                                <td>Estimated Gratuity<sup>2</sup></td>
                                <td><input type="text" class="form-control text-center" id="gratuity"
                                        style="height: 21px;border: 0px none;" value="{{ $ctc->gratuity ?? '' }}"
                                        readonly>
                                </td>
                            </tr>
                            <tr>
                                <td>Employer’s PF contribution</td>
                                <td><input type="text" class="form-control text-center" id="emplyerPF"
                                        style="height: 21px;border: 0px none;" value="{{ $ctc->emplyerPF ?? '' }}"
                                        readonly>
                                </td>
                            </tr>
                            <tr id="empesic_tr" class="{{ $ctc->grsM_salary > 21000 ? 'd-none' : '' }}">
                                <td>Employer’s ESIC contribution</td>
                                <td><input type="text" class="form-control text-center" id="emplyerESIC"
                                        style="height: 21px;border: 0px none;" value="{{ $ctc->emplyerESIC ?? '' }}"
                                        readonly>
                                </td>
                            </tr>
                            <tr>
                                <td>Insurance Policy Premium</td>
                                <td><input type="text" class="form-control text-center" id="medical"
                                        style="height: 21px;border: 0px none;" value="{{ $ctc->medical ?? '' }}"
                                        readonly>
                                </td>
                            </tr>

                            @if ($sql->Company == 1)
                                <tr>
                                    <th>Fixed CTC</th>
                                    <td><input type="text" class="form-control text-center font-weight-bold"
                                            id="fixed_ctc" style="height: 21px;border: 0px none;font-weight: bold;"
                                            value="{{ $ctc->fixed_ctc ?? '' }}" readonly>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Performance Pay<sup>3</sup></td>
                                    <td class="text-center" id="variable_pay">
                                        @php
                                            $variable_pay = 0;
                                            if ($ctc->anualgrs != null && $ctc->anualgrs != '') {
                                                $variable_pay = round(($ctc->anualgrs * 5) / 100);
                                                echo $variable_pay;
                                            } else {
                                                echo $variable_pay;
                                            }

                                        @endphp
                                    </td>
                                </tr>
                                <tr>
                                    <th>Total CTC</th>
                                    <td class="text-center" id="final_ctc" style="font-weight: bold;">
                                       {{ $ctc->total_ctc ?? '' }}
                                    </td>
                                </tr>
                                @if (($sql->Vehicle_Policy == 13 && $sql->Grade >= 70) || $ctc->communication_allowance == 'Y')
                                    <tr>
                                        <td class="text-center" colspan="2">(D) Perks
                                        </td>
                                    </tr>
                                    @if ($sql->Vehicle_Policy == 13 && $sql->Grade >= 70)
                                        <tr>
                                            <td>Car Allowance<sup>4</sup></td>
                                            <td class="text-center">
                                                @php
                                                    $car_allowance_data = $policy_conn
                                                        ->table(
                                                            'hrm_master_eligibility_policy_tbl' . $sql->Vehicle_Policy,
                                                        )
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
                                            <td class="text-center">{{$ctc->communication_allowance_amount}}</td>
                                        </tr>
                                    @endif

                                    <tr>
                                        <th>Total Gross CTC</th>
                                        <td class="text-center" id="final_ctc" style="font-weight: bold;">
                                            {{ $ctc->total_gross_ctc ?? '' }}
                                        </td>
                                    </tr>
                                @endif
                            @else
                                <tr>
                                    <th>Total Cost to Company</th>
                                    <td><input type="text" class="form-control text-center" id="total_ctc"
                                            style="height: 21px;border: 0px none;font-weight: bold;"
                                            value="{{ $ctc->total_ctc ?? '' }}" readonly>
                                    </td>
                                </tr>
                            @endif
                        </form>
                    </table>

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
                                        basis if <1 year working). </li>
                                    <li>The calculation shall be based on the pre-defined performance measures at both,
                                        Company & Individual level.
                                    </li>
                                </ol>
                            </li>
                        </ul>
                        <ol type="1" start="4">
                            <li>Subject to submission of vehicle documents.</li>
                        </ol>
                      {{--   <p>For more details refer to the Company’s Performance Pay policy.</p> --}}
                    @endif

                    <br><br>
                    <p style="margin-bottom:2px;">----------------------------<span
                            style="float: right">----------------------------</span></p>
                    <p style="margin-bottom: 0px;"><b>Authorized Signatory</b><span
                            style="float: right">{{ $sql->Title }} {{ $sql->FName }} {{ $sql->MName }}
                            {{ $sql->LName }}</span>
                    </p>
                    <p><b>{{ $sql->SigningAuth }} </b>
                    </p>
                </div>
                <div class="col text-center">
                    <button type="button" class="btn btn-primary btn-sm text-center d-none" id="save_ctc">Save
                        CTC
                    </button>
                    <button type="button" id="edit_ctc" class="btn btn-primary btn-sm d-none"><i
                            class="fa fa-edit"></i>
                        Edit
                    </button>
                </div>
            </div>
        </div>

        <div id="entitlement">
            <div class="page">
                <div class="subpage">
                    <p style="font-size:16px;"><b>Ref:</b> {{ $sql->LtrNo }}
                        <span style="float: right"><b>Date:</b>
                            @if ($sql->LtrDate == null)
                                {{ date('d-m-Y') }}
                            @else
                                {{ date('d-m-Y', strtotime($sql->LtrDate)) }}
                            @endif
                        </span>
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
                    @if (!empty($elg->DAHq) || !empty($elg->DAOut))
                        <p style="margin-bottom: 0;"><b>* Daily Allowances :</b></p>
                        <table class="table" style="width: 100%;font-size:16px;">
                            @if (!empty($elg->DAHq))
                                <tr>
                                    <td>DA@HQ : {{ $elg->DAHq_Rmk }}</td>
                                    <td>{{ $elg->DAHq }}</td>
                                </tr>
                            @endif
                            @if (!empty($elg->DAOut))
                                <tr>
                                    <td>
                                        @if ($sql->Department == 2 || $sql->Department == 3)
                                            Fooding Expense (For outside HQ travel with night halt)
                                        @else
                                            DA Outside HQ
                                        @endif : {{ $elg->DAOut_Rmk }}
                                    </td>
                                    <td>{{ $elg->DAOut }}</td>
                                </tr>
                            @endif
                        </table>
                    @endif
                    <p style="margin-bottom: 0;"><b>* Travel Eligibility:</b> (For Official Purpose Only)</p>
                    @if ($sql->Vehicle_Policy > 0 && $sql->Vehicle_Policy != 'NA')
                        @if (!in_array($sql->Vehicle_Policy, [3, 8, 9, 10]))
                            @if (!empty($elg->TwoWheel) && $elg->TwoWheel != 'NA')
                                <table class="table" style="width: 100%;font-size:16px;">
                                    <tr>
                                        <td style="width:40%;font-size:16px;">2 Wheeler :</td>
                                        @if ($sql->Department == 2 && ($sql->Grade == 61 || $sql->Grade == 62 || $sql->Grade == 63 || $sql->Grade == 64))
                                            <td>Rs. {{ $elg->TwoWheel }} {{ $elg->TwoWheel_Rmk }}</td>
                                        @else
                                            <td>Rs. {{ $elg->TwoWheel }} /Km {{ $elg->TwoWheel_Rmk }}</td>
                                        @endif

                                    </tr>
                                </table>
                            @endif
                        @endif
                        <table class="table" style="width: 100%;font-size:16px;">
                            <tr>
                                <td>Policy Name:</td>
                                <td>{{ get_vehicle_policy_name($sql->Vehicle_Policy) }}</td>
                            </tr>
                            @php
                                $MinKm = '';
                                $MaxKm = '';
                                $YrMaxKm = '';

                                // Fetch policy data
                                $policy_data = $policy_conn
                                    ->table('hrm_master_eligibility_policy_tbl' . $sql->Vehicle_Policy)
                                    ->where('GradeId', $sql->Grade)
                                    ->first();

                                // Ensure policy data exists
                                if ($policy_data) {
                                    // Conditions based on Vehicle_Policy
                                    if (in_array($sql->Vehicle_Policy, [3, 4, 6, 8, 9, 10, 11, 12])) {
                                        if (in_array($sql->Vehicle_Policy, [4, 11])) {
                                            // Handling Fn8 and Fn32 for policies 4 and 11
                                            $MaxKm = !empty($policy_data->Fn8)
                                                ? preg_replace('/[^0-9.]/', '', $policy_data->Fn8)
                                                : (!empty($policy_data->Fn32)
                                                    ? preg_replace('/[^0-9.]/', '', $policy_data->Fn32)
                                                    : '');
                                        } else {
                                            // Handling Fn7 and Fn9 for other specified policies
                                            $MinKm = !empty($policy_data->Fn7)
                                                ? preg_replace('/[^0-9.]/', '', $policy_data->Fn7)
                                                : '';
                                            $MaxKm = !empty($policy_data->Fn9)
                                                ? preg_replace('/[^0-9.]/', '', $policy_data->Fn9)
                                                : '';
                                        }
                                    } elseif (in_array($sql->Vehicle_Policy, [1, 2, 5, 7])) {
                                        // Handling Fn8 and Fn9 for these policies
                                        $MaxKm = !empty($policy_data->Fn9)
                                            ? preg_replace('/[^0-9.]/', '', $policy_data->Fn9)
                                            : (!empty($policy_data->Fn8)
                                                ? preg_replace('/[^0-9.]/', '', $policy_data->Fn8)
                                                : '');
                                    }
                                }

                                // Calculating YrMaxKm based on conditions
                                $YrMaxKm = in_array($sql->Vehicle_Policy, [3, 4, 6, 8, 9, 10, 11])
                                    ? $MaxKm
                                    : floatval($MaxKm) * 12;
                            @endphp

                            <tr>
                                <td colspan="2">Policy Details (<b style="color: blue;">Running KM Plan:
                                        @if (in_array($sql->Vehicle_Policy, ['3', '6', '8', '9', '10', '11']))
                                            Monthly , Max: {{ $YrMaxKm }} KM
                                        @else
                                            Yearly , Max: {{ $YrMaxKm }} KM
                                        @endif
                                    </b>)
                                </td>
                            </tr>
                            @php
                                $checkVpIds = [1, 2, 5, 7];

                            @endphp
                            @if (in_array($sql->Vehicle_Policy, $checkVpIds))
                                @php
                                    $fieldIds = '(1, 3, 4, 2, 5, 15, 23, 16, 24, 17, 25, 18, 26, 8)';
                                    // Fetch policies with matching field_ids
                                    $policies = $policy_conn
                                        ->table('hrm_master_eligibility_mapping_tblfield as m')
                                        ->join('hrm_master_eligibility_field as f', 'm.FieldId', '=', 'f.FieldId')
                                        ->select(
                                            'm.MappId',
                                            'm.PolicyId',
                                            'm.FieldId',
                                            'm.FOrder',
                                            'm.Sts',
                                            'f.FiledName',
                                        )
                                        ->where('m.PolicyId', $sql->Vehicle_Policy)
                                        ->whereIn('m.FieldId', explode(', ', trim($fieldIds, '()')))
                                        ->where('m.Sts', 1)
                                        ->orderBy('m.FOrder')
                                        ->get();
                                @endphp
                            @else
                                @php
                                    // Fetch policies without matching field_ids
                                    $policies = $policy_conn
                                        ->table('hrm_master_eligibility_mapping_tblfield as m')
                                        ->join('hrm_master_eligibility_field as f', 'm.FieldId', '=', 'f.FieldId')
                                        ->select(
                                            'm.MappId',
                                            'm.PolicyId',
                                            'm.FieldId',
                                            'm.FOrder',
                                            'm.Sts',
                                            'f.FiledName',
                                        )
                                        ->where('m.PolicyId', $sql->Vehicle_Policy)
                                        ->where('m.Sts', 1)
                                        ->orderBy('m.FOrder')
                                        ->get();
                                @endphp
                            @endif

                            @foreach ($policies as $field)
                                @php
                                    // Fetch additional data for each field
                                    $sdata = $policy_conn
                                        ->table('hrm_master_eligibility_policy_tbl' . $sql->Vehicle_Policy)
                                        ->where('GradeId', $sql->Grade)
                                        ->first();
                                @endphp
                                @if ($sdata != null && $sdata->{'Fn' . $field->FieldId} != null)
                                    <tr>
                                        <td style="width:40%;font-size:16px;">&nbsp;{{ $field->FiledName }}:</td>
                                        <td style="width:60%;" align="center">
                                            &nbsp;{{ $sdata->{'Fn' . $field->FieldId} ?? '' }}</td>
                                    </tr>
                                @endif
                            @endforeach


                        </table>
                    @else
                        @if (!empty($elg->TwoWheel) || !empty($elg->FourWheel))

                            <table class="table" style="width: 100%;font-size:16px;">
                                @if (!empty($elg->TwoWheel) && $elg->TwoWheel != 'NA')
                                    <tr>
                                        <td>2 Wheeler :</td>
                                        @if (
                                            $sql->Department == 2 ||
                                                ($sql->Department == 3 && ($sql->Grade == 61 || $sql->Grade == 62 || $sql->Grade == 63 || $sql->Grade == 64)))
                                            <td>Rs. {{ $elg->TwoWheel }} {{ $elg->TwoWheel_Rmk }}</td>
                                        @else
                                            <td>Rs. {{ $elg->TwoWheel }} /Km {{ $elg->TwoWheel_Rmk }}</td>
                                        @endif

                                    </tr>
                                @endif
                                @if (!empty($elg->FourWheel) && $elg->FourWheel != 'NA')
                                    <tr>
                                        <td>4 Wheeler :</td>
                                        <td>Rs. {{ $elg->FourWheel }} /Km {{ $elg->FourWheel_Rmk }}</td>
                                    </tr>
                                @endif

                            </table>
                        @endif
                    @endif
                    {{-- @if (!empty($elg->CostOfVehicle) && $elg->CostOfVehicle != 'NA')
                     <table class="table" style="width: 100%;font-size:16px;">
                         <tr>
                             <td style="width: 40%">Vehicle Entitlement value :</td>
                             <td>{{$elg->CostOfVehicle}}</td>
                         </tr>
                     </table>
                 @endif --}}

                    @if ($elg->Train == 'Y' || $elg->Flight == 'Y')
                        <table class="table" style="width: 100%;font-size:16px;">
                            <tr>
                                <td>Mode/Class of Travel Outside HQ :</td>
                                <td>
                                    @if ($elg->Flight == 'Y')
                                        <b>Flight - </b> {{ $elg->Flight_Class }} {{ $elg->Flight_Remark }} <br />
                                    @endif
                                    @if ($elg->Train == 'Y')
                                        <b>Train/Bus - </b> {{ $elg->Train_Class }} {{ $elg->Train_Remark }}</b>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    @endif
                    @if (
                        ($elg->Mobile_Allow == 'Y' && !empty($elg->Mobile)) ||
                            (!empty($elg->Mobile_Remb) && (!empty($elg->MExpense) || !empty($elg->Mobile_RembPost))))
                        <p style="margin-bottom: 0;"><b>* Mobile Eligibility :</b></p>
                        <table class="table" style="width: 100%;font-size:16px;">
                            @if (!empty($elg->Mobile_Remb) && (!empty($elg->MExpense) || !empty($elg->Mobile_RembPost)))
                                <tr>
                                    <td> Mobile expenses Reimbursement :</td>
                                    <td>
                                        @if (!empty($elg->MExpense))
                                            <b>Prepaid:</b> Rs. {{ $elg->MExpense }}
                                            /{{ $elg->MTerm }} {{ $elg->Mobile_Remb_Period_Rmk }} <br />
                                        @endif
                                        @if (!empty($elg->Mobile_RembPost))
                                            <b>Postpaid:</b> Rs. {{ $elg->Mobile_RembPost }}
                                            /{{ $elg->Mobile_RembPost_Period }}
                                            {{ $elg->Mobile_RembPost_Period_Rmk }}
                                        @endif
                                    </td>
                                </tr>
                            @endif
                            @if ($elg->Mobile_Allow == 'Y' && !empty($elg->Mobile))
                                <tr>
                                    <td>Mobile Handset Eligibility :</td>
                                    <td>Rs. {{ $elg->Mobile }}
                                        @if ($elg->GPRS == '1')
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
                            <td style="text-align: center;">Rs. {{ $elg->HealthIns }}</td>

                        </tr>
                        <tr>
                            <td>Group Term Life Insurance (Sum Insured) :</td>
                            <td style="text-align: center;"> Rs.
                                {{ $elg->Term_Insurance }}
                            </td>
                        </tr>
                        @if ($age >= 40)
                            <tr>
                                <td>Executive Health Check-up: <b>(Min. Age > 40Yrs, once in 2yrs)</b></td>
                                <td style="text-align: center;">Rs. {{ $elg->Helth_CheckUp }}</td>
                            </tr>
                        @endif
                    </table>

                    <b>Note: </b>
                    <ul>
                        <li>The above 2 Wheeler & 4 Wheeler travel eligibility is subject to submission of vehicle
                            details
                            belonging in the name of employee only.
                        </li>
                        <li>The vehicle must be under the name of employee only in all cases.
                        </li>
                    </ul>
                    {{-- <p class="text-center"><b><u>LIST OF DOCUMENTS REQUIRED DURING APPOINTMENT</u></b></p>
                <ol>
                    <li style="font-size:14px;">Form 16/Investment Declaration</li>
                    <li style="font-size:14px;">6 colored formal Passport Size Photos with White background</li>
                    <li style="font-size:14px;">Blood Group Test report</li>
                    <li style="font-size:14px;">Copy of educational certificates (10th / 12th / Graduation / Post
                        Graduation, etc.)</li>
                    <li style="font-size:14px;">Previous Employer documents (Service Certificates)</li>
                    <li style="font-size:14px;">Pay slip/ CTC structure of recent previous company</li>
                    <li style="font-size:14px;">Relieving letter from previous company/ Resignation Acceptance
                        Letter
                    </li>
                    <li style="font-size:14px;">Compulsory Documents (Driving license/PAN Card/ Aadhaar Card)</li>
                    <li style="font-size:14px;">Copy of Bank account passbook (Preferred only SBI/BOB) </li>
                </ol> --}}
                    <br><br><br><br>
                    <p style="margin-bottom:2px;">----------------------------<span
                            style="float: right">----------------------------</span></p>
                    <p style="margin-bottom: 0px;"><b>Authorized Signatory</b><span
                            style="float: right">{{ $sql->Title }} {{ $sql->FName }} {{ $sql->MName }}
                            {{ $sql->LName }}</span>
                    </p>
                    <p><b> {{ $sql->SigningAuth }}</b>
                    </p>
                </div>
                {{--            <div class="col text-center">
                            <button type="button" class="btn btn-primary btn-sm text-center d-none" id="save_ent">Save
                                entitlement
                            </button>
                            <button type="button" id="edit_ent" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i>
                                Edit
                            </button>
                        </div> --}}

            </div>
        </div>

        <div class="generate" id="generate">
            <center>
                @if ($sql->OfferLtrGen == '0')
                    <button type="button" class="btn  btn-md text-center btn-success" id="generateLtr"><i
                            class="fa fa-file"></i> Generate Letter
                    </button>
                @else
                    <button type="button" class="btn  btn-md text-center btn-danger" id="regenltr"><i
                            class="fa fa-file"></i> Re-Generate Letter
                    </button>
                @endif
                <a id="print" class="btn btn-info btn-md text-center text-light"
                    href="{{ route('offer_ltr_print') }}?jaid={{ $JAId }}"><i class="fa fa-print"></i>
                    Print
                </a>
            </center>
        </div>
    </div>

    <script src="{{ URL::to('/') }}/assets/js/bootstrap.bundle.min.js"></script>
    <script src="{{ URL::to('/') }}/assets/js/sweetalert2.min.js"></script>
    <script src="{{ URL::to('/') }}/assets/js/toastr.min.js"></script>
    <script>
        function calculate() {

            var basic = $('#basic').val();
            var hra = $('#hra').val();
            var bonus = $('#bonus').val();
            var special_alw = $('#special_alw').val();

            if (isNaN(basic) || basic == '') {
                basic = 0;
            }
            if (isNaN(hra) || hra == '') {
                hra = 0;
            }
            if (isNaN(bonus) || bonus == '') {
                bonus = 0;
            }
            if (isNaN(special_alw) || special_alw == '') {
                special_alw = 0;
            }

            var grsM_salary = Math.round(parseFloat(basic) + parseFloat(hra) + parseFloat(bonus) + parseFloat(special_alw));
            $('#grsM_salary').val(grsM_salary);

            var emplyPF = Math.round(parseFloat(basic * 12 / 100));
            $('#emplyPF').val(emplyPF);
            var emplyESIC = 0;
            if (grsM_salary > 21000) {
                $('#emplyESIC').val(0).attr('disabled', true);
                $("#esic_tr").addClass('d-none');
                $("#empesic_tr").addClass('d-none');
            } else {
                var emplyESIC = Math.round(parseFloat(grsM_salary * 0.75 / 100));
                $('#emplyESIC').val(emplyESIC).attr('disabled', false);
                $("#esic_tr").removeClass('d-none');
                $("#empesic_tr").removeClass('d-none');
            }
            var netMonth = Math.round(parseFloat(grsM_salary - (emplyPF + emplyESIC)));
            $('#netMonth').val(netMonth);
            var lta = $('#lta').val();
            var childedu = $('#childedu').val();
            var anualgrs = Math.round(parseFloat(parseFloat(grsM_salary * 12) + parseFloat(lta) + parseFloat(childedu)));
            $('#anualgrs').val(anualgrs);

            var gratuity = Math.round(parseFloat(basic * 15 / 26));
            $('#gratuity').val(gratuity);

            var emplyerPF = Math.round(parseFloat(emplyPF * 12));
            $('#emplyerPF').val(emplyerPF);

            if (grsM_salary > 21000) {
                $('#medical').val(15000).attr('disabled', true);
                $('#emplyerESIC').val(0).attr('disabled', true);
            } else {

                $('#medical').val(3000).attr('disabled', true);
                $('#emplyerESIC').val(Math.round(parseFloat(anualgrs * 3.25 / 100))).attr('disabled', true);
            }

            var total_ctc = Math.round(parseFloat(anualgrs) + parseFloat(gratuity) + parseFloat(emplyerPF) + parseFloat($(
                    '#emplyerESIC').val()) +
                parseFloat($('#medical').val()));
            $('#total_ctc').val(total_ctc);

            var variable_pay = parseFloat(anualgrs * 5 / 100);
            $("#variable_pay").text(variable_pay);

            var final_ctc = parseFloat(total_ctc) + parseFloat(variable_pay);
            $("#final_ctc").text(final_ctc);
        }

        $(document).on('click', '#generateLtr', function() {
            if (confirm('Are you sure you want to generate letter?')) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                var formData = {
                    jaid: $("#jaid").val(),
                };
                $.ajax({
                    type: 'POST',
                    url: "{{ route('offer_ltr_gen') }}",
                    data: formData,
                    dataType: 'json',
                    success: function(data) {
                        if (data.status == 200) {
                            toastr.success(data.msg);
                            window.location.reload();
                            window.opener.location.reload(true);
                        } else {
                            toastr.error(data.msg);
                        }
                    },
                    error: function(data) {}
                });
            } else {
                window.location.reload();
            }

        });


        $(document).on('click', '#regenltr', function() {
            if (confirm('Are you sure you want to generate letter?')) {
                var RemarkHr = prompt("Please Enter Revision Remark");
                if (RemarkHr != null) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    var formData = {
                        jaid: $("#jaid").val(),
                        RemarkHr: RemarkHr,
                    };
                    $.ajax({
                        type: 'POST',
                        url: "{{ route('offer_ltr_gen') }}",
                        data: formData,
                        dataType: 'json',
                        success: function(data) {
                            if (data.status == 200) {
                                toastr.success(data.msg);
                                window.location.reload();
                                window.opener.location.reload(true);
                            } else {
                                toastr.error(data.msg);
                            }
                        },
                        error: function(data) {}
                    });
                } else {
                    window.location.reload();
                }
            } else {
                window.location.reload();
            }

        });

        $(document).ready(function() {
            //================Disable all input element inside ctcform=========================
            var ctcform = document.getElementById('ctcform');
            var elements = ctcform.elements;
            for (var i = 0, len = elements.length; i < len; ++i) {
                elements[i].disabled = true;
                elements[i].style.backgroundColor = "white";
                elements[i].style.color = "Black";
            }
            //================Disable all input element inside entform=========================
            var entform = document.getElementById('entform');
            var elements = entform.elements;
            for (var i = 0, len = elements.length; i < len; ++i) {
                elements[i].disabled = true;
                elements[i].style.backgroundColor = "white";
                elements[i].style.color = "Black";
            }

            //=========================Enable all input element inside ctc form====================
            $(document).on('click', '#edit_ctc', function() {

                var ctcform = document.getElementById('ctcform');
                var elements = ctcform.elements;
                for (var i = 0, len = elements.length; i < len; ++i) {
                    elements[i].disabled = false;
                }
                $('#edit_ctc').addClass('d-none');
                $('#save_ctc').removeClass('d-none');
            });


            //=========================Enable all input element inside ent form====================
            $(document).on('click', '#edit_ent', function() {
                var entform = document.getElementById('entform');
                var elements = entform.elements;
                for (var i = 0, len = elements.length; i < len; ++i) {
                    elements[i].disabled = false;
                }
                $('#edit_ent').addClass('d-none');
                $('#save_ent').removeClass('d-none');
            });

            //============================Insert/Update CTC=========================
            $('#save_ctc').click(function(e) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                var formData = {
                    jaid: $("#jaid").val(),
                    basic: $('#basic').val(),
                    hra: $('#hra').val(),
                    bonus: $('#bonus').val(),
                    special_alw: $('#special_alw').val(),
                    grsM_salary: $('#grsM_salary').val(),
                    emplyPF: $('#emplyPF').val(),
                    emplyESIC: $('#emplyESIC').val(),
                    netMonth: $('#netMonth').val(),
                    lta: $('#lta').val(),
                    childedu: $('#childedu').val(),
                    anualgrs: $('#anualgrs').val(),
                    gratuity: $('#gratuity').val(),
                    emplyerPF: $('#emplyerPF').val(),
                    emplyerESIC: $('#emplyerESIC').val(),
                    medical: $('#medical').val(),
                    total_ctc: $('#total_ctc').val(),
                };
                $.ajax({
                    type: 'POST',
                    url: "{{ route('insert_ctc') }}",
                    data: formData,
                    dataType: 'json',
                    success: function(data) {
                        if (data.status == 200) {
                            toastr.success(data.msg);
                            $('#save_ctc').addClass("d-none");
                            $('#edit_ctc').removeClass('d-none');
                            var ctcform = document.getElementById('ctcform');
                            var elements = ctcform.elements;
                            for (var i = 0, len = elements.length; i < len; ++i) {
                                elements[i].disabled = true;
                                elements[i].style.backgroundColor = "white";
                                elements[i].style.color = "Black";
                            }
                            window.location.reload();
                        } else {
                            toastr.error(data.msg);
                            window.location.reload();
                        }
                    },

                    error: function(data) {

                    }
                });
            });

            //============================Insert/Update ENT=========================
            $('#save_ent').click(function(e) {

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                var formData = {
                    jaid: $("#jaid").val(),
                    LoadCityA: $('#LoadCityA').val(),
                    LoadCityB: $('#LoadCityB').val(),
                    LoadCityC: $('#LoadCityC').val(),
                    DAOut: $('#DAOut').val(),
                    DAHq: $('#DAHq').val(),
                    TwoWheel: $('#TwoWheel').val(),
                    FourWheel: $('#FourWheel').val(),
                    Train: $('#Train').val(),
                    Train_Class: $('#Train_Class').val(),
                    Flight: $('#Flight').val(),
                    Flight_Class: $('#Flight_Class').val(),
                    Flight_Remark: $('#Flight_Remark').val(),
                    Mobile: $('#Mobile').val(),
                    MExpense: $('#MExpense').val(),
                    MTerm: $('#MTerm').val(),
                    Laptop: $('#Laptop').val(),
                    HealthIns: $('#HealthIns').val(),
                    tline: $('#tline').val(),
                    two_wheel_line: $('#two_wheel_line').val(),
                    four_wheel_line: $('#four_wheel_line').val(),
                    GPRS: $('#GPRS').val(),


                };
                $.ajax({
                    type: 'POST',
                    url: "{{ route('insert_ent') }}",
                    data: formData,
                    dataType: 'json',
                    success: function(data) {
                        if (data.status == 200) {
                            toastr.success(data.msg);
                            $('#save_ent').addClass("d-none");
                            $('#edit_ent').removeClass('d-none');
                            var entform = document.getElementById('entform');
                            var elements = entform.elements;
                            for (var i = 0, len = elements.length; i < len; ++i) {
                                elements[i].disabled = true;
                                elements[i].style.backgroundColor = "white";
                                elements[i].style.color = "Black";
                            }
                        } else {
                            toastr.error(data.msg);
                        }
                    },

                    error: function(data) {

                    }
                });
            });

            $("#basic").on("input", function() {
                var bonus = $("#bonus").val();
                var basic = $("#basic").val();
                var old_bonus = $("#old_bonus").val();
                if (basic > 21000) {

                    $("#bonus").removeAttr('readonly');
                    $("#bonus").val("");
                    $("#bonus").attr('readonly', 'readonly');
                } else {
                    $("#bonus").removeAttr('readonly');
                    $("#bonus").val(old_bonus);
                    $("#bonus").attr('readonly', 'readonly');
                }
            });
        });

        /* function printLtr(url) {
             $("<iframe>") // create a new iframe element
                 .hide() // make it invisible
                 .attr("src", url) // point the iframe to the page you want to print
                 .appendTo("body");
         }*/
    </script>
</body>

</html>
