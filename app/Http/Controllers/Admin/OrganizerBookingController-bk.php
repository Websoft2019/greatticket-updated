<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\QRCodeHelper;
use App\Http\Controllers\Controller;
use App\Mail\TicketPdfMail;
use App\Models\Coupon;
use App\Models\Event;
use App\Models\Order;
use App\Models\Package;
use App\Services\TicketBookingService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class OrganizerBookingController extends Controller
{
    private function getMembershipNumber()
    {
        // $latestMembership = DB::table('ticket_users')
        //     ->whereNotNull('membership_no')
        //     ->orderBy('id', 'desc')
        //     ->first();

        // if ($latestMembership && strpos($latestMembership->membership_no, '-') !== false) {
        //     $explode = explode('-', $latestMembership->membership_no);
        //     $nextNumber = isset($explode[1]) ? ((int) $explode[1] + 1) : 10078;
        // } else {
        //     $membershipStartFrom = 'TOP-10077';
        //     $explode = explode('-', $membershipStartFrom);
        //     $nextNumber = isset($explode[1]) ? ((int) $explode[1] + 1) : 10078;
        // }

        // return 'TOP-' . $nextNumber;

        
    }

    public function create()
    {
        $user = Auth::user();
        $organizer = $user->organizer;
        $events = Event::where('organizer_id', $user->id)->where('status', 1)->with('packages')->get();

        return view('pages.bookings.create', compact('events', 'organizer'));
    }

    public function store(Request $request, TicketBookingService $ticketBookingService)
    {
        $user = Auth::user();
        $organizer = $user->organizer;
        // dd($request->all());
        $request->validate([
            'customer_name' => 'nullable|string',
            'customer_email' => 'nullable|email',
            'booking_type' => 'required|in:normal,complementary,reserved',
            'package_id' => 'required|exists:packages,id',
            'quantity' => 'required|integer|min:1',
            'attendees' => 'required|array',
            'attendees.*' => 'required|string',
            'coupon_code' => 'nullable|string',
        ]);
        // dd($request->all());

        $package = Package::findOrFail($request->package_id);
        $event = $package->event;

        // Check if organizer owns the event
        if ($event->organizer_id !== $user->id) {
            abort(403, 'Unauthorized action.');
        }

        // Check capacity
        if ($package->consumed_seat + $request->quantity > $package->capacity) {
            return back()->with('error', 'Not enough seats available.');
        }

        // Check coupon validity
        $couponcost = 0;
        $servicecharge = 0.00;
        $coupon_id = null;
        $cartTotal = $package->actual_cost * $request->quantity;

        if ($request->booking_type == "complementary") {
            $cartTotal = 0;
            $grandTotal = 0;
        } else {

            if ($organizer->cm_type == 'percentage') {
                $servicecharge = $cartTotal * ($organizer->cm_value / 100);
            } else {
                $servicecharge = $organizer->cm_value * $request->quantity;
            }

            if ($request->filled('coupon_code')) {
                $coupon = Coupon::firstWhere('code', $request->coupon_code);
                $countusecoupon = Order::where('coupon_id', $coupon->id)->where('paymentstatus', 'Y')->count();
                if ($coupon->couponlimitation != Null) {
                    if ($countusecoupon >= $coupon->couponlimitation) {
                        notify()->error("Coupon limitation exist.");
                        return redirect()->back()->withErrors(['coupon_error' => "Coupon limitation exist"]);
                    }
                }
                // Coupon does not exist
                if (!$coupon) {
                    notify()->error("Coupon doesn't exist.");
                    return redirect()->back()->withErrors(['coupon_error' => "Coupon doesn't exist."]);
                }

                // Coupon is expired
                if ($coupon->expire_at < now()) {
                    notify()->error("Coupon has expired.");
                    return redirect()->back()->withErrors(['coupon_error' => "Coupon has expired."]);
                }

                // Coupon doesn't belong to the organizer
                if ($coupon->organizer_id != $package->event->organizer_id) {
                    notify()->error("This coupon doesn't belong to this organizer.");
                    return redirect()->back()->withErrors(['coupon_error' => "This coupon doesn't belong to this organizer."]);
                }

                // Discount exceeds the cart cost
                if (($cartTotal - $coupon->cost) < 0) {
                    notify()->error("Cannot use this coupon. Discount exceeds the actual cost.");
                    return redirect()->back()->withErrors(['coupon_error' => "Cannot use this coupon. Discount exceeds the actual cost."]);
                }
                if ($coupon->couponlimitation != Null) {
                    $ordercount = Order::where('coupon_id', $coupon->id)->where('paymentstatus', 'Y')->count();
                    if ($ordercount >= $coupon->couponlimitation) {
                        notify()->error("Coupon limitation exist");
                        return redirect()->back()->withErrors(['coupon_error' => "Coupon limitation exist."]);
                    }
                }

                if ($coupon->coupontype == 'flat') {
                    $couponcost = $coupon->cost;
                } else {
                    $couponcost = $cartTotal * ($coupon->cost / 100);
                }
            }

            // Calculate final total
            $grandTotal = $cartTotal - $couponcost;
        }

        try {
            DB::beginTransaction(); // Start transaction
            $code = Str::uuid();
            // Generate the QR code and get the path
            $qrCodePath = QRCodeHelper::generateQrCode($code);
            // Create order
            $order = Order::create([
                'user_id' => $user->id,
                'name' => $request->customer_name ?? 'customer',
                'email' => $request->customer_email ?? null,
                'phone' => $request->customer_phone ?? null,
                'carttotalamount' => $cartTotal,
                'servicecharge' => $servicecharge,
                'coupon_id' => $coupon_id,
                'discount_amount' => $couponcost,
                'grandtotal' => $grandTotal,
                'paymentmethod' => 'manual',
                'paymentstatus' => ($request->booking_type == 'reserved') ? 'R' : 'Y',
                'reserved_at' => ($request->booking_type == 'reserved') ? now() : null,
                'expires_at' => ($request->booking_type == 'reserved') ? now()->addHours(8) : null,
                'qr_code' => $code,
                'qr_image' => $qrCodePath,
            ]);

            // Link to package
            $orderPackage = $order->orderPackages()->create([
                'package_id' => $package->id,
                'quantity' => $request->quantity,
                'is_complementary' => ($request->booking_type == 'complementary') ? 1 : 0,
            ]);

            // handles the ticket booking part
            $ticketBookingService->handle($order, $orderPackage, $request);



            if ($request->customer_email) {
                // Eager load relationships
                $order->load('orderPackages.package.event', 'orderPackages.ticketUsers');
                $name = $request->customer_name ?? 'customer';

                $this->pdfMail($order, $name);
            }

            DB::commit(); // Commit transaction
            // return redirect()->route('organizer.booking.confirmation', ['order' => $order->id]);

            return redirect()->route('organizer.bookings.show', $order->id)->with('success', 'Booking created successfully.');
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback transaction on error
            Log::error('Checkout process failed', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'user_id' => auth()->id(),
                'request_data' => $request->all(),
            ]);
            return redirect()->back()->withErrors(['Something went wrong during checkout. Please try again later.']);
        }
    }

    public function show(Order $order)
    {
        $order->load('orderPackages.package.event', 'orderPackages.ticketUsers');

        $data = [];
        $totalTickets = 0;

        foreach ($order->orderPackages as $pack) {
            $totalTickets += $pack->quantity;

            $data[$pack->package->id] = [
                'event' => $pack->package->event->title,
                'package' => $pack->package,
                'ticket_users' => $pack->ticketUsers,
            ];
        }

        return view('pages.bookings.show', [
            'order' => $order,
            'data' => $data,
            'totalTickets' => $totalTickets
        ]);
    }

    public function downloadPdf(Order $order)
    {
        $order->load('orderPackages.package.event', 'orderPackages.ticketUsers');

        $data = [];
        $totalTickets = 0;

        foreach ($order->orderPackages as $pack) {
            $totalTickets += $pack->quantity;

            $data[$pack->package->id] = [
                'event' => $pack->package->event->title,
                'event_date' => $pack->package->event->date ?? null,
                'event_time' => $pack->package->event->time,
                'venue' => $pack->package->event->vennue,
                'package' => $pack->package,
                'ticket_users' => $pack->ticketUsers->map(function($user){
                    return [
                        'name' => $user->name,
                        'ic' => $user->ic,
                        'membership_no' => $user->membership_no,
                        'seat_no' => $user->seat_id ? ( $user->seat->row_label . $user->seat->seat_number) : null, 
                        'qr_code' => $user->qr_code,
                        'qr_image' => $user->qr_image,
                        'ticket_type' => $user->ticket_type,
                        'created_at' => $user->created_at,
                        'checkedin' => $user->checkedin,
                    ];
                }),
                'poster' => $pack->package->event->primary_photo,
                'organizer_photo' => $pack->package->event->user->organizer->photo ?? null,
                'organizer_name' => $pack->package->event->user->name ?? null,
            ];
        }

        $purchaseDetails = [
            'customer_name' => $order->name,
            'data' => $data,
            'total_tickets' => $totalTickets,
            'total_price' => $order->grandtotal,
        ];

        $pdf = Pdf::loadView('pdf.tickets', compact('purchaseDetails'));

        return $pdf->download('ticket-details.pdf');
    }



    public function confirmation(Order $order)
    {
        return view('pages.bookings.confirmation', compact('order'));
    }

    private function pdfMail($order, $name)
    {
        $data = [];
        $totalTickets = 0;
        $organizer_mail = Auth::user()->email ?? '';

        foreach ($order->orderPackages as $pack) {
            $totalTickets += $pack->quantity;

            $data[$pack->package->id] = [
                'event' => $pack->package->event->title,
                'event_date' => $pack->package->event->date ?? null,
                'event_time' => $pack->package->event->time,
                'venue' => $pack->package->event->vennue,
                'poster' => $pack->package->event->primary_photo,
                'organizer_photo' => $pack->package->event->user->organizer->photo ?? null,
                'organizer_name' => $pack->package->event->user->name ?? null,
                'package' => $pack->package->toArray(),

                'ticket_users' => $pack->ticketUsers->map(function ($user) {
                    return [
                        'name' => $user->name,
                        'ic' => $user->ic,
                        'membership_no' => $user->membership_no,
                        'seat_no' => $user->seat_id ? ( $user->seat->row_label . $user->seat->seat_number) : null, 
                        'qr_code' => $user->qr_code,
                        'qr_image' => $user->qr_image,
                        'ticket_type' => $user->ticket_type,
                        'created_at' => $user->created_at,
                        'checkedin' => $user->checkedin,
                    ];
                })->toArray(),
            ];
        }

        // Prepare purchase details
        $purchaseDetails = [
            'customer_name' => $name,
            'data' => $data,
            'total_tickets' => $totalTickets,
            'total_price' => $order->grandtotal,
        ];
        // Generate the PDF
        $pdf = Pdf::loadView('pdf.tickets', compact('purchaseDetails'));
        // Save the PDF temporarily
        $pdfPath = storage_path('app/public/tickets.pdf');
        $pdf->save($pdfPath);

        // return $purchaseDetails;
        Mail::to($order->email)
            ->bcc(['greatticketmy@gmail.com', $organizer_mail])
            ->send(new TicketPdfMail($purchaseDetails, $pdfPath));

        // Clean up the temporary PDF file after sending the email
        unlink($pdfPath);
    }
}
