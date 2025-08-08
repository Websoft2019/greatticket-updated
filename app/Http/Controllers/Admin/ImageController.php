<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageController extends Controller
{
    public function index($eid)
    {
        $images = Image::where('event_id',$eid)->get();
        session()->put('e_id',$eid);
        return view('pages.images.index', compact('images'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.images.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);

        $photoPath = $request->file('photo')->store('images', 'public');

        Image::create([
            'event_id' => session()->get('e_id'),
            'photo' => $photoPath,
        ]);

        return redirect()->route('organizer.event.image.index',session()->get('e_id'))->with('success', 'Image uploaded successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $image = Image::findOrFail($id);
        return view('images.show', compact('image'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $image = Image::findOrFail($id);
        return view('images.edit', compact('image'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'photo' => 'sometimes|image|mimes:jpeg,png,webp,jpg,gif,svg|max:2048',
        ]);

        $image = Image::findOrFail($id);

        if ($request->hasFile('photo')) {
            // Delete the old photo
            Storage::disk('public')->delete($image->photo);

            $file = $request->file('photo');
            $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $filename = $originalName . '_' . Str::uuid() . '_' . time() . '.' . $extension;
            $photoPath = $file->storeAs('images', $filename, 'public');
            // Store the new photo
            $photoPath = $request->file('photo')->store('images', 'public');
        } else {
            $photoPath = $image->photo;
        }

        $image->update([
            'event_id' => session()->get('e_id'),
            'photo' => $photoPath,
        ]);

        return redirect()->route('organizer.event.image.index',session()->get('e_id'))->with('success', 'Image updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $image = Image::findOrFail($id);

        // Delete the photo
        Storage::disk('public')->delete($image->photo);

        $image->delete();

        return redirect()->route('organizer.event.image.index',session()->get('e_id'))->with('success', 'Image deleted successfully.');
    }
}
