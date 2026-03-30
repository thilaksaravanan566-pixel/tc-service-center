<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SparePart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SparePartController extends Controller
{
    /**
     * Display the Amazon-style inventory list.
     * Fixes the "Undefined variable $spareParts" error.
     */
    public function index()
    {
        // Fetch all parts, most recent first
        $spareParts = SparePart::latest()->get();

        // Ensure the variable name in compact() matches the Blade file
        return view('admin.stock.index', compact('spareParts'));
    }

    /**
     * Show the form for creating a new spare part.
     */
    public function create()
    {
        $categories = \App\Models\PartCategory::active()->orderBy('name')->get();
        return view('admin.stock.create', compact('categories'));
    }

    /**
     * Store a newly created spare part in the database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required',
            'color' => 'nullable|string|max:50',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $data = $request->except('image');

        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('spare_parts', 'public');
        }

        SparePart::create($data);

        return redirect()->route('admin.parts.index')->with('success', 'Part added to inventory!');
    }
    /**
     * Show the form for editing the specified spare part.
     */
    public function edit(SparePart $part)
    {
        $categories = \App\Models\PartCategory::active()->orderBy('name')->get();
        return view('admin.stock.edit', ['sparePart' => $part, 'categories' => $categories]);
    }

    /**
     * Update the specified spare part in storage.
     */
    public function update(Request $request, SparePart $part)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required',
            'color' => 'nullable|string|max:50',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $data = $request->except('image');

        if ($request->hasFile('image')) {
            if ($part->image_path && Storage::disk('public')->exists($part->image_path)) {
                Storage::disk('public')->delete($part->image_path);
            }
            $data['image_path'] = $request->file('image')->store('spare_parts', 'public');
        }

        $part->update($data);

        return redirect()->route('admin.parts.index')->with('success', 'Spare part updated successfully!');
    }

    /**
     * Remove the specified spare part from storage.
     */
    public function destroy(SparePart $part)
    {
        if ($part->image_path && Storage::disk('public')->exists($part->image_path)) {
            Storage::disk('public')->delete($part->image_path);
        }
        $part->delete();

        return redirect()->route('admin.parts.index')->with('success', 'Spare part deleted successfully!');
    }
}