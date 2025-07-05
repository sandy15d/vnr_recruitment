@component('mail::message')
<strong>Congratulations,</strong> {{ $details['candidate_name'] }}, Reference No :<strong> {{ $details['reference_no'] }}</strong>, with reference to your interview with us, we are glad to inform you that you have been selected for the post of {{ $details['job_title'] }}.

{{ $details['company'] }} is pleased to offer you the position of <strong>{{ $details['job_title'] }}</strong> at  Grade-{{ $details['grade'] }}.

To view, the job offers, kindly click on the link below:

@component('mail::buttons', [
    'buttons' => [
        [
            'url' => $details['offer_link'],
            'slot' => 'View Offer Letter',
            'color' => 'green' // This is the default
        ]
    ]
])
@endcomponent

Please confirm that the job offer, term and conditions are acceptable to you by accepting the letter and acknowledging the same within 7 days of receipt of this mail.

<small><i>Note: Please note this offer letter is valid for 7 days only, if no response is received from you on or before within 7 days form the receipt of this mail, It is assumed that you are no longer interested for the offered Job and the Job offer letter link shall also be deactivated.</i></small>

<p>* Please do not reply to this email - This is an automated message and responses cannot be received by our system.</p>


Thanks,<br>
VNR Recruitment
@endcomponent
