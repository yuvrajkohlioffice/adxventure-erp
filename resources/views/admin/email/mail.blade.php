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
    .header {
        border-bottom: 1px solid #ccc;
    }
    .header img {
      max-width: 100%;
    }

    .footer {
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
        <h2>{!! $header !!}</h2>
        <div class="content">
            {!! $message !!}
        </div>
        <div class="footer">
          @if(isset($footer))
            {!! $footer !!}
          @else
            <p style="margin-bottom:0;">Best Regards,</p>
            <p style="margin:1px;">HR Department | <strong>Adxventure</strong></p>
            <a href="mailto:hr@adxventure.com" style="color:#1a73e8; text-decoration:none;">hr@adxventure.com</a>
          @endif
        </div>
    </div>
</body>
</html>