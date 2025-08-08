<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            background-color: #f9f9f9;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .email-header {
            background-color: #4caf50;
            color: white;
            padding: 20px;
            text-align: center;
            font-size: 24px;
        }

        .email-content {
            padding: 20px;
        }

        .email-content h1 {
            font-size: 20px;
            color: #4caf50;
            margin-top: 0;
        }

        .email-content p {
            margin: 10px 0;
        }

        .email-footer {
            background-color: #f1f1f1;
            color: #666;
            padding: 15px;
            text-align: center;
            font-size: 14px;
        }

        .email-footer a {
            color: #4caf50;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            <strong>Organizer Account Verified</strong>
        </div>
        <div class="email-content">
            <h1>Congratulations {{ $organizer->user->name }}!</h1>
            <p>Your organizer account has been successfully verified.</p>
            <p>You can now fully access the features and manage your events on our platform.</p>
            <p>Click the link below to log in and get started:</p>
            <p><a href="{{ url('/login') }}" style="color: #4caf50; text-decoration: underline;">Log in to your account</a></p>
        </div>
        <div class="email-footer">
            <p>Thank you for being a part of our platform.</p>
            <p><a href="{{ url('/organizer/dashboard') }}">Visit Dashboard</a></p>
        </div>
    </div>
</body>
</html>
