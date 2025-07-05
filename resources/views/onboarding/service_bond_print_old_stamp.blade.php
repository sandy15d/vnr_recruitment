<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <style>
        ol,
        li {
            text-align: justify;

        }

        .abc {
            margin-left: 42.55pt;
            text-align: justify;
            text-justify: inter-ideograph;
            text-indent: -28.35pt
        }

        .bde {
            margin-left: 62.55pt;
            text-align: justify;
            text-justify: inter-ideograph;
            text-indent: -38.35pt
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
        ->select('appointing.*', 'offerletterbasic.*', 'candjoining.JoinOnDt', 'jobcandidates.Title', 'jobcandidates.FName', 'jobcandidates.MName', 'jobcandidates.LName', 'jobcandidates.FatherTitle', 'jobcandidates.FatherName', 'jobcandidates.Gender', 'jobcandidates.MaritalStatus', 'jobcandidates.SpouseName', 'jf_contact_det.perm_address', 'jf_contact_det.perm_city', 'jf_contact_det.perm_dist', 'jf_contact_det.perm_state', 'jf_contact_det.perm_pin')
        ->where('jobapply.JAId', $JAId)
        ->first();
    $months_word = ['One' => '1 (One)', 'Two' => '2 (Two)', 'Three' => '3 (Three)', 'Four' => '4 (Four)', 'Five' => '5 (Five)', 'Six' => '6 (Six)', 'Seven' => '7 (Seven)', 'Eight' => '8 (Eight)', 'Nine' => '9 (Nine)', 'Ten' => '10 (Ten)', 'Eleven' => '11 (Eleven)', 'Twelve' => '12 (Twelve)'];
@endphp

<body>

<div style="height:360px;"></div>
<div style="text-align: center; font-weight:bold;">
    <div style="float: left; width: 50%; text-align: left;">Ref:
        {{$sql->BLtrNo}}
    </div>
    <div style="float: right; width: 50%; text-align: right;">Date:
        {{ date('d-m-Y', strtotime($sql->B_Date)) }}

    </div>
</div>

<p style="text-align: center;font-size:16px;"><b>Service Bond</b></p>
<p style="text-align: justify">This agreement is executed at RAIPUR, CHHATTISGARH, on this
    @if ($sql->B_Date != '')
        {{ date('d/m/Y', strtotime($sql->B_Date)) }}
    @else
        {{ date('d/m/Y') }}
    @endif
    (and effective from date {{ date('d/m/Y', strtotime($sql->JoinOnDt)) }})
</p>

<p><b>BETWEEN</b></p>
<p style="text-align: justify"><b>{{ getcompany_name($sql->Company) }}</b> a Company incorporated
    under the provisions of the Companies Act, 1956 and having its registered office situated at
    Corporate Centre, Canal Road Crossing, Ring Road No.1, Raipur, Chhattisgarh- 492006,
    (hereinafter referred to as the <b>“Company”</b>) which expression shall unless repugnant to the
    subject or context shall mean and include its successors and assignees of the <b>FIRST PART</b>.
</p>
<p style="margin-bottom: 0px;"><b>AND</b></p>
{{--@php
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
<p style="text-align: justify">{{ $sql->Title }} {{ $sql->FName }}
    {{ $sql->MName }}
    {{ $sql->LName }}, {{ $x }} permanently residing at {{ $sql->perm_address }},
    {{ $sql->perm_city }},
    Dist-{{ getDistrictName($sql->perm_dist) }} ({{ getStateCode($sql->perm_state) }})
    -
    {{ $sql->perm_pin }} (hereinafter referred to as the <b>“Employee”</b>) of the <b>SECOND
        PART</b>.
    <br><br><b>WHEREAS</b> the Company has offered and the Employee has accepted
    the employment of the Company on the terms and conditions mentioned under the appointment letter
    dated {{ date('d-m-Y', strtotime($sql->A_Date)) }} and the Employee has agreed to abide with
    the
    terms and conditions of his/her employment.
</p>


<p style="text-align: justify"><b>AND WHEREAS</b>, in terms of the said letter of appointment, the
    Employee is required to provide an independent undertaking, as herein appearing to back up the
    obligations and liabilities of the Employee as condition of his/her employment.</p>
<p style="margin-bottom: 0px;">on behalf of <b>{{ getcompany_name($sql->Company) }}</b></p>
<pagebreak sheet-size="Legal-P"/>
<p style="text-align: justify">NOW, THEREFORE, THIS AGREEMENT WITNESSETH AND THE PARTIES HERETO
    AGREE AS UNDER:</p>
<ol type="1">
    <li>The Employee understands and acknowledges that the Company shall be incurring expenses on
        Employee’s skill enhancement directly through training programs or other means which shall
        be beneficial for the Employee and the Company either immediately or on future date as per
        the job requirements.
    </li>
    <li>The Employee by his/her own free will, discretion and judgement, agrees and undertakes to
        serve the Company continuously for a minimum period of
        <b>{{ $months_word[$sql->ServiceBondYears] }} years </b> from
        the date of his/her appointment with the Company (“Service Period”) and shall not leave the
        services of the Company before completion of the Service Period. During the said Service
        Period, the Employee shall not seek employment, or enter employment of any other employer or
        directly or indirectly engage in any business including as that of the Company.
    </li>
    <li>If the Employee leaves the service of the Company before completion of the said Service
        Period, then he/she shall be liable to refund the Company, the cost incurred by the Company
        in getting the vacancy published and all other ancillary expenses incurred by the Company in
        the process of selection of the Employee.
    </li>
    <li>During the Service Period, the Employee shall be under an obligation to work with utmost
        professional competency and dedication to serve the Company.
    </li>
    <li>If the Employee, at any time during his/her employment with the Company is found guilty of
    </li>
    <ol type="a">
        <li>any misconduct including as defined under the employee service regulations, or</li>
        <li>any willful breach or continuous neglect of the terms of this Agreement, or</li>
        <li>Negligence of his/her duties, the Company may, without any notice, determine his/her
            employment with the Company.
        </li>
    </ol>
</ol>

<ol type="1" start="6">
    <li>the Employee shall be deemed to have brought about such a situation that the Company is
        compelled to put an end to his/her employment and he/she shall, therefore, continue to be
        liable for all losses /damages in respect thereof and pay compensation to the Company in
        terms of this Agreement.
    </li>
    <li>If the Employee leaves the employment of the Company before expiry of the Service Period,
        then the Employee unconditionally agrees to pay, on demand, to the Company a sum of 50%
        (Fifty percent) of his annual salary as per the prevailing CTC rate (on date of leaving) as
        pre estimated liquidated damages as compensation for the breach of the terms of this
        Agreement.
    </li>
    <li>The Employee agrees to make good the Company all losses or damages suffered by the Company,
        through any breach of him of the terms and conditions of this Agreement or arising out of
        his employment with the Company.
    </li>
    <li>The company may contact the employee’s subsequent employer for informing about the pending
        recovery of dues if the employee does not pay the service bond or notice period dues as a
        part of the separation policy.
    </li>
    <li>In addition to the payment of the amount specified in the preceding clause, the Employee
        agrees and undertakes to indemnify the Company against all expenses incurred in initiating
        the legal process against the employee for the breach of the terms of this Agreement, if
        required.
    </li>
</ol>

<p>IN WITNESS WHEREOF the parties, to this Agreement have signed on the date, month and year first
    mentioned hereinabove. </p>

<p style="margin-bottom: 0px;">on behalf of <b>{{ getcompany_name($sql->Company) }}</b>
    &emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;<span
            style="text-align: right">_______________________</span></p>
<p>&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;{{ $sql->FName }}
    {{ $sql->MName }}
    {{ $sql->LName }}</p>

<p style="margin-bottom: 0px;">__________________________</p>
<p>Authorized Signatory </p>


<table class="table" style="width: 100%">
    <tr>
        <td style="width: 50%">
            <p><b>Witness 1:</b></p>
        </td>
        <td>
            <p><b>Witness 2:</b></p>
        </td>
    </tr>
    <tr>
        <td>
            <p>Name: ________________________________</p>
        </td>
        <td>
            <p>Name: ________________________________</p>
        </td>
    </tr>
    <tr>
        <td>
            <p>Address: ______________________________</p>
        </td>
        <td>
            <p>Address: ______________________________</p>
        </td>
    </tr>
    <tr>
        <td>
            <p>______________________________________</p>
        </td>
        <td>
            <p>______________________________________</p>
        </td>
    </tr>
    <tr>
        <td>
            <p>______________________________________</p>
        </td>
        <td>
            <p>______________________________________</p>
        </td>
    </tr>
    <tr>
        <td>
            <p>Contact No: ____________________________</p>
        </td>
        <td>
            <p>Contact No: ____________________________</p>
        </td>
    </tr>

    <tr>
        <td>
            <p>Signature: ______________________________</p>
        </td>
        <td>
            <p>Signature: ______________________________</p>
        </td>
    </tr>
</table>

</body>

</html>
