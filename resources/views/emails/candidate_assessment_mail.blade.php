@component('mail::message')
    <style>
        /* Add your custom styles here */
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
        }

        h1 {
            font-size: 24px;
            color: #333333;
        }

        h2 {
            font-size: 20px;
            color: #007BFF;
            margin-bottom: 10px;
        }

        p {
            font-size: 16px;
            color: #666666;
            margin-bottom: 10px;
        }

        a {
            color: #007BFF;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        .footer {
            font-size: 12px;
            color: #999999;
        }
    </style>


<h1>Dear, {{$details['candidate_name']}}</h1>


<p>Thank you for expressing your interest in  VNR Seeds Pvt Ltd, Raipur.
   We are excited about the possibility of having you join our team and contribute to our projects.</p>
<p>As part of our selection process, we will be conducting Assessment Tests, including the FIRO B assessment.
    These assessments are designed to evaluate your skills, aptitude, and compatibility with our organizational culture.</p>

<h2>Pre-Interview Process:</h2>
@component('mail::buttons', [
    'buttons' => [
        [
            'url' => $details['candidate_link'],
            'slot' => 'Pre Assessment Test',
            'color' => 'green'
        ]
    ]
])
@endcomponent
<p>Your credentials to access the online pre-interview process are as follows:</p>
<br><p>Reference No : <b style="color: blue">{{ $details['reference_no']}}</b></p>
<p>Upon successful completion of the Assessment Tests, you will be shortlisted for a face-to-face interview with our team.</p>
<p>We look forward to receiving your confirmation and seeing you excel in the assessment process.
</p>
<br><br>
<p>Thanks & Regards,<br> Team Talent Acquisition</p>

<p class="footer">
    *Please do not reply to this email. This is an automated message, and responses cannot be received by our system.
</p>

@endcomponent
