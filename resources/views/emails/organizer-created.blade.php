<!DOCTYPE html>
<html>
<head>
    <style>
        /* General styles for email body */
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
            background-color: #7b1fa2;
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
            color: #7b1fa2;
            margin-top: 0;
        }

        .email-content p {
            margin: 10px 0;
        }

        .email-content ul {
            list-style: none;
            padding: 0;
        }

        .email-content ul li {
            margin-bottom: 10px;
            padding: 10px;
            border: 1px solid #eee;
            border-radius: 4px;
            background-color: #f9f9f9;
        }

        .email-footer {
            background-color: #f1f1f1;
            color: #666;
            padding: 15px;
            text-align: center;
            font-size: 14px;
        }

        .email-footer a {
            color: #7b1fa2;
            text-decoration: none;
        }

        /* Responsive styling */
        @media (max-width: 600px) {
            .email-container {
                margin: 10px;
            }

            .email-header {
                font-size: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="email-header">
            <strong>New Organizer Registered</strong>
        </div>

        <!-- Content -->
        <div class="email-content">
            <h1>Hello Admin,</h1>
            <p>A new organizer has registered on your platform. Here are their details:</p>
            <ul>
                <li><strong>Name:</strong> {{ $organizer->user->name }}</li>
                <li><strong>Email:</strong> {{ $organizer->user->email }}</li>
                <li><strong>Phone:</strong> {{ $organizer->user->contact }}</li>
                <li><strong>About:</strong> {{ $organizer->about ?? 'N/A' }}</li>
                <li><strong>Address:</strong> {{ $organizer->address ?? 'N/A' }}</li>
                <li><strong>Verified:</strong> {{ $organizer->verify ? 'Yes' : 'No' }}</li>
            </ul>
            <p>Please review and take appropriate action.</p>
        </div>

        <!-- Footer -->
        <div class="email-footer">
            <p>Thank you for using our platform.</p>
            <p><a href="{{ url('/dashboard') }}">Visit Dashboard</a></p>
        </div>
    </div>
</body>
</html>
