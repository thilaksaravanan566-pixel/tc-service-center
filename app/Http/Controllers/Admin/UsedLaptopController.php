<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UsedLaptop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UsedLaptopController extends Controller
{
    /**
     * Display a listing of the used laptops.
     */
    public function index()
    {
        $laptops = UsedLaptop::latest()->paginate(10);
        return view('admin.laptops.index', compact('laptops'));
    }

    /**
     * Show the form for creating a new used laptop.
     */
    public function create()
    {
        return view('admin.laptops.create');
    }

    /**
     * Store a newly created used laptop in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'brand' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'processor' => 'required|string|max:255',
            'gpu' => 'required|string|max:255',
            'ram' => 'required|string|max:255',
            'storage' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'status' => 'required|in:available,sold',
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('laptops', 'public');
        }

        UsedLaptop::create($data);

        return redirect()->route('admin.laptops.index')->with('success', 'Used laptop has been added successfully to your inventory.');
    }

    /**
     * Display the specified used laptop.
     */
    public function show(string $id)
    {
        $laptop = UsedLaptop::findOrFail($id);
        return view('admin.laptops.show', compact('laptop'));
    }

    /**
     * Show the form for editing the specified used laptop.
     */
    public function edit(string $id)
    {
        $laptop = UsedLaptop::findOrFail($id);
        return view('admin.laptops.edit', compact('laptop'));
    }

    /**
     * Update the specified used laptop in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'brand' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'processor' => 'required|string|max:255',
            'gpu' => 'required|string|max:255',
            'ram' => 'required|string|max:255',
            'storage' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'status' => 'required|in:available,sold',
        ]);

        $laptop = UsedLaptop::findOrFail($id);
        $data = $request->all();

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($laptop->image && Storage::disk('public')->exists($laptop->image)) {
                Storage::disk('public')->delete($laptop->image);
            }
            $data['image'] = $request->file('image')->store('laptops', 'public');
        }

        $laptop->update($data);

        return redirect()->route('admin.laptops.index')->with('success', 'Used laptop details updated successfully.');
    }

    /**
     * Remove the specified used laptop from storage.
     */
    public function destroy(string $id)
    {
        $laptop = UsedLaptop::findOrFail($id);
        
        if ($laptop->image && Storage::disk('public')->exists($laptop->image)) {
            Storage::disk('public')->delete($laptop->image);
        }
        
        $laptop->delete();

        return redirect()->route('admin.laptops.index')->with('success', 'Used laptop has been removed from your inventory.');
    }
}
