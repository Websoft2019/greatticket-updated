<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeleteSeatsRequest;
use App\Http\Requests\StoreSeatsRequest;
use App\Http\Requests\UpdateSeatsRequest;
use App\Models\Package;
use App\Models\Seat;
use App\Services\SeatService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SeatController extends Controller
{
    public function store(StoreSeatsRequest $request, SeatService $seatService)
    {
        $createdSeats = $seatService->storeSeats(
            $request->input('package_id'),
            $request->input('seats')
        );

        // Log::info('Seats: '. $request);

        return response()->json([
            'success' => true,
            'message' => 'Seats stored successfully',
            'data' => $createdSeats,
        ]);
    }

    public function create($packageId)
    {
        return view('pages.package.seat', compact('packageId'));
    }

    public function edit($packageId)
    {
        // Get all seats for the package
        $seats = Seat::where('package_id', $packageId)
            ->orderBy('row_label')
            ->orderBy('seat_number')
            ->get();

        return view('pages.package.edit-seat', compact('packageId', 'seats'));
    }

    public function update(UpdateSeatsRequest $request, $packageId, SeatService $seatService)
    {
        $package = Package::where('id', $packageId)->first();

        if (!$package) {
            return response()->json([
                'success' => false,
                'message' => 'Package does not exist',
                'error' => "Package not found"
            ], 403);
        }

        $available = $package->capacity - $package->consumed_seat;

        if ($package && count($request->input('seats', [])) > $available) {
            return response()->json([
                'success' => false,
                'message' => 'The number of seats exceeds the package capacity of ' . $available . '.',
                'error' => "Number of seats exceeds"
            ], 500);
        }
        try {
            // Log::info('seats' . $request);
            $updatedSeats = $seatService->updateSeats(
                $packageId,
                $request->input('seats')
            );

            Log::info('seats', ['seats' => $updatedSeats]);

            return response()->json([
                'success' => true,
                'message' => 'Seats updated successfully',
                'data' => $updatedSeats,
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating seats: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to update seats',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($packageId, $seatId)
    {
        try {
            $seat = Seat::where('package_id', $packageId)
                ->where('id', $seatId)
                ->firstOrFail();

            $seat->delete();

            return response()->json([
                'success' => true,
                'message' => 'Seat deleted successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error deleting seat: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to delete seat'
            ], 500);
        }
    }

    public function deleteSeats(DeleteSeatsRequest $deleteSeatsRequest, SeatService $seatService)
    {
        try {
            $deletedCount = $seatService->deleteSeats($deleteSeatsRequest->seat_ids);

            return response()->json([
                'success' => true,
                'message' => "{$deletedCount} seat(s) deleted successfully."
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }
}
