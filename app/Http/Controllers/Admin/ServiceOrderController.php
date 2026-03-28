<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Device;
use App\Models\ServiceOrder;
use Illuminate\Http\Request;

use App\Models\DealerInventory;
use App\Models\InventoryLog;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use App\Models\Invoice;
use App\Models\User;
use App\Services\WhatsAppService;
use App\Services\BarcodeService;


class ServiceOrderController extends Controller
{
    public function index(Request $request)
    {
        $query = ServiceOrder::query()->with(['device.customer', 'dealer', 'technician'])->latest();

        // Filters
        if ($request->filled('type')) {
            $query->where('order_type', $request->type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(fn(\Illuminate\Database\Eloquent\Builder $q) => 
                $q->where('tc_job_id', 'LIKE', "%{$search}%")
                  ->orWhereHas('device.customer', fn($cq) => 
                      $cq->where('name', 'LIKE', "%{$search}%")
                        ->orWhere('mobile', 'LIKE', "%{$search}%")
                  )
                  ->orWhereHas('device', fn($dq) => 
                      $dq->where('brand', 'LIKE', "%{$search}%")
                        ->orWhere('model', 'LIKE', "%{$search}%")
                  )
            );
        }

        $orders = $query->paginate(15)->withQueryString();

        // Counters
        $counts = [
            'all' => ServiceOrder::count(),
            'dealer' => ServiceOrder::where('order_type', 'dealer')->count(),
            'online' => ServiceOrder::where('order_type', 'online')->count(),
            'walkin' => ServiceOrder::where('order_type', 'walkin')->count(),
        ];

        if ($request->ajax()) {
            return view('admin.services.partials.order_table', compact('orders'))->render();
        }

        return view('admin.services.index', compact('orders', 'counts'));
    }

    public function create()
    {
        return view('admin.services.create');
    }

    /**
     * Display the specific details of a TC Service Job.
     * This fixes the "Route [admin.services.view] not defined" error 
     * when combined with the route in web.php.
     */
    public function show($id)
    {
        $order = ServiceOrder::with(['device.customer', 'technician', 'deliveryPartner'])->findOrFail($id);
        $technicians = User::where('role', 'technician')->get();
        $deliveryPartners = User::where('role', 'delivery_partner')->orWhere('role', 'delivery')->get();
        return view('admin.services.show', compact('order', 'technicians', 'deliveryPartners'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'    => 'required|string|max:255',
            'mobile'  => 'required|string|max:15',
            'type'    => 'required|in:laptop,desktop,printer,networking,cctv,router,switch,access_point,camera,nvr,dvr,monitor',
            'brand'   => 'required',
            'model'   => 'required',
            'problem' => 'required',
        ]);

        /** @var Customer $customer */
        $customer = Customer::firstOrCreate(
            ['mobile' => $request->mobile],
            ['name'   => $request->name, 'email' => $request->email]
        );

        $photoPaths = [];
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $photoPaths[] = $photo->store('devices/damage', 'public');
            }
        }

        /** @var Device $device */
        $device = Device::create([
            'customer_id'   => $customer->id,
            'type'          => $request->type,
            'brand'         => $request->brand,
            'model'         => $request->model,
            'serial_number' => $request->serial_number,
            'processor'     => $request->processor,
            'ram'           => $request->ram,
            'ssd'           => $request->ssd,
            'hdd'           => $request->hdd,
            'ram_old'       => $request->ram_old,
            'storage_old'   => $request->storage_old,
            'damage_photos' => $photoPaths,
        ]);

        // Generate tracked, sequential Job ID (TC-2026-000001)
        /** @var BarcodeService $barcodeService */
        $barcodeService = app(BarcodeService::class);
        $jobId = $barcodeService->generateJobId();

        $order = ServiceOrder::create([
            'tc_job_id'     => $jobId,
            'order_type'    => $request->order_type ?? 'walkin',
            'customer_id'   => $customer->id,
            'device_id'     => $device->id,
            'status'        => 'received',
            'priority'      => $request->priority ?? 'medium',
            'fault_details' => $request->problem,
            'is_paid'       => false,
            'delivery_type' => 'take_away',
        ]);

        // ─── WhatsApp: Notify customer of job receipt ─────────────
        if (!empty($customer->mobile)) {
            /** @var WhatsAppService $wa */
            $wa = app(WhatsAppService::class);
            $wa->notifyJobReceived($customer->mobile, $jobId, $customer->name);
        }

        return redirect()->route('admin.services.index')
            ->with('success', "Job Registered! ID: {$jobId}");
    }

    public function update(Request $request, ServiceOrder $service)
    {
        $request->validate([
            'status'           => 'required|string',
            'priority'         => 'nullable|in:low,medium,high',
            'estimated_cost'   => 'nullable|numeric',
            'engineer_comment' => 'nullable|string',
            'is_paid'          => 'nullable|boolean',
        ]);

        $previousStatus = $service->status;

        $service->update([
            'status'           => $request->status,
            'priority'         => $request->priority ?? $service->priority,
            'estimated_cost'   => $request->estimated_cost,
            'engineer_comment' => $request->engineer_comment,
            'is_paid'          => $request->is_paid ?? false,
        ]);

        $service->refresh();
        $customer = $service->device?->customer ?? $service->customer;
        /** @var WhatsAppService $wa */
        $wa = app(WhatsAppService::class);

        // ─── WhatsApp: Job ready for pickup ──────────────────────
        if ($service->status === 'ready' && $previousStatus !== 'ready' && $customer?->mobile) {
            $wa->sendMessage(
                $customer->mobile,
                "Dear {$customer->name}, your device (Job: {$service->tc_job_id}) is ready for pickup at Thambu Computers! For delivery queries, call us."
            );
        }

        // ─── WhatsApp: Job completed & invoice generated ─────────
        if ($service->status === 'completed' && $service->is_paid) {
            $invoice = Invoice::firstOrCreate(
                ['service_order_id' => $service->id],
                [
                    'invoice_number' => 'INV-' . date('Y') . '-' . str_pad($service->id, 5, '0', STR_PAD_LEFT),
                    'customer_id'    => $service->customer_id,
                    'amount'         => $service->estimated_cost ?? 0,
                    'status'         => 'paid',
                ]
            );

            if ($customer?->mobile) {
                $amount   = number_format((float) ($service->estimated_cost ?? 0), 2);
                $invoiceUrl = route('admin.invoices.show', $invoice->id);
                $wa->sendInvoiceLink($customer->mobile, $customer->name, $invoice->invoice_number, $invoiceUrl);
            }
        }

        // ─── WhatsApp: Out for delivery ──────────────────────────
        if ($service->status === 'out_for_delivery' && $previousStatus !== 'out_for_delivery' && $customer?->mobile) {
            $wa->sendMessage(
                $customer->mobile,
                "Dear {$customer->name}, your device (Job: {$service->tc_job_id}) is out for delivery today! Track at: " . route('tracking.show', $service->tc_job_id)
            );
        }

        return redirect()->back()->with('success', 'Service details updated successfully.');
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
            // Deduct stock from Dealer Inventory (if dealer job) or Global Products
            if ($dealerId) {
                $inventory = DealerInventory::where('dealer_id', $dealerId)
                    ->where('product_id', $product->id)
                    ->first();

                if (!$inventory || $inventory->stock_quantity < $request->quantity) {
                    return back()->with('error', 'Insufficient stock in Dealer Inventory for this part.');
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
                    'description' => 'Part used in Service Job #' . $service->tc_job_id,
                ]);
            } else {
                if ($product->stock_quantity < $request->quantity) {
                    return back()->with('error', 'Insufficient global stock for this part.');
                }

                $previousStock = $product->stock_quantity;
                $product->decrement('stock_quantity', $request->quantity);

                InventoryLog::create([
                    'dealer_id' => null, // Global stock log
                    'product_id' => $product->id,
                    'type' => 'OUT',
                    'quantity' => $request->quantity,
                    'reference_type' => 'service_usage_global',
                    'reference_id' => $service->id,
                    'previous_stock' => $previousStock,
                    'new_stock' => $product->stock_quantity,
                    'description' => 'Part used in General Service Job #' . $service->tc_job_id,
                ]);
            }

            // Record in ServiceOrder parts_used
            $parts = $service->parts_used ?? [];
            $parts[] = [
                'id' => uniqid(),
                'product_id' => $product->id,
                'name' => $product->name,
                'quantity' => $request->quantity,
                'price' => $dealerId ? $product->dealer_price : $product->selling_price,
            ];
            $service->update(['parts_used' => $parts]);

            return redirect()->back()->with('success', 'Part consumption logged successfully.');
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

        if ($foundIndex === null) {
            return back()->with('error', 'Part entry not found.');
        }

        return DB::transaction(function () use ($service, $parts, $foundIndex, $partToReturn) {
            // Recalculate stock
            $product = Product::find($partToReturn['product_id']);
            $dealerId = $service->dealer_id;

            if ($dealerId) {
                $inventory = DealerInventory::where('dealer_id', $dealerId)
                    ->where('product_id', $product->id)
                    ->first();
                if ($inventory) {
                    $inventory->increment('stock_quantity', $partToReturn['quantity']);
                }
            } else {
                $product->increment('stock_quantity', $partToReturn['quantity']);
            }

            // Remove from array and update
            unset($parts[$foundIndex]);
            $service->update(['parts_used' => array_values($parts)]);

            return redirect()->back()->with('success', 'Part removed and stock returned successfully.');
        });
    }

    public function assignTechnician(Request $request, ServiceOrder $service)
    {
        $request->validate([
            'technician_id' => 'nullable|exists:users,id',
        ]);

        $service->update([
            'technician_id' => $request->technician_id,
        ]);

        return redirect()->back()->with('success', 'Technician assigned successfully.');
    }

    public function assignDelivery(Request $request, ServiceOrder $service)
    {
        $request->validate([
            'delivery_partner_id' => 'nullable|exists:users,id',
        ]);

        $service->update([
            'delivery_partner_id' => $request->delivery_partner_id,
        ]);

        return redirect()->back()->with('success', 'Delivery Partner assigned successfully.');
    }

    /**
     * Update device specs and upload damage/inspection photos.
     */
    public function updateDeviceSpecs(Request $request, $id)
    {
        $request->validate([
            'processor' => 'nullable|string',
            'ram'       => 'nullable|string',
            'ssd'       => 'nullable|string',
            'hdd'       => 'nullable|string',
            'photos'    => 'nullable|array|max:10',
            'photos.*'  => 'image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        $order = ServiceOrder::findOrFail($id);
        $device = $order->device;

        if (!$device) {
            return back()->with('error', 'No device linked to this service order.');
        }

        $existingPhotos = $device->damage_photos ?? [];
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $existingPhotos[] = $photo->store('devices/damage', 'public');
            }
        }

        $device->update([
            'processor' => $request->processor ?? $device->processor,
            'ram'       => $request->ram ?? $device->ram,
            'ssd'       => $request->ssd ?? $device->ssd,
            'hdd'       => $request->hdd ?? $device->hdd,
            'damage_photos' => $existingPhotos,
        ]);

        return back()->with('success', 'Device specs and photos updated successfully.');
    }

    /**
     * Delete a service order.
     */
    public function destroy(ServiceOrder $service)
    {
        $service->delete();
        return redirect()->route('admin.services.index')->with('success', 'Service order deleted.');
    }
}