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
Dear {{$details['name']}},
<p>We are pleased to inform you that your application for internship at VNR has been selected for further consideration.</p>
<p>To proceed further, we request you to complete an online psychometric test. This assessment is an essential part of our pre-interview process and will help us evaluate your suitability for the role.</p>
<h2><u>Instructions for the Test:</u></h2>
<ul>
    <li> <strong>Click here to access the test:</strong> <a href="{{$details['firob']}}" target="_blank">FIRO - B</a><br></li>
    <li>Use the below credentials to login.</li>
    <li>Reference No : <b style="color: blue">{{ $details['reference_no']}}</b></li>
    <li>Carefully read the instructions provided at the start of the test. They are available in both Hindi and English.</li>
</ul>


<p>If you encounter any issues or have questions regarding the test, please do not hesitate to contact the HR department at 0771-4350005, Extn: 230, 231, 225.</p>

<p>We look forward to reviewing your results and wish you the best of luck with the assessment. Your timely completion of the test will help us proceed smoothly with the selection process.</p>




<br><br>
<p>Thanks & Regards,<br> Talent Acquisition Team</p>

<p class="footer">
*Please do not reply to this email. This is an automated message, and responses cannot be received by our system.
</p>
@endcomponent
