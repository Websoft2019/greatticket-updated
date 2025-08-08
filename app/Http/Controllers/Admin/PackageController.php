<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PackageController extends Controller
{
    public function index($e_id)
    {
        $packages = Package::where('event_id',$e_id)->get();
        session()->put('e_id',$e_id);
        return view('pages.package.index', compact('packages'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // return session()->get('e_id');
        if(!session()->has('e_id')){
            return redirect()->route('organizer.event.index');
        }
        return view('pages.package.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'cost' => 'required|numeric',
            'discount_price' => 'sometimes|numeric',
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'status' => 'sometimes|boolean',
            'seat_status' => 'sometimes|boolean',
            'description' => 'nullable|string',
            'capacity' => 'required|numeric|min:1',
            'maxticket' => 'required|numeric|min:1',
        ]);
        $validatedData['event_id'] = session()->get('e_id');
        $photoPath = $request->file('photo')->store('photos/package', 'public');
        $validatedData['photo'] = $photoPath;
        $validatedData['actual_cost'] = $request->cost - $request->discount_price;
        // Generate slug from title
        $validatedData['slug'] = Str::slug($request->title)."-".auth()->user()->id;

        Package::create($validatedData);

        return redirect()->route('organizer.event.package.index',session()->get('e_id'))->with('success', 'Package created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $package = Package::findOrFail($id);
        return view('packages.show', compact('package'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $package = Package::findOrFail($id);
        return view('pages.package.edit', compact('package'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'cost' => 'required|numeric',
            'discount_price' => 'sometimes|numeric',
            'photo' => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'status' => 'sometimes|boolean',
            'seat_status' => 'sometimes|boolean',
            'description' => 'nullable|string',
            'capacity' => 'required|numeric|min:1',
            'maxticket' => 'required|numeric|min:1',
        ]);

        $package = Package::findOrFail($id);

        if($request->capacity < $package->consumed_seat){
            return redirect()->back()->with('error', "Capacity is less than consumed seat");
        }

        if ($request->hasFile('photo')) {
            // Delete the old photo
            Storage::disk('public')->delete($package->photo);

            // Store the new photo
            $photoPath = $request->file('photo')->store('photos/package', 'public');
        } else {
            $photoPath = $package->photo;
        }
        $actual_cost = $request->cost - $request->discount_price;

        $package->update([
            'event_id' => session()->get('e_id'),
            'title' => $request->title,
            'cost' => $request->cost,
            'actual_cost' => $actual_cost,
            'status' => $request->status,
            'seat_status' => $request->seat_status,
            'photo' => $photoPath,
            'capacity' => $request->capacity,
            'description' => $request->description,
            'discount_price' => $request->discount_price,
            'maxticket' => $request->maxticket,
        ]);

        return redirect()->route('organizer.event.package.index',session()->get('e_id'))->with('success', 'Package updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $package = Package::findOrFail($id);

        // Delete the photo
        Storage::disk('public')->delete($package->photo);

        $package->delete();

        return redirect()->route('organizer.event.package.index',session()->get('e_id'))->with('success', 'Package deleted successfully.');
    }
}
