<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Order;
use App\Models\Package;
use App\Models\OrderPackage;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
    {
        $user_id = auth()->id();
        $eventList = [];

        // Get all events where the user is the organizer, and eager load related data
        $events = Event::where('organizer_id', $user_id)
            ->with([
                'packages' => function ($query) {
                    // Sum only the quantity for orderPackages where the order's paymentstatus is not 'N'
                    $query->withCount(['orderPackages as total_quantity' => function ($query) {
                        $query->whereHas('order', function ($query) {
                            $query->where('paymentstatus', '!=', 'N');
                        })->select(DB::raw('sum(quantity)'));
                    }]);
                }
            ])
            ->get();

        // Initialize an array to hold the package data with calculated quantities and costs
        $eventPackagesData = [];

        // Iterate through events and their packages
        foreach ($events as $event) {
            $packagesData = [];

            foreach ($event->packages as $package) {
                // Get unique orders related to this package
                $orders = $package->orderPackages
                    ->map(function ($orderPackage) {
                        return $orderPackage->order;
                    })
                    ->filter(function ($order) {
                        return $order && $order->paymentstatus === 'Y';
                    })
                    ->unique('id')
                    ->values();

                // Calculate the total cost based on quantity and actual cost
                $totalCost = $package->total_quantity * $package->actual_cost;

                // Add the package data to the array
                $packagesData[] = [
                    'package' => $package,
                    'quantity' => $package->total_quantity,
                    'totalCost' => $totalCost,
                    'orders' => $orders,
                    'orderTotal' => $orders->sum('grandtotal'),
                ];
            }

            // Calculate the total event cost
            $totalEventCost = collect($packagesData)->sum('totalCost');
            $totalOrderCost = collect($packagesData)->sum('orderTotal');

            $eventList = $events->map(function ($event) {
                            $orders = $event->packages
                                ->flatMap(function ($pkg) {
                                    return $pkg->orderPackages;
                                })
                                ->pluck('order')
                                ->filter(function ($order) {
                                    return $order && $order->paymentstatus === 'Y';
                                })
                                ->unique('id');

                // Group by order date and count
                $dateCounts = $orders->groupBy(function ($order) {
                    return \Carbon\Carbon::parse($order->created_at)->format('Y-m-d');
                })->map->count();

                return [
                    'name' => $event->title,
                    'dates' => $dateCounts->keys()->toArray(),
                    'orderCounts' => $dateCounts->values()->toArray(),
                ];
            });

            // Add event data along with package info to the result
            $eventPackagesData[] = [
                'event' => $event,
                'packagesData' => $packagesData,
                'totalEventCost' => $totalEventCost,
                'totalOrderCost' => $totalOrderCost,
            ];
        }

        return view('pages.order.report', compact('eventPackagesData', 'eventList'));
    }

    public function details($id)
    {
        $order = Order::findOrFail($id);
        $check = $order->orderPackages->first();
        // dd($pack);
        if($check !== null){
            $packageinfo = Package::findOrFail($check->package_id);
            $event = Event::findorFail($packageinfo->event_id);
            $data=[
                'order' => $order,
                'event' => $event
            ];
        
        }else{
            $data = [
                'order' => collect(),
                'event' => collect(),
                ];
        }
        
        if (auth()->user()->role == 'o') {
            return view('pages.order.details', compact('order'));
        }
        return view('site.history-details', $data);
    }

    public function history()
    {
        $user = auth()->user();
        $orders = Order::where('user_id', $user->id)->whereHas('orderPackages')->get();
        return view('site.user-dashboard', compact('orders'));
    }

    public function test()
    {
        $user_id = auth()->id();

        // Get all events where the user is the organizer, and eager load related data
        $events = Event::where('organizer_id', $user_id)
            ->with([
                'packages' => function ($query) {
                    // Sum only the quantity for orderPackages where the order's paymentstatus is not 'N'
                    $query->withCount(['orderPackages as total_quantity' => function ($query) {
                        $query->whereHas('order', function ($query) {
                            $query->where('paymentstatus', '!=', 'N');
                        })->select(DB::raw('sum(quantity)'));
                    }]);
                }
            ])
            ->get();

        // return $events;

        // Initialize an array to hold the package data with calculated quantities and costs
        $eventPackagesData = [];

        // Iterate through events and their packages
        foreach ($events as $event) {
            $packagesData = [];

            foreach ($event->packages as $package) {
                // Calculate the total cost based on quantity and actual cost
                $totalCost = $package->total_quantity * $package->actual_cost;

                // Add the package data to the array
                $packagesData[] = [
                    'package' => $package,
                    'quantity' => $package->total_quantity,
                    'totalCost' => $totalCost
                ];
            }

            // Calculate the total event cost
            $totalEventCost = collect($packagesData)->sum('totalCost');

            // Add event data along with package info to the result
            $eventPackagesData[] = [
                'event' => $event,
                'packagesData' => $packagesData,
                'totalEventCost' => $totalEventCost
            ];
        }

        return view('pages.order.report', compact('eventPackagesData'));
    }

    public function testList($pid)
    {
        $orderPackages = OrderPackage::with('order', 'package', 'ticketUsers')->where('package_id', $pid)->get()->groupBy('order_id');
        return view('pages.order.test-list', compact('orderPackages'));
    }

    public function testDetails($id) {}

    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        // $order->delete();
        // Loop through each orderPackage
        foreach ($order->orderPackages as $orderPackage) {
            // Delete ticketUsers related to the orderPackage
            $orderPackage->ticketUsers()->delete();
        }

        // Delete orderPackages related to the order
        $order->orderPackages()->delete();

        // Finally, delete the order itself
        $order->delete();
        if(auth()->user()->role == 'u'){
            return redirect()->route('history');
        }
        return redirect()->back()->with('success', "Successfully deleted");
    }

    public function generatePdf(Order $op_id){
        if($op_id->user_id == Auth()->user()->id){
            // Increase memory limit for PDF generation
            $previousMemory = \App\Helpers\QRCodeHelper::increaseMemoryForPdf('256M');
            
            try {
                $orderpackageinfo = OrderPackage::where('order_id', $op_id->id)->limit(1)->first();
                $orderPackage = OrderPackage::with('ticketUsers')->findOrFail($orderpackageinfo->id);
            
                $event = $orderPackage->package->event;
                
                // Log memory usage for monitoring
                \Log::info('PDF generation memory usage', [
                    'before_pdf' => memory_get_usage(true),
                    'memory_limit' => ini_get('memory_limit'),
                    'order_id' => $op_id->id,
                    'tickets_count' => $orderPackage->ticketUsers->count()
                ]);
                
                $pdf = PDF::loadView("pdf.user-ticket", compact("orderPackage", "event"));
                
                \Log::info('PDF generation completed', [
                    'after_pdf' => memory_get_usage(true),
                    'peak_memory' => memory_get_peak_usage(true)
                ]);
                
                return $pdf->download("Event-Tickets.pdf");
            } finally {
                // Always reset memory limit
                \App\Helpers\QRCodeHelper::resetMemoryLimit($previousMemory);
            }
        }
        else{
            abort(404);
        }
        
    }
}
