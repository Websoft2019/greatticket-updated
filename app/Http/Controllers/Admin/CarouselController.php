<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Carousel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CarouselController extends Controller
{
    public function index()
    {
        $carousels = Carousel::all();
        return view('pages.carousel.index', compact('carousels'));
    }

    public  function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',  // You can change the validation as needed
        ]);
        $imagePath = $request->file('image')->store('images', 'public');

        // Create a new Carousel entry and store the image path
        $carousel = new Carousel();
        $carousel->image = $imagePath;
        $carousel->save();
        return redirect()->route('admin.carousel.index')->with('success', "Carousel created successfully");
    }

    public function update(Request $request, $id)
    {
        $carousel = Carousel::findOrFail($id);
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',  // You can change the validation as needed
        ]);
        $imagePath = $request->file('image')->store('images', 'public');

        // Create a new Carousel entry and store the image path
        if ($carousel->image) {
            Storage::disk('public')->delete($carousel->image);
        }
        $carousel->image = $imagePath;
        $carousel->save();
        return redirect()->route('admin.carousel.index')->with('success', "Carousel updated successfully");
    }

    public function destroy($id)
    {
        $carousel = Carousel::findOrFail($id);
        // Delete the image file from storage
        if ($carousel->image) {
            Storage::disk('public')->delete($carousel->image);
        }

        // Delete the carousel record from the database
        $carousel->delete();

        return redirect()->route('admin.carousel.index')->with('success', "Carousel delete successfully");
    }
}
