<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your OTP Code</title>
    <style>
        /* Custom CSS */
        .otp-container {
            margin: 0 auto;
            padding: 20px;
            max-width: 600px;
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 8px;
        }
        .otp-header {
            text-align: center;
            margin-bottom: 20px;
        }
        .otp-code {
            font-size: 24px;
            font-weight: bold;
            color: #007bff;
            text-align: center;
        }
        .app-name {
            font-weight: bold;
            color: #343a40;
        }
    </style>
</head>
<body>
    <div class="otp-container">
        <h1 class="otp-header">{{ config('app.name') }}</h1>
        <p class="otp-code">Your OTP for email verification is: <strong>{{ $otp }}</strong></p>
        <p>Thank you for choosing <span class="app-name">{{ config('app.name') }}</span>.</p>
        <p>If you did not request this OTP, please ignore this email.</p>
    </div>
</body>
</html>
