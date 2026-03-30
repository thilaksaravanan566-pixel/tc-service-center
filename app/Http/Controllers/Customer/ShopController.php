<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\SparePart;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $query = SparePart::query()->where('stock', '>', 0)->where('is_active', true);

        // Search filter
        if ($request->filled('search')) {
            $search = '%' . $request->search . '%';
            $query->whereNested(function ($q) use ($search) {
                $q->where('name', 'like', $search)
                  ->orWhere('brand', 'like', $search)
                  ->orWhere('category', 'like', $search)
                  ->orWhere('description', 'like', $search);
            });
        }

        // Category filter
        if ($request->filled('category') && $request->category !== 'all') {
            $query->where('category', $request->category);
        }

        // Sort
        $sort = $request->get('sort', 'latest');
        match ($sort) {
            'price_asc'  => $query->orderBy('price', 'asc'),
            'price_desc' => $query->orderBy('price', 'desc'),
            'name_asc'   => $query->orderBy('name', 'asc'),
            default      => $query->latest(),
        };

        $parts = $query->get();

        // Get unique categories for filter tabs
        $categories = SparePart::where('stock', '>', 0)
                                ->where('is_active', true)
                                ->whereNotNull('category')
                                ->distinct()
                                ->pluck('category')
                                ->sort()
                                ->values();

        return view('shop.index', compact('parts', 'categories'));
    }

    public function show($id)
    {
        $part = SparePart::findOrFail($id);
        return view('shop.show', compact('part'));
    }
}