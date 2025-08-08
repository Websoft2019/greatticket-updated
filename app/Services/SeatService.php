<?php

namespace App\Services;

use App\Models\Seat;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class SeatService
{
    /**
     * Store multiple seats for a package (optimized).
     *
     * @param  int   $packageId
     * @param  array $seats
     * @return array
     * @throws \Throwable
     */
    public function storeSeats(int $packageId, array $seats): array
    {
        $now = Carbon::now();

        $insertData = [];


        // Optional: Load existing seat combos to avoid duplicates
        $existingSeats = Seat::where('package_id', $packageId)
            ->get()
            // ->map(fn($seat) => $seat->row_label . ':' . $seat->seat_number)
            ->map(function ($seat) {
                return $seat->row_label . ':' . $seat->seat_number;
            })
            ->toArray();

        foreach ($seats as $seat) {
            $identifier = $seat['row_label'] . ':' . $seat['seat_number'];

            if (in_array($identifier, $existingSeats)) {
                continue; // Skip duplicates
            }

            $insertData[] = [
                'package_id'  => $packageId,
                'row_label'   => $seat['row_label'],
                'seat_number' => $seat['seat_number'],
                'position_x'  => $seat['position_x'] ?? null,
                'position_y'  => $seat['position_y'] ?? null,
                'status'      => $seat['status'] ?? 'available',
                'created_at'  => $now,
                'updated_at'  => $now,
            ];
        }

        DB::beginTransaction();

        try {
            if (!empty($insertData)) {
                Seat::insert($insertData);
            }

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Seat creation failed', ['error' => $e->getMessage()]);
            throw $e;
        }

        $data =  Seat::where('package_id', $packageId)
            ->whereIn(DB::raw("CONCAT(row_label, ':', seat_number)"), array_column($insertData, 'row_label'))
            ->get()
            ->toArray();

        return $data;
    }

    public function updateSeats($packageId, $seatsData)
    {
        // delete before updating remaining
        $updatedSeats = [];

        foreach ($seatsData as $seatData) {
            if (isset($seatData['id']) && $seatData['id']) {
                // Update existing seat
                $seat = Seat::where('package_id', $packageId)
                    ->where('id', $seatData['id'])
                    ->firstOrFail();

                $seat->update([
                    'row_label' => $seatData['row_label'],
                    'seat_number' => $seatData['seat_number'],
                    'position_x' => $seatData['position_x'] ?? null,
                    'position_y' => $seatData['position_y'] ?? null,
                    'status' => $seatData['status'],
                ]);

                $updatedSeats[] = $seat->fresh();
            } else {
                // Create new seat
                $seat = Seat::create([
                    'package_id' => $packageId,
                    'row_label' => $seatData['row_label'],
                    'seat_number' => $seatData['seat_number'],
                    'position_x' => $seatData['position_x'] ?? null,
                    'position_y' => $seatData['position_y'] ?? null,
                    'status' => $seatData['status'],
                ]);

                $updatedSeats[] = $seat;
            }
        }

        return $updatedSeats;
    }

    /**
     * @param array $seatIds
     * @return int
     */
    public function deleteSeats(array $seatIds): int
    {
        return DB::transaction(function () use ($seatIds) {
            // Lock the rows for update to prevent race conditions
            $seats = DB::table('seats')
                ->whereIn('id', $seatIds)
                ->where('status', 'available')
                ->lockForUpdate()
                ->get();

            // If some seat IDs were invalid or not available, exclude them
            $validIds = $seats->pluck('id')->all();

            if (empty($validIds)) {
                throw new \Exception('No deletable seats found (they might be booked or invalid).');
            }

            $deleted = DB::table('seats')->whereIn('id', $validIds)->delete();

            Log::info("Deleted {$deleted} available seat(s)", ['seat_ids' => $validIds]);

            return $deleted;
        });
    }

    /**
     * Validate and optionally extend expiring seats.
     *
     * @param Collection $seats - Collection of Seat models
     * @param int $extensionMinutes
     * @return array ['expired' => [...], 'extended' => [...]]
     */
    public function validateAndExtendExpiringSeats(Collection $seats, int $extensionMinutes = 5): array
    {
        $now = Carbon::now();
        $expired = [];
        $extended = [];

        foreach ($seats as $seat) {
            if (!$seat->expires_at) {
                continue;
            }
            $expired_at = Carbon::parse($seat->expires_at);
            
            // dd($seat->expires_at);
            if ($seat->expires_at && $expired_at->isPast()) {
                $expired[] = $seat;
            } elseif ($expired_at->diffInMinutes($now) <= 2) {
                $seat->update(['expires_at' => $now->copy()->addMinutes($extensionMinutes)]);
                $extended[] = $seat;
            }
        }

        return [
            'expired' => $expired,
            'extended' => $extended,
        ];
    }
}
