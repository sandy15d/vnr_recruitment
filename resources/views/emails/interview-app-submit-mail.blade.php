@component('mail::message')
<style>
/* Add your custom styles here */
body {
font-family: Arial, sans-serif;
background-color: #f5f5f5;
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
.footer {
font-size: 12px;
color: #999999;
}
</style>
Dear {{$details['recruiter_name']}},

<p>This is to inform you that {{$details['candidate_name']}}, {{$details['reference_no']}} has successfully submitted their online interview application form.
    Please review the application and take the necessary actions, such as scheduling the interview or verifying the provided information.</p>


<p>Thank you for your prompt attention to this matter.</p>




<br><br>
<p>Thanks & Regards,<br> Team Talent Acquisition</p>

<p class="footer">
*Please do not reply to this email. This is an automated message, and responses cannot be received by our system.
</p>
@endcomponent
