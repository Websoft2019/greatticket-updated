<?php

namespace App\Http\Controllers\Admin;

use App\Services\TicketBookingService;
use Illuminate\Support\Facades\Http;
use App\Helpers\QRCodeHelper;
use App\Http\Controllers\Controller;
use App\Mail\TicketPdfMail;
use App\Mail\TicketPurchaseMail;
use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class SanamPayController extends Controller
{

    private function getMembershipNumber()
    {
        return DB::transaction(function () {
            $latest = DB::table('ticket_users')
                ->whereNotNull('membership_no')
                ->orderByDesc('id')
                ->lockForUpdate()
                ->first();

            $lastNumber = 10077;
            if ($latest && strpos($latest->membership_no, '-') !== false) {
                $explode = explode('-', $latest->membership_no);
                $lastNumber = isset($explode[1]) ? (int) $explode[1] : $lastNumber;
            }

            // Loop until unique membership number is found
            do {
                $lastNumber++;
                $nextMembershipNo = 'TOP-' . $lastNumber;
                $exists = DB::table('ticket_users')
                    ->where('membership_no', $nextMembershipNo)
                    ->exists();
            } while ($exists);

            return $nextMembershipNo;

            // return 'TOP-' . $next;
        });
    }

    public function getSanangPayResult(Request $request)
    {
        if(env('SENANGPAY_MODE') == 'SANDBOX'){
            $secretkey = env('SENANGPAY_SANDBOX_SECRETKEY');
        }
        else{
            $secretkey = env('SENANGPAY_LIVE_SECRETKEY');
        }
        $status_id = $request->status_id;
        $order_id = $request->order_id;
        $transaction_id = $request->transaction_id;
        $msg = $request->msg;
        $hash = $request->hash;
        // $cartcode = Session::get('cartcode');
        $str = $secretkey . '' . $status_id . '' . $order_id . '' . $transaction_id . '' . $msg;

        $hashed_string = hash_hmac('SHA256', $str, $secretkey);
        $getcartcode = explode("-", $order_id);
        $cartcodevalue = $getcartcode[1];
        if ($hashed_string == $hash) {
            if (urldecode($request->status_id) == '1') {
                $order = Order::where('id', $cartcodevalue)->limit(1)->first();
                $organizer_mail = $order->orderPackages()->first()->package->event->user->email ?? '';
                if ($order->paymentstatus == 'Y') {
                    dd('This order already Paid');
                }
                $user = $order->user;

                if ($getcartcode[0] == 'W' || $getcartcode[0] == 'm') {

                    // Call the service
                    $bookingService = app(TicketBookingService::class);
                    $bookingService->handleUserBooking($order, $transaction_id);
                    

                    foreach ($order->orderPackages as $orderPackage) {
                        $data[$orderPackage->package->id] = [
                            'event' => $orderPackage->package->event->title,
                            'event_date' => $orderPackage->package->event->date ?? null,
                            'event_time' => $orderPackage->package->event->time,
                            'venue' => $orderPackage->package->event->vennue,
                            'poster' => $orderPackage->package->event->primary_photo,
                            'organizer_photo' => $orderPackage->package->event->user->organizer->photo ?? null,
                            'organizer_name' => $orderPackage->package->event->user->name ?? null,
                            'package' => $orderPackage->package->toArray(),
                            'ticket_users' => $orderPackage->ticketUsers->map(function ($ticketUser) {
                                return [
                                    'name' => $ticketUser->name,
                                    'seat_id' => $ticketUser->seat_id,
                                    'seat_no' => $ticketUser->seat_id ? ( $ticketUser->seat->row_label . $ticketUser->seat->seat_number) : null, 
                                    'ticket_type' => $ticketUser->ticket_type,
                                    'qr_code' => $ticketUser->qr_code,
                                    'qr_image' => $ticketUser->qr_image,
                                    'ic' => $ticketUser->ic,
                                    'membership_no' => $ticketUser->membership_no
                                ];
                            })->toArray(),
                        ];
                    }

                    $purchaseDetails = [
                        'customer_name' => $user->name,
                        'data' => $data,
                        'total_tickets' => $order->orderPackages->sum('quantity'),
                        'total_price' => $order->servicecharge + $order->grandtotal,
                        'payment_status' => $order->paymentstatus == 'Y' ? 'Paid' : ($order->paymentstatus == 'R' ? 'Pending (Reserved)' : 'Failed'),
                        'payment_method' => $order->paymentstatus == 'N' ? 'N/A' : ($order->paymentmethod == 'SanangPay' ? 'online' : $order->paymentmethod),
                    ];
                    // dd($purchaseDetails);

                    // Generate the PDF
                    $pdf = Pdf::loadView('pdf.tickets', compact('purchaseDetails'));
                    // Save the PDF temporarily
                    $pdfPath = storage_path('app/public/tickets.pdf');
                    $pdf->save($pdfPath);

                    // return $purchaseDetails;
                    Mail::to($order->email)
                        ->bcc(['greatticketmy@gmail.com', 'lalasathen@gmail.com', $organizer_mail])
                        ->send(new TicketPdfMail($purchaseDetails, $pdfPath));

                    // Clean up the temporary PDF file after sending the email
                    unlink($pdfPath);
                    // dd('here comes');
                    if ($getcartcode[0] == 'W') {
                        if (auth()->user()) {
                            if (auth()->user()->session_id) {
                                notify()->success("Payment successful. Ticket has been sent to your mail");
                                return redirect()->route('getHome')->with('success', "Ticket has been sent to your mail");
                            }
                            return redirect()->route('history')->with('success', "Ticket has been sent to your mail");
                        }

                        return redirect()->route('getHome', $request->all())->with('success', 'Ticket Purchase successful.');
                    } elseif ($getcartcode[0] == 'm') {
                        return redirect()->route('mobile.payment', $transaction_id);
                        return redirect()->route('getHome', $request->all())->with('success', 'Ticket purchase successfully.');
                    } else {
                        // return redirect()->route('getHome', $request->all());
                        return redirect()->route('events');
                    }
                } else {
                    abort('404');
                }
            } else {
                dd('payment failed status not 1');
            }
        } else {
            dd('payment failed hash not same');
        }
    }
    public function getMannualPay(Order $order)
    {

        $merchant_id = '244172845127423';
        $order_id = 'W-' . $order->id;
        $secretkey = '42815-932';
        $hash_string = $merchant_id . $secretkey . $order_id;
        $hash = hash_hmac('SHA256', $hash_string, $secretkey);

        $transaction_check_status_url = 'https://app.senangpay.my/apiv1/query_order_status';

        // Step 3: Send GET request using Guzzle (or any HTTP client)
        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', $transaction_check_status_url, [
            'query' => [
                'merchant_id' => $merchant_id,
                'order_id' => $order_id,
                'hash' => $hash
            ]
        ]);

        // Step 4: Get the response
        $result = json_decode($response->getBody(), true);
        dd($result['data']['product']);
        // Step 5: Return or handle the result
        if (isset($result['status']) && $result['status'] == 1 && !empty($result['data'])) {
            if ($order->paymentstatus == 'Y') {
                dd('already paid and already send email');
            }

            $user = $order->user;
            $reservationinfo = $order;
            $data = [];

            DB::table('orders')->where('id', $order->id)->update([
                'paymentstatus' => 'Y',
                'paymentmethod' => 'SanangPay',
                'updated_at' => date('Y-m-d'),
                'payerid' => '1744792624015673314'
            ]);
            foreach ($order->orderPackages as $pack) {
                $pack->package->increment('consumed_seat', $pack->quantity);

                foreach ($pack->ticketUsers as $user) {
                    try {
                        $code = Str::uuid();
                        // Generate the QR code and get the path
                        $qrCodePath = QRCodeHelper::generateQrCode($code);
                        $user->update([
                            'qr_code' => $code,
                            'qr_image' => $qrCodePath, // Save the file path for later reference
                            'membership_no' => $this->getMembershipNumber(),
                        ]);
                    } catch (\Exception $e) {
                        Log::error('QR Code Generation Failed', [
                            'user_id' => $user->id,
                            'error' => $e->getMessage()
                        ]);
                    }
                }
            }
            foreach ($order->orderPackages as $orderPackage) {
                $data[$orderPackage->package->id] = [
                    'event' => $orderPackage->package->event->title,
                    'package' => $orderPackage->package->toArray(),
                    'ticket_users' => $orderPackage->ticketUsers->map(function ($ticketUser) {
                        return [
                            'name' => $ticketUser->name,
                            'seat_number' => $ticketUser->seat->row_label??''. $ticketUser->seat->seat_number??'',
                            'ticket_type' => $ticketUser->ticket_type,
                            'qr_image' => $ticketUser->qr_image,
                            'ic' => $ticketUser->ic,
                            'membership_no' => $ticketUser->membership_no
                        ];
                    })->toArray(),
                ];
            }


            $purchaseDetails = [
                'customer_name' => $user->name,
                'data' => $data,
                'total_tickets' => $order->orderPackages->sum('quantity'),
                'total_price' => $order->servicecharge + $order->grandtotal,
                'payment_status' => $order->paymentstatus == 'Y' ? 'Paid' : ($order->paymentstatus == 'R' ? 'Pending (Reserved)' : 'Failed'),
                'payment_method' => $order->paymentstatus == 'N' ? 'N/A' : ($order->paymentmethod == 'SanangPay' ? 'online' : $order->paymentmethod),
            ];
            // dd($purchaseDetails);

            // Generate the PDF
            $pdf = Pdf::loadView('pdf.tickets', compact('purchaseDetails'));
            // Save the PDF temporarily
            $pdfPath = storage_path('app/public/tickets.pdf');
            $pdf->save($pdfPath);

            // return $purchaseDetails;
            Mail::to($order->email)
                ->bcc(['greatticketmy@gmail.com', 'lalasathen@gmail.com', 'wildaffairs88@gmail.com'])
                ->send(new TicketPdfMail($purchaseDetails, $pdfPath));

            // Clean up the temporary PDF file after sending the email
            unlink($pdfPath);
            dd('Ticket email successfully send ...');
        } else {
            dd('payment not approved');
        }
    }
    public function getMakeapaid($orderId)
    {
        $order = Order::findOrFail($orderId);

        return response()->json([
            'name' => $order->name,
            'email' => $order->email,
            'payer_id' => $order->payerid,
            'payment_method' => $order->paymentmethod,
        ]);
    }
    public function postCheckOrderAndSendEmail(Request $request)
    {
        // dd($request->all());
        $order = Order::findOrFail($request->get('orderId'));
        $merchant_id = '244172845127423';
        $secret_key = '42815-932';
        $order_id = 'W-' . $order->id;
        $payer_id = $request->get('payer_id');
        // check payer_id already used or not
        $check_payer_id = Order::where('payerid', $payer_id)->where('id', '!=', $order->id)->exists();
        if ($check_payer_id == true) {
            dd('This payer id already used for another order');
        }
        $hash_string = $merchant_id . $secret_key . $payer_id;
        $hash = hash_hmac('SHA256', $hash_string, $secret_key);

        $response = Http::get('https://app.senangpay.my/apiv1/query_transaction_status', [
            'merchant_id' => $merchant_id,
            'transaction_reference' => $payer_id,
            'hash' => $hash
        ]);

        $result = $response->json();
        if ($response->successful() && $result['status'] == 1 && !empty($result['data'])) {
            $paymentInfo = $result['data'][0]['payment_info'] ?? [];
            if (!empty($paymentInfo) && $paymentInfo['status'] === 'paid') {
                if ($order->paymentstatus != 'Y') {
                    DB::table('orders')->where('id', $order->id)->update([
                        'paymentstatus' => 'Y',
                        'paymentmethod' => 'SanangPay',
                        'updated_at' => date('Y-m-d'),
                        'payerid' => $payer_id
                    ]);
                }
                $user = $order->user;
                $organizer_mail = $order->orderPackages()->first()->package->event->user->email ?? '';
                foreach ($order->orderPackages as $pack) {
                    $pack->package->increment('consumed_seat', $pack->quantity);

                    foreach ($pack->ticketUsers as $user) {
                        try {
                            $code = Str::uuid();
                            // Generate the QR code and get the path
                            $qrCodePath = QRCodeHelper::generateQrCode($code);
                            $user->update([
                                'qr_code' => $code,
                                'qr_image' => $qrCodePath, // Save the file path for later reference
                                'membership_no' => $this->getMembershipNumber(),
                            ]);
                        } catch (\Exception $e) {
                            Log::error('QR Code Generation Failed', [
                                'user_id' => $user->id,
                                'error' => $e->getMessage()
                            ]);
                        }
                    }
                }

                foreach ($order->orderPackages as $orderPackage) {
                    $data[$orderPackage->package->id] = [
                        'event' => $orderPackage->package->event->title,
                        'event_date' => $pack->package->event->date ?? null,
                        'event_time' => $pack->package->event->time,
                        'venue' => $pack->package->event->vennue,
                        'poster' => $pack->package->event->primary_photo,
                        'organizer_photo' => $pack->package->event->user->organizer->photo ?? null,
                        'organizer_name' => $pack->package->event->user->name ?? null,
                        'package' => $orderPackage->package->toArray(),
                        'ticket_users' => $orderPackage->ticketUsers->map(function ($ticketUser) {
                            return [
                                'name' => $ticketUser->name,
                                'seat_number' => $ticketUser->seat->row_label??''. $ticketUser->seat->seat_number??'',
                                'ticket_type' => $ticketUser->ticket_type,
                                'qr_code' => $ticketUser->qr_code,
                                'qr_image' => $ticketUser->qr_image,
                                'ic' => $ticketUser->ic,
                                'membership_no' => $ticketUser->membership_no
                            ];
                        })->toArray(),
                    ];
                }

                $purchaseDetails = [
                    'customer_name' => $user->name,
                    'data' => $data,
                    'total_tickets' => $order->orderPackages->sum('quantity'),
                    'total_price' => $order->servicecharge + $order->grandtotal,
                    'payment_status' => $order->paymentstatus == 'Y' ? 'Paid' : ($order->paymentstatus == 'R' ? 'Pending (Reserved)' : 'Failed'),
                    'payment_method' => $order->paymentstatus == 'N' ? 'N/A' : ($order->paymentmethod == 'SanangPay' ? 'online' : $order->paymentmethod),
                ];
                // dd($purchaseDetails);

                // Generate the PDF
                $pdf = Pdf::loadView('pdf.tickets', compact('purchaseDetails'));
                // Save the PDF temporarily
                $pdfPath = storage_path('app/public/tickets.pdf');
                $pdf->save($pdfPath);

                // return $purchaseDetails;
                Mail::to($order->email)
                    ->bcc(['greatticketmy@gmail.com', 'lalasathen@gmail.com', $organizer_mail])
                    ->send(new TicketPdfMail($purchaseDetails, $pdfPath));
                dd('Payment is successful. Order ID: ' . $order->id);
            }
        }
        dd('Payment is not successful or no data found.');
    }
}
