@component('mail::message')
<strong>Dear, {{$details['Candidate']}}</strong>

<p>Thank you for your interest in working with us.</p>
<p>We acknowledge receipt of your application.</p>
<p>Our team is in the process of reviewing your resume. We will reach out to you, in case it matches the requirements for any current job openings that we have. if it doesn't, we will add your resume to our resume bank for future reference.</p>
<p>We appreciate your interest in our company and wish you the very best</p>
<p>Your Application Reference Number is: <strong>{{$details['ReferenceNo']}}</strong></p>
<h3 style="color: blue;font-size:25px;"></h3>



Warm Regards,<br>
Talent Acquisition Team
@endcomponent
