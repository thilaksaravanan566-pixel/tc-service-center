<?php

namespace App\Http\Controllers;

use App\Models\DynamicPage;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function show($slug)
    {
        // Try resolving a native page first, if it fails, throw 404 naturally
        $page = DynamicPage::where('slug', $slug)->where('is_published', true)->firstOrFail();
        
        return view('pages.show', compact('page'));
    }
}
