@component('mail::message')
<strong>Hello, {{$details['Candidate']}}</strong>

<p>You have selected this email address to submit your job application. To verify this email address belongs to you, enter the code below on the email verification page:</p>
<h3 style="color: blue;font-size:25px;">{{$details['EmailOTP']}}</h3>
<p>This code will be expire in three hours after this email was sent.</p>

<strong><i>Why you received this email?</i></strong>
<p><i>VNR requires verification whenever an address is selected to submit job application. You cananot submit your job application until you verify it.</i></p>

<p>If you did not make this request, please disregrad this email</p>


Thanks,<br>
VNR Recruitment
@endcomponent
