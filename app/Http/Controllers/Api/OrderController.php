<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TicketUserResource;
use App\Mail\TicketPurchaseMail;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderPackage;
use App\Models\Package;
use App\Models\TicketUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Notifications\UserActionNotification;
use Illuminate\Support\Facades\Mail;
use App\Helpers\QRCodeHelper;
use App\Http\Resources\PaymentHistoryResource;
use App\Models\Coupon;
use Illuminate\Support\Facades\Log;

class OrderController extends BaseApiController
{
    public function store(Request $request)
    {
        $rules = [
            // 'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'postcode' => 'nullable|numeric|max:10',
            'email' => 'required|email|max:255',
            'phone' => 'sometimes|string|max:20',
            'carttotalamount' => 'required|numeric',
            'coupon_code' => 'nullable|string|max:255',
        ];

        $userId = auth()->id();
        $cart = Cart::where('user_id', $userId)->get();

        if ($cart->isEmpty()) {
            return $this->sendError("Data not available in cart", "Cart is empty", 404);
        }

        $cost = 0.0;
        foreach ($cart as $crt) {
            $cost += $crt->cost;
            for ($i = 0; $i < ($crt->quantity * $crt->package->maxticket); $i++) {
                $rules['package-' . $crt->package_id . '-name-' . $i] = 'required|string|max:255'; // Validation for dynamically named fields
                //  $rules['package-' . $crt->package_id . '-ic-' . $i] = 'required|string|max:255'; // Validation for dynamically ic fields
            }
        }

        // Perform the validation
        $validator = Validator::make($request->all(), $rules);

        // Check if validation fails
        if ($validator->fails()) {
            return $this->sendError($validator->errors(), "Validation errors", 422);
        }

        $servicecharge = $cart->sum('commision');
        $couponcost = 0;
        $cartcost = $cart->sum('cost');

        // $package = Package::where('id', $cart->package_id)->first();
        $organizer_id = $cart->first()->event->organizer_id;
        if ($request->coupon_code != '') {
            $coupon = Coupon::where('code', $request->coupon_code)->first();

            //countusecoupon
            $order = Order::where('coupon_id', $coupon->id)->where('paymentstatus', 'Y')->count();
            if ($coupon->couponlimitation != Null) {
                if ($order >= $coupon->couponlimitation) {
                    return $this->sendError("Coupon limitation exist", "Coupon limitation exist", 404);
                }
            }

            if ($coupon->coupontype == 'flat') {
                $discountcost = $coupon->cost;
            } else {
                $discountcost = $cartcost * ($coupon->cost / 100);
            }

            if (!$coupon) {
                return $this->sendError("Coupon doesn't exists", "Coupon not found", 404);
            }
            if ($coupon->expire_at < now()) {
                return $this->sendError("Coupon Expire", "Coupon Expire", 404);
            }
            if ($coupon->organizer_id != $organizer_id) {
                return $this->sendError("Coupon doesn't exists", "This coupon doesn't belong to this organizer", 404);
            }
            if (($cartcost - $discountcost) < 0) {
                return $this->sendError("Cannot use this coupon", "Discount exceed the actual cost", 403);
            }
            $couponcost = $discountcost;
        }
        // $grandtotal = $request->carttotalamount + $servicecharge - $couponcost;
        $grandtotal = $cost - $couponcost;
        $code = Str::uuid()->toString();
        DB::beginTransaction();
        try {

            // if (auth()->check() && auth()->user()->session_id) {
            //     auth()->user()->update([
            //         'email' => $request->email,
            //     ]);
            // }

            foreach ($cart as $crt) {
                $package = Package::where('id', $crt->package_id)->first();
                $cartqtycount = $crt->quantity;
                $available = $package->capacity - $package->consumed_seat;
                if ($cartqtycount > $available) {
                    return $this->sendError("Seat not available", "Exceeds the available seat of package " . $package->title, 500);
                }
            }
            $code = Str::uuid();
            // Generate the QR code and get the path
            $qrCodePath = QRCodeHelper::generateQrCode($code);

            // Your order creation logic
            // Create new order with the generated code
            $order = Order::create([
                'user_id' => $userId,
                'carttotalamount' => $cost,
                'servicecharge' => $servicecharge,
                'discount_amount' => $couponcost,
                'coupon_id' => $coupon->id ?? null,
                'grandtotal' => $grandtotal,
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'qr_code' => $code,
                'qr_image' => $qrCodePath,
            ]);

            // Create order package
            foreach ($cart as $crt) {
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

            DB::commit();

            $message = "You have placed an order";
            $user = auth()->user();
            if (isset($user)) {
                $user->notify(new UserActionNotification($message));
            }

            return $this->sendResponse($order, "Order created successfully", 201);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Order creation failed: ' . $e->getMessage() . ' in file ' . $e->getFile() . ' on line ' . $e->getLine());
            return $this->sendError($e->getMessage(), "Order creation failed!", 500);
        }
    }

    public function history()
    {
        $user = auth()->user();
        // if($user->session_id){
        //     return response()->json([]);
        // }
        $orders = Order::where('user_id', $user->id)->where('paymentstatus', 'Y')->get();
        $orders = PaymentHistoryResource::collection($orders);
        return $this->sendResponse($orders, "History fetched successfully", 200);
    }

    public function historyDetails($id)
    {
        $user = auth()->user();
        try {
            $order = Order::where('id', $id)->where('user_id', $user->id)->where('paymentstatus', 'Y')->first();
            if (empty($order)) {
                return $this->sendError("Data not found", "History not available", 404);
            }
            $orderResource = new PaymentHistoryResource($order);
            // return response()->json($orderResource);
            $users = $order->orderPackages->flatMap(function ($orderPackage) {
                return $orderPackage->ticketUsers()->with('orderPackage.package')->get();
            });
            $users = TicketUserResource::collection($users);


            $response = [
                'order' => $orderResource,
                'users' => $users,
            ];

            return $this->sendResponse($response, "Order and names fetched successfully", 200);
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), "Failed fetching history", 500);
        }
    }

    public function qrResponse($id)
    {
        try {
            $order = Order::where('id', $id)->first();
            if ($order->paymentstatus == 'N') {
                return $this->sendError("Payment Failed", "Payment not complete", 402);
            }
            $qr = config('app.url') . '/storage/' . $order->qr_image;
            return $this->sendResponse(['qr' => $qr], "QR Code fetched", 200);
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), "Failed fetching error", 500);
        }
    }

    public function purchase()
    {

        $purchaseDetails = [
            'customer_name' => 'John Doe',
            'event_name' => 'Concert 2024',
            'tickets' => [
                ['name' => 'VIP Ticket', 'quantity' => 2, 'price' => 100],
                ['name' => 'General Admission', 'quantity' => 1, 'price' => 50],
            ],
            'total_tickets' => 3,
            'total_price' => 250
        ];

        Mail::to('customer@example.com')->send(new TicketPurchaseMail($purchaseDetails));
    }
}
