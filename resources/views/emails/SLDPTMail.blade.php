@component('mail::message')
    Hello,<br> <strong>{{$details['Recruiter_Name']}}</strong>

<p>The profile of <strong>{{$details['Name']}}</strong>,who applied for <strong>{{$details['Job_Code']}}</strong> has been reviewed by <strong>{{$details['Employee']}}</strong> and the candidate has been shortlisted for an interview.</p>
<p>Kindly,coordinate with the Candidate to schedule an interview at a time that is convenient for both parties.</p>

<strong>Details:</strong>
<p><strong>Reference No:</strong> {{$details['ReferenceNo']}}<br>
<strong>Name:</strong> {{$details['Name']}}<br>
<strong>Email:</strong> {{$details['Email']}}<br>
<strong>Phone:</strong> {{$details['Phone']}}</p>
<p>* Please do not reply to this email - This is an automated message and responses cannot be received by our system.</p>


Thanks & Regards,<br>
VNR Recruitment
@endcomponent
