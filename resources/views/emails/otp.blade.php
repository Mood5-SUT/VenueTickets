<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OTP Verification</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 500px;
            margin: 40px auto;
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #0d6efd, #6610f2);
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            color: #ffffff;
            margin: 0;
            font-size: 24px;
        }
        .header i {
            font-size: 40px;
            color: #ffffff;
        }
        .body {
            padding: 40px 30px;
            text-align: center;
        }
        .otp-code {
            font-size: 48px;
            font-weight: 700;
            letter-spacing: 15px;
            color: #0d6efd;
            background: #f0f4ff;
            padding: 20px 10px;
            border-radius: 10px;
            margin: 20px 0;
            border: 2px dashed #0d6efd;
        }
        .validity {
            color: #dc3545;
            font-size: 14px;
            margin-bottom: 20px;
        }
        .footer {
            background: #f8f9fa;
            padding: 20px 30px;
            text-align: center;
            font-size: 13px;
            color: #6c757d;
            border-top: 1px solid #e9ecef;
        }
        .warning {
            color: #6c757d;
            font-size: 13px;
            margin-top: 20px;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <p style="font-size: 40px; margin: 0;">🎫</p>
            <h1>VenueTickets</h1>
        </div>
        
        <div class="body">
            <h2 style="color: #212529; margin-bottom: 10px;">Hello, {{ $name }}!</h2>
            <p style="color: #6c757d; font-size: 16px;">Your verification code is:</p>
            
            <div class="otp-code">
                {{ $otp }}
            </div>
            
            <p style="color: #212529;">Enter this code to verify your account.</p>
            <p class="validity">⏰ This code expires in 10 minutes</p>
            
            <p class="warning">
                If you didn't request this code, please ignore this email.
            </p>
        </div>
        
        <div class="footer">
            <p>&copy; {{ date('Y') }} VenueTickets. All rights reserved.</p>
            <p>This is an automated message, please do not reply.</p>
        </div>
    </div>
</body>
</html>