<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class ReservedBookingController extends Controller
{
    public function index()
    {
        $reservedBookings = Order::where('paymentstatus', 'R')->latest()->get();
        return view('pages.bookings.reserved.index', compact('reservedBookings'));
    }

    public function update($id)
    {
        $order = Order::findOrFail($id);
        $order->paymentstatus = 'Y';
        $order->update();
        return redirect()->route('bookings.reserved.index');
    }

    public function show($id)
    {
        $order = Order::where('id', $id)->firstOrFail();
        return view('pages.bookings.reserved.details', compact('order'));
    }

    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        // $order->delete();
        // Loop through each orderPackage
        foreach ($order->orderPackages as $orderPackage) {
            if ($orderPackage->package) {
                $orderPackage->package->update([
                    'consumed_seat' => $orderPackage->package->consumed_seat - $orderPackage->quantity,
                ]);
            }
            // Delete ticketUsers related to the orderPackage
            $orderPackage->ticketUsers()->delete();
        }

        // Delete orderPackages related to the order
        $order->orderPackages()->delete();

        // Finally, delete the order itself
        $order->delete();
        return redirect()->route('bookings.reserved.index');
    }
}
