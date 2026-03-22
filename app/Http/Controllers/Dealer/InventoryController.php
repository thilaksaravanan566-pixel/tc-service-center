<?php

namespace App\Http\Controllers\Dealer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DealerInventory;
use App\Models\InventoryLog;
use Illuminate\Support\Facades\Auth;

class InventoryController extends Controller
{
    public function index()
    {
        $dealerId = Auth::user()->dealer->id;
        $inventory = DealerInventory::with('product')
            ->where('dealer_id', $dealerId)
            ->paginate(15);

        return view('dealer.inventory.index', compact('inventory'));
    }

    public function logs()
    {
        $dealerId = Auth::user()->dealer->id;
        $logs = InventoryLog::with('product')
            ->where('dealer_id', $dealerId)
            ->latest()
            ->paginate(15);

        return view('dealer.inventory.logs', compact('logs'));
    }
}
