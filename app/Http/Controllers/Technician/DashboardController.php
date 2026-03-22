<?php

namespace App\Http\Controllers\Technician;

use App\Http\Controllers\Controller;
use App\Models\ServiceOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\DealerInventory;
use App\Models\InventoryLog;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $technicianId = Auth::id();
        $orders = ServiceOrder::with(['device.customer'])
            ->whereIn('status', ['received', 'diagnosing', 'repairing'])
            ->latest()
            ->get();

        $visits = \App\Models\StoreVisit::with('dealer')
            ->where('assigned_to', $technicianId)
            ->whereIn('status', ['pending', 'on_site'])
            ->latest()
            ->get();

        return view('technician.dashboard', compact('orders', 'visits'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:' . implode(',', [
                ServiceOrder::STATUS_RECEIVED,
                ServiceOrder::STATUS_DIAGNOSING,
                ServiceOrder::STATUS_REPAIRING,
                ServiceOrder::STATUS_PENDING,
                ServiceOrder::STATUS_READY,
                ServiceOrder::STATUS_PACKING,
                ServiceOrder::STATUS_SHIPPING,
                ServiceOrder::STATUS_OUT_FOR_DELIVERY,
                ServiceOrder::STATUS_DELIVERED,
                ServiceOrder::STATUS_COMPLETED,
            ]),
            'engineer_comment' => 'nullable|string'
        ]);

        $order = ServiceOrder::findOrFail($id);
        
        $order->status = $request->status;
        if ($request->filled('engineer_comment')) {
            $order->engineer_comment = $request->engineer_comment;
        }
        
        // Auto-assign to the technician if not assigned yet and they start working on it
        if (!$order->technician_id && in_array($request->status, [ServiceOrder::STATUS_DIAGNOSING, ServiceOrder::STATUS_REPAIRING])) {
            $order->technician_id = Auth::id();
        }

        $order->save();

        return back()->with('success', "Service Job #{$order->tc_job_id} updated successfully!");
    }

    public function usePart(Request $request, ServiceOrder $service)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);
        $dealerId = $service->dealer_id;

        return DB::transaction(function () use ($service, $product, $dealerId, $request) {
            if ($dealerId) {
                $inventory = DealerInventory::where('dealer_id', $dealerId)->where('product_id', $product->id)->first();
                if (!$inventory || $inventory->stock_quantity < $request->quantity) {
                    return back()->with('error', 'Insufficient stock in Dealer Inventory.');
                }
                $previousStock = $inventory->stock_quantity;
                $inventory->decrement('stock_quantity', $request->quantity);
                InventoryLog::create([
                    'dealer_id' => $dealerId,
                    'product_id' => $product->id,
                    'type' => 'OUT',
                    'quantity' => $request->quantity,
                    'reference_type' => 'service_usage',
                    'reference_id' => $service->id,
                    'previous_stock' => $previousStock,
                    'new_stock' => $inventory->stock_quantity,
                    'description' => 'Part logged by Tech in Job #' . $service->tc_job_id,
                ]);
            } else {
                if ($product->stock_quantity < $request->quantity) {
                    return back()->with('error', 'Insufficient global stock.');
                }
                $previousStock = $product->stock_quantity;
                $product->decrement('stock_quantity', $request->quantity);
                InventoryLog::create([
                    'dealer_id' => null,
                    'product_id' => $product->id,
                    'type' => 'OUT',
                    'quantity' => $request->quantity,
                    'reference_type' => 'service_usage_global',
                    'reference_id' => $service->id,
                    'previous_stock' => $previousStock,
                    'new_stock' => $product->stock_quantity,
                    'description' => 'Part logged by Tech in Global Job #' . $service->tc_job_id,
                ]);
            }

            $parts = $service->parts_used ?? [];
            $parts[] = [
                'id' => uniqid(),
                'product_id' => $product->id,
                'name' => $product->name,
                'quantity' => $request->quantity,
                'price' => $dealerId ? $product->dealer_price : $product->selling_price,
            ];
            $service->update(['parts_used' => $parts]);

            return redirect()->back()->with('success', 'Part consumption logged.');
        });
    }

    public function removePart(Request $request, ServiceOrder $service, $partId)
    {
        $parts = $service->parts_used ?? [];
        $foundIndex = null;
        $partToReturn = null;
        foreach ($parts as $index => $part) {
            if ($part['id'] === $partId) {
                $foundIndex = $index;
                $partToReturn = $part;
                break;
            }
        }
        if ($foundIndex === null) return back()->with('error', 'Part not found.');

        return DB::transaction(function () use ($service, $parts, $foundIndex, $partToReturn) {
            $product = Product::find($partToReturn['product_id']);
            if ($service->dealer_id) {
                $inventory = DealerInventory::where('dealer_id', $service->dealer_id)->where('product_id', $product->id)->first();
                if ($inventory) $inventory->increment('stock_quantity', $partToReturn['quantity']);
            } else {
                $product->increment('stock_quantity', $partToReturn['quantity']);
            }
            unset($parts[$foundIndex]);
            $service->update(['parts_used' => array_values($parts)]);
            return redirect()->back()->with('success', 'Part removed.');
        });
    }

    public function updateVisit(Request $request, $id)
    {
        $visit = \App\Models\StoreVisit::findOrFail($id);
        $request->validate(['status' => 'required|in:pending,on_site,completed,cancelled']);
        $visit->update($request->all());
        return back()->with('success', 'Store visit status updated.');
    }
}
