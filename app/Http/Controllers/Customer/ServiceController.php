<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Device;
use App\Models\ServiceOrder;

class ServiceController extends Controller
{
    public function create()
    {
        return view('customer.service.book');
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:repair,warranty,desktop_assemble,cctv,used_laptop,laptop,desktop,printer',
            'brand' => 'nullable',
            'model' => 'nullable',
            'problem' => 'required',
            'delivery_type' => 'required|in:take_away,delivery',
            'delivery_mobile' => 'nullable|required_if:delivery_type,delivery',
            'delivery_location_url' => 'nullable|required_if:delivery_type,delivery',
            'delivery_address' => 'nullable|required_if:delivery_type,delivery',
        ]);

        $customer = Auth::guard('customer')->user();

        $photoPaths = [];
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $photoPaths[] = $photo->store('devices/damage', 'public');
            }
        }

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

        ServiceOrder::create([
            'tc_job_id'     => 'TC-' . date('Y') . '-' . strtoupper(substr(uniqid(), -4)),
            'order_type'    => 'online',
            'customer_id'   => $customer->id,
            'device_id'     => $device->id,
            'status'        => 'received',
            'fault_details' => $request->problem,
            'is_paid'       => false,
            'delivery_type' => $request->delivery_type,
            'delivery_mobile' => $request->delivery_type === 'delivery' ? $request->delivery_mobile : null,
            'delivery_location_url' => $request->delivery_type === 'delivery' ? $request->delivery_location_url : null,
            'delivery_address' => $request->delivery_type === 'delivery' ? $request->delivery_address : null,
        ]);

        return redirect()->route('customer.dashboard')->with('success', 'Service successfully booked! You can track it here.');
    }

    public function customBuild()
    {
        return view('customer.service.custom-build');
    }

    public function cctv()
    {
        return view('customer.service.cctv');
    }

    public function laptops()
    {
        $laptops = class_exists(\App\Models\UsedLaptop::class) ? \App\Models\UsedLaptop::latest()->get() : collect();
        return view('customer.shop.laptops', compact('laptops'));
    }
}
