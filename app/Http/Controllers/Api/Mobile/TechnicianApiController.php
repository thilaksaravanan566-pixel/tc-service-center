<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\ServiceOrder;
use App\Models\AiDiagnosis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class TechnicianApiController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        /** @var \App\Models\User|null $user */
        $user = User::query()->where('email', $request->email)
            ->where('role', 'technician')
            ->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials or not a technician.'], 401);
        }

        $token = $user->createToken('technician-mobile')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user'  => $user->only(['id', 'name', 'email', 'role']),
        ]);
    }

    public function assignedJobs(Request $request)
    {
        $jobs = ServiceOrder::with(['customer', 'device'])
            ->where('technician_id', $request->user()->id)
            ->whereNotIn('status', ['completed', 'cancelled'])
            ->latest()->get();

        return response()->json($jobs);
    }

    public function jobDetails(Request $request, $id)
    {
        $job = ServiceOrder::with(['customer', 'device', 'billings'])
            ->where('technician_id', $request->user()->id)
            ->findOrFail($id);

        return response()->json($job);
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:diagnosing,in_progress,waiting_for_parts,completed,cannot_repair',
        ]);

        $job = ServiceOrder::where('technician_id', $request->user()->id)->findOrFail($id);
        $job->update([
            'status'     => $request->status,
            'updated_at' => now(),
        ]);

        return response()->json(['message' => 'Job status updated.', 'new_status' => $request->status]);
    }

    public function uploadPhotos(Request $request, $id)
    {
        $request->validate([
            'photos'   => 'required|array',
            'photos.*' => 'file|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        $job    = ServiceOrder::where('technician_id', $request->user()->id)->findOrFail($id);
        $device = $job->device;
        $paths  = [];

        foreach ($request->file('photos') as $photo) {
            $paths[] = $photo->store("damage-photos/{$job->id}", 'public');
        }

        $existing = $device->damage_photos ?? [];
        $device->update(['damage_photos' => array_merge($existing, $paths)]);

        return response()->json(['message' => 'Photos uploaded.', 'paths' => $paths]);
    }

    public function addNotes(Request $request, $id)
    {
        $request->validate(['notes' => 'required|string|min:5']);

        $job = ServiceOrder::where('technician_id', $request->user()->id)->findOrFail($id);
        $job->update(['engineer_comment' => $request->notes]);

        return response()->json(['message' => 'Notes saved.']);
    }

    public function getParts(Request $request)
    {
        // Simple search for parts to assign to a job
        $query = \App\Models\SparePart::where('is_active', true)->where('stock', '>', 0);
        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('sku', 'like', '%' . $request->search . '%');
        }
        return response()->json($query->take(20)->get(['id', 'name', 'price', 'stock', 'sku']));
    }

    public function addPart(Request $request, $id)
    {
        $request->validate([
            'part_id' => 'required|exists:spare_parts,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $job = ServiceOrder::where('technician_id', $request->user()->id)->findOrFail($id);
        $part = \App\Models\SparePart::findOrFail($request->part_id);

        if ($part->stock < $request->quantity) {
            return response()->json(['message' => 'Insufficient stock.'], 422);
        }

        // Deduct stock
        $part->decrement('stock', $request->quantity);

        // Record in job
        $partsUsed = $job->parts_used ?? [];
        $partsUsed[] = [
            'part_id' => $part->id,
            'name' => $part->name,
            'quantity' => $request->quantity,
            'price' => $part->price,
            'total' => $part->price * $request->quantity,
            'added_at' => now()->toDateTimeString(),
        ];

        // Update total estimated cost of the repair implicitly
        $job->update([
            'parts_used' => $partsUsed,
            'estimated_cost' => $job->estimated_cost + ($part->price * $request->quantity)
        ]);

        return response()->json(['message' => 'Part added to job successfully.', 'job' => $job]);
    }

    public function aiDiagnose(Request $request)
    {
        $request->validate([
            'problem_description' => 'required|string|min:10',
            'device_type'         => 'required|string',
            'job_id'              => 'nullable|exists:service_orders,id'
        ]);

        $result = $this->runDiagnosis($request->problem_description, $request->device_type);

        $customer_id = null;
        if ($request->job_id) {
            $job = ServiceOrder::find($request->job_id);
            $customer_id = $job->customer_id;
        }

        AiDiagnosis::create([
            'service_order_id'    => $request->job_id,
            'customer_id'         => $customer_id,
            'problem_description' => $request->problem_description,
            'ai_analysis'         => $result['analysis'],
            'suggested_issues'    => $result['issues'],
            'troubleshooting_steps' => $result['steps'],
            'confidence_score'    => $result['confidence'],
            'diagnosis_type'      => 'text',
        ]);

        return response()->json(['diagnosis' => $result]);
    }

    private function runDiagnosis(string $problem, string $deviceType): array
    {
        $problem   = strtolower($problem);
        $issues    = [];
        $steps     = [];
        $confidence = 60;

        if (str_contains($problem, 'screen') || str_contains($problem, 'display') || str_contains($problem, 'blank')) {
            $issues[]   = 'Screen/Display Issue';
            $steps[]    = 'Check external monitor connection';
            $steps[]    = 'Inspect display cable inside chassis';
            $confidence = 85;
        }
        if (str_contains($problem, 'battery') || str_contains($problem, 'charging') || str_contains($problem, 'swollen')) {
            $issues[]   = 'Battery Problem';
            $steps[]    = 'Check charger output voltage';
            $steps[]    = 'Inspect battery for physical swelling';
            $confidence = 88;
        }
        if (str_contains($problem, 'slow') || str_contains($problem, 'freeze') || str_contains($problem, 'lag')) {
            $issues[]   = 'Performance Issue (RAM/Storage)';
            $steps[]    = 'Run memory diagnostic';
            $steps[]    = 'Check disk health with CrystalDiskInfo';
            $confidence = 80;
        }
        if (str_contains($problem, 'wifi') || str_contains($problem, 'internet') || str_contains($problem, 'network')) {
            $issues[]   = 'Network/WiFi Issue';
            $steps[]    = 'Update network drivers';
            $steps[]    = 'Reseat WiFi card';
            $confidence = 78;
        }
        if (empty($issues)) {
            $issues = ['General Hardware Issue'];
            $steps  = ['Perform visual inspection', 'Check all connections', 'Run diagnostic tools'];
            $confidence = 45;
        }

        $analysisText = 'AI Identified: ' . implode(', ', $issues) . ". Confidence: {$confidence}%";

        return ['issues' => $issues, 'steps' => $steps, 'analysis' => $analysisText, 'confidence' => $confidence];
    }
}
