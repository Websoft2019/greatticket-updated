<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\OrganizerCreatedMail;
use App\Mail\OrganizerVerifiedMail;
use App\Models\Organizer;
use App\Models\Religion;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;

class OrganizerController extends Controller
{
    // Display a listing of the organizers
    public function index()
    {
        $organizers = User::where('role', 'o')->latest()->with('organizer')->get(); // Assuming 'role' column determines the role
        return view('pages.organizer.index', compact('organizers'));
    }

    // Show the form for creating a new organizer
    public function create()
    {
        $religions = Religion::all();
        return view('pages.organizer.create', compact('religions'));
    }

    // Store a newly created organizer in storage
    public function store(Request $request)
    {
        // return $request;
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            // 'gender' => 'required|string|max:10',
            // 'dob' => 'required|date|before_or_equal:' . Carbon::today()->toDateString(),
            'religion_id' => 'sometimes|exists:religions,id',
            'photo' => 'sometimes|mimes:jpg,png,jpeg,webp,gif|max:2048',
            'address' => 'nullable|string|max:255',
            'contact' => 'sometimes|string|max:15',
            'about' => 'nullable|string|max:500',
            'cm_type' => 'required|string|in:flat,percentage',
            'cm_value' => 'required|numeric|min:0',
        ]);

        $path = null;
        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('organizer', 'public');
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'password_reset_required' => true,
            'role' => 'o', // Setting role to 'o'
            // 'gender' => $request->gender,
            // 'dob' => $request->dob,
            // 'religion_id' => $request->religion_id,
            'contact' => $request->contact,
        ]);

        Organizer::create([
            'user_id' => $user->id,
            'photo' => $path,
            'address' => $request->address,
            'about' => $request->about,
            'cm_type' => $request->cm_type,
            'cm_value' => $request->cm_value,
            'verify' => true,
        ]);

        return redirect()->route('admin.organizer.index')->with('success', 'Organizer created successfully.');
    }

    // Show the form for editing the specified organizer
    public function edit($id)
    {
        $organizer = User::findOrFail($id);
        return view('pages.organizer.edit', compact('organizer'));
    }

    // Update the specified organizer in storage
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'password' => 'nullable|string|min:8',
            'photo' => 'sometimes|mimes:jpg,png,jpeg,webp,gif|max:2048',
            'address' => 'nullable|string|max:255',
            'contact' => 'sometimes|max:15',
            'about' => 'nullable|string|max:500',
            'cm_type' => 'required|string|in:flat,percentage',
            'cm_value' => 'required|numeric|min:0',
        ]);
        $user = User::findOrFail($id);
        $path = $user->organizer->photo;
        if ($request->hasFile('photo')) {
            // Delete the old photo if exists
            if ($path) {
                Storage::disk('public')->delete($path);
            }
            $path = $request->file('photo')->store('user', 'public');
        }

        // Updating user fields, and only updating the password if it's present in the request
        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => 'o', // Setting role to 'o'
            'contact' => $request->contact,
        ];

        // Conditionally adding password to the update array if it's provided
        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password); // No need to hash here
            $updateData['password_reset_required'] = true; // Set reset required only if password changes
        }

        $user->update($updateData);

        // Updating organizer fields
        $user->organizer->update([
            'photo' => $path,
            'address' => $request->address,
            'about' => $request->about,
            'cm_type' => $request->cm_type,
            'cm_value' => $request->cm_value,
        ]);

        return redirect()->route('admin.organizer.index')->with('success', 'Organizer updated successfully.');
    }

    public function organizerCreate()
    {
        return view('site.register-organizer');
    }

    public function organizerStore(Request $request)
    {
        // Validate the inputs
        $request->validate([
            'user.name' => 'required|string|max:255',
            'user.email' => 'required|email|unique:users,email',
            'user.password' => 'required|min:6|confirmed',
            'user.phone' => 'required|string|max:15',
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'about' => 'required|string|max:500',
            'address' => 'required|string|max:255',
        ]);

        // Create the user
        $user = User::create([
            'name' => $request->input('user.name'),
            'email' => $request->input('user.email'),
            'password' => Hash::make($request->input('user.password')),
            'contact' => $request->input('user.phone'),
            'role' => 'o',
        ]);

        // Create the organizer
        $data = $request->except('photo', 'user');
        $data['user_id'] = $user->id;

        // Handle photo upload
        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('user', 'public');
        }

        $organizer = Organizer::create($data);
        $adminEmail = env('MAIL_USERNAME');
        Mail::to($adminEmail)->bcc('dkc1549@gmail.com')->send(new OrganizerCreatedMail($organizer));
        notify('success', 'Organizer register successfully');

        return redirect()->route('getHome');
    }

    public function verify($id)
    {
        $user = User::findOrFail($id);
        $user->organizer->verify = true;
        $user->organizer->save();
        // Send email to organizer
        Mail::to($user->email)->bcc('dkc1549@gmail.com')->send(new OrganizerVerifiedMail($user->organizer));

        return redirect()->route('admin.organizer.index')->with('success', 'Organizer verified and email sent.');
    }

    // Remove the specified organizer from storage
    public function delete($id)
    {
        $organizer = User::findOrFail($id);
        if ($organizer->photo) {
            Storage::disk('public')->delete($organizer->photo);
        }
        $organizer->delete();

        return redirect()->route('admin.organizer.index')->with('success', 'Organizer deleted successfully.');
    }
}
