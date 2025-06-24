@component('mail::message')
Dear Sir/Madam,

<p>Kindly review the offer letter of {{ $details['candidate_name'] }} and suggest for necessary action.</p>

To view, the offer letter, kindly click on the link below:

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

<p>* Please do not reply to this email - This is an automated message and responses cannot be received by our system.</p>


Thanks & Regards,<br>
VNR Recruitment
@endcomponent
