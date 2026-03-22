<?php

namespace App\Http\Controllers\Dealer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Device;
use App\Models\ServiceOrder;

class ServiceController extends Controller
{
    public function index()
    {
        $dealer = Auth::user()->dealer;
        $orders = $dealer->serviceOrders()->with('device')->latest()->paginate(15);
        return view('dealer.services.index', compact('orders'));
    }

    public function create()
    {
        return view('dealer.services.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:repair,warranty,desktop_assemble,cctv,laptop,desktop,printer',
            'brand' => 'required|string',
            'model' => 'required|string',
            'problem' => 'required|string',
            'delivery_type' => 'required|in:take_away,delivery',
        ]);

        $dealer = Auth::user()->dealer;

        $photoPaths = [];
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $photoPaths[] = $photo->store('devices/damage', 'public');
            }
        }

        $device = Device::create([
            'dealer_id'     => $dealer->id,
            'type'          => $request->type,
            'brand'         => $request->brand,
            'model'         => $request->model,
            'serial_number' => $request->serial_number,
            'damage_photos' => $photoPaths,
        ]);

        ServiceOrder::create([
            'tc_job_id'     => 'DL-' . date('Y') . '-' . strtoupper(substr(uniqid(), -4)),
            'order_type'    => 'dealer',
            'dealer_id'     => $dealer->id,
            'device_id'     => $device->id,
            'status'        => 'received',
            'fault_details' => $request->problem,
            'is_paid'       => false,
            'delivery_type' => $request->delivery_type,
            'delivery_address' => $request->delivery_address ?? $dealer->address,
        ]);

        return redirect()->route('dealer.dashboard')->with('success', 'Service booking request sent to TC Center.');
    }

    public function show($id)
    {
        $dealer = Auth::user()->dealer;
        $order = $dealer->serviceOrders()->with(['device', 'technician', 'inspectionPhotos', 'warranties', 'invoices'])->findOrFail($id);
        return view('dealer.services.show', compact('order'));
    }
}
