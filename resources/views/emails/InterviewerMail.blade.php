@component('mail::message')
<style>
/* Add your custom styles here */
body {
    font-family: Arial, sans-serif;
    background-color: #f5f5f5;
}

h1 {
    font-size: 24px;
    color: #333333;
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

a {
    color: #007BFF;
    text-decoration: none;
}

a:hover {
    text-decoration: underline;
}

.footer {
    font-size: 12px;
    color: #999999;
}
</style>


<h1>Interview Schedule</h1>

<p>Dear {{$details['interviewer_name']}},</p>

<p>
    I am writing to provide you with the details for the upcoming interview/s in which you will be the interviewer.
</p>

<h2>Interview Details</h2>

<p>
<strong>Candidate Name:</strong> {{ $details['candidate_name'] }}<br>
<strong>Date:</strong> {{ date('d-m-Y', strtotime($details['interview_date'])) }}<br>
<strong>Time:</strong> {{ date('h:i A', strtotime($details['interview_time'])) }} <br>
@if($details['interview_mode']==='offline')
    <strong>Venue:</strong> {{ $details['interview_venue'] }}<br>
@else
    <strong>Online Meeting Link:</strong> <a href="{{$details['interview_link']}}" target="_blank">{{ $details['interview_link'] }}</a><br>
@endif
<strong>Contact Person:</strong> {{ $details['contact_person'] }}<br>
<strong>Phone:</strong> 0771-4350005
</p>

{{--<p>
    To access the candidates' details, please log in to the recruitment portal with your credentials and navigate to the interview schedule section.
</p>--}}
<br><br>
<p>Thanks & Regards,<br> Team Talent Acquisition</p>

<p class="footer">
    *Please do not reply to this email. This is an automated message, and responses cannot be received by our system.
</p>

@endcomponent
