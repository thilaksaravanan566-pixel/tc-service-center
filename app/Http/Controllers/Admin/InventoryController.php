<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SparePart;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index()
    {
        $parts = SparePart::latest()->get();
        return view('admin.inventory.index', compact('parts'));
    }

    public function updateStock(Request $request, $id)
    {
        $part = SparePart::findOrFail($id);
        $part->increment('stock', $request->quantity); // Updates stock for TC Service Center
        
        return back()->with('success', "Stock for {$part->name} updated successfully.");
    }
}