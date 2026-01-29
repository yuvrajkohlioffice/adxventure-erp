<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Leave Request</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
    }
    .container {
      max-width: 600px;
      margin: 0 auto;
      padding: 20px;
      border: 1px solid #ccc;
      border-radius: 4px;
      /*background-color:#d3e3fd;*/
    }
    .header img {
      max-width: 100%;
    }
    .footer {
      margin-top: 20px;
      border-top: 1px solid #ccc;
      padding-top: 10px;
      font-size: 14px;
      color: #666;
    }
    .middle {
      margin-top: 20px;
      border-top: 1px solid #ccc;
      padding-top: 10px;
      font-size: 14px;
      color: #666;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="header">
        <center>
          <img style="width:200px;" src="https://tms.adxventure.com/logo.png" alt="Company Logo">
        </center>
    </div>
    <div class="content">
      <h2>Leave Request</h2>
      <p>
        Dear HR Team,
      </p>
      <p>
        I am writing to formally request a leave of absence from
        <strong>{{ $leave->from_date ?? '' }}</strong> to <strong>{{ $leave->to_date ?? ''   }} </strong>. 
        <br>
        The reason for my leave is {{ $leave->request ?? '' }}</strong>.
      </p>
      <p>
        Please find the details of my leave request below:
      </p>
      <ul>
        <li><strong>Start Date:</strong>  {{ $leave->from_date ?? '' }}</li>
        <li><strong>End Date:</strong> {{ $leave->to_date ?? '' }}</li>
        <li><strong>Total Number of Days:</strong> {{ $leave->days ?? '' }}</li>
        <li><strong>Type of Leave:</strong> {{  $leave->type ?? ''  }}</li>
      </ul>
     </div>
    {{--  @if(!empty($leave->content))
          <div class="middle" >
                {!! $leave->content ?? '' !!}
          </div>
      @endif --}}
     <div class="footer">
      <p>Thank you for considering my request. I understand the importance of maintaining 
        productivity within the team and will ensure that my responsibilities are appropriately delegated or managed in my absence. </p>
      <p>
        Looking forward to your response.
      </p>
      <p>
        Sincerely,
        <br>
        {{$leave->users->name ?? auth()->user()->name }}<br>
        {{$leave->users->email ?? auth()->user()->email }}<br>
        {{ $leave->users->phone_no ?? auth()->user()->phone_no }}
      </p>
  </div>
  </div>
</body>
</html>