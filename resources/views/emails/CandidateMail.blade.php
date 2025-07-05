@component('mail::message')
<p>Dear, {{$details['Candidate']}}</p>

<p>{{$details['Message']}}</p>

Thanks and Regards,<br>
{{$details['Recruiter']}}
@endcomponent
