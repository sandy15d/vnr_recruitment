@component('mail::message')
Dear Sir/Madam

<p>We are intended to recruit {{ $details['candidate_name'] }} who has applied for employment with our Company and we learnt that you are his/her former employer. In order to make an informed hiring decision, we need to know the applicant's work history.</p>

<p>Any information that you give will be held in the strictest confidence and shall not be disclosed with the employee in any matter.</p>

Kindly click on the link below to see the Reference check form.

@component('mail::buttons', [
    'buttons' => [
        [
            'url' => $details['form_link'],
            'slot' => 'View Reference Check Form',
            'color' => 'blue' // This is the default
        ]
    ]
])
@endcomponent

<p>* Please do not reply to this email - This is an automated message and responses cannot be received by our system.</p>


Thanks & Regards,<br>
VNR Recruitment
@endcomponent