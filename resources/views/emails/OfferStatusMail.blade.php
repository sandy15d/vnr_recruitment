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

<p>This is to inform you that {{$details['candidate_name']}}, {{$details['reference_no']}} has responded to the job offer extended to them. The candidate has {{$details['status']}} the offer.</p>
@if($details['status']=='Accepted')
<p>Please proceed with the necessary onboarding steps.</p>
@else
<p>Please proceed with next steps as appropriate.</p>
@endif


<p>Thank you for your prompt attention to this matter.</p>




<br><br>
<p>Thanks & Regards,<br> Team Talent Acquisition</p>

<p class="footer">
    *Please do not reply to this email. This is an automated message, and responses cannot be received by our system.
</p>
@endcomponent
