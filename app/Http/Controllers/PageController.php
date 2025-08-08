<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\PrivacyPolicy;
use App\Models\TermsCondition;
use Illuminate\Http\Request;
use App\Models\User;

class PageController extends Controller
{
    /**
     * Display all the static pages when authenticated
     *
     * @param string $page
     * @return \Illuminate\View\View
     */
    public function index(string $page)
    {
        $users = User::all();
        if (view()->exists("pages.{$page}")) {
            return view("pages.{$page}",compact("users"));
        }

        return abort(404);
    }

    public function profile()
    {
        return view("pages.profile-static");
    }

    public function signin()
    {
        return view("pages.sign-in-static");
    }

    public function signup()
    {
        return view("pages.sign-up-static");
    }

    public function privacy(){
        $privacy = optional(PrivacyPolicy::first());
        return view('site.privacy-policy',compact('privacy'));
    }

    public function termsCondition(){
        $term = optional(TermsCondition::first());
        return view('site.terms-condition', compact('term'));
    }
    
    public function test()
    {
        $reportDate = now()->subDay()->toDateString();

        // Get all organizers with their events
        // $organizers = User::where('role', 'o')
        //     ->with(['events'])
        //     ->get();
        // $organizers = User::where('email', 'jujutsukaisen011011@gmail.com')->get();
        $organizer = User::where('email', 'gtorganizer590@gmail.com')->first();

        $totalEmailsSent = 0;
        $totalOrganizers = $organizer->count();


        // foreach ($organizer->events as $event) {
        $event = $organizer->events->last();
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
        return view('pages.billing', compact('orders', 'organizer', 'event'));
    }
    
    public function testTicket(){

        $order = Order::where('paymentstatus', 'Y')->latest()->first();
        $order->load('orderPackages.package.event', 'orderPackages.ticketUsers');

        $data = [];
        $totalTickets = 0;

        foreach ($order->orderPackages as $pack) {
            $totalTickets += $pack->quantity;

            $data[$pack->package->id] = [
                'event' => $pack->package->event->title,
                'event_date' => $pack->package->event->date ?? null,
                'event_time' => $pack->package->event->time,
                'venue' => $pack->package->event->vennue,
                'package' => $pack->package,
                'ticket_users' => $pack->ticketUsers,
                'poster' => $pack->package->event->primary_photo,
                'organizer_photo' => $pack->package->event->user->organizer->photo ?? null,
                'organizer_name' => $pack->package->event->user->name ?? null,
            ];
        }

        $purchaseDetails = [
            'customer_name' => $order->name ?? 'Test User',
            'data' => $data,
            'total_tickets' => $totalTickets,
            'total_price' => $order->grandtotal,
        ];


        return view('pages.tables', compact('purchaseDetails'));
    }
}
