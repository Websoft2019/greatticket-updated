<?php

namespace App\Services;

use App\Helpers\QRCodeHelper;
use App\Models\Order;
use App\Models\OrderPackage;
use App\Models\Seat;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class TicketBookingService
{
    protected function getMembershipNumber(): int
    {
        // Note: This is already properly wrapped in DB::transaction()
        return DB::transaction(function () {
            $latest = DB::table('ticket_users')
                ->whereNotNull('membership_no')
                ->orderByDesc('id')
                ->lockForUpdate()
                ->first();

            $lastNumber = 10077;
            if ($latest && strpos($latest->membership_no, '-') !== false) {
                $explode = explode('-', $latest->membership_no);
                $lastNumber = isset($explode[1]) ? (int) $explode[1] : $lastNumber;
            }

            // Loop until unique membership number is found
            do {
                $lastNumber++;
                $nextMembershipNo = 'TOP-' . $lastNumber;
                $exists = DB::table('ticket_users')
                    ->where('membership_no', $nextMembershipNo)
                    ->exists();
            } while ($exists);

            return $lastNumber;
        });
    }

    protected function bookAvailableSeat($packageId): ?int
    {
        // This method should be called within an existing transaction
        // Remove the separate transaction since it's called from handle() which already has one
        // $seat = DB::selectOne("
        //     SELECT * FROM seats
        //     WHERE package_id = ? AND status = 'available'
        //     ORDER BY `row_label` ASC, `seat_number` ASC
        //     LIMIT 1
        //     FOR UPDATE
        // ", [$packageId]);
        $seat = DB::selectOne("
            SELECT * FROM seats
            WHERE package_id = ? AND status = 'available'
            ORDER BY 
            `row_label` ASC,
            CAST(`seat_number` AS UNSIGNED) ASC, 
            `seat_number` ASC  
        LIMIT 1
            FOR UPDATE
        ", [$packageId]);

        Log::info('seat ', ['seat' => $seat]);

        if ($seat) {
            // Add condition to prevent race conditions
            $affected = DB::update("
                UPDATE seats 
                SET status = 'booked' 
                WHERE id = ? AND status = 'available'
            ", [$seat->id]);

            if ($affected > 0) {
                return $seat->id;
            } else {
                Log::warning('Seat was already booked during update', ['seat_id' => $seat->id]);
                return null;
            }
        }

        return null;
    }

    public function handle(Order $order, OrderPackage $orderPackage, $request)
    {
        return DB::transaction(function () use ($order, $orderPackage, $request) {
            $ticketUsersData = [];
            $baseNumber = $this->getMembershipNumber();
            $package = $orderPackage->package;
            $seatRequired = $package && $package->seats()->exists();
            $isReserved = $request->booking_type === "reserved";
            $isComplementary = $request->booking_type === "complementary";

            if ($isReserved && $seatRequired) {
                foreach ($request->selected_seats as $index => $seat) {
                    $ticketUsersData[] = $this->buildTicketUserData(
                        $request->attendees[$index] ?? 'Not Assigned',
                        $request->ic[$index] ?? null,
                        $baseNumber + $index,
                        $orderPackage->id,
                        $isComplementary,
                        "reserved",
                        $seat
                    );
                }
            } else {
                if ($seatRequired) {
                    foreach ($request->selected_seats as $index => $seat) {
                        $ticketUsersData[] = $this->buildTicketUserData(
                            $request->attendees[$index] ?? 'Not Assigned',
                            $request->ic[$index] ?? null,
                            $baseNumber + $index,
                            $orderPackage->id,
                            $isComplementary,
                            "booked",
                            $seat
                        );
                    }
                } else {
                    foreach ($request->attendees as $index => $attendeeName) {
                        $ticketUsersData[] = $this->buildTicketUserData(
                            $request->attendees[$index] ?? 'Not Assigned',
                            $request->ic[$index] ?? null,
                            $baseNumber + $index,
                            $orderPackage->id,
                            $isComplementary,
                            "booked",
                        );
                    }
                }
            }

            $orderPackage->ticketUsers()->insert($ticketUsersData);

            if ($package) {
                $package->increment('consumed_seat', $request->quantity);
            }

            return true;
        });
    }


    public function handleUserBooking(Order $order, string $transactionId): ?bool
    {
        return DB::transaction(function () use ($order, $transactionId) {
            // Update payment info
            $order->update([
                'paymentstatus' => 'Y',
                'paymentmethod' => 'SanangPay',
                'updated_at' => now(),
                'payerid' => $transactionId,
            ]);

            if ($order->reserved_at) {
                foreach ($order->orderPackages as $orderPackage) {
                    $package = $orderPackage->package;
                    $hasSeats = $package && $package->seats()->exists();
                    foreach ($orderPackage->ticketUsers as $ticketUser) {
                        try {
                            // Generate new membership number and QR
                            $membershipNo = 'TOP-' . $this->getMembershipNumber();
                            $qrCode = Str::uuid();
                            $qrImage = QRCodeHelper::generateQrCode($qrCode);

                            // Update ticket user
                            $ticketUser->update([
                                'membership_no' => $membershipNo,
                                'ticket_type' => 'paid',
                                'qr_code' => $qrCode,
                                'qr_image' => $qrImage,
                            ]);
                            if ($hasSeats) {
                                $ticketUser->seat->update([
                                    'status' => 'booked',
                                ]);
                            }
                        } catch (\Exception $e) {
                            Log::error('Ticket user update failed', [
                                'user_id' => $ticketUser->id,
                                'error' => $e->getMessage(),
                            ]);
                        }
                    }
                }
            } else {
                foreach ($order->orderPackages as $orderPackage) {
                    $package = $orderPackage->package;

                    // Increment consumed seat count
                    // $package->increment('consumed_seat', $orderPackage->quantity);

                    $hasSeats = $package && $package->seats()->exists();

                    foreach ($orderPackage->ticketUsers as $ticketUser) {
                        try {
                            // Generate new membership number and QR
                            $membershipNo = 'TOP-' . $this->getMembershipNumber();
                            $qrCode = Str::uuid();
                            $qrImage = QRCodeHelper::generateQrCode($qrCode);

                            $seatId = $ticketUser?->seat_id;

                            if ($hasSeats && !$ticketUser->seat_id) {
                                $seatId = $this->bookAvailableSeat($package->id);

                                if ($seatId === null) {
                                    throw new \Exception("No available seats for package ID: {$package->id}");
                                }
                            }

                            // Update ticket user
                            $ticketUser->update([
                                'membership_no' => $membershipNo,
                                'qr_code' => $qrCode,
                                'qr_image' => $qrImage,
                                'seat_id' => $seatId,
                            ]);
                        } catch (\Exception $e) {
                            Log::error('Ticket user update failed', [
                                'user_id' => $ticketUser->id,
                                'error' => $e->getMessage(),
                            ]);
                        }
                    }
                }
            }
            // return true;
        });
    }

    private function buildTicketUserData(
        string $name,
        ?string $ic,
        int $membershipNumber,
        int $orderPackageId,
        bool $isComplementary,
        string $status = "reserved",
        ?int $seatId = null
    ): array {
        $code = Str::uuid();
        $qrImage = QRCodeHelper::generateQrCode($code);

        if ($seatId) {
            $seat = Seat::findOrFail($seatId);
            $seat->status = $status;
            $seat->save();
        }

        return [
            'name' => $name,
            'ic' => $ic,
            'membership_no' => 'TOP-' . $membershipNumber,
            'qr_code' => $code,
            'qr_image' => $qrImage,
            'order_package_id' => $orderPackageId,
            'ticket_type' => $isComplementary ? 'complementary' : ($status == "reserved" ? 'reserved' : 'paid'),
            'seat_id' => $seatId,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
