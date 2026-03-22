<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Shipment;
use App\Models\DealerOrder;
use App\Models\StoreVisit;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class LogisticsController extends Controller
{
    public function index()
    {
        $shipments = Shipment::with('dealerOrder.dealer')->latest()->paginate(15);
        return view('admin.logistics.index', compact('shipments'));
    }

    public function createShipment(DealerOrder $order)
    {
        return view('admin.logistics.create_shipment', compact('order'));
    }

    public function storeShipment(Request $request, DealerOrder $order)
    {
        $request->validate([
            'method' => 'required|in:courier,bus_parcel',
            'courier_name' => 'required_if:method,courier',
            'tracking_number' => 'required_if:method,courier',
            'bus_name' => 'required_if:method,bus_parcel',
            'lr_number' => 'required_if:method,bus_parcel',
        ]);

        DB::transaction(function () use ($request, $order) {
            Shipment::create(array_merge($request->all(), [
                'dealer_order_id' => $order->id,
                'status' => 'dispatched',
                'dispatch_at' => now(),
            ]));

            $order->update(['status' => 'shipped']);
        });

        return redirect()->route('admin.orders.index')->with('success', 'Shipment created and order marked as shipped.');
    }

    public function visits()
    {
        $visits = StoreVisit::with(['dealer', 'assignedTo'])->latest()->paginate(15);
        return view('admin.logistics.visits', compact('visits'));
    }

    public function createVisit()
    {
        $dealers = \App\Models\Dealer::all();
        $technicians = User::where('role', 'technician')->get();
        return view('admin.logistics.create_visit', compact('dealers', 'technicians'));
    }

    public function storeVisit(Request $request)
    {
        $request->validate([
            'dealer_id' => 'required|exists:dealers,id',
            'assigned_to' => 'required|exists:users,id',
            'visit_date' => 'required|date',
            'purpose' => 'required|string|max:255',
        ]);

        StoreVisit::create($request->all());

        return redirect()->route('admin.logistics.visits')->with('success', 'Visit scheduled successfully.');
    }
}
