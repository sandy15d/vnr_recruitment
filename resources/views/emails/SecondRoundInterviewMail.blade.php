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
<p>We wanted to express our appreciation for your interest in VNR and the time you invested in the initial interview process.
    Your qualifications and experience have stood out to us, and we are excited to invite you to the second round of interviews for the  {{$details['department']}} department.</p>
<h2><strong>Interview Schedule</strong></h2>

<p>
<strong>Date:</strong> {{ date('d-m-Y', strtotime($details['interview_date'])) }}<br>
<strong>Time:</strong> {{ date('h:i A', strtotime($details['interview_time'])) }} <br>
@if($details['interview_mode']==='offline')
<strong>Venue:</strong> {{ $details['interview_venue'] }}<br>
@else
<strong>Online Meeting Link:</strong> <a href="{{$details['interview_link']}}" target="_blank">{{ $details['interview_link'] }}</a><br>
@endif
<strong>Contact Person:</strong> HR<br>
<strong>Phone:</strong> 0771-4350005
</p>
@if($details['interview_mode']==='offline')
<p>We appreciate your interest in joining our team at VNR and look forward to meeting you on {{ date('d-m-Y', strtotime($details['interview_date'])) }}</p>
@else
<p>We appreciate your interest in joining our team at VNR and look forward to meeting you online on {{ date('d-m-Y', strtotime($details['interview_date'])) }}</p>
@endif
<p>Once again, congratulations on being shortlisted, and we hope to have a productive discussion during the interview.</p>
@if($details['interview_mode']==='offline')
@if($details['travelEligibility'] !== null)
    <b><i>Note:</i></b>
    <p>Candidates called for physical interview will be reimbursed the travelling expense by shortest route from your current address as filled in the Application form, for TO & FRO journey within India, subject to maximum limit of {{$details['travelEligibility']}}, on production of tickets. In case of travel by any other means, the reimbursement will be restricted to entitled fare admissible or actual, whichever is lower. The local travel expenses will not be reimbursed.</p>
@endif
@else
<b><i>Important Instructions to attend the Online interview process::</i></b>
<ul>
    <li>Ensure that your computer or device, camera, microphone, and internet connection are in good working order. Test them in advance to avoid technical issues during the interview.</li>
    <li>Find a quiet and well-lit space for the interview. Make sure the background is free from distractions.</li>
    <li>Keep a notepad and pen readily available.</li>
    <li>Dress as you would for an in-person interview, even though you are at home.</li>
</ul>

<br><br>
@endif

<p>Thanks & Regards,<br> Team Talent Acquisition</p>

<p class="footer">
    *Please do not reply to this email. This is an automated message, and responses cannot be received by our system.
</p>
@endcomponent