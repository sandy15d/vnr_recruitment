@component('mail::message')
Dear Sir/Madam,

<p>We have received and reviewed the applications submitted for the {{ $details['Title'] }} position and are forwarding the most suitable candidates for your review</p>

<p>Please assess the resumes and take the necessary actions, such as further shortlisting, rejecting candidates, or scheduling interviews.</p>
<p>Thank you for your prompt attention to this matter.</p>

To view the candidates profiles, please login to your account and see the details under the Technical Screening page.




<br><br>
<p>Thanks & Regards,<br> Team Talent Acquisition</p>

<p class="footer">
    *Please do not reply to this email. This is an automated message, and responses cannot be received by our system.
</p>
@endcomponent
