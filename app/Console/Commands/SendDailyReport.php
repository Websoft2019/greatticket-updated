<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
// use App\Mail\DailyReportMail;
use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;
use App\Mail\DailyEventOrderSummaryMail;

class SendDailyReport extends Command
{
    protected $signature = 'report:send-daily';
    protected $description = 'Send daily report email at midnight';

    public function handle()
    {
        // Get count of orders created yesterday with payment status "Y"
        // $ordersCount = Order::where('paymentstatus', 'Y')
        //     ->whereDate('created_at', Carbon::yesterday())
        //     ->count();

        // $reportData = [
        //     'Yesterday\'s Total Sales: ' . $ordersCount
        // ];

        // // Send to email
        // Mail::to('websoft.pokhara@gmail.com')->send(new DailyReportMail($reportData));

        // $this->info('Daily report sent successfully.');
        
        try {
            // Use specified date or yesterday
            // $reportDate = $this->option('date') 
            //     ? $this->option('date')
            //     : now()->subDay()->toDateString();
            $reportDate = now()->subDay()->toDateString();

            $this->info("Generating daily order reports for: {$reportDate}");

            // Get all organizers with their events
            $organizers = User::where('role', 'o')
                ->with(['events'])
                ->get();
            // $organizers = User::where('email', 'jujutsukaisen011011@gmail.com')->get();

            if ($organizers->isEmpty()) {
                $this->warn('No organizers found in the system.');
                return Command::SUCCESS;
            }

            $totalEmailsSent = 0;
            $totalOrganizers = $organizers->count();

            $this->info("Found {$totalOrganizers} organizers to process...");

            foreach ($organizers as $organizer) {
                $this->line("Processing organizer: {$organizer->name} ({$organizer->email})");
                
                if ($organizer->events->isEmpty()) {
                    $this->line("  - No events found for this organizer");
                    continue;
                }

                foreach ($organizer->events as $event) {
                    // Get orders for this specific event
                    $orders = Order::whereHas('orderPackages.package', function ($query) use ($event) {
                        $query->where('event_id', $event->id);
                    })
                        ->whereDate('created_at', $reportDate)
                        ->with([
                            'user',
                            'orderPackages.package'
                        ])
                        ->get();

                    if ($orders->isNotEmpty()) {
                        // if ($this->option('dry-run')) {
                        //     $this->info("  [DRY RUN] Would send report for event '{$event->title}' to {$organizer->email} ({$orders->count()} orders)");
                        // } else {
                            Mail::to($organizer->email)
                            ->bcc(["ishworchalise@gmail.com", "darshankc.xdezo@gmail.com"])
                            ->send(
                                new DailyEventOrderSummaryMail($organizer, $event, $orders)
                            );
                            $this->info("  ✓ Sent report for event '{$event->title}' ({$orders->count()} orders)");
                        // }
                        $totalEmailsSent++;
                    } else {
                        $this->line("  - No orders found for event '{$event->title}'");
                    }
                }
            }

            // if ($this->option('dry-run')) {
            //     $this->info("DRY RUN completed. Would have sent {$totalEmailsSent} emails.");
            // } else {
                $this->info("Daily event order reports completed successfully!");
                $this->info("Total emails sent: {$totalEmailsSent}");
            // }
            
            return Command::SUCCESS;
            
        } catch (\Exception $e) {
            $this->error('Failed to send daily event order reports: ' . $e->getMessage());
            $this->error('Stack trace: ' . $e->getTraceAsString());
            return Command::FAILURE;
        }
    }
}

