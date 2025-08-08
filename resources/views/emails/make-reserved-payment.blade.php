<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment Request</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f9f9f9;
            color: #333;
            padding: 20px;
        }

        .container {
            max-width: 600px;
            background: #fff;
            padding: 30px;
            margin: 0 auto;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.05);
        }

        h2 {
            color: #0a6ebd;
        }

        .button {
            display: inline-block;
            background-color: #0a6ebd;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
        }

        .footer {
            margin-top: 30px;
            font-size: 12px;
            color: #888;
            text-align: center;
        }

        .details {
            background: #f1f5f9;
            padding: 15px;
            border-radius: 5px;
        }

        .details p {
            margin: 5px 0;
        }

    </style>
</head>
<body>
    <div class="container">
        <h2>Hi {{ $purchaseDetails['user'] }},</h2>

        <p>Thank you for reserving tickets for <strong>{{ $purchaseDetails['event'] }}</strong>. To confirm your reservation, please complete the payment.</p>

        <div class="details">
            <p><strong>Event:</strong> {{ $purchaseDetails['event'] }}</p>
            <p><strong>Package:</strong> {{ $purchaseDetails['package'] }}</p>
            @if (!empty($purchaseDetails['seats']))
                <p><strong>Seats:</strong> {{ implode(', ', $purchaseDetails['seats']) }}</p>
            @else
                <p><strong>Seats:</strong> Not assigned yet</p>
            @endif
            <p><strong>Amount:</strong> NPR {{ number_format($purchaseDetails['amount'], 2) }}</p>
            <p><strong>Payment Deadline:</strong> {{ \Carbon\Carbon::parse($purchaseDetails['deadline'])->format('F j, Y g:i A') }}</p>
        </div>

        <p style="margin-top: 20px;">Click the button below to complete your payment:</p>

        <a href="{{ $purchaseDetails['paymentUrl'] }}" class="button">Pay Now</a>

        <p>If you’ve already paid, you may disregard this email.</p>

        <p>Thanks,<br>The {{ config('app.name') }} Team</p>

        <div class="footer">
            &copy; {{ now()->year }} {{ config('app.name') }}. All rights reserved.
        </div>
    </div>
</body>
</html>
