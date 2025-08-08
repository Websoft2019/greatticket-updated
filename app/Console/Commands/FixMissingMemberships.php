<?php

namespace App\Console\Commands;

use App\Helpers\QRCodeHelper;
use App\Models\TicketUser;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class FixMissingMemberships extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:memberships';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix users with missing membership_no, qr_code, and qr_image fields';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        DB::transaction(function () {
            // Lock and get the latest assigned membership number
            $latest = DB::table('ticket_users')
                ->whereNotNull('membership_no')
                ->orderByDesc(DB::raw("CAST(SUBSTRING_INDEX(membership_no, '-', -1) AS UNSIGNED)"))
                ->lockForUpdate()
                ->first();

            $lastNumber = 10077;
            if ($latest && strpos($latest->membership_no, '-') !== false) {
                $explode = explode('-', $latest->membership_no);
                $lastNumber = isset($explode[1]) ? (int) $explode[1] : $lastNumber;
            }

            // Fetch all users who need fixing
            $users = TicketUser::whereHas('orderPackage.order', function ($query) {
                $query->where('paymentstatus', 'Y');
            })
                ->whereNull('membership_no')
                ->whereNull('qr_code')
                ->whereNull('qr_image')
                ->where('created_at', '>', '2025-05-29')
                ->lockForUpdate()
                ->get();

            if ($users->isEmpty()) {
                $this->info('No users found that need fixing.');
                return;
            }

            foreach ($users as $user) {
                // Keep generating next number until unique
                do {
                    $lastNumber++;
                    $nextMembershipNo = 'TOP-' . $lastNumber;

                    $exists = DB::table('ticket_users')
                        ->where('membership_no', $nextMembershipNo)
                        ->exists();
                } while ($exists);

                // Generate QR info
                $uuid = (string) Str::uuid();
                $qrImagePath = QRCodeHelper::generateQrCode($uuid);

                // Update the record
                DB::table('ticket_users')
                    ->where('id', $user->id)
                    ->update([
                        'membership_no' => $nextMembershipNo,
                        'qr_code' => $uuid,
                        'qr_image' => $qrImagePath,
                        'updated_at' => Carbon::now(),
                    ]);

                $this->line("Updated user ID {$user->id} with membership_no {$nextMembershipNo}");
            }

            $this->info('All missing memberships and QR codes have been fixed!');
        });
    }
}
