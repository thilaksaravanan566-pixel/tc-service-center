<?php

namespace App\Http\Controllers\Technician;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ServiceOrder;
use App\Models\InspectionPhoto;

class InspectionPhotoController extends Controller
{
    /**
     * Upload inspection photos for a service order.
     */
    public function store(Request $request, $serviceOrderId)
    {
        $request->validate([
            'photos'            => 'required|array|min:1',
            'photos.*'          => 'required|image|mimes:jpeg,png,jpg,webp|max:8192',
            'photo_types'       => 'required|array',
            'photo_types.*'     => 'required|in:exterior,ram,storage,processor,motherboard,other',
            'labels'            => 'nullable|array',
            'labels.*'          => 'nullable|string|max:100',
            'notes'             => 'nullable|array',
            'notes.*'           => 'nullable|string|max:500',
            'inspection_stage'  => 'required|in:pre_repair,post_repair',
        ]);

        $order = ServiceOrder::findOrFail($serviceOrderId);

        $technicianId = Auth::user()->id;

        foreach ($request->file('photos') as $index => $photo) {
            $path = $photo->store('inspection_photos/' . $order->tc_job_id, 'public');

            InspectionPhoto::create([
                'service_order_id' => $order->id,
                'uploaded_by'      => $technicianId,
                'photo_type'       => $request->photo_types[$index] ?? 'other',
                'photo_path'       => $path,
                'label'            => $request->labels[$index] ?? null,
                'notes'            => $request->notes[$index] ?? null,
                'inspection_stage' => $request->inspection_stage,
            ]);
        }

        // Notify customer about inspection upload
        if ($order->customer_id) {
            \App\Models\CustomerNotification::create([
                'customer_id' => $order->customer_id,
                'type'        => 'service_update',
                'title'       => '📸 Inspection Photos Uploaded',
                'message'     => "Our technician has uploaded inspection photos for your device (Job #{$order->tc_job_id}). You can view them in your service tracking.",
                'action_url'  => route('tracking.show', $order->tc_job_id),
                'icon'        => '📸',
            ]);
        }

        return back()->with('success', count($request->file('photos')) . ' inspection photo(s) uploaded successfully.');
    }

    /**
     * Delete a single inspection photo.
     */
    public function destroy($id)
    {
        $photo = InspectionPhoto::findOrFail($id);
        
        if (\Illuminate\Support\Facades\Storage::disk('public')->exists($photo->photo_path)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($photo->photo_path);
        }
        
        $photo->delete();

        return back()->with('success', 'Photo removed.');
    }
}
