<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TechPlayer;
use App\Models\TechScore;
use App\Models\TechBadge;
use Illuminate\Support\Facades\Auth;

class LearningController extends Controller
{
    public function index()
    {
        return view('customer.learning.index');
    }

    public function game()
    {
        return view('customer.learning.game');
    }

    // --- NEW TECH LAB METHODS ---

    public function labDashboard()
    {
        $customer = Auth::guard('customer')->user();
        
        // Ensure player profile exists
        $player = TechPlayer::firstOrCreate(
            ['customer_id' => $customer->id],
            ['xp' => 0, 'level' => 1]
        );

        $recentScores = TechScore::where('customer_id', $customer->id)->latest()->take(5)->get();
        $badges = $customer->badges()->get() ?? collect(); 

        return view('customer.learning.lab_dashboard', compact('player', 'recentScores', 'badges'));
    }

    public function builder3d()
    {
        return view('customer.learning.builder3d');
    }

    public function repairSim()
    {
        return view('customer.learning.repair_sim');
    }

    public function troubleshoot()
    {
        return view('customer.learning.troubleshoot');
    }

    public function virusSmash()
    {
        return view('customer.learning.virus_smash');
    }

    public function repairTycoon()
    {
        $customer = Auth::guard('customer')->user();
        $player = TechPlayer::firstOrCreate(
            ['customer_id' => $customer->id],
            ['xp' => 0, 'level' => 1, 'coins' => 500]
        );
        return view('customer.learning.repair_tycoon', compact('player'));
    }

    public function saveTycoon(Request $request)
    {
        $request->validate([
            'coins' => 'required|integer',
            'tycoon_state' => 'required|array',
        ]);

        $customer = Auth::guard('customer')->user();
        $player = TechPlayer::where('customer_id', $customer->id)->first();
        
        $player->update([
            'coins' => $request->coins,
            'tycoon_state' => $request->tycoon_state,
        ]);

        return response()->json(['success' => true]);
    }

    public function deviceScanner()
    {
        return view('customer.learning.byte_scanner');
    }

    public function analyzeDevice(Request $request)
    {
        $validated = $request->validate([
            'brand' => 'required|string',
            'model' => 'required|string',
            'ram' => 'required|integer',
            'storage' => 'required|string',
            'problem' => 'required|string',
        ]);

        $analysis = [];
        $recommendations = [];
        $costs = [];
        $speech = "Analysis complete. ";

        // AI Logic Simulation
        if ($validated['ram'] < 16) {
            $analysis[] = "Memory bottleneck detected. Current {$validated['ram']}GB RAM is insufficient for modern multitasking.";
            $recommendations[] = [
                'type' => 'RAM Upgrade',
                'benefit' => 'Multi-tasking performance +40%',
                'icon' => '⚡'
            ];
            $costs[] = ['item' => 'RAM Upgrade (8GB)', 'price' => '2200'];
            $speech .= "Your RAM is currently low. Upgrading to 16GB will make your system much faster. ";
        }

        if (stripos($validated['storage'], 'HDD') !== false) {
            $analysis[] = "Mechanical storage drive detected. System I/O operations are limited by physical disk speed.";
            $recommendations[] = [
                'type' => 'SSD Migration',
                'benefit' => 'Boot speed +500%',
                'icon' => '🚀'
            ];
            $costs[] = ['item' => '512GB NVMe SSD', 'price' => '3800'];
            $speech .= "You are still using a hard disk. Switching to an SSD is the best way to speed up your laptop. ";
        }

        if (stripos($validated['problem'], 'heat') !== false || stripos($validated['problem'], 'fan') !== false || stripos($validated['problem'], 'slow') !== false) {
            $analysis[] = "Thermal throttling likely. Cumulative dust buildup or dried thermal compound identified.";
            $recommendations[] = [
                'type' => 'Deep Thermal Service',
                'benefit' => 'Operating Temp -15°C',
                'icon' => '❄️'
            ];
            $costs[] = ['item' => 'General Service', 'price' => '1200'];
            $speech .= "I recommend a professional thermal service to fix the overheating issues. ";
        }

        if (empty($analysis)) {
            $analysis[] = "Hardware specifications look optimal. Issue likely software-related or specific component failure.";
            $speech .= "Your hardware seems fine. A technician should check the specific components. ";
        }

        $speech .= "Would you like to book a service appointment now?";

        return response()->json([
            'analysis' => $analysis,
            'recommendations' => $recommendations,
            'costs' => $costs,
            'speech' => $speech
        ]);
    }

    public function saveScore(Request $request)
    {
        $request->validate([
            'game_type' => 'required|string',
            'score' => 'required|integer',
            'time_seconds' => 'nullable|integer',
        ]);

        $customer = Auth::guard('customer')->user();

        TechScore::create([
            'customer_id' => $customer->id,
            'game_type' => $request->game_type,
            'score' => $request->score,
            'time_seconds' => $request->time_seconds,
        ]);

        // Update player XP safely
        /** @var TechPlayer $player */
        $player = TechPlayer::firstOrCreate(
            ['customer_id' => $customer->id],
            ['xp' => 0, 'level' => 1, 'games_played' => 0]
        );
        
        $xpGained = floor($request->score / 10);
        $player->increment('xp', $xpGained);
        $player->increment('games_played');

        // Check for level up
        $newLevel = floor(sqrt($player->xp / 100)) + 1;
        if ($newLevel > $player->level) {
            $player->level = $newLevel;
            $player->save();
        }

        return response()->json([
            'success' => true, 
            'xp_gained' => $xpGained,
            'current_level' => $player->level
        ]);
    }
}
