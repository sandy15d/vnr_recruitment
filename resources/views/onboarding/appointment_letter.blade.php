<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="{{ URL::to('/') }}/assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Poppins:700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ URL::to('/') }}/assets/css/sweetalert2.min.css"/>
    <link rel="stylesheet" href="{{ URL::to('/') }}/assets/css/toastr.min.css"/>
    <script src="{{ URL::to('/') }}/assets/js/jquery.min.js"></script>
    <script src="https://kit.fontawesome.com/b0b5b1cf9f.js" crossorigin="anonymous"></script>
    <title>Appointment Letter Generation</title>
    <style>
        body {
            width: 230mm;
            height: 100%;
            margin: 0 auto;
            padding: 0;
            font: 12pt "Tahoma";
            background: rgb(204, 204, 204);
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
            padding: 1cm;
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

    $sql = DB::table('jobapply')
        ->leftJoin('appointing', 'appointing.JAId', '=', 'jobapply.JAId')
        ->leftJoin('offerletterbasic', 'offerletterbasic.JAId', '=', 'jobapply.JAId')
        ->leftJoin('jobcandidates', 'jobapply.JCId', '=', 'jobcandidates.JCId')
        ->leftJoin('candjoining', 'jobapply.JAId', '=', 'candjoining.JAId')
        ->leftJoin('jf_contact_det', 'jobcandidates.JCId', '=', 'jf_contact_det.JCId')
        ->leftJoin('jf_family_det', 'jobcandidates.JCId', '=', 'jf_family_det.JCId')
        ->select('appointing.*', 'offerletterbasic.*', 'candjoining.JoinOnDt','jobcandidates.DOB', 'jobcandidates.Title', 'jobcandidates.FName', 'jobcandidates.MName', 'jobcandidates.LName', 'jobcandidates.FatherTitle', 'jobcandidates.FatherName', 'jobcandidates.Gender', 'jobcandidates.MaritalStatus', 'jobcandidates.SpouseName', 'jf_contact_det.perm_address', 'jf_contact_det.perm_city', 'jf_contact_det.perm_dist', 'jf_contact_det.perm_state', 'jf_contact_det.perm_pin')
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
    $months_word = ['One' => '1 (One)', 'Two' => '2 (Two)', 'Three' => '3 (Three)', 'Four' => '4 (Four)', 'Five' => '5 (Five)', 'Six' => '6 (Six)', 'Seven' => '7 (Seven)', 'Eight' => '8 (Eight)', 'Nine' => '9 (Nine)', 'Ten' => '10 (Ten)', 'Eleven' => '11 (Eleven)', 'Twelve' => '12 (Twelve)'];
    $policy_conn = DB::connection('mysql3');
@endphp

<body>
<div class="container">
    <input type="hidden" name="jaid" id="jaid" value="{{ $JAId }}">
    <input type="hidden" name="ltrno" id="ltrno"
           value="{{ getcompany_code($sql->Company) . '_AL/' . getDepartmentCode($sql->Department) . '/' . date('M-Y', strtotime($sql->JoinOnDt)) . '/' . $JAId }}">

    <div id="appointment_ltr">
        <div class="page">
            <div class="subpage ml-3">

                <p style="margin-bottom:100px;"></p>
                <p class="text-center "><b><u> APPOINTMENT LETTER</u></b></p>
                <p style="font-size:16px;"><b>Ref:
                        {{ getcompany_code($sql->Company) . '_AL/' . getDepartmentCode($sql->Department) . '/' . date('M-Y', strtotime($sql->JoinOnDt)) . '/' . $JAId }}</b>
                    <span style="float:right"><b>Date:{{ date('d-m-Y', strtotime($sql->A_Date)) }}</span></b>
                </p>

                <br>
                <p><b>To,</b></p>
                <b>
                    <p style="margin-bottom: 0px;"> {{ $sql->Title }} {{ $sql->FName }} {{ $sql->MName }}
                        {{ $sql->LName }}</p>
                </b>
                <b>
                    <p style="margin-bottom: 0px;">{{ $sql->perm_address }}, {{ $sql->perm_city }},
                    </p>
                </b>
                <b>
                    <p style="margin-bottom: 0px;">
                        Dist-{{ getDistrictName($sql->perm_dist) }},{{ getStateName($sql->perm_state) }},
                        {{ $sql->perm_pin }}
                    </p>
                </b>
                <br>


                <p style="text-align:justify">We take pleasure in appointing you as
                    <b>{{ getCandidateFullDesignation($sql->JAId) }}</b> at
                    <b>Grade-{{ getGradeValue($sql->Grade) }}</b> in  <b>{{ getDepartment($sql->Department) }}</b> Department of {{ getcompany_name($sql->Company) }}
                    (<strong>"Company"</strong>). The said appointment shall be governed by the terms and
                    conditions, specified
                    herein below, apart from other service rules and conditions that are applicable or may become
                    applicable from time to time, at the sole discretion of the Company.
                </p>

                <ol>
                    <li>
                        <strong>Commencement of Service:</strong> The date of your appointment will be
                        {{ date('d-m-Y', strtotime($sql->JoinOnDt)) }} ("<strong>Appointment Date</strong>").
                    </li>

                    <li>

                        @if ($sql->ServiceCondition == 'Training' && $sql->OrientationPeriod != null && $sql->Stipend != null)
                            <p><strong>Place of Posting:</strong> You shall report at
                                <strong>{{ optional($sql)->F_City ? $sql->F_City . ',' : '' }}
                                    {{ getHq($sql->F_LocationHq) }} ({{ getHqStateCode($sql->F_StateHq) }})</strong>,
                                for an orientation program of {{ $sql->OrientationPeriod }} months.
                            </p>
                            <p>After completion of the orientation period, you shall be on a Training period of 12
                                months and during the period of training, you may be allocated various assignments
                                at different locations. </p>
                            <p>However, you may be required to (i) relocate to other locations in India; and/or (ii)
                                undertake such travel in India, (iii) overseas locations, from time to time, as may
                                be necessary in the interests of the Company's business.</p>
                        @elseif($sql->TempS == 1 && $sql->FixedS == 1)
                            <p style="margin-bottom: 0px;"><strong>Place of Posting:</strong> For minimum
                                {{ $sql->TempM }} months, your
                                temporary headquarter will be
                                <strong>{{ getHq($sql->T_LocationHq) }} ({{ getHqStateCode($sql->T_StateHq) }})</strong>
                                @if ($sql->T_StateHq1 > 0)
                                    or
                                    <strong>{{ getHq($sql->T_LocationHq1) }}
                                        ({{ getHqStateCode($sql->T_StateHq1) }}) </strong>
                                @endif
                                which may be increased if needed after {{ $sql->TempM }} months and
                                then
                                your principal place of employment shall be at
                                <strong>{{ optional($sql)->F_City ? $sql->F_City . ',' : '' }}
                                    {{ getHq($sql->F_LocationHq) }} ({{ getHqStateCode($sql->F_StateHq) }})</strong>.
                                However, you may be
                                required to (i) relocate to other locations in India; and/or (ii) undertake such
                                travel in India, (iii) overseas locations, from time to time, as may be necessary in
                                the interests of the Company's business.
                            </p>
                        @elseif($sql->TempS == 1 && $sql->FixedS == 0)
                            <p style="margin-bottom: 0px;">For minimum {{ $months_word[$sql->TempM] }} months,
                                your temporary headquarter
                                will be
                                <strong>{{ getHq($sql->T_LocationHq) }} ({{ getHqStateCode($sql->T_StateHq) }})</strong>
                                @if ($sql->T_StateHq1 > 0)
                                    or
                                    <strong>{{ getHq($sql->T_LocationHq1) }}
                                        ({{ getHqStateCode($sql->T_StateHq1) }}) </strong>
                                @endif
                                which may be increased if needed after {{ $months_word[$sql->TempM] }} months.
                                However, you may be required to (i) relocate to other locations in India; and/or
                                (ii) undertake such travel in India, (iii) overseas locations, from time to time, as
                                may be necessary in the interests of the Company's business.
                            </p>
                        @else
                            <p style="margin-bottom: 0px;"><strong>Place of Posting:</strong> Your principal place
                                of employment shall be at
                                <strong>{{ optional($sql)->F_City ? $sql->F_City . ',' : '' }}
                                    {{ getHq($sql->F_LocationHq) }} ({{ getHqStateCode($sql->F_StateHq) }})</strong>.
                                However, you may be required
                                to (i) relocate to other locations in India; and/or (ii) undertake such travel in
                                India, (iii) or overseas, from time to time, as may be necessary in the interests of
                                the Company's business.
                            </p>
                        @endif
                    </li>
                    <li>
                        <strong>Relevant documents:</strong> Your appointment and continuance in service with the
                        Company is subject to submission of documents as mentioned in the offer letter, by your
                        Appointment Date.
                    </li>

                    <li>
                        <strong>Reporting / Duties and responsibilities:</strong>
                        <ol type="a">
                            <li>Currently, you will report to
                                @if ($sql->repchk == 'RepWithoutEmp')
                                    <strong>{{ getDesignation($sql->reporting_only_desig) }}</strong>
                                @else
                                    <strong>{{ getFullName($sql->A_ReportingManager) }}
                                        ,{{ getEmployeeDesignation($sql->A_ReportingManager) }}</strong>
                                @endif

                                (<strong>“Manager”</strong>) or such
                                other person as may be suggested by the Company, from time to time.
                            </li>
                            <li>You will perform all the duties & responsibilities assigned to you from time to
                                time based on business requirement of the Company or any other incidental work, if
                                required by your Manger or other superiors at the Company. It may be subject to
                                changes at the sole discretion of the Company.
                            </li>
                        </ol>
                    </li>
                    @if ($sql->ServiceBond == 'Yes')
                        <li>
                            <strong>Service Bond:</strong> You shall sign and submit a service bond for continuation
                            of
                            your service at your own free will, discretion and judgement and agree to serve the
                            Company
                            continuously for a minimum period of <b>{{ $months_word[$sql->ServiceBondYears] }} </b>
                            years from
                            the Appointment Date (<strong>“Bond
                                Period”</strong>) and shall not leave the services of the Company prior to the
                            expiry of the Bond
                            Period.
                        </li>
                    @endif
                    @if ($sql->ServiceCondition == 'Probation' || $sql->ServiceCondition == 'Training')
                        <li>
                            <strong>{{$sql->ServiceCondition == 'Probation' ? 'Probation':'Training Period' }} : </strong>
                            <ol type="a">
                                <li>
                                    You will be on {{ $sql->ServiceCondition }} for a period of
                                    {{ $sql->ServiceCondition == 'Probation' ? '6 (Six)' : '12 (Twelve)' }}
                                    months
                                    from the Appointment Date (<strong>“Probation Date”</strong>) which maybe either
                                    extended or may
                                    be
                                    dispensed, at the sole discretion of the Company. Unless confirmed in writing,
                                    you
                                    will be deemed as a
                                    {{ $sql->ServiceCondition == 'Probation' ? 'probationer' : 'trainee' }} after
                                    expiry of the initial or
                                    extended
                                    {{ $sql->ServiceCondition }} Period.
                                </li>
                                <li>
                                    Upon satisfactory completion of the {{ $sql->ServiceCondition }} Period and a
                                    subsequent
                                    performance
                                    evaluation, your position may be confirmed or extended at the sole discretion of
                                    the
                                    Company.
                                </li>
                                <li>
                                    Based on your performance during the {{ $sql->ServiceCondition }} Period, the
                                    Company reserves the
                                    right to reduce/dispense with or extend the {{ $sql->ServiceCondition }}
                                    Period at its sole
                                    discretion
                                    or terminate your services with immediate effect, without giving any notice or
                                    assigning any reasons thereof.
                                </li>
                            </ol>
                        </li>
                    @endif
                    <li>
                        <strong>Remuneration:</strong>
                        <ol type="a">
                            @if ($sql->ServiceCondition == 'Training' && $sql->OrientationPeriod != null && $sql->Stipend != null)
                                <li>During the period of Orientation, you shall receive a consolidated stipend of
                                    Rs. {{ $sql->Stipend }}/- per month.
                                    After completion of your Orientation period, your annual cost to company (CTC)
                                    and entitlements details shall be as mentioned in the Annexures A and B attached
                                    hereto and effective from the Appointment Date, until further revisions are made
                                    by Company at its sole discretion.
                                </li>
                            @else
                                <li>Your annual cost to company (CTC) and other benefits will be as set out in
                                    Annexure A and Annexure B hereto and effective from the Appointment Date, until
                                    further revisions are made by Company at its sole discretion.
                                </li>
                            @endif
                            <li>You will be always governed by the policies, procedures and rules of the Company
                                related to the salary, allowances, benefits, and perquisites which are specified in
                                the Annexure A of this appointment letter. Further, the Company may modify or change
                                such allowances, benefits, and perquisites from time to time in accordance with its
                                policies.
                            </li>
                        </ol>
                    </li>

                </ol>


            </div>
        </div>

        <div class="page">
            <div class="subpage ml-3">
                <ul type="none">
                    <li>
                        <ol type="a" start="3">
                            <li>You shall be entitled for statutory benefits like Provident Fund, ESIC, Bonus and
                                Gratuity as per the relevant statutory acts and the relevant rules framed there
                                under.
                            </li>
                            <li>The payment of salary and benefits payable under this appointment shall be
                                subject to deduction of income tax as per the prevailing income tax rates and other
                                statutory deductions, as may be required in accordance with the applicable
                                legislations, in force from time to time.
                            </li>
                            <li>The Company views the compensation offered to you as an extremely confidential
                                matter and any leakage of the same shall be viewed as a serious breach of the
                                confidence and conditions of employment at your level.
                            </li>
                        </ol>
                    </li>
                </ul>
                <ol start="8">
                    <li>
                        <strong>Transfer & Deputation: </strong>As per the business requirements and at the sole
                        discretion of Company, you may be transferred or sent on deputation to any other section,
                        department or location in the same establishment or you may be transferred to any other
                        establishment (existing or which may be set up in future) under the control of the Company,
                        anywhere in the country with or without any additional benefits.
                    </li>
                    <li>
                        <strong>Termination of services: </strong>
                        <ol type="a">
                            <li>In case of discontinuation of service, for more than 10 days, this contract may be
                                terminated by the Company with immediate
                                effect and without any compensation thereof.
                            </li>

                            @php
                                if ($sql->Department == 15 || $sql->Department == 17 || $sql->Department==2 || $sql->Department==3 || $sql->Department ==14 || $sql->Department == 10) {
                                    $noticePeriod = '3 (three)';
                                } else {
                                    $noticePeriod = '1 (one)';
                                }
                            @endphp

                            <li>Upon your confirmation, your employment with the Company can be terminated by either
                                party giving to other a notice period of

                                {{ $noticePeriod }}
                                months’ notice in
                                writing or {{ $noticePeriod }} months’ wages in lieu
                                of
                                such notice.
                                However, in the event of your resignation, the Company at its sole discretion
                                will
                                have an option to accept the same and relieve you prior to completion of the
                                stipulated notice period of {{ $noticePeriod }} months’, without any pay in lieu
                                of
                                the notice period.
                            </li>
                            <li>Your performance will be under review and assessment by the Company from time to
                                time, and if Company is not satisfied with your ability or performance, the Company
                                has the right to terminate your employment, with or without notice or wages in lieu
                                thereof and without assigning of any reason.
                            </li>
                            <li>However, in the event of any gross misconduct or commission of a serious breach
                                by you, either during the period of probation or after confirmation, the company
                                reserves its rights to terminate your employment without giving any notice or wages
                                in lieu thereof and/or assigning of any reasons.
                            </li>
                        </ol>
                    </li>
                    <li>
                        <strong>Retirement: </strong>You will retire from the services of the Company on attaining
                        the age of 60 (Sixty) years, unless and otherwise extended by the company in writing.
                        Date of birth entered in your service record and as verified by you will be considered for
                        the purpose of determining your date of retirement.
                    </li>
                    <li>
                        <strong>Medical Fitness:</strong> This appointment and its continuance are subject to your
                        being sound and remaining medically (physically and mentally) fit. In case of any
                        fitness/health related issues you hold or develop at a later stage that affects your
                        performance as expected by the Company, the Company has the right to get you medically
                        examined by any certified medical practitioner during the period of your service in case you
                        don’t regain your fitness within the said period of 30 days, your services shall be liable
                        to be terminated at the sole discretion of the Company.
                    </li>
                    <li>
                        <strong>General Conditions: </strong>
                        <ol type="a">
                            <li>You will intimate in writing to the Company any change of address within a week
                                from such change, failing which any communication sent to you on your last recorded
                                address shall be deemed to have served on you.
                            </li>
                            <li>You may be selected and sponsored by the Company to visit other countries to
                                undergo specialized technical training/Attend Conference or Seminar/Business tour or
                                Study tour for meeting the business requirements of the Company and in such case,
                                you shall be governed by the Overseas Travel Policy of the Company.
                            </li>
                            <li>This appointment is offered on the basis of the information’s furnished by you.
                                If at any time it is found the employment has been obtained by furnishing
                                /misleading insufficient information or withheld material information, the Company
                                will have the right to terminate your services at any time without giving any notice
                                or any compensation in lieu thereof.
                            </li>
                        </ol>
                    </li>
                </ol>

                <br><br>


            </div>
        </div>

        <div class="page">
            <div class="subpage ml-3">
                <ul type="none">
                    <li>
                        <ol type="a" start="4">
                            <li>On cessation of your employment for any reason whatsoever, you will hand over
                                every property or article or document entrusted to you by the company during your
                                period of employment.
                            </li>
                            <li>Your appointment is valid subject to your acceptance of the terms and conditions of
                                this letter of appointment and submission of signed duplicate copy of this letter
                                and Service Agreement
                                @if ($sql->ServiceBond == 'Yes')
                                    ,Service Bond
                                @endif
                                attached as Annexure C
                                @if ($sql->ServiceBond == 'Yes')
                                    and Annexure D respectively
                                @endif
                                by you to the Company within same day from the date of issue of this
                                letter.<br>
                                In case of failure to submit the signed copy of the above-mentioned documents to the
                                Company, your services as per this Appointment Letter shall come to an end
                                automatically on the 7th (Seventh) Day from the date of issue of this letter and the
                                Company shall not be liable to pay any compensation to you for such period.
                            </li>
                        </ol>
                    </li>
                </ul>
                <ol start="13">
                    <li>This agreement shall be governed by laws of India. All matters related to this agreement
                        shall be subject to the exclusive jurisdiction of the courts at Raipur, Chhattisgarh.
                    </li>
                </ol>
                <br>
                <p>We wish you a long and successful association with the Company.</p>
                <br><br>
                <p><strong>For, {{ getcompany_name($sql->Company) }}</strong></p>
                <br>
                <br>
                ------------------------------------
                <p style="margin: 0px;"><strong>Authorized Signatory</strong></p>
                <p><strong>{{ $sql->SigningAuth }}</strong></p>

                --------------------------------------------------------------------------------------------------------------------
            {{--    @php
                    if ($sql->MaritalStatus != '' || $sql->MaritalStatus != null) {
                        if ($sql->MaritalStatus == 'Single') {
                            if ($sql->Gender == 'M') {
                                $x = 'S/o. ' . $sql->FatherTitle . ' ' . $sql->FatherName;
                            } else {
                                $x = 'D/o. ' . $sql->FatherTitle . ' ' . $sql->FatherName;
                            }
                        } else {
                            if ($sql->Gender == 'M') {
                                $x = 'S/o. ' . $sql->FatherTitle . ' ' . $sql->FatherName;
                            } else {
                                $x = 'W/o. ' . $sql->FatherTitle . ' ' . $sql->SpouseName;
                            }
                        }
                    } else {
                        if ($sql->Gender == 'M') {
                            $x = 'S/o. ' . $sql->FatherTitle . ' ' . $sql->FatherName;
                        } else {
                            $x = 'D/o. ' . $sql->FatherTitle . ' ' . $sql->FatherName;
                        }
                    }
                @endphp--}}
                @php

                    if ($sql->Gender == 'M') {
                        $x = 'S/o. ' . $sql->FatherTitle . ' ' . $sql->FatherName;
                    } else {
                        $x = 'D/o. ' . $sql->FatherTitle . ' ' . $sql->FatherName;
                    }

                @endphp
                <p style="text-align: justify">I, <strong> {{ $sql->FName }}
                        {{ $sql->MName }}
                        {{ $sql->LName }}</strong>, <strong>{{ $x }}</strong> have read and
                    understood the terms and conditions of this appointment letter and hereby signify my acceptance
                    of the same during my entire tenure of service.</p>
                <br>
                <div class="d-flex justify-content-between" style="height: 16px;">

                    <p><strong>------</strong></p>


                    <p><strong>-----------</strong></p>


                    <p><strong>---------------</strong></p>

                </div>
                <div class="d-flex justify-content-between">

                    <p><strong>Location</strong></p>


                    <p><strong>Date</strong></p>


                    <p><strong>Employee Signature</strong></p>

                </div>
                <br><br>
                <strong>
                    <p>Enclosed:</p>
                </strong>
                <ol type="1">
                    <li>Annexure A- CTC</li>
                    <li>Annexure B- Entitlements</li>
                    <li>Annexure C- Service Agreement</li>
                    @if ($sql->ServiceBond == 'Yes')
                        <li>Annexure D- Service Bond</li>
                    @endif
                </ol>
            </div>
        </div>

        <div id="ctc">
            <div class="page">
                <div class="subpage">
                    <br>
                    <p class="text-center"><b>ANNEXURE A – COMPENSATION STRUCTURE</b></p>
                    <br>
                    <center>
                        <table class="table" style="width: 100%">
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
                            @if ($ctc->hra != null || $ctc->hra != '')
                                <tr>
                                    <td>HRA</td>
                                    <td class="text-center">{{ $ctc->hra ?? '' }}</td>
                                </tr>
                            @endif
                            @if ($ctc->bonus != null || $ctc->bonus != '')
                                <tr>
                                    <td>Bonus<sup>1</sup></td>
                                    <td class="text-center">{{ $ctc->bonus ?? '' }}</td>
                                </tr>
                            @endif
                            @if ($ctc->special_alw != null || $ctc->special_alw != '')
                                <tr>
                                    <td>Special Allowance</td>
                                    <td class="text-center">{{ $ctc->special_alw ?? '' }}</td>
                                </tr>
                            @endif
                            <tr>
                                <th>Gross Monthly Salary</th>
                                <td class="text-center">{{ $ctc->grsM_salary ?? '' }}</td>
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
                                <td class="text-center">{{ $ctc->netMonth ?? '' }} </td>
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
                                <td class="text-center">{{ $ctc->anualgrs ?? '' }}</td>
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
                            <tr class="{{ $ctc->medical <= 0 ? 'd-none' : '' }}">
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
                                    <td class="text-center">{{ $ctc->fixed_ctc ?? '' }} </td>
                                </tr>

                                <tr>
                                    <td>Performance Pay<sup>3</sup></td>
                                    <td id="variable_pay" class="text-center">
                                        {{ $ctc->performance_pay ?? '' }}
                                    </td>
                                </tr>

                                <tr>
                                    <th>Total CTC</th>
                                    <td class="text-center" id="total_ctc">
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
                                                    $car_allowance = $car_allowance_data->Fn36 *12;
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
                                    <li>This amount is indicative of the target variable pay, actual pay-out will
                                        vary
                                        based on the performance of Company and Individual.
                                    </li>
                                    <li>It is linked with Company performance (as per fiscal year) and Individual
                                        Performance (as per appraisal period for minimum 6 months working, pro-rata
                                        basis if <1 year working).
                                    </li>
                                    <li>The calculation shall be based on the pre-defined performance measures at
                                        both,
                                        Company & Individual level.
                                    </li>
                                </ol>
                            </li>
                        </ul>
                        <ol type="1" start="4">
                            <li>Subject to submission of vehicle documents.</li>
                        </ol>
                       {{--  <p>For more details refer to the Company’s Performance Pay policy.</p> --}}
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
                    <br>
                    <p class="text-center"><b>ANNEXURE B – ENTITLEMENTS</b></p>
                    <br>
                    <p style="margin-bottom: 0;"><b>* Lodging Entitlements :</b> (Actual with upper limits per
                        day)</p>
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
                                    <td>@if ($sql->Department == 2 || $sql->Department ==3)
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
                                        @if(($sql->Department == 2 || $sql->Department == 3) && ($sql->Grade == 61 ||$sql->Grade == 62 ||$sql->Grade == 63||$sql->Grade == 64 ))
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
                                        @if(($sql->Department == 2 || $sql->Department == 3) && ($sql->Grade == 61 ||$sql->Grade == 62 ||$sql->Grade == 63||$sql->Grade == 64 ))
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
                   {{-- @if(!empty($elg->CostOfVehicle) && $elg->CostOfVehicle != 'NA')
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
                        @if($ctc->grsM_salary > 21000)
                            <tr>
                                <td>Health Insurance (Sum Insured):</td>
                                <td style="text-align: center;">Rs. {{$elg->HealthIns}}</td>

                            </tr>
                        @endif
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
                    <br><br>
                    <b>Note: </b>
                    <ul>
                        <li>The above 2 Wheeler & 4 Wheeler travel eligibility is subject to submission of vehicle details belonging in the name of employee only.</li>
                        <li>The vehicle must be under the name of employee only in all cases.</li>
                    </ul>
                    <br><br>
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
    </div>


    <div class="generate" id="generate">
        <center>
            @if ($sql->AppLtrGen == 'No' || $sql->AppLtrGen == '' || $sql->AppLtrGen == null)
                <button type="button" class="btn  btn-md text-center btn-success" id="generateLtr"><i
                        class="fa fa-file"></i> Generate Letter
                </button>
            @endif

            <button id="print" class="btn btn-info btn-md text-center text-light"
                    onclick="printLtr('{{ route('appointment_ltr_print') }}?jaid={{ $JAId }}');"><i
                    class="fa fa-print"></i> Print
            </button>
        </center>
    </div>
</div>

<script src="{{ URL::to('/') }}/assets/js/bootstrap.bundle.min.js"></script>
<script src="{{ URL::to('/') }}/assets/js/sweetalert2.min.js"></script>
<script src="{{ URL::to('/') }}/assets/js/toastr.min.js"></script>
<script>
    $(document).on('click', '#generateLtr', function () {
        var JAId = $("#jaid").val();
        var ltrno = $("#ltrno").val();
        var url = '<?= route('appointment_letter_generate') ?>';
        swal.fire({
            title: 'Are you sure?',
            html: 'Generate Appointment Letter',
            showCancelButton: true,
            showCloseButton: true,
            cancelButtonText: 'Cancel',
            confirmButtonText: 'Yes',
            cancelButtonColor: '#d33',
            confirmButtonColor: '#556ee6',
            width: 400,
            allowOutsideClick: false

        }).then(function (result) {
            if (result.value) {
                $.post(url, {
                    "_token": "{{ csrf_token() }}",
                    JAId: JAId,
                    ltrno: ltrno
                }, function (data) {
                    if (data.status == 200) {

                        toastr.success(data.msg);
                        setTimeout(function () {
                            window.location.reload();
                        }, 1000);
                    } else {
                        toastr.error(data.msg);
                    }
                }, 'json');
            }
        });


    });


    function printLtr(url) {
        $("<iframe>") // create a new iframe element
            .hide() // make it invisible
            .attr("src", url) // point the iframe to the page you want to print
            .appendTo("body");
    }
</script>
</body>

</html>
