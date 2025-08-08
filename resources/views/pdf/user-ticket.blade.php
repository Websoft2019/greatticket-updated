<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Ticket PDF</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: url('{{ public_path("images/bg.jpg") }}') no-repeat right center;
            background-size: contain;
            margin: 0;
            padding: 40px;
        }

        .container {
            width: 65%;
            background: #fff;
            padding: 30px;
        }

        h2 {
            margin-top: 0;
        }

        .description {
            margin-bottom: 20px;
            color: #555;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        th, td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: left;
        }

        th {
            background: #f4f4f4;
        }

        .qr-img {
            width: 50px;
            height: 50px;
            object-fit: cover;
        }

        .date-box {
            position: absolute;
            top: 60px;
            right: 60px;
            background: #7a40ff;
            color: #fff;
            text-align: center;
            padding: 10px 20px;
            font-weight: bold;
        }

        .date-box .day {
            font-size: 28px;
        }

        .date-box .month,
        .date-box .year {
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>{{ $event->title }}</h2>
        <p class="description">{{ $event->highlight }}</p>
        <p>{!! $event->description !!}</p>

        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Package</th>
                    <th>Cost ({{ $event->currency ?? 'RM' }})</th>
                    <th>Payment / QR</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($orderPackage->ticketUsers as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $orderPackage->package->title }}</td>
                        <td>RM {{ $orderPackage->package->actual_cost }}</td>
                        <td>
                            @php
                                $qrPath = storage_path('app/public/' . $user->qr_image); // or wherever your image is stored
                                $qrBase64 = '';
                            
                                if (file_exists($qrPath)) {
                                    $qrBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($qrPath));
                                }
                            @endphp
                            
                            @if($qrBase64)
                                <img src="{{ $qrBase64 }}" class="qr-img">
                            @endif

                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="date-box">
        <div class="day">{{ \Carbon\Carbon::parse($event->date)->format('d') }}</div>
        <div class="month">{{ \Carbon\Carbon::parse($event->date)->format('M') }}</div>
        <div class="year">{{ \Carbon\Carbon::parse($event->date)->format('Y') }}</div>
    </div>
</body>
</html>
