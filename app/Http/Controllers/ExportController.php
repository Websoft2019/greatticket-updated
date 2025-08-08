<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class ExportController extends Controller
{
    public function exportPdf(Request $request)
    {
        $request->validate([
            'event_id' => 'required|exists:events,id',
            'chart_image' => 'required',
        ]);

        $eventId = $request->event_id;
        // Replace this with actual logic to fetch your event data
        $event = \App\Models\Event::with([
            'packages' => function ($q) {
                $q->withCount([
                    'orderPackages as total_quantity' => function ($query) {
                        $query->whereHas('order', function ($subquery) {
                            $subquery->where('paymentstatus', '!=', 'N');
                        })->select(DB::raw('sum(quantity)'));
                    }
                ]);
            }
        ])->findOrFail($eventId);

        $eventData = $this->getEventData($event);

        $orders = $event->packages
            ->flatMap(fn($pkg) => $pkg->orderPackages)
            ->pluck('order')
            ->filter(fn($order) => $order && $order->paymentstatus === 'Y')
            ->unique('id');

        // Group by order date and count
        $dateCounts = $orders->groupBy(function ($order) {
            return \Carbon\Carbon::parse($order->created_at)->format('Y-m-d');
        })->map->count();

        $event_dates = $dateCounts->keys()->toArray();
        $orderCounts = $dateCounts->values()->toArray();
        $chartImage = $request->chart_image;

        $pdf = Pdf::loadView('pdf.events-report', compact('eventData', 'event_dates', 'orderCounts', 'chartImage'));
        return $pdf->download('event_report_' . $eventId . '.pdf');
    }

    private function getEventData($event)
    {

        // Sample transformation logic
        $totalCost = 0;
        $packagesData = [];

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
                'orderTotal' => $orders->sum(function($order){
                        return $order->carttotalamount - $order->discount_amount;
                    }),
            ];
        }

        // Calculate the total event cost
        $totalEventCost = collect($packagesData)->sum('totalCost');
        $totalOrderCost = collect($packagesData)->sum('orderTotal');

        return [
            'event' => $event,
            'packagesData' => $packagesData,
            'totalEventCost' => $totalEventCost,
            'totalOrderCost' => $totalOrderCost // or something different
        ];
    }
}