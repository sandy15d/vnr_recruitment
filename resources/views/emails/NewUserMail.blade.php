@component('mail::message')
<p>Dear,{{$details['Employee']}}</p>

<p>Your account has been created as a {{$details['Role']}}</p>

<p>Your login credentials are below,</p>

<p>USER ID &emsp;&emsp; : {{$details['Username']}}</p>
<p>Password &emsp;&emsp; : {{$details['Password']}}</p>
<p>Website &emsp;&emsp; : https://hrrec.vnress.in</p>
<br>
<br>
<small><b></i>*Please do not reply to this email- This is an automated message and responses cannot be received by our system.</i></b></small>
<br><br>



Thanks,<br>
VNR Recruitment
@endcomponent
