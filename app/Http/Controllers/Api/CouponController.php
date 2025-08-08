<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Package;
use App\Models\Order;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class CouponController extends BaseApiController
{
    public function index(Request $request)
    {
        
        $validatedData = Validator::make($request->all(), [
            'code' => 'required|string|max:255',
            'package_id' => 'required|exists:packages,id',
            'user_id' => 'required|exists:carts,user_id'
        ]);
        
        if ($validatedData->fails()) {
            return $this->sendError($validatedData->errors(), "Validation errors", 422);
        }
        $package = Package::where('id', $request->package_id)->first();
        $cartcost =  Cart::where('user_id', $request->user_id)->sum('cost');
        
        $coupon = Coupon::where('code', $request->code)->first();
        
        if (!$coupon) {
            return $this->sendError("Coupon doesn't exists", "Coupon not found", 404);
        }
        

        //countusecoupon
        $order = Order::where('coupon_id', $coupon->id)->where('paymentstatus', 'Y')->count();
        if($coupon->couponlimitation != Null){
            if ($order >= $coupon->couponlimitation) {
                return $this->sendError("Coupon limitation exist", "Coupon limitation exist", 404);
            }
        }
       
        if($coupon->coupontype == 'flat'){
            $discountcost = $coupon->cost;
            $dcost = 'RM '. $coupon->cost;
        }
        else{
            $discountcost = $cartcost*($coupon->cost/100);
            $dcost =  $coupon->cost.'%';
        }
        
        
        if ($coupon->expire_at < now()) {
            return $this->sendError("Coupon Expire", "Coupon Expire", 404);
        }
        if ($coupon->organizer_id != $package->event->organizer_id) {
            return $this->sendError("Coupon doesn't exists", "This coupon doesn't belong to this organizer", 404);
        }

        // return response()->json(['cost' => $coupon->cost, 'status' => true], 200);
        return $this->sendResponse(['discount' =>$discountcost, 'dcost' => $dcost],"Coupon discount fetched successfully",200);
    }
    
    public function mobile(Request $request){
        
        $user_id = $request->user_id;
        $validatedData = Validator::make($request->all(), [
            'code' => 'required|string|max:255',
            'package_id' => 'required|exists:packages,id',
        ]);

        if ($validatedData->fails()) {
            return $this->sendError($validatedData->errors(), "Validation errors", 422);
        }
        $package = Package::where('id', $request->package_id)->first();
        $cartcost = Cart::where('user_id', $request->user_id)->sum('cost');

        $coupon = Coupon::where('code', $request->code)->first();

        if (!$coupon) {
            return $this->sendError("Coupon doesn't exists", "Coupon not found", 404);
        }
        //countusecoupon
        $order = Order::where('coupon_id', $coupon->id)->where('paymentstatus', 'Y')->count();
        if($coupon->couponlimitation != Null){
            if ($order >= $coupon->couponlimitation) {
                return $this->sendError("Coupon limitation exist", "Coupon limitation exist", 404);
            }
        }
        
        $discountcost = 0;
       
        if($coupon->coupontype == 'flat'){
            $discountcost = $coupon->cost;
        }
        else{
            $discountcost = $cartcost*($coupon->cost/100);
        }

        
        if ($coupon->expire_at < now()) {
            return $this->sendError("Coupon Expire", "Coupon Expire", 404);
        }
        if ($coupon->organizer_id != $package->event->organizer_id) {
            return $this->sendError("Coupon doesn't exists", "This coupon doesn't belong to this organizer", 404);
        }

        // return response()->json(['cost' => $coupon->cost, 'status' => true], 200);
        return $this->sendResponse(['discount' =>$discountcost],"Coupon discount fetched successfully",200);
    }

    public function organizerBooking(Request $request){
        $user_id = $request->user_id;
        $validatedData = Validator::make($request->all(), [
            'code' => 'required|string|max:255',
            'package_id' => 'required|exists:packages,id',
            'quantity' => 'required|numeric|min:1',
        ]);

        if ($validatedData->fails()) {
            return $this->sendError($validatedData->errors(), "Validation errors", 422);
        }
        $package = Package::where('id', $request->package_id)->first();

        $coupon = Coupon::where('code', $request->code)->first();

        if (!$coupon) {
            return $this->sendError("Coupon doesn't exists", "Coupon not found", 404);
        }
        //countusecoupon
        $order = Order::where('coupon_id', $coupon->id)->where('paymentstatus', 'Y')->count();
        if($coupon->couponlimitation != Null){
            if ($order >= $coupon->couponlimitation) {
                return $this->sendError("Coupon limitation exist", "Coupon limitation exist", 404);
            }
        }
        
        $discountcost = 0;
       
        if($coupon->coupontype == 'flat'){
            $discountcost = $coupon->cost;
        }
        else{
            $discountcost = ($package->actual_cost * $request->quantity) * ($coupon->cost/100);
        }

        
        if ($coupon->expire_at < now()) {
            return $this->sendError("Coupon Expire", "Coupon Expire", 404);
        }
        if ($coupon->organizer_id != $package->event->organizer_id) {
            return $this->sendError("Coupon doesn't exists", "This coupon doesn't belong to this organizer", 404);
        }

        // return response()->json(['cost' => $coupon->cost, 'status' => true], 200);
        return $this->sendResponse(['discount' =>$discountcost],"Coupon discount fetched successfully",200);
    }
}
