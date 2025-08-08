<!DOCTYPE html>
<html>
<head>
    <title>Account Not Verified</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            padding: 50px;
        }

        .container {
            max-width: 600px;
            margin: auto;
            background: #f9f9f9;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #ff5722;
        }

        p {
            color: #333;
        }

        a {
            color: #4caf50;
            text-decoration: none;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Account Not Verified</h1>
        <p>Your organizer account is not verified yet.</p>
        <p>Please contact support or wait for admin approval.</p>
        <a href="{{ url('/') }}">Go to Homepage</a>
    </div>
</body>
</html>
