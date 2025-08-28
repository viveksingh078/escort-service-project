<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Email Verification</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }
        .container {
            background-color: #ffffff;
            max-width: 600px;
            margin: 30px auto;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
        }
        .header {
            font-size: 22px;
            font-weight: bold;
            color: #343a40;
            margin-bottom: 15px;
        }
        .message {
            font-size: 16px;
            color: #495057;
            margin-bottom: 25px;
            line-height: 1.5;
        }
        .button {
            display: inline-block;
            padding: 12px 24px;
            background-color: #fc53ec;
            color: #ffffff!important;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
            margin-bottom: 20px;
        }
        .footer {
            font-size: 13px;
            color: #6c757d;
            text-align: center;
            margin-top: 25px;
        }
        @media only screen and (max-width: 600px) {
            .container {
                padding: 20px;
            }
            .button {
                padding: 10px 20px;
                font-size: 15px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">Verify Your Email Address</div>

        <div class="message">
            <p>Hello,</p>
            <p>Thank you for registering. To complete your registration, please verify your email address by clicking the button below.</p>
            <p><strong>This link will expire in 10 minutes for security purposes.</strong></p>
        </div>

        <div style="text-align: center;">
            <a href="{{ $link }}" class="button" style="color:#fff;background:#fc53ec;">Verify Email</a>
        </div>

        <div class="message">
            <p>If the button above doesn't work, copy and paste this link into your browser:</p>
            <p><a href="{{ $link }}">{{ $link }}</a></p>
        </div>

        <div class="footer">
            If you did not request this email, you can safely ignore it.
        </div>
    </div>
</body>
</html>
