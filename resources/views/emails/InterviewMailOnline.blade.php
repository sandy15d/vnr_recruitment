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
<p>We are pleased to inform you that your application for the {{$details['department']}} at VNR has been shortlisted, and we would like to invite you for an online interview.</p>
 <h2>Interview Schedule</h2>

 <p>
  <strong>Date:</strong> {{ date('d-m-Y', strtotime($details['interview_date'])) }}<br>
  <strong>Time:</strong> {{ date('h:i A', strtotime($details['interview_time'])) }} <br>
  <strong>Online meeting link :</strong> <a href="{{$details['interview_link']}}" target="_blank">{{ $details['interview_link'] }}</a><br>
  <strong>Contact Person:</strong> HR<br>
  <strong>Phone:</strong> 0771-4350005
 </p>
 <h2>Pre-Interview Process:</h2>
 @component('mail::buttons', [
    'buttons' => [
            [
                'url' => $details['interview_form'],
                'slot' => 'Interview Application Form',
                'color' => 'green'
            ],[
                'url' => $details['firob'],
                'slot' => 'FIRO - B',
                'color' => 'blue'
            ]
        ]
    ])
 @endcomponent

<p>Please complete the above pre-interview process at your earliest convenience to ensure a smooth and efficient interview experience on the scheduled date. Should you encounter any technical difficulties or have any questions, please do not hesitate to contact our HR department at the provided contact number.
</p>

<p>Your credentials to access the online pre-interview process are as follows:</p>
 <br>Reference No : <b style="color: blue">{{ $details['reference_no']}}</b>
<br>
<p>We appreciate your interest in joining our team at VNR and look forward to meeting you online on {{ date('d-m-Y',strtotime($details['interview_date'])) }}.</p>

<br>
<p>Once again, congratulations on being shortlisted, and we hope to have a productive discussion during the interview.</p>
 <strong><i>Important Instruction to attend the Online interview process:</i></strong>
 <ul>
 <li>Ensure that your computer or device, camera, microphone, and internet connection are in good working order. Test them in advance to avoid technical issues during the interview.</li>
 <li>Find a quiet and well-lit space for the interview. Make sure the background is free from distractions.</li>
 <li>Keep a notepad and pen readily available.</li>
 <li>Dress as you would for an in-person interview, even though you are at home.</li>
 </ul>

 <br><br>
 <p>Thanks & Regards,<br> Team Talent Acquisition</p>

 <p class="footer">
  *Please do not reply to this email. This is an automated message, and responses cannot be received by our system.
 </p>

@endcomponent