<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CartResource;
use App\Models\Cart;
use App\Models\Package;
use App\Models\Event;
use App\Models\Seat;
use App\Notifications\UserActionNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class CartController extends BaseApiController
{
    public function index()
    {
        $user = auth()->user();
        try {
            $items = Cart::where('user_id', $user->id)->with('package')->get();
            $count = $items->count();
            $totalCost = number_format($items->sum('cost'), 2, '.', '');
            $sst = $items->sum('commision');
            $carts = CartResource::collection($items);
            $data = ['carts' => $carts, 'totalCost' => $totalCost, 'count' => $count, 'sst' => number_format($sst, 2, '.', '')];
            return $this->sendResponse($data, "Carts fetched successfully", 200);
        } catch (\Exception $e) {
            Log::error("Error while fetching cart items. \nError => " . $e->getMessage(), [
                'user_id' => $user->id,
            ]);
            return $this->sendError($e->getMessage(), 'Failed to fetch cart data', 500);
        }
    }

    public function store(Request $request)
    {
        // return response()->json($request->all());
        // return response()->json($request->has('seat'));
        $user = auth()->user();
        // Define validation rules
        $validator = Validator::make($request->all(), [
            'event_id' => 'required|exists:events,id',
            'package_id' => 'required|exists:packages,id',
            'quantity' => 'required|integer|min:1',

            'cost' => 'required|numeric|min:0',
            'seats' => 'sometimes|array',
            'seats.*' => 'sometimes|exists:seats,id',
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->errors(), "Validation fails", 422);
        }

        $cart = Cart::where('user_id', $user->id)->distinct()->pluck('event_id');
        $cartCount = $cart->count();
        // Prevent to add another event package
        if ($cartCount > 0 && (!$cart->contains($request->event_id))) {
            return $this->sendError("Another event's package exists in the cart", "Cart isn't empty", 422);
        }

        $tempackage = Cart::where('user_id', $user->id)->where('package_id', $request->package_id)->first();
        // Package already exists
        if ($tempackage) {
            return $this->sendError("This package already exists in Cart", "Package already exists", 422);
        }

        $package = Package::where('id', $request->package_id)->first();
        $totalcost = $request->quantity * $package->actual_cost;

        if ($request->cost != $totalcost) {
            return $this->sendError("The total cost doesn't match with the actual total cost", "Incorrect data", 422);
        }

        $available = $package->capacity - $package->consumed_seat;
        if ($request->quantity > $available) {
            return $this->sendError("Seat not available", "Exceeds the available seat", 500);
        }

        // check commision
        $event = Event::with('user.organizer')->find($package->event_id);
        $org = optional($event->user)->organizer;

        $totalcost1 = $request->quantity * $package->actual_cost;
        if ($org->cm_type == 'percentage') {
            $cm = $totalcost1 * ($org->cm_value / 100);
        } else {
            $cm = $org->cm_value * $request->quantity;
        }

        // $totalcost = $cm + $totalcost1;

        // All checks pass, add package to the cart (example, adjust as needed)
        DB::beginTransaction();

        try {
            // Create a new cart entry
            $cart = Cart::create([
                "user_id" => $user->id,
                "event_id" => $request->event_id,
                "package_id" => $request->package_id,
                "quantity" => $request->quantity,
                "cost" => $totalcost,
                "commision" => $cm,
            ]);

            if ($request->has('seats')) {
                $seats = $request->seats;
                foreach ($seats as $data) {
                    $seat = Seat::where('id', $data)->where('status', 'available')->first();

                    if (!$seat) {
                        DB::rollBack();
                        notify()->error("One or more selected seats are already taken.");
                        return $this->sendError('One or more selected seats are already taken.', [], 404);
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

            // Send the notification
            $message = "Package added to the cart";
            $user->notify(new UserActionNotification($message));
            DB::commit();

            return $this->sendResponse($cart, "Cart item added successfully", 201);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error while adding to cart", [
                'user_id' => $user->id,
                'event_id' => $request->event_id,
                'package_id' => $request->package_id,
                'quantity' => $request->quantity,
                'cost' => $request->cost,
                'error' => $e->getMessage(),
            ]);
            return $this->sendError($e->getMessage(), "Error while adding item in cart", 500);
        }
    }

    public function destroy($id)
    {
        $user = auth()->user();

        try {
            // Find the cart item by ID and user_id to ensure ownership
            $cart = Cart::with('seats')->where('user_id', $user->id)->where('id', $id)->first();

            // Check if the cart item exists
            if (!$cart) {
                return $this->sendError('Cart item not found or you do not have permission to delete this item.', 'Not Found', 404);
            }

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

            // Delete the cart item
            $cart->delete();

            $message = "Item removed from the cart";
            $user->notify(new UserActionNotification($message));

            return $this->sendResponse(null, 'Cart item removed successfully', 200);
        } catch (\Exception $e) {
            Log::error("Error while removing cart item. \nError => " . $e->getMessage(), [
                'user_id' => $user->id,
                'cart_id' => $id,
                'error' => $e->getMessage(),
            ]);

            return $this->sendError($e->getMessage(), "Error while removing item from cart", 500);
        }
    }
}
