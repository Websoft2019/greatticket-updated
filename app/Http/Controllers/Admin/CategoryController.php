<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(){
        $categories = Category::all();
        return view('pages.event.category.index', compact('categories'));
    }

    public function store(Request $request){
        $request->validate([
            'name' => 'required|max:255',
            'status' => 'required|in:show,hide',
        ]);

        $category = new Category();
        $category->name = $request->name;
        $category->status = $request->status;
        $category->save();
        return redirect()->route('admin.event.category.index');
    }

    public function update(Request $request, $id){
        $request->validate([
            'name' => 'required|max:255',
            'status' => 'required|in:show,hide',
        ]);

        $category = Category::findOrFail($id);
        $category->name = $request->name;
        $category->status = $request->status;
        $category->save();
        return redirect()->route('admin.event.category.index');
    }

    public function destroy($id){
        $category = Category::findOrFail($id);
        $category->delete();
        return redirect()->route('admin.event.category.index');
    }
}
