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
Dear {{$details['candidate_name']}},
<p>Thank you for attending the interview with us. We are pleased to inform you that we are moving forward with your application for next stage.</p>
<p>As part of our hiring process and in accordance with the company’s 2 or 4 wheeler vehicle policy, we kindly request you to visit the below link and provide the required information and upload the necessary documents at your earliest convenience. </p>

<strong> <a href="{{$details['link']}}" target="_blank">Vehicle Information</a></strong>

<p>If you have any questions or need further assistance, please do not hesitate to contact the HR department at 0771-4350005</p>

<br><br>
<p>Thanks & Regards,<br> Talent Acquisition Team</p>

<p class="footer">
*Please do not reply to this email. This is an automated message, and responses cannot be received by our system.
</p>
@endcomponent
