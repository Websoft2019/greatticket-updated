<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\Event;
use App\Models\Package;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderPackage;
use Illuminate\Http\Request;

class HomeController extends Controller
{
        /**
     * Create a new controller instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $userCount = User::where('role','u')->count();
        $organizerCount = User::where('role', 'o')->count();
        $eventCount = Event::count();
        $package = Package::count();
        $data = [
            'userCount' => $userCount,
            'organizerCount' => $organizerCount,
            'eventCount' => $eventCount,
            'packageCount' => $package,
        ];
        return view('pages.dashboard', $data);
    }

    public function organizer(){
        $user_id = auth()->id();
        $eventCount = Event::where('organizer_id',$user_id)->count();
        $packageCount = Package::whereIn('event_id',function($query) use ($user_id){
            $query->select('id')
                    ->from('events')
                    ->where('organizer_id',$user_id);
        })->count();
        
        // Events with order counts and date
        $events = Event::where('organizer_id', $user_id)
            ->whereHas('packages.orderPackages.order', function ($query) {
                $query->where('paymentstatus', 'Y');
            })
            ->with(['packages.orderPackages.order' => function ($query) {
                $query->where('paymentstatus', 'Y');
            }])
            ->get();

        $eventData = $events->map(function ($event) {
            $orders = $event->packages
                ->flatMap(fn($pkg) => $pkg->orderPackages)
                ->pluck('order')
                ->filter(fn($order) => $order && $order->paymentstatus === 'Y')
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

        
        // for oranizer
        $logOrgID = User::where('id', auth()->id())->with('organizer')->first()->id;
        $eventsID = Event::where('organizer_id', $logOrgID)->get('id');
        $packagesId =  Package::whereIn('event_id', $eventsID)->get('id');
        $ordersID = OrderPackage::whereIn('package_id', $packagesId)->get('order_id');
        $totalOrders = Order::whereIn('id', $ordersID)
            ->count();
            // dd($totalOrders);
        $completedorder = Order::where('paymentstatus', 'Y')
            ->whereIn('id', $ordersID)
            ->count();

        $grandtotalAmt = Order::where('paymentstatus', 'Y')
            ->whereIn('id', $ordersID)
            ->sum('grandtotal');

        $data = [
            'eventCount' => $eventCount,
            'packageCount' => $packageCount,
            'totalOrders' => $totalOrders,
            'completedorder' => $completedorder,
            'grandtotalAmt' => $grandtotalAmt,
            'events' => $eventData,
        ];
        return view('pages.organizer-dashboard', $data);
    }

    public function deleteAccount($id){
        $user = User::findOrFail($id);
        $user->delete();
         
        return redirect()->route('page', ['page' => 'user-management']);
    }

    public function mobile($payer_id){
        $order = Order::where('payerid', $payer_id)->firstOrFail();
        return view('mobile',compact('order'));
    }
}
