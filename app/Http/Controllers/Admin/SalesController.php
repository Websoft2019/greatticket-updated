<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Event;
use App\Models\Package;
use App\Models\OrderPackage;
use Illuminate\Support\Facades\DB;
use App\Mail\DailyEventOrderSummaryMail;
use Mail;

class SalesController extends Controller
{
    public function adminReport()
    {

        $salesData = Order::join('order_package', 'orders.id', '=', 'order_package.order_id')
            ->join('packages', 'order_package.package_id', '=', 'packages.id')
            ->join('events', 'packages.event_id', '=', 'events.id')
            ->join('users AS event_users', 'events.organizer_id', '=', 'event_users.id')
            ->join('organizers', 'event_users.id', '=', 'organizers.user_id')
            ->select(
                'orders.id as order_id',
                'order_package.quantity',
                'packages.id as package_id',
                'packages.title AS package_title',
                'events.id as event_id',
                'events.title AS event_title',
                'event_users.name as organizer_name',
                'organizers.cm_type',
                'organizers.cm_value',
                DB::raw('
            SUM(order_package.quantity) AS total_tickets_sold,
            SUM(order_package.quantity * packages.actual_cost) AS total_revenue,
            CASE 
                WHEN organizers.cm_type = "percentage" THEN 
                    (SUM(order_package.quantity) * packages.actual_cost * organizers.cm_value / 100) 
                WHEN organizers.cm_type = "flat" THEN 
                    (SUM(order_package.quantity) * organizers.cm_value) 
                ELSE 0 
            END AS commission_money
        ')
            )
            ->groupBy(
                'orders.id',
                'order_package.quantity',
                'packages.id',
                'packages.title',
                'events.id',
                'events.title',
                'event_users.name',
                'organizers.cm_type',
                'organizers.cm_value',
                'packages.actual_cost'
            )
            ->get();

        return view('pages.salesreport.admin-sales-report', compact('salesData'));
    }

    // public function salesReport(Request $request)
    // {
    //     $dateto = $request->input('dateto') ?? Carbon::today();
    //     $userId = auth()->id();
    //     $events = Event::where('id', $userId)->get();
    //     $packages = Package::whereHas('event', function ($query) use ($userId) {
    //         $query->where('organizer_id', $userId);
    //     })->get();
    //     if ($request->has('datefrom')) {
    //         $date = $request->input('datefrom');

    //         $logOrgID = User::where('id', auth()->id())->with('organizer')->first()->id;
    //         $eventsID = Event::where('organizer_id', $logOrgID)->get('id');
    //         $packagesId =  Package::whereIn('event_id', $eventsID)->get('id');
    //         $ordersID = OrderPackage::whereIn('package_id', $packagesId)->get('order_id');

    //         $salesReports = Order::whereBetween('created_at', [$date, $dateto])
    //             ->whereIn('id', $ordersID)
    //             ->where('paymentstatus', 'Y')
    //             ->get();
    //     } else {
    //         $salesReports = collect();
    //     }
    //     // get the event by auth user/org
    //     $orgEnvents = User::where('id', auth()->id())->with('organizer')->with('events')->first();


    //     return view('pages.salesreport.index', [
    //         'salesReports' => $salesReports,
    //         'orgEnvents' => $orgEnvents,
    //     ]);
    // }

    public function salesReport(Request $request)
    {
        $userId = auth()->id();
        $dateFrom = $request->input('datefrom'); // could be null
        $dateTo = $request->input('dateto');     // could be null
        $paymentMethod = $request->input('paymentmethod'); // manual / SanangPay / null

        // Get event and package IDs for the logged-in organizer
        $eventIds = Event::where('organizer_id', $userId)->pluck('id');
        $packageIds = Package::whereIn('event_id', $eventIds)->pluck('id');
        $orderIds = OrderPackage::whereIn('package_id', $packageIds)->pluck('order_id');

        // Build the query with flexible filters
        $salesQuery = Order::whereIn('id', $orderIds)
            ->where('paymentstatus', 'Y');

        // Apply date filter if dates are provided
        if ($dateFrom && $dateTo) {
            $salesQuery->whereBetween('created_at', [$dateFrom, $dateTo]);
        } elseif ($dateFrom) {
            $salesQuery->whereDate('created_at', '>=', $dateFrom);
        } elseif ($dateTo) {
            $salesQuery->whereDate('created_at', '<=', $dateTo);
        }

        // Apply payment method filter if provided
        if ($paymentMethod) {
            $salesQuery->where('paymentmethod', $paymentMethod);
        }

        $salesReports = $salesQuery->get();

        // Load organizer with events for summary or UI
        $orgEvents = User::with(['organizer', 'events'])
            ->findOrFail($userId);
        dd('here');

        return view('pages.salesreport.report', [
            'salesReports' => $salesReports,
            'orgEnvents' => $orgEvents,
            'selectedMethod' => $paymentMethod,
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
        ]);
    }



    // public function test()
    // {
    //     $organizers = User::select('id', 'name', 'email')
    //         ->where('role', 'o')
    //         ->with(['organizer']) // Load the organizer details
    //         ->with([
    //             'events' => function ($query) {
    //                 $query->select([
    //                     'events.id',
    //                     'events.title',
    //                     'events.organizer_id',
    //                     'events.date',
    //                 ])
    //                     ->withSum(['packages as total_event_amount' => function ($query) {
    //                         $query->join('order_package', 'packages.id', '=', 'order_package.package_id')
    //                             ->join('orders', 'orders.id', '=', 'order_package.order_id')
    //                             ->where('orders.paymentstatus', '!=', 'N');
    //                     }], DB::raw('order_package.quantity * packages.actual_cost'))
    //                     ->addSelect(DB::raw('(SELECT o.cm_type FROM organizers o 
    //                             JOIN users u ON o.user_id = u.id 
    //                             WHERE u.id = events.organizer_id) as cm_type'))
    //                     ->addSelect(DB::raw('(SELECT o.cm_value FROM organizers o 
    //                             JOIN users u ON o.user_id = u.id 
    //                             WHERE u.id = events.organizer_id) as cm_value'))
    //                     ->withSum(['packages as admin_amount' => function ($query) {
    //                         $query->join('order_package', 'packages.id', '=', 'order_package.package_id')
    //                             ->join('orders', 'orders.id', '=', 'order_package.order_id')
    //                             ->where('orders.paymentstatus', '!=', 'N');
    //                     }], DB::raw('CASE 
    //                         WHEN (SELECT o.cm_type FROM organizers o 
    //                             JOIN users u ON o.user_id = u.id 
    //                             WHERE u.id = events.organizer_id) = "percentage"
    //                         THEN (order_package.quantity * packages.actual_cost) * 
    //                             ((SELECT o.cm_value FROM organizers o 
    //                             JOIN users u ON o.user_id = u.id 
    //                             WHERE u.id = events.organizer_id) / 100)
    //                         ELSE (SELECT o.cm_value FROM organizers o 
    //                             JOIN users u ON o.user_id = u.id 
    //                             WHERE u.id = events.organizer_id)
    //                         END'))
    //                     ->withSum(['packages as totalamount' => function ($query) {
    //                         $query->join('order_package', 'packages.id', '=', 'order_package.package_id')
    //                             ->join('orders', 'orders.id', '=', 'order_package.order_id')
    //                             ->where('orders.paymentstatus', '!=', 'N');
    //                     }], DB::raw('order_package.quantity * packages.actual_cost'))
    //                     ->with([
    //                         'packages' => function ($query) {
    //                             $query->select([
    //                                 'packages.id',
    //                                 'packages.title',
    //                                 'packages.actual_cost',
    //                                 'packages.event_id',
    //                             ])
    //                                 ->withCount(['orderPackages as total_quantity' => function ($query) {
    //                                     $query->whereHas('order', function ($query) {
    //                                         $query->where('paymentstatus', '!=', 'N');
    //                                     })->select(DB::raw('sum(quantity)'));
    //                                 }])
    //                                 ->selectSub(function ($subquery) {
    //                                     $subquery->from('order_package')
    //                                         ->join('orders', 'order_package.order_id', '=', 'orders.id')
    //                                         ->whereColumn('order_package.package_id', 'packages.id')
    //                                         ->where('orders.paymentstatus', '!=', 'N')
    //                                         ->select(DB::raw('sum(order_package.quantity * packages.actual_cost)'));
    //                                 }, 'total_amount_received');
    //                         }
    //                     ]);
    //             }
    //         ])
    //         ->get();


    //     // return $organizers;
    //     return view('pages.salesreport.report', compact('organizers'));
    // }


    public function test(Request $request)
    {
        // Get the payment method filter from the request
        $paymentMethod = $request->input('payment_method');

        $organizers = User::select('id', 'name', 'email')
            ->where('role', 'o')
            ->with(['organizer']) // Load the organizer details
            ->with([
                'events' => function ($query) use ($paymentMethod) {
                    $query->select([
                        'events.id',
                        'events.title',
                        'events.organizer_id',
                        'events.date',
                    ])
                        ->withSum(['packages as total_event_amount' => function ($query) use ($paymentMethod) {
                            $query->join('order_package', 'packages.id', '=', 'order_package.package_id')
                                ->join('orders', 'orders.id', '=', 'order_package.order_id')
                                ->where('orders.paymentstatus', '!=', 'N');

                            // Add payment method filter if specified
                            if ($paymentMethod) {
                                $query->where('orders.paymentmethod', $paymentMethod);
                            }
                        }], DB::raw('order_package.quantity * packages.actual_cost'))
                        ->addSelect(DB::raw('(SELECT o.cm_type FROM organizers o 
                            JOIN users u ON o.user_id = u.id 
                            WHERE u.id = events.organizer_id) as cm_type'))
                        ->addSelect(DB::raw('(SELECT o.cm_value FROM organizers o 
                            JOIN users u ON o.user_id = u.id 
                            WHERE u.id = events.organizer_id) as cm_value'))
                        ->withSum(['packages as admin_amount' => function ($query) use ($paymentMethod) {
                            $query->join('order_package', 'packages.id', '=', 'order_package.package_id')
                                ->join('orders', 'orders.id', '=', 'order_package.order_id')
                                ->where('orders.paymentstatus', '!=', 'N');

                            // Add payment method filter if specified
                            if ($paymentMethod) {
                                $query->where('orders.paymentmethod', $paymentMethod);
                            }
                        }], DB::raw('CASE 
                        WHEN (SELECT o.cm_type FROM organizers o 
                            JOIN users u ON o.user_id = u.id 
                            WHERE u.id = events.organizer_id) = "percentage"
                        THEN (order_package.quantity * packages.actual_cost) * 
                            ((SELECT o.cm_value FROM organizers o 
                            JOIN users u ON o.user_id = u.id 
                            WHERE u.id = events.organizer_id) / 100)
                        ELSE (SELECT o.cm_value FROM organizers o 
                            JOIN users u ON o.user_id = u.id 
                            WHERE u.id = events.organizer_id)
                        END'))
                        ->withSum(['packages as totalamount' => function ($query) use ($paymentMethod) {
                            $query->join('order_package', 'packages.id', '=', 'order_package.package_id')
                                ->join('orders', 'orders.id', '=', 'order_package.order_id')
                                ->where('orders.paymentstatus', '!=', 'N');

                            // Add payment method filter if specified
                            if ($paymentMethod) {
                                $query->where('orders.paymentmethod', $paymentMethod);
                            }
                        }], DB::raw('order_package.quantity * packages.actual_cost'))
                        ->with([
                            'packages' => function ($query) use ($paymentMethod) {
                                $query->select([
                                    'packages.id',
                                    'packages.title',
                                    'packages.actual_cost',
                                    'packages.event_id',
                                ])
                                    ->withCount(['orderPackages as total_quantity' => function ($query) use ($paymentMethod) {
                                        $query->whereHas('order', function ($query) use ($paymentMethod) {
                                            $query->where('paymentstatus', '!=', 'N');

                                            // Add payment method filter if specified
                                            if ($paymentMethod) {
                                                $query->where('paymentmethod', $paymentMethod);
                                            }
                                        })->select(DB::raw('sum(quantity)'));
                                    }])
                                    ->selectSub(function ($subquery) use ($paymentMethod) {
                                        $subquery->from('order_package')
                                            ->join('orders', 'order_package.order_id', '=', 'orders.id')
                                            ->whereColumn('order_package.package_id', 'packages.id')
                                            ->where('orders.paymentstatus', '!=', 'N');

                                        // Add payment method filter if specified
                                        if ($paymentMethod) {
                                            $subquery->where('orders.paymentmethod', $paymentMethod);
                                        }

                                        $subquery->select(DB::raw('sum(order_package.quantity * packages.actual_cost)'));
                                    }, 'total_amount_received');
                            }
                        ]);
                }
            ])
            ->get();

        // Get available payment methods for the filter dropdown
        $paymentMethods = DB::table('orders')
            ->select('paymentmethod')
            ->where('paymentstatus', '!=', 'N')
            ->distinct()
            ->pluck('paymentmethod');

        return view('pages.salesreport.report', compact('organizers', 'paymentMethods', 'paymentMethod'));
    }
    public function sendDailyReport(Request $request)
{
    $request->validate([
        'event_id' => 'required|exists:events,id',
        'report_date' => 'required|date',
    ]);

    $reportDate = \Carbon\Carbon::parse($request->get('report_date'))->toDateString();

    $eventinfo = Event::find($request->get('event_id'));
    $orginfo = User::find($eventinfo->organizer_id);

    $orders = Order::whereHas('orderPackages.package', function ($query) use ($eventinfo) {
            $query->where('event_id', $eventinfo->id);
        })
        ->whereDate('created_at', $reportDate)
        ->with(['user', 'orderPackages.package'])
        ->get();
    
    $reportDate = Carbon::parse($request->get('report_date'))->toDateString();

    Mail::to($orginfo->email)->send(
        new DailyEventOrderSummaryMail($orginfo, $eventinfo, $orders, $reportDate)
    );
    

    return back()->with('success', 'Daily report sent successfully.');
}

}
