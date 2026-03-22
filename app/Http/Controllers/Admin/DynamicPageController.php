<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DynamicPage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DynamicPageController extends Controller
{
    public function index()
    {
        $pages = DynamicPage::latest()->get();
        return view('admin.customization.pages.index', compact('pages'));
    }

    public function create()
    {
        return view('admin.customization.pages.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'blocks' => 'required|array'
        ]);

        $page = DynamicPage::create([
            'title' => $request->title,
            'slug' => Str::slug($request->title) . '-' . uniqid(),
            'content_blocks' => $request->blocks,
            'is_published' => $request->has('is_published')
        ]);

        return redirect()->route('admin.pages.index')->with('success', 'Dynamic page compiled successfully.');
    }

    public function edit($id)
    {
        $page = DynamicPage::findOrFail($id);
        return view('admin.customization.pages.edit', compact('page'));
    }

    public function update(Request $request, $id)
    {
        $page = DynamicPage::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'blocks' => 'required|array'
        ]);

        $page->update([
            'title' => $request->title,
            'slug' => Str::slug($request->title) . '-' . uniqid(), // Prevent conflict on rename
            'content_blocks' => $request->blocks,
            'is_published' => $request->has('is_published')
        ]);

        return redirect()->route('admin.pages.index')->with('success', 'Page content updated.');
    }

    public function destroy($id)
    {
        DynamicPage::findOrFail($id)->delete();
        return back()->with('success', 'Page removed off the server.');
    }
}
