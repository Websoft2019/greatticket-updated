<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Models\Seat;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ReleaseOrderExpiredSeats extends Command
{
    protected $signature = 'seats:release-order-expired-seats';
    protected $description = 'Release expired order seats and adjust package seats efficiently';

    public function handle()
    {
        DB::transaction(function () {
            // Fetch expired orders with related data
            $orders = Order::with('orderPackages.ticketUsers.seat.package')
                ->where('paymentstatus', 'R')
                ->where('expires_at', '<=', now())
                ->get();

            if ($orders->isEmpty()) {
                $this->info('No expired orders found.');
                return;
            }

            // Collect IDs and prepare adjustments
            $seatIds = [];
            $packageAdjustments = [];
            $orderIds = [];

            foreach ($orders as $order) {
                $orderIds[] = $order->id;

                foreach ($order->orderPackages as $oPackage) {
                    $packageId = $oPackage->package_id;
                    if (!isset($packageAdjustments[$packageId])) {
                        $packageAdjustments[$packageId] = 0;
                    }
                    $packageAdjustments[$packageId] += $oPackage->quantity;

                    foreach ($oPackage->ticketUsers as $user) {
                        if ($user->seat) {
                            $seatIds[] = $user->seat->id;
                        }
                    }
                }
            }

            // ✅ Bulk update seats
            if (!empty($seatIds)) {
                Seat::whereIn('id', $seatIds)->update([
                    'reserved_at' => null,
                    'expires_at' => null,
                    'status' => 'available',
                ]);
            }

            // ✅ Bulk update packages (decrement consumed seats)
            foreach ($packageAdjustments as $packageId => $qty) {
                DB::table('packages')
                    ->where('id', $packageId)
                    ->decrement('consumed_seat', $qty);
            }

            // ✅ Bulk update orders
            Order::whereIn('id', $orderIds)->update([
                'paymentstatus' => 'N',
                'expires_at' => null,
                'reserved_at' => null,
            ]);

            $this->info('Expired seats have been released successfully.');
        });
    }
}
