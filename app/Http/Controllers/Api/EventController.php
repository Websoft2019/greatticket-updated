<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\EventResource;
use App\Http\Resources\TrendingResource;
use App\Models\Category;
use App\Models\Event;
use App\Models\TrendingEvent;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

use function PHPUnit\Framework\isEmpty;

class EventController extends BaseApiController
{
    public function index()
    {
        $now = now();
        try {
            // $events = Event::where('date', '>', $now)->orderBy("created_at", "desc")->paginate(10);
            // $events = Event::where(function ($query) {
            //     $query->where('date', '>', now()->toDateString())  // Future events
            //         ->orWhere(function ($query) {
            //             $query->where('date', now()->toDateString())  // Today's events
            //                 ->where('time', '>=', now()->toTimeString()); // Until the current time
            //         });
            // })
            //     // ->orderBy('date', 'asc')
            //     // ->orderBy('time', 'asc')
            //     ->orderBy("created_at", "desc")
            //     ->paginate(10);
            $events = Event::where('status', true)
                    ->where(function ($query) {
                        $query->where('date', '>', now()->toDateString()) // Future events
                            ->orWhere(function ($subQuery) {
                                $subQuery->where('date', '=', now()->toDateString()) // Today’s events
                                    ->where('time', '>', now()->toTimeString()); // Only events later today
                            });
                    })
                    ->orderBy("created_at", "desc")
                    ->paginate(10);
                    
            $events = [
                'events' => EventResource::collection($events),
                'pagination' => [
                    'total' => $events->total(),
                    'per_page' => $events->perPage(),
                    'current_page' => $events->currentPage(),
                    'last_page' => $events->lastPage(),
                    'from' => $events->firstItem(),
                    'to' => $events->lastItem()
                ]
            ];
            return $this->sendResponse($events, "Events fetched successfully", 200);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return $this->sendError($e->getMessage(), "Cannot fetch event", 500);
        }
    }

    public function show($id)
    {
        try {
            $event = Event::where('status',true)->with('images')->findOrFail($id);
            $event = new EventResource($event);
            return $this->sendResponse($event, 'Event fetched successfully', 200);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return $this->sendError($e->getMessage(), 'Cannot fetch event', 500);
        }
    }

    public function search(Request $request)
    {
        $now = now();
        // Validate the search query
        $validated = Validator::make($request->all(), [
            'name' => 'required|string|min:1',
        ]);

        if ($validated->fails()) {
            return $this->sendError($validated->errors(), "Validation fails", 422);
        }

        try {
            // Extract the search query from the request
            $query = $request->input('name');

            // Perform the search using the Event model
            $events = Event::where('status', true)->where('title', 'like', "%{$query}%")->where('date', '>', $now)->limit(10)->get();
            $events = EventResource::collection($events);
            // Return JSON response with the matching events
            return $this->sendResponse($events, "Events fetched successfully", 200);
        } catch (\Exception $e) {
            // Log the exception
            Log::error('Search query failed', [
                'error' => $e->getMessage(),
                'query' => $query,
                'trace' => $e->getTraceAsString(),
            ]);

            // Return JSON response with an error message
            return $this->sendError('Cannot fetch events', 'An error occurred while fetching events', 500);
        }
    }

    public function category()
    {
        $categories = Category::where('status', 'show')->get();
        $categories = CategoryResource::collection($categories);
        return $this->sendResponse($categories, "Categories fetched successfully", 200);
    }

    public function categoryFilter($id)
    {
        $now = now();
        // Check if the category exists
        $categoryExists = Category::where('id', $id)->exists();

        if (!$categoryExists) {
            // If the category does not exist, return an error response
            return $this->sendError("Category not found", [], 404);
        }

        $events = Event::where('category_id', $id)->where('date', '>', $now)->get();
        $events = EventResource::collection($events);
        return $this->sendResponse($events, "Events fetched successfully", 200);
    }

    public function trending()
    {
        $events = Event::where('date', '>=', Carbon::today())
            ->withCount(['packages as bookings_count' => function ($query) {
                $query->join('order_package', 'order_package.package_id', '=', 'packages.id')
                    ->select(DB::raw('SUM(order_package.quantity)'));
            }])
            ->having('bookings_count', '>', 0) // Ensure we get events with bookings
            ->orderByDesc('bookings_count')
            ->take(8)
            ->get();

        $trendingEvents = TrendingEvent::orderBy('priority', 'desc')->limit(8)->get();
        $trendingEvents = $trendingEvents->pluck('event');
        if ($trendingEvents->isEmpty()) {
            $trendingEvents  = $events;
        }

        if ($trendingEvents) {
            return response()->json([
                'success' => true,
                'message' => 'Trending events found',
                'data' => TrendingResource::collection($trendingEvents),
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'No trending events found',
                'data' => 'Cannot fetch trending events'
            ]);
        }

        // return $this->sendResponse($trendingEvents, "Trending Events fetched successfully", 200);
    }
}
