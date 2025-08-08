<?php

namespace App\Http\Controllers;

use App\Mail\ContactUsMail;
use App\Models\Cart;
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
        $loginstatus = Auth::check() ? 'true' : 'false';
        if($loginstatus == 'false'){
            return redirect()->back();
        }
        $request->validate([
            'quantity' => 'required|min:1',
        ]);
        if ($guest) {
            $gStatus =  $this->guest();

            if (!$gStatus) {
                return redirect()->back();
            }
        }
        $user = auth()->user();

        // Get distinct event IDs in the cart for this user
        $cart = Cart::where('user_id', $user->id)->distinct()->pluck('event_id');
        $cartCount = $cart->count();

        // Prevent adding another event's package
        if ($cartCount > 0 && (!$cart->contains($package->event_id))) {
            notify()->error("Another event's package exists in the cart. Cart isn't empty.");
            return redirect()->route('getCart')->with('error', "Another event's package exists in the cart. Cart isn't empty.");
        }

        // Check if the package already exists in the cart
        $tempackage = Cart::where('user_id', $user->id)->where('package_id', $package->id)->first();
        if ($tempackage) {
            notify()->error("This package already exists in the Cart.");
            return redirect()->route('getCart')->with('error', "This package already exists in the Cart.");
        }

        

        // Check if enough seats are available
        $available = $package->capacity - $package->consumed_seat;
        if ($request->quantity > $available) {
            notify()->error("Not enough seats available for this package.");
            return redirect()->route('getCart')->with('error', "Not enough seats available for this package.");
        }
        // check commision
            $event = Event::find($package->event_id);
            $org = Organizer::where('user_id', $event->organizer_id)->limit(1)->first();
            
            $totalcost1 = $request->quantity * $package->actual_cost;
            if($org->cm_type == 'percentage'){
                $cm = $totalcost1 * ($org->cm_value / 100);
            }
            else{
                $cm = $org->cm_value * $request->quantity;
            }
            // $totalcost = $cm+$totalcost1;
            $totalcost = $totalcost1;
            
        // All checks pass, add package to the cart (example, adjust as needed)
        Cart::create([
            'user_id' => $user->id,
            'event_id' => $package->event_id,
            'package_id' => $package->id,
            'quantity' => $request->quantity,
            'cost' => $totalcost,
            'commision' => $cm,
        ]);

        return redirect()->route('getCart');
    }

    public function getCart()
    {
        if(Auth::check()){
            $cart1 = Cart::where('user_id', auth()->user()->id)->limit(1)->first();
            if($cart1){
                $data = [
                    'carts' => Cart::where('user_id', auth()->user()->id)->get(),
                    'totalamount' => Cart::where('user_id', auth()->user()->id)->sum('cost'),
                    'totalcommision' => Cart::where('user_id', auth()->user()->id)->sum('commision'),
                    'event' => Event::find($cart1->event_id)
                ];

                return view('site.cart', $data);
            }
            else{
                return redirect()->route('getHome');
            }
        }
        else{
            return redirect()->route('getHome');
        }
    }

    public function cartDestroy($id)
    {
        
        $cart = Cart::where('user_id', auth()->user()->id)->where('id', $id)->firstOrFail();
        $cart->delete();
        $cart2 = Cart::where('user_id', auth()->user()->id)->where('id', $id)->get();
        if($cart2->count()){
            return redirect()->route('getCart');
        }
        else{
            return redirect()->route('getHome');
        }
    }

    public function getUserLogin()
    {
        return view('site.login');
    }
    public function getCheckout()
    {
        // if ($code == Session::get('cartcode')) {
        if (auth()->check()) {
            $order = Order::where('user_id', auth()->id())->where('paymentstatus', 'N')->limit(1)->first();
            $carts = Cart::where('user_id', auth()->id())->get();
            
            if ($carts->count() == 0) {
                notify()->error('Cart is empty');
                return redirect()->route('getCart');
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
                // 'address' => 'required|max:255',
                // 'country' => 'required|max:255',
                // 'state' => 'required|max:255',
                // 'city' => 'required|max:255',
                // 'postcode' => 'required|max:255',
                // 'email' => 'required|email|unique:users,email,' . $userId,
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
                for($i = 0; $i < ($cart->quantity*$cart->package->maxticket); $i++) {
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
                    if($coupon->couponlimitation != Null){
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
                    if($coupon->couponlimitation != Null){
                        $ordercount = Order::where('coupon_id',$coupon->id)->where('paymentstatus', 'Y')->count();
                        if ($order >= $coupon->couponlimitation) {
                            notify()->error("Coupon limitation exist");
                            return redirect()->back()->withErrors(['coupon_error' => "Coupon limitation exist."]);
                        }
                    }

                    if($coupon->coupontype == 'flat'){
                        $couponcost = $coupon->cost;
                    }
                    else{
                        $couponcost = $carttotalamount*($coupon->cost/100);
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

                    // Create ticket users for each cart item
                    for ($i = 0; $i < ($crt->quantity*$crt->package->maxticket); $i++) {
                        $name = $request->input('package-' . $crt->package_id . '-name-' . $i);
                        $ic = $request->input('package-' . $crt->package_id . '-ic-' . $i);

                        TicketUser::create([
                            'order_package_id' => $order_package->id,
                            'name' => $name,
                            'ic' => $ic,
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
        $data=[
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
            // 'address' => 'required|string|max:255',
            // 'country' => 'required|string|max:255',
            // 'state' => 'required',
            // 'city' => 'required|string|max:255',
            // 'postcode' => 'required|string|max:10',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            // Add validation for the tickets if necessary
        ]);


        // Update the order with the validated data
        $order->update([
            'name' => $validatedData['name'],
            // 'address' => $validatedData['address'],
            // 'country' => $validatedData['country'],
            // 'state' => $validatedData['state'],
            // 'city' => $validatedData['city'],
            // 'postcode' => $validatedData['postcode'],
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

    public function getconfirm(Request $request)
    {
        if ($request->filled('order_id') && $request->filled('event_name')) {
            session()->put('order_id', $request->input('order_id'));
            session()->put('eventTitle', $request->input('event_name'));
        }
        $order = Order::findOrFail(Session::get('order_id'));
        if (auth()->check() AND auth()->user()->id == $order->user_id) {
            
            $eventname = session()->get('eventTitle');
            session()->forget('eventTitle');
            if ($order) {
                $data = [
                    'order' => $order,
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

        if($request->isMethod('post'))
        {
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
            if($org->cm_type == 'percentage'){
                $cm = $totalcost1 * ($org->cm_value / 100);
            }
            else{
                $cm = $org->cm_value * $request->quantity;
            }

            // dd($cm);

            $totalcost = $cm+$totalcost1;

            $total_cost = $data['cart_qty'] * $data['actual_cost'];

            Cart::where('id', $id)->update(['quantity' => $data['cart_qty'], 'cost' => $total_cost, 'commision' => $cm]);

           return redirect()->back()->with('success', 'Cart updated successfully');
        }

    }
    public function getTest(Order $order){
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
        return view('emails.ticket-purchase', ['data' => $data, 'pdfPath' => $pdfPath, 'customer_name' =>$user->name, 'ic_number' =>$user->ic, 'total_tickets' => $order->orderPackages->sum('quantity'), 'total_price' => $order->servicecharge+$order->grandtotal ]);
    }
   
}
