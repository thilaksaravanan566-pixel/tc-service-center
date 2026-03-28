// app/Http/Controllers/Api/TrackingController.php
public function updateStatus(UpdateStatusRequest $request, $id) {
    $order = ServiceOrder::findOrFail($id); [cite: 4]
    
    // Statuses: 'received', 'packing', 'shipping', 'out_for_delivery', 'delivered'
    $order->update(['status' => $request->status]);

    return response()->json([
        'message' => 'TC Status Updated Successfully',
        'current_status' => $order->status
    ]);
}