<?php

namespace App\Http\Controllers\Dealer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ServiceOrder;
use App\Models\Invoice;
use App\Models\DealerInventory;
use App\Models\StoreVisit;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $dealer = $user->dealer;

        if (!$dealer) {
            return redirect('/')->with('error', 'Dealer profile not found.');
        }

        $stats = [
            'total_orders' => $dealer->serviceOrders()->count(),
            'pending_orders' => $dealer->serviceOrders()->whereNotIn('status', [ServiceOrder::STATUS_DELIVERED, ServiceOrder::STATUS_COMPLETED])->count(),
            'total_revenue' => Invoice::where('dealer_id', $dealer->id)
                ->where('payment_status', 'paid')
                ->sum('total'),
            'recent_orders' => $dealer->serviceOrders()->with('device')->latest()->take(5)->get(),
            'inventory_sku_count' => DealerInventory::where('dealer_id', $dealer->id)->count(),
            'pending_visits_count' => StoreVisit::where('dealer_id', $dealer->id)->whereIn('status', ['pending', 'on_site'])->count(),
        ];

        return view('dealer.dashboard', compact('dealer', 'stats'));
    }
}
