<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
</head>

<body style="font-family: Arial, sans-serif; line-height: 1.6; background-color: #f4f4f4; margin: 0; padding: 0;">

    <div style="max-width: 600px; margin: 20px auto; border: 1px solid #e0e0e0; padding: 20px; background-color: #ffffff; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
        <!-- Header -->
        <div style="background-color: #4CAF50; color: white; text-align: center; padding: 20px;">
            <img src="https://greatticket.my/site/images/logo.png" alt="Company Logo" style="max-width: 150px; margin-bottom: 10px;">
            <h1 style="margin: 0; font-size: 24px;">Ticket Purchase Confirmation</h1>
        </div>

        <!-- Body -->
        <div style="margin: 20px 0;">
            <p style="margin-bottom: 10px;">Dear {{ $customer_name }},</p>
            <p style="margin-bottom: 10px;">Thank you for purchasing tickets! Below are your purchase details:</p>

            <!-- Loop through packages -->
            @foreach ($data as $packageId => $packageDetails)
                <h2 style="font-size: 18px; margin: 20px 0 10px 0;">Event: {{ $packageDetails['event'] }}</h2>


                <table width="100%" border="1" cellpadding="10" cellspacing="0" style="border-collapse: collapse; margin-top: 10px;">
                    <thead>
                        <tr>
                            <th style="background-color: #f2f2f2; font-weight: bold; text-align: left;">Ticket Holder</th>
                            <th style="background-color: #f2f2f2; font-weight: bold; text-align: left;">Package</th>
                            {{-- <th style="background-color: #f2f2f2; font-weight: bold; text-align: left;">Cost</th> --}}
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($packageDetails['ticket_users'] as $ticketUser)
                            <tr>
                                <td style="background-color: #fafafa;">{{ $ticketUser['name'] }} ({{$ticketUser['ic']}})</td>
                                <td style="background-color: #fafafa;">{{ $packageDetails['package']['title'] }}</td>
                                {{-- <td style="background-color: #fafafa;">RM{{ $packageDetails['package']['cost'] }}</td> --}}
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endforeach

            <!-- Total section -->
            <div style="margin-top: 20px;">
                {{-- <p style="font-weight: bold; font-size: 16px;">Total Tickets: {{ $total_tickets }}</p> --}}
                <p style="font-weight: bold; font-size: 16px;">Total Price: RM {{ $total_price }}</p>
            </div>
        </div>

        <!-- Footer -->
        <div style="margin-top: 30px; text-align: center; font-size: 12px; color: #888;">
            <p>Thank you for choosing us! We hope you enjoy the event.</p>
        </div>

        <!-- Note -->
        <p style="font-size: 14px; color: #555; margin-top: 20px; text-align: center;">
            If you have any questions, feel free to contact us at <a href="mailto:enquiry@greatticket.my">enquiry@greatticket.my</a>.
        </p>
    </div>

</body>

</html>
