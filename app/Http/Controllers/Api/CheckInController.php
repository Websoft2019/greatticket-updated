<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderPackageResource;
use App\Models\Order;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class CheckInController extends BaseApiController
{
    public function check(Request $request){
        $validated = Validator::make($request->all(),[
            'code' => 'required|max:100|exists:orders,qr_code',
        ]);

        if ($validated->fails()) {
            return $this->sendError($validated->errors(), "Validation fails", 422);
        }
        try {
            // Fetch the order with related order packages
            $order = Order::where('qr_code', $request->code)->with('orderPackages.ticketUsers', 'orderPackages.package')->first();
        
            if ($order) {
        
                // Return a successful response
                $orderPackages = OrderPackageResource::collection($order->orderPackages);
                return $this->sendResponse($orderPackages, 'Order found',200);
            } else {
        
                // Return a response for missing order
                return $this->sendError('No order found');
            }
        } catch (Exception $e) {
            // Log the exception with an error message
            Log::error('Error fetching order', [
                'qr_code' => $request->code,
                'error' => $e->getMessage(),
            ]);
        
            // Return an error response
            return $this->sendError('Something unusual happen', 'An error occurred while fetching the order', 500);
        }
        
    }
}
