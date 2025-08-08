<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\TrendingEvent;
use Illuminate\Http\Request;

class TrendingEventController extends Controller
{
    public function index()
    {
        $trendingEvents = TrendingEvent::with('event')->get();
        $events = Event::where('date', '>=', now())->get();
        return view('pages.trending-events.index', compact('trendingEvents', 'events'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'event_id' => 'required|exists:events,id',
            'priority' => 'required|integer|min:1',
        ]);

        TrendingEvent::create($request->all());
        return redirect()->route('admin.trending-events.index')->with('success', 'Trending Event added successfully.');
    }

    public function destroy($id)
    {
        $trendingEvent = TrendingEvent::findOrFail($id);
        $trendingEvent->delete();

        return redirect()->route('admin.trending-events.index')->with('success', 'Trending Event deleted successfully.');
    }
}
