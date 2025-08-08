<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::where('organizer_id',auth()->user()->id)->get();
        return view('pages.event.index', compact('events'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('pages.event.create', compact('categories'));
    }

    public function store(Request $request)
    {
        // Validate the input data
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'date' => 'required|date',
            'time' => 'required|string',
            'vennue' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id|numeric|max:99999',
            'primary_photo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'seat_view' => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'status' => 'sometimes|boolean',
            'highlight' => 'nullable|string|max:500',
            'longitude' => 'required|numeric',
            'latitude' => 'required|numeric',
            'description' => 'nullable|string',
        ]);
        // Generate slug from title
        $validatedData['slug'] = Str::slug($request->title)."-".auth()->user()->id;

        // Handle file upload and store the file in 'photos' directory of 'public' disk
        if ($request->hasFile('primary_photo')) {
            $validatedData['primary_photo'] = $request->file('primary_photo')->store('photos/event', 'public');
        }
        if ($request->hasFile('seat_view')) {
            $validatedData['seat_view'] = $request->file('seat_view')->store('photos/event', 'public');
        }

        $validatedData['organizer_id'] = auth()->user()->id;

        // Create and save the event using mass assignment
        Event::create($validatedData);  

        // Redirect with success message
        return redirect()->route('organizer.event.index')->with('success', 'Event created successfully.');
    }

    public function edit($id)
    {
        $categories = Category::all();
        $event = Event::findOrFail($id);
        return view('pages.event.edit', compact('event', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $event = Event::findOrFail($id);

        // Validate the input data
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'date' => 'required|date',
            'time' => 'required|string',
            'vennue' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id|numeric|max:99999',
            'status' => 'sometimes|boolean',
            'highlight' => 'nullable|string|max:500',
            'longitude' => 'required|numeric',
            'latitude' => 'required|numeric',
            'description' => 'nullable|string|max:1000',
        ]);

        // Handle file upload
        if ($request->hasFile('primary_photo')) {
            // Delete the old photo
            if ($event->primary_photo) {
                Storage::disk('public')->delete($event->primary_photo);
            }
            // Store the new photo
            $validatedData['primary_photo'] = $request->file('primary_photo')->store('photos/event', 'public');
        }
        if ($request->hasFile('seat_view')) {
            // Delete the old photo
            if ($event->seat_view) {
                Storage::disk('public')->delete($event->seat_view);
            }
            // Store the new photo
            $validatedData['seat_view'] = $request->file('seat_view')->store('photos/event', 'public');
        }

        // Update the event with validated data
        $event->update($validatedData);

        // Redirect with success message
        return redirect()->route('organizer.event.index')->with('success', 'Event updated successfully.');
    }

    public function delete($id)
    {
        $event = Event::findOrFail($id);

        // Delete the associated file from storage
        if ($event->primary_photo) {
            Storage::disk('public')->delete($event->primary_photo);
        }

        // Delete the event
        $event->delete();

        // Redirect with success message
        return redirect()->route('organizer.event.index')->with('success', 'Event deleted successfully.');
    }
    private function generateUniqueSlug($title, $id = 0)
    {
        // Generate initial slug
        $slug = Str::slug($title);
        $originalSlug = $slug;

        // Check if the slug exists in the database
        $slugExists = Event::where('slug', $slug)
            ->where('id', '!=', $id)
            ->exists();

        // Append number if slug exists
        $count = 1;
        while ($slugExists) {
            $slug = $originalSlug . '-' . $count;
            $slugExists = Event::where('slug', $slug)
                ->where('id', '!=', $id)
                ->exists();
            $count++;
        }

        return $slug;
    }
}
