<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daily Event Order Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f8f9fa;
        }
        .container {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 25px;
            border-radius: 8px;
            margin-bottom: 25px;
            text-align: center;
        }
        .header h1 {
            margin: 0 0 10px 0;
            font-size: 28px;
        }
        .header p {
            margin: 0;
            font-size: 16px;
            opacity: 0.9;
        }
        .event-info {
            background-color: #e3f2fd;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 25px;
            border-left: 5px solid #2196f3;
        }
        .event-info h2 {
            margin: 0 0 15px 0;
            color: #1976d2;
            font-size: 24px;
        }
        .event-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-top: 15px;
        }
        .event-detail-item {
            background-color: rgba(255,255,255,0.7);
            padding: 10px;
            border-radius: 5px;
        }
        .event-detail-item strong {
            color: #1976d2;
        }
        .summary {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 15px;
            margin: 25px 0;
        }
        .summary-item {
            text-align: center;
            padding: 20px;
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .summary-item h3 {
            margin: 0 0 10px 0;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 1px;
            opacity: 0.9;
        }
        .summary-item p {
            margin: 0;
            font-size: 28px;
            font-weight: bold;
        }
        .orders-section {
            margin-top: 30px;
        }
        .orders-section h2 {
            color: #333;
            border-bottom: 3px solid #667eea;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            background-color: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        th {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px 12px;
            text-align: left;
            font-weight: 600;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        td {
            padding: 15px 12px;
            border-bottom: 1px solid #eee;
            vertical-align: top;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:hover {
            background-color: #f0f8ff;
            transition: background-color 0.3s ease;
        }
        .package-details {
            font-size: 13px;
            color: #666;
            line-height: 1.4;
        }
        .package-item {
            background-color: #e8f5e8;
            padding: 5px 8px;
            border-radius: 4px;
            margin: 2px 0;
            display: inline-block;
            font-size: 12px;
        }
        .status-badge {
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 500;
            text-transform: capitalize;
        }
        .status-pending { background-color: #fff3cd; color: #856404; }
        .status-completed { background-color: #d4edda; color: #155724; }
        .status-cancelled { background-color: #f8d7da; color: #721c24; }
        .status-processing { background-color: #d1ecf1; color: #0c5460; }
        .no-orders {
            text-align: center;
            color: #6c757d;
            font-style: italic;
            padding: 60px 20px;
            background-color: #f8f9fa;
            border-radius: 8px;
            margin: 20px 0;
        }
        .no-orders-icon {
            font-size: 48px;
            margin-bottom: 15px;
            opacity: 0.5;
        }
        .footer {
            margin-top: 40px;
            padding-top: 25px;
            border-top: 2px solid #eee;
            color: #6c757d;
            font-size: 14px;
            text-align: center;
        }
        .footer p {
            margin: 5px 0;
        }
        .greeting {
            background-color: #f0f8ff;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            border-left: 4px solid #4CAF50;
        }
        @media (max-width: 600px) {
            .container {
                padding: 15px;
            }
            .summary {
                grid-template-columns: 1fr;
            }
            .event-details {
                grid-template-columns: 1fr;
            }
            table {
                font-size: 12px;
            }
            th, td {
                padding: 10px 8px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📊 Daily Order Report</h1>
            <p>{{ now()->subDay()->format('l, F j, Y') }}</p>
        </div>

        <div class="greeting">
            <p><strong>Hello {{ $organizer->name }},</strong></p>
            <p>Here's your daily order summary for your event. We hope you find this report helpful!</p>
        </div>

        <div class="event-info">
            <h2>🎉 {{ $event->title }}</h2>
            <div class="event-details">
                @if(isset($event->date))
                <div class="event-detail-item">
                    <strong>📅 Event Date:</strong><br>
                    {{ \Carbon\Carbon::parse($event->date)->format('M j, Y') }}
                </div>
                @endif
                @if(isset($event->location))
                <div class="event-detail-item">
                    <strong>📍 Location:</strong><br>
                    {{ $event->location }}
                </div>
                @endif
                @if(isset($event->category))
                <div class="event-detail-item">
                    <strong>🏷️ Category:</strong><br>
                    {{ $event->category->name ?? 'N/A' }}
                </div>
                @endif
                @if(isset($event->status))
                <div class="event-detail-item">
                    <strong>📈 Status:</strong><br>
                    {{ ucfirst($event->status) }}
                </div>
                @endif
            </div>
            @if(isset($event->description) && $event->description)
            <div style="margin-top: 15px;">
                <strong>📝 Description:</strong><br>
                <em>{!! Str::limit($event->description, 150) !!}</em>
            </div>
            @endif
        </div>

        @php
            $totalRevenue = 0;
            $totalPackages = 0;
            foreach($orders as $order) {
                $totalRevenue += $order->carttotalamount;
                foreach($order->orderPackages as $orderPackage) {
                    if($orderPackage->package->event_id == $event->id) {
                        $totalPackages += $orderPackage->quantity;
                    }
                }
            }
            $averageOrderValue = $orders->count() > 0 ? $totalRevenue / $orders->count() : 0;
        @endphp

        <div class="summary">
            <div class="summary-item">
                <h3>📦 Total Orders</h3>
                <p>{{ $orders->count() }}</p>
            </div>
            <div class="summary-item">
                <h3>💰 Total Revenue</h3>
                <p>RM {{ number_format($totalRevenue, 2) }}</p>
            </div>
            <div class="summary-item">
                <h3>🎫 Packages Sold</h3>
                <p>{{ $totalPackages }}</p>
            </div>
            
        </div>

        @if($orders->count() > 0)
            <div class="orders-section">
                <h2>📋 Order Details</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Order #</th>
                            <th>Customer</th>
                            <th>Package(s)</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                            <tr>
                                <td><strong>#{{ $order->id }}</strong></td>
                                <td>
                                    <div>{{ $order->name ?? 'Guest Customer' }}</div>
                                    @if($order->email)
                                        <small style="color: #666;">{{ $order->email }}</small>
                                    @endif
                                </td>
                                <td>
                                    @foreach($order->orderPackages as $orderPackage)
                                        @if($orderPackage->package->event_id == $event->id)
                                            <div class="package-item">
                                                {{ $orderPackage->package->title }} 
                                                <strong>({{ $orderPackage->quantity }}x)</strong>
                                            </div>
                                        @endif
                                    @endforeach
                                </td>
                                <td>
                                    <strong>RM {{ number_format($order->carttotalamount,2) }}</strong>
                                </td>
                                <td>
                                    @php
                                        $status = $order->paymentstatus ?? 'pending';
                                        $statusClass = 'status-' . strtolower($status);
                                    @endphp
                                    <span class="status-badge {{ $statusClass }}">
                                        {{ ucfirst($status) }}
                                    </span>
                                </td>
                                <td>
                                    <div>{{ $order->created_at->format('g:i A') }}</div>
                                    <small style="color: #666;">{{ $order->created_at->format('M j') }}</small>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="no-orders">
                <div class="no-orders-icon">📭</div>
                <h3>No Orders Today</h3>
                <p>No orders were placed for this event on {{ now()->subDay()->format('F j, Y') }}</p>
                <p>Don't worry - tomorrow is a new day with new opportunities!</p>
            </div>
        @endif

        <div class="footer">
            <p><strong>📧 This is an automated report</strong></p>
            <p>Generated on {{ now()->format('F j, Y \a\t g:i A T') }}</p>
            <p>If you have any questions about this report, please contact our support team.</p>
            <p>Thank you for using our platform! 🙏</p>
        </div>
    </div>
</body>
</html>