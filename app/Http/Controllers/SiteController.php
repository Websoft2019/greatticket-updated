<?php

namespace App\Http\Controllers;

use App\Mail\ContactUsMail;
use App\Models\Cart;
use App\Services\SeatService;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Order;
use App\Models\OrderPackage;
use App\Models\Package;
use App\Models\Religion;
use App\Models\TicketUser;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Helpers\QRCodeHelper;
use App\Models\Carousel;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\ContactUs;
use App\Models\TrendingEvent;
use Carbon\Carbon;
use App\Models\Organizer;
use App\Models\Seat;
use Barryvdh\DomPDF\Facade\Pdf;

class SiteController extends Controller
{
    public function getComingSoon()
    {
        return view('site.comingsoon');
    }

    public function getHome()
    {
        $trending = TrendingEvent::orderBy('priority', 'desc')->limit(8)->get();
        $data = [
            'todayevent' => Event::where('status', true)->whereDate('date', date('Y-m-d'))->limit(1)->first(),
            'trendingevents' => $trending->pluck('event'),
            'events' => Event::where('date', '>=', now())->where('status', 1)->limit(8)->get(),
            // 'events' => Event::where(function ($query) {
            //     $query->where('date', '>', now()->toDateString())  // Future events
            //         ->orWhere(function ($query) {
            //             $query->where('date', now()->toDateString())  // Today's events
            //                 ->where('time', '>=', now()->toTimeString()); // Until the current time
            //         });
            // })
            //     ->orderBy('date', 'asc')
            //     ->orderBy('time', 'asc')
            //     ->limit(8)
            //     ->get(),
            // 'events' => Event::orderBy('date', 'asc')->get(),
            'categories' => Category::all(),
            'carousels' => Carousel::all(),

            'completed_events' => Event::where('status', true)->where(function ($query) {
                $query->where('date', '<', now()->toDateString())  // Future events
                    ->orWhere(function ($query) {
                        $query->where('date', now()->toDateString())  // Today's events
                            ->where('time', '<=', now()->toTimeString()); // Until the current time
                    });
            })
                // ->orderBy('date', 'asc')
                // ->orderBy('time', 'asc')
                ->orderBy("created_at", "desc")
                ->limit(8)
                ->get(),
        ];


        // dd($data);
        return view('site.home', $data);
    }

    public function events()
    {
        // $data = [
        //     'todayevent' => Event::whereDate('date', date('Y-m-d'))->limit(1)->first(),
        //     'events' => Event::where('date', '>=', now())->paginate(10),
        //     'categories' => Category::all(),
        // ];
        $completed_events = Event::where('status', true)->where(function ($query) {
            $query->where('date', '<', now()->toDateString())  // Future events
                ->orWhere(function ($query) {
                    $query->where('date', now()->toDateString())  // Today's events
                        ->where('time', '<=', now()->toTimeString()); // Until the current time
                });
        })
            // ->orderBy('date', 'asc')
            // ->orderBy('time', 'asc')
            ->orderBy("created_at", "desc")
            ->paginate(10);

        $events = Event::where('status', true)->where('date', '>=', now())->paginate(10);

        $categories = Category::all();

        //dd($completed_events);

        //dd($events);

        // return $events;
        return view('site.events', compact('events', 'categories', 'completed_events'));
    }

    public function searchEvents(Request $request)
    {
        $categoryId = $request->input('category');
        $date = $request->input('date');

        $query = Event::query();

        // Filter by category if one is selected
        if ($categoryId && $categoryId != -1) {
            $query->where('category_id', $categoryId);
        }

        // Filter by date if a date is provided
        if ($date) {
            $endDate = Carbon::parse($date)->addYear();
            $query->whereBetween('date', [$date, $endDate])->where('status', 1);
        }

        $events = $query->orderBy('date')->paginate(10);

        $todayevent = Event::where('status', true)->whereDate('date', date('Y-m-d'))->limit(1)->first();
        $completed_events = Event::where('status', true)->where(function ($query) {
            $query->where('date', '<', now()->toDateString())  // Future events
                ->orWhere(function ($query) {
                    $query->where('date', now()->toDateString())  // Today's events
                        ->where('time', '<=', now()->toTimeString()); // Until the current time
                });
        });

        // Pass categories and events to the view
        $categories = Category::all();
        return view('site.events', compact('events', 'categories', 'todayevent', 'categoryId', 'date', 'completed_events'));
    }
    public function getEventDetail($slug)
    {
        $event = Event::where('slug', $slug)->where('status', 1)->first();

        if (!$event) {
            abort(404); // or redirect()->back()->with('error', 'Event not found');
        }

        $loginstatus = Auth::check() ? 'true' : 'false';

        $data = [
            'event' => $event,
            'packages' => Package::where('event_id', $event->id)->where('status', true)->orderBy('actual_cost', 'asc')->get(),
            'loginstatus' => $loginstatus // used to show/hide login modal in site.eventdetail
        ];

        return view('site.eventdetail', $data);
    }

    public function postAddtoCart(Request $request, Package $package, $guest = false)
    {
        // dd($request->all());
        // Auth check
        if (!Auth::check()) {
            return redirect()->back();
        }

        // Validation
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        // Guest check (optional)
        if ($guest && !$this->guest()) {
            return redirect()->back();
        }

        $user = auth()->user();

        // Restrict to one event in cart
        $cartEventIds = Cart::where('user_id', $user->id)->distinct()->pluck('event_id');
        if ($cartEventIds->count() > 0 && !$cartEventIds->contains($package->event_id)) {
            notify()->error("Another event's package exists in the cart.");
            return redirect()->route('getCart');
        }

        // Prevent duplicate package
        $existingCart = Cart::where('user_id', $user->id)->where('package_id', $package->id)->first();
        if ($existingCart) {
            notify()->error("This package already exists in the Cart.");
            return redirect()->route('getCart');
        }

        // Check available capacity
        $available = $package->capacity - $package->consumed_seat;
        if ($request->quantity > $available) {
            notify()->error("Not enough seats available for this package.");
            return redirect()->route('getCart');
        }

        // Calculate cost & commission
        $event = Event::find($package->event_id);
        $org = Organizer::where('user_id', $event->organizer_id)->first();
        $totalCost = $request->quantity * $package->actual_cost;
        $cm = ($org->cm_type === 'percentage')
            ? ($totalCost * ($org->cm_value / 100))
            : ($org->cm_value * $request->quantity);

        DB::beginTransaction();

        try {
            // Create cart
            $cart = Cart::create([
                'user_id' => $user->id,
                'event_id' => $package->event_id,
                'package_id' => $package->id,
                'quantity' => $request->quantity,
                'cost' => $totalCost,
                'commision' => $cm,
            ]);

            // Handle seat reservation if seats are selected
            if ($request->has('seats')) {
                $seatArray = json_decode($request->seats, true);

                foreach ($seatArray as $seatInfo) {
                    // Parse the seat ID like "58_A5" → find by row and number
                    $row = $seatInfo['row'];
                    $number = $seatInfo['number'];

                    $seat = Seat::where('package_id', $package->id)
                        ->where('row_label', $row)
                        ->where('seat_number', $number)
                        ->where('status', 'available')
                        ->first();

                    if (!$seat) {
                        DB::rollBack();
                        notify()->error("One or more selected seats are already taken.");
                        return redirect()->route('getCart');
                    }

                    // Reserve the seat
                    $seat->update([
                        'status' => 'reserved',
                        'reserved_at' => now(),
                        'expires_at' => now()->addMinutes(15),
                    ]);

                    // Link seat to cart
                    $cart->seats()->attach($seat->id);
                }
            }

            DB::commit();
            notify()->success("Package added to cart successfully.");
        } catch (\Exception $e) {
            DB::rollBack();
            logger()->error('Add to cart failed: ' . $e->getMessage());
            notify()->error("Something went wrong.");
        }

        return redirect()->route('getCart');
    }

    public function getCart()
    {
        if (!Auth::check()) {
            return redirect()->route('getHome');
        }

        $user = auth()->user();
        $carts = Cart::with(['package', 'event', 'seats'])->where('user_id', $user->id)->get();

        if ($carts->isEmpty()) {
            return redirect()->route('getHome');
        }

        $totalAmount = $carts->sum('cost');
        $totalCommission = $carts->sum('commision');
        $event = $carts->first()->event;

        return view('site.cart', [
            'carts' => $carts,
            'totalamount' => $totalAmount,
            'totalcommision' => $totalCommission,
            'event' => $event
        ]);
    }


    public function cartDestroy($id)
    {
        $cart = Cart::with('seats')->where('user_id', auth()->id())->where('id', $id)->firstOrFail();

        // Release reserved seats
        foreach ($cart->seats as $seat) {
            $seat->update([
                'status' => 'available',
                'reserved_at' => null,
                'expires_at' => null,
            ]);
        }

        // Detach pivot and delete cart
        $cart->seats()->detach();
        $cart->delete();

        // If cart is now empty, redirect home
        $hasRemaining = Cart::where('user_id', auth()->id())->exists();

        return redirect()->route($hasRemaining ? 'getCart' : 'getHome');
    }


    public function getUserLogin()
    {
        return view('site.login');
    }
    public function getCheckout(SeatService $seatService)
    {
        // if ($code == Session::get('cartcode')) {
        if (auth()->check()) {
            $order = Order::where('user_id', auth()->id())->where('paymentstatus', 'N')->limit(1)->first();
            $carts = Cart::with(['seats', 'package'])->where('user_id', auth()->id())->get();

            if ($carts->count() == 0) {
                notify()->error('Cart is empty');
                return redirect()->route('getCart');
            }

            $allSeats = $carts->flatMap->seats;

            $result = $seatService->validateAndExtendExpiringSeats($allSeats);

            if (count($result['expired']) > 0) {
                notify()->error('Some seats have expired. Please review your cart.');
                return redirect()->route('getCart');
            }

            if (count($result['extended']) > 0) {
                notify()->warning('Some seats were about to expire. We\'ve extended them for a few more minutes.');
            }

            $data = [
                'carts' => $carts,
                'carttotal' => Cart::where('user_id', auth()->id())->sum('cost'),
                'order' => $order,
                'noOfTicket' => $carts->sum('quantity'),
                'totalcommision' => Cart::where('user_id', auth()->user()->id)->sum('commision'),
            ];
            return view('site.checkout', $data);
        } else {
            abort('404');
        }
    }
    public function postCheckout(Request $request)
    {

        if (auth()->check()) {

            $userId = auth()->id();

            $carts = Cart::where('user_id', $userId)->get();

            // $event = Event::where('id', $cart->event_id)->first();
            // session()->put('eventname', $event->title);

            if ($carts->isEmpty()) {
                notify()->error('Cart not found');
                return redirect()->back()->withErrors(['Cart not found']);
            }

            $firstPackage = $carts->first()->package; // Since all carts are tied to the same event, get the first package
            session()->put('eventTitle',  $firstPackage->event->title);

            $carttotalamount = Cart::where('user_id', $userId)->sum('cost');
            $carttotalcommision = Cart::where('user_id', $userId)->sum('commision');

            $validatedata = [
                'name' => 'required|max:255',
                'email' => 'required|email',
                'phone' => 'required',
            ];

            foreach ($carts as $cart) {
                $package = $cart->package;
                $available = $package->capacity - $package->consumed_seat;
                if ($cart->quantity > $available) {
                    notify()->error('Exceeds the available seat');
                    return redirect()->back()->with('error', 'Exceeds the available seat');
                }
                for ($i = 0; $i < ($cart->quantity * $cart->package->maxticket); $i++) {
                    $validatedata[('package-' . $cart->package_id . '-name-' . $i)] = 'required|max:255';
                    // $validatedata[('package-' . $cart->package_id . '-ic-' . $i)] = 'required|max:255';
                }
            }

            $request->validate($validatedata);

            $servicecharge = 0.00;
            $couponcost = 0.00;
            try {
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
                    if (($carttotalamount - $coupon->cost) < 0) {
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
                        $couponcost = $carttotalamount * ($coupon->cost / 100);
                    }
                }

                DB::beginTransaction(); // Start transaction

                if (auth()->check() && auth()->user()->session_id) {
                    auth()->user()->update([
                        // 'email' => $request->email,
                        'contact' => $request->phone,
                    ]);
                }

                $code = Str::uuid();
                // Generate the QR code and get the path
                $qrCodePath = QRCodeHelper::generateQrCode($code);

                // Create order
                $order = Order::create([
                    'user_id' => $userId,
                    'name' => $request->input('name'),
                    'address' => $request->input('address'),
                    'country' => $request->input('country'),
                    'city' => $request->input('city'),
                    'state' => $request->input('state'),
                    'postcode' => $request->input('postcode'),
                    'email' => $request->input('email'),
                    'phone' => $request->input('phone'),
                    'carttotalamount' => $carttotalamount,
                    'servicecharge' => $carttotalcommision,
                    'coupon_id' => $coupon->id ?? null,
                    'grandtotal' => ($carttotalamount) - $couponcost,
                    'paymentmethod' => $request->input('paymentmethod'),
                    'paymentstatus' => 'R',
                    'reserved_at' => now(),
                    'expires_at' => now()->addMinutes(10),
                    'discount_amount' => $couponcost,
                    'qr_code' => $code,
                    'qr_image' => $qrCodePath,
                ]);

                // $package->consumed_seat += $cartqtycount;
                // $package->save();

                // Create order package
                foreach ($carts as $crt) {
                    $order_package = OrderPackage::create([
                        'order_id' => $order->id,
                        'package_id' => $crt->package_id,
                        'quantity' => $crt->quantity,
                    ]);

                    $package = $order_package->package;
                    // Increment consumed seat count
                    $package->increment('consumed_seat', $order_package->quantity);

                    // Create ticket users for each cart item
                    for ($i = 0; $i < ($crt->quantity * $crt->package->maxticket); $i++) {
                        $name = $request->input('package-' . $crt->package_id . '-name-' . $i);
                        $ic = $request->input('package-' . $crt->package_id . '-ic-' . $i);

                        $seat = $crt->seats[$i] ?? null;

                        TicketUser::create([
                            'order_package_id' => $order_package->id,
                            'name' => $name,
                            'ic' => $ic,
                            'seat_id' => $seat?->id,
                        ]);
                    }
                }

                // Delete the cart after processing
                Cart::where('user_id', $userId)->delete();
                Session::put('order_id', $order->id);
                DB::commit(); // Commit transaction

                return redirect()->route('getconfirm');
            } catch (\Exception $e) {
                DB::rollBack(); // Rollback transaction on error
                Log::error('Checkout process failed', [
                    'error' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'user_id' => auth()->id(),
                    'request_data' => $request->all(),
                ]);
                notify()->error('Something went wrong during checkout. Please try again later.');
                return redirect()->back()->withErrors(['Something went wrong during checkout. Please try again later.']);
            }
        } else {
            abort(404);
        }
    }

    public function viewConfirm()
    {
        $order_id = Session::get('order_id');
        $data = [
            'order' => Order::where('id', $order_id)
                ->with(['OrderPackages.ticketUsers', 'OrderPackages.package'])
                ->firstOrFail(),
            'totalcommision' => Cart::where('user_id', auth()->user()->id)->sum('commision'),
        ];

        // return $order;
        return view('site.view-confirm', $data);
    }

    public function updateConfirm(Request $request, $id)
    {
        // Find the order by its ID
        $order = Order::findOrFail($id);

        // Validate the incoming request
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            // Add validation for the tickets if necessary
        ]);


        // Update the order with the validated data
        $order->update([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'phone' => $validatedData['phone'],
        ]);

        // If you have additional logic to update related items (like tickets), you can loop through them here
        // Example:
        foreach ($order->orderPackages as $orderItem) {
            foreach ($orderItem->ticketUsers as $index => $ticketUser) {
                $ticketUser->update([
                    'name' => $request->input('package-' . $orderItem->package_id . '-name-' . $index),
                    'ic' => $request->input('package-' . $orderItem->package_id . '-ic-' . $index)
                ]);
            }
        }

        // Redirect to the confirmation page or wherever you need to go
        return redirect()->route('getconfirm')->with('success', 'Order details updated successfully');
    }

    public function getconfirm(Request $request, SeatService $seatService)
    {
        if ($request->filled('order_id') && $request->filled('event_name')) {
            session()->put('order_id', $request->input('order_id'));
            session()->put('eventTitle', $request->input('event_name'));
        }
        $order = Order::where('id', Session::get('order_id'))->where('paymentstatus', 'R')->first();
        if (!$order || ($order->expires_at && $order->expires_at->isPast())) {
            notify()->error('Your payment session has expired');
            return redirect()->route('getHome');
        }
        if (auth()->check() and auth()->user()->id == $order->user_id) {

            $ticket_users = $order->orderPackages->flatMap->ticketUsers;

            $seats = $ticket_users->map(function ($user) {
                return $user->seat;
            })->filter(); // Removes any nulls

            $result = $seatService->validateAndExtendExpiringSeats($seats);

            if (count($result['expired']) > 0) {
                notify()->error('Some reserved seats have expired. Please return to cart.');
                return redirect()->route('getCart');
            }

            if (count($result['extended']) > 0) {
                notify()->warning('Some seats were about to expire. We’ve extended them for a few more minutes.');
            }

            $eventname = session()->get('eventTitle');
            session()->forget('eventTitle');
            $expiresAt = Carbon::parse($order->expires_at);
            $remainingSeconds = max(0, $expiresAt->diffInSeconds(now()));
            if ($order) {
                $data = [
                    'order' => $order,
                    'remainingSeconds' => $remainingSeconds,
                    'cart' => Cart::where('user_id', auth()->id())->get(),
                    'eventname' => $eventname,
                ];
                return view('site.confirm', $data);
            } else {
                abort('404');
            }
        } else {
            abort('404');
        }
    }


    public function guest()
    {
        try {
            $session_id = Session::get('id') ?? Str::uuid();
            $email = Str::uuid() . ".greatticket@gmail.com";
            $password = Str::random(16);
            $religion = Religion::firstOrFail();
            $user = User::create([
                'session_id' => $session_id,
                'name' => 'Guest User',
                'email' => $email,
                'password' => $password,
                'religion_id' => $religion->id,
            ]);
            Auth::login($user);
            return true;
        } catch (Exception $e) {
            Log::alert($e->getMessage());
            return false;
        }
    }

    //about us
    public function getAboutUs()
    {
        return view('site.about-us');
    }

    //contact us
    public function getContactUs()
    {
        return view('site.contact-us');
    }

    //send email
    public function postSendEmail(Request $request)
    {

        $data = $request->all();
        //echo "<pre>"; print_r($data); die;
        $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email',
            'contact' => 'required|max:15',
            'subject' => 'required|max:255',
            'message' => 'required',
        ]);



        try {
            // echo "<pre>"; print_r($data); die;

            $adminEmail = env('MAIL_USERNAME');
            Mail::to($adminEmail)->send(new ContactUsMail($data));

            $contact_us = new ContactUs();
            $contact_us->name = $data['name'];
            $contact_us->email = $data['email'];
            $contact_us->contact = $data['contact'];
            $contact_us->subject = $data['subject'];
            $contact_us->message = $data['message'];
            $contact_us->save();


            notify()->success("Email sent successfully");
            return redirect()->back();
        } catch (Exception $e) {
            Log::alert($e->getMessage());
            notify()->error('Failed to send email');
            return redirect()->back();
        }
    }

    //update cart qty
    public function updateCart(Request $request, $id = null)
    {

        if ($request->isMethod('post')) {
            $data = $request->all();
            // echo "<pre>"; print_r($data); die;
            $event_id = Cart::select('event_id')->where('id', $id)->first();

            // dd($event_id);

            $event = Event::where('id', $event_id->event_id)->first();
            //    if($event)
            //    {
            //      dd($event->organizer_id);
            //    }
            $org = Organizer::where('user_id', $event['organizer_id'])->limit(1)->first();

            $totalcost1 = $data['cart_qty'] * $data['actual_cost'];
            //dd($totalcost1);
            if ($org->cm_type == 'percentage') {
                $cm = $totalcost1 * ($org->cm_value / 100);
            } else {
                $cm = $org->cm_value * $request->quantity;
            }

            // dd($cm);

            $totalcost = $cm + $totalcost1;

            $total_cost = $data['cart_qty'] * $data['actual_cost'];

            Cart::where('id', $id)->update(['quantity' => $data['cart_qty'], 'cost' => $total_cost, 'commision' => $cm]);

            return redirect()->back()->with('success', 'Cart updated successfully');
        }
    }
    public function getTest(Order $order)
    {
        $user = $order->user;
        $reservationinfo = $order;
        $data = [];
        foreach ($order->orderPackages as $orderPackage) {
            $data[$orderPackage->package->id] = [
                'event' => $orderPackage->package->event->title,
                'package' => $orderPackage->package->toArray(),
                'ticket_users' => $orderPackage->ticketUsers->map(function ($ticketUser) {
                    return [
                        'name' => $ticketUser->name,
                        'seat_number' => $ticketUser->seat_number,
                        'qr_image' => $ticketUser->qr_image,
                        'ic' => $ticketUser->ic,
                        'membership_no' => $ticketUser->membership_no
                    ];
                })->toArray(),
            ];
        }

        $purchaseDetails = [
            'customer_name' => $user->name,
            'ic_number' => $user->ic,
            'data' => $data,
            'total_tickets' => $order->orderPackages->sum('quantity'),
            'total_price' => $order->grandtotal,
            'service_charge' => $order->servicecharge
        ];
        // dd($order->servicecharge+$order->grandtotal);
        $pdf = Pdf::loadView('pdf.tickets', compact('purchaseDetails'));
        // Save the PDF temporarily
        $pdfPath = storage_path('app/public/tickets.pdf');
        $pdf->save($pdfPath);
        // dd($pdfPath);
        return view('emails.ticket-purchase', ['data' => $data, 'pdfPath' => $pdfPath, 'customer_name' => $user->name, 'ic_number' => $user->ic, 'total_tickets' => $order->orderPackages->sum('quantity'), 'total_price' => $order->servicecharge + $order->grandtotal]);
    }
}
