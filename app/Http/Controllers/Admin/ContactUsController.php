<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ContactUs;

class ContactUsController extends Controller
{
    public function index()
    {
        //get contact us data and pass it to view
        $messages = ContactUs::all();
        return view('pages.contact_us.index', compact('messages'));
    }

    public function destroy($id)
    {
        $contact_us = ContactUs::findOrFail($id);
        //dd($contact_us);

        // Delete the contact_us
        $contact_us->delete();

        // Redirect with success message
        return redirect()->route('admin.contactUs')->with('success', 'message deleted successfully.');
    }
}
