<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PrivacyPolicy;
use Illuminate\Http\Request;

class PrivacyPolicyController extends Controller
{
    public function index(){
        $privacy = PrivacyPolicy::firstOrFail();
        return view("pages/privacy-policy",compact("privacy"));
    }

    public function update(Request $request, $id){
        $privacy = PrivacyPolicy::findOrFail($id);
        $request->validate([
            "title" => "required | max:255",
            "description" => "sometimes|string",
        ]);
        $privacy->update($request->all());
        return redirect()->route("admin.page.privacy.index")->with("success","");
    }
}
