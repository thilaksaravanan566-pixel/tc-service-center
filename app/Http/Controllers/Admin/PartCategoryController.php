<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PartCategory;
use Illuminate\Http\Request;

class PartCategoryController extends Controller
{
    public function index()
    {
        $categories = PartCategory::orderBy('name')->get();
        return view('admin.part-categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:part_categories,name',
        ]);

        PartCategory::create([
            'name' => $request->name,
            'status' => 'active',
        ]);

        return redirect()->back()->with('success', 'Category added successfully!');
    }

    public function update(Request $request, $id)
    {
        $category = PartCategory::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255|unique:part_categories,name,'.$category->id,
            'status' => 'required|in:active,inactive',
        ]);

        $category->update([
            'name' => $request->name,
            'status' => $request->status,
        ]);

        return redirect()->back()->with('success', 'Category updated successfully!');
    }

    public function destroy($id)
    {
        // Instead of hard deleting which could break history, we just deactivate
        PartCategory::findOrFail($id)->update(['status' => 'inactive']);
        return redirect()->back()->with('success', 'Category disabled.');
    }
}
