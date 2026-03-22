<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AiDiagnosis;
use App\Models\SparePart;
use App\Models\ServiceOrder;
use App\Models\Customer;
use Illuminate\Http\Request;

class AIAssistantController extends Controller
{
    /**
     * AI Assistant Dashboard.
     */
    public function index()
    {
        $recentDiagnoses = AiDiagnosis::with(['customer', 'serviceOrder'])
            ->latest()
            ->take(10)
            ->get();

        $lowStockParts = SparePart::where('stock', '<', 5)
            ->where('is_active', true)
            ->orderBy('stock')
            ->get();

        return view('admin.ai.index', compact('recentDiagnoses', 'lowStockParts'));
    }

    /**
     * AI Technician Diagnosis — analyzes problem description and suggests fixes.
     * Uses a rule-based engine (can be extended to OpenAI API).
     */
    public function diagnose(Request $request)
    {
        $request->validate([
            'problem_description' => 'required|string|min:10|max:2000',
            'device_type'         => 'required|string',
            'customer_id'         => 'nullable|exists:customers,id',
            'service_order_id'    => 'nullable|exists:service_orders,id',
        ]);

        $problem     = strtolower($request->problem_description);
        $deviceType  = strtolower($request->device_type);

        // ─── Rule-based AI Engine ───
        $analysis = $this->runDiagnosisEngine($problem, $deviceType);

        // Find matching spare parts in inventory
        $suggestedPartIds = [];
        if (!empty($analysis['parts_keywords'])) {
            $matchedParts = SparePart::query()->where('is_active', true)
                ->where(function (\Illuminate\Database\Eloquent\Builder $q) use ($analysis) {
                    foreach ($analysis['parts_keywords'] as $kw) {
                        $q->orWhere('name', 'like', "%{$kw}%")
                          ->orWhere('category', 'like', "%{$kw}%");
                    }
                })
                ->get(['id', 'name', 'category', 'price', 'stock']);
            $suggestedPartIds = $matchedParts->pluck('id')->toArray();
        } else {
            $matchedParts = collect();
        }

        $record = AiDiagnosis::create([
            'customer_id'          => $request->customer_id,
            'service_order_id'     => $request->service_order_id,
            'problem_description'  => $request->problem_description,
            'ai_analysis'          => $analysis['analysis'],
            'suggested_issues'     => $analysis['issues'],
            'suggested_parts'      => $suggestedPartIds,
            'troubleshooting_steps'=> $analysis['steps'],
            'confidence_score'     => $analysis['confidence'],
            'diagnosis_type'       => 'text',
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success'       => true,
                'analysis'      => $analysis,
                'matched_parts' => $matchedParts,
                'diagnosis_id'  => $record->id,
            ]);
        }

        return back()
            ->with('success', 'AI Diagnosis complete!')
            ->with('ai_result', ['analysis' => $analysis, 'matched_parts' => $matchedParts]);
    }

    /**
     * Smart Inventory AI — demand prediction and restock suggestions.
     */
    public function inventoryForecast(Request $request)
    {
        $parts = SparePart::with(['productOrders' => function ($q) {
            $q->where('created_at', '>=', now()->subMonths(3));
        }])->get();

        $forecasts = $parts->map(function ($part) {
            $recent3MonthSales = $part->productOrders->sum('quantity');
            $avgMonthlySales   = $recent3MonthSales > 0 ? round($recent3MonthSales / 3, 1) : 0;
            $recommendedStock  = max(ceil($avgMonthlySales * 2), 5); // 2 months buffer, min 5
            $reorderAlert      = $part->stock < $recommendedStock;
            $reorderQuantity   = max(0, $recommendedStock - $part->stock);

            return [
                'id'                => $part->id,
                'name'              => $part->name,
                'category'          => $part->category,
                'current_stock'     => $part->stock,
                'avg_monthly_sales' => $avgMonthlySales,
                'recommended_stock' => $recommendedStock,
                'reorder_alert'     => $reorderAlert,
                'reorder_quantity'  => $reorderQuantity,
                'status'            => $part->stock <= 0 ? 'out_of_stock'
                                    : ($reorderAlert ? 'low_stock' : 'adequate'),
            ];
        })->sortByDesc('reorder_alert')->values();

        if ($request->ajax()) {
            return response()->json(['forecasts' => $forecasts]);
        }

        return view('admin.ai.inventory_forecast', compact('forecasts'));
    }

    // ─────────────────────────────────────────────
    // PRIVATE: Rule-Based Diagnosis Engine
    // ─────────────────────────────────────────────
    private function runDiagnosisEngine(string $problem, string $deviceType): array
    {
        $issues        = [];
        $steps         = [];
        $partsKeywords = [];
        $confidence    = 60;

        // ─── Screen Issues ───
        if (str_contains($problem, 'screen') || str_contains($problem, 'display') || str_contains($problem, 'flicker') || str_contains($problem, 'blank') || str_contains($problem, 'lines on screen')) {
            $issues[]       = 'Screen / Display Damage';
            $partsKeywords  = array_merge($partsKeywords, ['screen', 'lcd', 'display', 'panel']);
            $steps[] = '1. Connect an external monitor to verify if GPU is working.';
            $steps[] = '2. Check display cable connection inside chassis.';
            $steps[] = '3. Test with a known-good screen if available.';
            $steps[] = '4. If backlight issue — check inverter board / LED strip.';
            $confidence = 85;
        }

        // ─── Battery Issues ───
        if (str_contains($problem, 'battery') || str_contains($problem, 'not charging') || str_contains($problem, 'swollen') || str_contains($problem, 'drain') || str_contains($problem, 'shut down')) {
            $issues[]      = 'Battery Failure / Swelling';
            $partsKeywords = array_merge($partsKeywords, ['battery', 'charger', 'adapter']);
            $steps[] = '1. Check charger output voltage with multimeter.';
            $steps[] = '2. Inspect battery for physical swelling — REPLACE immediately if swollen.';
            $steps[] = '3. Check charging IC on motherboard.';
            $steps[] = '4. Run battery health diagnostic software.';
            $confidence = 88;
        }

        // ─── Motherboard / Power Issues ───
        if (str_contains($problem, 'not starting') || str_contains($problem, 'dead') || str_contains($problem, 'no power') || str_contains($problem, 'beep') || str_contains($problem, 'motherboard')) {
            $issues[]      = 'Motherboard / Power Circuit Failure';
            $partsKeywords = array_merge($partsKeywords, ['motherboard', 'capacitor', 'power supply']);
            $steps[] = '1. Check power button continuity with multimeter.';
            $steps[] = '2. Inspect capacitors on motherboard for bulging.';
            $steps[] = '3. Test with known-good power adapter / PSU.';
            $steps[] = '4. Check BIOS chip and reseat RAM.';
            $steps[] = '5. Attempt CMOS reset by removing battery for 30 seconds.';
            $confidence = 75;
        }

        // ─── RAM Issues ───
        if (str_contains($problem, 'ram') || str_contains($problem, 'memory') || str_contains($problem, 'blue screen') || str_contains($problem, 'bsod') || str_contains($problem, 'freezing') || str_contains($problem, 'slow')) {
            $issues[]      = 'RAM / Memory Issue';
            $partsKeywords = array_merge($partsKeywords, ['ram', 'memory', 'ddr']);
            $steps[] = '1. Reseat RAM modules in alternate slots.';
            $steps[] = '2. Run MemTest86 overnight.';
            $steps[] = '3. Test with a single RAM stick at a time.';
            $steps[] = '4. Check for RAM slot damage on motherboard.';
            $confidence = 82;
        }

        // ─── Storage Issues ───
        if (str_contains($problem, 'hdd') || str_contains($problem, 'ssd') || str_contains($problem, 'clicking') || str_contains($problem, 'storage') || str_contains($problem, 'boot') || str_contains($problem, 'data')) {
            $issues[]      = 'HDD / SSD Storage Failure';
            $partsKeywords = array_merge($partsKeywords, ['ssd', 'hdd', 'hard drive', 'nvme']);
            $steps[] = '1. Boot from USB and run disk health check (CrystalDiskInfo).';
            $steps[] = '2. Listen for clicking noises (HDD head crash indicator).';
            $steps[] = '3. Attempt data recovery via external USB enclosure.';
            $steps[] = '4. Replace failing drive with new SSD for better performance.';
            $confidence = 87;
        }

        // ─── Overheating ───
        if (str_contains($problem, 'hot') || str_contains($problem, 'overheat') || str_contains($problem, 'fan') || str_contains($problem, 'thermal') || str_contains($problem, 'shuts off')) {
            $issues[]      = 'Overheating / Cooling System Failure';
            $partsKeywords = array_merge($partsKeywords, ['thermal paste', 'fan', 'heatsink', 'cooler']);
            $steps[] = '1. Clean dust from fans and heatsink vents.';
            $steps[] = '2. Re-apply premium thermal paste on CPU/GPU.';
            $steps[] = '3. Test fan RPM in BIOS — replace fan if noisy or stopped.';
            $steps[] = '4. Check for bent/damaged heatsink fins.';
            $confidence = 90;
        }

        // ─── Networking Issues ───
        if (str_contains($problem, 'wifi') || str_contains($problem, 'network') || str_contains($problem, 'internet') || str_contains($problem, 'connection') || str_contains($problem, 'router')) {
            $issues[]      = 'Network / WiFi Adapter Issue';
            $partsKeywords = array_merge($partsKeywords, ['wifi adapter', 'network card', 'antenna', 'router']);
            $steps[] = '1. Update/reinstall network drivers.';
            $steps[] = '2. Reseat M.2 WiFi card or USB WiFi adapter.';
            $steps[] = '3. Check antenna cable connections inside chassis.';
            $steps[] = '4. Test with USB WiFi dongle to isolate issue.';
            $confidence = 80;
        }

        // ─── CCTV Issues ───
        if (str_contains($problem, 'camera') || str_contains($problem, 'cctv') || str_contains($problem, 'dvr') || str_contains($problem, 'nvr') || str_contains($problem, 'no recording')) {
            $issues[]      = 'CCTV / DVR / NVR Issue';
            $partsKeywords = array_merge($partsKeywords, ['camera', 'dvr', 'nvr', 'hdd', 'cable']);
            $steps[] = '1. Check power supply to each camera.';
            $steps[] = '2. Verify BNC/RJ45 cable integrity.';
            $steps[] = '3. Test DVR/NVR with monitor output directly.';
            $steps[] = '4. Check HDD health in DVR/NVR settings.';
            $steps[] = '5. Factory reset DVR/NVR if no video displayed.';
            $confidence = 78;
        }

        // Fallback
        if (empty($issues)) {
            $issues = ['General Hardware Issue'];
            $steps  = [
                '1. Perform visual inspection for physical damage.',
                '2. Check all cable connections.',
                '3. Run hardware diagnostic tool.',
                '4. Consult manufacturer service manual.',
            ];
            $confidence = 45;
        }

        $analysisText = "Based on the reported symptoms, the following issue(s) have been identified:\n"
            . implode(', ', $issues) . ".\n\n"
            . "Confidence Level: {$confidence}%\n\n"
            . "Recommended Troubleshooting:\n"
            . implode("\n", $steps);

        return [
            'issues'         => $issues,
            'steps'          => $steps,
            'analysis'       => $analysisText,
            'parts_keywords' => array_unique($partsKeywords),
            'confidence'     => $confidence,
        ];
    }
}
