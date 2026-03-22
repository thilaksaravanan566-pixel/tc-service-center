<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\ServiceOrder;
use Illuminate\Http\Request;

class TrackingController extends Controller
{
    public function index()
    {
        return view('tracking.index'); // Show the search box for Job ID
    }

    public function show($job_id)
    {
        // Search for the TC Job ID
        $order = ServiceOrder::with(['device', 'technician', 'deliveryPartner'])
            ->where('tc_job_id', $job_id)
            ->firstOrFail();

        // This view will show the Luxury Status Stepper
        return view('tracking.show', compact('order'));
    }

    public function updateDeviceSpecs(Request $request, $job_id)
    {
        $order = ServiceOrder::where('tc_job_id', $job_id)->firstOrFail();
        $device = $order->device;

        // Ensure that updates only happen BEFORE technician starts service
        if (!in_array($order->status, ['received', 'assigned'])) {
            return back()->with('error', 'Cannot update specs after service has started.');
        }

        $request->validate([
            'processor' => 'nullable|string',
            'ram'       => 'nullable|string',
            'ssd'       => 'nullable|string',
            'hdd'       => 'nullable|string',
            'photos'    => 'nullable|array|max:10',
            'photos.*'  => 'image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

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

        return back()->with('success', 'Hardware specs and condition photos updated successfully.');
    }
}