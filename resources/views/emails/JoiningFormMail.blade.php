@component('mail::message')
Dear {{ $details['candidate_name'] }}
<br>
<p>We are delighted to extend our warmest welcome to you as the newest member of the VNR team!</p>
<p>Congratulations on accepting the job offer for the position of {{ $details['designation'] }}</p>
<p>Your credentials to access the online pre-onboarding process are as follows:</p>
Reference No :<strong> {{ $details['reference_no'] }}</strong>
<p>To view and fill the joining form, kindly click on the link below,</p>

@component('mail::buttons', [
    'buttons' => [
        [
            'url' => $details['link'],
            'slot' => 'Click Here',
            'color' => 'green' // This is the default
        ]
    ]
])
@endcomponent to start the pre-onboarding documentation.
<p>Please complete the above pre-onboarding documentation process at your earliest convenience to ensure a smooth and efficient onboarding experience on the scheduled date. Should you encounter any technical difficulties or have any questions, please do not hesitate to contact our HR department at the provided contact number.</p>


Thanks & Regards,<br>
<p style="margin-bottom: 0;">Team Talent Acquisition</p>
<small>*Please do not reply to this email- This is an automated message and responses cannot be received by our system.</small>
@endcomponent
