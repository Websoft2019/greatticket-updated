<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Order Summary</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap');

        body {
            height: 100vh;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(to bottom right, #0A0B1D, #7632CA);
            color: #FFFFFF;
            font-family: 'Poppins', sans-serif;
            text-align: center;
            padding: 20px;
        }
        .container {
            max-width: 90%;
            width: 100%;
        }
        .card {
            border: none;
            border-radius: 15px;
            background: rgba(255, 255, 255, 0.15);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(15px);
            padding: 20px;
        }
        .card-header {
            background: rgba(118, 50, 202, 0.9); /* Purple */
            color: #FFFFFF; /* White */
            font-size: 20px;
            font-weight: 600;
            border-radius: 10px;
            padding: 12px;
        }
        .order-item {
            border-bottom: 1px solid rgba(255, 255, 255, 0.3);
            padding: 10px 0;
            font-size: 16px;
        }
        .order-item:last-child {
            border-bottom: none;
        }
        .package-list {
            font-size: 14px;
            color: #EDEDED;
            margin-top: 5px;
        }
        .price {
            font-weight: 600;
            color: purple; /* Purple */
        }
        .total-price {
            font-size: 18px;
            font-weight: 700;
            color: #FFFFFF; /* White */
        }
        .btn-primary {
            background-color: #7632CA;
            border: none;
            font-size: 16px;
            padding: 10px 20px;
            border-radius: 8px;
            color: #FFFFFF;
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            background-color: #5a1fa1;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="card">
        <div class="card-header">
            Order Summary
        </div>
        <div class="card-body">
            <div class="order-item">
                <strong>Name:</strong> {{ $order->name }}
            </div>
            <div class="order-item">
                <strong>Packages:</strong>
                <ul class="package-list">
                    @foreach ($order->orderPackages as $packageData)
                        <li>{{ $packageData->package->title }} (x{{ $packageData->quantity }})</li>
                    @endforeach
                </ul>
            </div>
            <div class="order-item">
                <strong>Sub Total:</strong> <span class="price">RM {{ number_format($order->carttotalamount, 2) }}</span>
            </div>
            <div class="order-item">
                <strong>Total:</strong> <span class="total-price">RM {{ number_format($order->grandtotal, 2) }}</span>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS (Optional) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
