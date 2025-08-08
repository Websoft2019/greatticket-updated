<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Daily Order Summary</title>
</head>
<body style="margin:0; padding:0; font-family: Arial, sans-serif; background-color: #f4f4f4;">
  <table width="100%" cellpadding="0" cellspacing="0" style="background-color:#f4f4f4; padding: 20px;">
    <tr>
      <td align="center">
        <table width="600" cellpadding="0" cellspacing="0" style="background-color:#ffffff; border-radius:8px; overflow:hidden;">
          <!-- Header -->
          <tr>
            <td align="center" style="background: linear-gradient(135deg, #667eea, #764ba2); color:#fff; padding:30px;">
              <h1 style="margin:0; font-size:26px;">📊 Daily Order Report</h1>
              <p style="margin:5px 0 0; font-size:14px;">{{ \Carbon\Carbon::parse($reportDate)->format('l, F j, Y') }}</p>
            </td>
          </tr>

          <!-- Greeting -->
          <tr>
            <td style="padding: 20px;">
              <p><strong>Hello {{ $organizer->name }},</strong></p>
              <p>Here's the order summary for your event <strong>{{ $event->title }}</strong>.</p>
            </td>
          </tr>

          <!-- Summary Cards -->
          <tr>
            <td align="center">
              <table width="90%" cellpadding="0" cellspacing="0" style="margin: 10px auto;">
                <tr>
                  <td style="padding:10px; text-align:center; background-color:#e8f0fe; border-radius:6px;">
                    <p style="margin:0; font-size:12px;">Total Orders</p>
                    <p style="margin:0; font-size:20px;"><strong>{{ $orders->count() }}</strong></p>
                  </td>
                  <td style="padding:10px; text-align:center; background-color:#fce4ec; border-radius:6px;">
                    <p style="margin:0; font-size:12px;">Total Revenue</p>
                    <p style="margin:0; font-size:20px;"><strong>RM{{ number_format($summary['totalRevenue'], 2) }}</strong></p>
                  </td>
                  <td style="padding:10px; text-align:center; background-color:#e8f5e9; border-radius:6px;">
                    <p style="margin:0; font-size:12px;">Packages Sold</p>
                    <p style="margin:0; font-size:20px;"><strong>{{ $summary['totalPackages'] }}</strong></p>
                  </td>
                </tr>
              </table>
            </td>
          </tr>

          <!-- Orders Table -->
          @if($orders->count() > 0)
          <tr>
            <td style="padding: 20px;">
              <h3 style="margin: 0 0 10px; border-bottom: 1px solid #ddd; padding-bottom: 5px;">📋 Order Details</h3>
              <table width="100%" cellpadding="5" cellspacing="0" border="1" style="border-collapse: collapse; font-size: 13px;">
                <thead style="background-color: #667eea; color: white;">
                  <tr>
                    <th align="left">Order #</th>
                    <th align="left">Customer</th>
                    <th align="left">Amount</th>
                    <th align="left">Status</th>
                    <th align="left">Time</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($orders as $order)
                  <tr>
                    <td>#{{ $order->id }}</td>
                    <td>{{ $order->name ?? 'Guest' }}</td>
                    <td>RM {{ number_format($order->carttotalamount, 2) }}</td>
                    <td>{{ ucfirst($order->paymentstatus ?? 'Pending') }}</td>
                    <td>{{ $order->created_at->format('g:i A') }}</td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </td>
          </tr>
          @else
          <tr>
            <td style="padding: 30px; text-align: center; font-style: italic; color: #777;">
              📭 No orders for this event on {{ \Carbon\Carbon::parse($reportDate)->format('l, F j, Y') }}.
            </td>
          </tr>
          @endif

          <!-- Footer -->
          <tr>
            <td style="padding: 20px; text-align: center; font-size: 12px; color: #999;">
              <p>This is an automated email report generated on {{ now()->format('F j, Y \a\t g:i A T') }}.</p>
              <p>For questions, contact our support team.</p>
              <p>Thank you for using our platform 🙏</p>
            </td>
          </tr>

        </table>
      </td>
    </tr>
  </table>
</body>
</html>
