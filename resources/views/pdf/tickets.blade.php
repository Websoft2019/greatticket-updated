@php
    use App\Helpers\QRCodeHelper;
@endphp
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Tickets</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            margin: 0;
            padding: 20px;
            font-size: 12px;
            color: #000;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .header h2 {
            margin: 0;
        }

        .ticket {
            width: 100%;
            border: 1px solid #000;
            padding: 10px;
            margin-bottom: 20px;
            page-break-inside: avoid;
        }

        .ticket-header {
            width: 100%;
            margin-bottom: 10px;
            border-bottom: 1px solid #000;
            padding-bottom: 10px;
        }

        .ticket-header table {
            width: 100%;
        }

        .ticket-header td {
            vertical-align: top;
        }

        .ticket-header .logo {
            width: 150px;
        }

        .ticket-header .header-right {
            text-align: right;
            font-size: 14px;
        }

        .ticket-body table {
            width: 100%;
            border-collapse: collapse;
        }

        .ticket-body td {
            vertical-align: top;
            padding: 10px;
            border-right: 1px solid #ccc;
        }

        .ticket-body td:last-child {
            border-right: none;
        }

        .qr-section img {
            width: 120px;
            height: auto;
            margin-bottom: 10px;
        }

        .ticket-code {
            font-weight: bold;
            margin-top: 5px;
        }

        .details-section .row {
            margin-bottom: 5px;
        }

        .details-section .label {
            font-weight: bold;
            display: inline-block;
            width: 90px;
        }

        .user-info {
            margin-top: 10px;
            padding: 5px;
            background: #f0f0f0;
        }

        .poster-section img {
            width: 120px;
            height: auto;
        }

        .footer {
            margin-top: 10px;
            font-size: 10px;
            text-align: center;
            border-top: 1px solid #000;
            padding-top: 5px;
        }
        .img{
            margin: 5px 0px;
            text-align: center;
        }
        .img img{
            width: 65px;
            height: 65px;
            object-fit: contain;
            border-radius: 50%;
        }
    </style>
</head>

<body>
    <div class="header">
        @php
            $logoPath = QRCodeHelper::getSafePublicPath('site/images/logo.png');
        @endphp
        @if($logoPath)
            <img src="file://{{ $logoPath }}" alt="Logo" style="width: 200px; height: auto;">
        @endif

        <h2>Ticket Purchase Details</h2>
        <p>Customer Name: {{ $purchaseDetails['customer_name'] }}</p>
        {{-- <p>Total Tickets: {{ $purchaseDetails['total_tickets'] }}</p> --}}
        <p>Total Price: {{ number_format($purchaseDetails['total_price'], 2) }}</p>
    </div>
    @foreach ($purchaseDetails['data'] as $packageId => $packageData)
        @foreach ($packageData['ticket_users'] as $ticketUser)
            <div class="ticket">
                <!-- Header -->
                <div class="ticket-header">
                    <table>
                        <tr>
                            <td class="logo">
                                @php
                                    $logoPath = QRCodeHelper::getSafePublicPath('site/images/logo.png');
                                @endphp
                                @if($logoPath)
                                    <img src="file://{{ $logoPath }}" alt="Logo" width="120">
                                @endif
                            </td>
                            <td class="header-right">
                                <strong>{{ $packageData['event'] ?? 'Event Title' }}</strong><br>
                                {{ \Carbon\Carbon::parse($packageData['event_date'])->format('l, F j, Y') }}<br>
                                Time: {{ $packageData['event_time'] ?? 'N/A' }}<br>
                                Venue: {{ $packageData['venue'] ?? 'N/A' }}
                            </td>
                        </tr>
                    </table>
                </div>

                <!-- Body -->
                <div class="ticket-body">
                    <table>
                        <tr>
                            <!-- QR Section -->
                            <td class="qr-section" width="25%">
                                <p><strong>Scan QR to Enter</strong></p>
                                @php
                                    $qrImagePath = QRCodeHelper::getSafeImagePath($ticketUser['qr_image'] ?? '');
                                    
                                    // Debug info (remove in production)
                                    if (config('app.debug')) {
                                        $debugInfo = QRCodeHelper::debugImagePath($ticketUser['qr_image'] ?? '');
                                        Log::info('PDF Ticket QR Debug', $debugInfo);
                                    }
                                @endphp
                                @if($qrImagePath)
                                    <img src="file://{{ $qrImagePath }}" alt="QR Code">
                                @else
                                    <div style="width: 120px; height: 120px; border: 1px solid #ccc; display: flex; align-items: center; justify-content: center; flex-direction: column;">
                                        <span style="font-size: 12px; font-weight: bold;">QR CODE</span>
                                        @if(config('app.debug'))
                                            <span style="font-size: 8px; color: red;">{{ $ticketUser['qr_image'] ?? 'No QR image path' }}</span>
                                        @endif
                                    </div>
                                @endif
                                <p>QR Code</p>
                                <p class="ticket-code">{{ $ticketUser['qr_code'] ?? 'N/A' }}</p>
                            </td>

                            <!-- Ticket Details -->
                            <td class="details-section" width="50%">
                                {{-- <div class="row"><span class="label">Seat:</span> {{ $ticketUser['seat'] ?? 'N/A' }}</div> --}}
                                <div class="row"><span class="label">Package:</span>
                                    {{ $packageData['package']['title'] }}</div>
                                {{-- <div class="row"><span class="label">Price:</span> RM{{ number_format($packageData['package']['cost'], 2) }}</div> --}}

                                <div class="user-info">
                                    <div class="row"><span class="label">Name:</span> {{ $ticketUser['name'] }}
                                    </div>
                                    @if ($ticketUser['seat_no'])
                                        <div class="row">
                                            <span class="label">Seat No. :</span>
                                            {{$ticketUser['seat_no']}} ({{$ticketUser['ticket_type']}})
                                        </div>
                                    @endif
                                    <div class="row"><span class="label">Payment Status:</span> {{$purchaseDetails['payment_status']}} </div>
                                </div>
                                <div class="organizer-details">
                                    @php
                                        $organizerPhotoPath = QRCodeHelper::getSafeImagePath($packageData['organizer_photo'] ?? '');
                                    @endphp
                                    @if ($organizerPhotoPath)
                                        <div class="img">
                                            <img src="file://{{ $organizerPhotoPath }}" alt="Organizer Photo">
                                        </div>
                                    @endif
                                    <div class="row">If you have any question, please reach out to <b>{{ $packageData['organizer_name'] ?? 'Organizer' }}</b>
                                    </div>
                                </div>
                            </td>

                            <!-- Poster Section -->
                            <td class="poster-section" width="25%">
                                @php
                                    $posterPath = QRCodeHelper::getSafeImagePath($packageData['poster'] ?? '');
                                @endphp
                                @if ($posterPath)
                                    <img src="file://{{ $posterPath }}" alt="Event Poster">
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>

                <!-- Footer -->
                <div class="footer">
                    <p><strong>No refunds or exchanges. Present this ticket at the gate for entry.</strong></p>
                    <p>Thank you for your purchase!</p>
                </div>
            </div>
        @endforeach
    @endforeach
</body>

</html>
