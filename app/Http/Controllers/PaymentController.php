<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\TicketUser;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function showPaymentPage(Order $order)
    {
        if ($order->is_paid) {
            return redirect('/')->with('message', 'This order has already been paid.');
        }

        return view('site.payment.reserved', compact('order'));
    }

    public function saveTicketUsers(Request $request, Order $order)
    {
        $ticketUsers = $request->input('ticket_users');

        foreach ($ticketUsers as $id => $data) {
            $ticketUser = TicketUser::find($id);
            if ($ticketUser && $ticketUser->order_package_id == $order->orderPackages->first()->id) {
                $ticketUser->name = $data['name'] ?? $ticketUser->name;
                $ticketUser->save();
            }
        }

        return redirect()->route('payment.reserved.pay', $order->qr_code);
    }

    public function showPaymentButton(Order $order)
    {
        if ($order->paymentstatus == 'Y') {
            return redirect('/')->with('message', 'Already paid.');
        }

        $eventname = $order->event->title ?? 'Event';

        return view('site.payment.confirmed_payment', compact('order', 'eventname'));
    }



    public function processPayment(Request $request, Order $order)
    {
        // Example: Cash/Manual payment
        // Add logic for actual payment gateway integration if needed

        if ($order->is_paid) {
            return redirect()->route('home')->with('message', 'Already paid.');
        }

        $order->update([
            'is_paid' => true,
            'payment_method' => 'manual',
            'payment_status' => 'completed',
            'paid_at' => now(),
        ]);

        // Optionally: send confirmation email or ticket

        return redirect()->route('home')->with('success', 'Payment successful! Your tickets are confirmed.');
    }
}
