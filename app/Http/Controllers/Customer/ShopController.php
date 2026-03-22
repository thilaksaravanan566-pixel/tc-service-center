<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\SparePart;

class ShopController extends Controller
{
    public function index()
    {
        // Showcase only parts currently in stock
        $parts = SparePart::where('stock', '>', 0)->latest()->get();
        return view('shop.index', compact('parts'));
    }

    public function show($id)
    {
        $part = SparePart::findOrFail($id);
        return view('shop.show', compact('part'));
    }
}