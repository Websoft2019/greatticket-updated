<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Seat;
use App\Models\Package;
use Illuminate\Http\Request;

class SeatController extends Controller
{
    public function getPackageSeats(Package $package)
    {
        $seats = Seat::where('package_id', $package->id)
            ->orderBy('row_label')
            ->orderBy('seat_number')
            ->get()
            ->map(function ($seat) use ($package) {
                return [
                    // 'id' => $package->id . '_' . $seat->row_label . $seat->seat_number,
                    'id' => $seat->id,
                    'row' => $seat->row_label,
                    'number' => $seat->seat_number,
                    'seatName' => $seat->row_label . $seat->seat_number,
                    'status' => $seat->status,
                    'price' => $package->actual_cost,
                ];
            });

        return response()->json($seats);
    }
}
