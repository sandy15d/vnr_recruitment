@component('mail::message')
<strong>Congratulations,</strong> {{ $details['name']}},Reference No : <b style="color: blue">{{ $details['reference_no']}}</b>, Your application  has been shortlisted.
<p>Kindly fill the job application form and FIRO B Test online by clicking the bellow link, this will save your time and efforts on the interview date.</p>
@component('mail::buttons', [
    'buttons' => [
        [
            'url' => $details['interview_form'],
            'slot' => 'Interview Application Form',
            'color' => 'green'
        ],[
            'url' => $details['firob'],
            'slot' => 'FIRO - B',
            'color' => 'blue'
        ]
    ]
])
@endcomponent

<small>*Please do not reply to this email- This is an automated message and responses cannot be received by our system.</small>

Thanks,<br>
VNR Recruitment
@endcomponent 