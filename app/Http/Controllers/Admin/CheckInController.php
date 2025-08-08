<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Package;
use App\Models\TicketUser;
use Illuminate\Http\Request;

class CheckInController extends Controller
{
    public function events()
    {
        // $events = Event::where('date', '<=', now())->latest()->get();
        $events = Event::latest()->get();
        return view('pages.checkin.index', compact('events'));
    }

    public function listCheckedin($id)
    {
        $event = Event::select('id', 'title', 'date')->where('id', $id)->firstOrFail();
        // $event->load('packages.orderPackages.ticketUsers');
        $event->load([
            'packages' => function ($query) {
                $query->select('id', 'event_id', 'title');
            },
            'packages.orderPackages' => function ($query) {
                $query->select('id', 'package_id', 'quantity');
            },
            'packages.orderPackages.ticketUsers' => function ($query) {
                $query->select('id', 'order_package_id', 'name', 'qr_code', 'qr_image', 'checkedin');
            }
        ]);
        // return $event;
        return view('pages.checkin.checkedin', compact('event'));
    }

    public function checkin(Request $request, $id)
    {
        $event = Event::findOrFail($id);
        $request->validate([
            'code' => 'required|exists:ticket_users,qr_code',
        ]);

        $ticketUser = TicketUser::where('qr_code', $request->code)->firstOrFail();

        // Check if the ticketUser belongs to the current event using relationships
        $isValidEvent = $ticketUser->orderPackage->package->event->id === $event->id;

        if (!$isValidEvent) {
            return redirect()->back()->with('error', "QR code is not valid for this event.");
        }

        if ($ticketUser->checked_in) {
            return redirect()->back()->with('error', "Already registered");
        }
        $ticketUser->checkedin = now();
        $ticketUser->save();

        return redirect()->route('admin.checkin.checkin', $id)->with('success', "$ticketUser->name CheckedIn Successfully");
    }

    public function packageListCheckedin($id){
        $package = Package::findOrFail($id);
        // $event->load('packages.orderPackages.ticketUsers');
        $package->load([
            'orderPackages' => function ($query) {
                $query->select('id', 'package_id', 'quantity');
            },
            'orderPackages.ticketUsers' => function ($query) {
                $query->select('id', 'order_package_id', 'name', 'qr_code', 'qr_image', 'checkedin');
            }
        ]);
        // return $event;
        return view('pages.checkin.package-checkedin', compact('package'));
    }

    public function packageCheckin(Request $request, $id){
        $package = Package::findOrFail($id);
        $request->validate([
            'code' => 'required|exists:ticket_users,qr_code',
        ]);

        $ticketUser = TicketUser::where('qr_code', $request->code)->firstOrFail();

        // Check if the ticketUser belongs to the current event using relationships
        $isValidPackage = $ticketUser->orderPackage->package->id === $package->id;

        if (!$isValidPackage) {
            return redirect()->back()->with('error', "QR code is not valid for this package.");
        }

        if ($ticketUser->checked_in) {
            return redirect()->back()->with('error', "Already registered");
        }
        $ticketUser->checkedin = now();
        $ticketUser->save();

        return redirect()->route('admin.checkin.package.checkin', $id)->with('success', "$ticketUser->name CheckedIn Successfully");
    }
}
