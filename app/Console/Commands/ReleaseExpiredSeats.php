<?php

namespace App\Console\Commands;

use App\Models\Cart;
use App\Models\Seat;
use Illuminate\Console\Command;

class ReleaseExpiredSeats extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'seats:release-expired-seats';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $expiredSeats = Seat::isExpired()->with('carts')->get();

        $affectedCartIds = [];

        foreach ($expiredSeats as $seat) {
            // Collect affected cart IDs
            foreach ($seat->carts as $cart) {
                $affectedCartIds[] = $cart->id;
            }

            // Detach seat from all carts (clean pivot)
            $seat->carts()->detach();

            // Mark seat as available again
            $seat->update([
                'status' => 'available',
                'reserved_at' => null,
                'expires_at' => null,
            ]);
        }

        // Step 2: Delete carts that have no seats left
        $affectedCartIds = array_unique($affectedCartIds);

        $carts = Cart::whereIn('id', $affectedCartIds)->withCount('seats')->get();

        foreach ($carts as $cart) {
            if ($cart->seats_count === 0) {
                $cart->delete();
            }
        }
    }
}
