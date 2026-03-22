<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Device;
use Illuminate\Http\Request;

class DeviceController extends Controller
{
    public function index()
    {
        $devices = Device::with('customer')->latest()->paginate(10);
        return view('admin.devices.index', compact('devices'));
    }

    public function show(Device $device)
    {
        return view('admin.devices.show', compact('device'));
    }

    public function edit(Device $device)
    {
        return view('admin.devices.edit', compact('device'));
    }

    public function update(Request $request, Device $device)
    {
        $device->update($request->only(['type', 'brand', 'model', 'ram', 'storage', 'processor', 'serial_number']));
        return redirect()->route('admin.devices.index')->with('success', 'Device specs updated.');
    }
}