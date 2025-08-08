<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TermsCondition;
use Illuminate\Http\Request;

class TermsConditionController extends Controller
{
    public function index(){
        $term = TermsCondition::firstOrFail();
        return view("pages/terms-condition", compact("term"));
    }

    public function update(Request $request, $id){
        $term = TermsCondition::findOrFail($id);
        $request->validate([
            "title" => "required | max:255",
            "description" => "sometimes|string",
        ]);
        $term->update($request->all());
        return redirect()->route("admin.page.term.index")->with("success","");
    }
}
