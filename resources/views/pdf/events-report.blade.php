<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Event PDF</title>

    <style>
        body {
            font-family: sans-serif;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 6px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        h3 {
            margin-top: 0;
        }

        /* Keep your existing styles */
        .chart-img {
            width: 100%;
            max-width: 100%;
            margin: 20px 0;
        }

        /* Add print-specific styles */
        @media print {
            .chart-img {
                page-break-inside: avoid;
            }
        }
    </style>
</head>

<body>

    <h3>Event: {{ $eventData['event']->title }}</h3>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Package Title</th>
                {{-- <th>Capacity</th> --}}
                <th>Quantity</th>
                <th>Total Cost</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($eventData['packagesData'] as $index => $packageData)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $packageData['package']->title }}</td>
                    {{-- <td>{{ $packageData['package']->capacity }}</td> --}}
                    <td>{{ $packageData['quantity'] }}</td>
                    <td>
                        {{ $packageData['orderTotal'] }}
                    </td>
                </tr>
            @endforeach
            <tr>
                <td colspan="3" style="text-align: right;"><strong>Total Event Cost:</strong></td>
                <td> {{ $eventData['totalOrderCost'] }}</td>
            </tr>
        </tbody>
    </table>
    <div class="chart-section">
        <h4>Order Trends</h4>
        @php
            $imageData = explode(',', $chartImage);
        @endphp
        <img src="data:image/png;base64,{!! $imageData[1] !!}" class="chart-img" alt="Order Trends Chart">
    </div>

</body>

</html>
